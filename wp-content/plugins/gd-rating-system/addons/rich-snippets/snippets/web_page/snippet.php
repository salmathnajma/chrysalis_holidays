<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_snippet_web_page extends gdrts_rich_snippet_mode_snippet_article {
    public $name = 'web_page';
    public $type = 'WebPage';
    public $label = 'WebPage';

    protected function _get_engine() {
        if ($this->base['display'] == 'jsonld') {
            return new gdrts_rich_snippet_mode_engine_jsonld_web_page($this);
        } else if ($this->base['display'] == 'microdata') {
            return new gdrts_rich_snippet_mode_engine_microdata_web_page($this);
        }

        return null;
    }
}

new gdrts_rich_snippet_mode_snippet_web_page();
