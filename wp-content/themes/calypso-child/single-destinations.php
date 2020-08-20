<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package calypso
 */

get_header();

do_action( 'ca_before_page_wrapper' );
?>
<!-- <script src="wp-content/plugins/gravityforms/js/jquery.min.js"></script>
<script src="wp-content/plugins/gravityforms/js/jquery.json.min.js"></script>
<script src="wp-content/plugins/gravityforms/js/jquery.json-1.3.min.js"></script> -->
<div class="<?php echo ca_layout(); ?>">
    <div class="blog-post blog-post-wrapper ">
        <div class="container single_destination mt-5">
        
            <?php
			if ( have_posts() ) :
				while ( have_posts() ) :
          the_post();
		   get_template_part( 'template-parts/content', 'single' );
					
        endwhile;
      else :
            get_template_part( 'template-parts/content', 'none' );
      endif;
?>
<!-- ---------display comment_form------------ -->
<?php
if ( post_password_required() ) {
	return;
}

?>
<div id="comments" class="section section-comments">
	<div class="row">
		<div class="col-md-12">
			<div class="media-body comment_lists">
				
				<?php comment_form( custom_comments_template() ); ?>
				<?php if ( ! comments_open() && get_comments_number() ) : ?>
					<?php if ( is_single() ) : ?>
						<h4 class="no-comments ca-title text-center"><?php esc_html_e( 'Comments are closed.', 'calypso' ); ?></h4>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php

     cats_related_post(); //similar packages
?>
        </div>
    </div>
</div>
<?php
get_footer();