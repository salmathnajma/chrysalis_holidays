<?php
/**
 * The front page template file.
 *
 * If the user has selected a static page for their homepage, this is what will
 * appear.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package calypso
 */


if ( ! is_page_template()) {

        get_header();
       
		/**
		 * Home page sections hook.
		 */
		do_action( 'home_sections', false );

		get_footer();

} else {
	include( get_page_template() );
} ?>
