<?php
/**
 * Product card.
 *
 * @package Orpitey
 *
 * @var array $args product (WC_Product), variant ('full'|'compact').
 */

defined( 'ABSPATH' ) || exit;

/** @var WC_Product $product */
$product = $args['product'];
$variant = $args['variant'];
$link    = $product->get_permalink();
$name    = $product->get_name();
$label   = orpitey_product_cat_label( $product );
$cats    = wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'slugs' ) );
$image   = $product->get_image(
	'woocommerce_thumbnail',
	array(
		'class'   => 'product-card__img',
		'loading' => 'lazy',
		'alt'     => $name,
	)
);

if ( 'compact' === $variant ) :
	?>
	<a class="product-card product-card--compact group" href="<?php echo esc_url( $link ); ?>">
		<div class="product-card__media"><?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php if ( $label ) : ?>
			<p class="product-card__cat"><?php echo esc_html( $label ); ?></p>
		<?php endif; ?>
		<h3 class="product-card__title" title="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $name ); ?></h3>
		<p class="product-card__price"><?php echo orpitey_card_price_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
	</a>
	<?php
	return;
endif;

$badge    = orpitey_product_badge( $product );
$subtitle = orpitey_product_subtitle( $product );
$ajax     = $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && $product->supports( 'ajax_add_to_cart' );
?>
<article class="product-card group" data-cats="<?php echo esc_attr( is_array( $cats ) ? implode( ' ', $cats ) : '' ); ?>">
	<div class="product-card__media">
		<a class="product-card__link" href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: product */ __( '%s দেখুন', 'orpitey' ), $name ) ); ?>">
			<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
		<?php if ( $badge ) : ?>
			<span class="product-badge"><?php echo esc_html( $badge ); ?></span>
		<?php endif; ?>
		<?php orpitey_wishlist_button( $product ); ?>
		<?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
			<div class="product-card__actions">
				<?php if ( $ajax ) : ?>
					<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="card-cart add_to_cart_button ajax_add_to_cart" data-quantity="1" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>" rel="nofollow" aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>"><?php orpitey_the_icon( 'bag', 18 ); ?><span class="card-cart__label"><?php esc_html_e( 'ব্যাগে যোগ করুন', 'orpitey' ); ?></span></a>
					<a class="card-order" href="<?php echo esc_url( add_query_arg( 'orpitey_buy_now', '1', $product->add_to_cart_url() ) ); ?>"><?php esc_html_e( 'অর্ডার করুন', 'orpitey' ); ?></a>
				<?php else : ?>
					<a class="card-cart" href="<?php echo esc_url( $link ); ?>" aria-label="<?php echo esc_attr__( 'ব্যাগে যোগ করুন', 'orpitey' ); ?>"><?php orpitey_the_icon( 'bag', 18 ); ?><span class="card-cart__label"><?php esc_html_e( 'ব্যাগে যোগ করুন', 'orpitey' ); ?></span></a>
					<a class="card-order" href="<?php echo esc_url( $link ); ?>"><?php esc_html_e( 'অর্ডার করুন', 'orpitey' ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="product-card__body">
		<?php if ( $label ) : ?>
			<p class="product-card__cat"><?php echo esc_html( $label ); ?></p>
		<?php endif; ?>
		<div class="product-card__row">
			<div class="product-card__info">
				<h3 class="product-card__title"><a href="<?php echo esc_url( $link ); ?>" title="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $name ); ?></a></h3>
				<?php if ( $subtitle ) : ?>
					<p class="product-card__subtitle"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
			</div>
			<div class="product-card__price"><?php echo orpitey_card_price_html( $product ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>
	</div>
</article>
