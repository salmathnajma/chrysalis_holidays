<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_addon_shortcake extends gdrts_addon {
    public $prefix = 'shortcake';

    public function load_admin() {}

    public function init() {
        $this->register();
    }

    public function register() {
        require_once(GDRTS_PATH.'core/admin/shared.php');

        shortcode_ui_register_for_shortcode('gdrts_rating_value', array(
            'label' => 'Rating: '.__("Value", "gd-rating-system"),
            'listItemImage' => 'dashicons-flag',
            'attrs' => apply_filters('gdrts_shortcake_attrs_rating_value',
                    array_merge($this->_atts_basic_attributes(), 
                                $this->_atts_method_attributes(),
                                $this->_atts_value_attributes(),
                                $this->_atts_tag_attributes()))
        ));

        shortcode_ui_register_for_shortcode('gdrts_rating_value_auto', array(
            'label' => 'Rating: '.__("Value - Auto Item", "gd-rating-system"),
            'listItemImage' => 'dashicons-flag',
            'attrs' => apply_filters('gdrts_shortcake_attrs_rating_value_auto',
                    array_merge($this->_atts_method_attributes(),
                                $this->_atts_value_attributes(),
                                $this->_atts_tag_attributes()))
        ));

        shortcode_ui_register_for_shortcode('gdrts_has_voted', array(
            'label' => 'Rating: '.__("User Has Voted", "gd-rating-system"),
            'listItemImage' => 'dashicons-yes-alt',
            'inner_content' => array(
                'label' => __("Inner Content", "gd-rating-system"),
            ),
            'attrs' => apply_filters('gdrts_shortcake_attrs_has_voted',
                array_merge($this->_atts_basic_attributes(),
                    $this->_atts_method_attributes(),
                    $this->_atts_user_attributes(),
                    $this->_atts_tag_attributes()))
        ));

        shortcode_ui_register_for_shortcode('gdrts_has_voted_auto', array(
            'label' => 'Rating: '.__("User Has Voted - Auto Item", "gd-rating-system"),
            'listItemImage' => 'dashicons-yes-alt',
            'inner_content' => array(
                'label' => __("Inner Content", "gd-rating-system"),
            ),
            'attrs' => apply_filters('gdrts_shortcake_attrs_has_voted_auto',
                array_merge($this->_atts_method_attributes(),
                    $this->_atts_user_attributes(),
                    $this->_atts_tag_attributes()))
        ));

        shortcode_ui_register_for_shortcode('gdrts_has_not_voted', array(
            'label' => 'Rating: '.__("User Has Not Voted", "gd-rating-system"),
            'listItemImage' => 'dashicons-dismiss',
            'inner_content' => array(
                'label' => __("Inner Content", "gd-rating-system"),
            ),
            'attrs' => apply_filters('gdrts_shortcake_attrs_has_not_voted',
                array_merge($this->_atts_basic_attributes(),
                    $this->_atts_method_attributes(),
                    $this->_atts_user_attributes(),
                    $this->_atts_tag_attributes()))
        ));

        shortcode_ui_register_for_shortcode('gdrts_has_not_voted_auto', array(
            'label' => 'Rating: '.__("User Has Not Voted - Auto Item", "gd-rating-system"),
            'listItemImage' => 'dashicons-dismiss',
            'inner_content' => array(
                'label' => __("Inner Content", "gd-rating-system"),
            ),
            'attrs' => apply_filters('gdrts_shortcake_attrs_has_not_voted_auto',
                array_merge($this->_atts_method_attributes(),
                    $this->_atts_user_attributes(),
                    $this->_atts_tag_attributes()))
        ));

        if (gdrts_is_method_loaded('stars-rating')) {
            shortcode_ui_register_for_shortcode('gdrts_stars_rating', array(
                'label' => 'Rating: '.__("Stars Rating", "gd-rating-system"),
                'listItemImage' => 'dashicons-star-filled',
                'attrs' => apply_filters('gdrts_shortcake_attrs_stars_rating', 
                        array_merge($this->_atts_basic_attributes(), 
                                    $this->_atts_stars_rating_method(), 
                                    $this->_atts_css_classes()))
            ));

            shortcode_ui_register_for_shortcode('gdrts_stars_rating_auto', array(
                'label' => 'Rating: '.__("Stars Rating - Auto Item", "gd-rating-system"),
                'listItemImage' => 'dashicons-star-filled',
                'attrs' => apply_filters('gdrts_shortcake_attrs_stars_rating_auto', 
                        array_merge($this->_atts_stars_rating_method(), 
                                    $this->_atts_css_classes()))
            ));

            shortcode_ui_register_for_shortcode('gdrts_stars_rating_list', array(
                'label' => 'Rating: '.__("Stars Rating - Items List", "gd-rating-system"),
                'listItemImage' => 'dashicons-star-filled',
                'attrs' => apply_filters('gdrts_shortcake_attrs_stars_rating_list', 
                        array_merge($this->_atts_list_attributes(), 
                                    $this->_atts_stars_rating_list_method(), 
                                    $this->_atts_css_classes()))
            ));
        }

        if (gdrts_is_method_loaded('like-this')) {
            shortcode_ui_register_for_shortcode('gdrts_like_this', array(
                'label' => 'Rating: '.__("Like This", "gd-rating-system"),
                'listItemImage' => 'dashicons-yes',
                'attrs' => apply_filters('gdrts_shortcake_attrs_like_this', 
                        array_merge($this->_atts_basic_attributes(), 
                                    $this->_atts_like_this_method(), 
                                    $this->_atts_css_classes()))
            ));

            shortcode_ui_register_for_shortcode('gdrts_like_this_auto', array(
                'label' => 'Rating: '.__("Like This - Auto Item", "gd-rating-system"),
                'listItemImage' => 'dashicons-yes',
                'attrs' => apply_filters('gdrts_shortcake_attrs_like_this_auto', 
                        array_merge($this->_atts_like_this_method(), 
                                    $this->_atts_css_classes()))
            ));

            shortcode_ui_register_for_shortcode('gdrts_like_this_list', array(
                'label' => 'Rating: '.__("Like This - Items List", "gd-rating-system"),
                'listItemImage' => 'dashicons-yes',
                'attrs' => apply_filters('gdrts_shortcake_attrs_like_this_list', 
                        array_merge($this->_atts_list_attributes(), 
                                    $this->_atts_like_this_list_method(), 
                                    $this->_atts_css_classes()))
            ));
        }

        do_action('gdrts_shortcake_register');
    }

    public function _atts_method_attributes() {
        return array(
            array(
                'label' => __("Method (and Series)", "gd-rating-system"),
                'attr' => 'method',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_all_methods('-'),
                'value' => 'stars-rating'
            )
        );
    }

    public function _atts_value_attributes() {
        return array(
            array(
                'label' => __("Value", "gd-rating-system"),
                'attr' => 'value',
                'type' => 'text',
                'value' => 'rating',
                'description' => __("There are many values that can be displayed. For more information consult the knowledge base.", "gd-rating-system")
            )
        );
    }

    public function _atts_user_attributes() {
        return array(
            array(
                'label' => __("User ID", "gd-rating-system"),
                'attr' => 'value',
                'type' => 'number',
                'value' => '0'
            )
        );
    }

    public function _atts_tag_attributes() {
        return array(
            array(
                'label' => __("Wrapper Tag", "gd-rating-system"),
                'attr' => 'tag',
                'type' => 'select',
                'options' => array(
                    'span' => "SPAN",
                    'div' => "DIV"
                ),
                'value' => 'span'
            )
        );
    }

    public function _atts_basic_attributes() {
        return array(
            array(
                'label' => __("Rating object Entity and Type", "gd-rating-system"),
                'attr' => 'type',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_entity_name_types(),
                'value' => 'posts.post'
            ),
            array(
                'label' => __("Rating object ID", "gd-rating-system"),
                'attr' => 'id',
                'type' => 'number',
                'value' => 0,
                'description' => __("Entity and Type with ID here descirbe rating object.", "gd-rating-system").' '.__("This ID must be integer higher than 0.", "gd-rating-system")
            ),
            array(
                'label' => __("Rating Item ID", "gd-rating-system"),
                'attr' => 'item_id',
                'type' => 'number',
                'value' => 0,
                'description' => __("If you now item ID for rating object, you can use it instead of Rating object entity, type and ID defined above.", "gd-rating-system").' '.__("This ID must be integer higher than 0.", "gd-rating-system")
            )
        );
    }

    public function _atts_list_attributes() {
        return array(
            array(
                'label' => __("Rating object Entity and Type", "gd-rating-system"),
                'attr' => 'type',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_entity_name_types(),
                'value' => 'posts.post'
            ),
            array(
                'label' => __("Order By", "gd-rating-system"),
                'attr' => 'orderby',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_orderby(),
                'value' => 'rating'
            ),
            array(
                'label' => __("Order", "gd-rating-system"),
                'attr' => 'order',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_order(),
                'value' => 'DESC'
            ),
            array(
                'label' => __("Limit", "gd-rating-system"),
                'attr' => 'limit',
                'type' => 'number',
                'value' => 5
            ),
            array(
                'label' => __("Terms ID's (comma separated)", "gd-rating-system"),
                'attr' => 'terms',
                'type' => 'text',
                'description' => __("For posts and custom post types.", "gd-rating-system")
            ),
            array(
                'label' => __("Authors ID's (comma separated)", "gd-rating-system"),
                'attr' => 'author',
                'type' => 'text',
                'description' => __("For posts, pages and custom post types.", "gd-rating-system")
            ),
            array(
                'label' => __("Post Statuses", "gd-rating-system"),
                'attr' => 'status',
                'type' => 'select',
                'meta' => array('multiple' => true),
                'options' => $this->_data_post_statuses(),
                'description' => __("For posts, pages and custom post types. It is not advisable to include all post statuses. If nothing is selected, default post statuses will be auto applied.", "gd-rating-system"),
                'value' => array()
            ),
            array(
                'label' => __("Post Types", "gd-rating-system"),
                'attr' => 'post_type',
                'type' => 'select',
                'meta' => array('multiple' => true),
                'options' => $this->_data_post_types(),
                'description' => __("For comments. If nothing is selected, all post types will be taken into account.", "gd-rating-system"),
                'value' => array()
            ),
            array(
                'label' => __("With minimal rating", "gd-rating-system"),
                'attr' => 'rating_min',
                'type' => 'number',
                'value' => 0
            ),
            array(
                'label' => __("With minimal number of votes", "gd-rating-system"),
                'attr' => 'votes_min',
                'type' => 'number',
                'value' => 1
            )
        );
    }

    public function _atts_css_classes() {
        return array(
            array(
                'label' => __("Additional rating block CSS classes (space separated)", "gd-rating-system"),
                'attr' => 'style_class',
                'type' => 'text'
            ),
            array(
                'label' => __("Shortcode wrapper CSS classes (space separated)", "gd-rating-system"),
                'attr' => 'class',
                'type' => 'text'
            )
        );
    }

    private function _atts_like_this_method($method = 'like-this') {
        return array(
            array(
                'label' => __("Template", "gd-rating-system"),
                'attr' => 'template',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_templates($method),
                'value' => gdrts_admin_shared::data_default_template($method)
            ),
            array(
                'label' => __("Disable Rating", "gd-rating-system"),
                'attr' => 'disable_rating',
                'type' => 'checkbox',
                'value' => ''
            ),
            array(
                'label' => __("Alignment", "gd-rating-system"),
                'attr' => 'alignment',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_align(),
                'value' => ''
            ),
            array(
                'label' => __("Style Theme", "gd-rating-system"),
                'attr' => 'style_theme',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_likes_style_theme(),
                'value' => ''
            ),
            array(
                'label' => __("Style Type", "gd-rating-system"),
                'attr' => 'style_type',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_likes_style_type(),
                'value' => ''
            ),
            array(
                'label' => __("Image", "gd-rating-system"),
                'attr' => 'style_image_name',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_likes_style_image_name(),
                'value' => ''
            ),
            array(
                'label' => __("Size", "gd-rating-system"),
                'attr' => 'style_size',
                'type' => 'number',
                'value' => 30,
                'description' => __("Size in pixels thumbs or text.", "gd-rating-system")
            )
        );
    }

    private function _atts_like_this_list_method($method = 'like-this') {
        return array(
            array(
                'label' => __("Template", "gd-rating-system"),
                'attr' => 'template',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_templates($method, 'list'),
                'value' => gdrts_admin_shared::data_default_template($method, 'list')
            ),
            array(
                'label' => __("Style Theme", "gd-rating-system"),
                'attr' => 'style_theme',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_likes_style_theme(),
                'value' => ''
            ),
            array(
                'label' => __("Style Type", "gd-rating-system"),
                'attr' => 'style_type',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_likes_style_type(),
                'value' => ''
            ),
            array(
                'label' => __("Image", "gd-rating-system"),
                'attr' => 'style_image_name',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_likes_style_image_name(),
                'value' => ''
            ),
            array(
                'label' => __("Size", "gd-rating-system"),
                'attr' => 'style_size',
                'type' => 'number',
                'value' => 30,
                'description' => __("Size in pixels thumbs or text.", "gd-rating-system")
            )
        );
    }

    private function _atts_stars_rating_method($method = 'stars-rating') {
        return array(
            array(
                'label' => __("Votes Distribution", "gd-rating-system"),
                'attr' => 'distribution',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_distributions(),
                'value' => ''
            ),
            array(
                'label' => __("Disable Rating", "gd-rating-system"),
                'attr' => 'disable_rating',
                'type' => 'checkbox',
                'value' => ''
            ),
            array(
                'label' => __("Template", "gd-rating-system"),
                'attr' => 'template',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_templates($method),
                'value' => gdrts_admin_shared::data_default_template($method)
            ),
            array(
                'label' => __("Alignment", "gd-rating-system"),
                'attr' => 'alignment',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_align(),
                'value' => ''
            ),
            array(
                'label' => __("Style Type", "gd-rating-system"),
                'attr' => 'style_type',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_style_type(),
                'value' => ''
            ),
            array(
                'label' => __("Image", "gd-rating-system"),
                'attr' => 'style_image_name',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_style_image_name(),
                'value' => ''
            ),
            array(
                'label' => __("Size", "gd-rating-system"),
                'attr' => 'style_size',
                'type' => 'number',
                'value' => 30,
                'description' => __("Size in pixels for each star.", "gd-rating-system")
            ),
            array(
                'label' => __("Font Icon Colors", "gd-rating-system").': '.__("Empty Stars", "gd-rating-system"),
                'attr' => 'font_color_empty',
                'type' => 'color',
                'value' => ''
            ),
            array(
                'label' => __("Font Icon Colors", "gd-rating-system").': '.__("Current Stars", "gd-rating-system"),
                'attr' => 'font_color_current',
                'type' => 'color',
                'value' => ''
            ),
            array(
                'label' => __("Font Icon Colors", "gd-rating-system").': '.__("Active Stars", "gd-rating-system"),
                'attr' => 'font_color_active',
                'type' => 'color',
                'value' => ''
            )
        );
    }

    private function _atts_stars_rating_list_method($method = 'stars-rating') {
        return array(
            array(
                'label' => __("Template", "gd-rating-system"),
                'attr' => 'template',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_templates($method, 'list'),
                'value' => gdrts_admin_shared::data_default_template($method, 'list')
            ),
            array(
                'label' => __("Style Type", "gd-rating-system"),
                'attr' => 'style_type',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_style_type(),
                'value' => 'font'
            ),
            array(
                'label' => __("Image", "gd-rating-system"),
                'attr' => 'style_image_name',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_style_image_name(),
                'value' => 'star'
            ),
            array(
                'label' => __("Size", "gd-rating-system"),
                'attr' => 'style_size',
                'type' => 'number',
                'value' => 20,
                'description' => __("Size in pixels for each star.", "gd-rating-system")
            ),
            array(
                'label' => __("Font Icon Colors", "gd-rating-system").': '.__("Empty Stars", "gd-rating-system"),
                'attr' => 'font_color_empty',
                'type' => 'color',
                'value' => ''
            ),
            array(
                'label' => __("Font Icon Colors", "gd-rating-system").': '.__("Current Stars", "gd-rating-system"),
                'attr' => 'font_color_current',
                'type' => 'color',
                'value' => ''
            ),
            array(
                'label' => __("Font Icon Colors", "gd-rating-system").': '.__("Active Stars", "gd-rating-system"),
                'attr' => 'font_color_active',
                'type' => 'color',
                'value' => ''
            )
        );
    }

    private function _data_post_types() {
        $types = get_post_types(array(), 'objects');

        $data = array();

        foreach ($types as $cpt => $object) {
            $data[] = array('value' => $cpt, 'label' => $object->label);
        }

        return $data;
    }

    private function _data_post_statuses() {
        $statuses = get_post_stati(array(), 'objects');

        $data = array();

        foreach ($statuses as $status => $object) {
            $data[] = array('value' => $status, 'label' => $object->label);
        }

        return $data;
    }
}

global $_gdrts_addon_shortcake;
$_gdrts_addon_shortcake = new gdrts_addon_shortcake();

function gdrtsa_shortcake() {
    global $_gdrts_addon_shortcake;
    return $_gdrts_addon_shortcake;
}
