<?php if (!defined('ABSPATH')) { exit; } ?>

<div class="d4p-group d4p-group-dashboard-card d4p-group-dashboard-basic">
    <h3><?php _e("Votes", "gd-rating-system"); ?></h3>
    <div class="d4p-group-stats">
        <ul>

        <?php 

        $_data = gdrts_statistics()->get_total_votes_counts();

        ?>

        <li><a href="admin.php?page=gd-rating-system-log">
                <?php echo d4p_render_icon('flag', 'i', true, true) ?> 
                <?php echo sprintf(_n("<strong>%s</strong> Vote total", "<strong>%s</strong> Votes total", $_data['total'], "gd-rating-system"), $_data['total']); ?></a>
        </li>

        <li><a href="admin.php?page=gd-rating-system-log&filter-user_id=0">
                <?php echo d4p_render_icon('flag-o', 'i', true, true) ?> 
                <?php echo sprintf(_n("<strong>%s</strong> Vote anonymous", "<strong>%s</strong> Votes anonymous", $_data['visitors'], "gd-rating-system"), $_data['visitors']); ?></a>
        </li>

        <?php

        ?>

        </ul><div class="d4p-clearfix"></div>
    </div>
    <div class="d4p-group-inner">
        <h4><?php _e("Recent Votes", "gd-rating-system"); ?></h4>

<?php

$limit = apply_filters('gdrts_dashboard_votes_list_limit', 12);
$logs = gdrts_db()->get_latest_log_items($limit);

if (empty($logs)) {
    _e("There are no ratings logged.", "gd-rating-system");
} else {

    ?>

<ul class="gdrts-dashboard-ratings">

    <?php

foreach ($logs as $log) {
    if (gdrts_is_method_loaded($log->method)) {
        $_multis = gdrts_db()->get_log_entry($log->log_id);

        $log->meta = gdrts_db()->get_log_meta($log->log_id);
        $log->data = gdrts_db()->get_item($log->item_id);
        $log->multis = isset($_multis->multi) ? $_multis->multi : array();

        include(GDRTS_PATH.'forms/shared/dashboard-vote.php');
    }
}
    
    ?>

</ul>

    <?php
}

?>
        
    </div>
    <div class="d4p-group-footer">
        <a href="<?php echo 'admin.php?page=gd-rating-system-log'; ?>" class="button-primary"><?php _e("Votes log", "gd-rating-system"); ?></a>
    </div>
</div>