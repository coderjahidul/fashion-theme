<?php
/**
 * Empty results.
 *
 * @package Orpitey
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="empty-state">
	<span class="empty-state__icon"><?php orpitey_the_icon( 'search', 26 ); ?></span>
	<h2 class="empty-state__title"><?php esc_html_e( 'এখানে এখনো কিছু নেই', 'orpitey' ); ?></h2>
	<p><?php esc_html_e( 'অন্য কিছু খুঁজে দেখুন।', 'orpitey' ); ?></p>
	<?php get_search_form(); ?>
</div>
