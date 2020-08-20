<?php
/**
 * Custom control for headings
 *
 * @package calypso
 */
if ( class_exists( 'WP_Customize_Control' ) ) {

    class Ca_Heading_Control extends WP_Customize_Control {

        public $type = 'ca-heading';

        public function enqueue() {
			wp_enqueue_style( 'ca-heading', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/custom-controls/heading/style.css', array(), '1.1', 'all' );
	    }

        public function render_content() {
            if ( ! empty( $this->label ) ) {

                echo '<h4 class="ca-customizer-heading">'.$this->label.'</h4>';
            }
        }

    }
}