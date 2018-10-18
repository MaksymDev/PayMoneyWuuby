<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Custom Field element
 *
 * @var $key string custom field key
 * @var $link string Link type: 'post' / 'custom' / 'none'
 * @var $custom_link array
 * @var $type string 'text' / 'image'
 * @var $thumbnail_size string Image WordPress size
 * @var $icon string Icon name
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

$postID = get_the_ID();
if ( ! $postID ) {
	return FALSE;
}

$classes = isset( $classes ) ? $classes : '';

$tag = 'div';

// Retrieve meta key value
if ( $key != 'custom' ) {
	$value = get_post_meta( $postID, $key, TRUE );
} elseif ( ! empty( $custom_key ) ) {
	$value = get_post_meta( $postID, $custom_key, TRUE );
} else {
	$value = '';
}

$type = 'text';

// Force "image" type for relevant meta keys
if ( $key == 'us_tile_additional_image' ) {
	$type = 'image';
}

$classes .= ' type_' . $type;

// Generate image semantics
if ( $type == 'image' ) {
	$value = intval( $value );

	if ( $value ) {
		global $us_grid_img_size;
		if ( ! empty( $us_grid_img_size ) AND $us_grid_img_size != 'default' ) {
			$thumbnail_size = $us_grid_img_size;
		}

		$image = wp_get_attachment_image_src( $value, $thumbnail_size );

		if ( is_array( $image ) ) {
			$value = wp_get_attachment_image( $value, $thumbnail_size );
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
} elseif ( is_array( $value ) ) {
	$value = implode( ' ', $value );
} else {
	$value = wpautop( $value ); // add <p> and <br> if custom field has WYSIWYG
}

// Generate anchor semantics
$link_atts = array(
	'href' => '',
	'meta' => '',
);
if ( $link === 'post' ) {
	$link_atts = us_grid_get_post_link( $link_atts );
} elseif ( $link === 'custom' ) {
	$link_atts = us_grid_get_custom_link( $custom_link, $link_atts );
}

// Output the element
$output = '<' . $tag . ' class="w-grid-item-elm' . $classes . '">';
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon ) . ' ';
}
if ( ! empty( $link_atts['href'] ) ) {
	$output .= '<a href="' . esc_url( $link_atts['href'] ) . '"' . $link_atts['meta'] . '>';
}

$output .= $value;

if ( ! empty( $link_atts['href'] ) ) {
	$output .= '</a>';
}
$output .= '</' . $tag . '>';

echo $output;
