<?php
/**
 * Empty cart page.
 *
 * @package Orpitey
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

remove_action( 'woocommerce_cart_is_empty', 'wc_empty_cart_message', 10 );
do_action( 'woocommerce_cart_is_empty' );
?>
<div class="empty-state">
	<span class="empty-state__icon"><?php orpitey_the_icon( 'bag', 26 ); ?></span>
	<h2 class="empty-state__title"><?php esc_html_e( 'আপনার ব্যাগ খালি', 'orpitey' ); ?></h2>
	<p><?php esc_html_e( 'পছন্দের শাড়ি বা থ্রি-পিস ব্যাগে যোগ করুন', 'orpitey' ); ?></p>
	<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
		<a class="btn btn-primary wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'সংগ্রহ দেখুন', 'orpitey' ); ?> <?php orpitey_the_icon( 'arrow-right', 16 ); ?></a>
	<?php endif; ?>
</div>
