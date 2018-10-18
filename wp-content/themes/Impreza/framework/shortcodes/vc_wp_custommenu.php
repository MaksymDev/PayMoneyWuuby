<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: vc_wp_custommenu
 *
 * Overloaded by UpSolution custom implementation.
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $atts           array Shortcode attributes
 *
 */

$atts = us_shortcode_atts( $atts, $shortcode_base );

$classes = '';

if ( ! empty( $atts['layout'] ) )  {
	$classes .= ' layout_' . $atts['layout'];
}
$classes .= ' align_' . $atts['align'];
if ( $atts['el_class'] != '' ) {
	$classes .= ' ' . $atts['el_class'];
}

$inline_css = us_prepare_inline_css( array(
	'font-size' => $atts['font_size'],
));

$output = '<div class="vc_wp_custommenu ' . esc_attr( $classes ) . '"';
if ( ! empty( $atts['el_id'] ) )  {
	$output .= ' id="' . $atts['el_id'] . '"';
}
$output .= $inline_css . '>';
$type = 'WP_Nav_Menu_Widget';
$args = array();
global $wp_widget_factory;
// to avoid unwanted warnings let's check before using widget
if ( is_object( $wp_widget_factory ) AND isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
	ob_start();
	the_widget( $type, $atts, $args );
	$output .= ob_get_clean();
	$output .= '</div>';

	echo $output;
} else {
	echo 'Widget ' . esc_attr( $type ) . 'Not found in : vc_wp_custommenu';
}
