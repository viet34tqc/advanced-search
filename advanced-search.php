<?php
/**
 * Plugin Name:       NV Advanced Search
 * Plugin URI:
 * Description:       The plugin adds an advanced search form with search suggestions using a shortcode.
 * Version:           1.0.0
 * Author:            Viet Nguyen
 * Author URI:
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nv-as
 * Domain Path:       /languages
 */

namespace NV_Advanced_Search;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

$plugin_transient = array(
	'autosuggest_transient' => 'nds_autosuggest_' . $plugin_version, // appending version in cases when the plugin updates and we want the newer transient version.
	'autosuggest_transient_expiration' => ( 6 * HOUR_IN_SECONDS ),
	'admin_notice_transient' => 'nds-advanced-search-activation-notice' . $plugin_version,
	'admin_notice_transient_expiration' => 5,
);

require 'vendor/autoload.php';

$min_php = '5.6.0';

// Check the minimum required PHP version and run the plugin.
if ( version_compare( PHP_VERSION, $min_php, '>=' ) ) {
    require __DIR__ . '/bootstrap.php';
}
