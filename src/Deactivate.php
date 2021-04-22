<?php
namespace NVAdvancedSearch;

class Deactivate {
	public function __construct( $file ) {
		register_deactivation_hook( $file, [ $this, 'deactivate' ] );
	}

	public function deactivate() {
		$settings = get_option( 'nv_search_settings' );
		if ( $settings['delete_on_deactive'] !== 'on' ) {
			return;
		}
		delete_option( 'nv_search_settings' );
		$transient = json_decode( NV_ADVANCED_SEARCH_TRANSIENT, true );
		delete_transient( $transient['name'] );
	}
}
