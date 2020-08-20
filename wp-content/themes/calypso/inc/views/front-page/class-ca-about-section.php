<?php
/**
 * class for home page about section view
 *
 * @package calypso
 */

class Ca_About_Section{
    public function __construct() {
		$this->hook_section();
	}

	private function hook_section() {
        $section_priority = apply_filters( 'ca_about_priority', 20 );
        add_action( 'home_sections', array( $this, 'render_section' ), absint( $section_priority ) );
	}

	public function render_section() {
		$hide_section = get_theme_mod( 'ca_hide_about_section', false );
        $section_style = '';
        $class_to_add = '';
        
		if ( (bool) $hide_section === true ) {
            if ( is_customize_preview() ) {
                $section_style .= 'display: none;';
            } else {
                return;
            }
        }
        $background = $this->get_background();
        if ( ! empty( $background ) ) {
			$class_to_add   = 'section-image';
			$section_style .= 'background-image: url(\'' . esc_url( $background ) . '\');';
		}
        $section_style = 'style="' . $section_style . '"';
        
        do_action('before_about');
		?>
        <section class="home-section ca-about <?php echo esc_attr( $class_to_add ); ?>" id="about" data-sorder="ca_about" <?php echo wp_kses_post( $section_style ); ?>>
            <div class="container">
                <div class="row ca-about-content d-block ">
                    <div class="col-12">
                     <?php $this->render_content(); ?>
                    </div>
                </div>
            </div>
        </section>
		<?php

        do_action('after_about');
	}

	public function render_content() {
        if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();
                the_content();
            }
        } else { 
            get_template_part( 'template-parts/content', 'none' );
        }
	}

	public function get_background() {
		
		$ca_about_image = get_theme_mod( 'ca_about_image', get_template_directory_uri() . '/assets/images/about.jpg'  );
		
		return $ca_about_image;

	}

}

new Ca_About_Section();