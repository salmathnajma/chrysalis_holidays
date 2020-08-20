<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_addon_shortcode_builder extends gdrts_addon {
    public $prefix = 'shortcode-builder';

    public function __construct() {
        parent::__construct();
    }

    public function load_admin() {
        require_once(GDRTS_PATH.'addons/shortcode-builder/admin.php');
    }
}

global $_gdrts_addon_shortcode_builder;
$_gdrts_addon_shortcode_builder = new gdrts_addon_shortcode_builder();

function gdrtsa_shortcode_builder() {
    global $_gdrts_addon_shortcode_builder;
    return $_gdrts_addon_shortcode_builder;
}
