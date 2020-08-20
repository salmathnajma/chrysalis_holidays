<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package calypso
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="section section-comments">
	<div class="row">
		<div class="col-md-12">
			<div class="media-area">
				<h3 class="ca-title text-center">
					<?php
					$comments_number = get_comments_number();
					if ( 0 == ! $comments_number ) {
						if ( 1 === $comments_number ) {
							/* translators: %s: post title */
							_x( 'One comment', 'comments title', 'calypso' );
						} else {
							printf(
								/* translators: 1: number of comments, 2: post title */
								_nx(
									'%1$s Comment',
									'%1$s Comments',
									$comments_number,
									'comments title',
									''
								),
								number_format_i18n( $comments_number )
							);
						}
					}
					?>
				</h3>
				<?php
				wp_list_comments( 'type=comment&callback=ca_comments_list' );
				wp_list_comments( 'type=pings&callback=ca_comments_list' );

				$pages = paginate_comments_links(
					array(
						'echo' => false,
						'type' => 'array',
					)
				);
				if ( is_array( $pages ) ) {
					echo '<div class="text-center"><ul class="nav pagination pagination-primary">';
					foreach ( $pages as $page ) {
						echo '<li>' . $page . '</li>';
					}
					echo '</ul></div>';
				}

				?>
			</div>
			<div class="media-body">
				<?php comment_form( ca_comments_template() ); ?>
				<?php if ( ! comments_open() && get_comments_number() ) : ?>
					<?php if ( is_single() ) : ?>
						<h4 class="no-comments ca-title text-center"><?php esc_html_e( 'Comments are closed.', 'calypso' ); ?></h4>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
