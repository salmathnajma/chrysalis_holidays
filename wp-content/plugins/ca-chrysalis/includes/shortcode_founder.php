<?php
//founder section
function show_founder_promise( $atts,$contents = null) {
    extract(shortcode_atts(array(
      'heading' => '',
      'sub_heading' => '',
      'link' => '',
      'src' => ''
    ), $atts)); 
    $res='<div class="container pt-5 mb-lg-5">';
      $res.='<div class="row my-5">';
        // $res.='<div class="col-sm-12 col-md-12 col-lg-2">';
        //  $res.=' <img src=" '.get_stylesheet_directory_uri(). '/assets/quote.png" class="ml-lg-3 pr-1 qoute-center pt-0">';
        // $res.=' </div>';
          $res.='<div class="background-quote col-sm-12 col-md-12 col-lg-9">';
          $res.=' <h2> '.$heading.'</h2>';
          $res.='  <h4>'.$sub_heading.'</h4>';
          $res.=' <p class="mt-lg-5">'.$contents.'</p>';
          $res.=' </div>';
              $res.='<div class="col-sm-12 col-md-12 col-lg-3 justify-content-center d-flex flex-wrap align-items-center">';
              $res.=' <img src=" '.$src.'" class="rounded-circle">';//defualt class--(rounded-circle)
              $res.=' </div>';
      $res.=' </div>';
      $res .='<button class="buttons d-block m-auto">';
      $res .='<a href='.$link.' class="d-block">More</a>';
      $res .='</button>';
   $res.=' </div>';
  return $res;
  }
  add_shortcode('founder' , 'show_founder_promise' );
  ?>