<?php
/**
 * Custom control helper for page editor
 *
 * @package calypso
 */

class Ca_Page_Editor_Helper  {
	/**
	 * Initialize Customizer Page Editor Helper.
	 */
	public function init() {
        add_action( 'customize_controls_print_footer_scripts', array( $this, 'customize_editor' ), 1 );
        
		add_filter( 'tiny_mce_before_init', array( $this, 'override_tinymce_options' ) );
		add_filter( 'wp_default_editor', array( $this, 'change_editor_mode_to_html' ) );
       // $this->filter_content();
       
	}

	/**
	 * Display editor for page editor control.
	 *
	 * @since 1.1.51
	 */
	public function customize_editor() {
		?>
		<div id="wp-editor-widget-container" style="display: none;">
			<a class="close" href="javascript:WPEditorWidget.hideEditor();"><span class="icon"></span></a>
			<div class="editor">
				<?php
				$settings = array(
					'tinymce' => array(
						'content_style' => '',
						'rows'          => 55,
						'setup'         => "function (editor) {
                 			editor.onInit.add(function(){
                 			var iframe = document.getElementById('wpeditorwidget_ifr');
                 			iframe.style.height = '260px';
                 			});
                		}",
					),
				);
				wp_editor( '', 'wpeditorwidget', $settings );
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Allow all HTML tags in TinyMce editor.
	 */
	public function override_tinymce_options( $init_array ) {
		$opts                                  = '*[*]';
		$init_array['valid_elements']          = $opts;
		$init_array['extended_valid_elements'] = $opts;

		return $init_array;
	}

	/**
	 * Change the default mode of the editor to html when using the tinyMce editor in customizer.
	*/
	public function change_editor_mode_to_html( $editor_mode ) {
		if ( is_customize_preview() && function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			if ( ! isset( $screen->id ) ) {
				return $editor_mode;
			}
			if ( $screen->id === 'customize' ) {
				return 'tmce';
			}
		}

		return $editor_mode;
	}

	/**
	 * This filter is used to filter the content of the post after it is retrieved from the database and before it is
	 * printed to the screen.
	 */
	private function filter_content() {
		global $wp_embed;
		add_filter( 'ca_text', 'wptexturize' );
		add_filter( 'ca_text', 'convert_smilies' );
		add_filter( 'ca_text', 'convert_chars' );
		add_filter( 'ca_text', 'wpautop' );
		add_filter( 'ca_text', 'shortcode_unautop' );
		add_filter( 'ca_text', 'do_shortcode' );
		add_filter( 'ca_text', array( $wp_embed, 'run_shortcode' ) );
		add_filter( 'ca_text', array( $wp_embed, 'autoembed' ) );
	}
}
