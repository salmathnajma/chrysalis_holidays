<?php

include_once dirname( __FILE__ ) . '/isotope-class.php';




function get_clients_list( $args = array() ) {
	
	$custom_post_type = PARENT_TYPE_SLUG;
	$posts_per_page = -1;
	$clients = array();
	
	$defaults = array(
					'posts_per_page' => $posts_per_page,
					'post_type' => $custom_post_type
				);
				
	$args = wp_parse_args( $args, $defaults );
	$clients_list = get_posts( $args ) ;
	foreach ( $clients_list as $client ) {
		$clients[$client->ID]= $client->post_title;
	}
	
	return $clients;
	
}



function get_client_portfolio( $post_id = null, $args = null ) {

	$args = is_array($args) ? $args : array();
	$clients = $post_id ;
	if ( !empty( $clients ) ) {
		$args['meta_query'] = array(
									array(
										'key' => 'clients',
										'value' => $clients,
										'compare' => 'LIKE'
									)
								);
								
		$portfolio_list = get_portfolio_list( $args );
		$total_count = count( $portfolio_list );
		
		echo '<div class="child-container">';
		echo '<h3>' . sprintf( _nx( CHILD_TYPE_NAME, CHILD_TYPE_PLURAL, $total_count, 'framework' ), number_format_i18n( $total_count ) ) . '</h3>';
		foreach ( $portfolio_list as $post_id => $post_title ) {
			?>
			<div class='child'>
				<?php 
				$bridge_options = get_option('bridge_option_name');
				$width = $bridge_options['child_img_width'];
				$height = $bridge_options['child_img_height'];
				show_bridge_thumb($post_id,$width,$height); 
				 ?>
				<h4><a href="<?php echo get_permalink( $post_id ); ?>"><?php echo get_the_title( $post_id );?> </a></h4>
				<p><?php echo get_the_excerpt( $post_id );?></p>
			</div>
			<?php
		}
		echo '</div>';
	}
	return;
	
}


function get_portfolio_list( $args = array() ) {
	
	$custom_post_type = CHILD_TYPE_SLUG;
	$posts_per_page = -1;
	$portfolios = array();
	
	$defaults = array(
					'posts_per_page' => $posts_per_page,
					'post_type' => $custom_post_type
				);
				
	$args = wp_parse_args( $args, $defaults );
	$portofolio_list = get_posts( $args ) ;
	foreach ( $portofolio_list as $portfolio ) {
		$portfolios[$portfolio->ID]= $portfolio->post_title;
	}
	
	return $portfolios;
	
}


function get_portfolio_clients( $post_id = null, $args = null, $block = true ) {

	$args = is_array($args) ? $args : array();
	$clients = get_post_meta( $post_id, 'clients' );
	if ( !empty( $clients ) ) {
		
		$data = (array) maybe_unserialize( $clients[0] );
		$args['post__in'] = $data;
		$clients_list = get_clients_list( $args );
		$total_count = count( $clients_list );
		if($block){
			echo '<div class="parent-container">';
			echo '<h3>' . sprintf( _nx( PARENT_TYPE_NAME, PARENT_TYPE_PLURAL, $total_count, 'framework' ), number_format_i18n( $total_count ) ) . '</h3>';
			foreach ( $clients_list as $post_id => $post_title ) {
				?>
				<div class='parent'>
					<?php 
					$bridge_options = get_option('bridge_option_name');
					$width = $bridge_options['parent_img_width'];
					$height = $bridge_options['parent_img_height'];
					show_bridge_thumb($post_id,$width,$height); 
					?>
					<h4><a href="<?php echo get_permalink( $post_id ); ?>"><?php echo get_the_title( $post_id );?> </a></h4>
					<p><?php echo get_the_excerpt( $post_id );?></p>
				</div>
				<?php
			}
			echo '</div>';
		}else{
			foreach ( $clients_list as $post_id => $post_title ) {
				$count ++;
				if ( $count > 1)
					echo ', ';
				echo '<a href="' . get_permalink( $post_id ) .'" target="_blank">'. get_the_title( $post_id ) . '</a>';
			}
		}
		
		
	}
	return;
	
}

