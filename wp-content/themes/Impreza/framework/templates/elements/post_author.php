<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Author element
 *
 * @var $link string Link type: 'post' / 'author' / 'custom' / 'none'
 * @var $custom_link array
 * @var $tag string 'h1' / 'h2' / 'h3' / 'h4' / 'h5' / 'h6' / 'p' / 'div'
 * @var $color string Custom color
 * @var $icon string Icon name
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

$classes = isset( $classes ) ? $classes : '';
$classes .= ' vcard author'; // needed for Google structured data

// Generate anchor semantics
$link_atts = array(
	'href' => '',
	'meta' => '',
);
if ( $link === 'author_page' ) {
	$link_atts['href'] = get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'user_nicename' ) );
} elseif ( $link === 'author_website' ) {
	$link_atts['href'] = ( get_the_author_meta('url') ) ? get_the_author_meta('url') : '';
	$link_atts['meta'] = ' target="_blank" rel="nofollow"';
} elseif ( $link === 'post' ) {
	$link_atts = us_grid_get_post_link( $link_atts );
} elseif ( $link === 'custom' ) {
	$link_atts = us_grid_get_custom_link( $custom_link, $link_atts );
}

// Output the element
$output = '<div class="w-grid-item-elm' . $classes . '">';
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon ) . ' ';
}
if ( ! empty( $link_atts['href'] ) ) {
	$output .= '<a class="fn" href="' . esc_url( $link_atts['href'] ) . '"' . $link_atts['meta'] . '>';
}

$output .= get_the_author();

if ( ! empty( $link_atts['href'] ) ) {
	$output .= '</a>';
}
$output .= '</div>';

echo $output;
