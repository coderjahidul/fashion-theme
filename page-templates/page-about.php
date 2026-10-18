<?php
/**
 * Template Name: আমাদের সম্পর্কে (About)
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$campaigns = orpitey_campaigns();
	$fallback  = ! empty( $campaigns[0]['image'] ) ? $campaigns[0]['image'] : orpitey_mod( 'campaign_image' );
	$image     = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'full' ) : $fallback;
	?>
	<section class="about-hero">
		<img class="about-hero__image" src="<?php echo esc_url( $image ); ?>" alt="">
		<div class="hero__overlay"></div>
		<div class="about-hero__inner container">
			<p class="hero__eyebrow"><span></span><?php esc_html_e( 'আমাদের গল্প', 'orpitey' ); ?></p>
			<h1 class="about-hero__title"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="hero__text"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<section class="section">
		<div class="container container--narrow">
			<div class="prose prose--lead"><?php the_content(); ?></div>
		</div>
	</section>

	<section class="section section--soft">
		<div class="container">
			<div class="text-center"><?php orpitey_section_heading( __( 'কেন Orpitey', 'orpitey' ), __( 'আমাদের প্রতিশ্রুতি', 'orpitey' ) ); ?></div>
			<div class="value-grid">
				<?php foreach ( orpitey_trust_items( 'home' ) as $item ) : ?>
					<div class="value-card">
						<span class="value-card__icon"><?php orpitey_the_icon( $item['icon'], 24 ); ?></span>
						<h3><?php echo esc_html( $item['title'] ); ?></h3>
						<p><?php echo esc_html( $item['text'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<?php get_template_part( 'template-parts/home/campaign' ); ?>
	<?php
endwhile;

get_footer();
