<?php
/**
 * class for home page subscribe section view
 *
 * @package calypso
 */

class Ca_Subscribe_Section{
    public function __construct() {
		$this->hook_section();
	}

	public function hook_section() {
		$section_priority = apply_filters( 'ca_subscribe_priority', 5 );
        add_action( 'ca_do_footer', array( $this, 'render_section' ), absint( $section_priority ) );
	}

	

	public function render_section() {
		$hide_section = get_theme_mod( 'ca_hide_subscribe_section', true );
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
        if ( !empty( $background ) ) {
			$class_to_add   = 'section-image';
			$section_style .= 'background-image: url(\'' . esc_url( $background ) . '\');';
		}
		$section_style = 'style="' . $section_style . '"';
		do_action('before_subscribe');
		?>
        <section class="home-section ca-subscribe <?php echo esc_attr( $class_to_add ); ?>" id="subscribe" data-sorder="ca_subscribe" <?php echo wp_kses_post( $section_style ); ?>>
            <div class="container">
                <div class="row ca-subscribe-content d-block">
                    <?php $this->render_content(); ?>
                </div>
            </div>
        </section>
		<?php
		do_action('after_subscribe');
	}

	public function render_content() {
        $content = $this->get_content();
        $this->show_content( $content );
        
    }

    public function show_content( $content ) {
		?>
		
		<div class="col-12">
			<?php if ( ! empty( $content['title'] ) ) { ?>
				<h1 class="subscribe-title"><?php echo wp_kses_post( $content['title'] ); ?></h1>
			<?php } ?>
			<?php if ( ! empty( $content['desciption'] ) ) { ?>
				<p class="ca-description subscribe-sub-title"><?php echo wp_kses_post( $content['desciption'] ); ?></p>
			<?php } ?>
			<?php if ( is_active_sidebar( 'subscribe-widgets' ) ) : ?>
					<div class="ca-subscribe-container">
						<?php dynamic_sidebar( 'subscribe-widgets' ); ?>
					</div>
				<?php endif; ?>
		</div>
		<?php
		
	}
    
    public function get_content() {
		$content = array();

		/* translators: 1 - link to customizer setting. 2 - 'customizer' */
		$title_default          = current_user_can( 'edit_theme_options' ) ? sprintf( esc_html__( 'Change in the %s', 'calypso' ), sprintf( '<a href="%1$s" class="default-link">%2$s</a>', esc_url( admin_url( 'customize.php?autofocus&#91;control&#93;=ca_subscribe_title' ) ), __( 'Customizer', 'calypso'  ) ) ) : false;

		$ca_subscribe_title = get_theme_mod( 'ca_subscribe_title', $title_default );
		
		if ( ! empty( $ca_subscribe_title ) ) {
			$content['title'] = $ca_subscribe_title;
		}

		/* translators: 1 - link to customizer setting. 2 - 'customizer' */
		$text_default          = current_user_can( 'edit_theme_options' ) ? sprintf( esc_html__( 'Change in the %s', 'calypso' ), sprintf( '<a href="%1$s" class="default-link">%2$s</a>', esc_url( admin_url( 'customize.php?autofocus&#91;control&#93;=ca_subscribe_description' ) ), __( 'Customizer', 'calypso' ) ) ) : false;
		$ca_subscribe_description = get_theme_mod( 'ca_subscribe_description', $text_default );
		if ( ! empty( $ca_subscribe_description ) ) {
			$content['desciption'] = $ca_subscribe_description;
		}

		return $content;
	}

	public function get_background() {
		
		$ca_subscribe_image = get_theme_mod( 'ca_subscribe_image', get_template_directory_uri() . '/assets/images/newsletter.jpg'  );
		
		return $ca_subscribe_image;

	}

}
new Ca_Subscribe_Section();