<?php
/**
 * Related products.
 *
 * @package Orpitey
 * @version 10.3.0
 */

defined( 'ABSPATH' ) || exit;

if ( $related_products ) :
	?>
	<section class="section section--soft related-products">
		<div class="container">
			<div class="section-head">
				<div><?php orpitey_section_heading( __( 'আপনার আরও পছন্দ হতে পারে', 'orpitey' ), __( 'সম্পর্কিত সংগ্রহ', 'orpitey' ) ); ?></div>
			</div>
			<ul class="products product-grid">
				<?php foreach ( $related_products as $related_product ) : ?>
					<li class="product-grid__item"><?php orpitey_product_card( $related_product ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
endif;

wp_reset_postdata();
