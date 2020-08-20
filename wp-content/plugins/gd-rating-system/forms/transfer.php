<?php

if (!defined('ABSPATH')) { exit; }

$panels = array(
    'index' => array(
        'title' => __("Transfer Data Index", "gd-rating-system"), 'icon' => 'exchange', 
        'info' => __("All transfer tools are split into several panels, and you access each starting from the right.", "gd-rating-system")),
    'gd-star-rating' => array(
        'title' => __("Standard Ratings", "gd-rating-system"), 'icon' => 'cloud-download', 
        'break' => __("GD Star Rating", "gd-rating-system"), 
        'button' => 'button', 'button_text' => __("Transfer", "gd-rating-system"),
        'info' => __("Import data from GD Star Rating plugin.", "gd-rating-system")),
    'wp-postratings' => array(
        'title' => 'WP PostRatings', 'icon' => 'cloud-download', 
        'break' => __("Other Plugins", "gd-rating-system"), 
        'button' => 'button', 'button_text' => __("Transfer", "gd-rating-system"),
        'info' => __("Import data from WP PostRatings plugin.", "gd-rating-system")),
    'yet-another-stars-rating' => array(
        'title' => 'YASR', 'icon' => 'cloud-download', 
        'button' => 'button', 'button_text' => __("Transfer", "gd-rating-system"),
        'info' => __("Import data from Yet Another Stars Rating plugin.", "gd-rating-system")),
    'kk-star-ratings' => array(
        'title' => 'KK Star Ratings', 'icon' => 'cloud-download', 
        'button' => 'button', 'button_text' => __("Transfer", "gd-rating-system"),
        'info' => __("Import data from KK Star Ratings plugin.", "gd-rating-system"))
);

include(GDRTS_PATH.'forms/shared/top.php');

?>

<form method="post" action="">
    <div class="d4p-content-left">
        <div class="d4p-panel-scroller d4p-scroll-active">
            <div class="d4p-panel-title">
                <i aria-hidden="true" class="fa fa-exchange"></i>
                <h3><?php _e("Transfer Data", "gd-rating-system"); ?></h3>
                <?php if ($_panel != 'index') { ?>
                <h4><?php echo d4p_render_icon($panels[$_panel]['icon'], 'i', true); ?> <?php echo $panels[$_panel]['title']; ?></h4>
                <?php } ?>
            </div>
            <div class="d4p-panel-info">
                <?php echo $panels[$_panel]['info']; ?>
            </div>
            <?php if ($_panel != 'index' && $panels[$_panel]['button'] != 'none') { ?>
                <div class="d4p-panel-buttons">
                    <input id="gdrts-tool-<?php echo $_panel; ?>" class="button-primary" type="<?php echo $panels[$_panel]['button']; ?>" value="<?php echo $panels[$_panel]['button_text']; ?>" />
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="d4p-content-right">
        <?php

        if ($_panel == 'index') {
            foreach ($panels as $panel => $obj) {
                if ($panel == 'index') continue;

                $url = 'admin.php?page=gd-rating-system-'.$_page.'&panel='.$panel;

                if (isset($obj['break'])) { ?>

                    <div style="clear: both"></div>
                    <div class="d4p-panel-break d4p-clearfix">
                        <h4><?php echo $obj['break']; ?></h4>
                    </div>
                    <div style="clear: both"></div>

                <?php } ?>

                <div class="d4p-options-panel">
                    <?php echo d4p_render_icon($obj['icon'], 'i', true); ?>
                    <h5><?php echo $obj['title']; ?></h5>
                    <div>
                        <a class="button-primary" href="<?php echo $url; ?>"><?php _e("Transfer Panel", "gd-rating-system"); ?></a>
                    </div>
                </div>

                <?php
            }
        } else {
            include(GDRTS_PATH.'forms/transfer/'.$_panel.'.php');
        }

        ?>
    </div>
</form>

<?php 

include(GDRTS_PATH.'forms/shared/bottom.php');
