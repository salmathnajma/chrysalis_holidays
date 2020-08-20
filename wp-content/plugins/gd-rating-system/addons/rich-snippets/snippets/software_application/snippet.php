<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_snippet_software_application extends gdrts_rich_snippet_mode_snippet {
    public $name = 'software_application';
    public $type = 'SoftwareApplication';
    public $label = 'Software Application';

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

    protected function _get_engine() {
        if ($this->base['display'] == 'jsonld') {
            return new gdrts_rich_snippet_mode_engine_jsonld_software_application($this);
        }

        return null;
    }
}

new gdrts_rich_snippet_mode_snippet_software_application();
