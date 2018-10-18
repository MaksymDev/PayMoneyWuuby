<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_counter
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['initial'] mixed The initial number value (integer or float)
 * @param $atts           ['target'] mixed The target number value (integer or float)
 * @param $atts           ['color'] string number color: 'text' / 'primary' / 'secondary' / 'custom'
 * @param $atts           ['custom_color'] string Custom color value
 * @param $atts           ['size'] string Number size: 'small' / 'medium' / 'large'
 * @param $atts           ['title'] string Title for the counter
 * @param $atts           ['title_tag'] string Title HTML tag: 'div' / 'h2'/ 'h3'/ 'h4'/ 'h5'/ 'h6'/ 'p'
 * @param $atts           ['title_size'] string Title Size
 * @param $atts           ['align'] string Alignment
 * @param $atts           ['prefix'] string Number prefix
 * @param $atts           ['suffix'] string Number suffix
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'us_counter' );

$classes = $elm_atts = $number_inline_css = '';

$classes .= ' size_' . $atts['size'];
$classes .= ' color_' . $atts['color'];
$classes .= ' align_' . $atts['align'];
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

$elm_atts .= ' data-initial="' . $atts['initial'] . '"';
$elm_atts .= ' data-target="' . $atts['target'] . '"';
$elm_atts .= ' data-prefix="' . $atts['prefix'] . '"';
$elm_atts .= ' data-suffix="' . $atts['suffix'] . '"';

// Generate inline styles for Number
if ( $atts['font'] != 'body' ) {
	$number_inline_css .= us_get_font_css( $atts['font'] );
}
if ( strpos( $atts['text_styles'], 'bold' ) !== FALSE ) {
	$number_inline_css .= 'font-weight:bold;';
}
if ( strpos( $atts['text_styles'], 'uppercase' ) !== FALSE ) {
	$number_inline_css .= 'text-transform:uppercase;';
}
$number_inline_css .= us_prepare_inline_css(
	array(
		'font-size' => $atts['size'],
		'color' => $atts['custom_color'],
	),
	$style_attr = FALSE
);

// Generate inline styles for Title
$title_inline_css = us_prepare_inline_css(
	array(
		'font-size' => $atts['title_size'],
	)
);

// Output the element
$output = '<div class="w-counter' . $classes . '"' . $elm_atts . '><div class="w-counter-h">';
$output .= '<div class="w-counter-number"';
if ( ! empty ( $number_inline_css ) ) {
	$output .= ' style="' . esc_attr( $number_inline_css ) . '"';
}
$output .= '>';
$output .= $atts['prefix'] . $atts['initial'] . $atts['suffix'];
$output .= '</div>';
if ( ! empty ( $atts['title'] ) ) {
	$output .= '<' . $atts['title_tag'] .' class="w-counter-title"' . $title_inline_css . '>';
	$output .= $atts['title'];
	$output .= '</' . $atts['title_tag'] . '>';
}
$output .= '</div></div>';

echo $output;
