<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_iconbox
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['icon'] string Icon
 * @param $atts           ['style'] string Icon style: 'default' / 'circle' / 'outlined'
 * @param $atts           ['color'] string Icon color: 'primary' / 'secondary' / 'light' / 'contrast' / 'custom'
 * @param $atts           ['icon_color'] string Icon color value
 * @param $atts           ['bg_color'] string Icon circle color
 * @param $atts           ['iconpos'] string Icon position: 'top' / 'left'
 * @param $atts           ['size'] string Icon size in pixels
 * @param $atts           ['img'] int Icon image (from WordPress media)
 * @param $atts           ['title'] string Title
 * @param $atts           ['title_tag'] string Title HTML tag: 'div' / 'h2'/ 'h3'/ 'h4'/ 'h5'/ 'h6'/ 'p'
 * @param $atts           ['title_size'] string Title Size
 * @param $atts           ['link'] string Link in a serialized format: 'url:http%3A%2F%2Fwordpress.org|title:WP%20Website|target:_blank|rel:nofollow'
 * @param $atts           ['alignment'] string Alignment of the whole element
 * @param $atts           ['el_class'] string Extra class name
 */
$atts = us_shortcode_atts( $atts, 'us_iconbox' );

$classes = $icon_html = $link_opener = $link_closer = '';

$classes .= ' iconpos_' . $atts['iconpos'];
$classes .= ' style_' . $atts['style'];
$classes .= ' color_' . $atts['color'];
$classes .= ' align_' . $atts['alignment'];
if ( $atts['title'] == '' ) {
	$classes .= ' no_title';
}
if ( $content == '' ) {
	$classes .= ' no_text';
}
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

// Use image instead icon, if set
if ( $atts['img'] != '' ) {
	$classes .= ' icontype_img';
	if ( is_numeric( $atts['img'] ) ) {
		$img = wp_get_attachment_image_src( intval( $atts['img'] ), 'full' );
		if ( $img !== FALSE ) {
			$icon_html = '<img src="' . $img[0] . '"';
			if ( ! preg_match( '~\.svg$~', $img[0] ) ) {
				$icon_html .= ' width="' . $img[1] . '" height="' . $img[2] . '"';
			}
			$icon_html .= ' alt="' . $atts['title'] . '">';
		}
	} else {
		// Direct link to image is set in the shortcode attribute
		$icon_html = '<img src="' . $atts['img'] . '" alt="' . $atts['title'] . '">';
	}
} elseif ( $atts['icon'] != '' ) {
	$icon_html = us_prepare_icon_tag( $atts['icon'] );
}

$link = us_vc_build_link( $atts['link'] );
if ( ! empty( $link['url'] ) ) {
	$_link_attr = ' href="' . esc_url( $link['url'] ) . '"';
	$_link_attr .= ( $link['target'] == '_blank' ) ? ' target="_blank"' : '';
	$_link_attr .= ( $link['rel'] == 'nofollow' ) ? ' rel="nofollow"' : '';
	if ( ! empty( $link['title'] ) ) {
		$_link_attr .= ' title="' . esc_attr( $link['title'] ) . '"';
	} elseif ( $atts['title'] != '' ) {
		$_link_attr .= ' aria-label="' . esc_attr( $atts['title'] ) . '"';
	} else {
		$_link_attr .= ' aria-hidden="true"';
	}
	$link_opener = '<a class="w-iconbox-link"' . $_link_attr . '>';
	$link_closer = '</a>';
}

$icon_inline_css = us_prepare_inline_css(
	array(
		'font-size' => ( $atts['size'] == '36px' ) ? '' : $atts['size'],
		'box-shadow' => empty( $atts['bg_color'] ) ? '' : '0 0 0 2px ' . $atts['bg_color'] . ' inset',
		'background-color' => $atts['bg_color'],
		'color' => $atts['icon_color'],
	)
);

// Output the element
$output = '<div class="w-iconbox' . $classes . '">';
if ( in_array( $atts['iconpos'], array( 'top', 'left' ) ) ) {
	$output .= $link_opener;
	$output .= '<div class="w-iconbox-icon"' . $icon_inline_css . '>' . $icon_html . '</div>';
	$output .= $link_closer;
	$output .= '<div class="w-iconbox-meta">';
} elseif ( $atts['iconpos'] == 'right' ) {
	$output .= '<div class="w-iconbox-meta">';
}
if ( $atts['title'] != '' ) {
	$output .= $link_opener;
	$title_inline_css = us_prepare_inline_css(
		array(
			'font-size' => $atts['title_size'],
		)
	);
	$output .= '<' . $atts['title_tag'] . ' class="w-iconbox-title"' . $title_inline_css . '>' . $atts['title'] . '</' . $atts['title_tag'] . '>';
	$output .= $link_closer;
}
if ( $content != '' ) {
	$output .= '<div class="w-iconbox-text">' . do_shortcode( $content ) . '</div>';
}
if ( in_array( $atts['iconpos'], array( 'top', 'left' ) ) ) {
	$output .= '</div>';
} elseif ( $atts['iconpos'] == 'right' ) {
	$output .= '</div>';
	$output .= $link_opener;
	$output .= '<div class="w-iconbox-icon"' . $icon_inline_css . '>' . $icon_html . '</div>';
	$output .= $link_closer;
}
$output .= '</div>';

echo $output;
