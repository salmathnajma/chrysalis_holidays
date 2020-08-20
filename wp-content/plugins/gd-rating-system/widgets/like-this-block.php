<?php

if (!defined('ABSPATH')) { exit; }

class gdrtsWidget_like_this_block extends gdrts_widget_single_core {
    public $rating_method = 'like-this';
    public $widget_code = 'like_this_block';
    public $widget_base = 'gdrts_like_this_block';

    public $defaults = array(
        'style_type' => '',
        'style_theme' => '',
        'style_image_name' => '',
        'style_size' => 30
    );

    public function __construct($id_base = false, $name = "", $widget_options = array(), $control_options = array()) {
        $this->widget_description = __("Show Like This block.", "gd-rating-system");
        $this->widget_name = gdrts()->widget_name_prefix.__("Like This Block", "gd-rating-system");

        parent::__construct($this->widget_base, $this->widget_name, array(), array('width' => 500));
    }

    public function form($instance) {
        if ($this->_form_available()) {
            $instance = wp_parse_args((array)$instance, $this->get_defaults());

            $_tabs = array(
                'global' => array('name' => __("Global", "gd-rating-system"), 'include' => array('shared-global', 'shared-display')),
                'content' => array('name' => __("Content", "gd-rating-system"), 'include' => array('stars-rating-content')),
                'display' => array('name' => __("Display", "gd-rating-system"), 'include' => array('like-this-display')),
                'extra' => array('name' => __("Extra", "gd-rating-system"), 'include' => array('shared-wrapper'))
            );

            include(GDRTS_PATH.'forms/widgets/shared-loader.php');
        }
    }

    public function update($new_instance, $old_instance) {
        if (!$this->_method_available()) {
            return $old_instance;
        }

        $instance = $this->_shared_update($new_instance, $old_instance);

        $instance['style_type'] = d4p_sanitize_basic($new_instance['style_type']);
        $instance['style_theme'] = d4p_sanitize_basic($new_instance['style_theme']);
        $instance['style_image_name'] = d4p_sanitize_basic($new_instance['style_image_name']);
        $instance['style_size'] = intval($new_instance['style_size']);

        return apply_filters('gdrts_widget_settings_save', $instance, $new_instance, $this->widget_code, $this->rating_method);
    }

    public function render($results, $instance) {
        gdrts()->load_embed();

        $instance = wp_parse_args((array)$instance, $this->get_defaults());

        if ($instance['styling'] == 'default') {
            $keys = apply_filters('gdrts_widget_default_keys', array('styling', 'template', 'alignment', 'style_type', 'style_theme', 'style_image_name', 'style_size', 'style_class'), 'like_this_block', 'like-this');

            foreach ($keys as $key) {
                unset($instance[$key]);
            }
        }

        echo _gdrts_widget_render_header($instance, $this->widget_base);

        if ($instance['content'] == 'post') {
            echo _gdrts_embed_like_this_auto($instance);
        } else {
            echo _gdrts_embed_like_this($instance);
        }

        echo _gdrts_widget_render_footer($instance);
    }
}
