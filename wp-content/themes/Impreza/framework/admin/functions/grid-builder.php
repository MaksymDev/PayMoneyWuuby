<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

add_filter( 'usof_container_classes', 'usgb_usof_container_classes' );
function usgb_usof_container_classes( $classes ) {
	return $classes . ' with_gb';
}

add_action( 'init', 'usgb_create_post_types', 7 );
function usgb_create_post_types() {

	// Grid Layout post type
	register_post_type(
		'us_grid_layout', array(
			'labels' => array(
				'name' => __( 'Grid Layouts', 'us' ),
				'singular_name' => __( 'Grid Layout', 'us' ),
				'add_new' => __( 'Add Grid Layout', 'us' ),
				'add_new_item' => __( 'Add Grid Layout', 'us' ),
				'edit_item' => __( 'Edit Grid Layout', 'us' ),
			),
			'public' => TRUE,
			'show_in_menu' => 'us-theme-options',
			'exclude_from_search' => TRUE,
			'show_in_admin_bar' => FALSE,
			'publicly_queryable' => FALSE,
			'show_in_nav_menus' => FALSE,
			'capability_type' => array( 'us_page_block', 'us_page_blocks' ),
			'map_meta_cap' => TRUE,
			'supports' => FALSE,
			'rewrite' => FALSE,
			'register_meta_box_cb' => 'usgb_us_grid_type_pages',
		)
	);

	// Add "Used in" column into Grid Layouts admin page
	add_filter( 'manage_us_grid_layout_posts_columns', 'ushb_us_grid_layout_columns_head' );
	add_action( 'manage_us_grid_layout_posts_custom_column', 'ushb_us_grid_layout_columns_content', 10, 2 );
	function ushb_us_grid_layout_columns_head( $defaults ) {
		$result = array();
		foreach ( $defaults as $key => $title ) {
			if ( $key == 'date' ) {
				$result['used_in'] = __( 'Used in', 'us' );
			}
			$result[$key] = $title;
		}
		return $result;
	}

	function ushb_us_grid_layout_columns_content( $column_name, $post_ID ) {
		if ( $column_name == 'used_in' ) {
			$used_in = array(
				'options' => array(),
				'posts' => array(),
			);
			global $usof_options, $wpdb;
			usof_load_options_once();
			if ( isset( $usof_options['post_related_layout'] ) AND $usof_options['post_related_layout'] == $post_ID ) {
				$used_in['options'][] = '<strong>' . __( 'Related Posts', 'us' ) . '</strong>';
			}
			if ( isset( $usof_options['blog_layout'] ) AND $usof_options['blog_layout'] == $post_ID ) {
				$used_in['options'][] = '<strong>' . __( 'Blog Home Page', 'us' ) . '</strong>';
			}
			if ( isset( $usof_options['archive_layout'] ) AND $usof_options['archive_layout'] == $post_ID ) {
				$used_in['options'][] = '<strong>' . __( 'Archive Pages', 'us' ) . '</strong>';
			}
			if ( isset( $usof_options['search_layout'] ) AND $usof_options['search_layout'] == $post_ID ) {
				$used_in['options'][] = '<strong>' . __( 'Search Results Page', 'us' ) . '</strong>';
			}
			$usage_query = "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = 'us_grid_layout_ids' AND meta_value LIKE '%" . $post_ID . "%' LIMIT 0, 100";
			foreach ( $wpdb->get_results( $usage_query ) as $usage_result ) {
				$post = get_post( $usage_result->post_id );
				if ( $post ) {
					$used_in['posts'][$post->ID] = array(
						'url' => get_permalink( $post->ID ),
						'edit_url' => get_edit_post_link( $post->ID ),
						'title' => get_the_title( $post->ID ),
						'post_type' => get_post_type( $post->ID ),
					);
				}
			}
			foreach ( $used_in['options'] as $where ) {
				echo '<strong>' . $where . '</strong> (<a href="' . admin_url() . 'admin.php?page=us-theme-options#blog">' . __( 'edit in Theme Options', 'us' ) . '</a>)<br>';
			}
			foreach ( $used_in['posts'] as $where ) {
				if ( $where['post_type'] == 'us_page_block' ) {
					// output admin Edit link for Page Blocks
					echo '<a href="' . $where['edit_url'] . '" title="' . __( 'Edit Page Block', 'us' ) . '">' . $where['title'] . '</a><br>';
				} else {
					// output Permalink for other post types
					echo '<a href="' . $where['url'] . '" target="_blank" title="' . us_translate( 'View Page' ) . '">' . $where['title'] . '</a><br>';
				}
			}
		}
	}

}

function usgb_us_grid_type_pages() {
	global $post;
	// Dev note: This check is not necessary, but still wanted to make sure this function won't be bound somewhere else
	if ( ! ( $post instanceof WP_Post ) OR $post->post_type !== 'us_grid_layout' ) {
		return;
	}
	if ( $post->post_status === 'auto-draft' ) {
		// Page for creating new grid: creating it instantly and proceeding to editing
		$post_data = array( 'ID' => $post->ID );
		// Retrieving occupied names to generate new post title properly
		$existing_grids = usgb_get_existing_grids();
		if ( isset( $_GET['duplicate_from'] ) AND ( $original_post = get_post( (int) $_GET['duplicate_from'] ) ) !== NULL ) {
			// Handling post duplication
			$post_data['post_content'] = $original_post->post_content;
			$title_pattern = $original_post->post_title . ' (%d)';
			$cur_index = 2;
		} else {
			// Handling creation from scratch
			$title_pattern = __( 'Layout %d', 'us' );
			$cur_index = count( $existing_grids ) + 1;
		}
		// Generating new post title
		while ( in_array( $post_data['post_title'] = sprintf( $title_pattern, $cur_index ), $existing_grids ) ) {
			$cur_index ++;
		}
		wp_update_post( $post_data );
		wp_publish_post( $post->ID );
		// Redirect
		if ( isset( $_GET['duplicate_from'] ) ) {
			// When duplicating post, showing posts list next
			wp_redirect( admin_url( 'edit.php?post_type=us_grid_layout' ) );
		} else {
			// When creating from scratch proceeding to post editing next
			wp_redirect( admin_url( 'post.php?post=' . $post->ID . '&action=edit' ) );
		}
	} else {
		// Page for editing a grid
		add_action( 'admin_enqueue_scripts', 'usgb_enqueue_scripts' );
		add_action( 'edit_form_top', 'usgb_edit_form_top' );
	}
}

