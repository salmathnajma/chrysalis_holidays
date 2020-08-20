<?php
/**
 * control all analytics settings
 *
 * @package calypso
 */
class Ca_Analytics_Customizer_Settings {

    public function __construct() {
        $this->render_settings();
    }

    private function render_settings(){
        global $wp_customize;

        $wp_customize->add_setting( 'ca_tracking_header', array(
            'default' => '',
            'transport' => 'postMessage',
            array(          
                'sanitize_callback' => 'ca_sanitize_js_code', //encode for DB insert
                'sanitize_js_callback' => 'ca_escape_js_output' //ecape script for the textarea
            )
          
        ));

        $wp_customize->add_setting( 'ca_tracking_body', array(
            'default' => '',
            'transport' => 'postMessage',
            array(          
                'sanitize_callback' => 'ca_sanitize_js_code', //encode for DB insert
                'sanitize_js_callback' => 'ca_escape_js_output' //ecape script for the textarea
            )
          
        ));

        $wp_customize->add_setting( 'ca_tracking_footer', array(
            'default' => '',
            'transport' => 'postMessage',
            array(          
                'sanitize_callback' => 'ca_sanitize_js_code', //encode for DB insert
                'sanitize_js_callback' => 'ca_escape_js_output' //ecape script for the textarea
            )
          
        ));
    }

   
}