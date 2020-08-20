<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_cache_memory extends d4p_cache_core {
    public $store = 'gdrts';

	public static function get_instance() {
		static $_instance = false;

		if ($_instance === false) {
			$_instance = new gdrts_cache_memory();
		}

		return $_instance;
	}

    public function get_item_id($entity, $name, $id) {
        if (!is_null($entity) && !is_null($name) && !is_null($id) && is_numeric($id)) {
            $item_id = $this->get('item_id', $entity.'-'.$name.'-'.$id);

            if ($item_id == false) {
                $item_id = gdrts_db()->get_item_id($entity, $name, $id);

                $this->set('item_id', $entity.'-'.$name.'-'.$id, $item_id);
            }

            return $item_id;
        } else {
            return false;
        }
    }

    public function get_log_entry($log_id) {
	    $entry = $this->get('log_entry', $log_id);

	    if ($entry == false) {
	        $entry = gdrts_db()->get_log_entry($log_id);

	        $this->set('log_entry', $log_id, $entry);
        }

	    return $entry;
    }

    public function has_user_voted_for_item($user_id, $item_id, $method, $series = null) {
        if (!gdrts_is_method_valid($method)) {
            return new WP_Error('method_invalid', __("Method is invalid", "gd-rating-system"));
        }

        $user_id = absint($user_id);
        $item_id = absint($item_id);

        if ($user_id == 0) {
            return new WP_Error('user_invalid', __("User ID is invalid", "gd-rating-system"));
        }

        if ($item_id == 0) {
            return new WP_Error('item_invalid', __("Item ID is invalid", "gd-rating-system"));
        }

        $key = $user_id.'-'.$item_id.'-'.$method.(is_string($series) ? '-'.$series : '');

        if (!$this->in('has_user_voted_for_item', $key)) {
            $value = gdrts_db()->has_user_voted_for_item($user_id, $item_id, $method, $series);

            $this->set('has_user_voted_for_item', $key, $value);

            return $value;
        } else {
            return $this->get('has_user_voted_for_item', $key);
        }
    }
}

/** @return gdrts_cache_memory */
function gdrts_cache() {
    return gdrts_cache_memory::get_instance();
}
