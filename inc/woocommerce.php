<?php
/**
 * WooCommerce integration: layout hooks, product card data, cart drawer, buy now.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

/*
 * Send order emails after the checkout response, so "অর্ডার নিশ্চিত করুন"
 * is not held up by the mail server.
 */
add_filter( 'woocommerce_defer_transactional_emails', '__return_true' );

/*
 * Layout wrappers and sidebar.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

add_filter(
	'woocommerce_breadcrumb_defaults',
	function () {
		return array(
			'delimiter'   => orpitey_icon( 'chevron-right', 14 ),
			'wrap_before' => '<nav class="breadcrumb" aria-label="' . esc_attr__( 'পেজের অবস্থান', 'orpitey' ) . '">',
			'wrap_after'  => '</nav>',
			'before'      => '',
			'after'       => '',
			'home'        => __( 'হোম', 'orpitey' ),
		);
	}
);

add_filter(
	'loop_shop_per_page',
	function () {
		return 12;
	}
);

add_filter(
	'loop_shop_columns',
	function () {
		return 4;
	}
);

// Archive toolbar is rendered by archive-product.php.
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

add_filter(
	'woocommerce_pagination_args',
	function ( $args ) {
		$args['prev_text']          = orpitey_icon( 'chevron-left', 16 ) . '<span class="screen-reader-text">' . esc_html__( 'আগের পাতা', 'orpitey' ) . '</span>';
		$args['next_text']          = orpitey_icon( 'chevron-right', 16 ) . '<span class="screen-reader-text">' . esc_html__( 'পরের পাতা', 'orpitey' ) . '</span>';
		$args['before_page_number'] = '<span class="screen-reader-text">' . esc_html__( 'পাতা', 'orpitey' ) . ' </span>';
		return $args;
	}
);

/**
 * Shop URL that keeps the current price filter and sort, and drops the page number.
 *
 * @param array $overrides Query args. Pass term (WP_Term|null) to change category.
 * @return string
 */
