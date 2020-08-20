<?php if (!defined('ABSPATH')) { exit; } ?>

<li>
    <div class="gdrts-vote">
        <strong style="float: right"><a href="<?php echo admin_url('admin.php?page=gd-rating-system-log&filter-method='.$log->method); ?>"><?php echo gdrts_get_method_label($log->method); ?></a></strong>
        <?php echo apply_filters('gdrts_votes_grid_vote_'.$log->method, '', $log); ?> &middot; 
        <?php

        $_entity = gdrts()->get_entity($log->data->entity);

        ?>
        <a href="<?php echo admin_url('admin.php?page=gd-rating-system-log&filter-entity='.$log->data->entity); ?>"><?php echo $_entity['label']; ?></a> &middot; 
        <?php

        $label = '';

        if (isset($_entity['types'][$log->data->name])) {
            $label = $_entity['types'][$log->data->name];
        } else {
            $label = $log->data->name.' <strong style="color: red">('.__("missing", "gd-rating-system").')</strong>';
        }

        ?>
        <a href="<?php echo admin_url('admin.php?page=gd-rating-system-log&filter-entity='.$log->data->entity.'.'.$log->data->name); ?>"><?php echo $label; ?></a> &middot; 
        <a href="<?php echo admin_url('admin.php?page=gd-rating-system-log&filter-item_id='.$log->item_id); ?>"><?php echo $log->item_id; ?></a>
    </div>
    <div class="gdrts-voter">
        <span style="float: right"><?php echo sprintf(__("%s ago", "gd-rating-system"), human_time_diff(mysql2date('U', $log->logged))); ?></span>
        <?php

        $user = __("by", "gd-rating-system").' <strong><a href="'.admin_url('admin.php?page=gd-rating-system-log&filter-user_id='.$log->user_id.'&filter-method='.$log->method).'">';

        if ($log->user_id == 0) {
            $user.= __("Visitor", "gd-rating-system");
        } else {
            $u = get_user_by('id', $log->user_id);

            if ($u) {
                $user .= $u->display_name;
            } else {
                $user.= __("Unknown", "gd-rating-system");
            }
        }

        $user.= '</a></strong>';

        echo $user;

        if (!gdrts_using_hashed_ip()) {
            echo ' &middot; ';
            echo __("from", "gd-rating-system").' '.$log->ip;
        }

        ?>
    </div>
</li>