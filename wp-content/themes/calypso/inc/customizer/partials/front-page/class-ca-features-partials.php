<?php
/**
 * defines all home page blog partials
 *
 * @package calypso
 */
class Ca_Features_Partials {

    public function __construct() {
        
        $this->render_partials();
    }

    private function render_partials(){

        global $wp_customize;

        $wp_customize->selective_refresh->add_partial( 'ca_hide_feature_section', array(
            'selector' => '#features',
            'container_inclusive' => true,
           'render_callback' => function() {
                echo $this->ca_get_features_section();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_feature_layout', array(
            'selector' => '.ca-features',
            'render_callback' => function() {
                echo $this->ca_get_feature_layout_content();
             },
        ) );
       
        $wp_customize->selective_refresh->add_partial( 'ca_feature_title', array(
            'selector' => '.ca-features-title',
            'render_callback' => function() {
                echo $this->ca_get_feature_title();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_feature_description', array(
            'selector' => '.ca-features-description',
            'render_callback' => function() {
                echo $this->ca_get_feature_description();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_features_content', array(
            'selector' => '.ca-features-content',
            'container_inclusive' => true,
            'render_callback' => function() {
                echo $this->ca_get_feature_content();
             },
        ) );

    }

    function ca_get_features_section(){
        $feature = new Ca_Features_Section();
        $feature->render_section();
    }
    function ca_get_feature_title(){
        $ca_feature_title = get_theme_mod( 'ca_feature_title' );
        return  $ca_feature_title;
    }
    function ca_get_feature_description(){
        $ca_feature_description_title = get_theme_mod( 'ca_feature_description_title' );
        return  $ca_feature_description_title;
    }
    function ca_get_feature_content(){
        $feature = new Ca_Features_Section();
        $feature->show_features_content();
    }
    function ca_get_feature_layout_content(){
        $feature = new Ca_Features_Section();
        $feature->render_section();
        
    }
}