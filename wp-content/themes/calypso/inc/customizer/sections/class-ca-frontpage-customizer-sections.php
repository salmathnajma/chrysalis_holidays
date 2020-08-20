<?php
/**
 * control all frontpage sections
 *
 * @package calypso
 */
class Ca_Frontpage_Customizer_Sections {

    public function __construct() {
          $this->render_sections();
    }

    private function render_sections(){

        global $wp_customize;

        $wp_customize->add_section('ca_feature_section',array(
			'title'=>'Features Section',
			'priority'=>20,
			'panel'=>'ca_frontpage_settings',
        ));

        $wp_customize->add_section('ca_about_section',array(
			'title'=>'About Section',
			'priority'=>30,
			'panel'=>'ca_frontpage_settings',
        ));
        
        
        $wp_customize->add_section('ca_blog_section',array(
			'title'=>'Blog Section',
			'priority'=>40,
			'panel'=>'ca_frontpage_settings',
        ));

        $wp_customize->add_section('ca_slider_section',array(
			'title'=>'Slider Section',
			'priority'=> 25,
			'panel'=>'ca_frontpage_settings',
        ));

    }

    public function render_additional_sections(){

        global $wp_customize;

        $wp_customize->add_section('sidebar-widgets-welcome-widgets',array(
			'title'=>'Welcome Section',
			'priority'=>10,
			'panel'=>'ca_frontpage_settings',
        ));

        $wp_customize->add_section('sidebar-widgets-subscribe-widgets',array(
			'title'=>'Subscribe Section',
			'priority'=>40,
			'panel'=>'ca_frontpage_settings',
        ));

        $wp_customize->add_section('sidebar-widgets-contact-widgets',array(
			'title'=>'Contact Section',
			'priority'=>50,
			'panel'=>'ca_frontpage_settings',
        ));

    }
}

?>