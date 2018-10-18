<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_logos
 *
 * Dev note: if you want to change some of the default values or acceptable attributes, overload the shortcodes config.
 *
 * @var   $shortcode      string Current shortcode name
 * @var   $shortcode_base string The original called shortcode name (differs if called an alias)
 * @var   $content        string Shortcode's inner content
 * @var   $atts           array Shortcode attributes
 *
 * @param $atts ['items'] array of Logos
 * @param $atts ['type'] string layout type: 'grid' / 'carousel'
 * @param $atts ['columns'] int Columns quantity
 * @param $atts ['with_indents'] bool Add indents between items?
 * @param $atts ['style'] string Hover style: '1' / '2'
 * @param $atts ['orderby'] string Items order: '' / 'rand'
 * @param $atts ['img_size'] string Images Size
 * @param $atts ['el_class'] string Extra class name
 * @param $atts ['carousel_arrows'] bool used in Carousel type
 * @param $atts ['carousel_dots'] bool used in Carousel type
 * @param $atts ['carousel_center'] bool used in Carousel type
 * @param $atts ['carousel_autoplay'] bool used in Carousel type
 * @param $atts ['carousel_interval'] int used in Carousel type
 * @param $atts ['carousel_slideby'] bool used in Carousel type
 * @param $atts ['carousel_autoplay_smooth'] bool used in Carousel type
 */

$atts = us_shortcode_atts( $atts, 'us_logos' );

$classes = $list_classes = '';

$atts['columns'] = intval( $atts['columns'] );
if ( $atts['columns'] < 1 OR $atts['columns'] > 8 ) {
	$atts['columns'] = 5;
}

if ( isset( $atts['img_size'] ) ) {
	$tnail_size = $atts['img_size'];
} else {
	$tnail_size = 'medium';
}

$classes .= ' style_' . $atts['style'];

if ( $atts['with_indents'] ) {
	$classes .= ' with_indents';
}
if ( isset( $atts['type'] ) ) {
	$classes .= ' type_' . $atts['type'];
}
if ( $atts['columns'] != 1 ) {
	$classes .= ' cols_' . $atts['columns'];
}
if ( $atts['el_class'] != '' ) {
	$classes .= ' ' . $atts['el_class'];
}
if ( $atts['type'] == 'carousel' ) {
	$list_classes .= ' owl-carousel';
}

// Generate extra "index" class to differ several elements at one page
global $us_logos_index;
$us_logos_index = isset( $us_logos_index ) ? ( $us_logos_index + 1 ) : 1;
$classes .= ' index_' . $us_logos_index;

// We need owl script for this
if ( us_get_option( 'ajax_load_js', 0 ) == 0 ) {
	wp_enqueue_script( 'us-owl' );
}

$output = '<div class="w-logos' . $classes . '"><div class="w-logos-list' . $list_classes . '">';
if ( empty( $atts['items'] ) ) {
	$atts['items'] = array();
} else {
	$atts['items'] = json_decode( urldecode( $atts['items'] ), TRUE );
	if ( ! is_array( $atts['items'] ) ) {
		$atts['items'] = array();
	}
}
if ( $atts['orderby'] == 'rand' ) {
	shuffle( $atts['items'] );
}

foreach ( $atts['items'] as $index => $item ) {
	$item['image'] = ( isset( $item['image'] ) ) ? $item['image'] : '';
	$item['link'] = ( isset( $item['link'] ) ) ? $item['link'] : '';

	$img_id = intval( $item['image'] );

	if ( $img_id AND ( $image_html = wp_get_attachment_image( $img_id, $tnail_size ) ) ) {
		// We got image
	} else {
		// In case of any image issue using placeholder so admin could understand it quickly
		global $us_template_directory_uri;
		$placeholder_url = $us_template_directory_uri . '/framework/img/us-placeholder-square.jpg';
		$image_html = '<img src="' . $placeholder_url . '" width="600" height="600" alt="">';
	}

	if ( $item['link'] != '' AND $link = us_vc_build_link( $item['link'] ) AND ( ! empty( $link['url'] ) ) ) {
		$_link_meta = ( $link['target'] == '_blank' ) ? ' target="_blank"' : '';
		$_link_meta .= ( $link['rel'] == 'nofollow' ) ? ' rel="nofollow"' : '';
		$_link_meta .= empty( $link['title'] ) ? '' : ( ' title="' . esc_attr( $link['title'] ) . '"' );
		$output .= '<a class="w-logos-item" href="' . esc_url( $link['url'] ) . '"' . $_link_meta . '>';
		$output .= $image_html . '</a>';
	} else {
		$output .= '<div class="w-logos-item">' . $image_html . '</div>';
	}
}
$output .= '</div>';

