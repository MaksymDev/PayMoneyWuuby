<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * WPBakery Page Builder support
 *
 * @link http://codecanyon.net/item/visual-composer-page-builder-for-wordpress/242431?ref=UpSolution
 */

if ( ! class_exists( 'Vc_Manager' ) ) {

	/**
	 * @param $width
	 *
	 * @since 4.2
	 * @return bool|string
	 */
	function us_wpb_translateColumnWidthToSpan( $width ) {
		preg_match( '/(\d+)\/(\d+)/', $width, $matches );
		if ( ! empty( $matches ) ) {
			$part_x = (int) $matches[1];
			$part_y = (int) $matches[2];
			if ( $part_x > 0 AND $part_y > 0 ) {
				$value = ceil( $part_x / $part_y * 12 );
				if ( $value > 0 AND $value <= 12 ) {
					$width = 'vc_col-sm-' . $value;
				}
			}
		}

		return $width;
	}

	/**
	 * @param $column_offset
	 * @param $width
	 *
	 * @return mixed|string
	 */
	function us_vc_column_offset_class_merge( $column_offset, $width ) {
		if ( preg_match( '/vc_col\-sm\-\d+/', $column_offset ) ) {
			return $column_offset;
		}

		return $width . ( empty( $column_offset ) ? '' : ' ' . $column_offset );
	}

	/**
	 * @param            $subject
	 * @param            $property
	 * @param bool|false $strict
	 *
	 * @since 4.9
	 * @return bool
	 */
	function us_vc_shortcode_custom_css_has_property( $subject, $property, $strict = FALSE ) {
		$styles = array();
		$pattern = '/\{([^\}]*?)\}/i';
		preg_match( $pattern, $subject, $styles );
		if ( array_key_exists( 1, $styles ) ) {
			$styles = explode( ';', $styles[1] );
		}
		$new_styles = array();
		foreach ( $styles as $val ) {
			$val = explode( ':', $val );
			if ( is_array( $property ) ) {
				foreach ( $property as $prop ) {
					$pos = strpos( $val[0], $prop );
					$full = ( $strict ) ? ( $pos === 0 AND strlen( $val[0] ) === strlen( $prop ) ) : TRUE;
					if ( $pos !== FALSE AND $full ) {
						$new_styles[] = $val;
					}
				}
			} else {
				$pos = strpos( $val[0], $property );
				$full = ( $strict ) ? ( $pos === 0 AND strlen( $val[0] ) === strlen( $property ) ) : TRUE;
				if ( $pos !== FALSE AND $full ) {
					$new_styles[] = $val;
				}
			}
		}

		return ! empty( $new_styles );
	}

	return;
}

add_action( 'vc_before_init', 'us_vc_set_as_theme' );
function us_vc_set_as_theme() {
	vc_set_as_theme();
}

add_action( 'vc_after_init', 'us_vc_after_init' );
function us_vc_after_init() {
	$updater = vc_manager()->updater();
	$updateManager = $updater->updateManager();

	remove_filter( 'upgrader_pre_download', array( $updater, 'preUpgradeFilter' ) );
	remove_filter( 'pre_set_site_transient_update_plugins', array( $updateManager, 'check_update' ) );
	remove_filter( 'plugins_api', array( $updateManager, 'check_info' ) );
	remove_action( 'in_plugin_update_message-' . vc_plugin_name(), array( $updateManager, 'addUpgradeMessageLink' ) );
}

