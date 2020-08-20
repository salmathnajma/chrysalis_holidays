<?php
/**
 * defined all home page about controls
 *
 * @package calypso
 */
class Ca_About_Controls {

    public function __construct() {
       
        $this->render_controls();
    }

    private function render_controls(){
        
        global $wp_customize;

        $wp_customize->add_control( 'ca_hide_about_section',array(
            'label' => __( 'Hide This Section', 'calypso' ),
            'description' => esc_html__( 'Hide About section from the home page','calypso' ),
            'priority' => 10, 
            'type'=> 'checkbox',
            'section'  => 'ca_about_section',
            'settings'=>'ca_hide_about_section',
        ));

        $frontpage_id = get_option( 'page_on_front' );
        
        $wp_customize->add_control( new Ca_Button_Control( $wp_customize, 'ca_about_content',
       
            array(
                'label' => __( 'About Content' ,'calypso'),
                'section' => 'ca_about_section',
                'settings' => 'ca_about_content',
                'priority' => 20, 
                'button_text'     => esc_html__( '(Edit)', 'calypso' ),
                'button_class'    => 'open-editor',
                'icon_class'      => 'fa-pencil',
                'link'            => get_edit_post_link( $frontpage_id )
            )
        ));

        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'ca_about_image',
            array(
                'label' => __( 'Background Image', 'calypso' ),
                'section' => 'ca_about_section',
                'settings' => 'ca_about_image',
                'priority' => 30, 
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