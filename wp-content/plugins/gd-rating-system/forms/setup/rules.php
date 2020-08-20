<?php if (!defined('ABSPATH')) { exit; } ?>

<h3><?php _e("Rules Settings", "gd-rating-system"); ?></h3>
<?php

if (!empty(gdrts_settings()->current['items'])) {
	include(GDRTS_PATH.'forms/setup/rules-legacy.php');

	foreach ($available as $item) {
        $key = $item['item'].'_'.$item['obj'].'_';

        if (!empty($item['method'])) {
            $key.= $item['method'].'_';
        }

        $active = gdrts_settings()->get($key.'rule_active', 'items');
        $settings = gdrts_settings()->prefix_get($key, 'items');

        unset($settings['rule_active']);

        $rule = new gdrts_settings_rule();

        $rule->item = $item['item'];
        $rule->object = $item['obj'];
        $rule->method = $item['method'];
        $rule->active = $active;
        $rule->settings = $settings;

		gdrts_settings()->save_rule($rule);
    }

	gdrts_settings()->current['items'] = array();

	gdrts_settings()->save('items');
	gdrts_settings()->save('rules');

	_e("Rules conversion completed.", "gd-rating-system");
} else {
	_e("No rules found to convert.", "gd-rating-system");
}
