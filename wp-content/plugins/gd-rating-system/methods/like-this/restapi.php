<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_method_restapi_like_this extends gdrts_method_rest_api {
    public $method = 'like-this';
}

new gdrts_method_restapi_like_this();
