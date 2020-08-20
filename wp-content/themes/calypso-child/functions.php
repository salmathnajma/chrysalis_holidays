<?php

function my_enqueue_scripts() {

  wp_register_script( 'script',get_stylesheet_directory_uri().'/js/script.js', array('jquery'),'1.0', true );
  wp_register_script( 'timeliner_script',get_stylesheet_directory_uri().'/js/timeliner.js', array('jquery'),'1.0', true );
  wp_register_script( 'timeliner_init',get_stylesheet_directory_uri().'/js/timeline_initialize.js', array('jquery'),'1.0', true );

 // wp_enqueue_script( 'jquery-migrate', 'https://code.jquery.com/jquery-migrate-3.0.1.min.js', array('jquery'), '3.0.1', false ); to call jquery @first

  wp_enqueue_script( 'jquery-isotope', get_stylesheet_directory_uri().'/js/jquery-isotope.min.js');
  wp_enqueue_script( 'sweetalert',get_stylesheet_directory_uri().'/js/sweetalert.min.js');
  wp_enqueue_script( 'jq_validation',get_stylesheet_directory_uri().'/js/jquery.validate.min.js',array('jquery'),'1.10.0',true);
  wp_enqueue_script( 'script');
  wp_enqueue_script( 'timeliner_script' );
  wp_enqueue_script( 'timeliner_init' );

  wp_enqueue_style( 'stylesheet',get_stylesheet_directory_uri().'/css/timeliner.css');
  wp_enqueue_style( 'stylesheet',get_stylesheet_directory_uri().'/css/responsive.css');

}
add_action( 'wp_enqueue_scripts', 'my_enqueue_scripts', 20);
//add_filter("gform_init_scripts_footer", "init_scripts");//Move Gravity forms` scripts to footer
function init_scripts() {
return true;
}
//gravity_form_enqueue_scripts( 1, true );

/*=============header-search-button========================*/
function filter_search_form( $form ){
  $link = get_post_meta(110, 'call', true);
  $output  = '';
  $output .= $form;
  $output .= '<a id="call_button" href="tel:+'.$link.'">';
  $output .= '<button class="buttons">';
  $output .= '<span class="d-block">Call</span>';
  $output .= '</button>';
  $output .= '</a>';
  return $output;
}
add_filter( 'get_search_form', 'filter_search_form',11);

/*=============welcome-section==========================*/
function get_background() {
  $ca_welcome_image = get_theme_mod( 'ca_welcome_image', get_template_directory_uri() . '/assets/images/welcome.jpg'  );
  
  return $ca_welcome_image;
}

function welcome_section(){
       $background = get_background();
      if ( ! empty( $background ) ) {
        $section_style .= 'background-image: url(\'' . esc_url( $background ) . '\'); background-repeat:no-repeat;';
      }
      $section_style = 'style="' . $section_style . '"';
 ?>
   <section class="home-section ca-welcome <?php echo esc_attr( $class_to_add ); ?>" id="welcome" data-sorder="ca_welcome" <?php echo wp_kses_post( $section_style ); ?>>
   <div class="container position-relative">
      <div class="row w-100 select_box">
            <div class=" col-lg-5 col-md-12 col-sm-12 ">
                <h4 class="font-weight-bold">6 Destinations to choose from</h4>
                <select id="destinations" onChange="window.location.href=this.value">
                    <option value="destination_1">Choose Your Heavenly Destination</option>
                      <?php 
                      $args = array(
                        'type' => 'destination',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'taxonomy' => 'destination-category'
                      );
                      $categories = get_categories($args);
                      foreach($categories as $category) { ?>
                      <option value="destination-category/<?php echo $category->slug ?>"><?php echo $category->name ?></option>
                      <?php   } ?>
                </select>
            </div>
            <div class="text-center mt-3 col-lg-2 col-md-12 col-sm-12 ">
                <h1 style="font-size: 2.5rem;" class="font-weight-bold">Or</h1>
            </div>
            <div class="col-lg-5 col-md-12 col-sm-12">
                <h4 class="font-weight-bold">Choose Holiday Type</h4>
                <select id="destinations" onChange="window.location.href=this.value">
                <option value="destination_1">Choose Your Holiday Type</option>
                <?php 
                      $args = array(
                        'type' => 'destination',
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'taxonomy' => 'destination-tag'
                      );
                      $tags = get_tags($args);
                      foreach($tags as $tag) { ?>
                      <option value="destination-tag/<?php echo $tag->slug ?>"><?php echo $tag->name ?></option>
                      <?php   } ?>
                </select>
            </div>
        </div>
    </div>
</section>
  <?php
}
add_action('home_sections','welcome_section');

