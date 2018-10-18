<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_scroller
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['speed'] string Scroll Speed
 * @param $atts           ['dots'] bool Show navigation dots?
 * @param $atts           ['dots_pos'] string Dots Position
 * @param $atts           ['dots_size'] string Dots Size
 * @param $atts           ['dots_color'] string Dots color value
 * @param $atts           ['disable_width'] string Dots color value
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'us_scroller' );

$classes = $data_atts = '';

$classes .= ' style_' . $atts['dots_style'] . ' pos_' . $atts['dots_pos'];
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

if ( $atts['speed'] != '' ) {
	$data_atts = ' data-speed="' . $atts['speed'] . '"';
}
if ( $atts['disable_width'] != '' ) {
	$data_atts .= ' data-disablewidth="' . intval( $atts['disable_width'] ) . '"';
}

$dot_inline_css = us_prepare_inline_css( array(
	'font-size' => $atts['dots_size'],
	'box-shadow' => empty( $atts['dots_color'] ) ? '' : '0 0 0 2px ' . $atts['dots_color'],
	'background-color' => $atts['dots_color'],
));

// Output the element
$output = '<div class="w-scroller' . $classes . '"' . $data_atts . ' aria-hidden="true">';
if ( $atts['dots'] ) {
	$output .= '<div class="w-scroller-dots">';
	$output .= '<a href="javascript:void(0);" class="w-scroller-dot"><span' . $dot_inline_css . '></span></a>';
	$output .= '</div>';
}
$output .= '</div>';

echo $output;
