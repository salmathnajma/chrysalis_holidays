<?php
/**
 * class for include front-end css,js and functions
 *
 * @package calypso
 */
class Ca_Frontend {

    public function __construct() {
       // add_action( 'init', [ $this, 'enqueue_styles' ] );
    }

    /** Enqueue theme styles.  */
    public function enqueue_styles() {

        wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css',array(), CA_VERSION );
        wp_enqueue_style( 'ca-datepicker', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css',array(), CA_VERSION );
        wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css', array(), CA_VERSION );
        wp_enqueue_style( 'calypso-style', get_stylesheet_uri() );
       
        // Customizer Style
		if ( is_customize_preview() ) {
			wp_enqueue_style( 'ca-customizer-preview-style', get_template_directory_uri() . '/assets/css/customizer-preview.css', array(), CA_VERSION );
        }
        
        new Ca_Inline_Styles();
    }

     /** Enqueue theme scripts.  */
    public function enqueue_scripts() {
        wp_enqueue_script( 'calypso-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

        wp_enqueue_script( 'calypso-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );
        if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
            wp_enqueue_script( 'comment-reply' );
        }
        wp_enqueue_script( 'popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js', array( 'jquery' ), CA_VERSION, true );
        wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/bootstrap/js/bootstrap.min.js', array( 'jquery' ), CA_VERSION, true );
       
        wp_enqueue_script( 'moment', 'https://cdn.jsdelivr.net/momentjs/latest/moment.min.js', array( 'jquery' ), CA_VERSION, true );
        wp_enqueue_script( 'ca-datepicker', 'https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js', array( 'jquery' ), CA_VERSION, true );

        wp_enqueue_script( 'ca-scripts', get_template_directory_uri() . '/assets/js/ca-script.js',array( 'jquery' ),CA_VERSION,true);

        wp_enqueue_script( 'ca-booking', get_template_directory_uri() . '/assets/js/ca-booking.js',array( 'jquery' ),CA_VERSION,true);

        wp_localize_script(
            'ca-booking',
            'ca_booking_obj',
            array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) )
        );
    }

    /**
	 * Register widgets for the theme.
	 */
	public function initialize_widgets() {
        $sidebars_array = array(
            'sidebar-1'           => esc_html__( 'Sidebar', 'calypso' ),
            'welcome-widgets'    => esc_html__( 'Welcome', 'calypso' ),
            'subscribe-widgets'    => esc_html__( 'Subscribe', 'calypso' ),
            'contact-widgets'    => esc_html__( 'Contact', 'calypso' ),
        );
        
        $footer_sidebars_array = array(
            'footer-one-widgets'   => esc_html__( 'Footer One', 'calypso' ),
            'footer-two-widgets'   => esc_html__( 'Footer Two', 'calypso' ),
            'footer-three-widgets' => esc_html__( 'Footer Three', 'calypso' ),
            'footer-four-widgets' => esc_html__( 'Footer Four', 'calypso' )
        );

        if ( ! empty( $footer_sidebars_array ) ) {
			$sidebars_array = array_merge( $sidebars_array, $footer_sidebars_array );
        }
        
        foreach ( $sidebars_array as $sidebar_id => $sidebar_name ) {
            $sidebar_settings = array(
				'name'          => $sidebar_name,
				'id'            => $sidebar_id,
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h5>',
				'after_title'   => '</h5>',
			);
            
            if ( $sidebar_id === 'subscribe-widgets' || $sidebar_id === 'blog-subscribe-widgets' ) {
				$sidebar_settings['before_widget'] = '';
				$sidebar_settings['after_widget']  = '';
			}

			register_sidebar( $sidebar_settings );
        }

    }
}



?>