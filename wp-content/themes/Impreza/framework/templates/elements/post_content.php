<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Content element
 *
 * @var $type string Show: 'excerpt_only' / 'excerpt_content' / 'part_content' / 'full_content'
 * @var $length int Amount of words
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

$classes = isset( $classes ) ? $classes : '';

// Post excerpt is not empty
if ( in_array( $type, array( 'excerpt_content', 'excerpt_only' ) ) AND has_excerpt() ) {
	$the_content = get_the_excerpt();
// Either the excerpt is empty and we show the content instead or we show the content only
} elseif ( in_array( $type, array( 'excerpt_content', 'part_content', 'full_content' ) ) ) {
	$the_content = get_the_content();
	us_get_post_preview( $the_content, TRUE );
	$the_content = apply_filters( 'the_content', $the_content );
	// Limit the amount of words for the corresponding types
	if ( in_array( $type, array( 'excerpt_content', 'part_content' ) ) AND intval( $length ) > 0 ) {
		$the_content = wp_trim_words( $the_content, intval( $length ) );
	}
// Post excerpt is empty and we show nothing in this case
} else {
	$the_content = '';
}

// Don't output the content for "Link" post format
if ( get_post_format() == 'link' OR $the_content == '' ) {
	return FALSE;
}

$output = '<div class="w-grid-item-elm' . $classes . '">' . $the_content . '</div>';

echo $output;
