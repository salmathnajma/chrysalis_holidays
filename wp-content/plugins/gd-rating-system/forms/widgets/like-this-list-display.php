<?php if (!defined('ABSPATH')) { exit; } ?>

<h4><?php _e("Template and style", "gd-rating-system"); ?></h4>
<table>
    <tbody>
        <tr>
            <td class="cell-singular" colspan="2">
                <label for="<?php echo $this->get_field_id('template'); ?>"><?php _e("Template", "gd-rating-system"); ?>:</label>
                <?php d4p_render_select(gdrts_admin_shared::data_list_templates('like-this', 'list'), array('id' => $this->get_field_id('template'), 'class' => 'widefat', 'name' => $this->get_field_name('template'), 'selected' => $instance['template'])); ?>
            </td>
        </tr>
        <tr>
            <td class="cell-left">
                <label for="<?php echo $this->get_field_id('style_theme'); ?>"><?php _e("Style Theme", "gd-rating-system"); ?>:</label>
                <?php d4p_render_select(gdrts_admin_shared::data_list_likes_style_theme(), array('id' => $this->get_field_id('style_theme'), 'class' => 'widefat', 'name' => $this->get_field_name('style_theme'), 'selected' => $instance['style_theme'])); ?>
            </td>
            <td class="cell-right">
                <label for="<?php echo $this->get_field_id('style_class'); ?>"><?php _e("Additional CSS Class", "gd-rating-system"); ?>:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('style_class'); ?>" name="<?php echo $this->get_field_name('style_class'); ?>" type="text" value="<?php echo esc_attr($instance['style_class']); ?>" />
            </td>
        </tr>
    </tbody>
</table>

<h4><?php _e("Custom icon", "gd-rating-system"); ?></h4>
<table>
    <tbody>
        <tr>
            <td class="cell-left">
                <label for="<?php echo $this->get_field_id('style_type'); ?>"><?php _e("Style Type", "gd-rating-system"); ?>:</label>
                <?php d4p_render_select(gdrts_admin_shared::data_list_likes_style_type(), array('id' => $this->get_field_id('style_type'), 'class' => 'widefat', 'name' => $this->get_field_name('style_type'), 'selected' => $instance['style_type'])); ?>

                <label for="<?php echo $this->get_field_id('style_size'); ?>"><?php _e("Size (px)", "gd-rating-system"); ?>:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('style_size'); ?>" name="<?php echo $this->get_field_name('style_size'); ?>" type="number" min="0" step="1" value="<?php echo esc_attr($instance['style_size']); ?>" />
            </td>
            <td class="cell-right">
                <label for="<?php echo $this->get_field_id('style_image_name'); ?>"><?php _e("Image", "gd-rating-system"); ?>:</label>
                <?php d4p_render_select(gdrts_admin_shared::data_list_likes_style_image_name(), array('id' => $this->get_field_id('style_image_name'), 'class' => 'widefat', 'name' => $this->get_field_name('style_image_name'), 'selected' => $instance['style_image_name'])); ?>

                <?php do_action('gdrts_widget_display_types', $this, $instance, 'like-this'); ?>
            </td>
        </tr>
    </tbody>
</table>