/*==================subscribe-section============================ */
add_filter( 'gform_validation_message', 'prefix_gf_empty_validation_message', 10, 2 );
function prefix_gf_empty_validation_message( $message, $form ) {
    return "";
}
//=================custom post type===============================
function custom_post_type(){
$labels = array(
  'name' => 'Destinations',
  'singular_name' => 'Destination',
  'add_new' => 'Add Item',
  'all_items'=> 'All Items',
  'add_new_item' => 'Add Item',
  'edit_item' => 'Edit Item',
  'new_item' => 'New Item',
  'view_item' => 'View Item',
  'search_item' => 'Search Destinations',
  'not_found' => 'No items found',
  'not_found_in_trash' => 'No items found in trash',
  'parent_item_colon' => 'parent Item'
  
);
$args = array(
  'labels' => $labels,
  'public' => true,
  'has_archive' => true,
  'publicly_queryable'  => true,
  'query_var'  => true,
  'rewrite' => array( 'slug' => 'destinations' ),
  'capability_type'     => 'post',
  'hierarchical'        => false,
  'supports'            => array( 'title', 
                                  'editor', 
                                  'excerpt', 
                                  'author', 
                                  'thumbnail', 
                                  'comments', 
                                  'revisions',
                                  'custom-fields'
                                 ),
 'taxonomies' => array( 'destination-category','destination-tag'),
 'menu_position' => 5,
 'exclude_from_search' => false,
 'show_in_rest' => true // to support gutenberg or block model
);
register_post_type('destinations', $args);
}
add_action('init', 'custom_post_type');

