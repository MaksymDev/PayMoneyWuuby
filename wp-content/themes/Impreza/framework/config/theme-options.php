<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options
 *
 * @filter us_config_theme-options
 */
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

global $us_template_directory_uri, $usof_img_sizes, $usof_enable_portfolio, $us_template_directory;

if ( ! isset( $usof_img_sizes ) ) {
	$usof_img_sizes = array();
}
if ( ! isset( $usof_enable_portfolio ) ) {
	$usof_enable_portfolio = 1;
}

// Get Pages and order alphabetically
$us_page_list = us_get_posts_titles_for( 'page', 'title' );

// Get Headers
$us_headers_list = us_get_posts_titles_for( 'us_header' );
$headers_description = sprintf( __( 'Add or edit Header on the %s page', 'us' ), '<a href="' . admin_url() . 'edit.php?post_type=us_header" target="_blank">' . _x( 'Headers', 'site top area', 'us' ) . '</a>' );
$headers_description .= '<br><img src="' . $us_template_directory_uri . '/framework/admin/img/usof/l-header.png">';

// Get Page Blocks
$us_page_blocks_list = us_get_posts_titles_for( 'us_page_block' );
$titlebars_description = sprintf( __( 'Add or edit Title Bar on the %s page', 'us' ), '<a href="' . admin_url() . 'edit.php?post_type=us_page_block" target="_blank">' . __( 'Page Blocks', 'us' ) . '</a>' );
$titlebars_description .= '<br><img src="' . $us_template_directory_uri . '/framework/admin/img/usof/l-titlebar.png">';
$footers_description = sprintf( __( 'Add or edit Footer on the %s page', 'us' ), '<a href="' . admin_url() . 'edit.php?post_type=us_page_block" target="_blank">' . __( 'Page Blocks', 'us' ) . '</a>' );
$footers_description .= '<br><img src="' . $us_template_directory_uri . '/framework/admin/img/usof/l-footer.png">';

// Get Sidebars
$sidebars_list = us_get_sidebars();
$sidebars_description = sprintf( __( 'Add or edit Sidebar on the %s page', 'us' ), '<a href="' . admin_url( 'widgets.php' ) . '" target="_blank">' . us_translate( 'Widgets' ) . '</a>' );
$sidebars_description .= '<br><img src="' . $us_template_directory_uri . '/framework/admin/img/usof/l-sidebar.png">';

// Get CSS & JS assets
$usof_assets = array();
$assets_config = us_config( 'assets', array() );
foreach ( $assets_config as $component => $component_atts ) {
	if ( isset( $component_atts['hidden'] ) AND $component_atts['hidden'] ) {
		continue;
	}
	$usof_assets[ $component ] = array(
		'title' => $component_atts['title'],
		'group' => isset( $component_atts['group'] ) ? $component_atts['group'] : NULL,
	);
	if ( isset( $component_atts['apply_if'] ) ) {
			$usof_assets[ $component ]['apply_if'] = $component_atts['apply_if'];
	}
	if ( isset( $component_atts['css'] ) ) {
		$usof_assets[ $component ]['css_size'] = file_exists( $us_template_directory . $component_atts['css'] ) ? number_format( ( filesize( $us_template_directory . $component_atts['css'] ) / 1024 ) * 0.8, 1 ) : NULL;
	}
	if ( isset( $component_atts['js'] ) ) {
		$usof_assets[ $component ]['js_size'] = file_exists( $us_template_directory . $component_atts['js'] ) ? number_format( ( filesize( $us_template_directory . $component_atts['js'] ) / 1024 ) * 0.8, 1 ) : NULL;
	}
}
$optimize_assets_add_class = ' blocked';
$optimize_assets_alert_add_class = '';
$upload_dir = wp_upload_dir();
if ( wp_is_writable( $upload_dir['basedir'] ) ) {
	$optimize_assets_add_class = '';
	$optimize_assets_alert_add_class = ' hidden';
}

// Get public CPTs
$public_cpt = $public_cpt_for_posts = us_get_public_cpt();

// Remove types which can't display as posts
unset( $public_cpt_for_posts['tribe_events'] );
unset( $public_cpt_for_posts['forum'] );
unset( $public_cpt_for_posts['topic'] );

// Predefine types for 'Pages Layout' options
$pages_layout_predefined_types = array(
	// Example: type => title
	'portfolio' => __( 'Portfolio Pages', 'us' ),
	'post' => us_translate_x( 'Posts', 'post type general name' ),
	'archive' => __( 'Archive Pages', 'us' ),
	'search' => __( 'Search Results Page', 'us' ),
	'blog' => __( 'Blog Home Page', 'us' ),
	'shop' => __( 'Shop Pages', 'us' ),
	'product' => us_translate( 'Products', 'woocommerce' ),
);
if ( $usof_enable_portfolio != 1 ) {
	unset( $pages_layout_predefined_types['portfolio'] );
}
if ( ! class_exists( 'woocommerce' ) ) {
	unset( $pages_layout_predefined_types['shop'] );
	unset( $pages_layout_predefined_types['product'] );
}

// Add public CPTs for 'Pages Layout' options
$pages_layout_predefined_types = us_array_merge( $pages_layout_predefined_types, $public_cpt );

