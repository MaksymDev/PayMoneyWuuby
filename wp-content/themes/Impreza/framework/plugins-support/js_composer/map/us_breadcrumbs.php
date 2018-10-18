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
		'base' => 'us_breadcrumbs',
		'name' => __( 'Breadcrumbs', 'us' ),
		'description' => '',
		'category' => us_translate( 'Content', 'js_composer' ),
		'weight' => 113,
		'params' => array(
			array(
				'param_name' => 'home',
				'heading' => __( 'Homepage Label', 'us' ),
				'description' => __( 'Leave blank to hide the homepage link', 'us' ),
				'type' => 'textfield',
				'std' => $config['atts']['home'],
			),
			array(
				'param_name' => 'show_current',
				'type' => 'checkbox',
				'value' => array( __( 'Show current page', 'us' ) => TRUE ),
				( ( $config['atts']['show_current'] !== FALSE ) ? 'std' : '_std' ) => $config['atts']['show_current'],
			),
			array(
				'param_name' => 'font_size',
				'heading' => __( 'Font Size', 'us' ),
				'description' => sprintf( __( 'Examples: %s', 'us' ), '13px, 0.9rem' ),
				'type' => 'textfield',
				'std' => $config['atts']['font_size'],
				'edit_field_class' => 'vc_col-sm-6',
				'admin_label' => TRUE,
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
				'edit_field_class' => 'vc_col-sm-6',
				'admin_label' => TRUE,
			),
			array(
				'param_name' => 'separator_type',
				'heading' => __( 'Separator between items', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Icon', 'us' ) => 'icon',
					__( 'Custom symbol', 'us' ) => 'custom',
				),
				'std' => $config['atts']['separator_type'],
			),
			array(
				'param_name' => 'separator_icon',
				'type' => 'us_icon',
				'std' => $config['atts']['separator_icon'],
				'dependency' => array( 'element' => 'separator_type', 'value' => 'icon' ),
			),
			array(
				'param_name' => 'separator_symbol',
				'type' => 'textfield',
				'std' => $config['atts']['separator_symbol'],
				'dependency' => array( 'element' => 'separator_type', 'value' => 'custom' ),
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
