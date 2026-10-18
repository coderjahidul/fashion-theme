<?php
/**
 * Template Name: যোগাযোগ (Contact)
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

get_header();

// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$status   = isset( $_GET['contact'] ) ? sanitize_key( $_GET['contact'] ) : '';
$messages = array(
	'sent'    => array( 'success', __( 'ধন্যবাদ! আপনার বার্তা পেয়েছি, শিগগিরই যোগাযোগ করব।', 'orpitey' ) ),
	'invalid' => array( 'error', __( 'অনুগ্রহ করে নাম, বার্তা এবং ফোন বা ইমেইল দিন।', 'orpitey' ) ),
	'error'   => array( 'error', __( 'বার্তা পাঠানো যায়নি, আবার চেষ্টা করুন।', 'orpitey' ) ),
);

$info = array(
	array( 'phone', __( 'ফোন', 'orpitey' ), orpitey_mod( 'contact_phone' ), orpitey_tel_href( orpitey_mod( 'contact_phone' ) ) ),
	array( 'mail', __( 'ইমেইল', 'orpitey' ), orpitey_mod( 'contact_email' ), 'mailto:' . orpitey_mod( 'contact_email' ) ),
	array( 'map-pin', __( 'ঠিকানা', 'orpitey' ), orpitey_mod( 'contact_address' ), '' ),
	array( 'clock', __( 'খোলার সময়', 'orpitey' ), orpitey_mod( 'contact_hours' ), '' ),
);
if ( orpitey_mod( 'contact_whatsapp' ) ) {
	$info[] = array( 'message', __( 'হোয়াটসঅ্যাপ', 'orpitey' ), orpitey_mod( 'contact_whatsapp' ), 'https://wa.me/' . preg_replace( '/\D/', '', orpitey_mod( 'contact_whatsapp' ) ) );
}

while ( have_posts() ) :
	the_post();
	orpitey_page_header(
		array(
			'kicker' => __( 'আমরা আছি আপনার পাশে', 'orpitey' ),
			'title'  => get_the_title(),
			'text'   => has_excerpt() ? wpautop( get_the_excerpt() ) : '',
		)
	);
	?>
	<section class="section section--tight">
		<div class="container contact-layout">
			<div class="contact-info">
				<?php foreach ( $info as $row ) : ?>
					<?php
					if ( ! $row[2] ) {
						continue;
					}
					?>
					<div class="contact-info__item">
						<span class="contact-info__icon"><?php orpitey_the_icon( $row[0], 20 ); ?></span>
						<div>
							<p class="contact-info__label"><?php echo esc_html( $row[1] ); ?></p>
							<?php if ( $row[3] ) : ?>
								<a href="<?php echo esc_url( $row[3] ); ?>"><?php echo esc_html( $row[2] ); ?></a>
							<?php else : ?>
								<p><?php echo nl2br( esc_html( $row[2] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; ?>
				<?php orpitey_social_links(); ?>
				<?php if ( get_the_content() ) : ?>
					<div class="prose contact-extra"><?php the_content(); ?></div>
				<?php endif; ?>
			</div>

			<div class="card contact-card">
				<h2 class="card__title"><?php esc_html_e( 'বার্তা পাঠান', 'orpitey' ); ?></h2>
				<?php if ( isset( $messages[ $status ] ) ) : ?>
					<div class="notice notice--<?php echo esc_attr( $messages[ $status ][0] ); ?>"><?php echo esc_html( $messages[ $status ][1] ); ?></div>
				<?php endif; ?>
				<form class="form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="orpitey_contact">
					<?php wp_nonce_field( 'orpitey_contact', 'orpitey_contact_nonce' ); ?>
					<div class="form-row form-row--2">
						<p>
							<label for="c-name"><?php esc_html_e( 'আপনার নাম', 'orpitey' ); ?> *</label>
							<input class="input" type="text" id="c-name" name="name" required>
						</p>
						<p>
							<label for="c-phone"><?php esc_html_e( 'ফোন নম্বর', 'orpitey' ); ?></label>
							<input class="input" type="tel" id="c-phone" name="phone">
						</p>
					</div>
					<p>
						<label for="c-email"><?php esc_html_e( 'ইমেইল', 'orpitey' ); ?></label>
						<input class="input" type="email" id="c-email" name="email">
					</p>
					<p>
						<label for="c-message"><?php esc_html_e( 'বার্তা', 'orpitey' ); ?> *</label>
						<textarea class="input" id="c-message" name="message" rows="5" required></textarea>
					</p>
					<p class="hp-field" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></p>
					<button type="submit" class="btn btn-primary btn-block"><?php esc_html_e( 'পাঠান', 'orpitey' ); ?> <?php orpitey_the_icon( 'arrow-right', 16 ); ?></button>
				</form>
			</div>
		</div>
	</section>
	<?php
endwhile;

get_footer();
