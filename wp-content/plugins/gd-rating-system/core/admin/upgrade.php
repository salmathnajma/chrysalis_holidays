<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_admin_upgrade {
    public function __construct() { }

    public function database_upgrade() {
        require_once(GDRTS_PATH.'core/admin/install.php');

        gdrts_install_database();
        gdrts_update_database_tables_collations();
    }

    public function check_if_used_method($method) {
        $sql = "SELECT COUNT(*) FROM ".gdrts_db()->itemmeta." WHERE meta_key = '".$method."_rating'";

        return gdrts_db()->get_var($sql) > 0;
    }

    private function _run($type, $insert, $delete) {
        $results = array(
            'type' => $type,
            'error' => '',
            'insert' => 0,
            'delete' => 0
        );

        $status = gdrts_db()->query($insert);

        if ($status !== false) {
            $results['insert'] = gdrts_db()->rows_affected();

            $status = gdrts_db()->query($delete);

            if ($status !== false) {
                $results['delete'] = gdrts_db()->rows_affected();
            } else {
                $status['error'] = 'delete';
            }
        } else {
            $status['error'] = $type == 'items' ? 'insert' : 'update';
        }

        if ($type == 'logs') {
            $results['update'] = $results['insert'];

            unset($results['insert']);
        }

        return $results;
    }

    public function items_like_this() {
        $insert = "
INSERT INTO ".gdrts_db()->items_basic." 
SELECT 
    NULL AS `id`,
    i.`item_id` AS `item_id`, 
    'like-this' AS `method`, 
    '' AS `series`, 
    CAST(ml.`meta_value` AS DATETIME) AS `latest`,
    CAST(mr.`meta_value` AS UNSIGNED) AS `rating`, 
    CAST(mv.`meta_value` AS UNSIGNED) AS `votes`,
    0 AS `sum`,
    0 AS `max`
FROM ".gdrts_db()->items." i 
INNER JOIN ".gdrts_db()->itemmeta." mr ON mr.`item_id` = i.`item_id`
INNER JOIN ".gdrts_db()->itemmeta." mv ON mv.`item_id` = i.`item_id`
INNER JOIN ".gdrts_db()->itemmeta." ml ON ml.`item_id` = i.`item_id`
WHERE mr.`meta_key` = 'like-this_rating' 
AND mv.`meta_key` = 'like-this_votes' 
AND ml.`meta_key` = 'like-this_latest'";

        $delete = "
DELETE FROM ".gdrts_db()->itemmeta." 
WHERE `meta_key` IN ('like-this_rating', 'like-this_votes', 'like-this_latest')";

        return $this->_run('items', $insert, $delete);
    }

    public function logs_like_this() {
        $insert = "
UPDATE ".gdrts_db()->logs." l 
INNER JOIN ".gdrts_db()->logmeta." m ON m.`log_id` = l.`log_id`
SET l.`vote` = m.`meta_value`
WHERE l.`method` = 'like-this'
AND m.`meta_key` = 'vote'";

        $delete = "
DELETE m 
FROM ".gdrts_db()->logs." l 
INNER JOIN ".gdrts_db()->logmeta." m ON m.`log_id` = l.`log_id`
WHERE l.`method` = 'like-this'
AND m.`meta_key` = 'vote'";

        return $this->_run('logs', $insert, $delete);
    }

    public function items_stars_rating() {
        $insert = "
INSERT INTO ".gdrts_db()->items_basic." 
SELECT 
    NULL AS `id`,
    i.`item_id` AS `item_id`, 
    'stars-rating' AS `method`, 
    '' AS `series`, 
    CAST(ml.`meta_value` AS DATETIME) AS `latest`,
    CAST(mr.`meta_value` AS DECIMAL(10,2)) * 100 AS `rating`, 
    CAST(mv.`meta_value` AS UNSIGNED) AS `votes`,
    CAST(ms.`meta_value` AS DECIMAL(10,2)) * 100 AS `sum`,
    CAST(mx.`meta_value` AS UNSIGNED) AS `max`
FROM ".gdrts_db()->items." i 
INNER JOIN ".gdrts_db()->itemmeta." mr ON mr.`item_id` = i.`item_id`
INNER JOIN ".gdrts_db()->itemmeta." mv ON mv.`item_id` = i.`item_id`
INNER JOIN ".gdrts_db()->itemmeta." ml ON ml.`item_id` = i.`item_id`
INNER JOIN ".gdrts_db()->itemmeta." ms ON ms.`item_id` = i.`item_id`
INNER JOIN ".gdrts_db()->itemmeta." mx ON mx.`item_id` = i.`item_id`
WHERE mr.`meta_key` = 'stars-rating_rating' 
AND mv.`meta_key` = 'stars-rating_votes' 
AND ml.`meta_key` = 'stars-rating_latest' 
AND ms.`meta_key` = 'stars-rating_sum' 
AND mx.`meta_key` = 'stars-rating_max'";

        $delete = "
DELETE FROM ".gdrts_db()->itemmeta." 
WHERE `meta_key` IN ('stars-rating_rating', 'stars-rating_votes', 'stars-rating_latest', 'stars-rating_sum', 'stars-rating_max')";

        return $this->_run('items', $insert, $delete);
    }

    public function logs_stars_rating() {
        $insert = "
UPDATE ".gdrts_db()->logs." l 
INNER JOIN ".gdrts_db()->logmeta." mv ON mv.`log_id` = l.`log_id`
INNER JOIN ".gdrts_db()->logmeta." mm ON mm.`log_id` = l.`log_id`
SET l.`vote` = mv.`meta_value`,
    l.`max` = CAST(mm.`meta_value` as UNSIGNED)
WHERE l.`method` = 'stars-rating'
AND mv.`meta_key` = 'vote'
AND mm.`meta_key` = 'max'";

        $delete = "
DELETE m 
FROM ".gdrts_db()->logs." l 
INNER JOIN ".gdrts_db()->logmeta." m ON m.`log_id` = l.`log_id`
WHERE l.`method` = 'stars-rating'
AND m.`meta_key` IN ('vote', 'max')";

        return $this->_run('logs', $insert, $delete);
    }
}
