<?php
/**
 * control all appearence sections
 *
 * @package calypso
 */
class Ca_Appearence_Customizer_Sections {

    public function __construct() {
       
        $this->render_sections();
    }

    private function render_sections(){

        global $wp_customize;

        $wp_customize->add_section('ca_layout_section',array(
			'title'=>'Layout Settings',
			'priority'=>10,
			'panel'=>'ca_appearance_settings',
        ));

        $wp_customize->add_section('ca_header_section',array(
			'title'=>'Header Settings',
			'priority'=>20,
			'panel'=>'ca_appearance_settings',
        ));

        $wp_customize->add_section('ca_page_header_section',array(
			'title'=>'Page Header Settings',
			'priority'=>30,
			'panel'=>'ca_appearance_settings',
        ));

        $wp_customize->add_section('ca_general_section',array(
			'title'=>'General Settings',
			'priority'=>40,
			'panel'=>'ca_appearance_settings',
        ));

    }

   
}

?>