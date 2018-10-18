<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * WooCommerce Theme Support
 *
 * @link http://www.woothemes.com/woocommerce/
 */

add_action( 'after_setup_theme', 'us_woocommerce_support' );
function us_woocommerce_support() {
	add_theme_support(
		'woocommerce', array(
			'gallery_thumbnail_image_width' => 150, // changed gallery thumbnail size to default WP 'thumbnail'
		)
	);
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}

if ( ! class_exists( 'woocommerce' ) ) {
	return FALSE;
}

global $woocommerce;
if ( version_compare( $woocommerce->version, '2.1', '<' ) ) {
	define( 'WOOCOMMERCE_USE_CSS', FALSE );
} else {
	add_filter( 'woocommerce_enqueue_styles', 'us_woocommerce_dequeue_styles' );
	function us_woocommerce_dequeue_styles( $styles ) {
		$styles = array();

		return $styles;
	}

	add_action( 'wp_enqueue_scripts', 'us_woocomerce_dequeue_checkout_styles', 100 );
	function us_woocomerce_dequeue_checkout_styles() {
		wp_dequeue_style( 'select2' );
		wp_deregister_style( 'select2' );
	}
}

if ( ! ( defined( 'US_DEV' ) AND US_DEV ) AND us_get_option( 'optimize_assets', 0 ) == 0 ) {
	add_action( 'wp_enqueue_scripts', 'us_woocommerce_enqueue_styles', 14 );
}
function us_woocommerce_enqueue_styles( $styles ) {
	global $us_template_directory_uri;
	$min_ext = ( ! ( defined( 'US_DEV' ) AND US_DEV ) ) ? '.min' : '';
	wp_enqueue_style( 'us-woocommerce', $us_template_directory_uri . '/css/plugins/woocommerce' . $min_ext . '.css', array(), US_THEMEVERSION, 'all' );
}

// Adjust HTML markup for all WooCommerce pages
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
if ( ! function_exists( 'us_woocommerce_before_main_content' ) ) {
	add_action( 'woocommerce_before_main_content', 'us_woocommerce_before_main_content', 10 );
	function us_woocommerce_before_main_content() {
		echo '<div class="l-main">';
		echo '<div class="l-main-h i-cf">';
		echo '<main class="l-content">';
		if ( is_post_type_archive( 'product' ) AND 0 === absint( get_query_var( 'paged' ) ) ) {

			$shop_page = get_post( wc_get_page_id( 'shop' ) );

			if ( $shop_page ) {
				$description = apply_filters( 'the_content', $shop_page->post_content );
				if ( $description ) {
					$has_own_sections = ( strpos( $description, ' class="l-section' ) !== FALSE );
					if ( ! $has_own_sections ) {
						$description = '<section class="l-section for_shop_description"><div class="l-section-h i-cf">' . $description . '</div></section>';
					}
					echo $description;
				}
			}
		}
		echo '<section id="shop" class="l-section for_shop">';
		echo '<div class="l-section-h i-cf">';
	}
}
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
if ( ! function_exists( 'us_woocommerce_after_main_content' ) ) {
	add_action( 'woocommerce_after_main_content', 'us_woocommerce_after_main_content', 20 );
	function us_woocommerce_after_main_content() {
		echo '</div></section></main>';
		us_load_template( 'templates/sidebar' );
		echo '</div></div>';
	}
}

// Adjust HTML markup for product in list
add_action( 'woocommerce_before_shop_loop_item', 'us_woocommerce_before_shop_loop_item', 20 );
function us_woocommerce_before_shop_loop_item() {
	echo '<div class="product-h">';
}

add_action( 'woocommerce_after_shop_loop_item', 'us_woocommerce_after_shop_loop_item', 20 );
function us_woocommerce_after_shop_loop_item() {
	echo '</div>';
}

add_action( 'woocommerce_before_shop_loop_item_title', 'us_woocommerce_before_shop_loop_item_title', 20 );
function us_woocommerce_before_shop_loop_item_title() {
	echo '<div class="product-meta">';
}

add_action( 'woocommerce_after_shop_loop_item_title', 'us_woocommerce_after_shop_loop_item_title', 20 );
function us_woocommerce_after_shop_loop_item_title() {
	echo '</div>';
}

// Change number of columns for Shop pages
add_filter( 'loop_shop_columns', 'loop_columns' );
if ( ! function_exists( 'loop_columns' ) ) {
	function loop_columns() {
		return us_get_option( 'shop_columns', 4 );
	}
}

// Change number of Related Products on Product page
function woo_related_products_limit() {
	global $product;

	$args['posts_per_page'] = us_get_option( 'product_related_qty', 4 );

	return $args;
}

add_filter( 'woocommerce_output_related_products_args', 'us_related_products_args' );
function us_related_products_args( $args ) {
	$args['posts_per_page'] = us_get_option( 'product_related_qty', 4 );
	$args['columns'] = us_get_option( 'product_related_qty', 4 );

	return $args;
}

