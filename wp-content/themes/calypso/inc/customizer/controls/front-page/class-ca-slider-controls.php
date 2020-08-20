<?php
/**
 * defined all home page blog controls
 *
 * @package calypso
 */
class Ca_Slider_Controls {

    public function __construct() {
        $this->render_controls();
    }

    private function render_controls(){
        
        global $wp_customize;

        $wp_customize->add_control( 'ca_hide_slider_section',array(
            'label' => __( 'Hide This Section', 'calypso' ),
            'description' => esc_html__( 'Hide slider section from the home page','calypso' ),
            'priority' => 10, 
            'type'=> 'checkbox',
            'section'  => 'ca_slider_section',
            'settings'=>'ca_hide_slider_section',
        ));

       

        $wp_customize->add_control(
            new Ca_Repeater_Control(
                $wp_customize, 'ca_slider_content', array(
                    'label'                             => esc_html__( 'Sliders', 'calypso' ),
                    'section'                           => 'ca_slider_section',
                    'priority'                          => 20,
                    'add_field_label'                   => esc_html__( 'Add new slider', 'calypso' ),
                    'item_name'                         => esc_html__( 'Slider', 'calypso' ),
                    'customizer_repeater_icon_control'  => false,
                    'customizer_repeater_title_control' => true,
                    'customizer_repeater_text_control'  => true,
                    'customizer_repeater_link_control'  => true,
                    'customizer_repeater_color_control' => false,
                    'customizer_repeater_color2_control' => false,
                    'customizer_repeater_image_control' => true
                  
                )
            )
        );

        
    }
}