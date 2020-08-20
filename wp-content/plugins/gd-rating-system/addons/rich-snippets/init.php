<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_addon_rich_snippets_init extends gdrts_extension_init {
    public $group = 'addons';
    public $prefix = 'rich-snippets';

    public function __construct() {
        parent::__construct();

        add_action('gdrts_load_addon_rich-snippets', array($this, 'load'), 2);
        add_filter('gdrts_info_addon_rich-snippets', array($this, 'info'));
    }

    public function register() {
        gdrts()->register_addon('rich-snippets', __("Rich Snippets", "gd-rating-system"), array(
            'free' => true
        ));
    }

    public function settings() {
        $this->register_option('snippet_single_per_page', false);
        $this->register_option('snippet_on_singular_pages', false);
        $this->register_option('snippet_use_gmt_dates', false);

        $this->register_option('snippet_organization_name', '');
        $this->register_option('snippet_organization_logo', 0);

        $this->register_option('metaboxes_post_types', null);

        foreach (array_keys(gdrts()->get_entity_types('posts')) as $name) {
            $location = in_array($name, array('post', 'page')) ? 'jsonld' : 'hide';

            $itemmode  = $name == 'page' ? 'web_page' : (
                         $name == 'post' ? 'article' : (
                         $name == 'attachment' ? 'media_object' : 'web_page'));

            $this->register_option($name.'_snippet_display', $location);
            $this->register_option($name.'_snippet_mode', $itemmode);
            $this->register_option($name.'_snippet_rating', 'none');
            $this->register_option($name.'_snippet_rating_method', 'stars-rating');
            $this->register_option($name.'_snippet_review_method', 'stars-review');
            $this->register_option($name.'_snippet_custom_type_name', 'Thing');
            $this->register_option($name.'_snippet_custom_type_features', array('author', 'publisher', 'image', 'published', 'modified'));
        }
    }

    public function info($info = array()) {
        return array('icon' => 'flag', 'description' => __("Generate rich snippets used by Google for search engine results.", "gd-rating-system"));
    }

    public function load() {
        require_once(GDRTS_PATH.'addons/rich-snippets/objects.php');
        require_once(GDRTS_PATH.'addons/rich-snippets/load.php');
    }
}

$__gdrts_addon_rich_snippets = new gdrts_addon_rich_snippets_init();
