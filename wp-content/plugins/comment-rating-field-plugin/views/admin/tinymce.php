<form class="wpzinc-tinymce-popup">
    <input type="hidden" name="shortcode" value="crfp" />
    
    <!-- Enabled -->
    <div class="option">
        <div class="left">
            <strong><?php _e( 'Enabled', 'comment-rating-field-pro-plugin' ); ?></strong>
        </div>
        <div class="right">
            <select name="enabled" data-shortcode="enabled" size="1">
                <option value="1">
                    <?php _e( 'Display when Ratings Exist', 'comment-rating-field-pro-plugin' ); ?>
                </option>
                <option value="2">
                    <?php _e( 'Always Display', 'comment-rating-field-pro-plugin' ); ?>
                </option>
            </select>
        </div>
    </div>
    
    <div class="option">
        <div class="left">
            <strong><?php _e( 'Post ID', 'comment-rating-field-pro-plugin' ); ?></strong>
        </div>
        <div class="right">
            <input type="hidden" name="displayAverage" data-shortcode="displayAverage" value="1" />
            <input type="number" name="id" data-shortcode="id" min="1" max="9999999" step="1" /> 
        
            <p class="description">
                <?php _e( 'Only required if you want to show the average ratings for a different Post/Page than this one', 'comment-rating-field-pro-plugin' ); ?>
            </p>
        </div>
    </div>     

    <div class="option">
        <input type="submit" name="submit" value="<?php _e( 'Insert', 'comment-rating-field-pro-plugin' ); ?>" class="button button-primary" />
    </div>
</form>