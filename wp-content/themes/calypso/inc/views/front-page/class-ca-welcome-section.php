<?php
/**
 * class for home page welcome section view
 *
 * @package calypso
 */

class Ca_Welcome_Section{
    public function __construct() {
		$this->hook_section();
	}

	private function hook_section() {
		$section_priority = apply_filters( 'ca_welcome_priority', 10 );
        add_action( 'home_sections', array( $this, 'render_section' ), absint( $section_priority ) );
	}

	public function render_section() {
		$hide_section = get_theme_mod( 'ca_hide_welcome_section', false );
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
			//$class_to_add   = 'section-image';
			$section_style .= 'background-image: url(\'' . esc_url( $background ) . '\'); background-repeat:no-repeat;';
		}
		$section_style = 'style="' . $section_style . '"';

		do_action('before_welcome');
		?>

		<section class="home-section ca-welcome <?php echo esc_attr( $class_to_add ); ?>" id="welcome" data-sorder="ca_welcome" <?php echo wp_kses_post( $section_style ); ?>>
					<?php $this->render_content(); 	?>
			
		</section>
		<?php
		do_action('after_welcome');
	}

	public function render_content() {
		$content = $this->get_content();
		
		

		if ( !$content    )
			return;

		?>
		<div class="item active">
			<div class="">
				<?php
				if ( is_customize_preview() ) 
					echo '<div class="welcome-image"></div>';
				?>
				<div class="container">
					<div class="row welcome-content">
						<?php $this->show_content( $content ); ?>
					</div>
				</div><!-- /.container -->
				
			</div><!-- /.page-header -->
		</div>
		<?php
	}

	public function get_content() {
		$content = array();

		
		$ca_welcome_layout = get_theme_mod( 'ca_welcome_layout', 'center' );
		$class_to_add = (  $ca_welcome_layout == 'center' ) ? 'text-center' : '' ;
		if ( ! empty( $class_to_add ) ) {
			$content['class_to_add'] = $class_to_add;
		}else{
			$content['class_to_add'] = '';
		}
		

		/* translators: 1 - link to customizer setting. 2 - 'customizer' */
		$title_default          = current_user_can( 'edit_theme_options' ) ? sprintf( esc_html__( 'Change in the %s', 'calypso' ), sprintf( '<a href="%1$s" class="default-link">%2$s</a>', esc_url( admin_url( 'customize.php?autofocus&#91;control&#93;=ca_welcome_title' ) ), __( 'Customizer', 'calypso'  ) ) ) : false;

		

		$ca_welcome_title = get_theme_mod( 'ca_welcome_title', $title_default );
		
		if ( ! empty( $ca_welcome_title ) ) {
			$content['title'] = $ca_welcome_title;
		}

		/* translators: 1 - link to customizer setting. 2 - 'customizer' */
		$text_default          = current_user_can( 'edit_theme_options' ) ? sprintf( esc_html__( 'Change in the %s', 'calypso' ), sprintf( '<a href="%1$s" class="default-link">%2$s</a>', esc_url( admin_url( 'customize.php?autofocus&#91;control&#93;=ca_welcome_description' ) ), __( 'Customizer', 'calypso' ) ) ) : false;
		$ca_welcome_description = get_theme_mod( 'ca_welcome_description', $text_default );
		if ( ! empty( $ca_welcome_description ) ) {
			$content['desciption'] = $ca_welcome_description;
		}

		$button_text_default          = current_user_can( 'edit_theme_options' ) ? esc_html__( 'Change in the customizer', 'calypso' ) : false;
		$ca_welcome_btn_text = get_theme_mod( 'ca_welcome_btn_text', $button_text_default );
		if ( ! empty( $ca_welcome_btn_text ) ) {
			$content['button_text'] = $ca_welcome_btn_text;
		}

		$button_link_default          = current_user_can( 'edit_theme_options' ) ? esc_url( admin_url( 'customize.php?autofocus&#91;control&#93;=ca_welcome_btn_text' ) ) : '#';
		$ca_welcome_btn_url = get_theme_mod( 'ca_welcome_btn_url', $button_link_default );

		if ( ! empty( $ca_welcome_btn_url ) ) {
			$content['button_link'] = $ca_welcome_btn_url;
		}



		return $content;
	}

	public function get_background() {
		$ca_welcome_image = get_theme_mod( 'ca_welcome_image', get_template_directory_uri() . '/assets/images/welcome.jpg'  );
		
		return $ca_welcome_image;

	}

	public function show_content( $content ) {
		
		
		$class_to_add = $content['class_to_add'];

		$ca_welcome_layout = get_theme_mod( 'ca_welcome_layout', 'center' );



		$wrap_class   = apply_filters( 'ca_filter_welcome_content_classes', 'col-md-12' );

		if($ca_welcome_layout == 'right')
			$this->render_widget_area();
	
		?>
		
		<div class="<?php echo $wrap_class. ' ' .$class_to_add; ?>">
			<?php if ( ! empty( $content['title'] ) ) { ?>
				<h1 class="welcome-title"><?php echo wp_kses_post( $content['title'] ); ?></h1>
			<?php } ?>
			<?php if ( ! empty( $content['desciption'] ) ) { ?>
				<p class="ca-description welcome-sub-title"><?php echo wp_kses_post( $content['desciption'] ); ?></p>
			<?php } ?>
			<?php if ( ! empty( $content['button_link'] ) && ! empty( $content['button_text'] ) ) { ?>
				<div class="welcome buttons">
					<a href="<?php echo esc_url( $content['button_link'] ); ?>"
							title="<?php echo esc_html( $content['button_text'] ); ?>"
							class="btn btn-primary" <?php echo ca_is_external_url( $content['button_link'] ); ?>><?php echo esc_html( $content['button_text'] ); ?></a>
					
				</div>
			<?php } ?>
		</div>
		<?php
		if($ca_welcome_layout == 'left')
		$this->render_widget_area();
		
	}

	public function render_widget_area( ) {
		 if ( is_active_sidebar( 'welcome-widgets' ) ) : ?>
					<div class="ca-welcome-widget col-md-4">
						<?php dynamic_sidebar( 'welcome-widgets' ); ?>
					</div>
				<?php endif; 
	}

}

new Ca_Welcome_Section();