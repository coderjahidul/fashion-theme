<?php
/**
 * Fallback template.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

get_header();

orpitey_page_header(
	array(
		'title' => is_archive() ? wp_strip_all_tags( get_the_archive_title() ) : get_bloginfo( 'name' ),
	)
);
?>
<section class="section section--tight">
	<div class="container">
		<?php if ( have_posts() ) : ?>
			<div class="post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/post-card' );
				endwhile;
				?>
			</div>
			<?php orpitey_pagination(); ?>
		<?php else : ?>
			<?php get_template_part( 'template-parts/content-none' ); ?>
		<?php endif; ?>
	</div>
</section>
<?php
get_footer();
