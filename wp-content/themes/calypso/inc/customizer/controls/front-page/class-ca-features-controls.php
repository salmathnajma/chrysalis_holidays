<?php
/**
 * defined all home page blog controls
 *
 * @package calypso
 */
class Ca_Features_Controls {

    public function __construct() {
       
        $this->render_controls();
    }

    private function render_controls(){
        
        global $wp_customize;

        $wp_customize->add_control( 'ca_hide_feature_section',array(
            'label' => __( 'Hide This Section', 'calypso' ),
            'description' => esc_html__( 'Hide feature section from the home page','calypso' ),
            'priority' => 10, 
            'type'=> 'checkbox',
            'section'  => 'ca_feature_section',
            'settings'=>'ca_hide_feature_section',
        ));

        $wp_customize->add_control( new Ca_Image_Radiobutton_Control( $wp_customize, 'ca_feature_layout',
            array(
                'label' => __( 'Layout','calypso' ),
                'section' => 'ca_feature_section',
                'settings' => 'ca_feature_layout',
                'priority' => 15, 
                'choices' => array(
                    'left' => array(  // Required. Setting for this particular radio button choice
                        'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/left-align.png', // Required. URL for the image
                        'name' => __( 'Title Left ' , 'calypso') // Required. Title text to display
                    ),
                    'center' => array(
                        'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/center-align.png',
                        'name' => __( 'Title Center', 'calypso' )
                    ),
                    'right' => array(
                        'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/right-align.png',
                        'name' => __( 'Title Right' , 'calypso')
                    )
                )
            )
        ));

        $wp_customize->add_control('ca_feature_title',array(
			'label'=>__( 'Title', 'calypso' ),
			'type'=>'text',
			'section'=>'ca_feature_section',
            'settings'=>'ca_feature_title',
            'priority' => 20
        ));
        
        $wp_customize->add_control( 'ca_feature_description', array(
            'label' => __( 'Description', 'calypso' ),
            'type' => 'textarea',
            'section' => 'ca_feature_section',
            'settings' => 'ca_feature_description',
            'priority' => 30
        ));

        $wp_customize->add_control(
            new Ca_Repeater_Control(
                $wp_customize, 'ca_features_content', array(
                    'label'                             => esc_html__( 'Features Content', 'calypso' ),
                    'section'                           => 'ca_feature_section',
                    'priority'                          => 40,
                    'add_field_label'                   => esc_html__( 'Add new Feature', 'calypso' ),
                    'item_name'                         => esc_html__( 'Feature', 'calypso' ),
                    'customizer_repeater_icon_control'  => true,
                    'customizer_repeater_title_control' => true,
                    'customizer_repeater_text_control'  => true,
                    'customizer_repeater_link_control'  => true,
                    'customizer_repeater_color_control' => true,
                    'customizer_repeater_color2_control' => true,
                    'customizer_repeater_image_control' => true
                  
                )
            )
        );

        
    }
}