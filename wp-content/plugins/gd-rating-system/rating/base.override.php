<?php

if (!defined('ABSPATH')) { exit; }

if (!function_exists('gdrts_get_hashed_ip')) {
    function gdrts_get_hashed_ip($ip = null) {
        if (is_null($ip)) {
            $ip = d4p_visitor_ip();
        }

        $md5 = function_exists('hash') ? hash('md5', $ip) : md5($ip);

        return 'md5:'.strtolower($md5);
    }
}

if (!function_exists('gdrts_get_user_agent')) {
    function gdrts_get_user_agent() {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            return trim($_SERVER['HTTP_USER_AGENT']);
        }

        return '';
    }
}

if (!function_exists('gdrts_compact_number')) {
    function gdrts_compact_number($count, $decimals = 1) {
        if ($count > 999) {
            if ($count < 1000000) {
                $count = number_format($count / 1000, $decimals).'K';
            } else if ($count < 1000000000) {
                $count = number_format($count / 1000000, $decimals).'M';
            } else if ($count < 1000000000000) {
                $count = number_format($count / 1000000000, $decimals).'B';
            } else {
                $count = number_format($count / 1000000000000, $decimals).'T';
            }
        }

        return $count;
    }
}
