<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_breadcrumbs
 *
 * @var   $shortcode string Current shortcode name
 * @var   $config    array Shortcode's config
 *
 * @param $config    ['atts'] array Shortcode's attributes and default values
 */

vc_map(
	array(
		'base' => 'us_page_title',
		'name' => __( 'Page Title', 'us' ),
		'description' => '',
		'category' => us_translate( 'Content', 'js_composer' ),
		'weight' => 114,
		'params' => array(
			array(
				'param_name' => 'font',
				'heading' => __( 'Font', 'us' ),
				'type' => 'dropdown',
				'value' => array_flip( us_get_fonts( 'without_groups' ) ),
				'std' => $config['atts']['font'],
				'admin_label' => TRUE,
			),
			array(
				'param_name' => 'text_styles',
				'type' => 'checkbox',
				'value' => array(
					__( 'Bold', 'us' ) => 'bold',
					__( 'Uppercase', 'us' ) => 'uppercase',
				),
				( ( $config['atts']['text_styles'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['text_styles'],
			),
			array(
				'param_name' => 'font_size',
				'heading' => __( 'Font Size', 'us' ),
				'description' => sprintf( __( 'Examples: %s', 'us' ), '26px, 1.3rem' ),
				'type' => 'textfield',
				'std' => $config['atts']['font_size'],
				'edit_field_class' => 'vc_col-sm-6',
				'admin_label' => TRUE,
			),
			array(
				'param_name' => 'line_height',
				'heading' => __( 'Line height', 'us' ),
				'description' => sprintf( __( 'Examples: %s', 'us' ), '36px, 1.3' ),
				'type' => 'textfield',
				'std' => $config['atts']['line_height'],
				'edit_field_class' => 'vc_col-sm-6',
				'admin_label' => TRUE,
			),
			array(
				'param_name' => 'tag',
				'heading' => __( 'HTML tag', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					'h1' => 'h1',
					'h2' => 'h2',
					'h3' => 'h3',
					'h4' => 'h4',
					'h5' => 'h5',
					'h6' => 'h6',
					'div' => 'div',
				),
				'std' => $config['atts']['tag'],
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'color',
				'heading' => us_translate( 'Color' ),
				'type' => 'colorpicker',
				'std' => $config['atts']['color'],
			),
			array(
				'param_name' => 'align',
				'heading' => us_translate( 'Alignment' ),
				'type' => 'dropdown',
				'value' => array(
					us_translate( 'Left' ) => 'left',
					us_translate( 'Center' ) => 'center',
					us_translate( 'Right' ) => 'right',
				),
				'std' => $config['atts']['align'],
				'admin_label' => TRUE,
			),
			array(
				'param_name' => 'inline',
				'type' => 'checkbox',
				'value' => array( __( 'Show the next text in the same line', 'us' ) => TRUE ),
				( ( $config['atts']['inline'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['inline'],
				'dependency' => array( 'element' => 'align', 'value' => array( 'left', 'right' ) ),
			),
			array(
				'param_name' => 'description',
				'type' => 'checkbox',
				'value' => array( __( 'Show archive pages description', 'us' ) => TRUE ),
				( ( $config['atts']['description'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['description'],
			),
			array(
				'param_name' => 'el_class',
				'heading' => us_translate( 'Extra class name', 'js_composer' ),
				'type' => 'textfield',
				'std' => $config['atts']['el_class'],
			),
		),
	)
);
