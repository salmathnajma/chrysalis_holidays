<?php
/**
 * class for header view
 *
 * @package calypso
 */

class Ca_Header{

    public function __construct() {
        add_action( 'ca_do_header', array( $this, 'header' ) );
        add_filter( 'wp_nav_menu_args', array( $this, 'modify_primary_menu' ) );
    }

    /**
	 * Render header
	 */
	public function header() { ?>
        <nav id="site-navigation" class="navbar navbar-expand-md navbar-light">
            <div class="<?php echo apply_filters( 'ca_menu_container','container' ); ?>">
                <?php $this->site_logo(); ?>
                <?php  $this->render_navigation(); ?>

            </div>
        </nav>
        <?php
    }

    public function render_navigation() {  ?>
  
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#main-navigation" aria-controls="main-navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="icon-bar top-bar"></span>
            <span class="icon-bar middle-bar"></span>
            <span class="icon-bar bottom-bar"></span>				
        </button>
        
            <?php
                
            if ( function_exists('has_nav_menu') && has_nav_menu('primary') ) {
                wp_nav_menu( array(
                    'theme_location'    => 'primary',
                    'depth'             => 2,
                    'container'         => 'div',
                    'container_class'   => 'collapse navbar-collapse',
                    'container_id'      => 'main-navigation',
                    'menu_class'        => 'nav navbar-nav ml-auto' ,
                    'fallback_cb'       => 'Ca_Bootstrap_Nav::fallback',
                    'walker'            => new Ca_Bootstrap_Nav(),
                ) );
                
            }else{ ?>
                <ul class="navbar-nav nav-menu">
                    <?php if ( is_page() )
                            $highlight = "page_item";
                            else
                            $highlight = "page_item current_page_item"; 
                    ?>
                    <li class="<?php echo $highlight; ?>"><a href="<?php echo home_url( '/' ); ?>"><?php _e( 'Home', 'calypso' ); ?></a></li>
                    <?php wp_list_pages( 'sort_column=menu_order&depth=6&title_li=&exclude=' ); ?>
                    <div class="clear"></div>
                </ul><?php
            } ?>
         <?php
    }
    

    public function site_logo() {
        if ( get_theme_mod( 'custom_logo' ) ) {

            $logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
            $alt = get_post_meta( get_theme_mod( 'custom_logo' ), '_wp_attachment_image_alt', true );
            if ( empty( $alt ) )
				$alt = get_bloginfo( 'name' );
           ?> 
          <a class="navbar-brand logo" href="<?php echo esc_url( home_url( '/' ) ); ?>"
                            title="<?php bloginfo( 'name' ); ?>"><img class="logo" src="<?php echo esc_url( $logo[0] ); ?>" alt="<?php echo esc_attr( $alt ); ?>" /></a>
            <?php
		    if (  display_header_text() ) { ?>
                 <a class="navbar-brands" href="<?php echo esc_url( home_url( '/' ) ); ?>"
                            title="<?php bloginfo( 'name' ); ?>"><h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1></a><br/>
                <?php
             }
        }else{ 
            if(is_customize_preview() ){
                echo '<a class="navbar-brand logo"><p>Update your logo</p></a>';
            } ?>

             <a class="navbar-brands" href="<?php echo esc_url( home_url( '/' ) ); ?>"
                            title="<?php bloginfo( 'name' ); ?>"><h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1></a><br/>
			<?php
        }
        do_action('header-additional-mobile');
    }

    /**
	 * Modify Primary Navigation to add navigation cart and search.
	 *
	 * @param string $markup the markup for the navigation addons.
	 */
	public function modify_primary_menu( $markup ) {
     	if ( 'primary' !== $markup['theme_location'] ) {
			return $markup;
		}
		$markup['items_wrap'] = $this->show_modified_navigation();

		return $markup;
    }
    
    private function show_modified_navigation() {

		$nav  = '<ul id="%1$s" class="%2$s">';
		$nav .= '%3$s';
		$nav .= $this->search_form();
		$nav .= '</ul>';

		return $nav;
    }
    
    private function search_form() {
        $hide_search = get_theme_mod( 'ca_hide_search', false );
		if ( (bool) $hide_search === true ) {
			return ;
        }
   
		add_filter( 'get_search_form', array( $this, 'filter_search_form' ) );
		$form = get_search_form( false );
		remove_filter( 'get_search_form', array( $this, 'filter_search_form' ) );

		return $form;
    }
    public function filter_search_form( $form ) {
        $output  = '';
      	$output .= '<li class="menu-search">';
		$output .= '<div class="nav-search">';
		$output .= $form;
		$output .= '</div>';
		$output .= '<div class="toggle-search"><i class="fa fa-search"></i></div>';
        $output .= '</li>';
        
		return $output;
	}
 }

new Ca_Header();