<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_grid_database extends d4p_grid {
    public $_table_class_name = 'gdrts-grid-database';

    function __construct($args = array()) {
        parent::__construct(array(
            'singular'=> 'table',
            'plural' => 'tables',
            'ajax' => false
        ));
    }

    public function get_columns() {
	    return array(
            'name' => __("Table", "gd-rating-system"),
            'engine' => __("Engine", "gd-rating-system"),
            'records' => __("Records", "gd-rating-system"),
            'size' => __("Size", "gd-rating-system")
	    );
    }

    function column_name($item) {
        return $item->Name;
    }

    function column_engine($item) {
        return $item->Engine;
    }

    function column_records($item) {
        return $item->Rows;
    }

    function column_size($item) {
        return d4p_file_size_format($item->Data_length + $item->Index_length);
    }

    public function prepare_items() {
        $this->get_column_info_simple();

        $filter = gdrts_db()->wpdb()->prefix.gdrts_db()->_prefix;
        $query = "SHOW TABLE STATUS LIKE '".$filter."%'";

        $this->items = gdrts_db()->run($query);

        $this->set_pagination_args(array(
            'total_items' => count($this->items),
            'total_pages' => 1,
            'per_page' => count($this->items),
        ));
    }
}
