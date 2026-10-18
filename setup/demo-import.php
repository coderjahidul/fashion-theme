<?php
/**
 * WP-CLI: `wp orpitey setup` prepares pages, menus and WooCommerce settings for the theme.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

/**
 * Orpitey theme setup commands.
 */
class Orpitey_CLI {

	/**
	 * Create theme pages and menus, set the homepage and switch cart/checkout to the classic shortcodes.
	 *
	 * ## OPTIONS
	 *
	 * [--demo]
	 * : Also create four demo products (from the design images) and three journal posts.
	 *
	 * [--keep-blocks]
	 * : Leave the Cart and Checkout pages on WooCommerce blocks.
	 *
	 * ## EXAMPLES
	 *
	 *     wp orpitey setup
	 *     wp orpitey setup --demo
	 *
	 * @param array $args       Positional args.
	 * @param array $assoc_args Flags.
	 */
	public function setup( $args, $assoc_args ) {
		$pages = $this->create_pages();

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $pages['home'] );
		update_option( 'page_for_posts', $pages['journal'] );
		WP_CLI::log( 'Homepage and Journal (posts page) set.' );

		if ( class_exists( 'WooCommerce' ) ) {
			if ( empty( $assoc_args['keep-blocks'] ) ) {
				$this->use_classic_shortcode( wc_get_page_id( 'cart' ), '[woocommerce_cart]' );
				$this->use_classic_shortcode( wc_get_page_id( 'checkout' ), '[woocommerce_checkout]' );
			}
			update_option( 'woocommerce_currency', 'BDT' );
			update_option( 'woocommerce_price_num_decimals', 0 );
			update_option( 'woocommerce_enable_ajax_add_to_cart', 'yes' );
			update_option( 'woocommerce_cart_redirect_after_add', 'no' );
			update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
			update_option( 'woocommerce_registration_generate_password', 'no' );
			WP_CLI::log( 'WooCommerce: BDT, 0 decimals, AJAX add to cart.' );
		}

		$this->create_menus( $pages );

		if ( ! empty( $assoc_args['demo'] ) ) {
			$this->demo_products();
			$this->demo_posts();
		}

