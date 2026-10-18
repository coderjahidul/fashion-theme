<?php
/**
 * Comments.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments__title">
			<?php
			/* translators: %s: number of comments */
			echo esc_html( sprintf( __( '%sটি মন্তব্য', 'orpitey' ), orpitey_bn( get_comments_number() ) ) );
			?>
		</h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 44,
					'reply_text'  => __( 'উত্তর', 'orpitey' ),
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'          => __( 'মন্তব্য লিখুন', 'orpitey' ),
			'label_submit'         => __( 'পাঠান', 'orpitey' ),
			'class_submit'         => 'btn btn-primary',
			'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'মন্তব্য', 'orpitey' ) . ' *</label><textarea id="comment" class="input" name="comment" cols="45" rows="6" required></textarea></p>',
			'fields'               => array(
				'author'  => '<p class="comment-form-author"><label for="author">' . esc_html__( 'নাম', 'orpitey' ) . ' *</label><input id="author" class="input" name="author" type="text" required></p>',
				'email'   => '<p class="comment-form-email"><label for="email">' . esc_html__( 'ইমেইল', 'orpitey' ) . ' *</label><input id="email" class="input" name="email" type="email" required></p>',
				'cookies' => '<p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" value="yes"> <label for="wp-comment-cookies-consent">' . esc_html__( 'নাম ও ইমেইল এই ব্রাউজারে মনে রাখুন।', 'orpitey' ) . '</label></p>',
			),
			'comment_notes_before' => '',
			'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
			'title_reply_after'    => '</h3>',
		)
	);
	?>
</section>
