<?php
/**
 * defined all layout controls
 *
 * @package calypso
 */
class Ca_Tracking_Controls {

    public function __construct() {
       
        $this->render_controls();
    }

    private function render_controls(){
        
        global $wp_customize;

        $wp_customize->add_control( 'ca_tracking_header', array(
            'label' => __( 'Tracking code in header', 'calypso' ),
            'type' => 'textarea',
            'section' => 'ca_tracking_section',
            'settings' => 'ca_tracking_header',
            'priority' => 10
        ));
        $wp_customize->add_control( 'ca_tracking_body', array(
            'label' => __( 'Tracking code in body', 'calypso' ),
            'type' => 'textarea',
            'section' => 'ca_tracking_section',
            'settings' => 'ca_tracking_body',
            'priority' => 20
        ));
        $wp_customize->add_control( 'ca_tracking_footer', array(
            'label' => __( 'Tracking code in footer', 'calypso' ),
            'type' => 'textarea',
            'section' => 'ca_tracking_section',
            'settings' => 'ca_tracking_footer',
            'priority' => 30
        ));
      
    }

   
}