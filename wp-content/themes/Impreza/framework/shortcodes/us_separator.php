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
 * @param $atts           ['type'] string Separator type: 'default' / 'fullwidth' / 'short' / 'invisible'
 * @param $atts           ['size'] string Separator size: 'small' / 'medium' / 'large' / 'huge'
 * @param $atts           ['thick'] string Line thickness: '1' / '2' / '3' / '4' / '5'
 * @param $atts           ['style'] string Line style: 'solid' / 'dashed' / 'dotted' / 'double'
 * @param $atts           ['color'] string Color style: 'border' / 'primary' / 'secondary' / 'custom'
 * @param $atts           ['bdcolor'] string Border color value
 * @param $atts           ['icon'] string Icon
 * @param $atts           ['text'] string Title
 * @param $atts           ['title_tag'] string Title HTML tag: 'h1' / 'h2'/ 'h3'/ 'h4'/ 'h5'/ 'h6'/ 'div'
 * @param $atts           ['title_size'] string Font Size
 * @param $atts           ['align'] string Alignment
 * @param $atts           ['link'] string Link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:_blank|rel:nofollow'
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'us_separator' );

$classes = $inner_html = $inline_css = $link_opener = $link_closer = '';

$classes .= ' type_' . $atts['type'];
$classes .= ' size_' . $atts['size'];
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

// Generate link semantics
$link = us_vc_build_link( $atts['link'] );
if ( ! empty( $link['url'] ) ) {
	$link_target = ( $link['target'] == '_blank' ) ? ' target="_blank"' : '';
	$link_rel = ( $link['rel'] == 'nofollow' ) ? ' rel="nofollow"' : '';
	$link_title = empty( $link['title'] ) ? '' : ( ' title="' . esc_attr( $link['title'] ) . '"' );
	$link_opener = '<a href="' . esc_url( $link['url'] ) . '"' . $link_target . $link_rel . $link_title . '>';
	$link_closer = '</a>';
}

// Generate separator icon and title
if ( $atts['type'] != 'invisible' ) {
	$classes .= ' thick_' . $atts['thick'];
	$classes .= ' style_' . $atts['style'];
	$classes .= ' color_' . $atts['color'];
	$classes .= ' align_' . $atts['align'];

	if ( ! empty( $atts['text'] ) ) {
		$inner_html .= '<' . $atts['title_tag'] . ' class="w-separator-text">';
		$inner_html .= $link_opener;
		$inner_html .= us_prepare_icon_tag( $atts['icon'] );
		$inner_html .= '<span>' . $atts['text'] . '</span>';
		$inner_html .= $link_closer;
		$inner_html .= '</' . $atts['title_tag'] . '>';
	} else {
		$inner_html .= us_prepare_icon_tag( $atts['icon'] );
	}

	if ( $inner_html != '' ) {
		$classes .= ' with_content';
	}

	$inline_css = us_prepare_inline_css(
		array(
			'border-color' => $atts['bdcolor'],
			'color' => $atts['bdcolor'],
			'font-size' => $atts['title_size'],
		)
	);
}

// Output the element
$output = '<div class="w-separator' . $classes . '"' . $inline_css . '>';
if ( $atts['type'] != 'invisible' ) {
	$output .= '<div class="w-separator-h">' . $inner_html . '</div>';
}
$output .= '</div>';

echo $output;
