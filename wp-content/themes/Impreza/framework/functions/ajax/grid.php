<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Ajax method for grids ajax pagination.
 */
add_action( 'wp_ajax_nopriv_us_ajax_grid', 'us_ajax_grid' );
add_action( 'wp_ajax_us_ajax_grid', 'us_ajax_grid' );
function us_ajax_grid() {

	if ( class_exists( 'WPBMap' ) AND method_exists( 'WPBMap', 'addAllMappedShortcodes' ) ) {
		WPBMap::addAllMappedShortcodes();
	}

	// Filtering $template_vars, as is will be extracted to the template as local variables
	$template_vars = shortcode_atts(
		array(
			'query_args' => array(),
			'post_id' => FALSE,
			'us_grid_index' => FALSE,
			'exclude_items' => 'none',
			'items_offset' => 0,
			'items_layout' => 'blog_classic',
			'type' => 'grid',
			'columns' => 2,
			'img_size' => 'default',
			'lang' => FALSE,
		), us_maybe_get_post_json( 'template_vars' )
	);

	if ( class_exists( 'SitePress' ) AND $template_vars['lang'] ) {
		global $sitepress;
		$sitepress->switch_lang( $template_vars['lang'] );
	}

	$post_id = isset( $template_vars['post_id'] ) ? intval( $template_vars['post_id'] ) : 0;
	if ( $post_id > 0 ) {
		$post = get_post( $post_id );
		if ( empty( $post ) ) {
			wp_send_json_error();
		}

		$grid_index = isset( $template_vars['us_grid_index'] ) ? intval( $template_vars['us_grid_index'] ) : 1;

		// Retrieving the relevant shortcode from the page to get options
		$post_content = $post->post_content;

		preg_match_all( '~\[us_grid(.*?)\]~', $post_content, $matches );

		if ( ! isset( $matches[0][ $grid_index - 1 ] ) ) {
			wp_send_json_error();
		}

		// Getting the relevant shortcode options
		$shortcode = $matches[0][ $grid_index - 1 ];
		// For proper shortcode_parse_atts behaviour
		$shortcode = substr_replace( $shortcode, ' ]', - 1 );
		$shortcode_atts = shortcode_parse_atts( $shortcode );

		$shortcode_atts = shortcode_atts(
			array(
				'post_type' => '',
			), $shortcode_atts
		);

		if ( $shortcode_atts['post_type'] == '' ) {
			$shortcode_atts['post_type'] = 'post';
		}

		$allowed_post_types = array( $shortcode_atts['post_type'] );
	}

	// Filtering query_args
	if ( isset( $template_vars['query_args'] ) AND is_array( $template_vars['query_args'] ) ) {
		// Query Args keys, that won't be filtered
		$allowed_query_keys = array(
			// Grid listing shortcode requests
			'author_name',
			'us_portfolio_category',
			'us_portfolio_tag',
			'category_name',
			'tax_query',
			// Archive requests
			'year',
			'monthnum',
			'day',
			'tag',
			// Search requests
			's',
			// Pagination
			'paged',
			'orderby',
			'posts_per_page',
			'post__not_in',
			// Custom users' queries
			'post_type',
		);
		$public_cpt = array_keys( us_get_public_cpt() );
		if ( ! isset( $allowed_post_types ) ) {
			$allowed_post_types = array_merge(
				array( 'us_portfolio', 'post', 'product' ),
				$public_cpt
			);
		}


		foreach ( $template_vars['query_args'] as $query_key => $query_val ) {
			if ( ! in_array( $query_key, $allowed_query_keys ) ) {
				unset( $template_vars['query_args'][ $query_key ] );
			}
		}
		if ( isset( $template_vars['query_args']['post_type'] ) ) {
			$is_allowed_post_type = FALSE;
			foreach ( $allowed_post_types as $allowed_post_type ) {
				if ( $template_vars['query_args']['post_type'] == $allowed_post_type OR ( is_array( $template_vars['query_args']['post_type'] ) AND count( $template_vars['query_args']['post_type'] ) == 1 AND $template_vars['query_args']['post_type'][0] == $allowed_post_type ) ) {
					$is_allowed_post_type = TRUE;
				}
			}
			if ( ! $is_allowed_post_type ) {
				unset( $template_vars['query_args']['post_type'] );
			}
		}
		if ( ! isset( $template_vars['query_args']['s'] ) AND ! isset( $template_vars['query_args']['post_type'] ) ) {
			$template_vars['query_args']['post_type'] = 'post';
		}
		// Providing proper post statuses
		$template_vars['query_args']['post_status'] = array( 'publish' => 'publish' );
		$template_vars['query_args']['post_status'] += (array) get_post_stati( array( 'public' => TRUE ) );
		// Add private states if user is capable to view them
		if ( is_user_logged_in() AND current_user_can( 'read_private_posts' ) ) {
			$template_vars['query_args']['post_status'] += (array) get_post_stati( array( 'private' => TRUE ) );
		}
		$template_vars['query_args']['post_status'] = array_values( $template_vars['query_args']['post_status'] );
	}

	// Passing values that were filtered due to post protocol
	us_load_template( 'templates/us_grid/listing', $template_vars );

	// We don't use JSON to reduce data size
	die;
}
