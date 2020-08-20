<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_addon_admin_rich_snippets extends gdrts_addon_admin {
    protected $prefix = 'rich-snippets';
    protected $has_help = true;

    public function __construct() {
        parent::__construct();

        add_action('add_meta_boxes', array($this, 'admin_meta'));

        add_action('save_post', array($this, 'metabox_post_save'), 10, 3);

        add_filter('gdrts_rich_snippet_admin_metabox_tabs', array($this, 'metabox_tabs'));
        add_action('gdrts_rich_snippet_admin_metabox_content_basic', array($this, 'metabox_content_basic'));
        add_action('gdrts_rich_snippet_admin_metabox_content_modes', array($this, 'metabox_content_modes'));
        add_action('gdrts_rich_snippet_admin_metabox_content_preview', array($this, 'metabox_content_preview'));
        add_action('gdrts_rich_snippet_admin_metabox_content_test', array($this, 'metabox_content_test'));
        add_action('gdrts_rich_snippet_admin_metabox_save_post', array($this, 'metabox_save_basic'));
        add_action('gdrts_rich_snippet_admin_metabox_save_post', array($this, 'metabox_save_modes'));

        add_action('gdrts_admin_enqueue_scripts_posts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts($hook) {
        wp_enqueue_style('flatpickr', gdrts_plugin()->lib_file('flatpickr', 'css', 'flatpickr.min', false), array(), gdrts_settings()->file_version());
        wp_enqueue_script('flatpickr', gdrts_plugin()->lib_file('flatpickr', 'js', 'flatpickr.min', false), array('jquery'), gdrts_settings()->file_version(), true);

        $flatpickr_locale = gdrts_plugin()->locale_js_code('flatpickr');

        if ($flatpickr_locale !== false) {
            wp_enqueue_script('flatpickr-'.$flatpickr_locale, GDRTS_URL.'libs/flatpickr/l10n/'.$flatpickr_locale.'.min.js', array('flatpickr'), gdrts_settings()->file_version(), true);
        }

        wp_enqueue_style('gdrts-richsnippets', gdrts_admin()->file('css', 'richsnippets', false, true, GDRTS_URL.'addons/rich-snippets/'), array(), gdrts_settings()->file_version());
        wp_enqueue_script('gdrts-richsnippets', gdrts_admin()->file('js', 'richsnippets', false, true, GDRTS_URL.'addons/rich-snippets/'), array('gdrts-metabox', 'flatpickr'), gdrts_settings()->file_version(), true);
    }

    public function admin_meta() {
        if (current_user_can('edit_posts')) {
            $post_types = get_post_types(array('public' => true), 'objects');
            $allowed_types = $this->get('metaboxes_post_types');

            foreach (array_keys($post_types) as $post_type) {
                if ($post_type != 'attachment' && (is_null($allowed_types) || (is_array($allowed_types) && in_array($post_type, $allowed_types)))) {
                    add_meta_box('gdrts-rich-snippets-metabox', __("GD Rating System: Rich Snippets", "gd-rating-system"), array($this, 'metabox_post'), $post_type, 'normal', 'high');
                }
            }
        }
    }

    public function metabox_post() {
        global $post_ID;

        if (current_user_can('edit_post', $post_ID)) {
            require_once(GDRTS_PATH.'addons/rich-snippets/schema.php');
            require_once(GDRTS_PATH.'addons/rich-snippets/forms.php');

            include(GDRTS_PATH.'addons/rich-snippets/forms/meta.php');
        } else {
            _e("You don't have rights to control these settings", "gd-rating-system");
        }
    }

    public function metabox_post_save($post_id, $post, $update) {
        if (isset($_POST['gdrts'])) {
            require_once(GDRTS_PATH.'addons/rich-snippets/schema.php');

            do_action('gdrts_rich_snippet_admin_metabox_save_post', $post, $update);
        }
    }

    public function help() {
        $screen = get_current_screen();

        $screen->add_help_tab(
            array(
                'id' => 'gdrts-help-settings-rich-snippets',
                'title' => __("Rich Snippets", "gd-rating-system"),
                'content' => $this->help_richsnippets()
            )
        );

        $screen->add_help_tab(
            array(
                'id' => 'gdrts-help-settings-rich-snippets-links',
                'title' => __("Rich Snippets Links", "gd-rating-system"),
                'content' => $this->help_richsnippets_links()
            )
        );
    }

    public function help_richsnippets() {
        $render = '<p>'.__("These are some of the rules related to use of rich snippets by Google.", "gd-rating-system").'</p>';
        $render .= '<ul>';
        $render .= '<li>'.__("There is no guarantee that Google will use rich snippets in the search results!", "gd-rating-system").'</li>';
        $render .= '<li>'.__("When you make changes to the snippet type parameters, make sure you test your page in Rich Snippets testing tool. Depending on the item type, additional parameters must be provided, either by using advnced snippet types or by providing additional data with the custom code.", "gd-rating-system").'</li>';
        $render .= '</ul>';
        $render .= '<p>'.__("Make sure you read additional information about using the Rich Snippets.", "gd-rating-system").'</p>';
        $render .= '<ul>';
        $render .= '<li>'.__("Many item scope schema types don't support use of rating elements. Make sure you check the Schema.org hierarchy to get all valid types.", "gd-rating-system").'</li>';
        $render .= '<li>'.__("You can override settings from this page on individual posts or pages edit pages.", "gd-rating-system").'</li>';
        $render .= '</ul>';

        return $render;
    }

    public function help_richsnippets_links() {
        $render = '<p>'.__("Here are few important links for working with rich snippets.", "gd-rating-system").'</p>';
        $render .= '<ul>';
        $render .= '<li><a target="_blank" href="https://developers.google.com/structured-data/testing-tool/">'.__("Google Rich Snippets Testing Tool", "gd-rating-system").'</a></li>';
        $render .= '<li><a target="_blank" href="https://schema.org/docs/full.html">'.__("Schema.org full objects Hierarchy", "gd-rating-system").'</a></li>';
        $render .= '</ul>';

        return $render;
    }

    public function metabox_tabs($tabs) {
        $tabs['basic'] = '<span class="dashicons dashicons-flag" aria-labelledby="gdrts-addon-metatab-rich-snippets-basic" title="'.__("Setup", "gd-rating-system").'"></span><span id="gdrts-addon-metatab-rich-snippets-basic" class="d4plib-metatab-label">'.__("Setup", "gd-rating-system").'</span>';
        $tabs['modes'] = '<span class="dashicons dashicons-layout" aria-labelledby="gdrts-addon-metatab-rich-snippets-modes" title="'.__("Snippets", "gd-rating-system").'"></span><span id="gdrts-addon-metatab-rich-snippets-modes" class="d4plib-metatab-label">'.__("Snippets", "gd-rating-system").'</span>';

        $tabs['preview'] = '<span class="dashicons dashicons-editor-code" aria-labelledby="gdrts-addon-metatab-rich-snippets-preview" title="'.__("Test", "gd-rating-system").'"></span><span id="gdrts-addon-metatab-rich-snippets-preview" class="d4plib-metatab-label">'.__("Preview", "gd-rating-system").'</span>';
        $tabs['test'] = '<span class="dashicons dashicons-search" aria-labelledby="gdrts-addon-metatab-rich-snippets-test" title="'.__("Test", "gd-rating-system").'"></span><span id="gdrts-addon-metatab-rich-snippets-test" class="d4plib-metatab-label">'.__("Test", "gd-rating-system").'</span>';

        return $tabs;
    }

    public function metabox_content_basic() {
        global $post;

        $item = gdrts_rating_item::get_instance(null, 'posts', $post->post_type, $post->ID);

        if ($item === false) {
            _e("This item is invalid.", "gd-rating-system");
        } else {
            $_gdrts_id = $post->ID;
            $_gdrts_display = $item->get('rich-snippets_display', 'default');
            $_gdrts_rating_method = $item->get('rich-snippets_rating_method', 'default');

            include(GDRTS_PATH.'addons/rich-snippets/forms/basic.php');
        }
    }

    public function metabox_content_modes() {
        global $post;

        $_gdrts_item = gdrts_rating_item::get_instance(null, 'posts', $post->post_type, $post->ID);

        if ($_gdrts_item === false) {
            _e("This item is invalid.", "gd-rating-system");
        } else {
            $_gdrts_id = $post->ID;
            $_gdrts_mode = $_gdrts_item->get('rich-snippets_mode', $this->get($post->post_type.'_snippet_mode'));

            $_gdrts_base = array(
                'rating' => $_gdrts_item->get('rich-snippets_rating_method', $this->get($post->post_type.'_snippet_rating_method')),
                'review' => 'default'
            );

            $this->load_snippets_admin();

            do_action('gdrts_rich_snippet_admin_meta_content_init', $_gdrts_item, $_gdrts_base);

            include(GDRTS_PATH.'addons/rich-snippets/forms/modes.php');
        }
    }

    public function metabox_content_preview() {
        global $post;

        include(GDRTS_PATH.'addons/rich-snippets/forms/preview.php');
    }

    public function metabox_content_test() {
        global $post;

        include(GDRTS_PATH.'addons/rich-snippets/forms/test.php');
    }

    public function metabox_save_basic($post) {
        if (isset($_POST['gdrts']['rich-snippets'])) {
            $data = $_POST['gdrts']['rich-snippets'];

            if (wp_verify_nonce($data['nonce'], 'gdrts-rich-snippets-'.$post->ID) !== false) {
                $item = gdrts_rating_item::get_instance(null, 'posts', $post->post_type, $post->ID);

                if ($item === false) {
                    return;
                }

                $display = d4p_sanitize_basic($data['display']);
                $rating_method = d4p_sanitize_basic($data['rating_method']);

                $item->prepare_save();

                if ($display == 'default') {
                    $item->un_set('rich-snippets_display');
                } else {
                    $item->set('rich-snippets_display', $display);
                }

                if ($rating_method == 'default') {
                    $item->un_set('rich-snippets_rating_method');
                } else {
                    $item->set('rich-snippets_rating_method', $rating_method);
                }

                $item->un_set('rich-snippets_review_method');

                $item->save(false);
            }
        }
    }

    public function metabox_save_modes($post) {
        if (isset($_POST['gdrts']['rich-snippets-mode'])) {
            $data = $_POST['gdrts']['rich-snippets-mode'];

            if (wp_verify_nonce($data['nonce'], 'gdrts-rich-snippets-mode-'.$post->ID) !== false) {
                $item = gdrts_rating_item::get_instance(null, 'posts', $post->post_type, $post->ID);

                if ($item === false) {
                    return;
                }

                $mode = d4p_sanitize_basic($data['mode']);

                $item->prepare_save();

                if ($mode == 'default') {
                    $item->un_set('rich-snippets_mode');
                } else {
                    $item->set('rich-snippets_mode', $mode);
                }

                $this->load_snippets_admin();

                $item = apply_filters('gdrts_rich_snippet_admin_meta_content_save', $item, $data, $mode);

                $item->save(false);
            }
        }
    }

    public function panels($panels) {
        $panels['addon_rich_snippets'] = array(
            'title' => __("Rich Snippets", "gd-rating-system"), 'icon' => 'flag', 'type' => 'addon',
            'info' => __("Settings on this panel are for control over search engine rich snippets integration.", "gd-rating-system"));

        return $panels;
    }

    public function settings($settings, $method = '') {
        $settings['addon_rich_snippets'] = array(
            'ars_organization' => array('name' => __("Organization", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement('addons', $this->key('snippet_organization_name'), __("Name", "gd-rating-system"), __("If empty, website name will be used.", "gd-rating-system"), d4pSettingType::TEXT, $this->get('snippet_organization_name')),
                new d4pSettingElement('addons', $this->key('snippet_organization_logo'), __("Logo", "gd-rating-system"), __("Most snippet types require the image for the organization. Without it, you can expect errors related to the snippet testing.", "gd-rating-system"), d4pSettingType::IMAGE, $this->get('snippet_organization_logo'))
            )),
            'ars_metaboxes' => array('name' => __("Rich Snippet Metabox", "gd-rating-system"), 'settings' => array(
                new d4pSettingElement('addons', $this->key('metaboxes_post_types'), __("Show for post types", "gd-rating-system"), __("All posts belonging to selected post types will show the rich snippets metabox that can be used to override various snippets settings and related data. Without metabox, plugin will use global snippets settings, and for some snippet types you need to make adjustments for individual posts and that requires metabox.", "gd-rating-system"), d4pSettingType::CHECKBOXES, $this->get('metaboxes_post_types'), 'array', $this->get_list_valid_post_types())
            ))
        );

        foreach (gdrts()->get_entity_types('posts') as $name => $label) {
            $key = $name.'_snippet_';

            $options = array(
                new d4pSettingElement('addons', $this->key($key.'display'), __("Display", "gd-rating-system"), __("It is highly recommended to use JSON-LD format, Microdata format will be removed in future versions of the plugin.", "gd-rating-system"), d4pSettingType::SELECT, $this->get($key.'display'), 'array', $this->get_list_embed_locations()),
                new d4pSettingElement('', '', __("Rich Snippet", "gd-rating-system"), '', d4pSettingType::HR),
                new d4pSettingElement('addons', $this->key($key.'mode'), __("Snippet Type", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get($key.'mode'), 'array', $this->get_list_snippet_modes()),
                new d4pSettingElement('addons', $this->key($key.'rating'), __("Include Rating Data", "gd-rating-system"), __("Some snippet types don't allow for the ratings to be included. Google allows for very limited number of snippet types to have ratings included.", "gd-rating-system"), d4pSettingType::SELECT, $this->get($key.'rating'), 'array', $this->get_list_include_ratings()),
                new d4pSettingElement('addons', $this->key($key.'rating_method'), __("Rating Method", "gd-rating-system"), '', d4pSettingType::SELECT, $this->get($key.'rating_method'), 'array', $this->get_list_rating_methods()),
                new d4pSettingElement('', '', __("Custom Type", "gd-rating-system"), '', d4pSettingType::HR),
                new d4pSettingElement('addons', $this->key($key.'custom_type_name'), __("Name", "gd-rating-system"), __("This has to be valid rich snippet type name. If this value is invalid, snippet validation will fail. Also, Google allows ratings to be included with some types of the rich snippets, and if you include rating with unsupported snippet type, Google will not allow it.", "gd-rating-system"), d4pSettingType::TEXT, $this->get($key.'custom_type_name')),
                new d4pSettingElement('addons', $this->key($key.'custom_type_features'), __("Include", "gd-rating-system"), __("List of autogenerated data for the rich snippet.", "gd-rating-system"), d4pSettingType::CHECKBOXES, $this->get($key.'custom_type_features'), 'array', $this->get_list_custom_features())
            );

            $settings['addon_rich_snippets']['ars_cpt_'.$name] = array(
                'name' => sprintf(__("Rich Snippets for: '%s'", "gd-rating-system"), $label), 'settings' => $options
            );
        }

        $settings['addon_rich_snippets']['ars_advanced'] = array('name' => __("Advanced Settings", "gd-rating-system"), 'settings' => array(
            new d4pSettingElement('addons', $this->key('snippet_use_gmt_dates'), __("Use GMT date/time values", "gd-rating-system"), __("Some calendars store dates that when processed through Gregorian calendar are set into the past, and Google refuses to validate the snippet. In such case, it is best to use GMT version of date/time stored by WordPress.", "gd-rating-system").' '.__("This option should stay disabled.", "gd-rating-system"), d4pSettingType::BOOLEAN, $this->get('snippet_use_gmt_dates')),
            new d4pSettingElement('addons', $this->key('snippet_single_per_page'), __("Single snippet per page", "gd-rating-system"), __("If this is enabled, the plugin will attempt to only add one rich snippet to the page, and it should be for the main page content, but depending on the page layout that might not work properly.", "gd-rating-system").' '.__("This option should stay disabled.", "gd-rating-system"), d4pSettingType::BOOLEAN, $this->get('snippet_single_per_page')),
            new d4pSettingElement('addons', $this->key('snippet_on_singular_pages'), __("Snippet on singular pages only", "gd-rating-system"), __("If this is enabled, plugin will run rich snippets generating on singular posts or pages only.", "gd-rating-system").' '.__("This option should stay disabled.", "gd-rating-system"), d4pSettingType::BOOLEAN, $this->get('snippet_on_singular_pages'))
        ));

        return $settings;
    }

    private function get_list_valid_post_types() {
        $list = array();
        $post_types = get_post_types(array('public' => true), 'objects');

        foreach ($post_types as $post_type => $object) {
            if ($post_type != 'attachment') {
                $list[$post_type] = $object->label;
            }
        }

        return $list;
    }

    public function get_list_embed_locations() {
        return array(
            'jsonld' => __("Use JSON-LD", "gd-rating-system"),
            'microdata' => __("Use Microdata", "gd-rating-system"),
            'hide' => __("Hide", "gd-rating-system")
        );
    }

    public function get_list_embed_methods() {
        $list = array();

        foreach (gdrts()->methods as $key => $data) {
            if (gdrts_is_method_loaded($key) && $key != 'like-this' && $key != 'emote-this') {
                if (gdrts_method_has_series($key)) {
                    $obj = gdrts_get_method_object($key);

                    foreach ($obj->all_series_list() as $sers => $label) {
                        $list[$key.'::'.$sers] = $data['label'].' &minus; '.$label;
                    }
                } else {
                    $list[$key] = $data['label'];
                }
            }
        }

        return $list;
    }

    public function get_list_rating_methods() {
        $list = array();

        foreach (gdrts()->methods as $key => $data) {
            if (gdrts_is_method_loaded($key) && $data['review'] === false && $key != 'like-this') {
                if (gdrts_method_has_series($key)) {
                    $obj = gdrts_get_method_object($key);

                    foreach ($obj->all_series_list() as $sers => $label) {
                        $list[$key.'::'.$sers] = $data['label'].' &minus; '.$label;
                    }
                } else {
                    $list[$key] = $data['label'];
                }
            }
        }

        return $list;
    }

    public function get_list_snippet_modes() {
        return wp_list_pluck(gdrtsa_rich_snippets()->modes, 'label');
    }

    public function get_list_include_ratings() {
        return array(
            'rating' => __("Aggregated Rating", "gd-rating-system"),
            'none' => __("Do not include Rating or Review", "gd-rating-system")
        );
    }

    public function get_list_custom_features() {
        return array(
            'author' => __("Author", "gd-rating-system"),
            'publisher' => __("Publisher", "gd-rating-system"),
            'image' => __("Featured Image", "gd-rating-system"),
            'published' => __("Publication Date", "gd-rating-system"),
            'modified' => __("Modification Date", "gd-rating-system")
        );
    }

    public function load_snippets_admin() {
        foreach (gdrtsa_rich_snippets()->modes as $mode) {
            require_once($mode['path'].'admin.php');
        }
    }
}

global $_gdrts_addon_admin_rich_snippets;
$_gdrts_addon_admin_rich_snippets = new gdrts_addon_admin_rich_snippets();

function gdrtsa_admin_rich_snippets() {
    global $_gdrts_addon_admin_rich_snippets;
    return $_gdrts_addon_admin_rich_snippets;
}
