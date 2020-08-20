<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package calypso
 */

get_header();
do_action( 'ca_before_page_wrapper' );
?>
<div class=" <?php echo ca_layout(); ?>">
	<main id="main" class="site-main content-area">
		<div class="ca-blog section section-text">
			<div class="container">
				<?php do_action( 'ca_before_single_post_content' ); ?>
				<div class="row" id="blog-list">
					<?php do_action( 'ca_render_blogs'); ?>
				</div>
			</div>
		</div><!-- .ca-blogs -->
	</main><!-- #main -->
</div><!-- .main -->

<?php

get_footer();
