<?php
/**
 * class for managing the sidebar layout
 *
 * @package calypso
 */

class Ca_Sidebar_Layout{

    public function __construct() {
		add_filter( 'ca_filter_index_content_classes', array( $this, 'index_content_classes' ) );
		add_filter( 'ca_filter_page_content_classes', array( $this, 'page_content_classes' ) );
		add_filter( 'ca_filter_welcome_content_classes', array( $this, 'welcome_content_classes' ) );
        add_action( 'ca_page_sidebar', array( $this, 'render_page_sidebar' ) );
    }

    public function index_content_classes( $classes ) {

        $sidebar_layout  = get_theme_mod( 'ca_blog_sidebar_layout', 'right-sidebar' ) ;
        
        if ( $sidebar_layout === 'full-width' ){
            return 'col-md-12 blog-posts-wrap';
        }else{
            return 'col-md-8 blog-posts-wrap';
        }
			
		
	}
	
	public function page_content_classes( $classes ) {

        $sidebar_layout  = get_theme_mod( 'ca_page_layout', 'full-width' ) ;
        
        if ( $sidebar_layout === 'full-width' ){
            return 'col-md-12 blog-posts-wrap';
        }else{
            return 'col-md-8 blog-posts-wrap';
        }
			
		
	}
	
	public function welcome_content_classes( $classes ) {

        $sidebar_layout  = get_theme_mod( 'ca_welcome_layout', 'center' ) ;
        
        if ( $sidebar_layout === 'center' ){
            return 'col-md-12';
        }else{
            return 'col-md-8';
        }
			
		
    }

    /**
	 * Render the page sidebar.
	 */
	public function render_page_sidebar() {
		if ( class_exists( 'WooCommerce' ) ) {
			if ( is_cart() || is_checkout() || is_account_page() ) {
				return;
			}
			if ( is_shop() ) {
				get_sidebar( 'woocommerce' );

				return;
			}
		}
		get_sidebar();

		return;
	}

}
new Ca_Sidebar_Layout();
?>