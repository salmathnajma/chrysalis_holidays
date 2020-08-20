<?php

if (!defined('ABSPATH')) { exit; }

$list_content = array(
    'post' => __("Current single post/page", "gd-rating-system"),
    'custom' => __("Custom rating item", "gd-rating-system"),
);

?>
<h4><?php _e("Content", "gd-rating-system"); ?></h4>
<table>
    <tbody>
        <tr>
            <td class="cell-singular">
                <label for="<?php echo $this->get_field_id('content'); ?>"><?php _e("Content for widget", "gd-rating-system"); ?>:</label>
                <?php d4p_render_select($list_content, array('id' => $this->get_field_id('content'), 'class' => 'widefat', 'name' => $this->get_field_name('content'), 'selected' => $instance['content'])); ?>
                <em class="solo-content"><?php

                _e("If current post/page is set, widget will be vivisble only on singular posts. If set to custom, use options below to set the rating object.", "gd-rating-system");

                ?></em>
            </td>
        </tr>
    </tbody>
</table>

<h4><?php _e("Get rating object by Type and ID", "gd-rating-system"); ?></h4>
<table>
    <tbody>
        <tr>
            <td class="cell-left">
                <label for="<?php echo $this->get_field_id('type'); ?>"><?php _e("Type", "gd-rating-system"); ?>:</label>
                <?php d4p_render_select(gdrts_admin_shared::data_list_entity_name_types(), array('id' => $this->get_field_id('type'), 'class' => 'widefat', 'name' => $this->get_field_name('type'), 'selected' => $instance['type'])); ?>
            </td>
            <td class="cell-right">
                <label for="<?php echo $this->get_field_id('id'); ?>"><?php _e("ID", "gd-rating-system"); ?>:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="number" min="0" step="1" value="<?php echo esc_attr($instance['id']); ?>" />
            </td>
        </tr>
        <tr>
            <td colspan="2" class="cell-singular"><em class="solo-content"><?php

                _e("If you use this, make sure you enter correct ID for the selected rating type. Plugin will not check if the item is valid.", "gd-rating-system");

                ?></em></td>
        </tr>
    </tbody>
</table>

<h4><?php _e("Get rating object Item ID", "gd-rating-system"); ?></h4>
<table>
    <tbody>
        <tr>
            <td class="cell-left">
                <label for="<?php echo $this->get_field_id('item_id'); ?>"><?php _e("Item ID", "gd-rating-system"); ?>:</label>
                <input class="widefat" id="<?php echo $this->get_field_id('item_id'); ?>" name="<?php echo $this->get_field_name('item_id'); ?>" type="number" min="0" step="1" value="<?php echo esc_attr($instance['item_id']); ?>" />
            </td>
            <td class="cell-right">
            </td>
        </tr>
    </tbody>
</table>
