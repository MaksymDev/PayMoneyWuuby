<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

get_header();

// Define if Title Bar is set
if ( is_singular() ) {
	$titlebar_id = us_get_option( 'titlebar_product_id' );
} else {
	$titlebar_id = us_get_option( 'titlebar_shop_id' );
	if ( ! is_search() AND ! is_tax() ) {
		if ( usof_meta( 'us_titlebar', array(), wc_get_page_id( 'shop' ) ) == 'hide' ) {
			$titlebar_id = '';
		} elseif ( usof_meta( 'us_titlebar', array(), wc_get_page_id( 'shop' ) ) == 'custom' ) {
			$titlebar_id = usof_meta( 'us_titlebar_id', array(), wc_get_page_id( 'shop' ) );
		}
	}
}
if ( $titlebar_id == '__defaults__' ) {
	$titlebar_id = us_get_option( 'titlebar_id' );
}

// If Title Bar is set, remove page title & breadcrumbs
if ( $titlebar_id != '' ) {

	// Hiding the default WooCommerce page title to avoid duplication
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	add_filter( 'woocommerce_show_page_title', 'us_woocommerce_dont_show_page_title' );
	function us_woocommerce_dont_show_page_title() {
		return FALSE;
	}

	// Hiding the default WooCommerce breadcrumbs to avoid duplication
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 3 );
}

$template_vars = array();
if ( is_singular() ) {
	$template_vars['title'] = get_the_title();
} else {
	$template_vars['title'] = woocommerce_page_title( FALSE );
}
us_load_template( 'templates/titlebar', $template_vars );
