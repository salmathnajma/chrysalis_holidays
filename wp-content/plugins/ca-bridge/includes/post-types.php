<?php

/* Add Custom Post Type: Clients */

add_action( 'init', 'ca_create_client_post_type' );

function ca_create_client_post_type() {

		$token = PARENT_TYPE_SLUG;
		$singular = PARENT_TYPE_NAME;
		$plural = PARENT_TYPE_PLURAL;
		$supports = array( 'title', 'editor', 'excerpt','thumbnail','comments' );
		
		
		$labels = array(
						'name' => _x( $singular, 'post type general name', 'framework' ),
						'singular_name' => _x( $singular, 'post type singular name', 'framework' ),
						'add_new' => _x( 'Add New', $singular ),
						'add_new_item' => sprintf( __( 'Add New %s', 'framework' ), $singular ),
						'edit_item' => sprintf( __( 'Edit %s', 'framework' ), $singular ),
						'new_item' => sprintf( __( 'New %s', 'framework' ), $singular ),
						'all_items' => sprintf( __( '%s', 'framework' ), $plural ),
						'view_item' => sprintf( __( 'View %s', 'framework' ), $singular ),
						'search_items' => sprintf( __( 'Search %a', 'framework' ), $plural ),
						'not_found' =>  sprintf( __( 'No %s Found', 'framework' ), $plural ),
						'not_found_in_trash' => sprintf( __( 'No %s Found In Trash', 'framework' ), $plural ),
						'parent_item_colon' => '',
						'menu_name' => $plural
				);
		
		$args = array(
						'labels' => $labels,
						'public' => true,			
						'publicly_queryable' => true,
						'rewrite' => true,
						'show_ui' => true, 
						'show_in_menu' => 'edit.php?post_type='.CHILD_TYPE_SLUG,
						'query_var' => true,
						'capability_type' => 'post',
						'hierarchical' => false,
						'can_export' => true,
						'supports' => $supports,
						'menu_icon' => PLUGIN_PATH.'/images/icon-parent-16.png'
					  );
					  
		register_post_type( $token, $args );
		
		/* Taxonomy Type - Client Categories */
			
		
			
		$labels = array(
			'name' => __( PARENT_CATEGORY_NAME, 'taxonomy general name' ),
			'singular_name' => _x( PARENT_CATEGORY_NAME, 'taxonomy singular name' ),
			'search_items' =>  __( 'Search '.PARENT_CATEGORY_PLURAL, 'framework' ),
			'all_items' => __( 'All '.PARENT_CATEGORY_PLURAL, 'framework' ),
			'parent_item' => __( 'Parent '.PARENT_CATEGORY_NAME, 'framework' ),
			'parent_item_colon' => __( 'Parent '.PARENT_CATEGORY_NAME.':', 'framework' ),
			'edit_item' => __( 'Edit '.PARENT_CATEGORY_NAME, 'framework' ), 
			'update_item' => __( 'Update '.PARENT_CATEGORY_NAME, 'framework' ),
			'add_new_item' => __( 'Add New '.PARENT_CATEGORY_NAME, 'framework' ),
			'new_item_name' => __( 'New '.PARENT_CATEGORY_NAME, 'framework' ),
			'menu_name' => __( PARENT_CATEGORY_PLURAL, 'framework' )
		); 	
	
	$args = array(
					'hierarchical' => true,
					'labels' => $labels,
					'show_ui' => true,
					'query_var' => true,
					'rewrite' => array( 'slug' => __( PARENT_CATEGORY_SLUG ),  'with_front' => true )
				);
	
	register_taxonomy(PARENT_CATEGORY_SLUG, array( $token ), $args );
		
	

}




/* Add Custom Post Type: Portfolio */

add_action( 'init', 'ca_create_portfolio_post_type' );

