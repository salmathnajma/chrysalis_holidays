<?php

include(GDRTS_PATH.'forms/shared/top.php');

require_once(GDRTS_PATH.'addons/shortcode-builder/shortcodes.php');

$_panel = gdrts_admin()->panel;
if ($_panel === false || empty($_panel)) {
    $_panel = 'index';
}

$shortcodes = new gdrts_shortcode_builder_list();
$shortcodes->init();

$groups = array();
foreach ($shortcodes->shortcodes as $obj) {
    $group = $obj['group'];
    $icon = $obj['item_icon'];

    if (!isset($groups[$group])) {
        $groups[$group] = $icon;
    }
}

$shortcode = $_panel == 'index' ? false : $shortcodes->shortcodes[$_panel];

?>

<div class="d4p-content-left">
    <div class="d4p-panel-scroller d4p-scroll-active">
        <div class="d4p-panel-title">
            <i aria-hidden="true" class="fa fa-code"></i>
            <h3><?php _e("Shortcodes Builder", "gd-rating-system"); ?></h3>
            <?php if ($_panel != 'index') { ?>
                <h4 style="margin-bottom: 5px">
                    <i aria-hidden="true" style="margin-top: 4px" class="dashicons <?php echo $shortcode['item_icon']; ?>"></i> <?php echo $shortcode['group']; ?>:
                    <br/><?php echo $shortcode['label']; ?></h4>
                <?php echo $shortcode['description']; ?>
            <?php } ?>
        </div>
        <?php if ($_panel != 'index') { ?>
        <div class="d4p-panel-info">
            <a style="width: 100%; text-align: center;" href="admin.php?page=gd-rating-system-shortcodes" class="button-secondary"><?php _e("All Shortcodes", "gd-rating-system"); ?></a>
        </div>
        <?php } ?>
    </div>
</div>
<div class="d4p-content-right">
    <?php

    if ($_panel == 'index') {
        foreach ($groups as $group => $icon) {
            ?><div style="clear: both"></div>
    <div class="d4p-panel-break d4p-clearfix">
        <h4><span class="dashicons <?php echo $icon; ?>"></span> <?php echo $group; ?></h4>
    </div>
    <div style="clear: both"></div><?php
            foreach ($shortcodes->shortcodes as $scode => $obj) {
                if ($obj['group'] == $group) {
                    $url = 'admin.php?page=gd-rating-system-shortcodes&panel='.$scode;

                    ?><div class="d4p-options-panel d4p-options-shortcode">
                    <a class="button-primary" href="<?php echo $url; ?>"><?php _e("Build", "gd-rating-system"); ?></a>
                    <h5 data-balloon-pos="up-left" data-balloon-length="large" aria-label="<?php echo $obj['description']; ?>"><?php echo $obj['label']; ?></h5>
                    </div><?php
                }
            }
        }
    } else {
        include('shortcode.php');
    }

    ?>
</div>