<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_admin_product extends gdrts_rich_snippet_mode_admin {
    public $name = 'product';
    public $defaults = array(
        'rating' => 'both',
        'sku' => '',
        'mpn' => '',
        'gtin8' => '',
        'gtin13' => '',
        'gtin14' => '',
        'brand' => '',
        'name' => '',
        'description' => '',
        'offer' => 'none',
        'offers' => array(),
        'aggregate_offer' => array()
    );

    public function meta_content_save($item, $data, $mode) {
        $this->item = $item;

        $this->meta_data_load();

        foreach (array('rating', 'sku', 'mpu', 'gtin8', 'gtin13', 'gtin14', 'brand', 'name', 'description', 'offer') as $key) {
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

new gdrts_rich_snippet_mode_admin_product();
