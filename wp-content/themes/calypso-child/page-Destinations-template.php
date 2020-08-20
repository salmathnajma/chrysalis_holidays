<?php
/**
 * The template Name:Destionations-template(archive)
 
 */

get_header();
                $args = array (
                    'post_type'=>'destinations',
                    'post__in' =>$ids
                   );
                   //$posts= get_posts($args); 
                $posts= new WP_query( $args );
               //print_r( $posts);
                //die;
                        if ( $posts->have_posts() ) :
                                while ( $posts->have_posts() ) : $posts->the_post();
                                    get_template_part('template-parts/content','archive');
                                endwhile; 
                        endif;
get_footer();?>
