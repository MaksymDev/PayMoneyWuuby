<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output Post Comments element
 *
 * @var $number bool Show number only
 * @var $link string Link type: 'post' / 'custom' / 'none'
 * @var $custom_link array
 * @var $icon string Icon name
 * @var $design_options array
 *
 * @var $classes string
 * @var $id string
 */

if ( get_post_format() == 'link' OR ! comments_open() ) {
	return;
}

$classes = isset( $classes ) ? $classes : '';

// Generate anchor semantics
$link_atts = array(
	'href' => '',
	'meta' => '',
);
if ( $link === 'post' ) {
	if ( get_post_type() == 'product' ) {
		$link_atts['href'] = apply_filters( 'the_permalink', get_permalink() ) . '#reviews';
	} else {
		ob_start();
		comments_link();
		$link_atts['href'] = ob_get_clean();
	}
} elseif ( $link === 'custom' ) {
	$link_atts = us_grid_get_custom_link( $custom_link, $link_atts );
}

$comments_none = '0';
if ( ! $number ) {
	$classes .= ' with_word';
	$comments_none = us_translate( 'No Comments' );
}

// Output the element
$output = '<div class="w-grid-item-elm' . $classes . '">';
if ( ! empty( $icon ) ) {
	$output .= us_prepare_icon_tag( $icon ) . ' ';
}
if ( ! empty( $link_atts['href'] ) ) {
	$output .= '<a href="' . esc_url( $link_atts['href'] ) . '"' . $link_atts['meta'] . '>';
}

$comments_number = get_comments_number();

if ( get_post_type() == 'product' ) {
	$output .= sprintf( us_translate_n( '%s customer review', '%s customer reviews', $comments_number, 'woocommerce' ), '<span class="count">' . esc_html( $comments_number ) . '</span>' );
} else {
	ob_start();
	$comments_label = sprintf( us_translate_n( '%s <span class="screen-reader-text">Comment</span>', '%s <span class="screen-reader-text">Comments</span>', $comments_number ), $comments_number );
	comments_number( $comments_none, $comments_label, $comments_label );
	$output .= ob_get_clean();
}

if ( ! empty( $link_atts['href'] ) ) {
	$output .= '</a>';
}

$output .= '</div>';

echo $output;
