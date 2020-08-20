<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package calypso-child
 */

get_header();
do_action( 'ca_before_page_wrapper' );
?>

<div class=" <?php echo ca_layout(); ?>">
	<main id="main" class="site-main">
		<?php
		$class_to_add = '';
		if ( class_exists( 'WooCommerce' ) && ! is_cart() ) {
			$class_to_add = 'blog-post-wrapper';
		}
		?>
		<div class="blog-post <?php esc_attr( $class_to_add ); ?>">
			<div class="container">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content', 'page' );
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>
			</div>
		</div>
	</main><!-- #main -->
</div><!-- #primary -->

<?php

get_footer();
