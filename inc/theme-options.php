<?php
/**
 * Theme Options admin page. Values stay in theme mods so the front end is unchanged.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

/**
 * Default values for every theme mod.
 *
 * @return array
 */
function orpitey_defaults() {
	return array(
		'announcement'        => 'সারাদেশে হোম ডেলিভারি · 5,000 টাকার বেশি অর্ডারে ডেলিভারি ফ্রি',
		'color_primary'       => '#5c0c11',
		'color_accent'        => '#ce9e52',
		'color_background'    => '#fcf6eb',
		'color_text'          => '#2b1b14',
		'font_body'           => 'shadhinata',
		'font_display'        => 'shadhinata',

		'hero_image'          => ORPITEY_URI . '/assets/images/hero.jpg',
		'hero_eyebrow'        => 'ঈদ · বিয়ে · বিশেষ আয়োজন',
		'hero_title'          => "ঐতিহ্যের বুননে\nআপনার গল্প",
		'hero_text'           => 'বাংলাদেশের কারুশিল্প আর আধুনিক রুচির মেলবন্ধনে—প্রতিটি পোশাক আপনার বিশেষ মুহূর্তের জন্য।',
		'hero_button'         => 'সংগ্রহ দেখুন',
		'hero_link'           => '#collection',

		'hero_2_image'        => ORPITEY_URI . '/assets/images/campaign.jpg',
		'hero_2_eyebrow'      => 'উৎসব 2026',
		'hero_2_title'        => "এক ফ্রেমে\nশাড়ি ও থ্রি-পিস",
		'hero_2_text'         => 'বিয়ে, ঈদ কিংবা প্রিয়জনের সঙ্গে বিশেষ দিন—শাড়ি ও থ্রি-পিসে থাকুক আপনার নিজস্বতা।',
		'hero_2_button'       => 'উৎসব সংগ্রহ',
		'hero_2_link'         => '#campaign',

		'hero_3_image'        => ORPITEY_URI . '/assets/images/product-wine.jpg',
		'hero_3_eyebrow'      => 'থ্রি-পিস',
		'hero_3_title'        => "নরম আলোয়\nপ্রতিদিনের সাজ",
		'hero_3_text'         => 'সূক্ষ্ম কারুকাজের থ্রি-পিস—ঘরের আরাম থেকে উৎসবের মুহূর্ত, একই রুচিতে।',
		'hero_3_button'       => 'থ্রি-পিস দেখুন',
		'hero_3_link'         => '#collection',

		'trust_1_title'       => 'দ্রুত ডেলিভারি',
		'trust_1_text'        => 'সারাদেশে 3–5 দিন',
		'trust_2_title'       => 'সহজ পরিবর্তন',
		'trust_2_text'        => '7 দিনের মধ্যে',
		'trust_3_title'       => 'মান নিশ্চিত',
		'trust_3_text'        => 'যাচাই করা প্রতিটি পোশাক',

		'collection_kicker'   => 'আপনার জন্য বাছাই করা',
		'collection_title'    => 'প্রিয় সংগ্রহ',
		'collection_cats'     => 'saree,three-piece',
		'collection_count'    => 8,

		'campaign_image'      => ORPITEY_URI . '/assets/images/campaign.jpg',
		'campaign_kicker'     => 'উৎসব 2026',
		'campaign_title'      => "উৎসবের রঙে\nরাঙিয়ে নিন",
		'campaign_text'       => 'বিয়ে, ঈদ কিংবা প্রিয়জনের সঙ্গে বিশেষ দিন—Orpitey-এর শাড়ি ও থ্রি-পিসে থাকুক আপনার নিজস্বতা।',
		'campaign_button'     => 'উৎসব সংগ্রহ',
		'campaign_link'       => '',

		'journal_kicker'      => 'Orpitey Journal',
		'journal_title'       => 'আপনার সাজ, আপনার পরিচয়',
		'journal_text'        => 'ঐতিহ্য থেকে আধুনিকতা—পোশাকের ভাষায় প্রতিদিনের গল্প লিখুন নিজের মতো করে।',
		'journal_posts'       => 3,

		'product_trust_1'     => 'দ্রুত ডেলিভারি|ঢাকায় 2–3 দিন',
		'product_trust_2'     => 'সহজ পরিবর্তন|7 দিনের মধ্যে',
		'product_trust_3'     => 'নিরাপদ পেমেন্ট|ক্যাশ অন ডেলিভারি',
		'product_delivery'    => "ঢাকার ভেতরে 2–3 দিন এবং ঢাকার বাইরে 3–5 দিনের মধ্যে ডেলিভারি।\n5,000 টাকার বেশি অর্ডারে ডেলিভারি ফ্রি।\nপণ্য হাতে পাওয়ার 7 দিনের মধ্যে সাইজ বা রঙ পরিবর্তন করা যাবে।",
		'product_care'        => "প্রথমবার ড্রাই ওয়াশ করুন।\nহালকা গরম আয়রন, উল্টো দিক থেকে।\nসরাসরি রোদে শুকাবেন না।",
		'size_guide'          => "S — বুক 36\"\nM — বুক 38\"\nL — বুক 40\"\nXL — বুক 42\"",
		'product_whatsapp'    => '',
		'product_messenger'   => '',

		'footer_tagline'      => 'আধুনিক নারীর জন্য বাংলাদেশের ঐতিহ্য ও রুচির পোশাক।',
		'footer_payments'     => '',
		'footer_follow_title' => 'আমাদের সঙ্গে থাকুন',
		'footer_follow_text'  => 'নতুন সংগ্রহ ও বিশেষ আয়োজনের খবর পেতে আমাদের অনুসরণ করুন।',
		'footer_copyright'    => '© 2026 Orpitey Fashion. সর্বস্বত্ব সংরক্ষিত।',
		'social_facebook'     => '',
		'social_instagram'    => '',
		'social_youtube'      => '',

		'contact_phone'       => '+880 1700-000000',
		'contact_email'       => 'hello@orpitey.com',
		'contact_address'     => 'ধানমন্ডি, ঢাকা, বাংলাদেশ',
		'contact_hours'       => 'প্রতিদিন সকাল 10টা – রাত 9টা',
		'contact_whatsapp'    => '',
	);
}

/**
 * Get a theme mod with the theme default.
 *
 * @param string $key Setting key without prefix.
 * @return mixed
 */
function orpitey_mod( $key ) {
	$defaults = orpitey_defaults();
	$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	$value    = get_theme_mod( 'orpitey_' . $key, $default );
	return ( '' === $value || null === $value ) ? $default : $value;
}

/**
 * Whether the mobile bottom navigation is shown.
 *
 * @return bool
 */
function orpitey_bottom_nav_enabled() {
	return (bool) get_theme_mod( 'orpitey_bottom_nav', true );
}

/**
 * Whether the homepage hero slider shows the dot indicators.
 *
 * @return bool
 */
function orpitey_hero_dots_enabled() {
	return (bool) get_theme_mod( 'orpitey_hero_dots', true );
}

/**
 * Whether the homepage hero slider shows the previous and next arrows.
 *
 * @return bool
 */
function orpitey_hero_arrows_enabled() {
	return (bool) get_theme_mod( 'orpitey_hero_arrows', true );
}

/**
 * Multi-line setting rendered with <br>.
 *
 * @param string $key Setting key.
 * @return string Escaped HTML.
 */
function orpitey_mod_lines( $key ) {
	return nl2br( esc_html( orpitey_mod( $key ) ), false );
}

/**
 * Homepage hero slides. Slide 1 keeps the original theme mod keys.
 *
 * @return array
 */
function orpitey_hero_slides() {
	$slides = array();

	for ( $i = 1; $i <= 3; $i++ ) {
		$prefix = 1 === $i ? 'hero_' : 'hero_' . $i . '_';
		$image  = orpitey_mod( $prefix . 'image' );
		if ( ! $image ) {
			continue;
		}
		$slides[] = array(
			'image'   => $image,
			'eyebrow' => orpitey_mod( $prefix . 'eyebrow' ),
			'title'   => orpitey_mod( $prefix . 'title' ),
			'text'    => orpitey_mod( $prefix . 'text' ),
			'button'  => orpitey_mod( $prefix . 'button' ),
			'link'    => orpitey_mod( $prefix . 'link' ),
		);
	}

	return $slides;
}

/**
 * Hero slider fields for Theme Options. Slide 1 uses the original keys.
 *
 * @return array
 */
function orpitey_hero_option_fields() {
	$fields = array();
	$slides = array(
		1 => __( 'Slide 1', 'orpitey' ),
		2 => __( 'Slide 2', 'orpitey' ),
		3 => __( 'Slide 3', 'orpitey' ),
	);
	$parts  = array(
		'image'   => array( 'image', __( 'Image', 'orpitey' ) ),
		'eyebrow' => array( 'text', __( 'Eyebrow', 'orpitey' ) ),
		'title'   => array( 'textarea', __( 'Title (line breaks)', 'orpitey' ) ),
		'text'    => array( 'textarea', __( 'Description', 'orpitey' ) ),
		'button'  => array( 'text', __( 'Button text', 'orpitey' ) ),
		'link'    => array( 'text', __( 'Button link', 'orpitey' ) ),
	);

	foreach ( $slides as $n => $name ) {
		$prefix = 1 === $n ? 'hero_' : 'hero_' . $n . '_';
		foreach ( $parts as $part => $meta ) {
			$fields[] = array(
				'key'   => $prefix . $part,
				'type'  => $meta[0],
				'label' => $name . ': ' . $meta[1],
			);
		}
	}

	return $fields;
}

