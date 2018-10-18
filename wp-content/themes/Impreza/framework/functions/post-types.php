<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

// Should be inited before the WPBakery Page Builder (that is 9)
$portfolio_slug = us_get_option( 'portfolio_slug', 'portfolio' );
add_action( 'init', 'us_create_post_types', 8 );
function us_create_post_types() {

	if ( us_get_option( 'enable_portfolio', 1 ) == 1 ) {
		global $portfolio_slug;
		if ( $portfolio_slug == '' ) {
			$portfolio_rewrite = array( 'slug' => FALSE, 'with_front' => FALSE );
		} else {
			$portfolio_rewrite = array( 'slug' => untrailingslashit( $portfolio_slug ) );
		}
		// Portfolio Page post type
		register_post_type(
			'us_portfolio', array(
				'labels' => array(
					'name' => __( 'Portfolio', 'us' ),
					'singular_name' => __( 'Portfolio Page', 'us' ),
					'all_items' => __( 'Portfolio Pages', 'us' ),
					'add_new' => __( 'Add Portfolio Page', 'us' ),
					'add_new_item' => __( 'Add Portfolio Page', 'us' ),
					'edit_item' => __( 'Edit Portfolio Page', 'us' ),
					'featured_image' => us_translate_x( 'Featured Image', 'page' ),
					'view_item' => us_translate( 'View Page' ),
					'not_found' => us_translate( 'No pages found.' ),
					'not_found_in_trash' => us_translate( 'No pages found in Trash.' ),
				),
				'public' => TRUE,
				'rewrite' => $portfolio_rewrite,
				'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'comments' ),
				'capability_type' => array( 'us_portfolio', 'us_portfolios' ),
				'map_meta_cap' => TRUE,
				'menu_icon' => 'dashicons-images-alt',
			)
		);

		// Portfolio Categories
		register_taxonomy(
			'us_portfolio_category', array( 'us_portfolio' ), array(
				'labels' => array(
					'name' => __( 'Portfolio Categories', 'us' ),
					'menu_name' => us_translate( 'Categories' ),
				),
				'show_admin_column' => TRUE,
				'hierarchical' => TRUE,
				'rewrite' => array( 'slug' => us_get_option( 'portfolio_category_slug', 'portfolio_category' ) ),
			)
		);

		// Portfolio Tags
		register_taxonomy(
			'us_portfolio_tag', array( 'us_portfolio' ), array(
				'labels' => array(
					'name' => __( 'Portfolio Tags', 'us' ),
					'menu_name' => us_translate( 'Tags' ),
				),
				'show_admin_column' => TRUE,
				'rewrite' => array( 'slug' => us_get_option( 'portfolio_tag_slug' ) ),
			)
		);

		// Portfolio slug may have changed, so we need to keep WP's rewrite rules fresh
		if ( get_transient( 'us_flush_rules' ) ) {
			flush_rewrite_rules();
			delete_transient( 'us_flush_rules' );
		}
	}

	if ( us_get_option( 'enable_testimonials', 1 ) == 1 ) {
		// Testimonial post type
		register_post_type(
			'us_testimonial', array(
				'labels' => array(
					'name' => __( 'Testimonials', 'us' ),
					'singular_name' => __( 'Testimonial', 'us' ),
					'add_new' => __( 'Add Testimonial', 'us' ),
					'add_new_item' => __( 'Add Testimonial', 'us' ),
					'edit_item' => __( 'Edit Testimonial', 'us' ),
					'featured_image' => __( 'Author Photo', 'us' ),
				),
				'public' => TRUE,
				'publicly_queryable' => FALSE,
				'show_in_nav_menus' => FALSE,
				'supports' => array( 'title', 'editor', 'thumbnail' ),
				'menu_icon' => 'dashicons-testimonial',
				'capability_type' => array( 'us_testimonial', 'us_testimonials' ),
				'map_meta_cap' => TRUE,
				'rewrite' => FALSE,
			)
		);

		// Testimonial Categories
		register_taxonomy(
			'us_testimonial_category', array( 'us_testimonial' ), array(
				'labels' => array(
					'name' => __( 'Testimonial Categories', 'us' ),
					'menu_name' => us_translate( 'Categories' ),
				),
				'public' => TRUE,
				'show_admin_column' => TRUE,
				'publicly_queryable' => FALSE,
				'show_in_nav_menus' => FALSE,
				'show_in_rest' => FALSE,
				'show_tagcloud' => FALSE,
				'hierarchical' => TRUE,
			)
		);
	}

	// Widget Area post type (used in Menus)
	register_post_type(
		'us_widget_area', array(
			'labels' => array(
				'name' => __( 'Sidebars', 'us' ),
				'singular_name' => __( 'Sidebar', 'us' ),
			),
			'public' => FALSE,
			'show_in_nav_menus' => TRUE,
		)
	);

	// Page Block post type
	register_post_type(
		'us_page_block', array(
			'labels' => array(
				'name' => __( 'Page Blocks', 'us' ),
				'singular_name' => __( 'Page Block', 'us' ),
				'add_new' => __( 'Add Page Block', 'us' ),
				'add_new_item' => __( 'Add Page Block', 'us' ),
				'edit_item' => __( 'Edit Page Block', 'us' ),
			),
			'public' => TRUE,
			'show_in_menu' => 'us-theme-options',
			'exclude_from_search' => TRUE,
			'show_in_admin_bar' => FALSE,
			'publicly_queryable' => FALSE,
			'show_in_nav_menus' => FALSE,
			'capability_type' => array( 'us_page_block', 'us_page_blocks' ),
			'map_meta_cap' => TRUE,
			'rewrite' => FALSE,
			'register_meta_box_cb' => 'us_page_block_type_pages',
		)
	);

	// Add "Used in" column into Page Blocks admin page
	add_filter( 'manage_us_page_block_posts_columns', 'us_manage_page_block_columns_head' );
	add_action( 'manage_us_page_block_posts_custom_column', 'us_manage_page_block_columns_content', 10, 2 );
	function us_manage_page_block_columns_head( $defaults ) {
		$result = array();
		foreach ( $defaults as $key => $title ) {
			if ( $key == 'date' ) {
				$result['used_in'] = __( 'Used in', 'us' );
			}
			$result[ $key ] = $title;
		}

		return $result;
	}
	function us_manage_page_block_columns_content( $column_name, $post_ID ) {
		if ( $column_name == 'used_in' ) {
			$used_in = array(
				'options' => array(),
				'post_titlebars' => array(),
				'post_footers' => array(),
			);
			global $usof_options, $wpdb;
			usof_load_options_once();

			// Get CPTs
			$public_cpt = us_get_public_cpt();

			// Predefine types of 'Pages Layout' options
			$pages_layout_predefined_types = array(
				'' => __( 'Defaults', 'us' ),
				'portfolio' => __( 'Portfolio Pages', 'us' ),
				'post' => us_translate_x( 'Posts', 'post type general name' ),
				'archive' => __( 'Archive Pages', 'us' ),
				'search' => __( 'Search Results Page', 'us' ),
				'blog' => __( 'Blog Home Page', 'us' ),
				'shop' => __( 'Shop Pages', 'us' ),
				'product' => us_translate( 'Products', 'woocommerce' ),
			);

			// Add supported CPT of 'Pages Layout' options
			$pages_layout_predefined_types = array_merge( $pages_layout_predefined_types, $public_cpt );

			// Generate 'Used In' sources
			foreach ( $pages_layout_predefined_types as $type => $title ) {
				$type = ( $type != '' ) ? ( '_' . $type ) : '';
				if ( isset( $usof_options['titlebar' . $type . '_id'] ) AND $usof_options['titlebar' . $type . '_id'] == $post_ID ) {
					$used_in['options'][] = $title;
				}
				if ( isset( $usof_options['footer' . $type . '_id'] ) AND $usof_options['footer' . $type . '_id'] == $post_ID ) {
					$used_in['options'][] = $title;
				}
			}
			// Post titlebars
			$usage_query = "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = 'us_titlebar_id' AND meta_value = '" . $post_ID . "' LIMIT 0, 100";
			foreach ( $wpdb->get_results( $usage_query ) as $usage_result ) {
				$post = get_post( $usage_result->post_id );
				if ( $post ) {
					$is_custom_footer = get_post_meta( $post->ID, 'us_titlebar', TRUE ) == 'custom';
					if ( $is_custom_footer ) {
						$used_in['post_titlebars'][$post->ID] = array(
							'url' => get_permalink( $post->ID ),
							'title' => get_the_title( $post->ID ),
						);
					}
				}
			}
			// Post footers
			$usage_query = "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key = 'us_footer_id' AND meta_value = '" . $post_ID . "' LIMIT 0, 100";
			foreach ( $wpdb->get_results( $usage_query ) as $usage_result ) {
				$post = get_post( $usage_result->post_id );
				if ( $post ) {
					$is_custom_footer = get_post_meta( $post->ID, 'us_footer', TRUE ) == 'custom';
					if ( $is_custom_footer ) {
						$used_in['post_footers'][$post->ID] = array(
							'url' => get_permalink( $post->ID ),
							'title' => get_the_title( $post->ID ),
						);
					}
				}
			}

			// Output the result
			foreach ( $used_in['options'] as $where ) {
				echo '<strong>' . $where . '</strong> (<a href="' . admin_url() . 'admin.php?page=us-theme-options#pages_layout">' . __( 'edit in Theme Options', 'us' ) . '</a>)<br>';
			}
			foreach ( $used_in['post_titlebars'] as $where ) {
				echo '<a href="' . $where['url'] . '" target="_blank" title="' . us_translate( 'View Page' ) . '">' . $where['title'] . '</a><br>';
			}
			foreach ( $used_in['post_footers'] as $where ) {
				echo '<a href="' . $where['url'] . '" target="_blank" title="' . us_translate( 'View Page' ) . '">' . $where['title'] . '</a><br>';
			}
		}
	}

	// Add ability to duplicate posts
	add_filter( 'post_row_actions', 'us_page_block_post_row_actions', 10, 2 );
	function us_page_block_post_row_actions( $actions, $post ) {
		if ( $post->post_type === 'us_page_block' ) {
			// Removing duplicate post plugin affection
			unset( $actions['duplicate'], $actions['edit_as_new_draft'] );
			$actions = us_array_merge_insert(
				$actions, array(
				'duplicate' => '<a href="' . admin_url( 'post-new.php?post_type=us_page_block&duplicate_from=' . $post->ID ) . '" aria-label="' . esc_attr__( 'Duplicate', 'us' ) . '">' . esc_html__( 'Duplicate', 'us' ) . '</a>',
			), 'before', isset( $actions['trash'] ) ? 'trash' : 'untrash'
			);
		}

		return $actions;
	}

	function us_page_block_type_pages() {
		global $post;
		// Dev note: This check is not necessary, but still wanted to make sure this function won't be bound somewhere else
		if ( ! ( $post instanceof WP_Post ) OR $post->post_type !== 'us_page_block' ) {
			return;
		}
		if ( $post->post_status === 'auto-draft' ) {
			// Page for creating new Page Block: creating it instantly and proceeding to editing
			$post_data = array( 'ID' => $post->ID );
			// Retrieving occupied names to generate new post title properly
			$existing_page_blocks = array();
			$page_blocks = get_posts(
				array(
					'post_type' => 'us_page_block',
					'posts_per_page' => - 1,
					'post_status' => 'any',
					'suppress_filters' => 0,
				)
			);
			foreach ( $page_blocks as $page_block ) {
				$existing_page_blocks[ $page_block->ID ] = $page_block->post_title;
			}
			if ( isset( $_GET['duplicate_from'] ) AND ( $original_post = get_post( (int) $_GET['duplicate_from'] ) ) !== NULL ) {
				// Handling post duplication
				$post_data['post_content'] = $original_post->post_content;
				$title_pattern = $original_post->post_title . ' (%d)';
				$cur_index = 2;
			} else {
				// Handling creation from scratch
				$title_pattern = __( 'Page Block', 'us' ) . ' %d';
				$cur_index = count( $existing_page_blocks ) + 1;
			}
			// Generating new post title
			while ( in_array( $post_data['post_title'] = sprintf( $title_pattern, $cur_index ), $existing_page_blocks ) ) {
				$cur_index ++;
			}
			wp_update_post( $post_data );
			wp_publish_post( $post->ID );
			// Redirect
			if ( isset( $_GET['duplicate_from'] ) ) {
				// When duplicating post, showing posts list next
				wp_redirect( admin_url( 'edit.php?post_type=us_page_block' ) );
			} else {
				// When creating from scratch proceeding to post editing next
				wp_redirect( admin_url( 'post.php?post=' . $post->ID . '&action=edit' ) );
			}
		}
	}

	// Add iframe param for posts and pages opened in grid lightbox
	global $us_iframe;
	$us_iframe = ( isset( $_GET['us_iframe'] ) AND $_GET['us_iframe'] == 1 ) ? TRUE : FALSE;

	if ( $us_iframe ) {
		add_filter('show_admin_bar', '__return_false');
		remove_action( 'wp_head', '_admin_bar_bump_cb' );
	}
}

