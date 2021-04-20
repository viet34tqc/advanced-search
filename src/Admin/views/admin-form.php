<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://nuancedesignstudio.in
 * @since      1.0.0
 *
 * @package    NDS_Advanced_Search
 * @subpackage NDS_Advanced_Search/inc/admin/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<form method="post" action="options.php">
		<?php
		// add the nonce, option_page, action and referer.
		settings_fields( 'nv_search_option_group' );
		do_settings_sections( 'nv_search_page' );
		submit_button();
		?>
	</form>

</div>
