<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Grid Templates
 */
global $us_template_directory_uri;
return array(

/* Blog =========================================================================== */

'blog_classic' => array(
	'title' => us_translate( 'Blog' ) . ' ' . __( 'Classic', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'media_preview' => 1,
			'design_options' => array(
				'margin_bottom_default' => '1rem',
			),
		),
		'post_title:1' => array(
			'design_options' => array(
				'margin_bottom_default' => '0.5rem',
			),
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|clock',
		),
		'hwrapper:1' => array(
			'wrap' => 1,
			'color_text' => us_get_option( 'color_content_faded' ),
		),
		'post_author:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|user',
		),
		'post_comments:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|comments',
		),
		'post_taxonomy:1' => array(
			'taxonomy_name' => 'category',
			'font_size' => '0.9rem',
			'icon' => 'far|folder-open',
		),
		'post_taxonomy:2' => array(
			'taxonomy_name' => 'post_tag',
			'font_size' => '0.9rem',
			'icon' => 'far|tags',
		),
		'post_content:1' => array(
			'design_options' => array(
				'margin_top_default' => '0.5rem',
			),
		),
		'btn:1' => array(
			'style' => 'outlined',
			'design_options' => array(
				'margin_top_default' => '1.5rem',
			),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'post_title:1',
				2 => 'hwrapper:1',
				3 => 'post_content:1',
				4 => 'btn:1',
			),
			'hwrapper:1' => array(
				0 => 'post_date:1',
				1 => 'post_author:1',
				2 => 'post_taxonomy:1',
				3 => 'post_taxonomy:2',
				4 => 'post_comments:1',
			),
		),
	),
),

'blog_flat' => array(
	'title' => us_translate( 'Blog' ) . ' ' . __( 'Flat', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'media_preview' => 1,
		),
		'post_title:1' => array(
		),
		'hwrapper:2' => array(
			'alignment' => 'center',
			'wrap' => 1,
			'color_text' => us_get_option( 'color_content_faded' ),
		),
		'post_content:1' => array(
			'length' => '20',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|clock',
		),
		'post_author:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|user',
		),
		'btn:1' => array(
			'style' => 'outlined',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'design_options' => array(
				'padding_top_default' => '1.5rem',
				'padding_right_default' => '2.5rem',
				'padding_bottom_default' => '2.5rem',
				'padding_left_default' => '2.5rem',
			),
		),
		'post_taxonomy:1' => array(
			'taxonomy_name' => 'category',
			'font_size' => '0.9rem',
			'icon' => 'far|folder-open',
		),
		'post_taxonomy:2' => array(
			'taxonomy_name' => 'post_tag',
			'font_size' => '0.9rem',
			'icon' => 'far|tags',
		),
		'post_comments:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|comments',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'hwrapper:2' => array(
				0 => 'post_date:1',
				1 => 'post_author:1',
				2 => 'post_taxonomy:1',
				3 => 'post_taxonomy:2',
				4 => 'post_comments:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'hwrapper:2',
				2 => 'post_content:1',
				3 => 'btn:1',
			),
		),
		'options' => array(
			'overflow' => 1,
			'color_bg' => us_get_option( 'color_content_bg' ),
			'box_shadow_hover' => '1.5',
		),
	),
),

'blog_tiles' => array(
	'title' => us_translate( 'Blog' ) . ' ' . __( 'Tiles', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'placeholder' => 1,
			'hover' => 1,
			'scale_hover' => '1.2',
		),
		'vwrapper:1' => array(
			'valign' => 'bottom',
			'bg_gradient' => 1,
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '5rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '1.5rem',
				'padding_left_default' => '2rem',
			),
			'opacity' => '0',
			'transition_duration' => '0.45',
		),
		'post_title:1' => array(
			'text_styles' => array(
				0 => 'bold',
			),
			'color_text' => '#ffffff',
		),
		'hwrapper:1' => array(
			'wrap' => 1,
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|clock',
			'color_text' => '#ffffff',
		),
		'post_author:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|user',
			'color_text' => '#ffffff',
		),
		'post_comments:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|comments',
			'color_text' => '#ffffff',
		),
		'post_taxonomy:1' => array(
			'taxonomy_name' => 'category',
			'style' => 'badge',
			'text_styles' => array(
				0 => 'bold',
				1 => 'uppercase',
			),
			'font_size' => '10px',
		),
		'post_taxonomy:2' => array(
			'taxonomy_name' => 'post_tag',
			'font_size' => '0.9rem',
			'icon' => 'far|tags',
			'color_text' => '#ffffff',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_taxonomy:1',
				1 => 'post_title:1',
				2 => 'hwrapper:1',
			),
			'hwrapper:1' => array(
				0 => 'post_date:1',
				1 => 'post_author:1',
				2 => 'post_taxonomy:2',
				3 => 'post_comments:1',
			),
		),
		'options' => array(
			'overflow' => 1,
		),
	),
),

