<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_addon_posts extends gdrts_addon {
    public $prefix = 'posts';

    private $_current_rating_method = '';
    private $_current_rating_series = '';
    private $_current_rating_location = '';
    private $_current_rating_priority = 10;

    private $_is_excerpt = false;

    public function load_admin() {
        require_once(GDRTS_PATH.'addons/posts/admin.php');
    }

    public function core() {
        if (is_admin()) {
            return;
        }

        add_filter('get_the_excerpt', array($this, 'excerpt_on'), 1);
        add_filter('get_the_excerpt', array($this, 'excerpt_off'), 2000000000);

        add_action('wp_head', array($this, 'load'), 2000000000);
    }

    public function excerpt_on($excerpt) {
        $this->_is_excerpt = true;

        return $excerpt;
    }

    public function excerpt_off($excerpt) {
        $this->_is_excerpt = false;

        return $excerpt;
    }

    /** @global WP_Post $post */
    public function load() {
        if (!is_front_page() && !is_home() && !is_posts_page() && is_singular()) {
            global $post;

            $item = gdrts_get_rating_item_by_post($post);

            if ($item !== false) {
                $this->_prepare_rating($post, $item);
            }
        }
    }

    /** @param WP_Post $post
      * @param gdrts_rating_item $item */
    private function _prepare_rating($post, $item) {
        $post_type = $post->post_type;

        $location = $item->get('posts-integration_location', 'default');
        $method = $item->get('posts-integration_method', 'default');
        $priority = $item->get('posts-integration_priority', '');

        if ($location == 'default') {
            $location = $this->get($post_type.'_auto_embed_location');
        }

        if ($method == 'default') {
            $method = $this->get($post_type.'_auto_embed_method');
        }

        if ($priority == '') {
            $priority = $this->get($post_type.'_auto_embed_priority');
        }

        if (!is_numeric($priority) || empty($priority)) {
            $priority = 10;
        }

        $_location = apply_filters('gdrts_posts_auto_embed_location', $location, $post_type);
        $_method = apply_filters('gdrts_posts_auto_embed_method', $method, $post_type);
        $_priority = apply_filters('gdrts_posts_auto_embed_priority', $priority, $post_type);

        $_parts = explode('::', $_method, 2);
        $this->_current_rating_method = $_parts[0];
        $this->_current_rating_series = null;

        if (isset($_parts[1])) {
            $this->_current_rating_series = $_parts[1];
        }

        $this->_current_rating_location = $_location;
        $this->_current_rating_priority = $_priority;

        if (gdrts_is_method_loaded($this->_current_rating_method)) {
            if (!empty($_location) && is_string($_location) && in_array($_location, array('top', 'bottom', 'both'))) {
                add_filter('the_content', array($this, 'content_rating'), $this->_current_rating_priority);
            }
        }
    }

    public function content_rating($content) {
        global $post;

        if (!is_main_query() || $this->_is_excerpt) {
            return $content;
        }

        remove_filter('the_content', array($this, 'content_rating'), $this->_current_rating_priority);

        $rating = gdrts_posts_render_rating(array(
            'name' => $post->post_type, 
            'id' => $post->ID, 
            'method' => $this->_current_rating_method,
            'series' => $this->_current_rating_series
        ));

        if ($this->_current_rating_location == 'top' || $this->_current_rating_location == 'both') {
            $content = $rating.$content;
        }

        if ($this->_current_rating_location == 'bottom' || $this->_current_rating_location == 'both') {
            $content.= $rating;
        }

        return $content;
    }
}

global $_gdrts_addon_posts;
$_gdrts_addon_posts = new gdrts_addon_posts();

function gdrtsa_posts() {
    global $_gdrts_addon_posts;
    return $_gdrts_addon_posts;
}
