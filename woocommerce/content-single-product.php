<?php
/**
 * Single product content.
 *
 * @package Orpitey
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}

$image_ids = array_filter( array_merge( array( $product->get_image_id() ), $product->get_gallery_image_ids() ) );
$badge     = orpitey_product_badge( $product );
$kicker    = orpitey_product_cat_label( $product, 1 );
$coll      = $product->get_meta( '_orpitey_collection' );
if ( $coll ) {
	$kicker = $kicker ? $kicker . ' · ' . $coll : $coll;
}
$rating  = (float) $product->get_average_rating();
$reviews = (int) $product->get_review_count();
$care    = $product->get_meta( '_orpitey_care' );
$care    = $care ? $care : orpitey_mod( 'product_care' );
?>
<div class="container product-breadcrumb"><?php woocommerce_breadcrumb(); ?></div>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'single-product-layout', $product ); ?>>
	<section class="product-main container">
		<div class="product-gallery" data-gallery>
			<?php if ( count( $image_ids ) > 1 ) : ?>
				<div class="product-gallery__thumbs">
					<?php foreach ( $image_ids as $i => $image_id ) : ?>
						<button type="button" class="product-gallery__thumb<?php echo 0 === $i ? ' is-active' : ''; ?>" data-src="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'full' ) ); ?>" data-srcset="<?php echo esc_attr( wp_get_attachment_image_srcset( $image_id, 'full' ) ); ?>" data-full="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'full' ) ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: image number */ __( 'ছবি %s দেখুন', 'orpitey' ), orpitey_bn( $i + 1 ) ) ); ?>">
							<?php echo wp_get_attachment_image( $image_id, 'woocommerce_gallery_thumbnail', false, array( 'alt' => '' ) ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<div class="product-gallery__main">
				<?php
				if ( $image_ids ) {
					$featured_id = reset( $image_ids );
					?>
					<div class="product-gallery__frame">
						<?php
						echo wp_get_attachment_image(
							$featured_id,
							'full',
							false,
							array(
								'class'         => 'product-gallery__image',
								'alt'           => $product->get_name(),
								'fetchpriority' => 'high',
								'data-full'     => wp_get_attachment_image_url( $featured_id, 'full' ),
							)
						);
						?>
					</div>
					<button type="button" class="product-gallery__enlarge" data-gallery-zoom>
						<?php orpitey_the_icon( 'zoom-in', 16 ); ?>
						<span><?php esc_html_e( 'বড় করে দেখুন', 'orpitey' ); ?></span>
					</button>
					<?php
				} else {
					echo wc_placeholder_img( 'woocommerce_single', array( 'class' => 'product-gallery__image' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
				<?php if ( $badge ) : ?>
					<span class="product-badge product-badge--lg"><?php echo esc_html( $badge ); ?></span>
				<?php endif; ?>
			</div>
		</div>

		<div class="product-summary summary entry-summary">
			<div class="product-summary__top">
				<?php if ( $kicker ) : ?>
					<p class="section-kicker"><?php echo esc_html( $kicker ); ?></p>
				<?php endif; ?>
				<?php orpitey_wishlist_button( $product, 'icon-button product-wishlist' ); ?>
			</div>

			<h1 class="product-title product_title entry-title"><?php the_title(); ?></h1>

			<div class="product-rating-row">
				<?php if ( $reviews && wc_review_ratings_enabled() ) : ?>
					<a class="product-stars" href="#reviews" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: rating */ __( '5 এর মধ্যে %s রেটিং', 'orpitey' ), orpitey_bn( number_format( $rating, 1 ) ) ) ); ?>">
						<?php
						for ( $i = 1; $i <= 5; $i++ ) {
							orpitey_the_icon( 'star', 15, $i <= round( $rating ) ? 'is-filled' : 'is-empty' );
						}
						?>
					</a>
					<span class="product-rating-text"><?php echo esc_html( orpitey_bn( number_format( $rating, 1 ) ) . ' · ' . sprintf( /* translators: %s: count */ __( '%sটি পর্যালোচনা', 'orpitey' ), orpitey_bn( $reviews ) ) ); ?></span>
				<?php endif; ?>
				<?php if ( $product->is_in_stock() ) : ?>
					<span class="stock-status is-in"><?php orpitey_the_icon( 'check', 15 ); ?> <?php esc_html_e( 'স্টকে আছে', 'orpitey' ); ?></span>
				<?php else : ?>
					<span class="stock-status is-out"><?php esc_html_e( 'স্টক শেষ', 'orpitey' ); ?></span>
				<?php endif; ?>
			</div>

			<div class="product-price-row">
				<p class="price product-price"><?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			</div>

			<?php if ( $product->get_short_description() ) : ?>
				<div class="product-excerpt"><?php echo apply_filters( 'woocommerce_short_description', $product->get_short_description() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>

			<?php
			$guide_id  = absint( $product->get_meta( '_orpitey_size_guide' ) );
			$has_guide = ( $guide_id && wp_attachment_is_image( $guide_id ) ) || ( $product->is_type( 'variable' ) && orpitey_mod( 'size_guide' ) );
			if ( $has_guide ) :
				?>
				<button type="button" class="link-button size-guide-toggle" data-open-dialog="size-guide"><?php esc_html_e( 'সাইজ গাইড', 'orpitey' ); ?></button>
			<?php endif; ?>

			<div class="product-cart">
				<?php woocommerce_template_single_add_to_cart(); ?>
				<?php orpitey_product_chat_buttons(); ?>
			</div>

			<?php get_template_part( 'template-parts/trust-badges', null, array( 'context' => 'product' ) ); ?>

			<div class="accordion">
				<?php
				$description = get_the_content();
				ob_start();
				wc_display_product_attributes( $product );
				$attributes = trim( ob_get_clean() );
				if ( $description || $attributes ) :
					?>
					<details class="accordion__item" open>
						<summary><?php esc_html_e( 'পণ্যের বিস্তারিত', 'orpitey' ); ?><?php orpitey_the_icon( 'chevron-down', 16 ); ?></summary>
						<div class="accordion__body prose">
							<?php
							if ( $description ) {
								the_content();
							}
							echo $attributes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</div>
					</details>
				<?php endif; ?>

				<details class="accordion__item">
					<summary><?php esc_html_e( 'ডেলিভারি ও পরিবর্তন', 'orpitey' ); ?><?php orpitey_the_icon( 'chevron-down', 16 ); ?></summary>
					<div class="accordion__body"><ul><li><?php echo implode( '</li><li>', array_map( 'esc_html', array_filter( array_map( 'trim', explode( "\n", orpitey_mod( 'product_delivery' ) ) ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li></ul></div>
				</details>

				<details class="accordion__item">
					<summary><?php esc_html_e( 'যত্নের নিয়ম', 'orpitey' ); ?><?php orpitey_the_icon( 'chevron-down', 16 ); ?></summary>
					<div class="accordion__body"><ul><li><?php echo implode( '</li><li>', array_map( 'esc_html', array_filter( array_map( 'trim', explode( "\n", $care ) ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li></ul></div>
				</details>

				<?php if ( comments_open() && wc_reviews_enabled() ) : ?>
					<details class="accordion__item" id="reviews-accordion">
						<summary>
							<?php
							/* translators: %s: count */
							echo esc_html( sprintf( __( 'পর্যালোচনা (%s)', 'orpitey' ), orpitey_bn( $reviews ) ) );
							orpitey_the_icon( 'chevron-down', 16 );
							?>
						</summary>
						<div class="accordion__body"><?php comments_template(); ?></div>
					</details>
				<?php endif; ?>
			</div>

			<div class="product-meta-small">
				<?php if ( $product->get_sku() ) : ?>
					<span><?php esc_html_e( 'কোড:', 'orpitey' ); ?> <?php echo esc_html( $product->get_sku() ); ?></span>
				<?php endif; ?>
				<?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span>' . esc_html__( 'ক্যাটাগরি:', 'orpitey' ) . ' ', '</span>' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<?php do_action( 'woocommerce_single_product_summary' ); ?>
		</div>
		<?php if ( $image_ids ) : ?>
			<dialog class="product-zoom" data-gallery-dialog>
				<button type="button" class="product-zoom__nav product-zoom__nav--prev" data-lightbox-prev aria-label="<?php esc_attr_e( 'আগের ছবি', 'orpitey' ); ?>"><?php orpitey_the_icon( 'chevron-left', 22 ); ?></button>
				<button type="button" class="product-zoom__nav product-zoom__nav--next" data-lightbox-next aria-label="<?php esc_attr_e( 'পরের ছবি', 'orpitey' ); ?>"><?php orpitey_the_icon( 'chevron-right', 22 ); ?></button>
				<div class="product-zoom__tools">
					<button type="button" class="product-zoom__tool" data-zoom-out aria-label="<?php esc_attr_e( 'ছোট করুন', 'orpitey' ); ?>"><?php orpitey_the_icon( 'minus', 18 ); ?></button>
					<button type="button" class="product-zoom__tool" data-zoom-in aria-label="<?php esc_attr_e( 'বড় করুন', 'orpitey' ); ?>"><?php orpitey_the_icon( 'plus', 18 ); ?></button>
					<button type="button" class="product-zoom__tool" data-close-dialog aria-label="<?php esc_attr_e( 'বন্ধ করুন', 'orpitey' ); ?>"><?php orpitey_the_icon( 'x', 18 ); ?></button>
				</div>
				<div class="product-zoom__stage" data-zoom-stage>
					<img alt="" data-zoom-img>
				</div>
				<p class="product-zoom__count" data-zoom-count></p>
			</dialog>
		<?php endif; ?>
	</section>

	<?php do_action( 'woocommerce_after_single_product_summary' ); ?>
</div>

<?php if ( ! empty( $has_guide ) ) : ?>
	<dialog class="modal<?php echo $guide_id ? ' modal--image' : ''; ?>" id="size-guide">
		<div class="modal__head">
			<h2><?php esc_html_e( 'সাইজ গাইড', 'orpitey' ); ?></h2>
			<button type="button" class="icon-button" data-close-dialog aria-label="<?php esc_attr_e( 'বন্ধ করুন', 'orpitey' ); ?>"><?php orpitey_the_icon( 'x', 18 ); ?></button>
		</div>
		<?php if ( $guide_id && wp_attachment_is_image( $guide_id ) ) : ?>
			<?php echo wp_get_attachment_image( $guide_id, 'large', false, array( 'class' => 'size-guide-image' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php else : ?>
			<ul class="size-guide-list"><li><?php echo implode( '</li><li>', array_map( 'esc_html', array_filter( array_map( 'trim', explode( "\n", orpitey_mod( 'size_guide' ) ) ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></li></ul>
		<?php endif; ?>
	</dialog>
<?php endif; ?>

<?php do_action( 'woocommerce_after_single_product' ); ?>
