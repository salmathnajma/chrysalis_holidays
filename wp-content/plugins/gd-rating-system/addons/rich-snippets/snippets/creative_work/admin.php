<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_admin_creative_work extends gdrts_rich_snippet_mode_admin {
    public $name = 'creative_work';
    public $defaults = array(
        'rating' => 'both',
        'headline' => ''
    );

    public function meta_content_save($item, $data, $mode) {
        $this->item = $item;

        $this->meta_data_load();

        $this->data['rating'] = d4p_sanitize_basic($data[$this->name]['rating']);
        $this->data['headline'] = d4p_sanitize_basic($data[$this->name]['headline']);

        $item = $this->meta_data_save($item);

        return $item;
    }
}

new gdrts_rich_snippet_mode_admin_creative_work();
