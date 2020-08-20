<?php if (!defined('ABSPATH')) { exit; } ?>

<div class="d4p-group d4p-group-import d4p-group-about">
    <h3>GD Rating System</h3>
    <div class="d4p-group-inner">
        <ul>
            <li><?php _e("Version", "gd-rating-system"); ?>: <span><?php echo gdrts_settings()->info_version; ?></span></li>
            <li><?php _e("Status", "gd-rating-system"); ?>: <span><?php echo ucfirst(gdrts_settings()->info_status); ?></span></li>
            <li><?php _e("Edition", "gd-rating-system"); ?>: <span><?php echo ucfirst(gdrts_settings()->info_edition); ?></span></li>
            <li><?php _e("Build", "gd-rating-system"); ?>: <span><?php echo gdrts_settings()->info_build; ?></span></li>
            <li><?php _e("Date", "gd-rating-system"); ?>: <span><?php echo gdrts_settings()->info_updated; ?></span></li>
        </ul>
        <hr style="margin: 1em 0 .7em; border-top: 1px solid #eee"/>
        <ul>
            <li><?php _e("First released", "gd-rating-system"); ?>: <span><?php echo gdrts_settings()->info_released; ?></span></li>
        </ul>
    </div>
</div>

<div class="d4p-group d4p-group-import d4p-group-about">
    <h3>Important Links</h3>
    <div class="d4p-group-inner">
        <ul>
            <li><?php _e("On Dev4Press", "gd-rating-system"); ?>: <span><a href="https://plugins.dev4press.com/gd-rating-system/" target="_blank">plugins.dev4press.com/gd-rating-system</a></span></li>
        </ul>
    </div>
</div>

<div class="d4p-group d4p-group-import d4p-group-about">
    <h3><?php _e("JavaScript Libraries", "gd-rating-system"); ?></h3>
    <div class="d4p-group-inner">
        <ul style="list-style: outside disc; margin-left: 10px;">
            <li>WP-JS-Hooks, by Carl Danley
                <br/><a href="https://github.com/carldanley/WP-JS-Hooks" target="_blank">github.com/carldanley/WP-JS-Hooks</a></li>
        </ul>
    </div>
</div>

<div class="d4p-group d4p-group-import d4p-group-about">
    <h3><?php _e("Images", "gd-rating-system"); ?></h3>
    <div class="d4p-group-inner">
        <ul style="list-style: outside disc; margin-left: 10px;">
            <li>Snowflake
                <br/><a href="https://commons.wikimedia.org/wiki/File:Snowflake.svg" target="_blank">commons.wikimedia.org/wiki/File:Snowflake.svg</a></li>
            <li>Christmas Star, by Icons8
                <br/><a href="http://icons8.com/" target="_blank">icons8.com</a></li>
            <li>Oxygen Star, by Oxygen Team
                <br/><a href="http://www.iconarchive.com/artist/oxygen-icons.org.html" target="_blank">www.iconarchive.com/artist/oxygen-icons.org.html</a></li>
            <li>Crystal Star, by Everaldo Coelho
                <br/><a href="http://www.everaldo.com/" target="_blank">www.everaldo.com</a></li>
        </ul>
    </div>
</div>
