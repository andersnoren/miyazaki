// ======================================================================= Namespace
var miyazaki = miyazaki || {},
	$ = jQuery;


// ======================================================================= Helper Functions

/* OUTPUT AJAX ERRORS */

function miyazakiAjaxErrors( jqXHR, exception ) {
	if ( jqXHR.status === 0 ) {
		alert( 'Not connect.n Verify Network.' );
	} else if ( jqXHR.status == 404 ) {
		alert( 'Requested page not found. [404]' );
	} else if ( jqXHR.status == 500 ) {
		alert( 'Internal Server Error [500].' );
	} else if ( exception === 'parsererror' ) {
		alert( 'Requested JSON parse failed.' );
	} else if ( exception === 'timeout' ) {
		alert( 'Time out error.' );
	} else if ( exception === 'abort' ) {
		alert( 'Ajax request aborted.' );
	} else {
		alert( 'Uncaught Error.n' + jqXHR.responseText );
	}
}


// ======================================================================= Interval Scroll
miyazaki.intervalScroll = {

	init: function() {

		didScroll = false;

		// Check for the scroll event
		$( window ).on( 'scroll load', function() {
			didScroll = true;
		} );

		// Once every 100ms, check if we have scrolled, and if we have, do the intensive stuff
		setInterval( function() {
			if ( didScroll ) {
				didScroll = false;

				// When this triggers, we know that we have scrolled
				$( window ).triggerHandler( 'did-interval-scroll' );

			}

		}, 250 );

	},

} // miyazaki.intervalScroll


// ======================================================================= Toggles
miyazaki.toggles = {

	init: function() {

		// Do the toggle
		miyazaki.toggles.toggle();

	},

	toggle: function() {

		$( '*[data-toggle-target]' ).on( 'click toggle', function( e ) {

			var $toggle = $( this );

			// Get our targets
			var targetString = $toggle.data( 'toggle-target' );

			if ( targetString == 'next' ) {
				var $target = $toggle.next();
			} else {
				var $target = $( targetString );
			}

			$target.trigger( 'will-be-toggled' );

			// Get the class to toggle, if specified
			var classToToggle = $toggle.data( 'class-to-toggle' ) ? $toggle.data( 'class-to-toggle' ) : 'active';

			// Toggle the target of the clicked toggle
			if ( $toggle.data( 'toggle-type' ) == 'slidetoggle' ) {
				var duration = $toggle.data( 'toggle-duration' ) ? $toggle.data( 'toggle-duration' ) : '400';
				$target.slideToggle( duration );
			} else {
				$target.toggleClass( classToToggle );
			}

			// Toggle the toggles
			$( '*[data-toggle-target="' + targetString + '"]' ).each( function() {
				$( this ).toggleClass( 'active' );
			} );

			// Check whether to set focus
			if ( $toggle.is( '.active' ) && $toggle.data( 'set-focus' ) ) {
				var $focusElement = $( $toggle.data( 'set-focus' ) );

				if ( $focusElement.length ) {
					$focusElement.focus();
				}
			}

			// Check whether to lock the scroll
			if ( $toggle.data( 'lock-scroll' ) ) {
				miyazaki.scrollLock.setTo( true );
			} else if ( $toggle.data( 'unlock-scroll' ) ) {
				miyazaki.scrollLock.setTo( false );
			} else if ( $toggle.data( 'toggle-scroll-lock' ) ) {
				miyazaki.scrollLock.setTo();
			}

			$target.trigger( 'toggled' );

			return false;

		} );
	}

} // miyazaki.toggles


