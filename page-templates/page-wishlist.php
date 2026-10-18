<?php
/**
 * Template Name: পছন্দের তালিকা (Wishlist)
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

get_header();

$ids = function_exists( 'orpitey_wishlist_ids' ) ? orpitey_wishlist_ids() : array();

orpitey_page_header(
	array(
		'kicker' => __( 'আপনার বাছাই', 'orpitey' ),
		'title'  => get_the_title(),
	)
);
?>
<section class="section section--tight">
	<div class="container" data-wishlist-page>
		<?php if ( $ids ) : ?>
			<div class="product-grid">
				<?php
				foreach ( $ids as $id ) {
					orpitey_product_card( $id );
				}
				?>
			</div>
		<?php endif; ?>
		<div class="empty-state" data-wishlist-empty<?php echo $ids ? ' hidden' : ''; ?>>
			<span class="empty-state__icon"><?php orpitey_the_icon( 'heart', 26 ); ?></span>
			<h2 class="empty-state__title"><?php esc_html_e( 'পছন্দের তালিকা খালি', 'orpitey' ); ?></h2>
			<p><?php esc_html_e( 'পণ্যের ছবিতে হার্ট চিহ্নে ক্লিক করে পছন্দের পোশাক এখানে জমিয়ে রাখুন।', 'orpitey' ); ?></p>
			<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
				<a class="btn btn-primary" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'সংগ্রহ দেখুন', 'orpitey' ); ?> <?php orpitey_the_icon( 'arrow-right', 16 ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
get_footer();