add_action( 'vc_after_set_mode', 'us_vc_after_set_mode' );
function us_vc_after_set_mode() {

	do_action( 'us_before_js_composer_mappings' );

	$shortcodes_config = us_config( 'shortcodes', array() );

	// Removing VC Font Awesome style in admin pages 
	add_action( 'admin_head', 'us_remove_js_composer_fontawesome', 1 );
	function us_remove_js_composer_fontawesome() {
		wp_dequeue_style( 'font-awesome' );
		wp_deregister_style( 'font-awesome' );
	}

	// Mapping WPBakery Page Builder backend behaviour for used shortcodes
	if ( vc_mode() != 'page' ) {
		foreach ( $shortcodes_config as $shortcode => $config ) {
			if ( isset( $config['custom_vc_map'] ) AND ! empty( $config['custom_vc_map'] ) ) {
				require $config['custom_vc_map'];
			}
		}
	}

	if ( us_get_option( 'disable_extra_vc', 1 ) == 1 ) {
		// Removing the elements that are not supported at the moment by the theme
		if ( is_admin() ) {
			foreach ( $shortcodes_config as $shortcode => $config ) {
				if ( isset( $config['supported'] ) AND ! $config['supported'] ) {
					vc_remove_element( $shortcode );
				}
			}
		} else {
			add_action( 'template_redirect', 'us_vc_disable_extra_sc', 100 );
		}

	}

	if ( ! vc_is_page_editable() ) {
		// Removing original VC styles and scripts
		add_action( 'wp_enqueue_scripts', 'us_remove_vc_base_css_js', 15 );
		function us_remove_vc_base_css_js() {
			if ( wp_style_is( 'font-awesome', 'registered' ) ) {
				wp_dequeue_style( 'font-awesome' );
				wp_deregister_style( 'font-awesome' );
			}
			if ( us_get_option( 'disable_extra_vc', 1 ) == 1 ) {
				if ( wp_style_is( 'js_composer_front', 'registered' ) ) {
					wp_dequeue_style( 'js_composer_front' );
					wp_deregister_style( 'js_composer_front' );
				}
				if ( wp_script_is( 'wpb_composer_front_js', 'registered' ) ) {
					wp_deregister_script( 'wpb_composer_front_js' );
				}
			}
		}
	}

	if ( vc_is_page_editable() ) {
		// Disabling some of the shortcodes for front-end edit mode
		US_Shortcodes::instance()->vc_front_end_compatibility();
	}

	if ( is_admin() AND us_get_option( 'disable_extra_vc', 1 ) == 1 ) {
		// Removing grid elements
		add_action( 'admin_menu', 'us_remove_vc_grid_elements_submenu' );
		function us_remove_vc_grid_elements_submenu() {
			remove_submenu_page( VC_PAGE_MAIN_SLUG, 'edit.php?post_type=vc_grid_item' );
		}
	}

	// Disabling Frontend editor for Page Blocks
	add_action( 'current_screen', 'us_disable_frontend_for_page_blocks' );

	do_action( 'us_after_js_composer_mappings' );
}

function us_disable_frontend_for_page_blocks() {
	if ( function_exists( 'get_current_screen' ) ) {
		$screen = get_current_screen();
		if ( $screen->post_type == 'us_page_block' ) {
			vc_disable_frontend();
		}
	}
}

function us_vc_disable_extra_sc() {
	$shortcodes_config = us_config( 'shortcodes', array() );

	foreach ( $shortcodes_config as $shortcode => $config ) {
		if ( isset( $config['supported'] ) AND ! $config['supported'] ) {
			remove_shortcode( $shortcode );
		}
	}
}

// Disabling redirect to VC welcome page
remove_action( 'init', 'vc_page_welcome_redirect' );

add_action( 'after_setup_theme', 'us_vc_init_vendor_woocommerce', 99 );
function us_vc_init_vendor_woocommerce() {
	remove_action( 'wp_enqueue_scripts', 'vc_woocommerce_add_to_cart_script' );
}

/**
 * Get image size values for selector
 *
 * @param array [$size_names] List of size names
 *
 * @return array
 */
