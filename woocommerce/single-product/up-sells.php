<?php
/**
 * Up-sells.
 *
 * @package Orpitey
 * @version 9.6.0
 */

defined( 'ABSPATH' ) || exit;

if ( $upsells ) :
	?>
	<section class="section upsell-products">
		<div class="container">
			<?php orpitey_section_heading( __( 'সঙ্গে মানাবে', 'orpitey' ), __( 'আরও দেখুন', 'orpitey' ) ); ?>
			<ul class="products product-grid product-grid--compact">
				<?php foreach ( $upsells as $upsell ) : ?>
					<li class="product-grid__item"><?php orpitey_product_card( $upsell, array( 'variant' => 'compact' ) ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
endif;

wp_reset_postdata();
