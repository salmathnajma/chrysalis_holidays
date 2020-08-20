<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package calypso
 */

get_header();
do_action( 'ca_before_page_wrapper' );
?>
<div class="<?php echo ca_layout(); ?>">
	<div class="ca-blog">
		<div class="container" >
			<?php do_action( 'ca_before_single_post_content' ); ?>
			<div class="row" id="blog-list"> 
			<?php do_action( 'ca_render_blogs'); ?>
			</div>
		</div>
	</div>
<?php

get_footer();
