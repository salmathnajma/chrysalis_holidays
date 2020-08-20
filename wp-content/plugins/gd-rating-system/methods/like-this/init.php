<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_method_like_this_init extends gdrts_extension_init {
    public $group = 'methods';
    public $prefix = 'like-this';

    public function __construct() {
        parent::__construct();

        add_action('gdrts_load_method_like-this', array($this, 'load'), 1);
        add_filter('gdrts_info_method_like-this', array($this, 'info'));
    }

    public function register() {
        gdrts()->register_method('like-this', __("Like This", "gd-rating-system"), array(
            'icon' => 'thumbs-o-up',
            'override' => true,
            'allow_multiple_votes' => false,
            'has_max' => false,
            'form_ready' => false
        ));
    }

    public function settings() {
        $this->register_option('remove_vote', true);
        $this->register_option('revote_ajax', false);
        $this->register_option('disable_rating', false);

        $this->register_option('allow_super_admin', true);
        $this->register_option('allow_user_roles', true);
        $this->register_option('allow_visitor', true);
        $this->register_option('allow_author', true);

        $this->register_option('votes_count_compact_show', true);
        $this->register_option('votes_count_compact_decimals', 1);

        $this->register_option('cta', '');
        $this->register_option('template', 'default');
        $this->register_option('alignment', 'none');
        $this->register_option('class', '');

        $this->register_option('style_type', 'font');
        $this->register_option('style_theme', 'standard');
        $this->register_option('style_image_name', 'like');
        $this->register_option('style_size', 24);

        $this->register_option('labels_like', 'Like');
        $this->register_option('labels_liked', 'Liked');
        $this->register_option('labels_unlike', 'Unlike');
    }

    public function info($info = array()) {
        return array('icon' => 'thumbs-o-up', 'description' => __("Like This rating method.", "gd-rating-system"));
    }

    public function load() {
        do_action('gdrts_pre_load_method_like-this');

        require_once(GDRTS_PATH.'methods/like-this/load.php');
    }
}

$__gdrts_method_like_this = new gdrts_method_like_this_init();
