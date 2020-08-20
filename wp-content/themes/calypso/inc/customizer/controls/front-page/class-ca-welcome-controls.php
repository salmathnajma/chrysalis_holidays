<?php
/**
 * defined all home page welcome controls
 *
 * @package calypso
 */
class Ca_Welcome_Controls {

    public function __construct() {
       
        $this->render_controls();
    }

    private function render_controls(){
        
        global $wp_customize;

       
        $wp_customize->add_control( new Ca_Image_Radiobutton_Control( $wp_customize, 'ca_welcome_layout',
            array(
                'label' => __( 'Layout','calypso' ),
                'section' => 'sidebar-widgets-welcome-widgets',
                'settings' => 'ca_welcome_layout',
                'priority' => -10, 
                'choices' => array(
                    'left' => array(  // Required. Setting for this particular radio button choice
                        'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/left-align.png', // Required. URL for the image
                        'name' => __( 'Left Align' , 'calypso') // Required. Title text to display
                    ),
                    'center' => array(
                        'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/center-align.png',
                        'name' => __( 'Center Align', 'calypso' )
                    ),
                    'right' => array(
                        'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/right-align.png',
                        'name' => __( 'Right Align' , 'calypso')
                    )
                )
            )
        ));
        
        $wp_customize->add_control( 'ca_hide_welcome_section',array(
            'label' => __( 'Hide This Section', 'calypso' ),
            'description' => esc_html__( 'Hide welcome section from the home page','calypso' ),
            'priority' => 10, 
            'type'=> 'checkbox',
            'section'  => 'sidebar-widgets-welcome-widgets',
            'settings'=>'ca_hide_welcome_section',
        ));

        $wp_customize->add_control('ca_welcome_title',array(
			'label'=>__( 'Title', 'calypso' ),
			'type'=>'text',
			'section'=>'sidebar-widgets-welcome-widgets',
            'settings'=>'ca_welcome_title',
            'priority' => 20, 
            'input_attrs' => array( // Optional.
                'placeholder' => __( 'Enter welcome title', 'calypso' ),
            ),
        ));
        
        $wp_customize->add_control( 'ca_welcome_description', array(
            'label' => __( 'Description', 'calypso' ),
            'type' => 'textarea',
            'section' => 'sidebar-widgets-welcome-widgets',
            'settings' => 'ca_welcome_description',
            'priority' => 30, 
            'input_attrs' => array( // Optional.
                'placeholder' => __( 'Enter welcome text', 'calypso' ),
            ),
        ));

        $wp_customize->add_control('ca_welcome_btn_text',array(
			'label'=>__( 'Button Text', 'calypso' ),
			'type'=>'text',
			'section'=>'sidebar-widgets-welcome-widgets',
            'settings'=>'ca_welcome_btn_text',
            'priority' => 40, 
            
        ));

        $wp_customize->add_control('ca_welcome_btn_url',array(
			'label'=>__( 'Button Url', 'calypso' ),
			'type'=>'text',
			'section'=>'sidebar-widgets-welcome-widgets',
            'settings'=>'ca_welcome_btn_url',
            'priority' => 50, 
           
        ));

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ca_welcome_image',
            array(
                'label' => __( 'Background Image', 'calypso' ),
                'section' => 'sidebar-widgets-welcome-widgets',
                'settings' => 'ca_welcome_image',
                'priority' => 60, 
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

        
    }
}