/**
 * Theme Options sections and fields.
 *
 * @return array
 */
function orpitey_option_sections() {
	return array(
		'general'  => array(
			'label'  => __( 'General', 'orpitey' ),
			'fields' => array(
				array(
					'key'   => 'announcement',
					'type'  => 'text',
					'label' => __( 'Announcement bar', 'orpitey' ),
				),
				array(
					'key'     => 'bn_digits',
					'type'    => 'checkbox',
					'label'   => __( 'Show prices and numbers in Bangla digits', 'orpitey' ),
					'default' => true,
				),
				array(
					'key'     => 'bottom_nav',
					'type'    => 'checkbox',
					'label'   => __( 'Show mobile bottom navigation', 'orpitey' ),
					'default' => true,
				),
				array(
					'key'   => 'color_primary',
					'type'  => 'color',
					'label' => __( 'Primary color', 'orpitey' ),
				),
				array(
					'key'   => 'color_accent',
					'type'  => 'color',
					'label' => __( 'Accent color', 'orpitey' ),
				),
				array(
					'key'   => 'color_background',
					'type'  => 'color',
					'label' => __( 'Background', 'orpitey' ),
				),
				array(
					'key'   => 'color_text',
					'type'  => 'color',
					'label' => __( 'Text color', 'orpitey' ),
				),
				array(
					'key'     => 'font_body',
					'type'    => 'select',
					'label'   => __( 'Body font', 'orpitey' ),
					'choices' => orpitey_font_labels(),
				),
				array(
					'key'     => 'font_display',
					'type'    => 'select',
					'label'   => __( 'Heading font', 'orpitey' ),
					'choices' => orpitey_font_labels(),
				),
			),
		),
		'layout'   => array(
			'label'  => __( 'Home: Arrange', 'orpitey' ),
			'fields' => array(),
		),
		'hero'     => array(
			'label'  => __( 'Home: Hero', 'orpitey' ),
			'fields' => array_merge(
				array(
					array(
						'key'     => 'hero_dots',
						'type'    => 'checkbox',
						'label'   => __( 'Show slider dots', 'orpitey' ),
						'default' => true,
					),
					array(
						'key'     => 'hero_arrows',
						'type'    => 'checkbox',
						'label'   => __( 'Show slider arrows', 'orpitey' ),
						'default' => true,
					),
				),
				orpitey_hero_option_fields()
			),
		),
		'trust'    => array(
			'label'  => __( 'Home: Benefits', 'orpitey' ),
			'fields' => array(),
		),
		'coll'     => array(
			'label'  => __( 'Home: Featured collection', 'orpitey' ),
			'fields' => array(
				array(
					'key'   => 'collection_kicker',
					'type'  => 'text',
					'label' => __( 'Eyebrow', 'orpitey' ),
				),
				array(
					'key'   => 'collection_title',
					'type'  => 'text',
					'label' => __( 'Title', 'orpitey' ),
				),
				array(
					'key'   => 'collection_count',
					'type'  => 'number',
					'label' => __( 'Products per tab', 'orpitey' ),
				),
			),
		),
		'campaign' => array(
			'label'  => __( 'Home: Campaign', 'orpitey' ),
			'fields' => array(),
		),
		'search'   => array(
			'label'  => __( 'Search', 'orpitey' ),
			'fields' => array(),
		),
		'journal'  => array(
			'label'  => __( 'Home: Journal', 'orpitey' ),
			'fields' => array(
				array(
					'key'   => 'journal_kicker',
					'type'  => 'text',
					'label' => __( 'Eyebrow', 'orpitey' ),
				),
				array(
					'key'   => 'journal_title',
					'type'  => 'text',
					'label' => __( 'Title', 'orpitey' ),
				),
				array(
					'key'   => 'journal_text',
					'type'  => 'textarea',
					'label' => __( 'Description', 'orpitey' ),
				),
				array(
					'key'   => 'journal_posts',
					'type'  => 'number',
					'label' => __( 'Posts to show (0 = heading only)', 'orpitey' ),
				),
			),
		),
		'product'  => array(
			'label'  => __( 'Product page', 'orpitey' ),
			'fields' => array(
				array(
					'key'   => 'product_trust_1',
					'type'  => 'text',
					'label' => __( 'Benefit 1 (title|text)', 'orpitey' ),
				),
				array(
					'key'   => 'product_trust_2',
					'type'  => 'text',
					'label' => __( 'Benefit 2 (title|text)', 'orpitey' ),
				),
				array(
					'key'   => 'product_trust_3',
					'type'  => 'text',
					'label' => __( 'Benefit 3 (title|text)', 'orpitey' ),
				),
				array(
					'key'   => 'product_delivery',
					'type'  => 'textarea',
					'label' => __( 'Delivery and exchange (one per line)', 'orpitey' ),
				),
				array(
					'key'   => 'product_care',
					'type'  => 'textarea',
					'label' => __( 'Care instructions (default)', 'orpitey' ),
				),
				array(
					'key'   => 'size_guide',
					'type'  => 'textarea',
					'label' => __( 'Size guide', 'orpitey' ),
				),
				array(
					'key'   => 'product_whatsapp',
					'type'  => 'url',
					'label' => __( 'WhatsApp link', 'orpitey' ),
				),
				array(
					'key'   => 'product_messenger',
					'type'  => 'url',
					'label' => __( 'Messenger link', 'orpitey' ),
				),
			),
		),
		'footer'   => array(
			'label'  => __( 'Footer and social', 'orpitey' ),
			'fields' => array(
				array(
					'key'   => 'footer_tagline',
					'type'  => 'textarea',
					'label' => __( 'Tagline', 'orpitey' ),
				),
				array(
					'key'   => 'footer_payments',
					'type'  => 'image',
					'label' => __( 'Payment methods image (below tagline)', 'orpitey' ),
				),
				array(
					'key'   => 'footer_follow_title',
					'type'  => 'text',
					'label' => __( 'Follow heading', 'orpitey' ),
				),
				array(
					'key'   => 'footer_follow_text',
					'type'  => 'textarea',
					'label' => __( 'Follow text', 'orpitey' ),
				),
				array(
					'key'   => 'footer_copyright',
					'type'  => 'text',
					'label' => __( 'Copyright', 'orpitey' ),
				),
				array(
					'key'   => 'social_facebook',
					'type'  => 'url',
					'label' => 'Facebook URL',
				),
				array(
					'key'   => 'social_instagram',
					'type'  => 'url',
					'label' => 'Instagram URL',
				),
				array(
					'key'   => 'social_youtube',
					'type'  => 'url',
					'label' => 'YouTube URL',
				),
			),
		),
		'contact'  => array(
			'label'  => __( 'Contact details', 'orpitey' ),
			'fields' => array(
				array(
					'key'   => 'contact_phone',
					'type'  => 'text',
					'label' => __( 'Phone', 'orpitey' ),
				),
				array(
					'key'   => 'contact_email',
					'type'  => 'text',
					'label' => __( 'Email', 'orpitey' ),
				),
				array(
					'key'   => 'contact_address',
					'type'  => 'textarea',
					'label' => __( 'Address', 'orpitey' ),
				),
				array(
					'key'   => 'contact_hours',
					'type'  => 'text',
					'label' => __( 'Opening hours', 'orpitey' ),
				),
				array(
					'key'   => 'contact_whatsapp',
					'type'  => 'text',
					'label' => __( 'WhatsApp number (e.g. 8801700000000)', 'orpitey' ),
				),
			),
		),
	);
}

/**
 * Sanitize one Theme Options value.
 *
 * @param mixed  $value Raw value.
 * @param string $type  Field type.
 * @return mixed
 */
function orpitey_sanitize_option_value( $value, $type ) {
	if ( 'textarea' === $type ) {
		return sanitize_textarea_field( $value );
	}
	if ( 'url' === $type || 'image' === $type ) {
		return esc_url_raw( $value );
	}
	if ( 'number' === $type ) {
		return absint( $value );
	}
	if ( 'color' === $type ) {
		$hex = sanitize_hex_color( $value );
		return $hex ? $hex : '';
	}
	if ( 'select' === $type ) {
		return sanitize_key( $value );
	}
	return sanitize_text_field( $value );
}

/**
 * Bangla-capable font choices.
 *
 * @return array
 */
