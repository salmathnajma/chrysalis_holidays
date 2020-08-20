<?php
//testimonial section

function testimonail_fun( $atts,$contents = null ) {
    extract(shortcode_atts(array(
      'heading' => '',
      'sub_heading' => '',
      //'link' => ''
    ), $atts));   
  $res= '<div class="container-fluid testimonial-sec mb-5">';
  $res.=' <div class="row">';
      $res.=' <div class="slider_background_left col-sm-12 col-md-12 col-lg-6 p-lg-0">';
      // $res.='<img src= "' .get_stylesheet_directory_uri(). '/assets/slide-750x600.jpg" style="object-fit: cover;">';
      $res.='</div>';
      $res.=' <div class=" slider_background_right col-sm-12 col-md-12 col-lg-6  p-lg-0 mt-3 mt-lg-0">';  
  
                $res.='<h1 class="text-center text-white mt-5 pt-5">'.$heading.'</h1>';
                $res.='<h4 class="text-center text-white">'.$sub_heading.'</h4>';
                $res.= '<div class="star text-center">';
                        $x = 1;
                        while($x <= 5) {
                          $res.='<i class="fa fa-star fa1"></i>';
                          $x++;
                        } 
                $res.='</div>';
                $ca_slider_layout = apply_filters( 'ca_slider_layout', 'col-12' );
                $ca_slider_content_layout = apply_filters( 'ca_slider_content_layout', 'single' );
                $slider_content  = get_theme_mod( 'ca_slider_content');

                $res.='<section class="home-section ca-slider" id="slider" data-sorder="ca_slider">';
                    $res.='<div class="container">';
                        $res.='<div class="row">';
                          
                            $res.='<div class='.$ca_slider_layout.'>';
                                $res.='<div class="carousel slide carousel-fade" data-ride="carousel" id="caSlider">';                         
                                $res.= render_content($slider_content);
                                $res.= '</div>';
                            $res.='</div>';
                    
                        $res.='</div>'; 
                    $res.='</div>';
                 $res.='</section>';
             
      $res.='</div>';
  $res.='</div>';
  $res.='</div>';
  return $res;
  }
  add_shortcode('testimonails' , 'testimonail_fun' );
  
  function render_content($slider_content) {
    $slider_content = json_decode( $slider_content );
    if($slider_content){
            $slide_count = count($slider_content);
  
           

            $res= '<div class="carousel-inner">';
                  $i = 0; 
                  $class = '';
                  foreach ( $slider_content as $slider ) :
                    $link = '';

                      $image = ! empty( $slider->image_url ) ? apply_filters( 'ca_translate_single_string', $slider->image_url, 'Slider section' ) : '';
                      $title = ! empty( $slider->title ) ? apply_filters( 'ca_translate_single_string', $slider->title, 'Slider section' ) : '';
                      $text = ! empty( $slider->text ) ? apply_filters( 'ca_translate_single_string', $slider->text, 'Slider section' ) : '';
                      $link = ! empty( $slider->link ) ? apply_filters( 'ca_translate_single_string', $slider->link, 'Slider section' ) : '';
  
                      $extension =  substr($image, strrpos($image, '.' )+1); 
  
                      if($i == 0)
                          $class = " active ";
                      else
                          $class = "";
                
                      $res.='<div class="carousel-item' .$class.'">';
                            $res.='<a style="display:block" href="'.$link.'">';
                                  $res.='<div class="image-container text-center">';
                                        if($extension == 'mp4'){ 
                                          $res.='<video  title="1" id="bgvid" autoplay loop muted poster="http://www.thefrasier.com/wp-content/themes/frasier/images/Frasier_Home_120314.jpg"><source src="' .$image. '" type="video/webm">Your browser does not support the video tag.</video>';
                                        }else{ 
                                           if($text)
                                           $res.='<p class="slide-text" id="text"><a href="'.$link.'">' .$text. '</a></p>';
                                           if($title)
                                           $res.='<a href="'.$link.'" class="font-weight-bold">'.$title.'</a> </br>';
                                         
                                          $res.=' <img className="d-block w-100" class="mt-3 rounded-circle" src="'.$image.'"/>';
                                        }     
                                    $res.=' </div>';
                            $res.='</a>';
                            
                      $res.='</div>';
                      $i++;
                  endforeach;
                  $c = 0;
                  $res.= '<ol class="carousel-indicators mt-3">';
                        foreach ( $slider_content as $slider ) :
                          {
                            if($c == 0){
                                $class = " active ";
                                }
                            else{
                                $class = "";
                               }
        
                                $res.='<li data-target="#caSlider" data-slide-to="'.$c.'" class="'.$class.' rounded-circle indicator-btn"></li>';
                                $c++;
                              }
                        endforeach;
                  $res.='</ol>';
            $res.= '</div>';
            
    }
    return $res;
  }
?>