<?php
/**
 * class for managing the blog layout of the theme
 *
 * @package calypso
 */

class Ca_Blog_Layout{
    public function __construct() {
			
			add_filter('excerpt_more',array( $this, 'filter_excerpt_more'));
			
			add_action( 'ca_render_blogs', array( $this, 'render_blog_wrapper' ) );
      add_action( 'ca_blog_post_template_part', array( $this, 'render' ), 2 );
    }

	public function render_blog_wrapper(  ) {
		$sidebar_layout  = get_theme_mod( 'ca_page_layout', 'full-width' ) ;
		$blog_layout = get_theme_mod( 'ca_blog_layout', 'blog_normal_layout' );

		if($blog_layout === 'blog_card_layout'){
			$wrap_class = '';
			$wrap_posts = '';
		}else{
			$wrap_class = apply_filters( 'ca_filter_page_content_classes', 'col-md-12 blog-posts-wrap' );
			$wrap_posts = 'flex-row';
		}
	
		if ( $sidebar_layout === 'left-sidebar' && $blog_layout != 'blog_card_layout')
			get_sidebar();

		if($wrap_class)
			echo '<div class="'.esc_attr( $wrap_class ).'">';
			
		$counter = 0;
			if ( have_posts() ) {
				if($wrap_posts)
					echo '<div class="' . esc_attr( $wrap_posts ) . '">';
				while ( have_posts() ) {
					the_post();
					$counter ++;

					if ( ( $blog_layout === 'blog_alternative_layout' ) && ( $counter % 2 == 0 ) ) {
						get_template_part( 'template-parts/content', 'alternative' );
					}elseif ( $blog_layout === 'blog_card_layout' ){
						get_template_part( 'template-parts/content', 'card' );
					} else {
						get_template_part( 'template-parts/content' );
					}

				}
				if($wrap_posts)
					echo '</div>';
				the_posts_pagination();
			} else {
				get_template_part( 'template-parts/content', 'none' );
			}
		if($wrap_class)
			echo '</div>';
	
		if ( $sidebar_layout === 'right-sidebar' && $blog_layout != 'blog_card_layout') 
			get_sidebar();
		
	}


    public function render( $layout ) {
		
		$pid           = get_the_ID();
		$article_class = $this->get_article_class( $layout );
		$wrapper_class = $this->get_wrapper_class( $layout );
		$row_class     = 'row ';
		if ( $layout === 'alternative' ) {
			$row_class = 'row alternative-blog-row ';
		}elseif ( $layout === 'card' ) {
			$row_class = 'card card-plain card-blog';
		}

		$settings = array(
			'pid'           => $pid,
			'article_class' => $article_class,
			'wrapper_class' => $wrapper_class,
			'row_class'     => $row_class,
			'layout'        => $layout,
		);
		
		echo $this->get_article( $settings );
    }

    /**
	 * Get an article.
	 *
	 * @param array $args Article arguments.
	 */
	public function get_article( $args = array() ) {

		$article_template = '';

		$article_template .= '<article 
		id="post-' . esc_attr( $args['pid'] ) . '" 
		class="' . join( ' ', get_post_class( $args['article_class'], $args['pid'] ) ) . '">';
		$article_template .= '<div class="' . esc_attr( $args['row_class'] ) . '">';
		if ( $args['layout'] === 'card' ) {
			$article_template .= '<a class="display:block;" href="' . esc_url( get_the_permalink() ) . '" title="' . the_title_attribute(
				array(
					'echo' => false,
				)
			) . '">';
		}

		if ( $args['layout'] === 'default' || $args['layout'] === 'card' ) {
			$article_template .= $this->render_post_thumbnail($args['layout']);
        }
        
        $article_template .= '<div class= "' . esc_attr( $args['wrapper_class'] ) . '">';
        $article_template .= $this->render_post_body($args['layout']);
        $article_template .= '</div>';

		if ( $args['layout'] === 'alternative' ) {
			$article_template .= $this->render_post_thumbnail();
		}
		if ( $args['layout'] === 'card' )
			$article_template .= '</a>';
		$article_template .= '</div>';
		$article_template .= '</article>';

		return $article_template;
    }
    
    public function render_post_thumbnail( $type = 'default' ) {
			global $post;
			if ( ! $this->is_valid_layout_type( $type ) )
				return '';
		
			if ( ! has_post_thumbnail() ) 
				return '';
			


			$post_thumbnail_content = '';
			$size                   = 'ca-post-thumb';

			if($type == 'default' || $type == 'alternative'){
				$wrap_class = 'col-ms-5 col-sm-5';
			}else{
				$wrap_class = '';
			}
			
			if($wrap_class)
				$post_thumbnail_content .= '<div class="' . esc_attr( $wrap_class ) . '">';

			$post_thumbnail_content .= '<div class="card-image">';
			if($type != 'card'){
				$post_thumbnail_content .= '<a href="' . esc_url( get_the_permalink() ) . '" title="' . the_title_attribute(
					array(
						'echo' => false,
					)
				) . '">';
			}
			
			$post_thumbnail_content .= get_the_post_thumbnail( null, $size );
			if($type != 'card')
				$post_thumbnail_content .= '</a>';
			$post_thumbnail_content .= '</div>';

			if($wrap_class)
				$post_thumbnail_content .= '</div>';

			return $post_thumbnail_content;
    }
    
