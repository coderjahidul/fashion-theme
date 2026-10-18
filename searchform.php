<?php
/**
 * Search form.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

$uid = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $uid ); ?>" class="screen-reader-text"><?php esc_html_e( 'খুঁজুন', 'orpitey' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $uid ); ?>" class="input" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'শাড়ি, থ্রি-পিস খুঁজুন…', 'orpitey' ); ?>">
	<button type="submit" class="btn btn-primary"><?php esc_html_e( 'খুঁজুন', 'orpitey' ); ?></button>
</form>
