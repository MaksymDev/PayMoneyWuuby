<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_message
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['color'] string Message box color: 'info' / 'attention' / 'success' / 'error' / 'custom'
 * @param $atts           ['bg_color'] string Background color
 * @param $atts           ['text_color'] string Text color
 * @param $atts           ['icon'] string Icon
 * @param $atts           ['closing'] bool Enable closing?
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'us_message' );

$classes = $icon_html = $closer_html = '';

$classes .= ' color_' . $atts['color'];
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

if ( ! empty( $atts['icon'] ) ) {
	$icon_html = '<div class="w-message-icon">' . us_prepare_icon_tag( $atts['icon'] ) . '</div>';
	$classes .= ' with_icon';
}

if ( $atts['closing'] ) {
	$classes .= ' with_close';
	$closer_html = '<a class="w-message-close" href="javascript:void(0)" title="' . us_translate( 'Close' ) . '"></a>';
}

$inline_css = us_prepare_inline_css( array(
	'background-color' => $atts['bg_color'],
	'color' => $atts['text_color'],
));

// Output the element
$output = '<div class="w-message' . $classes . '"' . $inline_css . '>' . $icon_html;
$output .= '<div class="w-message-body"><p>' . do_shortcode( $content ) . '</p></div>' . $closer_html . '</div>';

echo $output;
