<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core_rules {
	private $rules = array();
	private $match = array();

	private $filters = false;

	public function __construct() {}

	public static function get_instance() {
		static $_instance = false;

		if ($_instance === false) {
			$_instance = new gdrts_core_rules();
		}

		return $_instance;
	}

	public function filters_allowed() {
		return $this->filters;
	}

	public function get_rule_settings($obj, $entity = null, $name = null, $method = false, $filters = null) {
		$object = $obj->prefix;

		$entity = is_null($entity) ? gdrts_single()->args('entity') : $entity;
		$name = is_null($name) ? gdrts_single()->args('name') : $name;

        $filters = array();

		$_key_base = $method !== false ? $method.'-' : '';

		$match_key_partial = $_key_base.$object.'-'.$entity;
		$match_key = $match_key_partial.'.'.$name;

		if (!isset($this->match[$match_key]) && !isset($this->match[$match_key_partial])) {
			$this->find_rules($object, $entity, $name, $method);
		}

		if (empty($this->rules) || empty($this->match)) {
            $_settings = $this->method_based_settings($obj, $method);
            $_settings['_rule_matched'] = false;
        } else {
            $id = false;

            if (isset($this->match[$match_key])) {
                $id = $this->match[$match_key][0];
            } else if (isset($this->match[$match_key_partial])) {
                $id = $this->match[$match_key_partial][0];
            }

            if ($id !== false && isset($this->rules[$id])) {
                $_settings = $this->rules[$id]->settings;
                $_settings['_rule_matched'] = true;
            } else {
                $_settings = $this->method_based_settings($obj, $method);
                $_settings['_rule_matched'] = false;
            }
        }

		return $_settings;
	}

	protected function find_rules($object, $entity, $name, $method = false) {
		foreach (gdrts_settings()->current['rules']['list'] as $id => $rule) {
			if ($rule['active'] && $rule['object'] == $object && ($method === false || $rule['method'] == $method)) {
			    $key = $method !== false ? $method.'-' : '';
			    $key.= $object.'-'.$entity;

				if ($rule['item'] == $entity.'.'.$name) {
					$key.= '.'.$name;

					$this->rules[$id] = new gdrts_settings_rule($rule);

					if (!isset($this->match[$key])) {
						$this->match[$key] = array();
					}

					$this->match[$key][] = $id;
				} else if ($rule['item'] == $entity) {
					$this->rules[$id] = new gdrts_settings_rule($rule);

					if (!isset($this->match[$key])) {
						$this->match[$key] = array();
					}

					$this->match[$key][] = $id;
				}
			}
		}
	}

	protected function method_based_settings($object, $method = false) {
	    if ($object->extension_prop('method_settings', false) && $method !== false) {
	        $settings = array();

	        foreach ($object->settings as $key => $value) {
	            if (strpos($key, $method.'_') === 0) {
	                $settings[substr($key, strlen($method.'-'))] = $value;
                }
            }

	        return wp_parse_args($settings, $object->get_default_settings());
        }

	    return $object->settings;
    }
}

/** @return gdrts_core_rules */
function gdrts_rules() {
	return gdrts_core_rules::get_instance();
}