function orpitey_font_choices() {
	return array(
		'shadhinata'        => array(
			'label'  => 'Shadhinata',
			'stack'  => '"Li Shadhinata Unicode", "Noto Sans Bengali", ui-sans-serif, system-ui, sans-serif',
			'google' => '',
		),
		'hind-siliguri'     => array(
			'label'  => 'Hind Siliguri',
			'stack'  => '"Hind Siliguri", "Noto Sans Bengali", ui-sans-serif, system-ui, sans-serif',
			'google' => 'Hind+Siliguri:wght@300;400;500;600;700',
		),
		'noto-sans-bengali' => array(
			'label'  => 'Noto Sans Bengali',
			'stack'  => '"Noto Sans Bengali", "Hind Siliguri", ui-sans-serif, system-ui, sans-serif',
			'google' => 'Noto+Sans+Bengali:wght@400;500;600;700',
		),
		'noto-serif-bengali' => array(
			'label'  => 'Noto Serif Bengali',
			'stack'  => '"Noto Serif Bengali", "Noto Sans Bengali", ui-serif, Georgia, serif',
			'google' => 'Noto+Serif+Bengali:wght@400;500;600;700',
		),
		'anek-bangla'       => array(
			'label'  => 'Anek Bangla',
			'stack'  => '"Anek Bangla", "Noto Sans Bengali", ui-sans-serif, system-ui, sans-serif',
			'google' => 'Anek+Bangla:wght@400;500;600;700',
		),
		'tiro-bangla'       => array(
			'label'  => 'Tiro Bangla',
			'stack'  => '"Tiro Bangla", "Noto Serif Bengali", ui-serif, Georgia, serif',
			'google' => 'Tiro+Bangla:wght@400;700',
		),
		'atma'              => array(
			'label'  => 'Atma',
			'stack'  => '"Atma", "Noto Sans Bengali", ui-sans-serif, system-ui, sans-serif',
			'google' => 'Atma:wght@400;500;600;700',
		),
		'baloo-da-2'        => array(
			'label'  => 'Baloo Da 2',
			'stack'  => '"Baloo Da 2", "Noto Sans Bengali", ui-sans-serif, system-ui, sans-serif',
			'google' => 'Baloo+Da+2:wght@400;500;600;700',
		),
	);
}

/**
 * Font labels for the Theme Options select.
 *
 * @return array
 */
function orpitey_font_labels() {
	$labels = array();
	foreach ( orpitey_font_choices() as $key => $font ) {
		$labels[ $key ] = $font['label'];
	}
	return $labels;
}

/**
 * One font choice, or null when the key is unknown.
 *
 * @param string $key Font key.
 * @return array|null
 */
function orpitey_font_by_key( $key ) {
	$choices = orpitey_font_choices();
	return isset( $choices[ $key ] ) ? $choices[ $key ] : null;
}

/**
 * Google Fonts stylesheet for the selected families.
 *
 * @return string
 */
function orpitey_google_fonts_url() {
	$families = array();
	foreach ( array( 'font_body', 'font_display' ) as $key ) {
		$font = orpitey_font_by_key( orpitey_mod( $key ) );
		if ( $font && ! empty( $font['google'] ) ) {
			$families[ $font['google'] ] = true;
		}
	}
	if ( ! $families ) {
		return '';
	}
	$query = array();
	foreach ( array_keys( $families ) as $family ) {
		$query[] = 'family=' . $family;
	}
	return 'https://fonts.googleapis.com/css2?' . implode( '&', $query ) . '&display=swap';
}

/**
 * Hex color when it differs from the theme default.
 *
 * @param string $key Theme mod key.
 * @return string
 */
function orpitey_custom_hex( $key ) {
	$defaults = orpitey_defaults();
	$value    = strtolower( (string) orpitey_mod( $key ) );
	$default  = isset( $defaults[ $key ] ) ? strtolower( (string) $defaults[ $key ] ) : '';
	if ( $value === $default ) {
		return '';
	}
	$hex = sanitize_hex_color( $value );
	return $hex ? $hex : '';
}

/**
 * Light or dark text for a hex background.
 *
 * @param string $hex Hex color.
 * @return string
 */
function orpitey_contrast_color( $hex ) {
	$hex = ltrim( $hex, '#' );
	$r   = hexdec( substr( $hex, 0, 2 ) );
	$g   = hexdec( substr( $hex, 2, 2 ) );
	$b   = hexdec( substr( $hex, 4, 2 ) );
	$lum = ( 0.2126 * $r + 0.7152 * $g + 0.0722 * $b ) / 255;
	return $lum > 0.62 ? '#2b1b14' : '#fcf6eb';
}

/**
 * Inline CSS for customized colors and fonts.
 *
 * @return string
 */
function orpitey_design_overrides() {
	$rules      = array();
	$primary    = orpitey_custom_hex( 'color_primary' );
	$accent     = orpitey_custom_hex( 'color_accent' );
	$background = orpitey_custom_hex( 'color_background' );
	$text       = orpitey_custom_hex( 'color_text' );
	$defaults   = orpitey_defaults();

	if ( $primary ) {
		$rules[] = '--primary:' . $primary;
		$rules[] = '--primary-foreground:' . orpitey_contrast_color( $primary );
		$rules[] = '--ring:' . $primary;
		$rules[] = '--footer:color-mix(in oklab, ' . $primary . ' 45%, black)';
	}
	if ( $accent ) {
		$rules[] = '--gold:' . $accent;
		$rules[] = '--gold-light:color-mix(in oklab, ' . $accent . ' 62%, white)';
		$rules[] = '--accent:' . $accent;
		$rules[] = '--accent-foreground:' . orpitey_contrast_color( $accent );
		$rules[] = '--secondary:color-mix(in oklab, ' . $accent . ' 28%, var(--background))';
	}
	if ( $background ) {
		$rules[] = '--background:' . $background;
		$rules[] = '--card:color-mix(in oklab, ' . $background . ' 72%, white)';
		$rules[] = '--soft:color-mix(in oklab, ' . $background . ' 82%, var(--gold))';
		$rules[] = '--muted:color-mix(in oklab, var(--foreground) 8%, ' . $background . ')';
		$rules[] = '--border:color-mix(in oklab, var(--foreground) 16%, ' . $background . ')';
		$rules[] = '--input:var(--border)';
	}
	if ( $text ) {
		$rules[] = '--foreground:' . $text;
		$rules[] = '--card-foreground:' . $text;
		$rules[] = '--muted-foreground:color-mix(in oklab, ' . $text . ' 58%, var(--background))';
	}

	$body = orpitey_font_by_key( orpitey_mod( 'font_body' ) );
	if ( $body && orpitey_mod( 'font_body' ) !== $defaults['font_body'] ) {
		$rules[] = '--font-sans:' . $body['stack'];
	}
	$display = orpitey_font_by_key( orpitey_mod( 'font_display' ) );
	if ( $display && orpitey_mod( 'font_display' ) !== $defaults['font_display'] ) {
		$rules[] = '--font-display:' . $display['stack'];
	}

	if ( ! $rules ) {
		return '';
	}
	return ':root{' . implode( ';', $rules ) . ';}';
}

add_action(
	'wp_enqueue_scripts',
	function () {
		$css = orpitey_design_overrides();
		if ( $css ) {
			wp_add_inline_style( 'orpitey-tokens', $css );
		}
	},
	20
);

/**
 * Home trust rows. A saved list wins; otherwise the original three defaults.
 *
 * @return array[]
 */
function orpitey_home_trust_items() {
	$stored = get_theme_mod( 'orpitey_trust_home', null );
	if ( is_array( $stored ) ) {
		$items = array();
		foreach ( $stored as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$items[] = array(
				'title' => isset( $row['title'] ) ? (string) $row['title'] : '',
				'text'  => isset( $row['text'] ) ? (string) $row['text'] : '',
				'icon'  => orpitey_trust_icon( isset( $row['icon'] ) ? $row['icon'] : '', count( $items ) ),
			);
		}
		return $items;
	}

	$items = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$items[] = array(
			'title' => orpitey_mod( 'trust_' . $i . '_title' ),
			'text'  => orpitey_mod( 'trust_' . $i . '_text' ),
			'icon'  => orpitey_trust_icon( '', $i - 1 ),
		);
	}
	return $items;
}

/**
 * Save the home trust repeater.
 */
function orpitey_save_home_trust_items() {
	$rows  = isset( $_POST['trust_items'] ) && is_array( $_POST['trust_items'] ) ? wp_unslash( $_POST['trust_items'] ) : array();
	$clean = array();

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$title = isset( $row['title'] ) ? sanitize_text_field( $row['title'] ) : '';
		$text  = isset( $row['text'] ) ? sanitize_text_field( $row['text'] ) : '';
		if ( '' === $title && '' === $text ) {
			continue;
		}
		$clean[] = array(
			'title' => $title,
			'text'  => $text,
			'icon'  => orpitey_trust_icon( isset( $row['icon'] ) ? sanitize_key( $row['icon'] ) : '', count( $clean ) ),
		);
		if ( count( $clean ) >= 12 ) {
			break;
		}
	}

	set_theme_mod( 'orpitey_trust_home', $clean );
}

/**
 * One trust row in Theme Options.
 *
 * @param array      $item  Title and text.
 * @param int|string $index Row index, or a placeholder for the JS template.
 */
