<?php
/**
 * class for include back-end css,js and functions
 *
 * @package calypso
 */
class Ca_Backend {

    public function __construct() {

        
    }

    /** Enqueue theme styles.  */
    public function enqueue_styles() {
       
    }

    
     /** Enqueue theme scripts.  */
    public function enqueue_scripts() {
       
        
        
    }

    public function enqueue_customizer_script() {
        wp_enqueue_script(
			'ca-customizer',
			get_template_directory_uri() . '/assets/admin/js/customizer.js',
			array( 'jquery' ),
			CA_VERSION,
			true
		);
    }

    public function enqueue_customizer_controls() {

        wp_enqueue_style( 'ca-customizer-style', get_template_directory_uri() . '/assets/admin/css/customizer-style.css', array(), CA_VERSION );
	
        wp_enqueue_script(
			'ca-customizer-controls',
			get_template_directory_uri() . '/assets/admin/js/customizer-controls.js',
			array( 'jquery' ),
			CA_VERSION,
			true
        );
    }
}



?>