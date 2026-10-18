<?php
/**
 * Journal (blog posts page).
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

get_header();

$posts_page = get_option( 'page_for_posts' );

orpitey_page_header(
	array(
		'kicker' => orpitey_mod( 'journal_kicker' ),
		'title'  => $posts_page ? get_the_title( $posts_page ) : orpitey_mod( 'journal_title' ),
		'text'   => '<p>' . esc_html( orpitey_mod( 'journal_text' ) ) . '</p>',
	)
);

$cats = get_categories( array( 'hide_empty' => true ) );
?>
<section class="section section--tight">
	<div class="container">
		<?php if ( count( $cats ) > 1 ) : ?>
			<div class="chip-row journal-cats">
				<?php foreach ( $cats as $journal_cat ) : ?>
					<a class="chip" href="<?php echo esc_url( get_category_link( $journal_cat ) ); ?>"><?php echo esc_html( $journal_cat->name ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

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
