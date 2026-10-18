<?php
/**
 * Homepage hero slider.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

$slides      = orpitey_hero_slides();
$multiple    = count( $slides ) > 1;
$show_dots   = $multiple && orpitey_hero_dots_enabled();
$show_arrows = $multiple && orpitey_hero_arrows_enabled();
if ( ! $slides ) {
	return;
}
?>
<section class="hero<?php echo $multiple ? ' hero--slider' : ''; ?>" id="top"<?php echo $multiple ? ' data-hero-slider' : ''; ?>>
	<div class="hero__slides">
		<?php foreach ( $slides as $index => $slide ) : ?>
			<?php $active = 0 === $index; ?>
			<div class="hero__slide<?php echo $active ? ' is-active' : ''; ?>" <?php echo $active ? '' : 'aria-hidden="true"'; ?>>
				<img class="hero__image" src="<?php echo esc_url( $slide['image'] ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( $slide['title'] ) ); ?>" width="1920" height="1088" <?php echo $active ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
				<div class="hero__overlay"></div>
				<div class="hero__inner">
					<div class="hero__content<?php echo $active ? ' hero-reveal' : ''; ?>">
						<?php if ( $slide['eyebrow'] ) : ?>
							<p class="hero__eyebrow"><span></span><?php echo esc_html( $slide['eyebrow'] ); ?></p>
						<?php endif; ?>
						<?php
						$title_tag = $active ? 'h1' : 'h2';
						printf(
							'<%1$s class="hero__title">%2$s</%1$s>',
							$title_tag, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							nl2br( esc_html( $slide['title'] ), false ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						);
						?>
						<?php if ( $slide['text'] ) : ?>
							<p class="hero__text"><?php echo esc_html( $slide['text'] ); ?></p>
						<?php endif; ?>
						<?php if ( $slide['button'] ) : ?>
							<a class="btn btn-gold" href="<?php echo esc_url( $slide['link'] ? $slide['link'] : '#collection' ); ?>"><?php echo esc_html( $slide['button'] ); ?> <?php orpitey_the_icon( 'arrow-right', 16 ); ?></a>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if ( $show_dots || $show_arrows ) : ?>
		<div class="hero__controls<?php echo ( $show_arrows && ! $show_dots ) ? ' hero__controls--arrows' : ''; ?>">
			<?php if ( $show_dots ) : ?>
				<div class="hero__dots" role="tablist" aria-label="<?php esc_attr_e( 'হিরো স্লাইড', 'orpitey' ); ?>">
					<?php foreach ( $slides as $index => $slide ) : ?>
						<button type="button" class="hero__dot<?php echo 0 === $index ? ' is-active' : ''; ?>" role="tab" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: slide number */ __( 'স্লাইড %d', 'orpitey' ), $index + 1 ) ); ?>" <?php echo 0 === $index ? 'aria-selected="true"' : 'aria-selected="false"'; ?>></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php if ( $show_arrows ) : ?>
				<div class="hero__nav">
					<button type="button" class="hero__arrow hero__arrow--prev" aria-label="<?php esc_attr_e( 'আগের স্লাইড', 'orpitey' ); ?>">
						<?php orpitey_the_icon( 'chevron-left', 18 ); ?>
					</button>
					<button type="button" class="hero__arrow hero__arrow--next" aria-label="<?php esc_attr_e( 'পরের স্লাইড', 'orpitey' ); ?>">
						<?php orpitey_the_icon( 'chevron-right', 18 ); ?>
					</button>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
