<?php if (!defined('ABSPATH')) { exit; } ?>

<div id="gdrts-dbfour-intro">
    <div class="d4p-group d4p-group-reset d4p-group-important">
        <h3><?php _e("Important", "gd-rating-system"); ?></h3>
        <div class="d4p-group-inner">
            <?php _e("Database Upgrade tool will transfer some of the rating data from rating meta tables into new database tables.", "gd-rating-system"); ?><br/><br/>
            <ul style="list-style: inside disc; font-weight: normal; margin: 0">
                <li><?php _e("Make sure that the rating maintenance mode is in effect on the website to avoid problems with data changing during the process.", "gd-rating-system"); ?></li>
                <li><?php _e("The upgrade process will copy data from plugin's meta tables into new dedicated tables. Old data will be removed after that.", "gd-rating-system"); ?></li>
                <li style="font-weight: bold"><?php _e("It is highly recommended to create database backup before proceeding with this upgrade!", "gd-rating-system"); ?></li>
                <li style="font-weight: bold"><?php _e("This page will show the progress, make sure not to close the page while the process is working!", "gd-rating-system"); ?></li>
            </ul>
            <hr/>
            <?php _e("In a very rare case, database tables can end up with different charset collations (if database was moved from one server to another or some other changes were made).", "gd-rating-system"); ?><br/><br/>
            <ul style="list-style: inside disc; font-weight: normal; margin: 0">
                <li><?php _e("The plugin will attempt to adjust the collation before the upgrade process.", "gd-rating-system"); ?></li>
                <li><?php _e("If you use some unusual collations (not UTF8 or UTF8MB4), make sure that all tables and columns in those tables use same collations before you proceed!", "gd-rating-system"); ?></li>
                <li style="font-weight: bold"><?php _e("If something is not properly updated, collation is most likely to blame, and you need to check if your database tables related to GD Rating System have proper collations, and they are all with the same collation.", "gd-rating-system"); ?></li>
            </ul>
        </div>
    </div>
</div>

<div id="gdrts-dbfour-process" style="display: none;">
    <div class="d4p-group d4p-group-reset d4p-group-important">
        <h3><?php _e("Important", "gd-rating-system"); ?></h3>
        <div class="d4p-group-inner" style="text-align: left;">
            <?php _e("Upgrade is in progress.", "gd-rating-system"); ?><br/><br/>
            <ul style="list-style: inside disc; font-weight: normal; margin: 0">
                <li><?php _e("Do not close this page, it will stop the process!", "gd-rating-system"); ?></li>
            </ul>
        </div>
    </div>

    <div class="d4p-group d4p-group-reset" id="gdrts-dbfour-progress">
        <h3><?php _e("Processing progress", "gd-rating-system"); ?></h3>
        <div class="d4p-group-inner">
            <pre></pre>
        </div>
    </div>
</div>