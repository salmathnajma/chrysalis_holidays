<h4><?php _e("Post rating filters", "gd-rating-system"); ?></h4>
<table>
	<tbody>
		<tr>
			<td class="cell-singular">
				<label for="<?php echo $this->get_field_id('terms'); ?>"><?php _e("Terms ID's (comma separated)", "gd-rating-system"); ?>:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('terms'); ?>" name="<?php echo $this->get_field_name('terms'); ?>" type="text" value="<?php echo esc_attr($instance['terms']); ?>" />
                <em>
                    <?php _e("This option will be ignored if the widget is set to detect active terms.", "gd-rating-system"); ?>
                </em>
			</td>
        </tr>
        <tr>
			<td class="cell-singular">
				<label for="<?php echo $this->get_field_id('author'); ?>"><?php _e("Authors ID's (comma separated)", "gd-rating-system"); ?>:</label>
				<input class="widefat" id="<?php echo $this->get_field_id('author'); ?>" name="<?php echo $this->get_field_name('author'); ?>" type="text" value="<?php echo esc_attr($instance['author']); ?>" />
			</td>
		</tr>
	</tbody>
</table>
