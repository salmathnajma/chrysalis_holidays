<?php

if (!defined('ABSPATH')) { exit; }

abstract class gdrts_extension_admin {
	protected $group = '';
	protected $prefix = '';
	protected $panel_priority = 10;
	protected $has_rules = false;
	protected $has_help = false;

	/** @var gdrts_settings_rule */
	protected $rule = false;

	public function __construct() {
		add_filter('gdrts_admin_settings_panels', array($this, 'panels'), $this->panel_priority);
		add_filter('gdrts_admin_internal_settings', array($this, 'settings'));

		add_filter('gdrts_admin_icon_'.$this->prefix, array($this, 'icon'));

		if ($this->has_rules) {
			add_filter('gdrts_admin_get_rule_options_'.$this->prefix, array($this, 'rule_options'), 10, 2);
			add_filter('gdrts_admin_get_rule_on_save_'.$this->prefix, array($this, 'rule_on_save'), 10, 2);
		}

		if ($this->has_help) {
			add_action('gdrts_load_admin_page_settings_addon_'.str_replace('-', '_', $this->prefix), array($this, 'help'));
		}
	}

	public function icon($icon) {
		if ($this->group == 'addons') {
			return gdrts()->addons[$this->prefix]['icon'];
		} else if ($this->group == 'methods') {
			return gdrts()->methods[$this->prefix]['icon'];
		}

		return $icon;
	}

	/** @param array $settings
	  * @param gdrts_settings_rule $rule
	  * @return array */
	public function rule_options($settings, $rule) {
		$this->rule = $rule;

		return $this->_shared_settings('rules', $rule->id.'][settings][', $rule->method);
	}

	/** @param array $settings
	  * @param gdrts_settings_rule $rule
	  * @return array */
	public function rule_on_save($settings, $rule) {
		$this->rule = $rule;

		return $this->_shared_settings('rules', '#', $rule->method);
	}

	/** @return array */
	public function _shared_settings($prefix = '', $prekey = '', $method = '') {
		return array();
	}

	public function panels($panels) {
		return $panels;
	}

	public function settings($settings, $method = '') {
		return $settings;
	}

	public function get($name, $global = false, $prekey = '') {
		if ($global === false && $this->has_rules && $this->rule !== false) {
		    if ($this->rule->has_settings()) {
                return $this->rule->get($name);
            } else {
                return gdrts_settings()->get($this->key($name), $this->group);
            }
		} else {
		    $value = gdrts_settings()->get($this->key($name, $prekey), $this->group);

		    if (is_null($value)) {
		        $value = gdrts_settings()->get($this->key($name), $this->group);
            }

			return $value;
		}
	}

	public function key($name, $prekey = '') {
		$prekey = empty($prekey) ? $this->prefix.'_' : $prekey;

		if ($prekey == '#') {
			$prekey = '';
		}

		return $prekey.$name;
	}
}

abstract class gdrts_addon_admin extends gdrts_extension_admin {
	protected $group = 'addons';
}

abstract class gdrts_method_admin extends gdrts_extension_admin {
	protected $group = 'methods';
	protected $has_rules = true;

	public function __construct() {
		parent::__construct();

		add_filter('gdrts_votes_grid_content_column_method', array($this, 'grid_vote_item'), 10, 2);
		add_filter('gdrts_ratings_grid_ratings', array($this, 'grid_ratings'), 10, 2);
		add_filter('gdrts_votes_grid_vote_'.$this->prefix, array($this, 'grid_vote'), 10, 2);
	}

	public function grid_vote_item($label, $item) {
		return $label;
	}

	public function grid_ratings($list, $item) {
		return $list;
	}

	public function grid_vote($render, $item) {
		return $render;
	}
}

abstract class gdrts_extension_init {
    public $group = '';
    public $prefix = '';

    protected $override_methods = array();

    public function __construct() {
        add_action('gdrts_settings_init', array($this, 'settings'));
        add_action('gdrts_register_methods_and_addons', array($this, 'register'));
    }

    public function register_option($name, $value) {
        gdrts_settings()->register($this->group, $this->prefix.'_'.$name, $value);
    }

