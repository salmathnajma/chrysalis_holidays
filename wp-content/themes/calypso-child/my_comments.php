<!-- reviews listing page -->
<?php
//echo do_shortcode( '[site_reviews]' );
//echo do_shortcode( '[site_reviews_form]' );
if ( post_password_required() ) {
	return;
}
?>
<div class="media-area">
				<h3 class="ca-title text-center my-3">
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
									'%1$s Reviews',
									'%1$s Reviews',
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
				//echo do_shortcode( '[site_reviews]' );
				wp_list_comments( 'type=comment&callback=custom_comments_list' );
				wp_list_comments( 'type=pings&callback=custom_comments_list' );

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