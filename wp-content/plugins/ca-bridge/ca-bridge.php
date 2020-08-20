<?php
/**
 * Plugin Name: CA Bridge
 * Plugin URI: http://www.conditionsapply.net/
 * Description: CA Bridge helps to connect different postypes. An extension for cappa theme.
 * Version: 1.0
 * Author: Nithin K V
 * Author URI: http://creativeasset.net/
 * Requires at least: 3.0
 * Tested up to: 4.9
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


include_once dirname( __FILE__ ) . '/config.php';
include_once dirname( __FILE__ ) . '/includes/admin-settings.php';
include_once dirname( __FILE__ ) . '/includes/post-types.php';
include_once dirname( __FILE__ ) . '/includes/metafields.php';
include_once dirname( __FILE__ ) . '/includes/functions.php';
include_once dirname( __FILE__ ) . '/template.php';
include_once dirname( __FILE__ ) . '/includes/assets.php';


add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'add_action_links' );

function add_action_links ( $links ) {
    $mylinks = array(
        '<a href="' . admin_url( 'options-general.php?page=bridge-settings' ) . '">Settings</a>',
    );
    return array_merge( $links, $mylinks );
}


?>