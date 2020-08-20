<?php

if (!defined('ABSPATH')) { exit; }

$_classes = array(
    'd4p-wrap', 
    'wpv-'.GDRTS_WPV, 
    'd4p-page-'.gdrts_admin()->page,
    'd4p-panel',
    'd4p-panel-'.$_panel);

$_tabs = array(
    'whatsnew' => __("What&#8217;s New", "gd-rating-system"),
    'info' => __("Info", "gd-rating-system"),
    'changelog' => __("Changelog", "gd-rating-system"),
    'dev4press' => __("Dev4Press", "gd-rating-system")
);

?>

<div class="<?php echo join(' ', $_classes); ?>">
    <h1><?php printf(__("Welcome to GD Rating System&nbsp;%s", "gd-rating-system"), gdrts_settings()->info_version); ?></h1>
    <p class="d4p-about-text">
        Powerful, highly customizable and versatile ratings plugin to allow your users to vote for anything you want. Includes different rating methods and add-ons.
    </p>
    <div class="d4p-about-badge" style="background-color: #262261;">
        <i class="d4p-icon d4p-plugin-icon-gd-rating-system"></i>
        <?php printf(__("Version %s", "gd-rating-system"), gdrts_settings()->info_version); ?>
    </div>

    <h2 class="nav-tab-wrapper wp-clearfix">
        <?php

        foreach ($_tabs as $_tab => $_label) {
            echo '<a href="admin.php?page=gd-rating-system-about&panel='.$_tab.'" class="nav-tab'.($_tab == $_panel ? ' nav-tab-active' : '').'">'.$_label.'</a>';
        }

        ?>
    </h2>

    <div class="d4p-about-inner">