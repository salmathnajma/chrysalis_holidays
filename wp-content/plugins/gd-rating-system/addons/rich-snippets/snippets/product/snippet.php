<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_snippet_product extends gdrts_rich_snippet_mode_snippet {
    public $name = 'product';
    public $type = 'Product';
    public $label = 'Product';

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
        'offer' => '',
        'offers' => array(),
        'aggregate_offer' => array()
    );

    protected function _get_engine() {
        if ($this->base['display'] == 'jsonld') {
            return new gdrts_rich_snippet_mode_engine_jsonld_product($this);
        } else if ($this->base['display'] == 'microdata') {
            return new gdrts_rich_snippet_mode_engine_microdata_product($this);
        }

        return null;
    }
}

new gdrts_rich_snippet_mode_snippet_product();
