<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_separator
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['title'] string Progress Bar title
 * @param $atts           ['count'] int Pregress Bar length in percents: '0' - '100'
 * @param $atts           ['style'] string Style: '1' / '2' / '3' / '4' / '5'
 * @param $atts           ['size'] string Height
 * @param $atts           ['color'] string Color style: 'primary' / 'secondary' / 'heading' / 'text' / 'custom'
 * @param $atts           ['bar_color'] string
 * @param $atts           ['hide_count'] bool Hide progress value counter?
 */

$atts = us_shortcode_atts( $atts, 'us_progbar' );

$classes = '';

$classes .= ' style_' . $atts['style'];
$classes .= ' color_' . $atts['color'];
if ( $atts['hide_count'] ) {
	$classes .= ' hide_count';
}
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

if ( $atts['title'] != '' ) {
	$title_tag = '<span class="w-progbar-title-text">' . $atts['title'] . '</span>';
} else {
	$title_tag = '';
	$classes .= ' title_none';
}

$atts['count'] = max( 0, min( 100, $atts['count'] ) );

$bar_inline_css = us_prepare_inline_css( array(
	'height' => $atts['size'],
	'width' => $atts['count'] . '%',
	'background-color' => $atts['bar_color'],
));

// Output the element
$output = '<div class="w-progbar' . $classes . ' initial" data-count="' . $atts['count'] . '">';
$output .= '<h6 class="w-progbar-title">';
$output .= $title_tag;
$output .= '<span class="w-progbar-title-count">' . $atts['count'] . '%</span>';
$output .= '</h6>';
$output .= '<div class="w-progbar-bar"><div class="w-progbar-bar-h"' . $bar_inline_css . '>';
$output .= '<span class="w-progbar-bar-count">' . $atts['count'] . '%</span>';
$output .= '</div></div></div>';

echo $output;