'blog_cards' => array(
	'title' => us_translate( 'Blog' ) . ' ' . __( 'Cards', 'us' ),
	'data' => array(
		'post_image:1' => array(
		),
		'post_title:1' => array(
			'text_styles' => array(
				0 => 'bold',
			),
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'padding_top_default' => '9%',
				'padding_right_default' => '11%',
				'padding_bottom_default' => '11%',
				'padding_left_default' => '11%',
			),
		),
		'post_taxonomy:1' => array(
			'taxonomy_name' => 'category',
			'style' => 'badge',
			'text_styles' => array(
				0 => 'bold',
				1 => 'uppercase',
			),
			'font_size' => '10px',
			'design_options' => array(
				'position_top_default' => '1.2rem',
				'position_left_default' => '1.2rem',
				'position_right_default' => '1.2rem',
			),
		),
		'hwrapper:1' => array(
			'wrap' => 1,
			'color_text' => us_get_option( 'color_content_faded' ),
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|clock',
		),
		'post_author:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|user',
		),
		'post_taxonomy:2' => array(
			'taxonomy_name' => 'post_tag',
			'font_size' => '0.9rem',
			'icon' => 'far|tags',
		),
		'post_comments:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|comments',
		),
		'post_content:1' => array(
			'length' => '20',
		),
		'btn:1' => array(
			'style' => 'outlined',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'post_taxonomy:1',
				2 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'hwrapper:1',
				2 => 'post_content:1',
				3 => 'btn:1',
			),
			'hwrapper:1' => array(
				0 => 'post_date:1',
				1 => 'post_author:1',
				2 => 'post_taxonomy:2',
				3 => 'post_comments:1',
			),
		),
		'options' => array(
			'overflow' => 1,
			'color_bg' => us_get_option( 'color_content_bg' ),
			'border_radius' => '0.3',
			'box_shadow' => '0.3',
			'box_shadow_hover' => '1',
		),
	),
),

'blog_side_image' => array(
	'title' => us_translate( 'Blog' ) . ' ' . __( 'Side Image', 'us' ),
	'data' => array(
		'hwrapper:1' => array(
			'el_class' => 'responsive',
		),
		'post_image:1' => array(
			'placeholder' => 1,
			'circle' => 1,
			'thumbnail_size' => 'us_350_350_crop',
			'width' => '30%',
			'design_options' => array(
				'margin_right_default' => is_rtl() ? '0' : '5%',
				'margin_left_default' => is_rtl() ? '5%' : '0',
			),
		),
		'vwrapper:1' => array(
		),
		'post_title:1' => array(
		),
		'hwrapper:2' => array(
			'wrap' => 1,
			'color_text' => us_get_option( 'color_content_faded' ),
		),
		'post_content:1' => array(
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|clock',
		),
		'post_author:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|user',
		),
		'post_comments:1' => array(
			'font_size' => '0.9rem',
			'icon' => 'far|comments',
		),
		'btn:1' => array(
			'style' => 'outlined',
		),
		'post_taxonomy:1' => array(
			'taxonomy_name' => 'post_tag',
			'font_size' => '0.9rem',
			'icon' => 'far|tags',
		),
		'post_taxonomy:2' => array(
			'taxonomy_name' => 'category',
			'font_size' => '0.9rem',
			'icon' => 'far|folder-open',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'hwrapper:1',
			),
			'hwrapper:1' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'hwrapper:2',
				2 => 'post_content:1',
				3 => 'btn:1',
			),
			'hwrapper:2' => array(
				0 => 'post_date:1',
				1 => 'post_author:1',
				2 => 'post_taxonomy:2',
				3 => 'post_taxonomy:1',
				4 => 'post_comments:1',
			),
		),
	),
),

'blog_compact' => array(
	'title' => us_translate( 'Blog' ) . ' ' . __( 'Compact', 'us' ),
	'data' => array(
		'hwrapper:1' => array(
			'wrap' => 1,
		),
		'post_title:1' => array(
			'color_link' => '0',
			'font_size' => '1rem',
			'tag' => 'div',
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
		),
		'post_comments:1' => array(
			'font_size' => '0.9rem',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'hwrapper:1',
			),
			'hwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
				2 => 'post_comments:1',
			),
		),
	),
),

