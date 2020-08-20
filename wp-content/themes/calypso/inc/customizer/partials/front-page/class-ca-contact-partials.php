<?php
/**
 * defines contact  partials
 *
 * @package calypso
 */
class Ca_Contact_Partials {

    public function __construct() {
        
        $this->render_partials();
    }

    private function render_partials(){

        global $wp_customize;

        $wp_customize->selective_refresh->add_partial( 'ca_contact_image', array(
            'selector' => 'section#contact',
            'container_inclusive' => true,
            'render_callback' => function() {
                echo $this->ca_get_contact_section();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_hide_contact_section', array(
            'selector' => 'section#contact',
            'container_inclusive' => true,
           'render_callback' => function() {
                echo $this->ca_get_contact_section();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_contact_title', array(
            'selector' => '.contact-title',
            'render_callback' => function() {
                echo $this->ca_get_contact_title();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_contact_description', array(
            'selector' => '.contact-sub-title',
            'render_callback' => function() {
                echo $this->ca_get_contact_description();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_contact_shortcode', array(
            'selector' => '#contact .contact-shortcode',
            'container_inclusive' => true,
           'render_callback' => function() {
                echo $this->ca_get_contact_shortcode();
             },
        ) );

        $wp_customize->selective_refresh->add_partial( 'ca_contact_content', array(
            'selector' => '#contact .contact-content',
            'container_inclusive' => true,
           'render_callback' => function() {
                echo $this->ca_get_contact_content();
             },
        ) );

    }

    function ca_get_contact_section(){
        $contact = new Ca_Contact_Section();
        $contact->render_section();
    }
    
    function ca_get_contact_title(){
        $ca_contact_title = get_theme_mod( 'ca_contact_title' );
        return  $ca_contact_title;
    }

    function ca_get_contact_description(){
        $ca_contact_description = get_theme_mod( 'ca_contact_description' );
        return  $ca_contact_description;
    }

    function ca_get_contact_shortcode(){
        $contact = new Ca_Contact_Section();
        $contact->get_ca_shortcode();
    }

    function ca_get_contact_content(){
        $contact = new Ca_Contact_Section();
        $contact->get_contact_content();
    }
}