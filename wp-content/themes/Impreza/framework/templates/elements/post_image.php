<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Image element
 *
 * @var $thumbnail_size string Image WordPress size
 * @var $placeholder bool Use placeholder if post has no thumbnail?
 * @var $media_preview bool Show media preview for video and gallery posts?
 * @var $link string Link type: 'post' / 'custom' / 'none'
 * @var $custom_link array
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

global $us_template_directory_uri, $us_grid_img_size, $_wp_additional_image_sizes, $us_post_img_ratio, $us_post_slider_size;

$classes = isset( $classes ) ? $classes : '';
$classes .= ( isset( $circle ) AND $circle ) ? ' as_circle' : '';

// Overwrite thumbnail_size from [us_grid] shortcode if set
if ( ! empty( $us_grid_img_size ) AND $us_grid_img_size != 'default' ) {
	$thumbnail_size = $us_grid_img_size;
}

// Calculate aspect ratio for media preview and for placeholder
if ( isset( $_wp_additional_image_sizes[ $thumbnail_size ] ) AND $_wp_additional_image_sizes[ $thumbnail_size ]['width'] != 0 AND $_wp_additional_image_sizes[ $thumbnail_size ]['height'] != 0 ) {
	$us_post_img_ratio = number_format( $_wp_additional_image_sizes[ $thumbnail_size ]['height'] / $_wp_additional_image_sizes[ $thumbnail_size ]['width'] * 100, 4 );
}

// Generate anchor semantics
$_post_preview = '';
$link_atts = array(
	'href' => '',
	'meta' => '',
);
if ( $link === 'post' ) {
	$link_atts = us_grid_get_post_link( $link_atts );
} elseif ( $link === 'custom' ) {
	$link_atts = us_grid_get_custom_link( $custom_link, $link_atts );
}
$link_atts['meta'] .= ' aria-label="' . esc_attr( strip_tags( get_the_title() ) ) . '"'; // needed for accessibility support

// Generate media preview for Video, Audio, Gallery formats
if ( $media_preview AND ! post_password_required() ) {
	$us_post_slider_size = $thumbnail_size; // set slider images size for media preview

	$the_content = get_the_content();
	$_post_preview = us_get_post_preview( $the_content, TRUE );

	if ( $_post_preview != '' ) {
		$classes .= ' media_preview'; // add CSS class for media preview
		$link_atts['href'] = $link_atts['meta'] = ''; // remove link for media preview
	}
}

// Output Featured image
if ( $_post_preview == '' AND has_post_thumbnail() ) {
	$_post_preview = get_the_post_thumbnail( get_the_ID(), $thumbnail_size );
}

// Output the first image from the content of Gallery format
if ( $_post_preview == '' AND get_post_format() == 'gallery' ) {
	$the_content = get_the_content();
	if ( preg_match( '~\[us_gallery.+?\]|\[us_image_slider.+?\]|\[gallery.+?\]~', $the_content, $matches ) ) {
		$gallery = preg_replace( '~(vc_gallery|us_gallery|gallery)~', 'us_image_slider', $matches[0] );
		preg_match( '~\[us_image_slider(.+?)\]~', $gallery, $matches2 );
		$shortcode_atts = shortcode_parse_atts( $matches2[1] );
		if ( ! empty( $shortcode_atts['ids'] ) ) {
			$ids = explode( ',', $shortcode_atts['ids'] );
			if ( count( $ids ) > 0 ) {
				$_post_preview = wp_get_attachment_image( $ids[0], $thumbnail_size );
			}
		}
	}
}

// Output placeholder if enabled
if ( $_post_preview == '' AND $placeholder ) {
	$classes .= ' with_placeholder';
	$_post_preview ='<div class="w-grid-item-placeholder" style="padding-bottom:' . ( empty( $us_post_img_ratio ) ? 100 : $us_post_img_ratio ) . '%;"></div>';
}

// Don't output the element without any content
if ( $_post_preview == '' ) {
	return FALSE;
}

$output = '<div class="w-grid-item-elm' . $classes . '">';
if ( ! empty( $link_atts['href'] ) ) {
	$output .= '<a href="' . esc_url( $link_atts['href'] ) . '"' . $link_atts['meta'] . '>';
}

$output .= $_post_preview;

if ( ! empty( $link_atts['href'] ) ) {
	$output .= '</a>';
}
$output .= '</div>';

echo $output;