function orpitey_render_trust_row( $item, $index ) {
	$number = is_numeric( $index ) ? (string) ( (int) $index + 1 ) : '';
	?>
	<div class="orpitey-repeater__row">
		<div class="orpitey-repeater__head">
			<strong class="orpitey-repeater__label"><?php echo esc_html( sprintf( __( 'Benefit %s', 'orpitey' ), $number ) ); ?></strong>
			<button type="button" class="button-link-delete orpitey-repeater__remove"><?php esc_html_e( 'Remove', 'orpitey' ); ?></button>
		</div>
		<p>
			<span class="orpitey-icon-picker__caption"><?php esc_html_e( 'Icon', 'orpitey' ); ?></span>
			<span class="orpitey-icon-picker" role="radiogroup" aria-label="<?php esc_attr_e( 'Icon', 'orpitey' ); ?>">
				<?php foreach ( orpitey_trust_icon_choices() as $name => $label ) : ?>
					<label title="<?php echo esc_attr( $label ); ?>">
						<input type="radio" name="trust_items[<?php echo esc_attr( $index ); ?>][icon]" value="<?php echo esc_attr( $name ); ?>" <?php checked( $item['icon'], $name ); ?>>
						<?php orpitey_the_icon( $name, 18 ); ?>
					</label>
				<?php endforeach; ?>
			</span>
		</p>
		<p>
			<label><?php esc_html_e( 'Title', 'orpitey' ); ?></label><br>
			<input class="regular-text" type="text" name="trust_items[<?php echo esc_attr( $index ); ?>][title]" value="<?php echo esc_attr( $item['title'] ); ?>">
		</p>
		<p>
			<label><?php esc_html_e( 'Text', 'orpitey' ); ?></label><br>
			<input class="regular-text" type="text" name="trust_items[<?php echo esc_attr( $index ); ?>][text]" value="<?php echo esc_attr( $item['text'] ); ?>">
		</p>
	</div>
	<?php
}

/**
 * Home benefits repeater.
 */
function orpitey_render_trust_repeater() {
	$items = orpitey_home_trust_items();
	?>
	<div class="orpitey-repeater" data-max="12" data-label="<?php echo esc_attr__( 'Benefit', 'orpitey' ); ?>" data-template="orpitey-trust-row-template">
		<div class="orpitey-repeater__list">
			<?php foreach ( $items as $index => $item ) : ?>
				<?php orpitey_render_trust_row( $item, $index ); ?>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button orpitey-repeater__add"><?php esc_html_e( 'Add new', 'orpitey' ); ?></button>
		<script type="text/html" id="orpitey-trust-row-template">
			<?php orpitey_render_trust_row( array( 'title' => '', 'text' => '', 'icon' => 'truck' ), '__i__' ); ?>
		</script>
	</div>
	<?php
}

/**
 * Popular search chips. Saved rows win; otherwise the busiest categories.
 *
 * @return array[] label, url.
 */
function orpitey_search_suggestions() {
	$stored = get_theme_mod( 'orpitey_search_suggestions', null );
	$items  = array();

	if ( is_array( $stored ) ) {
		foreach ( $stored as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$label = isset( $row['label'] ) ? trim( (string) $row['label'] ) : '';
			if ( '' === $label ) {
				continue;
			}
			$url = isset( $row['url'] ) ? $row['url'] : '';
			if ( ! $url ) {
				$args = array( 's' => $label );
				if ( class_exists( 'WooCommerce' ) ) {
					$args['post_type'] = 'product';
				}
				$url = add_query_arg( $args, home_url( '/' ) );
			}
			$items[] = array(
				'label' => $label,
				'url'   => $url,
			);
		}
	}

	if ( $items ) {
		return $items;
	}

	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return array();
	}

	$cats = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'orderby'    => 'count',
			'order'      => 'DESC',
			'number'     => 8,
			'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
		)
	);
	if ( ! $cats || is_wp_error( $cats ) ) {
		return array();
	}

	foreach ( $cats as $cat ) {
		$link = get_term_link( $cat );
		if ( is_wp_error( $link ) ) {
			continue;
		}
		$items[] = array(
			'label' => orpitey_category_label( $cat ),
			'url'   => $link,
		);
	}

	return $items;
}

/**
 * Save popular search chips.
 */
function orpitey_save_search_suggestions() {
	$rows  = isset( $_POST['search_suggestions'] ) && is_array( $_POST['search_suggestions'] ) ? wp_unslash( $_POST['search_suggestions'] ) : array();
	$clean = array();

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$label = isset( $row['label'] ) ? sanitize_text_field( $row['label'] ) : '';
		if ( '' === $label ) {
			continue;
		}
		$clean[] = array(
			'label' => $label,
			'url'   => isset( $row['url'] ) ? esc_url_raw( $row['url'] ) : '',
		);
		if ( count( $clean ) >= 12 ) {
			break;
		}
	}

	set_theme_mod( 'orpitey_search_suggestions', $clean );
}

/**
 * One popular-search row in Theme Options.
 *
 * @param array      $item  Label and URL.
 * @param int|string $index Row index, or a placeholder for the JS template.
 */
function orpitey_render_search_suggestion_row( $item, $index ) {
	$number = is_numeric( $index ) ? (string) ( (int) $index + 1 ) : '';
	?>
	<div class="orpitey-repeater__row">
		<div class="orpitey-repeater__head">
			<strong class="orpitey-repeater__label"><?php echo esc_html( sprintf( __( 'Search %s', 'orpitey' ), $number ) ); ?></strong>
			<button type="button" class="button-link-delete orpitey-repeater__remove"><?php esc_html_e( 'Remove', 'orpitey' ); ?></button>
		</div>
		<p>
			<label><?php esc_html_e( 'Label', 'orpitey' ); ?></label><br>
			<input class="regular-text" type="text" name="search_suggestions[<?php echo esc_attr( $index ); ?>][label]" value="<?php echo esc_attr( $item['label'] ); ?>">
		</p>
		<p>
			<label><?php esc_html_e( 'Link (optional)', 'orpitey' ); ?></label><br>
			<input class="regular-text" type="url" name="search_suggestions[<?php echo esc_attr( $index ); ?>][url]" value="<?php echo esc_attr( $item['url'] ); ?>" placeholder="https://">
		</p>
	</div>
	<?php
}

/**
 * Popular searches repeater.
 */
function orpitey_render_search_suggestions_repeater() {
	$stored = get_theme_mod( 'orpitey_search_suggestions', array() );
	$items  = is_array( $stored ) ? $stored : array();
	?>
	<p class="description"><?php esc_html_e( 'Chips shown under the search box as জনপ্রিয় খোঁজ. Leave the link empty to search products for that label. If you add none, the most popular categories are shown.', 'orpitey' ); ?></p>
	<div class="orpitey-repeater" data-max="12" data-label="<?php echo esc_attr__( 'Search', 'orpitey' ); ?>" data-template="orpitey-search-row-template">
		<div class="orpitey-repeater__list">
			<?php foreach ( $items as $index => $item ) : ?>
				<?php
				orpitey_render_search_suggestion_row(
					array(
						'label' => isset( $item['label'] ) ? $item['label'] : '',
						'url'   => isset( $item['url'] ) ? $item['url'] : '',
					),
					$index
				);
				?>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button orpitey-repeater__add"><?php esc_html_e( 'Add new', 'orpitey' ); ?></button>
		<script type="text/html" id="orpitey-search-row-template">
			<?php orpitey_render_search_suggestion_row( array( 'label' => '', 'url' => '' ), '__i__' ); ?>
		</script>
	</div>
	<?php
}

/**
 * Product categories that can be added as collection tabs.
 *
 * @return array slug => label.
 */
function orpitey_collection_cat_choices() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return array();
	}
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
		)
	);
	if ( is_wp_error( $terms ) ) {
		return array();
	}
	$choices = array();
	foreach ( $terms as $term ) {
		if ( 'uncategorized' === $term->slug ) {
			continue;
		}
		$choices[ $term->slug ] = function_exists( 'orpitey_category_label' ) ? orpitey_category_label( $term ) : $term->name;
	}
	return $choices;
}

/**
 * Category slugs for the homepage collection tabs.
 *
 * @return string[]
 */
function orpitey_collection_tabs() {
	$stored = get_theme_mod( 'orpitey_collection_tabs', null );
	if ( is_array( $stored ) ) {
		$slugs = array();
		foreach ( $stored as $row ) {
			$slug = is_array( $row ) && isset( $row['slug'] ) ? sanitize_title( $row['slug'] ) : '';
			if ( '' !== $slug ) {
				$slugs[] = $slug;
			}
		}
		return $slugs;
	}
	return array_values( array_filter( array_map( 'trim', explode( ',', orpitey_mod( 'collection_cats' ) ) ) ) );
}

/**
 * Save homepage collection tabs.
 */
function orpitey_save_collection_tabs() {
	$rows  = isset( $_POST['collection_tabs'] ) && is_array( $_POST['collection_tabs'] ) ? wp_unslash( $_POST['collection_tabs'] ) : array();
	$clean = array();

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$slug = isset( $row['slug'] ) ? sanitize_title( $row['slug'] ) : '';
		if ( '' === $slug ) {
			continue;
		}
		$clean[] = array( 'slug' => $slug );
		if ( count( $clean ) >= 12 ) {
			break;
		}
	}

	set_theme_mod( 'orpitey_collection_tabs', $clean );
}

