<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_admin_news_article extends gdrts_rich_snippet_mode_admin_article {
    public $name = 'news_article';
}

new gdrts_rich_snippet_mode_admin_news_article();
