<?php

if (!defined('ABSPATH')) { exit; }

$_panel = gdrts_admin()->panel === false ? 'whatsnew' : gdrts_admin()->panel;

if (!in_array($_panel, array('changelog', 'whatsnew', 'info', 'dev4press'))) {
    $_panel = 'whatsnew';
}

include(GDRTS_PATH.'forms/about/header.php');

include(GDRTS_PATH.'forms/about/'.$_panel.'.php');

include(GDRTS_PATH.'forms/about/footer.php');
include(GDRTS_PATH.'forms/dialogs/about.php');
