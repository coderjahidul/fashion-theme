<?php
/**
 * Extra product fields: subtitle, badge, care instructions.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'woocommerce_product_options_general_product_data',
	function () {
		echo '<div class="options_group">';
		woocommerce_wp_text_input(
			array(
				'id'          => '_orpitey_subtitle',
				'label'       => __( 'Subtitle', 'orpitey' ),
				'placeholder' => 'Soft cotton · fine work',
				'desc_tip'    => true,
				'description' => __( 'Shown under the product name (fabric · work).', 'orpitey' ),
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'          => '_orpitey_badge',
				'label'       => __( 'Badge', 'orpitey' ),
				'placeholder' => 'New / Bestseller',
				'desc_tip'    => true,
				'description' => __( 'Leave empty for automatic: sale, bestseller (featured), or new (within 30 days).', 'orpitey' ),
			)
		);
		woocommerce_wp_text_input(
			array(
				'id'          => '_orpitey_collection',
				'label'       => __( 'Collection name', 'orpitey' ),
				'placeholder' => 'Festival collection',
				'desc_tip'    => true,
				'description' => __( 'Shown next to the category on the product page.', 'orpitey' ),
			)
		);
		woocommerce_wp_textarea_input(
			array(
				'id'          => '_orpitey_care',
				'label'       => __( 'Care instructions', 'orpitey' ),
				'desc_tip'    => true,
				'description' => __( 'One per line. If empty, the Theme Options default is used.', 'orpitey' ),
			)
		);

		$guide_id  = absint( get_post_meta( get_the_ID(), '_orpitey_size_guide', true ) );
		$guide_url = $guide_id ? wp_get_attachment_image_url( $guide_id, 'medium' ) : '';
		?>
		<p class="form-field orpitey-size-guide-field">
			<label for="orpitey-size-guide-pick"><?php esc_html_e( 'Size guide image', 'orpitey' ); ?></label>
			<span class="orpitey-size-guide-control">
				<input type="hidden" name="_orpitey_size_guide" value="<?php echo esc_attr( $guide_id ? (string) $guide_id : '' ); ?>">
				<img class="orpitey-size-guide-preview<?php echo $guide_url ? '' : ' is-empty'; ?>" src="<?php echo esc_url( $guide_url ); ?>" alt="">
				<button type="button" class="button" id="orpitey-size-guide-pick"><?php esc_html_e( 'Choose image', 'orpitey' ); ?></button>
				<button type="button" class="button orpitey-size-guide-remove<?php echo $guide_url ? '' : ' is-empty'; ?>"><?php esc_html_e( 'Remove', 'orpitey' ); ?></button>
				<span class="description"><?php esc_html_e( 'Shown in a popup when Size guide is opened on the product page.', 'orpitey' ); ?></span>
			</span>
		</p>
		<?php
		echo '</div>';
	}
);

add_action(
	'admin_enqueue_scripts',
	function ( $hook ) {
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( ! $screen || 'product' !== $screen->post_type ) {
			return;
		}
		wp_enqueue_media();
		wp_register_style( 'orpitey-product-meta', false, array(), ORPITEY_VERSION );
		wp_enqueue_style( 'orpitey-product-meta' );
		wp_add_inline_style(
			'orpitey-product-meta',
			'.orpitey-size-guide-control{display:inline-block;vertical-align:top;}
			.orpitey-size-guide-preview{display:block;max-width:180px;height:auto;margin:0 0 8px;border:1px solid #c3c4c7;}
			.orpitey-size-guide-preview.is-empty,.orpitey-size-guide-remove.is-empty{display:none;}
			.orpitey-size-guide-control .description{display:block;margin-top:6px;}'
		);
		wp_add_inline_script(
			'jquery',
			"(function($){
				var frame;
				$(document).on('click', '#orpitey-size-guide-pick', function(e){
					e.preventDefault();
					if (frame) { frame.open(); return; }
					frame = wp.media({ title: 'Size guide image', button: { text: 'Use this image' }, multiple: false, library: { type: 'image' } });
					frame.on('select', function(){
						var file = frame.state().get('selection').first().toJSON();
						var field = $('.orpitey-size-guide-field');
						field.find('input[name=\"_orpitey_size_guide\"]').val(file.id);
						field.find('.orpitey-size-guide-preview').attr('src', file.sizes && file.sizes.medium ? file.sizes.medium.url : file.url).removeClass('is-empty');
						field.find('.orpitey-size-guide-remove').removeClass('is-empty');
					});
					frame.open();
				});
				$(document).on('click', '.orpitey-size-guide-remove', function(e){
					e.preventDefault();
					var field = $(this).closest('.orpitey-size-guide-field');
					field.find('input[name=\"_orpitey_size_guide\"]').val('');
					field.find('.orpitey-size-guide-preview').attr('src', '').addClass('is-empty');
					$(this).addClass('is-empty');
				});
			})(jQuery);"
		);
	}
);

add_action(
	'woocommerce_admin_process_product_object',
	function ( WC_Product $product ) {
		// Nonce is verified by WooCommerce before this action fires.
		// phpcs:disable WordPress.Security.NonceVerification.Missing
		foreach ( array( '_orpitey_subtitle', '_orpitey_badge', '_orpitey_collection' ) as $key ) {
			if ( isset( $_POST[ $key ] ) ) {
				$product->update_meta_data( $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
			}
		}
		if ( isset( $_POST['_orpitey_care'] ) ) {
			$product->update_meta_data( '_orpitey_care', sanitize_textarea_field( wp_unslash( $_POST['_orpitey_care'] ) ) );
		}
		if ( isset( $_POST['_orpitey_size_guide'] ) ) {
			$guide_id = absint( wp_unslash( $_POST['_orpitey_size_guide'] ) );
			if ( $guide_id && ! wp_attachment_is_image( $guide_id ) ) {
				$guide_id = 0;
			}
			$product->update_meta_data( '_orpitey_size_guide', $guide_id );
		}
		// phpcs:enable
	}
);
