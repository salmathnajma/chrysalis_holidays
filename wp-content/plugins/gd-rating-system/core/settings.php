<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core_settings extends d4p_plugin_settings_corex {
    public $base = 'gdrts';

    public $skip_update = array('items');
    public $skip_delete_on_update = array('addons', 'methods');

    public $settings = array(
        'core' => array(
            'upgrade_to_40' => false,
            'voting_disabled' => false,
            'voting_disabled_message' => 'Voting is currently disabled.',
            'maintenance' => true,
            'maintenance_message' => 'Voting is currently disabled, data maintenance in progress.',
            'cronjob_recheck_max_stars_rating' => false
        ),
        'early' => array(
            'custom_entities' => array(),
            'disable_widgets' => array()
        ),
        'load' => array(),
        'entities' => array(),
        'templates' => array(),
        'addons' => array(),
        'methods' => array(),
        'rules' => array(
        	'id' => 0,
	        'list' => array()
        ),
        'settings' => array(
            'load_on_demand' => true,
            'load_full_css' => false,
            'load_full_js' => false,
            'metaboxes_post_types' => null,
            'db_cache_time_global' => 86400,
            'db_cache_time_aggregate' => 86400,
            'db_cache_time_period' => 14400,
            'db_cache_time_query' => 14400,
            'db_cache_on_aggregate' => true,
            'db_cache_on_period' => true,
            'db_cache_on_query' => false,
            'fonticons_font' => true,
            'debug_rating_block' => false,
            'debug_files' => 'auto',
            'debug_ajax_error' => 'console',
            'cronjob_hour_of_day' => '2',
            'throttle_active' => false,
            'throttle_period' => 3,
            'use_nonce' => true,
            'annonymous_verify' => 'ip_or_cookie',
            'annonymous_same_ip' => true,
            'ajax_header_no_cache' => true,
            'decimal_round' => 2,
            'admin_log_remove' => false,
            'log_vote_user_agent' => false,
            'log_vote_ip_hashed' => false,
            'metabox_override' => false,
            'step_transfer' => 500,
            'step_recalculate' => 50
        ),
        'items' => array()
    );

    protected function constructor() {
        $this->info = new gdrts_core_info();

        add_action('gdrts_early_settings', array($this, 'early_init'));
        add_action('gdrts_load_settings', array($this, 'init'));
    }

    public function early_init() {
        $now = $this->_settings_get('early');

        if (!is_array($now)) {
            $now = $this->settings['early'];
        }

        $this->current['early'] = $now;
    }

    protected function _db() {
        wp_schedule_single_event(time(), 'gdrts_cron_daily_maintenance_job');

        require_once(GDRTS_PATH.'core/admin/install.php');

        gdrts_install_database();
        gdrts_update_database_tables_collations();
    }

	/** @param string $name
	  * @return string */
    protected function _name($name) {
        return 'dev4press_'.$this->info->code.'_'.$name;
    }

    public function items_get($prefix) {
        $results = array();

        foreach ($this->current['items'] as $key => $value) {
            if (substr($key, 0, strlen($prefix)) == $prefix) {
                $results[substr($key, strlen($prefix))] = $value;
            }
        }

        return $results;
    }

    public function set($name, $value, $group = 'settings', $save = false) {
        if (is_null($group) || empty($group) || is_null($name) || empty($name)) {
            return;
        }

        $old = isset($this->current[$group][$name]) ? $this->current[$group][$name] : null;

        if ($old != $value) {
            do_action('gdrts_settings_value_changed', $name, $group, $old, $value);
        }

        $this->current[$group][$name] = $value;

        if ($save) {
            $this->save($group);
        }
    }

    public function save_custom_entity($entity) {
        $this->current['early']['custom_entities'][$entity['name']] = $entity;

        $this->save('early');
    }

    public function delete_custom_entity($entity) {
        if (isset($this->current['early']['custom_entities'][$entity])) {
            unset($this->current['early']['custom_entities'][$entity]);
        }

        $this->save('early');
    }

    public function force_debug() {
        return $this->get('debug_files') == 'source';
    }

    public function get_rules($prefix) {
        $results = array();

        foreach ($this->current['items'] as $key => $value) {
            if (substr($key, 0, strlen($prefix)) == $prefix) {
                $results[substr($key, strlen($prefix))] = $value;
            }
        }

        return $results;
    }

    public function prefix_get($prefix, $group = 'settings') {
        $settings = array_merge(array_keys($this->settings[$group]), array_keys($this->current[$group]));

        $results = array();

        foreach ($settings as $key) {
            if (substr($key, 0, strlen($prefix)) == $prefix) {
                $results[substr($key, strlen($prefix))] = $this->get($key, $group);
            }
        }

        return $results;
    }

    /** @param string $item
      * @param string $object
      * @param string $method
      * @return int */
    public function new_rule($item, $object, $method = '') {
    	$rule = new gdrts_settings_rule();
    	$rule->item = $item;
    	$rule->object = $object;
    	$rule->method = $method;

    	return $this->save_rule($rule, true);
    }

	/** @param gdrts_settings_rule $rule
	  * @param bool $save
	  * @return int */
    public function save_rule($rule, $save = false) {
    	if ($rule->id == 0) {
		    $this->current['rules']['id']++;
		    $rule->id = $this->current['rules']['id'];
	    }

    	$rule_current = isset( $this->current['rules']['list'][$rule->id]) ? $this->current['rules']['list'][$rule->id] : false;
    	$rule_values = $rule->to_array();

        if (!empty($rule_values['settings']) && $rule_current !== false) {
            foreach ($rule_values['settings'] as $key => $val) {
                if (!isset($rule_current['settings'][$key]) || $val !== $rule_current['settings'][$key]) {
                    $old = isset($rule_current['settings'][$key]) ? $rule_current['settings'][$key] : null;
                    do_action('gdrts_rule_value_changed', $key, $rule, $old, $val);
                }
            }
        }

    	$this->current['rules']['list'][$rule->id] = $rule_values;

	    if ($save) {
		    $this->save('rules');
	    }

	    return $rule->id;
    }

	/** @param int $rule
	  * @return gdrts_settings_rule|bool */
	public function get_rule($rule) {
		$rule = absint($rule);

		if ($rule > 0 && isset($this->current['rules']['list'][$rule])) {
			return new gdrts_settings_rule($this->current['rules']['list'][$rule]);
		}

		return false;
    }

	/** @param int $rule
	  * @param bool $save */
	public function remove_rule($rule, $save = false) {
		$rule = absint($rule);

		if ($rule > 0 && isset($this->current['rules']['list'][$rule])) {
			unset($this->current['rules']['list'][$rule]);

			if ($save) {
				$this->save('rules');
			}
		}
	}

	/** @param int $rule
	  * @param bool $status
	  * @param bool $save */
	public function change_rule_status($rule, $status, $save = false) {
		$rule = absint($rule);
		$status = (bool)$status;

		if ($rule > 0 && isset($this->current['rules']['list'][$rule])) {
			$this->current['rules']['list'][$rule]['active'] = $status;

			if ($save) {
				$this->save('rules');
			}
		}
	}
}
