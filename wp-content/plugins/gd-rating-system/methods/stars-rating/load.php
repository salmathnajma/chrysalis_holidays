<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_method_stars_rating extends gdrts_method {
    public $prefix = 'stars-rating';

    public function __construct() {
        require_once(GDRTS_PATH.'methods/stars-rating/user.php');
        require_once(GDRTS_PATH.'methods/stars-rating/render.php');
        require_once(GDRTS_PATH.'methods/stars-rating/functions.php');

        parent::__construct();
    }

    public function trigger_enqueue() {
        parent::trigger_enqueue();

        gdrts_plugin()->method_enqueue_stars();
    }

    public function load_admin() {
        require_once(GDRTS_PATH.'methods/stars-rating/admin.php');
    }

    public function load_rest_api() {
        require_once(GDRTS_PATH.'methods/stars-rating/restapi.php');
    }

    public function get_max_value() {
        return absint($this->get_rule('stars'));
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
            'responsive' => $this->get_rule('responsive'),
            'distribution' => $this->get_rule('distribution'),
            'rating' => $this->get_rule('rating'),
            'style_type' => $this->get_rule('style_type'),
            'style_name' => $this->get_rule($_type_name),
            'style_size' => $this->get_rule('style_size'),
            'font_color_empty' => $this->get_rule('font_color_empty'),
            'font_color_current' => $this->get_rule('font_color_current'),
            'font_color_active' => $this->get_rule('font_color_active'),
            'style_class' => $this->get_rule('class'),
            'labels' => $this->get_rule('labels')
        );

        if (empty($defaults['style_name'])) {
            $defaults['style_type'] = 'font';
            $defaults['style_name'] = 'star';
        }

        $args = wp_parse_args($method, $defaults);

        if (!gdrts_single()->is_suppress_filters()) {
            $args = apply_filters('gdrts_stars_rating_loop_single_args', $args);
        }

        return $args;
    }

    public function _get_args_list($method = array()) {
        $_type_name = 'style_'.$this->get_rule('style_type').'_name';

        $defaults = array(
            'template' => $this->get_rule('template'),
            'responsive' => $this->get_rule('responsive'),
            'rating' => $this->get_rule('rating'),
            'style_type' => $this->get_rule('style_type'),
            'style_name' => $this->get_rule($_type_name),
            'style_size' => $this->get_rule('style_size'),
            'style_class' => $this->get_rule('class'),
            'font_color_empty' => $this->get_rule('font_color_empty'),
            'font_color_current' => $this->get_rule('font_color_current'),
            'font_color_active' => $this->get_rule('font_color_active'),
            'labels' => $this->get_rule('labels')
        );

        if (empty($defaults['style_name'])) {
            $defaults['style_type'] = 'font';
            $defaults['style_name'] = 'star';
        }

        $args = wp_parse_args($method, $defaults);

        $args = apply_filters('gdrts_stars_rating_loop_list_args', $args);

        return $args;
    }

    public function labels() {
        $labels = array();

        for ($id = 1; $id <= $this->_calc['stars']; $id++) {
            $key = $id - 1;

            $label = isset($this->_args['labels'][$key]) ? $this->_args['labels'][$key] : false;
            $labels[] = $label !== false ? __($label, "gd-rating-system") : sprintf(_n("%s Star", "%s Stars", $id, "gd-rating-system"), $id);
        }

        return $labels;
    }

    public function prepare_loop_single($method, $args = array()) {
        $this->init_rule_settings();

        $this->_engine = 'single';
        $this->_render = new gdrts_render_single_stars_rating();
        $this->_args = $this->_get_args_single($method);

        gdrts_single()->user_init();

        $this->_user = new gdrts_user_stars_rating($this->_args['allow_super_admin'], $this->_args['allow_user_roles'], $this->_args['allow_visitor'], $this->_args['allow_author']);

        gdrts_single()->item()->prepare($this->prefix);

        $this->_calc['stars'] = absint($this->get_rule('stars'));
        $this->_calc['resolution'] = absint($this->get_rule('resolution'));
        $this->_calc['vote'] = $this->get_rule('vote');
        $this->_calc['vote_limit'] = $this->get_rule('vote_limit');
        $this->_calc['revote_ajax'] = $this->get_rule('revote_ajax');

        $this->_calc['votes'] = absint(gdrts_single()->item()->get_method_value('votes'));
        $this->_calc['sum'] = floatval(gdrts_single()->item()->get_method_value('sum'));
        $this->_calc['max'] = absint(gdrts_single()->item()->get_method_value('max'));
        $this->_calc['average'] = gdrts_single()->item()->get_method_value('rating');
        $this->_calc['distribution'] = gdrts_single()->item()->get('stars-rating_distribution', $this->distribution_array($this->_calc['max']));

        if ($this->_calc['votes'] > 0 && $this->_calc['max'] != $this->_calc['stars']) {
            $factor = $this->_calc['stars'] / $this->_calc['max'];

            $this->_calc['sum'] = $this->_calc['sum'] * $factor;
            $this->_calc['average'] = $this->_calc['average'] * $factor;
            $this->_calc['max'] = $this->_calc['stars'];

            $new_dist = array();

            foreach ($this->_calc['distribution'] as $key => $value) {
                $new_key = number_format(round(floatval($key) * $factor, 2), 2);
                $new_dist[$new_key] = $value;
            }

            $this->_calc['distribution'] = $new_dist;
        }

        $this->_calc['average'] = number_format($this->_calc['average'], gdrts()->decimals());
        $this->_calc['allowed'] = $this->user()->is_allowed();
        $this->_calc['open'] = false;
        $this->_calc['real_votes'] = $this->_calc['votes'];

        $this->calculate_display_votes();

        $this->_calc = apply_filters('gdrts_stars_rating_loop_single_calc', $this->_calc);

        if (!isset($this->_calc[$this->_args['rating']])) {
            $this->_args['rating'] = 'average';
        }

        $this->_calc['rating'] = $this->_calc[$this->_args['rating']];
        $this->_calc['rating_own'] = 0;
        $this->_calc['current'] = absint(100 * ($this->_calc['rating'] / $this->_calc['stars']));
        $this->_calc['current_own'] = 0;

        if ($this->user()->has_voted()) {
            $vote = $this->user()->active_vote();

            $this->_calc['rating_own'] = $vote->vote * ($this->_calc['stars'] / $vote->max);
            $this->_calc['current_own'] = absint(100 * ($vote->vote / $vote->max));
        }

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
        $this->_render = new gdrts_render_list_stars_rating();
        $this->_args = $this->_get_args_list($method);

        $this->_calc['stars'] = intval($this->get_rule('stars'));

        $this->_calc = apply_filters('gdrts_stars_rating_loop_list_calc', $this->_calc);
    }

    public function update_list_item() {
        gdrts_list()->item()->prepare($this->prefix);

        $this->_calc['sum'] = floatval(gdrts_list()->item()->get_method_period_value('sum'));
        $this->_calc['max'] = absint(gdrts_list()->item()->get_method_period_value('max'));
        $this->_calc['votes'] = absint(gdrts_list()->item()->get_method_period_value('votes'));
        $this->_calc['average'] = gdrts_list()->item()->get_method_period_value('rating');

        if (gdrts_list()->item()->is_period()) {
            $this->_calc['sum'] = $this->_calc['votes'] * $this->_calc['average'];
        }

        if ($this->_calc['votes'] > 0 && $this->_calc['max'] != $this->_calc['stars']) {
            $factor = $this->_calc['stars'] / $this->_calc['max'];

            $this->_calc['sum'] = $this->_calc['sum'] * $factor;
            $this->_calc['average'] = $this->_calc['average'] * $factor;
            $this->_calc['max'] = $this->_calc['stars'];
        }

        $this->_calc['average'] = number_format($this->_calc['average'], gdrts()->decimals());

        $this->calculate_display_votes();

        $this->_calc = apply_filters('gdrts_stars_rating_loop_list_item_calc', $this->_calc);

        if (!isset($this->_calc[$this->_args['rating']])) {
            $this->_args['rating'] = 'average';
        }

        $this->_calc['rating'] = $this->_calc[$this->_args['rating']];
        $this->_calc['current'] = intval(100 * ($this->_calc['rating'] / $this->_calc['stars']));

        $this->_args = apply_filters('gdrts_stars_rating_loop_list_item_args', $this->_args);
    }

    public function json_single($data, $method) {
        if ($method == $this->method()) {
            $data['stars'] = array(
                'max' => $this->_calc['stars'],
                'resolution' => $this->_calc['resolution'],
                'responsive' => $this->_args['responsive'],
                'current' => $this->_calc['current'],
                'char' => gdrts()->get_font_star_char($this->_args['style_type'], $this->_args['style_name']),
                'name' => $this->_args['style_name'],
                'size' => $this->_args['style_size'],
                'type' => $this->_args['style_type']
            );

            $data['labels'] = $this->labels();

            $data['render']['method'] = $this->_args;
        }

        return $data;
    }

    public function json_list($data, $method) {
        if ($method == $this->method()) {
            $data['stars'] = array(
                'max' => $this->_calc['stars'],
                'char' => gdrts()->get_font_star_char($this->_args['style_type'], $this->_args['style_name']),
                'name' => $this->_args['style_name'],
                'size' => $this->_args['style_size'],
                'type' => $this->_args['style_type'],
                'responsive' => $this->_args['responsive']
            );

            $data['labels'] = $this->labels();
        }

        return $data;
    }

    public function distribution_array($max) {
        $dist = array();

        for ($i = 0; $i < $max; $i++) {
            $key = number_format($i + 1, 2);
            $dist[$key] = 0;
        }

        return $dist;
    }

    /** @param $item gdrts_rating_item */
    public function calculate($item, $action, $vote, $max = null, $previous = 0, $update_latest = true) {
        $item->prepare_save();
        $item->prepare($this->prefix);

        $votes = $item->get_method_value('votes');
        $sum = $item->get_method_value('sum');
        $max_db = $item->get_method_value('max');
        $distribution = $item->get('stars-rating_distribution', $this->distribution_array($max));

        if ($votes > 0 && $max_db != $max) {
            $factor = $max / $max_db;
            $sum = $sum * $factor;

            $new_dist = array();

            foreach ($distribution as $key => $value) {
                $new_key = number_format(round(floatval($key) * $factor, 2), 2);
                $new_dist[$new_key] = $value;
            }

            $distribution = $new_dist;
        }

        if ($action == 'vote') {
            $votes++;

            $sum = $sum + floatval($vote);
        } else if ($action == 'revote') {
            $sum = $sum + floatval($vote) - floatval($previous);
        }

        if ($action == 'revote') {
            $dist_previous = number_format(round($previous, 2), 2);

            if (isset($distribution[$dist_previous])) {
                $distribution[$dist_previous] = $distribution[$dist_previous] - 1;
            }
        }

        $dist_vote = number_format(round($vote, 2), 2);

        if (!isset($distribution[$dist_vote])) {
            $distribution[$dist_vote] = 0;
        }

        $distribution[$dist_vote] = $distribution[$dist_vote] + 1;

        krsort($distribution);

        $rating = round($sum / $votes, gdrts()->decimals());

        $item->set_rating('sum', $sum);
        $item->set_rating('max', $max);
        $item->set_rating('votes', $votes);
        $item->set_rating('rating', $rating);

        $item->set('stars-rating_distribution', $distribution);

        if ($update_latest) {
            $item->set_rating('latest', gdrts_db()->datetime());
        }

        $item = apply_filters('gdrts_calculate_stars_rating_item', $item, $action, $vote, $max, $previous);

        $item->save($update_latest, false);

        do_action('gdrts_save_item', 'stars-rating', $item);
        do_action('gdrts_save_item_stars-rating', $item);
    }

    /** @param object $input
      * @param gdrts_rating_item $item
      * @param gdrts_core_user $user
      * @param null $render
      * @return array */
    public function validate_vote($input, $item, $user, $render = null) {
        $this->init_rule_settings_for_item($item);

        $_return = array(
            'errors' => new WP_Error(),
            'action' => 'none',
            'previous' => '',
            'reference' => 0
        );

        $vote = round(floatval($input->value), 2);
        $max = absint($input->max);

        $_calc_stars = absint($this->get_rule('stars'));
        $_calc_vote = $this->get_rule('vote');
        $_calc_vote_limit = $this->get_rule('vote_limit');

        if ($max != $_calc_stars) {
            $_return['errors']->add('request_max', __("Maximum value don't match the rule.", "gd-rating-system"));
        }

        if ($vote == 0 || $vote < 0 || $vote > $max) {
            $_return['errors']->add('request_vote', __("Vote value out of rule bounds.", "gd-rating-system"));
        }

        if (empty($_return['errors']->errors)) {
            $user->prepare($this->method());

            $_return = apply_filters('gdrts_vote_limit_validate_'.$_calc_vote, $_return, $item, $user, $vote, $_calc_vote_limit, $_calc_stars);
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
            'vote' => $input->value,
            'max' => $input->max
        );

        $log_id = gdrts_db()->add_to_log($item->item_id, $user->id, $this->method(), $data);

        if (!is_null($log_id)) {
            $user->update_cookie($log_id);
        }

        $this->calculate($item, $action, $input->value, $input->max, $previous);

        return true;
    }

    public function remove_vote_by_log($log, $ref = null) {
        $item = gdrts_get_rating_item_by_id($log->item_id);

        $item->prepare_save();
        $item->prepare($this->prefix);

        $votes = absint($item->get_method_value('votes'));
        $sum = floatval($item->get_method_value('sum'));
        $max = absint($item->get_method_value('max'));
        $distribution = $item->get('stars-rating_distribution', $this->distribution_array($max));

        $remove_vote = floatval($log->vote);
        $remove_max = absint($log->max);

        if ($remove_max != $max) {
            $remove_vote = $remove_vote * ($max / $remove_max);
        }

        $sum = $sum - floatval($remove_vote);

        $dist = number_format(round($remove_vote, 2), 2);

        if (isset($distribution[$dist])) {
            $distribution[$dist] = $distribution[$dist] - 1;
        }

        if (is_null($ref)) {
            $votes--;
        } else {
            $revert_vote = floatval($ref->vote);
            $revert_max = absint($ref->max);

            if ($revert_max != $max) {
                $revert_vote = $revert_vote * ($max / $revert_max);
            }

            $sum = $sum + floatval($revert_vote);

            $dist = number_format(round($revert_vote, 2), 2);

            if (!isset($distribution[$dist])) {
                $distribution[$dist] = 0;
            }

            $distribution[$dist] = $distribution[$dist] + 1;
        }

        krsort($distribution);

        $rating = $votes > 0 ? round($sum / $votes, gdrts()->decimals()) : 0;

        $item->set_rating('sum', $sum);
        $item->set_rating('max', $max);
        $item->set_rating('votes', $votes);
        $item->set_rating('rating', $rating);

        $item->set('stars-rating_distribution', $distribution);

        $item->save(false, false);
    }

    /** @param gdrts_rating_item $item
      * @param string $series
      * @return array */
    public function rating($item, $series = '') {
        $rating = array();

        $votes = absint($item->get_method_value('votes', 0, $this->prefix));

        if ($votes > 0) {
            $rating['count'] = $votes;
            $rating['best'] = absint($item->get_method_value('max', 0, $this->prefix));
            $rating['value'] = number_format($item->get_method_value('rating', 0, $this->prefix), gdrts()->decimals());
        }

        return $rating;
    }
}

global $_gdrts_method_stars_rating;
$_gdrts_method_stars_rating = new gdrts_method_stars_rating();

/** @return gdrts_method_stars_rating */
function gdrtsm_stars_rating() {
    global $_gdrts_method_stars_rating;
    return $_gdrts_method_stars_rating;
}
