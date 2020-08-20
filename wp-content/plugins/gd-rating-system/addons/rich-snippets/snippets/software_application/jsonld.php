<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_engine_jsonld_software_application extends gdrts_rich_snippet_mode_engine_jsonld {
    public $name = 'software_application';
    public $type = 'SoftwareApplication';

    public function run() {
        $this->build = array(
            '@context' => 'http://schema.org/',
            '@type' => $this->type,
            'url' => $this->snippet->item->url(),
            'name' => empty($this->snippet->data['name']) ? $this->snippet->item->title() : $this->snippet->data['name'],
            'operatingSystem' => $this->snippet->data['os'],
            'applicationCategory' => 'https://schema.org/'.$this->snippet->data['category'],
            'description' => $this->snippet->data['description']
        );

        $this->_get_featured_image();

        if ($this->snippet->data['offer'] == 'aggregate_offer') {
            $this->build['offers'] = $this->_get_aggregate_offer();
        } else if ($this->snippet->data['offer'] == 'offers') {
            $this->build['offers'] = $this->_get_offers_list();
        }

        $this->_rating_data();
    }
}