if ( $atts['type'] == 'carousel' ) {
	$preloader_type = us_get_option( 'preloader' );
	if ( ! in_array( $preloader_type, us_get_preloader_numeric_types() ) ) {
		$preloader_type = 1;
	}
	$output .= '<div class="g-preloader type_' . $preloader_type . '"><div></div></div>';
}

if ( empty( $atts['breakpoint_1_width'] ) ) {
	$atts['breakpoint_1_width'] = '1024px';
}

if ( empty( $atts['breakpoint_2_width'] ) ) {
	$atts['breakpoint_2_width'] = '768px';
}

if ( empty( $atts['breakpoint_3_width'] ) ) {
	$atts['breakpoint_3_width'] = '480px';
}

if ( isset( $atts['type'] ) AND $atts['type'] == 'carousel' ) {

	$json_data = array(
		'carousel_settings' => array(
			'items' => $atts['columns'],
			'nav' => intval( ! ! $atts['carousel_arrows'] ),
			'dots' => intval( ! ! $atts['carousel_dots'] ),
			'center' => intval( ! ! $atts['carousel_center'] ),
			'autoplay' => intval( ! ! $atts['carousel_autoplay'] ),

		),
		'carousel_breakpoints' => array(
			0 => array(
				'items' => min( $atts['columns'], intval( $atts['breakpoint_3_cols'] ) ),
				'autoplay' => intval( ! ! $atts['breakpoint_3_autoplay'] ),
				'autoplayHoverPause' => intval( ! ! $atts['breakpoint_3_autoplay'] ),
			),
			intval( $atts['breakpoint_3_width'] ) => array(
				'items' => min( $atts['columns'], intval( $atts['breakpoint_2_cols'] ) ),
				'autoplay' => intval( ! ! $atts['breakpoint_2_autoplay'] ),
				'autoplayHoverPause' => intval( ! ! $atts['breakpoint_2_autoplay'] ),
			),
			intval( $atts['breakpoint_2_width'] ) => array(
				'items' => min( $atts['columns'], intval( $atts['breakpoint_1_cols'] ) ),
				'autoplay' => intval( ! ! $atts['breakpoint_1_autoplay'] ),
				'autoplayHoverPause' => intval( ! ! $atts['breakpoint_1_autoplay'] ),
			),
			intval( $atts['breakpoint_1_width'] ) => array(
				'items' => $atts['columns'],
			),
		),
	);

	if ( $atts['carousel_slideby'] ) {
		$json_data['carousel_settings']['slideby'] = 'page';
	} else {
		$json_data['carousel_settings']['slideby'] = '1';
	}

	if ( $atts['carousel_autoplay'] == 1 ) {
		$json_data['carousel_settings']['timeout'] = intval( $atts['carousel_interval'] * 1000 );
		if ( $atts['carousel_autoplay_smooth'] == 1 ) {
			$json_data['carousel_settings']['smooth_play'] = '1';
		}
	}

	$output .= '<div class="w-logos-json hidden"' . us_pass_data_to_js( $json_data ) . '></div>';
}

$output .= '</div>';

// Generate responsive styles for Grid type
if ( $atts['type'] == 'grid' ) {
	$output .= '<style>
@media(max-width:' . ( intval( $atts['breakpoint_1_width'] ) - 1 ) . 'px){
.w-logos.index_' . $us_logos_index . ' .w-logos-item{width:' . 100 / intval( $atts['breakpoint_1_cols'] ) . '%}
}
@media(max-width:' . ( intval( $atts['breakpoint_2_width'] ) - 1 ) . 'px){
.w-logos.index_' . $us_logos_index . ' .w-logos-item{width:' . 100 / intval( $atts['breakpoint_2_cols'] ) . '%}
}
@media(max-width:' . ( intval( $atts['breakpoint_3_width'] ) - 1 ) . 'px){
.w-logos.index_' . $us_logos_index . ' .w-logos-item{width:' . 100 / intval( $atts['breakpoint_3_cols'] ) . '%}
}
</style>';
}
echo $output;