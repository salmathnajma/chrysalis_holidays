<?php
/**
 * control all typography sections
 *
 * @package calypso
 */
class Ca_Typography_Customizer_Sections {

    public function __construct() {
       
        $this->render_sections();
    }

    private function render_sections(){

        global $wp_customize;

        $wp_customize->add_section('ca_general_typography_section',array(
			'title'=>'General',
			'priority'=>10,
			'panel'=>'ca_typography_settings',
        ));

        $wp_customize->add_section('ca_menu_section',array(
			'title'=>'Main Menu',
			'priority'=>20,
			'panel'=>'ca_typography_settings',
        ));

        $wp_customize->add_section('ca_frontpage_typography_section',array(
			'title'=>'Front Page',
			'priority'=>30,
			'panel'=>'ca_typography_settings',
        ));

    }
}
?>