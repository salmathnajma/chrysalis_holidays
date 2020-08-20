<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_snippet_news_article extends gdrts_rich_snippet_mode_snippet_article {
    public $name = 'news_article';
    public $type = 'NewsArticle';
    public $label = 'NewsArticle';

    protected function _get_engine() {
        if ($this->base['display'] == 'jsonld') {
            return new gdrts_rich_snippet_mode_engine_jsonld_news_article($this);
        } else if ($this->base['display'] == 'microdata') {
            return new gdrts_rich_snippet_mode_engine_microdata_news_article($this);
        }

        return null;
    }
}

new gdrts_rich_snippet_mode_snippet_news_article();
