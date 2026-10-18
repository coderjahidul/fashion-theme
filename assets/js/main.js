/* global orpitey, orpiteyWishlist, jQuery */
( function () {
	'use strict';

	const settings = window.orpitey || {};
	const i18n = settings.i18n || {};
	const $ = window.jQuery;

	const bnDigits = ( value ) => {
		const str = String( value );
		return settings.bnDigits ? str.replace( /\d/g, ( d ) => '০১২৩৪৫৬৭৮৯'[ d ] ) : str;
	};

	const applyBnDigits = ( root ) => {
		if ( ! settings.bnDigits || ! root ) {
			return;
		}
		const walker = document.createTreeWalker( root, NodeFilter.SHOW_TEXT );
		const nodes = [];
		let node;
		while ( ( node = walker.nextNode() ) ) {
			if ( ! /[0-9]/.test( node.nodeValue ) ) {
				continue;
			}
			const parent = node.parentElement;
			if ( ! parent || parent.closest( 'script, style, textarea, noscript' ) ) {
				continue;
			}
			nodes.push( node );
		}
		nodes.forEach( ( textNode ) => {
			textNode.nodeValue = bnDigits( textNode.nodeValue );
		} );
	};

	document.addEventListener( 'DOMContentLoaded', () => applyBnDigits( document.body ) );

	/* A click leaves focus on the card button, which keeps the hover actions visible. */
	document.addEventListener( 'mouseout', ( event ) => {
		const card = event.target.closest( '.product-card' );
		if ( ! card ) {
			return;
		}
		const next = event.relatedTarget;
		if ( next && card.contains( next ) ) {
			return;
		}
		const active = document.activeElement;
		if ( active && card.contains( active ) ) {
			active.blur();
		}
	} );
	if ( $ ) {
		$( document.body ).on( 'wc_fragments_refreshed wc_fragments_loaded added_to_cart removed_from_cart updated_wc_div updated_checkout', () => {
			applyBnDigits( document.body );
		} );
	}

	/* Homepage hero slider */
	const hero = document.querySelector( '[data-hero-slider]' );
	if ( hero ) {
		const slides = Array.from( hero.querySelectorAll( '.hero__slide' ) );
		const dots = Array.from( hero.querySelectorAll( '.hero__dot' ) );
		const prev = hero.querySelector( '.hero__arrow--prev' );
		const next = hero.querySelector( '.hero__arrow--next' );
		const reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
		let index = 0;
		let timer;

		const show = ( nextIndex ) => {
			index = ( nextIndex + slides.length ) % slides.length;
			slides.forEach( ( slide, i ) => {
				const active = i === index;
				slide.classList.toggle( 'is-active', active );
				slide.setAttribute( 'aria-hidden', active ? 'false' : 'true' );
				const content = slide.querySelector( '.hero__content' );
				if ( content && ! reduceMotion ) {
					content.classList.remove( 'hero-reveal' );
					if ( active ) {
						void content.offsetWidth;
						content.classList.add( 'hero-reveal' );
					}
				}
			} );
			dots.forEach( ( dot, i ) => {
				const active = i === index;
				dot.classList.toggle( 'is-active', active );
				dot.setAttribute( 'aria-selected', active ? 'true' : 'false' );
			} );
		};

		const stop = () => {
			clearInterval( timer );
		};

		const start = () => {
			stop();
			if ( reduceMotion || slides.length < 2 ) {
				return;
			}
			timer = setInterval( () => show( index + 1 ), 6000 );
		};

		if ( prev ) {
			prev.addEventListener( 'click', () => {
				show( index - 1 );
				start();
			} );
		}
		if ( next ) {
			next.addEventListener( 'click', () => {
				show( index + 1 );
				start();
			} );
		}
		dots.forEach( ( dot, i ) => {
			dot.addEventListener( 'click', () => {
				show( i );
				start();
			} );
		} );

		hero.addEventListener( 'mouseenter', stop );
		hero.addEventListener( 'mouseleave', start );
		hero.addEventListener( 'focusin', stop );
		hero.addEventListener( 'focusout', ( event ) => {
			if ( ! hero.contains( event.relatedTarget ) ) {
				start();
			}
		} );
		hero.addEventListener( 'keydown', ( event ) => {
			if ( event.key === 'ArrowLeft' ) {
				show( index - 1 );
				start();
			} else if ( event.key === 'ArrowRight' ) {
				show( index + 1 );
				start();
			}
		} );

		start();
	}

	/* Homepage trust badges: one slide at a time on mobile */
	document.querySelectorAll( '[data-trust-slider]' ).forEach( ( slider ) => {
		const track = slider.querySelector( '.trust-badges' );
		const slides = track ? Array.from( track.querySelectorAll( '.trust-badge' ) ) : [];
		const dots = Array.from( slider.querySelectorAll( '.trust-slider__dot' ) );
		if ( ! track || slides.length < 2 ) {
			return;
		}

		const mq = window.matchMedia( '(max-width: 639px)' );
		const reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
		let index = 0;
		let timer;
		let paused = false;

		const setActive = ( next ) => {
			index = ( next + slides.length ) % slides.length;
			dots.forEach( ( dot, i ) => {
				const active = i === index;
				dot.classList.toggle( 'is-active', active );
				dot.setAttribute( 'aria-selected', active ? 'true' : 'false' );
			} );
		};

		const go = ( next ) => {
			if ( ! mq.matches ) {
				return;
			}
			setActive( next );
			track.scrollTo( {
				left: slides[ index ].offsetLeft,
				behavior: reduceMotion ? 'auto' : 'smooth',
			} );
		};

		const stop = () => {
			clearInterval( timer );
		};

		const start = () => {
			stop();
			if ( ! mq.matches || reduceMotion || paused ) {
				return;
			}
			timer = setInterval( () => go( index + 1 ), 4500 );
		};

		dots.forEach( ( dot, i ) => {
			dot.addEventListener( 'click', () => {
				go( i );
				start();
			} );
		} );

		let scrollTimer;
		track.addEventListener( 'scroll', () => {
			if ( ! mq.matches ) {
				return;
			}
			clearTimeout( scrollTimer );
			scrollTimer = setTimeout( () => {
				const left = track.scrollLeft;
				let closest = 0;
				let distance = Infinity;
				slides.forEach( ( slide, i ) => {
					const delta = Math.abs( slide.offsetLeft - left );
					if ( delta < distance ) {
						distance = delta;
						closest = i;
					}
				} );
				if ( closest !== index ) {
					setActive( closest );
				}
			}, 80 );
		}, { passive: true } );

		const pause = () => {
			paused = true;
			stop();
		};
		const resume = () => {
			paused = false;
			start();
		};

		track.addEventListener( 'touchstart', pause, { passive: true } );
		track.addEventListener( 'touchend', resume );
		track.addEventListener( 'touchcancel', resume );
		slider.addEventListener( 'mouseenter', stop );
		slider.addEventListener( 'mouseleave', start );
		slider.addEventListener( 'focusin', stop );
		slider.addEventListener( 'focusout', ( event ) => {
			if ( ! slider.contains( event.relatedTarget ) ) {
				start();
			}
		} );

		mq.addEventListener( 'change', () => {
			stop();
			track.scrollTo( { left: 0 } );
			setActive( 0 );
			start();
		} );

		start();
	} );

	/* Toast */
	let toastTimer;
	const toast = ( message ) => {
		const el = document.querySelector( '.toast' );
		if ( ! el || ! message ) {
			return;
		}
		el.textContent = message;
		el.hidden = false;
		clearTimeout( toastTimer );
		toastTimer = setTimeout( () => {
			el.hidden = true;
		}, 3200 );
	};
	window.orpiteyToast = toast;

	/* Overlay panels: mobile menu, search, cart drawer */
	let lastTrigger = null;

	const openPanel = ( id, trigger ) => {
		const panel = document.getElementById( id );
		if ( ! panel ) {
			return false;
		}
		document.querySelectorAll( '.overlay-panel.is-open, .search-overlay.is-open' ).forEach( ( p ) => {
			if ( p !== panel ) {
				closePanel( p, false );
			}
		} );
		lastTrigger = trigger || document.activeElement;
		panel.classList.add( 'is-open' );
		panel.setAttribute( 'aria-hidden', 'false' );
		if ( ! document.body.classList.contains( 'is-locked' ) ) {
			const gap = Math.max( 0, window.innerWidth - document.documentElement.clientWidth );
			document.documentElement.style.setProperty( '--scrollbar-gap', gap + 'px' );
			document.body.classList.add( 'is-locked' );
		}
		document.querySelectorAll( `[aria-controls="${ id }"]` ).forEach( ( t ) => t.setAttribute( 'aria-expanded', 'true' ) );

		const focusTarget = panel.querySelector( 'input[type="search"]' ) || panel.querySelector( '[data-close].icon-button' );
		if ( focusTarget ) {
			setTimeout( () => focusTarget.focus( { preventScroll: true } ), 60 );
		}
		return true;
	};

	const pinScroll = ( y ) => {
		const root = document.documentElement;
		root.style.scrollBehavior = 'auto';
		window.scrollTo( 0, y );
		requestAnimationFrame( () => {
			window.scrollTo( 0, y );
			root.style.scrollBehavior = '';
		} );
	};

	const closePanel = ( panel, restoreFocus = true ) => {
		if ( ! panel || ! panel.classList.contains( 'is-open' ) ) {
			return;
		}
		const scrollY = window.scrollY;
		panel.classList.remove( 'is-open' );
		panel.setAttribute( 'aria-hidden', 'true' );
		document.querySelectorAll( `[aria-controls="${ panel.id }"]` ).forEach( ( t ) => t.setAttribute( 'aria-expanded', 'false' ) );
		if ( ! document.querySelector( '.overlay-panel.is-open, .search-overlay.is-open' ) ) {
			document.body.classList.remove( 'is-locked' );
			document.documentElement.style.removeProperty( '--scrollbar-gap' );
		}
		if ( restoreFocus && lastTrigger && typeof lastTrigger.focus === 'function' ) {
			lastTrigger.focus( { preventScroll: true } );
		}
		pinScroll( scrollY );
	};

	window.orpiteyOpenCart = () => openPanel( 'cart-drawer' );

	document.addEventListener( 'click', ( event ) => {
		const opener = event.target.closest( '[data-open]' );
		if ( opener ) {
			// Stay on the cart/checkout page when the bag icon is clicked there.
			if ( opener.dataset.open === 'cart-drawer' && document.body.classList.contains( 'woocommerce-cart' ) ) {
				return;
			}
			if ( openPanel( opener.dataset.open, opener ) ) {
				event.preventDefault();
			}
			return;
		}
		const closer = event.target.closest( '[data-close]' );
		if ( closer ) {
			closePanel( closer.closest( '.overlay-panel, .search-overlay' ) );
		}
	} );

	document.addEventListener( 'keydown', ( event ) => {
		if ( event.key === 'Escape' ) {
			document.querySelectorAll( '.overlay-panel.is-open, .search-overlay.is-open' ).forEach( ( p ) => closePanel( p ) );
		}
	} );

	/* Homepage collection tabs */
	document.addEventListener( 'click', ( event ) => {
		const tab = event.target.closest( '[data-tab]' );
		if ( ! tab ) {
			return;
		}
		const section = tab.closest( 'section' );
		section.querySelectorAll( '[data-tab]' ).forEach( ( t ) => {
			const active = t === tab;
			t.classList.toggle( 'filter-button-active', active );
			t.setAttribute( 'aria-selected', active ? 'true' : 'false' );
		} );
		section.querySelectorAll( '[data-panel]' ).forEach( ( panel ) => {
			panel.hidden = panel.dataset.panel !== tab.dataset.tab;
		} );
	} );

	/* Quantity steppers for WooCommerce quantity inputs */
	const enhanceQuantities = ( root = document ) => {
		root.querySelectorAll( '.quantity:not(.qty-stepper)' ).forEach( ( wrap ) => {
			const input = wrap.querySelector( 'input.qty' );
			if ( ! input || input.type === 'hidden' || input.readOnly ) {
				return;
			}
			wrap.classList.add( 'qty-stepper' );
			if ( settings.bnDigits && ! wrap.querySelector( '.qty-stepper__slot' ) ) {
				const slot = document.createElement( 'span' );
				const shown = document.createElement( 'span' );
				slot.className = 'qty-stepper__slot';
				shown.className = 'qty-stepper__value';
				shown.setAttribute( 'aria-hidden', 'true' );
				const paint = () => {
					shown.textContent = bnDigits( input.value );
				};
				paint();
				slot.appendChild( shown );
				wrap.insertBefore( slot, input );
				slot.appendChild( input );
				input.classList.add( 'is-bn' );
				if ( $ ) {
					$( input ).on( 'input change', paint );
				} else {
					input.addEventListener( 'input', paint );
					input.addEventListener( 'change', paint );
				}
			}
			const make = ( dir, label, path ) => {
				const btn = document.createElement( 'button' );
				btn.type = 'button';
				btn.setAttribute( 'aria-label', label );
				btn.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">${ path }</svg>`;
				btn.addEventListener( 'click', () => {
					const step = parseFloat( input.step ) || 1;
					const min = input.min !== '' ? parseFloat( input.min ) : 0;
					const max = input.max !== '' ? parseFloat( input.max ) : Infinity;
					const current = parseFloat( input.value ) || 0;
					const next = Math.min( max, Math.max( min, current + dir * step ) );
					if ( next !== current ) {
						input.value = next;
						if ( $ ) {
							$( input ).trigger( 'input' ).trigger( 'change' );
						} else {
							input.dispatchEvent( new Event( 'change', { bubbles: true } ) );
						}
					}
				} );
				return btn;
			};
			const anchor = input.parentElement === wrap ? input : input.parentElement;
			wrap.insertBefore( make( -1, 'পরিমাণ কমান', '<path d="M5 12h14"/>' ), anchor );
			wrap.appendChild( make( 1, 'পরিমাণ বাড়ান', '<path d="M5 12h14"/><path d="M12 5v14"/>' ) );
		} );
	};
	window.orpiteyEnhanceQuantities = enhanceQuantities;
	enhanceQuantities();

	/* Cart updates stay on the page; the drawer opens only from the bag icon. */
	if ( $ ) {
		$( document.body ).on( 'added_to_cart', () => {
			toast( i18n.added );
		} );

		$( document.body ).on( 'updated_wc_div updated_cart_totals', () => enhanceQuantities() );

		const applyFragments = ( data ) => {
			if ( ! data || ! data.fragments ) {
				return;
			}
			$.each( data.fragments, ( selector, html ) => {
				$( selector ).replaceWith( html );
			} );
			$( document.body ).trigger( 'removed_from_cart', [ data.fragments, data.cart_hash ] );
			if ( document.body.classList.contains( 'woocommerce-cart' ) ) {
				$( document.body ).trigger( 'wc_update_cart' );
			}
		};

		const updateItem = ( item, qty ) => {
			item.classList.add( 'is-updating' );
			$.post( settings.qtyEndpoint, { key: item.dataset.key, qty, nonce: settings.nonce } )
				.done( ( data ) => {
					applyFragments( data );
					if ( ! document.body.classList.contains( 'woocommerce-checkout' ) ) {
						return;
					}
					const badge = document.querySelector( '.orpitey-cart-count' );
					const count = badge ? parseInt( badge.getAttribute( 'data-count' ), 10 ) : 0;
					if ( ! count && settings.cartUrl ) {
						window.location.href = settings.cartUrl;
						return;
					}
					$( document.body ).trigger( 'update_checkout' );
				} )
				.fail( () => {
					item.classList.remove( 'is-updating' );
					toast( i18n.error );
				} );
		};

		document.addEventListener( 'click', ( event ) => {
			const item = event.target.closest( '.drawer-item, .checkout-qty' );
			if ( ! item || item.classList.contains( 'is-updating' ) ) {
				return;
			}
			const step = event.target.closest( '[data-qty-step]' );
			if ( step ) {
				event.preventDefault();
				const current = parseInt( item.querySelector( '[data-qty]' ).dataset.qty, 10 ) || 1;
				updateItem( item, Math.max( 0, current + parseInt( step.dataset.qtyStep, 10 ) ) );
				return;
			}
			if ( event.target.closest( '[data-remove]' ) ) {
				event.preventDefault();
				updateItem( item, 0 );
			}
		} );
	}

	/* Wishlist */
	const wl = window.orpiteyWishlist || {};
	const cookieName = wl.cookie || 'orpitey_wishlist';

	const readCookie = () => {
		const match = document.cookie.match( new RegExp( '(?:^|; )' + cookieName + '=([^;]*)' ) );
		return match ? decodeURIComponent( match[ 1 ] ).split( ',' ).map( Number ).filter( Boolean ) : [];
	};

	let wishlist = Array.isArray( wl.ids ) ? wl.ids.map( Number ) : readCookie();

	const saveWishlist = () => {
		const value = encodeURIComponent( wishlist.join( ',' ) );
		document.cookie = `${ cookieName }=${ value }; path=/; max-age=${ 60 * 60 * 24 * 180 }; SameSite=Lax`;
		if ( settings.loggedIn && $ ) {
			$.post( settings.ajaxUrl, { action: 'orpitey_wishlist', nonce: settings.nonce, ids: wishlist.join( ',' ) } );
		}
	};

	const renderWishlist = () => {
		document.querySelectorAll( '[data-wishlist]' ).forEach( ( btn ) => {
			const active = wishlist.includes( Number( btn.dataset.wishlist ) );
			btn.classList.toggle( 'is-active', active );
			btn.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
		} );
		document.querySelectorAll( '.orpitey-wishlist-count' ).forEach( ( badge ) => {
			badge.textContent = bnDigits( wishlist.length );
			badge.classList.toggle( 'is-empty', wishlist.length === 0 );
		} );
	};

	document.addEventListener( 'click', ( event ) => {
		const btn = event.target.closest( '[data-wishlist]' );
		if ( ! btn ) {
			return;
		}
		event.preventDefault();
		const id = Number( btn.dataset.wishlist );
		const had = wishlist.includes( id );
		wishlist = had ? wishlist.filter( ( x ) => x !== id ) : [ ...wishlist, id ];
		saveWishlist();
		renderWishlist();
		toast( had ? i18n.wishRemove : i18n.wishAdd );

		const page = document.querySelector( '[data-wishlist-page]' );
		if ( page && had ) {
			const card = btn.closest( '.product-card' );
			if ( card ) {
				card.remove();
			}
			if ( ! wishlist.length ) {
				const empty = page.querySelector( '[data-wishlist-empty]' );
				if ( empty ) {
					empty.hidden = false;
				}
			}
		}
	} );

	if ( settings.loggedIn && Array.isArray( wl.ids ) ) {
		const value = encodeURIComponent( wishlist.join( ',' ) );
		document.cookie = `${ cookieName }=${ value }; path=/; max-age=${ 60 * 60 * 24 * 180 }; SameSite=Lax`;
	}
	renderWishlist();

	/* Shop filters: collapse the panel on small screens */
	const shopFilters = document.querySelector( '.shop-filters' );
	const shopFiltersToggle = document.querySelector( '.shop-filters__toggle' );
	if ( shopFilters && shopFiltersToggle ) {
		shopFiltersToggle.addEventListener( 'click', () => {
			const open = shopFilters.classList.toggle( 'is-open' );
			shopFiltersToggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		} );
	}

	/* Shop filters: price range and dropdowns */
	const filterForm = document.querySelector( '.shop-filters__form' );
	if ( filterForm ) {
		const range = filterForm.querySelector( '.price-range' );
		const minInput = filterForm.querySelector( '#orpitey-min-price' );
		const maxInput = filterForm.querySelector( '#orpitey-max-price' );
		const minLabel = range ? range.querySelector( '[data-price-min]' ) : null;
		const maxLabel = range ? range.querySelector( '[data-price-max]' ) : null;
		const fill = range ? range.querySelector( '.price-range__fill' ) : null;

		const formatPrice = ( amount ) => {
			const digits = bnDigits( Math.round( amount ).toString().replace( /\B(?=(\d{3})+(?!\d))/g, ',' ) );
			const symbol = range ? range.dataset.symbol || '৳' : '৳';
			const pos = range ? range.dataset.pos || 'right_space' : 'right_space';
			if ( pos === 'left' ) {
				return symbol + digits;
			}
			if ( pos === 'left_space' ) {
				return symbol + ' ' + digits;
			}
			if ( pos === 'right' ) {
				return digits + symbol;
			}
			return digits + ' ' + symbol;
		};

		const paintRange = () => {
			if ( ! range || ! minInput || ! maxInput ) {
				return;
			}
			const floor = Number( range.dataset.floor );
			const ceil = Number( range.dataset.ceil );
			let min = Number( minInput.value );
			let max = Number( maxInput.value );
			if ( min > max ) {
				if ( document.activeElement === minInput ) {
					min = max;
					minInput.value = String( min );
				} else {
					max = min;
					maxInput.value = String( max );
				}
			}
			const span = Math.max( 1, ceil - floor );
			if ( fill ) {
				fill.style.left = ( ( min - floor ) / span ) * 100 + '%';
				fill.style.width = ( ( max - min ) / span ) * 100 + '%';
			}
			if ( minLabel ) {
				minLabel.textContent = formatPrice( min );
			}
			if ( maxLabel ) {
				maxLabel.textContent = formatPrice( max );
			}
			const near = ( ceil - floor ) * 0.08;
			minInput.style.zIndex = max - min <= Math.max( near, 1 ) ? '4' : '2';
			maxInput.style.zIndex = '3';
		};

		const submitFilters = () => {
			if ( range && minInput && maxInput ) {
				const floor = Number( range.dataset.floor );
				const ceil = Number( range.dataset.ceil );
				if ( Number( minInput.value ) <= floor ) {
					minInput.disabled = true;
				}
				if ( Number( maxInput.value ) >= ceil ) {
					maxInput.disabled = true;
				}
			}
			filterForm.querySelectorAll( 'select[name], input[type="hidden"]' ).forEach( ( field ) => {
				if ( field.value === '' ) {
					field.disabled = true;
				}
			} );
			filterForm.submit();
		};

		if ( minInput && maxInput ) {
			minInput.addEventListener( 'input', paintRange );
			maxInput.addEventListener( 'input', paintRange );
			minInput.addEventListener( 'change', submitFilters );
			maxInput.addEventListener( 'change', submitFilters );
			paintRange();
		}

		filterForm.querySelectorAll( 'select[name^="filter_"]' ).forEach( ( select ) => {
			select.addEventListener( 'change', submitFilters );
		} );

		const catSelect = filterForm.querySelector( '[data-filter-nav]' );
		if ( catSelect ) {
			catSelect.addEventListener( 'change', () => {
				if ( catSelect.value ) {
					window.location.href = catSelect.value;
				}
			} );
		}
	}
}() );