/* Portfolio =========================================================================== */

'portfolio_1' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 1',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'translateY_hover' => '-10',
			'transition_duration' => '0.35',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '-1px',
				'position_left_default' => '0',
				'padding_top_default' => '1.2rem',
				'padding_right_default' => '1.5rem',
				'padding_bottom_default' => '1.2rem',
				'padding_left_default' => '1.5rem',
			),
			'color_bg' => 'inherit',
			'el_class' => 'grid_arrow_top',
			'hover' => 1,
			'translateY' => '101',
			'transition_duration' => '0.35',
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => 'inherit',
		),
		'post_custom_field:1' => array(
			'key' => 'us_tile_additional_image',
			'type' => 'image',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'translateY' => '100',
			'transition_duration' => '0.35',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'post_custom_field:1',
				2 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
			'color_bg' => us_get_option( 'color_content_bg' ),
			'color_text' => us_get_option( 'color_content_text' ),
		),
	),
),

'portfolio_2' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 2',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'opacity_hover' => '0.1',
			'scale_hover' => '1.1',
			'transition_duration' => '0.35',
		),
		'vwrapper:1' => array(
			'bg_gradient' => 1,
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '4rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '1.5rem',
				'padding_left_default' => '2rem',
			),
			'transition_duration' => '0.35',
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => '#ffffff',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'color_text' => '#ffffff',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
			'color_bg' => '#333333',
		),
	),
),

'portfolio_3' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 3',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'opacity' => '0.35',
			'transition_duration' => '0.4',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => 'inherit',
			'hover' => 1,
			'opacity_hover' => '0',
			'translateY_hover' => '-100',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'hover' => 1,
			'opacity_hover' => '0',
			'translateY_hover' => '100',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

'portfolio_4' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 4',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'opacity_hover' => '0.2',
		),
		'vwrapper:1' => array(
			'valign' => 'bottom',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => 'inherit',
			'hover' => 1,
			'opacity' => '0',
			'translateY' => '-60',
			'transition_duration' => '0.35',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'hover' => 1,
			'opacity' => '0',
			'opacity_hover' => '0.75',
			'translateY' => '-30',
			'transition_duration' => '0.35',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

'portfolio_5' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 5',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'scale_hover' => '1.2',
			'transition_duration' => '0.4',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '1.3rem',
				'position_right_default' => '1.3rem',
				'position_bottom_default' => '1.3rem',
				'position_left_default' => '1.3rem',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
			'hover' => 1,
			'color_bg' => 'inherit',
			'opacity' => '0',
			'opacity_hover' => '0.9',
			'scale' => '0',
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => 'inherit',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
			'color_bg' => us_get_option( 'color_content_bg' ),
			'color_text' => us_get_option( 'color_content_text' ),
		),
	),
),

'portfolio_6' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 6',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'opacity_hover' => '0.1',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
			'hover' => 1,
			'opacity' => '0',
			'scale' => '1.5',
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => 'inherit',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

'portfolio_7' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 7',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'opacity' => '0.65',
			'opacity_hover' => '0.1',
			'scale' => '1.1',
			'transition_duration' => '0.4',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2.6rem',
				'padding_right_default' => '2.6rem',
				'padding_bottom_default' => '2.6rem',
				'padding_left_default' => '2.6rem',
			),
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => 'inherit',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'hover' => 1,
			'opacity' => '0',
			'translateY' => '30',
			'transition_duration' => '0.4',
		),
		'html:1' => array(
			'design_options' => array(
				'position_top_default' => '1.3rem',
				'position_right_default' => '1.3rem',
				'position_bottom_default' => '1.3rem',
				'position_left_default' => '1.3rem',
				'border_top_default' => '2px',
				'border_right_default' => '2px',
				'border_bottom_default' => '2px',
				'border_left_default' => '2px',
			),
			'hover' => 1,
			'opacity' => '0',
			'scale' => '1.1',
			'transition_duration' => '0.4',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'html:1',
				2 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

'portfolio_8' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 8',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'width' => '110%',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'opacity_hover' => '0.1',
			'translateX' => is_rtl() ? '8' : '-8',
			'transition_duration' => '0.4',
		),
		'vwrapper:1' => array(
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => 'inherit',
			'hover' => 1,
			'opacity' => '0',
			'translateX' => '-33',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'hover' => 1,
			'opacity' => '0',
			'opacity_hover' => '0.75',
			'translateX' => '40',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

'portfolio_9' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 9',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'opacity_hover' => '0',
			'scale_hover' => '4',
			'transition_duration' => '0.4',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
			'hover' => 1,
			'scale' => '0',
			'transition_duration' => '0.5',
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => 'inherit',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

'portfolio_10' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 10',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
		),
		'vwrapper:1' => array(
			'bg_gradient' => 1,
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '-1px',
				'position_left_default' => '0',
				'padding_top_default' => '5rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '1.5rem',
				'padding_left_default' => '2rem',
			),
			'hover' => 1,
			'opacity' => '0',
			'transition_duration' => '0.45',
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => '#ffffff',
			'hover' => 1,
			'translateY' => '35',
			'transition_duration' => '0.35',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'color_text' => '#ffffff',
			'hover' => 1,
			'opacity' => '0',
			'opacity_hover' => '0.75',
			'translateY' => '100',
			'transition_duration' => '0.35',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

