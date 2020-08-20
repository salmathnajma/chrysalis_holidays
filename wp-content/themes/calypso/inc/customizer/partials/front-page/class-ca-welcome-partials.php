<?php
/**
 * defines all welcome partials
 *
 * @package calypso
 */
class Ca_Welcome_Partials {

    public function __construct() {
        
        $this->render_partials();
    }

    private function render_partials(){

        global $wp_customize;

        $wp_customize->selective_refresh->add_partial( 'ca_hide_welcome_section', array(
            'selector' => '#welcome',
            'container_inclusive' => true,
           'render_callback' => function() {
                echo $this->ca_get_welcome_section();
             },
        ) );
        $wp_customize->selective_refresh->add_partial( 'ca_welcome_layout', array(
            'selector' => '.welcome-content',
            'render_callback' => function() {
                echo $this->ca_get_welcome_layout_content();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_welcome_title', array(
            'selector' => '.welcome-title',
            'render_callback' => function() {
                echo $this->ca_get_welcome_title();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_welcome_description', array(
            'selector' => '.welcome-sub-title',
            'render_callback' => function() {
                echo $this->ca_get_welcome_description();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_welcome_btn_text', array(
            'selector' => '.welcome a',
            'render_callback' => function() {
                echo $this->ca_get_welcome_btn_text();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_welcome_image', array(
            'selector' => '.welcome.header-filter',
            'container_inclusive' => true,
            'render_callback' => function() {
                echo $this->ca_get_welcome_image();
             },
        ) );

    }

    function ca_get_welcome_section(){
        $welcome = new Ca_Welcome_Section();
        $welcome->render_section();
    }

    function ca_get_welcome_layout_content(){
        $welcome = new Ca_Welcome_Section();
        $content = $welcome->get_content();
        $welcome->show_content( $content );
    }

    function ca_get_welcome_title(){
        $ca_welcome_title = get_theme_mod( 'ca_welcome_title' );
        return  $ca_welcome_title;
    }

    function ca_get_welcome_description(){
        $ca_welcome_description = get_theme_mod( 'ca_welcome_description' );
        return  $ca_welcome_description;
    }

    function ca_get_welcome_btn_text(){
        $ca_welcome_btn_text = get_theme_mod( 'ca_welcome_btn_text' );
        return  $ca_welcome_btn_text;
    }

    function ca_get_welcome_image(){
        $welcome = new Ca_Welcome_Section();
        $background = $welcome->get_background();
        if($background){
            echo '<div class="welcome header-filter" style="background-image: url(' . esc_url( $background ) . ')"></div>';
        }
    }
}