    abstract public function settings();
    abstract public function register();

    abstract public function load();
}

abstract class gdrts_extension {
    public $group = '';
    public $prefix = '';

    public $settings = array();
    public $settings_rule = array();

    public function __construct() {
	    add_action('gdrts_init', array($this, 'init'));
	    add_action('gdrts_core', array($this, 'core'));

        add_action('gdrts_admin_load_modules', array($this, 'load_admin'));
        add_action('gdrts_populate_settings', array($this, 'populate_settings'));
        add_action('gdrts_enqueue_core_files', array($this, 'enqueue_core_files'));

        add_action('gdrts_register_enqueue_files', array($this, 'register_enqueue_files'), 10, 4);
        add_action('gdrts_register_enqueue_files_early', array($this, 'register_enqueue_files_early'), 10, 4);
    }

    public function init() { }

    public function core() { }

    public function enqueue_core_files() { }

    public function register_enqueue_files($js_full, $css_full, $js_dep, $css_dep) { }

    public function register_enqueue_files_early($js_full, $css_full, $js_dep, $css_dep) { }

    public function is_rule_settings_matched() {
        if (isset($this->settings_rule['_rule_matched'])) {
            return $this->settings_rule['_rule_matched'];
        }

        return false;
    }

	public function get_rule_settings($entity = null, $name = null, $method = false, $filters = null) {
		return gdrts_rules()->get_rule_settings($this, $entity, $name, $method, $filters);
	}

	public function get_rule_settings_for_item($item, $method = false) {
		return gdrts_rules()->get_rule_settings($this, $item->entity, $item->name, $method, $item->get_rule_filter_elements());
	}

	public function init_rule_settings($entity = null, $name = null, $method = false, $filters = null) {
		$this->settings_rule = $this->get_rule_settings($entity, $name, $method, $filters);
	}

    public function init_rule_settings_for_item($item, $method = false) {
	    $this->settings_rule = $this->get_rule_settings_for_item($item, $method);
    }

    public function get_rule($name, $default = '') {
        if (isset($this->settings_rule[$name])) {
            return $this->settings_rule[$name];
        } else if (isset($this->settings[$name])) {
            return $this->settings[$name];
        } else {
            return $default;
        }
    }

	public function get_rule_only($name, $default = '') {
		if ($this->is_rule_settings_matched() && isset($this->settings_rule[$name])) {
			return $this->settings_rule[$name];
		} else {
			return $default;
		}
	}

    public function get($name, $prefix = '', $prekey = '') {
        if ($prefix != '' && $prekey != '') {
            $override = gdrts_settings()->get($prekey.'_'.$name, $prefix);

            if (!is_null($override)) {
                return $override;
            }
        }

        return isset($this->settings[$name]) ? $this->settings[$name] : null;
    }

    public function key($name, $prekey = '') {
        $prekey = empty($prekey) ? $this->prefix.'_' : $prekey;

        return $prekey.$name;
    }

	public function populate_settings() {
		$this->settings = gdrts_settings()->prefix_get($this->prefix.'_', $this->group);
	}

    abstract public function load_admin();
    abstract public function extension_prop($name, $default = null);
    abstract public function get_default_settings();
}

abstract class gdrts_addon extends gdrts_extension {
    public $group = 'addons';

    public function extension_prop($name, $default = null) {
        return gdrts()->get_addon_prop($this->prefix, $name, $default);
    }

    public function get_default_settings() {
        $settings = array();

        foreach (gdrts_settings()->settings['addons'] as $key => $value) {
            if (substr($key, 0, strlen($this->prefix)) == $this->prefix) {
                $settings[substr($key, strlen($this->prefix) + 1)] = $value;
            }
        }

        return $settings;
    }
}

abstract class gdrts_method extends gdrts_extension {
    public $group = 'methods';

	/** @var gdrts_method_user */
    protected $_user = null;
    protected $_render = null;
    protected $_args = array();
    protected $_calc = array();
    protected $_engine = '';

