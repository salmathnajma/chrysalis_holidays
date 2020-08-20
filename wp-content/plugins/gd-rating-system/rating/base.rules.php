<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_settings_rule {
	public $id = 0;

	public $active = false;
	public $item = '';
	public $object = '';
	public $method = '';

	public $filters = array();
	public $settings = array();

	public function __construct($rule = array()) {
		if (!empty($rule)) {
			foreach (array('id', 'active', 'item', 'object', 'method', 'filters', 'settings') as $key) {
				if (isset($rule[$key])) {
					$this->$key = $rule[$key];
				}
			}
		}
	}

	public function to_array() {
		return (array)$this;
	}

	public function is_available() {
		$available = true;

		if (!empty($this->method)) {
			$_method = $this->get_method();

			$available = gdrts_is_method_loaded($_method['method']);
		}

		if ($available) {
			if ($this->get_type_name() === false) {
				if (!gdrts()->has_entity($this->get_entity_name())) {
					$available = false;
				}
			} else {
				if (!gdrts()->has_entity_type($this->get_entity_name(), $this->get_type_name())) {
					$available = false;
				}
			}
		}

		if ($available) {
			if (!gdrts_is_addon_loaded($this->object) && !gdrts_is_method_valid($this->object)) {
				$available = false;
			}
		}

		return $available;
	}

	public function has_settings() {
		return !empty($this->settings);
	}

	public function get($name, $default = false) {
		return isset($this->settings[$name]) ? $this->settings[$name] : $default;
	}

	public function get_entity_name() {
		$_parts = explode('.', $this->item);

		return $_parts[0];
	}

	public function get_type_name() {
		$_parts = explode('.', $this->item);

		return isset($_parts[1]) ? $_parts[1] : false;
	}

	public function get_entity() {
		return gdrts()->get_entity($this->get_entity_name());
	}

	public function get_method() {
		if (!empty($this->method)) {
			return gdrts()->convert_method_series_pair($this->method);
		}

		return null;
	}

	public function get_nonce() {
		return wp_create_nonce('gdrts-rule-'.$this->id);
	}

	public function get_label() {
		if ($this->is_available()) {
			$label = '<strong>'.gdrts()->get_object_label($this->object).'</strong>';

			if (!empty($this->method)) {
				$_method = $this->get_method();

				$label.= ' ('.$_method['label'].')';
			}

			$label.= ' '.__("for", "gd-rating-system").' ';
			$label.= '<strong>'.gdrts()->get_entity_type_label($this->get_entity_name(), $this->get_type_name()).'</strong>';
		} else {
			$label = '<strong>'.$this->object.'</strong>';

			if (!empty($this->method)) {
				$label.= ' ('.$this->method.')';
			}

			$label.= ' '.__("for", "gd-rating-system").' ';
			$label.= '<strong>'.$this->item.'</strong>';
		}

		return $label;
	}

	public function get_icons_for_display() {
		$icons = array(
			d4p_render_icon(apply_filters('gdrts_admin_icon_'.$this->object, 'flash'))
		);

		if (!empty($this->method)) {
			$_method = $this->get_method();
			$icons[] = d4p_render_icon(apply_filters('gdrts_admin_icon_'.$_method['method'], 'flash'));
		}

		$_entity = $this->get_entity();
		$icons[] = d4p_render_icon($_entity['icon']);

		return join(' &middot; ', $icons);
	}
}
