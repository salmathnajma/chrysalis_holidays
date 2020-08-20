<?php

if (!defined('ABSPATH')) { exit; }

gdrts_rescan_for_templates();

include(GDRTS_PATH.'forms/shared/top.php');

$panel = 'rules';
$_rule_id = absint($_GET['rule']);
$rule = gdrts_settings()->get_rule($_rule_id);

$_active_name = 'gdrtsvalue[rules]['.$rule->id.'][active]';
$_active_id = 'gdrtsvalue_rules_'.$rule->id.'_active';

?>

<form method="post" action="">
    <?php settings_fields('gd-rating-system-ruledit'); ?>
    <input type="hidden" value="postback" name="gdrts_handler" />

    <div class="d4p-content-left">
        <div class="d4p-panel-scroller d4p-scroll-active">
            <div class="d4p-panel-title">
                <i aria-hidden="true" class="fa fa-star-o"></i>
                <h3><?php _e("Rules", "gd-rating-system"); ?></h3>
                <h4><?php echo $rule->get_label(); ?></h4>
            </div>
            <div class="d4p-panel-info">
                <?php _e("These settings will be used before default settings. You can temporarally disable this rule override or you can remove them.", "gd-rating-system"); ?>
            </div>
            <div class="d4p-panel-buttons">
                <input type="submit" value="<?php _e("Save Settings", "gd-rating-system"); ?>" class="button-primary">
            </div>
            <div class="d4p-return-to-top">
                <a href="#wpwrap"><?php _e("Return to top", "gd-rating-system"); ?></a>
            </div>
        </div>
    </div>
    <div class="d4p-content-right">
        <div class="d4p-group d4p-group-about">
            <h3><?php _e("Rule Control", "gd-rating-system"); ?></h3>
            <div class="d4p-group-inner">
                <table class="form-table">
                    <tbody>
                        <tr valign="top">
                            <th scope="row"><?php _e("Status", "gd-rating-system"); ?></th>
                            <td>
                                <div class="d4p-setting-bool">
                                    <label for="<?php echo $_active_id; ?>">
                                        <input<?php echo $rule->active ? ' checked="checked"' : ''; ?> type="checkbox" class="widefat" id="<?php echo $_active_id; ?>" name="<?php echo $_active_name; ?>">Enabled
                                    </label>
                                    <em><?php _e("Rule will remain in the database, but it will not be used if it is not enabled.", "gd-rating-system"); ?></em>
                                </div>
                            </td>
                        </tr>
                        <tr valign="top"><td colspan="2"><div class="d4p-setting-hr"><hr></div></td></tr>
                        <tr valign="top">
                            <th scope="row"> </th>
                            <td>
                                <div class="d4p-setting-select">
                                    <a class="button-primary gdrts-action-delete-rule" href="<?php echo self_admin_url('admin.php?page=gd-rating-system-rules&gdrts_handler=getback&single-action=remove-rule&rule='.$rule->id.'&_wpnonce='.$rule->get_nonce()); ?>"><?php _e("Remove this rule", "gd-rating-system"); ?></a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d4p-group-divider"><?php _e("Rule Settings", "gd-rating-system"); ?></div>

        <?php

        d4p_includes(array(
            array('name' => 'functions', 'directory' => 'admin'), 
            array('name' => 'settings', 'directory' => 'admin')
        ), GDRTS_D4PLIB);

        $groups = apply_filters('gdrts_admin_get_rule_options_'.$rule->object, array(), $rule);

        $render = new d4pSettingsRender($panel, $groups);
        $render->base = 'gdrtsvalue';
        $render->render();

        ?>

        <div class="clear"></div>
        <div style="padding-top: 15px; border-top: 1px solid #777; max-width: 800px;">
            <input type="submit" value="<?php _e("Save Settings", "gd-rating-system"); ?>" class="button-primary">
        </div>
    </div>
</form>

<?php 

include(GDRTS_PATH.'forms/shared/bottom.php');
include(GDRTS_PATH.'forms/dialogs/rules.php');
