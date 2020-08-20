<?php
if ( !is_admin() )
add_action( 'wp_enqueue_scripts', 'bridge_enqueue' );

if ( !function_exists( 'bridge_enqueue' ) ) {
    function bridge_enqueue() {
        
        wp_enqueue_style( 'ca-bridge', plugin_dir_url( __FILE__ ) . 'css/ca-bridge.css' );
        wp_enqueue_script( 'isotope',plugin_dir_url( __FILE__ ) . 'js/jquery.isotope.min.js', array( 'jquery' ) );
		

    }
}
?>