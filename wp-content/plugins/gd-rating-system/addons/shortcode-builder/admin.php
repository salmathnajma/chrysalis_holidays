<?php

if (!defined('ABSPATH')) exit;

class gdrts_addon_admin_shortcode_builder {
    function __construct() {
        add_action('gdrts_admin_enqueue_scripts', array($this, 'enqueue_scripts'));

        add_filter('gdrts_admin_menu_items', array($this, 'menu_items'));
        add_filter('gdrts_admin_panel_path', array($this, 'panel_path'), 10, 2);
    }

    public function enqueue_scripts($page) {
        if ($page == 'shortcodes') {
            wp_enqueue_style('flatpickr', gdrts_plugin()->lib_file('flatpickr', 'css', 'flatpickr.min', false), array(), gdrts_settings()->file_version());
            wp_enqueue_script('flatpickr', gdrts_plugin()->lib_file('flatpickr', 'js', 'flatpickr.min', false), array('jquery'), gdrts_settings()->file_version(), true);

            $flatpickr_locale = gdrts_plugin()->locale_js_code('flatpickr');

            if ($flatpickr_locale !== false) {
                wp_enqueue_script('flatpickr-'.$flatpickr_locale, GDRTS_URL.'libs/flatpickr/l10n/'.$flatpickr_locale.'.min.js', array('flatpickr'), gdrts_settings()->file_version(), true);
            }

            wp_enqueue_style('gdrts-ratings-grid', gdrts_plugin()->file('css', 'rating/grid'), array(), gdrts_settings()->file_version());
            wp_enqueue_script('clipboard', gdrts_plugin()->lib_file('clipboardjs', 'js', 'clipboard.min', false), array(), gdrts_settings()->file_version(), true);

            wp_enqueue_style('gdrts-builder', gdrts_admin()->file('css', 'shortcodes', false, true, GDRTS_URL.'addons/shortcode-builder/'), array('gdrts-plugin', 'gdrts-ratings-grid'), gdrts_settings()->file_version());
            wp_enqueue_script('gdrts-builder', gdrts_admin()->file('js', 'shortcodes', false, true, GDRTS_URL.'addons/shortcode-builder/'), array('gdrts-plugin'), gdrts_settings()->file_version(), true);
        }
    }

    public function menu_items($items) {
        return array_slice($items, 0, 2) +
            array('shortcodes' => array('title' => __("Shortcodes", "gd-rating-system"), 'icon' => 'code')) +
            array_slice($items, 2);
    }

    public function panel_path($path, $_page) {
        $_new = '';

        if ($_page == 'shortcodes') {
            $_new = $_page;
        }

        if ($_new != '') {
            $path = GDRTS_PATH.'addons/shortcode-builder/forms/'.$_new.'.php';
        }

        return $path;
    }
}

global $_gdrts_addon_admin_shortcode_builder;
$_gdrts_addon_admin_shortcode_builder = new gdrts_addon_admin_shortcode_builder();

function gdrtsa_admin_shortcode_builder() {
    global $_gdrts_addon_admin_shortcode_builder;
    return $_gdrts_addon_admin_shortcode_builder;
}
