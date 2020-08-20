<?php
/**
 * class for home page contact section view
 *
 * @package calypso
 */

class Ca_Contact_Section{
    public function __construct() {
		$this->hook_section();
	}

	private function hook_section() {
        $section_priority = apply_filters( 'ca_contact_priority', 50 );
        add_action( 'home_sections', array( $this, 'render_section' ), absint( $section_priority ) );
    }

    public function render_section() {
		$hide_section = get_theme_mod( 'ca_hide_contact_section', true );
        $section_style = '';
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
        do_action('before_contact');
		?>
        <section class="home-section ca-contact <?php echo esc_attr( $class_to_add ); ?>" id="contact" data-sorder="ca_contact" <?php echo wp_kses_post( $section_style ); ?>>
            <div class="container">
                    <?php $this->render_content(); ?>
            </div>
        </section>
		<?php
        do_action('after_contact');
    }

    public function render_content() {
        $content = $this->get_content();
        $this->show_content( $content );
        
    }

    public function show_content( $content ) {
		?>
        <div class="row">
            <div class="col-12 text-center">
                <?php if ( ! empty( $content['title'] ) ) { ?>
                    <h1 class="contact-title"><?php echo wp_kses_post( $content['title'] ); ?></h1>
                <?php } ?>
                <?php if ( ! empty( $content['desciption'] ) ) { ?>
                    <p class="ca-description contact-sub-title"><?php echo wp_kses_post( $content['desciption'] ); ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6 col-md-6 col-sm-12 ">
            <?php  $this->get_contact_content(); ?>
            </div>
		    <div class="col-12 col-lg-6 col-md-6 col-sm-12">
                <?php
                    $this->get_ca_widget();
                    $this->get_ca_shortcode();
                ?>
		    </div>
        </div>
		<?php
		
    }

    public function get_contact_content() {
        $contact_content_default = '';
        if ( current_user_can( 'edit_theme_options' ) ) {
            $contact_content_default = $this->content_default();
        }
                        
        $contact_content = get_theme_mod( 'ca_contact_content', wp_kses_post( $contact_content_default ) );
        if ( ! empty( $contact_content ) ) {
            echo '<div class="contact-content">';
            echo wp_kses_post( force_balance_tags( $contact_content ) );
            echo '</div>';
        }
    }

    /**
	 * Get the contact default content
	 *
	 * @return string
	 */
	public function content_default() {
		$html = '<div class="ca-info info info-horizontal">
			<div class="icon icon-primary">
				<i class="fa fa-map-marker"></i>
			</div>
			<div class="description">
				<h4 class="info-title"> Find us at the office </h4>
				<p>No 8, Second Floor, Greenpark Layout, Bangalore</p>
			</div>
		</div>
		<div class="ca-info info info-horizontal">
			<div class="icon icon-primary">
				<i class="fa fa-mobile"></i>
			</div>
			<div class="description">
				<h4 class="info-title">Give us a ring</h4>
				<p>Nithin <br> +91 8971833806<br>Mon - Fri, 10:00-07:00</p>
			</div>
		</div>';

		return apply_filters( 'ca_contact_content_default', $html );
	}
    
    public function get_ca_shortcode() {
        $shortcode = get_theme_mod( 'ca_contact_shortcode');
        if($shortcode){
            ?>
             <div class="ca-contact-container contact-shortcode">
                    <?php echo do_shortcode( $shortcode ); ?>
                </div>
            <?php
        }else{
            return false;
        }
    }
    public function get_ca_widget() {
        if ( is_active_sidebar( 'contact-widgets' ) ) { ?>
                <div class="ca-contact-container">
                    <?php dynamic_sidebar( 'contact-widgets' ); ?>
                </div>
        <?php }else{
            return false;
        } 
    }

    public function get_content() {
		$content = array();

		/* translators: 1 - link to customizer setting. 2 - 'customizer' */
		$title_default          = current_user_can( 'edit_theme_options' ) ? sprintf( esc_html__( 'Change in the %s', 'calypso' ), sprintf( '<a href="%1$s" class="default-link">%2$s</a>', esc_url( admin_url( 'customize.php?autofocus&#91;control&#93;=ca_contact_title' ) ), __( 'Customizer', 'calypso'  ) ) ) : false;

		$ca_contact_title = get_theme_mod( 'ca_contact_title', $title_default );
		
		if ( ! empty( $ca_contact_title ) ) {
			$content['title'] = $ca_contact_title;
		}

		/* translators: 1 - link to customizer setting. 2 - 'customizer' */
		$text_default          = current_user_can( 'edit_theme_options' ) ? sprintf( esc_html__( 'Change in the %s', 'calypso' ), sprintf( '<a href="%1$s" class="default-link">%2$s</a>', esc_url( admin_url( 'customize.php?autofocus&#91;control&#93;=ca_contact_description' ) ), __( 'Customizer', 'calypso' ) ) ) : false;
		$ca_contact_description = get_theme_mod( 'ca_contact_description', $text_default );
		if ( ! empty( $ca_contact_description ) ) {
			$content['desciption'] = $ca_contact_description;
		}

		return $content;
	}
    
    public function get_background() {
		
		$ca_contact_image = get_theme_mod( 'ca_contact_image', get_template_directory_uri() . '/assets/images/contact.jpg'  );
		
		return $ca_contact_image;

	}
}

new Ca_Contact_Section();