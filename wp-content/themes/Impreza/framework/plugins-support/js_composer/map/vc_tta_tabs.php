<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Modifying shortcode: vc_tta_tabs
 *
 * @var   $shortcode string Current shortcode name
 * @var   $config    array Shortcode's config
 *
 * @param $config    ['atts'] array Shortcode's attributes and default values
 */
if ( version_compare( WPB_VC_VERSION, '4.6', '<' ) ) {
	// Oops: the modified shorcode doesn't exist in current VC version. Doing nothing.
	return;
}

if ( ! vc_is_page_editable() ) {
	vc_remove_param( 'vc_tta_tabs', 'title' );
	vc_remove_param( 'vc_tta_tabs', 'style' );
	vc_remove_param( 'vc_tta_tabs', 'shape' );
	vc_remove_param( 'vc_tta_tabs', 'color' );
	vc_remove_param( 'vc_tta_tabs', 'no_fill_content_area' );
	vc_remove_param( 'vc_tta_tabs', 'spacing' );
	vc_remove_param( 'vc_tta_tabs', 'gap' );
	vc_remove_param( 'vc_tta_tabs', 'tab_position' );
	vc_remove_param( 'vc_tta_tabs', 'alignment' );
	vc_remove_param( 'vc_tta_tabs', 'autoplay' );
	vc_remove_param( 'vc_tta_tabs', 'active_section' );
	vc_remove_param( 'vc_tta_tabs', 'pagination_style' );
	vc_remove_param( 'vc_tta_tabs', 'pagination_color' );
	vc_remove_param( 'vc_tta_tabs', 'css_animation' );
	vc_add_params(
		'vc_tta_tabs', array(
		array(
			'param_name' => 'layout',
			'heading' => __( 'Tabs Layout', 'us' ),
			'type' => 'dropdown',
			'value' => array(
				__( 'Simple', 'us' ) => 'default',
				__( 'Modern', 'us' ) => 'modern',
				__( 'Trendy', 'us' ) => 'trendy',
				__( 'Timeline', 'us' ) => 'timeline',
			),
			'std' => $config['atts']['layout'],
			'weight' => 180,
		),
		array(
			'param_name' => 'stretch',
			'type' => 'checkbox',
			'value' => array( __( 'Stretch tabs to the full available width', 'us' ) => TRUE ),
			( ( $config['atts']['stretch'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['stretch'],
			'weight' => 170,
		),
		array(
			'param_name' => 'title_font',
			'heading' => __( 'Tabs Title Font', 'us' ),
			'type' => 'dropdown',
			'value' => array_flip( us_get_fonts( 'without_groups' ) ),
			'std' => $config['atts']['title_font'],
			'weight' => 160,
		),
		array(
			'param_name' => 'title_text_styles',
			'type' => 'checkbox',
			'value' => array(
				__( 'Bold', 'us' ) => 'bold',
				__( 'Uppercase', 'us' ) => 'uppercase',
			),
			( ( $config['atts']['title_text_styles'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['title_text_styles'],
			'weight' => 150,
		),
		array(
			'param_name' => 'title_size',
			'heading' => __( 'Tabs Title Size', 'us' ),
			'description' => sprintf( __( 'Examples: %s', 'us' ), '26px, 1.3em, 2rem' ),
			'type' => 'textfield',
			'std' => $config['atts']['title_size'],
			'edit_field_class' => 'vc_col-sm-6',
			'weight' => 140,
		),
		array(
			'param_name' => 'title_tag',
			'heading' => __( 'Sections Title HTML tag', 'us' ),
			'description' => __( 'Used for SEO purposes', 'us' ),
			'type' => 'dropdown',
			'value' => array(
				'h1' => 'h1',
				'h2' => 'h2',
				'h3' => 'h3',
				'h4' => 'h4',
				'h5' => 'h5',
				'h6' => 'h6',
				'p' => 'p',
				'div' => 'div',
			),
			'std' => $config['atts']['title_tag'],
			'edit_field_class' => 'vc_col-sm-6',
			'weight' => 130,
		),
	)
	);
}

// Setting proper shortcode order in VC shortcodes listing
vc_map_update( 'vc_tta_tabs', array( 'weight' => 320 ) );
