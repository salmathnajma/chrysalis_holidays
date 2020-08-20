<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_addon_rich_snippets extends gdrts_addon {
    public $prefix = 'rich-snippets';
    public $inserted = false;

    public $modes = array();

    public function load_admin() {
        require_once(GDRTS_PATH.'addons/rich-snippets/admin.php');
    }

    public function core() {
        $this->modes();

        add_action('gdrts-template-rating-rich-snippet', array($this, 'snippet'));
        add_filter('gdrts_rating_item_instance_init', array($this, 'rating_item_instance'));
    }

    public function rating_item_instance($data) {
        $snippets = array();

        foreach ($data['meta'] as $key => $obj) {
            if (substr($key, 0, strlen($this->prefix) + 1) == $this->prefix.'_') {
                $rk = substr($key, strlen($this->prefix) + 1);

                if (substr($rk, 0, 5) == 'mode_') {
                    $mk = substr($rk, 5);

                    foreach (array_keys(gdrtsa_rich_snippets()->modes) as $mode) {
                        if (substr($mk, 0, strlen($mode) + 1) == $mode.'_') {
                            $name = substr($mk, strlen($mode) + 1);

                            $snippets[$mode][$name] = $obj;
                        }
                    }
                } else {
                    $snippets[$rk] = $obj;
                }
            }
        }

        $data['snippets'] = $snippets;

        return $data;
    }

    public function modes() {
        $path = GDRTS_PATH.'addons/rich-snippets/snippets/';

        $this->modes = array(
            'custom' => array(
                'type' => 'Custom',
                'label' => __("Custom Type", "gd-rating-system"),
                'path' => $path.'custom/',
                'google' => 'https://www.schema.org/'),
            'creative_work' => array(
                'type' => 'CreativeWork',
                'label' => __("Creative Work", "gd-rating-system"),
                'path' => $path.'creative_work/'),
            'media_object' => array(
                'type' => 'MediaObject',
                'label' => __("Media Object", "gd-rating-system"),
                'extends' => array('creative_work'),
                'path' => $path.'media_object/'),
            'article' => array(
                'type' => 'Article',
                'label' => __("Article", "gd-rating-system"),
                'extends' => array('creative_work'),
                'path' => $path.'article/',
                'google' => 'https://developers.google.com/search/docs/data-types/article'),
            'news_article' => array(
                'type' => 'NewsArticle',
                'label' => __("News Article", "gd-rating-system"),
                'extends' => array('creative_work', 'article'),
                'path' => $path.'news_article/',
                'google' => 'https://developers.google.com/search/docs/data-types/article'),
            'blog_posting' => array(
                'type' => 'BlogPosting',
                'label' => __("Blog Posting", "gd-rating-system"),
                'extends' => array('creative_work', 'article'),
                'path' => $path.'blog_posting/',
                'google' => 'https://developers.google.com/search/docs/data-types/article'),
            'web_page' => array(
                'type' => 'WebPage',
                'label' => __("Web Page", "gd-rating-system"),
                'extends' => array('creative_work', 'article'),
                'path' => $path.'web_page/'),
            'product' => array(
                'type' => 'Product',
                'label' => __("Product", "gd-rating-system"),
                'path' => $path.'product/',
                'google' => 'https://developers.google.com/search/docs/data-types/product'),
            'software_application' => array(
                'type' => 'SoftwareApplication',
                'label' => __("Software Application", "gd-rating-system"),
                'path' => $path.'software_application/',
                'microdata' => false,
                'google' => 'https://developers.google.com/search/docs/data-types/software-app')
        );

        do_action('gdrts_rich_snippets_register_modes');
    }

    public function register_mode($snippet, $args = array()) {
        $defaults = array(
            'label' => '',
            'path' => ''
        );

        $this->modes[$snippet] = wp_parse_args($args, $defaults);
    }

    public function get_registered_modes() {
        return array_keys($this->modes);
    }

    public function get_data($post) {
        $item = gdrts_rating_item::get_instance(null, 'posts', $post->post_type, $post->ID);

        if ($item === false) {
            return array('display' => 'hide');
        }

        $data = array(
            'display' => $item->get('rich-snippets_display', $this->get($post->post_type.'_snippet_display')),
            'methods' => array(
                'rating' => $item->get('rich-snippets_rating_method', $this->get($post->post_type.'_snippet_rating_method')),
                'review' => 'default'
            ),
            'mode' => $item->get('rich-snippets_mode', $this->get($post->post_type.'_snippet_mode'))
        );

        return apply_filters('gdrts_rich_snippets_snippet_data', $data, $item, $post);
    }

    public function gmt() {
        return $this->get('snippet_use_gmt_dates');
    }

    public function is_allowed($the_post) {
        $allowed = true;

        if ($this->get('snippet_on_singular_pages')) {
            $allowed = is_main_query() && is_singular();
        }

        if ($allowed && $this->get('snippet_single_per_page')) {
            $allowed = !$this->inserted;
        }

        return apply_filters('gdrts_rich_snippets_snippet_is_allowed', $allowed, $the_post);
    }

    public function snippet_display_method($the_post) {
        if (is_null($the_post) || !$the_post) {
            $the_post = get_post();
        }

        if ($the_post instanceof WP_Post) {
            $data = $this->get_data($the_post);

            return $data['display'];
        }

        return 'hide';
    }

    public function snippet_to_string($the_post) {
        ob_start();

        $this->snippet($the_post, true);

        $render = ob_get_contents();
        ob_end_clean();

        return trim($render);
    }

    public function snippet($the_post = null, $force = false) {
        if (is_null($the_post) || !$the_post) {
            if (gdrts_single()->is_loop()) {
                if (gdrts_single()->item()->data->object instanceof WP_Post) {
                    $the_post = gdrts_single()->item()->data->object;
                }
            } else if (gdrts_list()->is_loop()) {
                if (gdrts_list()->item()->data->object instanceof WP_Post) {
                    $the_post = gdrts_list()->item()->data->object;
                }
            }
        }

        if ($the_post instanceof WP_Post && ($this->is_allowed($the_post) || $force)) {
            $data = $this->get_data($the_post);
            $mode = isset($data['mode']) && isset($this->modes[$data['mode']]) ? $this->modes[$data['mode']] : null;

            if ($data['display'] != 'hide' && $data['display'] != 'default' && !is_null($mode)) {
                $item = gdrts_rating_item::get_instance(null, 'posts', $the_post->post_type, $the_post->ID);

                if (isset($mode['extends']) && !empty($mode['extends'])) {
                    foreach ($mode['extends'] as $ex) {
                        $_m = isset($this->modes[$ex]) ? $this->modes[$ex] : null;

                        if (!is_null($_m)) {
                            require_once($_m['path'].'/snippet.php');
                            require_once($_m['path'].'/'.$data['display'].'.php');
                        }
                    }
                }

                require_once($mode['path'].'/snippet.php');
                require_once($mode['path'].'/'.$data['display'].'.php');

                do_action('gdrts_rich_snippets_run_snippet_mode_'.$data['mode'], $item, $data);
            }
        }
    }
}

global $_gdrts_addon_rich_snippets;
$_gdrts_addon_rich_snippets = new gdrts_addon_rich_snippets();

function gdrtsa_rich_snippets() {
    global $_gdrts_addon_rich_snippets;
    return $_gdrts_addon_rich_snippets;
}
