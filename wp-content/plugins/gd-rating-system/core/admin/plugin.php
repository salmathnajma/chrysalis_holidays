<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_admin_core extends d4p_admin_core {
	public $plugin = 'gd-rating-system';
	public $help;
	public $privacy;

	function __construct() {
		parent::__construct();

		$this->url = GDRTS_URL;
		$this->help = new gdrts_admin_help();
		$this->privacy = new gdrts_admin_privacy();

		add_action('save_post', array($this, 'metabox_post_save'), 10, 3);

        add_action('gdrts_core', array($this, 'core'), 20);

		add_filter('set-screen-option', array($this, 'screen_options_grid_rows_save'), 10, 3);
		add_action('gdrts_settings_value_changed', array($this, 'settings_value_changed'), 10, 4);
        add_action('gdrts_rule_value_changed', array($this, 'rule_value_changed'), 10, 4);

		if (is_multisite()) {
			add_filter('wpmu_drop_tables', array($this, 'wpmu_drop_tables'));
		}

		add_filter('gdrts_admin_metabox_tabs', array($this, 'metabox_tabs'), 20, 3);
		add_action('gdrts_admin_metabox_content_posts-override', array($this, 'metabox_content_override'), 10, 2);
		add_action('gdrts_admin_metabox_save_post', array($this, 'metabox_save_override'));

		add_filter('gdrts_admin_grid_votes_columns', array($this, 'admin_grid_votes_columns'));

        add_filter('plugin_row_meta', array(&$this, 'plugin_links'), 10, 2);

		do_action('gdrts_admin_construct');
	}

    function plugin_links($links, $file) {
        if ($file == 'gd-rating-system/gd-rating-system.php' ){
            $links[] = '<a target="_blank" style="color: #cc0000; font-weight: bold;" href="https://plugins.dev4press.com/gd-rating-system/">'.__("Upgrade to GD Rating System Pro", "gd-rating-system").'</a>';
        }

        return $links;
    }

	public function admin_grid_votes_columns($columns) {
		if (gdrts_using_hashed_ip() && isset($columns['ip'])) {
			unset($columns['ip']);
		}

		return $columns;
	}

	public function metabox_tabs($tabs, $post_id, $post_type) {
		if (gdrts_settings()->get('metabox_override')) {
			$tabs['posts-override'] = '<span class="dashicons dashicons-admin-page" aria-labelledby="gdrts-addon-metatab-posts-override" title="'.__("Override", "gd-rating-system").'"></span><span id="gdrts-addon-metatab-posts-override" class="d4plib-metatab-label">'.__("Override", "gd-rating-system").'</span>';
		}

		return $tabs;
	}

	public function metabox_content_override($post_id, $post_type) {
		global $post;

		$item = gdrts_rating_item::get_instance(null, 'posts', $post->post_type, $post->ID);

		if ($item === false) {
			_e("This item is invalid.", "gd-rating-system");
		} else {
			$_gdrts_id = $post->ID;
			$_gdrts_title = $item->get( 'title', '' );
			$_gdrts_url = $item->get( 'url', '' );

			include( GDRTS_PATH . 'forms/meta/posts-override.php' );
		}
	}

	public function metabox_save_override($post) {
		if (isset($_POST['gdrts']['posts-override'])) {
			$data = $_POST['gdrts']['posts-override'];

			if (wp_verify_nonce($data['nonce'], 'gdrts-posts-override-'.$post->ID) !== false) {
				$item = gdrts_rating_item::get_instance(null, 'posts', $post->post_type, $post->ID);

				if ($item === false) {
					return;
				}

				$title = d4p_sanitize_basic($data['title']);
				$url = d4p_sanitize_basic($data['url']);

				$item->prepare_save();

				if ($title == '') {
					$item->un_set('title');
				} else {
					$item->set('title', $title);
				}

				if ($url == '') {
					$item->un_set('url');
				} else {
					$item->set('url', $url);
				}

				$item->save(false);
			}
		}
	}

	public function wpmu_drop_tables($drop_tables) {
		return array_merge($drop_tables, gdrts_db()->db_site);
	}

	public function rule_value_changed($name, $rule, $old, $new) {
	    $ondemand = false;

	    if (in_array($rule->object, array('stars-rating'))) {
	        if ($name == 'stars') {
                if ($rule->object == 'stars-rating') {
                    gdrts_settings()->set('cronjob_recheck_max_stars_rating', true, 'core', true);
                }

                $ondemand = true;
            }
        }

        if ($ondemand) {
            gdrts_plugin()->prepare_ondemand_maintenance();
        }
    }

	public function settings_value_changed($name, $group, $old, $new) {
        $ondemand = false;

        if (strpos($name, 'stars-rating_stars') !== false) {
            gdrts_settings()->set('cronjob_recheck_max_stars_rating', true, 'core', true);

            $ondemand = true;
        }

        if ($ondemand) {
            gdrts_plugin()->prepare_ondemand_maintenance();
        }
	}

	public function admin_meta() {
		if (current_user_can('edit_posts')) {
			$post_types = get_post_types(array('public' => true), 'objects');
			$allowed_types = gdrts_settings()->get('metaboxes_post_types');

			foreach (array_keys($post_types) as $post_type) {
				if ($post_type != 'attachment' && (is_null($allowed_types) || (is_array($allowed_types) && in_array($post_type, $allowed_types)))) {
					add_meta_box('gdrts-metabox', __("GD Rating System", "gd-rating-system"), array($this, 'metabox_post'), $post_type, 'normal', 'high');
				}
			}
		}
	}

	public function metabox_post_save($post_id, $post, $update) {
		if (isset($_POST['gdrts'])) {
			do_action('gdrts_admin_metabox_save_post', $post, $update);
		}
	}

	public function metabox_post() {
		global $post_ID;

		if (current_user_can('edit_post', $post_ID)) {
			include(GDRTS_PATH.'forms/meta/post.php');
		} else {
			_e("You don't have rights to control these settings", "gd-rating-system");
		}
	}

	public function screen_options_grid_rows_save($status, $option, $value) {
		if ($option == 'gdrts_rows_per_page_ratings') {
			return $value;
		}

		if ($option == 'gdrts_rows_per_page_votes') {
			return $value;
		}

		return $status;
	}

	public function screen_options_grid_rows_ratings() {
		$args = array(
			'label' => __("Rows", "gd-rating-system"),
			'default' => 25,
			'option' => 'gdrts_rows_per_page_ratings'
		);

		add_screen_option('per_page', $args);

		require_once(GDRTS_PATH.'core/grids/ratings.php');

		$load_table = new gdrts_grid_ratings();
	}

	public function screen_options_grid_rows_votes() {
		$args = array(
			'label' => __("Rows", "gd-rating-system"),
			'default' => 25,
			'option' => 'gdrts_rows_per_page_votes'
		);

		add_screen_option('per_page', $args);

		require_once(GDRTS_PATH.'core/grids/votes.php');

		$load_table = new gdrts_grid_votes();
	}

	public function core() {
		parent::core();

		if (gdrts_settings()->force_debug()) {
			$this->is_debug = true;
		}

		do_action('gdrts_admin_load_modules');

		if (isset($_GET['panel']) && $_GET['panel'] != '') {
			$this->panel = d4p_sanitize_slug($_GET['panel']);
		}

		if (isset($_POST['gdrts_handler']) && $_POST['gdrts_handler'] == 'postback') {
			require_once(GDRTS_PATH.'core/admin/postback.php');

			$postback = new gdrts_admin_postback();
		}

		$this->init_ready();

		if (gdrts_settings()->is_install()) {
			add_action('admin_notices', array($this, 'install_notice'));
		} else if (gdrts_settings()->is_update()) {
			add_action('admin_notices', array($this, 'update_notice'));
		} else if (gdrts_settings()->get('upgrade_to_40', 'core') === false) {
            add_action('admin_notices', array($this, 'upgrade_notice'));
        }
	}

    public function upgrade_notice() {
        if (current_user_can('install_plugins') && $this->page !== 'front' && $this->page !== 'tools' && $this->panel !== 'dbfour') {
            echo '<div class="error"><p>';
            echo __("GD Rating System database upgrade process needs to be completed. Please, run the upgrade.", "gd-rating-system");
            echo ' <a href="admin.php?page=gd-rating-system-tools&panel=dbfour">'.__("Click Here", "gd-rating-system").'</a>.';
            echo '</p></div>';
        }
    }

	public function update_notice() {
		if (current_user_can('install_plugins') && $this->page === false) {
			echo '<div class="updated"><p>';
			echo __("GD Rating System is updated, and you need to review the update process.", "gd-rating-system");
			echo ' <a href="admin.php?page=gd-rating-system-about">'.__("Click Here", "gd-rating-system").'</a>.';
			echo '</p></div>';
		}
	}

	public function install_notice() {
		if (current_user_can('install_plugins') && $this->page === false) {
			echo '<div class="updated"><p>';
			echo __("GD Rating System is activated and it needs to finish installation.", "gd-rating-system");
			echo ' <a href="admin.php?page=gd-rating-system-about">'.__("Click Here", "gd-rating-system").'</a>.';
			echo '</p></div>';
		}
	}

	public function init_ready() {
		$this->menu_items = apply_filters('gdrts_admin_menu_items', array(
			'front' => array('title' => __("Overview", "gd-rating-system"), 'icon' => 'home'),
			'about' => array('title' => __("About", "gd-rating-system"), 'icon' => 'info-circle'),
			'settings' => array('title' => __("Settings", "gd-rating-system"), 'icon' => 'cogs'),
			'rules' => array('title' => __("Rules", "gd-rating-system"), 'icon' => 'star-o'),
			'types' => array('title' => __("Rating Types", "gd-rating-system"), 'icon' => 'thumb-tack'),
			'ratings' => array('title' => __("Ratings Items", "gd-rating-system"), 'icon' => 'star-half-o'),
			'log' => array('title' => __("Votes Log", "gd-rating-system"), 'icon' => 'file-text-o'),
			'transfer' => array('title' => __("Transfer Data", "gd-rating-system"), 'icon' => 'exchange'),
			'information' => array('title' => __("Information", "gd-rating-system"), 'icon' => 'info-circle'),
			'tools' => array('title' => __("Tools", "gd-rating-system"), 'icon' => 'wrench')
		));
	}

	public function admin_init() {
		d4p_include('grid', 'admin', GDRTS_D4PLIB);

		do_action('gdrts_admin_init');
	}

	public function admin_menu() {
		$parent = 'gd-rating-system-front';

		$icon = 'dashicons-star-filled';

		$this->page_ids[] = add_menu_page(
			'GD Rating System',
			'Rating System',
			GDRTS_CAP,
			$parent,
			array($this, 'panel_load'),
			$icon);

		foreach($this->menu_items as $item => $data) {
			$this->page_ids[] = add_submenu_page($parent,
				'GD Rating System: '.$data['title'],
				$data['title'],
				GDRTS_CAP,
				'gd-rating-system-'.$item,
				array($this, 'panel_load'));
		}

		$this->admin_load_hooks();
	}

	public function enqueue_scripts($hook) {
		$load_admin_data = false;
		$flatpickr_locale = gdrts_plugin()->locale_js_code('flatpickr');

		if ($this->page !== false) {
			d4p_admin_enqueue_defaults();

			wp_enqueue_script('jquery-ui-sortable');

            wp_enqueue_style('fontawesome', $this->url.'d4plib/resources/fontawesome/css/font-awesome.min.css', array(), D4P_FONTAWESOME);

			wp_enqueue_style('d4plib-font', $this->file('css', 'font', true), array(), D4P_VERSION);
			wp_enqueue_style('d4plib-shared', $this->file('css', 'shared', true), array(), D4P_VERSION);
			wp_enqueue_style('d4plib-admin', $this->file('css', 'admin', true), array('d4plib-shared'), D4P_VERSION);

			if (is_rtl()) {
				wp_enqueue_style('d4plib-rtl', $this->file('css', 'rtl', true), array('d4plib-admin'), D4P_VERSION);
			}

			if ($this->page == 'about') {
				wp_enqueue_style('d4plib-grid', $this->file('css', 'grid', true), array(), D4P_VERSION.'.'.D4P_BUILD);
			}

			if ($this->page == 'front') {
				wp_enqueue_style('gdrts-metabox', $this->file('css', 'admin/meta'), array(), gdrts_settings()->file_version());
			}

            wp_enqueue_style('gdrts-balloon', $this->file('css', 'admin/balloon'), array(), gdrts_settings()->file_version());
            wp_enqueue_style('gdrts-plugin', $this->file('css', 'admin/plugin'), array('d4plib-admin', 'gdrts-balloon'), gdrts_settings()->file_version());

			wp_enqueue_script('d4plib-shared', $this->file('js', 'shared', true), array('jquery', 'wp-color-picker'), D4P_VERSION, true);
			wp_enqueue_script('d4plib-admin', $this->file('js', 'admin', true), array('d4plib-shared'), D4P_VERSION, true);
			wp_enqueue_script('d4plib-limitkeypress', GDRTS_URL.'d4plib/resources/libraries/jquery.limitkeypress.min.js', array(), gdrts_settings()->file_version(), true);

			wp_enqueue_script('gdrts-plugin', $this->file('js', 'admin/plugin'), array('d4plib-admin', 'd4plib-limitkeypress', 'jquery-ui-sortable'), gdrts_settings()->file_version(), true);

			do_action('gdrts_admin_enqueue_scripts', $this->page, $this->panel);
            do_action('gdrts_admin_enqueue_scripts_'.$this->page, $this->panel);

            if ($this->panel !== false) {
                do_action('gdrts_admin_enqueue_scripts_'.$this->page.'_'.$this->panel);
            }

			if ($this->page == 'transfer') {
				wp_enqueue_script('gdrts-transfer', $this->file('js', 'transfer'), array('gdrts-plugin'), gdrts_settings()->file_version(), true);
			}

			$_data = apply_filters('gdrts_admin_enqueue_scripts_data', array(
				'nonce' => wp_create_nonce('gdrts-admin-internal'),
				'wp_version' => GDRTS_WPV,
				'page' => $this->page,
				'panel' => $this->panel,
				'flatpickr_locale' => $flatpickr_locale,
				'button_icon_ok' => '<i aria-hidden="true" class="fa fa-check fa-fw"></i> ',
				'button_icon_cancel' => '<i aria-hidden="true" class="fa fa-times fa-fw"></i> ',
				'button_icon_delete' => '<i aria-hidden="true" class="fa fa-trash fa-fw"></i> ',
                'button_icon_recalculate' => '<i aria-hidden="true" class="fa fa-refresh fa-fw"></i> ',
				'button_icon_select' => '<i aria-hidden="true" class="fa fa-check fa-fw"></i> ',
				'dialog_button_ok' => __("OK", "gd-rating-system"),
				'dialog_button_close' => __("Close", "gd-rating-system"),
				'dialog_button_cancel' => __("Cancel", "gd-rating-system"),
				'dialog_button_delete' => __("Delete", "gd-rating-system"),
                'dialog_button_recalculate' => __("Recalculate", "gd-rating-system"),
				'dialog_button_select' => __("Select", "gd-rating-system"),
				'dialog_button_remove' => __("Remove", "gd-rating-system"),
				'dialog_button_clear' => __("Clear", "gd-rating-system"),
				'dialog_title_areyousure' => __("Are you sure you want to do this?", "gd-rating-system"),
				'dialog_content_pleasewait' => __("Please Wait...", "gd-rating-system"),
				'dialog_nothing' => __("Nothing is selected, process will not start.", "gd-rating-system"),
				'dialog_changelog' => __("Changelog", "gd-rating-system"),
				'button_stop' => __("Stop Process", "gd-rating-system"),
				'step_transfer' => gdrts_settings()->get('step_transfer'),
				'step_recalculate' => gdrts_settings()->get('step_recalculate')
			));

			wp_localize_script('gdrts-plugin', 'gdrts_data', $_data);

			$load_admin_data = true;
		}

		if ($hook == 'post.php' || $hook == 'post-new.php') {
			wp_enqueue_media();

			if (apply_filters('gdrts_enqueue_core_rating_slider', false)) {
				wp_enqueue_style('gd-rating-slider', gdrts_plugin()->lib_file('gd-rating-slider', 'css', 'rating-slider.min', false), array(), gdrts_settings()->file_version());
				wp_enqueue_script('gd-rating-slider', gdrts_plugin()->lib_file('gd-rating-slider', 'js', 'rating-slider.min', false), array('jquery'), gdrts_settings()->file_version(), true);
			}

			wp_enqueue_style('d4plib-shared', $this->file('css', 'shared', true), array(), D4P_VERSION);
			wp_enqueue_style('d4plib-metabox', $this->file('css', 'meta', true), array('d4plib-shared'), D4P_VERSION);
			wp_enqueue_style('gdrts-metabox', $this->file('css', 'admin/meta'), array('d4plib-metabox'), gdrts_settings()->file_version());

			wp_enqueue_script('d4plib-shared', $this->file('js', 'shared', true), array('jquery', 'wp-color-picker'), D4P_VERSION, true);
			wp_enqueue_script('d4plib-metabox', $this->file('js', 'meta', true), array('d4plib-shared'), D4P_VERSION, true);
			wp_enqueue_script('gdrts-metabox', $this->file('js', 'admin/meta'), array('d4plib-metabox'), gdrts_settings()->file_version(), true);

			do_action('gdrts_admin_enqueue_scripts_posts', $hook);

			$_data = apply_filters('gdrts_admin_enqueue_scripts_posts_data', array(
				'nonce' => wp_create_nonce('gdrts-admin-internal'),
				'wp_version' => GDRTS_WPV,
				'flatpickr_locale' => $flatpickr_locale
			));

			wp_localize_script('gdrts-metabox', 'gdrts_data', $_data);

			$load_admin_data = true;
		}

		if ($hook == 'widgets.php') {
			wp_enqueue_script('wp-color-picker');
			wp_enqueue_style('wp-color-picker');

			wp_enqueue_style('d4plib-widgets', $this->file('css', 'widgets', true), array(), D4P_VERSION);
			wp_enqueue_script('d4plib-widgets', $this->file('js', 'widgets', true), array('jquery', 'wp-color-picker'), D4P_VERSION, true);

            do_action('gdrts_admin_enqueue_scripts_widgets', $hook);
		}

		if ($load_admin_data) {
			wp_localize_script('d4plib-shared', 'd4plib_admin_data', array(
				'string_media_image_remove' => __("Remove", "gd-rating-system"),
				'string_media_image_preview' => __("Preview", "gd-rating-system"),
				'string_media_image_title' => __("Select Image", "gd-rating-system"),
				'string_media_image_button' => __("Use Selected Image", "gd-rating-system"),
				'string_are_you_sure' => __("Are you sure you want to do this?", "gd-rating-system"),
				'string_image_not_selected' => __("Image not selected.", "gd-rating-system")
			));
		}
	}

	public function admin_load_hooks() {
		foreach ($this->page_ids as $id) {
			add_action('load-'.$id, array($this, 'load_admin_page'));
		}

		add_action('load-rating-system_page_gd-rating-system-ratings', array($this, 'screen_options_grid_rows_ratings'));
		add_action('load-rating-system_page_gd-rating-system-log', array($this, 'screen_options_grid_rows_votes'));

		do_action('gdrts_admin_load_hooks');
	}

	public function load_admin_page() {
		$screen = get_current_screen();
		$id = $screen->id;

		if ($id == 'toplevel_page_gd-rating-system-front') {
			$this->page = 'front';
		} else if (substr($id, 0, 36) == 'rating-system_page_gd-rating-system-') {
			$this->page = substr($id, 36);
		}

		if ($this->page && isset($_GET['gdrts_handler']) && $_GET['gdrts_handler'] == 'getback') {
			require_once(GDRTS_PATH.'core/admin/getback.php');

			$getback = new gdrts_admin_getback();
		}

		$this->help->tab_sidebar();

		do_action('gdrts_load_admin_page_'.$this->page);

		if ($this->panel !== false && $this->panel != '') {
			do_action('gdrts_load_admin_page_'.$this->page.'_'.$this->panel);
		}

		$this->help->tab_getting_help();
	}

	public function title() {
		return 'GD Rating System';
	}

	public function install_or_update() {
		$install = gdrts_settings()->is_install();
		$update = gdrts_settings()->is_update();

		if ($install) {
			include(GDRTS_PATH.'forms/install.php');
		} else if ($update) {
			include(GDRTS_PATH.'forms/update.php');
		}

		return $install || $update;
	}

	public function panel_load() {
		if (!$this->install_or_update()) {
			$_page = $this->page;
			$_folder = '/';

			if ($_page == 'rules') {
				$_folder = '/rules/';

				if (isset($_GET['action']) && $_GET['action'] == 'rule') {
					$_page = 'rule';
				} else {
					$_page = 'index';
				}
			} else if ($_page == 'types') {
				$_folder = '/types/';

				if (isset($_GET['action']) && ($_GET['action'] == 'edit' || $_GET['action'] == 'new')) {
					$_page = 'entity';
				} else {
					$_page = 'index';
				}
			}

			$path = apply_filters('gdrts_admin_panel_path', GDRTS_PATH.'forms'.$_folder.$_page.'.php', $_page);

			include($path);
		}
	}
}

global $_gdrts_core_admin;
$_gdrts_core_admin = new gdrts_admin_core();

function gdrts_admin() {
	global $_gdrts_core_admin;
	return $_gdrts_core_admin;
}