/**
 * One collection-tab row.
 *
 * @param string     $slug  Category slug.
 * @param int|string $index Row index.
 * @param array      $choices Category choices.
 */
function orpitey_render_collection_row( $slug, $index, $choices ) {
	$number = is_numeric( $index ) ? (string) ( (int) $index + 1 ) : '';
	if ( $slug && ! isset( $choices[ $slug ] ) ) {
		$choices = array( $slug => $slug ) + $choices;
	}
	?>
	<div class="orpitey-repeater__row">
		<div class="orpitey-repeater__head">
			<strong class="orpitey-repeater__label"><?php echo esc_html( sprintf( __( 'Tab %s', 'orpitey' ), $number ) ); ?></strong>
			<button type="button" class="button-link-delete orpitey-repeater__remove"><?php esc_html_e( 'Remove', 'orpitey' ); ?></button>
		</div>
		<p>
			<label><?php esc_html_e( 'Category', 'orpitey' ); ?></label><br>
			<select class="regular-text" name="collection_tabs[<?php echo esc_attr( $index ); ?>][slug]">
				<option value=""><?php esc_html_e( 'Choose a category', 'orpitey' ); ?></option>
				<?php foreach ( $choices as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $slug, $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	</div>
	<?php
}

/**
 * Homepage collection tab repeater.
 */
function orpitey_render_collection_repeater() {
	$choices = orpitey_collection_cat_choices();
	$slugs   = orpitey_collection_tabs();
	?>
	<div class="orpitey-repeater" data-max="12" data-label="<?php echo esc_attr__( 'Tab', 'orpitey' ); ?>" data-template="orpitey-collection-row-template">
		<div class="orpitey-repeater__list">
			<?php foreach ( $slugs as $index => $slug ) : ?>
				<?php orpitey_render_collection_row( $slug, $index, $choices ); ?>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button orpitey-repeater__add"><?php esc_html_e( 'Add new', 'orpitey' ); ?></button>
		<script type="text/html" id="orpitey-collection-row-template">
			<?php orpitey_render_collection_row( '', '__i__', $choices ); ?>
		</script>
	</div>
	<?php
}

/**
 * Extra homepage product sections.
 *
 * @return array[]
 */
function orpitey_collection_sections() {
	$stored = get_theme_mod( 'orpitey_collection_sections', null );
	if ( ! is_array( $stored ) ) {
		return array();
	}
	$sections = array();
	$persist  = array();
	$used     = array();
	$dirty    = false;
	foreach ( $stored as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$kicker = isset( $row['kicker'] ) ? (string) $row['kicker'] : '';
		$title  = isset( $row['title'] ) ? (string) $row['title'] : '';
		$tabs   = orpitey_collection_section_tab_slugs( isset( $row['tabs'] ) ? $row['tabs'] : array() );
		$slug   = isset( $row['slug'] ) ? sanitize_title( $row['slug'] ) : '';
		if ( $slug && ! in_array( $slug, $tabs, true ) ) {
			array_unshift( $tabs, $slug );
		}
		if ( '' === $kicker && '' === $title && ! $tabs ) {
			continue;
		}
		$count = isset( $row['count'] ) ? absint( $row['count'] ) : 8;
		$count = $count > 0 ? $count : 8;
		$raw   = isset( $row['uid'] ) ? strtolower( (string) preg_replace( '/[^a-z0-9]/', '', (string) $row['uid'] ) ) : '';
		$uid   = orpitey_layout_uid( $raw, $used );
		if ( $uid !== $raw ) {
			$dirty = true;
		}
		$sections[] = array(
			'kicker' => $kicker,
			'title'  => $title,
			'count'  => $count,
			'tabs'   => $tabs,
			'uid'    => $uid,
		);
		$persist[]  = array(
			'kicker' => $kicker,
			'title'  => $title,
			'count'  => $count,
			'tabs'   => array_map(
				static function ( $tab_slug ) {
					return array( 'slug' => $tab_slug );
				},
				$tabs
			),
			'uid'    => $uid,
		);
	}
	if ( $dirty ) {
		set_theme_mod( 'orpitey_collection_sections', $persist );
	}
	return $sections;
}

/**
 * Save extra homepage product sections.
 */
function orpitey_save_collection_sections() {
	$rows  = isset( $_POST['collection_sections'] ) && is_array( $_POST['collection_sections'] ) ? wp_unslash( $_POST['collection_sections'] ) : array();
	$clean = array();
	$used  = array();

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$kicker = isset( $row['kicker'] ) ? sanitize_text_field( $row['kicker'] ) : '';
		$title  = isset( $row['title'] ) ? sanitize_text_field( $row['title'] ) : '';
		$tabs   = orpitey_collection_section_tab_slugs( isset( $row['tabs'] ) ? $row['tabs'] : array() );
		if ( '' === $kicker && '' === $title && ! $tabs ) {
			continue;
		}
		$count   = isset( $row['count'] ) ? absint( $row['count'] ) : 8;
		$clean[] = array(
			'kicker' => $kicker,
			'title'  => $title,
			'count'  => $count > 0 ? $count : 8,
			'uid'    => orpitey_layout_uid( isset( $row['uid'] ) ? $row['uid'] : '', $used ),
			'tabs'   => array_map(
				static function ( $tab_slug ) {
					return array( 'slug' => $tab_slug );
				},
				$tabs
			),
		);
		if ( count( $clean ) >= 6 ) {
			break;
		}
	}

	set_theme_mod( 'orpitey_collection_sections', $clean );
}

/**
 * Category slugs for one extra product section's tabs.
 *
 * @param mixed $tabs Raw tab rows.
 * @return string[]
 */
function orpitey_collection_section_tab_slugs( $tabs ) {
	if ( ! is_array( $tabs ) ) {
		return array();
	}
	$slugs = array();
	foreach ( $tabs as $tab ) {
		$slug = '';
		if ( is_array( $tab ) && isset( $tab['slug'] ) ) {
			$slug = sanitize_title( $tab['slug'] );
		} elseif ( is_string( $tab ) ) {
			$slug = sanitize_title( $tab );
		}
		if ( '' === $slug || in_array( $slug, $slugs, true ) ) {
			continue;
		}
		$slugs[] = $slug;
		if ( count( $slugs ) >= 12 ) {
			break;
		}
	}
	return $slugs;
}

/**
 * One category tab inside an extra product section.
 *
 * @param string     $slug           Category slug.
 * @param int|string $section_index  Section index.
 * @param int|string $tab_index      Tab index.
 * @param array      $choices        Category choices.
 */
