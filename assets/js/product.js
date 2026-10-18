/* global jQuery, orpitey */
( function ( $ ) {
	'use strict';

	const i18n = ( window.orpitey && window.orpitey.i18n ) || {};
	const toast = window.orpiteyToast || ( () => {} );

	/* Gallery */
	const gallery = document.querySelector( '[data-gallery]' );
	const mainImg = gallery ? gallery.querySelector( '.product-gallery__main img' ) : null;
	const original = mainImg ? { src: mainImg.src, srcset: mainImg.getAttribute( 'srcset' ) || '', full: mainImg.dataset.full || '' } : null;

	const showImage = ( src, srcset, full ) => {
		if ( ! mainImg || ! src || mainImg.src === src ) {
			if ( mainImg && full ) {
				mainImg.dataset.full = full;
			}
			return;
		}
		mainImg.classList.add( 'is-switching' );
		const done = () => mainImg.classList.remove( 'is-switching' );
		mainImg.addEventListener( 'load', done, { once: true } );
		if ( srcset ) {
			mainImg.setAttribute( 'srcset', srcset );
		} else {
			mainImg.removeAttribute( 'srcset' );
		}
		if ( full ) {
			mainImg.dataset.full = full;
		}
		mainImg.src = src;
		setTimeout( done, 600 );
	};

	if ( gallery ) {
		gallery.addEventListener( 'click', ( event ) => {
			const thumb = event.target.closest( '.product-gallery__thumb' );
			if ( ! thumb ) {
				return;
			}
			gallery.querySelectorAll( '.product-gallery__thumb' ).forEach( ( t ) => t.classList.toggle( 'is-active', t === thumb ) );
			showImage( thumb.dataset.src, thumb.dataset.srcset, thumb.dataset.full );
		} );
	}

	const zoomDialog = document.querySelector( '[data-gallery-dialog]' );
	const zoomImg = zoomDialog ? zoomDialog.querySelector( '[data-zoom-img]' ) : null;
	const zoomStage = zoomDialog ? zoomDialog.querySelector( '[data-zoom-stage]' ) : null;
	const zoomCount = zoomDialog ? zoomDialog.querySelector( '[data-zoom-count]' ) : null;
	let zoomScale = 1;
	let panX = 0;
	let panY = 0;
	let dragging = false;
	let dragOriginX = 0;
	let dragOriginY = 0;
	let lightboxSlides = [];
	let slideIndex = 0;

	const bnDigit = ( value ) => {
		const str = String( value );
		return window.orpitey && window.orpitey.bnDigits ? str.replace( /\d/g, ( digit ) => '০১২৩৪৫৬৭৮৯'[ digit ] ) : str;
	};

	const paintZoom = ( animate ) => {
		if ( ! zoomImg ) {
			return;
		}
		zoomImg.style.transition = animate ? 'transform .2s' : 'none';
		zoomImg.style.transform = 'translate(' + panX + 'px, ' + panY + 'px) scale(' + zoomScale + ')';
		if ( zoomStage ) {
			zoomStage.classList.toggle( 'is-zoomed', zoomScale > 1 );
		}
	};

	const setZoom = ( next, animate ) => {
		zoomScale = Math.min( 3, Math.max( 1, next ) );
		if ( zoomScale === 1 ) {
			panX = 0;
			panY = 0;
		}
		paintZoom( animate !== false );
	};

	const collectSlides = () => {
		const thumbs = gallery ? [ ...gallery.querySelectorAll( '.product-gallery__thumb' ) ] : [];
		if ( thumbs.length ) {
			return thumbs.map( ( thumb ) => ( {
				full: thumb.dataset.full || thumb.dataset.src,
				alt: mainImg ? mainImg.alt : '',
			} ) );
		}
		if ( ! mainImg ) {
			return [];
		}
		return [ { full: mainImg.dataset.full || mainImg.currentSrc || mainImg.src, alt: mainImg.alt || '' } ];
	};

	const showSlide = ( index ) => {
		if ( ! zoomImg || ! lightboxSlides.length ) {
			return;
		}
		slideIndex = ( index + lightboxSlides.length ) % lightboxSlides.length;
		const slide = lightboxSlides[ slideIndex ];
		zoomImg.alt = slide.alt || '';
		if ( zoomImg.getAttribute( 'src' ) !== slide.full ) {
			zoomImg.src = slide.full;
		}
		setZoom( 1, false );
		if ( zoomCount ) {
			zoomCount.textContent = bnDigit( slideIndex + 1 ) + ' / ' + bnDigit( lightboxSlides.length );
		}
		if ( zoomDialog ) {
			zoomDialog.classList.toggle( 'is-single', lightboxSlides.length < 2 );
		}
	};

	const openZoom = () => {
		if ( ! zoomDialog || ! mainImg || ! zoomImg || ! zoomDialog.showModal ) {
			return;
		}
		lightboxSlides = collectSlides();
		const current = mainImg.dataset.full || mainImg.currentSrc || mainImg.src;
		let index = lightboxSlides.findIndex( ( slide ) => slide.full === current );
		if ( index < 0 ) {
			lightboxSlides.unshift( { full: current, alt: mainImg.alt || '' } );
			index = 0;
		}
		showSlide( index );
		zoomDialog.showModal();
	};

	if ( gallery && zoomDialog ) {
		gallery.addEventListener( 'click', ( event ) => {
			if ( ! event.target.closest( '[data-gallery-zoom]' ) && ! event.target.closest( '.product-gallery__image' ) ) {
				return;
			}
			event.preventDefault();
			openZoom();
		} );

		zoomDialog.addEventListener( 'click', ( event ) => {
			if ( event.target.closest( '[data-zoom-in]' ) ) {
				setZoom( zoomScale + 0.5 );
			} else if ( event.target.closest( '[data-zoom-out]' ) ) {
				setZoom( zoomScale - 0.5 );
			} else if ( event.target.closest( '[data-lightbox-prev]' ) ) {
				showSlide( slideIndex - 1 );
			} else if ( event.target.closest( '[data-lightbox-next]' ) ) {
				showSlide( slideIndex + 1 );
			} else if ( event.target === zoomImg ) {
				setZoom( zoomScale > 1 ? 1 : 2 );
			} else if ( ! event.target.closest( 'button' ) ) {
				zoomDialog.close();
			}
		} );

		zoomDialog.addEventListener( 'keydown', ( event ) => {
			if ( event.key === 'ArrowRight' ) {
				event.preventDefault();
				showSlide( slideIndex + 1 );
			} else if ( event.key === 'ArrowLeft' ) {
				event.preventDefault();
				showSlide( slideIndex - 1 );
			}
		} );

		zoomDialog.addEventListener( 'close', () => setZoom( 1, false ) );

		if ( zoomStage ) {
			zoomStage.addEventListener( 'wheel', ( event ) => {
				event.preventDefault();
				setZoom( zoomScale + ( event.deltaY < 0 ? 0.25 : -0.25 ) );
			}, { passive: false } );

			zoomStage.addEventListener( 'pointerdown', ( event ) => {
				if ( zoomScale <= 1 || event.target.closest( 'button' ) ) {
					return;
				}
				dragging = true;
				dragOriginX = event.clientX - panX;
				dragOriginY = event.clientY - panY;
				zoomStage.setPointerCapture( event.pointerId );
			} );

			zoomStage.addEventListener( 'pointermove', ( event ) => {
				if ( ! dragging ) {
					return;
				}
				panX = event.clientX - dragOriginX;
				panY = event.clientY - dragOriginY;
				paintZoom( false );
			} );

			zoomStage.addEventListener( 'pointerup', () => {
				dragging = false;
			} );
		}
	}

	/* Variation swatches */
	const colorMap = {
		লাল: '#b3261e', red: '#b3261e', মেরুন: '#6d1a1f', maroon: '#6d1a1f', marron: '#6d1a1f', wine: '#5e1b2b',
		নীল: '#4a6f95', blue: '#4a6f95', navy: '#1f2f55', 'royal blue': '#2a4aa0', আকাশি: '#8fb8de', sky: '#8fb8de',
		কালো: '#1d1b19', black: '#1d1b19', সাদা: '#ffffff', white: '#ffffff', 'off white': '#f3ecdf', আইভরি: '#f5efe0', ivory: '#f5efe0', cream: '#f1e6cf',
		সবুজ: '#3f6b45', green: '#3f6b45', olive: '#6b6b2f', 'olive green': '#6b6b2f', teal: '#1f6f6b',
		হলুদ: '#d6a529', yellow: '#d6a529', mustard: '#c9972b', সোনালি: '#c9a14a', gold: '#c9a14a', 'antique gold': '#b08d4a', 'rose gold': '#c98e7e',
		গোলাপি: '#d98aa5', pink: '#d98aa5', peach: '#f0b89a', বেগুনি: '#6a3d7c', purple: '#6a3d7c', lavender: '#b7a3d4', magenta: '#a3246b',
		কমলা: '#d9772b', orange: '#d9772b', বাদামি: '#7a5236', brown: '#7a5236', ধূসর: '#8c8c8c', grey: '#8c8c8c', gray: '#8c8c8c', silver: '#c0c0c0',
	};

	const swatchColor = ( label, value ) => {
		const keys = [ label, value ].filter( Boolean ).map( ( s ) => String( s ).toLowerCase().replace( /-/g, ' ' ).trim() );
		for ( const key of keys ) {
			if ( colorMap[ key ] ) {
				return colorMap[ key ];
			}
			if ( window.CSS && CSS.supports( 'color', key.replace( /\s/g, '' ) ) ) {
				return key.replace( /\s/g, '' );
			}
		}
		return '';
	};

	const buildPicker = ( picker ) => {
		const select = picker.querySelector( 'select' );
		if ( ! select ) {
			return;
		}
		const isColor = picker.dataset.type === 'color';
		let group = picker.querySelector( '.swatches' );
		if ( ! group ) {
			group = document.createElement( 'div' );
			group.className = 'swatches' + ( isColor ? ' swatches--color' : '' );
			group.setAttribute( 'role', 'radiogroup' );
			picker.appendChild( group );
			picker.classList.add( 'is-enhanced' );
		}
		group.innerHTML = '';
		Array.from( select.options ).forEach( ( opt ) => {
			if ( ! opt.value ) {
				return;
			}
			const btn = document.createElement( 'button' );
			btn.type = 'button';
			btn.className = 'swatch';
			btn.dataset.value = opt.value;
			btn.setAttribute( 'role', 'radio' );
			btn.setAttribute( 'aria-checked', select.value === opt.value ? 'true' : 'false' );
			btn.setAttribute( 'aria-label', opt.text );
			if ( isColor ) {
				const dot = document.createElement( 'span' );
				dot.style.setProperty( '--swatch', opt.dataset.color || swatchColor( opt.text, opt.value ) || 'var(--muted)' );
				btn.appendChild( dot );
				btn.title = opt.text;
			} else {
				btn.textContent = opt.text;
			}
			if ( opt.disabled ) {
				btn.classList.add( 'is-disabled' );
			}
			if ( select.value === opt.value ) {
				btn.classList.add( 'is-selected' );
			}
			btn.addEventListener( 'click', () => {
				if ( btn.classList.contains( 'is-disabled' ) ) {
					return;
				}
				const next = select.value === opt.value ? '' : opt.value;
				$( select ).val( next ).trigger( 'change' );
			} );
			group.appendChild( btn );
		} );

		const label = picker.closest( 'tr' ) && picker.closest( 'tr' ).querySelector( 'th label' );
		if ( label ) {
			let picked = label.querySelector( '.picked-value' );
			if ( ! picked ) {
				picked = document.createElement( 'span' );
				picked.className = 'picked-value';
				label.appendChild( picked );
			}
			const current = select.options[ select.selectedIndex ];
			picked.textContent = select.value && current ? ': ' + current.text : '';
		}
	};

	const $form = $( 'form.variations_form' );
	const refreshPickers = () => document.querySelectorAll( '.variation-picker' ).forEach( buildPicker );

	if ( $form.length ) {
		refreshPickers();
		$form.on( 'woocommerce_update_variation_values reset_data', () => setTimeout( refreshPickers, 0 ) );
		$form.on( 'change', '.variations select', () => setTimeout( refreshPickers, 0 ) );

		$form.on( 'found_variation', ( event, variation ) => {
			if ( variation.image && variation.image.src ) {
				showImage( variation.image.src, variation.image.srcset, variation.image.full_src );
			}
		} );
		$form.on( 'reset_image', () => {
			if ( original ) {
				showImage( original.src, original.srcset, original.full );
			}
		} );
	}

	/* Add to cart without leaving the page; "buy now" submits normally */
	document.querySelectorAll( '.product-cart form.cart' ).forEach( ( form ) => {
		if ( ( form.getAttribute( 'method' ) || 'get' ).toLowerCase() !== 'post' ) {
			return;
		}
		form.addEventListener( 'submit', ( event ) => {
			const submitter = event.submitter;
			if ( submitter && submitter.name === 'orpitey_buy_now' ) {
				return;
			}
			const button = form.querySelector( '.single_add_to_cart_button' );
			if ( ! button || button.classList.contains( 'disabled' ) ) {
				return;
			}
			event.preventDefault();

			let data;
			try {
				data = new FormData( form, submitter || button );
			} catch ( e ) {
				data = new FormData( form );
			}
			if ( ! data.has( 'add-to-cart' ) && button.value ) {
				data.append( 'add-to-cart', button.value );
			}

			button.classList.add( 'is-loading' );
			fetch( form.action || window.location.href, { method: 'POST', body: data, credentials: 'same-origin' } )
				.then( ( res ) => res.text() )
				.then( ( html ) => {
					const doc = new DOMParser().parseFromString( html, 'text/html' );
					const error = doc.querySelector( '.woocommerce-error' );
					if ( error ) {
						toast( error.textContent.trim().replace( /\s+/g, ' ' ) );
						return;
					}
					$( document.body ).one( 'wc_fragments_refreshed', () => {
						toast( i18n.added );
					} );
					$( document.body ).trigger( 'wc_fragment_refresh' );
				} )
				.catch( () => toast( i18n.error ) )
				.finally( () => button.classList.remove( 'is-loading' ) );
		} );
	} );

	/* Size guide dialog */
	document.addEventListener( 'click', ( event ) => {
		const opener = event.target.closest( '[data-open-dialog]' );
		if ( opener ) {
			const dialog = document.getElementById( opener.dataset.openDialog );
			if ( dialog && dialog.showModal ) {
				dialog.showModal();
			}
			return;
		}
		if ( event.target.closest( '[data-close-dialog]' ) ) {
			event.target.closest( 'dialog' ).close();
		} else if ( event.target.tagName === 'DIALOG' ) {
			event.target.close();
		}
	} );

	/* Open the reviews accordion when jumping to #reviews */
	const openReviews = () => {
		if ( window.location.hash.indexOf( 'review' ) !== -1 || window.location.hash.indexOf( 'comment' ) !== -1 ) {
			const acc = document.getElementById( 'reviews-accordion' );
			if ( acc ) {
				acc.open = true;
			}
		}
	};
	document.querySelectorAll( 'a[href="#reviews"]' ).forEach( ( a ) => a.addEventListener( 'click', () => setTimeout( openReviews, 0 ) ) );
	openReviews();
}( jQuery ) );