    /**
	 * Render post body.
	 *
	 * @param string $type the type of post.
	 */
	public function render_post_body( $type = 'default' ) {
		if ( ! $this->is_valid_layout_type( $type ) ) {
			return '';
		}
		$post_body_content = '';

		$post_body_content .= '<p class="category text-info">';
		$post_body_content .= ca_category();
		$post_body_content .= '</p>';
		
		if($type == 'card'){
			$post_body_content .= '<h2 class="card-title entry-title"><a href="'.get_permalink().'">'. get_the_title(). '</a></h2>';// change=apply title link
		}else{
			$post_body_content .= the_title(
				sprintf(
					'<h2 class="card-title entry-title"><a href="%s" title="%s" rel="bookmark">',
					esc_url( get_permalink() ),
					the_title_attribute(
						array(
							'echo' => false,
						)
					)
				),
				'</a></h2>',
				false
			);
		}
		

		
		$post_body_content .= $this->render_post_meta();
       
		$post_body_content .= '<div class="card-description entry-summary ">';
		$post_body_content .= $this->get_ca_excerpt( $type );
		$post_body_content .= $this->get_postlink();
		$post_body_content .= '</div>';

		$post_body_content = apply_filters('post_list_content', $post_body_content);
		return $post_body_content;
    }

    /**
	 * Render post meta.
	 */
	public function render_post_meta() {
		$post_meta_content  = '';
		$post_meta_content .= '<div class="posted-by vcard author post-meta">';
		$post_meta_content .= sprintf(
            /* translators: %1$s is Author name wrapped, %2$s is Time */
            esc_html__( 'By %1$s, %2$s', 'calypso' ),
            sprintf(
                /* translators: %1$s is Author name, %2$s is author link */
                '<a href="%2$s" title="%1$s" class="url">%1$s</a>',
                esc_html( get_the_author() ),
                esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) )
            ),
            sprintf(
                /* translators: %1$s is Time since post, %2$s is author Close tag */
                esc_html__( '%1$s ago %2$s', 'calypso' ),
                sprintf(
                    /* translators: %1$s is Time since, %2$s is Link to post */
                    '<a href="%2$s">%1$s',
                    $this->get_time_tags(),
                    esc_url( get_permalink() )
                ),
                '</a>'
            )
        );
		$post_meta_content .= '</div>';

		return $post_meta_content;
    }
    
    /**
	 * Get <time> tags.
	 *
	 * @return string
	 */
	public function get_time_tags() {
		$time = '';

		$time .= '<time class="entry-date published" datetime="' . esc_attr( get_the_date( 'c' ) ) . '" content="' . esc_attr( get_the_date( 'Y-m-d' ) ) . '">';
		$time .= esc_html( human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
		$time .= '</time>';
		if ( get_the_time( 'U' ) === get_the_modified_time( 'U' ) ) {
			return $time;
		}
		$time .= '<time class="updated ca-hidden" datetime="' . esc_attr( get_the_modified_date( 'c' ) ) . '">';
		$time .= esc_html( human_time_diff( get_the_modified_date( 'U' ), current_time( 'timestamp' ) ) );
		$time .= '</time>';

		return $time;
	}
    
    /**
	 * Get post excerpt.
	 *
	 */
	public function get_ca_excerpt( $type ) {
        global $post;
        
        /**
		 * Check for excerpt
		 */
		if ( has_excerpt( $post->ID ) ) {
				return apply_filters( 'the_excerpt', get_the_excerpt() );
		}

		$content = $this->get_post_content();

	
		/**
		 * Check for more tag
		 */
		$more = strpos( $post->post_content, '<!--more' );
		if ( ! empty( $more ) ) {
			return  $content ;
		}

		return apply_filters( 'the_excerpt', get_the_excerpt() );
	}

	function filter_excerpt_more($more) {
		remove_filter('excerpt_more', 'new_excerpt_more'); 
		return '';
	}

	public function get_postlink(){
		global $post;
		$content = '<div class="post-bottom">';
		$content .= '<a class="more-link" href="'.get_permalink($post->ID).'">';
		$content .= '<span>Read More</span>';
		$content .= '</a>';
		$content .= '</div>';

		return $content;
	}

    /**
	 * Get post content.
	 */
	public function get_post_content() {
		$content = get_the_content();
		$content = apply_filters( 'the_content', $content );
		$content = strip_shortcodes( $content );

		return $content;
	}
    /**
	 * Get article class names.
	 *
	 * @return string
	 */
	public function get_article_class( $layout ) {
		$classes = '';
		switch ( $layout ) {
			case 'default':
			case 'alternative':
				$classes  = 'card card-blog';
				$classes .= ( is_sticky() && is_home() && ! is_paged() ? ' card-raised' : ' card-plain' );
				break;
			case 'card':
				$classes  = 'col-12 col-lg-4 col-md-4 col-sm-12';
				$classes .= ( is_sticky() && is_home() && ! is_paged() ? ' card-raised' : ' card-plain' );
				break;
		}

		return $classes;
    }
    
    /**
	 * Wrapper classes for alternative layout
	 */
	private function get_wrapper_class( $layout ) {
		$classes = '';
		switch ( $layout ) {
			case 'default':
			case 'alternative':
				$classes = has_post_thumbnail() ? 'col-ms-7 col-sm-7' : 'col-sm-12';
				break;
			case 'card':
				$classes = 'content';
				break;
		}

		return $classes;
    }
    
    /**
	 * Utility to check if layout is allowed.
	 *
	 * @param string $type the type of layout to check.
	 *
	 * @return bool
	 */
	private function is_valid_layout_type( $type ) {
		$allowed_layouts = array(
			'default',
			'alternative',
			'card'
		);
		if ( in_array( $type, $allowed_layouts ) ) {
			return true;
		}

		return false;
	}
}
new Ca_Blog_Layout();