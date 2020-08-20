<?php
/**
 * control all appearence settings
 *
 * @package calypso
 */
class Ca_Appearence_Customizer_Settings {

    public function __construct() {
        $this->render_settings();
    }

    private function render_settings(){
        $this->render_layout_settings();
        $this->render_page_header_settings();
        $this->render_general_settings();
    }

    private function render_layout_settings(){
 
        global $wp_customize;

        $wp_customize->add_setting( 'ca_page_layout', array(
            'default' => 'full-width',
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_text_sanitization'
        ));

        $wp_customize->add_setting( 'ca_blog_sidebar_layout', array(
            'default' => 'right-sidebar',
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_text_sanitization'
        ));

        $wp_customize->add_setting( 'ca_blog_layout', array(
            'default' => 'blog_normal_layout',
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_text_sanitization'
        ));
    }

    private function render_page_header_settings(){

        global $wp_customize;

        $wp_customize->add_setting( 'ca_header_layout', array(
            'default' => 'default',
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_text_sanitization'
        ));

        $wp_customize->add_setting( 'ca_header_image',array(
            'default' => '',
            'transport' => 'postMessage',
            'sanitize_callback' => 'absint'
        ));

        $wp_customize->add_setting( 'ca_header_bgcolor', array(
            'default' => '#333',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color'
        ));

    }

    private function render_general_settings(){
        global $wp_customize;
        
        $wp_customize->add_setting( 'ca_hide_search',array(
            'default' => 0,
            'sanitize_callback' => 'ca_sanitize_checkbox',
            'transport' => 'postMessage'
        ));
    }
}