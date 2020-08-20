<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core_query {
    protected $cache_key = 'query-results';
    protected $cache = null;
    protected $votes = false;
    protected $simple = false;
    protected $found = true;

    public $args = array();
    public $sql = '';
    public $sql_count = '';
    public $result_items = array();
    public $result_rows = 0;
    public $count = 0;

    public function __construct() { }

	public static function get_instance() {
		static $_instance = false;

		if ($_instance === false) {
			$_instance = new gdrts_core_query();
		}

		return $_instance;
	}

	public function query($args = array()) {
        $this->prepare_args($args);
        $this->build_query();
    }

    public function run($args = array(), $cache = null) {
        $this->cache = $cache;
        $this->query($args);

        $this->_execute_query();

        return $this->result_items;
    }

    public function query_count($args = array()) {
        $this->prepare_args($args);
        $this->build_query_count();
    }

    public function run_count($args = array()) {
        $this->query_count($args);

        $this->_execute_query_count();

        return $this->count;
    }

    protected function get_objects_array() {
        return array(
            'post_type' => array(),
            'status' => gdrts_get_default_post_statuses(),
            'author' => array(),
            'meta' => array(),
            'terms' => array()
        );
    }

    protected function prepare_args($args = array()) {
        if (!isset($args['objects'])) {
            $args['objects'] = array();
        }

        foreach (array('terms', 'author', 'status', 'meta', 'post_type') as $key) {
            if (isset($args[$key])) {
                $args['objects'][$key] = $args[$key];
                unset($args[$key]);
            }
        }

        $defaults = array(
            'method' => 'stars-rating',
            'entity' => 'posts',
            'name' => 'post',
            'id__in' => array(),
            'id__not_in' => array(),
            'orderby' => 'rating',
            'order' => 'DESC',
            'offset' => 0,
            'limit' => 5,
            'return' => 'objects',
            'rating_min' => 0,
            'votes_min' => 1,
            'source' => '',
            'object' => array()
        );

        $this->args = wp_parse_args($args, $defaults);
        $this->args['object'] = wp_parse_args($this->args['object'], $this->get_objects_array());

        $this->votes = apply_filters('gdrts_query_has_votes_'.$this->args['method'], $this->votes);

        if (!$this->votes && in_array($this->args['orderby'], array('votes', 'sum'))) {
            $this->args['orderby'] = 'rating';
        }

        $this->simple = false;

        if ($this->args['return'] == 'itemids' || $this->args['return'] == 'baseids') {
            if (in_array($this->args['orderby'], array('rating', 'votes', 'id'))) {
                $this->simple = true;
            }
        }
    }

    protected function build_query() {
        $parts = $this->prepare_query_parts();

        $this->sql = "SELECT".$parts['found']." DISTINCT ".$parts['select']." FROM ".$parts['from'];

        if (!empty($parts['where'])) {
            $this->sql.= " WHERE ".join(' AND ', $parts['where']);
        }

        $this->sql.= " ".$parts['group'].$parts['order'].$parts['limit'];

        $this->sql = apply_filters('gdrts_core_query_sql', $this->sql, $parts, $this->args);

        return $this->sql;
    }

    protected function build_query_count() {
        $parts = $this->prepare_query_parts(true);

        $this->sql_count = "SELECT ".$parts['select']." FROM ".$parts['from'];

        if (!empty($parts['where'])) {
            $this->sql_count.= " WHERE ".join(' AND ', $parts['where']);
        }

        $this->sql_count.= $parts['group'];

        $this->sql_count = apply_filters('gdrts_core_query_count_sql', $this->sql_count, $parts, $this->args);

        return $this->sql_count;
    }

    protected function prepare_query_parts($count = false) {
        $parts = array(
            'select' => '',
            'found' => ' SQL_CALC_FOUND_ROWS',
            'from'   => gdrts_db()->items." i INNER JOIN ".gdrts_db()->items_basic." b".
                        " ON b.`item_id` = i.`item_id` AND b.`method` = '".$this->args['method']."'",
            'where'  => array(
                        "i.`entity` = '".$this->args['entity']."'",
                        "i.`name` = '".$this->args['name']."'"),
            'group'  => '', 
            'order'  => '', 
            'limit'  => ''
        );

        if ($count) {
            $parts['select'] = 'COUNT(*)';
        } else {
            if ($this->simple) {
                $parts['select'] = 'i.`item_id`, b.`id` as `base_id`';
            } else {
                $parts['select'] = 'i.`item_id`, i.`entity`, i.`name`, i.`id`, b.`id` as `base_id`, b.`latest`, b.`votes`, b.`rating`, b.`sum`, b.`max`';
            }
        }

        if (!empty($this->args['id__in'])) {
            $parts['where'][] = "i.`id` IN (".join(', ', $this->args['id__in']).")";
        } else if (!empty($this->args['id__not_in'])) {
            $parts['where'][] = "i.`id` NOT IN (".join(', ', $this->args['id__not_in']).")";
        }

        if (is_numeric($this->args['rating_min']) && $this->args['rating_min'] > 0) {
            $parts['where'][] = 'b.`rating` >= '.$this->args['rating_min'];
        }

        if (is_numeric($this->args['votes_min']) && $this->args['votes_min'] > 0) {
            $parts['where'][].= 'b.`votes` >= '.$this->args['votes_min'];
        }

        $parts = $this->parse_order($parts);
        $parts = $this->parse_object($parts);

        if (is_numeric($this->args['limit']) && $this->args['limit'] > 0) {
            $parts['limit'] = " LIMIT ".absint($this->args['offset']).", ".absint($this->args['limit']);
            $this->found = true;
        } else {
            $parts['found'] = '';
            $this->found = false;
        }

        return apply_filters('gdrts_core_query_parts', $parts, $this->args);
    }

    protected function prepare_quick($items) {
        $list = array();
        $normalize = gdrts()->methods[$this->args['method']]['db_normalized'];
        $items = array_values($items);

        foreach ($items as $item) {
            if (isset($item->rating)) {
                $item->rating = $item->rating / $normalize;
                $item->sum = $item->sum / $normalize;
            }

            $list[] = $item;
        }

        return $list;
    }

    protected function prepare_objects($items) {
        $list = array();
        $metas = array();
        $ratings = array();

        $get = array();
        $real = array();
        foreach ($items as $item_id => $obj) {
            gdrts_cache()->set('item_id', $obj->entity.'-'.$obj->name.'-'.$obj->id, $item_id);

            if (!gdrts_cache()->in('item', $item_id)) {
                $get[] = $item_id;
                $real[] = $obj->id;
            }
        }

        if (!empty($get)) {
            $metas = gdrts_db()->get_items_meta($get);
            $ratings = gdrts_db()->get_items_ratings($get);

            $this->_prime_wordpress_cache($real);
        }

        $i = 1;
        foreach ($items as $item_id => $obj) {
            if (in_array($item_id, $get)) {
                $data = array(
                    'item_id' => $item_id,
                    'entity' => $obj->entity,
                    'name' => $obj->name,
                    'id' => intval($obj->id),
                    'latest' => intval(mysql2date('G', $obj->latest)),
                    'meta' => isset($metas[$item_id]) ? $metas[$item_id] : array(),
                    'ratings' => isset($ratings[$item_id]) ? $ratings[$item_id] : array()
                );

                $item = gdrts_rating_item::cache_and_get_instance($item_id, $data);

                if ($item === false) {
                    continue;
                }
            } else {
                $item = gdrts_get_rating_item_by_id($item_id);
            }

            $item->ordinal = $i;
            $list[] = $item;

            $i++;
        }

        return $list;
    }

    protected function parse_order($q) {
        if ($this->args['orderby'] != '' && $this->args['orderby'] != 'none') {
            if ($this->args['orderby'] == 'rand') {
                $q['order'] = ' ORDER BY RAND()';
            } else {
                $q['order'] = ' ORDER BY ';

                $order = $this->_get_order();
                $orderby = $this->args['orderby'];

                $has_max = gdrts()->get_method_prop($this->args['method'], 'has_max', false);

                $_default = $has_max ? 'b.`rating`/b.`max` ' : 'b.`rating` ';

                if ($this->votes) {
                    $_default.= $order.', b.`votes`';
                }
                
                switch ($orderby) {
                    case 'item':
                    case 'item_id':
                        $q['order'].= 'i.`item_id`';
                        break;
                    case 'id':
                        $q['order'].= 'i.`id`';
                        break;
                    case 'latest':
                        $q['order'].= 'i.`latest`';
                        break;
                    case 'up':
                        $q['order'].= '`up` '.$order.', b.`rating`';
                        break;
                    case 'down':
                        $q['order'].= '`down` '.$order.', b.`rating`';
                        break;
                    case 'votes':
                        $q['order'].= 'b.`votes`';
                        break;
                    case 'percentage':
                        $q['order'].= '`percentage` '.$order.', b.`votes`';
                        break;
                    case 'sum':
                        $q['order'].= 'b.`sum`';
                        break;
                    case 'rating':
                        $q['order'].= $_default;
                        break;
                    default:
                        $q['order'].= apply_filters('gdrts_core_query_sort_orderby_'.$orderby, $_default, $this->args);
                        break;
                }

                $q['order'].= ' '.$order;
            }
        }

        return $q;
    }

    protected function parse_object($q) {
        $d = wp_parse_args($this->args['object'], array('post_type' => array(), 'author' => array(), 'meta' => array(), 'terms' => array()));

        $active = false;

        foreach ($d as &$value) {
            $value = (array)$value;

            if (!empty($value)) {
                $active = true;
            }
        }

        if ($active) {
            switch ($this->args['entity']) {
                case 'posts':
                    $q = $this->_parse_object_posts($q, $d);
                    break;
                case 'comments':
                    $q = $this->_parse_object_comments($q, $d);
                    break;
                case 'terms':
                    $q = $this->_parse_object_terms($q, $d);
                    break;
                case 'users':
                    $q = $this->_parse_object_users($q, $d);
                    break;
            }
        }

        return $q;
    }

    protected function _get_order() {
        $order = 'DESC';

        if (strtoupper($this->args['order']) == 'ASC') {
            $order = 'ASC';
        }

        return $order;
    }

    protected function _parse_object_posts($q, $d) {
        $q['from'].= ' INNER JOIN '.gdrts_db()->wpdb()->posts.' op ON op.`ID` = i.`id`';

        $q['order'].= ', op.`post_date` '.$this->_get_order();

        if (!empty($d['author'])) {
            $data = $this->_process_items($d['authors']);

            if (!empty($data['add'])) {
                $q['where'][] = 'op.`post_author` IN ('.join(', ', $data['add']).')';
            }

            if (!empty($data['sub'])) {
                $q['where'][] = 'op.`post_author` NOT IN ('.join(', ', $data['sub']).')';
            }
        }

        if (!empty($d['status'])) {
            $q['where'][] = "op.`post_status` IN ('".join("', '", $d['status'])."')";
        }

        if (!empty($d['meta'])) {
            $mid = 1;

            foreach ($d['meta'] as $key => $value) {
                $q['from'].= ' INNER JOIN '.gdrts_db()->wpdb()->postmeta.' om'.$mid.' ON op.`ID` = om'.$mid.'.`post_id`';

                $q['where'][] = "om".$mid.".`meta_key` = '".esc_sql($key)."'";
                $q['where'][] = "om".$mid.".`meta_value` = '".esc_sql($value)."'";

                $mid++;
            }
        }

        if (!empty($d['terms'])) {
            $taxonomies = gdrts()->get_object_taxonomies($this->args['name']);
            $taxonomies = apply_filters('gdrts_core_query_posts_terms_taxonomies', $taxonomies, $this->args['name'], $this);

            if (!empty($taxonomies)) {
                $q['from'].= ' INNER JOIN '.gdrts_db()->wpdb()->term_relationships.' otr ON op.`ID` = otr.`object_id`';
                $q['from'].= ' INNER JOIN '.gdrts_db()->wpdb()->term_taxonomy.' ott ON ott.`term_taxonomy_id` = otr.`term_taxonomy_id`';

                $data = $this->_process_items($d['terms']);

                if (!empty($data['add'])) {
                    $q['where'][] = 'ott.`term_id` IN ('.join(', ', $data['add']).')';
                }

                if (!empty($data['sub'])) {
                    $q['where'][] = 'ott.`term_id` NOT IN ('.join(', ', $data['sub']).')';
                }

                $q['where'][] = "ott.`taxonomy` IN ('".join("', '", $taxonomies)."')";
            }
        }

        return $q;
    }

    protected function _parse_object_comments($q, $d) {
        $q['from'].= ' INNER JOIN '.gdrts_db()->wpdb()->comments.' oc ON oc.`comment_ID` = i.`id`';

        $q['order'].= ', oc.`comment_date` '.$this->_get_order();

        if (!empty($d['author'])) {
            $data = $this->_process_items($d['authors']);

            if (!empty($data['add'])) {
                $q['where'][] = 'oc.`user_id` IN ('.join(', ', $data['add']).')';
            }

            if (!empty($data['sub'])) {
                $q['where'][] = 'oc.`user_id` NOT IN ('.join(', ', $data['sub']).')';
            }
        }

        if (!empty($d['post_type'])) {
            $q['from'].= ' INNER JOIN '.gdrts_db()->wpdb()->posts.' ops ON oc.`comment_post_ID` = ops.`ID`';

            $q['where'][] = "ops.`post_type` IN ('".join("', '", $d['post_type'])."')";
        }

        if (!empty($d['meta'])) {
            $mid = 1;

            foreach ($d['meta'] as $key => $value) {
                $q['from'].= ' INNER JOIN '.gdrts_db()->wpdb()->commentmeta.' om'.$mid.' ON oc.`comment_ID` = om'.$mid.'.`comment_id`';

                $q['where'][] = "om".$mid.".`meta_key` = '".esc_sql($key)."'";
                $q['where'][] = "om".$mid.".`meta_value` = '".esc_sql($value)."'";

                $mid++;
            }
        }

        return $q;
    }

    protected function _parse_object_terms($q, $d) {
        $q['from'].= ' INNER JOIN '.gdrts_db()->wpdb()->terms.' ot ON i.id = ot.`term_id`';
        $q['from'].= ' INNER JOIN '.gdrts_db()->wpdb()->term_taxonomy.' ott ON ott.`term_id` = ot.`term_id`';

        $q['order'].= ', ott.`term_id` '.$this->_get_order();

        if (!empty($d['meta'])) {
            $mid = 1;

            foreach ($d['meta'] as $key => $value) {
                $q['from'].= ' INNER JOIN '.gdrts_db()->wpdb()->termmeta.' om'.$mid.' ON ott.`term_id` = om'.$mid.'.`term_id`';

                $q['where'][] = "om".$mid.".`meta_key` = '".esc_sql($key)."'";
                $q['where'][] = "om".$mid.".`meta_value` = '".esc_sql($value)."'";

                $mid++;
            }
        }

        return $q;
    }

    protected function _parse_object_users($q, $d) {
        $q['from'].= ' INNER JOIN '.gdrts_db()->wpdb()->users.' ou ON ou.`ID` = i.`id`';

        $q['order'].= ', ou.`user_registered` '.$this->_get_order();

        if (!empty($d['meta'])) {
            $mid = 1;

            foreach ($d['meta'] as $key => $value) {
                $q['from'].= ' INNER JOIN '.gdrts_db()->wpdb()->usermeta.' om'.$mid.' ON ou.`ID` = om'.$mid.'.`user_id`';

                $q['where'][] = "om".$mid.".`meta_key` = '".esc_sql($key)."'";
                $q['where'][] = "om".$mid.".`meta_value` = '".esc_sql($value)."'";

                $mid++;
            }
        }

        return $q;
    }

    protected function _method() {
        return $this->args['method'];
    }

    protected function _use_cache() {
        if (is_null($this->cache)) {
            return gdrts_settings()->get('db_cache_on_query');
        }

        return $this->cache === true;
    }

    protected function _execute_query() {
        $results = false;

        $db_cache_args = $this->args;
        $db_cache_args[] = $this->sql;

        if ($this->_use_cache()) {
            $results = gdrts_db_cache()->get($this->cache_key, $this->_method(), $db_cache_args);
        }

        if ($results === false) {
            $raw = gdrts_db()->run_and_index($this->sql, 'item_id');

            if ($this->found) {
                $rows = gdrts_db()->get_found_rows();
            } else {
                $rows = count($raw);
            }

            if (empty($raw)) {
                $items = null;
            } else {
                switch ($this->args['return']) {
                    case 'realids':
                        $items = wp_list_pluck($raw, 'id');
                        break;
                    case 'ids':
                    case 'itemids':
                        $items = array_keys($raw);
                        break;
                    case 'baseids':
                        $items = wp_list_pluck($raw, 'base_id');
                        break;
                    case 'quick':
                        $items = $this->prepare_quick($raw);
                        break;
                    default:
                    case 'objects':
                        $items = $this->prepare_objects($raw);
                        break;
                }
            }

            if ($this->_use_cache()) {
                $results = array(
                    'items' => $items,
                    'rows' => $rows
                );

                gdrts_db_cache()->set($this->cache_key, $this->_method(), $db_cache_args, $results);
            }

            $this->result_items = $items;
            $this->result_rows = $rows;
        } else {
            $this->result_items = $results['items'];
            $this->result_rows = $results['rows'];
        }
    }

    protected function _execute_query_count() {
        $this->count = gdrts_db()->get_var($this->sql);
    }

    protected function _prime_wordpress_cache($items) {
        switch ($this->args['entity']) {
            case 'posts':
                _prime_post_caches($items);
                break;
            case 'terms':
                _prime_term_caches($items);
                break;
            case 'commments':
                _prime_comment_caches($items);
                break;
        }
    }

    private function _process_items($input) {
        $data = array('add' => array(), 'sub' => array());

        foreach ($input as $val) {
            $val = intval($val);

            if ($val < 0) {
                $data['sub'][] = abs($val);
            } else {
                $data['add'][] = $val;
            }
        }

        return $data;
    }
}

/** @return gdrts_core_query */
function gdrts_query() {
    return gdrts_core_query::get_instance();
}

function gdrts_query_run($args = array(), $cache = null) {
    return gdrts_query()->run($args, $cache);
}
