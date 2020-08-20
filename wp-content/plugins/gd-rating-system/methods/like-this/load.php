<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_method_like_this extends gdrts_method {
    public $prefix = 'like-this';

    public function __construct() {
        require_once(GDRTS_PATH.'methods/like-this/user.php');
        require_once(GDRTS_PATH.'methods/like-this/render.php');

        parent::__construct();
    }

    public function trigger_enqueue() {
        parent::trigger_enqueue();

        if (!gdrts_plugin()->load_full_css()) {
            wp_enqueue_style('gdrts-methods-like-this');
        }

        if (!gdrts_plugin()->load_full_js()) {
            wp_enqueue_script('gdrts-methods-like-this');
        }
    }

    public function load_admin() {
        require_once(GDRTS_PATH.'methods/like-this/admin.php');
    }

    public function load_rest_api() {
        require_once(GDRTS_PATH.'methods/like-this/restapi.php');
    }

    public function _get_args_single($method = array()) {
        $_type_name = 'style_'.$this->get_rule('style_type').'_name';

        $defaults = array(
            'disable_rating' => $this->get_rule('disable_rating'),
            'allow_super_admin' => $this->get_rule('allow_super_admin'),
            'allow_user_roles' => $this->get_rule('allow_user_roles'),
            'allow_visitor' => $this->get_rule('allow_visitor'),
            'allow_author' => $this->get_rule('allow_author'),
            'votes_count_compact_show' => $this->get_rule('votes_count_compact_show'),
            'votes_count_compact_decimals' => $this->get_rule('votes_count_compact_decimals'),
            'cta' => $this->get_rule('cta'),
            'template' => $this->get_rule('template'),
            'alignment' => $this->get_rule('alignment'),
            'style_type' => $this->get_rule('style_type'),
            'style_name' => $this->get_rule($_type_name),
            'style_theme' => $this->get_rule('style_theme'),
            'style_size' => $this->get_rule('style_size'),
            'style_class' => $this->get_rule('class'),
            'labels' => $this->labels()
        );

        if (empty($defaults['style_name']) && $defaults['style_type'] != 'text') {
            $defaults['style_type'] = 'font';
            $defaults['style_name'] = 'star';
        }

        $args = wp_parse_args($method, $defaults);

        if (!gdrts_single()->is_suppress_filters()) {
            $args = apply_filters('gdrts_like_this_loop_single_args', $args, $this->prefix);
        }

        return $args;
    }

    public function _get_args_list($method = array()) {
        $_type_name = 'style_'.$this->get_rule('style_type').'_name';

        $defaults = array(
            'template' => $this->get_rule('template'),
            'style_type' => $this->get_rule('style_type'),
            'style_theme' => $this->get_rule('style_theme'),
            'style_name' => $this->get_rule($_type_name),
            'style_size' => $this->get_rule('style_size'),
            'style_class' => $this->get_rule('class'),
            'labels' => $this->labels()
        );

        if (empty($defaults['style_name'])) {
            $defaults['style_type'] = 'font';
            $defaults['style_name'] = 'star';
        }

        $args = wp_parse_args($method, $defaults);

        $args = apply_filters('gdrts_like_this_loop_list_args', $args, $this->prefix);

        return $args;
    }

    public function implements_votes($votes = false) {
        return true;
    }

    public function labels() {
        return array(
            'like' => $this->get_rule('labels_like'),
            'liked' => $this->get_rule('labels_liked'),
            'unlike' => $this->get_rule('labels_unlike')
        );
    }

    public function prepare_loop_single($method, $args = array()) {
        $this->init_rule_settings();

        $this->_engine = 'single';
        $this->_render = new gdrts_render_single_like_this();
        $this->_args = $this->_get_args_single($method);

        gdrts_single()->user_init();

        $this->_user = new gdrts_user_like_this($this->_args['allow_super_admin'], $this->_args['allow_user_roles'], $this->_args['allow_visitor'], $this->_args['allow_author']);

        gdrts_single()->item()->prepare($this->prefix);

        $this->_calc['remove_vote'] = $this->get_rule('remove_vote');
        $this->_calc['revote_ajax'] = $this->get_rule('revote_ajax');

        $this->_calc['votes'] = intval(gdrts_single()->item()->get_method_value('votes'));
        $this->_calc['sum'] = floatval(gdrts_single()->item()->get_method_value('rating'));

        $this->_calc['allowed'] = $this->user()->is_allowed();
        $this->_calc['open'] = false;
        $this->_calc['real_votes'] = $this->_calc['votes'];

        $this->calculate_display_votes();

        $this->_calc = apply_filters('gdrts_like_this_loop_single_calc', $this->_calc, $this->prefix);

        $this->_calc['rating'] = $this->_calc['sum'];

        if (gdrts()->is_locked() || $this->_args['disable_rating']) {
            $this->_calc['open'] = false;
        } else if (!gdrts_single()->is_loop_save() || $this->_calc['revote_ajax']) {
            $this->validate_open();
        } else if (gdrts_single()->is_loop_save()) {
            $this->_calc['open'] = false;
        }

        gdrts_single()->set_method_args($this->_args);
    }

    public function prepare_loop_list($method, $args = array(), $filters = null) {
        $this->init_rule_settings($args['entity'], $args['name'], false, $filters);

        $this->_engine = 'list';
        $this->_render = new gdrts_render_list_like_this();
        $this->_args = $this->_get_args_list($method);

        $this->_calc = apply_filters('gdrts_like_this_loop_list_calc', $this->_calc, $this->prefix);
    }

    public function update_list_item() {
        gdrts_list()->item()->prepare($this->prefix);

        $this->_calc['votes'] = intval(gdrts_list()->item()->get_method_period_value('votes'));
        $this->_calc['sum'] = intval(gdrts_list()->item()->get_method_period_value('rating'));

        $this->calculate_display_votes();

        $this->_calc = apply_filters('gdrts_like_this_loop_list_item_calc', $this->_calc);

        $this->_calc['rating'] = $this->_calc['sum'];

        $this->_args = apply_filters('gdrts_like_this_loop_list_item_args', $this->_args);
    }

    public function json_list($data, $method) {
        if ($method == $this->method()) {
            $data['likes'] = array(
                'chars' => gdrts()->get_font_like_chars($this->_args['style_type'], $this->_args['style_name']),
                'type' => $this->_args['style_type'],
                'name' => $this->_args['style_name']
            );
        }

        return $data;
    }

    public function json_single($data, $method) {
        if ($method == $this->method()) {
            $data['likes'] = array(
                'chars' => gdrts()->get_font_like_chars($this->_args['style_type'], $this->_args['style_name']),
                'theme' => $this->_args['style_theme'],
                'type' => $this->_args['style_type'],
                'name' => $this->_args['style_name'],
                'size' => $this->_args['style_size']
            );

            $data['labels'] = $this->labels();

            $data['render']['method'] = $this->_args;
        }

        return $data;
    }

    public function validate_open() {
        if ($this->user()->has_voted()) {
            $this->_calc['open'] = $this->_calc['remove_vote'];
        }

        $this->_calc['open'] = true;
    }

    /** @param gdrts_rating_item $item */
    public function calculate($item, $action, $vote, $previous = 0, $update_latest = true) {
        $item->prepare_save();
        $item->prepare($this->prefix);

        $votes = $item->get_method_value('votes');

        if ($action == 'like') {
            $votes++;
        } else if ($action == 'clear') {
            $votes--;
        }

        $item->set_rating('votes', $votes);
        $item->set_rating('rating', $votes);

        if ($update_latest) {
            $item->set_rating('latest', gdrts_db()->datetime());
        }

        $item = apply_filters('gdrts_calculate_like_this_item', $item, $action, $vote, $previous);

        $item->save($update_latest, false);

        do_action('gdrts_save_item', 'like-this', $item);
        do_action('gdrts_save_item_like-this', $item);
    }

    /** @param object $input
      * @param gdrts_rating_item $item
      * @param gdrts_core_user $user
      * @param null $render
      * @return array */
    public function validate_vote($input, $item, $user, $render = null) {
        $this->init_rule_settings_for_item($item);

        $vote = $input->value;

        $_return = array(
            'errors' => new WP_Error(),
            'action' => 'none',
            'previous' => '',
            'reference' => 0
        );

        if (!in_array($vote, array('like', 'clear'))) {
            $_return['errors']->add('request_vote', __("Vote value is not allowed.", "gd-rating-system"));
        }

        if (empty($_return['errors']->errors)) {
            $_remove_vote = $this->get_rule('remove_vote');

            if ($_remove_vote) {
                $stats = $user->get_log_stats_quick_anytime($item->item_id, $this->method());

                $rating = $stats['like']['items'] - $stats['clear']['items'];

                if ($rating > 0) {
                    $_return['previous'] = 'like';
                    $_return['reference'] = $stats['like']['log_id'];

                    $vote = 'clear';
                } else {
                    $vote = 'like';

                    if ($stats['clear']['items'] > 0) {
                        $_return['reference'] = $stats['clear']['log_id'];
                    }
                }

                $_return['action'] = $vote;
            } else {
                if ($vote == 'like' && !$this->user()->has_voted()) {
                    $_return['action'] = 'like';
                } else {
                    $_return['errors']->add('request_vote', __("Vote attempt is not valid.", "gd-rating-system"));
                }
            }
        }

        if (empty($_return['errors']->errors)) {
            unset($_return['errors']);

            return $_return;
        } else {
            return $_return['errors'];
        }
    }

    public function vote($input, $item, $user, $render = null) {
        $validation = $this->validate_vote($input, $item, $user, $render);

        if (is_wp_error($validation)) {
            return $validation;
        }

        extract($validation, EXTR_OVERWRITE); // $action, $previous, $reference

        if ($action == 'none') {
            return true;
        }

        $data = array(
            'ip' => $user->ip,
            'action' => $action,
            'ref_id' => $reference,
            'vote' => $action == 'like' ? 1 : -1
        );

        $log_id = gdrts_db()->add_to_log($item->item_id, $user->id, $this->method(), $data);

        if (!is_null($log_id)) {
            $user->update_cookie($log_id);
        }

        $this->calculate($item, $action, $data['vote'], $previous);

        return true;
    }

    public function remove_vote_by_log($log, $ref = null) {
        $item = gdrts_get_rating_item_by_id($log->item_id);

        $item->prepare_save();
        $item->prepare($this->prefix);

        $votes = $item->get_method_value('votes');
        $remove_vote = intval($log->vote);

        if ($remove_vote == -1) {
            $votes++;
        } else if ($remove_vote == 1) {
            $votes--;
        }

        $item->set_rating('votes', $votes);
        $item->set_rating('rating', $votes);

        $item->save(false, false);
    }

    public function rating($item, $series = '') { }
}

global $_gdrts_method_like_this;
$_gdrts_method_like_this = new gdrts_method_like_this();

function gdrtsm_like_this() {
    global $_gdrts_method_like_this;
    return $_gdrts_method_like_this;
}
