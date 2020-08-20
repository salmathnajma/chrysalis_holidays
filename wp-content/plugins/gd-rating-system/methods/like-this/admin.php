<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_method_admin_like_this extends gdrts_method_admin {
    protected $prefix = 'like-this';

    public function grid_ratings($list, $item) {
        if (isset($item->ratings[$this->prefix])) {
            $rating = '+'.$item->ratings[$this->prefix]['rating'];

            $list[$this->prefix] = '<i aria-hidden="true" class="fa fa-thumbs-o-up"></i> '.__("Like This", "gd-rating-system").': <strong>'.$rating.'</strong>';
        }

        return $list;
    }

    public function grid_vote_item($label, $item) {
        if ($item->method == 'like-this') {
            $label = '<i aria-hidden="true" class="fa fa-thumbs-o-up"></i> '.$label;
        }

        return $label;
    }

    public function grid_vote($list, $item) {
        $vote = $item->vote > 0 ? __("Like", "gd-rating-system") : __("Unlike", "gd-rating-system");

        $render = '<i title="'.__("Status", "gd-rating-system").': '.$item->status.'" class="fa fa-'.($item->status == 'active' ? 'check-circle' : 'times-circle').' fa-fw"></i> ';
        $render.= '<strong>'.$vote.'</strong> ';

        return $render;
    }

    public function panels($panels) {
        $panels['method_like_this'] = array(
            'title' => __("Like This", "gd-rating-system"), 'icon' => 'thumbs-o-up', 'type' => 'method',
            'info' => __("Settings on this panel are for global control over Like This integration. Each rating entity type can have own settings to override default ones.", "gd-rating-system"));

        return $panels;
    }

    public function settings($settings, $method = '') {
        $settings['method_like_this'] = array_merge(array(
            'mlt_rules_info' => array('name' => __("Important", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement('', '', __("Settings Rules", "gd-rating-system"), gdrts_admin_shared::data_settings_shared_notice(), d4pSettingType::INFO)
            ))
        ), $this->_shared_settings());

        return $settings;
    }

    public function _shared_settings($prefix = '', $prekey = '', $method = '') {
        $real_prefix = empty($prefix) ? 'methods' : $prefix;
        $type = $this->get('style_type');

        return apply_filters('gdrts_shared_settings_like-this', array(
            'mlt_rating' => array('name' => __("Rating", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement($real_prefix, $this->key('remove_vote', $prekey), __("Remove Vote", "gd-rating-system"), __("This will allow you to remove your Like vote.", "gd-rating-system"), d4pSettingType::BOOLEAN, $this->get('remove_vote')),
                new d4pSettingElement('', '', '', '', d4pSettingType::HR),
                new d4pSettingElement($real_prefix, $this->key('revote_ajax', $prekey), __("Quick Revote", "gd-rating-system"), __("If enabled, rating block will be ready for revote (or another vote) immediately after voting (no need to refresh the page).", "gd-rating-system"), d4pSettingType::BOOLEAN, $this->get('revote_ajax')),
                new d4pSettingElement($real_prefix, $this->key('disable_rating', $prekey), __("Disable Rating", "gd-rating-system"), __("If enabled, rating block will not allow ratings.", "gd-rating-system"), d4pSettingType::BOOLEAN, $this->get('disable_rating'))
            )),
            'mlt_allowed' => array('name' => __("Users allowed to vote", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement($real_prefix, $this->key('allow_author', $prekey), __("Author", "gd-rating-system"), __("This will be used only if author for the rating item can be determined (posts, comments).", "gd-rating-system"), d4pSettingType::BOOLEAN, $this->get('allow_author')),
                new d4pSettingElement($real_prefix, $this->key('allow_super_admin', $prekey), __("Super Admin", "gd-rating-system"), '', d4pSettingType::BOOLEAN, $this->get('allow_super_admin')),
                new d4pSettingElement($real_prefix, $this->key('allow_visitor', $prekey), __("Visitors", "gd-rating-system"), __("Visitors are not logged in.", "gd-rating-system"), d4pSettingType::BOOLEAN, $this->get('allow_visitor')),
                new d4pSettingElement($real_prefix, $this->key('allow_user_roles', $prekey), __("User Roles", "gd-rating-system"), '', d4pSettingType::CHECKBOXES, $this->get('allow_user_roles'), 'array', d4p_list_user_roles())
            )),
            'mlt_style' => array('name' => __("Style", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement($real_prefix, $this->key('template', $prekey), __("Template", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get('template'), 'array', gdrts_admin_shared::data_list_templates('like-this', 'single')),
                new d4pSettingElement($real_prefix, $this->key('style_theme', $prekey), __("Theme", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get('style_theme'), 'array', gdrts_admin_shared::data_list_likes_style_theme()),
                new d4pSettingElement($real_prefix, $this->key('style_type', $prekey), __("Type", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get('style_type'), 'array', gdrts_admin_shared::data_list_likes_style_type(), array('wrapper_class' => "gdrts-style-type-selection")),
                new d4pSettingElement($real_prefix, $this->key('style_image_name', $prekey), __("Image", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get('style_image_name'), 'array', gdrts_admin_shared::data_list_likes_style_image_name(), array('wrapper_class' => 'gdrts-select-type gdrts-sel-type-image '.($type == 'image' ? 'gdrts-select-type-show' : ''))),
                new d4pSettingElement($real_prefix, $this->key('style_size', $prekey), __("Size", "gd-rating-system"), '', d4pSettingType::INTEGER, $this->get('style_size'), '', array(), array('label_unit' => "px"))
            )),
            'mlt_extra' => array('name' => __("Extra", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement($real_prefix, $this->key('class', $prekey), __("CSS Class", "gd-rating-system"), __("One or more additional CSS classes to add to the rating block.", "gd-rating-system"), d4pSettingType::TEXT, $this->get('class'))
            )),
            'mlt_labels' => array('name' => __("Labels", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement($real_prefix, $this->key('labels_like', $prekey), __("Like", "gd-rating-system"), '', d4pSettingType::TEXT, $this->get('labels_like')),
                new d4pSettingElement($real_prefix, $this->key('labels_liked', $prekey), __("Liked", "gd-rating-system"), '', d4pSettingType::TEXT, $this->get('labels_liked')),
                new d4pSettingElement($real_prefix, $this->key('labels_unlike', $prekey), __("Unlike", "gd-rating-system"), '', d4pSettingType::TEXT, $this->get('labels_unlike'))
            )),
            'mlt_advanced' => array('name' => __("Advanced", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement($real_prefix, $this->key('cta', $prekey), __("Call to Action", "gd-rating-system"), __("This text is displayed before the rating block. Leave empty to use text set inside the template. This will be used only if the theme call renders the Call to Action block.", "gd-rating-system"), d4pSettingType::TEXT_HTML, $this->get('cta')),
                new d4pSettingElement($real_prefix, $this->key('alignment', $prekey), __("Alignment", "gd-rating-system"), __("This adds alignement class to the block, but will work only if you set block's inner wrapper element width. You need to adjust alignment styling through your theme, you can use alignment class added by the plugin to add styling.", "gd-rating-system"), d4pSettingType::SELECT, $this->get('alignment'), 'array', gdrts_admin_shared::data_list_align())
            )),
            'mlt_votes' => array('name' => __("Format votes count", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement($real_prefix, $this->key('votes_count_compact_show', $prekey), __("Compact Display", "gd-rating-system"), __("High number of votes will be compacted by using K, M, B and T modifiers.", "gd-rating-system"), d4pSettingType::BOOLEAN, $this->get('votes_count_compact_show')),
                new d4pSettingElement($real_prefix, $this->key('votes_count_compact_decimals', $prekey), __("Number of decimals", "gd-rating-system"), __("If compact display is enabled, select number of decimals to round the counts.", "gd-rating-system"), d4pSettingType::SELECT, $this->get('votes_count_compact_decimals'), 'array', array('0' => '0', '1' => '1', '2' => '2'))
            ))
        ), $type, $real_prefix, $prefix, $prekey);
    }
}

global $_gdrts_method_admin_like_this;
$_gdrts_method_admin_like_this = new gdrts_method_admin_like_this();

/** @return gdrts_method_admin_like_this */
function gdrtsa_admin_like_this() {
    global $_gdrts_method_admin_like_this;
    return $_gdrts_method_admin_like_this;
}
