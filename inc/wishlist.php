<?php
/**
 * Wishlist: cookie for guests, user meta for logged-in customers.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

const ORPITEY_WISHLIST_COOKIE = 'orpitey_wishlist';
const ORPITEY_WISHLIST_META   = '_orpitey_wishlist';

/**
 * Parse a comma separated id list.
 *
 * @param string|array $raw Raw value.
 * @return int[]
 */
function orpitey_parse_ids( $raw ) {
	if ( is_string( $raw ) ) {
		$raw = explode( ',', $raw );
	}
	return array_values( array_unique( array_filter( array_map( 'absint', (array) $raw ) ) ) );
}

/**
 * Current wishlist product ids.
 *
 * @return int[]
 */
function orpitey_wishlist_ids() {
	if ( is_user_logged_in() ) {
		return orpitey_parse_ids( get_user_meta( get_current_user_id(), ORPITEY_WISHLIST_META, true ) );
	}
	$cookie = isset( $_COOKIE[ ORPITEY_WISHLIST_COOKIE ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ ORPITEY_WISHLIST_COOKIE ] ) ) : '';
	return orpitey_parse_ids( $cookie );
}

/**
 * Is product in the wishlist.
 *
 * @param int $product_id Product id.
 * @return bool
 */
function orpitey_in_wishlist( $product_id ) {
	return in_array( (int) $product_id, orpitey_wishlist_ids(), true );
}

/**
 * URL of the page using the wishlist template.
 *
 * @return string
 */
function orpitey_wishlist_url() {
	static $url = null;
	if ( null === $url ) {
		$pages = get_pages(
			array(
				'meta_key'   => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value' => 'page-templates/page-wishlist.php', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'number'     => 1,
			)
		);
		$url = $pages ? get_permalink( $pages[0] ) : '';
	}
	return $url;
}

/**
 * Heart toggle button.
 *
 * @param WC_Product $product Product.
 * @param string     $class   Classes.
 */
function orpitey_wishlist_button( $product, $class = 'wishlist-button' ) {
	$active = orpitey_in_wishlist( $product->get_id() );
	printf(
		'<button type="button" class="%1$s%2$s" data-wishlist="%3$d" aria-pressed="%4$s" aria-label="%5$s">%6$s</button>',
		esc_attr( $class ),
		$active ? ' is-active' : '',
		(int) $product->get_id(),
		$active ? 'true' : 'false',
		/* translators: %s: product name */
		esc_attr( sprintf( __( '%s পছন্দের তালিকায় রাখুন', 'orpitey' ), $product->get_name() ) ),
		orpitey_icon( 'heart', 16 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	);
}

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_add_inline_script(
			'orpitey-main',
			'window.orpiteyWishlist = ' . wp_json_encode(
				array(
					'ids'    => is_user_logged_in() ? orpitey_wishlist_ids() : null,
					'cookie' => ORPITEY_WISHLIST_COOKIE,
					'url'    => orpitey_wishlist_url(),
				)
			) . ';',
			'before'
		);
	},
	20
);

/**
 * Logged-in toggle.
 */
function orpitey_ajax_wishlist() {
	check_ajax_referer( 'orpitey', 'nonce' );
	if ( ! is_user_logged_in() ) {
		wp_send_json_error();
	}
	$ids = orpitey_parse_ids( isset( $_POST['ids'] ) ? sanitize_text_field( wp_unslash( $_POST['ids'] ) ) : '' );
	update_user_meta( get_current_user_id(), ORPITEY_WISHLIST_META, implode( ',', $ids ) );
	wp_send_json_success( array( 'ids' => $ids ) );
}
add_action( 'wp_ajax_orpitey_wishlist', 'orpitey_ajax_wishlist' );

// Merge the guest list into the account on login.
add_action(
	'wp_login',
	function ( $login, $user ) {
		if ( empty( $_COOKIE[ ORPITEY_WISHLIST_COOKIE ] ) ) {
			return;
		}
		$guest = orpitey_parse_ids( sanitize_text_field( wp_unslash( $_COOKIE[ ORPITEY_WISHLIST_COOKIE ] ) ) );
		$saved = orpitey_parse_ids( get_user_meta( $user->ID, ORPITEY_WISHLIST_META, true ) );
		update_user_meta( $user->ID, ORPITEY_WISHLIST_META, implode( ',', array_unique( array_merge( $saved, $guest ) ) ) );
	},
	10,
	2
);
