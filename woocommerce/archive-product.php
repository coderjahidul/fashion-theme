<?php
/**
 * Shop and product category archive.
 *
 * @package Orpitey
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$current_term = is_product_taxonomy() ? get_queried_object() : null;
$title        = woocommerce_page_title( false );
if ( is_shop() && ! is_search() && 'Shop' === $title ) {
	$title = __( 'সব সংগ্রহ', 'orpitey' );
}
if ( $current_term instanceof WP_Term && 'product_cat' === $current_term->taxonomy ) {
	$title = orpitey_category_label( $current_term );
}
$description = is_product_taxonomy() ? term_description() : '';
if ( ! $description && is_shop() ) {
	$shop_page   = get_post( wc_get_page_id( 'shop' ) );
	$description = $shop_page ? wpautop( wp_kses_post( $shop_page->post_excerpt ) ) : '';
}
if ( is_search() ) {
	/* translators: %s: search query */
	$title = sprintf( __( '“%s” এর ফলাফল', 'orpitey' ), get_search_query() );
}

orpitey_page_header(
	array(
		'kicker' => is_search() ? __( 'অনুসন্ধান', 'orpitey' ) : __( 'Orpitey সংগ্রহ', 'orpitey' ),
		'title'  => $title,
		'text'   => $description,
	)
);

do_action( 'woocommerce_before_main_content' );

