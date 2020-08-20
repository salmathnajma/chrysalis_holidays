<?php
/**
 * class for managing the main layout of the theme post / page
 *
 * @package calypso
 */

class Ca_Layout_Manager{
    public function __construct() {

		add_filter( 'get_the_archive_title', array( $this, 'filter_archive_title' ) );

		add_action( 'ca_before_page_wrapper', array( $this, 'post_page_header' ));
		
		add_action( 'ca_before_single_post_content', array( $this, 'post_page_before_content' ) );

	}
	
	/**
	 * Remove "Category:", "Tag:", "Author:" from the archive title.
	 */
	public function filter_archive_title( $title ) {
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			$title = '<span class="vcard">' . get_the_author() . '</span>';
		} elseif ( is_year() ) {
			$title = get_the_date( 'Y' );
		} elseif ( is_month() ) {
			$title = get_the_date( 'F Y' );
		} elseif ( is_day() ) {
			$title = get_the_date( 'F j, Y' );
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		}

		return $title;
	}

    public function post_page_header() {
		$layout = get_theme_mod( 'ca_header_layout', 'default' ) ;
		if ( 'classic-blog' === $layout ) {
			return;
		}
		$this->display_header( $layout );
    }
    
    public function display_header( $layout ) {
		echo '<div id="primary" class="page-header header-small" data-parallax="active" >';
		
		if ( $layout == 'default' ) 
			echo $this->render_header( $layout );
		

		$this->render_header_background();
		echo '</div>';
    }
    
    public function render_header( $layout ) {
		if ( is_attachment() ) {
			$layout = 'default';
		}
	
		$header_output = '';
		if ( 'default' !== $layout ) {
			$header_output .= '	<div class="row"><div class="col-12">';
		}
		$header_output .= $this->header_content( $layout );
		$header_output .= $this->render_post_meta( $layout );

		if ( 'default' !== $layout ) {
			$header_output .= '</div></div>';
		}
		if ( 'default' === $layout ) {
			$header_output = '<div class="container"><div class="row"><div class="col-md-12 text-center">' . $header_output . '</div></div></div>';
		}

		return $header_output;
    }
    
    
    
    /**
	 * Header content display.
	 *
	 */
	public function header_content( $header_layout ) {

		$title_class = 'ca-title';

		if ( 'default' !== $header_layout ) {
			$title_class .= ' title-in-content';
		}
		if ( is_404() ) {
			$header_content_output = '<h1 class="'.$title_class.'">' . esc_html( 'Oops! That page can&rsquo;t be found.' ) . '</h1>';

			return $header_content_output;
		}
		if ( is_archive() ) {
			$title                 = get_the_archive_title();
			
			$header_content_output = '';

			if ( ! empty( $title ) ) {
				$header_content_output .= '<h1 class="'.$title_class.'">' . $title . '</h1>';
			}

			$description = get_the_archive_description();
			if ( $description ) {
				$header_content_output .= '<h5 class="description">' . $description . '</h5>';
			}

			return $header_content_output;
		}
		if ( is_search() ) {
			$header_content_output = '<h1 class="' . esc_attr( $title_class ) . '">';
			/* translators: search result */
			$header_content_output .= sprintf( esc_html__( 'Search Results for: %s', 'calypso' ), get_search_query() );
			$header_content_output .= '</h1>';

			return $header_content_output;
		}

		$disabled_frontpage = get_theme_mod( 'disable_frontpage_sections', false );
		if ( is_front_page() && get_option( 'show_on_front' ) === 'page' && true === (bool) $disabled_frontpage ) {
			$header_content_output = '<h1 class="' . esc_attr( $title_class ) . '">';

			$header_content_output .= single_post_title( '', false );
			$header_content_output .= '</h1>';

			return $header_content_output;
		}

		if ( is_front_page() && get_option( 'show_on_front' ) === 'posts' ) {
			$header_content_output = '<h1 class="' . esc_attr( $title_class ) . '">';

			$header_content_output .= get_bloginfo( 'description' );
			$header_content_output .= '</h1>';

			return $header_content_output;
		}

		$entry_class = '';
		if ( ! is_page() ) {
			$entry_class = 'entry-title';
		}
		$header_content_output = '<h1 class="' . esc_attr( $title_class ) . ' ' . esc_attr( $entry_class ) . '">' . single_post_title( '', false ) . '</h1>';

		return $header_content_output;
    }
    
    /**
	 * Check if post meta should be displayed.
	 *
	 * @param string $header_layout the header layout.
	 */
	private function render_post_meta( $header_layout ) {
		if ( ! is_single() ) {
			return '';
		}

		if ( class_exists( 'WooCommerce' ) ) {
			if ( is_product() ) {
				return '';
			}
		}

		global $post;
		$post_meta_output = '';
		$author_id        = $post->post_author;
		$author_name      = get_the_author_meta( 'display_name', $author_id );
		$author_posts_url = get_author_posts_url( get_the_author_meta( 'ID', $author_id ) );

		if ( 'default' === $header_layout ) {
			$post_meta_output .= '<p class="author">';
		} else {
			$post_meta_output .= '<p class="author meta-in-content">';
		}
		$post_meta_output .=  get_the_excerpt();//changed
		// $post_meta_output .= 
		// 	sprintf(
		// 		/* translators: %1$s is Author name wrapped, %2$s is Date*/
		// 		esc_html__( 'Published by %1$s on %2$s', 'calypso' ),
		// 		/* translators: %1$s is Author name, %2$s is Author link*/
		// 		sprintf(
		// 			'<a href="%2$s" class="vcard author"><strong class="fn">%1$s</strong></a>',
		// 			esc_html( $author_name ),
		// 			esc_url( $author_posts_url )
		// 		),
		// 		$this->get_time_tags()
        //     );
		
		if ( 'default' === $header_layout ) {
			$post_meta_output .= '</p>';
		} else {
			$post_meta_output .= '</p>';
		}

		return $post_meta_output;
    }
    
    private function get_time_tags() {
		$time = '';

		$time .= '<time class="entry-date published" datetime="' . esc_attr( get_the_date( 'c' ) ) . '" content="' . esc_attr( get_the_date( 'Y-m-d' ) ) . '">';
		$time .= esc_html( get_the_time( get_option( 'date_format' ) ) );
		$time .= '</time>';
		if ( get_the_time( 'U' ) === get_the_modified_time( 'U' ) ) {
			return $time;
		}
		$time .= '<time class="updated ca-hidden" datetime="' . esc_attr( get_the_modified_date( 'c' ) ) . '">';
		$time .= esc_html( get_the_time( get_option( 'date_format' ) ) );
		$time .= '</time>';

		return $time;
    }
    
    public function add_image_in_content() {
			if ( class_exists( 'WooCommerce' ) && ( is_product() || is_cart() || is_checkout() ) ) {
				return '';
			}
			$image_url = $this->get_page_background();
			if ( empty( $image_url ) ) {
				return '';
			}

			$image_id   = attachment_url_to_postid( $image_url );
			$image1_alt = '';
			if ( $image_id ) {
				$image1_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
			}

			$image_markup = '<img class="wp-post-image image-in-page" src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $image1_alt ) . '">';
			if ( class_exists( 'WooCommerce' ) && is_shop() ) {
				$image_markup = '<div class="col-md-12 image-in-page-wrapper">' . $image_markup . '</div>';
			}

			return $image_markup;
    }
    
    /**
	 *  Handle Pages and Posts Header image.
	 *  Single Product: Product Category Image > Header Image > Gradient
	 *  Product Category: Product Category Image > Header Image > Gradient
	 *  Shop Page: Shop Page Featured Image > Header Image > Gradient
	 *  Blog Page: Page Featured Image > Header Image > Gradient
	 *  Single Post: Featured Image > Gradient
	 */
	private function get_page_background() {
        
		$thumbnail = $this->get_post_page_background();
		if ( ! empty( $thumbnail ) ) {
			return esc_url( $thumbnail );
        }
       
        $thumbnail = get_theme_mod( 'ca_header_image');
        if ( ! empty( $thumbnail ) ) {
			return wp_get_attachment_url($thumbnail);
        }

		return false;

    }
    
    /**
	 * Get header background image for single page.
	 *
	 */
	private function get_post_page_background() {

		if ( class_exists( 'WooCommerce' ) && is_product() ) {
			return false;
		}

		if ( is_archive() ) {
			return false;
		}

		$pid = ca_get_current_page_id();
		if ( empty( $pid ) ) {
			return false;
		}

		// Get featured image.
		$thumb_tmp = get_the_post_thumbnail_url( $pid );
		if ( is_home() && 'page' === get_option( '`show_on_front`' ) ) {
			$page_for_posts_id = get_option( 'page_for_posts' );
			if ( ! empty( $page_for_posts_id ) ) {
				$thumb_tmp = get_the_post_thumbnail_url( $page_for_posts_id );
			}
		}

		return $thumb_tmp;
    }
    
    /**
	 * Render the header background div.
	 */
	private function render_header_background() {
        $background_image            = $this->get_page_background();
        $background_color = get_theme_mod( 'ca_header_bgcolor' );

		$header_filter_div = '<div class="header-filter';

		/* Header Image */
		if ( ! empty( $background_image ) ) {
			$header_filter_div .= '" style="background-image: url(' . esc_url( $background_image ) . ');"';
			/* Gradient Color */
		} elseif ( $background_color  ) {
			$header_filter_div .= ' header-filter-gradient" style="background: ' . esc_url( $background_color ) . ';"';
			/* Background Image */
		} else {
			$header_filter_div .= '"';
		}
		$header_filter_div .= '></div>';

		echo $header_filter_div;

	}

	/**
	 * Single post before content.
	 * This function display the title in page if layout is not default.
	 */
	public function post_page_before_content() {
		$layout = get_theme_mod( 'ca_header_layout', 'default' );
		if ( 'default' === $layout ) {
			return;
		}
		echo $this->render_header( $layout );
	}

}
new Ca_Layout_Manager();
?>