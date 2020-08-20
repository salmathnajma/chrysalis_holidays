function dest_archive(){
        $taxonomies = get_object_taxonomies( (object) array( 'post_type' => $post_type ) );
        // $taxonomies = array('destination-category','destination-tag');
        // $args = array(
        //   'post_type' => 'destinations',
        //   'hide_empty' => 0
        // );
       
        // $terms = get_terms( $taxonomies, $args);

      //  foreach($terms as $term){
       // $query_args = array( // Define the query
         // 'post_type' => 'destinations',
          // 'tax_query' => array( 
          //   'relation'=>'OR',
          //     array(
          //    'taxonomy' => 'destination-category',
          //    'field' => 'slug',
          //    'terms' => $term->slug,
          //     ),
    
          //     array(
          //    'taxonomy' => 'destination-tag',
          //    'field' => 'slug',
          //    'terms' => $term->slug,
          //     )
    
          // ), 
       // );
        //$related_cats_post = new WP_Query( $args );

        $taxonomies = get_object_taxonomies( (object) array( 'post_type' => 'destinations') );

         foreach( $taxonomies as $taxonomy ) {
          $terms = get_terms( $taxonomy );
        ?><div class="row text-center"><?php
       // if($related_cats_post->have_posts()):
        
            foreach( $terms as $term ) {
              $related_cats_post = new WP_Query( "taxonomy=$taxonomy&term=$term->slug" );  
               while($related_cats_post->have_posts()): $related_cats_post->the_post(); 
              ?>
  <div class="col-12 col-sm-12 col-md-6 col-lg-4 ">
      <div class="related_post border mb-5">
          <a href="<?php the_permalink(); ?>">
              <div class="<?php echo $term->slug ?>"> <?php the_post_thumbnail();?></div>
              <span class="truncate-overflow"><?php the_title(); ?></span>
          </a>
          <?php
                      if(get_post_meta(get_the_ID(), 'selling_price', true) != null && get_post_meta(get_the_ID(), 'selling_price', true) != '' && get_post_meta(get_the_ID(), 'original_price', true) != null && get_post_meta(get_the_ID(), 'original_price', true) != ''  ){
                      ?>
          <div class="text-left pl-3 pt-3 font-weight-bold ">Starting From:
              <div class="font-weight-bold"><span
                      class="selling-price">₹<?php  echo get_post_meta(get_the_ID(), 'selling_price', true); ?></span>
                  <span class="pl-3 original-price"><s>
                          ₹<?php echo get_post_meta(get_the_ID(), 'original_price', true); ?></s></span> </div>
          </div>
          <?php }
                      elseif(get_post_meta(get_the_ID(), 'selling_price', true) != null && get_post_meta(get_the_ID(), 'selling_price', true) != ''){?>
          <div class="text-left pl-3 pt-3 font-weight-bold ">Starting From:
              <div class="font-weight-bold"><span
                      class="selling-price">₹<?php  echo get_post_meta(get_the_ID(), 'selling_price', true); ?></span>
              </div>
          </div>

          <?php  } ?>
      </div>
  </div>
  <?php     
           endwhile;  
          }
       // wp_reset_postdata();// Restore original Post Data
      // endif; 
    //  }
    ?></div><?php
        }
}