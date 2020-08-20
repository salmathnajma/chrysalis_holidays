<?php
/**
 * The main customizer manager.
 *
 * @package calypso
 */

class Ca_Customizer {


    
    public function init() {
		add_action( 'customize_register', array( $this, 'register_customizer' ), 10 );
		add_filter('customizer_widgets_section_args', array( $this,'customizer_custom_widget_area'), 10, 3);

		add_action( 'customize_register', array( $this, 'register_additional_customizer' ), 999 );

	}

	function customizer_custom_widget_area($section_args, $section_id, $sidebar_id) {
		
		if( $sidebar_id === 'subscribe-widgets' ) {
			$section_args['priority'] = 20;
			$section_args['panel'] = 'ca_frontpage_settings';
		
		}
		if( $sidebar_id === 'contact-widgets' ) {
			$section_args['priority'] = 20;
			$section_args['panel'] = 'ca_frontpage_settings';
		
		}
		if( $sidebar_id === 'welcome-widgets' ) {
			$section_args['priority'] = 20;
			$section_args['panel'] = 'ca_frontpage_settings';
		
		}
	
		return $section_args;
	}

	function register_customizer($wp_customize){

		/* Change Default settings */
		$wp_customize->get_setting( 'custom_logo' )->transport      = 'postMessage';
		$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

			
		new Ca_Panels;
		new Ca_Sections;
		new Ca_Settings;
		new Ca_Controls;
		new Ca_Partials;
	
	}

	function register_additional_customizer(){

		$sections = new Ca_Sections;
		$sections->register_additional_sections();

		$settings = new Ca_Settings;
		$settings->register_additional_settings();

		$controls = new Ca_Controls;
		$controls->register_additional_controls();
	}
}

?>