// ======================================================================= Cover Modals
miyazaki.coverModals = {

	init: function() {

		if ( $( '.cover-modal' ).length ) {

			// Add body class when cover modals are active
			miyazaki.coverModals.addBodyClassOnActive();

			// Reset modal classes on resize
			miyazaki.coverModals.hideModalOnResize();

			// Close the search modal when the escape key is pressed
			miyazaki.coverModals.closeSearchOnEscape();

		}

	},

	addBodyClassOnActive: function() {

		$( '.cover-modal' ).on( 'toggled', function() {

			if ( $( this ).hasClass( 'active' ) ) {
				$( 'body' ).addClass( 'showing-modal' );
			} else {
				$( 'body' ).removeClass( 'showing-modal' );
			}

		} );

	},

	hideModalOnResize: function() {

		$( window ).on( 'resize', function() {

			var $activeModal = $( '.cover-modal.active' );

			if ( $activeModal.length ) {

				var winWidth = $( window ).width(),
					untoggleAbove = $activeModal.data( 'untoggle-above' ),
					untoggleBelow = $activeModal.data( 'untoggle-below' );

				if ( untoggleAbove && ( winWidth > untoggleAbove ) || untoggleBelow && ( winWidth < untoggleBelow ) ) {
					miyazaki.coverModals.untoggleModal( $activeModal );
				}
			}

		} );

	},

	closeSearchOnEscape: function() {

		$( document ).keyup( function( e ) {
			if ( e.keyCode == 27 && $( '.search-overlay' ).hasClass( 'active' ) ) {
				$( '.search-toggle' ).trigger( 'click' );
			}
		} );

	},

	// Untoggle a modal
	untoggleModal: function( $modal ) {

		$modalToggle = false;

		// If the modal has specified the string (ID or class) used by toggles to target it, untoggle the toggles with that target string.
		// The modal-target-string must match the string toggles use to target the modal.
		if ( $modal.data( 'modal-target-string' ) ) {
			var modalTargetClass = $modal.data( 'modal-target-string' ),
				$modalToggle = $( '*[data-toggle-target="' + modalTargetClass + '"]' ).first();
		}

		// If a modal toggle exists, trigger it so all of the toggle options are included
		if ( $modalToggle && $modalToggle.length ) {
			$modalToggle.trigger( 'click' );

		// If one doesn't exist, just hide the modal
		} else {
			$modal.removeClass( 'active' );
			$( 'body' ).removeClass( 'showing-modal' );
		}

	}

} // miyazaki.coverModals


// ======================================================================= Element in View
miyazaki.elementInView = {

	init: function() {

		$targets = $( '.do-spot' );
		miyazaki.elementInView.run( $targets );

		// Rerun on AJAX content loaded
		$( window ).on( 'ajax-content-loaded', function() {
			$targets = $( '.do-spot' );
			miyazaki.elementInView.run( $targets );
		} );

	},

	run: function( $targets ) {

		if ( $targets.length ) {

			// Add class indicating the elements will be spotted
			$targets.each( function() {
				$( this ).addClass( 'will-be-spotted' );
			} );

			miyazaki.elementInView.handleFocus( $targets );
		}

	},

	handleFocus: function( $targets ) {

		winHeight = $( window ).height();

		// Get dimensions of window outside of scroll for performance
		$( window ).on( 'load resize orientationchange', function() {
			winHeight = $( window ).height();
		} );

		$( window ).on( 'resize orientationchange did-interval-scroll', function() {

			var winTop 		= $( window ).scrollTop();
				winBottom 	= winTop + winHeight;

			// Check for our targets
			$targets.each( function() {

				var $this = $( this );

				if ( miyazaki.elementInView.isVisible( $this, checkAbove = true ) ) {
					$this.addClass( 'spotted' ).triggerHandler( 'spotted' );
				}

			} );

		} );

	},

	// Determine whether the element is in view
	isVisible: function( $elem, checkAbove ) {

		if ( typeof checkAbove === 'undefined' ) {
			checkAbove = false;
		}

		var winHeight 				= $( window ).height();

		var docViewTop 				= $( window ).scrollTop(),
			docViewBottom			= docViewTop + winHeight,
			docViewLimit 			= docViewBottom - 50;

		var elemTop 				= $elem.offset().top,
			elemBottom 				= $elem.offset().top + $elem.outerHeight();

		// If checkAbove is set to true, which is default, return true if the browser has already scrolled past the element
		if ( checkAbove && ( elemBottom <= docViewBottom ) ) {
			return true;
		}

		// If not, check whether the scroll limit exceeds the element top
		return ( docViewLimit >= elemTop );

	}

} // miyazaki.elementInView


// =======================================================================  Mobile Menu
miyazaki.mobileMenu = {

	init: function() {

		// On mobile menu toggle, scroll to the top
		miyazaki.mobileMenu.onToggle();

		// On screen resize, check whether to unlock scroll and match the mobile menu wrapper padding to the site header
		miyazaki.mobileMenu.resizeChecks();

	},

	onToggle: function() {

		$( '.mobile-menu-wrapper' ).on( 'will-be-toggled', function() {
			window.scrollTo( 0, 0 );
		} );

	},

	resizeChecks: function() {

		$( window ).on( 'load resize orientationchange', function() {

			// Update the mobile menu wrapper top padding to match the height of the header
			var $siteHeader = $( '#site-header' ),
				$mobileMenuWrapper = $( '.mobile-menu-wrapper' );
				
			var paddedHeaderHeight = $siteHeader.outerHeight() + 35;

			$mobileMenuWrapper.css( { 'padding-top': paddedHeaderHeight + 'px' } );
		} );

	}

} // miyazaki.mobileMenu


