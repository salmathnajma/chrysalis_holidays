<?php

if (!defined('ABSPATH')) { exit; }

require_once(GDRTS_PATH.'core/grids/database.php');

$_grid = new gdrts_grid_database();
$_grid->prepare_items();
$_grid->display();
