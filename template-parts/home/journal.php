<?php
/**
 * Homepage journal intro with the latest posts.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

$limit = (int) orpitey_mod( 'journal_posts' );
$posts = $limit ? get_posts(
	array(
		'numberposts'         => $limit,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
	)
) : array();
?>
<section class="section journal-intro">
	<div class="container">
		<div class="text-center">
			<?php orpitey_section_heading( orpitey_mod( 'journal_kicker' ), orpitey_mod( 'journal_title' ) ); ?>
			<p class="section-lead"><?php echo esc_html( orpitey_mod( 'journal_text' ) ); ?></p>
		</div>

		<?php if ( $posts ) : ?>
			<div class="post-grid">
				<?php
				global $post;
				foreach ( $posts as $post ) :
					setup_postdata( $post );
					get_template_part( 'template-parts/post-card' );
				endforeach;
				wp_reset_postdata();
				?>
			</div>
			<?php
			$blog = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : '';
			if ( $blog ) :
				?>
				<div class="section-cta"><a class="btn btn-outline" href="<?php echo esc_url( $blog ); ?>"><?php esc_html_e( 'সব লেখা পড়ুন', 'orpitey' ); ?> <?php orpitey_the_icon( 'arrow-right', 16 ); ?></a></div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>
