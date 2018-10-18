<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_separator
 *
 * @var   $shortcode string Current shortcode name
 * @var   $config    array Shortcode's config
 *
 * @param $config    ['atts'] array Shortcode's attributes and default values
 */
vc_map(
	array(
		'base' => 'us_separator',
		'name' => __( 'Separator', 'us' ),
		'description' => '',
		'icon' => 'icon-wpb-ui-separator',
		'category' => us_translate( 'Content', 'js_composer' ),
		'show_settings_on_create' => FALSE,
		'weight' => 340,
		'params' => array(
			array(
				'param_name' => 'type',
				'heading' => us_translate( 'Type' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Invisible', 'us' ) => 'invisible',
					__( 'Standard Line', 'us' ) => 'default',
					__( 'Full Width Line', 'us' ) => 'fullwidth',
					__( 'Short Line', 'us' ) => 'short',
				),
				'std' => $config['atts']['type'],
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'size',
				'heading' => us_translate( 'Size' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Small', 'us' ) => 'small',
					__( 'Medium', 'us' ) => 'medium',
					__( 'Large', 'us' ) => 'large',
					__( 'Huge', 'us' ) => 'huge',
				),
				'std' => $config['atts']['size'],
				'admin_label' => TRUE,
				'edit_field_class' => 'vc_col-sm-6',
			),
			array(
				'param_name' => 'thick',
				'heading' => __( 'Line Thickness', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					'1px' => '1',
					'2px' => '2',
					'3px' => '3',
					'4px' => '4',
					'5px' => '5',
				),
				'std' => $config['atts']['thick'],
				'edit_field_class' => 'vc_col-sm-6',
				'dependency' => array(
					'element' => 'type',
					'value' => array(
						'default',
						'fullwidth',
						'short',
					),
				),
			),
			array(
				'param_name' => 'style',
				'heading' => __( 'Line Style', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Solid', 'us' ) => 'solid',
					__( 'Dashed', 'us' ) => 'dashed',
					__( 'Dotted', 'us' ) => 'dotted',
					__( 'Double', 'us' ) => 'double',
				),
				'std' => $config['atts']['style'],
				'edit_field_class' => 'vc_col-sm-6',
				'dependency' => array(
					'element' => 'type',
					'value' => array(
						'default',
						'fullwidth',
						'short',
					),
				),
			),
			array(
				'param_name' => 'color',
				'heading' => __( 'Line Color', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Border (theme color)', 'us' ) => 'border',
					__( 'Text (theme color)', 'us' ) => 'text',
					__( 'Primary (theme color)', 'us' ) => 'primary',
					__( 'Secondary (theme color)', 'us' ) => 'secondary',
					us_translate( 'Custom color' ) => 'custom',
				),
				'std' => $config['atts']['color'],
				'dependency' => array(
					'element' => 'type',
					'value' => array(
						'default',
						'fullwidth',
						'short',
					),
				),
			),
			array(
				'param_name' => 'bdcolor',
				'type' => 'colorpicker',
				'std' => $config['atts']['bdcolor'],
				'edit_field_class' => 'vc_col-sm-6',
				'dependency' => array(
					'element' => 'color',
					'value' => array(
						'custom',
					),
				),
			),
			array(
				'param_name' => 'el_class',
				'heading' => us_translate( 'Extra class name', 'js_composer' ),
				'type' => 'textfield',
				'std' => $config['atts']['el_class'],
			),
			array(
				'param_name' => 'icon',
				'heading' => __( 'Icon', 'us' ),
				'type' => 'us_icon',
				'std' => $config['atts']['icon'],
				'dependency' => array(
					'element' => 'type',
					'value' => array(
						'default',
						'fullwidth',
						'short',
					),
				),
				'group' => __( 'Icon and Title', 'us' ),
			),
			array(
				'param_name' => 'text',
				'heading' => us_translate( 'Title' ),
				'type' => 'textfield',
				'std' => $config['atts']['text'],
				'holder' => 'div',
				'dependency' => array(
					'element' => 'type',
					'value' => array(
						'default',
						'fullwidth',
						'short',
					),
				),
				'group' => __( 'Icon and Title', 'us' ),
			),
			array(
				'param_name' => 'link',
				'heading' => us_translate( 'Link' ),
				'type' => 'vc_link',
				'std' => $config['atts']['link'],
				'dependency' => array( 'element' => 'text', 'not_empty' => TRUE ),
				'group' => __( 'Icon and Title', 'us' ),
			),
			array(
				'param_name' => 'title_tag',
				'heading' => __( 'Title HTML tag', 'us' ),
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
				'dependency' => array( 'element' => 'text', 'not_empty' => TRUE ),
				'group' => __( 'Icon and Title', 'us' ),
			),
			array(
				'param_name' => 'title_size',
				'heading' => __( 'Font Size', 'us' ),
				'description' => sprintf( __( 'Examples: %s', 'us' ), '26px, 1.3em, 200%' ),
				'type' => 'textfield',
				'std' => $config['atts']['title_size'],
				'edit_field_class' => 'vc_col-sm-6',
				'dependency' => array(
					'element' => 'type',
					'value' => array(
						'default',
						'fullwidth',
						'short',
					),
				),
				'group' => __( 'Icon and Title', 'us' ),
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
				'dependency' => array(
					'element' => 'type',
					'value' => array(
						'default',
						'fullwidth',
						'short',
					),
				),
				'group' => __( 'Icon and Title', 'us' ),
			),
		),
	)
);
