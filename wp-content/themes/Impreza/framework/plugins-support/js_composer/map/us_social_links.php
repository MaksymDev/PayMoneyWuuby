<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Shortcode: us_social_links
 *
 * @var   $shortcode string Current shortcode name
 * @var   $config    array Shortcode's config
 *
 * @param $config    ['atts'] array Shortcode's attributes and default values
 */

$social_links = us_config( 'social_links' );

vc_map(
	array(
		'base' => 'us_social_links',
		'name' => __( 'Social Links', 'us' ),
		'description' => '',
		'icon' => 'icon-wpb-balloon-facebook-left',
		'category' => us_translate( 'Content', 'js_composer' ),
		'weight' => 170,
		'params' => array(
			array(
				'type' => 'param_group',
				'param_name' => 'items',
				'params' => array(
					array(
						'heading' => __( 'Icon', 'us' ),
						'param_name' => 'type',
						'type' => 'dropdown',
						'value' => array_merge( array_flip( $social_links ), array( __( 'Custom Icon', 'us' ) => 'custom' ) ),
						'std' => '',
						'edit_field_class' => 'vc_col-sm-6',
						'admin_label' => TRUE,
					),
					array(
						'heading' => us_translate( 'Link' ),
						'param_name' => 'url',
						'type' => 'textfield',
						'std' => '',
						'edit_field_class' => 'vc_col-sm-6',
					),
					array(
						'param_name' => 'icon',
						'type' => 'us_icon',
						'std' => $config['items_atts']['icon'],
						'dependency' => array( 'element' => 'type', 'value' => 'custom' ),
					),
					array(
						'heading' => us_translate( 'Title' ),
						'param_name' => 'title',
						'type' => 'textfield',
						'std' => '',
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array( 'element' => 'type', 'value' => 'custom' ),
					),
					array(
						'heading' => __( 'Icon Color', 'us' ),
						'param_name' => 'color',
						'type' => 'colorpicker',
						'std' => '#1abc9c',
						'edit_field_class' => 'vc_col-sm-6',
						'dependency' => array( 'element' => 'type', 'value' => 'custom' ),
					),
				),
			),
			array(
				'param_name' => 'style',
				'heading' => __( 'Icons Style', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Simple', 'us' ) => 'default',
					__( 'With outline', 'us' ) => 'outlined',
					__( 'With light background', 'us' ) => 'solid',
					__( 'With colored background', 'us' ) => 'colored',
				),
				'std' => $config['atts']['style'],
				'admin_label' => TRUE,
				'group' => us_translate( 'Appearance' ),
			),
			array(
				'param_name' => 'hover',
				'heading' => __( 'Hover Style', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Fade', 'us' ) => 'fade',
					__( 'Slide', 'us' ) => 'slide',
					us_translate( 'None' ) => 'none',
				),
				'std' => $config['atts']['hover'],
				'edit_field_class' => 'vc_col-sm-6',
				'dependency' => array(
					'element' => 'style',
					'value' => array( 'default', 'outlined', 'solid' ),
				),
				'group' => us_translate( 'Appearance' ),
			),
			array(
				'param_name' => 'color',
				'heading' => __( 'Icons Color', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Default brands colors', 'us' ) => 'brand',
					__( 'Text (theme color)', 'us' ) => 'text',
					__( 'Link (theme color)', 'us' ) => 'link',
				),
				'std' => $config['atts']['color'],
				'edit_field_class' => 'vc_col-sm-6',
				'dependency' => array(
					'element' => 'style',
					'value' => array( 'default', 'outlined', 'solid' ),
				),
				'group' => us_translate( 'Appearance' ),
			),
			array(
				'param_name' => 'size',
				'heading' => __( 'Icons Size', 'us' ),
				'description' => sprintf( __( 'Examples: %s', 'us' ), '26px, 1.3rem, 200%' ),
				'type' => 'textfield',
				'std' => $config['atts']['size'],
				'admin_label' => TRUE,
				'edit_field_class' => 'vc_col-sm-6',
				'group' => us_translate( 'Appearance' ),
			),
			array(
				'param_name' => 'gap',
				'heading' => __( 'Gap between Icons', 'us' ),
				'description' => sprintf( __( 'Examples: %s', 'us' ), '2px, 0.1em, 4%' ),
				'type' => 'textfield',
				'std' => $config['atts']['gap'],
				'edit_field_class' => 'vc_col-sm-6',
				'group' => us_translate( 'Appearance' ),
			),
			array(
				'param_name' => 'shape',
				'heading' => __( 'Icons Shape', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					__( 'Square', 'us' ) => 'square',
					__( 'Rounded Square', 'us' ) => 'rounded',
					__( 'Circle', 'us' ) => 'circle',
				),
				'std' => $config['atts']['shape'],
				'edit_field_class' => 'vc_col-sm-6',
				'group' => us_translate( 'Appearance' ),
			),
			array(
				'param_name' => 'align',
				'heading' => __( 'Icons Alignment', 'us' ),
				'type' => 'dropdown',
				'value' => array(
					us_translate( 'Left' ) => 'left',
					us_translate( 'Center' ) => 'center',
					us_translate( 'Right' ) => 'right',
				),
				'std' => $config['atts']['align'],
				'edit_field_class' => 'vc_col-sm-6',
				'group' => us_translate( 'Appearance' ),
			),
			array(
				'param_name' => 'el_class',
				'heading' => us_translate( 'Extra class name', 'js_composer' ),
				'type' => 'textfield',
				'std' => $config['atts']['el_class'],
				'group' => us_translate( 'Appearance' ),
			),
		),
	)
);
