<?php if (!defined('ABSPATH')) { exit; } ?>

<div class="d4p-group d4p-group-reset d4p-group-important">
    <h3><?php _e("Important", "gd-rating-system"); ?></h3>
    <div class="d4p-group-inner">
        <?php _e("This tool removes or changes rich snippets related data in the database tables added by the plugin.", "gd-rating-system"); ?>
        <?php _e("Tools on this panel target individual rating items settings, the settings you can control via rich snippets metabox.", "gd-rating-system"); ?>
        <br/><br/>
        <?php _e("Deletion operations are not reversible, and it is highly recommended to create database backup before proceeding with this tool.", "gd-rating-system"); ?>
    </div>
</div>

<div class="d4p-group d4p-group-tools d4p-group-reset">
    <h3><?php _e("Modify and clear rich snippet items settings", "gd-rating-system"); ?></h3>
    <div class="d4p-group-inner">
        <p style="font-weight: bold"><?php _e("Select which actions you want to perform.", "gd-rating-system"); ?></p>
        <label>
            <input type="checkbox" class="widefat" name="gdrtstools[snippets][bulk][remove][display]" value="on" /> <?php echo __("Reset snippet display to Default", "gd-rating-system"); ?>
        </label>
        <label>
            <input type="checkbox" class="widefat" name="gdrtstools[snippets][bulk][remove][rating-method]" value="on" /> <?php echo __("Reset rating method to Default", "gd-rating-system"); ?>
        </label>
        <label>
            <input type="checkbox" class="widefat" name="gdrtstools[snippets][bulk][remove][review-method]" value="on" /> <?php echo __("Reset review method to Default", "gd-rating-system"); ?>
        </label>
        <label>
            <input type="checkbox" class="widefat" name="gdrtstools[snippets][bulk][remove][type]" value="on" /> <?php echo __("Remove snippet type override", "gd-rating-system"); ?>
        </label>
        <label>
            <input type="checkbox" class="widefat" name="gdrtstools[snippets][bulk][remove][rating]" value="on" /> <?php echo __("Remove rating data inclusion override", "gd-rating-system"); ?>
        </label>
        <label>
            <input type="checkbox" class="widefat" name="gdrtstools[snippets][bulk][remove][custom]" value="on" /> <?php echo __("Remove snippet name for Custom Snippet Type", "gd-rating-system"); ?>
        </label>
        <hr/>
        <p style="font-weight: bold; margin-bottom: 1em;"><?php _e("Select which post types posts will be affected by the selected actions.", "gd-rating-system"); ?></p>
        <?php foreach (gdrts()->get_entity_types('posts') as $name => $label) { ?>
            <label>
                <input type="checkbox" class="widefat" name="gdrtstools[snippets][bulk][cpt][<?php echo $name; ?>]" value="on" /> <?php echo $label; ?>
            </label>
        <?php } ?>
    </div>
</div>

<div class="d4p-group d4p-group-tools d4p-group-reset">
    <h3><?php _e("Update rich snippets default settings", "gd-rating-system"); ?></h3>
    <div class="d4p-group-inner">
        <label>
            <input type="checkbox" class="widefat" name="gdrtstools[snippets][ratings_disable]" value="on" /> <?php echo sprintf(__("Disable ratings mode for these snippet types: %s", "gd-rating-system"), 'Article, NewsArticle, BlogPosting, WebPage'); ?>
        </label>
    </div>
</div>

<div class="d4p-group d4p-group-tools d4p-group-reset">
    <h3><?php _e("Legacy rich snippets", "gd-rating-system"); ?></h3>
    <div class="d4p-group-inner">
        <label>
            <input type="checkbox" class="widefat" name="gdrtstools[snippets][legacy_remove]" value="on" /> <?php _e("Remove all legacy rich snippets data", "gd-rating-system"); ?>
        </label>
    </div>
</div>
