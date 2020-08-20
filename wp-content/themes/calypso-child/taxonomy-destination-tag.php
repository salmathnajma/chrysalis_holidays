
<?php get_header();
do_action( 'ca_before_page_wrapper' );
?>
<div class=" <?php echo ca_layout(); ?>">
	<main id="main" class="site-main content-area">
		<div class="ca-blog">
			<div class="container">
			<?php do_action( 'ca_before_single_post_content' ); ?>
				<div class="row" id="blog-list">
					<?php //do_action( 'ca_render_blogs'); ?>
					<?php dest_tag_archive();?>
				</div>
			</div>
		</div><!-- .ca-blogs -->
	</main><!-- #main -->
</div><!-- .main -->

<?php get_footer();?>
