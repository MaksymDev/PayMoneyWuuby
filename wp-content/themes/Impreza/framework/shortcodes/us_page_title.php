<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_page_title
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts           ['font'] string Font Family
 * @param $atts           ['text_styles'] string Text Styles: bold, uppercase
 * @param $atts           ['font_size'] string Font Size
 * @param $atts           ['line_height'] string Line Height
 * @param $atts           ['tag'] string HTML tag
 * @param $atts           ['align'] string Alignment
 * @param $atts           ['inline'] bool Show the next text in the same line
 * @param $atts           ['description'] bool Show Term Description
 * @param $atts           ['el_class'] string Extra class name
 */

$atts = us_shortcode_atts( $atts, 'us_page_title' );
$classes = $schema_heading = $output = '';

$classes .= ' align_' . $atts['align'];
if ( $atts['inline'] AND $atts['align'] != 'center' ) {
	$classes .= ' type_inline';
}
if ( ! empty( $atts['el_class'] ) ) {
	$classes .= ' ' . $atts['el_class'];
}

$tag = $atts['tag'];

// Generate inline styles
if ( $atts['font'] == 'body' AND $tag == 'div' ) {
	$inline_css = '';
} elseif ( $atts['font'] == 'heading' AND in_array( $tag, array( 'h1', 'h2',  'h3', 'h4',  'h5', 'h6' ) ) ) {
	$inline_css = '';
} else {
	$inline_css = us_get_font_css( $atts['font'] );
}
if ( strpos( $atts['text_styles'], 'bold' ) !== FALSE ) {
	$inline_css .= 'font-weight:bold;';
}
if ( strpos( $atts['text_styles'], 'uppercase' ) !== FALSE ) {
	$inline_css .= 'text-transform:uppercase;';
}
$inline_css .= us_prepare_inline_css(
	array(
		'font-size' => $atts['font_size'],
		'line-height' => $atts['line_height'],
		'color' => $atts['color'],
	),
	$style_attr = FALSE
);

// Add microdata depending on the relevant Theme Option
if ( us_get_option( 'schema_markup' ) ) {
	$schema_heading = ' itemprop="headline"';
}

// Get title based on page type
if ( is_home() ) {
	$title = us_translate( 'All Posts' );
} elseif ( is_search() ) {
	$title = sprintf( us_translate( 'Search results for &#8220;%s&#8221;' ), esc_attr( get_search_query() ) );
} elseif ( is_author() ) {
	$title = sprintf( us_translate( 'Posts by %s' ), get_the_author() );
} elseif ( is_tag() ) {
	$title = single_tag_title( '', FALSE );
} elseif ( is_category() ) {
	$title = single_cat_title( '', FALSE );
} elseif ( is_tax() ) {
	$title = single_term_title( '', FALSE );
} elseif ( function_exists( 'is_woocommerce' ) AND is_shop() ) {
	$title = woocommerce_page_title( '', FALSE );
} elseif ( is_archive() ) {
	$post_type = get_post_type_object( get_post_type() );
	if ( isset( $post_type->labels->name ) ) {
		$title = $post_type->labels->name;
	}
} else {
	$title = get_the_title();
}

// Output the element
if ( $title != '' ) {
	$output .= '<' . $tag . ' class="w-page-title' . $classes . '"';
	$output .= $schema_heading;
	$output .= empty( $inline_css ) ? '' : ( ' style="' . esc_attr( $inline_css ) . '"' );
	$output .= '>';
	$output .= $title;
	$output .= '</' . $tag . '>';
}
if ( $atts['description'] AND term_description() ) {
	$output .= '<div class="w-term-description">' . term_description() . '</div>';
}

echo $output;
