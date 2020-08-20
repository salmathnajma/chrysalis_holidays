<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_admin_custom extends gdrts_rich_snippet_mode_admin {
    public $name = 'custom';
    public $defaults = array(
        'rating' => 'both',
        'name' => '',
        'features' => array()
    );

    public function meta_content_init($item, $base = null) {
        parent::meta_content_init($item, $base);

        $this->defaults['name'] = gdrtsa_admin_rich_snippets()->get($this->item->name.'_snippet_custom_type_name');
        $this->defaults['features'] = gdrtsa_admin_rich_snippets()->get($this->item->name.'_snippet_custom_type_features');
    }

    public function meta_content_save($item, $data, $mode) {
        $this->item = $item;

        $this->meta_data_load();

        $this->data['rating'] = d4p_sanitize_basic($data[$this->name]['rating']);
        $this->data['name'] = d4p_sanitize_basic($data[$this->name]['name']);
        $this->data['features'] = d4p_sanitize_basic_array((array)$data[$this->name]['features']);

        $item = $this->meta_data_save($item);

        return $item;
    }
}

new gdrts_rich_snippet_mode_admin_custom();