// =======================================================================  Resize videos
miyazaki.intrinsicRatioEmbeds = {

	init: function() {

		// Resize videos after their container
		var vidSelector = 'iframe, object, video';
		var resizeVideo = function( sSel ) {
			$( sSel ).each( function() {
				var $video = $( this ),
					$container = $video.parent(),
					iTargetWidth = $container.width();

				if ( ! $video.attr( 'data-origwidth' ) ) {
					$video.attr( 'data-origwidth', $video.attr( 'width' ) );
					$video.attr( 'data-origheight', $video.attr( 'height' ) );
				}

				var ratio = iTargetWidth / $video.attr( 'data-origwidth' );

				$video.css( 'width', iTargetWidth + 'px' );
				$video.css( 'height', ( $video.attr( 'data-origheight' ) * ratio ) + 'px' );
			});
		};

		resizeVideo( vidSelector );

		$( window ).resize( function() {
			resizeVideo( vidSelector );
		} );

	},

} // miyazaki.intrinsicRatioEmbeds


// ======================================================================= Masonry
miyazaki.masonry = {

	init: function() {

		$wrapper = $( '.posts' );

		if ( $wrapper.length ) {

			$grid = $wrapper.imagesLoaded( function() {

				$grid = $wrapper.masonry( {
					columnWidth: 		'.grid-sizer',
					itemSelector: 		'.preview',
					percentPosition: 	true,
					stagger: 			0,
					transitionDuration: 0,
				} );

			} );

			$grid.on( 'layoutComplete', function() {
				$( '.posts' ).css( 'opacity', 1 );
				$( window ).triggerHandler( 'scroll' );
			} );

		}

	}

} // miyazaki.masonry


