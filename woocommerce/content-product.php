<?php
/**
 * Product card inside WooCommerce loops.
 *
 * @package Orpitey
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}

$variant = 'up-sells' === wc_get_loop_prop( 'name' ) ? 'compact' : 'full';
?>
<li <?php wc_product_class( 'product-grid__item', $product ); ?>>
	<?php orpitey_product_card( $product, array( 'variant' => $variant ) ); ?>
</li>
