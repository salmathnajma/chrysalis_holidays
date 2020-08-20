<?php

/**
 * method to return main page layout
 */
function ca_layout() {
    return "main";
}

/**
 * helper to check if URL is external
 *
 * @param string $url Url to check.
 *
 * @return string
 */
function ca_is_external_url( $url ) {
    $link_url = parse_url( $url );
    $home_url = parse_url( home_url() );

    if ( ! empty( $link_url['host'] ) ) {
        if ( $link_url['host'] !== $home_url['host'] ) 
            return ' target="_blank"';
        
    } else {
        return '';
    }
}

function ca_get_column_layout() {
    $columns_val = array (	
        'col-12' => 1,
        'col-12 col-lg-6 col-md-6 col-sm-12' => 2,
        'col-12 col-lg-4 col-md-4 col-sm-12' => 3,
        'col-12 col-lg-3 col-md-6 col-sm-12' => 4									
    );

    return $columns_val;
}

function ca_category( $rel_tag = true ) {
/*
    $ca_disable_categories = get_theme_mod( 'ca_disable_categories', 'one' );

    if ( ! $ca_disable_categories || $ca_disable_categories === 'none' ) {
        return '';
    }
*/
    $filtered_categories = '';
    $categories = get_the_category();

    if ( ! empty( $categories ) ) {

        foreach ( $categories as $category ) {
            /* translators: %s is Category name */
            $filtered_categories .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( __( 'View all posts in %s', 'calypso' ), $category->name ) ) . '" ' . ( $rel_tag === true ? ' rel="tag"' : '' ) . '>' . esc_html( $category->name ) . '</a> ';
            /*
            if ( $ca_disable_categories === 'one' ) {
                break;
            }*/
        }
    }

    return $filtered_categories;
}

function ca_display_customizer_shortcut( $class_name, $is_section_toggle = false ){
    if ( ! is_customize_preview() ) {
        return;
    }
    $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z"></path>
        </svg>';
    if ( $is_section_toggle ) {
        $icon = '<i class="fa fa-eye"></i>';
    }
    echo
        '<span class="ca-hide-section-shortcut customize-partial-edit-shortcut customize-partial-edit-shortcut-' . esc_attr( $class_name ) . '">
    <button class="customize-partial-edit-shortcut-button">
        ' . $icon . '
    </button>
</span>';
}

/**
 * This function returns the current page id.
 *
 * @return bool|false|int|mixed
 */
function ca_get_current_page_id() {
	if ( is_home() ) {
		if ( 'page' === get_option( 'show_on_front' ) ) {
			return get_option( 'page_for_posts' );
		}

		return false;
	}
	if ( is_search() ) {
		return false;
	}
	if ( is_post_type_archive( array( 'post', 'page' ) ) ) {
		return false;
	}

	return ca_get_woo_page_id() !== false ? ca_get_woo_page_id() : get_the_ID();
}
/**
 * This function returns the page id of a WooCommerce page.
 *
 * @return bool|mixed
 */
function ca_get_woo_page_id() {
	if ( ! ca_check_woocommerce() ) {
		return false;
	}
	if ( is_shop() ) {
		return get_option( 'woocommerce_shop_page_id' );
	}
	if ( is_cart() ) {
		return get_option( 'woocommerce_cart_page_id' );
	}
	if ( is_checkout() ) {
		return get_option( 'woocommerce_checkout_page_id' );
	}

	return false;
}

    /**
	 * Display a custom wp_link_pages for singular view.
	 */
	function ca_wp_link_pages( $args = array() ) {
		$defaults = array(
			'before'           => '<ul class="nav pagination pagination-primary">',
			'after'            => '</ul>',
			'link_before'      => '',
			'link_after'       => '',
			'next_or_number'   => 'number',
			'nextpagelink'     => esc_html__( 'Next page', 'calypso'),
			'previouspagelink' => esc_html__( 'Previous page', 'calypso' ),
			'pagelink'         => '%',
			'echo'             => 1,
		);

		$r = wp_parse_args( $args, $defaults );
		$r = apply_filters( 'wp_link_pages_args', $r );

		global $page, $numpages, $multipage, $more;

		$output = '';
		if ( $multipage ) {
			if ( 'number' == $r['next_or_number'] ) {
				$output .= $r['before'];
				for ( $i = 1; $i < ( $numpages + 1 ); $i = $i + 1 ) {
					$j       = str_replace( '%', $i, $r['pagelink'] );
					$output .= ' ';
					$output .= $r['link_before'];
					if ( $i != $page || ( ( ! $more ) && ( $page == 1 ) ) ) {
						$output .= _wp_link_page( $i );
					} else {
						$output .= '<span class="page-numbers current">';
					}
					$output .= $j;
					if ( $i != $page || ( ( ! $more ) && ( $page == 1 ) ) ) {
						$output .= '</a>';
					} else {
						$output .= '</span>';
					}
					$output .= $r['link_after'];
				}
				$output .= $r['after'];
			} else {
				if ( $more ) {
					$output .= $r['before'];
					$i       = $page - 1;
					if ( $i && $more ) {
						$output .= _wp_link_page( $i );
						$output .= $r['link_before'] . $r['previouspagelink'] . $r['link_after'] . '</a>';
					}
					$i = $page + 1;
					if ( $i <= $numpages && $more ) {
						$output .= _wp_link_page( $i );
						$output .= $r['link_before'] . $r['nextpagelink'] . $r['link_after'] . '</a>';
					}
					$output .= $r['after'];
				}
			}// End if().
		}// End if().

		if ( $r['echo'] ) {
			echo wp_kses(
				$output,
				array(
					'div'  => array(
						'class' => array(),
						'id'    => array(),
					),
					'ul'   => array(
						'class' => array(),
					),
					'a'    => array(
						'href' => array(),
					),
					'li'   => array(),
					'span' => array(
						'class' => array(),
					),
				)
			);
		}

		return $output;
    }
    
