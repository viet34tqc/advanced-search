<?php

namespace NVAdvancedSearch\Admin;

class Admin {
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'init_setting_page' ] );
		add_action( 'admin_init', [ $this, 'register_setting' ] );
		add_action( 'admin_init', [ $this, 'register_fields' ] );
	}

	public function init_setting_page() {
		add_options_page(
			__( 'Advanced Search Settings', 'nv-as' ),
			__( 'Advanced Search Settings', 'nv-as' ),
			'manage_options',
			'advanced-search-settings',
			[ $this, 'render' ]
		);
	}

	public function render() {
		include __DIR__ . '/views/admin-form.php';
	}

	public function register_setting() {
		register_setting(
			'nv-search-settings',
			'nv-search-settings',
			[
				'sanitize_callback' => [ $this, 'validate_setting' ]
			]
		);
	}

	public function register_fields() {
		
	}

	private function validate_setting( $input ) {

	}
}
