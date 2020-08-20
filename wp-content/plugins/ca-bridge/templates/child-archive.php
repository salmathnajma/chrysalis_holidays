<?php get_header(); ?>
<div class="container">
    <div class="row">	
		<?php ca_content_inside(); ?>
		
		<div class="col col-lg-12">
			<div class="hero-container page">
				<div class="text">
					<?php echo get_post_meta( $post->ID, 'lead-desc', true ); ?>
				</div>
				<div class="image">
				<?php echo hero_image(); ?>
				</div>
				<div class="clear"></div>
			</div>
		</div>

	</div>
    <main id="main">
        <div class="row">	
            <div class="col col-lg-12">
                <div class="template-content">
                    <?php
                        if ( have_posts() ) :
                            while ( have_posts() ) :
                                the_post();
                                the_content();
                            endwhile;
                        endif;
                    ?>
                </div>
                <?php echo ca_portfolio_header(''); ?>
            </div>
        </div>

        <div class="list isotope">

    <?php
    $bridge_options = get_option('bridge_option_name');

        if ( get_query_var( 'paged' ) ) {
            $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
        }
        if ( get_query_var( 'page' ) ) {
            $paged = ( get_query_var('page') ) ? get_query_var('page') : 1; 
        }
        
        $paged = intval( $paged );
        $posts_per_page = 10;
        $custom_post_type = CHILD_TYPE_SLUG;
        $view = CHILD_TYPE_NAME;

        $args = array(
            'posts_per_page' => $posts_per_page,
            'post_type' => $custom_post_type,
            'paged' => $paged
        );
        query_posts( $args );
        
        if ( have_posts() ) :
            $count = 0;
			$columns = 4;
			$newrow = true;
			$columns_val = array (	
									'col col-lg-12' => 1,
									'col col-lg-6 col-md-12' => 2,
									'col col-lg-4 col-md-6 col-sm-12' => 3,
									'col col-lg-3 col-md-6 col-sm-12' => 4									
								);
			$columns_class = 'col col-lg-3 col-md-6 col-sm-12';
            $class = '';

            if ($bridge_options['child_archive_col_width'] ) 
				$columns = (int) $bridge_options['child_archive_col_width'];
            
            if ( in_array( $columns, $columns_val ) ) {
				$columns_class = array_search( $columns, $columns_val );
			}

            $posts_count = 0;
            while ( have_posts() ) :
                the_post();

                $term_list = '';
                $terms =  get_the_terms( $post->ID, CHILD_CATEGORY_SLUG );
                if ( is_array($terms) ) {
                    foreach( $terms as $term ) {
                        $term_list .= urldecode( $term->slug ) . ' ';
                    }
                }
                $term_list .= 'isotope-item';

                if($posts_count == 0)
                    echo '<div class="row">';
                    
                if ($posts_count != 0) {
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
                ?>
                <div class="<?php echo $columns_class.' '.$term_list; ?> type-child">
                <article id="post-<?php the_ID(); ?>" <?php post_class( "type-post clearfix" ); ?>>
                    <div class="post-thumb">
                        <a title="<?php printf( __('Permanent Link to %s', 'framework' ), get_the_title() ); ?>" href="<?php the_permalink(); ?>">
                            <?php	
                    
                    $width = $bridge_options['child_archive_img_width'];
                    $height = $bridge_options['child_archive_img_height'];
                    show_bridge_thumb(get_the_ID(),$width,$height); ?>
                        </a>
                    </div>
                    <header>
                        <?php
                        $title_before = '<h2 class="entry-title"><a href="' . get_permalink( get_the_ID() ) . '" rel="bookmark" title="' . the_title_attribute( array( 'echo' => 0 ) ) . '">';
                        $title_after = '</a></h2>';
                        the_title( $title_before, $title_after ); ?>
                    </header>
                </article>
                </div>
                <?php
        
            endwhile;
        else:
            echo "No posts to show";
        endif;

        ?>
        </div>
        <?php if ( function_exists('wp_pagenavi') ): ?>
		<div class="navigation clearfix"> 
			<?php wp_pagenavi(); ?>
		</div>
<?php
	  else:
		ca_pagenav();
	  endif;
?>
           
    </main>		
</div>
<?php get_footer(); ?>