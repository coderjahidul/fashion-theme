<?php
/**
 * Single journal post.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$cats = get_the_category();
	?>
	<article <?php post_class( 'single-post' ); ?>>
		<header class="post-hero">
			<div class="container container--narrow text-center">
				<p class="section-kicker">
					<?php echo $cats ? esc_html( $cats[0]->name ) : esc_html( orpitey_mod( 'journal_kicker' ) ); ?>
				</p>
				<h1 class="post-hero__title"><?php the_title(); ?></h1>
				<p class="post-hero__meta">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( orpitey_post_date() ); ?></time>
					· <?php the_author(); ?>
				</p>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="post-featured container"><?php the_post_thumbnail( 'full' ); ?></figure>
		<?php endif; ?>

		<div class="container container--narrow">
			<div class="prose post-content">
				<?php
				the_content();
				wp_link_pages();
				?>
			</div>

			<?php
			$tags = get_the_tags();
			if ( $tags ) :
				?>
				<div class="chip-row post-tags">
					<?php foreach ( $tags as $post_tag ) : ?>
						<a class="chip" href="<?php echo esc_url( get_tag_link( $post_tag ) ); ?>">#<?php echo esc_html( $post_tag->name ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<nav class="post-nav" aria-label="<?php esc_attr_e( 'অন্যান্য লেখা', 'orpitey' ); ?>">
				<div><?php previous_post_link( '%link', orpitey_icon( 'arrow-left', 14 ) . ' %title' ); ?></div>
				<div><?php next_post_link( '%link', '%title ' . orpitey_icon( 'arrow-right', 14 ) ); ?></div>
			</nav>

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