// Change number of Cross Sells on Cart page
add_filter( 'woocommerce_cross_sells_total', 'us_woocommerce_cross_sells_total' );
add_filter( 'woocommerce_cross_sells_columns', 'us_woocommerce_cross_sells_total' );
function us_woocommerce_cross_sells_total( $count ) {
	return us_get_option( 'product_related_qty', 4 );
}

// Remove WC sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Move cross sells bellow the shipping
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display', 10 );

// Add breadcrumbs before product title
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 3 );

// Alter Cart - add total number
add_filter( 'woocommerce_add_to_cart_fragments', 'us_add_to_cart_fragments' );
function us_add_to_cart_fragments( $fragments ) {
	global $woocommerce;

	$fragments['a.cart-contents'] = '<a class="cart-contents" href="' . esc_url( wc_get_cart_url() ) . '">' . $woocommerce->cart->get_cart_total() . '</a>';

	return $fragments;
}

// Add classes to <body> of WooCommerce pages
add_action( 'body_class', 'us_wc_body_class' );
function us_wc_body_class( $classes ) {
	$classes[] = 'us-woo-shop_' . us_get_option( 'shop_listing_style', 'standard' );
	$classes[] = 'us-woo-cart_' . us_get_option( 'shop_cart', 'standard' );
	if ( us_get_option( 'shop_catalog', 0 ) == 1 ) {
		$classes[] = 'us-woo-catalog';
	}

	return $classes;
}

// Pagination
if ( ! function_exists( 'woocommerce_pagination' ) ) {
	function woocommerce_pagination() {
		global $us_woo_disable_pagination;
		if ( isset( $us_woo_disable_pagination ) AND $us_woo_disable_pagination ) {
			return;
		}

		global $wp_query;
		if ( $wp_query->max_num_pages <= 1 ) {
			return;
		}
		the_posts_pagination(
			array(
				'mid_size' => 3,
				'before_page_number' => '<span>',
				'after_page_number' => '</span>',
			)
		);
	}
}

add_action( 'woocommerce_after_mini_cart', 'us_woocommerce_after_mini_cart' );
function us_woocommerce_after_mini_cart() {
	global $woocommerce;

	echo '<span class="us_mini_cart_amount" style="display: none;">' . $woocommerce->cart->cart_contents_count . '</span>';
}

// Add WooCommerce image sizes for selection
add_filter( 'us_image_sizes_select_values', 'us_woocommerce_image_sizes_select_values' );
function us_woocommerce_image_sizes_select_values( $image_sizes ) {
	$size_names = array( 'shop_single', 'shop_catalog', 'shop_thumbnail' );

	foreach ( $size_names as $size_name ) {
		// Detecting size
		$size = us_get_intermediate_image_size( $size_name );
		$size_title = ( $size['width'] == 0 ) ? __( 'any', 'us' ) : $size['width'];
		$size_title .= ' x ';
		$size_title .= ( $size['height'] == 0 ) ? __( 'any', 'us' ) : $size['height'];
		if ( $size['crop'] ) {
			$size_title .= ' ' . __( 'cropped', 'us' );
		}
		if ( ! in_array( $size_title, $image_sizes ) ) {
			$image_sizes[$size_title] = $size_name;
		}
	}

	return $image_sizes;
}

// Remove focus state on Checkout page
add_filter( 'woocommerce_checkout_fields', 'us_woocommerce_disable_autofocus_billing_firstname' );
function us_woocommerce_disable_autofocus_billing_firstname( $fields ) {
	$fields['shipping']['shipping_first_name']['autofocus'] = FALSE;

	return $fields;
}

// Wrap attributes <select> for better styling
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'us_woocommerce_dropdown_variation_attribute_options_html' );
function us_woocommerce_dropdown_variation_attribute_options_html( $html ) {
	$html = '<div class="woocommerce-select">' . $html . '</div>';

	return $html;
}

// Remove title on Shop pages
if ( ! in_array( 'shop_title', us_get_option( 'shop_elements' ) ) ) {
	add_filter( 'woocommerce_show_page_title', '__return_false' );
}

// Remove title on Product pages
if ( ! in_array( 'product_title', us_get_option( 'shop_elements' ) ) ) {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
}

// Remove built-in breadcrumbs
if ( ! in_array( 'breadcrumbs', us_get_option( 'shop_elements' ) ) ) {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 3 );
}

/*
 * WooCommerce grid appearance via us_grid
 */
// Shop page
add_action( 'woocommerce_before_shop_loop', 'us_apply_grid_to_woocommerce_loop' );
add_action( 'woocommerce_after_shop_loop', 'us_remove_grid_from_woocommerce_loop' );

// Related products on single Product page
add_action( 'woocommerce_after_single_product_summary', 'us_apply_grid_to_woocommerce_loop', 5 );
add_action( 'woocommerce_after_single_product_summary', 'us_remove_grid_from_woocommerce_loop', 25 );

// Related products on Cart page
add_action( 'woocommerce_cart_collaterals', 'us_apply_grid_to_woocommerce_loop', 5 );
add_action( 'woocommerce_after_cart', 'us_remove_grid_from_woocommerce_loop' );

