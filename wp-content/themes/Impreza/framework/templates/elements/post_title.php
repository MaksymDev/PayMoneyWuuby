<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Title element
 *
 * @var $link string Link type: 'post' / 'custom' / 'none'
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

if ( get_post_type() == 'product' ) {
	$classes .= ' woocommerce-loop-product__title'; // needed for adding to cart 
} else {
	$classes .= ' entry-title'; // needed for Google structured data
}

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
$output = '<' . $tag . ' class="w-grid-item-elm' . $classes . '">';
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon ) . ' ';
}
if ( ! empty( $link_atts['href'] ) ) {
	$output .= '<a href="' . esc_url( $link_atts['href'] ) . '"' . $link_atts['meta'] . '>';
}

$output .= get_the_title();

if ( ! empty( $link_atts['href'] ) ) {
	$output .= '</a>';
}
$output .= '</' . $tag . '>';

echo $output;
