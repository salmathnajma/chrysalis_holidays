<?php

if (!defined('ABSPATH')) { exit; }

$list_styling = array(
    'default' => __("Default styling", "gd-rating-system"),
    'custom' => __("Custom styling", "gd-rating-system"),
);

?>
<h4><?php _e("Styling", "gd-rating-system"); ?></h4>
<table>
    <tbody>
        <tr>
            <td class="cell-singular">
                <label for="<?php echo $this->get_field_id('styling'); ?>"><?php _e("Styling to use", "gd-rating-system"); ?>:</label>
                <?php d4p_render_select($list_styling, array('id' => $this->get_field_id('styling'), 'class' => 'widefat', 'name' => $this->get_field_name('styling'), 'selected' => $instance['styling'])); ?>
                <em class="solo-content"><?php

                _e("If set to custom, use options below to set styling.", "gd-rating-system");

                ?></em>
            </td>
        </tr>
    </tbody>
</table>

<h4><?php _e("Custom styling", "gd-rating-system"); ?></h4>
<table>
    <tbody>
        <tr>
            <td class="cell-left">
                <label for="<?php echo $this->get_field_id('template'); ?>"><?php _e("Template", "gd-rating-system"); ?>:</label>
                <?php d4p_render_select(gdrts_admin_shared::data_list_templates('stars-rating'), array('id' => $this->get_field_id('template'), 'class' => 'widefat', 'name' => $this->get_field_name('template'), 'selected' => $instance['template'])); ?>
            </td>
            <td class="cell-right">
                <label for="<?php echo $this->get_field_id('distribution'); ?>"><?php _e("Votes Distribution", "gd-rating-system"); ?>:</label>
                <?php d4p_render_select(gdrts_admin_shared::data_list_distributions(), array('id' => $this->get_field_id('distribution'), 'class' => 'widefat', 'name' => $this->get_field_name('distribution'), 'selected' => $instance['distribution'])); ?>
            </td>
        </tr>
        <tr>
            <td class="cell-left">
                <label for="<?php echo $this->get_field_id('alignment'); ?>"><?php _e("Alignment", "gd-rating-system"); ?>:</label>
                <?php d4p_render_select(gdrts_admin_shared::data_list_align(), array('id' => $this->get_field_id('alignment'), 'class' => 'widefat', 'name' => $this->get_field_name('alignment'), 'selected' => $instance['alignment'])); ?>
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
                <?php d4p_render_select(gdrts_admin_shared::data_list_style_type(), array('id' => $this->get_field_id('style_type'), 'class' => 'widefat', 'name' => $this->get_field_name('style_type'), 'selected' => $instance['style_type'])); ?>

                <label for="<?php echo $this->get_field_id('style_size'); ?>"><?php _e("Size (px)", "gd-rating-system"); ?>:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('style_size'); ?>" name="<?php echo $this->get_field_name('style_size'); ?>" type="number" min="0" step="1" value="<?php echo esc_attr($instance['style_size']); ?>" />
            </td>
            <td class="cell-right">
                <label for="<?php echo $this->get_field_id('style_image_name'); ?>"><?php _e("Image", "gd-rating-system"); ?>:</label>
                <?php d4p_render_select(gdrts_admin_shared::data_list_style_image_name(), array('id' => $this->get_field_id('style_image_name'), 'class' => 'widefat', 'name' => $this->get_field_name('style_image_name'), 'selected' => $instance['style_image_name'])); ?>

                <?php do_action('gdrts_widget_display_types', $this, $instance, 'stars-rating'); ?>
            </td>
        </tr>
    </tbody>
</table>

<h4><?php _e("Custom font icon colors", "gd-rating-system"); ?></h4>
<table>
    <tbody>
        <tr>
            <td class="cell-left">
                <label for="<?php echo $this->get_field_id('font_color_empty'); ?>"><?php _e("Color", "gd-rating-system"); ?> - <?php _e("Empty Stars", "gd-rating-system"); ?>:</label>
                <input class="widefat d4p-color-picker" id="<?php echo $this->get_field_id('font_color_empty'); ?>" name="<?php echo $this->get_field_name('font_color_empty'); ?>" type="text" value="<?php echo esc_attr($instance['font_color_empty']); ?>" /><br/>
            </td>
            <td class="cell-right">
                <label for="<?php echo $this->get_field_id('font_color_current'); ?>"><?php _e("Color", "gd-rating-system"); ?> - <?php _e("Current Stars", "gd-rating-system"); ?>:</label>
                <input class="widefat d4p-color-picker" id="<?php echo $this->get_field_id('font_color_current'); ?>" name="<?php echo $this->get_field_name('font_color_current'); ?>" type="text" value="<?php echo esc_attr($instance['font_color_current']); ?>" /><br/>
            </td>
        </tr>
        <tr>
            <td class="cell-left">
                <label for="<?php echo $this->get_field_id('font_color_active'); ?>"><?php _e("Color", "gd-rating-system"); ?> - <?php _e("Active Stars", "gd-rating-system"); ?>:</label>
                <input class="widefat d4p-color-picker" id="<?php echo $this->get_field_id('font_color_active'); ?>" name="<?php echo $this->get_field_name('font_color_active'); ?>" type="text" value="<?php echo esc_attr($instance['font_color_active']); ?>" /><br/>
            </td>
            <td class="cell-right">
                
            </td>
        </tr>
    </tbody>
</table>
