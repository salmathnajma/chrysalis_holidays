<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_admin_blog_posting extends gdrts_rich_snippet_mode_admin_article {
    public $name = 'blog_posting';
}

new gdrts_rich_snippet_mode_admin_blog_posting();