function us_image_sizes_select_values( $size_names = NULL ) {
	if ( $size_names === NULL ) {
		$size_names = array( 'full', 'large', 'medium', 'thumbnail' );
	}
	$image_sizes = array();
	// For translation purposes
	$size_titles = array(
		'full' => us_translate( 'Full Size' ),
	);
	foreach ( $size_names as $size_name ) {
		$size_title = isset( $size_titles[ $size_name ] ) ? $size_titles[ $size_name ] : ucwords( $size_name );
		if ( $size_name != 'full' ) {
			// Detecting size
			$size = us_get_intermediate_image_size( $size_name );
			$size_title = ( ( $size['width'] == 0 ) ? __( 'any', 'us' ) : $size['width'] );
			$size_title .= ' x ';
			$size_title .= ( $size['height'] == 0 ) ? __( 'any', 'us' ) : $size['height'];
			if ( $size['crop'] ) {
				$size_title .= ' ' . __( 'cropped', 'us' );
			}
		}
		$image_sizes[ $size_title ] = $size_name;
	}

	// Custom sizes
	$custom_tnail_sizes = us_get_option( 'img_size' );
	if ( is_array( $custom_tnail_sizes ) ) {
		foreach ( $custom_tnail_sizes as $size_index => $size ) {
			$crop = ( ! empty( $size['crop'][0] ) );
			$crop_str = ( $crop ) ? '_crop' : '';
			$width = ( ! empty( $size['width'] ) AND intval( $size['width'] ) > 0 ) ? intval( $size['width'] ) : 0;
			$height = ( ! empty( $size['height'] ) AND intval( $size['height'] ) > 0 ) ? intval( $size['height'] ) : 0;
			$size_name = 'us_' . $width . '_' . $height . $crop_str;

			$size_title = ( $width == 0 ) ? __( 'any', 'us' ) : $width;
			$size_title .= ' x ';
			$size_title .= ( $height == 0 ) ? __( 'any', 'us' ) : $height;
			if ( $crop ) {
				$size_title .= ' ' . __( 'cropped', 'us' );
			}

			$image_sizes[ $size_title ] = $size_name;
		}
	}

	return apply_filters( 'us_image_sizes_select_values', $image_sizes );
}

// Add autocomplete for us_grid
add_action( 'vc_after_mapping', 'us_grid_map_shortcodes' );
function us_grid_map_shortcodes() {
	add_filter( 'vc_autocomplete_us_grid_ids_callback', 'us_grid_ids_autocomplete_suggester', 10, 1 );
	function us_grid_ids_autocomplete_suggester( $query ) {
		global $wpdb;
		$item_id = (int) $query;

		// Fetching the available post types to choose from
		$available_posts_types = us_grid_available_post_types();
		if ( count( $available_posts_types ) > 0 ) {
			$available_posts_types = array_keys( $available_posts_types );
			$where_post_type = " a.post_type IN ('" . implode( "','", $available_posts_types ) . "') AND ";
		} else {
			$where_post_type = "";
		}

		$post_meta_infos = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT a.ID AS id, a.post_title AS title
					FROM {$wpdb->posts} AS a
					WHERE {$where_post_type} ( a.ID = '%d' OR a.post_title LIKE '%%%s%%' )", $item_id > 0 ? $item_id : - 1, stripslashes( $query ), stripslashes( $query )
			), ARRAY_A
		);

		$results = array();
		if ( is_array( $post_meta_infos ) AND ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data = array();
				$data['value'] = $value['id'];
				$data['label'] = ( strlen( $value['title'] ) > 0 ) ? $value['title'] : us_translate( '(no title)' );
				$results[] = $data;
			}
		}
		return $results;
	}

	add_filter( 'vc_autocomplete_us_grid_ids_render', 'us_grid_ids_render', 10, 1 );
	function us_grid_ids_render( $query ) {
		$query = trim( $query['value'] ); // get value from requested
		if ( ! empty( $query ) ) {
			// get post
			$post_object = get_post( (int) $query );
			if ( is_object( $post_object ) ) {
				$post_title = $post_object->post_title;
				$post_id = $post_object->ID;
				$data = array();
				$data['value'] = $post_id;
				$data['label'] = ( strlen( $post_title ) > 0 ) ? $post_title : us_translate( '(no title)' );

				return ! empty( $data ) ? $data : FALSE;
			}
			return FALSE;
		}
		return FALSE;
	}
}

add_filter( 'us_page_block_the_content', 'us_VC_fixPContent', 11 );
function us_VC_fixPContent( $content = NULL ) {
	if ( $content ) {
		$s = array(
			'/' . preg_quote( '</div>', '/' ) . '[\s\n\f]*' . preg_quote( '</p>', '/' ) . '/i',
			'/' . preg_quote( '<p>', '/' ) . '[\s\n\f]*' . preg_quote( '<div ', '/' ) . '/i',
			'/' . preg_quote( '<p>', '/' ) . '[\s\n\f]*' . preg_quote( '<section ', '/' ) . '/i',
			'/' . preg_quote( '</section>', '/' ) . '[\s\n\f]*' . preg_quote( '</p>', '/' ) . '/i',
		);
		$r = array(
			'</div>',
			'<div ',
			'<section ',
			'</section>',
		);
		$content = preg_replace( $s, $r, $content );

		return $content;
	}

	return NULL;
}

