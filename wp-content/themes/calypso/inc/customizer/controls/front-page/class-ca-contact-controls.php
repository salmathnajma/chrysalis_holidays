<?php
/**
 * defined all contact controls
 *
 * @package calypso
 */
class Ca_Contact_Controls {

    public function __construct() {
       
        $this->render_controls();
    }

    private function render_controls(){
        
        global $wp_customize;

        $wp_customize->add_control( new Ca_Notice_Control( $wp_customize,  'ca_contact_notice', 
            array(
                'label' => __( 'Before Using This Section','calypso' ),
                'description' => __( 'For activating contact section, you must install any form plugins like <a href="https://wordpress.org/plugins/wpforms-lite/" target="_blank">WP Forms</a>. Then add related widget or shortcode to this section.','calypso' ),
                'section' => 'sidebar-widgets-contact-widgets',
                'priority' => 10
            )
        ));

        $wp_customize->add_control( 'ca_hide_contact_section',array(
            'label' => __( 'Hide This Section', 'calypso' ),
            'description' => esc_html__( 'Hide Contact section from the home page','calypso' ),
            'priority' => 30, 
            'type'=> 'checkbox',
            'section'  => 'sidebar-widgets-contact-widgets',
            'settings'=>'ca_hide_contact_section',
        ));

        $wp_customize->add_control('ca_contact_shortcode',array(
			'label'=>__( 'Shortcode', 'calypso' ),
			'type'=>'text',
			'section'=>'sidebar-widgets-contact-widgets',
            'settings'=>'ca_contact_shortcode',
            'priority' => 40
        ));
       

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ca_contact_image',
            array(
                'label' => __( 'Background Image', 'calypso' ),
                'section' => 'sidebar-widgets-contact-widgets',
                'settings' => 'ca_contact_image',
                'priority' => 50, 
                'button_labels' => array( // Optional.
                    'select' => __( 'Select Image', 'calypso' ),
                    'change' => __( 'Change Image', 'calypso' ),
                    'remove' => __( 'Remove', 'calypso' ),
                    'default' => __( 'Default' , 'calypso'),
                    'placeholder' => __( 'No image selected' , 'calypso'),
                    'frame_title' => __( 'Select Image', 'calypso'),
                    'frame_button' => __( 'Choose Image' , 'calypso'),
                )
            )
        ));

        $wp_customize->add_control('ca_contact_title',array(
			'label'=>__( 'Title', 'calypso' ),
			'type'=>'text',
			'section'=>'sidebar-widgets-contact-widgets',
            'settings'=>'ca_contact_title',
            'priority' => 60
        ));
        
        $wp_customize->add_control( 'ca_contact_description', array(
            'label' => __( 'Description', 'calypso' ),
            'type' => 'textarea',
            'section' => 'sidebar-widgets-contact-widgets',
            'settings' => 'ca_contact_description',
            'priority' => 70
        ));

        $wp_customize->add_control( new Ca_Page_Editor_Control( $wp_customize,           'ca_contact_content',
            array(
                'label' => __( 'Contact Content','calypso' ),
                'section' => 'sidebar-widgets-contact-widgets',
                'priority' => 80
            )
        ));

    }
}