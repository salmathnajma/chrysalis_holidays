<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_method_admin_stars_rating extends gdrts_method_admin {
    protected $prefix = 'stars-rating';

    public function grid_ratings($list, $item) {
        if (isset($item->ratings[$this->prefix])) {
            $rating = $item->ratings[$this->prefix]['rating'];
            $votes = $item->ratings[$this->prefix]['votes'];

            $list[$this->prefix] = '<i aria-hidden="true" class="fa fa-star"></i> '.__("Stars Rating", "gd-rating-system").': <strong>'.$rating.'</strong>';
            $list[$this->prefix].= ' ('.sprintf(_n("%s vote", "%s votes", $votes, "gd-rating-system"), $votes).')';
        }

        return $list;
    }

    public function grid_vote_item($label, $item) {
        if ($item->method == 'stars-rating') {
            $label = '<i aria-hidden="true" class="fa fa-star"></i> '.$label;
        }

        return $label;
    }

    public function grid_vote($list, $item) {
        $render = '<i title="'.__("Status", "gd-rating-system").': '.$item->status.'" class="fa fa-'.($item->status == 'active' ? 'check-circle' : 'times-circle').' fa-fw"></i> ';
        $render.= '<strong>'.$item->vote.'</strong> '.__("out of", "gd-rating-system").' '.$item->max;

        return $render;
    }

    public function panels($panels) {
        $panels['method_stars_rating'] = array(
            'title' => __("Stars Rating", "gd-rating-system"), 'icon' => 'star', 'type' => 'method',
            'info' => __("Settings on this panel are for global control over Stars Rating integration. Each rating entity type can have own settings to override default ones.", "gd-rating-system"));

        return $panels;
    }

    public function settings($settings, $method = '') {
        $settings['method_stars_rating'] = array_merge(array(
            'msr_rules_info' => array('name' => __("Important", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement('', '', __("Settings Rules", "gd-rating-system"), gdrts_admin_shared::data_settings_shared_notice(), d4pSettingType::INFO)
            ))
        ), $this->_shared_settings());

        return $settings;
    }

    public function _shared_settings($prefix = '', $prekey = '', $method = '') {
        $real_prefix = empty($prefix) ? 'methods' : $prefix;
        $type = $this->get('style_type');

        return apply_filters('gdrts_shared_settings_stars-rating', array(
            'msr_rating' => array('name' => __("Rating", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement($real_prefix, $this->key('stars', $prekey), __("Stars", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get('stars'), 'array', gdrts_admin_shared::data_list_stars()),
                new d4pSettingElement($real_prefix, $this->key('resolution', $prekey), __("Resolution", "gd-rating-system"), __("Determines minimal part of the star user is allowed to rate with.", "gd-rating-system"), d4pSettingType::SELECT, $this->get('resolution'), 'array', gdrts_admin_shared::data_list_resolutions()),
                new d4pSettingElement('', '', '', '', d4pSettingType::HR),
                new d4pSettingElement($real_prefix, $this->key('vote', $prekey), __("Vote", "gd-rating-system"), __("Control how many times user can vote.", "gd-rating-system"), d4pSettingType::SELECT, $this->get('vote'), 'array', gdrts_admin_shared::data_list_vote()),
                new d4pSettingElement($real_prefix, $this->key('vote_limit', $prekey), __("Vote Limit", "gd-rating-system"), __("Limit number of attemps per item. If the Vote is set to Multiple votes, this will limit number of votes. If the Vote is set to Revote, this will limit number of revote attempts.", "gd-rating-system"), d4pSettingType::NUMBER, $this->get('vote_limit')),
                new d4pSettingElement('', '', '', '', d4pSettingType::HR),
                new d4pSettingElement($real_prefix, $this->key('revote_ajax', $prekey), __("Quick Revote", "gd-rating-system"), __("If enabled, rating block will be ready for revote (or another vote) immediately after voting (no need to refresh the page).", "gd-rating-system"), d4pSettingType::BOOLEAN, $this->get('revote_ajax')),
                new d4pSettingElement($real_prefix, $this->key('disable_rating', $prekey), __("Disable Rating", "gd-rating-system"), __("If enabled, rating block will not allow ratings.", "gd-rating-system"), d4pSettingType::BOOLEAN, $this->get('disable_rating'))
            )),
            'msr_allowed' => array('name' => __("Users allowed to vote", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement($real_prefix, $this->key('allow_author', $prekey), __("Author", "gd-rating-system"), __("This will be used only if author for the rating item can be determined (posts, comments).", "gd-rating-system"), d4pSettingType::BOOLEAN, $this->get('allow_author')),
                new d4pSettingElement($real_prefix, $this->key('allow_super_admin', $prekey), __("Super Admin", "gd-rating-system"), '', d4pSettingType::BOOLEAN, $this->get('allow_super_admin')),
                new d4pSettingElement($real_prefix, $this->key('allow_visitor', $prekey), __("Visitors", "gd-rating-system"), __("Visitors are not logged in.", "gd-rating-system"), d4pSettingType::BOOLEAN, $this->get('allow_visitor')),
                new d4pSettingElement($real_prefix, $this->key('allow_user_roles', $prekey), __("User Roles", "gd-rating-system"), '', d4pSettingType::CHECKBOXES, $this->get('allow_user_roles'), 'array', d4p_list_user_roles())
            )),
            'msr_style' => array('name' => __("Style", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement($real_prefix, $this->key('template', $prekey), __("Template", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get('template'), 'array', gdrts_admin_shared::data_list_templates('stars-rating', 'single')),
                new d4pSettingElement($real_prefix, $this->key('style_type', $prekey), __("Type", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get('style_type'), 'array', gdrts_admin_shared::data_list_style_type(), array('wrapper_class' => "gdrts-style-type-selection")),
                new d4pSettingElement($real_prefix, $this->key('style_image_name', $prekey), __("Image", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get('style_image_name'), 'array', gdrts_admin_shared::data_list_style_image_name(), array('wrapper_class' => 'gdrts-select-type gdrts-sel-type-image '.($type == 'image' ? 'gdrts-select-type-show' : ''))),
                new d4pSettingElement($real_prefix, $this->key('style_size', $prekey), __("Size", "gd-rating-system"), '', d4pSettingType::INTEGER, $this->get('style_size'), '', array(), array('label_unit' => "px"))
            )),
            'msr_color' => array('name' => __("Font Icons Colors", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement($real_prefix, $this->key('font_color_empty', $prekey), __("Empty Stars", "gd-rating-system"), '', d4pSettingType::COLOR, $this->get('font_color_empty')),
                new d4pSettingElement($real_prefix, $this->key('font_color_current', $prekey), __("Current Stars", "gd-rating-system"), '', d4pSettingType::COLOR, $this->get('font_color_current')),
                new d4pSettingElement($real_prefix, $this->key('font_color_active', $prekey), __("Active Stars", "gd-rating-system"), '', d4pSettingType::COLOR, $this->get('font_color_active'))
            )),
            'msr_extra' => array('name' => __("Extra", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement($real_prefix, $this->key('responsive', $prekey), __("Responsive", "gd-rating-system"), __("Plugin will attempt to detect available space for rating stars and make them smaller to fit.", "gd-rating-system"), d4pSettingType::BOOLEAN, $this->get('responsive')),
                new d4pSettingElement($real_prefix, $this->key('distribution', $prekey), __("Votes Distribution", "gd-rating-system"), __("For distribution display, it is best to use full star rating resolution. If you don't use full star resolution, normalized display will use ceil rounding of votes to full stars for display purposes only.", "gd-rating-system"), d4pSettingType::SELECT, $this->get('distribution'), 'array', gdrts_admin_shared::data_list_distributions()),
                new d4pSettingElement($real_prefix, $this->key('rating', $prekey), __("Rating for Display", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get('rating'), 'array', gdrts_admin_shared::data_list_rating_value()),
                new d4pSettingElement($real_prefix, $this->key('class', $prekey), __("CSS Class", "gd-rating-system"), __("One or more additional CSS classes to add to the rating block.", "gd-rating-system"), d4pSettingType::TEXT, $this->get('class'))
            )),
            'msr_labels' => array('name' => __("Labels", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement($real_prefix, $this->key('labels', $prekey), __("Labels", "gd-rating-system"), __("Each label corresponds to one star. If you use more stars than you have labels, plugin will generate labels automatically based on star number.", "gd-rating-system").' '.__("If you want to translate these in the multi language website environment, it is reccomended not to change these labels here.", "gd-rating-system"), d4pSettingType::EXPANDABLE_TEXT, $this->get('labels'), '', array(), array('label_button_add' => __("Add New Label", "gd-rating-system")))
            )),
            'msr_advanced' => array('name' => __("Advanced", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement($real_prefix, $this->key('cta', $prekey), __("Call to Action", "gd-rating-system"), __("This text is displayed before the rating block. Leave empty to use text set inside the template. This will be used only if the theme call renders the Call to Action block.", "gd-rating-system"), d4pSettingType::TEXT_HTML, $this->get('cta')),
                new d4pSettingElement($real_prefix, $this->key('alignment', $prekey), __("Alignment", "gd-rating-system"), __("This adds alignement class to the block, but will work only if you set block's inner wrapper element width. You need to adjust alignment styling through your theme, you can use alignment class added by the plugin to add styling.", "gd-rating-system"), d4pSettingType::SELECT, $this->get('alignment'), 'array', gdrts_admin_shared::data_list_align())
            )),
            'msr_votes' => array('name' => __("Format votes count", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement($real_prefix, $this->key('votes_count_compact_show', $prekey), __("Compact Display", "gd-rating-system"), __("High number of votes will be compacted by using K, M, B and T modifiers.", "gd-rating-system"), d4pSettingType::BOOLEAN, $this->get('votes_count_compact_show')),
                new d4pSettingElement($real_prefix, $this->key('votes_count_compact_decimals', $prekey), __("Number of decimals", "gd-rating-system"), __("If compact display is enabled, select number of decimals to round the counts.", "gd-rating-system"), d4pSettingType::SELECT, $this->get('votes_count_compact_decimals'), 'array', array('0' => '0', '1' => '1', '2' => '2'))
            ))
        ), $type, $real_prefix, $prefix, $prekey);
    }
}

global $_gdrts_method_admin_stars_rating;
$_gdrts_method_admin_stars_rating = new gdrts_method_admin_stars_rating();

/** @return gdrts_method_admin_stars_rating */
function gdrtsa_admin_stars_rating() {
    global $_gdrts_method_admin_stars_rating;
    return $_gdrts_method_admin_stars_rating;
}
