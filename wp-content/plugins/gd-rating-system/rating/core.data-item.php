<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_item_post extends gdrts_item_data {
    public $terms = array();
    public $taxonomies = array();

    public function __construct($entity, $name, $id) {
        parent::__construct($entity, $name, $id);

        $this->object = get_post($this->id);

        if (gdrts_rules()->filters_allowed()) {
            $this->taxonomies = gdrts()->get_object_taxonomies($this->object);

            if (!empty($this->taxonomies)) {
                $_terms = wp_get_object_terms($this->id, $this->taxonomies);

                foreach ($_terms as $term) {
                    if (!isset($this->terms[$term->taxonomy])) {
                        $this->terms[$term->taxonomy] = array();
                    }

                    $this->terms[$term->taxonomy][] = $term->term_id;

                    $parents = get_ancestors($term->term_id, $term->taxonomy, 'taxonomy');

                    foreach ($parents as $id) {
                        $this->terms[$term->taxonomy][] = $id;
                    }
                }
            }
        }
    }

    public function get_title() {
        $title = $this->id;

        if (isset($this->object->post_title) && !empty($this->object->post_title)) {
            $title = get_the_title($this->object);
        }

        return apply_filters('gdrts_item_post_get_title', $title, $this);
    }

    public function get_author_id() {
        $author_id = 0;

        if (isset($this->object->post_author) && !empty($this->object->post_author)) {
            $author_id = $this->object->post_author;
        }

        return apply_filters('gdrts_item_post_get_author_id', $author_id, $this);
    }

    public function get_url() {
        return apply_filters('gdrts_item_post_get_url', get_permalink($this->id), $this);
    }

    public function get_excerpt() {
        return apply_filters('gdrts_item_post_get_excerpt', get_the_excerpt($this->id), $this);
    }

    public function get_date_published($format = 'c', $gmt = false) {
        if ($format == '') {
            $format = get_option('date_format');
        }

        $_date = $gmt ? $this->object->post_date_gmt : $this->object->post_date;

        return apply_filters('gdrts_item_post_get_date_published', gdrts_get_formatted_date_from_mysql_date($format, $_date, $gmt), $this, $format, $gmt);
    }

    public function get_date_modified($format = 'c', $gmt = false) {
        if ($format == '') {
            $format = get_option('date_format');
        }

        $_date = $gmt ? $this->object->post_modified_gmt : $this->object->post_modified;

        return apply_filters('gdrts_item_post_get_date_published', gdrts_get_formatted_date_from_mysql_date($format, $_date, $gmt), $this, $format, $gmt);
    }

    public function has_thumbnail() {
        return apply_filters('gdrts_item_post_has_thumbnail', has_post_thumbnail($this->id), $this);
    }

    public function get_thumbnail($size = 'thumbnail', $attr = array()) {
        return apply_filters('gdrts_item_post_get_thumbnail', get_the_post_thumbnail($this->id, $size, $attr), $this, $size, $attr);
    }

    public function get_thumbnail_url($size = 'thumbnail') {
        return apply_filters('gdrts_item_post_get_thumbnail_url', d4p_get_thumbnail_url($this->id, $size), $this, $size);
    }
}

class gdrts_item_post_bbp_reply extends gdrts_item_post {
    public function get_title() {
        if (!empty($this->object->post_title)) {
            $title = get_the_title($this->object);
        } else {
            $title = bbp_get_reply_title($this->id);
        }

        return apply_filters('gdrts_item_post_get_title', $title, $this);
    }
}

class gdrts_item_comment extends gdrts_item_data {
    public function __construct($entity, $name, $id) {
        parent::__construct($entity, $name, $id);

        $this->object = get_comment($this->id);
    }

    public function get_title() {
        return apply_filters('gdrts_item_comment_get_title', sprintf(__("%s on %s", "gd-rating-system"),
            get_comment_author($this->object),
            get_the_title($this->object->comment_post_ID)
        ), $this);
    }

    public function get_author_id() {
        $author_id = 0;

        if (isset($this->object->user_id) && !empty($this->object->user_id)) {
            $author_id = $this->object->user_id;
        }

        return apply_filters('gdrts_item_comment_get_author_id', $author_id, $this);
    }

    public function get_url() {
        return apply_filters('gdrts_item_comment_get_url', get_comment_link($this->id), $this);
    }

    public function get_excerpt() {
        return apply_filters('gdrts_item_comment_get_excerpt', '', $this);
    }

    public function get_date_published($format = 'c', $gmt = false) {
        if ($format == '') {
            $format = get_option('date_format');
        }

        $_date = $gmt ? $this->object->comment_date_gmt : $this->object->comment_date;

        return apply_filters('gdrts_item_comment_get_date_published', gdrts_get_formatted_date_from_mysql_date($format, $_date, $gmt), $this, $format, $gmt);
    }

    public function get_date_modified($format = 'c', $gmt = false) {
        if ($format == '') {
            $format = get_option('date_format');
        }

        $_date = $gmt ? $this->object->comment_date_gmt : $this->object->comment_date;

        return apply_filters('gdrts_item_comment_get_date_modified', gdrts_get_formatted_date_from_mysql_date($format, $_date, $gmt), $this, $format, $gmt);
    }