'portfolio_11' => array (
	'title' => __( 'Portfolio', 'us' ) . ' 11',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'opacity_hover' => '0.1',
			'transition_duration' => '0.35',
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
			'hover' => 1,
			'opacity' => '0',
			'translateY' => '-25',
			'transition_duration' => '0.35',
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => 'inherit',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
		),
		'html:1' => array(
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'border_bottom_default' => '10px',
			),
			'hover' => 1,
			'translateY' => '100',
			'transition_duration' => '0.35',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
				2 => 'html:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

'portfolio_12' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 12',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'opacity' => '0.65',
			'opacity_hover' => '0.1',
			'transition_duration' => '0.35',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '4rem',
				'padding_right_default' => '4rem',
				'padding_bottom_default' => '4rem',
				'padding_left_default' => '4rem',
			),
			'el_class' => 'grid_style_12',
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => 'inherit',
			'hover' => 1,
			'translateY' => '-50',
			'transition_duration' => '0.35',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'hover' => 1,
			'opacity' => '0',
			'opacity_hover' => '0.75',
			'translateY' => '75',
			'transition_duration' => '0.35',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

'portfolio_13' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 13',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'opacity' => '0.65',
			'opacity_hover' => '0.1',
			'transition_duration' => '0.35',
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => 'inherit',
			'design_options' => array(
				'margin_bottom_default' => '1.3rem',
			),
			'hover' => 1,
			'translateY' => '30',
			'transition_duration' => '0.35',
		),
		'post_date:1' => array(
			'design_options' => array(
				'position_left_default' => '0',
				'position_bottom_default' => '0',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
			'hover' => 1,
			'opacity' => '0',
			'translateY' => '100',
			'transition_duration' => '0.35',
		),
		'html:1' => array(
			'design_options' => array(
				'border_top_default' => '4px',
				'border_bottom_default' => '0',
			),
			'width' => '100%',
			'hover' => 1,
			'opacity' => '0',
			'translateY' => '900',
			'transition_duration' => '0.35',
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_left_default' => '2rem',
			),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
				2 => 'post_date:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'html:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

'portfolio_14' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 14',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'opacity' => '0.65',
			'opacity_hover' => '0.1',
			'scale' => '1.15',
			'translateX' => '-6',
			'transition_duration' => '0.35',
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => 'inherit',
		),
		'post_date:1' => array(
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
			),
			'hover' => 1,
			'opacity' => '0',
			'translateX' => '-50',
			'transition_duration' => '0.35',
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'post_date:1',
				2 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

'portfolio_15' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 15',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'opacity' => '0.9',
			'opacity_hover' => '0.1',
			'transition_duration' => '0.35',
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
			'el_class' => 'grid_style_15',
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => 'inherit',
			'hover' => 1,
			'opacity' => '0',
			'translateY' => '60',
			'transition_duration' => '0.35',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'hover' => 1,
			'opacity' => '0',
			'transition_duration' => '0.35',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

'portfolio_16' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 16',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'circle' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'el_class' => 'grid_corner_image',
			'hover' => 1,
			'scale' => '0.3',
			'transition_duration' => '0.4',
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '8%',
				'padding_right_default' => '33%',
				'padding_left_default' => '8%',
			),
			'hover' => 1,
			'opacity_hover' => '0',
			'scale_hover' => '2',
			'translateX_hover' => '-50',
			'translateY_hover' => '-50',
			'transition_duration' => '0.4',
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => 'inherit',
			'text_styles' => array(
				0 => 'bold',
			),
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