// ========================custom taxonomies===============================
add_action( 'init', 'create_destinations_taxonomies', 0 );
function create_destinations_taxonomies() { 
  //for category taxonomy
	$labels = array(
		'name'              => _x( 'Destination Categories', 'taxonomy general name', 'textdomain' ),
		'singular_name'     => _x( 'Destination Category', 'taxonomy singular name', 'textdomain' ),
		'search_items'      => __( 'Search Destination Categories', 'textdomain' ),
		'all_items'         => __( 'All Destination Categories', 'textdomain' ),
		'parent_item'       => __( 'Parent Destination Category', 'textdomain' ),
		'parent_item_colon' => __( 'Parent Destination Category:', 'textdomain' ),
		'edit_item'         => __( 'Edit Destination Category', 'textdomain' ),
		'update_item'       => __( 'Update Destination Category', 'textdomain' ),
		'add_new_item'      => __( 'Add New Destination Category', 'textdomain' ),
		'new_item_name'     => __( 'New Destination Category Name', 'textdomain' ),
		'menu_name'         => __( 'Destination Category', 'textdomain' ),
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
    'rewrite'           => array( 'slug' => 'destination-category' ),
    'show_in_rest' => true,
  );
  register_taxonomy( 'destination-category', array( 'destinations' ), $args );
  //for tag taxonomy
  $labels = array(
		'name'                       => _x( 'Destination Tags', 'taxonomy general name', 'textdomain' ),
		'singular_name'              => _x( 'Destination Tag', 'taxonomy singular name', 'textdomain' ),
		'search_items'               => __( 'Search Destination Tags', 'textdomain' ),
		'popular_items'              => __( 'Popular Destination Tags', 'textdomain' ),
		'all_items'                  => __( 'All Destination Tags', 'textdomain' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => __( 'Edit Destination Tag', 'textdomain' ),
		'update_item'                => __( 'Update Destination Tag', 'textdomain' ),
		'add_new_item'               => __( 'Add New Destination Tag', 'textdomain' ),
		'new_item_name'              => __( 'New Destination Tag Name', 'textdomain' ),
		'separate_items_with_commas' => __( 'Separate Destination Tags with commas', 'textdomain' ),
		'add_or_remove_items'        => __( 'Add or remove Destination Tags', 'textdomain' ),
		'choose_from_most_used'      => __( 'Choose from the most used Destination Tags', 'textdomain' ),
		'not_found'                  => __( 'No Destination Tags found.', 'textdomain' ),
		'menu_name'                  => __( 'Destination Tags', 'textdomain' ),
	);
	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
    'rewrite'               => array( 'slug' => 'destination-tag' ),
    'show_in_rest' => true,
	);
	register_taxonomy( 'destination-tag', 'destination', $args );
}
// ------------------------------destination-archive-------------------------------------------
function common_archive(){
  ?>
  <div class="col-12 col-sm-12 col-md-6 col-lg-4 ">
      <div class="related_post border mb-5">
          <a href="<?php the_permalink(); ?>">
              <div class=""> <?php the_post_thumbnail();?></div>
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
}
function dest_archive(){
      $taxonomies = array('destination-category','destination-tag');
      
       
        // $args = array(
        //  'post_type' => 'destinations',
        //   'hide_empty' => 0
        // );
       
        // $terms = get_terms( $args);
        //print_r($terms);
         //die;

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
       $args = array(
        'post_type' => 'destinations',
        'terms' => $term->slug,
    );

    

        ?><div class="row text-center"><?php

        //foreach( $terms as $term ) {
      //  if($related_cats_post->have_posts()):


        
      
      //  assigning variables to the loop
     // global $wp_query;
      $wp_query = new WP_Query($args);
      
      // starting loop
      while ($wp_query->have_posts()) : $wp_query->the_post();
      $slugs = '';
      $terms = get_the_terms( $post->ID, $taxonomies);
      //$term = array_shift( $term );
     // print_r( $terms);
      //die;
      foreach ($terms as $term) {
        if ($slugs == '') {
          $slugs = $term->slug;
        } else {
          $slugs = $slugs.' '.$term->slug;
        }
        
      }
      // print_r($slugs)  ;
      // die;
      
      // $slug = $term->slug;
      //         print_r($slug);
      //         die;
              ?>
  <div class=" <?php echo $slugs ?> col-12 col-sm-12 col-md-6 col-lg-4 element-item ">
      <div class="related_post border mb-5">
          <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail();?>
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
           //}  
           endwhile;  
         
       // wp_reset_postdata();// Restore original Post Data
      // endif; 
     //}
    ?></div><?php


}
//-----------------------destination-category-archive----------------------------------------------------------
function dest_category_archive(){  //start by fetching the terms for the destination-category taxonomy
  $terms = get_terms( 'destination-category', array(
   'orderby'    => 'count',
   'hide_empty' => 0,
   'posts_per_page'  => '9'
 ) );
 
 foreach( $terms as $term ) { //echo get_query_var('taxonomy');
  $term = get_queried_object();
 
  $query_args = array( // Define the query
      'post_type' => 'destinations',
      'destination-category' => $term->slug
  );
  $related_cats_post = new WP_Query(  $query_args );
 }
 ?><div class="container"><div class="row text-center"><?php
 if($related_cats_post->have_posts()):
   while($related_cats_post->have_posts()): $related_cats_post->the_post();
   common_archive();
   endwhile;
 wp_reset_postdata(); // Restore original Post Data
 endif; 
 ?></div></div><?php
 } 
