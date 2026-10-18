<?php
/**
 * Simple contact form handler for the Contact page template.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

/**
 * Handle submission.
 */
function orpitey_handle_contact() {
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$back = remove_query_arg( 'contact', $back );

	if ( ! isset( $_POST['orpitey_contact_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['orpitey_contact_nonce'] ), 'orpitey_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $back ) );
		exit;
	}

	// Honeypot: bots fill every field.
	if ( ! empty( $_POST['website'] ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'sent', $back ) );
		exit;
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	if ( '' === $name || '' === $message || ( '' === $phone && ! is_email( $email ) ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'invalid', $back ) );
		exit;
	}

	$to      = get_option( 'admin_email' );
	$subject = sprintf( '[%s] %s — %s', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), __( 'নতুন বার্তা', 'orpitey' ), $name );
	$body    = sprintf( "নাম: %s\nফোন: %s\nইমেইল: %s\n\n%s", $name, $phone, $email, $message );
	$headers = is_email( $email ) ? array( 'Reply-To: ' . $name . ' <' . $email . '>' ) : array();

	$sent = wp_mail( $to, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'contact', $sent ? 'sent' : 'error', $back ) );
	exit;
}
add_action( 'admin_post_nopriv_orpitey_contact', 'orpitey_handle_contact' );
add_action( 'admin_post_orpitey_contact', 'orpitey_handle_contact' );