    public function __construct() {
        parent::__construct();

        add_filter('gdrts_loop_single_json_data', array($this, 'json_single'), 10, 2);
        add_filter('gdrts_loop_list_json_data', array($this, 'json_list'), 10, 2);
        add_filter('gdrts_rating_item_instance_init', array($this, 'rating_item_instance'));
        add_filter('gdrts_query_has_votes_'.$this->prefix, array($this, 'implements_votes'));
        add_action('gdrts_addon_wp-rest-api_routes', array($this, 'load_rest_api'));
        add_action('gdrts_summary_rating_data_for_'.$this->prefix, array($this, 'summary_rating_data'), 10, 3);
        add_action('gdrts_format_rating_text_'.$this->prefix, array($this, 'format_rating_text'), 10, 3);
        add_action('gdrts_trigger_enqueue_'.$this->prefix, array($this, 'trigger_enqueue'));
    }

    public function trigger_enqueue() {
        gdrts()->trigger_enqueue();
    }

    public function implements_votes($votes = false) {
        return gdrts()->get_method_prop($this->prefix, 'has_votes', true);
    }

    public function form_ready() {
        return gdrts()->get_method_prop($this->prefix, 'form_ready', true);
    }

    public function allows_multiple_votes() {
        return gdrts_method_allows_multiple_votes($this->prefix);
    }

    public function has_series() {
        return gdrts_method_has_series($this->prefix);
    }

    public function all_series_list() {
        return array();
    }

    public function get_max_value() {
        return 1;
    }

    public function reset_loop() {
        $this->_args = array();
        $this->_calc = array();
    }

    public function method() {
        return $this->prefix;
    }

    public function loop() {
        return $this;
    }

    public function user() {
        return $this->_user;
    }

    public function render() {
        return $this->_render;
    }

    public function args($name, $default = false) {
        return isset($this->_args[$name]) ? $this->_args[$name] : $default;
    }

    public function calc($name, $key = null, $default = false) {
        if (is_null($key)) {
            return isset($this->_calc[$name]) ? $this->_calc[$name] : $default;
        } else {
            return isset($this->_calc[$name][$key]) ? $this->_calc[$name][$key] : $default;
        }
    }

    public function value($name, $echo = true) {
        $value = '';

        if (isset($this->_calc[$name])) {
            $value = $this->_calc[$name];
        }

        if ($echo) {
            echo $value;
        }

        return $value;
    }

    public function has_votes() {
        return $this->value('votes', false) > 0;
    }

    public function templates_list($entity, $name) {
        $template = isset($this->_args['template']) ? $this->_args['template'] : 'widget';

        $base = 'gdrts--'.$this->prefix.'--list--'.$template;

        return array(
            $base.'--'.$entity.'-'.$name.'.php',
            $base.'--'.$entity.'.php',
            $base.'.php'
        );
    }

    public function templates_single($item) {
        $template = isset($this->_args['template']) ? $this->_args['template'] : 'default';

        $base = 'gdrts--'.$this->prefix.'--single--'.$template;

        return array(
            $base.'--'.$item->entity.'-'.$item->name.'-'.$item->id.'.php',
            $base.'--'.$item->entity.'-'.$item->name.'.php',
            $base.'--'.$item->entity.'.php',
            $base.'.php'
        );
    }

    public function format_rating_text($text, $vote, $meta) {
        return $text;
    }

	public function calculate_display_votes() {
		$this->_calc['votes_display'] = $this->calculate_compact_votes($this->_calc['votes']);
	}

	public function calculate_compact_votes($votes) {
		if ($this->args('votes_count_compact_show', false)) {
			$dec = $this->args('votes_count_compact_decimals', 1);

			return gdrts_compact_number($votes, $dec);
		}

		return $votes;
	}

	public function summary_rating_data($data = array(), $series = '', $item = null) {
		return $this->rating($item, $series);
	}

    /** @param gdrts_rating_item $item
      * @param string $series
      * @return array */
    public function rating($item, $series = '') {
		return array();
	}

	public function rating_item_instance($data) {
        foreach ($data['ratings'] as $method => &$raw) {
            if ($method == $this->prefix) {
                if ($this->has_series()) {
                    foreach ($raw as $series => &$obj) {
                        $obj = $this->rating_item_instance_single($obj, $data, $series);
                    }
                } else {
                    $raw = $this->rating_item_instance_single($raw, $data);
                }
            }
        }

        return $data;
    }