//-----------------------destination-tag-archive----------------------------------------------------------
function dest_tag_archive(){ //start by fetching the terms for the adestination-tag taxonomy
 $terms = get_terms( 'destination-tag', array(
  'orderby'    => 'count',
  'hide_empty' => 0
) );

foreach( $terms as $term ) {//echo get_query_var('taxonomy');
 $term = get_queried_object();
 
 $query_args = array(// Define the query
     'post_type' => 'destinations',
     'destination-tag' => $term->slug
 );
 $related_cats_post = new WP_Query(  $query_args);
}
?><div class="container"><div class="row text-center"><?php
if($related_cats_post->have_posts()):
  while($related_cats_post->have_posts()): $related_cats_post->the_post();
  common_archive();
  endwhile;
wp_reset_postdata();// Restore original Post Data
endif; 
?></div></div><?php
} 
//-----------------------Guarantee-Features-block-----------------------------------------------------------
function feature_fun( $atts,$contents = null) {
  extract(shortcode_atts(array(
    'title' => ''
  ), $atts)); 
  $res='<div class="container text-left mb-5" id="Guarantee-Features">';
        $res.='<h3>'.$title.'</h3>';
        $res.='<ul style="list-style-type:none;" class="pl-0 text-left">';
        $res.='<li class="background_instant">Instant Booking.</li>';
        $res.='<li class="background_price pr-3">Best Price Guarantee.</li>';
        $res.='<li class="background_verified pr-3">Reviews.</li>';
        $res.='</ul>';
 $res.='</div>';
return $res;
}
add_shortcode('features' , 'feature_fun' );
//----------------------------price form-block-------------------------------------------------------
function price_fun( $atts,$contents = null) {

  extract(shortcode_atts(array(
    'selling_price' => '',
    'original_price' => '',
    'form_id' => ''
  ), $atts));

  $selling_price = get_post_meta(get_the_ID(), 'selling_price', true); 
  $original_price = get_post_meta(get_the_ID(), 'original_price', true); 
  $res='<div class="border text-left" id="price_form">';
        $res.='<div class="pl-3 pt-3 font-weight-bold ">Starting From:</div>';
        $res.='<div class="pl-3 pt-1 pb-2 font-weight-bold "><span class="selling-price">'.$selling_price.'</span><span class="pl-5 original-price">

        <s> ₹'.$original_price.'.</s>

        </span></div>';
        $res .='<div class="pt-3"><button id="custom_price_btn" class="buttons d-block w-100" data-toggle="modal" data-target="#priceModal">';
        $res .='<a class="w-100 d-inline-block">Customize & Get Quotes</a>';
        $res .='</button></div></div>';  
        $res .='  <div class="modal fade" id="priceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content popup_form_container">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body phone_errormessage">'. do_shortcode('[gravityform id='.$form_id.' name=ContactUs title=false description=false]').' </div>
          </div>
        </div>
      </div>';
return $res;
}
add_shortcode('price' , 'price_fun' );
//------------------------accordion--------------------------------------------------------------------
function accordion_fun( $atts,$contents = null) {
  extract(shortcode_atts(array(
    'day_title' => '',
    'number' => ''
  ), $atts));
  $res='';
  $id = rand();
  $idEX = $id."EX";
  $res='
       <div class="timeline-wrapper">
        <h2 class="timeline-time float-left">
         <label class="num-label" >
         <div class="timeline-article text-center"><span class="num" >'.$number.'</span>
         </div></label></h2>
       <dl class="timeline-series">
      <dt id="'.$id.'" class="timeline-event"><a>'.$day_title.'</a></dt>
      <dd class="timeline-event-content" id="'.$idEX.'">

      <p>'.$contents.'</p>
        <br class="clear">
      </dd></dl>';
    $res.='<br class="clear">
</div>';
return $res;
}
add_shortcode('accordion' , 'accordion_fun' );

//----------------------Reviews(tab)-content listing page---------------------------------------------------------
add_shortcode( 'reviews', function( $atts = array(), $content = '' )
{
    if( is_singular() && post_type_supports( get_post_type(), 'comments' ) )
    {
        ob_start();
        comments_template( '/my_comments.php', true );
       return ob_get_clean();
    }
   // return '';
}, 10, 2 ); 
add_filter('comment_form_fields','crunchify_disable_comment_url');

//--------------------------Review Form--for destinaton page---------------------------------------------------
function custom_comments_template() {
  if ( is_user_logged_in() ) {
    $current_user = get_avatar( wp_get_current_user(), 64 );
  } else {
    $current_user = '<img src="' . get_template_directory_uri() . '/assets/images/placeholder.jpg" height="64" width="64"/>';
  }
 // ic_reviews();
  $args = array(
    'fields' => apply_filters(
        'comment_form_fields', array(
            'author' =>'<div class="row"> <div class="col-6"><p class="comment-form-author">' . 
           // '<label for="author">' . __( 'Name' ) . '</label> '.
            '<input id="author" placeholder="Name" name="author" type="text" value="' .
            esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' .
                ( $req ? '<span class="required">*</span>' : '' )  .
                '</p></div>',

            'email'  => '<div class="col-6"><p class="comment-form-email">' .
           // '<label for="email">' . __( 'Your Email' ) . '</label> ' .
             '<input id="email" placeholder="Email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
                '" size="30"' . $aria_req . ' />'  .
                ( $req ? '<span class="required">*</span>' : '' )  .
                '</p></div></div>'
        )

    ),
    'title_reply' => '<div class="crunchify-text"> <h2 class="Review-title  text-center">Reviews</h2></div>',
    'id_form'              => 'custom_comment_form',
    //'id_submit'         => 'custom_submit',
);
  return $args;
}


function wpsites_customize_comment_form_text_area($arg) {
  $arg['comment_field'] = '<p class="comment-form-comment"><textarea id="comment" name="comment" placeholder="Your Review" cols="45" rows="1" aria-required="true"></textarea></p>';
  return $arg;
}
add_filter('comment_form_defaults', 'wpsites_customize_comment_form_text_area');

