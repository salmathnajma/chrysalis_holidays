( function( $ ) {
    $.calypso = {
        init: function() {
			this.navSearch(),
			this.setControlClasses(),
			//this.fixHeaderPadding(),
			this.headerSpacingFrontpage()
        },
        navSearch: function () {
			$( '.toggle-search' ).on( 'click', function () {
				var navSearching = $( '.nav-searching' );
				$( '#site-navigation' ).toggleClass( 'nav-searching' );//changed=#site-navigation instead of search-nav
				navSearching.find( '.nav-search' ).addClass( 'is-focused' );
				navSearching.find( '.nav-search' ).find( '.search-field' ).focus();
				$( this ).find( 'i' )
					.fadeOut( 200, function () {
						$( this ).toggleClass( 'fa-search' );
						$( this ).toggleClass( 'fa-times' );
					} ).fadeIn( 200 );
			} );
		},
		setControlClasses: function () {
			/*
			var searchForm = $( '.search-form label' );
			if ( typeof (searchForm) !== 'undefined' ) {

				var searchField = $( searchForm ).find( '.search-field' );
				if ( $( searchField ).attr( 'value' ) === '' ) {
					$( searchForm ).addClass( 'label-floating is-empty' );
				} else {
					$( searchForm ).addClass( 'label-floating' );
				}

				$.utilitiesFunctions.addControlLabel( searchField );
			}

			var wooSearchForm = $( '.woocommerce-product-search' );
			if ( typeof (wooSearchForm) !== 'undefined' ) {

				var wooSearchField = $( wooSearchForm ).find( '.search-field' );
				if ( $( wooSearchField ).attr( 'value' ) === '' ) {
					$( wooSearchForm ).addClass( 'label-floating is-empty' );
				} else {
					$( wooSearchForm ).addClass( 'label-floating' );
				}

				$.utilitiesFunctions.addControlLabel( wooSearchField );
			}
			*/

			if ( typeof $( '.contact_submit_wrap' ) !== 'undefined' ) {
				$( '.pirate-forms-submit-button' ).addClass( 'btn btn-primary' );
			}

			/*
			if ( typeof $( '.form_captcha_wrap' ) !== 'undefined' ) {
				if ( $( '.form_captcha_wrap' ).hasClass( 'col-sm-4' ) ) {
					$( '.form_captcha_wrap' ).removeClass( 'col-sm-6' );
				}
				if ( $( '.form_captcha_wrap' ).hasClass( 'col-lg-6' ) ) {
					$( '.form_captcha_wrap' ).removeClass( 'col-lg-6' );
				}
				$( '.form_captcha_wrap' ).addClass( 'col-md-12' );
			}
			*/

			if ( typeof $( 'form' ) !== 'undefined' ) {
				$( 'form' ).addClass( 'form-group' );
			}

			if ( typeof $( 'input' ) !== 'undefined' ) {
				if ( typeof $( 'input[type="text"]' ) !== 'undefined' ) {
					$( 'input[type="text"]' ).addClass( 'form-control' );
				}

				if ( typeof $( 'input[type="email"]' ) !== 'undefined' ) {
					$( 'input[type="email"]' ).addClass( 'form-control' );
				}

				if ( typeof $( 'input[type="url"]' ) !== 'undefined' ) {
					$( 'input[type="url"]' ).addClass( 'form-control' );
				}

				if ( typeof $( 'input[type="password"]' ) !== 'undefined' ) {
					$( 'input[type="password"]' ).addClass( 'form-control' );
				}

				if ( typeof $( 'input[type="tel"]' ) !== 'undefined' ) {
					$( 'input[type="tel"]' ).addClass( 'form-control' );
				}

				if ( typeof $( 'input[type="search"]' ) !== 'undefined' ) {
					$( 'input[type="search"]' ).addClass( 'form-control' );
				}

				if ( typeof $( 'input.select2-input' ) !== 'undefined' ) {
					$( 'input.select2-input' ).removeClass( 'form-control' );
				}
			}

			if ( typeof $( 'textarea' ) !== 'undefined' ) {
				$( 'textarea' ).addClass( 'form-control' );
			}

			if ( typeof $( '.form-control' ) !== 'undefined' ) {
				$( '.form-control' ).parent().addClass( 'form-group' );

				$( window ).on(
					'scroll', function () {
						$( '.form-control' ).parent().addClass( 'form-group' );
					}
				);
			}
		},
		fixHeaderPadding: function() {
      
			var navbar_height = $( '.navbar-fixed-top' ).outerHeight();


			var mobile_media = window.matchMedia( '(max-width: 600px)' );
			if ( $( '#wpadminbar' ).length && mobile_media.matches ) {
				$( '.wrapper.classic-blog' ).find( '.main' ).css( 'margin-top', navbar_height - 46 );
				$( '.carousel .item .container' ).css( 'padding-top', navbar_height + 50 - 46 );
				if ( $( '.home.page.page-template-default .navbar' ).hasClass( 'no-slider' ) ) {
					$( '.home.page.page-template-default .main' ).css( 'margin-top', navbar_height - 46 );
				}
			} else {
				$( '.wrapper.classic-blog' ).find( '.main' ).css( 'margin-top', navbar_height );
				$( '.carousel .item .container' ).css( 'padding-top', navbar_height + 50 );
				if ( $( '.home.page.page-template-default .navbar' ).hasClass( 'no-slider' ) ) {
					$( '.home.page.page-template-default .main' ).css( 'margin-top', navbar_height );
				}
			}

			if ( $( window ).width() > 768 ) {
				var beaver_offset = 40;
				if ( $( '.wrapper.classic-blog' ).length < 1 ) {
					$( '.pagebuilder-section' ).css( 'padding-top', navbar_height );
				} else {
					$( '.pagebuilder-section' ).css( 'padding-top', 0 );
				}
				$( '.fl-builder-edit .pagebuilder-section' ).css( 'padding-top', navbar_height + beaver_offset );
				$( '.page-header.header-small .container' ).css( 'padding-top', navbar_height + 100 );
				var headerHeight = $( '.single-product .page-header.header-small' ).height();
				var offset = headerHeight + 100;
				$( '.single-product .page-header.header-small .container' ).css( 'padding-top', headerHeight - offset );

				var marginOffset = headerHeight - navbar_height - 172;
				$( '.woocommerce.single-product .blog-post .col-md-12 > div[id^=product].product' ).css( 'margin-top', -marginOffset );
			} else {
				$( '.page-header.header-small .container , .woocommerce.single-product .blog-post .col-md-12 > div[id^=product].product' ).removeAttr( 'style' );
			}
			if ( $( '.no-content' ).length ) {
				$( '.page-header.header-small' ).css( 'min-height', navbar_height + 230 );
			}
		},
		headerSpacingFrontpage: function() {
			if ( this.inIframe() && this.isMobileUA() ) {
				return;
			}
			if ( $( '.home .carousel' ).length > 0 ) {
				var pageHeader = $( '.page-header' ),
					windowWidth = $( window ).width(),
					windowHeight = $( window ).height();
				if ( windowWidth > 768 ) {
					pageHeader.css( 'min-height', (windowHeight * 0.9) ); // 90% of window height
				} else {
					pageHeader.css( 'min-height', (windowHeight) );
				}
			}
        },
        inIframe: function() {
            return window.self !== window.top
        },
        isMobileUA: function() {
            return navigator.userAgent.match(/(iPhone|iPod|iPad|Android|BlackBerry|BB10|mobi|tablet|opera mini|nexus 7)/i)
        },
	},
	$.utilities = {

		'debounce': function ( func, wait, immediate ) {
			var timeout;
			return function () {
				var context = this, args = arguments;
				var later = function () {
					timeout = null;
					if ( !immediate ) {
						func.apply( context, args );
					}
				};
				var callNow = immediate && !timeout;
				clearTimeout( timeout );
				timeout = setTimeout( later, wait );
				if ( callNow ) {
					func.apply( context, args );
				}
			};
		},

		'isElementInViewport': function ( elem ) {
			var $elem = $( elem );

			// Get the scroll position of the page.
			var viewportTop = $( window ).scrollTop();
			var viewportBottom = viewportTop + $( window ).height();

			// Get the position of the element on the page.
			var elemTop = Math.round( $elem.offset().top );
			var elemBottom = elemTop + $elem.height();

			return ((elemTop < viewportBottom) && (elemBottom > viewportTop));
		},

		'verifyNavHeight': function () {
			var window_width = $( window ).width();
			var navHeight;
			if ( window_width < 768 ) {
				navHeight = $( '.navbar' ).outerHeight();
			} else {
				navHeight = ($( '.navbar' ).outerHeight() - 15);
			}
			return navHeight;
		},

		'getWidth': function () {
			if ( this.innerWidth ) {
				return this.innerWidth;
			}

			if ( document.documentElement && document.documentElement.clientWidth ) {
				return document.documentElement.clientWidth;
			}

			if ( document.body ) {
				return document.body.clientWidth;
			}
		},

		'addControlLabel': function ( field ) {
			var placeholderField = field.attr( 'placeholder' );
			field.removeAttr( 'placeholder' );
			$( '<label class="control-label"> ' + placeholderField + ' </label>' ).insertBefore( field );
		}
	},
	$.navigation = {
		init: function() {
			
			this.handleDropdowns(),
			this.handleResponsiveDropdowns()
        },
		'handleDropdowns': function () {
			var windowWidth = window.innerWidth;
			if ( windowWidth < 991 ) {
				return false;
			}

			var self = this;
			$( '.caret-wrap' ).on( 'touchstart', function ( e ) {
				e.preventDefault();
				e.stopPropagation();
				var menuItem = $( this ).closest( 'li' );
				if ( $( menuItem ).hasClass( 'dropdown-submenu' ) ) {
					$( menuItem ).siblings().removeClass( 'open' ).find( 'dropdown-submenu' ).removeClass( 'open' );
					$( menuItem ).siblings().find( '.caret-open' ).removeClass( 'caret-open' );
				}
				if ( $( this ).closest( 'li' ).parent().is( '.nav' ) ) {
					self.clearDropdowns();
				}
				$( this ).toggleClass( 'caret-open' );
				$( this ).closest( '.dropdown' ).toggleClass( 'open' );
				self.createOverlay();
			} );
			return false;
		},
		'handleResponsiveDropdowns': function () {
			
			var windowWidth = window.innerWidth;
			if ( windowWidth > 768 ) {
				return false;
			}
			$( '.navbar .dropdown > a .caret-wrap' ).on( 'click touchend',
				function ( event ) {
					var caret = $( this );
					event.preventDefault();
					event.stopPropagation();
					$( caret ).toggleClass( 'caret-open' );
					//Open dropdown.
					$( caret ).parent().siblings().toggleClass( 'open' );
				}
			);
		},
		'createOverlay': function () {
			var dropdownOverlay = $( '.dropdown-helper-overlay' );
			if ( dropdownOverlay.length > 0 ) {
				return false;
			}
			var self = this;
			dropdownOverlay = document.createElement( 'div' );
			dropdownOverlay.setAttribute( 'class', 'dropdown-helper-overlay' );
			$( '#main-navigation' ).append( dropdownOverlay );
			$( '.dropdown-helper-overlay' ).on( 'touchstart click', function () {
				this.remove();
				self.clearDropdowns();
			} );
			return false;
		},
		'clearDropdowns': function () {
			$( '.dropdown.open' ).removeClass( 'open' );
			$( '.caret-wrap.caret-open' ).removeClass( 'caret-open' );
		},

	}
} )( jQuery ),
jQuery(document).ready(function($) {

	jQuery.calypso.init();
	jQuery.navigation.init();
	$(window).on("scroll", function() {
		if($(window).scrollTop() > 80) {
			$("#booking-widget").addClass("active");
			$("#mobile-booking-widget").addClass("active");
		} else {
			$("#booking-widget").removeClass("active");
			$("#mobile-booking-widget").removeClass("active");
		}
	});


	
}),
jQuery(window).resize(function() {
   jQuery.calypso.fixHeaderPadding(), jQuery.calypso.headerSpacingFrontpage()
});