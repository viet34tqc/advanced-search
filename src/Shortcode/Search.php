<?php
namespace NVAdvancedSearch\Shortcode;

class Search {
	private $transient;

	public function __construct() {
		add_shortcode( 'nv_advanced_shortcode', [ $this, 'render' ] );
		add_action( 'wp_ajax_nv_advanced_search', [ $this, 'handle_ajax' ] );
		add_action( 'wp_ajax_nopriv_nv_advanced_search', [ $this, 'handle_ajax' ] );
		add_action( 'save_post', [ $this, 'delete_post_cache_for_post_type' ], 10, 2 );
		$this->transient = $this->get_transient_configure();
	}

	public function get_transient_configure() {
		return json_decode( NV_ADVANCED_SEARCH_TRANSIENT, true );
	}

	public function enqueue() {
		wp_enqueue_script( 'nv-as', \NV_ADVANCED_SEARCH_URL . 'src/Shortcode/js/search.js', [ 'jquery', 'jquery-ui-autocomplete' ], '', true );
		wp_enqueue_style( 'nv-as', \NV_ADVANCED_SEARCH_URL . 'src/Shortcode/css/jquery-ui.css' );
		wp_enqueue_style( 'nv-as', \NV_ADVANCED_SEARCH_URL . 'src/Shortcode/css/search-form.css' );
	}

	public function localize() {
		$cached_posts = get_transient( $this->transient['name'] );
		$data = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'cached_post_titles' => $cached_posts ?? [],
		);
		wp_localize_script( 'nv-as', 'params', $data );
	}

	public function get_search_form() {
		ob_start();
		include_once \NV_ADVANCED_SEARCH_DIR . 'src/Shortcode/views/search-form.php';
		return \ob_get_clean();
	}

	// Handle ajax when search.
	public function handle_ajax() {
		$posts = get_transient( $this->transient['name'] );
		if ( !$posts ) {
			$posts = $this->get_posts();
			if ( ! $posts ) {
				wp_send_json_error( 'No posts to display' );
			}
		}
		wp_send_json_success( $posts );
	}

	// Get posts from post types saved in option.
	// And save into transient.
	private function get_posts() {
		$post_types = get_option( 'nv_search_settings' );
		if ( ! $post_types ) {
			return '';
		}
		$posts = new \WP_Query( [
			'post_types'             => $post_types,
			'post_status'            => 'publish',
			'posts_per_page'         => -1,
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		] );
		if ( ! $posts->have_posts() ) {
			return [];
		}
		$cached_posts = [];
		while ( $posts->have_posts() ) {
			$posts->the_post();
			$cached_posts[] = [
				'title' => get_the_title(),
				'link'  => get_the_permalink(),
			];
		}
		set_transient( $this->transient['name'], $cached_posts, $this->transient['expire'] );
		return $cached_posts;
	}

	public function render( $atts ) {
		$this->enqueue();
		$this->localize();

		// Replace default markup search form.
		add_filter( 'get_search_form', [ $this, 'get_search_form' ] );

		ob_start();
		$search_form = get_search_form( false );
		echo $search_form;
		return ob_get_clean();
	}

	/**
	 * Delete cached posts from the transient when a post belonging to a
	 * post type specified in the plugin settings has been published or updated.
	 *
	 * @since 1.0.0
	 * @param string  $new_status New Post Status.
	 * @param string  $old_status Old Post Status.
	 * @param WP_Post $post  The Post Object.
	 */
	public function delete_post_cache_for_post_type( $post_id, $post ) {
		$transient_name = $this->transient['name'];
		$post_types = get_option( 'nv_search_settings' );
		if ( ! $post_types ) {
			return;
		}
		if ( in_array( $post->post_type, $post_types ) && get_transient( $transient_name ) ) {
			delete_transient( $transient_name );
		}
	}
}

