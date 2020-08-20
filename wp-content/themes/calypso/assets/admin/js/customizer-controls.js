/**
 *
 * @package calypso
 */

 jQuery( document ).ready(
	function ($) {
		'use strict';

		$.aboutBackground = {

			 init: function () {
				this.focusMenu();
		    },

        	/**
			* Focus menu when the user clicks on customizer shortcut of the menu.
			*/
			focusMenu: function () {
				wp.customize.previewer.bind(
					'trigger-focus-menu', function() {
						wp.customize.section( 'menu_locations' ).focus();
					}
				);
			},

           
		};

				$.aboutBackground.init();


				$("#customize-control-ca_welcome_layout").change(function(){

						if ( wp.customize.instance( 'ca_welcome_layout' ).get() === 'center' ) {
							$( '#customize-control-sidebars_widgets-welcome-widgets' ).hide();
							$( '#sub-accordion-section-sidebar-widgets-welcome-widgets .customize-control-widget_form' ).hide();
						} else {
							$( '#sub-accordion-section-sidebar-widgets-welcome-widgets .customize-control-widget_form' ).show();
							$( '#customize-control-sidebars_widgets-welcome-widgets' ).show();
						}
				});

	}
);