<?php
/**
 * Template helpers.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

/**
 * Section kicker + serif title.
 *
 * @param string $kicker Small label.
 * @param string $title  Heading.
 * @param string $tag    Heading tag.
 */
function orpitey_section_heading( $kicker, $title, $tag = 'h2' ) {
	if ( $kicker ) {
		echo '<p class="section-kicker">' . esc_html( $kicker ) . '</p>';
	}
	if ( $title ) {
		printf( '<%1$s class="section-title">%2$s</%1$s>', tag_escape( $tag ), esc_html( $title ) );
	}
}

/**
 * Page header band used by inner pages.
 *
 * @param array $args kicker, title, aside, text, breadcrumb (bool).
 */
function orpitey_page_header( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'kicker'     => '',
			'title'      => '',
			'aside'      => '',
			'text'       => '',
			'breadcrumb' => true,
		)
	);
	?>
	<header class="page-hero">
		<div class="container">
			<?php
			if ( $args['breadcrumb'] ) {
				orpitey_breadcrumb();
			}
			if ( $args['kicker'] ) {
				echo '<p class="section-kicker">' . esc_html( $args['kicker'] ) . '</p>';
			}
			?>
			<div class="page-hero__heading">
				<h1 class="page-hero__title"><?php echo esc_html( $args['title'] ); ?></h1>
				<?php if ( $args['aside'] ) : ?>
					<p class="page-hero__aside"><?php echo esc_html( $args['aside'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $args['text'] ) : ?>
				<div class="page-hero__text"><?php echo wp_kses_post( $args['text'] ); ?></div>
			<?php endif; ?>
		</div>
	</header>
	<?php
}

/**
 * Breadcrumb: WooCommerce's on shop pages, a simple one elsewhere.
 */
