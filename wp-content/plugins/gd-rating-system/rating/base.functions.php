<?php

if (!defined('ABSPATH')) { exit; }

function gdrts_render_rating($args = array(), $method = array()) {
    return gdrts_single()->render((array)$args, (array)$method);
}

function gdrts_render_ratings_list($args = array(), $method = array()) {
    return gdrts_list()->render((array)$args, (array)$method);
}

function gdrts_query_ratings($method = 'stars-rating', $args = array()) {
    return gdrts_query()->run($method, $args);
}

/** @return bool|gdrts_rating_item */
function gdrts_get_rating_item($args) {
    $defaults = array(
        'type' => null,
        'entity' => null, 
        'name' => null, 
        'item_id' => null,
        'id' => null
    );

    $atts = shortcode_atts($defaults, $args);

    if (!is_null($atts['type']) && is_null($atts['entity']) && is_null($atts['name'])) {
        $split = explode('.', $atts['type'], 2);

        if (count($split) == 2) {
            $atts['entity'] = $split[0];
            $atts['name'] = $split[1];
        }
    }

    return gdrts_rating_item::get_instance($atts['item_id'], $atts['entity'], $atts['name'], $atts['id']);
}

/** @return bool|gdrts_rating_item */
function gdrts_get_rating_item_by_id($item_id) {
    return gdrts_rating_item::get_instance($item_id);
}

/** @return bool|gdrts_rating_item */
function gdrts_get_rating_item_by_post($post = null) {
    if (is_null($post) || (is_array($post) && empty($post))) {
        global $post;
    }

    if (is_null($post)) {
        return false;
    }

    return gdrts_rating_item::get_instance(null, 'posts', $post->post_type, $post->ID);
}

/** @return bool|gdrts_core_user_rating|WP_Error */
function gdrts_get_user_rating($user_id) {
    if ($user_id < 1) {
        return new WP_Error('user-missing', __("User ID is required, and it has to be for valid registered user.", "gd-rating-system"));
    } else {
        return gdrts_core_user_rating::get_instance($user_id);
    }
}

function gdrts_get_current_user_rating() {
    if (is_user_logged_in()) {
        return gdrts_get_user_rating(get_current_user_id());
    } else {
        return gdrts_core_visitor_rating::get_instance();
    }
}

function gdrts_read_cookies() {
    $key = gdrts()->cookie_key();
    $raw = isset($_COOKIE[$key]) ? $_COOKIE[$key] : '';

    $cookie = array();

    if ($raw != '') {
        $raw = stripslashes($raw);

       $cookie = json_decode($raw);

        if (!empty($cookie)) {
            $cookie = array_map('intval', $cookie);
            $cookie = array_filter($cookie);
        }
    }

    return $cookie;
}

function gdrts_current_method_name() {
    return gdrts_loop()->method_name();
}

function gdrts_current_template() {
    if (is_null(gdrts()->template)) {
        return new WP_Error('gdrts-template', __("Outside of the rating template rendering.", "gd-rating-system"));
    }

    return gdrts()->template;
}

function gdrts_is_inside_widget() {
    if (is_null(gdrts()->widget)) {
        return false;
    }

    return gdrts()->widget;
}

function gdrts_is_inside_shortcode() {
    if (is_null(gdrts()->shortcode)) {
        return false;
    }

    return gdrts()->shortcode;
}

function gdrts_is_addon_loaded($name) {
    return gdrts_is_addon_valid($name) && in_array('addon_'.$name, gdrts()->loaded);
}

function gdrts_is_addon_valid($method) {
    return isset(gdrts()->addons[$method]);
}

function gdrts_is_method_loaded($name) {
    return gdrts_is_method_valid($name) && in_array('method_'.$name, gdrts()->loaded);
}

function gdrts_is_method_valid($method) {
    return isset(gdrts()->methods[$method]);
}

function gdrts_is_method_for_review($method) {
    return gdrts()->get_method_prop($method, 'review', false);
}