// Set Portfolio Pages slug
if ( us_get_option( 'enable_portfolio', 1 ) == 1 ) {
	if ( strpos( $portfolio_slug, '%us_portfolio_category%' ) !== FALSE ) {
		function us_portfolio_link( $post_link, $id = 0 ) {
			$post = get_post( $id );
			if ( is_object( $post ) ) {
				$terms = wp_get_object_terms( $post->ID, 'us_portfolio_category' );
				if ( $terms ) {
					return str_replace( '%us_portfolio_category%', $terms[0]->slug, $post_link );
				} else {
					// If no terms are assigned to this post, use a string instead (can't leave the placeholder there)
					return str_replace( '%us_portfolio_category%', 'uncategorized', $post_link );
				}
			}

			return $post_link;
		}
		add_filter( 'post_type_link', 'us_portfolio_link', 1, 3 );
	} elseif ( $portfolio_slug == '' ) {
		function us_portfolio_remove_slug( $post_link, $post, $leavename ) {
			if ( 'us_portfolio' != $post->post_type OR 'publish' != $post->post_status ) {
				return $post_link;
			}
			$post_link = str_replace( '/' . trailingslashit( $post->post_type ), '/', $post_link );

			return $post_link;
		}
		add_filter( 'post_type_link', 'us_portfolio_remove_slug', 10, 3 );

		function us_portfolio_parse_request( $query ) {
			if ( ! $query->is_main_query() OR 2 != count( $query->query ) OR ! isset( $query->query['page'] ) ) {
				return;
			}
			if ( ! empty( $query->query['name'] ) ) {
				$query->set( 'post_type', array( 'post', 'us_portfolio', 'page' ) );
			}
		}
		add_action( 'pre_get_posts', 'us_portfolio_parse_request' );
	}
}

