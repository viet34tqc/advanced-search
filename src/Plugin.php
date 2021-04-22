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
		define( 'NV_ADVANCED_SEARCH_DIR', plugin_dir_path( $this->file ) );
		define( 'NV_ADVANCED_SEARCH_URL', plugin_dir_url( $this->file ) );
		define( 'NV_ADVANCED_SEARCH_BASENAME', plugin_basename( $this->file ) );
		define( 'NV_ADVANCED_SEARCH_TEXTDOMAIN', 'nv-as' );

		$transient = array(
			'name'   => 'nv_autosuggest_' . NV_SEARCH_VERSION,   // appending version in cases when the plugin updates and we want the newer transient version.
			'expire' => ( 6 * HOUR_IN_SECONDS ),
		);
		define( 'NV_ADVANCED_SEARCH_TRANSIENT', \json_encode( $transient ) );
	}

	private function set_locale() {
		load_plugin_textdomain( 'nv-as', false, NV_ADVANCED_SEARCH_DIR . '/languages' );
	}
}

