<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_admin_maintenance {
    public static function delete_rating_item($item_id) {
        $item_id = (array)$item_id;

        $sql = "DELETE i, ib, im FROM ".gdrts_db()->items." i 
                INNER JOIN ".gdrts_db()->items_basic." ib ON ib.item_id = i.item_id 
                LEFT JOIN ".gdrts_db()->itemmeta." im ON im.item_id = i.item_id 
                WHERE i.item_id in (".join(', ', $item_id).")";
        gdrts_db()->query($sql);

        $sql = "DELETE l, lm FROM ".gdrts_db()->logs." l 
                LEFT JOIN ".gdrts_db()->logmeta." lm ON lm.log_id = l.log_id
                WHERE l.item_id in (".join(', ', $item_id).")";
        gdrts_db()->query($sql);
    }

    public static function clear_rating_item_method($item_id, $method = '', $series = '') {
        $item_id = (array)$item_id;

        $sql_items = "DELETE b FROM ".gdrts_db()->items_basic." b WHERE b.item_id in (".join(', ', $item_id).")";
        $sql_logs = "DELETE l FROM ".gdrts_db()->logs." l WHERE l.item_id in (".join(', ', $item_id).")";

        if ($method != '') {
            $sql_items.= " AND b.method = '".$method."'";
            $sql_logs.= " AND l.method = '".$method."'";

            if (!empty($series)) {
                $sql_items.= " AND b.series = '".$series."'";
                $sql_logs.= " AND l.series = '".$series."'";
            }
        }

        gdrts_db()->query($sql_items);
        gdrts_db()->query($sql_logs);
    }

    public static function clear_rating_item_method_limited($item_id, $method = '', $series = '') {
        $item_id = (array)$item_id;

        $sql_items = "DELETE b FROM ".gdrts_db()->items_basic." b WHERE b.item_id in (".join(', ', $item_id).")";

        if ($method != '') {
            $sql_items.= " AND b.method = '".$method."'";

            if (!empty($series)) {
                $sql_items.= " AND b.series = '".$series."'";
            }
        }

        gdrts_db()->query($sql_items);
    }

    public static function delete_votes_from_log($ids) {
        $ids = (array)$ids;

        sort($ids);

        foreach ($ids as $id) {
            gdrts_admin_maintenance::delete_vote_from_log($id);
        }
    }

    public static function delete_vote_from_log($log_id) {
        $row = gdrts_db()->get_log_entry($log_id);

        if ($row) {
            if (gdrts_is_method_loaded($row->method) && !gdrts_is_method_for_review($row->method)) {
                if ($row->status == 'active') {
                    $ref = $row->ref_id > 0 ? gdrts_db()->get_log_entry($row->ref_id) : null;

                    switch ($row->method) {
                        case 'stars-rating':
                            gdrtsm_stars_rating()->remove_vote_by_log($row, $ref);
                            break;
                        case 'like-this':
                            gdrtsm_like_this()->remove_vote_by_log($row, $ref);
                            break;
                        default:
                            do_action('gdrts_maintenance_delete_vote_'.$row->method, $row, $ref);
                            break;
                    }

                    if ($row->ref_id > 0) {
                        gdrts_db()->update(gdrts_db()->logs, array('status' => 'active'), array('log_id' => $row->ref_id));
                    }
                } else if ($row->status == 'replaced') {
                    if ($row->ref_id == 0) {
                        gdrts_db()->update(gdrts_db()->logs, array('ref_id' => 0), array('ref_id' => $row->log_id));
                    } else {
                        gdrts_db()->update(gdrts_db()->logs, array('ref_id' => $row->ref_id), array('ref_id' => $row->log_id));
                    }
                }

                gdrts_admin_maintenance::remove_votes_from_log($log_id);
            }
        }
    }

    public static function remove_votes_from_log($ids) {
        $ids = (array)$ids;

        $sql = "DELETE l, lm FROM ".gdrts_db()->logs." l
                LEFT JOIN ".gdrts_db()->logmeta." lm ON lm.log_id = l.log_id
                WHERE l.log_id in (".join(', ', $ids).")";

        gdrts_db()->query($sql);
    }

    public static function count_rating_objects() {
    	$query = "SELECT COUNT(*) FROM ".gdrts_db()->items." i INNER JOIN (SELECT DISTINCT item_id FROM ".gdrts_db()->items_basic.") b ON i.item_id = b.item_id";
        return gdrts_db()->get_var($query);
    }

    public static function recalculate_rating_object($item_id, $method, $series = false, $settings = array()) {
        $item = gdrts_get_rating_item_by_id($item_id);

        if ($method == 'stars-rating') {
            gdrts_admin_maintenance::recalculate_stars_rating($item, $settings);
        } else if ($method == 'like-this') {
            gdrts_admin_maintenance::recalculate_like_this($item, $settings);
        } else {
            do_action('gdrts_maintenance_recalculate_single_item_'.$method, $item, $series, $settings);
        }
    }

    public static function recalculate_rating_objects($offset, $limit, $settings = array()) {
        $query = "SELECT i.item_id FROM ".gdrts_db()->items." i INNER JOIN (SELECT DISTINCT item_id FROM ".gdrts_db()->items_basic.") b ON i.item_id = b.item_id ORDER BY item_id ASC LIMIT ".$offset.", ".$limit;
    	$objects = gdrts_db()->get_results($query);

        $results = array(
            'items' => 0,
            'processed' => 0,
            'saved' => 0,
            'cleared' => 0
        );

        foreach ($objects as $obj) {
            $item = gdrts_get_rating_item_by_id($obj->item_id);

            $results['items']++;

            foreach (array_keys($settings) as $method) {
                $results['processed']++;

                if ($method == 'stars-rating') {
                    $result = gdrts_admin_maintenance::recalculate_stars_rating($item, $settings[$method]);

                    if ($result) {
                        $results['saved']++;
                    } else {
                        $results['cleared']++;
                    }
                } else if ($method == 'like-this') {
                    $result = gdrts_admin_maintenance::recalculate_like_this($item, $settings[$method]);

                    if ($result) {
                        $results['saved']++;
                    } else {
                        $results['cleared']++;
                    }
                } else {
                    $results = apply_filters('gdrts_maintenance_recalculate_'.$method, $results, $item, $settings[$method]);
                }
            }
        }
    }

    /** @param $item gdrts_rating_item */
    public static function recalculate_like_this($item, $settings = array()) {
        gdrtsm_like_this()->init_rule_settings_for_item($item);

        $rule = array("l.`status` = 'active'", "l.`action` = 'like'", "l.`method` = 'like-this'", "l.`item_id` = ".$item->item_id);

        $log = gdrts_db()->get_log_items_filter($rule);
        $latest = gdrts_db()->get_log_latest_logged($rule);

        $likes = count($log);

        if ($likes == 0) {
            gdrts_admin_maintenance::clear_rating_item_method_limited($item->item_id, 'like-this');
        } else {
            $item->prepare_save();
            $item->prepare('like-this');

            if (in_array('rating', $settings)) {
                $item->set_rating('votes', $likes);
                $item->set_rating('rating', $likes);
                $item->set_rating('latest', $latest);
            }

            $item->save(false, false);

            return true;
        }

        return false;
    }

    /** @param $item gdrts_rating_item */
    public static function recalculate_stars_rating($item, $settings = array()) {
        gdrtsm_stars_rating()->init_rule_settings_for_item($item);

        $rule = array("l.`status` = 'active'", "l.`method` = 'stars-rating'", "l.`item_id` = ".$item->item_id);

        $log = gdrts_db()->get_log_items_filter($rule);
        $latest = gdrts_db()->get_log_latest_logged($rule);

        $sum = 0;
        $votes = 0;
        $max = gdrtsm_stars_rating()->get_rule('stars');
        $distribution = gdrtsm_stars_rating()->distribution_array($max);

        foreach ($log as $item_log) {
            $vmax = $item_log->max;
            $vote = $item_log->vote;
            $vote = $vote * ($max / $vmax);
            $sum+= $vote;
            $votes++;

            $dist = number_format(round($vote, 2), 2);

            if (!isset($distribution[$dist])) {
                $distribution[$dist] = 0;
            }

            $distribution[$dist] = $distribution[$dist] + 1;
        }

        if ($votes == 0) {
            gdrts_admin_maintenance::clear_rating_item_method_limited($item->item_id, 'stars-rating');
        } else {
            krsort($distribution);

            $rating = round($sum / $votes, gdrts()->decimals());

            $item->prepare_save();
            $item->prepare('stars-rating');

            if (in_array('rating', $settings)) {
                $item->set_rating('sum', $sum);
                $item->set_rating('max', $max);
                $item->set_rating('votes', $votes);
                $item->set_rating('rating', $rating);
                $item->set_rating('latest', $latest);
            }

            if (in_array('distribution', $settings)) {
                $item->set('stars-rating_distribution', $distribution);
            }

            $item->save(false, false);

            return true;
        }

        return false;
    }
}
