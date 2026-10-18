<?php
/**
 * Homepage product collection with category filter tabs.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

$part     = ( isset( $args ) && is_array( $args ) && isset( $args['part'] ) ) ? $args['part'] : 'all';
$only_uid = ( isset( $args ) && is_array( $args ) && isset( $args['uid'] ) ) ? (string) $args['uid'] : '';
if ( ! in_array( $part, array( 'main', 'extra', 'all' ), true ) ) {
	$part = 'all';
}

if ( 'extra' !== $part ) :
$count = max( 4, (int) orpitey_mod( 'collection_count' ) );
$tabs  = array(
	'all' => array(
		'label' => __( 'সব', 'orpitey' ),
		'ids'   => wc_get_products(
			array(
				'status'     => 'publish',
				'visibility' => 'catalog',
				'limit'      => $count,
				'orderby'    => 'date',
				'order'      => 'DESC',
				'return'     => 'ids',
			)
		),
	),
);

foreach ( orpitey_collection_tabs() as $slug ) {
	$term = orpitey_resolve_product_cat( $slug );
	if ( ! $term ) {
		continue;
	}
	$ids = wc_get_products(
		array(
			'status'     => 'publish',
			'visibility' => 'catalog',
			'limit'      => $count,
			'orderby'    => 'date',
			'order'      => 'DESC',
			'category'   => array( $term->slug ),
			'return'     => 'ids',
		)
	);
	if ( $ids ) {
		$tabs[ $slug ] = array(
			'label' => orpitey_category_label( $term ),
			'ids'   => $ids,
			'url'   => get_term_link( $term ),
		);
	}
}

if ( 'extra' !== $part && ! empty( $tabs['all']['ids'] ) ) :
	?>
<section class="section section--soft" id="collection">
	<div class="container">
		<div class="section-head">
			<div><?php orpitey_section_heading( orpitey_mod( 'collection_kicker' ), orpitey_mod( 'collection_title' ) ); ?></div>
			<?php if ( count( $tabs ) > 1 ) : ?>
				<div class="filter-group" role="tablist" aria-label="<?php esc_attr_e( 'ক্যাটাগরি', 'orpitey' ); ?>">
					<?php
					$first = true;
					foreach ( $tabs as $key => $tab ) :
						?>
						<button type="button" role="tab" class="filter-button<?php echo $first ? ' filter-button-active' : ''; ?>" aria-selected="<?php echo $first ? 'true' : 'false'; ?>" aria-controls="collection-<?php echo esc_attr( $key ); ?>" data-tab="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $tab['label'] ); ?></button>
						<?php
						$first = false;
					endforeach;
					?>
				</div>
			<?php endif; ?>
		</div>

		<?php
		$first = true;
		foreach ( $tabs as $key => $tab ) :
			?>
			<div class="product-grid" id="collection-<?php echo esc_attr( $key ); ?>" role="tabpanel" data-panel="<?php echo esc_attr( $key ); ?>"<?php echo $first ? '' : ' hidden'; ?>>
				<?php
				foreach ( $tab['ids'] as $id ) {
					orpitey_product_card( $id );
				}
				?>
			</div>
			<?php
			$first = false;
		endforeach;
		?>

		<div class="section-cta">
			<a class="btn btn-outline" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'সব পণ্য দেখুন', 'orpitey' ); ?> <?php orpitey_the_icon( 'arrow-right', 16 ); ?></a>
		</div>
	</div>
</section>
	<?php
endif;
endif;

if ( 'main' !== $part ) :
foreach ( orpitey_collection_sections() as $index => $extra ) :
	if ( $only_uid && ( empty( $extra['uid'] ) || $extra['uid'] !== $only_uid ) ) {
		continue;
	}
	$count = max( 4, (int) $extra['count'] );
	$tabs  = array(
		'all' => array(
			'label' => __( 'সব', 'orpitey' ),
			'ids'   => wc_get_products(
				array(
					'status'     => 'publish',
					'visibility' => 'catalog',
					'limit'      => $count,
					'orderby'    => 'date',
					'order'      => 'DESC',
					'return'     => 'ids',
				)
			),
		),
	);

	foreach ( $extra['tabs'] as $slug ) {
		$term = orpitey_resolve_product_cat( $slug );
		if ( ! $term ) {
			continue;
		}
		$ids = wc_get_products(
			array(
				'status'     => 'publish',
				'visibility' => 'catalog',
				'limit'      => $count,
				'orderby'    => 'date',
				'order'      => 'DESC',
				'category'   => array( $term->slug ),
				'return'     => 'ids',
			)
		);
		if ( $ids ) {
			$tabs[ $slug ] = array(
				'label' => orpitey_category_label( $term ),
				'ids'   => $ids,
			);
		}
	}

	if ( empty( $tabs['all']['ids'] ) ) {
		continue;
	}
	$title = $extra['title'] ? $extra['title'] : __( 'সংগ্রহ', 'orpitey' );
	?>
	<section class="section<?php echo 0 === $index % 2 ? '' : ' section--soft'; ?>" id="collection-extra-<?php echo esc_attr( $index ); ?>">
		<div class="container">
			<div class="section-head">
				<div><?php orpitey_section_heading( $extra['kicker'], $title ); ?></div>
				<?php if ( count( $tabs ) > 1 ) : ?>
					<div class="filter-group" role="tablist" aria-label="<?php esc_attr_e( 'ক্যাটাগরি', 'orpitey' ); ?>">
						<?php
						$first = true;
						foreach ( $tabs as $key => $tab ) :
							?>
							<button type="button" role="tab" class="filter-button<?php echo $first ? ' filter-button-active' : ''; ?>" aria-selected="<?php echo $first ? 'true' : 'false'; ?>" aria-controls="collection-extra-<?php echo esc_attr( $index . '-' . $key ); ?>" data-tab="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $tab['label'] ); ?></button>
							<?php
							$first = false;
						endforeach;
						?>
					</div>
				<?php endif; ?>
			</div>
			<?php
			$first = true;
			foreach ( $tabs as $key => $tab ) :
				?>
				<div class="product-grid" id="collection-extra-<?php echo esc_attr( $index . '-' . $key ); ?>" role="tabpanel" data-panel="<?php echo esc_attr( $key ); ?>"<?php echo $first ? '' : ' hidden'; ?>>
					<?php
					foreach ( $tab['ids'] as $id ) {
						orpitey_product_card( $id );
					}
					?>
				</div>
				<?php
				$first = false;
			endforeach;
			?>
			<div class="section-cta">
				<a class="btn btn-outline" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'সব পণ্য দেখুন', 'orpitey' ); ?> <?php orpitey_the_icon( 'arrow-right', 16 ); ?></a>
			</div>
		</div>
	</section>
	<?php
endforeach;
endif;
