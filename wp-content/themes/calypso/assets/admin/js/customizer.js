/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {



   // Site title and description.
   wp.customize( 'custom_logo', function( value ) {
		value.bind( function( to ) {
			$( '.navbar-brand.logo' ).text( to );
		} );
	} );
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.menu-shortcut' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );


	/**
     * This handles the customizer shortcuts.
     * Send events on click etc.
     */
    $.caCustomize = {
		'init': function () {
			this.addMenuShortcut();
		
			
		},
	
		/**
         * Add shortcut button for primary menu.
         */
        'addMenuShortcut' : function () {
			var primaryMenu = $('.navbar-nav');
            var menuShortcutHtml = '<span class="menu-shortcut customize-partial-edit-shortcut customize-partial-edit-shortcut-primary-menu"><button class="customize-partial-edit-shortcut-button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z"></path></svg></button></span>';
			primaryMenu.append(menuShortcutHtml);
            this.handleMenuShortcutClick();
		},
		/**
         * Handle the click of shortcut of the menu
         */
        'handleMenuShortcutClick': function () {
            $( '.menu-shortcut' ).on( 'click', function() {
				wp.customize.preview.send('trigger-focus-menu');
			});
        }
	};

	$.caCustomize.init();
	

} )( jQuery );
