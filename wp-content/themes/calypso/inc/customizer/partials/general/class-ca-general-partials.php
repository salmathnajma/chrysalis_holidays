<?php
/**
 * defines all general partials
 *
 * @package calypso
 */
class Ca_General_Partials {

    protected $gen_typo;

    public function __construct() {
       
        $this->define_general_typos();
        $this->render_partials();
        $this->render_typography_partials();
    }

    private function define_general_typos(){
        $this->gen_typo = array(
            'menu-fontsize',
            'menu-fontcolor',
            'heading-fontsize',
            'heading-fontfamily',
            'body-fontsize',
            'body-fontfamily',
            'body-fontcolor',
            'title-fontsize',
            'anchor-fontcolor',
            'section-title-fontsize',
            'section-des-fontsize',
            'welcome-fontcolor',
            'welcome-title-fontsize',
            'welcome-des-fontsize',
        );
    }

    private function render_partials(){

        global $wp_customize;

        $wp_customize->selective_refresh->add_partial( 'custom_logo', array(
			'selector'        => '.navbar-brand.logo',
			'render_callback' => 'ca_customize_partial_customlogo',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'ca_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'ca_customize_partial_blogdescription',
        ) );
    }

    function ca_customize_partial_customlogo() {
        if ( get_theme_mod( 'custom_logo' ) ) {
            $logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
            $logo = '<img src="' . esc_url( $logo[0] ) . '">';
        } else {
            $logo = '<p>Update you logo</p>';
        }
    
        return $logo;
    }
    
    function ca_customize_partial_blogname() {
        bloginfo( 'name' );
    }
    
    function ca_customize_partial_blogdescription() {
        bloginfo( 'description' );
    }


    private function render_typography_partials(){

        global $wp_customize;

        foreach ( $this->gen_typo as $gen_typo ) {
            $gen_typo = explode( '-', $gen_typo );
            $gen_typo  = implode( '_', $gen_typo );

            $partial = 'ca_' . $gen_typo;
            
            $wp_customize->selective_refresh->add_partial( $partial);
        }
       
    }

}