function us_apply_grid_to_woocommerce_loop() {
	$shop_listing_style = us_get_option( 'shop_listing_style', 'standard' );
	if ( $shop_listing_style != 'custom' ) {
		return;
	}
	add_filter( 'woocommerce_product_loop_start', 'us_grid_woocommerce_product_loop_start' );
	add_filter( 'woocommerce_product_loop_end', 'us_grid_woocommerce_product_loop_end' );
	add_filter( 'wc_get_template_part', 'us_wc_get_template_part_content_product', 10, 3 );
}

function us_remove_grid_from_woocommerce_loop() {
	$shop_listing_style = us_get_option( 'shop_listing_style', 'standard' );
	if ( $shop_listing_style != 'custom' ) {
		return;
	}
	remove_filter( 'woocommerce_product_loop_start', 'us_grid_woocommerce_product_loop_start' );
	remove_filter( 'woocommerce_product_loop_end', 'us_grid_woocommerce_product_loop_end' );
	remove_filter( 'wc_get_template_part', 'us_wc_get_template_part_content_product', 10 );
}

// Generate start of Grid listing
function us_grid_woocommerce_product_loop_start( $template ) {
	$result = '';

	// If the categories are present, prepend them
	$result .= woocommerce_maybe_show_product_subcategories();
	if ( $result != '' ) {
		ob_start();
		wc_set_loop_prop( 'loop', 0 );
		wc_get_template( 'loop/loop-start.php' );
		$result = ob_get_clean() . $result;
		ob_start();
		wc_get_template( 'loop/loop-end.php' );
		$result .= ob_get_clean();
	}
	$items_layout = us_get_option( 'shop_layout', 'blog_classic' );

	$grid_layout_settings = us_get_woocommerce_products_grid_layout( $items_layout );
	if ( ! isset( $grid_layout_settings ) OR empty( $grid_layout_settings ) ) {
		return $template;
	}

	// Disable regular WooCommerce pagination
	global $us_woo_disable_pagination;
	$us_woo_disable_pagination = TRUE;

	global $us_grid_listing_post_atts;
	$us_grid_listing_post_atts = array(
		'grid_layout_settings' => $grid_layout_settings,
		'type' => 'grid',
		'is_widget' => FALSE,
	);

	$template_vars = array(
		'grid_layout_settings' => $grid_layout_settings,
		'items_layout' => $items_layout,
		'type' => 'grid',
		'columns' => ( is_cart() || is_product() ) ? us_get_option( 'product_related_qty', 1 ) : us_get_option( 'shop_columns', 1 ),
		'pagination' => ( is_cart() || is_product() ) ? 'none' : 'regular',
		'items_gap' => us_get_option( 'shop_items_gap', 1.5 ) . 'rem',
	);

	$result .= us_get_template( 'templates/us_grid/listing-start', $template_vars );

	return $result;
}

// Generate end of Grid listing
function us_grid_woocommerce_product_loop_end( $template ) {
	$items_layout = us_get_option( 'shop_layout', 'blog_classic' );
	$grid_layout_settings = us_get_woocommerce_products_grid_layout( $items_layout );

	if ( ! isset( $grid_layout_settings ) OR empty( $grid_layout_settings ) ) {
		return $template;
	}

	global $wp_query;
	$template_vars = array(
		'wp_query' => $wp_query,
		'query_args' => $wp_query->query_vars,
		'grid_layout_settings' => $grid_layout_settings,
		'items_layout' => $items_layout,
		'type' => 'grid',
		'columns' => ( is_cart() || is_product() ) ? us_get_option( 'product_related_qty', 1 ) : us_get_option( 'shop_columns', 1 ),
		'pagination' => ( is_cart() || is_product() ) ? 'none' : 'regular',
		'items_gap' => us_get_option( 'shop_items_gap', 1.5 ) . 'rem',
	);

	$result = us_get_template( 'templates/us_grid/listing-end', $template_vars );

	return $result;

}

// Get Grid listing post template
function us_wc_get_template_part_content_product( $template, $slug, $name = '' ) {
	if ( ! ( $slug == 'content' AND $name == 'product' ) ) {
		return $template;
	}

	return us_locate_file( 'templates/us_grid/listing-post.php' );
}

// Get Grid Layout for products
function us_get_woocommerce_products_grid_layout( $items_layout ) {
	$grid_layout_settings = NULL;
	if ( ! empty( $items_layout ) ) {
		if ( $templates_config = us_config( 'grid-templates', array(), TRUE ) AND isset( $templates_config[$items_layout] ) ) {
			$grid_layout_settings = us_fix_grid_settings( $templates_config[$items_layout] );
		} elseif ( $grid_layout = get_post( (int) $items_layout ) ) {
			if ( $grid_layout instanceof WP_Post AND $grid_layout->post_type === 'us_grid_layout' ) {
				if ( ! empty( $grid_layout->post_content ) AND substr( strval( $grid_layout->post_content ), 0, 1 ) === '{' ) {
					try {
						$grid_layout_settings = json_decode( $grid_layout->post_content, TRUE );
					}
					catch ( Exception $e ) {
					}
				}
			}
		}
	}

	return $grid_layout_settings;
}
