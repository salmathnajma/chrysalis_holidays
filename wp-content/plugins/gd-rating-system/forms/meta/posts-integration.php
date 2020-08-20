<?php

if (!defined('ABSPATH')) { exit; }

$_rating_methods = gdrts_admin_shared::data_list_embed_methods('-');

?>

<?php if (!empty($_rating_methods)) { ?>
    <input type="hidden" name="gdrts[posts-integration][nonce]" value="<?php echo wp_create_nonce('gdrts-posts-integration-'.$_gdrts_id); ?>" />

    <div class="gdrts-metabox-wrapper" style="border-bottom: 2px solid #ddd;">
        <div class="gdrts-metabox-wrapper-left">
            <p>
                <label for="gdrts_posts-integration_location"><?php _e("Display Rating Block", "gd-rating-system"); ?></label>
                <?php d4p_render_select(array_merge(array('default' => __("Default", "gd-rating-system")), gdrtsa_admin_posts()->get_list_embed_locations()), array('class' => 'widefat', 'selected' => $_gdrts_display, 'name' => 'gdrts[posts-integration][location]')); ?>
            </p>
        </div>
        <div class="gdrts-metabox-wrapper-right">
            <p>
                <label for="gdrts_posts-integration_method"><?php _e("Rating Method", "gd-rating-system"); ?></label>
                <?php d4p_render_select(array_merge(array('default' => __("Default", "gd-rating-system")), $_rating_methods), array('class' => 'widefat', 'selected' => $_gdrts_method, 'name' => 'gdrts[posts-integration][method]')); ?>
            </p>
            <p>
                <label for="gdrts_posts-integration_priority"><?php _e("Rating Priority for Filter", "gd-rating-system"); ?></label>
                <input class="widefat" type="number" step="1" name="gdrts[posts-integration][priority]" value="<?php echo esc_attr($_gdrts_priority); ?>" />
            </p>
        </div>
    </div>
<?php } ?>
