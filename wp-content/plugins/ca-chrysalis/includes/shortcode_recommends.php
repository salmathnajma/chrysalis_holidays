<?php
//recommends section
function show_recommends( $atts ) {
    extract(shortcode_atts(array(
      'link' => '',
      'id' => ''
    ), $atts));
    $ids = explode(",",$id);
    $args = array(
      'post_type'=> 'destinations',
      'orderby' => 'post__in', 
      'post__in' => $ids,
      'posts_per_page'=> 4
    );
    $posts= get_posts( $args);
     $i = 0;
        
     foreach ($posts as $post) { 
          $src = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' );
          $links= get_permalink($post->ID);
          $result = '<a href='.$links.'>';
          $result .= ' <img src="' . $src . '" />';
          $result .= '</a>';
          $result .= '<div class="bottom-left">'.'<a href='.$links.'>'.$post->post_title.'</a>'.'</div>';
              switch ($i) {
                case '0':
                  $res .= '<div class="recommond_section row mt-lg-5"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-8  text-center "><div class="destination-img-container img-hover-zoom--basic"><div class="row"><div class="col-12  text-center recommends-img-container img_w800">'. $result .'</div></div></div>';
                  break;
                case '1':
                  $res .= '<div class="row mt-4"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-6  text-center"><div class="destination-img-container img-hover-zoom--basic">'. $result .'</div></div>';
                  break;
                case '2':
                  $res .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6  text-center mt-4 mt-lg-0"><div class="destination-img-container img-hover-zoom--basic">'. $result .'</div></div></div></div>';
                  break;
                case '3': 
                  $res .= '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4  text-center mt-4 mt-lg-0"> <div class="destination-img-container img-hover-zoom--basic">'. $result .'</div></div></div>';
                  break; 
                
                default:
                  break;
              }
            $i++;
      }            
        return $res;
}
add_shortcode('recommends' , 'show_recommends' );
?>