// Exclude Testimonials from search results
if ( us_get_option( 'enable_testimonials', 1 ) == 1 ) {
	add_action( 'pre_get_posts', 'us_exclude_testimonials_from_search' );
	function us_exclude_testimonials_from_search() {
		if ( ! is_search() ) {
			return;
		}
		global $wp_post_types;
		if ( post_type_exists( 'us_testimonial' ) ) {
			$wp_post_types['us_testimonial']->exclude_from_search = TRUE;
		}
	}
}

// Get link attributes from custom meta option. Example: {{meta_name}}
if ( us_get_option( 'enable_testimonials', 1 ) == 1 OR us_get_option( 'enable_portfolio', 1 ) == 1 ) {
	add_filter( 'us_grid_element_custom_link', 'us_grid_element_custom_link_cpt_meta', 10, 2 );
	function us_grid_element_custom_link_cpt_meta( $link_atts ) {
		if ( $link_atts['href'] == '{{us_testimonial_link}}' OR $link_atts['href'] == '{{us_tile_link}}' ) {
			$_field = ( $link_atts['href'] == '{{us_testimonial_link}}' ) ? 'us_testimonial_link' : 'us_tile_link';
			$postID = get_the_ID();
			$meta_field = get_post_meta( $postID, $_field, TRUE );
			$meta_link_atts = usof_get_link_atts( json_decode( $meta_field, TRUE ) );
			$link_atts['href'] = ( ! empty( $meta_link_atts['href'] ) ) ? $meta_link_atts['href'] : '';
			$link_atts['meta'] = ( ! empty( $link_atts['meta'] ) ) ? $link_atts['meta'] : '';
			$link_atts['meta'] .= ( ! empty( $meta_link_atts['target'] ) AND strpos( $link_atts['meta'], 'target=' ) === FALSE ) ? ' target="' . esc_attr( $meta_link_atts['target'] ) . '"' : '';
			$link_atts['meta'] .= ' rel="nofollow"'; // force "nofollow" for metabox URLs
		}

		return $link_atts;
	}
}

