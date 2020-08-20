<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_admin_shared {
    public static function data_settings_shared_notice() {
        return __("In this panel you can set global rules for this extension. But, you can override these settings for each intividual rating entity/type from the plugin Rules panel.", "gd-rating-system").'<br/><a class="button-secondary" style="margin-top: 5px" target="_blank" href="admin.php?page=gd-rating-system-rules">'.__("Open Rules Panel", "gd-rating-system").'</a>';
    }

    public static function data_settings_addon_shared_notice() {
        return __("In this panel you can set global rules for this extension. But, you can override these settings for each intividual rating entity/type from the plugin Rules panel.", "gd-rating-system").' '.__("If you want to control these settings via the Rules panel, make sure that Activation panel has Status enabled, and all other settings should be disabled.", "gd-rating-system").'<br/><a class="button-secondary" style="margin-top: 5px" target="_blank" href="admin.php?page=gd-rating-system-rules">'.__("Open Rules Panel", "gd-rating-system").'</a>';
    }

	public static function data_list_date_period() {
		return array(
			'disabled' => __("Disabled", "gd-rating-system"),
			'latest' => __("Latest Range", "gd-rating-system"),
			'range' => __("Custom Range", "gd-rating-system")
		);
	}

    public static function data_list_date_published() {
        return array(
            'disabled' => __("Disabled", "gd-rating-system"),
            'latest' => __("Latest Range", "gd-rating-system"),
            'range' => __("Custom Range", "gd-rating-system"),
            'exact' => __("Exact Match", "gd-rating-system")
        );
    }

    public static function data_list_custom_date_periods() {
        return array(
            'day' => __("Previous day", "gd-rating-system"),
            'week' => __("Previous 7 days", "gd-rating-system"),
            'two-weeks' => __("Previous 14 days", "gd-rating-system"),
            'month' => __("Previous 30 days", "gd-rating-system"),
            'year' => __("Previous 365 days", "gd-rating-system"),
            'last-month' => __("Last month", "gd-rating-system"),
            'current-month' => __("Current month", "gd-rating-system"),
            'last-year' => __("Last year", "gd-rating-system"),
            'current-year' => __("Current year", "gd-rating-system")
        );
    }

    public static function data_list_entity_name_types() {
        $items = array();

        foreach (gdrts()->get_entities() as $entity => $obj) {
            foreach ($obj['types'] as $name => $label) {
                $items[$entity.'.'.$name] = $obj['label'].': '.$label;
            }
        }

        return $items;
    }

    public static function data_list_embed_methods($series_separator = '&minus') {
        $list = array();

        foreach (gdrts()->methods as $key => $data) {
            if ($data['autoembed'] && gdrts_is_method_loaded($key)) {
                if (gdrts_method_has_series($key)) {
                    $obj = gdrts_get_method_object($key);

                    foreach ($obj->all_series_list() as $sers => $label) {
                        $list[$key.'::'.$sers] = $data['label'].' '.$series_separator.' '.$label;
                    }
                } else {
                    $list[$key] = $data['label'];
                }
            }
        }

        return $list;
    }

    public static function data_list_all_methods($series_separator = '&minus;') {
        $list = array();

        foreach (gdrts()->methods as $key => $data) {
            if (gdrts_is_method_loaded($key)) {
                if (gdrts_method_has_series($key)) {
                    $obj = gdrts_get_method_object($key);

                    foreach ($obj->all_series_list() as $sers => $label) {
                        $list[$key.'::'.$sers] = $data['label'].' '.$series_separator.' '.$label;
                    }
                } else {
                    $list[$key] = $data['label'];
                }
            }
        }

        return $list;
    }

    public static function data_list_style_image_name() {
        return gdrts_base_data::stars_style_image_name();
    }

    public static function data_list_style_type() {
        return gdrts_base_data::stars_style_type();
    }

    public static function data_list_likes_style_type() {
        return gdrts_base_data::likes_style_type();
    }

    public static function data_list_likes_style_image_name() {
        return gdrts_base_data::likes_style_image_name();
    }

    public static function data_list_likes_style_theme() {
        return gdrts_base_data::likes_style_theme();
    }

    public static function data_list_rating_value() {
        return apply_filters('gdrts_list_stars-rating_rating_value', array(
            'average' => __("Average", "gd-rating-system")
        ));
    }

    public static function data_list_orderby() {
        return apply_filters('gdrts_list_stars-rating_orderby', array(
            'rating' => __("Average Rating", "gd-rating-system"),
            'votes' => __("Votes", "gd-rating-system"),
            'item_id' => __("Item ID", "gd-rating-system"),
            'id' => __("Object ID", "gd-rating-system"),
            'latest' => __("Latest Vote", "gd-rating-system")
        ));
    }

    public static function data_list_order() {
        return array(
            'DESC' => __("Descending", "gd-rating-system"),
            'ASC' => __("Ascending", "gd-rating-system")
        );
    }

    public static function data_list_stars() {
        $list = array();

        for ($i = 1; $i < 26; $i++) {
            $list[$i] = sprintf(_n("%s star", "%s stars", $i, "gd-rating-system"), $i);
        }

        return $list;
    }

    public static function data_list_templates($method, $type = 'single') {
        if (gdrts_is_method_valid($method) && gdrts_is_template_type_valid($type)) {
            $templates = gdrts_settings()->get($method, 'templates');

            if (!isset($templates[$type]) || empty($templates[$type])) {
                gdrts_rescan_for_templates();

                $templates = gdrts_settings()->get($method, 'templates');
            }

            return $templates[$type];
        } else {
            return array();
        }
    }

    public static function data_default_template($method, $type = 'single') {
        $list = gdrts_admin_shared::data_list_templates($method, $type);

        if (isset($list['default'])) {
            return 'default';
        } else if (!is_array($list) || empty($list)) {
            return '';
        }

        return key($list);
    }

    public static function data_list_distributions() {
        return array(
            'normalized' => __("Normalized", "gd-rating-system"),
            'exact' => __("Exact", "gd-rating-system")
        );
    }

    public static function data_list_resolutions() {
        return array(
            100 => __("100% - Full Star", "gd-rating-system"),
            50 => __("50% - Half Star", "gd-rating-system"),
            25 => __("25% - One Quarter Star", "gd-rating-system"),
            20 => __("20% - One Fifth Star", "gd-rating-system"),
            10 => __("10% - One Tenth Star", "gd-rating-system")
        );
    }

    public static function data_list_vote() {
        $default_rules = array(
            'single' => __("Basic", "gd-rating-system").': '.__("Single vote only", "gd-rating-system"),
            'revote' => __("Basic", "gd-rating-system").': '.__("Single vote with revote", "gd-rating-system"),
            'multi' => __("Basic", "gd-rating-system").': '.__("Multiple votes", "gd-rating-system")
        );

        $custom_rules = apply_filters('gdrts_custom_vote_rules', array());

        if (!empty($custom_rules)) {
            $default_rules+= $custom_rules;
        }

        return $default_rules;
    }

    public static function data_list_align() {
        return array(
            'none' => __("No alignment", "gd-rating-system"),
            'left' => __("Left", "gd-rating-system"),
            'center' => __("Center", "gd-rating-system"),
            'right' => __("Right", "gd-rating-system")
        );
    }
}
