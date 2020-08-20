<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core_user {
    public $id = 0;
    public $ip = '';
    public $log = array();

    public $cookie = array();

    private $_method = '';
    private $_series = '';

    public function __construct($id = -1, $ip = true, $cookie = true) {
        if ($id > -1) {
            $this->id = absint($id);
        } else if (is_user_logged_in()) {
            $this->id = get_current_user_id();
        }

        if ($ip === true) {
            $this->ip = gdrts_get_visitor_ip();
        } else if ($ip !== false) {
            $this->ip = $ip;
        }

        if ($cookie) {
            $this->cookie = gdrts_read_cookies();
        }
    }

    public function prepare($method, $series = null) {
        $this->_method = $method;
        $this->_series = $series;
    }

    public function get_log_ids() {
        $log_ids = array();

        if ($this->id == 0) {
            $log_ids = $this->cookie;
        }

        return $log_ids;
    }

    public function get_last_vote_timestamp() {
        return gdrts_db()->get_last_vote_timestamp($this->id, $this->ip, $this->cookie);
    }

    public function update_cookie($log_id) {
        $log_id = intval($log_id);

        if ($log_id > 0 && !in_array($log_id, $this->cookie)) {
            $this->cookie[] = $log_id;

            setcookie(gdrts()->cookie_key(), json_encode($this->cookie), gdrts()->cookie_expiration(), '/', COOKIE_DOMAIN);
        }
    }

    public function load_log($item_id, $method, $series = null) {
        $data = gdrts_db()->get_log_item_user_method($item_id, $this->id, $method, $series, $this->ip, $this->get_log_ids());

        if (is_null($series) || empty($series)) {
            $this->log[$item_id][$method] = $data;
        } else {
            $this->log[$item_id][$method][$series] = $data;
        }
    }

    public function get_log_item_user_method($item_id, $method = null, $series = null) {
        if (is_null($method)) {
            $method = $this->_method;
            $series = $this->_series;
        }

        if (is_null($series)) {
            if (!isset($this->log[$item_id][$method])) {
                $this->load_log($item_id, $method);
            }

            return $this->log[$item_id][$method];
        } else {
            if (!isset($this->log[$item_id][$method][$series])) {
                $this->load_log($item_id, $method, $series);
            }

            return $this->log[$item_id][$method][$series];
        }
    }

    public function get_log_stats_quick_anytime($item_id, $method = null, $series = null) {
        return $this->get_log_stats_quick($item_id, '', '', $method, $series);
    }

    public function get_log_stats_quick($item_id, $from = '', $to = '', $method = null, $series = null) {
        if (is_null($method)) {
            $method = $this->_method;
            $series = $this->_series;
        }

        $args = array(
            'item_id' => $item_id,
            'user_id' => $this->id,
            'method' => $method,
            'series' => $series,
            'ip' => $this->ip,
            'log_ids' => $this->get_log_ids(),
            'from' => $from,
            'to' => $to
        );

        $cache_key = md5(serialize($args));

        if (gdrts_cache()->in('item_user_stats', $cache_key)) {
            return gdrts_cache()->get('item_user_stats', $cache_key);
        }

        $data = gdrts_db()->get_log_counts_user_method(
            $item_id,
            $this->id,
            $method,
            $series,
            $this->ip,
            $this->get_log_ids(),
            $from,
            $to
        );

        gdrts_cache()->set('item_user_stats', $cache_key, $data);

        return $data;
    }
}
