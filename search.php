<?php
/**
 * General search results: matching products first, then journal posts and pages.
 * Product-only searches (post_type=product) use woocommerce/archive-product.php.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

get_header();

orpitey_page_header(
	array(
		'kicker' => __( 'অনুসন্ধান', 'orpitey' ),
		/* translators: %s: search query */
		'title'  => sprintf( __( '“%s” এর ফলাফল', 'orpitey' ), get_search_query() ),
	)
);

$product_ids = array();
if ( class_exists( 'WooCommerce' ) && get_search_query() ) {
	$product_ids = wc_get_products(
		array(
			'status'     => 'publish',
			'visibility' => 'search',
			'limit'      => 8,
			's'          => get_search_query(),
			'return'     => 'ids',
		)
	);
}
?>
<section class="section section--tight">
	<div class="container">
		<div class="search-page-form"><?php get_search_form(); ?></div>

		<?php if ( $product_ids ) : ?>
			<div class="section-head section-head--sm">
				<h2 class="section-title section-title--sm"><?php esc_html_e( 'পণ্য', 'orpitey' ); ?></h2>
				<a class="link-arrow" href="<?php echo esc_url( add_query_arg( array( 's' => get_search_query(), 'post_type' => 'product' ), home_url( '/' ) ) ); ?>"><?php esc_html_e( 'সব পণ্য দেখুন', 'orpitey' ); ?> <?php orpitey_the_icon( 'arrow-right', 14 ); ?></a>
			</div>
			<div class="product-grid">
				<?php
				foreach ( $product_ids as $id ) {
					orpitey_product_card( $id );
				}
				?>
			</div>
		<?php endif; ?>

		<?php
		$has_other = false;
		if ( have_posts() ) :
			ob_start();
			while ( have_posts() ) :
				the_post();
				if ( 'product' === get_post_type() ) {
					continue;
				}
				$has_other = true;
				get_template_part( 'template-parts/post-card' );
			endwhile;
			$cards = ob_get_clean();
			if ( $has_other ) :
				?>
				<div class="section-head section-head--sm">
					<h2 class="section-title section-title--sm"><?php esc_html_e( 'লেখা ও পাতা', 'orpitey' ); ?></h2>
				</div>
				<div class="post-grid"><?php echo $cards; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php
				orpitey_pagination();
			endif;
		endif;

		if ( ! $product_ids && ! $has_other ) {
			get_template_part( 'template-parts/content-none' );
		}
		?>
	</div>
</section>
<?php
get_footer();