function orpitey_render_collection_section_tab_row( $slug, $section_index, $tab_index, $choices ) {
	$number = is_numeric( $tab_index ) ? (string) ( (int) $tab_index + 1 ) : '';
	if ( $slug && ! isset( $choices[ $slug ] ) ) {
		$choices = array( $slug => $slug ) + $choices;
	}
	?>
	<div class="orpitey-repeater__row">
		<div class="orpitey-repeater__head">
			<strong class="orpitey-repeater__label"><?php echo esc_html( sprintf( __( 'Tab %s', 'orpitey' ), $number ) ); ?></strong>
			<button type="button" class="button-link-delete orpitey-repeater__remove"><?php esc_html_e( 'Remove', 'orpitey' ); ?></button>
		</div>
		<p>
			<label><?php esc_html_e( 'Category', 'orpitey' ); ?></label><br>
			<select class="regular-text" name="collection_sections[<?php echo esc_attr( $section_index ); ?>][tabs][<?php echo esc_attr( $tab_index ); ?>][slug]">
				<option value=""><?php esc_html_e( 'Choose a category', 'orpitey' ); ?></option>
				<?php foreach ( $choices as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $slug, $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	</div>
	<?php
}

/**
 * One extra product-section row.
 *
 * @param array      $section Section fields.
 * @param int|string $index   Row index.
 * @param array      $choices Category choices.
 */
function orpitey_render_collection_section_row( $section, $index, $choices ) {
	$number = is_numeric( $index ) ? (string) ( (int) $index + 1 ) : '';
	$tabs   = isset( $section['tabs'] ) && is_array( $section['tabs'] ) ? $section['tabs'] : array();
	?>
	<div class="orpitey-repeater__row">
		<div class="orpitey-repeater__head">
			<strong class="orpitey-repeater__label"><?php echo esc_html( sprintf( __( 'Section %s', 'orpitey' ), $number ) ); ?></strong>
			<button type="button" class="button-link-delete orpitey-repeater__remove"><?php esc_html_e( 'Remove', 'orpitey' ); ?></button>
		</div>
		<input type="hidden" name="collection_sections[<?php echo esc_attr( $index ); ?>][uid]" value="<?php echo esc_attr( isset( $section['uid'] ) ? $section['uid'] : '' ); ?>">
		<p>
			<label><?php esc_html_e( 'Eyebrow', 'orpitey' ); ?></label><br>
			<input class="regular-text" type="text" name="collection_sections[<?php echo esc_attr( $index ); ?>][kicker]" value="<?php echo esc_attr( $section['kicker'] ); ?>">
		</p>
		<p>
			<label><?php esc_html_e( 'Title', 'orpitey' ); ?></label><br>
			<input class="regular-text" type="text" name="collection_sections[<?php echo esc_attr( $index ); ?>][title]" value="<?php echo esc_attr( $section['title'] ); ?>">
		</p>
		<p>
			<label><?php esc_html_e( 'Products per tab', 'orpitey' ); ?></label><br>
			<input class="small-text" type="number" min="0" step="1" name="collection_sections[<?php echo esc_attr( $index ); ?>][count]" value="<?php echo esc_attr( $section['count'] ); ?>">
		</p>
		<div class="orpitey-repeater orpitey-repeater--nested" data-max="12" data-label="<?php echo esc_attr__( 'Tab', 'orpitey' ); ?>" data-token="__t__">
			<div class="orpitey-repeater__list">
				<?php foreach ( $tabs as $tab_index => $tab_slug ) : ?>
					<?php orpitey_render_collection_section_tab_row( $tab_slug, $index, $tab_index, $choices ); ?>
				<?php endforeach; ?>
			</div>
			<button type="button" class="button orpitey-repeater__add"><?php esc_html_e( 'Add new', 'orpitey' ); ?></button>
			<template>
				<?php orpitey_render_collection_section_tab_row( '', $index, '__t__', $choices ); ?>
			</template>
		</div>
	</div>
	<?php
}

/**
 * Repeater for extra homepage product sections.
 */
function orpitey_render_collection_sections_repeater() {
	$choices  = orpitey_collection_cat_choices();
	$sections = orpitey_collection_sections();
	$blank    = array(
		'kicker' => '',
		'title'  => '',
		'count'  => 8,
		'tabs'   => array(),
		'uid'    => '',
	);
	?>
	<h2 class="orpitey-repeater__title"><?php esc_html_e( 'Extra product sections', 'orpitey' ); ?></h2>
	<div class="orpitey-repeater" data-max="6" data-label="<?php echo esc_attr__( 'Section', 'orpitey' ); ?>" data-template="orpitey-collection-section-template">
		<div class="orpitey-repeater__list">
			<?php foreach ( $sections as $index => $section ) : ?>
				<?php orpitey_render_collection_section_row( $section, $index, $choices ); ?>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button orpitey-repeater__add"><?php esc_html_e( 'Add product section', 'orpitey' ); ?></button>
		<script type="text/html" id="orpitey-collection-section-template">
			<?php orpitey_render_collection_section_row( $blank, '__i__', $choices ); ?>
		</script>
	</div>
	<?php
}

/**
 * Homepage campaign blocks. A saved list wins; otherwise the original campaign.
 *
 * @return array[]
 */
function orpitey_campaigns() {
	$stored = get_theme_mod( 'orpitey_campaigns', null );
	if ( is_array( $stored ) ) {
		$campaigns = array();
		$persist   = array();
		$used      = array();
		$dirty     = false;
		foreach ( $stored as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$campaign = orpitey_normalize_campaign( $row );
			if ( orpitey_campaign_is_empty( $campaign ) ) {
				continue;
			}
			$raw = strtolower( (string) preg_replace( '/[^a-z0-9]/', '', $campaign['uid'] ) );
			$campaign['uid'] = orpitey_layout_uid( $raw, $used );
			if ( $campaign['uid'] !== $raw ) {
				$dirty = true;
			}
			$campaigns[] = $campaign;
			$persist[]   = $campaign;
		}
		if ( $dirty ) {
			set_theme_mod( 'orpitey_campaigns', $persist );
		}
		return $campaigns;
	}

	return array(
		orpitey_normalize_campaign(
			array(
				'image'  => orpitey_mod( 'campaign_image' ),
				'kicker' => orpitey_mod( 'campaign_kicker' ),
				'title'  => orpitey_mod( 'campaign_title' ),
				'text'   => orpitey_mod( 'campaign_text' ),
				'button' => orpitey_mod( 'campaign_button' ),
				'link'   => orpitey_mod( 'campaign_link' ),
				'uid'    => 'default',
			)
		),
	);
}

/**
 * Normalize one campaign row.
 *
 * @param array $row Raw campaign fields.
 * @return array
 */
function orpitey_normalize_campaign( $row ) {
	return array(
		'image'  => isset( $row['image'] ) ? (string) $row['image'] : '',
		'kicker' => isset( $row['kicker'] ) ? (string) $row['kicker'] : '',
		'title'  => isset( $row['title'] ) ? (string) $row['title'] : '',
		'text'   => isset( $row['text'] ) ? (string) $row['text'] : '',
		'button' => isset( $row['button'] ) ? (string) $row['button'] : '',
		'link'   => isset( $row['link'] ) ? (string) $row['link'] : '',
		'uid'    => isset( $row['uid'] ) ? (string) $row['uid'] : '',
	);
}

/**
 * Whether a campaign row has nothing to show.
 *
 * @param array $campaign Campaign fields.
 * @return bool
 */
function orpitey_campaign_is_empty( $campaign ) {
	return '' === $campaign['image'] && '' === $campaign['kicker'] && '' === $campaign['title'] && '' === $campaign['text'] && '' === $campaign['button'];
}

/**
 * Save homepage campaigns.
 */
function orpitey_save_campaigns() {
	$rows  = isset( $_POST['campaigns'] ) && is_array( $_POST['campaigns'] ) ? wp_unslash( $_POST['campaigns'] ) : array();
	$clean = array();
	$used  = array();

	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) ) {
			continue;
		}
		$campaign = array(
			'image'  => isset( $row['image'] ) ? esc_url_raw( $row['image'] ) : '',
			'kicker' => isset( $row['kicker'] ) ? sanitize_text_field( $row['kicker'] ) : '',
			'title'  => isset( $row['title'] ) ? sanitize_textarea_field( $row['title'] ) : '',
			'text'   => isset( $row['text'] ) ? sanitize_textarea_field( $row['text'] ) : '',
			'button' => isset( $row['button'] ) ? sanitize_text_field( $row['button'] ) : '',
			'link'   => isset( $row['link'] ) ? sanitize_text_field( $row['link'] ) : '',
			'uid'    => orpitey_layout_uid( isset( $row['uid'] ) ? $row['uid'] : '', $used ),
		);
		if ( orpitey_campaign_is_empty( $campaign ) ) {
			continue;
		}
		$clean[] = $campaign;
		if ( count( $clean ) >= 6 ) {
			break;
		}
	}

	set_theme_mod( 'orpitey_campaigns', $clean );
}

/**
 * One campaign row in Theme Options.
 *
 * @param array      $campaign Campaign fields.
 * @param int|string $index    Row index.
 */
function orpitey_render_campaign_row( $campaign, $index ) {
	$number = is_numeric( $index ) ? (string) ( (int) $index + 1 ) : '';
	$image  = isset( $campaign['image'] ) ? $campaign['image'] : '';
	?>
	<div class="orpitey-repeater__row">
		<div class="orpitey-repeater__head">
			<strong class="orpitey-repeater__label"><?php echo esc_html( sprintf( __( 'Campaign %s', 'orpitey' ), $number ) ); ?></strong>
			<button type="button" class="button-link-delete orpitey-repeater__remove"><?php esc_html_e( 'Remove', 'orpitey' ); ?></button>
		</div>
		<input type="hidden" name="campaigns[<?php echo esc_attr( $index ); ?>][uid]" value="<?php echo esc_attr( $campaign['uid'] ); ?>">
		<p>
			<label><?php esc_html_e( 'Image', 'orpitey' ); ?></label><br>
			<span class="orpitey-image-field<?php echo $image ? '' : ' is-empty'; ?>">
				<input type="hidden" name="campaigns[<?php echo esc_attr( $index ); ?>][image]" value="<?php echo esc_attr( $image ); ?>">
				<span class="orpitey-image-preview">
					<img src="<?php echo esc_url( $image ); ?>" alt="">
				</span>
				<button type="button" class="button orpitey-image-select"><?php esc_html_e( 'Choose image', 'orpitey' ); ?></button>
				<button type="button" class="button orpitey-image-remove"><?php esc_html_e( 'Remove', 'orpitey' ); ?></button>
			</span>
		</p>
		<p>
			<label><?php esc_html_e( 'Eyebrow', 'orpitey' ); ?></label><br>
			<input class="regular-text" type="text" name="campaigns[<?php echo esc_attr( $index ); ?>][kicker]" value="<?php echo esc_attr( $campaign['kicker'] ); ?>">
		</p>
		<p>
			<label><?php esc_html_e( 'Title (line breaks)', 'orpitey' ); ?></label><br>
			<textarea class="large-text" rows="3" name="campaigns[<?php echo esc_attr( $index ); ?>][title]"><?php echo esc_textarea( $campaign['title'] ); ?></textarea>
		</p>
		<p>
			<label><?php esc_html_e( 'Description', 'orpitey' ); ?></label><br>
			<textarea class="large-text" rows="3" name="campaigns[<?php echo esc_attr( $index ); ?>][text]"><?php echo esc_textarea( $campaign['text'] ); ?></textarea>
		</p>
		<p>
			<label><?php esc_html_e( 'Button text', 'orpitey' ); ?></label><br>
			<input class="regular-text" type="text" name="campaigns[<?php echo esc_attr( $index ); ?>][button]" value="<?php echo esc_attr( $campaign['button'] ); ?>">
		</p>
		<p>
			<label><?php esc_html_e( 'Button link (shop if empty)', 'orpitey' ); ?></label><br>
			<input class="regular-text" type="text" name="campaigns[<?php echo esc_attr( $index ); ?>][link]" value="<?php echo esc_attr( $campaign['link'] ); ?>">
		</p>
	</div>
	<?php
}