// Add admin capabilities to Portfolio, Testimonials, Page Blocks
add_action( 'admin_init', 'us_add_theme_caps' );
function us_add_theme_caps() {
	global $wp_post_types;
	$role = get_role( 'administrator' );
	$force_refresh = FALSE;
	$custom_post_types = array( 'us_portfolio', 'us_testimonial', 'us_page_block' );
	foreach ( $custom_post_types as $post_type ) {
		if ( ! post_type_exists( $post_type ) ) {
			continue;
		}
		foreach ( $wp_post_types[ $post_type ]->cap as $cap ) {
			if ( ! $role->has_cap( $cap ) ) {
				$role->add_cap( $cap );
				$force_refresh = TRUE;
			}
		}
	}
	if ( $force_refresh AND current_user_can( 'manage_options' ) AND ! isset( $_COOKIE['us_cap_page_refreshed'] ) ) {
		// To prevent infinite refreshes when the DB is not writable
		setcookie( 'us_cap_page_refreshed' );
		header( 'Refresh: 0' );
	}
}

// Add role capabilities to Portfolio & Testimonials
add_action( 'admin_init', 'us_theme_activation_add_caps' );
function us_theme_activation_add_caps() {
	global $pagenow;
	if ( is_admin() AND $pagenow == 'themes.php' AND isset( $_GET['activated'] ) ) {
		if ( get_option( US_THEMENAME . '_editor_caps_set' ) == 1 ) {
			return;
		}
		update_option( US_THEMENAME . '_editor_caps_set', 1 );
		global $wp_post_types;
		$role = get_role( 'editor' );
		$custom_post_types = array( 'us_portfolio', 'us_testimonial' );
		foreach ( $custom_post_types as $post_type ) {
			if ( ! post_type_exists( $post_type ) ) {
				continue;
			}
			foreach ( $wp_post_types[ $post_type ]->cap as $cap ) {
				if ( ! $role->has_cap( $cap ) ) {
					$role->add_cap( $cap );
				}
			}
		}
	}
}

