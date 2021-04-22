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
			'nv_search_option_group',
			'nv_search_settings',
		);
	}

	public function register_fields() {
		add_settings_section(
			'post_types',
			'',
			[ $this, 'render_section' ],
			'nv_search_page'
		);

		add_settings_field(
			'post_types',
			'Post types',
			[ $this, 'render_checkbox_fields' ],
			'nv_search_page',
			'post_types',
		);
	}

	public function get_post_types() {
		$unsupported = [
			// WordPress built-in post types.
			'customize_changeset',
			'custom_css',
			'nav_menu_item',
			'oembed_cache',
			'revision',
			'user_request',
			'wp_block',

			// Meta Box post types.
			'mb-post-type',
			'mb-taxonomy',
			'mb-relationship',
			'mb-settings-page',
			'mb-views',
			'meta-box',
		];
		$post_types = get_post_types( [], 'objects' );
		$post_types = array_diff_key( $post_types, array_flip( $unsupported ) );

		return $post_types;
	}

	public function render_section() {
		?>
		<h4 class="nav-tab-wrapper"><?php echo esc_html( 'Select Post Types to Include in the Advanced Search', $this->plugin_text_domain ); ?></h4>
		<?php
	}

	public function render_checkbox_fields() {
		$post_types = $this->get_post_types();
		$settings = get_option( 'nv_search_settings' );
		foreach( $post_types as $post_type ) :
			$name    = $post_type->name;
			$label   = $post_type->label;
			$checked = ! empty( $settings ) ? in_array( $name, $settings ) : false;
		?>
			<div>
				<label for="<?php echo esc_attr( 'post_types' . '_' . $name ); ?>">
					<input
						type="checkbox"
						id="<?= esc_attr( 'post_types' . '_' . $name ); ?>"
						name="nv_search_settings[]"
						value="<?php echo esc_attr( $name ); ?>"
						<?php checked( $checked, 1, true ); ?>
					/>
					<?= esc_html( $label ); ?>
				</label>
			</div>
		<?php endforeach;
	}

	public function validate_setting( $input ) {

	}
}
