<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Grid Layout and Elements Options.
 * Options and elements' fields are described in USOF-style format.
 */

// Set hidden class if woocommerce disabled
if ( class_exists( 'woocommerce' ) ) {
	$hide_if_no_woocommerce = '';
} else {
	$hide_if_no_woocommerce = 'hidden';
}

// Generate posts taxonomies
$taxonomies_options = us_get_taxonomies();

// Custom Fields configuration
$custom_fields_options = us_get_custom_fields();

// Design Options as separate variable
$design_options_config = array(
	'design_options' => array(
		'type' => 'design_options',
		'std' => '',
		'states' => array( 'default' ),
		'with_position' => TRUE,
		'group' => __( 'Design Options', 'us' ),
	),
	'color_bg' => array(
		'title' => __( 'Background Сolor', 'us' ),
		'type' => 'color',
		'std' => '',
		'classes' => 'cols_2 clear_right',
		'group' => __( 'Design Options', 'us' ),
	),
	'color_border' => array(
		'title' => __( 'Border Сolor', 'us' ),
		'type' => 'color',
		'std' => '',
		'classes' => 'cols_2 clear_right',
		'group' => __( 'Design Options', 'us' ),
	),
	'color_text' => array(
		'title' => __( 'Text Сolor', 'us' ),
		'type' => 'color',
		'std' => '',
		'classes' => 'cols_2 clear_right',
		'group' => __( 'Design Options', 'us' ),
	),
	'width' => array(
		'title' => __( 'Custom Width', 'us' ),
		'type' => 'text',
		'std' => '',
		'description' => sprintf( __( 'Examples: %s', 'us' ), '200px, 4rem, 30%' ),
		'classes' => 'cols_2 desc_1',
		'group' => __( 'Design Options', 'us' ),
	),
	'border_radius' => array(
		'title' => __( 'Corners Radius', 'us' ),
		'type' => 'slider',
		'min' => 0.0,
		'max' => 5.0,
		'std' => 0.0,
		'step' => 0.1,
		'postfix' => 'rem',
		'group' => __( 'Design Options', 'us' ),
	),
	'hide_below' => array(
		'title' => __( 'Hide the element when the screen width is less than', 'us' ),
		'type' => 'slider',
		'min' => 0,
		'max' => 1600,
		'std' => 0,
		'step' => 20,
		'postfix' => 'px',
		'group' => __( 'Design Options', 'us' ),
	),
	'el_class' => array(
		'title' => us_translate( 'Extra class name', 'js_composer' ),
		'type' => 'text',
		'std' => '',
		'group' => __( 'Design Options', 'us' ),
	),
);

