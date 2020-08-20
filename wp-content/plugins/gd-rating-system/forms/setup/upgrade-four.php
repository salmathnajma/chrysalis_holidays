<?php if (!defined('ABSPATH')) { exit; } ?>

<h3><?php _e("Database upgrade 3.0", "gd-rating-system"); ?></h3>
<?php

if (!gdrts_db()->maybe_to_upgrade_to_four()) {
    _e("There is nothing to upgrade.", "gd-rating-system");
} else {
    $_show_upgrade_db_button = true;

    gdrts_settings()->set('maintenance', true, 'core', true);

    _e("You must run the Database Upgrade tool to make sure all old data is converted into new format. Rating has been placed in maintenance mode and no new ratings can be recorded during until the database upgrade is completed. Also, until the upgrade is completed, the ratings displayed will be wrong or missing.", "gd-rating-system");
}
