<?php
/**
 * Off-canvas mobile menu.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="overlay-panel overlay-panel--left" id="mobile-menu" aria-hidden="true">
	<div class="overlay-panel__backdrop" data-close></div>
	<aside class="overlay-panel__sheet" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'মেনু', 'orpitey' ); ?>">
		<div class="drawer-header">
			<img class="drawer-logo" src="<?php echo esc_url( orpitey_logo_url() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<button type="button" class="icon-button" data-close aria-label="<?php esc_attr_e( 'মেনু বন্ধ করুন', 'orpitey' ); ?>"><?php orpitey_the_icon( 'x', 20 ); ?></button>
		</div>
		<nav class="mobile-nav" aria-label="<?php esc_attr_e( 'মোবাইল মেনু', 'orpitey' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'depth'          => 2,
					'fallback_cb'    => 'orpitey_primary_menu_fallback',
				)
			);
			?>
		</nav>
		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
			<div class="mobile-nav__extra">
				<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php orpitey_the_icon( 'user', 18 ); ?> <?php esc_html_e( 'আমার অ্যাকাউন্ট', 'orpitey' ); ?></a>
				<?php if ( orpitey_wishlist_url() ) : ?>
					<a href="<?php echo esc_url( orpitey_wishlist_url() ); ?>"><?php orpitey_the_icon( 'heart', 18 ); ?> <?php esc_html_e( 'পছন্দের তালিকা', 'orpitey' ); ?></a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</aside>
</div>
