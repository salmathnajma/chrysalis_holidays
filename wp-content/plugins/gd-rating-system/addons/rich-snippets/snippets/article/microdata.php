<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_engine_microdata_article extends gdrts_rich_snippet_mode_engine_microdata_creative_work {
    public $name = 'article';
    public $type = 'Article';

    public function run() {
        parent::run();

        if (!isset($this->build['headline'])) {
            $this->build['root']['items']['headline'] = array('tag' => 'meta', 'itemprop' => 'headline', 'content' => $this->snippet->item->title());
        }
    }
}
