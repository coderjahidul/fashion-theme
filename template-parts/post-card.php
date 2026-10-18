<?php
/**
 * Journal post card (inside the loop).
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

$cats = get_the_category();
?>
<article <?php post_class( 'post-card group' ); ?>>
	<a class="post-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'orpitey-post', array( 'loading' => 'lazy' ) );
		} else {
			echo '<img src="' . esc_url( ORPITEY_URI . '/assets/images/campaign.jpg' ) . '" alt="" loading="lazy">';
		}
		?>
	</a>
	<div class="post-card__body">
		<p class="post-card__meta">
			<?php if ( $cats ) : ?>
				<span><?php echo esc_html( $cats[0]->name ); ?></span> ·
			<?php endif; ?>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( orpitey_post_date() ); ?></time>
		</p>
		<h3 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
		<a class="link-arrow" href="<?php the_permalink(); ?>"><?php esc_html_e( 'পড়ুন', 'orpitey' ); ?> <?php orpitey_the_icon( 'arrow-right', 14 ); ?></a>
	</div>
</article>