'portfolio_17' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 17',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'opacity_hover' => '0.1',
			'scale_hover' => '1.3',
			'translateX_hover' => '-11',
			'translateY_hover' => '-11',
			'transition_duration' => 1,
		),
		'vwrapper:1' => array(
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => 'inherit',
			'hover' => 1,
			'translateY' => '60',
			'transition_duration' => '0.4',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'hover' => 1,
			'opacity' => '0',
			'scale' => '0.75',
			'translateX' => '50',
			'transition_duration' => '0.4',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

'portfolio_18' => array(
	'title' => __( 'Portfolio', 'us' ) . ' 18',
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'opacity_hover' => '0.1',
			'scale_hover' => '1.1',
			'transition_duration' => '0.35',
		),
		'vwrapper:1' => array(
			'bg_gradient' => 1,
			'design_options' => array(
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '5rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '1.5rem',
				'padding_left_default' => '2rem',
			),
			'hover' => 1,
			'opacity' => '0',
			'transition_duration' => 1,
		),
		'post_title:1' => array(
			'link' => 'none',
			'color_text' => '#ffffff',
		),
		'post_date:1' => array(
			'font_size' => '0.9rem',
			'color_text' => '#ffffff',
		),
		'post_custom_field:1' => array(
			'key' => 'us_tile_additional_image',
			'type' => 'image',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'opacity' => '0',
			'transition_duration' => 1,
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'post_custom_field:1',
				2 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'post_date:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

'portfolio_compact' => array(
	'title' => __( 'Portfolio', 'us' ) . ' ' . __( 'Compact', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'link' => 'none',
			'placeholder' => 1,
			'thumbnail_size' => 'thumbnail',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
			),
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'valign' => 'middle',
			'design_options' => array(
				'position_top_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
				'position_left_default' => '0',
				'padding_top_default' => '0.8rem',
				'padding_right_default' => '0.8rem',
				'padding_bottom_default' => '0.8rem',
				'padding_left_default' => '0.8rem',
			),
			'color_bg' => 'rgba(0,0,0,0.8)',
			'hover' => 1,
			'opacity' => '0',
		),
		'post_title:1' => array(
			'link' => 'none',
			'font_size' => '0.7rem',
			'tag' => 'h4',
			'color_text' => '#ffffff',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
			),
		),
		'options' => array(
			'fixed' => 1,
			'link' => 'post',
		),
	),
),

/* Testimonial =========================================================================== */

'testimonial_1' => array(
	'title' => __( 'Testimonial', 'us' ) . ' 1',
	'data' => array(
		'post_content:1' => array(
			'type' => 'full_content',
		),
		'hwrapper:1' => array(
			'valign' => 'middle',
			'design_options' => array(
				'margin_top_default' => '1rem',
			),
		),
		'post_image:1' => array(
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'circle' => 1,
			'thumbnail_size' => 'thumbnail',
			'width' => '4rem',
			'design_options' => array(
				'margin_right_default' => '1rem',
			),
		),
		'vwrapper:1' => array(
		),
		'post_custom_field:1' => array(
			'key' => 'us_testimonial_author',
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'color_link' => '0',
			'text_styles' => array(
				0 => 'bold',
			),
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_custom_field:2' => array(
			'key' => 'us_testimonial_role',
			'font_size' => '0.9rem',
			'color_text' => us_get_option( 'color_content_faded' ),
		),
		'vwrapper:2' => array(
			'design_options' => array(
				'border_top_default' => '2px',
				'border_right_default' => '2px',
				'border_bottom_default' => '2px',
				'border_left_default' => '2px',
				'padding_top_default' => '2rem',
				'padding_right_default' => '2rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2rem',
			),
			'color_border' => us_get_option( 'color_content_border' ),
			'border_radius' => '0.3',
			'hover' => 1,
			'color_border_hover' => us_get_option( 'color_content_primary' ),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'vwrapper:2',
			),
			'hwrapper:1' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_custom_field:1',
				1 => 'post_custom_field:2',
			),
			'vwrapper:2' => array(
				0 => 'post_content:1',
				1 => 'hwrapper:1',
			),
		),
	),
),

