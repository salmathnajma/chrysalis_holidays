<?php

if (!defined('ABSPATH')) { exit; }

class gdrts_method_restapi_stars_rating extends gdrts_method_rest_api {
    public $method = 'stars-rating';
}

new gdrts_method_restapi_stars_rating();