// Add Default Footer on theme activation
function us_add_default_footer() {

	// If any Page Block exists, do nothing
	$args = array(
		'post_type' => 'us_page_block',
		'post_status' => 'any',
		'numberposts' => - 1,
	);
	$page_blocks = get_posts( $args );
	if ( $page_blocks ) {
		return FALSE;
	}

	// Create Default Footer
	$footer_content = '[vc_row color_scheme="footer-bottom"][vc_column][vc_column_text]<p style="text-align: center;">&copy; Copyright text goes here</p>[/vc_column_text][/vc_column][/vc_row]';
	$footer_post_array = array(
		'post_type' => 'us_page_block',
		'post_date' => date( 'Y-m-d H:i', time() ),
		'post_title' => __( 'Default Footer', 'us' ),
		'post_content' => $footer_content,
		'post_status' => 'publish',
	);
	$footer_post_id = wp_insert_post( $footer_post_array );

	// Set Default Footer as default in Theme Options
	global $usof_options;
	usof_load_options_once();

	$updated_options = $usof_options;
	$updated_options['footer_id'] = $footer_post_id;
	$updated_options = array_merge( usof_defaults(), $updated_options );

	usof_save_options( $updated_options );
}

// Add needed filters to Page Block content
add_filter( 'us_page_block_the_content', 'wptexturize' );
add_filter( 'us_page_block_the_content', 'wpautop' );
add_filter( 'us_page_block_the_content', 'shortcode_unautop' );
add_filter( 'us_page_block_the_content', 'wp_make_content_images_responsive' );
add_filter( 'us_page_block_the_content', 'do_shortcode', 12 );
add_filter( 'us_page_block_the_content', 'convert_smilies', 20 );