		flush_rewrite_rules();
		WP_CLI::success( 'Orpitey setup complete.' );
	}

	/**
	 * Restore the original block content of Cart and Checkout.
	 *
	 * ## EXAMPLES
	 *
	 *     wp orpitey restore-blocks
	 *
	 * @subcommand restore-blocks
	 */
	public function restore_blocks() {
		foreach ( array( 'cart', 'checkout' ) as $key ) {
			$id       = wc_get_page_id( $key );
			$original = get_post_meta( $id, '_orpitey_original_content', true );
			if ( $original ) {
				wp_update_post(
					array(
						'ID'           => $id,
						'post_content' => wp_slash( $original ),
					)
				);
				delete_post_meta( $id, '_orpitey_original_content' );
				WP_CLI::log( ucfirst( $key ) . ' restored.' );
			}
		}
		WP_CLI::success( 'Done.' );
	}

	/**
	 * Create or reuse theme pages.
	 *
	 * @return array slug => ID
	 */
	private function create_pages() {
		$defs = array(
			'home'            => array( 'হোম', '', '' ),
			'journal'         => array( 'Orpitey Journal', '', '' ),
			'about'           => array( 'ঐতিহ্যের সুতোয় বোনা আমাদের গল্প', 'page-templates/page-about.php', $this->about_content() ),
			'contact'         => array( 'যোগাযোগ', 'page-templates/page-contact.php', '' ),
			'wishlist'        => array( 'পছন্দের তালিকা', 'page-templates/page-wishlist.php', '' ),
			'delivery-info'   => array( 'ডেলিভারি তথ্য', 'page-templates/page-policy.php', $this->delivery_content() ),
			'exchange-policy' => array( 'পরিবর্তন নীতি', 'page-templates/page-policy.php', $this->exchange_content() ),
			'how-to-order'    => array( 'কীভাবে অর্ডার করবেন', 'page-templates/page-policy.php', $this->order_content() ),
		);

		$ids   = array();
		$order = 0;
		foreach ( $defs as $slug => $def ) {
			list( $title, $template, $content ) = $def;
			$existing = get_page_by_path( $slug );
			if ( $existing ) {
				$ids[ $slug ] = $existing->ID;
				WP_CLI::log( "Page exists: {$slug}" );
			} else {
				$ids[ $slug ] = wp_insert_post(
					array(
						'post_type'    => 'page',
						'post_status'  => 'publish',
						'post_title'   => $title,
						'post_name'    => $slug,
						'post_content' => $content,
						'menu_order'   => $order,
					)
				);
				WP_CLI::log( "Page created: {$slug}" );
			}
			if ( $template ) {
				update_post_meta( $ids[ $slug ], '_wp_page_template', $template );
			}
			++$order;
		}

		if ( ! get_post_field( 'post_excerpt', $ids['about'] ) ) {
			wp_update_post(
				array(
					'ID'           => $ids['about'],
					'post_excerpt' => 'বাংলাদেশের তাঁতি ও কারিগরদের হাতের কাজকে আধুনিক রুচিতে পৌঁছে দেওয়াই Orpitey-এর লক্ষ্য।',
				)
			);
		}

		$privacy = (int) get_option( 'wp_page_for_privacy_policy' );
		if ( $privacy ) {
			update_post_meta( $privacy, '_wp_page_template', 'page-templates/page-policy.php' );
		}
		$refund = get_page_by_path( 'refund_returns' );
		if ( $refund ) {
			update_post_meta( $refund->ID, '_wp_page_template', 'page-templates/page-policy.php' );
		}

		return $ids;
	}

	/**
	 * Swap a block-based WooCommerce page to its classic shortcode, keeping a backup.
	 *
	 * @param int    $page_id   Page id.
	 * @param string $shortcode Shortcode.
	 */
	private function use_classic_shortcode( $page_id, $shortcode ) {
		if ( $page_id <= 0 ) {
			return;
		}
		$content = get_post_field( 'post_content', $page_id );
		if ( false !== strpos( $content, $shortcode ) ) {
			return;
		}
		update_post_meta( $page_id, '_orpitey_original_content', $content );
		wp_update_post(
			array(
				'ID'           => $page_id,
				'post_content' => '<!-- wp:shortcode -->' . $shortcode . '<!-- /wp:shortcode -->',
			)
		);
		WP_CLI::log( "Page {$page_id} now uses {$shortcode} (backup saved; undo with `wp orpitey restore-blocks`)." );
	}

	/**
	 * Build and assign menus.
	 *
	 * @param array $pages Page ids.
	 */
	private function create_menus( $pages ) {
		$locations = get_theme_mod( 'nav_menu_locations', array() );

		if ( empty( $locations['primary'] ) ) {
			$menu_id = $this->fresh_menu( 'Orpitey হেডার' );
			foreach ( array( 'saree' => 'শাড়ি', 'three-piece' => 'থ্রি-পিস' ) as $slug => $label ) {
				$term = orpitey_resolve_product_cat( $slug );
				if ( $term ) {
					wp_update_nav_menu_item(
						$menu_id,
						0,
						array(
							'menu-item-title'     => $label,
							'menu-item-object'    => 'product_cat',
							'menu-item-object-id' => $term->term_id,
							'menu-item-type'      => 'taxonomy',
							'menu-item-status'    => 'publish',
						)
					);
				}
			}
			if ( function_exists( 'wc_get_page_permalink' ) ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-title'  => 'নতুন সংগ্রহ',
						'menu-item-url'    => add_query_arg( 'orderby', 'date', wc_get_page_permalink( 'shop' ) ),
						'menu-item-type'   => 'custom',
						'menu-item-status' => 'publish',
					)
				);
			}
			$locations['primary'] = $menu_id;
			WP_CLI::log( 'Header menu created.' );
		}

		if ( empty( $locations['footer-support'] ) ) {
			$menu_id = $this->fresh_menu( 'Orpitey সহায়তা' );
			$items   = array( 'how-to-order', 'delivery-info', 'exchange-policy', 'about', 'contact' );
			foreach ( $items as $slug ) {
				wp_update_nav_menu_item(
					$menu_id,
					0,
					array(
						'menu-item-title'     => 'about' === $slug ? 'আমাদের সম্পর্কে' : get_the_title( $pages[ $slug ] ),
						'menu-item-object'    => 'page',
						'menu-item-object-id' => $pages[ $slug ],
						'menu-item-type'      => 'post_type',
						'menu-item-status'    => 'publish',
					)
				);
			}
			$locations['footer-support'] = $menu_id;
			WP_CLI::log( 'Footer support menu created.' );
		}

		set_theme_mod( 'nav_menu_locations', $locations );
	}

	/**
	 * Create a menu, replacing one with the same name.
	 *
	 * @param string $name Menu name.
	 * @return int
	 */
	private function fresh_menu( $name ) {
		$existing = wp_get_nav_menu_object( $name );
		if ( $existing ) {
			wp_delete_nav_menu( $existing->term_id );
		}
		return (int) wp_create_nav_menu( $name );
	}

	/**
	 * Demo products from the design.
	 */
	private function demo_products() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		$saree = $this->ensure_cat( 'saree', 'Saree' );
		$three = $this->ensure_cat( 'three-piece', 'Three Piece' );

		$products = array(
			array( 'নীলিমা নূর থ্রি-পিস', 'nilima-noor-three-piece', 'product-blue.jpg', $three, 3450, '', 'সফট কটন · সূক্ষ্ম কারুকাজ', 'নতুন', 'নরম কটন কাপড়ে সূক্ষ্ম নকশার একটি আরামদায়ক থ্রি-পিস—দিনের আয়োজন থেকে সন্ধ্যার নিমন্ত্রণে সমান মানানসই।' ),
			array( 'মেহরুন কারুকাজ', 'meherun-karukaj', 'product-wine.jpg', $three, 5800, 4950, 'প্রিমিয়াম সিল্ক · জরি কাজ', '', 'গাঢ় মেরুন সিল্কে সোনালি জরির কাজ—উৎসবের সন্ধ্যায় রাজকীয় উপস্থিতি।' ),
			array( 'সোনালি আয়োজন', 'sonali-ayojon', 'product-mustard.jpg', $three, 3850, '', 'লন কটন · টোনাল এমব্রয়ডারি', '', 'হলুদ লন কটনে টোনাল এমব্রয়ডারি—হালকা, উজ্জ্বল আর গায়ে হলুদের জন্য একদম মানানসই।' ),
			array( 'রক্তিম জামদানি শাড়ি', 'roktim-jamdani-saree', 'hero.jpg', $saree, 8500, '', 'প্রিমিয়াম সিল্ক · অ্যান্টিক জরি', 'বেস্টসেলার', 'ঐতিহ্যবাহী জামদানি বুননে অ্যান্টিক জরির পাড়—বিয়ে ও বিশেষ দিনের জন্য।' ),
		);

		require_once ABSPATH . 'wp-admin/includes/image.php';

		foreach ( $products as $p ) {
			list( $name, $slug, $image, $cat, $regular, $sale, $subtitle, $badge, $excerpt ) = $p;
			if ( get_page_by_path( $slug, OBJECT, 'product' ) ) {
				WP_CLI::log( "Product exists: {$slug}" );
				continue;
			}
			$product = new WC_Product_Simple();
			$product->set_name( $name );
			$product->set_slug( $slug );
			$product->set_status( 'publish' );
			$product->set_regular_price( (string) $regular );
			if ( $sale ) {
				$product->set_sale_price( (string) $sale );
			}
			$product->set_short_description( $excerpt );
			$product->set_description( "<ul><li>কামিজ: প্রিমিয়াম কাপড়, হাতের কারুকাজ</li><li>ওড়না: হালকা অর্গানজা, এমব্রয়ডারি বর্ডার</li><li>পণ্যের রঙ ছবির তুলনায় সামান্য ভিন্ন হতে পারে</li></ul>" );
			$product->set_category_ids( array( $cat ) );
			$product->set_featured( 'বেস্টসেলার' === $badge );
			$product->set_image_id( $this->import_image( $image ) );
			$product->update_meta_data( '_orpitey_subtitle', $subtitle );
			$product->update_meta_data( '_orpitey_collection', 'উৎসব সংগ্রহ' );
			if ( $badge ) {
				$product->update_meta_data( '_orpitey_badge', $badge );
			}
			$product->save();
			WP_CLI::log( "Product created: {$slug}" );
		}
	}

	/**
	 * Demo journal posts.
	 */
	private function demo_posts() {
		$posts = array(
			array( 'ঈদে কোন শাড়ি মানাবে আপনাকে', 'eid-saree-guide', 'campaign.jpg', 'রঙ, কাপড় আর আবহাওয়া বুঝে ঈদের দিনের শাড়ি বেছে নেওয়ার সহজ কিছু পরামর্শ।' ),
			array( 'জামদানির যত্ন: প্রজন্ম থেকে প্রজন্ম', 'jamdani-care', 'hero.jpg', 'জামদানি শাড়ি ভাঁজ করা, রাখা ও পরিষ্কারের নিয়ম—যাতে বুনন থাকে আগের মতোই।' ),
			array( 'থ্রি-পিসে প্রতিদিনের স্টাইল', 'three-piece-daily-style', 'product-mustard.jpg', 'অফিস থেকে আড্ডা—একটি থ্রি-পিস দিয়ে তিনভাবে সাজার আইডিয়া।' ),
		);
		require_once ABSPATH . 'wp-admin/includes/image.php';
		foreach ( $posts as $p ) {
			list( $title, $slug, $image, $excerpt ) = $p;
			if ( get_page_by_path( $slug, OBJECT, 'post' ) ) {
				continue;
			}
			$id = wp_insert_post(
				array(
					'post_title'   => $title,
					'post_name'    => $slug,
					'post_status'  => 'publish',
					'post_excerpt' => $excerpt,
					'post_content' => '<!-- wp:paragraph --><p>' . $excerpt . '</p><!-- /wp:paragraph --><!-- wp:heading --><h2>শুরুটা হোক রঙ দিয়ে</h2><!-- /wp:heading --><!-- wp:paragraph --><p>উৎসবের দিনে উজ্জ্বল রঙ, আর সন্ধ্যার আয়োজনে গাঢ় মেরুন বা সোনালি—এই সহজ নিয়মেই অর্ধেক কাজ হয়ে যায়।</p><!-- /wp:paragraph -->',
				)
			);
			set_post_thumbnail( $id, $this->import_image( $image ) );
			WP_CLI::log( "Post created: {$slug}" );
		}
	}

	/**
	 * Ensure a product category exists.
	 *
	 * @param string $slug Slug.
	 * @param string $name Name.
	 * @return int
	 */
	private function ensure_cat( $slug, $name ) {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( $term ) {
			return (int) $term->term_id;
		}
		$new = wp_insert_term( $name, 'product_cat', array( 'slug' => $slug ) );
		return is_wp_error( $new ) ? 0 : (int) $new['term_id'];
	}

	/**
	 * Copy a theme image into the media library.
	 *
	 * @param string $file File name in assets/images.
	 * @return int Attachment id.
	 */
	private function import_image( $file ) {
		$existing = get_posts(
			array(
				'post_type'   => 'attachment',
				'meta_key'    => '_orpitey_source', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value'  => $file, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
				'numberposts' => 1,
				'fields'      => 'ids',
			)
		);
		if ( $existing ) {
			return (int) $existing[0];
		}
		$upload = wp_upload_bits( 'orpitey-' . $file, null, file_get_contents( ORPITEY_DIR . '/assets/images/' . $file ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( ! empty( $upload['error'] ) ) {
			WP_CLI::warning( $upload['error'] );
			return 0;
		}
		$type = wp_check_filetype( $upload['file'] );
		$id   = wp_insert_attachment(
			array(
				'post_mime_type' => $type['type'],
				'post_title'     => pathinfo( $file, PATHINFO_FILENAME ),
				'post_status'    => 'inherit',
			),
			$upload['file']
		);
		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
		update_post_meta( $id, '_orpitey_source', $file );
		return (int) $id;
	}

	/**
	 * About page content.
	 *
	 * @return string
	 */
	private function about_content() {
		return '<!-- wp:paragraph --><p>Orpitey-এর শুরু একটি সহজ ভাবনা থেকে—বাংলাদেশের তাঁত, জামদানি আর হাতের কারুকাজ যেন প্রতিদিনের পোশাকে জায়গা পায়।</p><!-- /wp:paragraph --><!-- wp:paragraph --><p>আমরা সরাসরি কারিগর ও বিশ্বস্ত সরবরাহকারীদের সঙ্গে কাজ করি। প্রতিটি শাড়ি ও থ্রি-পিস পাঠানোর আগে কাপড়, সেলাই ও রঙ যাচাই করা হয়।</p><!-- /wp:paragraph --><!-- wp:heading --><h2>আমাদের লক্ষ্য</h2><!-- /wp:heading --><!-- wp:paragraph --><p>ঐতিহ্যকে আধুনিক নারীর রুচিতে তুলে ধরা, ন্যায্য দামে এবং সারাদেশে নির্ভরযোগ্য ডেলিভারিতে।</p><!-- /wp:paragraph -->';
	}

	/**
	 * Delivery page content.
	 *
	 * @return string
	 */
	private function delivery_content() {
		return '<!-- wp:heading --><h2>ডেলিভারি সময়</h2><!-- /wp:heading --><!-- wp:list --><ul><li>ঢাকার ভেতরে: ২–৩ কার্যদিবস</li><li>ঢাকার বাইরে: ৩–৫ কার্যদিবস</li></ul><!-- /wp:list --><!-- wp:heading --><h2>ডেলিভারি চার্জ</h2><!-- /wp:heading --><!-- wp:list --><ul><li>ঢাকার ভেতরে: ৭০ টাকা</li><li>ঢাকার বাইরে: ১৩০ টাকা</li><li>৫,০০০ টাকার বেশি অর্ডারে ডেলিভারি ফ্রি</li></ul><!-- /wp:list --><!-- wp:heading --><h2>পেমেন্ট</h2><!-- /wp:heading --><!-- wp:paragraph --><p>ক্যাশ অন ডেলিভারি—পণ্য হাতে পেয়ে মূল্য পরিশোধ করুন।</p><!-- /wp:paragraph -->';
	}

	/**
	 * Exchange page content.
	 *
	 * @return string
	 */
	private function exchange_content() {
		return '<!-- wp:paragraph --><p>পণ্য হাতে পাওয়ার ৭ দিনের মধ্যে সাইজ বা রঙ পরিবর্তন করা যাবে।</p><!-- /wp:paragraph --><!-- wp:heading --><h2>শর্তসমূহ</h2><!-- /wp:heading --><!-- wp:list --><ul><li>পণ্য অব্যবহৃত ও ট্যাগসহ থাকতে হবে</li><li>অর্ডার নম্বর ও ছবি সহ আমাদের জানাতে হবে</li><li>ত্রুটিপূর্ণ পণ্যের ক্ষেত্রে পরিবর্তনের ডেলিভারি চার্জ আমরা বহন করব</li></ul><!-- /wp:list -->';
	}

	/**
	 * How to order content.
	 *
	 * @return string
	 */
	private function order_content() {
		return '<!-- wp:list {"ordered":true} --><ol><li>পছন্দের পণ্যটি খুলে সাইজ ও রঙ বাছাই করুন</li><li>“ব্যাগে যোগ করুন” অথবা “অর্ডার করুন” চাপুন</li><li>চেকআউটে নাম, ফোন নম্বর ও ঠিকানা দিন</li><li>“অর্ডার নিশ্চিত করুন” চাপুন—আমরা ফোন করে অর্ডার নিশ্চিত করব</li></ol><!-- /wp:list -->';
	}
}

WP_CLI::add_command( 'orpitey', 'Orpitey_CLI' );
