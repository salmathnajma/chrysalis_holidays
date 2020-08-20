<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_snippet_article extends gdrts_rich_snippet_mode_snippet_creative_work {
    public $name = 'article';
    public $type = 'Article';
    public $label = 'Article';

    public $defaults = array(
        'rating' => 'none',
        'headline' => ''
    );

    protected function _get_engine() {
        if ($this->base['display'] == 'jsonld') {
            return new gdrts_rich_snippet_mode_engine_jsonld_article($this);
        } else if ($this->base['display'] == 'microdata') {
            return new gdrts_rich_snippet_mode_engine_microdata_article($this);
        }

        return null;
    }
}

new gdrts_rich_snippet_mode_snippet_article();
