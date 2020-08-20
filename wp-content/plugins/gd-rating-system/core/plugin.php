<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core_plugin {
    public $plugin = 'gd-rating-system';

    public $is_debug;
    public $wp_version;

    public $cap = 'gdrts_standard';

    private $_load_js = false;
    private $_load_css = false;
    private $_enqueued = false;
    private $_jquery = 'jquery';

	public $js_locale = array(
		'flatpickr' => array('da', 'de', 'es', 'fr', 'it', 'nl', 'pl', 'pt', 'ru', 'sr')
	);

    public $widgets = array();

    function __construct() {
        add_action('plugins_loaded', array($this, 'core'));
        add_action('after_setup_theme', array($this, 'init'), 20);
    }

    public function core() {
        global $wp_version;

        $this->is_debug = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG;
        $this->wp_version = substr(str_replace('.', '', $wp_version), 0, 2);

        add_action('widgets_init', array($this, 'widgets_init'));

        $this->load_language();

        define('GDRTS_WPV', intval($this->wp_version));

        add_action('init', array($this, 'rating_load'), 15);
        add_action('init', array($this, 'rating_start'), 20);

        add_action('gdrts_cron_daily_maintenance_job', array($this, 'daily_maintenance_job'));
        add_action('gdrts_cron_ondemand_maintenance_job', array($this, 'ondemand_maintenance_job'));
    }

    public function init() {
        do_action('gdrts_theme_setup');

        require_once(GDRTS_PATH.'rating/base.render.php');
        require_once(GDRTS_PATH.'rating/base.override.php');

        $this->_jquery = apply_filters('gdrts_jquery_enqueue_dependency', 'jquery');
    }

    public function ondemand_maintenance_job() {
        @ini_set('memory_limit', '256M');
        @set_time_limit(0);

        require_once(GDRTS_PATH.'core/admin/cron.php');

        do_action('gdrts_ondemand_maintenance_job_start');

        gdrts_admin_cron::recalculate_on_max_change();

        do_action('gdrts_ondemand_maintenance_job_end');
    }

    public function daily_maintenance_job() {
        @ini_set('memory_limit', '256M');
        @set_time_limit(0);

        require_once(GDRTS_PATH.'core/admin/cron.php');

        do_action('gdrts_daily_maintenance_job_start');

        gdrts_admin_cron::recalculate_on_max_change();
        gdrts_admin_cron::recalculate_statistics();
        gdrts_admin_cron::remove_expired_cache_entries();

        do_action('gdrts_daily_maintenance_job_end');
    }

    public function lib_file($lib, $type, $name, $min = true, $base_url = null) {
        $get = is_null($base_url) ? GDRTS_URL.'libs/' : $base_url;

        $get.= $lib.'/'.$name;
        
        if (!$this->is_debug && $min) {
            $get.= '.min';
        }

        $get.= '.'.$type;

        return $get;
    }

    public function file($type, $name, $d4p = false, $min = true, $base_url = null) {
        $get = is_null($base_url) ? GDRTS_URL : $base_url;

        if ($d4p) {
            $get.= 'd4plib/resources/';
        }

        if ($name == 'font') {
            $get.= 'font/styles.css';
        } else {
            $get.= $type.'/'.$name;

            if (!$this->is_debug && $type != 'font' && $min) {
                $get.= '.min';
            }

            $get.= '.'.$type;
        }

        return $get;
    }

    public function rating_load() {
        do_action('gdrts_load');

        $this->init_capabilities();

        if (!wp_next_scheduled('gdrts_cron_daily_maintenance_job')) {
            $cron_hour = intval(gdrts_settings()->get('cronjob_hour_of_day'));
            $cron_time = mktime($cron_hour, 0, 0, date('m'), date('d') + 1, date('Y'));

            wp_schedule_event($cron_time, 'daily', 'gdrts_cron_daily_maintenance_job');
        }

        if (gdrts_settings()->get('debug_rating_block')) {
            add_action('gdrts-template-rating-block-before', array($this, 'rating_block_single'), 1);
        }

        if (gdrts_settings()->get('load_full_js')) {
            $this->_load_js = true;
        }

        if (gdrts_settings()->get('load_full_css')) {
            $this->_load_css = true;
        }

        $this->register_scripts();

        do_action('gdrts_init');
    }

    public function prepare_ondemand_maintenance() {
        wp_schedule_single_event(time() + 5, 'gdrts_cron_ondemand_maintenance_job');
    }

    public function widgets_init() {
        $this->widgets = apply_filters('gdrts_widgets_list', array(
            'stars-rating-block' => array('method' => 'stars-rating', 'label' => __("Stars Rating Block", "gd-rating-system"), 'widget' => 'gdrtsWidget_stars_rating_block'),
            'stars-rating-list' => array('method' => 'stars-rating', 'label' => __("Stars Rating List", "gd-rating-system"), 'widget' => 'gdrtsWidget_stars_rating_list'),
            'like-this-block' => array('method' => 'like-this', 'label' => __("Like This Block", "gd-rating-system"), 'widget' => 'gdrtsWidget_like_this_block'),
            'like-this-list' => array('method' => 'like-this', 'label' => __("Like This List", "gd-rating-system"), 'widget' => 'gdrtsWidget_like_this_list')
        ));

        $disabled_widgets = apply_filters('gdrts_disabled_widgets', gdrts_settings()->get('disable_widgets', 'early'));

        foreach ($this->widgets as $folder => $data) {
            if (!in_array($folder, $disabled_widgets)) {
                $path = isset($data['folder']) ? $data['folder'] : GDRTS_PATH.'widgets/';

                require_once($path.$folder.'.php');

                register_widget($data['widget']);
            }
        }
    }

    public function init_capabilities() {
        $role = get_role('administrator');

        if (!is_null($role)) {
            $role->add_cap('gdrts_standard');
        } else {
            $this->cap = 'activate_plugins';
        }

        define('GDRTS_CAP', $this->cap);
    }

    public function load_language() {
        load_plugin_textdomain('gd-rating-system', false, 'gd-rating-system/languages');
        load_plugin_textdomain('d4plib', false, 'gd-rating-system/d4plib/languages');
    }

	public function locale() {
		return apply_filters('plugin_locale', get_user_locale(), $this->plugin);
	}

	public function locale_js_code($script) {
		$locale = $this->locale();

		if (!empty($locale) && isset($this->js_locale[$script])) {
			$code = strtolower(substr($locale, 0, 2));

			if (in_array($code, $this->js_locale[$script])) {
				return $code;
			}
		}

		return false;
	}

    public function rating_start() {
        do_action('gdrts_core');

        if (gdrts_settings()->get('load_on_demand')) {
            add_action('gdrts_demand_files_enqueue', array($this, 'enqueue_core_files'));
        } else {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_core_files'));
        }
    }

    public function register_scripts() {
        $js = array();
        $css = array();

        if (!empty($this->_jquery)) {
            $js[] = $this->_jquery;
        }

        wp_register_script('gdrts-events', $this->file('js', 'rating/events'), $js, gdrts_settings()->file_version(), false);

        $js[] = 'gdrts-events';

        $js = apply_filters('gdrts_enqueue_core_depend_js', $js);

        do_action('gdrts_register_enqueue_files_early', $this->_load_js, $this->_load_css, $js, $css);

        if ($this->_load_css) {
            wp_register_style('gdrts-full', $this->file('css', 'methods/full'), array(), gdrts_settings()->file_version());

            if (is_rtl()) {
                wp_register_style('gdrts-full-rtl', $this->file('css', 'methods/full-rtl'), array('gdrts-full'), gdrts_settings()->file_version());

                $css[] = 'gdrts-full-rtl';
            } else {
                $css[] = 'gdrts-full';
            }
        } else {
            $css[] = 'gdrts-ratings-grid';
            $css[] = 'gdrts-ratings-core';

            wp_register_style('gdrts-ratings-grid', $this->file('css', 'rating/grid'), array(), gdrts_settings()->file_version());
            wp_register_style('gdrts-ratings-core', $this->file('css', 'rating/core'), array('gdrts-ratings-grid'), gdrts_settings()->file_version());

            wp_register_style('gdrts-methods-stars-rating', $this->file('css', 'methods/stars'), array('gdrts-ratings-core'), gdrts_settings()->file_version());
            wp_register_style('gdrts-methods-like-this', gdrts_plugin()->file('css', 'methods/likes'), array('gdrts-ratings-core'), gdrts_settings()->file_version());

            if (is_rtl()) {
                wp_register_style('gdrts-methods-stars-rating-rtl', $this->file('css', 'methods/stars-rtl'), array('gdrts-methods-stars-rating'), gdrts_settings()->file_version());
            }
        }

        if ($this->_load_js) {
            wp_register_script('gdrts-full', $this->file('js', 'methods/full'), $js, gdrts_settings()->file_version(), true);

            $js[] = 'gdrts-full';
        } else {
            wp_register_script('gdrts-ratings-core', $this->file('js', 'rating/core'), $js, gdrts_settings()->file_version(), true);

            $js[] = 'gdrts-ratings-core';

            wp_register_script('gdrts-methods-stars-rating', $this->file('js', 'methods/stars-rating'), array('gdrts-ratings-core'), gdrts_settings()->file_version(), true);
            wp_register_script('gdrts-methods-like-this', gdrts_plugin()->file('js', 'methods/like-this'), array('gdrts-ratings-core'), gdrts_settings()->file_version(), true);
        }

        do_action('gdrts_register_enqueue_files', $this->_load_js, $this->_load_css, $js, $css);
    }

    public function enqueue_core_files() {
        if ($this->enqueued()) {
            return;
        }

        add_action('wp_footer', array($this, 'footer'), 100000000);

        if (gdrts_settings()->force_debug()) {
            $this->is_debug = true;
        }

        if ($this->_load_css) {
            if (is_rtl()) {
                wp_enqueue_style('gdrts-full-rtl');
            } else {
                wp_enqueue_style('gdrts-full');
            }
        } else {
            wp_enqueue_style('gdrts-ratings-grid');
            wp_enqueue_style('gdrts-ratings-core');
        }

        if ($this->_load_js) {
            wp_enqueue_script('gdrts-full');
        } else {
            wp_enqueue_script('gdrts-ratings-core');
        }

        do_action('gdrts_enqueue_core_files', $this->_load_js, $this->_load_css);

        $_args = apply_filters('gdrts_enqueue_core_files_data', array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('gd-rating-system'),
            'rtl' => is_rtl(),
            'user' => get_current_user_id(),
            'handler' => 'gdrts_live_handler',
            'ajax_error' => gdrts_settings()->get('debug_ajax_error'),
            'wp_version' => GDRTS_WPV
        ));

        wp_localize_script($this->_load_js ? 'gdrts-full' : 'gdrts-ratings-core', 'gdrts_rating_data', $_args);

        $this->_enqueued = true;
    }

    public function footer() {
        gdrts()->find_template('gdrts--system--run-javascript-core.php');
    }

    public function enqueued() {
        return $this->_enqueued;
    }

    public function rating_block_single() {
        $lines = array('GD Rating System '.ucfirst(gdrts_settings()->info_edition).' '.gdrts_settings()->info_version.' - b'.gdrts_settings()->info_build);

        echo '<!-- '.join(D4P_EOL, $lines).' -->'.D4P_EOL;
    }

    public function load_full_css() {
        return $this->_load_css;
    }

    public function load_full_js() {
        return $this->_load_js;
    }

    public function method_enqueue_stars() {
        if (!$this->load_full_css()) {
            if (is_rtl()) {
                wp_enqueue_style('gdrts-methods-stars-rating-rtl');
            } else {
                wp_enqueue_style('gdrts-methods-stars-rating');
            }
        }

        if (!$this->load_full_js()) {
            wp_enqueue_script('gdrts-methods-stars-rating');
        }
    }
}
