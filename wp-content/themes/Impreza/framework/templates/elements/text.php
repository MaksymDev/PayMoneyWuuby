<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output text element
 *
 * @var $text           string
 * @var $size           int Text size
 * @var $size_tablets   int Text size for tablets
 * @var $size_mobiles   int Text size for mobiles
 * @var $link           string Link
 * @var $icon           string FontAwesome or Material icon
 * @var $font           string Font Source
 * @var $color          string Custom text color
 * @var $design_options array
 * @var $classes        string
 * @var $id             string
 */

$classes = isset( $classes ) ? $classes : '';

$output = '<div class="w-text' . $classes . '"><div class="w-text-h">';
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon );
}
$link_atts = usof_get_link_atts( $link );
if ( ! empty( $link_atts['href'] ) ) {
	$output .= '<a class="w-text-value" href="' . esc_url( $link_atts['href'] ) . '"';
	if ( ! empty( $link_atts['target'] ) ) {
		$output .= ' target="' . esc_attr( $link_atts['target'] ) . '"';
	}
	$output .= '>';
} else {
	$output .= '<span class="w-text-value">';
}
$output .= strip_tags( $text, '<br>' );
if ( ! empty( $link_atts['href'] ) ) {
	$output .= '</a>';
} else {
	$output .= '</span>';
}
$output .= '</div></div>';

echo $output;
