<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_snippet_blog_posting extends gdrts_rich_snippet_mode_snippet_article {
    public $name = 'blog_posting';
    public $type = 'BlogPosting';
    public $label = 'BlogPosting';

    protected function _get_engine() {
        if ($this->base['display'] == 'jsonld') {
            return new gdrts_rich_snippet_mode_engine_jsonld_blog_posting($this);
        } else if ($this->base['display'] == 'microdata') {
            return new gdrts_rich_snippet_mode_engine_microdata_blog_posting($this);
        }

        return null;
    }
}

new gdrts_rich_snippet_mode_snippet_blog_posting();
