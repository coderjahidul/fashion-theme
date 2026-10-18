<?php
/**
 * Homepage.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;

get_header();

foreach ( orpitey_home_layout() as $item ) {
	if ( ! empty( $item['hidden'] ) ) {
		continue;
	}
	orpitey_render_home_section( $item['id'] );
}

get_footer();
