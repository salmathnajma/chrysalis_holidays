<?php
/**
 * Custom control for alpha color picker selection
 *
 * @package calypso
 */
if ( class_exists( 'WP_Customize_Control' ) ) {

    class Ca_Alpha_Color_Control extends WP_Customize_Control {
        /**
		 * The type of control being rendered
		 */
		public $type = 'alpha-color';
		/**
		 * Add support for palettes to be passed in.
		 *
		 * Supported palette values are true, false, or an array of RGBa and Hex colors.
		 */
		public $palette;
		/**
		 * Add support for showing the opacity value on the slider handle.
		 */
		public $show_opacity;
        /**
		 * Enqueue our scripts and styles
		 */
		public function enqueue() {
            wp_enqueue_script( 'ca-alpha-color', trailingslashit( get_template_directory_uri() ) . '/inc/customizer/custom-controls/alpha-color/script.js', array( 'jquery' ), '1.0', true );
       
			wp_enqueue_style( 'ca-alpha-color', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/custom-controls/alpha-color/style.css', array(), '1.1', 'all' );
        }

        /**
		 * Render the control in the customizer
		 */
		public function render_content() {

			// Process the palette
			if ( is_array( $this->palette ) ) {
				$palette = implode( '|', $this->palette );
			} else {
				// Default to true.
				$palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';
			}

			// Support passing show_opacity as string or boolean. Default to true.
			$show_opacity = ( false === $this->show_opacity || 'false' === $this->show_opacity ) ? 'false' : 'true';

			?>
				<label>
					<?php // Output the label and description if they were passed in.
					if ( isset( $this->label ) && '' !== $this->label ) {
						echo '<span class="customize-control-title">' . sanitize_text_field( $this->label ) . '</span>';
					}
					if ( isset( $this->description ) && '' !== $this->description ) {
						echo '<span class="description customize-control-description">' . sanitize_text_field( $this->description ) . '</span>';
					} ?>
				</label>
				<input class="alpha-color-control" type="text" data-show-opacity="<?php echo $show_opacity; ?>" data-palette="<?php echo esc_attr( $palette ); ?>" data-default-color="<?php echo esc_attr( $this->settings['default']->default ); ?>" <?php $this->link(); ?>  />
			<?php
		}
    }
}