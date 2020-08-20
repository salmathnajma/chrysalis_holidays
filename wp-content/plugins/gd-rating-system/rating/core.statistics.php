<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core_statistics {
    public function __construct() { }

	public static function get_instance() {
		static $_instance = false;

		if ($_instance === false) {
			$_instance = new gdrts_core_statistics();
		}

		return $_instance;
	}

    public function get_entities_active_items() {
        $entities = gdrts()->get_entities();

        $sql = "SELECT i.entity, COUNT(*) AS items FROM ".gdrts_db()->items." i
                INNER JOIN (SELECT DISTINCT item_id FROM ".gdrts_db()->itemmeta.") m ON i.item_id = m.item_id
                WHERE m.item_id IS NOT NULL GROUP BY i.entity";

        $raw = gdrts_db()->get_results($sql);

        $data = array();

        foreach ($entities as $entity => $obj) {
            $data[$entity] = array(
                'label' => $obj['label'],
                'icon' => $obj['icon'],
                'count' => 0
            );
        }

        foreach ($raw as $r) {
            $entity = $r->entity;
            $items = $r->items;

            if (isset($data[$entity])) {
                $data[$entity]['count'] = $items;
            }
        }

        return $data;
    }

    public function get_total_votes_counts() {
        $sql = "SELECT IF(`user_id` = 0, 'visitors', 'users') AS users, COUNT(*) AS votes
                FROM ".gdrts_db()->logs." WHERE `status` = 'active' GROUP BY users";
        $raw = gdrts_db()->get_results($sql);

        $data = array(
            'users' => 0,
            'visitors' => 0,
            'total' => 0
        );

        foreach ($raw as $row) {
            $data[$row->users] = $row->votes;
            $data['total']+= $row->votes;
        }

        return $data;
    }
}

/** @return gdrts_core_statistics */
function gdrts_statistics() {
    return gdrts_core_statistics::get_instance();
}
