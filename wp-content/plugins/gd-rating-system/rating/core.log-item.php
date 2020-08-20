<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core_log_item {
    public $log_id;
    public $item_id;
    public $user_id;
    public $ref_id;
    public $action;
    public $status;
    public $method;
    public $logged;
    public $ip;
    public $series;
    public $vote;
    public $max;

    public $meta;
    public $multi;

    public function __construct($data) {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function meta($name, $default = null) {
        return isset($this->meta[$name]) ? $this->meta[$name] : $default;
    }

    public function is_active_vote() {
        return $this->status == 'active' && in_array($this->action, array('vote', 'revote'));
    }
}
