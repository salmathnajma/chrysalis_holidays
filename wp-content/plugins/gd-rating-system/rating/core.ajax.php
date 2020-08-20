<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core_ajax {
    public function __construct() {
        add_action('wp_ajax_gdrts_live_handler', array($this, 'handler'));
        add_action('wp_ajax_nopriv_gdrts_live_handler', array($this, 'handler'));

        add_action('gdrts_ajax_request_error', array($this, 'process_error'), 10, 7);
    }

    public function handler() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            do_action('gdrts_ajax_request_error',
                'request_invalid_method',
                null,
                null,
                __("Invalid Request method.", "gd-rating-system"),
                '',
                405);
        }

        if (!isset($_REQUEST['req'])) {
            do_action('gdrts_ajax_request_error',
                'request_malformed',
                null,
                null,
                __("Malformed Request.", "gd-rating-system"));
        }

        $request = json_decode(wp_unslash($_REQUEST['req']));

        if (is_null($request)) {
            do_action('gdrts_ajax_request_error',
                'request_not_json',
                $_REQUEST['req'],
                null,
                __("Malformed Request.", "gd-rating-system"));
        }

        $process = apply_filters('gdrts_ajax_live_handler', false, $request);

        if ($process === false && isset($request->todo) && isset($request->uid)) {
            do_action('gdrts_ajax_process_request_start', $request);

            switch ($request->todo) {
                case 'vote':
                    $this->vote($request);
                    break;
                default:
                    do_action('gdrts_ajax_request_error',
                        'request_invalid',
                        $request,
                        null,
                        __("Invalid Request.", "gd-rating-system"),
                        $request->uid);
                    break;
            }
        } else {
            do_action('gdrts_ajax_request_error',
                'request_incomplete',
                $request,
                null,
                __("Incomplete Request.", "gd-rating-system"));
        }
    }

    public function vote($request) {
        gdrts_single()->do_suppress_filters();

        $break = apply_filters('gdrts_ajax_live_handler_vote_preprocess', false, $request);

        if ($break !== false) {
            $defaults = array(
                'code' => 'request_invalid',
                'message' => __("Invalid Request.", "gd-rating-system"),
            );

            $args = $break === true ? array() : $break;

            $args = wp_parse_args($args, $defaults);

            do_action('gdrts_ajax_request_error',
                $args['code'],
                $request,
                null,
                $args['message'],
                $request->uid);
        }

        $check_nonce = apply_filters('gdrts_ajax_check_nonce', gdrts_settings()->get('use_nonce'));
        $check_throttle = apply_filters('gdrts_ajax_check_throttle', gdrts_settings()->get('throttle_active'));

        $item_id = absint($request->item);
        $item = gdrts_get_rating_item_by_id($item_id);
        $user = new gdrts_core_user();

        if ($check_throttle) {
            $throttle = gdrts_settings()->get('throttle_period');
            $timestamp = $user->get_last_vote_timestamp();

            $diff = absint(time() - $timestamp);

            if ($throttle >= $diff) {
                do_action('gdrts_ajax_request_error',
                    'request_throttle',
                    $request,
                    null,
                    __("Voting rate restricted.", "gd-rating-system"),
                    $request->uid,
                    429,
                    array(
                        'diff' => $diff,
                        'user' => $user
                    ));
            }
        }

        if ($check_nonce) {
            d4p_check_ajax_referer($item->nonce_key(), $request->nonce);
        }

        if ($item->error) {
            do_action('gdrts_ajax_request_error',
                'invalid_item',
                $request,
                $item,
                __("Invalid item for rating.", "gd-rating-system"),
                $request->uid);
        }

        if (gdrts_settings()->get('maintenance', 'core') || gdrts_settings()->get('voting_disabled', 'core')) {
            do_action('gdrts_ajax_request_error',
                'in_maintenance',
                $request,
                $item,
                __("Voting is currently disabled.", "gd-rating-system"),
                $request->uid);
        }

        if (!isset($request->series)) {
            $request->series = null;
        }

        $user->load_log($item_id, $request->method, $request->series);
        $request->render->series = $request->series;

        $completed = false;
        if (isset($request->meta) && isset($request->method) && is_string($request->method)) {
            switch ($request->method) {
                case 'stars-rating':
                    $completed = gdrtsm_stars_rating()->vote($request->meta, $item, $user, $request->render);
                    break;
                case 'like-this':
                    $completed = gdrtsm_like_this()->vote($request->meta, $item, $user, $request->render);
                    break;
                default:
                    $completed = apply_filters('gdrts_ajax_vote_'.$request->method, false, $request->meta, $item, $user, $request->render);
                    break;
            }
        }

        if (is_wp_error($completed)) {
            do_action('gdrts_ajax_request_error',
                'general',
                $request,
                $completed,
                $completed->get_error_message(),
                $request->uid);
        } else if ($completed !== false) {
            $request->render->args->echo = false;

            gdrts_cache()->clear();
            gdrts_single()->do_loop_save();

            $render = gdrts_single()->render((array)$request->render->args, (array)$request->render->method);

            $result = array(
                'status' => 'ok',
                'render' => $render,
                'uid' => $request->uid
            );

            $this->respond(json_encode($result));
        } else {
            do_action('gdrts_ajax_request_error',
                'invalid_method',
                $request,
                null,
                __("Invalid rating method processing.", "gd-rating-system"),
                $request->uid);
        }
    }

    public function process_error($error, $request = null, $item = null, $message = '', $uid = '', $code = 400, $data = null) {
        if (empty($message)) {
            $message = __("Unspecified Rating Problem.", "gd-rating-system");
        }

        do_action('gdrts_ajax_live_handler_error', $error, $message, $code, array(
            'request' => $request,
            'item' => $item,
            'uid' => $uid,
            'data' => $data
        ));

        $this->error($message, $uid, $code);
    }

    public function error($message, $uid = '', $code = 400) {
        $result = array(
            'status' => 'error', 
            'message' => $message
        );

        if (!empty($uid)) {
            $result['uid'] = $uid;
        }

        $this->respond(json_encode($result), $code);
    }

    public function respond($response, $code = 200) {
        status_header($code);

        if (gdrts_settings()->get('ajax_header_no_cache')) {
            nocache_headers();
        }

        header('Content-Type: application/json');

        die($response);
    }
}

global $_gdrts_ajax;
$_gdrts_ajax = new gdrts_core_ajax();

function gdrts_ajax() {
    global $_gdrts_ajax;
    return $_gdrts_ajax;
}
