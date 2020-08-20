<?php
/**
 * defined all layout controls
 *
 * @package calypso
 */
class Ca_Layout_Controls {

    public function __construct() {
       
        $this->render_controls();
    }

    private function render_controls(){
        
        global $wp_customize;

        $wp_customize->add_control( new Ca_Image_Radiobutton_Control( $wp_customize, 'ca_page_layout',
            array(
                'label' => __( 'Page Layout','calypso' ),
                'section' => 'ca_layout_section',
                'settings' => 'ca_page_layout',
                'priority' => 10, 
                'choices' => $this->get_layout_choices()
            )
        ));

        $wp_customize->add_control( new Ca_Image_Radiobutton_Control( $wp_customize, 'ca_blog_sidebar_layout',
            array(
                'label' => __( 'Blog Sidebar Layout','calypso' ),
                'section' => 'ca_layout_section',
                'settings' => 'ca_blog_sidebar_layout',
                'priority' => 20, 
                'choices' => $this->get_layout_choices()
            )
        ));

        $wp_customize->add_control( new Ca_Image_Radiobutton_Control( $wp_customize, 'ca_blog_layout',
            array(
                'label' => __( 'Blog Listing Layout' ,'calypso'),
                'section' => 'ca_layout_section',
                'settings' => 'ca_blog_layout',
                'priority' => 30, 
                'choices' => $this->get_blog_layout_choices()
            )
        ));

        $wp_customize->add_control( 'ca_hide_search',array(
            'label' => __( 'Hide Search bar from the header', 'calypso' ),
            'priority' => 40, 
            'type'=> 'checkbox',
            'section'  => 'ca_general_section',
            'settings'=>'ca_hide_search',
        ));
    }

    private function get_layout_choices() {
		return array(
			'left-sidebar'    => array(
                'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/sidebar-left.png',
                'name' => 'Left Sidebar',
				'label' => esc_html__( 'Left Sidebar', 'calypso' ),
			),
			'full-width'  => array(
                'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/full-width.png',
                'name' => 'Full Width',
				'label' => esc_html__( 'Full Width', 'calypso' ),
			),
			'right-sidebar' => array(
                'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/sidebar-right.png',
                'name' => 'Right Sidebar',
				'label' => esc_html__( 'Right Sidebar', 'calypso' ),
			),
        );
   }

   private function get_blog_layout_choices(){
        return array(
            'blog_normal_layout'    => array(
                'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/blog-default.png',
                'name' => 'Default',
                'label' => esc_html__( 'Default', 'calypso' ),
            ),
            'blog_alternative_layout'  => array(
                'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/blog-alternative.png',
                'name' => 'Alternative',
                'label' => esc_html__( 'Alternative', 'calypso' ),
            ),
            'blog_card_layout'  => array(
                'image' => trailingslashit( get_template_directory_uri() ) . 'assets/images/blog-card.jpg',
                'name' => 'Card Layout',
                'label' => esc_html__( 'Card Layout', 'calypso' ),
            )
        );
    }
}