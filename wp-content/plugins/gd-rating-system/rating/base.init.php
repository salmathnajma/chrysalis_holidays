<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core {
    private $_embed_loaded = false;
    private $_working_item = false;
    private $_capture_page = false;

    public $addons = array();
    public $methods = array();
    public $fonts = array();

    public $loaded = array();
    public $debug = array();

    public $template = null;
    public $widget = null;
    public $shortcode = null;

    public $widget_name_prefix = 'gdRTS: ';

    private $entities = array(
        'posts' => array('name' => 'posts', 'label' => 'Post Types', 'types' => array(), 'icon' => 'file-text-o'),
        'terms' => array('name' => 'terms', 'label' => 'Terms', 'types' => array(), 'icon' => 'tags'),
        'comments' => array('name' => 'comments', 'label' => 'Comments', 'types' => array(), 'icon' => 'comments-o'),
        'users' => array('name' => 'users', 'label' => 'Users', 'types' => array(), 'icon' => 'users'),
        'custom' => array('name' => 'custom', 'label' => 'Custom', 'types' => array(), 'icon' => 'asterisk')
    );

    public function __construct() {
        require_once(GDRTS_PATH.'rating/base.data.php');

        require_once(GDRTS_PATH.'rating/base.functions.php');
        require_once(GDRTS_PATH.'rating/base.expanded.php');

        require_once(GDRTS_PATH.'rating/core.log-item.php');
        require_once(GDRTS_PATH.'rating/core.data-item.php');
        require_once(GDRTS_PATH.'rating/core.item.php');
        require_once(GDRTS_PATH.'rating/core.user.php');
        require_once(GDRTS_PATH.'rating/core.user.rating.php');
	    require_once(GDRTS_PATH.'rating/core.rules.php');
        require_once(GDRTS_PATH.'rating/core.capture.php');
        require_once(GDRTS_PATH.'rating/core.limiter.php');

        require_once(GDRTS_PATH.'core/objects/cache.memory.php');
        require_once(GDRTS_PATH.'core/objects/cache.db.php');

        add_action('gdrts_theme_setup', array($this, 'early'));
        add_action('gdrts_load', array($this, 'prepare'));
        add_action('gdrts_init', array($this, 'prepared'));
        add_action('template_redirect', array($this, 'capture'));

        add_action('wp', array($this, 'ready'));

        $this->unload_template();
    }

    /** @return gdrts_core_capture|bool */
    public function page() {
        return $this->_capture_page;
    }

    /** @return gdrts_core_db */
    public function db() {
        return gdrts_db();
    }

    /** @return gdrts_core_settings */
    public function settings() {
        return gdrts_settings();
    }

    public function _types_registration() {
        do_action('gdrts_register_entities');

        $custom_entities = gdrts_settings()->get('custom_entities', 'early');

        foreach ($custom_entities as $entity => $data) {
            if ($entity != 'custom') {
                $this->register_entity($entity, $data['label'], $data['types'], $data['icon']);
            }
        }

        global $wp_post_types, $wp_taxonomies;

        foreach ($wp_post_types as $post_type) {
            if ($post_type->public) {
                $this->entities['posts']['types'][$post_type->name] = $post_type->label;
            }
        }

        foreach ($wp_taxonomies as $taxonomy) {
            if ($taxonomy->public) {
                $this->entities['terms']['types'][$taxonomy->name] = $taxonomy->label;
            }
        }

        $this->entities['comments']['types']['comment'] = 'Comments';
        $this->entities['users']['types']['user'] = 'Users';
        $this->entities['custom']['types']['free'] = 'Free';

        if (isset($custom_entities['custom'])) {
            $this->entities['custom']['types'] = array_merge($this->entities['custom']['types'], $custom_entities['custom']['types']);
        }

        do_action('gdrts_register_types');

        foreach ($this->entities as $entity => $obj) {
            gdrts_settings()->register('entities', $entity, array());

            foreach (array_keys($obj['types']) as $type) {
                gdrts_settings()->register('entities', $entity.'.'.$type, array());
            }
        }
    }

    public function _extensions_registration() {
        do_action('gdrts_register_methods_and_addons');

        foreach ($this->addons as $addon => $obj) {
            gdrts_settings()->register('load', 'addon_'.$addon, $obj['autoload']);
        }

        foreach ($this->methods as $method => $obj) {
            gdrts_settings()->register('load', 'method_'.$method, $obj['autoload']);
        }
    }

    public function _fonts_registration() {
        require_once(GDRTS_PATH.'rating/font.default.php');
        $this->fonts['font'] = new gdrts_font_default();
    }

    public function decimals() {
        return gdrts_settings()->get('decimal_round');
    }

    public function register_item_option($entity, $name, $option, $value) {
        gdrts_settings()->register('items', $entity.'_'.$name.'_'.$option, $value);
    }

    public function early() {
        do_action('gdrts_early_settings');
    }

    public function prepare() {
        $this->_types_registration();
        $this->_extensions_registration();
        $this->_fonts_registration();

        do_action('gdrts_load_settings');

        $load = gdrts_settings()->group_get('load');

        foreach ($load as $key => $do) {
            if ($do) {
                $this->loaded[] = $key;

                do_action('gdrts_load_'.$key);
            }
        }

        do_action('gdrts_populate_settings');
        do_action('gdrts_register_icons_fonts');

        foreach (array_keys($this->fonts) as $type) {
            $load_font = gdrts_settings()->get('fonticons_'.$type);
            $load_font = is_null($load_font) ? true : $load_font;

            if (apply_filters('gdrts_activate_font_'.$type, $load_font)) {
                $this->fonts[$type]->actions();
            }
        }

        gdrts_limiter();

        do_action('gdrts_plugin_rating_ready');
    }

    public function prepared() {
        require_once(GDRTS_PATH.'rating/core.sort-posts.php');
        require_once(GDRTS_PATH.'rating/core.sort-comments.php');

        gdrts_posts_sort();
        gdrts_comments_sort();
    }

    public function ready() {
        do_action('gdrts_ready');

        if ($this->is_locked()) {
            add_action('gdrts-template-rating-block-after', array($this, 'show_disabled_message'));
        }
    }

    public function capture() {
        if (!is_admin()) {
            $this->_capture_page = new gdrts_core_capture();
        }
    }

    public function show_disabled_message() {
        echo '<div class="gdrts-voting-disabled">';

        if (gdrts_settings()->get('maintenance', 'core')) {
            echo gdrts_settings()->get('maintenance_message', 'core');
        } else if (gdrts_settings()->get('voting_disabled', 'core')) {
            echo gdrts_settings()->get('voting_disabled_message', 'core');
        }

        echo '</div>';
    }

    public function cookie_key() {
        return apply_filters('gdrts_cookie_key', 'wp-gdrts-log');
    }

    public function cookie_expiration($time = null) {
        if (is_null($time)) {
            $time = apply_filters('gdrts_cookie_expiration', YEAR_IN_SECONDS);
        }

        return time() + $time;
    }

    public function is_locked() {
        return gdrts_settings()->get('voting_disabled', 'core') || gdrts_settings()->get('maintenance', 'core');
    }

    public function load_embed() {
        if (!$this->_embed_loaded) {
            require_once(GDRTS_PATH.'rating/base.embed.php');

            $this->_embed_loaded = true;
        }
    }

    public function debug_queue($value, $label = '') {
        $this->debug[] = array('value' => $value, 'label' => $label);
    }

    public function flush_debug_queue() {
        foreach ($this->debug as $debug) {
            $item = D4P_EOL.'<!-- ';

            if ($debug['label'] != '') {
                $item.= $debug['label'].':'.D4P_EOL;
            }

            $_value = gdrts_print_debug_info($debug['value']);

            $item.= $_value.' -->';

            echo $item;
        }

        $this->debug = array();
    }

    public function get_font_star_char($type, $name) {
        if ($type == 'image') {
            return '';
        }

        if (!isset($this->fonts[$type])) {
            $type = 'font';
        }

        return $this->fonts[$type]->get_star_char($name);
    }

    public function get_font_like_chars($type, $name) {
        if ($type == 'image') {
            return '';
        }

        if (!isset($this->fonts[$type])) {
            $type = 'font';
        }

        return $this->fonts[$type]->get_like_chars($name);
    }

    public function default_storages_paths() {
        return apply_filters('gdrts_default_templates_storage_paths', array(
            GDRTS_PATH.'templates/',
            GDRTS_PATH.'templates/stars-rating/',
            GDRTS_PATH.'templates/like-this/',
            WP_CONTENT_DIR.'/uploads/gdrts/'
        ));
    }

    public function has_entity($entity) {
        return isset($this->entities[$entity]);
    }

    public function get_entities() {
        return $this->entities;
    }

    public function get_entity($entity) {
        return isset($this->entities[$entity]) ? $this->entities[$entity] : array();
    }

    public function has_entity_type($entity, $type) {
        return isset($this->entities[$entity]['types'][$type]);
    }

    public function get_entity_types($entity) {
        return isset($this->entities[$entity]['types']) ? $this->entities[$entity]['types'] : array();
    }

	public function get_entity_label($entity) {
		return isset($this->entities[$entity]) ? $this->entities[$entity]['label'] : '';
	}

    public function get_entity_type_label($entity, $type) {
    	if ($type === false) {
    		return $this->get_entity_label($entity);
	    }

        return isset($this->entities[$entity]['types'][$type]) ? $this->entities[$entity]['types'][$type] : '';
    }

    public function get_entity_joined_label($name, $split = '::') {
        $parts = explode($split, $name);
        $entity = $parts[0];
        $type = $parts[1];

        return array(
            'entity' => $this->get_entity_label($entity),
            'type' => $this->get_entity_type_label($entity, $type)
        );
    }

    public function get_object_label($object) {
    	if (gdrts_is_method_valid($object)) {
    		return $this->methods[$object]['label'];
	    } else if (gdrts_is_addon_loaded($object)) {
		    return $this->addons[$object]['label'];
	    }

    	return $object;
    }

    /**
     * Register new entity.
     * 
     * @param string $entity name of the entity to add
     * @param string $label label for the entity
     * @param array $types list of the types to add for entity
     * @param string $icon icon for the entity
     */
    public function register_entity($entity, $label, $types = array(), $icon = 'ticket') {
        if (!$this->has_entity($entity)) {
            $this->entities[$entity] = array('name' => $entity, 'label' => $label, 'types' => $types, 'icon' => $icon);
        }
    }

    /**
     * Register entity type.
     * 
     * @param string $entity name of the entity
     * @param string $name name of the type to add
     * @param string $label label for the type to add
     */
    public function register_type($entity, $name, $label) {
        if ($this->has_entity($entity)) {
            $this->entities[$entity]['types'][$name] = $label;
        }
    }

    /**
     * Register an addon.
     * 
     * @param string $name name of the addon
     * @param string $label label for the addon
     * @param array $args optional settings
     */
    public function register_addon($name, $label, $args = array()) {
        if (!isset($this->addons[$name])) {
            $defaults = array(
                'label' => $label,
                'icon' => 'puzzle-piece',
                'override' => false,
                'override_basic' => true,
                'override_methods' => array(),
                'method_settings' => false,
                'hide_from_extensions' => false,
                'autoload' => true,
                'pro' => true,
                'free' => false,
                'pack' => false,
                'plugin' => false
            );

            $args = wp_parse_args($args, $defaults);

            $this->addons[$name] = $args;
        }
    }

    /**
     * Register a method.
     * 
     * @param string $name name of the method
     * @param string $label label for the method
     * @param array $args optional settings
     */
    public function register_method($name, $label, $args = array()) {
        if (!isset($this->methods[$name])) {
            $defaults = array(
                'label' => $label,
                'icon' => 'star',
                'override' => false, 
                'autoembed' => true, 
                'autoload' => true, 
                'review' => false,
                'has_series' => false,
                'has_votes' => true,
                'has_max' => true,
                'is_numeric' => true,
                'form_ready' => true,
                'allow_multiple_votes' => true,
                'db_normalized' => 1,
                'db_items_multi' => false,
                'db_logs_multi' => false,
                'method_settings' => false
            );

            $args = wp_parse_args($args, $defaults);

            $this->methods[$name] = $args;
        }
    }

    public function unload_template() {
        $this->template = null;
    }

    public function unload_widget() {
        $this->widget = null;
    }

    public function unload_shortcode() {
        $this->shortcode = null;
    }

    public function load_template($type, $list, $path) {
        $file = basename($path, '.php');
        $parts = explode('--', $file, 3);

        $this->template = array(
            'method' => gdrts_loop()->method_name(),
            'type' => $type,
            'list' => $list,
            'name' => $parts[2],
            'file' => basename($path),
            'folder' => dirname($path),
            'path' => $path
        );

        if (gdrts_debug_on()) {
            $this->debug_queue($this->template['path'], 'template');
        }
    }

    public function load_widget($name, $args, $instance) {
        $this->widget = array(
            'name' => $name,
            'args' => $args,
            'instance' => $instance
        );
    }

    public function load_shortcode($name, $args) {
        $this->shortcode = array(
            'name' => $name,
            'args' => $args
        );
    }

    public function find_template($templates, $load = true) {
        $theme = array();

        $templates = (array)$templates;

        foreach ($templates as $template) {
            $theme[] = 'gdrts/'.$template;
            $theme[] = $template;
        }

        $found = locate_template($theme, false);

        if (empty($found)) {
            $storages = gdrts()->default_storages_paths();

            foreach ($templates as $template) {
                foreach ($storages as $path) {
                    $path = trailingslashit($path);

                    if (file_exists($path.$template)) {
                        $found = $path.$template;
                        break 2;
                    }
                }
            }
        }

        if (empty($found)) {
            return null;
        }

        if ($load) {
            include($found);
        } else {
            return $found;
        }
    }

    public function trigger_enqueue() {
        if (has_action('gdrts_demand_files_enqueue')) {
            do_action('gdrts_demand_files_enqueue');
        }
    }

    public function render_template($templates, $type = 'single') {
        ob_start();

        $found = $this->find_template($templates, false);
        $this->load_template($type, $templates, $found);

        $this->flush_debug_queue();

        $result = '';

        if (!is_null($found)) {
            include($found);

            $result = ob_get_contents();

            ob_end_clean();
        }

        $this->unload_template();

        return $result;
    }

    public function get_valid_taxonomies() {
        $taxonomies = get_taxonomies(array('public' => true, 'show_ui' => true));

        return apply_filters('gdrts_get_valid_taxonomies', array_keys($taxonomies));
    }

    public function get_object_taxonomies($object, $output = 'names') {
        $list = get_object_taxonomies($object, $output);
        $valid = $this->get_valid_taxonomies();

        $taxonomies = array_intersect($valid, $list);
        $taxonomies = array_values($taxonomies);

        return $taxonomies;
    }

    public function convert_method_series_pair($method) {
        $_with_series = strpos($method, '::') !== false;

        $data = array(
	        'input' => $method,
	        'label' => '',
	        'method' => $method,
	        'method_label' => '',
	        'series' => '',
	        'series_label' => '');

        if ($_with_series) {
            $_split = explode('::', $method);

            $data['method'] = $_split[0];
            $data['series'] = $_split[1];
        }

        $obj = gdrts()->methods[$data['method']];

        $data['label'] = $obj['label'];
        $data['method_label'] = $obj['label'];

        if (!empty($data['series'])) {
            $method_obj = gdrts_get_method_object($data['method']);
            $data['series_label'] = $method_obj->get_series_label($data['series']);
            $data['label'].= ' :: '.$method_obj->get_series_label($data['series']);
        }

        return $data;
    }

    public function expand_methods_with_series($methods, $with = true) {
        $new = array();

        if ($methods === true) {
            $methods = array_keys($this->methods);
        }

        foreach ($methods as $method) {
            if (gdrts_is_method_loaded($method)) {
                if (gdrts_method_has_series($method)) {
                    if ($with) {
                        $new[] = $method;
                    }

                    $obj = gdrts_get_method_object($method);

                    foreach (array_keys($obj->all_series()) as $series) {
                        $new[] = $method.'::'.$series;
                    }
                } else {
                    $new[] = $method;
                }
            }
        }

        return $new;
    }

	/** @return gdrts_rating_item */
	public function get_item() {
		return $this->_working_item;
	}

	/** @param gdrts_rating_item $item */
	public function set_item($item) {
		$this->_working_item = $item;
	}

	public function get_method_prop($method, $name, $default = null) {
	    if (isset($this->methods[$method])) {
	        if (isset($this->methods[$method][$name])) {
	            return $this->methods[$method][$name];
            }
        }

	    return $default;
    }

    public function get_addon_prop($addon, $name, $default = null) {
        if (isset($this->addons[$addon])) {
            if (isset($this->addons[$addon][$name])) {
                return $this->addons[$addon][$name];
            }
        }

        return $default;
    }
}