// Hide activation notice
add_action( 'admin_notices', 'us_hide_js_composer_activation_notice', 100 );
function us_hide_js_composer_activation_notice() {
	?>
	<script>
		(function($){
			var setCookie = function(c_name, value, exdays){
				var exdate = new Date();
				exdate.setDate(exdate.getDate() + exdays);
				var c_value = encodeURIComponent(value) + ((null === exdays) ? "" : "; expires=" + exdate.toUTCString());
				document.cookie = c_name + "=" + c_value;
			};
			setCookie('vchideactivationmsg_vc11', '100', 30);
			$('#vc_license-activation-notice').remove();
		})(window.jQuery);
	</script>
	<?php
}

// Set backend editor for certain post types
$list = array(
	'page',
	'us_portfolio',
	'us_page_block',
);
vc_set_default_editor_post_types( $list );

// Remove backend editor for Headers & Grid Layouts
add_filter( 'vc_settings_exclude_post_type', 'us_vc_settings_exclude_post_type' );
function us_vc_settings_exclude_post_type( $types ) {
	$types = array(
		'us_header',
		'us_grid_layout',
	);

	return $types;
}

add_action( 'current_screen', 'us_header_vc_check_post_type_validation_fix' );
function us_header_vc_check_post_type_validation_fix( $current_screen ) {
	global $pagenow;
	if ( $pagenow == 'post.php' AND $current_screen->post_type == 'us_header' ) {
		add_filter( 'vc_check_post_type_validation', '__return_false', 12 );
	}
}

// Add parameter for icon selection
if ( ! function_exists( 'us_icon_settings_field' ) ) {
	global $us_template_directory_uri;
	vc_add_shortcode_param( 'us_icon', 'us_icon_settings_field', $us_template_directory_uri . '/framework/plugins-support/js_composer/js/us_icon.js' );
	function us_icon_settings_field( $settings, $value ) {
		$icon_sets = us_config( 'icon-sets', array() );
		reset( $icon_sets );
		$value = trim( $value );
		if ( ! preg_match( '/(fas|far|fal|fab|material)\|[a-z0-9-]/i', $value ) ) {
			$value = $settings['std'];
		}
		$select_value = $input_value = '';
		$value_arr = explode( '|', $value );
		if ( count( $value_arr ) == 2 ) {
			$select_value = $value_arr[0];
			$input_value = $value_arr[1];
		}
		if ( empty( $select_value ) ) {
			$select_value = key( $icon_sets );
		}
		ob_start();
		?>
		<div class="us-icon">
			<input name="<?php echo esc_attr( $settings['param_name'] ); ?>" class="us-icon-value wpb_vc_param_value wpb-textinput <?php echo esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field'; ?>" type="hidden" value="<?php echo esc_attr( $value ); ?>">
			<select name="icon_set" class="us-icon-select">
				<?php foreach ( $icon_sets as $set_slug => $set_info ) { ?>
					<option value="<?php echo $set_slug ?>"<?php if ( $select_value == $set_slug ) {
						echo ' selected="selected"';
					} ?> data-info-url="<?php echo $set_info['set_url'] ?>"><?php echo $set_info['set_name'] ?></option>
				<?php } ?>
			</select>
			<div class="us-icon-preview">
				<?php
				$icon_preview_html = preg_replace( '/fa-\dx/', '', us_prepare_icon_tag( $value ) );
				echo ( $icon_preview_html ) ? $icon_preview_html : '<i class="material-icons"></i>';
				?>
			</div>
			<div class="us-icon-input">
				<input name="icon_name" class="wpb-textinput us-icon-text" type="text" value="<?php echo esc_attr( $input_value ); ?>">
			</div>
		</div>
		<div class="us-icon-desc">
			<?php echo '<a class="us-icon-set-link" href="' . $icon_sets[$select_value]['set_url'] . '" target="_blank">' . __( 'Enter icon name from the list', 'us' ) . '</a>. ' . sprintf( __( 'Examples: %s', 'us' ), 'star, edit, code' ) ?>
		</div>
		<?php
		$result = ob_get_clean();

		return $result;
	}
}

