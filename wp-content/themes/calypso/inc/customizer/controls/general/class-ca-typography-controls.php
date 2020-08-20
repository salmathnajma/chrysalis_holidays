<?php
/**
 * defined all typography controls
 *
 * @package calypso
 */
class Ca_Typography_Controls {

    public function __construct() {
       
        $this->render_controls();
    }

    private function render_controls(){

        $this->render_general_typography_controls();
        $this->render_menu_typography_controls();
        $this->render_frontpage_typography_controls();
        
    }

    private function render_general_typography_controls(){
        global $wp_customize;

        $wp_customize->add_control( 'ca_anchor_fontcolor',
            array(
                'label' => __( 'Link Color' ,'calypso'),
                'section' => 'ca_general_typography_section',
                'type' => 'color'
            )
        );

        $wp_customize->add_control( new Ca_Heading_Control( $wp_customize, 'ca_typography_heading_heading',
            array(
                'label' => __( 'Heading Typography' ,'calypso'),
                'section' => 'ca_general_typography_section',
            )
        ));

        $wp_customize->add_control( new Ca_Google_Fonts_Control( $wp_customize, 'ca_heading_fontfamily',
            array(
                'label' => __( 'Heading Font Family' ,'calypso'),
                'section' => 'ca_general_typography_section',
                'input_attrs' => array(
                    'font_count' => 'all',
                    'orderby' => 'alpha',
                ),
            )
        ));
        $wp_customize->add_control( new Ca_Slider_Control( $wp_customize, 'ca_heading_fontsize',
            array(
                'label' => esc_html__( 'Heading Font Size (px)','calypso' ),
                'section' => 'ca_general_typography_section',
                'input_attrs' => array(
                    'min' => 10, 
                    'max' => 50, 
                    'step' => 1, 
                ),
            )
        ));
       

        $wp_customize->add_control( new Ca_Heading_Control( $wp_customize, 'ca_typography_body_heading',
            array(
                'label' => __( 'Body Typography' ,'calypso'),
                'section' => 'ca_general_typography_section',
            )
        ));

        $wp_customize->add_control( new Ca_Google_Fonts_Control( $wp_customize, 'ca_body_fontfamily',
            array(
                'label' => __( 'Body Font Family','calypso' ),
                'section' => 'ca_general_typography_section',
                'input_attrs' => array(
                    'font_count' => 'all',
                    'orderby' => 'alpha',
                ),
            )
        ));

        $wp_customize->add_control( new Ca_Slider_Control( $wp_customize, 'ca_body_fontsize',
            array(
                'label' => esc_html__( 'Body Font Size (px)' ,'calypso'),
                'section' => 'ca_general_typography_section',
                'input_attrs' => array(
                    'min' => 8, 
                    'max' => 32, 
                    'step' => 1, 
                ),
            )
        ));
        $wp_customize->add_control( 'ca_body_fontcolor',
            array(
                'label' => __( 'Body Font Color' ,'calypso'),
                'section' => 'ca_general_typography_section',
                'type' => 'color'
            )
        );

        $wp_customize->add_control( new Ca_Heading_Control( $wp_customize, 'ca_typography_title_heading',
            array(
                'label' => __( 'Page Title Typography','calypso' ),
                'section' => 'ca_general_typography_section',
            )
        ));

        $wp_customize->add_control( new Ca_Slider_Control( $wp_customize, 'ca_title_fontsize',
            array(
                'label' => esc_html__( 'Title Font Size (px)' ,'calypso'),
                'section' => 'ca_general_typography_section',
                'input_attrs' => array(
                    'min' => 10, 
                    'max' => 100, 
                    'step' => 1, 
                ),
            )
        ));
       

    }

    private function render_menu_typography_controls(){
        global $wp_customize;


        $wp_customize->add_control( new Ca_Slider_Control( $wp_customize, 'ca_menu_fontsize',
            array(
                'label' => esc_html__( 'Font Size (px)','calypso' ),
                'section' => 'ca_menu_section',
                'input_attrs' => array(
                    'min' => 8, 
                    'max' => 32, 
                    'step' => 1, 
                ),
            )
        ));

        $wp_customize->add_control( new Ca_Alpha_Color_Control( $wp_customize, 'ca_menu_fontcolor',
            array(
                'label' => __( 'Font Color' ,'calypso'),
                'section' => 'ca_menu_section',
                'show_opacity' => true, // Optional. Show or hide the opacity value on the opacity slider handle. Default: true
                'palette' => array( // Optional. Select the colours for the colour palette . Default: WP color control palette
                    '#000',
                    '#fff',
                    '#df312c',
                    '#df9a23',
                    '#eef000',
                    '#7ed934',
                    '#1571c1',
                    '#8309e7'
                )   
            )
        ));
    }

    private function render_frontpage_typography_controls(){
        global $wp_customize;

        $wp_customize->add_control( new Ca_Heading_Control( $wp_customize, 'ca_typography_welcome_section_heading',
            array(
                'label' => __( 'Welcome Sections Typography','calypso' ),
                'section' => 'ca_frontpage_typography_section',
            )
        ));

        $wp_customize->add_control( 'ca_welcome_fontcolor',
            array(
                'label' => __( 'Welcome Font Color','calypso' ),
                'section' => 'ca_frontpage_typography_section',
                'type' => 'color'
            )
        );

        $wp_customize->add_control( new Ca_Slider_Control( $wp_customize, 'ca_welcome_title_fontsize',
            array(
                'label' => esc_html__( 'Welcome Title Font Size (px)','calypso' ),
                'section' => 'ca_frontpage_typography_section',
                'input_attrs' => array(
                    'min' => 10, 
                    'max' => 120, 
                    'step' => 1, 
                ),
            )
        ));
        

        $wp_customize->add_control( new Ca_Slider_Control( $wp_customize, 'ca_welcome_des_fontsize',
            array(
                'label' => esc_html__( 'Welcome Description Font Size (px)','calypso' ),
                'section' => 'ca_frontpage_typography_section',
                'input_attrs' => array(
                    'min' => 10, 
                    'max' => 120, 
                    'step' => 1, 
                ),
            )
        ));

        $wp_customize->add_control( new Ca_Heading_Control( $wp_customize, 'ca_typography_section_heading',
            array(
                'label' => __( 'Sections Typography','calypso' ),
                'section' => 'ca_frontpage_typography_section',
            )
        ));

        $wp_customize->add_control( new Ca_Slider_Control( $wp_customize, 'ca_section_title_fontsize',
            array(
                'label' => esc_html__( 'Section Title Font Size (px)','calypso' ),
                'section' => 'ca_frontpage_typography_section',
                'input_attrs' => array(
                    'min' => 10, 
                    'max' => 100, 
                    'step' => 1, 
                ),
            )
        ));
      

        $wp_customize->add_control( new Ca_Slider_Control( $wp_customize, 'ca_section_des_fontsize',
            array(
                'label' => esc_html__( 'Section Description Font Size (px)', 'calypso'),
                'section' => 'ca_frontpage_typography_section',
                'input_attrs' => array(
                    'min' => 8, 
                    'max' => 40, 
                    'step' => 1, 
                ),
            )
        ));
      
       
    }
}