function gdrts_method_has_series($method) {
    return gdrts()->get_method_prop($method, 'has_series', false);
}

function gdrts_method_allows_multiple_votes($method) {
    return gdrts()->get_method_prop($method, 'allow_multiple_votes', false);
}

function gdrts_is_template_type_valid($type) {
    return in_array($type, array('single', 'list'));
}

function gdrts_register_entity($entity, $label, $types = array(), $icon = 'ticket') {
    gdrts()->register_entity($entity, $label, $types, $icon);
}

function gdrts_register_type($entity, $name, $label) {
    gdrts()->register_type($entity, $name, $label);
}

function gdrts_register_font($name, $object) {
    if (!isset(gdrts()->fonts[$name])) {
        gdrts()->fonts[$name] = $object;
    }
}

function gdrts_load_object_data($entity, $name, $id) {
    $data = apply_filters('gdrts_object_data_'.$entity.'_'.$name, null, $id);

    if (is_null($data)) {
        switch ($entity) {
            case 'posts':
                if (gdrts_has_bbpress() && $name == bbp_get_reply_post_type()) {
                    $data = new gdrts_item_post_bbp_reply($entity, $name, $id);
                } else {
                    $data = new gdrts_item_post($entity, $name, $id);
                }
                break;
            case 'terms':
                $data = new gdrts_item_term($entity, $name, $id);
                break;
            case 'comments':
                $data = new gdrts_item_comment($entity, $name, $id);
                break;
            case 'users':
                $data = new gdrts_item_user($entity, $name, $id);
                break;
            default:
            case 'custom':
                $data = new gdrts_item_custom($entity, $name, $id);
                break;
        }
    }

    return $data;
}

function gdrts_print_debug_info($value) {
    $render = $value;

    if (is_array($value) || is_object($value)) {
        $render = '';

        foreach ($value as $key => $val) {
            $render.= $key.' => '.gdrts_print_debug_info($val).', ';
        }

        if (!empty($render)) {
            $render = substr($render, 0, strlen($render) - 2);
        }
    } else if (is_bool($value)) {
        $render = $value ? 'TRUE' : 'FALSE';
    } else if (is_null($value)) {
        $render = 'NULL';
    } else if (is_string($value)) {
        $render = "'".$value."'";
    }

    return $render;
}

function gdrts_get_method_object($method) {
    if (gdrts_is_method_loaded($method)) {
        switch ($method) {
            case 'stars-rating':
                return gdrtsm_stars_rating();
            case 'like-this':
                return gdrtsm_like_this();
            default:
                return apply_filters('gdrts_get_method_object_'.$method, null);
        }
    }

    return null;
}

function gdrts_get_method_label($method) {
    if (strpos($method, '::') !== false) {
        $split = explode('::', $method);
        $method = $split[0];
    }

    if (gdrts_is_method_loaded($method)) {
        return gdrts()->methods[$method]['label'];
    } else {
        return $method;
    }
}

function gdrts_get_method_series_label($method, $series = null) {
    if (is_null($series)) {
        if (strpos($method, '::') !== false) {
            $split = explode('::', $method);
            $method = $split[0];
            $series = $split[1];
        } else {
            $series = '';
        }
    }

    if (gdrts_is_method_loaded($method) && gdrts_method_has_series($method)) {
        return gdrts_get_method_object($method)->get_series_label($series);
    } else {
        return '';
    }
}

function gdrts_list_all_methods($include_series = false) {
    $items = array();

    foreach (gdrts()->methods as $method => $obj) {
        if (gdrts_is_method_loaded($method)) {
            $items[$method] = $obj['label'];

            if ($include_series) {
                $obj = gdrts_get_method_object($method);

                if ($obj->has_series()) {
                    $list = $obj->all_series_list();

                    foreach ($list as $key => $label) {
                        $items[$method.'::'.$key] = ' &boxur; '.$label;
                    }
                }
            }
        }
    }

    return $items;
}

