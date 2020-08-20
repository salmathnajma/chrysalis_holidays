<?php

if (!defined('ABSPATH')) { exit; }

$items = gdrts_list_all_entities();

$objects = array();
$groups = array();

$values = array();
foreach (gdrts()->methods as $method => $obj) {
	if (isset($obj['override']) && $obj['override'] && gdrts_is_method_loaded($method)) {
		$values[$method] = $obj['label'];
	}
}

if (!empty($values)) {
	$objects[] = array('title' => __("Methods", "gd-rating-system"), 'values' => $values, 'scope' => 'method');
}

$values = array();
foreach (gdrts()->addons as $addon => $obj) {
	if (isset($obj['override']) && $obj['override'] && gdrts_is_addon_loaded($addon)) {
		$values[$addon] = $obj['label'];

		if ($obj['override_basic']) {
			$groups[$addon] = false;
		} else {
			if (isset($obj['override_methods']) && ($obj['override_methods'] === true || !empty($obj['override_methods']))) {
				$groups[$addon] = gdrts()->expand_methods_with_series(apply_filters('gdrts_rules_addon_'.$addon.'_methods', $obj['override_methods']));
			}
		}
	}
}

if (!empty($values)) {
	$objects[] = array('title' => __("Addons", "gd-rating-system"), 'values' => $values, 'scope' => 'addon');
}

$available = array();

foreach ($items as $group) {
	foreach ($group['values'] as $_item => $_label_item) {
		foreach ($objects as $objs) {
			$is_addon = $objs['scope'];

			foreach ($objs['values'] as $_objc => $_label_objc) {
				if ($is_addon && !empty($groups[$_objc])) {
					foreach ($groups[$_objc] as $_method) {
						$the_method = gdrts()->convert_method_series_pair($_method);

						if (gdrts_is_method_loaded($the_method['method'])) {
							$key = $_item.'_'.$_objc.'_'.$_method.'_rule_active';
							$ava = gdrts_settings()->get($key, 'items');

							$_label_method = $the_method['label'];

							if (!is_null($ava)) {
								$available[$key] = array(
									'label' => '<strong>'.$_label_objc.'</strong> ('.$_label_method.') '.__("for", "gd-rating-system").' <strong>'.$_label_item.'</strong>',
									'item' => $_item, 'obj' => $_objc, 'method' => $_method, 'active' => $ava
								);
							}
						}
					}
				} else {
					$key = $_item.'_'.$_objc.'_rule_active';
					$ava = gdrts_settings()->get($key, 'items');

					if (!is_null($ava)) {
						$available[$key] = array(
							'label' => '<strong>'.$_label_objc.'</strong> '.__("for", "gd-rating-system").' <strong>'.$_label_item.'</strong>',
							'item' => $_item, 'obj' => $_objc, 'method' => '', 'active' => $ava
						);
					}
				}
			}
		}
	}
}