'testimonial_2' => array(
	'title' => __( 'Testimonial', 'us' ) . ' 2',
	'data' => array(
		'post_content:1' => array(
			'type' => 'full_content',
		),
		'hwrapper:1' => array(
			'valign' => 'middle',
			'design_options' => array(
				'margin_top_default' => '1rem',
			),
		),
		'post_image:1' => array(
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'circle' => 1,
			'thumbnail_size' => 'thumbnail',
			'width' => '4rem',
			'design_options' => array(
				'margin_right_default' => '1rem',
			),
		),
		'vwrapper:1' => array(
		),
		'post_custom_field:1' => array(
			'key' => 'us_testimonial_author',
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'color_link' => '0',
			'text_styles' => array(
				0 => 'bold',
			),
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_custom_field:2' => array(
			'key' => 'us_testimonial_role',
			'font_size' => '0.9rem',
			'color_text' => us_get_option( 'color_content_faded' ),
		),
		'vwrapper:2' => array(
			'design_options' => array(
				'padding_top_default' => '3.5rem',
				'padding_left_default' => '2rem',
			),
		),
		'post_custom_field:3' => array(
			'key' => 'custom',
			'font_size' => '3rem',
			'icon' => 'fas|quote-left',
			'design_options' => array(
				'position_top_default' => '0',
				'position_left_default' => '0',
			),
			'color_text' => us_get_option( 'color_content_primary' ),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'vwrapper:2',
			),
			'hwrapper:1' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_custom_field:1',
				1 => 'post_custom_field:2',
			),
			'vwrapper:2' => array(
				0 => 'post_custom_field:3',
				1 => 'post_content:1',
				2 => 'hwrapper:1',
			),
		),
	),
),

'testimonial_3' => array(
	'title' => __( 'Testimonial', 'us' ) . ' 3',
	'data' => array(
		'post_content:1' => array(
			'type' => 'full_content',
		),
		'hwrapper:1' => array(
			'valign' => 'middle',
		),
		'post_image:1' => array(
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'circle' => 1,
			'thumbnail_size' => 'thumbnail',
			'width' => '4rem',
			'design_options' => array(
				'margin_right_default' => '1rem',
			),
		),
		'vwrapper:1' => array(
		),
		'post_custom_field:1' => array(
			'key' => 'us_testimonial_author',
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'color_link' => '0',
			'text_styles' => array(
				0 => 'bold',
			),
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_custom_field:2' => array(
			'key' => 'us_testimonial_role',
			'font_size' => '0.9rem',
			'color_text' => us_get_option( 'color_content_faded' ),
		),
		'vwrapper:2' => array(
			'design_options' => array(
				'padding_left_default' => '2rem',
			),
		),
		'post_custom_field:3' => array(
			'key' => 'custom',
			'font_size' => '1.4rem',
			'icon' => 'fas|quote-left',
			'design_options' => array(
				'position_top_default' => '0',
				'position_left_default' => '0',
			),
			'hover' => 1,
			'opacity' => '0.2',
			'opacity_hover' => '0.2',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'vwrapper:2',
			),
			'hwrapper:1' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_custom_field:1',
				1 => 'post_custom_field:2',
			),
			'vwrapper:2' => array(
				0 => 'post_custom_field:3',
				1 => 'post_content:1',
				2 => 'hwrapper:1',
			),
		),
	),
),

'testimonial_4' => array(
	'title' => __( 'Testimonial', 'us' ) . ' 4',
	'data' => array(
		'post_content:1' => array(
			'type' => 'full_content',
		),
		'hwrapper:1' => array(
		),
		'post_image:1' => array(
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'placeholder' => 1,
			'circle' => 1,
			'thumbnail_size' => 'thumbnail',
			'width' => '5.5rem',
			'design_options' => array(
				'margin_right_default' => '1rem',
			),
			'el_class' => 'with_quote_icon',
		),
		'vwrapper:1' => array(
		),
		'post_custom_field:1' => array(
			'key' => 'us_testimonial_author',
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'color_link' => '0',
			'text_styles' => array(
				0 => 'bold',
			),
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_custom_field:2' => array(
			'key' => 'us_testimonial_role',
			'font_size' => '0.9rem',
			'color_text' => us_get_option( 'color_content_faded' ),
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'hwrapper:1',
			),
			'hwrapper:1' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_content:1',
				1 => 'post_custom_field:1',
				2 => 'post_custom_field:2',
			),
		),
	),
),

