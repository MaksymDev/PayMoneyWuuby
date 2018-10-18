<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_breadcrumbs
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['home'] string Homepage Label
 * @param $atts           ['font_size'] string Font Size
 * @param $atts           ['align'] string Alignment
 * @param $atts           ['separator_type'] string Separator Type: 'icon' / 'custom'
 * @param $atts           ['separator_icon'] string Separator Icon
 * @param $atts           ['separator_symbol'] string Separator Symbol
 * @param $atts           ['show_current'] bool Show current page?
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'us_breadcrumbs' );

// Don't show the element on the homepage
if ( is_home() OR is_front_page() ) {
	return;
}

$classes = '';
$classes .= ' separator_' . $atts['separator_type'];
$classes .= ' align_' . $atts['align'];
if ( ! $atts['show_current'] ) {
	$classes .= ' hide_current';
}
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

// Generate inline styles
$inline_css = us_prepare_inline_css(
	array(
		'font-size' => $atts['font_size'],
	)
);

// Generate separator between crumbs
$delimiter = '';
if ( $atts['separator_type'] == 'icon' ) {
	$delimiter = us_prepare_icon_tag( $atts['separator_icon'] );
} elseif ( $atts['separator_type'] == 'custom' ) {
	$delimiter = strip_tags( $atts['separator_symbol'] );
}
if ( $delimiter != '' ) {
	$delimiter = '<li class="g-breadcrumbs-separator">' . $delimiter . '</li>';
}

// Generate microdata markup
$microdata_list = $microdata_item = $link_attr = '';
if ( us_get_option( 'schema_markup' ) ) {
	$microdata_list = ' itemscope itemtype="http://schema.org/BreadcrumbList"';
	$microdata_item = ' itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"';
	$link_attr = ' itemprop="name"';
}

// Homepage Label
$home = strip_tags( $atts['home'] );

// The breadcrumb’s container starting code
$list_before = '<ol class="g-breadcrumbs' . $classes . '"' . $inline_css . $microdata_list . '>';

// The breadcrumb’s container ending code
$list_after = '</ol>';

// Code before single crumb
$item_before = '<li class="g-breadcrumbs-item"' . $microdata_item . '>';

// Code after single crumb
$item_after = '</li>';

// Return default WooCommerce breadcrumbs
if ( function_exists( 'woocommerce_breadcrumb' ) AND is_woocommerce() ) {
	return woocommerce_breadcrumb(
		array(
			'wrap_before' => $list_before,
			'wrap_after' => $list_after,
			'delimiter' => $delimiter,
			'before' => $item_before,
			'after' => $item_after,
			'home' => $home,
		)
	);

// Return default bbPress breadcrumbs
} elseif ( function_exists( 'bbp_get_breadcrumb' ) AND is_singular( array( 'topic', 'forum' ) ) ) {
	echo bbp_get_breadcrumb(
		array(
			'before' => $list_before,
			'after' => $list_after,
			'sep' => $delimiter,
			'crumb_before' => $item_before,
			'crumb_after' => $item_after,
		)
	);

// Output theme breadcrumbs
} else {
	echo $list_before . us_breadcrumbs( $delimiter, $home, $item_before, $item_after, $link_attr ) . $list_after;
}
