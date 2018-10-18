<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Taxonomy element
 *
 * @var $taxonomy_name string Taxonomy name
 * @var $link string Link type: 'post' / 'archive' / 'custom' / 'none'
 * @var $custom_link array
 * @var $color string Custom color
 * @var $icon string Icon name
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

if ( empty( $taxonomy_name ) OR ! taxonomy_exists( $taxonomy_name ) OR ! is_object_in_taxonomy( get_post_type(), $taxonomy_name ) ) {
	return FALSE;
}
$taxonomies = get_the_terms( get_the_ID(), $taxonomy_name );
if ( ! is_array( $taxonomies ) OR count( $taxonomies ) == 0 ) {
	return FALSE;
}

$classes = isset( $classes ) ? $classes : '';
$classes .= ' style_' . $style;

// Generate anchor semantics
$link_atts = array(
	'href' => '',
	'meta' => '',
);
if ( $link === 'post' ) {
	$link_atts = us_grid_get_post_link( $link_atts );
} elseif ( $link === 'custom' ) {
	$link_atts = us_grid_get_custom_link( $custom_link, $link_atts );
}

// Output the element
$output = '<div class="w-grid-item-elm' . $classes . '">';
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon ) . ' ';
}

$i = 1;
foreach ( $taxonomies as $taxonomy ) {
	if ( $link === 'archive' ) {
		$link_atts['href'] = get_term_link( $taxonomy );
		// Output "rel" attribute for Posts tags
		if ( $taxonomy_name == 'post_tag' ) {
			$link_atts['meta'] .= ' rel="tag"';
		}
	}
	if ( ! empty( $link_atts['href'] ) ) {
		$output .= '<a href="' . esc_url( $link_atts['href'] ) . '"' . $link_atts['meta'] . '>';
	}
	$output .= $taxonomy->name;
	if ( ! empty( $link_atts['href'] ) ) {
		$output .= '</a>';
	}
	// Output comma after anchor except the last one
	if ( $style != 'badge' AND $i != count( $taxonomies ) ) {
		$output .= $separator;
	}
	$i++;
}

$output .= '</div>';

echo $output;
