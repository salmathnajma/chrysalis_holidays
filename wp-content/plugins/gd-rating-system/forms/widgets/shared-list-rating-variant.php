<?php if (!defined('ABSPATH')) { exit; } ?>

<h4><?php _e("Rating Content", "gd-rating-system"); ?></h4>
<table>
    <tbody>
        <tr>
            <td class="cell-singular">
                <label for="<?php echo $this->get_field_id('variant_type'); ?>">
                    <input<?php echo in_array('type', $instance['variant']) ? ' checked="checked"' : ''; ?> type="checkbox" value="on" name="<?php echo $this->get_field_name('variant_type'); ?>" id="<?php echo $this->get_field_id('variant_type'); ?>" />
                    <span><?php _e("Automatically detect active post type", "gd-rating-system"); ?></span>
                </label>
            </td>
        </tr>
        <tr>
            <td class="cell-singular" style="padding-left: 25px">
                <label for="<?php echo $this->get_field_id('variant_hide'); ?>">
                    <input<?php echo in_array('hide', $instance['variant']) ? ' checked="checked"' : ''; ?> type="checkbox" value="on" name="<?php echo $this->get_field_name('variant_hide'); ?>" id="<?php echo $this->get_field_id('variant_hide'); ?>" />
                    <span><?php _e("Show empty list if the post type can't be detected", "gd-rating-system"); ?></span>
                </label>
            </td>
        </tr>
        <tr>
            <td class="cell-singular">
                <label for="<?php echo $this->get_field_id('variant_term'); ?>">
                    <input<?php echo in_array('term', $instance['variant']) ? ' checked="checked"' : ''; ?> type="checkbox" value="on" name="<?php echo $this->get_field_name('variant_term'); ?>" id="<?php echo $this->get_field_id('variant_term'); ?>" />
                    <span><?php _e("Automatically detect active term", "gd-rating-system"); ?></span>
                </label>
            </td>
        </tr>
        <?php if (gdrts_rules()->filters_allowed()) { ?>
        <tr>
            <td class="cell-singular">
                <label for="<?php echo $this->get_field_id('variant_rule'); ?>">
                    <input<?php echo in_array('rule', $instance['variant']) ? ' checked="checked"' : ''; ?> type="checkbox" value="on" name="<?php echo $this->get_field_name('variant_rule'); ?>" id="<?php echo $this->get_field_id('variant_rule'); ?>" />
                    <span><?php _e("Use settings rules if available", "gd-rating-system"); ?></span>
                </label>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<h4><?php _e("Rating Items", "gd-rating-system"); ?></h4>
<table>
    <tbody>
        <tr>
            <td class="cell-singular">
                <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e("Type", "gd-rating-system"); ?>:</label>
                <?php d4p_render_select(gdrts_admin_shared::data_list_entity_name_types(), array('id' => $this->get_field_id('type'), 'class' => 'widefat', 'name' => $this->get_field_name('type'), 'selected' => $instance['type'])); ?>
                <em>
                    <?php _e("This option will be ignored if the widget is set to detect active post type.", "gd-rating-system"); ?>
                </em>
            </td>
        </tr>
    </tbody>
</table>
