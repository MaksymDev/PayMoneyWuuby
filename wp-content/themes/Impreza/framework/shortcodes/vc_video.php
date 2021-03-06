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
 * @param $atts           ['link'] string Video link
 * @param $atts           ['ratio'] string Ratio: '16x9' / '4x3' / '3x2' / '1x1'
 * @param $atts           ['max_width'] string Max width in pixels
 * @param $atts           ['align'] string Video alignment: 'left' / 'center' / 'right'
 * @param $atts           ['css'] string Extra css
 * @param $atts           ['el_id'] string element ID
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'vc_video' );

$classes = $inline_css = $el_id_string = $video_title = $embed_html = '';

if ( ! empty( $atts['ratio'] ) ) {
	$classes .= ' ratio_' . $atts['ratio'];
}

if ( ! empty( $atts['max_width'] ) ) {
	$classes .= ' align_' . $atts['align'];
	$inline_css = us_prepare_inline_css( array(
		'max-width' => $atts['max_width'] . 'px',
	));
}
if ( ! empty( $atts['css'] ) AND function_exists( 'vc_shortcode_custom_css_class' ) ) {
	$classes .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
}
if ( $atts['el_class'] != '' ) {
	$classes .= ' ' . $atts['el_class'];
}

if ( $atts['el_id'] != '' ) {
	$el_id_string = ' id="' . esc_attr( $atts['el_id'] ) . '"';
}

if ( $atts['video_related'] == FALSE ) {
	$video_related = '?rel=0';
} else {
	$video_related = '';
}

foreach ( us_config( 'embeds' ) as $provider => $embed ) {
	if ( $embed['type'] != 'video' OR ! preg_match( $embed['regex'], $atts['link'], $matches ) ) {
		continue;
	}

	if ( $atts['video_title'] == FALSE AND $provider == 'youtube' ) {
		if ( $atts['video_related'] == FALSE ) {
			$video_title = '&';
		} else {
			$video_title = '?';
		}
		$video_title .= 'showinfo=0';
	} elseif ( $atts['video_title'] == FALSE AND $provider == 'vimeo' ) {
		$video_title = '&title=0&byline=0';
	}
	$video_id = $matches[$embed['match_index']];
	$embed_html = str_replace( '<id>', $matches[$embed['match_index']], $embed['html'] );
	$embed_html = str_replace( '<related>', $video_related, $embed_html );
	$embed_html = str_replace( '<title>', $video_title, $embed_html );
	break;
}

if ( empty( $embed_html ) ) {
	// Using the default WordPress way
	global $wp_embed;
	$embed_html = $wp_embed->run_shortcode( '[embed]' . $atts['link'] . '[/embed]' );
}

$output = '<div class="w-video' . $classes . '"' . $inline_css . $el_id_string . '><div class="w-video-h">' . $embed_html . '</div></div>';

echo $output;
