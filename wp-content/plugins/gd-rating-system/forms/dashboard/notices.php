<?php if (!defined('ABSPATH')) { exit; } ?>

<?php

$_icons = array();
$_content = array();

if (gdrts_settings()->get('upgrade_to_40', 'core') === false) {
    $_icons[] = array(
        'class' => 'upgrade',
        'label' => __("Upgrade Required", "gd-rating-system")
    );

    $_content[] = __("GD Rating System Pro database upgrade process needs to be completed. Please, run the upgrade.", "gd-rating-system").' <a href="admin.php?page=gd-rating-system-tools&panel=dbfour">'.__("Click Here", "gd-rating-system").'</a>.';
}

if (gdrts_settings()->get('maintenance', 'core')) {
    $_icons[] = array(
        'class' => 'maintenance',
        'label' => __("Maintenance Mode", "gd-rating-system")
    );

    $_content[] = __("Plugin is currently in maintenance mode, all voting is disabled.", "gd-rating-system").' <a href="admin.php?page=gd-rating-system-settings&panel=maintenance">'.__("Maintenance Options", "gd-rating-system").'</a>.';
} else if (gdrts_settings()->get('voting_disabled', 'core')) {
    $_icons[] = array(
        'class' => 'maintenance',
        'label' => __("Voting Disabled", "gd-rating-system")
    );

    $_content[] = __("All voting is currently disabled.", "gd-rating-system").' <a href="admin.php?page=gd-rating-system-settings&panel=maintenance">'.__("Maintenance Options", "gd-rating-system").'</a>.';
}

if (empty($_icons)) {
    $_icons[] = array(
        'class' => 'ok',
        'label' => __("OK", "gd-rating-system")
    );
}

if (empty($_content)) {
    $_content[] = __("Everything appears to be in order.", "gd-rating-system");
}

?>

<div class="d4p-group d4p-group-dashboard-card d4p-group-dashboard-basic d4p-group-dashboard-status">
    <h3><?php _e("Plugin Status", "gd-rating-system"); ?></h3>
    <div class="d4p-group-inner">
        <?php foreach ($_icons as $_icon) { ?>
            <span class="gdrts-label gdrts-label-<?php echo $_icon['class']; ?>"><?php echo $_icon['label']; ?></span>
        <?php } ?>
        <strong><?php echo join('<br/>', $_content); ?></strong>
    </div>
</div>
