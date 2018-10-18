<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Date element
 *
 * @var $type string Date type: 'published' / 'modified'
 * @var $format string Date format selected from preset
 * @var $format_custom string Date custom format
 * @var $icon string Icon name
 * @var $tag string 'h1' / 'h2' / 'h3' / 'h4' / 'h5' / 'h6' / 'p' / 'div'
 * @var $color string Custom color
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

$classes = isset( $classes ) ? $classes : '';

// Classes for Google structured data
$classes .= ' entry-date';
if ( $type == 'modified' ) {
	$classes .= ' updated';
} else {
	$classes .= ' published';
}

$tag = 'time';

// Generate date format
if ( $format == 'default' ) {
	$format = get_option( 'date_format' );
} elseif ( $format == 'custom' ) {
	$format = $format_custom;
}
$date = ( $type == 'modified' ) ? get_the_modified_date( $format ) : get_the_date( $format );

$output = '<' . $tag . ' class="w-grid-item-elm' . $classes . '"';
// Output datetime attribute for <time> tag
if ( $tag == 'time' ) {
	$output .= ' datetime="' . get_the_date( 'Y-m-d H:i:s' ) . '"';
}
$output .= '>';
// Output icon
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon ) . ' ';
}
// Output date value
$output .= $date;
$output .= '</' . $tag . '>';

echo $output;