'testimonial_5' => array(
	'title' => __( 'Testimonial', 'us' ) . ' 5',
	'data' => array(
		'post_content:1' => array(
			'type' => 'full_content',
		),
		'post_image:1' => array(
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'circle' => 1,
			'thumbnail_size' => 'thumbnail',
			'width' => '7rem',
		),
		'post_custom_field:1' => array(
			'key' => 'us_testimonial_author',
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'color_link' => '0',
			'text_styles' => array(
				0 => 'bold',
			),
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_custom_field:2' => array(
			'key' => 'us_testimonial_role',
			'font_size' => '0.9rem',
			'color_text' => us_get_option( 'color_content_faded' ),
		),
		'vwrapper:2' => array(
			'alignment' => 'center',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'vwrapper:2',
			),
			'vwrapper:2' => array(
				0 => 'post_content:1',
				1 => 'post_image:1',
				2 => 'post_custom_field:1',
				3 => 'post_custom_field:2',
			),
		),
	),
),

'testimonial_6' => array(
	'title' => __( 'Testimonial', 'us' ) . ' 6',
	'data' => array(
		'post_content:1' => array(
			'type' => 'full_content',
		),
		'hwrapper:1' => array(
			'valign' => 'middle',
			'design_options' => array(
				'padding_top_default' => '1.5rem',
				'padding_right_default' => '2.5rem',
				'padding_left_default' => '2.5rem',
			),
		),
		'post_image:1' => array(
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'circle' => 1,
			'thumbnail_size' => 'thumbnail',
			'width' => '4rem',
			'design_options' => array(
				'margin_right_default' => '1rem',
			),
		),
		'vwrapper:1' => array(
		),
		'post_custom_field:1' => array(
			'key' => 'us_testimonial_author',
			'link' => 'custom',
			'custom_link' => array(
				'url' => '{{us_testimonial_link}}',
				'target' => '',
			),
			'color_link' => '0',
			'text_styles' => array(
				0 => 'bold',
			),
			'design_options' => array(
				'margin_bottom_default' => '0',
			),
		),
		'post_custom_field:2' => array(
		'key' => 'us_testimonial_role',
			'font_size' => '0.9rem',
			'color_text' => us_get_option( 'color_content_faded' ),
		),
		'vwrapper:2' => array(
			'design_options' => array(
				'padding_top_default' => '2rem',
				'padding_right_default' => '2.5rem',
				'padding_bottom_default' => '2rem',
				'padding_left_default' => '2.5rem',
			),
			'color_bg' => us_get_option( 'color_content_bg_alt' ),
			'color_text' => us_get_option( 'color_content_text' ),
			'border_radius' => '0.3',
			'el_class' => 'grid_arrow_bottom',
			'hover' => 1,
			'color_bg_hover' => us_get_option( 'color_content_primary' ),
			'color_text_hover' => '#ffffff',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'vwrapper:2',
				1 => 'hwrapper:1',
			),
			'hwrapper:1' => array(
				0 => 'post_image:1',
				1 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_custom_field:1',
				1 => 'post_custom_field:2',
			),
			'vwrapper:2' => array(
				0 => 'post_content:1',
			),
		),
	),
),

/* Shop =========================================================================== */

'shop_standard' => array(
	'title' => us_translate_x( 'Shop', 'Page title', 'woocommerce' ) . ' ' . __( 'Standard', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'placeholder' => 1,
			'thumbnail_size' => 'shop_catalog',
		),
		'product_field:1' => array(
			'type' => 'sale_badge',
			'design_options' => array(
				'position_top_default' => '10px',
				'position_left_default' => '10px',
				'padding_left_default' => '0.8rem',
				'padding_right_default' => '0.8rem',
			),
			'color_bg' => us_get_option( 'color_content_primary' ),
			'color_text' => '#fff',
			'border_radius' => '2',
		),
		'post_title:1' => array(
			'font_size' => '1rem',
			'design_options' => array(
				'margin_top_default' => '0.8rem',
				'margin_bottom_default' => '0.6rem',
			),
		),
		'product_field:2' => array(
			'type' => 'rating',
			'design_options' => array(
				'margin_bottom_default' => '0.4rem',
			),
		),
		'product_field:3' => array(
			'font_size' => '1.2rem',
			'text_styles' => array(
				0 => 'bold',
			),
		),
		'btn:1' => array(
			'add_to_cart' => 1,
			'view_cart_link' => 1,
			'font_size' => '0.8rem',
			'design_options' => array(
				'margin_top_default' => '0.8rem',
			),
			'border_radius' => '0.2',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'product_field:1',
				2 => 'post_title:1',
				3 => 'product_field:2',
				4 => 'product_field:3',
				5 => 'btn:1',
			),
		),
	),
),

