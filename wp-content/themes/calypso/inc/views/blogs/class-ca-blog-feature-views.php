<?php
/**
 * class for managing extra views for blogs
 *
 * @package calypso
 */

class Ca_Blog_Feature_Views{

    public function __construct() {
		add_action( 'ca_after_single_post_article', array( $this, 'post_after_article' ),10 );
		add_action( 'ca_related_posts', array( $this, 'related_posts' ),20 );
		add_action( 'ca_post_nav', array( $this, 'render_ca_post_nav' ));
	    add_action( 'ca_blog_social_icons', array( $this, 'social_icons' ) );
		add_action( 'ca_do_header', array( $this, 'hidden_sidebars' ) );
	}

    public function post_after_article() {
		global $post;
		$categories = get_the_category( $post->ID );
		?>

		<div class="section section-blog-info">
			<div class="row">
				<?php
					$hide_socials = get_theme_mod( 'ca_hide_social_icon', false );
					if($hide_socials){
						$class= 'col-12';
					}else{
						$class= 'col-md-6';
					}
				?>
				<div class="<?php echo $class; ?>">
					<div class="entry-categories"><?php esc_html_e( 'Categories:', 'calypso' ); ?>
						<?php
						foreach ( $categories as $category ) {
							echo '<span class="label label-primary"><a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a></span>';
						}
						?>
					</div>
					<?php the_tags( '<div class="entry-tags">' . esc_html__( 'Tags: ', 'calypso' ) . '<span class="entry-tag">', '</span><span class="entry-tag">', '</span></div>' ); ?>
				</div>
				<?php do_action( 'ca_blog_social_icons' ); ?>
			</div>
			<?php
			$this->render_author_box();
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>
		</div>
		<?php
	}

	public function render_ca_post_nav() {
		$hide_posts = get_theme_mod( 'ca_hide_post_nav', false );
		if ( (bool) $hide_posts === true ) {
			return;
        }
		?>
		<div id="post-navigation" class ="row">
			<div class="col-6 "><span class="link"><?php previous_post_link('< %link'); ?></span></div>
			<div class="col-6 text-right"><span class="link"><?php next_post_link('%link >'); ?></span></div>
		</div>
		<?php
	}

	
	/**
	 * Related posts for single view.
	*/
	public function related_posts() {
		global $post;

		$hide_posts = get_theme_mod( 'ca_hide_related_posts', false );
		if ( (bool) $hide_posts === true ) {
			return;
        }

		$blog_layout = new Ca_Blog_Layout();
		$cats         = wp_get_object_terms(
			$post->ID,
			'category',
			array(
				'fields' => 'ids',
			)
		);
		$args         = array(
			'posts_per_page'      => 3,
			'cat'                 => $cats,
			'orderby'             => 'date',
			'ignore_sticky_posts' => true,
			'post__not_in'        => array( $post->ID ),
		);
		$allowed_html = array(
			'br'     => array(),
			'em'     => array(),
			'strong' => array(),
			'i'      => array(
				'class' => array(),
			),
			'span'   => array(),
		);

		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) :
			?>
			<div class="section related-posts ca-blog">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<h2 class="ca-title text-center related-post-title"><?php echo get_theme_mod( 'ca_related_posts_title', 'Related Posts' ); ?></h2>
							<div class="row">
								<?php
								while ( $loop->have_posts() ) :
									$loop->the_post();
									?>
									<div class="col-md-4">
										<div class="card card-plain card-blog">
										<a class="display:block;" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
										<?php if ( has_post_thumbnail() ) : ?>
											<div class="card-image">
													<?php the_post_thumbnail( 'ca-post-thumb' ); ?>
											</div>
										<?php endif; ?>
											<div class="content">
												<?php echo $blog_layout->render_post_body('card'); ?>
											</div>
										</a>
										</div>
									</div>
								<?php endwhile; ?>
								<?php wp_reset_postdata(); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		endif;
	}
    
    /**
	 * Social sharing icons for single view.
	 */
	public function social_icons() {
       
		$hide_socials = get_theme_mod( 'ca_hide_social_icon', false );
		if ( (bool) $hide_socials === true ) {
			return;
        }
    

		$post_link  = esc_url( get_the_permalink() );
		$post_title = get_the_title();

		$facebook_url =
			esc_url(
				add_query_arg(
					array(
						'u' => $post_link,
					),
					'https://www.facebook.com/sharer/sharer.php'
				)
			);

		$twitter_url =
			esc_url(
				add_query_arg(
					array(
						'status' => wp_strip_all_tags( $post_title ) . ' - ' . esc_url( $post_link ),
					),
					'https://twitter.com/home'
				)
			);

		$google_url =
			esc_url(
				add_query_arg(
					array(
						'url' => $post_link,
					),
					'https://plus.google.com/share'
				)
			);

		$social_links = '
        <div class="col-md-6 social-share-wrapper">
            <div class="entry-social">
                <a target="_blank" rel="tooltip"
                   data-original-title="' . esc_attr__( 'Share on Facebook', 'calypso' ) . '"
                   class="btn btn-just-icon btn-round btn-facebook"
                   href="' . $facebook_url . '">
                   <i class="fa fa-facebook"></i>
                </a>
                
                <a target="_blank" rel="tooltip"
                   data-original-title="' . esc_attr__( 'Share on Twitter', 'calypso' ) . '"
                   class="btn btn-just-icon btn-round btn-twitter"
                   href="' . $twitter_url . '">
                   <i class="fa fa-twitter"></i>
                </a>
                
                <a target="_blank" rel="tooltip"
                   data-original-title=" ' . esc_attr__( 'Share on Google+', 'calypso' ) . '"
                   class="btn btn-just-icon btn-round btn-google"
                   href="' . $google_url . '">
                   <i class="fa fa-google"></i>
               </a>
            </div>
		</div>';
		echo $social_links;
    }
    
    public function render_author_box() {
		$author_description = get_the_author_meta( 'description' );
		if ( empty( $author_description ) ) {
			return;
		}
		?>
		<div class="card card-profile card-plain">
			<div class="row">
				<div class="col-md-2">
					<div class="card-avatar">
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
								title="<?php echo esc_attr( get_the_author() ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 100 ); ?></a>
					</div>
				</div>
				<div class="col-md-10">
					<h4 class="card-title"><?php the_author(); ?></h4>
					<p class="description"><?php the_author_meta( 'description' ); ?></p>
				</div>
			</div>
		</div>
		<?php
	}
	/**
	 * Display the hidden sidebars to enable the customizer panels.
	 */
	public function hidden_sidebars() {
		echo '<div style="display: none">';
		if ( is_customize_preview() ) {
			dynamic_sidebar( 'subscribe-widgets' );
			dynamic_sidebar( 'welcome-widgets' );
			dynamic_sidebar( 'contact-widgets' );
		}
		echo '</div>';
	}
}
new Ca_Blog_Feature_Views();