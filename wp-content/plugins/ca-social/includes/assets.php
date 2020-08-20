<?php
if ( !is_admin() )
add_action( 'wp_enqueue_scripts', 'social_enqueue' );

if ( !function_exists( 'social_enqueue' ) ) {
    function social_enqueue() {
        
        wp_enqueue_style( 'ca-spcial', plugin_dir_url( __FILE__ ) . 'css/ca-social.css' );
 
    }
}
?>