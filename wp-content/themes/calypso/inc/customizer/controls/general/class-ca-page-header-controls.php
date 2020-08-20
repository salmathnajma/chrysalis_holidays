<?php
/**
 * defined all page header controls
 *
 * @package calypso
 */
class Ca_Page_Header_Controls {

    public function __construct() {
       
        $this->render_controls();
    }

    private function render_controls(){
        
        global $wp_customize;

        $wp_customize->add_control( new Ca_Image_Radiobutton_Control( $wp_customize, 'ca_header_layout',
            array(
                'label' => __( 'Page Header Layout' ,'calypso'),
                'section' => 'ca_page_header_section',
                'settings' => 'ca_header_layout',
                'priority' => 10, 
                'choices' => $this->get_header_layout_choices()
            )
        ));
        $wp_customize->add_control( new WP_Customize_Cropped_Image_Control( $wp_customize,'ca_header_image', 
            array(
                'label' => __( 'Header Backround Image','calypso' ),
                'description' => esc_html__( 'Click Add new image to upload an image file from your computer. Your theme works best with an image with a header size of 2000 × 1150 pixels — you’ll be able to crop your image once you upload it for a perfect fit.' ,'calypso'),
                'section' => 'ca_page_header_section',
                'settings' => 'ca_header_image',
                'priority' => 20, 
                'flex_width' => false, // Optional. Default: false
                'flex_height' => true, // Optional. Default: false
                'width' => 2000, // Optional. Default: 150
                'height' => 1150, // Optional. Default: 150
                'button_labels' => array( // Optional.
                    'select' => __( 'Select Image','calypso' ),
                    'change' => __( 'Change Image' ,'calypso'),
                    'remove' => __( 'Remove' ,'calypso'),
                    'default' => __( 'Default' ,'calypso'),
                    'placeholder' => __( 'No image selected','calypso' ),
                    'frame_title' => __( 'Select Image' ,'calypso'),
                    'frame_button' => __( 'Choose Image' ,'calypso'),
                )
            )
        ));

        $wp_customize->add_control( 'ca_header_bgcolor',
            array(
                'label' => __( 'Header Background Color' ,'calypso'),
                'description' => esc_html__( 'Set default color for your header background.','calypso' ),
                'section' => 'ca_page_header_section',
                'priority' => 30, // Optional. Order priority to load the control. Default: 10
                'type' => 'color'
            )
        );
    }

    private function get_header_layout_choices() {
        return array(
            'default'    => array(
                'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/default-header.png',
                'name' => 'Default',
                'label' => esc_html__( 'Default', 'calypso' ),
            ),
            'no-content'  => array(
                'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/no-content.png',
                'name' => 'No Content',
                'label' => esc_html__( 'No Content', 'calypso' ),
            ),
            'classic-blog' => array(
                'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/classic-blog.png',
                'name' => 'Classic',
                'label' => esc_html__( 'Classic', 'calypso' ),
            ),
        );
    }
}