/**
 * Get available grids
 * @return array
 */
function usgb_get_existing_grids() {
	$result = array();
	$grids = get_posts(
		array(
			'post_type' => 'us_grid_layout',
			'posts_per_page' => - 1,
			'post_status' => 'any',
			'suppress_filters' => 0,
		)
	);
	foreach ( $grids as $grid ) {
		$result[$grid->ID] = $grid->post_title;
	}

	return $result;
}

function usgb_enqueue_scripts() {
	// Appending dependencies
	usof_print_styles();
	usof_print_scripts();

	// Appending required assets
	global $us_template_directory_uri;
	wp_enqueue_script( 'us-grid-builder', $us_template_directory_uri . '/framework/admin/js/grid-builder.js', array( 'usof-scripts' ), TRUE );

	// Disabling WP auto-save
	wp_dequeue_script( 'autosave' );
}

function usgb_edit_form_top( $post ) {
	echo '<div class="usof-container type_builder" data-ajaxurl="' . esc_attr( admin_url( 'admin-ajax.php' ) ) . '" data-id="' . esc_attr( $post->ID ) . '">';
	echo '<form class="usof-form" method="post" action="#" autocomplete="off">';
	// Output _nonce and _wp_http_referer hidden fields for ajax secuirity checks
	wp_nonce_field( 'usgb-update' );
	echo '<div class="usof-header">';
	echo '<div class="usof-header-title">' . __( 'Edit Grid Layout', 'us' ) . '</div>';

	us_load_template(
		'vendor/usof/templates/field', array(
			'name' => 'post_title',
			'id' => 'usof_header_title',
			'field' => array(
				'type' => 'text',
				'placeholder' => __( 'Grid Layout Name', 'us' ),
				'classes' => 'desc_0', // Reset desc position of global GB field
			),
			'values' => array(
				'post_title' => $post->post_title,
			),
		)
	);

	echo '<div class="usof-control for_help"><a href="https://help.us-themes.com/' . strtolower( US_THEMENAME ) . '/grid/" target="_blank" title="' . us_translate( 'Help' ) . '"></a></div>';
	echo '<div class="usof-control for_import"><a href="#" title="' . __( 'Export / Import', 'us' ) . '"></a></div>';
	echo '<div class="usof-control for_templates">';
	echo '<a href="#" title="' . __( 'Grid Layout Templates', 'us' ) . '"></a>';
	echo '<div class="usof-control-desc"><span>' . __( 'Choose Grid Layout Template to start with', 'us' ) . '</span></div>';
	echo '</div>';
	echo '<div class="usof-control for_save status_clear">';
	echo '<button class="usof-button type_save" type="button"><span>' . us_translate( 'Save Changes' ) . '</span>';
	echo '<span class="usof-preloader"></span></button>';
	echo '<div class="usof-control-message"></div></div></div>';

	us_load_template(
		'vendor/usof/templates/field', array(
			'name' => 'post_content',
			'id' => 'usof_header',
			'field' => array(
				'type' => 'grid_builder',
				'classes' => 'desc_0', // Reset desc position of global GB field
			),
			'values' => array(
				'post_content' => $post->post_content,
			),
		)
	);

	echo '</form>';
	echo '</div>';
}

// Add "Duplicate" link for Grid Layouts admin page
add_filter( 'post_row_actions', 'usgb_post_row_actions', 10, 2 );
function usgb_post_row_actions( $actions, $post ) {
	if ( $post->post_type === 'us_grid_layout' ) {
		// Removing duplicate post plugin affection
		unset( $actions['duplicate'], $actions['edit_as_new_draft'] );
		$actions = us_array_merge_insert(
			$actions, array(
			'duplicate' => '<a href="' . admin_url( 'post-new.php?post_type=us_grid_layout&duplicate_from=' . $post->ID ) . '" aria-label="' . esc_attr__( 'Duplicate', 'us' ) . '">' . esc_html__( 'Duplicate', 'us' ) . '</a>',
		), 'before', isset( $actions['trash'] ) ? 'trash' : 'untrash'
		);
	}

	return $actions;
}

// Remember Grid Layout IDs when save post. For "Used in" column
add_action( 'save_post', 'us_save_post_add_grid_id' );
function us_save_post_add_grid_id( $post_id ) {
	$post = get_post( $post_id );
	$the_content = $post->post_content;
	if ( preg_match_all('/\[us_grid[^\]]+items_layout="([0-9]+)"/i', $the_content, $matches) ) {
		$ids = implode( ',', $matches[1] );
		update_post_meta( $post_id, 'us_grid_layout_ids', $ids );
	} else {
		update_post_meta( $post_id, 'us_grid_layout_ids', '' );
	}
}
