<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_core_comments_sorter {
    public $order = 'DESC';
    public $method = 'stars-rating';
    public $series = '';
    public $value = 'rating';
    public $scope = 'all';
    public $min = 0;

    public function __construct() {
        add_action('pre_get_comments', array($this, 'pre_get_comments'));
    }

	public static function get_instance() {
		static $_instance = false;

		if ($_instance === false) {
			$_instance = new gdrts_core_comments_sorter();
		}

		return $_instance;
	}

	public function pre_get_comments($cmm_query) {
        if (isset($cmm_query->query_vars['orderby']) && $cmm_query->query_vars['orderby'] == 'gdrts') {
            $this->prepare_rating($cmm_query->query_vars);

            add_filter('comments_clauses', array($this, 'comments_clauses'));
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

    public function comments_clauses($pieces) {
        remove_filter('comments_clauses', array($this, 'comments_clauses'));

        $_use_join = $this->scope == 'rated' ? " INNER JOIN " : " LEFT JOIN ";
        $_orderby = $pieces['orderby'];

        $pieces['join'].= $_use_join.gdrts_db()->items." gdrts_i ON gdrts_i.entity = 'comments' AND gdrts_i.id = ".gdrts_db()->wpdb()->comments.".comment_ID";
        $pieces['join'].= $_use_join.gdrts_db()->items_basic." gdrts_b ON gdrts_i.item_id = gdrts_b.item_id AND gdrts_b.method = '".$this->method."'";

        if (!empty($this->series)) {
            $pieces['join'].= " AND gdrts_b.series = '".$this->series."'";
        }

        if ($this->scope == 'rated') {
            if (isset($pieces['where']) && !empty($pieces['where'])) {
                $pieces['where'].= ' AND ';
            }

            $pieces['where'].= 'gdrts_b.rating >= '.$this->min;
        }

        $pieces['orderby'] = 'gdrts_b.rating '.$this->order;

        if ($this->value == 'rating') {
            $pieces['orderby'].= ', gdrts_b.votes '.$this->order;
        }

        $pieces['orderby'].= ', '.$_orderby;

        return $pieces;
    }
}

/** @return gdrts_core_comments_sorter */
function gdrts_comments_sort() {
    return gdrts_core_comments_sorter::get_instance();
}
