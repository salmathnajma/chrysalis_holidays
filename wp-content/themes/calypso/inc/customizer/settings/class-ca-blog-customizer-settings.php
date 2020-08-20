<?php
/**
 * control all blog settings
 *
 * @package calypso
 */
class Ca_Blog_Customizer_Settings {

    public function __construct() {
        $this->render_settings();
    }

    private function render_settings(){
        $this->render_layout_settings();
    }

    private function render_layout_settings(){
        
        global $wp_customize;
        
        $wp_customize->add_setting('ca_blog_excerpt_length',array(
            'default'=>__( 40, 'calypso' ),
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage' 
        ));

        $wp_customize->add_setting( 'ca_hide_social_icon',array(
            'default' => 0,
            'sanitize_callback' => 'ca_sanitize_checkbox',
            'transport' => 'postMessage'
        ));

        $wp_customize->add_setting( 'ca_hide_post_nav',array(
            'default' => 0,
            'sanitize_callback' => 'ca_sanitize_checkbox',
            'transport' => 'postMessage'
        ));

        $wp_customize->add_setting( 'ca_hide_related_posts',array(
            'default' => 0,
            'sanitize_callback' => 'ca_sanitize_checkbox',
            'transport' => 'postMessage'
        ));

        $wp_customize->add_setting('ca_related_posts_title',array(
            'default'=>__( 'Related Posts', 'calypso' ),
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'transport' => 'postMessage' 
        ));
    }
}