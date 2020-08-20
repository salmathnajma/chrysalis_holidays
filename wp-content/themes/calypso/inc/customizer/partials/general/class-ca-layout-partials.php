<?php
/**
 * defines all layout partials
 *
 * @package calypso
 */
class Ca_Layout_Partials {

    public function __construct() {
        
        $this->render_partials();
    }

    private function render_partials(){

        global $wp_customize;

        

        $wp_customize->selective_refresh->add_partial( 'ca_header_layout', array(
            'selector' => '.page-header',
            'container_inclusive' => true,
            'render_callback' => function() {
                echo ca_get_page_header_layout();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_header_image', array(
            'selector' => '.page-header .header-filter',
            'container_inclusive' => true,
            'render_callback' => function() {
                echo ca_get_page_header_image();
             },
        ) );
        $wp_customize->selective_refresh->add_partial( 'ca_header_bgcolor', array(
            'selector' => '.page-header .header-filter',
            'container_inclusive' => true,
            'render_callback' => function() {
                echo ca_get_page_header_image();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_blog_sidebar_layout', array(
            'selector' => '#blog-list',
            'render_callback' => function() {
                echo ca_get_blog_archive();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_blog_layout', array(
            'selector' => '#blog-list',
            'render_callback' => function() {
                echo ca_get_blog_archive();
             },
        ) );

    }

    function ca_get_page_header_layout(){
        $layout = new Ca_Layout_Manager();
        echo $layout->post_page_header();
    }

    function ca_get_page_header_image(){
        $layout = new Ca_Layout_Manager();
        echo $layout->render_header_background();
        
    }
    function ca_get_blog_archive(){
        $layout = new Ca_Blog_Layout();
        echo $layout->render_blog_wrapper();
        
    }
}