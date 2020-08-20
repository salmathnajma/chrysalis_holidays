<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_font_default extends gdrts_font {
    public $version = '1.4';
    public $name = 'font';

    public function __construct() {
        parent::__construct();

        $this->label = __("Default Font Icon", "gd-rating-system");

        $this->icons = array(
            'star' => array('char' => 's', 'label' => __("Star", "gd-rating-system")),
            'asterisk' => array('char' => 'a', 'label' => __("Asterisk", "gd-rating-system")),
            'heart' => array('char' => 'h', 'label' => __("Heart", "gd-rating-system")),
            'bell' => array('char' => 'b', 'label' => __("Bell", "gd-rating-system")),
            'square' => array('char' => 'q', 'label' => __("Square", "gd-rating-system")),
            'circle' => array('char' => 'c', 'label' => __("Circle", "gd-rating-system")),
            'gear' => array('char' => 'g', 'label' => __("Gear", "gd-rating-system")),
            'trophy' => array('char' => 't', 'label' => __("Trophy", "gd-rating-system")),
            'snowflake' => array('char' => 'f', 'label' => __("Snowflake", "gd-rating-system")),
            'like' => array('char' => 'l', 'label' => __("Thumb", "gd-rating-system")),
            'like2' => array('char' => 'k', 'label' => __("Thumb Alt", "gd-rating-system")),
            'dislike' => array('char' => 'd', 'label' => __("Thumb Down", "gd-rating-system")),
            'dislike2' => array('char' => 'i', 'label' => __("Thumb Down Alt", "gd-rating-system")),
            'smile' => array('char' => 'm', 'label' => __("Smile", "gd-rating-system")),
            'frown' => array('char' => 'r', 'label' => __("Frown", "gd-rating-system")),
            'plus' => array('char' => '+', 'label' => __("Plus", "gd-rating-system")),
            'minus' => array('char' => '-', 'label' => __("Minus", "gd-rating-system")),
            'spinner' => array('char' => 'x', 'label' => __("Spinner", "gd-rating-system")),
            'clear' => array('char' => 'e', 'label' => __("Clear", "gd-rating-system")),
            'check' => array('char' => 'j', 'label' => __("Check", "gd-rating-system"))
        );

        $this->likes = array(
            'hands-fill' => array('like' => 'l', 'liked' => 'j', 'clear' => 'e', 'label' => __("Hands Filled", "gd-rating-system")),
            'hands-empty' => array('like' => 'k', 'liked' => 'j', 'clear' => 'e', 'label' => __("Hands Empty", "gd-rating-system"))
        );
    }

    public function register_enqueue_files($js_full, $css_full, $js_dep, $css_dep) {
        wp_register_style('gdrts-font-default', gdrts_plugin()->file('css', 'fonts/default'), $css_dep, gdrts_settings()->file_version());
    }

    public function enqueue_core_files() {
        wp_enqueue_style('gdrts-font-default');
    }
}