    public function has_thumbnail() {
        return apply_filters('gdrts_item_comment_has_thumbnail', has_post_thumbnail($this->object->comment_post_ID), $this);
    }

    public function get_thumbnail($size = 'thumbnail', $attr = array()) {
        return apply_filters('gdrts_item_comment_get_thumbnail', get_the_post_thumbnail($this->object->comment_post_ID, $size, $attr), $this, $size, $attr);
    }

    public function get_thumbnail_url($size = 'thumbnail') {
        return apply_filters('gdrts_item_comment_get_thumbnail_url', d4p_get_thumbnail_url($this->object->comment_post_ID, $size), $this, $size);
    }
}

class gdrts_item_user extends gdrts_item_data {
    public function __construct($entity, $name, $id) {
        parent::__construct($entity, $name, $id);

        $this->object = get_user_by('id', $this->id);
    }

    public function get_title() {
        return apply_filters('gdrts_item_user_get_title', $this->object->display_name, $this);
    }

    public function get_author_id() {
        return apply_filters('gdrts_item_user_get_author_id', $this->id, $this);
    }

    public function get_url() {
        return apply_filters('gdrts_item_user_get_url', '', $this);
    }

    public function get_excerpt() {
        return apply_filters('gdrts_item_user_get_excerpt', '', $this);
    }

    public function get_date_published($format = 'c', $gmt = false) {
        if ($format == '') {
            $format = get_option('date_format');
        }

        return apply_filters('gdrts_item_user_get_date_published', mysql2date($format, $this->object->user_registered), $this, $format, $gmt);
    }

    public function get_date_modified($format = 'c', $gmt = false) {
        if ($format == '') {
            $format = get_option('date_format');
        }

        return apply_filters('gdrts_item_user_get_date_modified', mysql2date($format, $this->object->user_registered), $this, $format, $gmt);
    }

    public function has_thumbnail() {
        return apply_filters('gdrts_item_user_has_thumbnail', true, $this);
    }

    public function get_thumbnail($size = 'thumbnail', $attr = array()) {
        $size = intval($size);

        if ($size == 0) {
            $size = 96;
        }

        return apply_filters('gdrts_item_user_get_thumbnail', get_avatar($this->id, $size, '', $this->get_title(), $attr), $this, $size, $attr);
    }

    public function get_thumbnail_url($size = 'thumbnail') {
        $size = intval($size);

        if ($size == 0) {
            $size = 96;
        }

        return apply_filters('gdrts_item_user_get_thumbnail_url', get_avatar_url($this->id, array('size' => $size)), $this, $size);
    }
}

class gdrts_item_term extends gdrts_item_data {
    public function __construct($entity, $name, $id) {
        parent::__construct($entity, $name, $id);

        $this->object = get_term_by('id', $this->id, $this->name);
    }

    public function get_title() {
        return apply_filters('gdrts_item_term_get_title', $this->object->name, $this);
    }

    public function get_author_id() {
        return apply_filters('gdrts_item_term_get_author_id', 0, $this);
    }

    public function get_url() {
        return apply_filters('gdrts_item_term_get_url', get_term_link($this->object), $this);
    }

    public function get_excerpt() {
        return apply_filters('gdrts_item_term_get_excerpt', $this->object->description, $this);
    }

    public function get_date_published($format = 'c', $gmt = false) {
        return apply_filters('gdrts_item_term_get_date_published', null, $this, $format, $gmt);
    }

    public function get_date_modified($format = 'c', $gmt = false) {
        return apply_filters('gdrts_item_term_get_date_modified', null, $this, $format, $gmt);
    }

    public function has_thumbnail() {
        return apply_filters('gdrts_item_term_has_thumbnail', false, $this);
    }

    public function get_thumbnail($size = 'thumbnail', $attr = array()) {
        return apply_filters('gdrts_item_term_get_thumbnail', '', $this, $size, $attr);
    }

    public function get_thumbnail_url($size = 'thumbnail') {
        return apply_filters('gdrts_item_term_get_thumbnail_url', '', $this, $size);
    }
}

class gdrts_item_custom extends gdrts_item_data {
    public function is_valid() {
        return true;
    }

    public function get_title() {
        return apply_filters('gdrts_item_custom_get_title', '', $this);
    }

    public function get_author_id() {
        return apply_filters('gdrts_item_custom_get_author_id', 0, $this);
    }

    public function get_url() {
        return apply_filters('gdrts_item_custom_get_url', '', $this);
    }

    public function get_excerpt() {
        return apply_filters('gdrts_item_custom_get_excerpt', '', $this);
    }

    public function get_date_published($format = 'c', $gmt = false) {
        return apply_filters('gdrts_item_custom_get_date_published', null, $this, $format, $gmt);
    }

    public function get_date_modified($format = 'c', $gmt = false) {
        return apply_filters('gdrts_item_custom_get_date_modified', null, $this, $format, $gmt);
    }

    public function has_thumbnail() {
        return apply_filters('gdrts_item_custom_has_thumbnail', false, $this);
    }

    public function get_thumbnail($size = 'thumbnail', $attr = array()) {
        return apply_filters('gdrts_item_custom_get_thumbnail', '', $this, $size, $attr);
    }

    public function get_thumbnail_url($size = 'thumbnail') {
        return apply_filters('gdrts_item_custom_get_thumbnail_url', '', $this, $size);
    }
}
