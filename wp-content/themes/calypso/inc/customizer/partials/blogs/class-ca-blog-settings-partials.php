<?php
/**
 * defines all blog settings partials
 *
 * @package calypso
 */
class Ca_Blog_Settings_Partials {

    public function __construct() {
        
        $this->render_partials();
    }

    private function render_partials(){

        global $wp_customize;

        $wp_customize->selective_refresh->add_partial( 'ca_blog_excerpt_length', array(
            'selector' => '#blog-list',
            'render_callback' => function() {
                echo ca_get_blog_excerpt();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_hide_social_icon', array(
            'selector' => '.social-share-wrapper',
            'container_inclusive' => true,
            'render_callback' => function() {
                echo ca_show_social_share();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_hide_post_nav', array(
            'selector' => '#post-navigation',
            'container_inclusive' => true,
            'render_callback' => function() {
                echo ca_show_post_nav();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_hide_related_posts', array(
            'selector' => '.section.related-posts',
            'container_inclusive' => true,
            'render_callback' => function() {
                echo ca_show_related_posts();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_related_posts_title', array(
            'selector' => '.related-post-title',
             'render_callback' => function() {
                echo ca_get_related_post_title();
             },
        ) );

    }


    function ca_show_social_share(){
        $view = new Ca_Blog_Feature_Views();
        echo $view->social_icons();
        
    }

    function ca_get_blog_excerpt(){
        $layout = new Ca_Blog_Layout();
        echo $layout->get_ca_excerpt();
        
    }

    function ca_show_post_nav(){
        $view = new Ca_Blog_Feature_Views();
        $view->render_ca_post_nav();
    }

    function ca_show_related_posts(){
        $view = new Ca_Blog_Feature_Views();
        $view->related_posts();
    }

    function ca_get_related_post_title(){
        $title = get_theme_mod( 'ca_related_posts_title', 'Related Posts' );
        return  $title;
    }

}