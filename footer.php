<?php
/**
 * Site footer.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

?>
</main>

<footer class="site-footer">
	<div class="site-footer__grid container" style="--footer-cols: <?php echo has_nav_menu( 'footer-shop' ) ? 3 : 2; ?>">
		<div class="footer-brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
				<img class="footer-logo" src="<?php echo esc_url( orpitey_logo_url() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="435" height="277" loading="lazy">
			</a>
			<p><?php echo esc_html( orpitey_mod( 'footer_tagline' ) ); ?></p>
			<?php $payments = orpitey_mod( 'footer_payments' ); ?>
			<?php if ( $payments ) : ?>
				<div class="footer-payments">
					<img src="<?php echo esc_url( $payments ); ?>" alt="<?php esc_attr_e( 'পেমেন্ট মাধ্যম', 'orpitey' ); ?>" loading="lazy">
				</div>
			<?php endif; ?>
		</div>

		<div class="footer-col">
			<h3 class="footer-heading"><?php esc_html_e( 'সহায়তা', 'orpitey' ); ?></h3>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'footer-support',
					'container'      => false,
					'menu_class'     => 'footer-links',
					'depth'          => 1,
					'fallback_cb'    => 'orpitey_footer_menu_fallback',
				)
			);
			?>
		</div>

		<?php if ( has_nav_menu( 'footer-shop' ) ) : ?>
			<div class="footer-col">
				<h3 class="footer-heading"><?php esc_html_e( 'সংগ্রহ', 'orpitey' ); ?></h3>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer-shop',
						'container'      => false,
						'menu_class'     => 'footer-links',
						'depth'          => 1,
					)
				);
				?>
			</div>
		<?php endif; ?>

		<div class="footer-col">
			<h3 class="footer-heading"><?php echo esc_html( orpitey_mod( 'footer_follow_title' ) ); ?></h3>
			<p><?php echo esc_html( orpitey_mod( 'footer_follow_text' ) ); ?></p>
			<?php orpitey_social_links(); ?>
		</div>
	</div>
	<div class="site-footer__bottom">
		<p class="site-footer__line">
			<span><?php echo esc_html( orpitey_mod( 'footer_copyright' ) ); ?></span>
		</p>
	</div>
</footer>

<?php
if ( class_exists( 'WooCommerce' ) ) {
	if ( orpitey_bottom_nav_enabled() ) {
		get_template_part( 'template-parts/bottom-nav' );
	}
	get_template_part( 'template-parts/cart-drawer' );
}
?>
<div class="toast" role="status" aria-live="polite" hidden></div>

<?php wp_footer(); ?>
</body>
</html>
