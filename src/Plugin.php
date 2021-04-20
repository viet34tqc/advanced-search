<?php
namespace NVAdvancedSearch;

class Plugin {
	/**
	 * Plugin base file.
	 *
	 * @var string
	 */
	protected $file;

	public function __construct( $file ) {
		$this->file = $file;
		// Define constant
		$this->define_constant();
		// Set locole
		$this->set_locale();
	}

	private function define_constant() {
		define( 'NV_SEARCH_VERSION', '1.0.0' );
		define( 'NV_SEARCH_NAME_DIR', plugin_dir_path( $this->file ) );
		define( 'NV_SEARCH_NAME_URL', plugin_dir_url( $this->file ) );
		define( 'NV_SEARCH_BASENAME', plugin_basename( $this->file ) );
		define( 'NV_SEARCH_TEXT_DOMAIN', 'nv-as' );
	}

	private function set_locale() {
		load_plugin_textdomain( 'auto-listings', false, NV_SEARCH_NAME_DIR . '/languages' );
	}
}

