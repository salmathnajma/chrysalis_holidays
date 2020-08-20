<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core_db extends d4p_wpdb_core {
    public $db_site = array();

    public $_prefix = 'gdrts';
    public $_tables = array(
        'cache',
        'itemmeta',
        'items',
        'items_basic',
        'logmeta',
        'logs',
        'exports');
    public $_metas = array(
        'item' => 'item_id',
        'log' => 'log_id');

    public function get_item($item_id) {
        return $this->get_row($this->prepare("SELECT * FROM ".$this->items." WHERE item_id = %d", absint($item_id)));
    }

    public function get_item_meta($item_id) {
        $raw = $this->run($this->prepare("SELECT * FROM ".$this->itemmeta." WHERE item_id = %d", absint($item_id)));
        $data = array();

        foreach ($raw as $row) {
            $data[$row->meta_key] = maybe_unserialize($row->meta_value);
        }

        return $data;
    }

    public function get_item_ratings($item_id) {
        $data = array();

        $raw = $this->run($this->prepare("SELECT * FROM ".$this->items_basic." WHERE item_id = %d", absint($item_id)));

        foreach ($raw as $row) {
            $entry = array(
                'id' => absint($row->id),
                'latest' => $row->latest,
                'votes' => absint($row->votes),
                'rating' => $row->rating / gdrts()->methods[$row->method]['db_normalized'],
                'sum' => absint($row->sum) / gdrts()->methods[$row->method]['db_normalized']
            );

            if ($row->max > 0) {
                $entry['max'] = absint($row->max);
            }

            $data[$row->method] = $entry;
        }

        return $data;
    }

    public function get_items_meta($items) {
        $items = $this->clean_ids_list($items);

        if (empty($items)) {
            return array();
        }

        $raw = $this->run("SELECT * FROM ".$this->itemmeta." WHERE item_id in (".join(', ', $items).")");
        $data = array();

        foreach ($raw as $row) {
            $data[$row->item_id][$row->meta_key] = maybe_unserialize($row->meta_value);
        }

        return $data;
    }

    public function get_items_ratings($items) {
        $items = $this->clean_ids_list($items);

        if (empty($items)) {
            return array();
        }

        $raw = $this->run("SELECT * FROM ".$this->items_basic." WHERE item_id in (".join(', ', $items).")");
        $data = array();

        foreach ($raw as $row) {
            $entry = array(
                'id' => $row->id,
                'latest' => $row->latest,
                'votes' => $row->votes,
                'rating' => $row->rating / gdrts()->methods[$row->method]['db_normalized'],
                'sum' => $row->sum / gdrts()->methods[$row->method]['db_normalized']
            );

            if ($row->max > 0) {
                $entry['max'] = $row->max;
            }

            $data[$row->item_id][$row->method] = $entry;
        }

        return $data;
    }

    public function get_item_id($entity, $name, $id) {
	    if (!gdrts()->has_entity_type($entity, $name)) {
	    	return false;
	    }

	    $id = absint($id);

	    if ($id == 0) {
	        return false;
        }

	    $sql = $this->prepare("SELECT `item_id` FROM ".$this->items." WHERE `entity` = %s AND `name` = %s AND `id` = %d", $entity, $name, $id);

        $item_id = $this->get_var($sql);

        if (is_null($item_id)) {
            $item_id = $this->_add_new_item($entity, $name, $id);
        }

        return $item_id === false || is_null($item_id) ? false : intval($item_id);
    }

    public function get_log($log_id) {
        return $this->get_row($this->prepare("SELECT * FROM ".$this->logs." WHERE log_id = %d", absint($log_id)));
    }

    public function get_log_meta($log_id) {
        $raw = $this->run($this->prepare("SELECT * FROM ".$this->logmeta." WHERE log_id = %d", absint($log_id)));
        $data = array();

        foreach ($raw as $row) {
            $data[$row->meta_key] = maybe_unserialize($row->meta_value);
        }

        return $data;
    }

    public function get_log_entry($log_id) {
        $query =
"SELECT l.*, COUNT(m.meta_id) AS `meta`, 0 AS `multi`
FROM ".$this->logs." l
LEFT JOIN ".$this->logmeta." m ON m.`log_id` = l.`log_id`
WHERE l.`log_id` = ".$log_id."
GROUP BY l.`log_id`";

        $row = $this->get_row($query);

        if (is_object($row)) {
            $log = $this->_log_expand(array($row));

            return $log[$log_id];
        }

        return false;
    }

    public function get_log_latest_logged($filter = array()) {
        $sql = "SELECT l.`logged` FROM ".$this->logs." l WHERE ".join(' AND ', $filter)." ORDER BY `logged` DESC LIMIT 0, 1";

        return $this->get_var($sql);
    }

    public function get_log_items_filter($filter = array()) {
        $sql = "SELECT l.* FROM ".$this->logs." l WHERE ".join(' AND ', $filter);

        $raw = $this->run($sql);
        $data = array();

        foreach ($raw as $row) {
            $data[$row->log_id] = $row;
        }

        return $data;
    }

    public function get_log_item_user($item_id, $user_id, $ip = '', $log_ids = array(), $from = '', $to = '') {
	    $log_ids = $this->clean_ids_list($log_ids);
	    $filters = $this->_log_filter($user_id, $ip, $log_ids, $from, $to);

        $log = array();

        if (empty($filters)) {
            return $log;
        }

        $SQL = array(
            'select' => array(
                'l.*',
                'COUNT(m.meta_id) as `meta`',
                '0 as `multi`'),
            'from' => array(
                $this->logs.' l',
                'LEFT JOIN '.$this->logmeta.' m ON m.`log_id` = l.`log_id`'),
            'where' => array(
                "l.`item_id` = ".$item_id),
            'group' => 'l.`log_id`',
            'order' => 'l.`log_id` DESC'
        );

        $SQL['where'] = array_merge($SQL['where'], $filters);

        $query = $this->build_query($SQL, false);

        $raw = $this->run($query);
        $raw = $this->_log_expand($raw);

        foreach ($raw as $log_id => $r) {
            $log[$r->method][$r->series][$r->status][$log_id] = new gdrts_core_log_item((array)$r);
        }

        return $log;
    }

    public function get_log_item_user_method($item_id, $user_id, $method, $series = null, $ip = '', $log_ids = array(), $from = '', $to = '') {
	    $log_ids = $this->clean_ids_list($log_ids);
	    $filters = $this->_log_filter($user_id, $ip, $log_ids, $from, $to);

        $log = array();

        if (empty($filters)) {
            return $log;
        }

        $SQL = array(
            'select' => array(
                'l.*',
                'COUNT(m.meta_id) as `meta`',
                '0 as `multi`'),
            'from' => array(
                $this->logs.' l',
                'LEFT JOIN '.$this->logmeta.' m ON m.`log_id` = l.`log_id`'),
            'where' => array(
                "l.`item_id` = ".$item_id,
                "l.`method` = '".$method."'"),
            'group' => 'l.`log_id`',
            'order' => 'l.`log_id` DESC'
        );

        $SQL['where'] = array_merge($SQL['where'], $filters);

        $query = $this->build_query($SQL, false);

        $raw = $this->run($query);
        $raw = $this->_log_expand($raw);

        foreach ($raw as $row) {
            $id = absint($row->log_id);
            $action = $row->action;

            $log[$action][$id] = $row;
        }

        return $log;
    }

    public function get_log_counts_user_method($item_id, $user_id, $method, $series = null, $ip = '', $log_ids = array(), $from = '', $to = '') {
        $log_ids = $this->clean_ids_list($log_ids);
        $filters = $this->_log_filter($user_id, $ip, $log_ids, $from, $to);

        $log = array(
            'vote' => array('items' => 0, 'log_id' => 0),
            'revote' => array('items' => 0, 'log_id' => 0),
            'like' => array('items' => 0, 'log_id' => 0),
            'clear' => array('items' => 0, 'log_id' => 0)
        );

        if (empty($filters)) {
            return $log;
        }

        $SQL = array(
            'select' => array(
                'l.`action`',
                'COUNT(*) AS `items`',
                'MAX(`log_id`) AS `log_id`'),
            'from' => array(
                $this->logs.' l'),
            'where' => array(
                "l.`item_id` = ".$item_id,
                "l.`method` = '".$method."'"),
            'group' => 'l.`log_id`'
        );

        $SQL['where'] = array_merge($SQL['where'], $filters);

        $query = $this->build_query($SQL, false);

        $raw = $this->run($query);

        foreach ($raw as $row) {
            $key = $row->action;
            unset($row->action);

            $log[$key] = (array)$row;
        }

        return $log;
    }

    public function get_last_vote_timestamp($user_id, $ip = '', $log_ids = array()) {
        $log_ids = $this->clean_ids_list($log_ids);
        $filters = $this->_log_filter($user_id, $ip, $log_ids);

        if (empty($filters)) {
            return 0;
        }

        $SQL = array(
            'select' => array('`logged`'),
            'from' => array($this->logs.' l'),
            'where' => $filters,
            'order' => 'l.`logged` DESC'
        );

        $query = $this->build_query($SQL, false);

        $datetime = $this->get_var($query);

        if ($datetime) {
            return strtotime($datetime);
        }

        return 0;
    }

    public function update_item_latest($item_id) {
        $this->update($this->items, array(
            'latest' => $this->datetime()
        ), array(
            'item_id' => absint($item_id)
        ));
    }

    public function add_to_log($item_id, $user_id, $method, $data = array(), $meta = array(), $multi = array()) {
        $defaults = array(
            'action' => 'vote',
            'status' => 'active',
            'ip' => gdrts_get_visitor_ip(),
            'logged' => $this->datetime(),
            'ref_id' => 0,
            'vote' => '',
            'max' => 0
        );

        $data = wp_parse_args($data, $defaults);

        $data['item_id'] = $item_id;
        $data['user_id'] = $user_id;
        $data['method'] = $method;

        $result = $this->insert($this->logs, $data);

        if ($result !== false) {
            $log_id = $this->get_insert_id();

            if (!isset($meta['ua']) && gdrts_settings()->get('log_vote_user_agent')) {
                $ua = gdrts_get_user_agent();

                if (!empty($ua)) {
                    $meta['ua'] = $ua;
                }
            }

            $meta = apply_filters('gdrts_db_add_vote_to_log', $meta, $data);

            if (!empty($meta)) {
                $this->insert_meta_data($this->logmeta, 'log_id', $log_id, $meta);
            }

            if ($data['ref_id'] > 0) {
                $this->update($this->logs,
	                array('status' => 'replaced'),
	                array('log_id' => $data['ref_id'])
                );
            }

            do_action('gdrts_db_vote_logged', $log_id, $data, $meta);

            return $log_id;
        }

        return null;
    }

    public function insert_item_rating($item_id, $data, $method, $series = null) {
        $defaults = array(
            'item_id' => $item_id,
            'method' => $method,
            'series' => is_null($series) ? '' : $series,
            'latest' => '',
            'rating' => 0,
            'votes' => 0,
            'sum' => 0,
            'max' => 0
        );

        $args = shortcode_atts($defaults, $data);
        $args['sum'] = $args['sum'] * gdrts()->methods[$method]['db_normalized'];
        $args['rating'] = $args['rating'] * gdrts()->methods[$method]['db_normalized'];

        $status = $this->insert($this->items_basic, $args);

        if ($status !== false) {
            foreach ($data as $key => $value) {
                if (!isset($args[$key]) && $key != 'id') {
                    $meta = is_null($series) ? $method.'_'.$key : $method.'-'.$series.'_'.$key;

                    $this->update_meta('item', $item_id, $meta, $value);
                }
            }
        }
    }

    public function update_item_rating($item_id, $data, $method, $series = null, $backup = array()) {
        $defaults = array(
            'latest' => '',
            'rating' => 0,
            'votes' => 0,
            'sum' => 0,
            'max' => 0
        );

        $id = isset($data['id']) ? $data['id'] : 0;

        $args = shortcode_atts($defaults, $data);
        $args['sum'] = $args['sum'] * gdrts()->methods[$method]['db_normalized'];
        $args['rating'] = $args['rating'] * gdrts()->methods[$method]['db_normalized'];

        $this->update($this->items_basic, $args, array('id' => $id));

        foreach ($data as $key => $value) {
            if (!isset($args[$key]) && $key != 'id') {
                $old = isset($backup[$key]) ? $backup[$key] : '';
                $meta = is_null($series) ? $method.'_'.$key : $method.'-'.$series.'_'.$key;

                $this->update_meta('item', $item_id, $meta, $value, $old);
            }
        }
    }

    public function has_user_voted_for_item($user_id, $item_id, $method, $series = null) {
        if (is_string($series)) {
            $sql = $this->prepare("SELECT COUNT(*) AS votes FROM ".$this->logs." WHERE `action` = 'vote' AND `user_id` = %d AND `item_id` = %d AND `method` = %s AND `series` = %s", $user_id, $item_id, $method, $series);
        } else {
            $sql = $this->prepare("SELECT COUNT(*) AS votes FROM ".$this->logs." WHERE `action` = 'vote' AND `user_id` = %d AND `item_id` = %d AND `method` = %s", $user_id, $item_id, $method);
        }

        return $this->get_var($sql) > 0;
    }

    public function get_users_who_voted_for_item($item_id, $method, $series = null, $args = array()) {
        $defaults = array('limit' => 24, 'offset' => 0, 'orderby' => 'logged', 'order' => 'DESC');

        $args = wp_parse_args($args, $defaults);

        if (!gdrts_is_method_valid($method)) {
            return new WP_Error('method_invalid', __("Method is invalid", "gd-rating-system"));
        }

        $item_id = absint($item_id);

        $args['offset'] = absint($args['offset']);
        $args['limit'] = absint($args['limit']);

        $SQL = array(
            'select' => 'l.log_id, l.user_id, l.vote, u.user_login, u.user_email, u.user_url, u.display_name',
            'join' => $this->logs.' l INNER JOIN '.$this->users.' u ON u.ID = l.user_id',
            'where' => array('l.item_id = '.$item_id, "l.status = 'active'"),
            'orderby' => 'l.logged',
            'order' => $args['order'] == 'DESC' ? 'DESC' : 'ASC',
            'offset' => $args['offset'] > 0 ? $args['offset'] : 0,
            'limit' => $args['limit'] > 0 ? $args['limit'] : 0
        );

        $SQL['where'][] = "l.method = '".esc_sql($method)."'";

        switch ($args['orderby']) {
            case 'user':
                $SQL['orderby'] = 'l.user_id';
                break;
            case 'rand':
                $SQL['orderby'] = 'rand()';
                $SQL['order'] = '';
                break;
        }

        $SQL = apply_filters('gdrts_get_users_who_voted_for_item_query', $SQL, $item_id, $method, $series, $args);

        if (!empty($SQL['where'])) {
            $SQL['where'] = ' WHERE '.join(' AND ', $SQL['where']);
        } else {
            $SQL['where'] = '';
        }

        $query = "SELECT SQL_CALC_FOUND_ROWS ".$SQL['select']." FROM ".$SQL['join'].$SQL['where'];
        $query.= " ORDER BY ".$SQL['orderby']." ".$SQL['order'];

        if ($SQL['limit'] > 0) {
            $query.= " LIMIT ".$SQL['offset'].", ".$SQL['limit'];
        }

        $list = $this->run($query);
        
        return array(
            'list' => $list,
            'item_id' => $item_id,
            'method' => $method,
            'series' => $series,
            'count' => count($list),
            'total' => $this->get_found_rows(),
            'offset' => $SQL['offset'],
            'limit' => $SQL['limit']
        );
    }

    public function get_latest_log_items($limit = 10) {
        $query = "SELECT * FROM ".$this->logs." l ORDER BY l.logged DESC LIMIT 0, ".absint($limit);

        return $this->get_results($query);
    }

    public function md5_hash_votes_log_ips() {
        $query = "UPDATE ".$this->logs." SET ip = CONCAT('md5:', LCASE(MD5(ip))) WHERE SUBSTR(ip, 1, 4) != 'md5:'";

        $this->query($query);

        return $this->rows_affected();
    }

    public function snippets_remove_legacy_data() {
        $query = "DELETE FROM ".$this->itemmeta." WHERE meta_key IN ('rich-snippets_method', 'rich-snippets_settings', 'rich-snippets_itemscope', 'rich-snippets_settings', 'rich-snippets_main_entity', 'rich-snippets_image', 'rich-snippets_author', 'rich-snippets_publisher', 'rich-snippets_date_published', 'rich-snippets_date_modified')";

        $this->query($query);
    }

    public function snippets_disable_ratings() {
        $query = "UPDATE ".$this->itemmeta." SET meta_value = 'none' WHERE meta_key IN ('rich-snippets_mode_web_page_rating', 'rich-snippets_mode_news_article_rating', 'rich-snippets_mode_article_rating', 'rich-snippets_mode_blog_posting_rating')";

        $this->query($query);
    }

    public function snippets_remove_metakey($post_types, $meta_keys) {
        $post_types = (array)$post_types;
        $meta_keys = (array)$meta_keys;

        $query = "DELETE m FROM ".$this->itemmeta." m INNER JOIN ".$this->items." i 
                  ON i.item_id = m.item_id AND m.meta_key IN ('".join("', '", $meta_keys)."') 
                  WHERE i.`entity` = 'posts' AND i.`name` IN ('".join("', '", $post_types)."')";

        $this->query($query);
    }

	private function _add_new_item($entity, $name, $id) {
		if (!is_null($entity) && !is_null($name) && !is_null($id) && is_numeric($id)) {
			if (!gdrts()->has_entity_type($entity, $name)) {
				return null;
			}

			$result = $this->insert($this->items, array(
				'entity' => $entity,
				'name' => $name,
				'id' => $id
			));

			if ($result !== false) {
				return $this->get_insert_id();
			}
		}

		return null;
	}

	private function _log_filter($user_id, $ip, $log_ids = array(), $from = '', $to = '') {
        $where = array();

        if ($user_id == 0) {
            if (empty($ip)) {
                $ip = gdrts_get_visitor_ip();
            }

            $verify = gdrts_settings()->get('annonymous_verify');

            if ($verify == 'ip_andr_cookie') {
                $verify = 'ip_and_cookie';
            }

            if ($verify == 'cookie' && empty($log_ids)) {
                return array();
            }

            if (($verify == 'ip_or_cookie' || $verify == 'ip_and_cookie') && empty($log_ids)) {
                $verify = 'ip';
            }

            switch ($verify) {
                case 'ip_or_cookie':
                    $where[] = "(l.`log_id` IN (".join(', ', $log_ids).") OR l.`ip` = '".esc_sql($ip)."')";
                    break;
                case 'ip_and_cookie':
                    $where[] = "l.`log_id` IN (".join(', ', $log_ids).")";
                    $where[] = "l.`ip` = '".esc_sql($ip)."'";
                    break;
                case 'cookie':
                    $where[] = "l.`log_id` IN (".join(', ', $log_ids).")";
                    break;
                default:
                case 'ip':
                    $where[] = "l.`ip` = '".esc_sql($ip)."'";
                    break;
            }

            if (gdrts_settings()->get('annonymous_same_ip')) {
                $where[] = "l.`user_id` = 0";
            }
        } else {
            $where[] = "l.`user_id` = ".$user_id;
        }

        if (!empty($from)) {
            if (empty($to)) {
                $to = gdrts_db()->datetime(true);
            }

            if (is_numeric($from)) {
                $from = d4p_mysql_date($from);
            }

            if (is_numeric($to)) {
                $to = d4p_mysql_date($to);
            }

            $where[] = "l.`logged` >= '".esc_sql($from)."'";
            $where[] = "l.`logged` <= '".esc_sql($to)."'";
        }

        return $where;
    }

    private function _log_expand($raw) {
        $log = array();

        $_meta = array();

        foreach ($raw as $row) {
            $id = absint($row->log_id);

            if ($id > 0) {
                if ($row->meta > 0) {
                    $_meta[] = $id;

                    $row->meta = new stdClass();
                } else {
                    $row->meta = false;
                }

                $row->multi = false;

                $log[$id] = $row;
            }
        }

        if (!empty($_meta)) {
            $raw = $this->run("SELECT * FROM ".$this->logmeta." WHERE `log_id` in (".join(', ', $_meta).")");

            foreach ($raw as $meta) {
                $id = absint($meta->log_id);
                $key = $meta->meta_key;

                $log[$id]->meta->$key = maybe_unserialize($meta->meta_value);
            }
        }

        return $log;
    }

    public function maybe_to_upgrade_to_four() {
        $count = 0;
        $where = array();

        foreach (array('%_votes', '%_rating', '%_up', '%_down', '%_latest', '%_sum', '%_max') as $item) {
            $where[] = "`meta_key` LIKE '".$item."'";
        }

        $sql = "SELECT COUNT(*) FROM ".$this->itemmeta." WHERE ".join(' OR ', $where);
        $count+= $this->get_var($sql);

        $sql = "SELECT COUNT(*) FROM ".$this->logmeta." WHERE `meta_key` IN ('vote', 'max') OR `meta_key` LIKE 'vote_%'";
        $count+= $this->get_var($sql);

        return $count > 0;
    }
}
