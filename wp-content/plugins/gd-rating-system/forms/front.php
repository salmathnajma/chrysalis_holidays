<?php

if (!defined('ABSPATH')) { exit; }

include(GDRTS_PATH.'forms/shared/top.php');

require_once(GDRTS_PATH.'rating/core.statistics.php');

?>

<div class="d4p-plugin-dashboard">
    <div class="d4p-content-left">
        <div class="d4p-dashboard-badge" style="background-color: #262261">
            <div aria-hidden="true" class="d4p-plugin-logo"><i class="d4p-icon d4p-plugin-icon-gd-rating-system"></i></div>
            <h3>GD Rating System</h3>

            <h5>
                <?php 

                _e("Version", "gd-rating-system");
                echo': '.gdrts_settings()->info->version;

                if (gdrts_settings()->info->status != 'stable') {
                    echo ' - <span class="d4p-plugin-unstable" style="color: #fff; font-weight: 900;">'.strtoupper(gdrts_settings()->info->status).'</span>';
                }

                ?>

            </h5>
        </div>

        <div class="d4p-buttons-group">
            <a class="button-secondary" href="admin.php?page=gd-rating-system-settings"><i aria-hidden="true" class="fa fa-cogs fa-fw"></i> <?php _e("Settings", "gd-rating-system"); ?></a>
            <a class="button-secondary" href="admin.php?page=gd-rating-system-rules"><i aria-hidden="true" class="fa fa-star-o fa-fw"></i> <?php _e("Rules", "gd-rating-system"); ?></a>
            <a class="button-secondary" href="admin.php?page=gd-rating-system-types"><i aria-hidden="true" class="fa fa-thumb-tack fa-fw"></i> <?php _e("Rating Types", "gd-rating-system"); ?></a>
            <a class="button-secondary" href="admin.php?page=gd-rating-system-tools"><i aria-hidden="true" class="fa fa-wrench fa-fw"></i> <?php _e("Tools", "gd-rating-system"); ?></a>
        </div>

        <div class="d4p-buttons-group">
            <a class="button-secondary" href="admin.php?page=gd-rating-system-information"><i aria-hidden="true" class="fa fa-info-circle fa-fw"></i> <?php _e("Information", "gd-rating-system"); ?></a>
            <a class="button-secondary" href="admin.php?page=gd-rating-system-about"><i aria-hidden="true" class="fa fa-info-circle fa-fw"></i> <?php _e("About", "gd-rating-system"); ?></a>
        </div>
    </div>
    <div class="d4p-content-right">
        <?php

        include(GDRTS_PATH.'forms/dashboard/notices.php');

        do_action('gdrts_dashboard_content_blocks_before');

        include(GDRTS_PATH.'forms/dashboard/votes.php');
        include(GDRTS_PATH.'forms/dashboard/pro.php');
        include(GDRTS_PATH.'forms/dashboard/methods.php');
        include(GDRTS_PATH.'forms/dashboard/items.php');

        do_action('gdrts_dashboard_content_blocks_after');

        ?>
        <div class="d4p-clearfix"></div>
        <?php ?>
    </div>
</div>

<?php 

include(GDRTS_PATH.'forms/shared/bottom.php');
