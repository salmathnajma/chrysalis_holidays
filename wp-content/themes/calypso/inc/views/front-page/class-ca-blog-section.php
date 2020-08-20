<?php
/**
 * class for home page blog section view
 *
 * @package calypso
 */

class Ca_Blog_Section{
    public function __construct() {
		$this->hook_section();
	}

	private function hook_section() {
        $section_priority = apply_filters( 'ca_blog_priority', 30 );
        add_action( 'home_sections', array( $this, 'render_section' ), absint( $section_priority ) );
	}

	public function render_section() {
		$hide_section = get_theme_mod( 'ca_hide_blog_section', false );
        $section_style = '';
		if ( (bool) $hide_section === true ) {
            if ( is_customize_preview() ) {
                $section_style .= 'display: none;';
            } else {
                return;
            }
        }

        $title = get_theme_mod( 'ca_blog_title','Blog'  );
        $description = get_theme_mod( 'ca_blog_description');
       
        $section_style = 'style="' . $section_style . '"';
        do_action('before_blog');
		?>
        <section class="home-section ca-blog" id="blog" data-sorder="ca_blog" <?php echo wp_kses_post( $section_style ); ?>>
            <div class="container">
                <div class="row d-block col-12 text-center ca-blog-title-section">
                    <?php 
                    if ( ! empty( $title ) || is_customize_preview() ) {
                        echo '<h2 class="ca-title">' . wp_kses_post( $title ) . '</h2>';
                    }
                    // if ( ! empty( $description ) || is_customize_preview() ) {
                    //     echo '<p class="ca-description ca-title-description" >' .  $description  . '</p>';
                    // }
                    // ?>
                </div>
                <?php $this->blog_content(); ?>
            </div>
        </section>
		<?php
        do_action('after_blog');
	}

	public function blog_content() {

        global $columns_class, $class, $allowed_html;

       
        $limit = get_theme_mod( 'ca_blog_number', 2 );
        $columns = get_theme_mod( 'ca_blog_column', 2 );
        $count = 0;
		$newrow = true;
		$columns_val = ca_get_column_layout();
        if ( in_array( $columns, $columns_val ) ) {
			$columns_class = array_search( $columns, $columns_val );
        }
        
  
        
        $args                   = array(
            'ignore_sticky_posts' => true,
            'post_type' => 'post',
        );
        $args['posts_per_page'] = ! empty( $limit ) ? absint( $limit ) : 2;

        $loop = new WP_Query( $args );

        global $posts_count;
		$posts_count = 0;

        $allowed_html = array(
			'br'     => array(),
			'em'     => array(),
			'strong' => array(),
			'i'      => array(
				'class' => array(),
			),
			'span'   => array(),
        );
        
        if ( ! $loop->have_posts() ) 
            return;
        
        while ( $loop->have_posts() ) :
            $loop->the_post();
            
            if($posts_count == 0)
            
                echo '<div class="row">';
            if ( !is_singular() &&  $posts_count != 0) {
                $count ++;
                $class = '';
                
                if ( $newrow ) 
                    $newrow = false;
                
                if ( $count % $columns == 0 ) {
                    $newrow = true;
                    
                    echo '</div><div class="row">';
                }
            }
            $posts_count++;

            get_template_part( 'template-parts/content', 'home-post' );

		endwhile;
		echo '</div>';
	}

	

}

new Ca_Blog_Section();