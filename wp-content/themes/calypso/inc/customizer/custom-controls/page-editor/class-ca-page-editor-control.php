<?php
/**
 * Custom control for page editor
 *
 * @package calypso
 */
if ( class_exists( 'WP_Customize_Control' ) ) {

    class Ca_Page_Editor_Control extends WP_Customize_Control {
        public $type = 'ca_page_editor';

        public function enqueue() {
            wp_enqueue_script( 'ca-page-editor', trailingslashit( get_template_directory_uri() ) . '/inc/customizer/custom-controls/page-editor/script.js', array( 'jquery' ), '1.0', true );
        }

        /**
	 * Render the content on the theme customizer page
	 */
	public function render_content() {
		?>
		<label>
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_textarea( $this->value() ); ?>" id="<?php echo esc_attr( $this->id ); ?>" class="editorfield">
			<button data-editor-id="<?php echo esc_attr( $this->id ); ?>" class="button edit-content-button"><?php _e( '(Edit)', 'calypso' ); ?></button>
		</label>
		<?php
	}
    }
}