//----------------------Review Form--for other page/post-------------------------------------------

 function post_custom_comments_template() {
  if ( is_user_logged_in() ) {
    $current_user = get_avatar( wp_get_current_user(), 64 );
  } else {
    $current_user = '<img src="' . get_template_directory_uri() . '/assets/images/placeholder.jpg" height="64" width="64"/>';
  }

  $args = array(
    'fields' => apply_filters(
      'comment_form_fields', array(
          'author' =>'<div class="row"> <div class="col-6"><p class="comment-form-author">' . 
         // '<label for="author">' . __( 'Name' ) . '</label> '.
          '<input id="author" placeholder="Name" name="author" type="text" value="' .
          esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' .
              ( $req ? '<span class="required">*</span>' : '' )  .
              '</p></div>',
              

          'email'  => '<div class="col-6"><p class="comment-form-email">' .
         // '<label for="email">' . __( 'Your Email' ) . '</label> ' .
           '<input id="email" placeholder="Email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
              '" size="30"' . $aria_req . ' />'  .
              ( $req ? '<span class="required">*</span>' : '' )  .
              '</p></div></div>'
        ),

    )
    
);

  return $args;
}

function crunchify_disable_comment_url($fields) { 
  unset($fields['url']);
  unset($fields['cookies']);
  return $fields;
}

// .....change textarea position to top.........
function t5_move_textarea( $input = array () )
{
    static $textarea = '';

    if ( 'comment_form_defaults' === current_filter() )
    {
        // Copy the field to our internal variable …
        $textarea = $input['comment_field'];
        // … and remove it from the defaults array.
        $input['comment_field'] = '';
        return $input;
    }

    print apply_filters( 'comment_form_field_comment', $textarea );
}
add_filter( 'comment_form_defaults', 't5_move_textarea' );
add_action( 'comment_form_top', 't5_move_textarea' );

// define the comment_form_submit_button callback
function filter_comment_form_submit_button( $submit_button, $args ) {
  $submit_before = '<div class="form-group text-center"><div class="buttons d-inline-block">';
  $submit_button='<input name="submit" type="submit" id="submit" class="submit" value="Submit">';
  $submit_after = '</div></div>';
  return $submit_before . $submit_button . $submit_after;
};
add_filter( 'comment_form_submit_button', 'filter_comment_form_submit_button', 10, 2 );

//------------------ direct to review-tab  after comment_form_submission------------------

function redirect_comments( $location, $commentdata ) {
  if(!isset($commentdata) || empty($commentdata->comment_post_ID) ){
    return $location;
    
  }
  $post_id = $commentdata->comment_post_ID;
  if('destinations' == get_post_type($post_id)){
     $location = get_permalink( $post_id ) . '/?tab=true';
   return $location;
  }
  return $location;
}
add_filter( 'comment_post_redirect', 'redirect_comments', 10,2 );

//-------------------- Custom list of review-comments------------------------------------------------------------

function custom_comments_list( $comment, $args, $depth ) {
  ?>
<div class="comment_listing" <?php comment_class( empty( $args['has_children'] ) ? 'media' : 'parent media' ); ?>
    id="comment-<?php comment_ID(); ?>">
    <?php if ( $args['type'] != 'pings' ) : ?>
    <a class="pull-left" href="<?php echo esc_url( get_comment_author_url( $comment ) ); ?> ">
        <div class="comment-author avatar vcard mr-3">
            <?php
          if ( $args['avatar_size'] != 0 ) {
            echo get_avatar( $comment, 60 );
          }
          ?>
        </div>
    </a>
    <?php endif; ?>
    <div class="media-body border">
        <h4 class="media-heading">
            <?php echo get_comment_author_link(); ?>
            <small>
                <?php
          printf(
            /* translators: %1$s is Date, %2$s is Time */
           // esc_html__( '&#183; %1$s at %2$s', 'calypso' ),
            date(" - d/m/y", strtotime(get_comment_date())),
            //get_comment_time()
          );
          edit_comment_link( esc_html__( '(Edit)', 'calypso' ), '  ', '' );
          ?>
            </small>
        </h4>

        <?php comment_text(); ?>
        <div class="media-footer clearfix">
            <?php
        echo get_comment_reply_link(
          array(
            'depth'      => $depth,
            'max_depth'  => $args['max_depth'],
            'reply_text' => sprintf( '<i class="fa fa-mail-reply"></i> %s', esc_html__( 'Reply', 'calypso' ) ),
          ),
          $comment->comment_ID,
          $comment->comment_post_ID
        );
        ?>
        </div>
    </div>
</div>
<?php
  }

