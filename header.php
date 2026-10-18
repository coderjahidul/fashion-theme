<?php
/**
 * Site header.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

$has_wc       = class_exists( 'WooCommerce' );
$wishlist_url = $has_wc ? orpitey_wishlist_url() : '';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'মূল অংশে যান', 'orpitey' ); ?></a>

<?php if ( orpitey_mod( 'announcement' ) ) : ?>
	<div class="announcement-bar"><?php echo esc_html( orpitey_mod( 'announcement' ) ); ?></div>
<?php endif; ?>

<header class="site-header" id="site-header">
	<div class="site-header__inner">
		<button type="button" class="icon-button menu-toggle" aria-label="<?php esc_attr_e( 'মেনু খুলুন', 'orpitey' ); ?>" aria-controls="mobile-menu" aria-expanded="false" data-open="mobile-menu">
			<?php orpitey_the_icon( 'menu', 20 ); ?>
		</button>

		<nav class="primary-nav" aria-label="<?php esc_attr_e( 'প্রধান মেনু', 'orpitey' ); ?>">
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

		<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ' হোম' ); ?>" rel="home">
			<img src="<?php echo esc_url( orpitey_logo_url() ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="435" height="277">
		</a>

		<div class="header-actions">
			<button type="button" class="icon-button" aria-label="<?php esc_attr_e( 'খুঁজুন', 'orpitey' ); ?>" aria-controls="search-overlay" aria-expanded="false" data-open="search-overlay">
				<?php orpitey_the_icon( 'search', 18 ); ?>
			</button>
			<?php if ( $has_wc ) : ?>
				<a class="icon-button header-account" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" aria-label="<?php esc_attr_e( 'আমার অ্যাকাউন্ট', 'orpitey' ); ?>">
					<?php orpitey_the_icon( 'user', 18 ); ?>
				</a>
				<?php if ( $wishlist_url ) : ?>
					<a class="icon-button header-wishlist" href="<?php echo esc_url( $wishlist_url ); ?>" aria-label="<?php esc_attr_e( 'পছন্দের তালিকা', 'orpitey' ); ?>">
						<?php orpitey_the_icon( 'heart', 18 ); ?>
						<span class="count-badge orpitey-wishlist-count is-empty">0</span>
					</a>
				<?php endif; ?>
				<a class="icon-button header-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'শপিং ব্যাগ', 'orpitey' ); ?>" aria-controls="cart-drawer" data-open="cart-drawer">
					<?php orpitey_the_icon( 'bag', 18 ); ?>
					<?php orpitey_cart_count(); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</header>

<?php get_template_part( 'template-parts/mobile-menu' ); ?>
<?php get_template_part( 'template-parts/search-overlay' ); ?>

<main id="main" class="site-main">
