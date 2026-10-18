<?php
/**
 * Theme setup: supports, menus, image sizes.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'orpitey', ORPITEY_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 120,
				'width'       => 200,
				'flex-height' => true,
				'flex-width'  => true,
			)
		);

		add_theme_support(
			'woocommerce',
			array(
				'thumbnail_image_width' => 600,
				'single_image_width'    => 900,
				'product_grid'          => array(
					'default_rows'    => 3,
					'min_rows'        => 1,
					'default_columns' => 4,
					'min_columns'     => 2,
					'max_columns'     => 4,
				),
			)
		);

		register_nav_menus(
			array(
				'primary'        => __( 'হেডার মেনু', 'orpitey' ),
				'footer-support' => __( 'ফুটার: সহায়তা', 'orpitey' ),
				'footer-shop'    => __( 'ফুটার: শপ', 'orpitey' ),
			)
		);

		add_image_size( 'orpitey-card', 600, 800, true );
		add_image_size( 'orpitey-post', 800, 533, true );
	}
);

add_filter(
	'woocommerce_get_image_size_thumbnail',
	function ( $size ) {
		return array(
			'width'  => 600,
			'height' => 800,
			'crop'   => 1,
		);
	}
);

add_filter(
	'body_class',
	function ( $classes ) {
		$classes[] = 'orpitey';
		if ( class_exists( 'WooCommerce' ) && orpitey_bottom_nav_enabled() ) {
			$classes[] = 'has-bottom-nav';
		}
		return $classes;
	}
);

add_filter(
	'excerpt_length',
	function () {
		return 24;
	}
);

add_filter(
	'excerpt_more',
	function () {
		return '…';
	}
);

/**
 * Logo URL: custom logo if set, otherwise the bundled Orpitey logo.
 *
 * @return string
 */
function orpitey_logo_url() {
	$logo_id = get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$src = wp_get_attachment_image_url( $logo_id, 'full' );
		if ( $src ) {
			return $src;
		}
	}
	return ORPITEY_URI . '/assets/images/logo.png';
}
