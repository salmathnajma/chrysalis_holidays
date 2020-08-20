<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core_limiter {
    public $limits = array();

    public $allow_custom = false;
    public $day_start = 0;
    public $week_start = 1;

    public function __construct() {
        foreach (array('single', 'revote', 'multi') as $limit) {
            add_filter('gdrts_vote_limit_validate_'.$limit, array($this, 'validate_'.$limit), 10, 6);
            add_filter('gdrts_vote_limit_open_'.$limit, array($this, 'open_'.$limit), 10, 3);
        }

        add_filter('gdrts_vote_limit_render_user', array($this, 'limit_render_user'), 10, 5);
    }

    public static function get_instance() {
        static $_instance = false;

        if ($_instance === false) {
            $_instance = new gdrts_core_limiter();
        }

        return $_instance;
    }

    public function __call($name, $arguments) {
        if (substr($name, 0, 9) == 'validate_') {
            $real = substr($name, 9);

            if (isset($this->limits[$real])) {
                $parts = explode('_', $real);
                $call = 'custom_validate_'.$parts[1];

                return call_user_func(array($this, $call), $parts[0], $arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4], $arguments[5]);
            }
        } else if (substr($name, 0, 5) == 'open_') {
            $real = substr($name, 5);

            if (isset($this->limits[$real])) {
                $parts = explode('_', $real);
                $call = 'custom_open_'.$parts[1];

                return call_user_func(array($this, $call), $parts[0], $arguments[0], $arguments[1], $arguments[2]);
            }
        }

        return false;
    }

    public function limit_render_user($tpls, $limiter, $limit, $remaining, $closed = '', $vote = null) {
        $process = true;
        $scope = $limiter;

        if ($process) {
            $vote = is_null($vote) ? array(
                'multi' => __("Your previous vote was <strong>%s</strong>, %s ago.", "gd-rating-system"),
                'revote' => __("You voted <strong>%s</strong>, %s ago.", "gd-rating-system"),
                'single' => __("You have voted <strong>%s</strong>, %s ago.", "gd-rating-system")
            ) : $vote;

            $tpls['vote'] = $vote[$scope];

            if (!empty($closed)) {
                $tpls['remaining'] = $closed;
            } else {
                switch ($scope) {
                    case 'multi':
                        if ($limit > 0 && $remaining > 0) {
                            $tpls['remaining'] = _n("You can vote %s more time.", "You can vote %s more times.", $remaining, "gd-rating-system");
                        }
                        break;
                    case 'revote':
                        if ($limit > 0 && $remaining > 0) {
                            $tpls['remaining'] = _n("You can change your vote %s more time.", "You can change your vote %s more times.", $remaining, "gd-rating-system");
                        }
                        break;
                }
            }
        }

        return $tpls;
    }

    /** @param gdrts_rating_item $item
      * @param gdrts_core_user $user
      * @param mixed $vote
      * @param int $limit
      * @return array */
    public function validate_multi($_return, $item, $user, $vote = '', $limit = 0, $max = null) {
        $_return = array('errors' => new WP_Error(), 'action' => 'none', 'previous' => '', 'reference' => 0);

        $stats = $user->get_log_stats_quick_anytime($item->item_id);

        $votes = $stats['vote']['items'];
        $revotes = $stats['revote']['items'];

        if ($limit > 0 && $votes + $revotes > $limit) {
            $_return['errors']->add('request_limit', __("You have reached the limit to number of vote attempts.", "gd-rating-system"));
        } else {
            $_return['action'] = 'vote';
        }

        return $_return;
    }

    /** @param gdrts_rating_item $item
      * @param gdrts_core_user $user
      * @param mixed $vote
      * @param int $limit
      * @return array */
    public function validate_revote($_return, $item, $user, $vote = '', $limit = 0, $max = null) {
        $_return = array('errors' => new WP_Error(), 'action' => 'none', 'previous' => '', 'reference' => 0);

        $stats = $user->get_log_stats_quick_anytime($item->item_id);

        $votes = $stats['vote']['items'];
        $revotes = $stats['revote']['items'];

        if ($limit > 0 && $revotes > $limit) {
            $_return['errors']->add('request_limit', __("You have reached the limit to number of vote attempts.", "gd-rating-system"));
        } else {
            $_return['action'] = $votes == 0 ? 'vote' : 'revote';

            if ($revotes > 0) {
                $_return['reference'] = $stats['revote']['log_id'];
            } else if ($votes > 0) {
                $_return['reference'] = $stats['vote']['log_id'];
            }

            if ($_return['reference'] > 0) {
                $entry = gdrts_cache()->get_log_entry($_return['reference']);
                $_return['previous'] = is_array($vote) ? $entry->multi : $entry->vote;

                if (!is_null($max)) {
                    if ($entry->max != $max) {
                        if (is_array($_return['previous'])) {
                            foreach ($_return['previous'] as $_pk => &$_pv) {
                                $_pv = $_pv * ($max / $entry->max);
                            }
                        } else {
                            $_return['previous'] = $_return['previous'] * ($max / $entry->max);
                        }
                    }
                }
            }

            if (is_array($_return['previous'])) {
                $same = true;

                foreach ($_return['previous'] as $_pk => $_pv) {
                    if (!isset($vote[$_pk]) || ($vote[$_pk] != $_pv)) {
                        $same = false;
                    }
                }

                if ($same) {
                    $_return['action'] = 'none';
                }
            } else {
                if ($_return['previous'] == $vote) {
                    $_return['action'] = 'none';
                }
            }
        }

        return $_return;
    }

    /** @param gdrts_rating_item $item
      * @param gdrts_core_user $user
      * @param mixed $vote
      * @param int $limit
      * @return array */
    public function validate_single($_return, $item, $user, $vote = '', $limit = 0, $max = null) {
        $_return = array('errors' => new WP_Error(), 'action' => 'none', 'previous' => '', 'reference' => 0);

        $stats = $user->get_log_stats_quick_anytime($item->item_id);

        if ($stats['vote']['items'] > 0) {
            $_return['errors']->add('request_limit', __("You already voted.", "gd-rating-system"));
        } else {
            $_return['action'] = 'vote';
        }

        return $_return;
    }

    /** @param gdrts_method_user $user
      * @param int $limit
      * @return array */
    public function open_multi($is_open, $user, $limit = 0) {
        $stats = $user->stats_anytime();

        $open = $limit == 0 || $limit > $stats['vote']['items'] + $stats['revote']['items'];

        return array(
            'status' => $open,
            'message' => !$open ? __("You have reached the limit of allowed number of votes.", "gd-rating-system") : '',
            'remaining' => $limit - $stats['vote']['items']
        );
    }

    /** @param gdrts_method_user $user
      * @param int $limit
      * @return array */
    public function open_revote($is_open, $user, $limit = 0) {
        $stats = $user->stats_anytime();

        $open = $limit == 0 || $limit > $stats['revote']['items'];

        return array(
            'status' => $open,
            'message' => !$open ? __("You have reached the limit of allowed vote changes.", "gd-rating-system") : '',
            'remaining' => $limit - $stats['revote']['items']
        );
    }

    /** @param gdrts_method_user $user
      * @param int $limit
      * @return array */
    public function open_single($is_open, $user, $limit = 0) {
        $stats = $user->stats_anytime();

        $open = ($stats['vote']['items'] + $stats['revote']['items']) == 0;

        return array(
            'status' => $open,
            'message' => !$open ? __("You have already voted.", "gd-rating-system") : '',
            'remaining' => 0
        );
    }

    /** @param string $limiter
      * @param int $limit
      * @param gdrts_core_user $user
      * @param gdrts_rating_item $item
      * @return bool */
    public function is_open($limiter, $limit, $user, $item) {
        $stats = $user->get_log_stats_quick_anytime($item->item_id);

        switch ($limiter) {
            case 'single':
                return ($stats['vote']['items'] + $stats['revote']['items']) == 0;
            case 'revote':
                return $limit == 0 || $limit > $stats['revote']['items'];
            case 'multi':
                return $limit == 0 || $limit > $stats['vote']['items'] + $stats['revote']['items'];
            default:
                return false;
        }
    }
}

/** @return gdrts_core_limiter */
function gdrts_limiter() {
    return gdrts_core_limiter::get_instance();
}
