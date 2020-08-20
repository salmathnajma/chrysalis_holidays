<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package calypso
 */

$sidebar_layout  = get_theme_mod( 'ca_page_layout', 'full-width' ) ;
$wrap_class   = apply_filters( 'ca_filter_page_content_classes', 'col-md-12 blog-posts-wrap' );
?>

<article id="post-<?php the_ID(); ?>" class="section section-text">
<?php do_action( 'ca_before_single_post_content' ); ?>
	<div class="row">
		
		<?php
		if ( $sidebar_layout === 'left-sidebar' )
			do_action( 'ca_page_sidebar' );
		?>
		<div class="<?php echo esc_attr( $wrap_class ); ?>">
			<?php
			

			the_content();

			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>
		</div>
		<?php
		if ( $sidebar_layout === 'right-sidebar' ) 
			do_action( 'ca_page_sidebar' );
		?>
	</div>
</article>
