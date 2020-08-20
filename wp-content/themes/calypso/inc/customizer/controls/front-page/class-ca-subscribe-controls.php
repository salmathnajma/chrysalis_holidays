<?php
/**
 * defined all subscribe controls
 *
 * @package calypso
 */
class Ca_Subscribe_Controls {

    public function __construct() {
       
        $this->render_controls();
    }

    private function render_controls(){
        
        global $wp_customize;

        $wp_customize->add_control( new Ca_Notice_Control( $wp_customize,               'ca_subscribe_notice', 
            array(
                'label' => __( 'Before Using This Section' ,'calypso'),
                'description' => __( 'For activating subscribe newsletter section, you must install any newsletter plugins like <a href="https://wordpress.org/plugins/newsletter/" target="_blank">Newsletter</a>. Then add related widget to this section.','calypso' ),
                'section' => 'sidebar-widgets-subscribe-widgets',
                'priority' => 10
            )
        ));

        $wp_customize->add_control( 'ca_hide_subscribe_section',array(
            'label' => __( 'Hide This Section', 'calypso' ),
            'description' => esc_html__( 'Hide Subscribe section from the home page','calypso' ),
            'priority' => 30, 
            'type'=> 'checkbox',
            'section'  => 'sidebar-widgets-subscribe-widgets',
            'settings'=>'ca_hide_subscribe_section',
        ));
       

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ca_subscribe_image',
            array(
                'label' => __( 'Background Image', 'calypso' ),
                'section' => 'sidebar-widgets-subscribe-widgets',
                'settings' => 'ca_subscribe_image',
                'priority' => 40, 
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

        $wp_customize->add_control('ca_subscribe_title',array(
			'label'=>__( 'Title', 'calypso' ),
			'type'=>'text',
			'section'=>'sidebar-widgets-subscribe-widgets',
            'settings'=>'ca_subscribe_title',
            'priority' => 50
        ));
        
        $wp_customize->add_control( 'ca_subscribe_description', array(
            'label' => __( 'Description', 'calypso' ),
            'type' => 'textarea',
            'section' => 'sidebar-widgets-subscribe-widgets',
            'settings' => 'ca_subscribe_description',
            'priority' => 60
        ));
    }
}