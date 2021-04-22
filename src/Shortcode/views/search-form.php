<?php
/*
 * Markup for the custom search form goes here.
 *
 * Note: Form input is stored inside an array with the plugin's name
 * e.g. $_POST['plugin_name']
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<form id="nv-as-form" role="search" method="POST" class="search-form" action="">
	<button class="nds-search-button" type="submit" class="search-submit"><?= __( 'Search', 'nv-as' ); ?></button>
	<div class="nds-input-container">
		<label>
			<input
				required
				class="nv-as-input" type="search"
				class="search-field"
				placeholder="<?= __( 'start typing for suggestions &hellip;', 'nv-as' ); ?>"
			/>
		</label>
	</div>
</form>
