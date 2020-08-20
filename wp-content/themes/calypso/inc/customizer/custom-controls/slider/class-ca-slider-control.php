<?php
/**
 * Custom control for slider 
 *
 * @package calypso
 */
if ( class_exists( 'WP_Customize_Control' ) ) {

    class Ca_Slider_Control extends WP_Customize_Control {

        public $type = 'slider_control';
         /**
		 * Enqueue our scripts and styles
		 */
		public function enqueue() {
            wp_enqueue_script( 'ca-slider', trailingslashit( get_template_directory_uri() ) . '/inc/customizer/custom-controls/slider/script.js', array( 'jquery' ), '1.0', true );
       
			wp_enqueue_style( 'ca-slider', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/custom-controls/slider/style.css', array(), '1.1', 'all' );
        }
        
        /**
		 * Render the control in the customizer
		 */
		public function render_content() {
            ?>
                <div class="slider-custom-control">
                    <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span><input type="number" id="<?php echo esc_attr( $this->id ); ?>" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $this->value() ); ?>" class="customize-control-slider-value" <?php $this->link(); ?> />
                    <div class="slider" slider-min-value="<?php echo esc_attr( $this->input_attrs['min'] ); ?>" slider-max-value="<?php echo esc_attr( $this->input_attrs['max'] ); ?>" slider-step-value="<?php echo esc_attr( $this->input_attrs['step'] ); ?>"></div><span class="slider-reset dashicons dashicons-image-rotate" slider-reset-value="<?php echo esc_attr( $this->value() ); ?>"></span>
                </div>
            <?php
            }
    }
}