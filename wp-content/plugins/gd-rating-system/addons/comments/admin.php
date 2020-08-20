<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_addon_admin_comments extends gdrts_addon_admin {
	protected $prefix = 'comments';

    public function panels($panels) {
        $panels['addon_comments'] = array(
            'title' => __("Ratings in Comments", "gd-rating-system"), 'icon' => 'comments-o', 'type' => 'addon',
            'info' => __("Settings on this panel are for control over comments integration.", "gd-rating-system"));

        return $panels;
    }

    public function settings($settings, $method = '') {
        $settings['addon_comments'] = array('ac_embed' => array('name' => __("Auto Embed", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement('addons', $this->key('comments_auto_embed_location'), __("Location", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get('comments_auto_embed_location'), 'array', $this->get_list_embed_locations()),
                new d4pSettingElement('addons', $this->key('comments_auto_embed_method'), __("Method", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get('comments_auto_embed_method'), 'array', gdrts_admin_shared::data_list_embed_methods('-')),
                new d4pSettingElement('addons', $this->key('comments_auto_embed_priority'), __("Priority", "gd-rating-system"), __("Use lower values to run the filter earlier, or higher to run it later. Value 10 is default priority.", "gd-rating-system"), d4pSettingType::INTEGER, $this->get('comments_auto_embed_priority'))
            )
        ));

        return $settings;
    }

    public function get_list_embed_locations() {
        return array(
            'top' => __("Top", "gd-rating-system"),
            'bottom' => __("Bottom", "gd-rating-system"),
            'both' => __("Top and Bottom", "gd-rating-system"),
            'hide' => __("Hide", "gd-rating-system")
        );
    }
}

global $_gdrts_addon_admin_comments;
$_gdrts_addon_admin_comments = new gdrts_addon_admin_comments();

/** @return gdrts_addon_admin_comments */
function gdrtsa_admin_comments() {
    global $_gdrts_addon_admin_comments;
    return $_gdrts_addon_admin_comments;
}