// Hover Options as separate variable
$hover_options_config = array(
	'hover' => array(
		'text' => __( 'Enable hover effect', 'us' ),
		'description' => __( 'Change appearance of this element when hover on the whole Grid Layout', 'us' ),
		'type' => 'switch',
		'std' => 0,
		'classes' => 'desc_1',
		'group' => __( 'Hover Effect', 'us' ),
	),
	'opacity' => array(
		'title' => __( 'Opacity', 'us' ),
		'type' => 'slider',
		'min' => 0.00,
		'max' => 1.00,
		'step' => 0.05,
		'std' => 1.00,
		'classes' => 'cols_2',
		'show_if' => array( 'hover', '=', TRUE ),
		'group' => __( 'Hover Effect', 'us' ),
	),
	'opacity_hover' => array(
		'title' => __( 'Opacity on Hover', 'us' ),
		'type' => 'slider',
		'min' => 0.00,
		'max' => 1.00,
		'step' => 0.05,
		'std' => 1.00,
		'classes' => 'cols_2',
		'show_if' => array( 'hover', '=', TRUE ),
		'group' => __( 'Hover Effect', 'us' ),
	),
	'scale' => array(
		'title' => __( 'Scale', 'us' ),
		'type' => 'slider',
		'min' => 0.00,
		'max' => 2.00,
		'step' => 0.05,
		'std' => 1.00,
		'classes' => 'cols_2',
		'show_if' => array( 'hover', '=', TRUE ),
		'group' => __( 'Hover Effect', 'us' ),
	),
	'scale_hover' => array(
		'title' => __( 'Scale on Hover', 'us' ),
		'type' => 'slider',
		'min' => 0.00,
		'max' => 2.00,
		'step' => 0.05,
		'std' => 1.00,
		'classes' => 'cols_2',
		'show_if' => array( 'hover', '=', TRUE ),
		'group' => __( 'Hover Effect', 'us' ),
	),
	'translateX' => array(
		'title' => __( 'Horizontal Shift', 'us' ),
		'type' => 'slider',
		'min' => -100,
		'max' => 100,
		'std' => 0,
		'postfix' => '%',
		'classes' => 'cols_2',
		'show_if' => array( 'hover', '=', TRUE ),
		'group' => __( 'Hover Effect', 'us' ),
	),
	'translateX_hover' => array(
		'title' => __( 'Horizontal Shift on Hover', 'us' ),
		'type' => 'slider',
		'min' => -100,
		'max' => 100,
		'std' => 0,
		'postfix' => '%',
		'classes' => 'cols_2',
		'show_if' => array( 'hover', '=', TRUE ),
		'group' => __( 'Hover Effect', 'us' ),
	),
	'translateY' => array(
		'title' => __( 'Vertical Shift', 'us' ),
		'type' => 'slider',
		'min' => -100,
		'max' => 100,
		'std' => 0,
		'postfix' => '%',
		'classes' => 'cols_2',
		'show_if' => array( 'hover', '=', TRUE ),
		'group' => __( 'Hover Effect', 'us' ),
	),
	'translateY_hover' => array(
		'title' => __( 'Vertical Shift on Hover', 'us' ),
		'type' => 'slider',
		'min' => -100,
		'max' => 100,
		'std' => 0,
		'postfix' => '%',
		'classes' => 'cols_2',
		'show_if' => array( 'hover', '=', TRUE ),
		'group' => __( 'Hover Effect', 'us' ),
	),
	'color_bg_hover' => array(
		'title' => __( 'Background Сolor on Hover', 'us' ),
		'type' => 'color',
		'std' => '',
		'classes' => 'clear_right cols_2',
		'show_if' => array( 'hover', '=', TRUE ),
		'group' => __( 'Hover Effect', 'us' ),
	),
	'color_border_hover' => array(
		'title' => __( 'Border Сolor on Hover', 'us' ),
		'type' => 'color',
		'std' => '',
		'classes' => 'clear_right cols_2',
		'show_if' => array( 'hover', '=', TRUE ),
		'group' => __( 'Hover Effect', 'us' ),
	),
	'color_text_hover' => array(
		'title' => __( 'Text Сolor on Hover', 'us' ),
		'type' => 'color',
		'std' => '',
		'classes' => 'clear_right cols_2',
		'show_if' => array( 'hover', '=', TRUE ),
		'group' => __( 'Hover Effect', 'us' ),
	),
	'transition_duration' => array(
		'title' => __( 'Hover Transition Duration', 'us' ),
		'type' => 'slider',
		'min' => 0.00,
		'max' => 2.00,
		'step' => 0.05,
		'std' => 0.30,
		'postfix' => 's',
		'classes' => 'cols_2',
		'show_if' => array( 'hover', '=', TRUE ),
		'group' => __( 'Hover Effect', 'us' ),
	),
);