function get_portfolio_meta ( $args='' ) {
	
	$defaults = array (
			'post_id' => null,
			'field' => '',
			'title' => true,
			'output' => true,
			'class'	=> ''
		);
	
	$args = wp_parse_args( $args, $defaults );
	extract ( $args );
	
	global $post;
	if ( empty( $post_id ) )
		$post_id = $post->ID;
	if ( !is_bool( $title ) )
		$title = ( $title == 'false' )? 0:1;

	$html = '';
	if ( $post_id && ( !empty( $field ) ) ) {
		$data = get_post_meta( $post_id, $field, true ); 
		
		if ( $field == 'date' ) {
			$timestamp = strtotime( $data );
			$data = date("jS F, Y", $timestamp); 
		}
		
		if( !empty( $data ) ) {
			if ( $title ){
				$html .= '<div class="portfolio-meta"><h5 class="fl">' . ucfirst( $field ) . ' : </h5>';
				$html .= '<div class="'. $class .'">' . $data . '</div></div>';
			}else{
				$html .= '<div class="'. $class .'">' . $data . '</div>';
			}
		}
		
		if ( !empty( $html ) ) {
			if ( $output )
				return $html;
			else
				return $html;
		}
	}
	return false;
	
}

function ca_portfolio_header( $html ) {
    
    $html .= '<ul id="sort-by" class="bridge-filter">';
    $html .= '<li><a href="#all" data-filter="type-child" class="active">' . __( 'All', 'framework' ) . '</a></li>'; 
    $html .= wp_list_categories( array( 'title_li' => '',
                                        'echo' => false,
                                        'taxonomy' => CHILD_CATEGORY_SLUG,
                                        'walker' => new Isotope_Walker() ) );
    $html .= '</ul>';
    
    return $html;
    
}


function show_bridge_thumb($post_id, $width = 100, $height = 100) {
		$align = 'alignleft';
	$link = 'src';
	
	ca_image( 'width=' . $width . '&height=' . $height . '&class=thumbnail ' . $align . '&link=' . $link. '&id=' . $post_id );
	
}


add_action( 'wp_head', 'custom_hooks', 10 );

function custom_hooks() {
	if ( is_singular( CHILD_TYPE_SLUG ) || is_singular( PARENT_TYPE_SLUG ) ) {
		add_action( 'ca_post_inside_before', 'ca_post_title', 9 );
	}
}

add_filter('single_template', 'load_single_template');

function load_single_template($single) {

    global $wp_query, $post;

    /* Checks for single template by post type */
    if ( $post->post_type == CHILD_TYPE_SLUG ) {

		if(file_exists(trailingslashit(get_stylesheet_directory()) . 'ca-bridge/single-child.php')) {
			return trailingslashit(get_stylesheet_directory()) . 'ca-bridge/single-child.php';
		}elseif ( file_exists( CA_BRIDGE . '/templates/single-child.php' ) ) {

			return CA_BRIDGE . '/templates/single-child.php';
		
        }
	}
	
	/* Checks for single template by post type */
    if ( $post->post_type == PARENT_TYPE_SLUG ) {

		if(file_exists(trailingslashit(get_stylesheet_directory()) . 'ca-bridge/single-parent.php')) {
			return trailingslashit(get_stylesheet_directory()) . 'ca-bridge/single-parent.php';
		}elseif ( file_exists( CA_BRIDGE . '/templates/single-parent.php' ) ) {
            return CA_BRIDGE . '/templates/single-parent.php';
        }
    }

	return $single;

}
add_filter('template_include', 'load_category_template',99);
function load_category_template( $template ){
   
	
	if( is_tax(CHILD_CATEGORY_SLUG)){
		if(file_exists(trailingslashit(get_stylesheet_directory()) . 'ca-bridge/child-category.php')) {
			return trailingslashit(get_stylesheet_directory()) . 'ca-bridge/child-category.php';
		}elseif ( file_exists( CA_BRIDGE . '/templates/child-category.php' ) ) {
            return CA_BRIDGE . '/templates/child-category.php';
        }
		
	}

	if ( is_page_template( "templates/parent-archive.php" ) ) {

		if(file_exists(trailingslashit(get_stylesheet_directory()) . 'ca-bridge/parent-archive.php')) {
			$new_template = locate_template( array( 'ca-bridge/parent-archive.php' ) );
			return $new_template;
		}elseif ( file_exists( CA_BRIDGE . '/templates/parent-archive.php' ) ) {
            return CA_BRIDGE . '/templates/parent-archive.php';
        }
	}

	if ( is_page_template( "templates/.php" ) ) {

		if(file_exists(trailingslashit(get_stylesheet_directory()) . 'ca-bridge/child-archive.php')) {
			$new_template = locate_template( array( 'ca-bridge/child-archive.php' ) );
			return $new_template;
		}elseif ( file_exists( CA_BRIDGE . '/templates/child-archive.php' ) ) {
            return CA_BRIDGE . '/templates/child-archive.php';
        }
	}
  
	return $template;
}




?>