//------------------------set-default-avatar------------------------------------------------------------------
  add_filter( 'avatar_defaults', 'wpb_new_gravatar' );
  function wpb_new_gravatar ($avatar_defaults) {
  $myavatar = 'https://chrysalis.cahosting.biz/wp-content/uploads/2020/05/avatar.png';
  $avatar_defaults[$myavatar] = "Default Gravatar";
  return $avatar_defaults;
  }

//------------------------similar-package------------------------------------------------------------------
function cats_related_post() {
?><h2 class="text-center m-5">Similar Packages</h2><?php
    $post_id = get_the_ID();
    $cat_ids = array();
    $categories = get_the_category( $post_id );

    if(!empty($categories) && is_wp_error($categories)):
        foreach ($categories as $category):
            array_push($cat_ids, $category->term_id);
        endforeach;
    endif;

    $current_post_type = get_post_type($post_id);
    $query_args = array( 
        'category__in'   => $cat_ids,
        'post_type'      => $current_post_type,
        'post__not_in'    => array($post_id),
        'posts_per_page'  => '3'
     );
   
    $related_cats_post = new WP_Query( $query_args );
    
    ?><div class="row text-center"><?php
    if($related_cats_post->have_posts()):
      while($related_cats_post->have_posts()): $related_cats_post->the_post();
      common_archive();
      endwhile;
        wp_reset_postdata();// Restore original Post Data
     endif; 
     ?></div><?php
}


//------------------------tabs--------------------------------------------------------------------
// function tabs_fun($atts,$contents = null) {
//     extract(shortcode_atts(array(
//        'heading' => ''
//      ), $atts));
//      $output='';
//      $output.='<div class="tab-container">';
//      $output.='<input type="radio" id="tab1" name="tab" checked>';
//      $output.='<label for="tab1" class="labels"><i class="fa fa-code"></i> Information</label>';
//      $output.='<input type="radio" id="tab2" name="tab">';
//      $output.='<label for="tab2" class="labels"><i class="fa fa-history"></i>TourPlan</label>';
//      $output.='<input type="radio" id="tab3" name="tab">';
//      $output.='<label for="tab3" class="labels"><i class="fa fa-pencil"></i>Location</label>';
//      $output.='<input type="radio" id="tab4" name="tab">';
//      $output.='<label for="tab4" class="labels"><i class="fa fa-share-alt"></i>Gallery</label>';
//      $output.='<input type="radio" id="tab5" name="tab">';
//      $output.='<label for="tab5" class="labels"><i class="fa fa-share-alt"></i>Reviews</label>';
//      $output.='<div class="line"></div';
//      $output.='>'.do_shortcode($content).'</div>';  
//     return $output;
//   }
//   add_shortcode( 'tabs', 'tabs_fun' ); 
//------------------------tourplan tab------------------------------------------------------------
// function tourplan_fun( $atts,$contents = null) {
//   extract(shortcode_atts(array(
//     'heading' => '',
//     'nums' => ''
//   ), $atts)); 
//   $res=' <div class=""><div class="accordion"><div class="conference-center-line"></div>';
//     $res.=' <label class="num-label" > <div class="timeline-article text-center">';
//         $res.=' <span class="num" >'.$nums.'</span ></div></label > ';
//         $res.=' <h3>'.$heading.'</h3></div>';
//         $res.=' <div class="row accordion-panel"> <p>'.$contents.'</p>';
//         $res.=' </div>';
//     $res.=' </div>';
// return $res;
// }
// add_shortcode('tourplan' , 'tourplan_fun' );
//------------------------accordion-container-------------------------------------------------------------------
// function accordion_container_fun( $atts,$contents = null) {
//   extract(shortcode_atts(array(
//     'day_title' => '',
//     'number' => ''
//   ), $atts));
//   $res='';
//   $res='<div id="timeline" class="timeline-container"></div>';
// return $res;
// }
// add_shortcode('accordion_container' , 'accordion_container_fun' );



// function filter_media_comment_status( $open, $post_id ) {
//     $post = get_post( $post_id );
//     if( $post->post_type == 'destinations' ) {
//         return false;
//     }
//     return $open;
// }
// add_filter( 'comments_open', 'filter_media_comment_status', 10 , 2 );


 ?>