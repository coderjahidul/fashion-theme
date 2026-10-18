<?php
/**
 * Bangla localisation helpers: Bengali digits and WooCommerce string overrides.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether Bengali digits are enabled (Theme Options).
 *
 * @return bool
 */
function orpitey_bn_digits_enabled() {
	return (bool) get_theme_mod( 'orpitey_bn_digits', true );
}

/**
 * Convert Latin digits to Bengali digits in plain text.
 *
 * @param string|int|float $text Text.
 * @return string
 */
function orpitey_bn( $text ) {
	$text = (string) $text;
	if ( ! orpitey_bn_digits_enabled() ) {
		return $text;
	}
	return strtr( $text, array( '0' => '০', '1' => '১', '2' => '২', '3' => '৩', '4' => '৪', '5' => '৫', '6' => '৬', '7' => '৭', '8' => '৮', '9' => '৯' ) );
}

/**
 * Show Bengali digits as English digits in storefront text.
 *
 * @param string $text Text or HTML.
 * @return string
 */
function orpitey_en_digits( $text ) {
	if ( orpitey_bn_digits_enabled() || ! is_string( $text ) || '' === $text || ! orpitey_is_storefront_request() ) {
		return $text;
	}
	return strtr(
		$text,
		array(
			'০' => '0',
			'১' => '1',
			'২' => '2',
			'৩' => '3',
			'৪' => '4',
			'৫' => '5',
			'৬' => '6',
			'৭' => '7',
			'৮' => '8',
			'৯' => '9',
		)
	);
}

add_filter( 'the_content', 'orpitey_en_digits', 20 );
add_filter( 'the_excerpt', 'orpitey_en_digits', 20 );
add_filter( 'woocommerce_short_description', 'orpitey_en_digits', 20 );
add_filter( 'the_title', 'orpitey_en_digits', 20 );

/**
 * English month names that WordPress prints while the site locale is not Bangla.
 *
 * @return array
 */
function orpitey_month_names() {
	return array(
		'January'   => 'জানুয়ারি',
		'February'  => 'ফেব্রুয়ারি',
		'March'     => 'মার্চ',
		'April'     => 'এপ্রিল',
		'May'       => 'মে',
		'June'      => 'জুন',
		'July'      => 'জুলাই',
		'August'    => 'আগস্ট',
		'September' => 'সেপ্টেম্বর',
		'October'   => 'অক্টোবর',
		'November'  => 'নভেম্বর',
		'December'  => 'ডিসেম্বর',
	);
}

add_filter(
	'date_i18n',
	function ( $date ) {
		if ( ! orpitey_is_storefront_request() || 0 === strpos( get_locale(), 'bn' ) ) {
			return $date;
		}
		return orpitey_bn( strtr( $date, orpitey_month_names() ) );
	}
);

/**
 * Convert digits in HTML text nodes only, leaving tags and attributes untouched.
 *
 * @param string $html HTML.
 * @return string
 */
