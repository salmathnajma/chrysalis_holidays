<?php
/**
 * defines all home page blog partials
 *
 * @package calypso
 */
class Ca_Frontpage_Blog_Partials {

    public function __construct() {
        
        $this->render_partials();
    }

    private function render_partials(){

        global $wp_customize;

        $wp_customize->selective_refresh->add_partial( 'ca_hide_blog_section', array(
            'selector' => '#blog',
            'container_inclusive' => true,
           'render_callback' => function() {
                echo $this->ca_get_blog_section();
             },
        ) );
       
        $wp_customize->selective_refresh->add_partial( 'ca_blog_title', array(
            'selector' => '.ca-blog-title-section .ca-title',
            'render_callback' => function() {
                echo $this->ca_get_blog_title();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_blog_description', array(
            'selector' => '.ca-blog-title-section .ca-description',
            'render_callback' => function() {
                echo $this->ca_get_blog_description();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_blog_column', array(
            'selector' => '#blog',
            'render_callback' => function() {
                echo $this->ca_get_blog_section();
             },
        ) );
        $wp_customize->selective_refresh->add_partial( 'ca_blog_number', array(
            'selector' => '#blog',
            'render_callback' => function() {
                echo $this->ca_get_blog_section();
             },
        ) );

    }

    function ca_get_blog_section(){
        $blog = new Ca_Blog_Section();
        $blog->render_section();
    }
    function ca_get_blog_title(){
        $ca_blog_title = get_theme_mod( 'ca_blog_title' );
        return  $ca_blog_title;
    }
    function ca_get_blog_description(){
        $ca_blog_description = get_theme_mod( 'ca_blog_description' );
        return  $ca_blog_description;
    }
}