function gdrts_list_all_entities() {
    $items = array();

    foreach (gdrts()->get_entities() as $entity => $obj) {
        $rule = array(
            'title' => $obj['label'],
            'values' => array(
                $entity => sprintf(__("All %s Types", "gd-rating-system"), $obj['label'])
            )
        );

        foreach ($obj['types'] as $name => $label) {
            $rule['values'][$entity.'.'.$name] = $label;
        }

        $items[] = $rule;
    }

    return $items;
}

function gdrts_get_visitor_ip($hashed = null) {
    $ip = d4p_visitor_ip();
    $is = is_null($hashed) ? gdrts_using_hashed_ip() : false;

    if ($is) {
        $ip = gdrts_get_hashed_ip($ip);
    }

    return $ip;
}

function gdrts_get_default_post_statuses() {
    return apply_filters('gdrts_default_post_statuses', array('publish', 'closed', 'inherit'));
}

/** @global WP_Query $wp_query
  * @param WP_Query $wpq */
function gdrts_wp_query_post_index($wpq = null) {
    if (is_null($wpq)) {
        global $wp_query;
        $wpq = $wp_query;
    }

    $page = $wpq->get('paged') == 0 ? 1 : $wpq->get('paged');
    $index = $wpq->current_post + ($page - 1) * $wpq->get('posts_per_page');

    if (strtoupper($wpq->get('order')) == 'DESC') {
        $index++;
    } else {
        $index = $wpq->found_posts - $index;
    }

    return $index;
}

function gdrts_get_summary_rating($item, $method, $series = '') {
    return apply_filters('gdrts_summary_rating_data_for_'.$method, array(), $series, $item);
}

function gdrts_get_formatted_date_from_mysql_date($format, $mysql_date, $gmt = false) {
    if ($gmt) {
        $_date = date_create_immutable_from_format('Y-m-d H:i:s', $mysql_date, new DateTimeZone('UTC'));

        if (!$_date) {
            return $mysql_date;
        }

        return $_date->format($format);
    } else {
        return mysql2date($format, $mysql_date);
    }
}

function _gdrts_helper_parse_latest($value) {
    $date = array('from' => '', 'to' => '');

    switch ($value) {
        default:
        case 'day':
            $date['from'] = d4p_mysql_date(strtotime('yesterday'));
            $date['to'] = d4p_mysql_date(strtotime('today'));
            break;
        case 'week':
            $date['from'] = d4p_mysql_date(strtotime('today') - 7 * DAY_IN_SECONDS);
            $date['to'] = d4p_mysql_date(strtotime('today'));
            break;
        case 'two-weeks':
            $date['from'] = d4p_mysql_date(strtotime('today') - 14 * DAY_IN_SECONDS);
            $date['to'] = d4p_mysql_date(strtotime('today'));
            break;
        case 'month':
            $date['from'] = d4p_mysql_date(strtotime('today') - 30 * DAY_IN_SECONDS);
            $date['to'] = d4p_mysql_date(strtotime('today'));
            break;
        case 'year':
            $date['from'] = d4p_mysql_date(strtotime('today') - 365 * DAY_IN_SECONDS);
            $date['to'] = d4p_mysql_date(strtotime('today'));
            break;
        case 'last-month':
            $date['from'] = date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m") - 1, 1));
            $date['to'] = date("Y-m-d 00:00:00", mktime(0, 0, 0, date("m"), 1));
            break;
        case 'last-year':
            $date['from'] = date("Y-m-d 00:00:00", mktime(0, 0, 0, 1, 1, date("Y") - 1));
            $date['to'] = date("Y-m-d 00:00:00", mktime(0, 0, 0, 1, 3, date("Y")));
            break;
        case 'current-month':
            $date['from'] = date('Y-m-01 00:00:00');
            $date['to'] = date('Y-m-t 23:59:59');
            break;
        case 'current-year':
            $date['from'] = date('Y-01-01 00:00:00');
            $date['to'] = date('Y-12-31 23:59:59');
            break;
    }

    return $date;
}
