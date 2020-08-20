<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_admin_cron {
    public static function recalculate_on_max_change($force = false) {
        if ($force || gdrts_settings()->get('cronjob_recheck_max_stars_rating', 'core')) {
            gdrts_admin_cron::recalculate_max_changed_stars_rating();

            gdrts_settings()->set('cronjob_recheck_max_stars_rating', false, 'core', true);
        }
    }

    public static function recalculate_max_changed_stars_rating() {
        if (!function_exists('gdrtsm_stars_rating')) {
            return;
        }

        foreach (gdrts()->get_entities() as $entity => $obj) {
            foreach (array_keys($obj['types']) as $type) {
                $settings = gdrts_rules()->get_rule_settings(gdrtsm_stars_rating(), $entity, $type);

                $object = array(
                    'entity' => $entity,
                    'name' => $type,
                    'method' => 'stars-rating',
                    'series' => '',
                    'max' => $settings['stars']
                );

                gdrts_admin_cron::recalculate_max_changed_single_type($object, true);
            }
        }
    }

    public static function recalculate_max_changed_single_type($object, $sum = true) {
        $max = $object['max'];

        $set = array(
            "b.`rating` = FLOOR(b.`rating` * (".$max."/b.`max`))"
        );

        $where = array(
            "b.`method` = '".$object['method']."'",
            "i.`entity` = '".$object['entity']."'",
            "i.`name` = '".$object['name']."'",
            "b.`max` != ".$max
        );

        if ($sum) {
            $set[] = "b.`sum` = FLOOR(b.`sum` * (".$max."/b.`max`))";
        }

        $set[] = "b.`max` = ".$max;

        $sql = "UPDATE ".gdrts_db()->items_basic." b INNER JOIN ".gdrts_db()->items." i ON i.item_id = b.item_id SET ".join(", ", $set)." WHERE ".join(" AND ", $where);

        gdrts_db()->query($sql);
    }

    public static function recalculate_statistics() {
        $results = array();

        foreach (gdrts()->get_entities() as $entity => $obj) {
            $results[$entity] = array();

            foreach (array_keys($obj['types']) as $type) {
                $results[$entity.'.'.$type] = array();
            }
        }

        $results = gdrts_admin_cron::recalculate_statistics_ratings($results);
        $results = gdrts_admin_cron::recalculate_statistics_likes($results);

        $valid_methods = array(
            'stars-rating' => array(
                'items', 'votes', 'rating'
            ),
            'like-this' => array(
                'items', 'rating'
            )
        );

        $old = gdrts_settings()->group_get('entities');

        foreach ($old as $key => &$data) {
            foreach ($valid_methods as $method => $valid_keys) {
                foreach ($valid_keys as $v) {
                    if (isset($results[$key][$method][$v])) {
                        $data[$method][$v] = $results[$key][$method][$v];
                    } else {
                        if (isset($data[$method][$v])) {
                            unset($data[$method][$v]);
                        }
                    }
                }
            }
        }

        gdrts_settings()->current['entities'] = $old;
        gdrts_settings()->save('entities');
    }

    public static function recalculate_statistics_ratings($results) {
        $rating_methods = array('stars-rating');

        foreach ($rating_methods as $method) {
            $normalize = gdrts()->get_method_prop($method, 'db_normalized', 1);

            $sql =
"SELECT 
    i.entity, 
    COUNT(i.item_id) AS items, 
    SUM(b.votes) AS votes, 
    AVG(b.rating) AS rating
FROM ".gdrts_db()->items." i
INNER JOIN ".gdrts_db()->items_basic." b ON b.item_id = i.item_id
WHERE b.method = '".$method."'
GROUP BY i.entity
ORDER BY i.entity";

            $data = gdrts_db()->run($sql);

            foreach ($data as $row) {
                $results[$row->entity][$method] = array(
                    'items' => $row->items,
                    'votes' => $row->votes,
                    'rating' => round($row->rating / $normalize, 2)
                );
            }

            $sql =
"SELECT 
    i.entity, 
    i.name, 
    COUNT(i.item_id) AS items, 
    SUM(b.votes) AS votes, 
    AVG(b.rating) AS rating
FROM ".gdrts_db()->items." i
INNER JOIN ".gdrts_db()->items_basic." b ON b.item_id = i.item_id
WHERE b.method = '".$method."'
GROUP BY i.entity, i.name
ORDER BY i.entity, i.name";

            $data = gdrts_db()->run($sql);

            foreach ($data as $row) {
                $type = $row->entity.'.'.$row->name;

                $results[$type][$method] = array(
                    'items' => $row->items,
                    'votes' => $row->votes,
                    'rating' => round($row->rating / $normalize, 2)
                );
            }
        }

        return $results;
    }

    public static function recalculate_statistics_likes($results) {
        $rating_methods = array('like-this');

        foreach ($rating_methods as $method) {
            $sql =
"SELECT 
    i.entity, 
    COUNT(i.item_id) AS items, 
    AVG(b.rating) AS rating
FROM ".gdrts_db()->items." i
INNER JOIN ".gdrts_db()->items_basic." b ON b.item_id = i.item_id
WHERE b.method = '".$method."'
GROUP BY i.entity
ORDER BY i.entity";

            $data = gdrts_db()->run($sql);

            foreach ($data as $row) {
                $results[$row->entity][$method] = array(
                    'items'  => $row->items,
                    'rating' => $row->rating
                );
            }

            $sql =
"SELECT 
    i.entity,
    i.name,
    COUNT(i.item_id) AS items, 
    AVG(b.rating) AS rating
FROM ".gdrts_db()->items." i
INNER JOIN ".gdrts_db()->items_basic." b ON b.item_id = i.item_id
WHERE b.method = '".$method."'
GROUP BY i.entity, i.name
ORDER BY i.entity, i.name";

            $data = gdrts_db()->run($sql);

            foreach ($data as $row) {
                $type = $row->entity.'.'.$row->name;

                $results[$type][$method] = array(
                    'items'  => $row->items,
                    'rating' => $row->rating
                );
            }
        }

        return $results;
    }

    public static function remove_expired_cache_entries() {
        gdrts_db_cache()->clean();
    }
}
