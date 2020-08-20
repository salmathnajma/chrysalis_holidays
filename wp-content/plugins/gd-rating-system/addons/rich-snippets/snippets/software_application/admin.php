<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_admin_software_application extends gdrts_rich_snippet_mode_admin {
    public $name = 'software_application';
    public $defaults = array(
        'rating' => 'both',
        'os' => '',
        'category' => '',
        'name' => '',
        'description' => '',
        'offer' => 'none',
        'offers' => array(),
        'aggregate_offer' => array()
    );

    public function meta_content_save($item, $data, $mode) {
        $this->item = $item;

        $this->meta_data_load();

        foreach (array('os', 'category', 'name', 'description', 'offer') as $key) {
            if (isset($data[$this->name][$key])) {
                $this->data[$key] = d4p_sanitize_basic($data[$this->name][$key]);
            }
        }

        $this->_save_offers($data);
        $this->_save_aggregated_offer($data);

        $item = $this->meta_data_save($item);

        return $item;
    }
}

new gdrts_rich_snippet_mode_admin_software_application();