/**
 * Homepage campaign repeater.
 */
function orpitey_render_campaign_repeater() {
	$campaigns = orpitey_campaigns();
	$blank     = orpitey_normalize_campaign( array() );
	?>
	<div class="orpitey-repeater" data-max="6" data-label="<?php echo esc_attr__( 'Campaign', 'orpitey' ); ?>" data-template="orpitey-campaign-row-template">
		<div class="orpitey-repeater__list">
			<?php foreach ( $campaigns as $index => $campaign ) : ?>
				<?php orpitey_render_campaign_row( $campaign, $index ); ?>
			<?php endforeach; ?>
		</div>
		<button type="button" class="button orpitey-repeater__add"><?php esc_html_e( 'Add new', 'orpitey' ); ?></button>
		<script type="text/html" id="orpitey-campaign-row-template">
			<?php orpitey_render_campaign_row( $blank, '__i__' ); ?>
		</script>
	</div>
	<?php
}

/**
 * Stable id for a sortable homepage block.
 *
 * @param string $preferred Preferred id.
 * @param array  $used      Ids already taken, updated by reference.
 * @return string
 */
function orpitey_layout_uid( $preferred, array &$used ) {
	$uid = strtolower( (string) preg_replace( '/[^a-z0-9]/', '', (string) $preferred ) );
	if ( strlen( $uid ) < 4 || isset( $used[ $uid ] ) ) {
		do {
			$uid = strtolower( (string) preg_replace( '/[^a-z0-9]/', '', wp_generate_password( 12, false, false ) ) );
		} while ( strlen( $uid ) < 6 || isset( $used[ $uid ] ) );
	}
	$used[ $uid ] = true;
	return $uid;
}

/**
 * Homepage blocks in their default visual order.
 *
 * @return array id => label.
 */
function orpitey_home_section_catalog() {
	$items = array(
		'hero'       => __( 'Hero', 'orpitey' ),
		'trust'      => __( 'Benefits', 'orpitey' ),
		'collection' => __( 'Featured collection', 'orpitey' ),
	);

	foreach ( orpitey_collection_sections() as $section ) {
		$label = $section['title'] ? $section['title'] : __( 'Product section', 'orpitey' );
		$items[ 'section:' . $section['uid'] ] = $label;
	}

	foreach ( orpitey_campaigns() as $campaign ) {
		$label = $campaign['title'] ? preg_replace( '/\s+/', ' ', $campaign['title'] ) : __( 'Campaign', 'orpitey' );
		$items[ 'campaign:' . $campaign['uid'] ] = $label;
	}

	$items['journal'] = __( 'Journal', 'orpitey' );
	return $items;
}

/**
 * Saved homepage order, with any new blocks inserted in the default position.
 *
 * @param array|null $stored Stored rows, or null to read the theme mod.
 * @return array[]
 */
function orpitey_home_layout( $stored = null ) {
	$catalog = orpitey_home_section_catalog();
	if ( null === $stored ) {
		$stored = get_theme_mod( 'orpitey_home_layout', null );
	}
	$items = array();
	$seen  = array();
	if ( is_array( $stored ) ) {
		foreach ( $stored as $row ) {
			if ( ! is_array( $row ) || empty( $row['id'] ) ) {
				continue;
			}
			$id = (string) $row['id'];
			if ( ! isset( $catalog[ $id ] ) || isset( $seen[ $id ] ) ) {
				continue;
			}
			$items[]       = array(
				'id'     => $id,
				'hidden' => ! empty( $row['hidden'] ),
			);
			$seen[ $id ] = true;
		}
	}

	$keys = array_keys( $catalog );
	foreach ( $keys as $position => $id ) {
		if ( isset( $seen[ $id ] ) ) {
			continue;
		}
		$insert = count( $items );
		for ( $prev = $position - 1; $prev >= 0; $prev-- ) {
			foreach ( $items as $index => $item ) {
				if ( $item['id'] === $keys[ $prev ] ) {
					$insert = $index + 1;
					break 2;
				}
			}
		}
		array_splice(
			$items,
			$insert,
			0,
			array(
				array(
					'id'     => $id,
					'hidden' => false,
				),
			)
		);
		$seen[ $id ] = true;
	}

	return $items;
}

/**
 * Save homepage section order and visibility.
 */
function orpitey_save_home_layout() {
	if ( ! isset( $_POST['home_layout'] ) || ! is_array( $_POST['home_layout'] ) ) {
		return;
	}
	$rows  = wp_unslash( $_POST['home_layout'] );
	$clean = array();
	foreach ( $rows as $row ) {
		if ( ! is_array( $row ) || empty( $row['id'] ) ) {
			continue;
		}
		$clean[] = array(
			'id'     => sanitize_text_field( $row['id'] ),
			'hidden' => isset( $row['hidden'] ) && '1' === (string) $row['hidden'],
		);
	}
	set_theme_mod( 'orpitey_home_layout', orpitey_home_layout( $clean ) );
}

/**
 * Theme Options list for sorting and hiding homepage sections.
 */