// Add parameter for Grid Layout selection
if ( ! function_exists( 'us_grid_layout_field' ) ) {
	global $us_template_directory_uri;
	vc_add_shortcode_param( 'us_grid_layout', 'us_grid_layout_field', $us_template_directory_uri . '/framework/plugins-support/js_composer/js/us_grid_layout.js' );
	function us_grid_layout_field( $settings, $value ){
		$templates_config = us_config( 'grid-templates', array(), TRUE );
		$default_layout_templates = array();
		foreach ( $templates_config as $template_name => $template ) {
			$default_layout_templates[$template['title']] = $template_name;
		}

		$custom_layouts = array_flip( us_get_posts_titles_for( 'us_grid_layout', 'title' ) );
		ob_start();
		?>
		<div class="us-grid-layout">
			<select name="<?php echo esc_attr( $settings['param_name'] ); ?>" class="wpb_vc_param_value wpb-input wpb-select <?php echo esc_attr( $settings['param_name'] ) ?> dropdown us-grid-layout-select">
				<optgroup label="<?php _e( 'Grid Layouts', 'us' ); ?>">
				<?php foreach ( $custom_layouts as $title => $id ) { ?>
					<option value="<?php echo $id ?>"<?php if ( $value == $id ) {
						echo ' selected="selected"';
					} ?> data-edit-url="<?php echo admin_url('/post.php?post=' . $id . '&action=edit'); ?>"><?php echo $title; ?></option>
				<?php } ?>
				</optgroup>
				<optgroup label="<?php _e( 'Grid Layout Templates', 'us' ); ?>">
				<?php foreach ( $default_layout_templates as $title => $name ) { ?>
					<option value="<?php echo $name ?>"<?php if ( $value == $name ) {
						echo ' selected="selected"';
					} ?>><?php echo $title; ?></option>
				<?php } ?>
				</optgroup>
			</select>
			<div class="us-grid-layout-desc-edit">
				<?php echo sprintf( _x( '%sEdit selected%s or %screate a new one%s.', 'Grid Layout', 'us' ), '<a href="#" class="edit-link" target="_blank">', '</a>', '<a href="' . admin_url() . 'post-new.php?post_type=us_grid_layout" target="_blank">', '</a>' ); ?>
			</div>
			<div class="us-grid-layout-desc-add">
				<a href="<?php admin_url() ?>post-new.php?post_type=us_grid_layout" target="_blank"><?php _e( 'Add Grid Layout', 'us' ) ?></a>.
			</div>
		</div>
		<?php
		$result = ob_get_clean();

		return $result;
	}
}

// Add Theme Color Palette to Iris color pickers, mostly used by WPBakery Page Builder
add_action( 'admin_print_scripts', 'us_override_iris_colorpalette', 100 );
function us_override_iris_colorpalette() {
	$screen = get_current_screen();
	if ( wp_script_is( 'wp-color-picker', 'enqueued' ) AND $screen->id != 'toplevel_page_us-theme-options' ) {
		$theme = wp_get_theme();
		if ( is_child_theme() ) {
			$theme = wp_get_theme( $theme->get( 'Template' ) );
		}
		$theme_name = $theme->get( 'Name' );
		$palette = get_option( 'usof_color_palette_' . $theme_name );
		unset( $theme, $theme_name );
		if ( is_array( $palette ) AND ! empty( $palette ) ) {
			$json_palette = array();
			$default_color = "#ffffff";
			$palette_length = count( $palette );
			// Convert all colors to HEX since Page Builder doesn't support transparency in its palette
			foreach ( $palette as $color ) {
				$json_palette[] = us_rgba2hex( $color );
			}
			// Fill till it comes 8 colors
			if ( $palette_length !== 8 ) {
				for ( $i = 0; $i < 8 - $palette_length; $i ++ ) {
					$json_palette[] = $default_color;
				}
			}
			$json_palette = json_encode( $json_palette );
			?>
			<script>
				// Add palette to WordPress iris color pickers
				jQuery(document).ready(function($){
					if (!$.wp) return;
					$.wp.wpColorPicker.prototype.options = {
						palettes: <?php echo $json_palette; ?>,
						width: 255
					};
				});
			</script>
			<?php
		}
	}
}
