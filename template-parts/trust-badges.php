<?php
/**
 * Trust badges strip.
 *
 * @package Orpitey
 *
 * @var array $args context ('home'|'product').
 */

defined( 'ABSPATH' ) || exit;

$context = isset( $args['context'] ) ? $args['context'] : 'home';
$items   = orpitey_trust_items( $context );
$is_home = 'home' === $context;
?>
<?php if ( $is_home ) : ?>
<div class="trust-slider" data-trust-slider>
<?php endif; ?>
<div class="trust-badges trust-badges--<?php echo esc_attr( $context ); ?>">
	<?php foreach ( $items as $item ) : ?>
		<div class="trust-badge">
			<span class="trust-badge__icon"><?php orpitey_the_icon( $item['icon'], 'product' === $context ? 20 : 22 ); ?></span>
			<div>
				<p class="trust-badge__title"><?php echo esc_html( $item['title'] ); ?></p>
				<p class="trust-badge__text"><?php echo esc_html( $item['text'] ); ?></p>
			</div>
		</div>
	<?php endforeach; ?>
</div>
<?php if ( $is_home && count( $items ) > 1 ) : ?>
	<div class="trust-slider__dots" role="tablist" aria-label="<?php esc_attr_e( 'সুবিধাসমূহ', 'orpitey' ); ?>">
		<?php foreach ( $items as $index => $item ) : ?>
			<button type="button" class="trust-slider__dot<?php echo 0 === $index ? ' is-active' : ''; ?>" role="tab" aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>" aria-label="<?php echo esc_attr( $item['title'] ); ?>"></button>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php if ( $is_home ) : ?>
</div>
<?php endif; ?>
