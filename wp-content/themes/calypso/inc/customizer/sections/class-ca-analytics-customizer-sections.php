<?php
/**
 * control all appearence sections
 *
 * @package calypso
 */
class Ca_Analytics_Customizer_Sections {

    public function __construct() {
       
        $this->render_sections();
    }

    private function render_sections(){

        global $wp_customize;

        $wp_customize->add_section('ca_tracking_section',array(
			'title'=>'Tracking Codes',
			'priority'=>10,
			'panel'=>'ca_analytics_settings',
        ));

    }

   
}

?>