<?php
/**
 * Default page. Cart, checkout and account pages get a wide layout.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

get_header();

$is_app = function_exists( 'orpitey_is_wc_app_page' ) && orpitey_is_wc_app_page();

while ( have_posts() ) :
	the_post();

	$kicker = '';
	$title  = get_the_title();
	$aside  = '';
	if ( $is_app ) {
		if ( is_order_received_page() ) {
			$kicker = __( 'অর্ডার সম্পন্ন', 'orpitey' );
			$title  = __( 'ধন্যবাদ!', 'orpitey' );
		} elseif ( is_checkout() ) {
			$title = __( 'চেকআউট', 'orpitey' );
			$aside = orpitey_bag_count_label();
		} elseif ( is_cart() ) {
			$kicker = __( 'শপিং ব্যাগ', 'orpitey' );
			$title  = __( 'আপনার ব্যাগ', 'orpitey' );
		} elseif ( is_account_page() ) {
			$kicker = is_user_logged_in() ? __( 'স্বাগতম', 'orpitey' ) : __( 'Orpitey অ্যাকাউন্ট', 'orpitey' );
			$title  = is_user_logged_in() ? __( 'আমার অ্যাকাউন্ট', 'orpitey' ) : __( 'লগইন / রেজিস্টার', 'orpitey' );
		}
	}

	orpitey_page_header(
		array(
			'kicker' => $kicker,
			'title'  => $title,
			'aside'  => $aside,
			'text'   => ( ! $is_app && has_excerpt() ) ? wpautop( get_the_excerpt() ) : '',
		)
	);
	?>
	<section class="section section--tight">
		<div class="container<?php echo $is_app ? ' wc-app' : ' container--narrow'; ?>">
			<?php if ( ! $is_app && has_post_thumbnail() ) : ?>
				<figure class="page-featured"><?php the_post_thumbnail( 'large' ); ?></figure>
			<?php endif; ?>
			<div class="<?php echo $is_app ? 'wc-app__content' : 'prose'; ?>">
				<?php
				the_content();
				wp_link_pages();
				?>
			</div>
			<?php
			if ( ! $is_app && ( comments_open() || get_comments_number() ) ) {
				comments_template();
			}
			?>
		</div>
	</section>
	<?php
endwhile;

get_footer();
