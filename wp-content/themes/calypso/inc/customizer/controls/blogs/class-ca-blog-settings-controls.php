<?php
/**
 * defined all blog settings controls
 *
 * @package calypso
 */
class Ca_Blog_Settings_Controls {

    public function __construct() {
       
        $this->render_controls();
    }

    private function render_controls(){
        
        global $wp_customize;

        $wp_customize->add_control('ca_blog_excerpt_length',array(
			'label'=>__( 'Excerpt length', 'calypso' ),
			'type'=>'number',
			'section'=>'ca_blog_general_section',
            'settings'=>'ca_blog_excerpt_length',
            'priority' => 10, 
        ));

        $wp_customize->add_control( 'ca_hide_social_icon',array(
            'label' => __( 'Hide Social Sharing Icons', 'calypso' ),
            'priority' => 20, 
            'type'=> 'checkbox',
            'section'  => 'ca_blog_general_section',
            'settings'=>'ca_hide_social_icon',
        ));

        $wp_customize->add_control( 'ca_hide_post_nav',array(
            'label' => __( 'Hide Post Nav', 'calypso' ),
            'priority' => 30, 
            'type'=> 'checkbox',
            'section'  => 'ca_blog_general_section',
            'settings'=>'ca_hide_post_nav',
        ));

        $wp_customize->add_control( 'ca_hide_related_posts',array(
            'label' => __( 'Hide Related Posts', 'calypso' ),
            'priority' => 40, 
            'type'=> 'checkbox',
            'section'  => 'ca_blog_general_section',
            'settings'=>'ca_hide_related_posts',
        ));

        $wp_customize->add_control('ca_related_posts_title',array(
			'label'=>__( 'Related Posts Title', 'calypso' ),
			'type'=>'text',
			'section'=>'ca_blog_general_section',
            'settings'=>'ca_related_posts_title',
            'priority' => 50
        ));
    }
}