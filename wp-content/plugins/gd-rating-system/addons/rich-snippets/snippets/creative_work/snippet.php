<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_snippet_creative_work extends gdrts_rich_snippet_mode_snippet {
    public $name = 'creative_work';
    public $type = 'CreativeWork';
    public $label = 'CreativeWork';

    public $defaults = array(
        'rating' => 'both',
        'headline' => ''
    );

    protected function _get_engine() {
        if ($this->base['display'] == 'jsonld') {
            return new gdrts_rich_snippet_mode_engine_jsonld_creative_work($this);
        } else if ($this->base['display'] == 'microdata') {
            return new gdrts_rich_snippet_mode_engine_microdata_creative_work($this);
        }

        return null;
    }
}

new gdrts_rich_snippet_mode_snippet_creative_work();
