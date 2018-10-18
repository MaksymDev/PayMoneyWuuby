<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: vc_column
 *
 * Overloaded by UpSolution custom implementation.
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['width'] string Width in format: 1/2 (is set by WPBakery Page Builder renderer)
 * @param $atts           ['text_color'] string Text color
 * @param $atts           ['animate'] string Animation type: '' / 'fade' / 'afc' / 'afl' / 'afr' / 'afb' / 'aft' / 'hfc' / 'wfc'
 * @param $atts           ['animate_delay'] float Animation delay (in seconds)
 * @param $atts           ['el_id'] string element ID
 * @param $atts           ['el_class'] string Additional class
 * @param $atts           ['offset'] string WPBakery Page Builder classes for responsive behaviour
 * @param $atts           ['css'] string Custom CSS
 */

// $shorcode_base may be: 'vc_column' / 'vc_column_inner'
$atts = us_shortcode_atts( $atts, $shortcode_base );

$inner_classes = $el_id_string = $link_html = '';

if ( function_exists( 'wpb_translateColumnWidthToSpan' ) ) {
	$width = wpb_translateColumnWidthToSpan( $atts['width'] );

} elseif ( function_exists( 'us_wpb_translateColumnWidthToSpan' ) ) {
	$width = us_wpb_translateColumnWidthToSpan( $atts['width'] );
}

if ( function_exists( 'vc_column_offset_class_merge' ) ) {
	$width = vc_column_offset_class_merge( $atts['offset'], $width );

} elseif ( function_exists( 'us_vc_column_offset_class_merge' ) ) {
	$width = us_vc_column_offset_class_merge( $atts['offset'], $width );
}
$classes = $width . ' wpb_column vc_column_container';

if ( function_exists( 'vc_shortcode_custom_css_has_property' ) AND vc_shortcode_custom_css_has_property(
		$atts['css'], array(
		'border',
		'background',
	)
	)
) {
	$classes .= ' has-fill';
} elseif ( function_exists( 'us_vc_shortcode_custom_css_has_property' ) AND us_vc_shortcode_custom_css_has_property(
		$atts['css'], array(
		'border',
		'background',
	)
	)
) {
	$classes .= ' has-fill';
}

if ( ! empty( $atts['animate'] ) ) {
	$classes .= ' animate_' . $atts['animate'];
	if ( ! empty( $atts['animate_delay'] ) ) {
		$atts['animate_delay'] = floatval( $atts['animate_delay'] );
		$classes .= ' d' . intval( $atts['animate_delay'] * 5 );
	}
}
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

if ( ! empty( $atts['css'] ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$inner_classes .= ' ' . vc_shortcode_custom_css_class( $atts['css'], ' ' );
}
if ( ! empty( $atts['text_color'] ) ) {
	$inner_classes .= ' color_custom';
}

if ( $atts['el_id'] != '' ) {
	$el_id_string = ' id="' . esc_attr( $atts['el_id'] ) . '"';
}

$link = us_vc_build_link( $atts['link'] );
if ( ! empty( $link['url'] ) ) {
	$classes .= ' has-link';
	$_link_meta = ( $link['target'] == '_blank' ) ? ' target="_blank"' : '';
	$_link_meta .= ( $link['rel'] == 'nofollow' ) ? ' rel="nofollow"' : '';
	$_link_meta .= empty( $link['title'] ) ? '' : ( ' title="' . esc_attr( $link['title'] ) . '"' );
	$link_html = '<a class="vc_column-link smooth-scroll" href="' . esc_url( $link['url'] ) . '"' . $_link_meta . '></a>';
}

$inline_css = us_prepare_inline_css( array(
	'color' => $atts['text_color'],
));

// Output the element
$output = '<div class="' . $classes . '"' . $el_id_string . '>';
$output .= '<div class="vc_column-inner' . $inner_classes . '"' . $inline_css . '>';
$output .= '<div class="wpb_wrapper">' . do_shortcode( $content ) . '</div>';
$output .= $link_html;
$output .= '</div></div>';

echo $output;
