<?php

if (!defined('ABSPATH')) { exit; }

include(GDRTS_PATH.'forms/setup/database.php');

if (gdrts_settings()->get('upgrade_to_40', 'core') === false) {
    include(GDRTS_PATH.'forms/setup/upgrade-four.php');
}

include(GDRTS_PATH.'forms/setup/templates.php');
include(GDRTS_PATH.'forms/setup/rules.php');
include(GDRTS_PATH.'forms/setup/settings.php');
include(GDRTS_PATH.'forms/setup/cache.php');
