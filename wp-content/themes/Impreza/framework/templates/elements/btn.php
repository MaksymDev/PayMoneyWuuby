<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Button element. Used in Header Builder & Grid builder
 *
 * @var $add_to_cart      string Used in Grid builder
 * @var $label            string
 * @var $link_type        string Used in Grid builder
 * @var $link             string
 * @var $style            string
 * @var $color            string Button Colors
 * @var $icon             string
 * @var $iconpos          string
 * @var $size             string
 * @var $size_tablets     string
 * @var $size_mobiles     string
 * @var $color_bg         string
 * @var $color_hover_bg   string
 * @var $color_text       string
 * @var $color_hover_text string
 * @var $design_options   array
 * @var $classes          string
 * @var $id               string
 */

$classes = isset( $classes ) ? $classes : '';

$classes .= ' style_' . $style;
if ( isset( $color ) ) {
	$classes .= ' color_' . $color;
} else {
	$classes .= ' color_custom';
}

$icon_html = '';
if ( ! empty( $icon ) ) {
	$icon_html = us_prepare_icon_tag( $icon );
	$classes .= ' icon_at' . $iconpos;
} else {
	$classes .= ' icon_none';
}

// Generate anchor semantics
if ( isset( $link_type ) AND $link_type === 'post' ) {
	$link_atts['href'] = apply_filters( 'the_permalink', get_permalink() );
} elseif ( empty( $link_type ) OR $link_type === 'custom' ) {
	$link_atts = usof_get_link_atts( $link );
	if ( ! isset( $link_atts['href'] ) ) {
		$link_atts['href'] = '';
	}
	// TODO: replace [lang] and other possible variables like it in one filter
	if ( ! empty( $link_atts['href'] ) AND strpos( $link_atts['href'], '[lang]' ) !== FALSE ) {
		$link_atts['href'] = str_replace( '[lang]', usof_get_lang(), $link_atts['href'] );
	}
	$link_atts = apply_filters( 'us_grid_element_custom_link', $link_atts );
} else { //elseif ( $link_type == 'none' )
	$link_atts['href'] = '';
}

// Output the element
$output = '<a class="w-btn' . $classes . '" href="' . esc_url( $link_atts['href'] ) . '"';
if ( ! empty( $link_atts['target'] ) ) {
	$output .= ' target="' . esc_attr( $link_atts['target'] ) . '"';
}
if ( ! empty( $link_atts['meta'] ) ) {
	$output .= $link_atts['meta'];
}
$output .= '>';
$output .= $icon_html;
$output .= '<span class="w-btn-label">' . strip_tags( $label, '<br>' ) . '</span>';
$output .= '</a>';

if ( class_exists( 'woocommerce' ) AND isset( $add_to_cart ) AND $add_to_cart ) {

	if ( empty( $view_cart_link ) ) {
		$classes .= ' no_view_cart_link';
	}
	echo '<div class="w-grid-item-elm' . $classes . '">';
	// Output WooCommerce add to cart semantics
	woocommerce_template_loop_add_to_cart();
	echo '</div>';

} else {
	echo $output;
}