/**
	 * Custom list of comments for the theme.
		 */
	function ca_comments_list( $comment, $args, $depth ) {
		?>
		<div  <?php comment_class( empty( $args['has_children'] ) ? 'media' : 'parent media' ); ?>
				id="comment-<?php comment_ID(); ?>">
			<?php if ( $args['type'] != 'pings' ) : ?>
				<a class="pull-left" href="<?php echo esc_url( get_comment_author_url( $comment ) ); ?> ">
					<div class="comment-author avatar vcard">
						<?php
						if ( $args['avatar_size'] != 0 ) {
							echo get_avatar( $comment, 64 );
						}
						?>
					</div>
				</a>
			<?php endif; ?>
			<div class="media-body">
				<h4 class="media-heading">
					<?php echo get_comment_author_link(); ?>
					<small>
						<?php
						printf(
							/* translators: %1$s is Date, %2$s is Time */
							esc_html__( '&#183; %1$s at %2$s', 'calypso' ),
							get_comment_date(),
							get_comment_time()
						);
						edit_comment_link( esc_html__( '(Edit)', 'calypso' ), '  ', '' );
						?>
					</small>
				</h4>
				<?php comment_text(); ?>
				<div class="media-footer">
					<?php
					echo get_comment_reply_link(
						array(
							'depth'      => $depth,
							'max_depth'  => $args['max_depth'],
							'reply_text' => sprintf( '<i class="fa fa-mail-reply"></i> %s', esc_html__( 'Reply', 'calypso' ) ),
						),
						$comment->comment_ID,
						$comment->comment_post_ID
					);
					?>
				</div>
			</div>
		</div>
		<?php
    }
    
    /**
	 * Custom list of comments for the theme.
	 */
	function ca_comments_template() {
		if ( is_user_logged_in() ) {
			$current_user = get_avatar( wp_get_current_user(), 64 );
		} else {
			$current_user = '<img src="' . get_template_directory_uri() . '/assets/images/placeholder.jpg" height="64" width="64"/>';
		}

		$args = array(
			'class_form'         => 'form media-body',
			'class_submit'       => 'btn btn-primary pull-right',
			'title_reply_before' => '<h3 class="ca-title text-center">',
			'title_reply_after'  => '</h3> <span class="pull-left author"> <div class="avatar">' . $current_user . '</div> </span>',
			'must_log_in'        => '<p class="must-log-in">' .
									sprintf(
										wp_kses(
											/* translators: %s is Link to login */
											__( 'You must be <a href="%s">logged in</a> to post a comment.', 'calypso' ),
											array(
												'a' => array(
													'href' => array(),
												),
											)
										),
										esc_url( wp_login_url( apply_filters( 'the_permalink', esc_url( get_permalink() ) ) ) )
									) . '</p>',
			'comment_field'      => '<div class="form-group label-floating is-empty"> <label class="control-label">' . esc_html__( 'What\'s on your mind?', 'calypso' ) . '</label><textarea id="comment" name="comment" class="form-control" rows="6" aria-required="true"></textarea><span class="ca-input"></span> </div>',
		);

		return $args;
	}

/**
 * Check if WooCommerce exists.
 *
 * @return bool
 */
function ca_check_woocommerce() {
	return class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() );
}

function full_video( $html ) {
  
	return '<div class="video-container">' . $html . '</div>';
  }
  add_filter( 'embed_oembed_html', 'full_video', 10, 3 );
  add_filter( 'video_embed_html', 'full_video' );
?>