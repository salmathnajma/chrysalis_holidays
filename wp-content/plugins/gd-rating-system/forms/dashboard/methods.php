<?php if (!defined('ABSPATH')) { exit; } ?>

<div class="d4p-group d4p-group-dashboard-card d4p-group-dashboard-basic">
    <h3><?php _e("Rating Methods", "gd-rating-system"); ?></h3>
    <div class="d4p-group-stats">
        <ul>

        <?php 

        $_data = gdrts_list_all_methods();

        foreach (gdrts()->methods as $method => $obj) {

        ?><li><a href="admin.php?page=gd-rating-system-settings&panel=method_<?php echo $method; ?>">
                <?php echo d4p_render_icon($obj['icon'], 'i', true, true) ?> 
                <strong><?php echo $obj['label']; ?></strong></a>
        </li><?php

        }

        ?>

        </ul><div class="d4p-clearfix"></div>
    </div>
    <div class="d4p-group-inner">
        <h4><?php _e("Important", "gd-rating-system"); ?></h4>
        <p>
            <?php _e("Each method has global configuration linked from this panel, and it allows you to configure it for specific rating type and override the default (or global) settings. Some addons have similar settings override support.", "gd-rating-system"); ?>
        </p>
    </div>
    <div class="d4p-group-footer">
        <a href="admin.php?page=gd-rating-system-settings" class="button-primary"><?php _e("All plugin settings", "gd-rating-system"); ?></a>
        <a href="admin.php?page=gd-rating-system-rules" class="button-primary"><?php _e("Settings rules", "gd-rating-system"); ?></a>
    </div>
</div>