// =======================================================================  Smooth Scroll
miyazaki.smoothScroll = {

	init: function() {

		// Scroll to on-page elements by hash.
		$( 'a[href*="#"]' ).not( '[href="#"]' ).not( '[href="#0"]' ).not( '.disable-hash-scroll' ).on( 'click', function( e ) {
			if ( location.pathname.replace(/^\//, '' ) == this.pathname.replace(/^\//, '' ) && location.hostname == this.hostname ) {
				$target = $( this.hash ).length ? $( this.hash ) : $( '[name=' + this.hash.slice(1) + ']' );
				miyazaki.smoothScroll.scrollToTarget( $target, $( this ) );
				e.preventDefault();
			}
		} );

		// Scroll to elements specified with a data attribute.
		$( document ).on( 'click', '*[data-scroll-to]', function( e ) {
			var $target = $( $( this ).data( 'scroll-to' ) );
			miyazaki.smoothScroll.scrollToTarget( $target, $( this ) );
			e.preventDefault();
		} );

	},

	// Scroll to target.
	scrollToTarget: function( $target, $clickElem ) {

		if ( $target.length ) {

			var additionalOffset 	= 0,
				timeOutTime			= 0;

			// Close any parent modal before calculating offset and scrolling.
			// Also, set a timeout, to make sure elements have the correct offset before we scroll.
			if ( $clickElem && $clickElem.closest( '.cover-modal' ) ) {
				miyazaki.coverModals.untoggleModal( $clickElem.closest( '.cover-modal' ) );
				timeOutTime = 5;
			}

			setTimeout( function() {

				// Determine offset.
				var scrollOffset = $target.offset().top + additionalOffset;

				// Scroll to position.
				miyazaki.smoothScroll.scrollToPosition( scrollOffset );

			}, timeOutTime );

		}

	},

	// Scroll to position.
	scrollToPosition: function( scrollOffset ) {

		// Animate.
		$( 'html, body' ).animate( {
			scrollTop: scrollOffset,
		}, 500, function() {
			$( window ).trigger( 'did-interval-scroll' );
		} );

	}

} // miyazaki.smoothScroll


// =======================================================================  Scroll Lock
miyazaki.scrollLock = {

	init: function() {

		// Init variables
		window.scrollLocked = false,
		window.prevScroll = {
			scrollLeft : $( window ).scrollLeft(),
			scrollTop  : $( window ).scrollTop()
		},
		window.prevLockStyles = {},
		window.lockStyles = {
			'overflow-y' : 'scroll',
			'position'   : 'fixed',
			'width'      : '100%'
		};

		// Instantiate cache in case someone tries to unlock before locking
		miyazaki.scrollLock.saveStyles();

	},

	// Save context's inline styles in cache
	saveStyles: function() {

		var styleAttr = $( 'html' ).attr( 'style' ),
			styleStrs = [],
			styleHash = {};

		if ( ! styleAttr ) {
			return;
		}

		styleStrs = styleAttr.split( /;\s/ );

		$.each( styleStrs, function serializeStyleProp( styleString ) {
			if ( ! styleString ) {
				return;
			}

			var keyValue = styleString.split( /\s:\s/ );

			if ( keyValue.length < 2 ) {
				return;
			}

			styleHash[ keyValue[ 0 ] ] = keyValue[ 1 ];
		} );

		$.extend( prevLockStyles, styleHash );
	},

	// Lock the scroll (do not call this directly)
	lock: function() {

		var appliedLock = {};

		if ( scrollLocked ) {
			return;
		}

		// Save scroll state and styles
		prevScroll = {
			scrollLeft : $( window ).scrollLeft(),
			scrollTop  : $( window ).scrollTop()
		};

		miyazaki.scrollLock.saveStyles();

		// Compose our applied CSS, with scroll state as styles
		$.extend( appliedLock, lockStyles, {
			'left' : - prevScroll.scrollLeft + 'px',
			'top'  : - prevScroll.scrollTop + 'px'
		} );

		// Then lock styles and state
		$( 'html' ).css( appliedLock );
		$( window ).scrollLeft( 0 ).scrollTop( 0 );

		scrollLocked = true;
	},

	// Unlock the scroll (do not call this directly)
	unlock: function() {

		if ( ! scrollLocked ) {
			return;
		}

		// Revert styles and state
		$( 'html' ).attr( 'style', $( '<x>' ).css( prevLockStyles ).attr( 'style' ) || '' );
		$( window ).scrollLeft( prevScroll.scrollLeft ).scrollTop( prevScroll.scrollTop );

		scrollLocked = false;
	},

	// Call this to lock or unlock the scroll
	setTo: function( on ) {

		// If an argument is passed, lock or unlock accordingly
		if ( arguments.length ) {
			if ( on ) {
				miyazaki.scrollLock.lock();
			} else {
				miyazaki.scrollLock.unlock();
			}
			// If not, toggle to the inverse state
		} else {
			if ( scrollLocked ) {
				miyazaki.scrollLock.unlock();
			} else {
				miyazaki.scrollLock.lock();
			}
		}

	},

} // miyazaki.scrollLock


// ==================================================================== Load More
miyazaki.loadMore = {

	init: function() {

		var $pagination = $( '#pagination' );

		// First, check that there's a pagination
		if ( $pagination.length ) {

			// Default values for variables
			window.loading = false;
			window.lastPage = false;

			miyazaki.loadMore.prepare( $pagination );

		}

	},

	prepare: function( $pagination ) {

		// Get the query arguments from the pagination element
		var query_args = JSON.parse( $pagination.attr( 'data-query-args' ) );

		// If we're already at the last page, exit out here
		if ( query_args.paged == query_args.max_num_pages ) {
			$pagination.addClass( 'last-page' );
		} else {
			$pagination.removeClass( 'last-page' );
		}

		// Get the load more setting
		var loadMoreType = 'button';
		if ( $( 'body' ).hasClass( 'pagination-type-scroll' ) ) {
			loadMoreType = 'scroll';
		} else if ( $( 'body' ).hasClass( 'pagination-type-links' ) ) {
			// No JS needed – exit out
			return;
		}

		// Do the appropriate load more detection, depending on the type
		if ( loadMoreType == 'scroll' ) {
			miyazaki.loadMore.detectScroll( $pagination, query_args );
		} else if ( loadMoreType == 'button' ) {
			miyazaki.loadMore.detectButtonClick( $pagination, query_args );
		}

	},

	// Load more on scroll
	detectScroll: function( $pagination, query_args ) {

		$( window ).on( 'did-interval-scroll', function() {

			// If it's the last page, or we're already loading, we're done here
			if ( lastPage || loading ) {
				return;
			}

			var paginationOffset 	= $pagination.offset().top,
				winOffset 			= $( window ).scrollTop() + $( window ).outerHeight();

			// If the bottom of the window is below the top of the pagination, start loading
			if ( ( winOffset > paginationOffset ) ) {
				miyazaki.loadMore.loadPosts( $pagination, query_args );
			}

		} );

	},

	// Load more on click
	detectButtonClick: function( $pagination, query_args ) {

		// Load on click
		$( '#load-more' ).on( 'click', function() {

			// Make sure we aren't already loading
			if ( loading ) {
				return;
			}

			miyazaki.loadMore.loadPosts( $pagination, query_args );
			return false;
		} );

	},

	// Load the posts
	loadPosts: function( $pagination, query_args ) {

		// We're now loading
		loading = true;
		$pagination.addClass( 'loading' ).removeClass( 'last-page' );

		// Increment paged to indicate another page has been loaded
		query_args.paged++;

		// Prepare the query args for submission
		var json_query_args = JSON.stringify( query_args );

		$.ajax({
			url: miyazaki_ajax_load_more.ajaxurl,
			type: 'post',
			data: {
				action: 'miyazaki_ajax_load_more',
				json_data: json_query_args
			},
			success: function( result ) {

				// Get the results
				var $result = $( result ),
					$articleWrapper = $( $pagination.data( 'load-more-target' ) );

				// If there are no results, we're at the last page
				if ( ! $result.length ) {
					loading = false;
					$articleWrapper.addClass( 'no-results' );
					$pagination.addClass( 'last-page' ).removeClass( 'loading' );
				}

				if ( $result.length ) {

					$articleWrapper.removeClass( 'no-results' );

					// Wait for the images to load
					$result.imagesLoaded( function() {

						// Append the results
						$articleWrapper.append( $result ).masonry( 'appended', $result ).masonry();

						$( window ).triggerHandler( 'ajax-content-loaded' );
						$( window ).triggerHandler( 'did-interval-scroll' );

						// Update history
						miyazaki.loadMore.updateHistory( query_args.paged );

						// We're now finished with the loading
						loading = false;
						$pagination.removeClass( 'loading' );

						// If that was the last page, make sure we don't check for any more
						if ( query_args.paged == query_args.max_num_pages ) {
							$pagination.addClass( 'last-page' );
							lastPage = true;
							return;
						} else {
							$pagination.removeClass( 'last-page' );
							lastPage = false;
						}

						

					} );

				}

			},

			error: function( jqXHR, exception ) {
				miyazakiAjaxErrors( jqXHR, exception );
			}
		} );

	},

	// Update browser history
    updateHistory: function( paged ) {

		var newUrl,
			currentUrl = document.location.href;

		// If currentUrl doesn't end with a slash, append one
		if ( currentUrl.substr( currentUrl.length - 1 ) !== '/' ) {
			currentUrl += '/';
		}

		var hasPaginationRegexp = new RegExp( '^(.*/page)/[0-9]*/(.*$)' );

		if ( hasPaginationRegexp.test( currentUrl ) ) {
			newUrl = currentUrl.replace( hasPaginationRegexp, '$1/' + paged + '/$2' );
		} else {
			var beforeSearchReplaceRegexp = new RegExp( '^([^?]*)(\\??.*$)' );
			newUrl = currentUrl.replace( beforeSearchReplaceRegexp, '$1page/' + paged + '/$2' );
		}

		history.pushState( {}, '', newUrl );

	}

} // Load More




/*	-----------------------------------------------------------------------------------------------
	Focus Management
--------------------------------------------------------------------------------------------------- */

miyazaki.focusManagement = {

	init: function() {

		// Add and remove a class from dropdown menu items on focus
		miyazaki.focusManagement.dropdownFocus();

	},

	// Add and remove a class from dropdown menu items on focus
	dropdownFocus: function() {
		$( '.main-menu a' ).on( 'blur focus', function( e ) {
			$( this ).parents( 'li.menu-item-has-children' ).toggleClass( 'focus' );
		} );
	}

} // miyazaki.focusManagement


// ======================================================================= Function calls
$( document ).ready( function( ) {

	miyazaki.intervalScroll.init();						// Interval scroll
	miyazaki.toggles.init();							// Toggles
	miyazaki.coverModals.init();						// Cover modal specifics
	miyazaki.elementInView.init();						// Check for element in view
	miyazaki.mobileMenu.init();							// Mobile menu
	miyazaki.intrinsicRatioEmbeds.init();				// Resize embeds
	miyazaki.masonry.init();							// Masonry grid
	miyazaki.smoothScroll.init();						// Smooth scrolls to anchor links
	miyazaki.loadMore.init();							// Load more posts
	miyazaki.scrollLock.init();							// Handle locking of the scroll
	miyazaki.focusManagement.init();					// Focus functionality

} );
