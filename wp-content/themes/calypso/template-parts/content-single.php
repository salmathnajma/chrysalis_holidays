<?php
/**
 * The default template for displaying single post
 *
 * @package calypso
 */


$sidebar_layout  = get_theme_mod( 'ca_blog_sidebar_layout', 'right-sidebar' ) ;
$wrap_class   = apply_filters( 'ca_filter_index_content_classes', 'col-md-8 blog-posts-wrap' );
?>
<article id="post-<?php the_ID(); ?>" class="section section-text">
	<?php do_action( 'ca_before_single_post_content' ); ?>
	<div class="row">
		<?php
		if ( $sidebar_layout === 'left-sidebar' )
            get_sidebar();
		?>
		<div class="<?php echo esc_attr( $wrap_class ); ?>" data-layout="<?php echo esc_attr( $sidebar_layout ); ?>">
			<div class="single-post-wrap entry-content">
				<?php
					
					

					the_content();

					ca_wp_link_pages(
						array(
							'before'      => '<div class="text-center"> <ul class="nav pagination pagination-primary">',
							'after'       => '</ul> </div>',
							'link_before' => '<li>',
							'link_after'  => '</li>',
						)
					);
			
				?>
			
			</div>
			
			<?php
			do_action( 'ca_post_nav' );
			do_action( 'ca_after_single_post_article' );
			echo '</div>';
			if ( $sidebar_layout === 'right-sidebar' ) 
			    get_sidebar();


			?>
		</div>
		<?php do_action('ca_related_posts') ?>
	
</article>

