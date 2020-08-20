<?php
/**
 * Custom control for notice
 *
 * @package calypso
 */
if ( class_exists( 'WP_Customize_Control' ) ) {

    class Ca_Notice_Control extends WP_Customize_Control {
		/**
		 * The type of control being rendered
		 */
 		public $type = 'notice';
		/**
		 * Enqueue our scripts and styles
		 */
 		public function enqueue() {
			wp_enqueue_style( 'ca-notice', get_template_directory_uri() . '/inc/customizer/custom-controls/notice/style.css' );
	
		}
		/**
		 * Render the control in the customizer
		 */
        public function render_content() {
			$allowed_html = array(
				'a' => array(
					'href' => array(),
					'title' => array(),
					'class' => array(),
					'target' => array(),
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
				'i' => array(
					'class' => array()
				),
				'span' => array(
					'class' => array(),
				),
				'code' => array(),
			);
		?>
			<div class="ca-notice-custom-control">
				<?php if( !empty( $this->label ) ) { ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php } ?>
				<?php if( !empty( $this->description ) ) { ?>
					<span class="customize-control-description"><?php echo wp_kses( $this->description, $allowed_html ); ?></span>
				<?php } ?>
			</div>
		<?php
		}
     }
     
}
?>