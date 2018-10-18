<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: vc_tta_section
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
 * @param $atts           ['title'] string Section title
 * @param $atts           ['tab_id'] string Section slug
 * @param $atts           ['icon'] string Icon
 * @param $atts           ['i_position'] string Icon position: 'left' / 'right'
 * @param $atts           ['active'] bool Tab is opened when page loads
 * @param $atts           ['indents'] string Indents type: '' / 'none'
 * @param $atts           ['bg_color'] string Background color
 * @param $atts           ['text_color'] string Text color
 * @param $atts           ['c_position'] string Control position (inherited from wrapping vc_tta_tabs shortcode): 'left' / 'right'
 * @param $atts           ['title_tag'] string Title HTML tag (inherited from wrapping vc_tta_tabs shortcode): 'div' / 'h2'/ 'h3'/ 'h4'/ 'h5'/ 'h6'/ 'p'
 * @param $atts           ['title_size'] string Title Size
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'vc_tta_section' );

$classes = $tab_id = $item_tag_href = '';

global $us_tabs_atts, $us_tab_index;
// Tab indexes start from 1
$us_tab_index = isset( $us_tab_index ) ? ( $us_tab_index + 1 ) : 1;

// We could overload some of the atts at vc_tabs implementation, so apply them in here as well
if ( isset( $us_tab_index ) AND isset( $us_tabs_atts[$us_tab_index - 1] ) ) {
	$atts = array_merge( $atts, $us_tabs_atts[$us_tab_index - 1] );
}

if ( $atts['icon'] ) {
	$classes .= ' with_icon';
}
if ( $atts['indents'] == 'none' ) {
	$classes .= ' no_indents';
}
if ( $atts['active'] ) {
	$classes .= ' active';
}
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

$item_tag = 'div';
$tab_id = ( ! empty( $atts['tab_id'] ) ) ? ' id="' . $atts['tab_id'] . '"' : '';
if ( $tab_id != '' ) {
	$item_tag = 'a';
	$item_tag_href = ' href="#' . $atts['tab_id'] . '"';
}

$inline_css = us_prepare_inline_css( array(
	'background-color' => $atts['bg_color'],
	'color' => $atts['text_color'],
));
if ( ! empty( $inline_css ) ) {
	$classes .= ' color_custom';
}
$section_header_inline_css = us_prepare_inline_css( array(
	'font-size' => $atts['title_size'],
));

// Output the element
$output = '<div class="w-tabs-section' . $classes . '"' . $tab_id . $inline_css . '>';
$output .= '<' . $item_tag . $item_tag_href . ' class="w-tabs-section-header"' . $section_header_inline_css . '><div class="w-tabs-section-header-h">';
if ( $atts['c_position'] == 'left' ) {
	$output .= '<div class="w-tabs-section-control"></div>';
}
if ( $atts['icon'] AND $atts['i_position'] == 'left' ) {
	$output .= us_prepare_icon_tag( $atts['icon'] );
}
$output .= '<' . $atts['title_tag'] . ' class="w-tabs-section-title">' . $atts['title'] . '</' . $atts['title_tag'] . '>';
if ( $atts['icon'] AND $atts['i_position'] == 'right' ) {
	$output .= us_prepare_icon_tag( $atts['icon'] );
}
if ( $atts['c_position'] == 'right' ) {
	$output .= '<div class="w-tabs-section-control"></div>';
}
$output .= '</div></' . $item_tag . '>';
$output .= '<div class="w-tabs-section-content"><div class="w-tabs-section-content-h i-cf">' . do_shortcode( $content ) . '</div></div>';
$output .= '</div>';

echo $output;
