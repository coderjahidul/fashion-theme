<?php
/**
 * Template Name: নীতিমালা (Policy)
 *
 * Content page with a side list of the other policy/help pages.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

get_header();

$siblings = get_pages(
	array(
		'meta_key'    => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
		'meta_value'  => 'page-templates/page-policy.php', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		'sort_column' => 'menu_order,post_title',
	)
);

while ( have_posts() ) :
	the_post();
	orpitey_page_header(
		array(
			'kicker' => __( 'সহায়তা', 'orpitey' ),
			'title'  => get_the_title(),
			'text'   => has_excerpt() ? wpautop( get_the_excerpt() ) : '',
		)
	);
	?>
	<section class="section section--tight">
		<div class="container policy-layout">
			<aside class="policy-nav">
				<p class="section-kicker"><?php esc_html_e( 'আরও তথ্য', 'orpitey' ); ?></p>
				<ul>
					<?php foreach ( $siblings as $policy_page ) : ?>
						<li><a class="<?php echo get_the_ID() === $policy_page->ID ? 'is-active' : ''; ?>" href="<?php echo esc_url( get_permalink( $policy_page ) ); ?>"><?php echo esc_html( get_the_title( $policy_page ) ); ?></a></li>
					<?php endforeach; ?>
				</ul>
				<div class="policy-help">
					<p><?php esc_html_e( 'আরও জানতে চান?', 'orpitey' ); ?></p>
					<a href="<?php echo esc_url( orpitey_tel_href( orpitey_mod( 'contact_phone' ) ) ); ?>"><?php orpitey_the_icon( 'phone', 16 ); ?> <?php echo esc_html( orpitey_mod( 'contact_phone' ) ); ?></a>
				</div>
			</aside>
			<div class="prose"><?php the_content(); ?></div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
