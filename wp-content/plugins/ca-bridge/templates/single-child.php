<?php get_header(); ?>
<div class="container">
    <?php ca_content_inside(); ?>
    <main id="main">
        <div class="row">
            <div class="col col-lg-12">
            <?php
				if ( have_posts() ) :
					while ( have_posts() ) :
						the_post(); ?>
                        <?php ca_post_before(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class( "type-post clearfix" ); ?>>
                            <section class="entry-content">
                            <?php ca_post_inside_before(); ?>
                               
                                <?php the_content(); ?>

                                <?php get_portfolio_clients( $post->ID ); ?>

                            </section> <!-- end article section -->
                        </article> <!-- end article -->
                        <?php ca_post_after(); ?>
                <?php endwhile;
                endif;
            ?>
                
                <?php
	$commenting = get_option( 'comments' );
	if ( ( $commenting == 'post' || $commenting == 'both' ) && is_single() ){
		comments_template();
	}
?>
            </div>
            
        </div>
    </main>		
</div>
<?php get_footer(); ?>