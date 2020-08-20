<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_snippet_media_object extends gdrts_rich_snippet_mode_snippet_creative_work {
    public $name = 'media_object';
    public $type = 'MediaObject';
    public $label = 'MediaObject';

    protected function _get_engine() {
        if ($this->base['display'] == 'jsonld') {
            return new gdrts_rich_snippet_mode_engine_jsonld_media_object($this);
        } else if ($this->base['display'] == 'microdata') {
            return new gdrts_rich_snippet_mode_engine_microdata_media_object($this);
        }

        return null;
    }
}

new gdrts_rich_snippet_mode_snippet_media_object();
