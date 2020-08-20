<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_rating_item {
    public $item_id;
    public $entity;
    public $name;
    public $id;
    public $latest;

    public $meta;
    public $ratings;
    public $snippets;
    public $period;
    public $data = null;
    public $ordinal = 0;

    public $error = false;

    private $backup_meta;
    private $backup_ratings;

    private $_method;
    private $_series;

    public function __construct($data) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }

        if ($this->item_id > 0 && is_null($this->data)) {
            $this->data = gdrts_load_object_data($this->entity, $this->name, $this->id);
        }
    }

    public function __get($name) {
        if (isset($this->meta[$name])) {
            return $this->meta[$name];
        } else {
            if (substr($name, 0, 14) == 'rich-snippets_') {
                $key = substr($name, 14);

                if (isset($this->snippets[$key])) {
                    return $this->snippets[$key];
                } else if (substr($key, 0, 5) == 'mode_') {
                    $rk = substr($key, 5);

                    foreach ($this->snippets as $group => $data) {
                        if (substr($rk, 0, strlen($group) + 1) == $group.'_') {
                            $vk = substr($rk, strlen($group) + 1);

                            if (isset($data[$vk])) {
                                return $vk;
                            }

                            return null;
                        }
                    }
                }
            } else {
                foreach ($this->ratings as $method => $inner) {
                    if (gdrts_method_has_series($method)) {
                        foreach ($inner as $series => $obj) {
                            $val = $method.'-'.$series.'_';

                            if (substr($name, 0, strlen($val)) == $val) {
                                $key = substr($name, strlen($val));

                                if (isset($inner[$key])) {
                                    return $inner[$key];
                                }

                                break;
                            }
                        }
                    } else {
                        if (substr($name, 0, strlen($method) + 1) == $method.'_') {
                            $key = substr($name, strlen($method) + 1);

                            if (isset($inner[$key])) {
                                return $inner[$key];
                            }

                            break;
                        }
                    }
                }
            }

            return null;
        }
    }

    /** @return gdrts_rating_item|bool */
    public static function cache_and_get_instance($item_id, $data) {
        $defaults = array(
            'item_id' => $item_id,
            'entity' => '',
            'name' => '',
            'id' => '',
            'latest' => '',
            'meta' => '',
            'ratings' => '',
            'snippets' => ''
        );

        $data = shortcode_atts($defaults, $data);
        $data = apply_filters('gdrts_rating_item_instance_init', $data);

        gdrts_cache()->add('item', $item_id, $data);

        if (!gdrts()->has_entity_type($data['entity'], $data['name'])) {
            return false;
        }

        return new gdrts_rating_item($data);
    }

    /** @return gdrts_rating_item|bool */
    public static function get_instance($item_id = null, $entity = null, $name = null, $id = null) {
        if (is_null($item_id) || $item_id == 0) {
            $item_id = gdrts_cache()->get_item_id($entity, $name, $id);

            if ($item_id === false || is_null($item_id)) {
                return false;
            }
        }

        $item_id = absint($item_id);

        $data = gdrts_cache()->get('item', $item_id);

        if ($data === false) {
            $item = gdrts_db()->get_item($item_id);

            if (isset($item->item_id) && $item->item_id > 0) {
                $data = array(
                    'item_id' => $item_id,
                    'entity' => $item->entity,
                    'name' => $item->name,
                    'id' => intval($item->id),
                    'latest' => intval(mysql2date('G', $item->latest)),
                    'meta' => gdrts_db()->get_item_meta($item_id),
                    'ratings' => gdrts_db()->get_item_ratings($item_id)
                );

                return gdrts_rating_item::cache_and_get_instance($item_id, $data);
            } else {
                return false;
            }
        }

	    if (!gdrts()->has_entity_type($data['entity'], $data['name'])) {
		    return false;
	    }

        return new gdrts_rating_item($data);
    }

    public function prepare($method, $series = null) {
        if (strpos($method, '::') !== false) {
            $split = explode('::', $method, 2);

            $method = $split[0];
            $series = $split[1];
        }

        $this->_method = $method;
        if (gdrts_method_has_series($method)) {
            $this->_series = $series;
        }
    }

    public function prepare_save() {
        $this->backup_meta = $this->meta;
        $this->backup_ratings = $this->ratings;
    }

    public function save($update_latest = true, $save_all_ratings = true) {
        foreach ($this->meta as $key => $value) {
            if (is_array($this->backup_meta) && isset($this->backup_meta[$key])) {
                if ($this->backup_meta[$key] != $value) {
                    gdrts_db()->update_meta('item', $this->item_id, $key, $value, $this->backup_meta[$key]);
                }
            } else {
                gdrts_db()->add_meta('item', $this->item_id, $key, $value, true);
            }
        }

        if (is_array($this->backup_meta)) {
            foreach ($this->backup_meta as $key => $value) {
                if (!isset($this->meta[$key])) {
                    gdrts_db()->delete_meta('item', $this->item_id, $key);
                }
            }
        }

        if ($save_all_ratings) {
            foreach ($this->ratings as $method => $data) {
                if (gdrts_method_has_series($method)) {
                    foreach ($data as $series => $obj) {
                        $this->_save_rating_data($obj, $method, $series);
                    }
                } else {
                    $this->_save_rating_data($data, $method);
                }
            }
        } else {
            if (gdrts_method_has_series($this->_method)) {
                $this->_save_rating_data($this->ratings[$this->_method][$this->_series], $this->_method, $this->_series);
            } else {
                $this->_save_rating_data($this->ratings[$this->_method], $this->_method);
            }
        }

        if ($update_latest) {
            gdrts_db()->update_item_latest($this->item_id);
        }

        gdrts_cache()->delete('item', $this->item_id);
    }

    public function set($name, $value) {
        $this->meta[$name] = $value;
    }

    public function set_rating($name, $value, $method = null, $series = null) {
        if (is_null($method)) {
            $method = $this->_method;
            $series = $this->_series;
        } else {
            if (strpos($method, '::') !== false) {
                $split = explode('::', $method, 2);

                $method = $split[0];
                $series = $split[1];
            }
        }

        if (gdrts_method_has_series($method)) {
            $this->ratings[$method][$series][$name] = $value;
        } else {
            $this->ratings[$method][$name] = $value;
        }
    }

    public function un_set($name) {
        if (isset($this->meta[$name])) {
            unset($this->meta[$name]);
        }
    }

    public function is_period() {
        return isset($this->period->rating);
    }

    public function getfix($prefix, $name, $default = '') {
        if (isset($this->period->{$name})) {
            return $this->period->{$name};
        }

        return $this->get($prefix.$name, $default);
    }

    public function get($name, $default = '') {
        $value = $this->{$name};

        if (is_null($value)) {
            $value = $default;
        }

        return $value;
    }

    public function get_meta($name, $default = false) {
        if (isset($this->meta[$name])) {
            return $this->meta[$name];
        } else {
            return $default;
        }
    }

    public function get_meta_prefixed($prefix, $with_prefix = false) {
        $meta = array();

        foreach ($this->meta as $key => $value) {
            if (substr($key, 0, strlen($prefix)) == $prefix) {
                $new = $with_prefix ? $key : substr($key, strlen($prefix));
                $meta[$new] = $value;
            }
        }

        return $meta;
    }

    public function get_snippet_value($mode, $default = array()) {
        if (isset($this->snippets[$mode])) {
            return $this->snippets[$mode];
        }

        return $default;
    }

    public function get_method_data($method = null, $series = null) {
        if (is_null($method)) {
            $method = $this->_method;
            $series = $this->_series;
        } else {
            if (strpos($method, '::') !== false) {
                $split = explode('::', $method, 2);

                $method = $split[0];
                $series = $split[1];
            }
        }

        if (gdrts_method_has_series($method) && (is_null($series) || empty($series))) {
            $series = 'default';
        }

        $key = is_null($series) || empty($series) ? $method.'_' : $method.'-'.$series.'_';

        $meta = $this->get_meta_prefixed($key);

        if (gdrts_method_has_series($method)) {
            if (isset($this->ratings[$method][$series])) {
                $meta+= $this->ratings[$method][$series];
            }
        } else {
            if (isset($this->ratings[$method])) {
                $meta+= $this->ratings[$method];
            }
        }

        return $meta;
    }

    public function get_method_period_value($name, $default = 0, $method = null, $series = null) {
        if (isset($this->period->{$name})) {
            return $this->period->{$name};
        }

        return $this->get_method_value($name, $default, $method, $series);
    }

    public function get_method_value($name, $default = 0, $method = null, $series = null) {
        $method = $this->get_method_data($method, $series);

        if (isset($method[$name])) {
            return $method[$name];
        }

        return $default;
    }

    public function item_data() {
        return array(
            'entity' => $this->entity,
            'name' => $this->name,
            'id' => $this->id,
            'item_id' => $this->item_id,
            'nonce' => wp_create_nonce($this->nonce_key())
        );
    }

    public function nonce_key() {
        return 'gdrts_item_'.$this->entity.'_'.$this->name.'_'.$this->id;
    }

    public function rating_classes() {
        return array(
            'gdrts-item-entity-'.$this->entity,
            'gdrts-item-name-'.$this->name,
            'gdrts-item-id-'.$this->id,
            'gdrts-item-itemid-'.$this->item_id
        );
    }

    public function update_meta($meta = array()) {
        if (!empty($meta)) {
            $this->prepare_save();

            foreach ($meta as $key => $value) {
                $this->meta[$key] = $value;
            }

            $this->save(false);
        }
    }

    public function title() {
        if (isset($this->meta['title']) && !empty($this->meta['title'])) {
            return $this->meta['title'];
        } else {
            return $this->data->get_title();
        }
    }

    public function url() {
        if (isset($this->meta['url']) && !empty($this->meta['url'])) {
            return $this->meta['url'];
        } else {
            return $this->data->get_url();
        }
    }

    public function excerpt() {
        if (isset($this->meta['excerpt']) && !empty($this->meta['excerpt'])) {
            return $this->meta['excerpt'];
        } else {
            return $this->data->get_excerpt();
        }
    }

    public function author_id() {
        if (isset($this->meta['author_id']) && !empty($this->meta['author_id'])) {
            return $this->meta['author_id'];
        } else {
            return $this->data->get_author_id();
        }
    }

    public function thumbnail($size = 'thumbnail', $attr = array()) {
        if ($this->data->has_thumbnail()) {
            return $this->data->get_thumbnail($size, $attr);
        }

        return '';
    }

    public function thumbnail_url($size = 'thumbnail') {
        if ($this->data->has_thumbnail()) {
            return $this->data->thumbnail_url($size);
        }

        return '';
    }

    public function date_published($format = 'c', $gmt = false) {
        return $this->data->get_date_published($format, $gmt);
    }

    public function date_modified($format = 'c', $gmt = false) {
        return $this->data->get_date_modified($format, $gmt);
    }

    public function has_voted($method, $series = null, $user_id = 0) {
        if ($user_id == 0) {
            $user_id = get_current_user_id();
        }

        if ($user_id == 0) {
            return new WP_Error('invalid_user_id', __("Invalid user ID provided or user not logged in.", "gd-rating-system"));
        }

        return gdrts_cache()->has_user_voted_for_item($user_id, $this->item_id, $method, $series);
    }

    public function users_who_voted($method, $series = null, $args = array()) {
        return gdrts_db()->get_users_who_voted_for_item($this->item_id, $method, $series, $args);
    }

    public function reviewed_item() {
        return array();
    }

    public function get_rule_filter_elements() {
    	$list = array();

    	if ($this->entity == 'posts') {
    		if (!empty($this->data->terms)) {
    			$list['terms'] = call_user_func_array('array_merge', $this->data->terms);
		    }
	    }

    	return $list;
    }

    private function _save_rating_data($obj, $method, $series = null) {
        if (isset($obj['id']) && $obj['id'] > 0) {
            if (is_null($series)) {
                $backup = isset($this->backup_ratings[$method]) ? $this->backup_ratings[$method] : array();
            } else {
                $backup = isset($this->backup_ratings[$method][$series]) ? $this->backup_ratings[$method] : array();
            }

            gdrts_db()->update_item_rating($this->item_id, $obj, $method, $series, $backup);
        } else {
            gdrts_db()->insert_item_rating($this->item_id, $obj, $method, $series);
        }
    }
}
