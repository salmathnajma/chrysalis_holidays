<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_addon_admin_feeds extends gdrts_addon_admin {
	protected $prefix = 'feeds';
    public $_geo_ip = false;

    public function panels($panels) {
        $panels['addon_feeds'] = array(
            'title' => __("Feeds", "gd-rating-system"), 'icon' => 'rss-square', 'type' => 'addon',
            'info' => __("Settings on this panel are for support of various feed types and ratings embedding.", "gd-rating-system"));

        return $panels;
    }

    public function settings($settings, $method = '') {
        $settings['addon_feeds'] = array(
            'afd_rss' => array('name' => __("RSS", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement('addons', $this->key('rss'), __("Active", "gd-rating-system"), '', d4pSettingType::BOOLEAN, $this->get('rss')),
                new d4pSettingElement('addons', $this->key('rss_hide'), __("Hide Rating Block", "gd-rating-system"), '', d4pSettingType::BOOLEAN, $this->get('rss_hide'))
            )),
            'afd_amp' => array('name' => __("AMP: Accelerated Mobile Pages", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement('addons', $this->key('amp'), __("Active", "gd-rating-system"), '', d4pSettingType::BOOLEAN, $this->get('amp')),
                new d4pSettingElement('addons', $this->key('amp_hide'), __("Hide Rating Block", "gd-rating-system"), '', d4pSettingType::BOOLEAN, $this->get('amp_hide'))
            )),
            'afd_fia' => array('name' => __("FIA: Facebook Instant Articles", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement('addons', $this->key('fia'), __("Active", "gd-rating-system"), '', d4pSettingType::BOOLEAN, $this->get('fia')),
                new d4pSettingElement('addons', $this->key('fia_hide'), __("Hide Rating Block", "gd-rating-system"), '', d4pSettingType::BOOLEAN, $this->get('fia_hide'))
            )),
            'afd_anf' => array('name' => __("ANF: Apple News Format", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement('addons', $this->key('anf'), __("Active", "gd-rating-system"), '', d4pSettingType::BOOLEAN, $this->get('anf')),
                new d4pSettingElement('addons', $this->key('anf_hide'), __("Hide Rating Block", "gd-rating-system"), '', d4pSettingType::BOOLEAN, $this->get('anf_hide'))
            ))
        );

        return $settings;
    }
}

global $_gdrts_addon_admin_feeds;
$_gdrts_addon_admin_feeds = new gdrts_addon_admin_feeds();

/** @return gdrts_addon_admin_feeds */
function gdrtsa_admin_feeds() {
    global $_gdrts_addon_admin_feeds;
    return $_gdrts_addon_admin_feeds;
}
