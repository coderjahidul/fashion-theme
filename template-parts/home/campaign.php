<?php
/**
 * Homepage campaign blocks.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

$shop   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
$only   = ( isset( $args ) && is_array( $args ) && isset( $args['uid'] ) ) ? (string) $args['uid'] : '';
$anchor = ( isset( $args ) && is_array( $args ) && ! empty( $args['anchor'] ) );

foreach ( orpitey_campaigns() as $index => $campaign ) :
	if ( $only && $campaign['uid'] !== $only ) {
		continue;
	}
	$link = $campaign['link'] ? $campaign['link'] : $shop;
	$html_id = ( $anchor || ( ! $only && 0 === $index ) ) ? 'campaign' : 'campaign-' . $campaign['uid'];
	?>
	<section class="campaign" id="<?php echo esc_attr( $html_id ); ?>">
		<?php if ( $campaign['image'] ) : ?>
			<div class="campaign__media">
				<img src="<?php echo esc_url( $campaign['image'] ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( $campaign['title'] ) ); ?>" width="1408" height="912" loading="lazy">
			</div>
		<?php endif; ?>
		<div class="campaign__panel pattern-panel">
			<div class="campaign__content">
				<?php if ( $campaign['kicker'] ) : ?>
					<p class="section-kicker section-kicker--light"><?php echo esc_html( $campaign['kicker'] ); ?></p>
				<?php endif; ?>
				<?php if ( $campaign['title'] ) : ?>
					<h2 class="campaign__title"><?php echo nl2br( esc_html( $campaign['title'] ), false ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<?php endif; ?>
				<?php if ( $campaign['text'] ) : ?>
					<p class="campaign__text"><?php echo esc_html( $campaign['text'] ); ?></p>
				<?php endif; ?>
				<?php if ( $campaign['button'] ) : ?>
					<a class="btn btn-gold-outline" href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $campaign['button'] ); ?> <?php orpitey_the_icon( 'arrow-right', 16 ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
endforeach;
