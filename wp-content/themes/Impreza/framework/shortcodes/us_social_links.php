<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_social_links
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['items'] array Social Link
 * @param $atts           ['align'] string Icons alignment: 'left' / 'center' / 'right'
 * @param $atts           ['style'] string Icons Style: 'default' / 'outlined' / 'solid' / 'colored'
 * @param $atts           ['hover'] string Hover Style: 'fade' / 'slide' / 'none'
 * @param $atts           ['color'] string Icons Color: 'brand' / 'text' / 'link'
 * @param $atts           ['shape'] string Icons Shape: 'square' / 'rounded' / 'circle'
 * @param $atts           ['size'] string Icons size
 * @param $atts           ['gap'] string Gap between Icons
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'us_social_links' );

$classes = ' align_' . $atts['align'];
$classes .= ' style_' . $atts['style'];
$classes .= ' hover_' . $atts['hover'];
$classes .= ' color_' . $atts['color'];
$classes .= ' shape_' . $atts['shape'];
if ( $atts['el_class'] != '' ) {
	$classes .= ' ' . $atts['el_class'];
}

$list_css = us_prepare_inline_css( array(
	'font-size' => $atts['size'],
	'margin' => '-' . $atts['gap'],
));
$item_css = us_prepare_inline_css( array(
	'margin' => $atts['gap'],
));

// Output the element
$output = '<div class="w-socials' . $classes . '">';
$output .= '<div class="w-socials-list"' . $list_css . '>';

if ( empty( $atts['items'] ) ) {
	$atts['items'] = array();
} else {
	$atts['items'] = json_decode( urldecode( $atts['items'] ), TRUE );
	if ( ! is_array( $atts['items'] ) ) {
		$atts['items'] = array();
	}
}

$social_links = us_config( 'social_links' );

foreach ( $atts['items'] as $index => $item ) {
	$social_title = ( isset( $social_links[$item['type']] ) ) ? $social_links[$item['type']] : $item['type'];
	$social_url = ( isset( $item['url'] ) ) ? $item['url'] : '';
	$social_target = $social_icon = $social_custom_bg = $social_custom_color = '';

	// Custom type
	if ( $item['type'] == 'custom' ) {
		$social_title = ( ! empty( $item['title'] ) ) ? $item['title'] : __( 'Custom Icon', 'us' );
		$social_url = esc_url( $social_url );
		$social_target = ' target="_blank"';
		if ( isset( $item['icon'] ) ) {
			$social_icon = us_prepare_icon_tag( $item['icon'] );
		}
		$social_custom_bg = us_prepare_inline_css( array(
			'background-color' => $item['color'],
		));
		$social_custom_color = us_prepare_inline_css( array(
			'color' => $item['color'],
		));
	// Email type
	} elseif ( $item['type'] == 'email' ) {
		if ( filter_var( $social_url, FILTER_VALIDATE_EMAIL ) ) {
			$social_url = 'mailto:' . $social_url;
		}
	// Skype type
	} elseif ( $item['type'] == 'skype' ) {
		if ( strpos( $social_url, ':' ) === FALSE ) {
			$social_url = 'skype:' . esc_attr( $social_url );
		}
	// All others types
	} else {
		$social_url = esc_url( $social_url );
		$social_target = ' target="_blank"';
	}

	$output .= '<div class="w-socials-item ' . $item['type'] . '"' . $item_css . '>';
	$output .= '<a class="w-socials-item-link"' . $social_target . ' href="' . $social_url . '" aria-label="' . esc_attr( $social_title ) . '" rel="nofollow"';
	if ( $atts['color'] == 'brand' ) {
		$output .= $social_custom_color;
	}
	$output .= '>';
	$output .= '<span class="w-socials-item-link-hover"' . $social_custom_bg . '></span>';
	$output .= $social_icon;
	$output .= '</a>';
	$output .= '<div class="w-socials-item-popup"><span>' . strip_tags( $social_title ) . '</span></div>';
	$output .= '</div>';
}

$output .= '</div></div>';

echo $output;
