<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_shortcode_builder_list {
    public $shortcodes = array();

    public function __construct() {
    }

    public function init() {
        require_once(GDRTS_PATH.'core/admin/shared.php');

        if (gdrts_is_method_loaded('stars-rating')) {
            $this->register('gdrts_stars_rating', array(
                'label' => __("Single Item", "gd-rating-system"),
                'group' => __("Stars Rating", "gd-rating-system"),
                'item_icon' => 'dashicons-star-filled',
                'description' => __("Show rating block for Stars Rating method for the selected single rating item.", "gd-rating-system"),
                'attrs' => apply_filters('gdrts_shortcake_attrs_stars_rating',
                    array_merge($this->_atts_basic_attributes(),
                        $this->_atts_stars_rating_method(),
                        $this->_atts_css_classes()))
            ));

            $this->register('gdrts_stars_rating_auto', array(
                'label' => __("Auto Item", "gd-rating-system"),
                'group' => __("Stars Rating", "gd-rating-system"),
                'item_icon' => 'dashicons-star-filled',
                'description' => __("Show rating block for Stars Rating method for auto-detected single post or page rating item.", "gd-rating-system"),
                'attrs' => apply_filters('gdrts_shortcake_attrs_stars_rating_auto',
                    array_merge($this->_atts_stars_rating_method(),
                        $this->_atts_css_classes()))
            ));

            $this->register('gdrts_stars_rating_list', array(
                'label' => __("Items List", "gd-rating-system"),
                'group' => __("Stars Rating", "gd-rating-system"),
                'item_icon' => 'dashicons-star-filled',
                'description' => __("Show ratings list for the Stars Rating method.", "gd-rating-system"),
                'attrs' => apply_filters('gdrts_shortcake_attrs_stars_rating_list',
                    array_merge($this->_atts_list_attributes(),
                        $this->_atts_stars_rating_list_method(),
                        $this->_atts_css_classes()))
            ));
        }

        if (gdrts_is_method_loaded('like-this')) {
            $this->register('gdrts_like_this', array(
                'label' => __("Single Item", "gd-rating-system"),
                'group' => __("Like This", "gd-rating-system"),
                'item_icon' => 'dashicons-yes',
                'description' => __("Show rating block for Like This method for the selected single rating item.", "gd-rating-system"),
                'attrs' => apply_filters('gdrts_shortcake_attrs_like_this',
                    array_merge($this->_atts_basic_attributes(),
                        $this->_atts_like_this_method(),
                        $this->_atts_css_classes()))
            ));

            $this->register('gdrts_like_this_auto', array(
                'label' => __("Auto Item", "gd-rating-system"),
                'group' => __("Like This", "gd-rating-system"),
                'item_icon' => 'dashicons-yes',
                'description' => __("Show rating block for Like This method for auto-detected single post or page rating item.", "gd-rating-system"),
                'attrs' => apply_filters('gdrts_shortcake_attrs_like_this_auto',
                    array_merge($this->_atts_like_this_method(),
                        $this->_atts_css_classes()))
            ));

            $this->register('gdrts_like_this_list', array(
                'label' => __("Items List", "gd-rating-system"),
                'group' => __("Like This", "gd-rating-system"),
                'item_icon' => 'dashicons-yes',
                'description' => __("Show ratings list for the Like This method.", "gd-rating-system"),
                'attrs' => apply_filters('gdrts_shortcake_attrs_like_this_list',
                    array_merge($this->_atts_list_attributes(),
                        $this->_atts_like_this_list_method(),
                        $this->_atts_css_classes()))
            ));
        }

        $this->register('gdrts_rating_value', array(
            'label' => __("Single Item", "gd-rating-system"),
            'group' => __("Rating Value", "gd-rating-system"),
            'item_icon' => 'dashicons-flag',
            'description' => __("Show the rating related value for the selected single rating item.", "gd-rating-system"),
            'attrs' => apply_filters('gdrts_shortcake_attrs_rating_value',
                array_merge($this->_atts_basic_attributes(),
                    $this->_atts_method_attributes(),
                    $this->_atts_value_attributes(),
                    $this->_atts_tag_attributes()))
        ));

        $this->register('gdrts_rating_value_auto', array(
            'label' => __("Auto Item", "gd-rating-system"),
            'group' => __("Rating Value", "gd-rating-system"),
            'item_icon' => 'dashicons-flag',
            'description' => __("Show the rating related value for the current, auto-detected single post or page rating item.", "gd-rating-system"),
            'attrs' => apply_filters('gdrts_shortcake_attrs_rating_value_auto',
                array_merge($this->_atts_method_attributes(),
                    $this->_atts_value_attributes(),
                    $this->_atts_tag_attributes()))
        ));

        $this->register('gdrts_has_voted', array(
            'label' => __("Single Item", "gd-rating-system"),
            'group' => __("Condition - User Has Voted", "gd-rating-system"),
            'item_icon' => 'dashicons-yes-alt',
            'inner_content' => true,
            'description' => __("Wrap the content with the condition and show only if the user voted for the selected single rating item.", "gd-rating-system"),
            'attrs' => apply_filters('gdrts_shortcake_attrs_has_voted',
                array_merge($this->_atts_basic_attributes(),
                    $this->_atts_method_attributes(),
                    $this->_atts_user_attributes(),
                    $this->_atts_tag_attributes()))
        ));

        $this->register('gdrts_has_voted_auto', array(
            'label' => __("Auto Item", "gd-rating-system"),
            'group' => __("Condition - User Has Voted", "gd-rating-system"),
            'item_icon' => 'dashicons-yes-alt',
            'inner_content' => true,
            'description' => __("Wrap the content with the condition and show only if the user voted for auto-detected single post or page rating item.", "gd-rating-system"),
            'attrs' => apply_filters('gdrts_shortcake_attrs_has_voted_auto',
                array_merge($this->_atts_method_attributes(),
                    $this->_atts_user_attributes(),
                    $this->_atts_tag_attributes()))
        ));

        $this->register('gdrts_has_not_voted', array(
            'label' => __("Single Item", "gd-rating-system"),
            'group' => __("Condition - User Has Not Voted", "gd-rating-system"),
            'item_icon' => 'dashicons-dismiss',
            'inner_content' => true,
            'description' => __("Wrap the content with the condition and show only if the user has not voted for the selected single rating item.", "gd-rating-system"),
            'attrs' => apply_filters('gdrts_shortcake_attrs_has_not_voted',
                array_merge($this->_atts_basic_attributes(),
                    $this->_atts_method_attributes(),
                    $this->_atts_user_attributes(),
                    $this->_atts_tag_attributes()))
        ));

        $this->register('gdrts_has_not_voted_auto', array(
            'label' => __("Auto Item", "gd-rating-system"),
            'group' => __("Condition - User Has Not Voted", "gd-rating-system"),
            'item_icon' => 'dashicons-dismiss',
            'inner_content' => true,
            'description' => __("Wrap the content with the condition and show only if the user has not voted for auto-detected single post or page rating item.", "gd-rating-system"),
            'attrs' => apply_filters('gdrts_shortcake_attrs_has_not_voted_auto',
                array_merge($this->_atts_method_attributes(),
                    $this->_atts_user_attributes(),
                    $this->_atts_tag_attributes()))
        ));

        do_action('gdrts_shortcode_builder_register', $this);
    }

    public function register($shortcode, $args) {
        $args['shortcode'] = $shortcode;
        $this->shortcodes[$shortcode] = $args;
    }

    public function _atts_method_attributes() {
        return array(
            array(
                'label' => __("Rating Method", "gd-rating-system"),
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
                'description' => sprintf(__("There are many values that can be displayed. Most common are: %s and %s and they will work with every rating method. For more information consult the knowledge base.", "gd-rating-system"), "'rating'", "'votes'"),
                'value' => 'rating'
            )
        );
    }

    public function _atts_user_attributes() {
        return array(
            array(
                'label' => __("User ID", "gd-rating-system"),
                'attr' => 'user_id',
                'type' => 'number',
                'description' => __("If user ID is set to 0, or if it is not set at all, current logged-in user ID will be used.", "gd-rating-system"),
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
                'label' => __("Rating Item ID", "gd-rating-system"),
                'attr' => 'item_id',
                'type' => 'number',
                'value' => 0,
                'description' => __("If you now item ID for rating object, you can use it instead of Rating object entity, type and ID.", "gd-rating-system").' '.__("This ID must be integer higher than 0.", "gd-rating-system")
            ),
            array(
                'label' => __("Rating object Entity and Type", "gd-rating-system"),
                'attr' => 'type',
                'type' => 'select',
                'options' => gdrts_admin_shared::data_list_entity_name_types(),
                'value' => 'posts.post',
                'rule' => array(
                    'item_id' => 0
                )
            ),
            array(
                'label' => __("Rating object ID", "gd-rating-system"),
                'attr' => 'id',
                'type' => 'number',
                'value' => 0,
                'description' => __("Entity and Type with ID here descirbe rating object.", "gd-rating-system").' '.__("This ID must be integer higher than 0.", "gd-rating-system"),
                'rule' => array(
                    'item_id' => 0
                )
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
                'description' => __("For posts and custom post types.", "gd-rating-system"),
                'rule' => array(
                    'type' => 'posts.%%'
                )
            ),
            array(
                'label' => __("Authors ID's (comma separated)", "gd-rating-system"),
                'attr' => 'author',
                'type' => 'text',
                'description' => __("For posts, pages and custom post types.", "gd-rating-system"),
                'rule' => array(
                    'type' => 'posts.%%'
                )
            ),
            array(
                'label' => __("Post Statuses", "gd-rating-system"),
                'attr' => 'status',
                'type' => 'multi',
                'options' => $this->_data_post_statuses(),
                'description' => __("For posts, pages and custom post types. It is not advisable to include all post statuses. If nothing is selected, default post statuses will be auto applied.", "gd-rating-system"),
                'value' => '',
                'rule' => array(
                    'type' => 'posts.%%'
                )
            ),
            array(
                'label' => __("Post Types", "gd-rating-system"),
                'attr' => 'post_type',
                'type' => 'multi',
                'options' => $this->_data_post_types(),
                'description' => __("For comments. If nothing is selected, all post types will be taken into account.", "gd-rating-system"),
                'value' => '',
                'rule' => array(
                    'type' => 'comments.%%'
                )
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
                'type' => 'text',
                'value' => ''
            ),
            array(
                'label' => __("Shortcode wrapper CSS classes (space separated)", "gd-rating-system"),
                'attr' => 'class',
                'type' => 'text',
                'value' => ''
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
                'value' => 'false'
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
                'value' => '',
                'rule' => array(
                    'style_type' => 'image'
                )
            ),
            array(
                'label' => __("Size", "gd-rating-system"),
                'attr' => 'style_size',
                'type' => 'number',
                'description' => __("Size in pixels thumbs or text.", "gd-rating-system"),
                'value' => 30
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
                'value' => '',
                'rule' => array(
                    'style_type' => 'image'
                )
            ),
            array(
                'label' => __("Size", "gd-rating-system"),
                'attr' => 'style_size',
                'type' => 'number',
                'description' => __("Size in pixels thumbs or text.", "gd-rating-system"),
                'value' => 30
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
                'value' => 'exact'
            ),
            array(
                'label' => __("Disable Rating", "gd-rating-system"),
                'attr' => 'disable_rating',
                'type' => 'checkbox',
                'value' => 'false'
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
                'value' => '',
                'rule' => array(
                    'style_type' => 'image'
                )
            ),
            array(
                'label' => __("Size", "gd-rating-system"),
                'attr' => 'style_size',
                'type' => 'number',
                'description' => __("Size in pixels for each star.", "gd-rating-system"),
                'value' => 30
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
                'value' => 'star',
                'rule' => array(
                    'style_type' => 'image'
                )
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
            if (post_type_supports($cpt, 'comments')) {
                $data[$cpt] = $object->label;
            }
        }

        return $data;
    }

    private function _data_post_statuses() {
        $statuses = get_post_stati(array(), 'objects');

        $data = array();

        foreach ($statuses as $status => $object) {
            if ($status != 'auto-draft') {
                $data[$status] = ucfirst($object->label);
            }
        }

        return $data;
    }
}
