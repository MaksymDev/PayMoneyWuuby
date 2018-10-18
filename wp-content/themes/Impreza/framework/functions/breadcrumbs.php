<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * Based on breadcrumbs function by Dimox
 * http://dimox.net/wordpress-breadcrumbs-without-a-plugin/
 */
if ( ! function_exists( 'us_breadcrumbs' ) ) {
	function us_breadcrumbs( $delimiter, $home, $item_before, $item_after, $link_attr ) {
		global $post;
		// Homepage Label
		$home = isset( $home ) ? $home : us_translate( 'Home' );
		// Separator between crumbs
		$delimiter = isset( $delimiter ) ? $delimiter : ' > ';
		// Code before the current crumb
		$before = isset( $item_before ) ? $item_before : '';
		// Code after the current crumb
		$after = isset( $item_after ) ? $item_after : '';
		// Links attributes
		$link_attr = isset( $link_attr ) ? $link_attr : '';
		// Predefined link attributes
		$homeLink = trailingslashit( home_url() );
		$link = $before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $after;
		// Predefined text
		$text['search'] = us_translate( 'Search Results' ); // text for a search results page
		$text['404'] = us_translate( 'Page not found' ); // text for the 404 page
		$text['forums'] = us_translate( 'Forums', 'bbpress' ); // text for the forums page
		// Generate HTML for output
		$output = '';
		// "Home" crumb
		if ( ! empty( $home ) ) {
			$output .= sprintf( $link, $homeLink, $home );
			$output .= $delimiter;
		}
		// Category (for Posts only)
		if ( is_category() ) {
			$thisCat = get_category( get_query_var( 'cat' ), FALSE );
			if ( $thisCat->parent != 0 ) {
				$cats = get_category_parents( $thisCat->parent, TRUE, $delimiter );
				$cats = str_replace( '<a', $before . '<a' . $link_attr, $cats );
				$cats = str_replace( '</a>', '</a>' . $after, $cats );
				$output .= $cats;
			}
			$output .= $before . single_cat_title( '', FALSE ) . $after;
		// Tag
		} elseif ( is_tag() ) {
			$output .= $before . single_tag_title( '', FALSE ) . $after;
		// Author
		} elseif ( is_author() ) {
			global $author;
			$userdata = get_userdata( $author );
			$output .= $before . $userdata->display_name . $after;
		// 404 page
		} elseif ( is_404() ) {
			$output .= $before . $text['404'] . $after;
		// Search Results
		} elseif ( is_search() ) {
			$output .= $before . $text['search'] . $after;
		// Day
		} elseif ( is_day() ) {
			$output .= sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) );
			$output .= $delimiter;
			$output .= sprintf( $link, get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ), __( get_the_time( 'F' ), 'us' ) );
			$output .= $delimiter;
			$output .= $before . get_the_time( 'd' ) . $after;
		// Month
		} elseif ( is_month() ) {
			$output .= sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) );
			$output .= $delimiter;
			$output .= $before . __( get_the_time( 'F' ), 'us' ) . $after;
		// Year
		} elseif ( is_year() ) {
			$output .= $before . get_the_time( 'Y' ) . $after;
		// Single post type
		} elseif ( is_single() ) {
			// Portfolio Pages
			if ( get_post_type() == 'us_portfolio' AND us_get_option( 'portfolio_breadcrumbs_page' ) != '' ) {
				$portfolio_breadcrumbs_page = get_post( us_get_option( 'portfolio_breadcrumbs_page' ) );
				if ( $portfolio_breadcrumbs_page ) {
					if ( class_exists( 'SitePress' ) AND defined( 'ICL_LANGUAGE_CODE' ) ) {
						$current_page_ID = apply_filters( 'wpml_object_id', $portfolio_breadcrumbs_page->ID, get_post_type( $portfolio_breadcrumbs_page->ID ), TRUE );
						$portfolio_breadcrumbs_page_title = get_the_title( $current_page_ID );
					} else {
						$portfolio_breadcrumbs_page_title = get_the_title( $portfolio_breadcrumbs_page->ID );
					}
					$output .= sprintf( $link, get_permalink( $portfolio_breadcrumbs_page->ID ), $portfolio_breadcrumbs_page_title );
				}

			// Posts
			} elseif ( get_post_type() == 'post' ) {
				$cat = get_the_category();
				$cat = $cat[0];
				$cats = get_category_parents( $cat, TRUE, $delimiter );
				$cats = preg_replace( "#^(.+)$delimiter$#", "$1", $cats );
				$cats = str_replace( '<a', $before . '<a' . $link_attr, $cats );
				$cats = str_replace( '</a>', '</a>' . $after, $cats );
				$output .= $cats;
			// CPT
			} else {
				$post_type_name = get_post_type();
				$taxonomies_found = FALSE;
				$taxonomy_names = get_object_taxonomies( $post_type_name );

				if ( $taxonomy_names != NULL ) {
					foreach ( $taxonomy_names as $taxonomy_name ) {
						$post_taxonomy = get_the_terms( get_the_ID(), $taxonomy_name );
						if ( is_array( $post_taxonomy ) AND count( $post_taxonomy ) > 0 ) {
							$post_taxonomy = $post_taxonomy[0];
							$get_term_parents_args = array(
								'separator' => $delimiter,
								'link'      => TRUE,
								'format'    => 'name',
							);
							$post_taxonomies = get_term_parents_list(
								$post_taxonomy,
								$taxonomy_name,
								$get_term_parents_args );
							$post_taxonomies = preg_replace( "#^(.+)$delimiter$#", "$1", $post_taxonomies );
							$post_taxonomies = str_replace( '<a', $before . '<a' . $link_attr, $post_taxonomies );
							$post_taxonomies = str_replace( '</a>', '</a>' . $after, $post_taxonomies );
							$output .= $post_taxonomies;
							$taxonomies_found = TRUE;
							break;
						}
					}
				}

				if ( ! $taxonomies_found ) {
					$post_type_obj = get_post_type_object( get_post_type() );
					if ( ! empty( $post_type_obj->labels->name ) ) {
						$output .= $before . $post_type_obj->labels->name . $after;
					}
				}

			}

			$output .= $delimiter;
			$output .= $before . get_the_title() . $after;
		// WooCommerce page
		} elseif ( function_exists( 'is_shop' ) and is_shop() ) {
			if ( ! $post->post_parent ) {
				$output .= $before . get_the_title() . $after;
			} elseif ( $post->post_parent ) {
				$parent_id = $post->post_parent;
				$breadcrumbs = array();
				while ( $parent_id ) {
					$page = get_post( $parent_id );
					$breadcrumbs[] = sprintf( $link, get_permalink( $page->ID ), get_the_title( $page->ID ) );
					$parent_id = $page->post_parent;
				}
				$breadcrumbs = array_reverse( $breadcrumbs );
				for ( $i = 0; $i < count( $breadcrumbs ); $i ++ ) {
					$output .= $breadcrumbs[ $i ];
					if ( $i != count( $breadcrumbs ) - 1 ) {
						$output .= $delimiter;
					}
				}
				$output .= $delimiter;
				$output .= $before . get_the_title() . $after;
			}
		// Page without parent
		} elseif ( is_page() AND ! $post->post_parent ) {
			$output .= $before . get_the_title() . $after;
		// Page with parent
		} elseif ( is_page() AND $post->post_parent ) {
			$parent_id = $post->post_parent;
			$breadcrumbs = array();
			$front_page_id = get_option( 'page_on_front' );
			$isFrontParent = FALSE;
			while ( $parent_id ) {
				$page = get_post( $parent_id );
				if ( $front_page_id == $parent_id ) {
					// Remove double home page when home is a parent
					$parent_id = $page->post_parent;
					if ( ! $parent_id ) {
						$isFrontParent = TRUE;
					}
					continue;
				}
				$breadcrumbs[] = sprintf( $link, get_permalink( $page->ID ), get_the_title( $page->ID ) );
				$parent_id = $page->post_parent;
			}
			$breadcrumbs = array_reverse( $breadcrumbs );
			for ( $i = 0; $i < count( $breadcrumbs ); $i ++ ) {
				$output .= $breadcrumbs[ $i ];
				if ( $i != count( $breadcrumbs ) - 1 ) {
					$output .= $delimiter;
				}
			}
			if ( $isFrontParent AND ! count( $breadcrumbs ) ) {
				$delimiter = '';
			}
			$output .= $delimiter;
			$output .= $before . get_the_title() . $after;
		// Any other page
		} elseif ( ! is_single() AND ! is_page() AND ! is_404() ) {
			$post_type_obj = get_post_type_object( get_post_type() );
			if ( isset( $post_type_obj->labels->name ) ) {
				$output .= $before . $post_type_obj->labels->name . $after;
			}
		}
		// Add pagination numbers
		if ( get_query_var( 'paged' ) AND ! ( get_post_type() == 'topic' OR get_post_type() == 'forum' ) ) {
			if ( is_category() OR is_day() OR is_month() OR is_year() OR is_search() OR is_tag() OR is_author() ) {
				$output .= ' (';
			} else {
				$output .= $delimiter;
			}
			$output .= us_translate_x( 'Page', 'post type singular name' ) . ' ' . get_query_var( 'paged' );
			if ( is_category() OR is_day() OR is_month() OR is_year() OR is_search() OR is_tag() OR is_author() ) {
				$output .= ')';
			}
		}
		return $output;
	}
}