'shop_modern' => array(
	'title' => us_translate_x( 'Shop', 'Page title', 'woocommerce' ) . ' ' . __( 'Modern', 'us' ),
	'data' => array(
		'post_image:1' => array(
			'placeholder' => 1,
			'thumbnail_size' => 'shop_catalog',
		),
		'product_field:1' => array(
			'type' => 'sale_badge',
			'design_options' => array(
				'position_top_default' => '10px',
				'position_left_default' => '10px',
				'padding_left_default' => '0.8rem',
				'padding_right_default' => '0.8rem',
			),
			'color_bg' => us_get_option( 'color_content_primary' ),
			'color_text' => '#fff',
			'border_radius' => '2',
		),
		'vwrapper:1' => array(
			'alignment' => 'center',
			'design_options' => array(
				'padding_top_default' => '1rem',
				'padding_right_default' => '1.2rem',
				'padding_bottom_default' => '1rem',
				'padding_left_default' => '1.2rem',
			),
			'color_bg' => 'inherit',
			'hover' => 1,
			'translateY_hover' => '-40',
		),
		'post_title:1' => array(
			'font_size' => '1rem',
			'design_options' => array(
				'margin_bottom_default' => '0.5rem',
			),
		),
		'product_field:2' => array(
			'type' => 'rating',
			'design_options' => array(
				'margin_bottom_default' => '0.3rem',
			),
		),
		'product_field:3' => array(
			'font_size' => '1.2rem',
			'text_styles' => array(
				0 => 'bold',
			),
		),
		'btn:1' => array(
			'add_to_cart' => 1,
			'design_options' => array(
				'position_left_default' => '0',
				'position_right_default' => '0',
				'position_bottom_default' => '0',
			),
			'width' => '100%',
			'hover' => 1,
			'opacity' => '0',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'post_image:1',
				1 => 'product_field:1',
				2 => 'vwrapper:1',
				3 => 'btn:1',
			),
			'vwrapper:1' => array(
				0 => 'post_title:1',
				1 => 'product_field:2',
				2 => 'product_field:3',
			),
		),
		'options' => array(
			'overflow' => 1,
			'color_bg' => us_get_option( 'color_content_bg' ),
			'border_radius' => '0.3',
			'box_shadow' => '0.3',
			'box_shadow_hover' => '1',
		),
	),
),

'shop_trendy' => array(
	'title' => us_translate_x( 'Shop', 'Page title', 'woocommerce' ) . ' ' . __( 'Trendy', 'us' ),
	'data' => array(
		'vwrapper:1' => array(
			'design_options' => array(
				'padding_top_default' => '10px',
				'padding_right_default' => '10px',
				'padding_left_default' => '10px',
			),
		),
		'post_image:1' => array(
			'placeholder' => 1,
			'thumbnail_size' => 'shop_catalog',
		),
		'product_field:1' => array(
			'type' => 'sale_badge',
			'design_options' => array(
				'position_top_default' => '20px',
				'position_left_default' => '20px',
				'padding_left_default' => '0.8rem',
				'padding_right_default' => '0.8rem',
			),
			'color_bg' => us_get_option( 'color_content_primary' ),
			'color_text' => '#fff',
		),
		'post_title:1' => array(
			'font_size' => '1rem',
			'design_options' => array(
				'margin_bottom_default' => '0.4rem',
			),
		),
		'product_field:2' => array(
			'type' => 'rating',
			'design_options' => array(
				'margin_bottom_default' => '0.2rem',
			),
		),
		'product_field:3' => array(
			'text_styles' => array(
				0 => 'bold',
			),
		),
		'btn:1' => array(
			'add_to_cart' => 1,
			'design_options' => array(
				'position_left_default' => '0',
				'position_right_default' => '0',
				'position_top_default' => '100%',
			),
			'width' => '100%',
			'hide_below' => '600',
			'hover' => 1,
			'opacity' => '0',
		),
	),
	'default' => array(
		'layout' => array(
			'middle_center' => array(
				0 => 'vwrapper:1',
			),
			'vwrapper:1' => array(
				0 => 'post_image:1',
				1 => 'product_field:1',
				2 => 'post_title:1',
				3 => 'product_field:2',
				4 => 'product_field:3',
				5 => 'btn:1',
			),
		),
		'options' => array(
			'color_bg' => us_get_option( 'color_content_bg' ),
			'box_shadow_hover' => '1',
		),
	),
),

);
