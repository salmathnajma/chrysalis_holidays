<?php

if (!defined('ABSPATH')) { exit; }

class gdrtsWidget_stars_rating_list extends gdrts_widget_list_core {
    public $rating_method = 'stars-rating';
    public $widget_code = 'stars_rating_list';
    public $widget_base = 'gdrts_stars_rating_list';

    public $defaults = array(
        'style_type' => '',
        'style_image_name' => '',
        'style_size' => 20,
        'font_color_empty' => '', 
        'font_color_current' => ''
    );

    public function __construct($id_base = false, $name = "", $widget_options = array(), $control_options = array()) {
        $this->widget_description = __("Show Stars Rating list.", "gd-rating-system");
        $this->widget_name = gdrts()->widget_name_prefix.__("Stars Rating List", "gd-rating-system");

        parent::__construct($this->widget_base, $this->widget_name, array(), array('width' => 500));
    }

    public function form($instance) {
        if ($this->_form_available()) {
            $instance = wp_parse_args((array)$instance, $this->get_defaults());

            $_tabs = array(
                'global' => array('name' => __("Global", "gd-rating-system"), 'include' => array('shared-global', 'shared-display')),
                'content' => array('name' => __("Content", "gd-rating-system"), 'include' => array('shared-list-rating-variant', 'shared-list-rating-scope')),
                'advanced' => array('name' => __("Advanced", "gd-rating-system"), 'include' => array('shared-filter')),
                'display' => array('name' => __("Display", "gd-rating-system"), 'include' => array('stars-rating-list-display')),
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
        $instance['style_image_name'] = d4p_sanitize_basic($new_instance['style_image_name']);
        $instance['style_size'] = intval($new_instance['style_size']);

        $instance['font_color_empty'] = d4p_sanitize_basic($new_instance['font_color_empty']);
        $instance['font_color_current'] = d4p_sanitize_basic($new_instance['font_color_current']);

        return apply_filters('gdrts_widget_settings_save', $instance, $new_instance, $this->widget_code, $this->rating_method);
    }

    public function render($results, $instance) {
        gdrts()->load_embed();

        $instance = wp_parse_args((array)$instance, $this->get_defaults());
        $instance = _gdrts_helper_prepare_widget_published($instance);

        echo _gdrts_widget_render_header($instance, $this->widget_base);

        echo _gdrts_embed_stars_rating_list($instance);

        echo _gdrts_widget_render_footer($instance);
    }
}