$shop_url   = wc_get_page_permalink( 'shop' );
$bounds     = orpitey_shop_price_bounds();
$price_min  = (int) $bounds['min'];
$price_max  = (int) $bounds['max'];
$active_min = isset( $_GET['min_price'] ) ? (int) wc_clean( wp_unslash( $_GET['min_price'] ) ) : $price_min; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$active_max = isset( $_GET['max_price'] ) ? (int) wc_clean( wp_unslash( $_GET['max_price'] ) ) : $price_max; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$active_min = min( $price_max, max( $price_min, $active_min ) );
$active_max = min( $price_max, max( $price_min, $active_max ) );
if ( $active_min > $active_max ) {
	$active_min = $price_min;
	$active_max = $price_max;
}
$price_span = max( 1, $price_max - $price_min );
$fill_left  = ( ( $active_min - $price_min ) / $price_span ) * 100;
$fill_width = ( ( $active_max - $active_min ) / $price_span ) * 100;
$currency_pos    = 'right_space';
$currency_symbol = html_entity_decode( get_woocommerce_currency_symbol(), ENT_QUOTES, 'UTF-8' );
$groups       = orpitey_variation_filter_groups();
$filter_action = $shop_url;
if ( is_search() ) {
	$filter_action = home_url( '/' );
} elseif ( $current_term instanceof WP_Term ) {
	$term_link = get_term_link( $current_term );
	if ( ! is_wp_error( $term_link ) ) {
		$filter_action = $term_link;
	}
}
$attr_on = false;
foreach ( $groups as $group ) {
	$param = $group['param'];
	if ( isset( $_GET[ $param ] ) && '' !== wc_clean( wp_unslash( $_GET[ $param ] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$attr_on = true;
	}
}
$filters_on = $attr_on || $active_min > $price_min || $active_max < $price_max || ( $current_term instanceof WP_Term && 'product_cat' === $current_term->taxonomy );

$filter_terms = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
	)
);
$by_parent = array();
if ( $filter_terms && ! is_wp_error( $filter_terms ) ) {
	foreach ( $filter_terms as $filter_term ) {
		$by_parent[ (int) $filter_term->parent ][] = $filter_term;
	}
}
?>
<section class="section section--tight shop-section">
	<div class="container">
		<div class="shop-layout">
			<aside class="shop-filters<?php echo $filters_on ? ' is-open' : ''; ?>" aria-label="<?php esc_attr_e( 'ফিল্টার', 'orpitey' ); ?>">
				<button type="button" class="shop-filters__toggle" aria-expanded="<?php echo $filters_on ? 'true' : 'false'; ?>" aria-controls="shop-filters-panel">
					<?php orpitey_the_icon( 'filter', 16 ); ?>
					<?php esc_html_e( 'ফিল্টার', 'orpitey' ); ?>
				</button>
				<h2 class="shop-filters__title"><?php esc_html_e( 'ফিল্টার', 'orpitey' ); ?></h2>

				<div class="shop-filters__panel" id="shop-filters-panel">
				<form class="shop-filters__form" method="get" action="<?php echo esc_url( $filter_action ); ?>">
					<?php if ( is_search() ) : ?>
						<input type="hidden" name="s" value="<?php echo esc_attr( get_search_query() ); ?>">
						<input type="hidden" name="post_type" value="product">
					<?php endif; ?>
					<?php if ( isset( $_GET['orderby'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
						<input type="hidden" name="orderby" value="<?php echo esc_attr( wc_clean( wp_unslash( $_GET['orderby'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>">
					<?php endif; ?>

					<div class="shop-filters__group">
						<label class="shop-filters__label" for="orpitey-min-price"><?php esc_html_e( 'মূল্য', 'orpitey' ); ?></label>
						<div class="price-range" data-floor="<?php echo esc_attr( $price_min ); ?>" data-ceil="<?php echo esc_attr( $price_max ); ?>" data-symbol="<?php echo esc_attr( $currency_symbol ); ?>" data-pos="<?php echo esc_attr( $currency_pos ); ?>">
							<div class="price-range__values">
								<span data-price-min><?php echo wp_kses_post( wc_price( $active_min ) ); ?></span>
								<span data-price-max><?php echo wp_kses_post( wc_price( $active_max ) ); ?></span>
							</div>
							<div class="price-range__track">
								<span class="price-range__bar"></span>
								<span class="price-range__fill" style="left:<?php echo esc_attr( $fill_left ); ?>%;width:<?php echo esc_attr( $fill_width ); ?>%;"></span>
								<input type="range" id="orpitey-min-price" name="min_price" min="<?php echo esc_attr( $price_min ); ?>" max="<?php echo esc_attr( $price_max ); ?>" step="1" value="<?php echo esc_attr( $active_min ); ?>">
								<input type="range" id="orpitey-max-price" name="max_price" min="<?php echo esc_attr( $price_min ); ?>" max="<?php echo esc_attr( $price_max ); ?>" step="1" value="<?php echo esc_attr( $active_max ); ?>" aria-label="<?php esc_attr_e( 'সর্বোচ্চ মূল্য', 'orpitey' ); ?>">
							</div>
						</div>
					</div>

					<?php if ( ! is_search() && ! empty( $by_parent ) ) : ?>
						<div class="shop-filters__group">
							<label class="shop-filters__label" for="orpitey-filter-cat"><?php esc_html_e( 'ক্যাটাগরি', 'orpitey' ); ?></label>
							<select id="orpitey-filter-cat" class="shop-filters__select" data-filter-nav>
								<option value="<?php echo esc_url( orpitey_shop_filter_url( array( 'term' => null ) ) ); ?>" <?php selected( ! ( $current_term instanceof WP_Term && 'product_cat' === $current_term->taxonomy ) ); ?>><?php esc_html_e( 'সব ক্যাটাগরি', 'orpitey' ); ?></option>
								<?php
								$render_cats = static function ( $parent_id, $depth ) use ( &$render_cats, $by_parent, $current_term ) {
									if ( empty( $by_parent[ $parent_id ] ) ) {
										return;
									}
									foreach ( $by_parent[ $parent_id ] as $cat ) {
										$active = $current_term instanceof WP_Term && (int) $current_term->term_id === (int) $cat->term_id;
										$prefix = $depth ? str_repeat( '— ', $depth ) : '';
										echo '<option value="' . esc_url( orpitey_shop_filter_url( array( 'term' => $cat ) ) ) . '"' . selected( $active, true, false ) . '>';
										echo esc_html( $prefix . orpitey_category_label( $cat ) );
										echo '</option>';
										$render_cats( (int) $cat->term_id, $depth + 1 );
									}
								};
								$render_cats( 0, 0 );
								?>
							</select>
						</div>
					<?php endif; ?>

					<?php foreach ( $groups as $group ) : ?>
						<?php
						$param   = $group['param'];
						$current = isset( $_GET[ $param ] ) ? wc_clean( wp_unslash( $_GET[ $param ] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
						?>
						<div class="shop-filters__group">
							<label class="shop-filters__label" for="<?php echo esc_attr( 'orpitey-' . $param ); ?>"><?php echo esc_html( $group['label'] ); ?></label>
							<select class="shop-filters__select" id="<?php echo esc_attr( 'orpitey-' . $param ); ?>" name="<?php echo esc_attr( $param ); ?>">
								<option value=""><?php echo esc_html( $group['all'] ); ?></option>
								<?php foreach ( $group['options'] as $value => $label ) : ?>
									<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current, (string) $value ); ?>><?php echo esc_html( $label ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					<?php endforeach; ?>
				</form>

				<?php if ( $filters_on ) : ?>
					<a class="btn btn-outline btn-sm btn-block shop-filters__clear" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'ফিল্টার মুছুন', 'orpitey' ); ?></a>
				<?php endif; ?>
				</div>
			</aside>

			<div class="shop-main">
		<div class="shop-toolbar">
			<div class="shop-toolbar__right">
				<?php
				if ( woocommerce_product_loop() ) {
					echo '<span class="shop-toolbar__count">' . orpitey_bn_html( wp_kses_post( sprintf( /* translators: %d: number of products */ _n( '%d টি পণ্য', '%d টি পণ্য', (int) wc_get_loop_prop( 'total' ), 'orpitey' ), (int) wc_get_loop_prop( 'total' ) ) ) ) . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					woocommerce_catalog_ordering();
				}
				?>
			</div>
		</div>

		<?php
		if ( woocommerce_product_loop() ) {
			do_action( 'woocommerce_before_shop_loop' );

			woocommerce_product_loop_start();
			if ( wc_get_loop_prop( 'total' ) ) {
				while ( have_posts() ) {
					the_post();
					do_action( 'woocommerce_shop_loop' );
					wc_get_template_part( 'content', 'product' );
				}
			}
			woocommerce_product_loop_end();

			do_action( 'woocommerce_after_shop_loop' );
		} else {
			?>
			<div class="empty-state">
				<span class="empty-state__icon"><?php orpitey_the_icon( 'search', 26 ); ?></span>
				<h2 class="empty-state__title"><?php esc_html_e( 'কোনো পণ্য পাওয়া যায়নি', 'orpitey' ); ?></h2>
				<p><?php esc_html_e( 'অন্য ক্যাটাগরি দেখুন অথবা ভিন্ন শব্দে খুঁজুন।', 'orpitey' ); ?></p>
				<a class="btn btn-primary" href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'সব পণ্য দেখুন', 'orpitey' ); ?></a>
			</div>
			<?php
		}
		?>
			</div>
		</div>
	</div>
</section>
<?php
do_action( 'woocommerce_after_main_content' );

get_footer( 'shop' );
