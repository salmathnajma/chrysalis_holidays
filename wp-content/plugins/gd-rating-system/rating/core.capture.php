<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core_capture {
    public $single = false;
    public $archive = false;

    public $post_type = '';

    public $taxonomies = array();
    public $terms = array();

    public $terms_children = false;
    public $terms_hierarchy = false;

    /** @var null|WP_Post|WP_Term|WP_Post_Type */
    public $object = null;

    public function __construct() {
        $this->object = $this->wp_query()->get_queried_object();

        if (is_singular()) {
            $this->single = true;

            if ($this->object instanceof WP_Post) {
                $this->post_type = $this->wp_query()->post->post_type;
                $this->taxonomies = gdrts()->get_object_taxonomies($this->object);

                if (!empty($this->taxonomies)) {
                    $_terms = wp_get_object_terms($this->object->ID, $this->taxonomies);

                    foreach ($_terms as $term) {
                        if (!isset($this->terms[$term->taxonomy])) {
                            $this->terms[$term->taxonomy] = array();
                        }

                        $this->terms[$term->taxonomy][] = $term->term_id;
                    }
                }
            }
        } else if (is_post_type_archive()) {
            $this->archive = true;

            if ($this->object instanceof WP_Post_Type) {
                $this->post_type = $this->object->name;
            }
        } else if (is_any_tax()) {
            $this->archive = true;

            if ($this->object instanceof WP_Term) {
                $this->post_type = $this->wp_query()->post->post_type;

                $this->taxonomies[] = $this->object->taxonomy;
                $this->terms[$this->object->taxonomy] = array($this->object->term_id);
            }
        }

        do_action_ref_array('gdrts_capture_page', array(&$this));
    }

    /** @return WP_Query */
    public function wp_query() {
        global $wp_query;
        return $wp_query;
    }

    public function terms_ids() {
        return call_user_func_array('array_merge', $this->terms);
    }

    public function terms_children_ids() {
        if ($this->terms_children === false) {
            $this->terms_children = array();
        }

        foreach ($this->terms as $tax => $terms) {
            foreach ($terms as $term) {
                $this->terms_children[] = $term;

                $parents = get_term_children($term, $tax);

                foreach ($parents as $id) {
                    $this->terms_children[] = $id;
                }
            }
        }

        return $this->terms_children;
    }

    public function terms_hierarchy_ids() {
        if ($this->terms_hierarchy === false) {
            $this->terms_hierarchy = array();

            foreach ($this->terms as $tax => $terms) {
                foreach ($terms as $term) {
                    $this->terms_hierarchy[] = $term;

                    $parents = get_ancestors($term, $tax, 'taxonomy');

                    foreach ($parents as $id) {
                        $this->terms_hierarchy[] = $id;
                    }
                }
            }
        }

        return $this->terms_hierarchy;
    }
}