function orpitey_breadcrumb() {
	if ( function_exists( 'woocommerce_breadcrumb' ) ) {
		woocommerce_breadcrumb();
		return;
	}
	echo '<nav class="breadcrumb" aria-label="' . esc_attr__( 'পেজের অবস্থান', 'orpitey' ) . '">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'হোম', 'orpitey' ) . '</a>';
	echo orpitey_icon( 'chevron-right', 14 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo '<span>' . esc_html( wp_get_document_title() ) . '</span>';
	echo '</nav>';
}

/**
 * Trust badge items.
 *
 * @param string $context 'home' or 'product'.
 * @return array[] icon, title, text.
 */
function orpitey_trust_items( $context = 'home' ) {
	$icons = array( 'truck', 'refresh', 'shield-check' );
	$items = array();

	if ( 'product' === $context ) {
		for ( $i = 1; $i <= 3; $i++ ) {
			$parts   = array_map( 'trim', explode( '|', orpitey_mod( 'product_trust_' . $i ) ) );
			$items[] = array(
				'icon'  => $icons[ $i - 1 ],
				'title' => $parts[0],
				'text'  => isset( $parts[1] ) ? $parts[1] : '',
			);
		}
		return $items;
	}

	foreach ( orpitey_home_trust_items() as $row ) {
		if ( '' === $row['title'] && '' === $row['text'] ) {
			continue;
		}
		$items[] = array(
			'icon'  => $row['icon'],
			'title' => $row['title'],
			'text'  => $row['text'],
		);
	}

	return $items;
}

/**
 * Social icon links.
 */
function orpitey_social_links() {
	$links = array_filter(
		array(
			'facebook'  => orpitey_mod( 'social_facebook' ),
			'instagram' => orpitey_mod( 'social_instagram' ),
			'youtube'   => orpitey_mod( 'social_youtube' ),
		)
	);
	if ( ! $links ) {
		return;
	}
	echo '<div class="social-links">';
	foreach ( $links as $network => $url ) {
		printf(
			'<a href="%1$s" target="_blank" rel="noopener" aria-label="%2$s">%3$s</a>',
			esc_url( $url ),
			esc_attr( ucfirst( $network ) ),
			orpitey_icon( $network, 18 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		);
	}
	echo '</div>';
}

/**
 * tel: link from a phone number that may be written in Bengali digits.
 *
 * @param string $phone Phone number.
 * @return string
 */
function orpitey_tel_href( $phone ) {
	$latin = strtr( $phone, array_flip( array( '০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯' ) ) );
	return 'tel:' . preg_replace( '/[^0-9+]/', '', $latin );
}

/**
 * Post date in Bangla digits.
 *
 * @param int|WP_Post|null $post Post.
 * @return string
 */
function orpitey_post_date( $post = null ) {
	$ts = get_post_timestamp( $post );
	if ( ! $ts || 0 === strpos( get_locale(), 'bn' ) ) {
		return orpitey_bn( get_the_date( '', $post ) );
	}
	$months = array( 'জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর' );
	return orpitey_bn( wp_date( 'j', $ts ) ) . ' ' . $months[ (int) wp_date( 'n', $ts ) - 1 ] . ' ' . orpitey_bn( wp_date( 'Y', $ts ) );
}

/**
 * Numbered pagination.
 *
 * @param array $args paginate_links() args.
 */
function orpitey_pagination( $args = array() ) {
	$links = paginate_links(
		wp_parse_args(
			$args,
			array(
				'type'      => 'array',
				'prev_text' => orpitey_icon( 'chevron-left', 16 ) . '<span class="screen-reader-text">' . __( 'আগের পাতা', 'orpitey' ) . '</span>',
				'next_text' => '<span class="screen-reader-text">' . __( 'পরের পাতা', 'orpitey' ) . '</span>' . orpitey_icon( 'chevron-right', 16 ),
			)
		)
	);
	if ( ! $links ) {
		return;
	}
	echo '<nav class="pagination" aria-label="' . esc_attr__( 'পাতা নির্বাচন', 'orpitey' ) . '">';
	foreach ( $links as $link ) {
		echo orpitey_bn_html( $link ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
	echo '</nav>';
}

/**
 * Fallback for the header menu before one is assigned: top product categories.
 */
function orpitey_primary_menu_fallback() {
	echo '<ul class="menu">';
	if ( taxonomy_exists( 'product_cat' ) ) {
		foreach ( array( 'saree' => 'শাড়ি', 'three-piece' => 'থ্রি-পিস' ) as $slug => $label ) {
			$term = function_exists( 'orpitey_resolve_product_cat' ) ? orpitey_resolve_product_cat( $slug ) : get_term_by( 'slug', $slug, 'product_cat' );
			if ( $term ) {
				echo '<li><a class="nav-link" href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $label ) . '</a></li>';
			}
		}
		$shop = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
		echo '<li><a class="nav-link" href="' . esc_url( add_query_arg( 'orderby', 'date', $shop ) ) . '">' . esc_html__( 'নতুন সংগ্রহ', 'orpitey' ) . '</a></li>';
	}
	echo '</ul>';
}

/**
 * Fallback for footer support menu.
 */
function orpitey_footer_menu_fallback() {
	$items = array();
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		$items[ wc_get_page_permalink( 'shop' ) ] = __( 'অর্ডার করুন', 'orpitey' );
	}
	foreach ( array( 'delivery-info' => 'ডেলিভারি তথ্য', 'exchange-policy' => 'পরিবর্তন নীতি', 'contact' => 'যোগাযোগ' ) as $slug => $label ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			$items[ get_permalink( $page ) ] = $label;
		}
	}
	echo '<ul class="footer-links">';
	foreach ( $items as $url => $label ) {
		echo '<li><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
	}
	echo '</ul>';
}

add_filter(
	'nav_menu_link_attributes',
	function ( $atts, $item, $args ) {
		if ( isset( $args->theme_location ) && 'primary' === $args->theme_location ) {
			$atts['class'] = trim( ( isset( $atts['class'] ) ? $atts['class'] : '' ) . ' nav-link' );
		}
		return $atts;
	},
	10,
	3
);