return array(

	// General Options
	'options' => array(
		'global' => array(
			'fixed' => array(
				'text' => __( 'Set Aspect Ratio', 'us' ),
				'type' => 'switch',
				'std' => 0,
			),
			'ratio' => array(
				'type' => 'select',
				'options' => array(
					'4x3' => __( '4:3 (landscape)', 'us' ),
					'3x2' => __( '3:2 (landscape)', 'us' ),
					'1x1' => __( '1:1 (square)', 'us' ),
					'2x3' => __( '2:3 (portrait)', 'us' ),
					'3x4' => __( '3:4 (portrait)', 'us' ),
					'16x9' => '16:9',
					'custom' => __( 'Custom', 'us' ),
				),
				'std' => '1x1',
				'classes' => 'for_above',
				'show_if' => array( 'fixed', '=', TRUE ),
			),
			'ratio_width' => array(
				'placeholder' => us_translate( 'Width' ),
				'type' => 'text',
				'std' => '21',
				'show_if' => array(
					array( 'fixed', '=', TRUE ),
					'and',
					array( 'ratio', '=', 'custom' ),
				),
			),
			'ratio_height' => array(
				'placeholder' => us_translate( 'Height' ),
				'type' => 'text',
				'std' => '9',
				'show_if' => array(
					array( 'fixed', '=', TRUE ),
					'and',
					array( 'ratio', '=', 'custom' ),
				),
			),
			'overflow' => array(
				'text' => __( 'Hide Overflowing Content', 'us' ),
				'type' => 'switch',
				'std' => 0,
				'show_if' => array( 'fixed', '=', FALSE ),
			),
			'link' => array(
				'title' => __( 'Overriding Link', 'us' ),
				'description' => __( 'Applies to the whole Grid Layout. All layout elements become not clickable.', 'us' ),
				'type' => 'select',
				'options' => array(
					'none' => us_translate( 'None' ),
					'post' => __( 'To a Post', 'us' ),
					'popup_post' => __( 'Opens a Post in a popup', 'us' ),
					'popup_post_image' => __( 'Opens a Post Image in a popup', 'us' ),
				),
				'std' => 'none',
				'classes' => 'desc_4',
			),
			'popup_width' => array(
				'title' => __( 'Popup Width', 'us' ),
				'description' => __( 'If left blank, popup will be stretched to the screen width', 'us' ),
				'type' => 'text',
				'std' => '',
				'classes' => 'desc_4',
				'show_if' => array( 'link', '=', 'popup_post' ),
			),
			'color_bg' => array(
				'title' => __( 'Background Color', 'us' ),
				'type' => 'color',
				'std' => '',
				'classes' => 'clear_right',
			),
			'color_text' => array(
				'title' => __( 'Text Color', 'us' ),
				'type' => 'color',
				'std' => '',
				'classes' => 'clear_right',
			),
			'border_radius' => array(
				'title' => __( 'Corners Radius', 'us' ),
				'type' => 'slider',
				'min' => 0.0,
				'max' => 3.0,
				'step' => 0.1,
				'std' => 0.0,
				'postfix' => 'rem',
			),
			'box_shadow' => array(
				'title' => __( 'Shadow', 'us' ),
				'type' => 'slider',
				'min' => 0.0,
				'max' => 3.0,
				'step' => 0.1,
				'std' => 0.0,
				'postfix' => 'rem',
			),
			'box_shadow_hover' => array(
				'title' => __( 'Shadow on Hover', 'us' ),
				'type' => 'slider',
				'min' => 0.0,
				'max' => 3.0,
				'step' => 0.1,
				'std' => 0.0,
				'postfix' => 'rem',
			),
		),
	),

	// Elements Configuration
	'elements' => array(

		// Post Image
		'post_image' => array(
			'title' => __( 'Post Image', 'us' ),
			'params' => array_merge( array(
				'link' => array(
					'title' => us_translate( 'Link' ),
					'type' => 'radio',
					'options' => array(
						'post' => __( 'To a Post', 'us' ),
						'custom' => __( 'Custom', 'us' ),
						'none' => us_translate( 'None' ),
					),
					'std' => 'post',
				),
				'custom_link' => array(
					'placeholder' => us_translate( 'Enter the URL' ),
					'type' => 'link',
					'std' => array(),
					'show_if' => array( 'link', '=', 'custom' ),
				),
				'placeholder' => array(
					'type' => 'switch',
					'text' => __( 'Show placeholder when post image is absent', 'us' ),
					'std' => 0,
				),
				'media_preview' => array(
					'type' => 'switch',
					'text' => __( 'Show media preview for posts with video, audio and gallery format', 'us' ),
					'std' => 0,
				),
				'circle' => array(
					'type' => 'switch',
					'text' => __( 'Enable rounded image', 'us' ),
					'std' => 0,
				),
				'thumbnail_size' => array(
					'title' => __( 'Image Size', 'us' ),
					'description' => '<a target="_blank" href="' . admin_url( 'admin.php?page=us-theme-options' ) . '#advanced">' . __( 'Edit image sizes', 'us' ) . '</a>.',
					'type' => 'select',
					'options' => array_flip( us_image_sizes_select_values() ),
					'std' => 'large',
					'classes' => 'desc_1',
				),
			), $design_options_config, $hover_options_config ),
		),

		// Post Title
		'post_title' => array(
			'title' => __( 'Post Title', 'us' ),
			'params' => array_merge( array(
				'link' => array(
					'title' => us_translate( 'Link' ),
					'type' => 'radio',
					'options' => array(
						'post' => __( 'To a Post', 'us' ),
						'custom' => __( 'Custom', 'us' ),
						'none' => us_translate( 'None' ),
					),
					'std' => 'post',
				),
				'custom_link' => array(
					'placeholder' => us_translate( 'Enter the URL' ),
					'type' => 'link',
					'std' => array(),
					'show_if' => array( 'link', '=', 'custom' ),
				),
				'color_link' => array(
					'title' => __( 'Link Color', 'us' ),
					'type' => 'switch',
					'text' => __( 'Inherit from text color', 'us' ),
					'std' => 1,
					'show_if' => array( 'link', '!=', 'none' ),
				),
				'font' => array(
					'title' => __( 'Font', 'us' ),
					'type' => 'select',
					'options' => us_get_fonts(),
					'std' => 'heading',
				),
				'text_styles' => array(
					'type' => 'checkboxes',
					'options' => array(
						'bold' => __( 'Bold', 'us' ),
						'uppercase' => __( 'Uppercase', 'us' ),
						'italic' => __( 'Italic', 'us' ),
					),
					'std' => array(),
					'classes' => 'for_above',
				),
				'font_size' => array(
					'title' => __( 'Font Size', 'us' ),
					'type' => 'text',
					'std' => '1.2rem',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
				),
				'font_size_mobiles' => array(
					'title' => __( 'Font Size on Mobiles', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
				),
				'line_height' => array(
					'title' => __( 'Line height', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '30px, 1.7' ),
					'classes' => 'cols_2 desc_1',
				),
				'line_height_mobiles' => array(
					'title' => __( 'Line height on Mobiles', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '30px, 1.7' ),
					'classes' => 'cols_2 desc_1',
				),
				'tag' => array(
					'title' => __( 'HTML tag', 'us' ),
					'type' => 'radio',
					'options' => array(
						'div' => 'div',
						'h1' => 'h1',
						'h2' => 'h2',
						'h3' => 'h3',
						'h4' => 'h4',
						'h5' => 'h5',
						'h6' => 'h6',
					),
					'std' => 'h2',
				),
			), $design_options_config, $hover_options_config ),
		),

		// Post Date
		'post_date' => array(
			'title' => __( 'Post Date', 'us' ),
			'params' => array_merge( array(
				'type' => array(
					'type' => 'radio',
					'options' => array(
						'published' => __( 'Date of creation', 'us' ),
						'modified' => __( 'Date of update', 'us' ),
					),
					'std' => 'published',
				),
				'format' => array(
					'title' => __( 'Format', 'us' ),
					'type' => 'select',
					'options' => array(
						'default' => us_translate( 'Default' ) . ': ' . date_i18n( get_option( 'date_format' ) ),
						'jS F Y' => date_i18n( 'jS F Y' ),
						'j M, G:i' => date_i18n( 'j M, G:i' ),
						'm/d/Y' => date_i18n( 'm/d/Y' ),
						'j.m.y' => date_i18n( 'j.m.y' ),
						'custom' => __( 'Custom', 'us' ),
					),
					'std' => 'default',
				),
				'format_custom' => array(
					'description' => '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">' . __( 'Documentation on date and time formatting.', 'us' ) . '</a>',
					'type' => 'text',
					'std' => 'F j, Y',
					'classes' => 'desc_1',
					'show_if' => array( 'format', '=', 'custom' ),
				),
				'text_styles' => array(
					'title' => __( 'Text Styles', 'us' ),
					'type' => 'checkboxes',
					'options' => array(
						'bold' => __( 'Bold', 'us' ),
						'uppercase' => __( 'Uppercase', 'us' ),
						'italic' => __( 'Italic', 'us' ),
					),
					'std' => array(),
				),
				'font_size' => array(
					'title' => __( 'Font Size', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
				),
				'font_size_mobiles' => array(
					'title' => __( 'Font Size on Mobiles', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
				),
				'icon' => array(
					'title' => __( 'Icon', 'us' ),
					'type' => 'icon',
					'std' => '',
				),
			), $design_options_config, $hover_options_config ),
		),

		// Post Taxonomy
		'post_taxonomy' => array(
			'title' => __( 'Post Taxonomy', 'us' ),
			'params' => array_merge( array(
				'taxonomy_name' => array(
					'title' => us_translate( 'Show' ),
					'type' => 'select',
					'options' => $taxonomies_options,
					'std' => key( $taxonomies_options ),
				),
				'style' => array(
					'title' => us_translate( 'Style' ),
					'type' => 'radio',
					'options' => array(
						'simple' => __( 'Simple', 'us' ),
						'badge' => __( 'Badges', 'us' ),
					),
					'std' => 'simple',
				),
				'separator' => array(
					'title' => __( 'Separator between items', 'us' ),
					'type' => 'text',
					'std' => ', ',
					'show_if' => array( 'style', '=', 'simple' ),
				),
				'link' => array(
					'title' => us_translate( 'Link' ),
					'type' => 'radio',
					'options' => array(
						'post' => __( 'To a Post', 'us' ),
						'archive' => __( 'To a Post Archive', 'us' ),
						'custom' => __( 'Custom', 'us' ),
						'none' => us_translate( 'None' ),
					),
					'std' => 'archive',
				),
				'custom_link' => array(
					'placeholder' => us_translate( 'Enter the URL' ),
					'type' => 'link',
					'std' => array(),
					'show_if' => array( 'link', '=', 'custom' ),
				),
				'color_link' => array(
					'title' => __( 'Link Color', 'us' ),
					'type' => 'switch',
					'text' => __( 'Inherit from text color', 'us' ),
					'std' => 1,
					'show_if' => array( 'link', '!=', 'none' ),
				),
				'text_styles' => array(
					'title' => __( 'Text Styles', 'us' ),
					'type' => 'checkboxes',
					'options' => array(
						'bold' => __( 'Bold', 'us' ),
						'uppercase' => __( 'Uppercase', 'us' ),
						'italic' => __( 'Italic', 'us' ),
					),
					'std' => array(),
				),
				'font_size' => array(
					'title' => __( 'Font Size', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
				),
				'font_size_mobiles' => array(
					'title' => __( 'Font Size on Mobiles', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
				),
				'icon' => array(
					'title' => __( 'Icon', 'us' ),
					'type' => 'icon',
					'std' => '',
				),
			), $design_options_config, $hover_options_config ),
		),

		// Post Author
		'post_author' => array(
			'title' => __( 'Post Author', 'us' ),
			'params' => array_merge( array(
				'link' => array(
					'title' => us_translate( 'Link' ),
					'type' => 'select',
					'options' => array(
						'author_page' => __( 'To the page with the Author\'s posts', 'us' ),
						'author_website' => __( 'To the Author\'s website (if specified on his profile)', 'us' ),
						'post' => __( 'To a Post', 'us' ),
						'custom' => __( 'Custom', 'us' ),
						'none' => us_translate( 'None' ),
					),
					'std' => 'author_page',
				),
				'custom_link' => array(
					'placeholder' => us_translate( 'Enter the URL' ),
					'type' => 'link',
					'std' => array(),
					'show_if' => array( 'link', '=', 'custom' ),
				),
				'color_link' => array(
					'title' => __( 'Link Color', 'us' ),
					'type' => 'switch',
					'text' => __( 'Inherit from text color', 'us' ),
					'std' => 1,
					'show_if' => array( 'link', '!=', 'none' ),
				),
				'text_styles' => array(
					'title' => __( 'Text Styles', 'us' ),
					'type' => 'checkboxes',
					'options' => array(
						'bold' => __( 'Bold', 'us' ),
						'uppercase' => __( 'Uppercase', 'us' ),
						'italic' => __( 'Italic', 'us' ),
					),
					'std' => array(),
				),
				'font_size' => array(
					'title' => __( 'Font Size', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
				),
				'font_size_mobiles' => array(
					'title' => __( 'Font Size on Mobiles', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
				),
				'icon' => array(
					'title' => __( 'Icon', 'us' ),
					'type' => 'icon',
					'std' => '',
				),
			), $design_options_config, $hover_options_config ),
		),

		// Post Comments
		'post_comments' => array(
			'title' => __( 'Post Comments', 'us' ),
			'params' => array_merge( array(
				'number' => array(
					'type' => 'switch',
					'text' => __( 'Show only number', 'us' ),
					'std' => 0,
				),
				'link' => array(
					'title' => us_translate( 'Link' ),
					'type' => 'radio',
					'options' => array(
						'post' => __( 'To a Post', 'us' ),
						'custom' => __( 'Custom', 'us' ),
						'none' => us_translate( 'None' ),
					),
					'std' => 'post',
				),
				'custom_link' => array(
					'placeholder' => us_translate( 'Enter the URL' ),
					'type' => 'link',
					'std' => array(),
					'show_if' => array( 'link', '=', 'custom' ),
				),
				'color_link' => array(
					'title' => __( 'Link Color', 'us' ),
					'type' => 'switch',
					'text' => __( 'Inherit from text color', 'us' ),
					'std' => 1,
					'show_if' => array( 'link', '!=', 'none' ),
				),
				'text_styles' => array(
					'title' => __( 'Text Styles', 'us' ),
					'type' => 'checkboxes',
					'options' => array(
						'bold' => __( 'Bold', 'us' ),
						'uppercase' => __( 'Uppercase', 'us' ),
						'italic' => __( 'Italic', 'us' ),
					),
					'std' => array(),
				),
				'font_size' => array(
					'title' => __( 'Font Size', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
				),
				'font_size_mobiles' => array(
					'title' => __( 'Font Size on Mobiles', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
				),
				'icon' => array(
					'title' => __( 'Icon', 'us' ),
					'type' => 'icon',
					'std' => '',
				),
			), $design_options_config, $hover_options_config ),
		),

		// Post Content
		'post_content' => array(
			'title' => __( 'Post Content', 'us' ),
			'params' => array_merge( array(
				'type' => array(
					'title' => us_translate( 'Show' ),
					'type' => 'select',
					'options' => array(
						'excerpt_content' => __( 'Excerpt, if it\'s empty, show part of a content', 'us' ),
						'excerpt_only' => __( 'Excerpt, if it\'s empty, show nothing', 'us' ),
						'part_content' => __( 'Part of a content', 'us' ),
						'full_content' => __( 'Full content', 'us' ),
					),
					'std' => 'excerpt_content',
				),
				'length' => array(
					'title' => __( 'Amount of words', 'us' ),
					'description' => __( 'All HTML tags of a content will be stripped', 'us' ),
					'type' => 'slider',
					'min' => 1,
					'max' => 100,
					'std' => 30,
					'classes' => 'desc_1',
					'show_if' => array( 'type', 'in', array( 'excerpt_content', 'part_content' ) ),
				),
				'font_size' => array(
					'title' => __( 'Font Size', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
				),
				'font_size_mobiles' => array(
					'title' => __( 'Font Size on Mobiles', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
				),
				'line_height' => array(
					'title' => __( 'Line height', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '30px, 1.7' ),
					'classes' => 'cols_2 desc_1',
				),
				'line_height_mobiles' => array(
					'title' => __( 'Line height on Mobiles', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '30px, 1.7' ),
					'classes' => 'cols_2 desc_1',
				),
			), $design_options_config, $hover_options_config ),
		),

		// Post Custom Field
		'post_custom_field' => array(
			'title' => __( 'Post Custom Field', 'us' ),
			'params' => array_merge( array(
				'key' => array(
					'title' => us_translate( 'Show' ),
					'type' => 'select',
					'options' => $custom_fields_options,
					'std' => key( $custom_fields_options ),
				),
				'custom_key' => array(
					'title' => __( 'Custom Field Name', 'us' ),
					'description' => __( 'Enter custom field name to retrieve meta data value.', 'us' ),
					'type' => 'text',
					'std' => '',
					'classes' => 'desc_1',
					'show_if' => array( 'key', '=', 'custom' ),
				),
				'link' => array(
					'title' => us_translate( 'Link' ),
					'type' => 'radio',
					'options' => array(
						'post' => __( 'To a Post', 'us' ),
						'custom' => __( 'Custom', 'us' ),
						'none' => us_translate( 'None' ),
					),
					'std' => 'none',
				),
				'custom_link' => array(
					'placeholder' => us_translate( 'Enter the URL' ),
					'type' => 'link',
					'std' => array(),
					'show_if' => array( 'link', '=', 'custom' ),
				),
				'color_link' => array(
					'title' => __( 'Link Color', 'us' ),
					'type' => 'switch',
					'text' => __( 'Inherit from text color', 'us' ),
					'std' => 1,
					'show_if' => array( 'link', '!=', 'none' ),
				),
				'thumbnail_size' => array(
					'title' => __( 'Image Size', 'us' ),
					'type' => 'select',
					'options' => array_flip( us_image_sizes_select_values() ),
					'std' => 'large',
					'show_if' => array( 'key', '=', 'us_tile_additional_image' ),
				),
				'text_styles' => array(
					'title' => __( 'Text Styles', 'us' ),
					'type' => 'checkboxes',
					'options' => array(
						'bold' => __( 'Bold', 'us' ),
						'uppercase' => __( 'Uppercase', 'us' ),
						'italic' => __( 'Italic', 'us' ),
					),
					'std' => array(),
					'show_if' => array( 'key', 'in', array(
						'us_testimonial_author',
						'us_testimonial_role',
						'us_testimonial_company',
						'custom',
					) ),
				),
				'font_size' => array(
					'title' => __( 'Font Size', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
					'show_if' => array( 'key', 'in', array(
						'us_testimonial_author',
						'us_testimonial_role',
						'us_testimonial_company',
						'custom',
					) ),
				),
				'font_size_mobiles' => array(
					'title' => __( 'Font Size on Mobiles', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
					'show_if' => array( 'key', 'in', array(
						'us_testimonial_author',
						'us_testimonial_role',
						'us_testimonial_company',
						'custom',
					) ),
				),
				'icon' => array(
					'title' => __( 'Icon', 'us' ),
					'type' => 'icon',
					'std' => '',
					'show_if' => array( 'key', 'in', array(
						'us_testimonial_author',
						'us_testimonial_role',
						'us_testimonial_company',
						'custom',
					) ),
				),
			), $design_options_config, $hover_options_config ),
		),

		// Button
		'btn' => array(
			'title' => __( 'Button', 'us' ),
			'params' => array_merge( array(
				'add_to_cart' => array(
					'type' => 'switch',
					'text' => sprintf( __( 'Use "%s" button', 'us' ), us_translate( 'Add to cart', 'woocommerce' ) ),
					'std' => 0,
					'classes' => $hide_if_no_woocommerce,
				),
				'view_cart_link' => array(
					'type' => 'switch',
					'text' => __( 'Show link to cart when adding products', 'us' ),
					'std' => 0,
					'classes' => $hide_if_no_woocommerce,
					'show_if' => array( 'add_to_cart', '=', 1 ),
				),
				'label' => array(
					'title' => __( 'Button Label', 'us' ),
					'type' => 'text',
					'std' => __( 'Read More', 'us' ),
					'show_if' => array( 'add_to_cart', '=', 0 ),
				),
				'link_type' => array(
					'title' => us_translate( 'Link' ),
					'type' => 'radio',
					'options' => array(
						'post' => __( 'To a Post', 'us' ),
						'custom' => __( 'Custom', 'us' ),
					),
					'std' => 'post',
					'show_if' => array( 'add_to_cart', '=', 0 ),
				),
				'link' => array(
					'placeholder' => us_translate( 'Enter the URL' ),
					'type' => 'link',
					'std' => array(),
					'show_if' => array(
						array( 'link_type', '=', 'custom' ),
						'and',
						array( 'add_to_cart', '=', 0 )
					),
				),
				'style' => array(
					'title' => __( 'Button Style', 'us' ),
					'type' => 'radio',
					'options' => array(
						'solid' => __( 'Solid', 'us' ),
						'outlined' => __( 'Outlined', 'us' ),
					),
					'std' => 'solid',
					'classes' => 'cols_2',
					'show_if' => array( 'add_to_cart', '=', 0 ),
				),
				'color' => array(
					'title' => __( 'Button Color', 'us' ),
					'type' => 'select',
					'options' => array(
						'primary' => __( 'Primary (theme color)', 'us' ),
						'secondary' => __( 'Secondary (theme color)', 'us' ),
						'light' => __( 'Border (theme color)', 'us' ),
						'contrast' => __( 'Text (theme color)', 'us' ),
						'black' => us_translate( 'Black' ),
						'white' => us_translate( 'White' ),
						'purple' => __( 'Purple', 'us' ),
						'pink' => __( 'Pink', 'us' ),
						'red' => __( 'Red', 'us' ),
						'yellow' => __( 'Yellow', 'us' ),
						'lime' => __( 'Lime', 'us' ),
						'green' => __( 'Green', 'us' ),
						'teal' => __( 'Teal', 'us' ),
						'blue' => __( 'Blue', 'us' ),
						'navy' => __( 'Navy', 'us' ),
						'midnight' => __( 'Midnight', 'us' ),
						'brown' => __( 'Brown', 'us' ),
						'cream' => __( 'Cream', 'us' ),
					),
					'std' => 'light',
					'classes' => 'cols_2',
					'show_if' => array( 'add_to_cart', '=', 0 ),
				),
				'font_size' => array(
					'title' => __( 'Font Size', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
					'show_if' => array( 'type', '=', 'text' ),
				),
				'font_size_mobiles' => array(
					'title' => __( 'Font Size on Mobiles', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
				),
				'icon' => array(
					'title' => __( 'Icon', 'us' ),
					'type' => 'icon',
					'std' => '',
					'show_if' => array( 'add_to_cart', '=', 0 ),
				),
				'iconpos' => array(
					'title' => __( 'Icon Position', 'us' ),
					'type' => 'radio',
					'options' => array(
						'left' => us_translate( 'Left' ),
						'right' => us_translate( 'Right' ),
					),
					'std' => 'left',
					'show_if' => array( 'add_to_cart', '=', 0 ),
				),
			), $design_options_config, $hover_options_config ),
		),

		// Custom HTML
		'html' => array(
			'title' => __( 'Custom HTML', 'us' ),
			'params' => array_merge( array(
				'content' => array(
					'description' => sprintf( __( 'Added content will be displayed inside the %s block', 'us' ), '<code>&lt;div class="w-html"&gt;&lt;/div&gt;</code>' ),
					'type' => 'html',
					'std' => '',
					'classes' => 'desc_2',
				),
			), $design_options_config, $hover_options_config ),
		),

		// Horizontal Wrapper
		'hwrapper' => array(
			'title' => __( 'Horizontal Wrapper', 'us' ),
			'params' => array_merge( array(
				'alignment' => array(
					'title' => __( 'Content Horizontal Alignment', 'us' ),
					'type' => 'radio',
					'options' => array(
						'left' => us_translate( 'Left' ),
						'center' => us_translate( 'Center' ),
						'right' => us_translate( 'Right' ),
					),
					'std' => 'left',
					'classes' => 'cols_2',
				),
				'valign' => array(
					'title' => __( 'Content Vertical Alignment', 'us' ),
					'type' => 'radio',
					'options' => array(
						'top' => us_translate( 'Top' ),
						'middle' => us_translate( 'Middle' ),
						'bottom' => us_translate( 'Bottom' ),
					),
					'std' => 'top',
					'classes' => 'cols_2',
				),
				'wrap' => array(
					'text' => __( 'Allow move content to the next line', 'us' ),
					'type' => 'switch',
					'std' => 0,
				),
			), $design_options_config, $hover_options_config ),
		),

		// Vertical Wrapper
		'vwrapper' => array(
			'title' => __( 'Vertical Wrapper', 'us' ),
			'params' => array_merge( array(
				'alignment' => array(
					'title' => __( 'Content Horizontal Alignment', 'us' ),
					'type' => 'radio',
					'options' => array(
						'left' => us_translate( 'Left' ),
						'center' => us_translate( 'Center' ),
						'right' => us_translate( 'Right' ),
					),
					'std' => 'left',
					'classes' => 'cols_2',
				),
				'valign' => array(
					'title' => __( 'Content Vertical Alignment', 'us' ),
					'type' => 'radio',
					'options' => array(
						'top' => us_translate( 'Top' ),
						'middle' => us_translate( 'Middle' ),
						'bottom' => us_translate( 'Bottom' ),
					),
					'std' => 'top',
					'classes' => 'cols_2',
				),
				'bg_gradient' => array(
					'text' => __( 'Add a transparent gradient to the background', 'us' ),
					'type' => 'switch',
					'std' => 0,
				),
				'color_grad' => array(
					'type' => 'color',
					'std' => 'rgba(30,30,30,0.8)',
					'classes' => 'cols_2',
					'show_if' => array( 'bg_gradient', '=', TRUE ),
				),
			), $design_options_config, $hover_options_config ),
		),

		// WooCommerce Product Field
		'product_field' => array(
			'title' => us_translate( 'Product data', 'woocommerce' ),
			'place_if' => class_exists( 'woocommerce' ),
			'params' => array_merge( array(
				'type' => array(
					'title' => us_translate( 'Show' ),
					'type' => 'select',
					'options' => array(
						'price' => us_translate( 'Price', 'woocommerce' ),
						'rating' => us_translate( 'Rating', 'woocommerce' ),
						'sku' => us_translate( 'SKU', 'woocommerce' ),
						'sale_badge' => __( 'Sale Badge', 'us' ),
					),
					'std' => 'price',
				),
				'sale_text' => array(
					'title' => __( 'Sale Badge Text', 'us' ),
					'type' => 'text',
					'std' => us_translate( 'Sale!', 'woocommerce' ),
					'show_if' => array( 'type', '=', 'sale_badge' ),
				),
				'text_styles' => array(
					'title' => __( 'Text Styles', 'us' ),
					'type' => 'checkboxes',
					'options' => array(
						'bold' => __( 'Bold', 'us' ),
						'uppercase' => __( 'Uppercase', 'us' ),
						'italic' => __( 'Italic', 'us' ),
					),
					'std' => array(),
					'show_if' => array( 'type', '!=', 'rating' ),
				),
				'font_size' => array(
					'title' => __( 'Font Size', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
				),
				'font_size_mobiles' => array(
					'title' => __( 'Font Size on Mobiles', 'us' ),
					'type' => 'text',
					'std' => '',
					'description' => sprintf( __( 'Examples: %s', 'us' ), '20px, 1.5rem' ),
					'classes' => 'cols_2 desc_1',
				),
			), $design_options_config, $hover_options_config ),
		),

	),
);
