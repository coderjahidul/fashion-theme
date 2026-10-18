<?php
/**
 * Styles and scripts.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'wp_enqueue_scripts',
	function () {
		$ver = ORPITEY_VERSION;
		$css = ORPITEY_URI . '/assets/css/';
		$js  = ORPITEY_URI . '/assets/js/';

		$fonts = orpitey_google_fonts_url();
		if ( $fonts ) {
			wp_enqueue_style( 'orpitey-fonts', $fonts, array(), null );
		}
		wp_enqueue_style( 'orpitey-tokens', $css . 'tokens.css', array(), $ver );
		wp_enqueue_style( 'orpitey-base', $css . 'base.css', array( 'orpitey-tokens' ), $ver );
		wp_enqueue_style( 'orpitey-components', $css . 'components.css', array( 'orpitey-base' ), $ver );
		wp_enqueue_style( 'orpitey-layout', $css . 'layout.css', array( 'orpitey-components' ), $ver );
		wp_enqueue_style( 'orpitey-pages', $css . 'pages.css', array( 'orpitey-layout' ), $ver );

		$deps = array();
		if ( class_exists( 'WooCommerce' ) ) {
			wp_enqueue_style( 'orpitey-woocommerce', $css . 'woocommerce.css', array( 'orpitey-layout' ), $ver );
			wp_enqueue_script( 'wc-cart-fragments' );
			$deps = array( 'jquery', 'wc-cart-fragments' );
		}

		wp_enqueue_script( 'orpitey-main', $js . 'main.js', $deps, $ver, true );
		wp_localize_script(
			'orpitey-main',
			'orpitey',
			array(
				'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
				'qtyEndpoint' => class_exists( 'WC_AJAX' ) ? WC_AJAX::get_endpoint( 'orpitey_update_qty' ) : '',
				'cartUrl'     => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '',
				'nonce'       => wp_create_nonce( 'orpitey' ),
				'loggedIn'    => is_user_logged_in(),
				'bnDigits'    => orpitey_bn_digits_enabled(),
				'i18n'        => array(
					'added'        => __( 'ব্যাগে যোগ হয়েছে', 'orpitey' ),
					'error'        => __( 'কিছু একটা সমস্যা হয়েছে, আবার চেষ্টা করুন।', 'orpitey' ),
					'wishAdd'      => __( 'পছন্দের তালিকায় রাখুন', 'orpitey' ),
					'wishRemove'   => __( 'পছন্দের তালিকা থেকে সরান', 'orpitey' ),
					'chooseOption' => __( 'অনুগ্রহ করে সাইজ/রঙ বাছাই করুন', 'orpitey' ),
				),
			)
		);

		if ( function_exists( 'is_product' ) && is_product() ) {
			wp_enqueue_script( 'orpitey-product', $js . 'product.js', array( 'jquery', 'orpitey-main' ), $ver, true );
		}

		if ( is_singular( 'post' ) && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
);

add_filter(
	'wp_resource_hints',
	function ( $urls, $relation ) {
		if ( 'preconnect' === $relation ) {
			$urls[] = 'https://fonts.googleapis.com';
			$urls[] = array(
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			);
		}
		return $urls;
	},
	10,
	2
);

// The theme ships its own WooCommerce styling.
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

add_action(
	'wp_enqueue_scripts',
	function () {
		$post = get_post();
		if ( ! $post || false === strpos( $post->post_content, '<!-- wp:woocommerce/' ) ) {
			wp_dequeue_style( 'wc-blocks-style' );
		}
	},
	100
);