// Generate 'Pages Layout' options
$pages_layout_config = array();
foreach ( $pages_layout_predefined_types as $type => $title ) {
	$pages_layout_config = array_merge(
		$pages_layout_config, array(
			'h_' . $type => array(
				'title' => $title,
				'type' => 'heading',
				'classes' => 'with_separator sticky',
			),
			'header_' . $type . '_id' => array(
				'title' => _x( 'Header', 'site top area', 'us' ),
				'description' => $headers_description,
				'type' => 'select',
				'options' => us_array_merge(
					array( '__defaults__' => '&ndash; ' . __( 'Use Defaults', 'us' ) . ' &ndash;' ), array( '' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;' ), $us_headers_list
				),
				'std' => '__defaults__',
				'place_if' => is_plugin_active( 'us-header-builder/us-header-builder.php' ),
			),
			'titlebar_' . $type . '_id' => array(
				'title' => __( 'Title Bar', 'us' ),
				'description' => $titlebars_description,
				'type' => 'select',
				'options' => us_array_merge(
					array( '__defaults__' => '&ndash; ' . __( 'Use Defaults', 'us' ) . ' &ndash;' ), array( '' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;' ), $us_page_blocks_list
				),
				'std' => '__defaults__',
			),
			'sidebar_' . $type . '_id' => array(
				'title' => __( 'Sidebar', 'us' ),
				'description' => $sidebars_description,
				'type' => 'select',
				'options' => us_array_merge(
					array( '__defaults__' => '&ndash; ' . __( 'Use Defaults', 'us' ) . ' &ndash;' ), array( '' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;' ), $sidebars_list
				),
				'std' => '__defaults__',
			),
			'sidebar_' . $type . '_pos' => array(
				'type' => 'radio',
				'options' => array(
					'left' => us_translate( 'Left' ),
					'right' => us_translate( 'Right' ),
				),
				'std' => 'right',
				'classes' => 'for_above',
				'show_if' => array(
					array( 'sidebar_' . $type . '_id', '!=', '__defaults__' ),
					'and',
					array( 'sidebar_' . $type . '_id', '!=', '' ),
				),
			),
			'footer_' . $type . '_id' => array(
				'title' => __( 'Footer', 'us' ),
				'description' => $footers_description,
				'type' => 'select',
				'options' => us_array_merge(
					array( '__defaults__' => '&ndash; ' . __( 'Use Defaults', 'us' ) . ' &ndash;' ), array( '' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;' ), $us_page_blocks_list
				),
				'std' => '__defaults__',
			),
		)
	);
}

// Custom Images Sizes description
$img_size_info = sprintf( __( 'To change the default image sizes, go to %s.', 'us' ), '<a target="_blank" href="' . admin_url( 'options-media.php' ) . '">' . us_translate( 'Media Settings' ) . '</a>' );
// Add link to Customizing > WooCommerce > Product Images
if ( class_exists( 'woocommerce' ) ) {
	$img_size_info .= ' ' . sprintf(
			__( 'To change the Product image sizes, go to %s.', 'us' ), '<a target="_blank" href="' . esc_url(
				add_query_arg(
					array(
						'autofocus' => array(
							'panel' => 'woocommerce',
							'section' => 'woocommerce_product_images',
						),
						'url' => wc_get_page_permalink( 'shop' ),
					), admin_url( 'customize.php' )
				)
			) . '">' . us_translate( 'WooCommerce settings', 'woocommerce' ) . '</a>'
		);
}
$img_size_info .= ' ' . sprintf( __( 'Read about %simage sizes and how to use them%s.', 'us' ), '<a target="_blank" href="https://help.us-themes.com/' . strtolower( US_THEMENAME ) . '/general/images/">', '</a>' );
$img_size_description = '<a href="#advanced">' . __( 'Edit image sizes', 'us' ) . '</a>.';

// Specify "Background Position" control values
$usof_bg_pos_values = array(
	'top left' => '<span class="dashicons dashicons-arrow-left-alt"></span>',
	'top center' => '<span class="dashicons dashicons-arrow-up-alt"></span>',
	'top right' => '<span class="dashicons dashicons-arrow-right-alt"></span>',
	'center left' => '<span class="dashicons dashicons-arrow-left-alt"></span>',
	'center center' => '<span class="dashicons dashicons-marker"></span>',
	'center right' => '<span class="dashicons dashicons-arrow-right-alt"></span>',
	'bottom left' => '<span class="dashicons dashicons-arrow-left-alt"></span>',
	'bottom center' => '<span class="dashicons dashicons-arrow-down-alt"></span>',
	'bottom right' => '<span class="dashicons dashicons-arrow-right-alt"></span>',
);


// Theme Options Config
return array(
	'general' => array(
		'title' => us_translate_x( 'General', 'settings screen' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/mixer',
		'fields' => array(
			'maintenance_mode' => array(
				'title' => __( 'Maintenance Mode', 'us' ),
				'description' => __( 'When this option is ON, all not logged in users will see only specific page selected by you. This is useful when your site is under construction.', 'us' ),
				'type' => 'switch',
				'text' => __( 'Show for site visitors only specific page', 'us' ),
				'std' => 0,
				'classes' => 'color_yellow',
			),
			'maintenance_page' => array(
				'type' => 'select',
				'options' => $us_page_list,
				'std' => '',
				'classes' => 'for_above',
				'show_if' => array( 'maintenance_mode', '=', TRUE ),
			),
			'maintenance_503' => array(
				'description' => __( 'When this option is ON, your site will send HTTP 503 response to search engines. Use this option only for short period of time.', 'us' ),
				'type' => 'switch',
				'text' => __( 'Enable "503 Service Unavailable" status', 'us' ),
				'std' => 0,
				'classes' => 'for_above',
				'show_if' => array( 'maintenance_mode', '=', TRUE ),
			),
			'page_404' => array(
				'title' => __( 'Page "404 Not Found"', 'us' ),
				'description' => __( 'Selected page will be shown instead of the "Page not found" message.', 'us' ),
				'type' => 'select',
				'options' => us_array_merge(
					array( 'default' => '&ndash; ' . us_translate( 'Default' ) . ' &ndash;' ), $us_page_list
				),
				'std' => 'default',
			),
			'site_icon' => array(
				'title' => us_translate( 'Site Icon' ),
				'description' => us_translate( 'Site Icons are what you see in browser tabs, bookmark bars, and within the WordPress mobile apps. Upload one here!' ) . '<br>' . sprintf( us_translate( 'Site Icons should be square and at least %s pixels.' ), '<strong>512</strong>' ),
				'type' => 'upload',
			),
			'preloader' => array(
				'title' => __( 'Preloader Screen', 'us' ),
				'type' => 'select',
				'options' => array(
					'disabled' => __( 'Disabled', 'us' ),
					'1' => sprintf( __( 'Shows Preloader %d', 'us' ), 1 ),
					'2' => sprintf( __( 'Shows Preloader %d', 'us' ), 2 ),
					'3' => sprintf( __( 'Shows Preloader %d', 'us' ), 3 ),
					'4' => sprintf( __( 'Shows Preloader %d', 'us' ), 4 ),
					'5' => sprintf( __( 'Shows Preloader %d', 'us' ), 5 ),
					'custom' => __( 'Shows Custom Image', 'us' ),
				),
				'std' => 'disabled',
			),
			'preloader_image' => array(
				'title' => '',
				'type' => 'upload',
				'classes' => 'for_above',
				'show_if' => array( 'preloader', '=', 'custom' ),
			),
			'rounded_corners' => array(
				'title' => __( 'Rounded Corners', 'us' ),
				'type' => 'switch',
				'text' => __( 'Enable rounded corners of theme elements', 'us' ),
				'std' => 1,
			),
			'links_underline' => array(
				'title' => __( 'Links Underline', 'us' ),
				'type' => 'switch',
				'text' => __( 'Underline text links on hover', 'us' ),
				'std' => 0,
			),
			'keyboard_accessibility' => array(
				'title' => __( 'Keyboard Accessibility', 'us' ),
				'type' => 'switch',
				'text' => __( 'Highlight theme elements on focus', 'us' ),
				'std' => 0,
			),
			'back_to_top' => array(
				'title' => __( '"Back to top" Button', 'us' ),
				'type' => 'switch',
				'text' => __( 'Enable button which allows scroll a page back to the top', 'us' ),
				'std' => 1,
			),
			'wrapper_back_to_top_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'force_right',
				'show_if' => array( 'back_to_top', '=', TRUE ),
			),
			'back_to_top_pos' => array(
				'title' => __( 'Button Position', 'us' ),
				'type' => 'radio',
				'options' => array(
					'left' => us_translate( 'Left' ),
					'right' => us_translate( 'Right' ),
				),
				'std' => 'right',
				'classes' => 'width_full cols_2',
			),
			'back_to_top_color' => array(
				'type' => 'color',
				'title' => __( 'Button Color', 'us' ),
				'std' => 'rgba(0,0,0,0.3)',
				'classes' => 'width_full cols_2',
			),
			'back_to_top_display' => array(
				'title' => __( 'Show Button after page is scrolled to', 'us' ),
				'description' => __( '1vh equals 1% of the screen height', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 200,
				'step' => 10,
				'std' => 100,
				'postfix' => 'vh',
				'classes' => 'width_full',
			),
			'wrapper_back_to_top_end' => array(
				'type' => 'wrapper_end',
			),
			'smooth_scroll_duration' => array(
				'title' => __( 'Smooth Scroll Duration', 'us' ),
				'type' => 'slider',
				'min' => 0,
				'max' => 3000,
				'step' => 100,
				'std' => 1000,
				'postfix' => 'ms',
			),
			'gmaps_api_key' => array(
				'title' => __( 'Google Maps API Key', 'us' ),
				'description' => __( 'The API key is required for the domains created after June 22, 2016.', 'us' ) . ' <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">' . __( 'Get API key', 'us' ) . '</a>',
				'type' => 'text',
				'std' => '',
			),
		),
	),
	'layout' => array(
		'title' => __( 'Site Layout', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/layout',
		'fields' => array(
			'responsive_layout' => array(
				'title' => __( 'Responsive Layout', 'us' ),
				'type' => 'switch',
				'text' => __( 'Enable responsive layout', 'us' ),
				'std' => 1,
			),
			'canvas_layout' => array(
				'title' => __( 'Site Canvas Layout', 'us' ),
				'type' => 'imgradio',
				'options' => array(
					'wide' => 'framework/admin/img/usof/canvas-wide',
					'boxed' => 'framework/admin/img/usof/canvas-boxed',
				),
				'std' => 'wide',
			),
			'color_body_bg' => array(
				'type' => 'color',
				'title' => __( 'Body Background Color', 'us' ),
				'std' => '#eeeeee',
				'show_if' => array( 'canvas_layout', '=', 'boxed' ),
			),
			'body_bg_image' => array(
				'title' => __( 'Body Background Image', 'us' ),
				'type' => 'upload',
				'show_if' => array( 'canvas_layout', '=', 'boxed' ),
			),
			'wrapper_body_bg_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'force_right',
				'show_if' => array(
					array( 'canvas_layout', '=', 'boxed' ),
					'and',
					array( 'body_bg_image', '!=', '' ),
				),
			),
			'body_bg_image_size' => array(
				'title' => __( 'Background Image Size', 'us' ),
				'type' => 'radio',
				'options' => array(
					'cover' => __( 'Fill Area', 'us' ),
					'contain' => __( 'Fit to Area', 'us' ),
					'initial' => __( 'Initial', 'us' ),
				),
				'std' => 'cover',
				'classes' => 'width_full',
			),
			'body_bg_image_repeat' => array(
				'title' => __( 'Background Image Repeat', 'us' ),
				'type' => 'radio',
				'options' => array(
					'repeat' => __( 'Repeat', 'us' ),
					'repeat-x' => __( 'Horizontally', 'us' ),
					'repeat-y' => __( 'Vertically', 'us' ),
					'no-repeat' => us_translate( 'None' ),
				),
				'std' => 'repeat',
				'classes' => 'width_full',
			),
			'body_bg_image_position' => array(
				'title' => __( 'Background Image Position', 'us' ),
				'type' => 'radio',
				'options' => $usof_bg_pos_values,
				'std' => 'top left',
				'classes' => 'bgpos width_full',
			),
			'body_bg_image_attachment' => array(
				'type' => 'switch',
				'text' => us_translate( 'Scroll with Page' ),
				'std' => 1,
				'classes' => 'width_full',
			),
			'wrapper_body_bg_end' => array(
				'type' => 'wrapper_end',
			),
			'site_canvas_width' => array(
				'title' => __( 'Site Canvas Width', 'us' ),
				'type' => 'slider',
				'min' => 1000,
				'max' => 1700,
				'step' => 10,
				'std' => 1300,
				'postfix' => 'px',
				'show_if' => array( 'canvas_layout', '=', 'boxed' ),
			),
			'site_content_width' => array(
				'title' => __( 'Site Content Width', 'us' ),
				'type' => 'slider',
				'min' => 900,
				'max' => 1600,
				'step' => 10,
				'std' => 1140,
				'postfix' => 'px',
			),
			'sidebar_width' => array(
				'title' => __( 'Sidebar Width', 'us' ),
				'description' => sprintf( __( 'Relative to the value of "%s" option.', 'us' ), __( 'Site Content Width', 'us' ) ) . ' ' . __( 'Used for pages with sidebar.', 'us' ),
				'type' => 'slider',
				'min' => 20,
				'max' => 50,
				'std' => 25,
				'postfix' => '%',
			),
			'content_width' => array(
				'title' => __( 'Content Width', 'us' ),
				'description' => sprintf( __( 'Relative to the value of "%s" option.', 'us' ), __( 'Site Content Width', 'us' ) ) . ' ' . __( 'Used for pages with sidebar.', 'us' ),
				'type' => 'slider',
				'min' => 50,
				'max' => 80,
				'std' => 70,
				'postfix' => '%',
			),
			'columns_stacking_width' => array(
				'title' => __( 'Columns Stacking Width', 'us' ),
				'description' => __( 'When screen width is less than this value, all columns within a row will become a single column.', 'us' ),
				'type' => 'slider',
				'min' => 768,
				'max' => 1025,
				'std' => 768,
				'postfix' => 'px',
				'show_if' => array( 'responsive_layout', '=', TRUE ),
			),
			'disable_effects_width' => array(
				'title' => __( 'Effects Disabling Width', 'us' ),
				'description' => __( 'When screen width is less than this value, vertical parallax and animation of elements appearance will be disabled.', 'us' ),
				'type' => 'slider',
				'min' => 300,
				'max' => 1025,
				'std' => 900,
				'postfix' => 'px',
			),
			'row_height' => array(
				'title' => __( 'Row Height by default', 'us' ),
				'type' => 'select',
				'options' => array(
					'auto' => __( 'Equals the content height', 'us' ),
					'small' => __( 'Small', 'us' ),
					'medium' => __( 'Medium', 'us' ),
					'large' => __( 'Large', 'us' ),
					'huge' => __( 'Huge', 'us' ),
					'full' => __( 'Full Screen', 'us' ),
				),
				'std' => 'medium',
			),
		),
	),
	'colors' => array(
		'title' => us_translate( 'Colors' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/colors',
		'fields' => array(

			// Color Schemes
			'color_style' => array(
				'title' => __( 'Choose Website Color Scheme', 'us' ),
				'type' => 'style_scheme',
			),

			'change_colors_start' => array(
				'type' => 'wrapper_start',
			),

			// Header colors
			'change_header_colors_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_colors',
			),
			'h_colors_1' => array(
				'title' => __( 'Header colors', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'color_header_top_bg' => array(
				'type' => 'color',
				'text' => __( 'Top Area Background Color', 'us' ),
			),
			'color_header_top_text' => array(
				'type' => 'color',
				'text' => __( 'Top Area Text Color', 'us' ),
			),
			'color_header_top_text_hover' => array(
				'type' => 'color',
				'text' => __( 'Top Area Text Hover Color', 'us' ),
			),
			'color_header_middle_bg' => array(
				'type' => 'color',
				'text' => __( 'Main Area Background Color', 'us' ),
			),
			'color_header_middle_text' => array(
				'type' => 'color',
				'text' => __( 'Main Area Text Color', 'us' ),
			),
			'color_header_middle_text_hover' => array(
				'type' => 'color',
				'text' => __( 'Main Area Text Hover Color', 'us' ),
			),
			'color_header_bottom_bg' => array(
				'type' => 'color',
				'text' => __( 'Bottom Area Background Color', 'us' ),
			),
			'color_header_bottom_text' => array(
				'type' => 'color',
				'text' => __( 'Bottom Area Text Color', 'us' ),
			),
			'color_header_bottom_text_hover' => array(
				'type' => 'color',
				'text' => __( 'Bottom Area Text Hover Color', 'us' ),
			),
			'color_header_transparent_text' => array(
				'type' => 'color',
				'text' => __( 'Transparent Header Text Color', 'us' ),
			),
			'color_header_transparent_text_hover' => array(
				'type' => 'color',
				'text' => __( 'Transparent Header Hover Text Color', 'us' ),
			),
			'color_header_search_bg' => array(
				'type' => 'color',
				'text' => __( 'Search Field Background Color', 'us' ),
			),
			'color_header_search_text' => array(
				'type' => 'color',
				'text' => __( 'Search Field Text Color', 'us' ),
			),
			'color_chrome_toolbar' => array(
				'type' => 'color',
				'text' => __( 'Toolbar Color in Chrome for Android', 'us' ),
			),
			'change_header_colors_end' => array(
				'type' => 'wrapper_end',
			),

			// Header Menu colors
			'change_menu_colors_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_colors',
			),
			'h_colors_2' => array(
				'title' => __( 'Header Menu colors', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'color_menu_transparent_active_text' => array(
				'type' => 'color',
				'text' => __( 'Transparent Menu Active Text Color', 'us' ),
			),
			'color_menu_active_bg' => array(
				'type' => 'color',
				'text' => __( 'Menu Active Background Color', 'us' ),
			),
			'color_menu_active_text' => array(
				'type' => 'color',
				'text' => __( 'Menu Active Text Color', 'us' ),
			),
			'color_menu_hover_bg' => array(
				'type' => 'color',
				'text' => __( 'Menu Hover Background Color', 'us' ),
			),
			'color_menu_hover_text' => array(
				'type' => 'color',
				'text' => __( 'Menu Hover Text Color', 'us' ),
			),
			'color_drop_bg' => array(
				'type' => 'color',
				'text' => __( 'Dropdown Background Color', 'us' ),
			),
			'color_drop_text' => array(
				'type' => 'color',
				'text' => __( 'Dropdown Text Color', 'us' ),
			),
			'color_drop_hover_bg' => array(
				'type' => 'color',
				'text' => __( 'Dropdown Hover Background Color', 'us' ),
			),
			'color_drop_hover_text' => array(
				'type' => 'color',
				'text' => __( 'Dropdown Hover Text Color', 'us' ),
			),
			'color_drop_active_bg' => array(
				'type' => 'color',
				'text' => __( 'Dropdown Active Background Color', 'us' ),
			),
			'color_drop_active_text' => array(
				'type' => 'color',
				'text' => __( 'Dropdown Active Text Color', 'us' ),
			),
			'color_menu_button_bg' => array(
				'type' => 'color',
				'text' => __( 'Menu Button Background Color', 'us' ),
			),
			'color_menu_button_text' => array(
				'type' => 'color',
				'text' => __( 'Menu Button Text Color', 'us' ),
			),
			'color_menu_button_hover_bg' => array(
				'type' => 'color',
				'text' => __( 'Menu Button Hover Background Color', 'us' ),
			),
			'color_menu_button_hover_text' => array(
				'type' => 'color',
				'text' => __( 'Menu Button Hover Text Color', 'us' ),
			),
			'change_menu_colors_end' => array(
				'type' => 'wrapper_end',
			),

			// Content colors
			'change_content_colors_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_colors',
			),
			'h_colors_3' => array(
				'title' => __( 'Content colors', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'color_content_bg' => array(
				'type' => 'color',
				'text' => __( 'Background Color', 'us' ),
			),
			'color_content_bg_alt' => array(
				'type' => 'color',
				'text' => __( 'Alternate Background Color', 'us' ),
			),
			'color_content_border' => array(
				'type' => 'color',
				'text' => __( 'Border Color', 'us' ),
			),
			'color_content_heading' => array(
				'type' => 'color',
				'text' => __( 'Heading Color', 'us' ),
			),
			'color_content_text' => array(
				'type' => 'color',
				'text' => __( 'Text Color', 'us' ),
			),
			'color_content_link' => array(
				'type' => 'color',
				'text' => __( 'Link Color', 'us' ),
			),
			'color_content_link_hover' => array(
				'type' => 'color',
				'text' => __( 'Link Hover Color', 'us' ),
			),
			'color_content_primary' => array(
				'type' => 'color',
				'text' => __( 'Primary Color', 'us' ),
			),
			'color_content_secondary' => array(
				'type' => 'color',
				'text' => __( 'Secondary Color', 'us' ),
			),
			'color_content_faded' => array(
				'type' => 'color',
				'text' => __( 'Faded Elements Color', 'us' ),
			),
			'change_content_colors_end' => array(
				'type' => 'wrapper_end',
			),

			// Alternate Content colors
			'change_alt_content_colors_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_colors',
			),
			'h_colors_4' => array(
				'title' => __( 'Alternate Content colors', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'color_alt_content_bg' => array(
				'type' => 'color',
				'text' => __( 'Background Color', 'us' ),
			),
			'color_alt_content_bg_alt' => array(
				'type' => 'color',
				'text' => __( 'Alternate Background Color', 'us' ),
			),
			'color_alt_content_border' => array(
				'type' => 'color',
				'text' => __( 'Border Color', 'us' ),
			),
			'color_alt_content_heading' => array(
				'type' => 'color',
				'text' => __( 'Heading Color', 'us' ),
			),
			'color_alt_content_text' => array(
				'type' => 'color',
				'text' => __( 'Text Color', 'us' ),
			),
			'color_alt_content_link' => array(
				'type' => 'color',
				'text' => __( 'Link Color', 'us' ),
			),
			'color_alt_content_link_hover' => array(
				'type' => 'color',
				'text' => __( 'Link Hover Color', 'us' ),
			),
			'color_alt_content_primary' => array(
				'type' => 'color',
				'text' => __( 'Primary Color', 'us' ),
			),
			'color_alt_content_secondary' => array(
				'type' => 'color',
				'text' => __( 'Secondary Color', 'us' ),
			),
			'color_alt_content_faded' => array(
				'type' => 'color',
				'text' => __( 'Faded Elements Color', 'us' ),
			),
			'change_alt_content_colors_end' => array(
				'type' => 'wrapper_end',
			),

			// Top Footer colors
			'change_subfooter_colors_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_colors',
			),
			'h_colors_5' => array(
				'title' => __( 'Top Footer colors', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'color_subfooter_bg' => array(
				'type' => 'color',
				'text' => __( 'Background Color', 'us' ),
			),
			'color_subfooter_bg_alt' => array(
				'type' => 'color',
				'text' => __( 'Alternate Background Color', 'us' ),
			),
			'color_subfooter_border' => array(
				'type' => 'color',
				'text' => __( 'Border Color', 'us' ),
			),
			'color_subfooter_text' => array(
				'type' => 'color',
				'text' => __( 'Text Color', 'us' ),
			),
			'color_subfooter_link' => array(
				'type' => 'color',
				'text' => __( 'Link Color', 'us' ),
			),
			'color_subfooter_link_hover' => array(
				'type' => 'color',
				'text' => __( 'Link Hover Color', 'us' ),
			),
			'change_subfooter_colors_end' => array(
				'type' => 'wrapper_end',
			),

			// Bottom Footer colors
			'change_footer_colors_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_colors',
			),
			'h_colors_6' => array(
				'title' => __( 'Bottom Footer colors', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'color_footer_bg' => array(
				'type' => 'color',
				'text' => __( 'Background Color', 'us' ),
			),
			'color_footer_bg_alt' => array(
				'type' => 'color',
				'text' => __( 'Alternate Background Color', 'us' ),
			),
			'color_footer_border' => array(
				'type' => 'color',
				'text' => __( 'Border Color', 'us' ),
			),
			'color_footer_text' => array(
				'type' => 'color',
				'text' => __( 'Text Color', 'us' ),
			),
			'color_footer_link' => array(
				'type' => 'color',
				'text' => __( 'Link Color', 'us' ),
			),
			'color_footer_link_hover' => array(
				'type' => 'color',
				'text' => __( 'Link Hover Color', 'us' ),
			),
			'change_footer_colors_end' => array(
				'type' => 'wrapper_end',
			),
			'change_colors_end' => array(
				'type' => 'wrapper_end',
			),
		),
	),
	'header' => array(
		'title' => _x( 'Header', 'site top area', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/header',
		'fields' => array(
			'header_info' => array(
				'description' => sprintf( __( 'To add more elements into your website header, install %sHeader Builder addon%s.', 'us' ), '<strong><a href="' . admin_url( 'admin.php?page=us-addons' ) . '" target="_blank">', '</strong></a>' ),
				'type' => 'message',
				'classes' => 'width_full color_blue',
			),
			'header_layout' => array(
				'type' => 'imgradio',
				'options' => array(
					'simple_1' => 'framework/admin/img/usof/ht-standard',
					'extended_1' => 'framework/admin/img/usof/ht-extended',
					'extended_2' => 'framework/admin/img/usof/ht-advanced',
					'centered_1' => 'framework/admin/img/usof/ht-centered',
					'vertical_1' => 'framework/admin/img/usof/ht-sided',
				),
				'std' => 'simple_1',
				'classes' => 'width_full',
			),
			'header_sticky' => array(
				'title' => __( 'Sticky Header', 'us' ),
				'type' => 'checkboxes',
				'options' => array(
					'default' => __( 'On Desktops', 'us' ),
					'tablets' => __( 'On Tablets', 'us' ),
					'mobiles' => __( 'On Mobiles', 'us' ),
				),
				'description' => __( 'Fix the header at the top of a page during scroll', 'us' ),
				'std' => array( 'default', 'tablets', 'mobiles' ),
				'show_if' => array( 'header_layout', '!=', 'vertical_1' ),
			),
			'header_transparent' => array(
				'title' => __( 'Transparent Header', 'us' ),
				'type' => 'switch',
				'text' => __( 'Make the header transparent at its initial position', 'us' ),
				'std' => 0,
				'show_if' => array( 'header_layout', '!=', 'vertical_1' ),
			),
			'header_fullwidth' => array(
				'title' => __( 'Full Width Content', 'us' ),
				'type' => 'switch',
				'text' => __( 'Stretch header content to the screen width', 'us' ),
				'std' => 0,
				'show_if' => array( 'header_layout', '!=', 'vertical_1' ),
			),
			'header_top_height' => array(
				'title' => __( 'Top Area Height', 'us' ),
				'type' => 'slider',
				'min' => 36,
				'max' => 300,
				'std' => 40,
				'postfix' => 'px',
				'show_if' => array( 'header_layout', '=', 'extended_1' ),
			),
			'header_top_sticky_height' => array(
				'title' => __( 'Top Sticky Area Height', 'us' ),
				'type' => 'slider',
				'min' => 0,
				'max' => 300,
				'std' => 0,
				'postfix' => 'px',
				'show_if' => array(
					array( 'header_sticky', 'has', 'default' ),
					'and',
					array( 'header_layout', '=', 'extended_1' ),
				),
			),
			'header_middle_height' => array(
				'title' => __( 'Main Area Height', 'us' ),
				'type' => 'slider',
				'min' => 50,
				'max' => 300,
				'std' => 100,
				'postfix' => 'px',
				'show_if' => array( 'header_layout', '!=', 'vertical_1' ),
			),
			'header_middle_sticky_height' => array(
				'title' => __( 'Main Sticky Area Height', 'us' ),
				'type' => 'slider',
				'min' => 0,
				'max' => 300,
				'std' => 50,
				'postfix' => 'px',
				'show_if' => array(
					array( 'header_sticky', 'has', 'default' ),
					'and',
					array( 'header_layout', '!=', 'vertical_1' ),
				),
			),
			'header_bottom_height' => array(
				'title' => __( 'Bottom Area Height', 'us' ),
				'type' => 'slider',
				'min' => 36,
				'max' => 300,
				'std' => 50,
				'postfix' => 'px',
				'show_if' => array( 'header_layout', 'in', array( 'extended_2', 'centered_1' ) ),
			),
			'header_bottom_sticky_height' => array(
				'title' => __( 'Bottom Sticky Area Height', 'us' ),
				'type' => 'slider',
				'min' => 0,
				'max' => 300,
				'std' => 50,
				'postfix' => 'px',
				'show_if' => array(
					array( 'header_sticky', 'has', 'default' ),
					'and',
					array( 'header_layout', 'in', array( 'extended_2', 'centered_1' ) ),
				),
			),
			'header_main_width' => array(
				'title' => __( 'Header Width', 'us' ),
				'type' => 'slider',
				'min' => 200,
				'max' => 400,
				'std' => 300,
				'postfix' => 'px',
				'show_if' => array( 'header_layout', '=', 'vertical_1' ),
			),
			'header_invert_logo_pos' => array(
				'title' => __( 'Inverted Logo Position', 'us' ),
				'type' => 'switch',
				'text' => __( 'Place Logo to the right side of the Header', 'us' ),
				'std' => 0,
				'show_if' => array( 'header_layout', 'in', array( 'simple_1', 'extended_1', 'extended_2' ) ),
			),

			// Header elements
			'h_header_2' => array(
				'title' => __( 'Header Elements', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'header_search_show' => array(
				'type' => 'switch',
				'text' => us_translate( 'Search' ),
				'std' => 1,
				'classes' => 'width_full',
			),
			'wrapper_search_start' => array(
				'type' => 'wrapper_start',
				'show_if' => array( 'header_search_show', '=', TRUE ),
			),
			'header_search_layout' => array(
				'title' => __( 'Layout', 'us' ),
				'type' => 'radio',
				'options' => array(
					'simple' => __( 'Simple', 'us' ),
					'modern' => __( 'Modern', 'us' ),
					'fullwidth' => __( 'Full Width', 'us' ),
					'fullscreen' => __( 'Full Screen', 'us' ),
				),
				'std' => 'fullscreen',
			),
			'wrapper_search_end' => array(
				'type' => 'wrapper_end',
			),
			'header_contacts_show' => array(
				'type' => 'switch',
				'text' => us_translate( 'Contact Info' ),
				'std' => 0,
				'show_if' => array( 'header_layout', 'not in', array( 'simple_1', 'centered_1' ) ),
				'classes' => 'width_full',
			),
			'wrapper_contacts_start' => array(
				'type' => 'wrapper_start',
				'show_if' => array(
					array( 'header_layout', 'not in', array( 'simple_1', 'centered_1' ) ),
					'and',
					array( 'header_contacts_show', '=', TRUE ),
				),
			),
			'header_contacts_phone' => array(
				'title' => __( 'Phone Number', 'us' ),
				'type' => 'text',
				'classes' => 'cols_2 width_full',
			),
			'header_contacts_email' => array(
				'title' => us_translate( 'Email' ),
				'type' => 'text',
				'classes' => 'cols_2 width_full',
			),
			'wrapper_contacts_end' => array(
				'type' => 'wrapper_end',
			),

			// Logo
			'h_header_3' => array(
				'title' => __( 'Logo', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'logo_type' => array(
				'type' => 'radio',
				'options' => array(
					'text' => us_translate( 'Text' ),
					'img' => us_translate( 'Image' ),
				),
				'std' => 'text',
				'classes' => 'width_full',
			),
			'logo_text' => array(
				'title' => us_translate( 'Text' ),
				'type' => 'text',
				'std' => 'LOGO',
				'show_if' => array( 'logo_type', '=', 'text' ),
			),
			'logo_font_size' => array(
				'title' => __( 'Font Size', 'us' ),
				'type' => 'slider',
				'min' => 12,
				'max' => 50,
				'std' => 26,
				'postfix' => 'px',
				'show_if' => array( 'logo_type', '=', 'text' ),
			),
			'logo_font_size_tablets' => array(
				'title' => __( 'Font Size on Tablets', 'us' ),
				'description' => __( 'This option is enabled when screen width is less than 900px', 'us' ),
				'type' => 'slider',
				'min' => 12,
				'max' => 50,
				'std' => 24,
				'postfix' => 'px',
				'show_if' => array( 'logo_type', '=', 'text' ),
			),
			'logo_font_size_mobiles' => array(
				'title' => __( 'Font Size on Mobiles', 'us' ),
				'description' => __( 'This option is enabled when screen width is less than 600px', 'us' ),
				'type' => 'slider',
				'min' => 12,
				'max' => 50,
				'std' => 20,
				'postfix' => 'px',
				'show_if' => array( 'logo_type', '=', 'text' ),
			),
			'logo_image' => array(
				'title' => us_translate( 'Image' ),
				'type' => 'upload',
				'show_if' => array( 'logo_type', '=', 'img' ),
			),
			'logo_height' => array(
				'title' => us_translate( 'Height' ),
				'type' => 'slider',
				'min' => 20,
				'max' => 300,
				'std' => 60,
				'postfix' => 'px',
				'show_if' => array( 'logo_type', '=', 'img' ),
			),
			'logo_height_sticky' => array(
				'title' => __( 'Height in the Sticky Header', 'us' ),
				'type' => 'slider',
				'min' => 20,
				'max' => 300,
				'std' => 60,
				'postfix' => 'px',
				'show_if' => array(
					array( 'logo_type', '=', 'img' ),
					'and',
					array( 'header_layout', '!=', 'vertical_1' ),
				),
			),
			'logo_height_tablets' => array(
				'title' => __( 'Height on Tablets', 'us' ),
				'description' => __( 'This option is enabled when screen width is less than 900px', 'us' ),
				'type' => 'slider',
				'min' => 20,
				'max' => 300,
				'std' => 40,
				'postfix' => 'px',
				'show_if' => array( 'logo_type', '=', 'img' ),
			),
			'logo_height_mobiles' => array(
				'title' => __( 'Height on Mobiles', 'us' ),
				'description' => __( 'This option is enabled when screen width is less than 600px', 'us' ),
				'type' => 'slider',
				'min' => 20,
				'max' => 300,
				'std' => 30,
				'postfix' => 'px',
				'show_if' => array( 'logo_type', '=', 'img' ),
			),

			// Menu
			'h_header_4' => array(
				'title' => us_translate( 'Menu' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'menu_source' => array(
				'title' => us_translate( 'Menu' ),
				'description' => sprintf( __( 'Add or edit a menu on the %s page', 'us' ), '<a href="' . admin_url( 'nav-menus.php' ) . '" target="_blank">' . us_translate( 'Menus' ) . '</a>' ),
				'type' => 'select',
				'options' => us_get_nav_menus(),
				'std' => 'main',
			),
			'menu_fontsize' => array(
				'title' => __( 'Main Items Font Size', 'us' ),
				'type' => 'slider',
				'min' => 12,
				'max' => 50,
				'std' => 16,
				'postfix' => 'px',
			),
			'menu_indents' => array(
				'title' => __( 'Distance Between Main Items', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 100,
				'step' => 2,
				'std' => 40,
				'postfix' => 'px',
			),
			'menu_height' => array(
				'title' => __( 'Main Items Height', 'us' ),
				'type' => 'switch',
				'text' => __( 'Stretch menu items to the full height of the header', 'us' ),
				'std' => 0,
			),
			'menu_sub_fontsize' => array(
				'title' => __( 'Dropdown Font Size', 'us' ),
				'type' => 'slider',
				'min' => 12,
				'max' => 50,
				'std' => 15,
				'postfix' => 'px',
			),
			'menu_mobile_width' => array(
				'title' => __( 'Enable mobile layout when screen width is less than', 'us' ),
				'type' => 'slider',
				'min' => 300,
				'max' => 2000,
				'std' => 900,
				'postfix' => 'px',
			),
			'menu_togglable_type' => array(
				'title' => __( 'Dropdown Behavior', 'us' ),
				'description' => __( 'When this option is OFF, mobile menu dropdown will be shown by click on an arrow only.', 'us' ),
				'type' => 'switch',
				'text' => __( 'Show dropdown by click on menu item title', 'us' ),
				'std' => 1,
			),
		),
	),

	// Pages Layout
	'pages_layout' => array(
		'title' => __( 'Pages Layout', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/page',
		'fields' => array_merge(
			array(
				'header_info_2' => array(
					'description' => sprintf( __( 'To display different website headers based on page type, install %sHeader Builder addon%s.', 'us' ), '<strong><a href="' . admin_url( 'admin.php?page=us-addons' ) . '" target="_blank">', '</strong></a>' ),
					'type' => 'message',
					'classes' => 'width_full color_blue',
					'place_if' => ! is_plugin_active( 'us-header-builder/us-header-builder.php' ),
				),
				// Defaults
				'h_defaults' => array(
					'title' => __( 'Defaults', 'us' ),
					'type' => 'heading',
					'classes' => 'with_separator sticky',
				),
				'header_id' => array(
					'title' => _x( 'Header', 'site top area', 'us' ),
					'description' => $headers_description,
					'type' => 'select',
					'options' => us_array_merge(
						array( '' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;' ), $us_headers_list
					),
					'std' => key( $us_headers_list ),
					'place_if' => is_plugin_active( 'us-header-builder/us-header-builder.php' ),
				),
				'titlebar_id' => array(
					'title' => __( 'Title Bar', 'us' ),
					'description' => $titlebars_description,
					'type' => 'select',
					'options' => us_array_merge(
						array( '' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;' ), $us_page_blocks_list
					),
					'std' => '',
				),
				'sidebar_id' => array(
					'title' => __( 'Sidebar', 'us' ),
					'description' => $sidebars_description,
					'type' => 'select',
					'options' => us_array_merge(
						array( '' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;' ), $sidebars_list
					),
					'std' => '',
				),
				'sidebar_pos' => array(
					'type' => 'radio',
					'options' => array(
						'left' => us_translate( 'Left' ),
						'right' => us_translate( 'Right' ),
					),
					'std' => 'right',
					'classes' => 'for_above',
					'show_if' => array( 'sidebar_id', '!=', '' ),
				),
				'footer_id' => array(
					'title' => __( 'Footer', 'us' ),
					'description' => $footers_description,
					'type' => 'select',
					'options' => us_array_merge(
						array( '' => '&ndash; ' . __( 'Do not display', 'us' ) . ' &ndash;' ), $us_page_blocks_list
					),
					'std' => '',
				),
			), $pages_layout_config
		),
	),

	'typography' => array(
		'title' => __( 'Typography', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/style',
		'fields' => array(

			// Regular Text
			'h_typography_1' => array(
				'title' => __( 'Regular Text', 'us' ),
				'type' => 'heading',
			),
			'body_font_family' => array(
				'type' => 'font',
				'preview' => array(
					'text' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque vel egestas leo. Nunc vel mauris eleifend, scelerisque nibh a, viverra quam. Proin eu venenatis ipsum. Nullam accumsan, velit nec egestas dictum, magna magna ornare nulla, a egestas mauris urna in neque.', 'us' ),
					'size_field' => 'body_fontsize',
					'lineheight_field' => 'body_lineheight',
				),
				'std' => 'Georgia, serif',
			),
			'body_text_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_font col_first',
			),
			'body_fontsize' => array(
				'description' => __( 'Font Size', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 20,
				'std' => 15,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'body_lineheight' => array(
				'description' => __( 'Line height', 'us' ),
				'type' => 'slider',
				'min' => 15,
				'max' => 35,
				'std' => 25,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'body_text_end' => array(
				'type' => 'wrapper_end',
			),
			'body_text_mobiles_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'for_font',
			),
			'body_fontsize_mobile' => array(
				'description' => __( 'Font Size on Mobiles', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 20,
				'std' => 15,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'body_lineheight_mobile' => array(
				'description' => __( 'Line height on Mobiles', 'us' ),
				'type' => 'slider',
				'min' => 15,
				'max' => 35,
				'std' => 25,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'body_text_mobiles_end' => array(
				'type' => 'wrapper_end',
			),

			// Headings
			'h_typography_2' => array(
				'title' => __( 'Headings', 'us' ),
				'type' => 'heading',
			),
			'heading_font_family' => array(
				'type' => 'font',
				'preview' => array(
					'text' => __( 'Heading Font Preview', 'us' ),
					'size_field' => 'h1_fontsize',
					'weight_field' => 'h1_fontweight',
					'letterspacing_field' => 'h1_letterspacing',
					'transform_field' => 'h1_transform',
					'color_field' => 'h1_color',
					'for_heading' => TRUE,
				),
				'std' => 'none',
			),
			'h1_start' => array(
				'title' => us_translate( 'Heading 1' ),
				'type' => 'wrapper_start',
				'classes' => 'for_font col_first',
			),
			'h1_fontsize' => array(
				'description' => __( 'Font Size', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 60,
				'std' => 40,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'h1_fontsize_mobile' => array(
				'description' => __( 'Font Size on Mobiles', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 60,
				'std' => 30,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'h1_lineheight' => array(
				'description' => __( 'Line height', 'us' ),
				'type' => 'slider',
				'min' => 1.00,
				'max' => 2.00,
				'step' => 0.01,
				'std' => 1.40,
				'classes' => 'inline slider_below',
			),
			'h1_fontweight' => array(
				'description' => __( 'Font Weight', 'us' ),
				'type' => 'slider',
				'min' => 100,
				'max' => 900,
				'step' => 100,
				'std' => 400,
				'classes' => 'inline slider_below',
			),
			'h1_letterspacing' => array(
				'description' => __( 'Letter Spacing', 'us' ),
				'type' => 'slider',
				'min' => - 0.10,
				'max' => 0.20,
				'step' => 0.01,
				'std' => 0,
				'postfix' => 'em',
				'classes' => 'inline slider_below',
			),
			'h1_transform' => array(
				'type' => 'checkboxes',
				'options' => array(
					'uppercase' => __( 'Uppercase', 'us' ),
					'italic' => __( 'Italic', 'us' ),
				),
				'std' => array(),
				'classes' => 'inline',
			),
			'h1_color' => array(
				'type' => 'color',
				'std' => '',
				'classes' => 'inline clear_right',
			),
			'h1_end' => array(
				'type' => 'wrapper_end',
			),
			'h2_start' => array(
				'title' => us_translate( 'Heading 2' ),
				'type' => 'wrapper_start',
				'classes' => 'for_font',
			),
			'h2_fontsize' => array(
				'description' => __( 'Font Size', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 60,
				'std' => 34,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'h2_fontsize_mobile' => array(
				'description' => __( 'Font Size on Mobiles', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 60,
				'std' => 26,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'h2_lineheight' => array(
				'description' => __( 'Line height', 'us' ),
				'type' => 'slider',
				'min' => 1.00,
				'max' => 2.00,
				'step' => 0.01,
				'std' => 1.40,
				'classes' => 'inline slider_below',
			),
			'h2_fontweight' => array(
				'description' => __( 'Font Weight', 'us' ),
				'type' => 'slider',
				'min' => 100,
				'max' => 900,
				'step' => 100,
				'std' => 400,
				'classes' => 'inline slider_below',
			),
			'h2_letterspacing' => array(
				'description' => __( 'Letter Spacing', 'us' ),
				'type' => 'slider',
				'min' => - 0.10,
				'max' => 0.20,
				'step' => 0.01,
				'std' => 0,
				'postfix' => 'em',
				'classes' => 'inline slider_below',
			),
			'h2_transform' => array(
				'type' => 'checkboxes',
				'options' => array(
					'uppercase' => __( 'Uppercase', 'us' ),
					'italic' => __( 'Italic', 'us' ),
				),
				'std' => array(),
				'classes' => 'inline',
			),
			'h2_color' => array(
				'type' => 'color',
				'std' => '',
				'classes' => 'inline clear_right',
			),
			'h2_end' => array(
				'type' => 'wrapper_end',
			),
			'h3_start' => array(
				'title' => us_translate( 'Heading 3' ),
				'type' => 'wrapper_start',
				'classes' => 'for_font col_first',
			),
			'h3_fontsize' => array(
				'description' => __( 'Font Size', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 60,
				'std' => 28,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'h3_fontsize_mobile' => array(
				'description' => __( 'Font Size on Mobiles', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 60,
				'std' => 24,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'h3_lineheight' => array(
				'description' => __( 'Line height', 'us' ),
				'type' => 'slider',
				'min' => 1.00,
				'max' => 2.00,
				'step' => 0.01,
				'std' => 1.40,
				'classes' => 'inline slider_below',
			),
			'h3_fontweight' => array(
				'description' => __( 'Font Weight', 'us' ),
				'type' => 'slider',
				'min' => 100,
				'max' => 900,
				'step' => 100,
				'std' => 400,
				'classes' => 'inline slider_below',
			),
			'h3_letterspacing' => array(
				'description' => __( 'Letter Spacing', 'us' ),
				'type' => 'slider',
				'min' => - 0.10,
				'max' => 0.20,
				'step' => 0.01,
				'std' => 0,
				'postfix' => 'em',
				'classes' => 'inline slider_below',
			),
			'h3_transform' => array(
				'type' => 'checkboxes',
				'options' => array(
					'uppercase' => __( 'Uppercase', 'us' ),
					'italic' => __( 'Italic', 'us' ),
				),
				'std' => array(),
				'classes' => 'inline',
			),
			'h3_color' => array(
				'type' => 'color',
				'std' => '',
				'classes' => 'inline clear_right',
			),
			'h3_end' => array(
				'type' => 'wrapper_end',
			),
			'h4_start' => array(
				'title' => us_translate( 'Heading 4' ),
				'type' => 'wrapper_start',
				'classes' => 'for_font',
			),
			'h4_fontsize' => array(
				'description' => __( 'Font Size', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 60,
				'std' => 24,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'h4_fontsize_mobile' => array(
				'description' => __( 'Font Size on Mobiles', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 60,
				'std' => 22,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'h4_lineheight' => array(
				'description' => __( 'Line height', 'us' ),
				'type' => 'slider',
				'min' => 1.00,
				'max' => 2.00,
				'step' => 0.01,
				'std' => 1.40,
				'classes' => 'inline slider_below',
			),
			'h4_fontweight' => array(
				'description' => __( 'Font Weight', 'us' ),
				'type' => 'slider',
				'min' => 100,
				'max' => 900,
				'step' => 100,
				'std' => 400,
				'classes' => 'inline slider_below',
			),
			'h4_letterspacing' => array(
				'description' => __( 'Letter Spacing', 'us' ),
				'type' => 'slider',
				'min' => - 0.10,
				'max' => 0.20,
				'step' => 0.01,
				'std' => 0,
				'postfix' => 'em',
				'classes' => 'inline slider_below',
			),
			'h4_transform' => array(
				'type' => 'checkboxes',
				'options' => array(
					'uppercase' => __( 'Uppercase', 'us' ),
					'italic' => __( 'Italic', 'us' ),
				),
				'std' => array(),
				'classes' => 'inline',
			),
			'h4_color' => array(
				'type' => 'color',
				'std' => '',
				'classes' => 'inline clear_right',
			),
			'h4_end' => array(
				'type' => 'wrapper_end',
			),
			'h5_start' => array(
				'title' => us_translate( 'Heading 5' ),
				'type' => 'wrapper_start',
				'classes' => 'for_font col_first',
			),
			'h5_fontsize' => array(
				'description' => __( 'Font Size', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 60,
				'std' => 20,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'h5_fontsize_mobile' => array(
				'description' => __( 'Font Size on Mobiles', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 60,
				'std' => 20,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'h5_lineheight' => array(
				'description' => __( 'Line height', 'us' ),
				'type' => 'slider',
				'min' => 1.00,
				'max' => 2.00,
				'step' => 0.01,
				'std' => 1.40,
				'classes' => 'inline slider_below',
			),
			'h5_fontweight' => array(
				'description' => __( 'Font Weight', 'us' ),
				'type' => 'slider',
				'min' => 100,
				'max' => 900,
				'step' => 100,
				'std' => 400,
				'classes' => 'inline slider_below',
			),
			'h5_letterspacing' => array(
				'description' => __( 'Letter Spacing', 'us' ),
				'type' => 'slider',
				'min' => - 0.10,
				'max' => 0.20,
				'step' => 0.01,
				'std' => 0,
				'postfix' => 'em',
				'classes' => 'inline slider_below',
			),
			'h5_transform' => array(
				'type' => 'checkboxes',
				'options' => array(
					'uppercase' => __( 'Uppercase', 'us' ),
					'italic' => __( 'Italic', 'us' ),
				),
				'std' => array(),
				'classes' => 'inline',
			),
			'h5_color' => array(
				'type' => 'color',
				'std' => '',
				'classes' => 'inline clear_right',
			),
			'h5_end' => array(
				'type' => 'wrapper_end',
			),
			'h6_start' => array(
				'title' => us_translate( 'Heading 6' ),
				'type' => 'wrapper_start',
				'classes' => 'for_font',
			),
			'h6_fontsize' => array(
				'description' => __( 'Font Size', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 60,
				'std' => 18,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'h6_fontsize_mobile' => array(
				'description' => __( 'Font Size on Mobiles', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 60,
				'std' => 18,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'h6_lineheight' => array(
				'description' => __( 'Line height', 'us' ),
				'type' => 'slider',
				'min' => 1.00,
				'max' => 2.00,
				'step' => 0.01,
				'std' => 1.40,
				'classes' => 'inline slider_below',
			),
			'h6_fontweight' => array(
				'description' => __( 'Font Weight', 'us' ),
				'type' => 'slider',
				'min' => 100,
				'max' => 900,
				'step' => 100,
				'std' => 400,
				'classes' => 'inline slider_below',
			),
			'h6_letterspacing' => array(
				'description' => __( 'Letter Spacing', 'us' ),
				'type' => 'slider',
				'min' => - 0.10,
				'max' => 0.20,
				'step' => 0.01,
				'std' => 0,
				'postfix' => 'em',
				'classes' => 'inline slider_below',
			),
			'h6_transform' => array(
				'type' => 'checkboxes',
				'options' => array(
					'uppercase' => __( 'Uppercase', 'us' ),
					'italic' => __( 'Italic', 'us' ),
				),
				'std' => array(),
				'classes' => 'inline',
			),
			'h6_color' => array(
				'type' => 'color',
				'std' => '',
				'classes' => 'inline clear_right',
			),
			'h6_end' => array(
				'type' => 'wrapper_end',
			),

			// Additional Google Fonts
			'h_typography_3' => array(
				'title' => __( 'Additional Google Fonts', 'us' ),
				'type' => 'heading',
			),
			'custom_font' => array(
				'type' => 'group',
				'params' => array(
					'font_family' => array(
						'type' => 'font',
						'only_google' => TRUE,
						'preview' => array(
							'text' => __( 'Google Font Preview', 'us' ),
						),
						'std' => 'Open Sans',
					),
				),
			),

			// Google Fonts Subset
			'h_typography_5' => array(
				'title' => __( 'Google Fonts Subset', 'us' ),
				'type' => 'heading',
			),
			'font_subset' => array(
				'description' => sprintf( __( 'Check available languages for needed fonts on %s website', 'us' ), '<a href="https://fonts.google.com/" target="_blank">Google Fonts</a>' ),
				'type' => 'select',
				'options' => array(
					'arabic' => 'arabic',
					'cyrillic' => 'cyrillic',
					'cyrillic-ext' => 'cyrillic-ext',
					'greek' => 'greek',
					'greek-ext' => 'greek-ext',
					'hebrew' => 'hebrew',
					'khmer' => 'khmer',
					'latin' => 'latin',
					'latin-ext' => 'latin-ext',
					'vietnamese' => 'vietnamese',
				),
				'std' => 'latin',
				'classes' => 'width_full desc_1 for_above',
			),

			// Uploaded Fonts
			'h_typography_4' => array(
				'title' => __( 'Uploaded Fonts', 'us' ),
				'type' => 'heading',
			),
			'uploaded_fonts_info' => array(
				'description' => sprintf( __( 'Add custom fonts via uploading %s files.', 'us' ), '<strong>woff</strong>, <strong>woff2</strong>' ) . ' <a target="_blank" href="https://help.us-themes.com/' . strtolower( US_THEMENAME ) . '/options/typography/#uploaded-fonts">' . __( 'Read about usage of uploaded fonts', 'us' ) . '</a>',
				'type' => 'message',
				'classes' => 'width_full color_blue for_above',
			),
			'uploaded_fonts' => array(
				'type' => 'group',
				'classes' => 'with_wrapper',
				'params' => array(
					'uploaded_font_start' => array(
						'type' => 'wrapper_start',
					),
					'name' => array(
						'title' => __( 'Font Name', 'us' ),
						'type' => 'text',
						'std' => 'Uploaded Font',
						'classes' => 'width_full cols_2',
					),
					'weight' => array(
						'title' => __( 'Font Weight', 'us' ),
						'type' => 'slider',
						'min' => 100,
						'max' => 900,
						'step' => 100,
						'std' => 400,
						'classes' => 'width_full cols_2',
					),
					'files' => array(
						'title' => __( 'Font Files', 'us' ),
						'type' => 'upload',
						'is_multiple' => TRUE,
						'preview_type' => 'text',
						'button_label' => us_translate( 'Select Files' ),
						'classes' => 'width_full',
					),
					'uploaded_font_end' => array(
						'type' => 'wrapper_end',
					),
				),
			),
		),
	),
	'buttons' => array(
		'title' => __( 'Buttons', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/buttons',
		'fields' => array(
			'button_preview' => array(
				'type' => 'button_preview',
				'classes' => 'width_full sticky',
			),
			'button_font' => array(
				'title' => __( 'Font', 'us' ),
				'type' => 'select',
				'options' => us_get_fonts(),
				'std' => 'body',
			),
			'button_text_style' => array(
				'title' => __( 'Text Styles', 'us' ),
				'type' => 'checkboxes',
				'options' => array(
					'uppercase' => __( 'Uppercase', 'us' ),
					'italic' => __( 'Italic', 'us' ),
				),
				'std' => array(),
			),
			'button_fontsize' => array(
				'title' => __( 'Default Font Size', 'us' ),
				'type' => 'slider',
				'min' => 10,
				'max' => 20,
				'std' => 15,
				'postfix' => 'px',
			),
			'button_fontweight' => array(
				'title' => __( 'Font Weight', 'us' ),
				'type' => 'slider',
				'min' => 100,
				'max' => 900,
				'step' => 100,
				'std' => 400,
			),
			'button_letterspacing' => array(
				'title' => __( 'Letter Spacing', 'us' ),
				'type' => 'slider',
				'min' => - 0.10,
				'max' => 0.20,
				'step' => 0.01,
				'std' => 0,
				'postfix' => 'em',
			),
			'button_height' => array(
				'title' => __( 'Relative Height', 'us' ),
				'type' => 'slider',
				'min' => 2.0,
				'max' => 5.0,
				'step' => 0.1,
				'std' => 2.8,
				'postfix' => 'em',
			),
			'button_width' => array(
				'title' => __( 'Relative Width', 'us' ),
				'type' => 'slider',
				'min' => 0.5,
				'max' => 5.0,
				'step' => 0.1,
				'std' => 1.8,
				'postfix' => 'em',
			),
			'button_border_radius' => array(
				'title' => __( 'Corners Radius', 'us' ),
				'type' => 'slider',
				'min' => 0.0,
				'max' => 2.5,
				'step' => 0.1,
				'std' => 0.3,
				'postfix' => 'em',
			),
			'button_shadow' => array(
				'title' => __( 'Shadow', 'us' ),
				'type' => 'slider',
				'min' => 0.0,
				'max' => 2.0,
				'step' => 0.1,
				'std' => 0.0,
				'postfix' => 'em',
			),
			'button_hover' => array(
				'title' => __( 'Hover Style', 'us' ),
				'type' => 'radio',
				'options' => array(
					'none' => us_translate( 'None' ),
					'fade' => __( 'Fade', 'us' ),
					'slide' => __( 'Slide', 'us' ),
					'reverse' => __( 'Reverse', 'us' ),
				),
				'std' => 'slide',
			),
			'button_shadow_hover' => array(
				'title' => __( 'Shadow on Hover', 'us' ),
				'type' => 'slider',
				'min' => 0.0,
				'max' => 2.0,
				'step' => 0.1,
				'std' => 0.0,
				'postfix' => 'em',
			),
		),
	),
	'portfolio' => array(
		'title' => __( 'Portfolio', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/images',
		'place_if' => ( $usof_enable_portfolio == 1 ),
		'fields' => array(
			'portfolio_breadcrumbs_page' => array(
				'title' => __( 'Intermediate Breadcrumbs Page', 'us' ),
				'type' => 'select',
				'options' => us_array_merge(
					array( '' => '&ndash; ' . us_translate( 'None' ) . ' &ndash;' ), $us_page_list
				),
				'std' => '',
			),
			'portfolio_comments' => array(
				'title' => us_translate( 'Comments' ),
				'type' => 'switch',
				'text' => __( 'Enable comments for Portfolio Pages', 'us' ),
				'std' => 0,
			),
			'portfolio_nav' => array(
				'title' => __( 'Prev/Next Navigation', 'us' ),
				'type' => 'switch',
				'text' => __( 'Show previous/next portfolio pages on sides of the screen', 'us' ),
				'std' => 1,
			),
			'portfolio_nav_invert' => array(
				'type' => 'switch',
				'text' => __( 'Invert position of previous and next', 'us' ),
				'std' => 0,
				'classes' => 'for_above',
				'show_if' => array( 'portfolio_nav', '=', TRUE ),
			),
			'portfolio_nav_category' => array(
				'type' => 'switch',
				'text' => __( 'Navigate within a category', 'us' ),
				'std' => 0,
				'classes' => 'for_above',
				'show_if' => array( 'portfolio_nav', '=', TRUE ),
			),
			'portfolio_slug' => array(
				'title' => __( 'Portfolio Page Slug', 'us' ),
				'type' => 'text',
				'std' => 'portfolio',
			),
			'portfolio_category_slug' => array(
				'title' => __( 'Portfolio Category Slug', 'us' ),
				'type' => 'text',
				'std' => 'portfolio_category',
			),
			'portfolio_tag_slug' => array(
				'title' => __( 'Portfolio Tag Slug', 'us' ),
				'type' => 'text',
				'std' => 'portfolio_tag',
			),
		),
	),
	'blog' => array(
		'title' => us_translate( 'Blog' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/blogs',
		'fields' => array(

			// Posts
			'h_blog_1' => array(
				'title' => us_translate_x( 'Posts', 'post type general name' ),
				'type' => 'heading',
				'classes' => 'with_separator sticky',
			),
			'cpt_as_posts' => array(
				'title' => __( 'Display as Posts', 'us' ),
				'description' => __( 'Selected types will display as Posts. Unselected types will display as Pages.', 'us' ),
				'type' => 'checkboxes',
				'options' => $public_cpt_for_posts,
				'classes' => 'desc_1' . ( ( count( $public_cpt_for_posts ) == 0 ) ? ' hidden' : '' ),
				'std' => array(),
			),
			'post_preview_layout' => array(
				'title' => __( 'Featured Image Layout', 'us' ),
				'type' => 'radio',
				'options' => array(
					'basic' => __( 'Standard', 'us' ),
					'modern' => __( 'Modern', 'us' ),
					'trendy' => __( 'Trendy', 'us' ),
					'none' => us_translate( 'None' ),
				),
				'std' => 'basic',
			),
			'post_preview_img_size' => array(
				'title' => __( 'Featured Image Size', 'us' ),
				'description' => $img_size_description,
				'type' => 'select',
				'options' => $usof_img_sizes,
				'std' => 'large',
				'classes' => 'desc_1',
				'show_if' => array( 'post_preview_layout', '!=', 'none' ),
			),
			'post_meta' => array(
				'title' => __( 'Post Elements', 'us' ),
				'type' => 'checkboxes',
				'options' => array(
					'date' => us_translate( 'Date' ),
					'author' => us_translate( 'Author' ),
					'categories' => us_translate( 'Categories' ),
					'comments' => us_translate( 'Comments' ),
					'tags' => us_translate( 'Tags' ),
				),
				'std' => array( 'date', 'author', 'categories', 'comments', 'tags' ),
			),
			'post_sharing' => array(
				'title' => __( 'Sharing Buttons', 'us' ),
				'type' => 'switch',
				'text' => __( 'Show block with sharing buttons', 'us' ),
				'std' => 0,
			),
			'wrapper_post_sharing_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'force_right',
				'show_if' => array( 'post_sharing', '=', TRUE ),
			),
			'post_sharing_providers' => array(
				'title' => '',
				'type' => 'checkboxes',
				'options' => array(
					'email' => us_translate( 'Email' ),
					'facebook' => 'Facebook',
					'twitter' => 'Twitter',
					'gplus' => 'Google+',
					'linkedin' => 'LinkedIn',
					'pinterest' => 'Pinterest',
					'vk' => 'Vkontakte',
				),
				'std' => array( 'facebook', 'twitter', 'gplus' ),
				'classes' => 'width_full',
			),
			'post_sharing_type' => array(
				'title' => __( 'Buttons Style', 'us' ),
				'type' => 'radio',
				'options' => array(
					'simple' => __( 'Simple', 'us' ),
					'solid' => __( 'Solid', 'us' ),
					'outlined' => __( 'Outlined', 'us' ),
				),
				'std' => 'simple',
				'classes' => 'width_full',
			),
			'wrapper_post_sharing_end' => array(
				'type' => 'wrapper_end',
			),
			'post_author_box' => array(
				'title' => __( 'Author Info', 'us' ),
				'type' => 'switch',
				'text' => __( 'Show block with information about post author', 'us' ),
				'std' => 0,
			),
			'post_nav' => array(
				'title' => __( 'Prev/Next Navigation', 'us' ),
				'type' => 'switch',
				'text' => __( 'Show links to previous/next posts', 'us' ),
				'std' => 0,
			),
			'wrapper_post_nav_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'force_right',
				'show_if' => array( 'post_nav', '=', TRUE ),
			),
			'post_nav_layout' => array(
				'title' => __( 'Layout', 'us' ),
				'type' => 'radio',
				'options' => array(
					'default' => __( 'Below a post content', 'us' ),
					'sided' => __( 'On sides of the screen', 'us' ),
				),
				'std' => 'default',
				'classes' => 'width_full',
			),
			'post_nav_invert' => array(
				'type' => 'switch',
				'text' => __( 'Invert position of previous and next', 'us' ),
				'std' => 0,
				'classes' => 'width_full',
			),
			'post_nav_category' => array(
				'type' => 'switch',
				'text' => __( 'Navigate within a category', 'us' ),
				'std' => 0,
				'classes' => 'width_full',
			),
			'wrapper_post_nav_end' => array(
				'type' => 'wrapper_end',
			),
			'post_related' => array(
				'title' => __( 'Related Posts', 'us' ),
				'type' => 'switch',
				'text' => __( 'Show list of similar posts', 'us' ),
				'std' => 0,
			),
			'wrapper_post_related_start' => array(
				'type' => 'wrapper_start',
				'classes' => 'force_right',
				'show_if' => array( 'post_related', '=', TRUE ),
			),
			'post_related_type' => array(
				// 'title' => us_translate( 'Show' ),
				'type' => 'radio',
				'options' => array(
					'tag' => __( 'Posts with the same tags', 'us' ),
					'category' => __( 'Posts with the same categories', 'us' ),
				),
				'std' => 'tag',
				'classes' => 'width_full',
			),
			'post_related_title' => array(
				'title' => us_translate( 'Title' ),
				'type' => 'text',
				'std' => __( 'Related Posts', 'us' ),
				'classes' => 'width_full',
			),
			'post_related_orderby' => array(
				'title' => us_translate( 'Order' ),
				'type' => 'select',
				'options' => array(
					'date' => __( 'By date of creation (newer first)', 'us' ),
					'date_asc' => __( 'By date of creation (older first)', 'us' ),
					'modified' => __( 'By date of update (newer first)', 'us' ),
					'modified_asc' => __( 'By date of update (older first)', 'us' ),
					'alpha' => __( 'Alphabetically', 'us' ),
					'rand' => us_translate( 'Random' ),
				),
				'std' => 'rand',
				'classes' => 'width_full',
			),
			'post_related_layout' => array(
				'title' => __( 'Grid Layout', 'us' ),
				'type' => 'grid_layout_select',
				'std' => 'blog_compact',
				'classes' => 'width_full desc_4',
			),
			'post_related_img_size' => array(
				'title' => __( 'Post Image Size', 'us' ),
				'description' => $img_size_description,
				'type' => 'select',
				'options' => array_merge(
					array( 'default' => __( 'As in Grid Layout', 'us' ) ), $usof_img_sizes
				),
				'std' => 'default',
				'classes' => 'width_full desc_1',
			),
			'post_related_quantity' => array(
				'title' => __( 'Posts Quantity', 'us' ),
				'type' => 'radio',
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'std' => '3',
				'classes' => 'width_full desc_4',
			),
			'post_related_cols' => array(
				'title' => __( 'Posts Columns', 'us' ),
				'type' => 'radio',
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'std' => '1',
				'classes' => 'width_full desc_4',
			),
			'post_related_items_gap' => array(
				'title' => __( 'Gap between Posts', 'us' ),
				'type' => 'slider',
				'min' => 0.0,
				'max' => 5.0,
				'step' => 0.1,
				'std' => 1.0,
				'postfix' => 'rem',
				'classes' => 'width_full desc_4',
			),
			'wrapper_post_related_end' => array(
				'type' => 'wrapper_end',
			),

			// Blog Home Page
			'h_blog_2' => array(
				'title' => __( 'Blog Home Page', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator sticky',
			),
			'blog_layout' => array(
				'title' => __( 'Grid Layout', 'us' ),
				'type' => 'grid_layout_select',
				'std' => 'blog_classic',
			),
			'blog_img_size' => array(
				'title' => __( 'Post Image Size', 'us' ),
				'description' => $img_size_description,
				'type' => 'select',
				'options' => array_merge(
					array( 'default' => __( 'As in Grid Layout', 'us' ) ), $usof_img_sizes
				),
				'std' => 'default',
				'classes' => 'desc_1',
				'show_if' => array( 'blog_layout', 'not in', array( 'latest', 'compact' ) ),
			),
			'blog_type' => array(
				'title' => __( 'Display Posts as', 'us' ),
				'type' => 'radio',
				'options' => array(
					'grid' => __( 'Regular Grid', 'us' ),
					'masonry' => __( 'Masonry', 'us' ),
				),
				'std' => 'grid',
			),
			'blog_cols' => array(
				'title' => __( 'Posts Columns', 'us' ),
				'type' => 'radio',
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'std' => '1',
			),
			'blog_items_gap' => array(
				'title' => __( 'Gap between Posts', 'us' ),
				'type' => 'slider',
				'min' => 0.0,
				'max' => 5.0,
				'step' => 0.1,
				'std' => 5.0,
				'postfix' => 'rem',
			),
			'blog_pagination' => array(
				'title' => us_translate( 'Pagination' ),
				'type' => 'select',
				'options' => array(
					'regular' => __( 'Numbered pagination', 'us' ),
					'ajax' => __( 'Load items on button click', 'us' ),
					'infinite' => __( 'Load items on page scroll', 'us' ),
				),
				'std' => 'regular',
			),

			// Archive Pages
			'h_blog_3' => array(
				'title' => __( 'Archive Pages', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator sticky',
			),
			'archive_layout' => array(
				'title' => __( 'Grid Layout', 'us' ),
				'type' => 'grid_layout_select',
				'std' => 'blog_classic',
			),
			'archive_img_size' => array(
				'title' => __( 'Post Image Size', 'us' ),
				'description' => $img_size_description,
				'type' => 'select',
				'options' => array_merge(
					array( 'default' => __( 'As in Grid Layout', 'us' ) ), $usof_img_sizes
				),
				'std' => 'default',
				'classes' => 'desc_1',
			),
			'archive_type' => array(
				'title' => __( 'Display Posts as', 'us' ),
				'type' => 'radio',
				'options' => array(
					'grid' => __( 'Regular Grid', 'us' ),
					'masonry' => __( 'Masonry', 'us' ),
				),
				'std' => 'grid',
			),
			'archive_cols' => array(
				'title' => __( 'Posts Columns', 'us' ),
				'type' => 'radio',
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'std' => '1',
			),
			'archive_items_gap' => array(
				'title' => __( 'Gap between Posts', 'us' ),
				'type' => 'slider',
				'min' => 0.0,
				'max' => 5.0,
				'step' => 0.1,
				'std' => 5.0,
				'postfix' => 'rem',
			),
			'archive_pagination' => array(
				'title' => us_translate( 'Pagination' ),
				'type' => 'select',
				'options' => array(
					'regular' => __( 'Numbered pagination', 'us' ),
					'ajax' => __( 'Load items on button click', 'us' ),
					'infinite' => __( 'Load items on page scroll', 'us' ),
				),
				'std' => 'regular',
			),

			// Search Results Page
			'h_blog_4' => array(
				'title' => __( 'Search Results Page', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator sticky',
			),
			'search_layout' => array(
				'title' => __( 'Grid Layout', 'us' ),
				'type' => 'grid_layout_select',
				'std' => 'blog_compact',
			),
			'search_img_size' => array(
				'title' => __( 'Post Image Size', 'us' ),
				'description' => $img_size_description,
				'type' => 'select',
				'options' => array_merge(
					array( 'default' => __( 'As in Grid Layout', 'us' ) ), $usof_img_sizes
				),
				'std' => 'default',
				'classes' => 'desc_1',
			),
			'search_type' => array(
				'title' => __( 'Display Posts as', 'us' ),
				'type' => 'radio',
				'options' => array(
					'grid' => __( 'Regular Grid', 'us' ),
					'masonry' => __( 'Masonry', 'us' ),
				),
				'std' => 'grid',
			),
			'search_cols' => array(
				'title' => __( 'Posts Columns', 'us' ),
				'type' => 'radio',
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'std' => '1',
			),
			'search_items_gap' => array(
				'title' => __( 'Gap between Posts', 'us' ),
				'type' => 'slider',
				'min' => 0.0,
				'max' => 5.0,
				'step' => 0.1,
				'std' => 1.0,
				'postfix' => 'rem',
			),
			'search_pagination' => array(
				'title' => us_translate( 'Pagination' ),
				'type' => 'select',
				'options' => array(
					'regular' => __( 'Numbered pagination', 'us' ),
					'ajax' => __( 'Load items on button click', 'us' ),
					'infinite' => __( 'Load items on page scroll', 'us' ),
				),
				'std' => 'regular',
			),
			'h_blog_5' => array(
				'title' => __( 'More Options', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator sticky',
			),

			// Blog Responsive Breakpoint 1
			'blog_breakpoint_1_start' => array(
				'title' => __( 'Responsive Layout', 'us' ),
				'type' => 'wrapper_start',
				'classes' => 'title_left',
			),
			'blog_breakpoint_1_width' => array(
				'title' => __( 'Below screen width', 'us' ),
				'description' => __( 'show', 'us' ),
				'type' => 'slider',
				'min' => 300,
				'max' => 1400,
				'step' => 10,
				'std' => 1200,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'blog_breakpoint_1_cols' => array(
				'type' => 'select',
				'options' => array(
					'6' => sprintf( us_translate_n( '%s column', '%s columns', 6 ), 6 ),
					'5' => sprintf( us_translate_n( '%s column', '%s columns', 5 ), 5 ),
					'4' => sprintf( us_translate_n( '%s column', '%s columns', 4 ), 4 ),
					'3' => sprintf( us_translate_n( '%s column', '%s columns', 3 ), 3 ),
					'2' => sprintf( us_translate_n( '%s column', '%s columns', 2 ), 2 ),
					'1' => sprintf( us_translate_n( '%s column', '%s columns', 1 ), 1 ),
				),
				'std' => '3',
				'classes' => 'inline',
			),
			'blog_breakpoint_1_end' => array(
				'type' => 'wrapper_end',
			),

			// Blog Responsive Breakpoint 2
			'blog_breakpoint_2_start' => array(
				'title' => ' ',
				'type' => 'wrapper_start',
				'classes' => 'title_left',
			),
			'blog_breakpoint_2_width' => array(
				'title' => __( 'Below screen width', 'us' ),
				'description' => __( 'show', 'us' ),
				'type' => 'slider',
				'min' => 300,
				'max' => 1400,
				'step' => 10,
				'std' => 900,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'blog_breakpoint_2_cols' => array(
				'type' => 'select',
				'options' => array(
					'6' => sprintf( us_translate_n( '%s column', '%s columns', 6 ), 6 ),
					'5' => sprintf( us_translate_n( '%s column', '%s columns', 5 ), 5 ),
					'4' => sprintf( us_translate_n( '%s column', '%s columns', 4 ), 4 ),
					'3' => sprintf( us_translate_n( '%s column', '%s columns', 3 ), 3 ),
					'2' => sprintf( us_translate_n( '%s column', '%s columns', 2 ), 2 ),
					'1' => sprintf( us_translate_n( '%s column', '%s columns', 1 ), 1 ),
				),
				'std' => '2',
				'classes' => 'inline',
			),
			'blog_breakpoint_2_end' => array(
				'type' => 'wrapper_end',
			),

			// Blog Responsive Breakpoint 3
			'blog_breakpoint_3_start' => array(
				'title' => ' ',
				'type' => 'wrapper_start',
				'classes' => 'title_left',
			),
			'blog_breakpoint_3_width' => array(
				'title' => __( 'Below screen width', 'us' ),
				'description' => __( 'show', 'us' ),
				'type' => 'slider',
				'min' => 300,
				'max' => 1400,
				'step' => 10,
				'std' => 600,
				'postfix' => 'px',
				'classes' => 'inline slider_below',
			),
			'blog_breakpoint_3_cols' => array(
				'type' => 'select',
				'options' => array(
					'6' => sprintf( us_translate_n( '%s column', '%s columns', 6 ), 6 ),
					'5' => sprintf( us_translate_n( '%s column', '%s columns', 5 ), 5 ),
					'4' => sprintf( us_translate_n( '%s column', '%s columns', 4 ), 4 ),
					'3' => sprintf( us_translate_n( '%s column', '%s columns', 3 ), 3 ),
					'2' => sprintf( us_translate_n( '%s column', '%s columns', 2 ), 2 ),
					'1' => sprintf( us_translate_n( '%s column', '%s columns', 1 ), 1 ),
				),
				'std' => '1',
				'classes' => 'inline',
			),
			'blog_breakpoint_3_end' => array(
				'type' => 'wrapper_end',
			),
		),
	),
	'woocommerce' => array(
		'title' => us_translate_x( 'Shop', 'Page title', 'woocommerce' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/cart',
		'place_if' => class_exists( 'woocommerce' ),
		'fields' => array(
			'shop_columns' => array(
				'title' => __( 'Products Grid Columns', 'us' ),
				'type' => 'radio',
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'std' => '3',
			),
			'shop_listing_style' => array(
				'title' => __( 'Products Grid Layout', 'us' ),
				'std' => 'standard',
				'type' => 'radio',
				'options' => array(
					'standard' => __( 'Standard', 'us' ),
					'modern' => __( 'Modern', 'us' ),
					'trendy' => __( 'Trendy', 'us' ),
					'custom' => __( 'Custom', 'us' ),
				),
			),
			'shop_layout' => array(
				'type' => 'grid_layout_select',
				'std' => 'blog_compact',
				'classes' => 'for_above',
				'show_if' => array( 'shop_listing_style', '=', 'custom' ),
			),
			'shop_items_gap' => array(
				'title' => __( 'Gap between Products', 'us' ),
				'type' => 'slider',
				'min' => 0.0,
				'max' => 5.0,
				'step' => 0.1,
				'std' => 1.5,
				'postfix' => 'rem',
				'show_if' => array( 'shop_listing_style', '=', 'custom' ),
			),
			'product_related_qty' => array(
				'title' => __( 'Related Products Quantity', 'us' ),
				'description' => __( 'On Product pages and Cart page', 'us' ),
				'std' => '3',
				'type' => 'radio',
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
			),
			'shop_elements' => array(
				'title' => __( 'Shop Elements', 'us' ),
				'description' => __( 'Disable elements to avoid duplication, when you set Title Bar for Shop Pages and Products.', 'us' ),
				'type' => 'checkboxes',
				'options' => array(
					'shop_title' => __( 'Title on Shop pages', 'us' ),
					'product_title' => __( 'Title on Product pages', 'us' ),
					'breadcrumbs' => __( 'Breadcrumbs', 'us' ),
				),
				'std' => array( 'shop_title', 'product_title', 'breadcrumbs' ),
				'classes' => 'vertical',
			),
			'shop_cart' => array(
				'title' => __( 'Cart Page Layout', 'us' ),
				'std' => 'compact',
				'type' => 'radio',
				'options' => array(
					'standard' => __( 'Standard', 'us' ),
					'compact' => __( 'Compact', 'us' ),
				),
			),
			'shop_catalog' => array(
				'title' => __( 'Catalog Mode', 'us' ),
				'type' => 'switch',
				'text' => sprintf( __( 'Remove "%s" buttons', 'us' ), us_translate( 'Add to cart', 'woocommerce' ) ),
				'std' => 0,
			),
		),
	),
	'advanced' => array(
		'title' => _x( 'Advanced', 'Advanced Settings', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/cog',
		'fields' => array(
			'h_advanced_1' => array(
				'title' => __( 'Theme Modules', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'enable_portfolio' => array(
				'type' => 'switch',
				'text' => __( 'Portfolio', 'us' ),
				'std' => 1,
				'classes' => 'width_full',
			),
			'enable_testimonials' => array(
				'type' => 'switch',
				'text' => __( 'Testimonials', 'us' ),
				'std' => 1,
				'classes' => 'width_full for_above',
			),
			'og_enabled' => array(
				'type' => 'switch',
				'text' => __( 'Open Graph meta tags', 'us' ),
				'std' => 1,
				'classes' => 'width_full for_above',
			),
			'schema_markup' => array(
				'type' => 'switch',
				'text' => __( 'Schema.org markup', 'us' ),
				'std' => 1,
				'classes' => 'width_full for_above',
			),

			'h_advanced_2' => array(
				'title' => __( 'Website Performance', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'lazyload_fonts' => array(
				'type' => 'switch',
				'text' => __( 'Defer Google Fonts loading', 'us' ),
				'description' => __( 'When this option is ON, Google Fonts files will be loaded after page content.', 'us' ),
				'std' => 0,
				'classes' => 'width_full desc_2',
			),
			'jquery_footer' => array(
				'type' => 'switch',
				'text' => __( 'Move jQuery scripts to the footer', 'us' ),
				'description' => __( 'When this option is ON, jQuery library files will be loaded after page content.', 'us' ) . ' ' . __( 'This will improve pages loading speed.', 'us' ),
				'std' => 1,
				'classes' => 'width_full desc_2 for_above',
			),
			'disable_jquery_migrate' => array(
				'type' => 'switch',
				'text' => __( 'Disable jQuery migrate script', 'us' ),
				'description' => __( 'When this option is ON, "jquery-migrate.min.js" file won\'t be loaded in front-end.', 'us' ) . ' ' . __( 'This will improve pages loading speed.', 'us' ),
				'std' => 1,
				'classes' => 'width_full desc_2 for_above',
			),
			'ajax_load_js' => array(
				'type' => 'switch',
				'text' => __( 'Dynamically load theme JS components', 'us' ),
				'description' => __( 'When this option is ON, theme components JS files will be loaded dynamically without additional external requests.', 'us' ) . ' ' . __( 'This will improve pages loading speed.', 'us' ),
				'std' => 1,
				'classes' => 'width_full desc_2 for_above',
			),
			'disable_extra_vc' => array(
				'type' => 'switch',
				'text' => __( 'Disable extra features of WPBakery Page Builder', 'us' ),
				'description' => __( 'When this option is ON, original CSS and JS files of WPBakery Page Builder won\'t be loaded in front-end.', 'us' ) . ' ' . __( 'This will improve pages loading speed.', 'us' ),
				'std' => 1,
				'place_if' => class_exists( 'Vc_Manager' ),
				'classes' => 'width_full desc_2 for_above',
			),
			'optimize_assets' => array(
				'type' => 'switch',
				'text' => __( 'Optimize JS and CSS size', 'us' ),
				'description' => __( 'When this option is ON, your site will load single JS file and single CSS file. You can disable unused components to reduce its sizes.', 'us' ) . ' ' . __( 'This will improve pages loading speed.', 'us' ),
				'std' => 0,
				'classes' => 'width_full desc_2 for_above' . $optimize_assets_add_class,
			),
			'optimize_assets_alert' => array(
				'description' => __( 'Your uploads folder is not writable. Change your server permissions to use this option.', 'us' ),
				'type' => 'message',
				'classes' => 'width_full string' . $optimize_assets_alert_add_class,
			),
			'optimize_assets_start' => array(
				'type' => 'wrapper_start',
				'show_if' => array( 'optimize_assets', '=', TRUE ),
			),
			'assets' => array(
				'type' => 'check_table',
				'options' => $usof_assets,
				'std' => array_keys( $usof_assets ),
				'classes' => 'width_full',
			),
			'optimize_assets_end' => array(
				'type' => 'wrapper_end',
			),

			'h_advanced_3' => array(
				'title' => __( 'Custom Image Sizes', 'us' ),
				'type' => 'heading',
				'classes' => 'with_separator',
			),
			'img_size_info' => array(
				'description' => $img_size_info,
				'type' => 'message',
				'classes' => 'width_full color_blue for_above',
			),
			'img_size' => array(
				'type' => 'group',
				'classes' => 'for_inline',
				'params' => array(
					'width' => array(
						'title' => us_translate( 'Width' ),
						'type' => 'slider',
						'min' => 0,
						'max' => 1000,
						'std' => 600,
						'postfix' => 'px',
						'classes' => 'inline slider_below',
					),
					'height' => array(
						'title' => us_translate( 'Height' ),
						'type' => 'slider',
						'min' => 0,
						'max' => 1000,
						'std' => 400,
						'postfix' => 'px',
						'classes' => 'inline slider_below',
					),
					'crop' => array(
						'type' => 'checkboxes',
						'options' => array(
							'crop' => __( 'Crop to exact dimensions', 'us' ),
						),
						'std' => array( '0' => 'crop' ),
						'classes' => 'inline',
					),
				),
				'std' => array(
					array(
						'width' => 350,
						'height' => 350,
						'crop' => array( '0' => 'crop' ),
					),
					array(
						'width' => 600,
						'height' => 600,
						'crop' => array( '0' => 'crop' ),
					),
				),
			),

		),
	),
	'code' => array(
		'title' => __( 'Custom Code', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/tag',
		'fields' => array(
			'custom_css' => array(
				'title' => __( 'Custom CSS', 'us' ),
				'description' => sprintf( __( 'CSS code from this field will overwrite theme styles. It will be located inside the %s tags just before the %s tag of every site page.', 'us' ), '<code>&lt;style&gt;&lt;/style&gt;</code>', '<code>&lt;/head&gt;</code>' ),
				'type' => 'css',
				'classes' => 'width_full desc_4',
			),
			'custom_html_head' => array(
				'title' => sprintf( __( 'Code before %s', 'us' ), '&lt;/head&gt;' ),
				'description' => sprintf( __( 'Use this field for Google Analytics code or other tracking code. If you paste custom JavaScript, use it inside the %s tags.', 'us' ), '<code>&lt;script&gt;&lt;/script&gt;</code>' ) . '<br><br>' . sprintf( __( 'Content from this field will be located just before the %s tag of every site page.', 'us' ), '<code>&lt;/head&gt;</code>' ),
				'type' => 'html',
				'classes' => 'width_full desc_4',
			),
			'custom_html' => array(
				'title' => sprintf( __( 'Code before %s', 'us' ), '&lt;/body&gt;' ),
				'description' => sprintf( __( 'Use this field for Google Analytics code or other tracking code. If you paste custom JavaScript, use it inside the %s tags.', 'us' ), '<code>&lt;script&gt;&lt;/script&gt;</code>' ) . '<br><br>' . sprintf( __( 'Content from this field will be located just before the %s tag of every site page.', 'us' ), '<code>&lt;/body&gt;</code>' ),
				'type' => 'html',
				'classes' => 'width_full desc_4',
			),
		),
	),
	'manage' => array(
		'title' => __( 'Manage Options', 'us' ),
		'icon' => $us_template_directory_uri . '/framework/admin/img/usof/backups',
		'fields' => array(
			'of_reset' => array(
				'title' => __( 'Reset Theme Options', 'us' ),
				'type' => 'reset',
			),
			'of_backup' => array(
				'title' => __( 'Backup Theme Options', 'us' ),
				'type' => 'backup',
			),
			'of_transfer' => array(
				'title' => __( 'Transfer Theme Options', 'us' ),
				'type' => 'transfer',
				'description' => __( 'You can transfer the saved options data between different installations by copying the text inside the text box. To import data from another installation, replace the data in the text box with the one from another installation and click "Import Options".', 'us' ),
			),
		),
	),
);
