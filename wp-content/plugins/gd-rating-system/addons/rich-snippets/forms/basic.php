<input type="hidden" name="gdrts[rich-snippets][nonce]" value="<?php echo wp_create_nonce('gdrts-rich-snippets-'.$_gdrts_id); ?>"/>

<div class="gdrts-metabox-wrapper">
    <div class="gdrts-metabox-wrapper-left">
        <p>
            <label for="gdrts_rich-snippets_display"><?php _e("Display", "gd-rating-system"); ?></label>
            <?php d4p_render_select(array_merge(array('default' => __("Default", "gd-rating-system")), gdrtsa_admin_rich_snippets()->get_list_embed_locations()), array('class' => 'widefat', 'selected' => $_gdrts_display, 'name' => 'gdrts[rich-snippets][display]')); ?>
        </p>
    </div>
    <div class="gdrts-metabox-wrapper-right">
        <p>
            <label for="gdrts_rich-snippets_rating_method"><?php _e("Rating Method", "gd-rating-system"); ?></label>
            <?php d4p_render_select(array_merge(array('default' => __("Default", "gd-rating-system")), gdrtsa_admin_rich_snippets()->get_list_rating_methods()), array('class' => 'widefat', 'selected' => $_gdrts_rating_method, 'name' => 'gdrts[rich-snippets][rating_method]')); ?>
        </p>
    </div>
</div>
