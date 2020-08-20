<?php if (!defined('ABSPATH')) { exit; } ?>

<input type="hidden" name="gdrts[posts-override][nonce]" value="<?php echo wp_create_nonce('gdrts-posts-override-'.$_gdrts_id); ?>" />
<p>
    <label for="gdrts_posts-override_title"><?php _e("Title", "gd-rating-system"); ?></label>
    <input name="gdrts[posts-override][title]" class="widefat" type="text" value="<?php echo esc_attr($_gdrts_title); ?>" />
</p>
<p>
    <label for="gdrts_posts-override_url"><?php _e("URL", "gd-rating-system"); ?></label>
    <input name="gdrts[posts-override][url]" class="widefat" type="text" value="<?php echo esc_attr($_gdrts_url); ?>" />
</p>
