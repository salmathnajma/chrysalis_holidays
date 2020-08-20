<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_admin_privacy {
    public function __construct() {
        add_action('admin_init', array($this, 'admin_init'));
    }

    public function admin_init() {
        if (function_exists('wp_add_privacy_policy_content')) {
            wp_add_privacy_policy_content('GD Rating System', $this->_privacy_policy_content());
        }
    }

    private function _privacy_policy_content() {
        $content = '';

        if (!gdrts_settings()->get('log_vote_ip_hashed')) {
            $content.= '<h2>'.__("What data GD Rating System collects", "gd-rating-system").'</h2>';
            $content.= '<p class="privacy-policy-tutorial">'.__("Each vote that is logged in the database contains the IP address from where the vote originated. The logged IP is used to prevent duplicated votes for anonymous visitors.", "gd-rating-system").'</p>';
        }

        $content.= '<h2>'.__("GD Rating System uses cookies", "gd-rating-system").'</h2>';
        $content.= '<p class="privacy-policy-tutorial">'.__("The plugin creates the cookie that contains log references to all the user votes. This cookie is used as a measure to prevent duplicated votes for anonymous visitors.", "gd-rating-system").'</p>';

        return apply_filters('gdrts_privacy_policy_content', $content);
    }
}
