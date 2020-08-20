<?php
/**
 * This is the theme core class.
 *
 * @package calypso
 */

class Ca_Core {

    protected $features_to_load;

    public function __construct() {
        $this->define_hooks();
        $this->define_features();
        $this->load_features();
        $this->setup_theme();
        
    }

    /* Register all hooks */
    private function define_hooks() {

        $back_end = new Ca_Backend();
        add_action( 'customize_preview_init', array( $back_end, 'enqueue_customizer_script' ) );
        add_action( 'customize_controls_enqueue_scripts', array( $back_end, 'enqueue_customizer_controls' ) );
       
        $front_end = new Ca_Frontend();
       
        add_action( 'widgets_init', array( $front_end, 'initialize_widgets' ) );
        add_action( 'wp_enqueue_scripts', array( $front_end, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $front_end, 'enqueue_scripts' ) );
       
        add_action( 'wp_head', array( $this, 'header_tracking_code' ), 999 );
        add_action( 'ca_do_header', array( $this, 'body_tracking_code' ), 1 );
        add_action( 'wp_footer', array( $this, 'footer_tracking_code' ), 999 );
    }

    function header_tracking_code() {
        $tracking_header  = get_theme_mod( 'ca_tracking_header' ) ;
        echo $tracking_header;

        global $post;
        if(isset($post->ID)){
            $ad_tracking_head = get_post_meta( $post->ID, 'ad_tracking_head', true );
            if($ad_tracking_head)
                echo stripslashes($ad_tracking_head) . "\n";
            
        }
    }

    function body_tracking_code() {
        $tracking_body  = get_theme_mod( 'ca_tracking_body' ) ;
        echo $tracking_body;

        global $post;
        if(isset($post->ID)){
            $ad_tracking_body = get_post_meta( $post->ID, 'ad_tracking_body', true );
            if($ad_tracking_body)
                echo stripslashes($ad_tracking_body) . "\n";
            
        }
    }

    function footer_tracking_code() {
        $tracking_footer  = get_theme_mod( 'ca_tracking_footer' ) ;
        echo $tracking_footer;

        global $post;
        if(isset($post->ID)){
            $ad_tracking_footer = get_post_meta( $post->ID, 'ad_tracking_footer', true );
            if($ad_tracking_footer)
                echo stripslashes($ad_tracking_footer) . "\n";
            
        }
    }

    private function define_features() {
        $this->features_to_load = array(
            'customizer',
            'page-editor-helper',
            
        );
    }

    private function load_features() {
       
        foreach ( $this->features_to_load as $feature_name ) {
            $feature_name = explode( '-', $feature_name );
            $feature_name = array_map( 'ucfirst', $feature_name );
            $feature_name  = implode( '_', $feature_name );

            $class = 'Ca_' . $feature_name;
            
             if ( class_exists( $class ) ) {
                 
                $feature = new $class;
                $feature->init();
            }
        }
        
    }

    private function setup_theme() {

        register_nav_menus( array(
            'primary' => __( 'Primary Menu', 'calypso' ),
            'footer'       => __( 'Footer Menu', 'calypso' ),
        ) );
        

        add_theme_support( 'starter-content', $this->get_starter_content() );
    }

    private function get_starter_content() {
        $default_home_content        = '<h3>' . esc_html__( 'About Calipso', 'calipso' ) . '</h3>' . esc_html__( 'Need more details? Please check our full documentation for detailed information on how to use Calipso.', 'calipso' );
		$default_home_featured_image = get_template_directory_uri() . '/assets/images/contact.jpg';

        /*
		 * Starter Content Support
		 */
		$starter_content = array(
			'attachments' => array(
				'featured-image-home' => array(
					'post_title'   => __( 'Featured Image Homepage', 'calipso' ),
					'post_content' => __( 'Featured Image Homepage', 'calipso' ),
					'file'         => 'assets/images/contact.jpg',
				),
			),
			'posts'       => array(
				'home' => array(
					'post_content' => $default_home_content,
					'thumbnail'    => '{{featured-image-home}}',
				),
				'blog',
			),
			'nav_menus'   => array(
				'primary' => array(
					'name'  => esc_html__( 'Primary Menu', 'calipso' ),
					'items' => array(
						'page_home',
						'page_blog',
					),
				),
			),
			'options'     => array(
				'show_on_front'            => 'page',
				'page_on_front'            => '{{home}}',
				'page_for_posts'           => '{{blog}}',
				'calipso_feature_thumbnail' => $default_home_featured_image,
			),
		);

		return $starter_content;
    }

    
}



?>