<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output WooCommerce Product Field
 *
 * @var $type string custom field
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

if ( ! class_exists( 'woocommerce' ) ) {
	return FALSE;
}

global $product;
if ( ! $product ) {
	return FALSE;
}

// Get product data value
if ( $type == 'price' ) {
	$value = $product->get_price_html();
} elseif ( $type == 'sku' ) {
	$value = $product->get_sku();
} elseif ( $type == 'rating' ) {
	$value = wc_get_rating_html( $product->get_average_rating() );
} elseif ( $type == 'sale_badge' AND $product->is_on_sale() ) {
	$value = $sale_text;
} else {
	$value = '';
}

$classes = isset( $classes ) ? $classes : '';
$classes .= isset( $type ) ? ( ' ' . $type ) : '';

// Output the element
$output = '<div class="w-grid-item-elm' . $classes . '">';
$output .= $value;
$output .= '</div>';

if ( $value != '' ) {
	echo $output;
}
