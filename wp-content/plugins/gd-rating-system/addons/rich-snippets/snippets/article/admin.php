<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_admin_article extends gdrts_rich_snippet_mode_admin_creative_work {
    public $name = 'article';
    public $defaults = array(
        'rating' => 'none',
        'headline' => ''
    );
}

new gdrts_rich_snippet_mode_admin_article();
