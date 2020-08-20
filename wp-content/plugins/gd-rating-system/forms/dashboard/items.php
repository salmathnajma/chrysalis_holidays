<?php if (!defined('ABSPATH')) { exit; } ?>

<div class="d4p-group d4p-group-dashboard-card d4p-group-dashboard-basic">
    <h3><?php _e("Rating Items", "gd-rating-system"); ?></h3>
    <div class="d4p-group-stats">
        <ul>

        <?php 

        $_data = gdrts_statistics()->get_entities_active_items();

        foreach ($_data as $entity => $obj) {
            $count = $obj['count'];

        ?><li><a href="admin.php?page=gd-rating-system-ratings&filter-entity=<?php echo $entity; ?>">
                <?php echo d4p_render_icon($obj['icon'], 'i', true, true) ?> 
                <strong><?php echo $obj['label']; ?></strong> 
                <?php echo sprintf(_n("%s item", "%s items", $count, "gd-rating-system"), $count); ?></a>
        </li><?php

        }

        ?>

        </ul><div class="d4p-clearfix"></div>
    </div>
    <div class="d4p-group-inner">
        <h4><?php _e("Important", "gd-rating-system"); ?></h4>
        <p>
            <?php _e("This overview counts only items that had any sort of rating related activity. Panel with rating items lists all registered items, and allows you to filter by item type and last activity period. Via Rating Types panel you can register new rating types.", "gd-rating-system"); ?>
        </p>
    </div>
    <div class="d4p-group-footer">
        <a href="<?php echo 'admin.php?page=gd-rating-system-types'; ?>" class="button-primary"><?php _e("All rating types", "gd-rating-system"); ?></a>
        <a href="<?php echo 'admin.php?page=gd-rating-system-ratings'; ?>" class="button-primary"><?php _e("All rating items", "gd-rating-system"); ?></a>
    </div>
</div>