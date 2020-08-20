<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_engine_jsonld_product extends gdrts_rich_snippet_mode_engine_jsonld {
    public $name = 'product';
    public $type = 'Product';

    public function run() {
        $this->build = array(
            '@context' => 'http://schema.org/',
            '@type' => $this->type,
            'url' => $this->snippet->item->url(),
            'name' => empty($this->snippet->data['name']) ? $this->snippet->item->title() : $this->snippet->data['name'],
            'brand' => $this->snippet->data['brand'],
            'description' => $this->snippet->data['description']
        );

        $this->_get_featured_image();

        foreach (array('sku', 'mpn', 'gtin8', 'gtin13', 'gtin14') as $key) {
            $value = $this->snippet->data[$key];

            if (!empty($value)) {
                $this->build[$key] = $value;
            }
        }

        if ($this->snippet->data['offer'] == 'aggregate_offer') {
            $this->build['offers'] = $this->_get_aggregate_offer();
        } else if ($this->snippet->data['offer'] == 'offers') {
            $this->build['offers'] = $this->_get_offers_list();
        }

        $this->_rating_data();
    }
}