function orpitey_shop_filter_url( $overrides = array() ) {
	$term = array_key_exists( 'term', $overrides ) ? $overrides['term'] : ( is_product_taxonomy() ? get_queried_object() : null );
	unset( $overrides['term'] );

	$base = ( $term instanceof WP_Term ) ? get_term_link( $term ) : wc_get_page_permalink( 'shop' );
	if ( is_wp_error( $base ) ) {
		$base = wc_get_page_permalink( 'shop' );
	}

	$keys = array( 'min_price', 'max_price', 'orderby' );
	foreach ( array_keys( $_GET ) as $key ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( is_string( $key ) && 0 === strpos( $key, 'filter_' ) ) {
			$keys[] = $key;
		}
	}

	$query = array();
	foreach ( $keys as $key ) {
		if ( isset( $_GET[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$query[ $key ] = wc_clean( wp_unslash( $_GET[ $key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
	}
	$query = array_merge( $query, $overrides );
	$query = array_filter(
		$query,
		static function ( $value ) {
			return '' !== $value && null !== $value;
		}
	);

	return add_query_arg( $query, $base );
}

/**
 * Lowest and highest catalog prices, in store currency.
 *
 * @return array{min:int,max:int}
 */
function orpitey_shop_price_bounds() {
	static $bounds = null;
	if ( null !== $bounds ) {
		return $bounds;
	}

	global $wpdb;
	$row = $wpdb->get_row(
		"SELECT FLOOR(MIN(lookup.min_price)) AS min_price, CEIL(MAX(lookup.max_price)) AS max_price
		FROM {$wpdb->prefix}wc_product_meta_lookup lookup
		INNER JOIN {$wpdb->posts} posts ON posts.ID = lookup.product_id
		WHERE posts.post_type = 'product' AND posts.post_status = 'publish' AND lookup.min_price > 0"
	); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$min = ( $row && null !== $row->min_price ) ? (int) $row->min_price : 0;
	$max = ( $row && null !== $row->max_price ) ? (int) $row->max_price : $min;
	if ( $max < $min ) {
		$max = $min;
	}

	$bounds = array(
		'min' => $min,
		'max' => $max,
	);
	return $bounds;
}

/**
 * Color and size filters, filled from variation attributes.
 *
 * Taxonomy attributes use WooCommerce layered nav. Local attributes are matched
 * against variation meta.
 *
 * @return array<string, array<string, mixed>>
 */
function orpitey_variation_filter_groups() {
	static $groups = null;
	if ( null !== $groups ) {
		return $groups;
	}

	$definitions = array(
		'color' => array(
			'label' => __( 'রং', 'orpitey' ),
			'all'   => __( 'সব রং', 'orpitey' ),
			'match' => '/colou?r|রং|রঙ/iu',
		),
		'size'  => array(
			'label' => __( 'সাইজ', 'orpitey' ),
			'all'   => __( 'সব সাইজ', 'orpitey' ),
			'match' => '/size|সাইজ/iu',
		),
	);

	$groups = array();
	foreach ( $definitions as $key => $definition ) {
		$groups[ $key ] = array(
			'label'    => $definition['label'],
			'all'      => $definition['all'],
			'param'    => 'filter_' . $key,
			'match'    => $definition['match'],
			'taxonomy' => '',
			'meta_key' => 'attribute_' . $key,
			'options'  => array(),
		);
	}

	foreach ( wc_get_attribute_taxonomies() as $attribute ) {
		$name = $attribute->attribute_label . ' ' . $attribute->attribute_name;
		foreach ( $groups as $key => $group ) {
			if ( ! preg_match( $group['match'], $name ) ) {
				continue;
			}
			$taxonomy = wc_attribute_taxonomy_name( $attribute->attribute_name );
			if ( ! taxonomy_exists( $taxonomy ) ) {
				continue;
			}
			$terms = get_terms(
				array(
					'taxonomy'   => $taxonomy,
					'hide_empty' => true,
				)
			);
			if ( is_wp_error( $terms ) ) {
				continue;
			}
			$groups[ $key ]['taxonomy'] = $taxonomy;
			foreach ( $terms as $term ) {
				$groups[ $key ]['options'][ $term->slug ] = $term->name;
			}
		}
	}

	$products = wc_get_products(
		array(
			'limit'  => -1,
			'status' => 'publish',
			'return' => 'objects',
		)
	);
	foreach ( $products as $product ) {
		foreach ( $product->get_attributes() as $attribute ) {
			if ( ! $attribute->get_variation() || $attribute->is_taxonomy() ) {
				continue;
			}
			foreach ( $groups as $key => $group ) {
				if ( $group['taxonomy'] || ! preg_match( $group['match'], $attribute->get_name() ) ) {
					continue;
				}
				$groups[ $key ]['meta_key'] = 'attribute_' . sanitize_title( $attribute->get_name() );
				foreach ( (array) $attribute->get_options() as $option ) {
					$option = (string) $option;
					if ( '' !== $option ) {
						$groups[ $key ]['options'][ $option ] = $option;
					}
				}
			}
		}
	}

	foreach ( $groups as $key => $group ) {
		$options = $group['options'];
		uksort(
			$options,
			static function ( $a, $b ) {
				return strnatcasecmp( (string) $a, (string) $b );
			}
		);
		$groups[ $key ]['options'] = $options;
		unset( $groups[ $key ]['match'] );
	}

	return $groups;
}

/**
 * Parent product IDs that have a variation with this local attribute value.
 *
 * @param string $meta_key Variation meta key, e.g. attribute_size.
 * @param string $value    Attribute value.
 * @return int[]
 */
function orpitey_products_with_local_attribute( $meta_key, $value ) {
	global $wpdb;

	$parents = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT DISTINCT p.post_parent
			FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} m ON m.post_id = p.ID
			WHERE p.post_type = 'product_variation'
				AND p.post_status = 'publish'
				AND m.meta_key = %s
				AND m.meta_value = %s",
			$meta_key,
			$value
		)
	); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

	return array_map( 'intval', $parents );
}

/**
 * Limit the shop loop to products whose variations match local color/size filters.
 *
 * @param WP_Query $query Main product query.
 */
function orpitey_filter_shop_by_variation( $query ) {
	$groups = orpitey_variation_filter_groups();
	$ids    = null;

	foreach ( $groups as $group ) {
		if ( $group['taxonomy'] ) {
			continue;
		}
		$param = $group['param'];
		$value = isset( $_GET[ $param ] ) ? wc_clean( wp_unslash( $_GET[ $param ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( '' === $value || ! isset( $group['options'][ $value ] ) ) {
			continue;
		}
		$matched = orpitey_products_with_local_attribute( $group['meta_key'], $value );
		$ids     = null === $ids ? $matched : array_values( array_intersect( $ids, $matched ) );
	}

	if ( null === $ids ) {
		return;
	}

	$current = array_filter( array_map( 'intval', (array) $query->get( 'post__in' ) ) );
	if ( $current ) {
		$ids = array_values( array_intersect( $current, $ids ) );
	}

	$query->set( 'post__in', $ids ? $ids : array( 0 ) );
}
add_action( 'woocommerce_product_query', 'orpitey_filter_shop_by_variation' );

// content-single-product.php renders these parts itself.
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

add_filter(
	'woocommerce_output_related_products_args',
	function ( $args ) {
		$args['posts_per_page'] = 4;
		$args['columns']        = 4;
		return $args;
	}
);

/*
 * Product data helpers used by the product card and single product.
 */

/**
 * Badge text: manual badge, sale, featured or new.
 *
 * @param WC_Product $product Product.
 * @return string
 */
function orpitey_product_badge( $product ) {
	$manual = $product->get_meta( '_orpitey_badge' );
	if ( $manual ) {
		return $manual;
	}
	if ( ! $product->is_in_stock() ) {
		return __( 'স্টক শেষ', 'orpitey' );
	}
	if ( $product->is_on_sale() ) {
		return __( 'বিশেষ মূল্য', 'orpitey' );
	}
	if ( $product->is_featured() ) {
		return __( 'বেস্টসেলার', 'orpitey' );
	}
	$created = $product->get_date_created();
	if ( $created && ( time() - $created->getTimestamp() ) < 30 * DAY_IN_SECONDS ) {
		return __( 'নতুন', 'orpitey' );
	}
	return '';
}

/**
 * Short line under the product name (fabric · work).
 *
 * @param WC_Product $product Product.
 * @return string
 */
function orpitey_product_subtitle( $product ) {
	return (string) $product->get_meta( '_orpitey_subtitle' );
}

/**
 * Primary category name for the small label above product names.
 *
 * @param WC_Product $product Product.
 * @param int        $limit   Number of categories.
 * @return string
 */
function orpitey_product_cat_label( $product, $limit = 1 ) {
	$terms = get_the_terms( $product->get_id(), 'product_cat' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return '';
	}
	$preferred = orpitey_collection_tabs();
	$rank      = function ( $term ) use ( $preferred ) {
		foreach ( $preferred as $i => $slug ) {
			if ( $term->slug === $slug || 0 === strpos( $term->slug, $slug . '-' ) ) {
				return $i;
			}
		}
		return 99 + ( $term->parent ? 1 : 0 );
	};
	usort(
		$terms,
		function ( $a, $b ) use ( $rank ) {
			return $rank( $a ) <=> $rank( $b );
		}
	);
	$names = array();
	foreach ( $terms as $term ) {
		if ( 'uncategorized' === $term->slug ) {
			continue;
		}
		$names[] = orpitey_category_label( $term );
		if ( count( $names ) >= $limit ) {
			break;
		}
	}
	return implode( ' · ', $names );
}

/**
 * Bangla display name for the two main categories (and their slug variants), term name for the rest.
 *
 * @param WP_Term $term Term.
 * @return string
 */
function orpitey_category_label( $term ) {
	$labels = apply_filters(
		'orpitey_category_labels',
		array(
			'saree'       => 'শাড়ি',
			'three-piece' => 'থ্রি-পিস',
		)
	);
	foreach ( $labels as $slug => $label ) {
		if ( $term->slug === $slug || 0 === strpos( $term->slug, $slug . '-' ) ) {
			return $label;
		}
	}
	return $term->name;
}

/**
 * Find a product category by slug. If it is empty, fall back to the fullest
 * category whose slug starts with the same prefix (e.g. three-piece-pakistan-inspired).
 *
 * @param string $slug Slug.
 * @return WP_Term|null
 */
function orpitey_resolve_product_cat( $slug ) {
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	if ( $term && $term->count > 0 ) {
		return $term;
	}
	$best = $term ? $term : null;
	foreach ( get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true ) ) as $candidate ) {
		if ( 0 === strpos( $candidate->slug, $slug . '-' ) && ( ! $best || $candidate->count > $best->count ) ) {
			$best = $candidate;
		}
	}
	return $best;
}

/**
 * Render a product card.
 *
 * @param WC_Product|int $product Product.
 * @param array          $args    variant => 'full'|'compact'.
 */
function orpitey_product_card( $product, $args = array() ) {
	$product = wc_get_product( $product );
	if ( ! $product || ! $product->is_visible() ) {
		return;
	}
	get_template_part(
		'template-parts/product-card',
		null,
		wp_parse_args(
			$args,
			array(
				'product' => $product,
				'variant' => 'full',
			)
		)
	);
}

/**
 * Card price HTML: current price with the regular price struck through below.
 *
 * @param WC_Product $product Product.
 * @return string
 */
function orpitey_card_price_html( $product ) {
	if ( $product->is_type( 'variable' ) ) {
		$min     = $product->get_variation_price( 'min', true );
		$max_reg = $product->get_variation_regular_price( 'max', true );
		$html    = wc_price( $min );
		if ( $product->get_variation_price( 'max', true ) !== $min ) {
			$html = '<span class="price-from">' . esc_html__( 'শুরু', 'orpitey' ) . '</span> ' . $html;
		}
		if ( $product->is_on_sale() && $max_reg > $min ) {
			$html .= '<del>' . wc_price( $max_reg ) . '</del>';
		}
		return $html;
	}

	$price = wc_get_price_to_display( $product );
	if ( '' === $product->get_price() ) {
		return '';
	}
	$html = wc_price( $price );
	if ( $product->is_on_sale() ) {
		$html .= '<del>' . wc_price( wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) ) ) . '</del>';
	}
	return $html;
}

/*
 * Cart drawer and header counter, refreshed through WooCommerce fragments.
 */

/**
 * "ব্যাগে ৩টি পণ্য" label for the checkout heading.
 *
 * @return string
 */
function orpitey_bag_count_label() {
	$count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
	return sprintf(
		/* translators: %s: number of products in the bag */
		__( 'ব্যাগে %sটি পণ্য', 'orpitey' ),
		orpitey_bn( $count )
	);
}

/**
 * Header bag counter.
 */
function orpitey_cart_count() {
	$count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	printf(
		'<span class="count-badge orpitey-cart-count%1$s" data-count="%2$d">%3$s</span>',
		$count ? '' : ' is-empty',
		(int) $count,
		esc_html( orpitey_bn( $count ) )
	);
}

/**
 * Drawer body (items + totals).
 */
function orpitey_cart_drawer_content() {
	echo '<div class="drawer-content orpitey-drawer-content">';
	get_template_part( 'template-parts/cart-drawer-content' );
	echo '</div>';
}

add_filter(
	'woocommerce_cart_item_remove_link',
	function ( $link ) {
		return str_replace( array( '&times;', '×' ), orpitey_icon( 'trash', 16 ), $link );
	}
);

add_filter(
	'woocommerce_add_to_cart_fragments',
	function ( $fragments ) {
		ob_start();
		orpitey_cart_count();
		$fragments['span.orpitey-cart-count'] = ob_get_clean();

		ob_start();
		orpitey_cart_drawer_content();
		$fragments['div.orpitey-drawer-content'] = ob_get_clean();

		$fragments['p.page-hero__aside'] = '<p class="page-hero__aside">' . esc_html( orpitey_bag_count_label() ) . '</p>';

		return $fragments;
	}
);

/**
 * Drawer quantity update / remove, returns refreshed fragments.
 */
function orpitey_ajax_update_qty() {
	check_ajax_referer( 'orpitey', 'nonce' );

	$key = isset( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '';
	$qty = isset( $_POST['qty'] ) ? max( 0, (int) $_POST['qty'] ) : 0;

	if ( $key && WC()->cart->get_cart_item( $key ) ) {
		if ( 0 === $qty ) {
			WC()->cart->remove_cart_item( $key );
		} else {
			WC()->cart->set_quantity( $key, $qty, true );
		}
	}

	WC_AJAX::get_refreshed_fragments();
}
add_action( 'wc_ajax_orpitey_update_qty', 'orpitey_ajax_update_qty' );

/*
 * Buy now: add to cart and jump straight to checkout.
 */
add_action(
	'woocommerce_after_add_to_cart_button',
	function () {
		global $product;
		if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
			return;
		}
		if ( $product->is_type( 'simple' ) ) {
			// Simple products send add-to-cart through the submit button, which the buy-now button replaces.
			echo '<input type="hidden" name="add-to-cart" value="' . esc_attr( $product->get_id() ) . '">';
		}
		echo '<button type="submit" name="orpitey_buy_now" value="1" class="btn btn-outline btn-block buy-now-button">' . esc_html__( 'অর্ডার করুন', 'orpitey' ) . '</button>';
	}
);

/**
 * WhatsApp and Messenger links for the product page. Empty until set in Theme Options.
 *
 * @return array[] label, url, brand.
 */
function orpitey_product_chat_links() {
	$links = array();
	$chat  = array(
		'whatsapp'  => array(
			'label' => 'WhatsApp',
			'url'   => orpitey_mod( 'product_whatsapp' ),
		),
		'messenger' => array(
			'label' => 'Messenger',
			'url'   => orpitey_mod( 'product_messenger' ),
		),
	);
	foreach ( $chat as $brand => $item ) {
		if ( ! $item['url'] ) {
			continue;
		}
		$links[] = array(
			'brand' => $brand,
			'label' => $item['label'],
			'url'   => $item['url'],
		);
	}
	return $links;
}

/**
 * Full-width chat buttons, matching the Buy now button.
 */
function orpitey_product_chat_buttons() {
	$links = orpitey_product_chat_links();
	if ( ! $links ) {
		return;
	}
	echo '<div class="product-chat">';
	foreach ( $links as $link ) {
		printf(
			'<a class="product-chat__button product-chat__button--%1$s" href="%2$s" target="_blank" rel="noopener noreferrer">%3$s<span>%4$s</span></a>',
			esc_attr( $link['brand'] ),
			esc_url( $link['url'] ),
			orpitey_chat_icon( $link['brand'] ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			esc_html( $link['label'] )
		);
	}
	echo '</div>';
}

/**
 * Small brand mark for a chat button.
 *
 * @param string $brand whatsapp|messenger.
 * @return string
 */
function orpitey_chat_icon( $brand ) {
	$paths = array(
		'whatsapp'  => '<path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>',
		'messenger' => '<path d="M12 2C6.5 2 2 6.1 2 11.1 2 14 3.4 16.6 5.7 18.3V22l3.4-1.9c.9.2 1.8.4 2.9.4 5.5 0 10-4.1 10-9.1S17.5 2 12 2zm1.1 12.3-2.6-2.7-5 2.7 5.5-5.8 2.6 2.7 4.9-2.7-5.4 5.8z"/>',
	);
	if ( ! isset( $paths[ $brand ] ) ) {
		return '';
	}
	return '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">' . $paths[ $brand ] . '</svg>';
}

add_filter(
	'woocommerce_add_to_cart_redirect',
	function ( $url ) {
		if ( ! empty( $_REQUEST['orpitey_buy_now'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return wc_get_checkout_url();
		}
		return $url;
	}
);

/**
 * Hide the "added to your cart" success notice on checkout.
 * Coupon and error notices stay.
 */
function orpitey_hide_checkout_added_notice() {
	if ( ! function_exists( 'wc_get_notices' ) || ! function_exists( 'wc_set_notices' ) || ! WC()->session ) {
		return;
	}

	$notices = wc_get_notices();
	if ( empty( $notices['success'] ) || ! is_array( $notices['success'] ) ) {
		return;
	}

	$notices['success'] = array_values(
		array_filter(
			$notices['success'],
			static function ( $notice ) {
				$html = isset( $notice['notice'] ) ? $notice['notice'] : (string) $notice;
				$text = wp_strip_all_tags( $html );
				if ( false !== strpos( $text, 'ব্যাগে যোগ হয়েছে' ) ) {
					return false;
				}
				return false === stripos( $text, 'added to your cart' );
			}
		)
	);

	if ( empty( $notices['success'] ) ) {
		unset( $notices['success'] );
	}

	wc_set_notices( $notices );
}
add_action( 'woocommerce_before_checkout_form_cart_notices', 'orpitey_hide_checkout_added_notice', 1 );
add_action( 'woocommerce_before_checkout_form', 'orpitey_hide_checkout_added_notice', 1 );

add_filter(
	'woocommerce_product_single_add_to_cart_text',
	function () {
		return __( 'ব্যাগে যোগ করুন', 'orpitey' );
	}
);

add_filter(
	'woocommerce_product_add_to_cart_text',
	function ( $text, $product ) {
		if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
			return __( 'ব্যাগে যোগ করুন', 'orpitey' );
		}
		if ( $product->is_type( 'variable' ) && $product->is_purchasable() ) {
			return __( 'সাইজ বাছাই করুন', 'orpitey' );
		}
		return __( 'বিস্তারিত দেখুন', 'orpitey' );
	},
	10,
	2
);

/**
 * Whether an attribute name or taxonomy is a color attribute.
 *
 * @param string $name Attribute label, slug or taxonomy.
 * @return bool
 */
function orpitey_is_color_attribute( $name ) {
	return (bool) preg_match( '/colou?r|রং|রঙ/iu', (string) $name );
}

/**
 * Saved swatch color for a term.
 *
 * @param int $term_id Term ID.
 * @return string Hex color or empty string.
 */
function orpitey_term_swatch_color( $term_id ) {
	$hex = sanitize_hex_color( (string) get_term_meta( (int) $term_id, '_orpitey_color', true ) );
	return $hex ? $hex : '';
}

/**
 * Color picker on the add-term screen for color attributes.
 */
function orpitey_color_term_add_field() {
	?>
	<div class="form-field">
		<label for="orpitey_color"><?php esc_html_e( 'রং', 'orpitey' ); ?></label>
		<input type="color" name="orpitey_color" id="orpitey_color" value="#5c0c11">
		<label style="display:block;margin-top:8px;">
			<input type="checkbox" name="orpitey_color_set" value="1">
			<?php esc_html_e( 'এই রংটি সুয়াচে দেখাও', 'orpitey' ); ?>
		</label>
		<p><?php esc_html_e( 'চেক না করলে নাম দেখে রং ধরা হয়, যেমন Red বা Black।', 'orpitey' ); ?></p>
	</div>
	<?php
}

/**
 * Color picker on the edit-term screen for color attributes.
 *
 * @param WP_Term $term Term.
 */
function orpitey_color_term_edit_field( $term ) {
	$hex = orpitey_term_swatch_color( $term->term_id );
	?>
	<tr class="form-field">
		<th scope="row"><label for="orpitey_color"><?php esc_html_e( 'রং', 'orpitey' ); ?></label></th>
		<td>
			<input type="color" name="orpitey_color" id="orpitey_color" value="<?php echo esc_attr( $hex ? $hex : '#5c0c11' ); ?>">
			<label style="margin-left:8px;">
				<input type="checkbox" name="orpitey_color_set" value="1" <?php checked( (bool) $hex ); ?>>
				<?php esc_html_e( 'এই রংটি সুয়াচে দেখাও', 'orpitey' ); ?>
			</label>
			<p class="description"><?php esc_html_e( 'চেক না করলে নাম দেখে রং ধরা হয়, যেমন Red বা Black।', 'orpitey' ); ?></p>
		</td>
	</tr>
	<?php
}

/**
 * Save the term swatch color.
 *
 * @param int $term_id Term ID.
 */
function orpitey_save_color_term( $term_id ) {
	if ( ! current_user_can( 'manage_product_terms' ) ) {
		return;
	}
	if ( ! empty( $_POST['orpitey_color_set'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$hex = isset( $_POST['orpitey_color'] ) ? sanitize_hex_color( wp_unslash( $_POST['orpitey_color'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( $hex ) {
			update_term_meta( $term_id, '_orpitey_color', $hex );
			return;
		}
	}
	delete_term_meta( $term_id, '_orpitey_color' );
}

/**
 * @param array<string, string> $columns Columns.
 * @return array<string, string>
 */
function orpitey_color_term_column( $columns ) {
	$columns['orpitey_color'] = __( 'রং', 'orpitey' );
	return $columns;
}

/**
 * @param string $content  Column content.
 * @param string $column   Column name.
 * @param int    $term_id  Term ID.
 * @return string
 */
function orpitey_color_term_column_content( $content, $column, $term_id ) {
	if ( 'orpitey_color' !== $column ) {
		return $content;
	}
	$hex = orpitey_term_swatch_color( $term_id );
	if ( ! $hex ) {
		return '—';
	}
	return '<span style="display:inline-block;width:16px;height:16px;margin-right:6px;vertical-align:middle;border:1px solid #c3c4c7;border-radius:50%;background:' . esc_attr( $hex ) . ';"></span>' . esc_html( $hex );
}

add_action(
	'admin_init',
	function () {
		if ( ! function_exists( 'wc_get_attribute_taxonomies' ) ) {
			return;
		}
		foreach ( wc_get_attribute_taxonomies() as $attribute ) {
			$label = $attribute->attribute_label . ' ' . $attribute->attribute_name;
			if ( ! orpitey_is_color_attribute( $label ) ) {
				continue;
			}
			$tax = wc_attribute_taxonomy_name( $attribute->attribute_name );
			add_action( $tax . '_add_form_fields', 'orpitey_color_term_add_field' );
			add_action( $tax . '_edit_form_fields', 'orpitey_color_term_edit_field' );
			add_action( 'created_' . $tax, 'orpitey_save_color_term' );
			add_action( 'edited_' . $tax, 'orpitey_save_color_term' );
			add_filter( 'manage_edit-' . $tax . '_columns', 'orpitey_color_term_column' );
			add_filter( 'manage_' . $tax . '_custom_column', 'orpitey_color_term_column_content', 10, 3 );
		}
	}
);

/*
 * Variation attributes: expose the attribute name to the swatch script.
 */
add_filter(
	'woocommerce_dropdown_variation_attribute_options_html',
	function ( $html, $args ) {
		$name     = wc_attribute_label( $args['attribute'], $args['product'] );
		$is_color = orpitey_is_color_attribute( $name . ' ' . $args['attribute'] );
		if ( $is_color && taxonomy_exists( $args['attribute'] ) ) {
			$terms = get_terms(
				array(
					'taxonomy'   => $args['attribute'],
					'hide_empty' => false,
				)
			);
			if ( ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$hex = orpitey_term_swatch_color( $term->term_id );
					if ( ! $hex ) {
						continue;
					}
					$html = preg_replace(
						'/(<option\b[^>]*\bvalue="' . preg_quote( $term->slug, '/' ) . '"[^>]*)(>)/',
						'$1 data-color="' . esc_attr( $hex ) . '"$2',
						$html,
						1
					);
				}
			}
		}
		return '<div class="variation-picker" data-type="' . ( $is_color ? 'color' : 'label' ) . '">' . $html . '</div>';
	},
	10,
	2
);

/*
 * Checkout: order review heading and button styling hooks.
 */
add_filter(
	'woocommerce_order_button_html',
	function ( $html ) {
		return str_replace( 'class="button', 'class="btn btn-primary btn-block button', $html );
	}
);

/*
 * My Account: menu icons are added in CSS; tidy labels.
 */
add_filter(
	'woocommerce_account_menu_items',
	function ( $items ) {
		unset( $items['downloads'] );
		return $items;
	}
);

/**
 * Is this one of the WooCommerce "app" pages that need a wide container.
 *
 * @return bool
 */
add_filter(
	'document_title_parts',
	function ( $parts ) {
		if ( function_exists( 'is_order_received_page' ) && is_order_received_page() ) {
			$parts['title'] = __( 'অর্ডার সম্পন্ন', 'orpitey' );
		}
		return $parts;
	}
);

function orpitey_is_wc_app_page() {
	return function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() );
}

/**
 * Reduce a phone number to the local 01XXXXXXXXX form.
 *
 * @param string $phone Raw phone.
 * @return string
 */
function orpitey_normalize_bd_phone( $phone ) {
	$phone  = strtr(
		(string) $phone,
		array(
			'০' => '0',
			'১' => '1',
			'২' => '2',
			'৩' => '3',
			'৪' => '4',
			'৫' => '5',
			'৬' => '6',
			'৭' => '7',
			'৮' => '8',
			'৯' => '9',
		)
	);
	$digits = preg_replace( '/\D+/', '', $phone );
	if ( 0 === strpos( $digits, '880' ) ) {
		$digits = '0' . substr( $digits, 3 );
	}
	return $digits;
}

/**
 * Easy checkout: name, mobile number, and one full address.
 */
add_filter(
	'woocommerce_checkout_fields',
	function ( $fields ) {
		$keep = array( 'billing_first_name', 'billing_phone', 'billing_address_1' );
		foreach ( array_keys( $fields['billing'] ) as $key ) {
			if ( ! in_array( $key, $keep, true ) ) {
				unset( $fields['billing'][ $key ] );
			}
		}

		$fields['billing']['billing_first_name']['label']        = __( 'আপনার নাম', 'orpitey' );
		$fields['billing']['billing_first_name']['placeholder']  = __( 'যেমন: তানিয়া আক্তার', 'orpitey' );
		$fields['billing']['billing_first_name']['class']        = array( 'form-row-wide', 'field-icon', 'field-icon--user' );
		$fields['billing']['billing_first_name']['priority']     = 10;
		$fields['billing']['billing_first_name']['autocomplete'] = 'name';
		$fields['billing']['billing_first_name']['required']     = true;

		$fields['billing']['billing_phone']['label']       = __( 'মোবাইল নম্বর', 'orpitey' );
		$fields['billing']['billing_phone']['placeholder'] = '01712345678';
		$fields['billing']['billing_phone']['type']        = 'tel';
		$fields['billing']['billing_phone']['required']    = true;
		$fields['billing']['billing_phone']['class']       = array( 'form-row-wide', 'field-icon', 'field-icon--phone' );
		$fields['billing']['billing_phone']['priority']    = 20;
		$fields['billing']['billing_phone']['validate']    = array();
		$fields['billing']['billing_phone']['description'] = __( 'এই নম্বরে কল করে অর্ডার নিশ্চিত করা হবে', 'orpitey' );
		$fields['billing']['billing_phone']['custom_attributes'] = array(
			'inputmode'    => 'tel',
			'autocomplete' => 'tel',
		);

		$fields['billing']['billing_address_1']['label']       = __( 'সম্পূর্ণ ঠিকানা', 'orpitey' );
		$fields['billing']['billing_address_1']['placeholder'] = __( 'বাসা/হোল্ডিং, রোড, এলাকা, থানা, জেলা', 'orpitey' );
		$fields['billing']['billing_address_1']['type']        = 'textarea';
		$fields['billing']['billing_address_1']['required']    = true;
		$fields['billing']['billing_address_1']['class']       = array( 'form-row-wide', 'field-icon', 'field-icon--address' );
		$fields['billing']['billing_address_1']['priority']    = 30;
		$fields['billing']['billing_address_1']['autocomplete'] = 'street-address';

		$fields['billing']['billing_country'] = array(
			'type'     => 'hidden',
			'required' => false,
			'default'  => 'BD',
			'priority' => 100,
		);

		unset( $fields['order']['order_comments'] );

		return $fields;
	}
);

add_filter(
	'woocommerce_form_field',
	function ( $field, $key ) {
		$icons = array(
			'billing_first_name'  => 'user',
			'shipping_first_name' => 'user',
			'billing_phone'       => 'phone',
			'shipping_phone'      => 'phone',
			'billing_address_1'   => 'map-pin',
			'shipping_address_1'  => 'map-pin',
		);
		if ( ! isset( $icons[ $key ] ) ) {
			return $field;
		}
		$icon = orpitey_icon( $icons[ $key ], 18, 'field-icon__svg' );
		return preg_replace( '/(<span class="woocommerce-input-wrapper">)/', '$1' . $icon, $field, 1 );
	},
	10,
	2
);

/**
 * Account address forms use the same field layout as the rest of the store.
 *
 * @param array $fields Address fields.
 * @return array
 */
function orpitey_style_address_fields( $fields ) {
	$row   = array(
		'first_name' => 'form-row-first',
		'last_name'  => 'form-row-last',
		'city'       => 'form-row-first',
		'state'      => 'form-row-last',
		'phone'      => 'form-row-first',
		'email'      => 'form-row-last',
	);
	$icons = array(
		'first_name' => 'field-icon--user',
		'phone'      => 'field-icon--phone',
		'address_1'  => 'field-icon--address',
	);

	foreach ( $fields as $key => $field ) {
		$suffix  = preg_replace( '/^(billing|shipping)_/', '', $key );
		$classes = isset( $field['class'] ) ? array_values( (array) $field['class'] ) : array();
		$classes = array_values(
			array_diff(
				$classes,
				array( 'form-row-first', 'form-row-last', 'form-row-wide', 'field-icon', 'field-icon--user', 'field-icon--phone', 'field-icon--address' )
			)
		);
		$classes[]               = isset( $row[ $suffix ] ) ? $row[ $suffix ] : 'form-row-wide';
		if ( isset( $icons[ $suffix ] ) ) {
			$classes[] = 'field-icon';
			$classes[] = $icons[ $suffix ];
		}
		$fields[ $key ]['class'] = $classes;
		if ( 'address_2' === $suffix ) {
			$fields[ $key ]['label_class'] = array();
		}
	}

	return $fields;
}
add_filter( 'woocommerce_billing_fields', 'orpitey_style_address_fields', 20 );
add_filter( 'woocommerce_shipping_fields', 'orpitey_style_address_fields', 20 );

add_filter(
	'woocommerce_checkout_get_value',
	function ( $value, $input ) {
		if ( in_array( $input, array( 'billing_country', 'shipping_country' ), true ) && ! $value ) {
			return 'BD';
		}
		return $value;
	},
	10,
	2
);

add_filter( 'default_checkout_billing_country', function () {
	return 'BD';
} );
add_filter( 'default_checkout_shipping_country', function () {
	return 'BD';
} );

add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false' );
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );

add_filter(
	'woocommerce_checkout_posted_data',
	function ( $data ) {
		if ( isset( $data['billing_phone'] ) ) {
			$data['billing_phone'] = orpitey_normalize_bd_phone( $data['billing_phone'] );
		}
		$data['billing_country']  = 'BD';
		$data['shipping_country'] = 'BD';
		$data['billing_last_name']  = '';
		$data['billing_city']       = '';
		$data['billing_state']      = '';
		$data['billing_postcode']   = '';
		$data['billing_address_2']  = '';
		$data['shipping_first_name'] = isset( $data['billing_first_name'] ) ? $data['billing_first_name'] : '';
		$data['shipping_last_name']  = '';
		$data['shipping_address_1']  = isset( $data['billing_address_1'] ) ? $data['billing_address_1'] : '';
		$data['shipping_phone']      = isset( $data['billing_phone'] ) ? $data['billing_phone'] : '';
		$data['shipping_city']       = '';
		$data['shipping_state']      = '';
		$data['shipping_postcode']   = '';
		return $data;
	}
);

add_action(
	'woocommerce_after_checkout_validation',
	function ( $data, $errors ) {
		$phone = isset( $data['billing_phone'] ) ? $data['billing_phone'] : '';
		if ( '' !== $phone && ! preg_match( '/^01[3-9]\d{8}$/', $phone ) ) {
			$errors->add( 'billing_phone_invalid', __( 'সঠিক মোবাইল নম্বর দিন, যেমন 01712345678।', 'orpitey' ) );
		}
	},
	10,
	2
);

add_filter(
	'woocommerce_localisation_address_formats',
	function ( $formats ) {
		$formats['BD'] = "{name}\n{address_1}";
		return $formats;
	}
);
