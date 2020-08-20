<?php

if (!defined('ABSPATH')) {
    exit;
}

class gdrts_rich_snippet_mode_admin_web_page extends gdrts_rich_snippet_mode_admin_article {
    public $name = 'web_page';
}

new gdrts_rich_snippet_mode_admin_web_page();
