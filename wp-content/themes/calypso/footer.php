<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package calypso
 */

?>

	</div><!-- #content -->

	<footer id="colophon" class="footer footer-black footer-big site-footer">
		<div class="footer-content">
		<?php do_action( 'ca_do_footer' ); ?>
		</div>
		<div class="site-info">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<a href="<?php echo esc_url( __( 'https://www.conditionsapply.net/', 'calypso' ) ); ?>">
							<?php
							/* translators: %s: CMS name, i.e. WordPress. */
							printf( esc_html__( 'Powered by %s', 'calypso' ), '*conditionsapply' );
							?>
						</a>
					</div>
				</div>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->
echo get_template_directory()

<?php wp_footer();
echo get_template_directory_uri(); ?>

</body>
</html>
