<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_addon_admin_posts extends gdrts_addon_admin {
	protected $prefix = 'posts';
    protected $has_help = true;

    public $post_types;

    public function __construct() {
        parent::__construct();

        $this->post_types = gdrts_posts_valid_post_types();

        add_filter('gdrts_admin_metabox_tabs', array($this, 'metabox_tabs'), 10, 3);
        add_action('gdrts_admin_metabox_content_posts-integration', array($this, 'metabox_content_integration'), 10, 2);
        add_action('gdrts_admin_metabox_save_post', array($this, 'metabox_save'));
    }

    public function help() {
        $screen = get_current_screen();

        $screen->add_help_tab(
            array(
                'id' => 'gdrts-help-settings-posts',
                'title' => __("Posts Integration", "gd-rating-system"),
                'content' => $this->help_posts_integration()
            )
        );
    }

    public function help_posts_integration() {
        $render = '<p>'.__("There are some limitations for using auto integration of ratings into posts.", "gd-rating-system").'</p>';
        $render.= '<ul>';
        $render.= '<li>'.__("This addon adds rating block into single post, page or other post types content using 'the_content' filter. It works on singular pages only and only inside the main query loop.", "gd-rating-system").'</li>';
        $render.= '<li>'.__("If your theme uses some non standard approach to display posts, this addon will most likely fail to add rating block into content.", "gd-rating-system").'</li>';
        $render.= '<li>'.__("For post types added by third party plugins, this addon might not work if these plugins use some non standard method to display the content or use own query or templates system.", "gd-rating-system").'</li>';
        $render.= '<li>'.__("This addon will not work with bbPress topics and replies, and bbPress post types are not displayed in this addon settings.", "gd-rating-system").'</li>';
        $render.= '</ul>';
        $render.= '<p>'.__("There are different things you can do to add ratings if this addon is not working for your post types or theme.", "gd-rating-system").'</p>';
        $render.= '<ul>';
        $render.= '<li>'.__("You can use shortcodes in the posts, pages or custom post types posts to display rating block in any location inside the content.", "gd-rating-system").'</li>';
        $render.= '<li>'.__("You can use manual integration functions to add rating block directly into theme templates.", "gd-rating-system").'</li>';
        $render.= '<li>'.__("To add ratings into bbPress topics or replies, use bbPress addon.", "gd-rating-system").'</li>';
        $render.= '</ul>';

        return $render;
    }

    public function icon($icon) {
        return 'thumb-tack';
    }

    public function metabox_tabs($tabs, $post_id, $post_type) {
        if (in_array($post_type, $this->post_types)) {
            $tabs['posts-integration'] = '<span class="dashicons dashicons-admin-post" aria-labelledby="gdrts-addon-metatab-posts-integration" title="'.__("Rating Embed", "gd-rating-system").'"></span><span id="gdrts-addon-metatab-posts-integration" class="d4plib-metatab-label">'.__("Rating Embed", "gd-rating-system").'</span>';
        }

        return $tabs;
    }

    public function metabox_content_integration($post_id, $post_type) {
        global $post;

        $item = gdrts_rating_item::get_instance(null, 'posts', $post->post_type, $post->ID);

	    if ($item === false) {
		    _e("This item is invalid.", "gd-rating-system");
	    } else {
		    $_gdrts_id = $post->ID;

		    $_gdrts_display = $item->get('posts-integration_location', 'default');
		    $_gdrts_method = $item->get('posts-integration_method', 'default');
		    $_gdrts_priority = $item->get('posts-integration_priority', '');

		    include(GDRTS_PATH.'forms/meta/posts-integration.php');
	    }
    }

    public function metabox_save($post) {
        if (isset($_POST['gdrts']['posts-integration'])) {
            $rating = $_POST['gdrts']['posts-integration'];

            if (wp_verify_nonce($rating['nonce'], 'gdrts-posts-integration-'.$post->ID) !== false) {
                $item = gdrts_rating_item::get_instance(null, 'posts', $post->post_type, $post->ID);

                if ($item === false) {
                	return;
                }

                $display = d4p_sanitize_basic($rating['location']);
                $method = d4p_sanitize_basic($rating['method']);
                $priority = empty($rating['priority']) && $rating['priority'] != 0 ? '' : intval($rating['priority']);

                $item->prepare_save();

                if ($display == 'default') {
                    $item->un_set('posts-integration_location');
                } else {
                    $item->set('posts-integration_location', $display);
                }

                if ($method == 'default') {
                    $item->un_set('posts-integration_method');
                } else {
                    $item->set('posts-integration_method', $method);
                }

                if ($priority == '') {
                    $item->un_set('posts-integration_priority');
                } else {
                    $item->set('posts-integration_priority', $priority);
                }

                $item->save(false);
            }
        }
    }

    public function panels($panels) {
        $panels['addon_posts'] = array(
            'title' => __("Ratings in Posts", "gd-rating-system"), 'icon' => 'thumb-tack', 'type' => 'addon',
            'info' => __("Settings on this panel are for control over integration of rating method blocks inside the post content on frontend for every post type.", "gd-rating-system"));
        $panels['addon_posts_sorting'] = array(
            'title' => __("Archive Sorting", "gd-rating-system"), 'icon' => 'thumb-tack', 'type' => 'addon',
            'info' => __("Settings on this panel are for control over the sorting of posts that have archives support.", "gd-rating-system"));

        return $panels;
    }

    public function settings($settings, $method = '') {
        $_rating_methods = gdrts_admin_shared::data_list_embed_methods('-');
        $_all_methods = gdrts_admin_shared::data_list_all_methods('-');

        foreach ($this->post_types as $name) {
            $label = gdrts()->get_entity_type_label('posts', $name);

            $key_rating = $name.'_auto_embed_';

            if (!empty($_rating_methods)) {
                $settings['addon_posts']['ap_'.$name] = array(
                    'name' => sprintf(__("Post Type: %s", "gd-rating-system"), $label),
                    'settings' => array(
                        new d4pSettingElement('addons', $this->key($key_rating.'location'), __("Location", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get($key_rating.'location'), 'array', $this->get_list_embed_locations()),
                        new d4pSettingElement('addons', $this->key($key_rating.'method'), __("Method", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get($key_rating.'method'), 'array', $_rating_methods),
                        new d4pSettingElement('addons', $this->key($key_rating.'priority'), __("Priority", "gd-rating-system"), __("Use lower values to run the filter earlier, or higher to run it later. Value 10 is default priority.", "gd-rating-system"), d4pSettingType::INTEGER, $this->get($key_rating.'priority'))
                    )
                );
            }

            if (d4p_post_type_has_archive($name)) {
                $settings['addon_posts_sorting']['aps_'.$name] = array('name' => sprintf(__("Post Type: %s", "gd-rating-system"), $label), 'settings' => array(
                    new d4pSettingElement('addons', $this->key($name.'_archive_sort_by_rating'), __("Sort archive by rating", "gd-rating-system"), '', d4pSettingType::BOOLEAN, $this->get($name.'_archive_sort_by_rating')),
                    new d4pSettingElement('addons', $this->key($name.'_archive_rating_method'), __("Method", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get($name.'_archive_rating_method'), 'array', $_all_methods),
                    new d4pSettingElement('addons', $this->key($name.'_archive_rating_value'), __("Value", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get($name.'_archive_rating_value'), 'array', $this->get_list_sort_value())
                ));
            }
        }

        return $settings;
    }

    public function get_list_sort_value() {
        return array(
            'rating' => __("Rating", "gd-rating-system"),
            'votes' => __("Votes", "gd-rating-system")
        );
    }

    public function get_list_embed_locations() {
        return array(
            'top' => __("Top", "gd-rating-system"),
            'bottom' => __("Bottom", "gd-rating-system"),
            'both' => __("Top and Bottom", "gd-rating-system"),
            'hide' => __("Hide", "gd-rating-system")
        );
    }
}

global $_gdrts_addon_admin_posts;
$_gdrts_addon_admin_posts = new gdrts_addon_admin_posts();

/** @return gdrts_addon_admin_posts */
function gdrtsa_admin_posts() {
    global $_gdrts_addon_admin_posts;
    return $_gdrts_addon_admin_posts;
}