    public function validate_open() {
        $_calc_vote = $this->_calc['vote'];
        $_calc_vote_limit = $this->_calc['vote_limit'];

        $_default_open = array(
            'status' => false,
            'message' => __("Currently, you can't vote.", "gd-rating-system"),
            'remaining' => 0
        );

        $open = apply_filters('gdrts_vote_limit_open_'.$_calc_vote, $_default_open, $this->user(), $_calc_vote_limit);

        $this->_calc['open'] = $open['status'];
        $this->_calc['open_message'] = $open['message'];
        $this->_calc['open_remaining'] = $open['remaining'];
    }

	public function load_rest_api() { }

    abstract public function prepare_loop_list($method, $args = array(), $filters = null);
    abstract public function prepare_loop_single($method, $args = array());

    abstract public function json_single($data, $method);
    abstract public function json_list($data, $method);

    /** @param $input array
      * @param $item gdrts_rating_item
      * @param $user gdrts_core_user
      * @param $render object
      * @return bool */
    abstract public function vote($input, $item, $user, $render = null);

    /** @param object $input
      * @param gdrts_rating_item $item
      * @param gdrts_core_user $user
      * @param null $render
      * @return array */
    abstract public function validate_vote($input, $item, $user, $render = null);

    abstract public function remove_vote_by_log($log, $ref = null);

    protected function rating_item_instance_single($obj, &$data, $series = null) {
        $prefix = $this->prefix.(!is_null($series) ? '-'.$series : '').'_';

        foreach ($data['meta'] as $key => $value) {
            if (substr($key, 0, strlen($prefix)) == $prefix) {
                $name = substr($key, strlen($prefix));
                $obj[$name] = $value;

                unset($data['meta'][$key]);
            }
        }

        return $obj;
    }

    public function extension_prop($name, $default = null) {
        return gdrts()->get_method_prop($this->prefix, $name, $default);
    }

    public function get_default_settings() {
        $settings = array();

        foreach (gdrts_settings()->settings['methods'] as $key => $value) {
            if (substr($key, 0, strlen($this->prefix)) == $this->prefix) {
                $settings[substr($key, strlen($this->prefix))] = $value;
            }
        }

        return $settings;
    }
}

abstract class gdrts_method_render_single {
    public function __construct() { }

    abstract public function owner();
    abstract public function classes($extra = '', $echo = true);

    public function has_votes() {
        return $this->owner()->has_votes();
    }

    public function has_distribution() {
        return $this->owner()->value('real_votes', false) > 0;
    }

    public function method() {
        $this->owner()->method();
    }

    public function args($name, $override = null) {
        if (is_null($override)) {
            return $this->owner()->args($name);
        }

        return $override;
    }

    public function value($name, $echo = true) {
        return $this->owner()->value($name, $echo);
    }

    public function call_to_action($render = '', $echo = true) {
        $cta = $this->owner()->args('cta');

        if (!empty($cta)) {
            $render = $cta;
        }

        $render = apply_filters('gdrts_render_single_'.$this->method().'_call_to_action', $render);

        if (!empty($render)) {
            $render = '<div class="gdrts-block-call-to-action">'.$render.'</div>';
        }

        if ($echo) {
            echo $render;
        } else {
            return $render;
        }
    }

    protected function get_classes($extra = '') {
        $classes = array_merge(
            array(
                'gdrts-rating-block',
                'gdrts-align-'.$this->owner()->args('alignment'),
                'gdrts-method-'.$this->owner()->method(),
                'gdrts-block-'.$this->owner()->method().'-item-'. gdrts_single()->item()->item_id,
                $this->owner()->calc('allowed') ? 'gdrts-rating-allowed' : 'gdrts-rating-forbidden',
                $this->owner()->calc('open') ? 'gdrts-rating-open' : 'gdrts-rating-closed',
                $this->owner()->args('style_class')
            ),
            gdrts_single()->item()->rating_classes()
        );

        if (!empty($extra)) {
            $classes[] = $extra;
        }

        return $classes;
    }