function ca_create_portfolio_post_type() {

		$token = CHILD_TYPE_SLUG;
		$singular = CHILD_TYPE_NAME;
		$plural = CHILD_TYPE_PLURAL;
		$supports = array( 'title', 'excerpt', 'editor', 'thumbnail', 'post-thumbnails','comments' );
		
		
		$labels = array(
						'name' => _x( $singular, 'post type general name', 'framework' ),
						'singular_name' => _x( $singular, 'post type singular name', 'framework' ),
						'add_new' => _x( 'Add New', $singular.' Item' ),
						'add_new_item' => sprintf( __( 'Add New %s', 'framework' ), $singular ),
						'edit_item' => sprintf( __( 'Edit %s', 'framework' ), $singular ),
						'new_item' => sprintf( __( 'New %s', 'framework' ), $singular ),
						'all_items' => sprintf( __( 'All %s', 'framework' ), $plural ),
						'view_item' => sprintf( __( 'View %s', 'framework' ), $singular ),
						'search_items' => sprintf( __( 'Search %a', 'framework' ), $plural ),
						'not_found' =>  sprintf( __( 'No %s Found', 'framework' ), $plural ),
						'not_found_in_trash' => sprintf( __( 'No %s Found In Trash', 'framework' ), $plural ),
						'parent_item_colon' => '',
						'menu_name' => $plural
				);
		
		$args = array(
						'labels' => $labels,
						'public' => true,			
						'publicly_queryable' => true,
						'rewrite' => array( 'slug' => $token, 'with_front' => true ),
						'show_ui' => true, 
						'show_in_menu' => true,
						'query_var' => true,
						'capability_type' => 'post',
						'menu_position' => 12,
						'hierarchical' => false,
						'can_export' => true,
						'supports' => $supports,
						'menu_icon' => PLUGIN_PATH.'/images/icon-child-16.png'
					  );
					  
		register_post_type( $token, $args );
		
		/* Taxonomy Type - Portfolio Categories */
			
		$labels = array(
				'name' => __( CHILD_CATEGORY_NAME, 'taxonomy general name' ),
				'singular_name' => _x( CHILD_CATEGORY_NAME, 'taxonomy singular name' ),
				'search_items' =>  __( 'Search '.CHILD_CATEGORY_PLURAL, 'framework' ),
				'all_items' => __( 'All '.CHILD_CATEGORY_PLURAL, 'framework' ),
				'parent_item' => __( 'Parent '.CHILD_CATEGORY_NAME, 'framework' ),
				'parent_item_colon' => __( 'Parent '.CHILD_CATEGORY_NAME.':', 'framework' ),
				'edit_item' => __( 'Edit '.CHILD_CATEGORY_NAME, 'framework' ), 
				'update_item' => __( 'Update '.CHILD_CATEGORY_NAME, 'framework' ),
				'add_new_item' => __( 'Add New '.CHILD_CATEGORY_NAME, 'framework' ),
				'new_item_name' => __( 'New '.CHILD_CATEGORY_NAME, 'framework' ),
				'menu_name' => __( CHILD_CATEGORY_PLURAL, 'framework' )
			); 	
		
		$args = array(
						'hierarchical' => true,
						'labels' => $labels,
						'show_ui' => true,
						'query_var' => true,
						'rewrite' => array( 'slug' => __( CHILD_CATEGORY_SLUG ),  'with_front' => true )
					);
		
		register_taxonomy(CHILD_CATEGORY_SLUG, array( $token ), $args );


}



add_filter( "manage_edit-".PARENT_TYPE_SLUG."_columns", "client_edit_columns" );

function client_edit_columns( $columns ) {

	$columns = array(
		"cb" =>	"<input type=\"checkbox\" />",
		"title" => __( 'Name', 'framework' ),
		"date" => __( 'Date', 'framework' ),
		PARENT_CATEGORY_SLUG => __( 'Categories', 'framework' )
	);

	return $columns;
	
}
add_filter( 'manage_edit-'.PARENT_TYPE_SLUG.'_sortable_columns', 'client_sortable_columns' );
function client_sortable_columns( $sortable_columns ) {

	$sortable_columns[PARENT_CATEGORY_SLUG] = PARENT_CATEGORY_SLUG;

	return $sortable_columns;
}

add_action( 'manage_'.PARENT_TYPE_SLUG.'_posts_custom_column', 'client_custom_columns', 10, 2 );

function client_custom_columns( $column, $post_id) {
	
	global $post;

	switch ( $column ) {
		case PARENT_CATEGORY_SLUG:
			echo get_the_term_list( $post_id, PARENT_CATEGORY_SLUG, '', ', ','');
		break;
		default:
			break;
	}
	
}


add_filter( "manage_edit-".CHILD_TYPE_SLUG."_columns", "portfolio_edit_columns" );

function portfolio_edit_columns( $columns ) {

	$columns = array(
		"cb" =>	"<input type=\"checkbox\" />",
		"title" => __(CHILD_TYPE_PLURAL, 'framework' ),
		PARENT_TYPE_SLUG => __( PARENT_TYPE_PLURAL, 'framework' ),
		"date" => __( 'Date', 'framework' ),
		CHILD_CATEGORY_SLUG => __( 'Category', 'framework' )
	);

	return $columns;
	
}
add_filter( 'manage_edit-'.CHILD_TYPE_SLUG.'_sortable_columns', 'portfolio_sortable_columns' );
function portfolio_sortable_columns( $sortable_columns ) {

	$sortable_columns[ PARENT_TYPE_SLUG] = PARENT_TYPE_SLUG;
	$sortable_columns['category'] = 'category';

	return $sortable_columns;
}

add_action( 'manage_'.CHILD_TYPE_SLUG.'_posts_custom_column', 'portfolio_custom_columns', 10, 2 );

function portfolio_custom_columns( $column, $post_id) {
	
	global $post;

	switch ( $column ) {
		case PARENT_TYPE_SLUG:
			get_portfolio_clients($post_id,'',false);
			break;
		case "date":
			if ( '0000-00-00 00:00:00' == $post->post_date && 'date' == $column_name ) {
				$t_time = $h_time = __( 'Unpublished' );
			} else {
				$t_time = get_the_time( __( 'Y/m/d g:i:s A' ) );
				$m_time = $post->post_date;
				$time = get_post_time( 'G', true, $post, false );
				if ( ( abs( $t_diff = time() - $time ) ) < 86400 ) {
					if ( $t_diff < 0 )
						$h_time = sprintf( __( '%s from now' ), human_time_diff( $time ) );
					else
						$h_time = sprintf( __( '%s ago' ), human_time_diff( $time ) );
				} else {
					$h_time = mysql2date( __( 'Y/m/d' ), $m_time );
				}
			}
			echo $h_time;
		break;
		case CHILD_CATEGORY_SLUG:
			echo get_the_term_list( $post_id, CHILD_CATEGORY_SLUG, '', ', ','');
			break;
		default:
			break;
	}
	
}


?>