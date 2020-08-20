<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_engine_jsonld_article extends gdrts_rich_snippet_mode_engine_jsonld_creative_work {
    public $name = 'article';
    public $type = 'Article';

    public function run() {
        parent::run();

        if (!isset($this->build['headline'])) {
            $this->build['headline'] = $this->snippet->item->excerpt();
        }
    }
}
