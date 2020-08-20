<?php
/**
 * control all frontpage settings
 *
 * @package calypso
 */
class Ca_Frontpage_Customizer_Settings {

    public function __construct() {
        $this->render_settings();
    }

    private function render_settings(){
        $this->render_feature_settings();
        $this->render_about_settings();
        $this->render_blog_settings();
        $this->render_slider_settings();
    }

    public function render_additional_settings(){
        $this->render_welcome_settings();
        $this->render_subscribe_settings();
        $this->render_contact_settings();
    }

    private function render_welcome_settings(){

        global $wp_customize;

        $wp_customize->add_setting( 'ca_hide_welcome_section',array(
            'default' => 0,
            'sanitize_callback' => 'ca_sanitize_checkbox',
            'transport' => 'postMessage'
        ));

        $wp_customize->add_setting('ca_welcome_title',array(
            'default'=>__( 'Welcome to our theme', 'calypso' ),
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'transport' => 'postMessage' 
        ));
        
        $wp_customize->add_setting( 'ca_welcome_description',array(
            'default' => __( 'A beautiful simple wordpress theme.', 'calypso' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        ));

        $wp_customize->add_setting('ca_welcome_btn_text',array(
            'default'=>__( 'About', 'calypso' ),
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'transport' => 'postMessage' 
        ));

        $wp_customize->add_setting('ca_welcome_btn_url',array(
            'default'=>__( '#', 'calypso' ),
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'transport' => 'postMessage' 
        ));

        $wp_customize->add_setting( 'ca_welcome_image',array(
            'default' => '',
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_sanitize_image'
        ));

        $wp_customize->add_setting( 'ca_welcome_layout', array(
            'default' => 'center',
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_text_sanitization'
        ));
    }
    private function render_feature_settings(){

        global $wp_customize;

        $wp_customize->add_setting( 'ca_hide_feature_section',array(
            'default' => 0,
            'sanitize_callback' => 'ca_sanitize_checkbox',
            'transport' => 'postMessage'
        ));

        $wp_customize->add_setting( 'ca_feature_layout', array(
            'default' => 'center',
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_text_sanitization'
        ));

        $wp_customize->add_setting('ca_feature_title',array(
            'default'=>__( 'Features', 'calypso' ),
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'transport' => 'postMessage' 
        ));
        
        $wp_customize->add_setting( 'ca_feature_description',array(
            'default' => __( 'Exclusive features from calypso', 'calypso' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        ));

        $wp_customize->add_setting( 'ca_features_content',array(
            'default' => __( 'Exclusive features from calypso', 'calypso' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_repeater_sanitize'
        ));
 
    }

    private function render_slider_settings(){
        global $wp_customize;

        $wp_customize->add_setting( 'ca_hide_slider_section',array(
            'default' => 0,
            'sanitize_callback' => 'ca_sanitize_checkbox',
            'transport' => 'postMessage'
        ));

        $wp_customize->add_setting( 'ca_slider_content',array(
            'default' => __( 'Sliders', 'calypso' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_repeater_sanitize'
        ));
    }

    private function render_about_settings(){

        global $wp_customize;

        $wp_customize->add_setting( 'ca_hide_about_section',array(
            'default' => 0,
            'sanitize_callback' => 'ca_sanitize_checkbox',
            'transport' => 'postMessage'
        ));

        $wp_customize->add_setting( 'ca_about_content', array(
            'default' => '',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_text_field'
        ));

        $wp_customize->add_setting( 'ca_about_image',array(
            'default' => '',
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_sanitize_image'
        ));
      
    }

    private function render_blog_settings(){

        global $wp_customize;

        $wp_customize->add_setting( 'ca_hide_blog_section',array(
            'default' => 0,
            'sanitize_callback' => 'ca_sanitize_checkbox',
            'transport' => 'postMessage'
        ));

        $wp_customize->add_setting('ca_blog_title',array(
            'default'=>__( 'Blogs', 'calypso' ),
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'transport' => 'postMessage' 
        ));
        
        $wp_customize->add_setting( 'ca_blog_description',array(
            'default' => __( 'New blogs from calypso', 'calypso' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        ));

        $wp_customize->add_setting('ca_blog_number',array(
            'default'=>__( 2, 'calypso' ),
            'sanitize_callback' => 'absint',
            'transport' => 'postMessage' 
        ));

        $wp_customize->add_setting('ca_blog_column',array(
            'default'=> 2,
            'sanitize_callback' => 'ca_radio_sanitization',
            'transport' => 'postMessage' 
        ));

        
    }

    private function render_subscribe_settings(){

        global $wp_customize;

        $wp_customize->add_setting( 'ca_hide_subscribe_section',array(
            'default' => 1,
            'sanitize_callback' => 'ca_sanitize_checkbox',
            'transport' => 'postMessage'
        ));

        $wp_customize->add_setting( 'ca_subscribe_image',array(
            'default' => '',
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_sanitize_image'
        ));

        $wp_customize->add_setting('ca_subscribe_title',array(
            'default'=>__( 'Get updated', 'calypso' ),
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'transport' => 'postMessage' 
        ));
        
        $wp_customize->add_setting( 'ca_subscribe_description',array(
            'default' => __( 'Subscribe for our latest news.', 'calypso' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        ));

        $wp_customize->add_setting( 'ca_subscribe_notice',array(
            'default' => '',
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_text_sanitization'
        ));
      
    }

    private function render_contact_settings(){

        global $wp_customize;
        
        $wp_customize->add_setting( 'ca_contact_notice',array(
            'default' => '',
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_text_sanitization'
        ));

        $wp_customize->add_setting( 'ca_hide_contact_section',array(
            'default' => 1,
            'sanitize_callback' => 'ca_sanitize_checkbox',
            'transport' => 'postMessage'
        ));

        $wp_customize->add_setting('ca_contact_shortcode',array(
            'sanitize_callback' => 'ca_text_sanitization',
            'transport' => 'postMessage' 
        ));

        $wp_customize->add_setting( 'ca_contact_image',array(
            'default' => '',
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_sanitize_image'
        ));

        $wp_customize->add_setting('ca_contact_title',array(
            'default'=>__( 'Contact US', 'calypso' ),
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'transport' => 'postMessage' 
        ));
        
        $wp_customize->add_setting( 'ca_contact_description',array(
            'default' => __( 'We will get back to you shortly.', 'calypso' ),
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_filter_nohtml_kses'
        ));

        $wp_customize->add_setting( 'ca_contact_content',array(
            'default' => '',
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_kses_post'
         ));
       
      
    }
   
}

?>