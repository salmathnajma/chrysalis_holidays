<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_base_data {
    public static function stars_style_image_name() {
        return apply_filters('gdrts_list_stars_styles_images', array(
            'star' => __("Star (512px)", "gd-rating-system"),
            'heart' => __("Heart (512px)", "gd-rating-system"),
            'snowflake' => __("Snowflake (512px)", "gd-rating-system"),
            'christmas' => __("Christmas Star (512px)", "gd-rating-system"),
            'oxygen' => __("Oxygen Star (256px)", "gd-rating-system"),
            'crystal' => __("Crystal Star (256px)", "gd-rating-system")
        ));
    }

    public static function stars_style_type() {
        return apply_filters('gdrts_list_stars_style_types', array(
            'image' => __("Image Based", "gd-rating-system")
        ));
    }

    public static function likes_style_image_name() {
        return apply_filters('gdrts_list_likes_styles_images', array(
            'like' => __("Likes (256px)", "gd-rating-system")
        ));
    }

    public static function likes_style_type() {
        return apply_filters('gdrts_list_likes_style_types', array(
            'image' => __("Image Based", "gd-rating-system"),
            'text' => __("Text Only", "gd-rating-system")
        ));
    }

    public static function likes_style_theme() {
        return apply_filters('gdrts_list_likes_style_theme', array(
            'simple' => __("Simple", "gd-rating-system"),
            'standard' => __("Standard", "gd-rating-system"),
            'expanding' => __("Expanding", "gd-rating-system"),
            'balloon' => __("Balloon", "gd-rating-system")
        ));
    }
}