function orpitey_bn_html( $html ) {
	if ( ! orpitey_bn_digits_enabled() || '' === $html || ! preg_match( '/\d/', $html ) ) {
		return $html;
	}
	$parts = preg_split( '/(<[^>]*>|&#?[a-zA-Z0-9]+;)/', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
	foreach ( $parts as $i => $part ) {
		if ( '' !== $part && '<' !== $part[0] && '&' !== $part[0] ) {
			$parts[ $i ] = orpitey_bn( $part );
		}
	}
	return implode( '', $parts );
}

/**
 * Bangla digits in visible text. Skips scripts, styles, and form values.
 *
 * @param string $html HTML.
 * @return string
 */
function orpitey_bn_visible_html( $html ) {
	if ( ! orpitey_bn_digits_enabled() || ! is_string( $html ) || '' === $html || ! preg_match( '/\d/', $html ) ) {
		return $html;
	}
	$parts = preg_split(
		'/(<(?:script|style|textarea|noscript)\b[^>]*>.*?<\/(?:script|style|textarea|noscript)>)/is',
		$html,
		-1,
		PREG_SPLIT_DELIM_CAPTURE
	);
	if ( ! is_array( $parts ) ) {
		return orpitey_bn_html( $html );
	}
	foreach ( $parts as $i => $part ) {
		if ( preg_match( '/^<(?:script|style|textarea|noscript)\b/i', $part ) ) {
			continue;
		}
		$part = orpitey_bn_html( $part );
		$part = preg_replace_callback(
			'/\b(placeholder|alt|title|aria-label)="([^"]*)"/',
			function ( $matches ) {
				return $matches[1] . '="' . orpitey_bn_html( $matches[2] ) . '"';
			},
			$part
		);
		$parts[ $i ] = $part;
	}
	return implode( '', $parts );
}

/**
 * Convert a storefront response when it is HTML, and leave JSON alone.
 *
 * @param string $html Buffered output.
 * @return string
 */
function orpitey_bn_buffer( $html ) {
	if ( ! is_string( $html ) || '' === $html || ! orpitey_bn_digits_enabled() ) {
		return $html;
	}
	$trim = ltrim( $html );
	if ( '' === $trim || '{' === $trim[0] || '[' === $trim[0] || false === strpos( $html, '<' ) ) {
		return $html;
	}
	return orpitey_bn_visible_html( $html );
}

/**
 * Bangla digits inside WooCommerce HTML fragments returned as JSON.
 *
 * @param array $fragments Fragments.
 * @return array
 */
function orpitey_bn_fragments( $fragments ) {
	if ( ! orpitey_bn_digits_enabled() || ! is_array( $fragments ) ) {
		return $fragments;
	}
	foreach ( $fragments as $key => $html ) {
		if ( is_string( $html ) ) {
			$fragments[ $key ] = orpitey_bn_visible_html( $html );
		}
	}
	return $fragments;
}

add_action(
	'template_redirect',
	function () {
		if ( is_admin() || ! orpitey_bn_digits_enabled() ) {
			return;
		}
		ob_start( 'orpitey_bn_buffer' );
	},
	-1
);

add_filter( 'woocommerce_add_to_cart_fragments', 'orpitey_bn_fragments', 99 );
add_filter( 'woocommerce_update_order_review_fragments', 'orpitey_bn_fragments', 99 );

/**
 * Variation prices are injected by script from JSON, which the page buffer skips.
 *
 * @param array $data Variation data.
 * @return array
 */
function orpitey_bn_variation_data( $data ) {
	if ( ! orpitey_bn_digits_enabled() || ! is_array( $data ) ) {
		return $data;
	}
	foreach ( array( 'price_html', 'availability_html' ) as $key ) {
		if ( ! empty( $data[ $key ] ) && is_string( $data[ $key ] ) ) {
			$data[ $key ] = orpitey_bn_html( $data[ $key ] );
		}
	}
	return $data;
}
add_filter( 'woocommerce_available_variation', 'orpitey_bn_variation_data' );

/**
 * Only localise on the storefront (including WooCommerce AJAX fragments).
 *
 * @return bool
 */
function orpitey_is_storefront_request() {
	return ! is_admin() || wp_doing_ajax();
}

add_filter(
	'paginate_links_output',
	function ( $html ) {
		return orpitey_is_storefront_request() ? orpitey_bn_html( $html ) : $html;
	}
);

/**
 * The currency word stays "টাকা". Digit style follows the Bangla-digits option.
 */
add_filter(
	'woocommerce_currency_symbol',
	function ( $symbol, $currency ) {
		if ( 'BDT' === $currency && orpitey_is_storefront_request() ) {
			return 'টাকা';
		}
		return $symbol;
	},
	10,
	2
);

add_filter(
	'woocommerce_price_format',
	function ( $format ) {
		if ( ! orpitey_is_storefront_request() || 'BDT' !== get_woocommerce_currency() ) {
			return $format;
		}
		return '%2$s&nbsp;%1$s';
	},
	10
);

/**
 * Bangla translations for the WooCommerce strings customers see most.
 * Only applied when the site language is not already Bangla.
 *
 * @return array
 */
function orpitey_wc_strings() {
	return array(
		'Add to cart'                               => 'ব্যাগে যোগ করুন',
		'Select options'                            => 'বিকল্প বাছাই করুন',
		'Read more'                                 => 'বিস্তারিত দেখুন',
		'View cart'                                 => 'ব্যাগ দেখুন',
		'&ldquo;%s&rdquo; has been added to your cart' => '&ldquo;%s&rdquo; ব্যাগে যোগ হয়েছে',
		'Cart'                                      => 'ব্যাগ',
		'Checkout'                                  => 'চেকআউট',
		'Order received'                            => 'অর্ডার সম্পন্ন',
		'Proceed to checkout'                       => 'চেকআউটে যান',
		'Cart totals'                               => 'মোট হিসাব',
		'Subtotal'                                  => 'সাবটোটাল',
		'Subtotal:'                                 => 'সাবটোটাল:',
		'Total'                                     => 'সর্বমোট',
		'Shipping'                                  => 'ডেলিভারি চার্জ',
		'Product'                                   => 'পণ্য',
		'Price'                                     => 'মূল্য',
		'Quantity'                                  => 'পরিমাণ',
		'Remove this item'                          => 'এই পণ্যটি সরান',
		'Update cart'                               => 'ব্যাগ আপডেট করুন',
		'Apply coupon'                              => 'কুপন প্রয়োগ করুন',
		'Coupon code'                               => 'কুপন কোড',
		'Coupon:'                                   => 'কুপন:',
		'Your cart is currently empty.'             => 'আপনার ব্যাগ এখন খালি।',
		'Return to shop'                            => 'শপে ফিরে যান',
		'Billing details'                           => 'বিলিং তথ্য',
		'Billing &amp; Shipping'                    => 'ডেলিভারি তথ্য',
		'Ship to a different address?'              => 'ভিন্ন ঠিকানায় পাঠাতে চান?',
		'Additional information'                    => 'অতিরিক্ত তথ্য',
		'Your order'                                => 'আপনার অর্ডার',
		'Place order'                               => 'অর্ডার নিশ্চিত করুন',
		'First name'                                => 'নামের প্রথম অংশ',
		'Last name'                                 => 'নামের শেষ অংশ',
		'Company name'                              => 'প্রতিষ্ঠানের নাম',
		'Country / Region'                          => 'দেশ',
		'Street address'                            => 'ঠিকানা',
		'Town / City'                               => 'শহর',
		'District'                                  => 'জেলা',
		'Postcode / ZIP'                            => 'পোস্টকোড',
		'Phone'                                     => 'ফোন',
		'Email address'                             => 'ইমেইল',
		'Order notes'                               => 'অর্ডার নোট',
		'Cash on delivery'                          => 'ক্যাশ অন ডেলিভারি',
		'Pay with cash upon delivery.'              => 'পণ্য হাতে পেয়ে মূল্য পরিশোধ করুন।',
		'Thank you. Your order has been received.'  => 'ধন্যবাদ! আপনার অর্ডারটি গ্রহণ করা হয়েছে।',
		'Order number:'                             => 'অর্ডার নম্বর:',
		'Date:'                                     => 'তারিখ:',
		'Total:'                                    => 'সর্বমোট:',
		'Payment method:'                           => 'পেমেন্ট পদ্ধতি:',
		'Order details'                             => 'অর্ডারের বিবরণ',
		'Billing address'                           => 'বিলিং ঠিকানা',
		'Shipping address'                          => 'ডেলিভারি ঠিকানা',
		'Login'                                     => 'লগইন',
		'Log in'                                    => 'লগইন',
		'Register'                                  => 'রেজিস্টার',
		'Username or email address'                 => 'ইউজারনেম অথবা ইমেইল',
		'Password'                                  => 'পাসওয়ার্ড',
		'Remember me'                               => 'মনে রাখুন',
		'Lost your password?'                       => 'পাসওয়ার্ড ভুলে গেছেন?',
		'Dashboard'                                 => 'ড্যাশবোর্ড',
		'Account pages'                             => 'অ্যাকাউন্ট',
		'Orders'                                    => 'অর্ডারসমূহ',
		'Downloads'                                 => 'ডাউনলোড',
		'Addresses'                                 => 'ঠিকানা',
		'Account details'                           => 'অ্যাকাউন্টের তথ্য',
		'Log out'                                   => 'লগআউট',
		'Order'                                     => 'অর্ডার',
		'Date'                                      => 'তারিখ',
		'Status'                                    => 'অবস্থা',
		'Actions'                                   => 'অ্যাকশন',
		'View'                                      => 'দেখুন',
		'In stock'                                  => 'স্টকে আছে',
		'Out of stock'                              => 'স্টক শেষ',
		'Clear'                                     => 'মুছুন',
		'Description'                               => 'বিবরণ',
		'Reviews'                                   => 'পর্যালোচনা',
		'Related products'                          => 'সম্পর্কিত সংগ্রহ',
		'Default sorting'                           => 'ডিফল্ট ক্রম',
		'Sort by popularity'                        => 'জনপ্রিয়তা অনুযায়ী',
		'Sort by average rating'                    => 'রেটিং অনুযায়ী',
		'Sort by latest'                            => 'নতুন আগে',
		'Sort by price: low to high'                => 'দাম: কম থেকে বেশি',
		'Sort by price: high to low'                => 'দাম: বেশি থেকে কম',
		'Showing the single result'                 => 'একটি পণ্য দেখানো হচ্ছে',
		'No products were found matching your selection.' => 'এই বাছাইয়ে কোনো পণ্য পাওয়া যায়নি।',
		'Sale!'                                     => 'বিশেষ মূল্য',
		'Choose an option'                          => 'বাছাই করুন',
		'Please choose product options for %1$s.'   => '%1$s-এর জন্য পণ্যের অপশন বাছুন।',
		'Please choose product options by visiting <a href="%1$s" title="%2$s">%2$s</a>.' => '<a href="%1$s" title="%2$s">%2$s</a> পেজে গিয়ে পণ্যের অপশন বাছুন।',
		'Please choose product options&hellip;'     => 'পণ্যের অপশন বাছুন…',
		'Please select some product options before adding this product to your cart.' => 'ব্যাগে যোগ করার আগে পণ্যের অপশন বাছুন।',
		'Sorry, this product is unavailable. Please choose a different combination.' => 'দুঃখিত, এই পণ্যটি এখন নেই। অন্য একটি কম্বিনেশন বাছুন।',
		'Sorry, no products matched your selection. Please choose a different combination.' => 'দুঃখিত, এই বাছাইয়ের সঙ্গে কোনো পণ্য মেলেনি। অন্য একটি কম্বিনেশন বাছুন।',
		'Your selection has been reset. Please select some product options before adding this product to your cart.' => 'আপনার বাছাই মুছে গেছে। ব্যাগে যোগ করার আগে পণ্যের অপশন বাছুন।',
		'Free shipping'                             => 'ফ্রি ডেলিভারি',
		'Flat rate'                                 => 'ডেলিভারি চার্জ',
		'(optional)'                                => '(ঐচ্ছিক)',
		'optional'                                  => 'ঐচ্ছিক',
		'Home'                                      => 'হোম',
		'Have a coupon?'                            => 'কুপন আছে?',
		'Click here to enter your code'             => 'কোড লিখতে এখানে ক্লিক করুন',
		'Enter your coupon code'                    => 'আপনার কুপন কোড লিখুন',
		'If you have a coupon code, please apply it below.' => 'কুপন কোড থাকলে নিচে লিখে প্রয়োগ করুন।',
		'House number and street name'              => 'বাড়ি নম্বর ও রাস্তার নাম',
		'Apartment, suite, unit, etc.'              => 'ফ্ল্যাট, তলা ইত্যাদি',
		'Apartment, suite, unit, etc. (optional)'   => 'ফ্ল্যাট, তলা ইত্যাদি (ঐচ্ছিক)',
		'Notes about your order, e.g. special notes for delivery.' => 'অর্ডার সম্পর্কে কিছু জানাতে চাইলে লিখুন, যেমন ডেলিভারির বিশেষ নির্দেশনা।',
		'Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.' => 'দুঃখিত, এই মুহূর্তে কোনো পেমেন্ট পদ্ধতি চালু নেই। সহায়তার জন্য আমাদের সঙ্গে যোগাযোগ করুন।',
		'Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our %s.' => 'আপনার তথ্য অ্যাকাউন্ট চালাতে এবং এই ওয়েবসাইটে আপনার অভিজ্ঞতা সহজ করতে ব্যবহৃত হবে। বিস্তারিত আমাদের %s-এ।',
		'privacy policy'                            => 'প্রাইভেসি পলিসি',
		'My account'                                => 'আমার অ্যাকাউন্ট',
		'Shop'                                      => 'শপ',
		'Change address'                            => 'ঠিকানা বদলান',
		'Shipping to %s.'                           => '%s-এ ডেলিভারি।',
		'Shipping options will be updated during checkout.' => 'ডেলিভারি চার্জ চেকআউটে হিসাব হবে।',
		'Shipping costs are calculated during checkout.' => 'ডেলিভারি চার্জ চেকআউটে হিসাব হবে।',
		'Enter your address to view shipping options.' => 'ডেলিভারি দেখতে ঠিকানা লিখুন।',
		'Enter a different address'                 => 'অন্য ঠিকানা দিন',
		'No shipping options were found for %s.'    => '%s-এর জন্য কোনো ডেলিভারি পাওয়া যায়নি।',
		'Update'                                    => 'আপডেট',
		'Calculate shipping'                        => 'ডেলিভারি হিসাব করুন',
		'Select a country / region&hellip;'         => 'দেশ বাছুন…',
		'Select an option&hellip;'                  => 'একটি অপশন বাছুন…',
		'State / County'                            => 'জেলা',
		'City:'                                     => 'শহর',
		'Postcode / ZIP:'                           => 'পোস্টকোড',
		'Country / region'                          => 'দেশ',
		'Username'                                  => 'ইউজারনেম',
		'Username or email'                         => 'ইউজারনেম অথবা ইমেইল',
		'Email'                                     => 'ইমেইল',
		'Email:'                                    => 'ইমেইল:',
		'Required'                                  => 'আবশ্যক',
		'%s is a required field.'                   => '%s আবশ্যক।',
		'Show password'                             => 'পাসওয়ার্ড দেখুন',
		'Hide password'                             => 'পাসওয়ার্ড লুকান',
		'A link to set a new password will be sent to your email address.' => 'নতুন পাসওয়ার্ড সেট করার লিংক আপনার ইমেইলে যাবে।',
		'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.' => 'পাসওয়ার্ড ভুলে গেছেন? ইউজারনেম বা ইমেইল দিন। নতুন পাসওয়ার্ডের লিংক ইমেইলে পাঠানো হবে।',
		'Reset password'                            => 'পাসওয়ার্ড রিসেট করুন',
		'Very weak'                                 => 'খুব দুর্বল',
		'Weak'                                      => 'দুর্বল',
		'Medium'                                    => 'মাঝারি',
		'Strong'                                    => 'শক্তিশালী',
		'Save address'                              => 'ঠিকানা সেভ করুন',
		'Save changes'                              => 'পরিবর্তন সেভ করুন',
		'Browse products'                           => 'পণ্য দেখুন',
		'No order has been made yet.'               => 'এখনো কোনো অর্ডার হয়নি।',
		'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)' => 'হ্যালো %1$s (আপনি না? <a href="%2$s">লগআউট</a>)',
		'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.' => 'এখান থেকে আপনার <a href="%1$s">সাম্প্রতিক অর্ডার</a> দেখতে, <a href="%2$s">ডেলিভারি ও বিলিং ঠিকানা</a> বদলাতে, এবং <a href="%3$s">পাসওয়ার্ড ও অ্যাকাউন্টের তথ্য</a> আপডেট করতে পারবেন।',
		'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">billing address</a>, and <a href="%3$s">edit your password and account details</a>.' => 'এখান থেকে আপনার <a href="%1$s">সাম্প্রতিক অর্ডার</a> দেখতে, <a href="%2$s">বিলিং ঠিকানা</a> বদলাতে, এবং <a href="%3$s">পাসওয়ার্ড ও অ্যাকাউন্টের তথ্য</a> আপডেট করতে পারবেন।',
		'Order updates'                             => 'অর্ডার আপডেট',
		'l jS \o\f F Y, h:ia'                       => 'j F Y, g:i a',
		'Pay'                                       => 'পেমেন্ট করুন',
		'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.' => 'দুঃখিত, আপনার অর্ডারটি সম্পন্ন হয়নি। আবার চেষ্টা করুন।',
	);
}

/**
 * Context-specific WooCommerce strings, such as the cart "Shipment" label.
 */
add_filter(
	'gettext_with_context_woocommerce',
	function ( $translation, $text, $context ) {
		if ( ! orpitey_is_storefront_request() || 0 === strpos( get_locale(), 'bn' ) ) {
			return $translation;
		}
		$map = array(
			'shipping packages'    => array(
				'Shipment'    => 'ডেলিভারি',
				'Shipment %d' => 'ডেলিভারি %d',
			),
			'checkout-validation' => array(
				'Billing %s'  => '%s',
				'Shipping %s' => '%s',
			),
		);
		return isset( $map[ $context ][ $text ] ) ? $map[ $context ][ $text ] : $translation;
	},
	20,
	3
);

/**
 * Privacy notes are stored as WooCommerce options, so the gettext map never sees them.
 */
add_filter(
	'woocommerce_get_privacy_policy_text',
	function ( $text, $type ) {
		if ( 0 === strpos( get_locale(), 'bn' ) ) {
			return $text;
		}
		if ( 'registration' === $type ) {
			return 'আপনার তথ্য অ্যাকাউন্ট চালাতে এবং এই ওয়েবসাইটে আপনার অভিজ্ঞতা সহজ করতে ব্যবহৃত হবে। বিস্তারিত জানতে দেখুন আমাদের [privacy_policy]।';
		}
		if ( 'checkout' === $type ) {
			return 'আপনার তথ্য অর্ডার সম্পন্ন করতে এবং এই ওয়েবসাইটে আপনার অভিজ্ঞতা সহজ করতে ব্যবহৃত হবে। বিস্তারিত জানতে দেখুন আমাদের [privacy_policy]।';
		}
		return $text;
	},
	10,
	2
);

/**
 * Bangla breadcrumb labels for the WooCommerce system pages.
 */
add_filter(
	'woocommerce_get_breadcrumb',
	function ( $crumbs ) {
		if ( ! function_exists( 'wc_get_page_id' ) || 0 === strpos( get_locale(), 'bn' ) ) {
			return $crumbs;
		}
		$labels = array(
			'shop'      => __( 'শপ', 'orpitey' ),
			'cart'      => __( 'ব্যাগ', 'orpitey' ),
			'checkout'  => __( 'চেকআউট', 'orpitey' ),
			'myaccount' => __( 'আমার অ্যাকাউন্ট', 'orpitey' ),
		);
		foreach ( $labels as $page => $label ) {
			$page_id = wc_get_page_id( $page );
			if ( $page_id <= 0 ) {
				continue;
			}
			$title = get_the_title( $page_id );
			$url   = get_permalink( $page_id );
			foreach ( $crumbs as $i => $crumb ) {
				if ( $crumb[0] === $title && ( empty( $crumb[1] ) || $crumb[1] === $url ) ) {
					$crumbs[ $i ][0] = $label;
				}
			}
		}
		return $crumbs;
	}
);

add_filter(
	'ngettext_woocommerce',
	function ( $translation, $single, $plural, $number ) {
		if ( ! orpitey_is_storefront_request() || 0 === strpos( get_locale(), 'bn' ) ) {
			return $translation;
		}
		if ( 'Address' === $single && 'Addresses' === $plural ) {
			return 'ঠিকানা';
		}
		if ( '%s has been added to your cart.' === $single ) {
			return '%s ব্যাগে যোগ হয়েছে।';
		}
		return $translation;
	},
	20,
	4
);

add_filter(
	'gettext',
	function ( $translation, $text, $domain ) {
		if ( 'default' !== $domain || ! orpitey_is_storefront_request() || 0 === strpos( get_locale(), 'bn' ) ) {
			return $translation;
		}
		$map = array(
			'Very weak' => 'খুব দুর্বল',
			'Weak'      => 'দুর্বল',
			'Medium'    => 'মাঝারি',
			'Strong'    => 'শক্তিশালী',
		);
		return isset( $map[ $text ] ) ? $map[ $text ] : $translation;
	},
	20,
	3
);

add_filter(
	'gettext_woocommerce',
	function ( $translation, $text ) {
		static $map = null;
		if ( ! orpitey_is_storefront_request() || 0 === strpos( get_locale(), 'bn' ) ) {
			return $translation;
		}
		if ( null === $map ) {
			$map = orpitey_wc_strings();
		}
		return isset( $map[ $text ] ) ? $map[ $text ] : $translation;
	},
	20,
	2
);
