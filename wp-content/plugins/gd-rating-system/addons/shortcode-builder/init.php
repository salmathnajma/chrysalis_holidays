<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_addon_shortcode_builder_init extends gdrts_extension_init {
    public $group = 'addons';
    public $prefix = 'shortcode-builder';

    public function __construct() {
        parent::__construct();

        add_action('gdrts_load_addon_shortcode-builder', array($this, 'load'), 2);
        add_filter('gdrts_info_addon_shortcode-builder', array($this, 'info'));
    }

    public function register() {
        gdrts()->register_addon('shortcode-builder', __("Shortcode Builder", "gd-rating-system"), array(
            'autoload' => true,
            'free' => true
        ));
    }

    public function settings() {}

    public function info($info = array()) {
        return array('icon' => 'code', 'description' => __("Quickly build the shortcodes for use in posts.", "gd-rating-system"));
    }

    public function load() {
        require_once(GDRTS_PATH.'addons/shortcode-builder/load.php');
    }
}

$__gdrts_addon_shortcode_builder = new gdrts_addon_shortcode_builder_init();
