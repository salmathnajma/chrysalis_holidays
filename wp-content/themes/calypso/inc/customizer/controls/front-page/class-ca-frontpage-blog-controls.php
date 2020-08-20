<?php
/**
 * defined all home page blog controls
 *
 * @package calypso
 */
class Ca_Frontpage_Blog_Controls {

    public function __construct() {
       
        $this->render_controls();
    }

    private function render_controls(){
        
        global $wp_customize;

        $wp_customize->add_control( 'ca_hide_blog_section',array(
            'label' => __( 'Hide This Section', 'calypso' ),
            'description' => esc_html__( 'Hide blog section from the home page','calypso' ),
            'priority' => 10, 
            'type'=> 'checkbox',
            'section'  => 'ca_blog_section',
            'settings'=>'ca_hide_blog_section',
        ));

        $wp_customize->add_control('ca_blog_title',array(
			'label'=>__( 'Title', 'calypso' ),
			'type'=>'text',
			'section'=>'ca_blog_section',
            'settings'=>'ca_blog_title',
            'priority' => 20
        ));
        
        $wp_customize->add_control( 'ca_blog_description', array(
            'label' => __( 'Description', 'calypso' ),
            'type' => 'textarea',
            'section' => 'ca_blog_section',
            'settings' => 'ca_blog_description',
            'priority' => 30
        ));

        $wp_customize->add_control('ca_blog_number',array(
			'label'=>__( 'Number of blogs to show', 'calypso' ),
			'type'=>'number',
			'section'=>'ca_blog_section',
            'settings'=>'ca_blog_number',
            'priority' => 40, 
            
        ));

        $wp_customize->add_control( 'ca_blog_column',array(
            'label' => "Number of columns",
            'section' => 'ca_blog_section',
            'settings' => 'ca_blog_column',
            'priority' => 50, // Optional. Order priority to load the control. Default: 10
            'type' => 'select',
            'choices' => array( // Optional.
                1 => __( 'Single Column','calypso' ),
                2 => __( 'Two Column' ,'calypso'),
                3 => __( 'Three Column' ,'calypso'),
                4 => __( 'Four Column','calypso' )
            )
        ));
    }
}