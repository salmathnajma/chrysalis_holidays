<?php
// trending destination section

function trending_destination( $atts, $contents = null ) {
    extract(shortcode_atts(array(
            'heading' => '',
            'link' => '',
            'ids' => ''
           ), $atts));
     $ids = explode(",",$ids);
   
     $args = array(
      'post_type'=> 'destinations',
      'orderby' => 'post__in', 
      'post__in' => $ids,
      'posts_per_page'=> 3  
      );
   $posts= get_posts($args); 
      $res = '<div class="destination_section row mt-lg-5">';
          
              $res .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-3  text-center text-lg-left mt-lg-3">';
                      $res .= '<h2>'.$heading.'</h2>';
                      $res .= '<p>'.$contents.'</p>';
                      if($link){
                        $res .='<button class="buttons d-block mx-auto my-5">';
                        $res .='<a href="'.$link.'" class="d-block">More</a>';
                        $res .='</button>';
                      }
                      // $res .='</div>';
              $res .='</div>';

                    foreach ($posts as $post) { 
                      $res .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 text-center mt-4 mt-lg-0">';
                      $res .= '<div class="destination-img-container img-hover-zoom--basic">';
                      $src = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' );
                      $links= get_permalink($post->ID);
                      $res .= '<a href= "' . $links . '" />';
                      $res .= ' <img src="' . $src . '" />';
                      $res .= '</a>';
                      $res .= '<div class="bottom-left">'.'<a href='.$links.'>'.$post->post_title.'</a>'.'</div>';
                      $res .= '</div>';
                      $res .= '</div>';
                    }   
      $res .= '</div>';   
  return $res;
  }
add_shortcode('destination' , 'trending_destination' );
?>