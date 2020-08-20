<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_admin_ajax {
    public $time_start = 0;

    public function __construct() {
        add_action('wp_ajax_gdrts_tools_dbfour', array($this, 'dbfour'));
        add_action('wp_ajax_gdrts_tools_recalc', array($this, 'recalc'));
        add_action('wp_ajax_gdrts_transfer_process', array($this, 'transfer'));

        do_action('gdrts_admin_ajax');
    }

    public function check_nonce() {
        $nonce = wp_verify_nonce($_REQUEST['_ajax_nonce'], 'gdrts-admin-internal');

        if ($nonce === false) {
            wp_die(-1);
        }
    }

    public function transfer() {
        $this->check_nonce();

        @ini_set('memory_limit', '256M');
        @set_time_limit(0);

        require_once(GDRTS_PATH.'core/admin/transfer.php');

        $operation = $_POST['operation'];

        switch ($operation) {
            case 'start':
                $settings = (array)$_POST['settings'];
                $total = gdrts_admin_transfer::count_objects($settings);
                $response = array(
                    'objects' => $total,
                    'message' => '- '.__("Transfer process is starting...", "gd-rating-system").
                                 '<br/>* '.sprintf(__("Total of %s rating objects found.", "gd-rating-system"), $total)
                );

                die(json_encode($response));
                break;
            case 'break':
                $response = array(
                    'message' => '* '.__("Transfer process has been stopped.", "gd-rating-system")
                );

                die(json_encode($response));
                break;
            case 'stop':
                $response = array(
                    'message' => '* '.__("Transfer process has finished.", "gd-rating-system")
                );

                die(json_encode($response));
                break;
            case 'run':
                $result = $this->transfer_run();

                $response = array(
                    'message' => '- '.$result
                );

                die(json_encode($response));
                break;
        }
    }

    public function transfer_run() {
        $total = absint($_POST['total']);
        $current = absint($_POST['current']);
        $step = absint($_POST['step']);
        $offset = $current * $step;

        $settings = (array)$_POST['settings'];

        $this->timer_start();

        gdrts_admin_transfer::transfer_objects($offset, $step, $settings);

        $timer = round($this->timer_stop(), 5);

        $done = ($current + 1) * $step;

        if ($done > $total) {
            $done = $total;
        }

        return sprintf(__("%s of %s done (%s sec).", "gd-rating-system"), $done, $total, $timer);
    }

    public function dbfour() {
        $this->check_nonce();

        require_once(GDRTS_PATH.'core/admin/upgrade.php');

        @ini_set('memory_limit', '256M');
        @set_time_limit(0);

        $operation = $_POST['operation'];

        switch ($operation) {
            case 'start':
                $_upgrade = new gdrts_admin_upgrade();
                $_upgrade->database_upgrade();

                $response = array(
                    'objects' => 0,
                    'message' => ''
                );

                $_steps = array();
                $_methods = array(
                    'like-this',
                    'stars-rating'
                );

                foreach ($_methods as $method) {
                    if ($_upgrade->check_if_used_method($method)) {
                        $_steps['items-'.$method] = false;
                        $_steps['logs-'.$method] = false;
                    }
                }

                set_transient('gdrts_dbfour_upgrade_steps', $_steps);

                $response['objects'] = count($_steps);
                $response['message'] = '* '.sprintf(__("Total of %s upgrade steps in queue.", "gd-rating-system"), $response['objects']);
                $response['message'].= D4P_EOL.'* '.__("Process is starting.", "gd-rating-system");

                die(json_encode($response));
                break;
            case 'break':
                $response = array(
                    'message' => '* '.__("Process has been interupted by user.", "gd-rating-system")
                );

                delete_transient('gdrts_dbfour_upgrade_steps');

                die(json_encode($response));
                break;
            case 'stop':
                $response = array(
                    'message' => '* '.__("Process has completed.", "gd-rating-system")
                );

                gdrts_settings()->set('upgrade_to_40', true, 'core');
                gdrts_settings()->set('maintenance', false, 'core');

                gdrts_settings()->save('core');

                delete_transient('gdrts_dbfour_upgrade_steps');

                die(json_encode($response));
                break;
            case 'run':
                $response = $this->dbfour_run();

                die(json_encode($response));
                break;
        }
    }

    public function dbfour_run() {
        $progress = get_transient('gdrts_dbfour_upgrade_steps');

        $_total_time = 0;
        $_render_result = array();
        $_steps_done = 0;

        $_upgrade = new gdrts_admin_upgrade();

        foreach ($progress as $item => $status) {
            if ($status === true) {
                continue;
            }

            $this->timer_start();

            $_item = explode('-', $item, 2);
            $_type = $_item[0];
            $_method = $_item[1];
            $_series = '';

            $_has_series = strpos($_method, '::') !== false;

            if ($_has_series) {
                $_break = explode('::', $_method, 2);
                $_method = $_break[0];
                $_series = $_break[1];

                $_call = $_type.'_'.str_replace('-', '_', $_method);

                $result = $_upgrade->$_call($_series);
            } else {
                $_call = $_type.'_'.str_replace('-', '_', $_method);

                $result = $_upgrade->$_call();
            }

            usleep(50000);

            $timer = round($this->timer_stop(), 6);
            $_total_time+= $timer;
            $_steps_done++;

            if ($_has_series) {
                $_render_result[] = '* '.sprintf(__("'%s' for '%s' ('%s') done (%s sec).", "gd-rating-system"),
                        strtoupper($_type),
                        ucwords(str_replace('-', ' ', $_method)),
                        ucwords(str_replace(array('-', '_'), ' ', $_series)),
                        $timer);
            } else {
                $_render_result[] = '* '.sprintf(__("'%s' for '%s' done (%s sec).", "gd-rating-system"),
                        strtoupper($_type),
                        ucwords(str_replace('-', ' ', $_method)),
                        $timer);
            }

            if (empty($result['error'])) {
                if ($result['type'] == 'logs') {
                    $_render_result[] = '  - '.sprintf(__("Updated records: %s", "gd-rating-system"), $result['update']);
                } else {
                    $_render_result[] = '  - '.sprintf(__("Inserted records: %s", "gd-rating-system"), $result['insert']);
                }

                $_render_result[] = '  - '.sprintf(__("Deleted records: %s", "gd-rating-system"), $result['delete']);
            } else {
                $_render_result[] = '  - '.sprintf(__("Error occured with operation: %s", "gd-rating-system"), strtoupper($result['error']));
            }

            $progress[$item] = true;

            set_transient('gdrts_dbfour_upgrade_steps', $progress);

            if ($_total_time > 15) {
                break;
            }
        }

        return array('done' => $_steps_done, 'message' => join(D4P_EOL, $_render_result));
    }

    public function recalc() {
        $this->check_nonce();

        @ini_set('memory_limit', '256M');
        @set_time_limit(0);

        require_once(GDRTS_PATH.'core/admin/maintenance.php');

        $operation = $_POST['operation'];

        switch ($operation) {
            case 'break':
                $response = array(
                    'message' => '* '.__("Process has been interupted by user.", "gd-rating-system")
                );

                die(json_encode($response));
                break;
            case 'start':
                gdrts_settings()->set('maintenance', true, 'settings', true);

                $total = gdrts_admin_maintenance::count_rating_objects();
                $response = array(
                    'objects' => $total,
                    'message' => '* '.sprintf(__("Total of %s rating objects found.", "gd-rating-system"), $total)
                );

                die(json_encode($response));
                break;
            case 'stop':
                gdrts_settings()->set('maintenance', false, 'settings', true);

                $response = array(
                    'message' => '* '.__("Process has completed.", "gd-rating-system")
                );

                die(json_encode($response));
                break;
            case 'run':
                $result = $this->recalc_run();

                $response = array(
                    'message' => '- '.$result
                );

                die(json_encode($response));
                break;
        }
    }

    public function recalc_run() {
        $total = absint($_POST['total']);
        $current = absint($_POST['current']);
        $step = absint($_POST['step']);
        $offset = $current * $step;

        $settings = array();
        $raw = (array)$_POST['settings'];

        foreach ($raw as $operation) {
            $o = explode('|', $operation);
            $m = gdrts()->convert_method_series_pair($o[0]);

            if (gdrts_is_method_valid($m['method'])) {
                if (!isset($settings[$m['method']])) {
                    $settings[$m['method']] = array();
                }

                $settings[$m['method']][] = $o[1];
            }
        }

        $this->timer_start();

        gdrts_admin_maintenance::recalculate_rating_objects($offset, $step, $settings);

        $timer = round($this->timer_stop(), 5);

        $done = ($current + 1) * $step;

        if ($done > $total) {
            $done = $total;
        }

        return sprintf(__("%s of %s done (%s sec).", "gd-rating-system"), $done, $total, $timer);
    }

    public function timer_start() {
        $this->time_start = microtime(true);
        return true;
    }

    public function timer_stop() {
        return (microtime(true) - $this->time_start);
    }
}

global $_gdrts_admin_ajax;

$_gdrts_admin_ajax = new gdrts_admin_ajax();

function gdrts_ajax_admin() {
    global $_gdrts_admin_ajax;
    return $_gdrts_admin_ajax;
}
