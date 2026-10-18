<?php
/**
 * Slide-out cart drawer shell. The body is replaced by WooCommerce fragments.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="overlay-panel overlay-panel--right" id="cart-drawer" aria-hidden="true">
	<div class="overlay-panel__backdrop" data-close></div>
	<aside class="overlay-panel__sheet cart-drawer" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'শপিং ব্যাগ', 'orpitey' ); ?>">
		<div class="drawer-header">
			<h2 class="drawer-title"><?php orpitey_the_icon( 'bag', 20 ); ?><?php esc_html_e( 'আপনার ব্যাগ', 'orpitey' ); ?></h2>
			<button type="button" class="icon-button" data-close aria-label="<?php esc_attr_e( 'ব্যাগ বন্ধ করুন', 'orpitey' ); ?>"><?php orpitey_the_icon( 'x', 20 ); ?></button>
		</div>
		<?php orpitey_cart_drawer_content(); ?>
	</aside>
</div>
