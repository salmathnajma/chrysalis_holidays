<?php
/**
 * control all appearence settings
 *
 * @package calypso
 */
class Ca_Typography_Customizer_Settings {

    public function __construct() {
        $this->render_settings();
    }

    private function render_settings(){

        $this->render_general_typography_settings();
        $this->render_menu_typography_settings();
        $this->render_frontpage_typography_settings();
       
    }

    private function render_general_typography_settings(){
        global $wp_customize;

        $wp_customize->add_setting( 'ca_anchor_fontcolor', array(
            'default' => '#2CD007',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color'
        ));

        $wp_customize->add_setting( 'ca_typography_heading_heading', array(
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_kses'
        ));

        $wp_customize->add_setting( 'ca_heading_fontfamily',array(
            'default' => json_encode(
                array(
                    'font' => 'Open Sans',
                    'regularweight' => 'regular',
                    'italicweight' => 'normal',
                    'boldweight' => '700',
                    'category' => 'sans-serif'
                )
            ),
            'sanitize_callback' => 'ca_google_font_sanitization'
        ));
        $wp_customize->add_setting( 'ca_heading_fontsize', array(
            'default' => 20,
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_sanitize_integer'
        ));
      
        $wp_customize->add_setting( 'ca_typography_body_heading', array(
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_kses'
        ));

        $wp_customize->add_setting( 'ca_body_fontfamily',array(
            'default' => json_encode(
                array(
                    'font' => 'Open Sans',
                    'regularweight' => 'regular',
                    'italicweight' => 'normal',
                    'boldweight' => '700',
                    'category' => 'sans-serif'
                )
            ),
            'sanitize_callback' => 'ca_google_font_sanitization'
        ));
        $wp_customize->add_setting( 'ca_body_fontsize', array(
            'default' => 16,
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_sanitize_integer'
        ));
        $wp_customize->add_setting( 'ca_body_fontcolor', array(
            'default' => '#000',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color'
        ));

        $wp_customize->add_setting( 'ca_typography_title_heading', array(
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_kses'
        ));

        $wp_customize->add_setting( 'ca_title_fontsize', array(
            'default' => 24,
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_sanitize_integer'
        ));
       
    }

    private function render_menu_typography_settings(){
 
        global $wp_customize;

        $wp_customize->add_setting( 'ca_menu_fontsize', array(
            'default' => 16,
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_sanitize_integer'
        ));

        $wp_customize->add_setting( 'ca_menu_fontcolor', array(
            'default' => 'rgba(209,0,55,0.7)',
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_hex_rgba_sanitization'
        ));
    }

    private function render_frontpage_typography_settings(){
 
        global $wp_customize;

        $wp_customize->add_setting( 'ca_typography_section_heading', array(
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_kses'
        ));

        $wp_customize->add_setting( 'ca_section_title_fontsize', array(
            'default' => 20,
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_sanitize_integer'
        ));

       
        $wp_customize->add_setting( 'ca_section_des_fontsize', array(
            'default' => 18 ,
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_sanitize_integer'
        ));

       
        $wp_customize->add_setting( 'ca_typography_welcome_section_heading', array(
            'transport' => 'postMessage',
            'sanitize_callback' => 'wp_kses'
        ));

        $wp_customize->add_setting( 'ca_welcome_title_fontsize', array(
            'default' => 30,
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_sanitize_integer'
        ));

        $wp_customize->add_setting( 'ca_welcome_fontcolor', array(
            'default' => '#fff',
            'transport' => 'postMessage',
            'sanitize_callback' => 'sanitize_hex_color'
        ));

        $wp_customize->add_setting( 'ca_welcome_des_fontsize', array(
            'default' => 20,
            'transport' => 'postMessage',
            'sanitize_callback' => 'ca_sanitize_integer'
        ));

    }
}
?>