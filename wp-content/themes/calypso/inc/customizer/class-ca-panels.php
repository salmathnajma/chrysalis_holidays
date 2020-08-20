<?php
/**
 * Customizer panel manager.
 *
 * @package calypso
 */

class Ca_Panels {

    public function init() {

    }

    public function __construct() {
        $this->register_panels();
    }

    private function register_panels() {

        global $wp_customize;

        $wp_customize->add_panel('ca_appearance_settings',array(
			'title'=>'Appearance Settings',
			'priority'=> 30,
        ));

        $wp_customize->add_panel('ca_analytics_settings',array(
			'title'=>'Analytics Settings',
			'priority'=> 35,
        ));
        
        $wp_customize->add_panel('ca_typography_settings',array(
			'title'=>'Typography Settings',
			'priority'=> 40,
		));

        $wp_customize->add_panel('ca_frontpage_settings',array(
			'title'=>'Front Page Settings',
			'priority'=> 50,
        ));
        
        $wp_customize->add_panel('ca_blog_settings',array(
			'title'=>'Blog Settings',
			'priority'=> 60,
		));

    }

}

?>