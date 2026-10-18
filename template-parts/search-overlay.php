<?php
/**
 * Search overlay.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="search-overlay" id="search-overlay" aria-hidden="true">
	<div class="search-overlay__backdrop" data-close></div>
	<div class="search-overlay__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'খুঁজুন', 'orpitey' ); ?>">
		<div class="search-overlay__head">
			<p class="search-overlay__label"><?php esc_html_e( 'খুঁজুন', 'orpitey' ); ?></p>
			<button type="button" class="icon-button" data-close aria-label="<?php esc_attr_e( 'বন্ধ করুন', 'orpitey' ); ?>"><?php orpitey_the_icon( 'x', 20 ); ?></button>
		</div>
		<form role="search" method="get" class="search-overlay__form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<div class="search-overlay__field">
				<?php orpitey_the_icon( 'search', 20 ); ?>
				<label for="overlay-search" class="screen-reader-text"><?php esc_html_e( 'শাড়ি বা থ্রি-পিস খুঁজুন', 'orpitey' ); ?></label>
				<input type="search" id="overlay-search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'শাড়ি, থ্রি-পিস, রঙ বা কাপড় খুঁজুন…', 'orpitey' ); ?>" autocomplete="off">
			</div>
			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
				<input type="hidden" name="post_type" value="product">
			<?php endif; ?>
			<button type="submit" class="btn btn-primary search-overlay__submit"><?php esc_html_e( 'খুঁজুন', 'orpitey' ); ?></button>
		</form>
		<?php
		$suggestions = orpitey_search_suggestions();
		if ( $suggestions ) :
			?>
			<div class="search-overlay__suggest">
				<p class="section-kicker"><?php esc_html_e( 'জনপ্রিয় খোঁজ', 'orpitey' ); ?></p>
				<div class="chip-row">
					<?php foreach ( $suggestions as $item ) : ?>
						<a class="chip" href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		endif;
		?>
	</div>
</div>
