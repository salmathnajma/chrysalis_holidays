<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core_posts_sorter {
    public $order = 'DESC';
    public $method = 'stars-rating';
    public $series = '';
    public $value = 'rating';
    public $scope = 'all';
    public $min = 0;

    public function __construct() {
        add_action('parse_query', array($this, 'parse_query'));

        add_filter('query_vars', array($this, 'query_vars'));
        add_action('pre_get_posts', array($this, 'pre_get_posts'));
    }

	public static function get_instance() {
		static $_instance = false;

		if ($_instance === false) {
			$_instance = new gdrts_core_posts_sorter();
		}

		return $_instance;
	}

    public function parse_query($wp_query) {
        if (!is_admin() && $wp_query->is_post_type_archive) {
            $post_type = $wp_query->query_vars['post_type'];

            $sort = false;
            $method = 'stars-rating';
            $series = '';
            $value = 'rating';

            if (gdrts_is_addon_loaded('posts') && gdrtsa_posts()->get($post_type.'_archive_sort_by_rating') === true) {
                $sort = true;
                $value = gdrtsa_posts()->get($post_type.'_archive_rating_value');

                $_method = gdrtsa_posts()->get($post_type.'_archive_rating_method');
                $_parts = explode('::', $_method, 2);
                $method = $_parts[0];

                if (isset($_parts[1])) {
                    $series = $_parts[1];
                }
            }

            if (apply_filters('gdrts_archive_posts_sorting_active', $sort, $post_type)) {
                $wp_query->query_vars['orderby'] = 'gdrts';
                $wp_query->query_vars['gdrts_method'] = apply_filters('gdrts_archive_posts_sorting_method', $method, $post_type);
                $wp_query->query_vars['gdrts_series'] = apply_filters('gdrts_archive_posts_sorting_series', $series, $post_type);
                $wp_query->query_vars['gdrts_value'] = apply_filters('gdrts_archive_posts_sorting_value', $value, $post_type);
            }
        }
    }

    public function query_vars($vars) {
        $vars[] = 'gdrts_method';
        $vars[] = 'gdrts_series';
        $vars[] = 'gdrts_value';
        $vars[] = 'gdrts_scope';
        $vars[] = 'gdrts_min';

        return $vars;
    }

    public function pre_get_posts($wp_query) {
        if (isset($wp_query->query_vars['orderby']) && $wp_query->query_vars['orderby'] == 'gdrts') {
            $this->prepare_rating($wp_query->query_vars);

            add_filter('posts_join', array($this, 'posts_join'));
            add_filter('posts_orderby', array($this, 'posts_orderby'));

            if ($this->scope == 'rated') {
                add_filter('posts_where', array($this, 'posts_where'));
            }
        }
    }

    public function prepare_rating($query_vars) {
        if (isset($query_vars['order'])) {
            $this->order = strtoupper($query_vars['order']) == 'ASC' ? 'ASC' : 'DESC';
        }

        if (isset($query_vars['gdrts_method'])) {
            $_method = sanitize_text_field($query_vars['gdrts_method']);

            if (gdrts_is_method_loaded($_method)) {
                $this->method = $_method;
            }
        }

        if (isset($query_vars['gdrts_series'])) {
            $_series = sanitize_text_field($query_vars['gdrts_series']);

            if (!empty($_series) && gdrts_method_has_series($this->method)) {
                $this->series = $_series;
            }
        }

        if (isset($query_vars['gdrts_value'])) {
            $_value = sanitize_text_field($query_vars['gdrts_value']);

            if (in_array($_value, array('rating', 'votes'))) {
                $this->value = $_value;
            }
        }

        if (isset($query_vars['gdrts_scope'])) {
            $_scope = sanitize_text_field($query_vars['gdrts_scope']);

            if (in_array($_scope, array('all', 'rated'))) {
                $this->scope = $_scope;
            }
        }

        if (isset($query_vars['gdrts_min'])) {
            $this->min = intval($query_vars['gdrts_min']);
        } else {
            $this->min = 0;

            if ($this->method == 'like-this') {
                $this->min = 1;
            }
        }

        if (gdrts_method_has_series($this->method) && $this->series == '') {
            $this->series = 'default';
        }
    }

    public function posts_where($where) {
        remove_filter('posts_where', array($this, 'posts_where'));

        if (!empty($where)) {
            $where.= ' AND ';
        }

        $where.= 'gdrts_b.rating >= '.$this->min;

        return $where;
    }

    public function posts_join($join) {
        remove_filter('posts_join', array($this, 'posts_join'));

        $_use_join = $this->scope == 'rated' ? " INNER JOIN " : " LEFT JOIN ";

        $join.= $_use_join.gdrts_db()->items." gdrts_i ON gdrts_i.entity = 'posts' AND gdrts_i.id = ".gdrts_db()->wpdb()->posts.".ID";
        $join.= $_use_join.gdrts_db()->items_basic." gdrts_b ON gdrts_i.item_id = gdrts_b.item_id AND gdrts_b.method = '".$this->method."'";

        if (!empty($this->series)) {
            $join.= " AND gdrts_b.series = '".$this->series."'";
        }

        return $join;
    }

    public function posts_orderby($orderby) {
        remove_filter('posts_orderby', array($this, 'posts_orderby'));

        $orderby_new = 'gdrts_b.rating '.$this->order;

        if ($this->value == 'rating') {
            $orderby_new.= ', gdrts_b.votes '.$this->order;
        }

        $orderby_new.= ', '.$orderby;

        return $orderby_new;
    }
}

/** @return gdrts_core_posts_sorter */
function gdrts_posts_sort() {
    return gdrts_core_posts_sorter::get_instance();
}