function orpitey_render_home_layout() {
	$catalog = orpitey_home_section_catalog();
	?>
	<p class="description"><?php esc_html_e( 'Use the arrows or drag to reorder. Use the eye icon to hide or show a section.', 'orpitey' ); ?></p>
	<div class="orpitey-layout">
		<?php
		$layout_items = orpitey_home_layout();
		$layout_last  = count( $layout_items ) - 1;
		foreach ( $layout_items as $index => $item ) :
			$hidden = ! empty( $item['hidden'] );
			$label  = isset( $catalog[ $item['id'] ] ) ? $catalog[ $item['id'] ] : $item['id'];
			?>
			<div class="orpitey-layout__row<?php echo $hidden ? ' is-hidden' : ''; ?>">
				<span class="orpitey-layout__handle" title="<?php esc_attr_e( 'Drag to reorder', 'orpitey' ); ?>">
					<span class="dashicons dashicons-menu" aria-hidden="true"></span>
				</span>
				<span class="orpitey-layout__label"><?php echo esc_html( $label ); ?></span>
				<span class="orpitey-layout__moves">
					<button type="button" class="orpitey-layout__move" data-dir="-1" aria-label="<?php esc_attr_e( 'Move up', 'orpitey' ); ?>" <?php disabled( 0 === $index ); ?>>
						<span class="dashicons dashicons-arrow-up-alt2" aria-hidden="true"></span>
					</button>
					<button type="button" class="orpitey-layout__move" data-dir="1" aria-label="<?php esc_attr_e( 'Move down', 'orpitey' ); ?>" <?php disabled( $index === $layout_last ); ?>>
						<span class="dashicons dashicons-arrow-down-alt2" aria-hidden="true"></span>
					</button>
				</span>
				<button type="button" class="button-link orpitey-layout__toggle" aria-pressed="<?php echo $hidden ? 'true' : 'false'; ?>" aria-label="<?php echo esc_attr( $hidden ? __( 'Show', 'orpitey' ) : __( 'Hide', 'orpitey' ) ); ?>">
					<span class="dashicons <?php echo $hidden ? 'dashicons-hidden' : 'dashicons-visibility'; ?>" aria-hidden="true"></span>
				</button>
				<input type="hidden" name="home_layout[<?php echo esc_attr( (string) $index ); ?>][id]" value="<?php echo esc_attr( $item['id'] ); ?>">
				<input type="hidden" name="home_layout[<?php echo esc_attr( (string) $index ); ?>][hidden]" value="<?php echo $hidden ? '1' : '0'; ?>">
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Output one homepage section.
 *
 * @param string $id Section id from the layout list.
 */
function orpitey_render_home_section( $id ) {
	static $campaign_anchor = false;

	if ( 'hero' === $id ) {
		get_template_part( 'template-parts/home/hero' );
		return;
	}
	if ( 'trust' === $id ) {
		echo '<section class="trust-strip">';
		get_template_part( 'template-parts/trust-badges', null, array( 'context' => 'home' ) );
		echo '</section>';
		return;
	}
	if ( 'collection' === $id && class_exists( 'WooCommerce' ) ) {
		get_template_part( 'template-parts/home/collection', null, array( 'part' => 'main' ) );
		return;
	}
	if ( 'journal' === $id ) {
		get_template_part( 'template-parts/home/journal' );
		return;
	}
	if ( 0 === strpos( $id, 'section:' ) && class_exists( 'WooCommerce' ) ) {
		get_template_part(
			'template-parts/home/collection',
			null,
			array(
				'part' => 'extra',
				'uid'  => substr( $id, 8 ),
			)
		);
		return;
	}
	if ( 0 === strpos( $id, 'campaign:' ) ) {
		get_template_part(
			'template-parts/home/campaign',
			null,
			array(
				'uid'    => substr( $id, 9 ),
				'anchor' => ! $campaign_anchor,
			)
		);
		$campaign_anchor = true;
	}
}

/**
 * Current value shown in the Theme Options form.
 *
 * @param string $key  Field key.
 * @param string $type Field type.
 * @return mixed
 */
function orpitey_option_field_value( $key, $type, $default = false ) {
	if ( 'checkbox' === $type ) {
		return (bool) get_theme_mod( 'orpitey_' . $key, $default );
	}
	return orpitey_mod( $key );
}

/**
 * Active tab slug from the query string.
 *
 * @return string
 */
function orpitey_theme_options_tab() {
	$sections = orpitey_option_sections();
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general';
	return isset( $sections[ $tab ] ) ? $tab : 'general';
}

add_action(
	'admin_menu',
	function () {
		add_theme_page(
			__( 'Theme Options', 'orpitey' ),
			__( 'Theme Options', 'orpitey' ),
			'edit_theme_options',
			'orpitey-theme-options',
			'orpitey_render_theme_options_page'
		);
	}
);

add_action(
	'admin_enqueue_scripts',
	function ( $hook ) {
		if ( 'appearance_page_orpitey-theme-options' !== $hook ) {
			return;
		}

		wp_enqueue_media();
		wp_enqueue_style(
			'orpitey-theme-options',
			ORPITEY_URI . '/assets/css/admin-theme-options.css',
			array(),
			ORPITEY_VERSION
		);
		wp_enqueue_script(
			'orpitey-theme-options',
			ORPITEY_URI . '/assets/js/admin-theme-options.js',
			array( 'jquery', 'jquery-ui-sortable', 'media-editor' ),
			ORPITEY_VERSION,
			true
		);
	}
);

add_action(
	'admin_post_orpitey_save_theme_options',
	function () {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( esc_html__( 'You are not allowed to do this.', 'orpitey' ) );
		}

		check_admin_referer( 'orpitey_theme_options', 'orpitey_theme_options_nonce' );

		$posted   = isset( $_POST['orpitey'] ) && is_array( $_POST['orpitey'] ) ? wp_unslash( $_POST['orpitey'] ) : array();
		$sections = orpitey_option_sections();

		orpitey_save_home_trust_items();
		orpitey_save_search_suggestions();
		orpitey_save_collection_tabs();
		orpitey_save_collection_sections();
		orpitey_save_campaigns();
		orpitey_save_home_layout();

		foreach ( $sections as $section ) {
			foreach ( $section['fields'] as $field ) {
				$key = $field['key'];
				$raw = isset( $posted[ $key ] ) ? $posted[ $key ] : '';
				if ( 'checkbox' === $field['type'] ) {
					set_theme_mod( 'orpitey_' . $key, ! empty( $raw ) );
					continue;
				}
				set_theme_mod( 'orpitey_' . $key, orpitey_sanitize_option_value( $raw, $field['type'] ) );
			}
		}

		$tab = isset( $_POST['tab'] ) ? sanitize_key( wp_unslash( $_POST['tab'] ) ) : 'general';
		if ( ! isset( $sections[ $tab ] ) ) {
			$tab = 'general';
		}

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'    => 'orpitey-theme-options',
					'tab'     => $tab,
					'updated' => '1',
				),
				admin_url( 'themes.php' )
			)
		);
		exit;
	}
);

/**
 * Render the Theme Options screen.
 */
function orpitey_render_theme_options_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	$sections = orpitey_option_sections();
	$tab      = orpitey_theme_options_tab();
	?>
	<div class="wrap orpitey-theme-options">
		<h1><?php esc_html_e( 'Theme Options', 'orpitey' ); ?></h1>
		<?php if ( isset( $_GET['updated'] ) && '1' === $_GET['updated'] ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings saved.', 'orpitey' ); ?></p></div>
		<?php endif; ?>

		<nav class="orpitey-tabs" aria-label="<?php esc_attr_e( 'Theme Options sections', 'orpitey' ); ?>">
			<?php foreach ( $sections as $id => $section ) : ?>
				<a href="<?php echo esc_url( add_query_arg( 'tab', $id ) ); ?>" class="<?php echo $id === $tab ? 'is-active' : ''; ?>" data-tab="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $section['label'] ); ?></a>
			<?php endforeach; ?>
		</nav>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="orpitey_save_theme_options">
			<input type="hidden" name="tab" id="orpitey-active-tab" value="<?php echo esc_attr( $tab ); ?>">
			<?php wp_nonce_field( 'orpitey_theme_options', 'orpitey_theme_options_nonce' ); ?>

			<?php foreach ( $sections as $id => $section ) : ?>
				<div class="orpitey-panel<?php echo $id === $tab ? ' is-active' : ''; ?>" id="orpitey-panel-<?php echo esc_attr( $id ); ?>" data-panel="<?php echo esc_attr( $id ); ?>">
					<?php if ( 'trust' === $id ) : ?>
						<?php orpitey_render_trust_repeater(); ?>
					<?php elseif ( 'search' === $id ) : ?>
						<?php orpitey_render_search_suggestions_repeater(); ?>
					<?php elseif ( 'layout' === $id ) : ?>
						<?php orpitey_render_home_layout(); ?>
					<?php elseif ( 'campaign' === $id ) : ?>
						<?php orpitey_render_campaign_repeater(); ?>
					<?php elseif ( 'coll' === $id ) : ?>
						<table class="form-table" role="presentation">
							<tbody>
								<?php foreach ( $section['fields'] as $field ) : ?>
									<?php orpitey_render_option_field( $field ); ?>
								<?php endforeach; ?>
							</tbody>
						</table>
						<?php orpitey_render_collection_repeater(); ?>
						<?php orpitey_render_collection_sections_repeater(); ?>
					<?php else : ?>
						<table class="form-table" role="presentation">
							<tbody>
								<?php foreach ( $section['fields'] as $field ) : ?>
									<?php orpitey_render_option_field( $field ); ?>
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>

			<p class="submit">
				<button type="submit" class="button button-primary"><?php esc_html_e( 'Save changes', 'orpitey' ); ?></button>
			</p>
		</form>
	</div>
	<?php
}

/**
 * Render one settings row.
 *
 * @param array $field Field definition.
 */
function orpitey_render_option_field( $field ) {
	$key     = $field['key'];
	$type    = $field['type'];
	$id      = 'orpitey-field-' . $key;
	$default = isset( $field['default'] ) ? $field['default'] : false;
	$value   = orpitey_option_field_value( $key, $type, $default );
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
		<td>
			<?php if ( 'textarea' === $type ) : ?>
				<textarea class="large-text" rows="5" id="<?php echo esc_attr( $id ); ?>" name="orpitey[<?php echo esc_attr( $key ); ?>]"><?php echo esc_textarea( $value ); ?></textarea>
			<?php elseif ( 'checkbox' === $type ) : ?>
				<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="orpitey[<?php echo esc_attr( $key ); ?>]" value="1" <?php checked( $value ); ?>>
			<?php elseif ( 'image' === $type ) : ?>
				<div class="orpitey-image-field<?php echo $value ? '' : ' is-empty'; ?>">
					<input type="hidden" id="<?php echo esc_attr( $id ); ?>" name="orpitey[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $value ); ?>">
					<div class="orpitey-image-preview">
						<img src="<?php echo esc_url( $value ); ?>" alt="">
					</div>
					<button type="button" class="button orpitey-image-select"><?php esc_html_e( 'Choose image', 'orpitey' ); ?></button>
					<button type="button" class="button orpitey-image-remove"><?php esc_html_e( 'Remove', 'orpitey' ); ?></button>
				</div>
			<?php elseif ( 'color' === $type ) : ?>
				<input type="color" id="<?php echo esc_attr( $id ); ?>" name="orpitey[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $value ); ?>">
			<?php elseif ( 'select' === $type ) : ?>
				<select id="<?php echo esc_attr( $id ); ?>" name="orpitey[<?php echo esc_attr( $key ); ?>]">
					<?php foreach ( $field['choices'] as $choice => $choice_label ) : ?>
						<option value="<?php echo esc_attr( $choice ); ?>" <?php selected( $value, $choice ); ?>><?php echo esc_html( $choice_label ); ?></option>
					<?php endforeach; ?>
				</select>
			<?php elseif ( 'number' === $type ) : ?>
				<input class="small-text" type="number" min="0" step="1" id="<?php echo esc_attr( $id ); ?>" name="orpitey[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $value ); ?>">
			<?php else : ?>
				<input class="regular-text" type="<?php echo 'url' === $type ? 'url' : 'text'; ?>" id="<?php echo esc_attr( $id ); ?>" name="orpitey[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $value ); ?>">
			<?php endif; ?>
		</td>
	</tr>
	<?php
}
