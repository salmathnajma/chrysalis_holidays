<?php
/**
 * defines subscribe  partials
 *
 * @package calypso
 */
class Ca_Subscribe_Partials {

    public function __construct() {
        
        $this->render_partials();
    }

    private function render_partials(){

        global $wp_customize;

        $wp_customize->selective_refresh->add_partial( 'ca_subscribe_image', array(
            'selector' => 'section#subscribe',
            'container_inclusive' => true,
            'render_callback' => function() {
                echo $this->ca_get_subscribe_section();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_hide_subscribe_section', array(
            'selector' => 'section#subscribe',
            'container_inclusive' => true,
           'render_callback' => function() {
                echo $this->ca_get_subscribe_section();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_subscribe_title', array(
            'selector' => '.subscribe-title',
            'render_callback' => function() {
                echo $this->ca_get_subscribe_title();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_subscribe_description', array(
            'selector' => '.subscribe-sub-title',
            'render_callback' => function() {
                echo $this->ca_get_subscribe_description();
             },
        ) );

    }

    function ca_get_subscribe_section(){
        $subscribe = new Ca_Subscribe_Section();
        $subscribe->render_section();
    }
    function ca_get_subscribe_title(){
        $ca_subscribe_title = get_theme_mod( 'ca_subscribe_title' );
        return  $ca_subscribe_title;
    }

    function ca_get_subscribe_description(){
        $ca_subscribe_description = get_theme_mod( 'ca_subscribe_description' );
        return  $ca_subscribe_description;
    }
}