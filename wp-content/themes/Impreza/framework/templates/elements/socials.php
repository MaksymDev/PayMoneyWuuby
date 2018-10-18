<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output socials element
 *
 * @var $style          string
 * @var $hover          string
 * @var $color          string
 * @var $items          array
 * @var $size           int
 * @var $size_tablets   int
 * @var $size_mobiles   int
 * @var $design_options array
 * @var $classes        string
 * @var $id             string
 */

$classes = isset( $classes ) ? $classes : '';
$classes .= ' style_' . $style;
$classes .= ' hover_' . $hover;
$classes .= ' color_' . $color;
$classes .= ' shape_' . $shape;

$output = '<div class="w-socials' . $classes . '"><div class="w-socials-list">';

$social_links = us_config( 'social_links' );

foreach ( $items as $index => $item ) {
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

	$output .= '<div class="w-socials-item ' . $item['type'] . '">';
	$output .= '<a class="w-socials-item-link"' . $social_target . ' href="' . $social_url . '" title="' . esc_attr( $social_title ) . '" rel="nofollow"';
	if ( $color == 'brand' ) {
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
