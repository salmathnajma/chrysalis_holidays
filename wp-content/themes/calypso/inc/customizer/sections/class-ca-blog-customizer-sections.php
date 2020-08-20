<?php
/**
 * control all blog sections
 *
 * @package calypso
 */
class Ca_Blog_Customizer_Sections {

    public function __construct() {
        

        $this->render_sections();
    }

    private function render_sections(){

        global $wp_customize;

        $wp_customize->add_section('ca_blog_general_section',array(
			'title'=>'General Settings',
			'priority'=>10,
			'panel'=>'ca_blog_settings',
        ));

    }

   
}

?>