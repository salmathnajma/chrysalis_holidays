<?php

if (!defined('ABSPATH')) { exit; }

do_action('gdrts_admin_panel_top');

$pages = gdrts_admin()->menu_items;
$_page = gdrts_admin()->page;
$_panel = gdrts_admin()->panel;

if (!empty($panels)) {
    if ($_panel === false || empty($_panel)) {
        $_panel = 'index';
    }

    $_available = array_keys($panels);

    if (!in_array($_panel, $_available)) {
        $_panel = 'index';
        gdrts_admin()->panel = false;
    }
}

$_classes = array('d4p-wrap', 'wpv-'.GDRTS_WPV, 'd4p-page-'.$_page);

if ($_panel !== false) {
    $_classes[] = 'd4p-panel';
    $_classes[] = 'd4p-panel-'.$_panel;
}

$_message = '';
$_color = '';

if (isset($_GET['message']) && $_GET['message'] != '') {
    $msg = d4p_sanitize_slug($_GET['message']);

    switch ($msg) {
        default:
            $filtered = apply_filters('gdrts_admin_return_message', array('message' => '', 'color' => ''), $msg);

            $_message = $filtered['message'];
            $_color = $filtered['color'];
            break;
        case 'saved':
            $_message = __("Settings are saved.", "gd-rating-system");
            break;
        case 'updated':
            $_message = __("Rating data changes have been made.", "gd-rating-system");
            break;
        case 'hashed':
            $ips = isset($_GET['ips']) ? absint($_GET['ips']) : 0;

            $_message = sprintf(__("Total of %s IP hashed.", "gd-rating-system"), $ips);
            break;
        case 'series-saved':
            $_message = __("Series settings are saved.", "gd-rating-system");
            break;
        case 'series-assigned':
            $_message = __("Series emoticons assignments are saved.", "gd-rating-system");
            break;
        case 'rule-removed':
            $_message = __("Rule removal operation completed.", "gd-rating-system");
            break;
        case 'rule-updated':
            $_message = __("Rule update operation completed.", "gd-rating-system");
            break;
        case 'entity-removed':
            $_message = __("Entity removal operation completed.", "gd-rating-system");
            break;
        case 'imported':
            $_message = __("Import operation completed.", "gd-rating-system");
            break;
        case 'import-failed':
            $_message = __("Import file is not valid.", "gd-rating-system");
            $_color = 'error';
            break;
        case 'transfer-failed':
            $_message = __("Invalid transfer configuration. Transfer failed.", "gd-rating-system");
            $_color = 'error';
            break;
        case 'transfered':
            $_message = __("Data transfer completed.", "gd-rating-system");
            break;
        case 'nothing':
            $_message = __("Nothing done.", "gd-rating-system");
            $_color = 'error';
            break;
	    case 'invalid':
		    $_message = __("Invalid request.", "gd-rating-system");
            $_color = 'error';
		    break;
        case 'nothing-removed':
            $_message = __("Nothing removed.", "gd-rating-system");
            $_color = 'error';
            break;
        case 'removed':
            $_message = __("Removal operation completed.", "gd-rating-system");
            break;
        case 'recalculated':
            $_message = __("Recalculation operation completed.", "gd-rating-system");
            break;
    }
}

?>
<div class="<?php echo join(' ', $_classes); ?>">
    <div class="d4p-header">
        <div class="d4p-navigator">
            <ul>
                <li class="d4p-nav-button">
                    <a href="#"><?php echo d4p_render_icon($pages[$_page]['icon'], 'i', true); ?> <?php echo $pages[$_page]['title']; ?></a>
                    <ul>
                        <?php

                        foreach ($pages as $page => $obj) {
                            if ($page != $_page) {
                                echo '<li><a href="admin.php?page=gd-rating-system-'.$page.'">'.d4p_render_icon($obj['icon'], 'i', true, true).' '.$obj['title'].'</a></li>';
                            } else {
                                echo '<li class="d4p-nav-current">'.d4p_render_icon($obj['icon'], 'i', true, true).' '.$obj['title'].'</li>';
                            }
                        }

                        ?>
                    </ul>
                </li>
                <?php if (!empty($panels)) { ?>
                <li class="d4p-nav-button">
                    <a href="#"><?php echo d4p_render_icon($panels[$_panel]['icon'], 'i', true); ?> <?php echo $panels[$_panel]['title']; ?></a>
                    <ul>
                        <?php

                        foreach ($panels as $panel => $obj) {
                            if ($panel != $_panel) {
                                echo '<li><a href="admin.php?page=gd-rating-system-'.$_page.'&panel='.$panel.'">'.d4p_render_icon($obj['icon'], 'i', true, true).' '.$obj['title'].'</a></li>';
                            } else {
                                echo '<li class="d4p-nav-current">'.d4p_render_icon($obj['icon'], 'i', true, true).' '.$obj['title'].'</li>';
                            }
                        }

                        ?>
                    </ul>
                </li>
                <?php } ?>
            </ul>
        </div>
        <div class="d4p-plugin">
            GD Rating System
        </div>
    </div>
    <?php

    if ($_message != '') {
        echo '<div class="updated '.$_color.'">'.$_message.'</div>';
    }

    ?>
    <div class="d4p-content">
