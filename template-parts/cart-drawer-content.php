<?php
/**
 * Cart drawer body: line items, subtotal and actions.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

$cart = WC()->cart;

if ( ! $cart || $cart->is_empty() ) :
	?>
	<div class="drawer-empty">
		<span class="drawer-empty__icon"><?php orpitey_the_icon( 'bag', 26 ); ?></span>
		<div>
			<p class="drawer-empty__title"><?php esc_html_e( 'আপনার ব্যাগ খালি', 'orpitey' ); ?></p>
			<p class="drawer-empty__text"><?php esc_html_e( 'পছন্দের শাড়ি বা থ্রি-পিস ব্যাগে যোগ করুন', 'orpitey' ); ?></p>
		</div>
		<a class="btn btn-primary btn-sm" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'সংগ্রহ দেখুন', 'orpitey' ); ?> <?php orpitey_the_icon( 'arrow-right', 16 ); ?></a>
	</div>
	<?php
	return;
endif;
?>
<ul class="drawer-items">
	<?php
	foreach ( $cart->get_cart() as $key => $item ) :
		$product = $item['data'];
		if ( ! $product || ! $product->exists() || $item['quantity'] <= 0 ) {
			continue;
		}
		$link = $product->is_visible() ? $product->get_permalink( $item ) : '';
		?>
		<li class="drawer-item" data-key="<?php echo esc_attr( $key ); ?>">
			<a class="drawer-item__image" href="<?php echo esc_url( $link ); ?>">
				<?php echo $product->get_image( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
			<div class="drawer-item__body">
				<p class="drawer-item__name"><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></p>
				<div class="drawer-item__meta"><?php echo wc_get_formatted_cart_item_data( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<div class="drawer-item__row">
					<div class="qty-stepper qty-stepper--sm">
						<button type="button" data-qty-step="-1" aria-label="<?php esc_attr_e( 'পরিমাণ কমান', 'orpitey' ); ?>"><?php orpitey_the_icon( 'minus', 14 ); ?></button>
						<span class="qty-stepper__value" data-qty="<?php echo esc_attr( $item['quantity'] ); ?>"><?php echo esc_html( orpitey_bn( $item['quantity'] ) ); ?></span>
						<button type="button" data-qty-step="1" aria-label="<?php esc_attr_e( 'পরিমাণ বাড়ান', 'orpitey' ); ?>"><?php orpitey_the_icon( 'plus', 14 ); ?></button>
					</div>
					<span class="drawer-item__price"><?php echo $cart->get_product_subtotal( $product, $item['quantity'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				</div>
			</div>
			<button type="button" class="drawer-item__remove" data-remove aria-label="<?php esc_attr_e( 'এই পণ্যটি সরান', 'orpitey' ); ?>"><?php orpitey_the_icon( 'trash', 16 ); ?></button>
		</li>
	<?php endforeach; ?>
</ul>
<div class="drawer-footer">
	<div class="drawer-subtotal">
		<span><?php esc_html_e( 'সাবটোটাল', 'orpitey' ); ?></span>
		<strong><?php echo $cart->get_cart_subtotal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
	</div>
	<p class="drawer-note"><?php esc_html_e( 'ডেলিভারি চার্জ চেকআউটে যোগ হবে।', 'orpitey' ); ?></p>
	<a class="btn btn-primary btn-block" href="<?php echo esc_url( wc_get_checkout_url() ); ?>"><?php esc_html_e( 'অর্ডার করুন', 'orpitey' ); ?> <?php orpitey_the_icon( 'arrow-right', 16 ); ?></a>
	<a class="btn btn-outline btn-block" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'ব্যাগ দেখুন', 'orpitey' ); ?></a>
</div>
