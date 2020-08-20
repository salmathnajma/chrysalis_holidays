<?php

if (!defined('ABSPATH')) { exit; }

include(GDRTS_PATH.'forms/shared/top.php');

$items = gdrts_list_all_entities();
$available = gdrts_settings()->current['rules']['list'];

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

?>

<div class="d4p-content-left">
    <div class="d4p-panel-scroller d4p-scroll-active">
        <div class="d4p-panel-title">
            <i aria-hidden="true" class="fa fa-star-o"></i>
            <h3><?php _e("Rules", "gd-rating-system"); ?></h3>
        </div>
        <div class="d4p-panel-info">
            <?php _e("You can create override rules for every rating content type registered with the plugin. Overrides can be added for rating methods and available addons.", "gd-rating-system"); ?>
        </div>
    </div>
</div>
<div class="d4p-content-right">
    <div class="d4p-group d4p-group-about">
        <h3><?php _e("Add new Rule", "gd-rating-system"); ?></h3>
        <div class="d4p-group-inner">
            <form method="post" action="">
                <?php settings_fields('gd-rating-system-newrule'); ?>
                <input type="hidden" value="postback" name="gdrts_handler" />

                <table class="form-table">
                    <tbody>
                        <tr valign="top">
                            <th scope="row"><?php _e("Item Type", "gd-rating-system"); ?></th>
                            <td>
                                <div class="d4p-setting-select">
                                    <?php gdrts_render_grouped_select($items, array('name' => 'item', 'class' => 'widefat')); ?>
                                </div>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e("Object Type", "gd-rating-system"); ?></th>
                            <td>
                                <div class="d4p-setting-select">
                                    <?php gdrts_render_grouped_select($objects, array('name' => 'object', 'class' => 'widefat')); ?>
                                </div>
                                <?php foreach ($groups as $_addon => $_methods) {
                                    if ($_methods === false) {
                                        echo '<input type="hidden" name="'.$_addon.'[method]" value="-1" />';
                                    } else {
                                    
                                    ?>
                                    <div class="gdrts-addon-methods-group gdrts-addon-group-<?php echo $_addon; ?>" style="display: none">
                                        <span style="display: block; margin: 6px 0 3px;"><?php _e("For Rating Method", "gd-rating-system"); ?></span>
                                        <?php

                                        $values = array();
                                        foreach ($_methods as $method) {
                                            $the_method = gdrts()->convert_method_series_pair($method);

                                            if (gdrts_is_method_loaded($the_method['method'])) {
                                                $values[$method] = $the_method['label'];
                                            }
                                        }

                                        if (empty($values)) {
                                            d4p_render_select(array('0' => __("Supported methods not available", "gd-rating-system")), array('selected' => '0', 'name' => $_addon.'[method]', 'class' => 'widefat'));
                                        } else {
                                            d4p_render_select($values, array('name' => $_addon.'[method]', 'class' => 'widefat'));
                                        }

                                        ?>
                                    </div>
                                <?php } } ?>
                            </td>
                        </tr>
                        <tr valign="top"><td colspan="2"><div class="d4p-setting-hr"><hr></div></td></tr>
                        <tr valign="top">
                            <th scope="row"> </th>
                            <td>
                                <div class="d4p-setting-select">
                                    <input type="submit" class="button-primary" value="<?php _e("Override Settings", "gd-rating-system"); ?>" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </form>
        </div>
    </div>

    <div class="d4p-group d4p-group-about">
        <h3><?php _e("Current Rules", "gd-rating-system"); ?></h3>
        <div class="d4p-group-inner">
            <?php

                if (empty($available)) {
                    _e("You don't have any custom rating rules created.", "gd-rating-system");
                } else {
                    foreach ($available as $av) {
                        $rule = new gdrts_settings_rule($av);

                        $_rule_available = $rule->is_available();
	                    $_edit_nonce = $rule->get_nonce();
	                    $_the_classes = array(
                            'gdrts-rule-block',
                            'gdrts-rule-'.($rule->active ? 'enabled' : 'disabled'),
                            'gdrts-available-'.($_rule_available ? 'yes' : 'no')
                        );

	                    echo '<div class="'.join(' ', $_the_classes).'">';
                            echo '<span class="gdrts-rule-icons" aria-hidden="true">'.$rule->get_icons_for_display().'</span>';
	                        echo '<span class="gdrts-rule-title"><strong>'.$rule->id.'</strong> &middot; '.$rule->get_label().'</span>';
	                        echo '<span class="gdrts-rule-actions">';
                                if ($_rule_available) {
                                    echo '<a class="button-primary" href="' . self_admin_url( 'admin.php?page=gd-rating-system-rules&action=rule&rule=' . $rule->id ) . '">' . __( "edit", "gd-rating-system" ) . '</a>';

                                    if ( $av['active'] ) {
                                        echo '<a class="button-secondary" href="' . self_admin_url( 'admin.php?page=gd-rating-system-rules&gdrts_handler=getback&single-action=disable-rule&rule=' . $rule->id . '&_wpnonce=' . $_edit_nonce ) . '">' . __( "disable", "gd-rating-system" ) . '</a>';
                                    } else {
                                        echo '<a class="button-secondary" href="' . self_admin_url( 'admin.php?page=gd-rating-system-rules&gdrts_handler=getback&single-action=enable-rule&rule=' . $rule->id . '&_wpnonce=' . $_edit_nonce ) . '">' . __( "enable", "gd-rating-system" ) . '</a>';
                                    }
                                }

                                echo '<a class="button-secondary gdrts-action-delete-rule" href="'.self_admin_url('admin.php?page=gd-rating-system-rules&gdrts_handler=getback&single-action=remove-rule&rule='.$rule->id.'&_wpnonce='.$_edit_nonce).'">'.__("delete", "gd-rating-system").'</a>';
	                        echo '</span>';
	                    echo '</div>';
                    }
                }

            ?>
        </div>
    </div>
</div>

<?php 

include(GDRTS_PATH.'forms/shared/bottom.php');
include(GDRTS_PATH.'forms/dialogs/rules.php');
