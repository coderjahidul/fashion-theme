<?php
/**
 * 404 page.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="not-found pattern-panel">
	<div class="not-found__inner">
		<p class="not-found__code"><?php echo esc_html( orpitey_bn( '404' ) ); ?></p>
		<h1 class="not-found__title"><?php esc_html_e( 'পাতাটি খুঁজে পাওয়া যায়নি', 'orpitey' ); ?></h1>
		<p class="not-found__text"><?php esc_html_e( 'আপনি যে পাতাটি খুঁজছেন সেটি সরানো হয়েছে অথবা আর নেই। নিচে খুঁজে দেখুন অথবা আমাদের সংগ্রহ ঘুরে আসুন।', 'orpitey' ); ?></p>
		<div class="not-found__search"><?php get_search_form(); ?></div>
		<div class="not-found__actions">
			<a class="btn btn-gold" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'হোমে ফিরে যান', 'orpitey' ); ?></a>
			<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
				<a class="btn btn-gold-outline" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'সংগ্রহ দেখুন', 'orpitey' ); ?> <?php orpitey_the_icon( 'arrow-right', 16 ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php
get_footer();
