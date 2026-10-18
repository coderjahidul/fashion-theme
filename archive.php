<?php
/**
 * Post archives (category, tag, date, author).
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

get_header();

orpitey_page_header(
	array(
		'kicker' => orpitey_mod( 'journal_kicker' ),
		'title'  => wp_strip_all_tags( get_the_archive_title() ),
		'text'   => get_the_archive_description(),
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
