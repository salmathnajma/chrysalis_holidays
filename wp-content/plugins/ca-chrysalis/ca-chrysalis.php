<?php
/**
 * Plugin Name: CA ShortCodes
 * Plugin URI: http://www.conditionsapply.net/
 * Description: CA ShortCodes to make functions easier 
 * Version: 1.0
 * Author: Salmath Najma U
 * Author URI: http://creativeasset.net/
 * Requires at least: 3.0
 * Tested up to: 5.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

include_once dirname( __FILE__ ) . '/includes/shortcode_destination.php';
include_once dirname( __FILE__ ) . '/includes/shortcode_recommends.php';
include_once dirname( __FILE__ ) . '/includes/shortcode_testimonial.php';
include_once dirname( __FILE__ ) . '/includes/shortcode_founder.php';


?>