    public function list_users($input, $args = array(), $echo = true) { }
}

abstract class gdrts_method_render_list {
    public function __construct() { }

    abstract public function owner();
    abstract public function classes($extra = '', $echo = true);

    public function method() {
        $this->owner()->method();
    }

    public function args($name, $override = null) {
        if (is_null($override)) {
            return $this->owner()->args($name);
        }

        return $override;
    }

    public function value($name, $echo = true) {
        return $this->owner()->value($name, $echo);
    }

    protected function get_classes($extra = '') {
        $classes = array(
            'gdrts-rating-list',
            'gdrts-method-'.$this->owner()->method()
        );

        if (!empty($extra)) {
            $classes[] = $extra;
        }

        return $classes;
    }
}

abstract class gdrts_method_user {
    public $method = '';
    public $user = null;
    public $item = 0;

    public $super_admin = true;
    public $user_roles = true;
    public $visitor = true;
    public $author = false;

    public $series = null;

    public function __construct($super_admin, $user_role, $visitor, $author = false) {
        $this->super_admin = $super_admin;
        $this->user_roles = $user_role;
        $this->visitor = $visitor;
        $this->author = $author;

        $this->user = gdrts_single()->user();
        $this->item = gdrts_single()->item()->item_id;
    }

    public function is_allowed() {
        $override = apply_filters('gdrts_user_is_allowed_override', null, $this);

        if (!is_null($override) && is_bool($override)) {
            return $override;
        }

        $author = $this->is_author_allowed();

        if ($author === false) {
            return false;
        }

        if (is_super_admin()) {
            return $this->super_admin;
        } else if (is_user_logged_in()) {
            $allowed = $this->user_roles;

            if ($allowed === true || is_null($allowed)) {
                return true;
            } else if (is_array($allowed) && empty($allowed)) {
                return false;
            } else if (is_array($allowed) && !empty($allowed)) {
                global $current_user;

                if (is_array($current_user->roles)) {
                    $matched = array_intersect($current_user->roles, $allowed);

                    return !empty($matched);
                }
            }
        } else {
            return $this->visitor;
        }
    }

    public function is_author_allowed() {
        if (is_user_logged_in() && !$this->author) {
            $author_id = 0;
            $item = gdrts_single()->item();

            if ($item->entity == 'posts') {
                $author_id = $item->data->object->post_author;
            } else if ($item->entity == 'comments') {
                $author_id = $item->data->object->comment_author;
            }

            $author_id = apply_filters('gdrts_rating_item_author_id', absint($author_id), $item);

            if ($author_id > 0 && $author_id == get_current_user_id()) {
                return false;
            }
        }

        return true;
    }

    public function stats($from = '', $to = '') {
        return $this->user->get_log_stats_quick($this->item, $from, $to, $this->method, $this->series);
    }

    public function stats_anytime() {
        return $this->user->get_log_stats_quick($this->item, '', '', $this->method, $this->series);
    }

    public function has_voted($from = '', $to = '') {
        $stats = $this->stats($from, $to);

        return $stats['vote']['items'] + $stats['revote']['items'] > 0;
    }

    public function previous_vote() {
        $stats = $this->stats_anytime();

        if ($stats['revote']['log_id'] > 0) {
            return gdrts_cache()->get_log_entry($stats['revote']['log_id']);
        }

        if ($stats['vote']['log_id'] > 0) {
            return gdrts_cache()->get_log_entry($stats['vote']['log_id']);
        }

        return null;
    }

    public function active_vote() {
        $stats = $this->stats_anytime();

        if ($stats['revote']['log_id'] > 0) {
            return gdrts_cache()->get_log_entry($stats['revote']['log_id']);
        } else if ($stats['vote']['log_id'] > 0) {
            return gdrts_cache()->get_log_entry($stats['vote']['log_id']);
        }

        return null;
    }

    public function count_votes($from = '', $to = '') {
        $stats = $this->stats($from, $to);

        return $stats['vote']['items'];
    }

    public function count_revotes($from = '', $to = '') {
        $stats = $this->stats($from, $to);

        return $stats['revote']['items'];
    }
}
