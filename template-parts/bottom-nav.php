<?php
/**
 * Mobile bottom navigation.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

$wishlist_url = orpitey_wishlist_url();
$home_current = is_front_page();
$wish_current = is_page_template( 'page-templates/page-wishlist.php' );
$account_current = function_exists( 'is_account_page' ) && is_account_page();
?>
<nav class="bottom-nav" aria-label="<?php esc_attr_e( 'মোবাইল নেভিগেশন', 'orpitey' ); ?>">
	<a class="bottom-nav__item<?php echo $home_current ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"<?php echo $home_current ? ' aria-current="page"' : ''; ?>>
		<span class="bottom-nav__icon"><?php orpitey_the_icon( 'home', 22 ); ?></span>
		<span class="bottom-nav__label"><?php esc_html_e( 'হোম', 'orpitey' ); ?></span>
	</a>
	<?php if ( $wishlist_url ) : ?>
		<a class="bottom-nav__item<?php echo $wish_current ? ' is-current' : ''; ?>" href="<?php echo esc_url( $wishlist_url ); ?>"<?php echo $wish_current ? ' aria-current="page"' : ''; ?>>
			<span class="bottom-nav__icon">
				<?php orpitey_the_icon( 'heart', 22 ); ?>
				<span class="count-badge orpitey-wishlist-count is-empty">0</span>
			</span>
			<span class="bottom-nav__label"><?php esc_html_e( 'পছন্দ', 'orpitey' ); ?></span>
		</a>
	<?php endif; ?>
	<a class="bottom-nav__item" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'শপিং ব্যাগ', 'orpitey' ); ?>" aria-controls="cart-drawer" data-open="cart-drawer">
		<span class="bottom-nav__icon">
			<?php orpitey_the_icon( 'bag', 22 ); ?>
			<?php orpitey_cart_count(); ?>
		</span>
		<span class="bottom-nav__label"><?php esc_html_e( 'ব্যাগ', 'orpitey' ); ?></span>
	</a>
	<a class="bottom-nav__item<?php echo $account_current ? ' is-current' : ''; ?>" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" aria-label="<?php esc_attr_e( 'আমার অ্যাকাউন্ট', 'orpitey' ); ?>"<?php echo $account_current ? ' aria-current="page"' : ''; ?>>
		<span class="bottom-nav__icon"><?php orpitey_the_icon( 'user', 22 ); ?></span>
		<span class="bottom-nav__label"><?php esc_html_e( 'অ্যাকাউন্ট', 'orpitey' ); ?></span>
	</a>
</nav>
