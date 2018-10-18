<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_btn
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['text'] string Button label
 * @param $atts           ['link'] string Button link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:_blank|rel:nofollow'
 * @param $atts           ['color'] string Button color: 'primary' / 'secondary' / 'light' / 'contrast' / 'black' / 'white' / 'custom'
 * @param $atts           ['bg_color'] string Button Background Color
 * @param $atts           ['text_color'] string Button Text Color
 * @param $atts           ['style'] string Button style: 'raised' / 'flat'
 * @param $atts           ['icon'] string Button icon
 * @param $atts           ['iconpos'] string Icon position: 'left' / 'right'
 * @param $atts           ['size'] string Button size
 * @param $atts           ['align'] string Button alignment: 'left' / 'center' / 'right'
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'us_btn' );

$classes = $icon_html = '';

$classes .= ' style_' . $atts['style'];
$classes .= ' color_' . $atts['color'];
if ( $atts['el_class'] != '' ) {
	$classes .= ' ' . $atts['el_class'];
}

if ( $atts['icon'] != '' ) {
	$icon_html = us_prepare_icon_tag( $atts['icon'] );
	$classes .= ' icon_at' . $atts['iconpos'];
} else {
	$classes .= ' icon_none';
}

$link = us_vc_build_link( $atts['link'] );
$_link_attr = ( $link['target'] == '_blank' ) ? ' target="_blank"' : '';
$_link_attr .= ( $link['rel'] == 'nofollow' ) ? ' rel="nofollow"' : '';
$_link_attr .= empty( $link['title'] ) ? '' : ( ' title="' . esc_attr( $link['title'] ) . '"' );

$inline_css = us_prepare_inline_css( array(
	'font-size' => ( $atts['size'] == '15px' ) ? '' : $atts['size'],
	'background-color' => $atts['bg_color'],
	'color' => $atts['text_color'],
));

// Output the element
$output = '<div class="w-btn-wrapper align_' . $atts['align'] . '">';
$output .= '<a class="w-btn' . $classes . '" href="' . esc_url( $link['url'] ) . '"';
$output .= $_link_attr . $inline_css;
$output .= '>';
$output .= $icon_html;
$output .= '<span class="w-btn-label">' . strip_tags( $atts['text'], '<br>' ) . '</span>';
$output .= '</a></div>';

echo $output;
