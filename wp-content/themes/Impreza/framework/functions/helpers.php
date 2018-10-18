<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Prepare a proper icon tag from user's custom input
 *
 * @param {String} $icon
 *
 * @return mixed|string
 */
function us_prepare_icon_tag( $icon ) {
	$icon = apply_filters( 'us_icon_class', $icon );
	$icon_arr = explode( '|', $icon );
	if ( count( $icon_arr ) != 2 ) {
		return '';
	}

	$icon_arr[1] = strtolower( sanitize_text_field( $icon_arr[1] ) );
	if ( $icon_arr[0] == 'material' ) {
		$icon_tag = '<i class="material-icons">' . str_replace( array( ' ', '-' ), '_', $icon_arr[1] ) . '</i>';
	} else {
		if ( substr( $icon_arr[1], 0, 3 ) == 'fa-' ) {
			$icon_tag = '<i class="' . $icon_arr[0] . ' ' . $icon_arr[1] . '"></i>';
		} else {
			$icon_tag = '<i class="' . $icon_arr[0] . ' fa-' . $icon_arr[1] . '"></i>';
		}
	}

	return apply_filters( 'us_icon_tag', $icon_tag );
}

/**
 * Search for some file in child theme, in parent theme and in framework
 *
 * @param string $filename Relative path to filename with extension
 * @param bool $all List an array of found files
 *
 * @return mixed Single mode: full path to file or FALSE if no file was found
 * @return array All mode: array or all the found files
 */
function us_locate_file( $filename, $all = FALSE ) {
	global $us_template_directory, $us_stylesheet_directory, $us_files_search_paths, $us_file_paths;
	if ( ! isset( $us_files_search_paths ) ) {
		$us_files_search_paths = array();
		if ( is_child_theme() ) {
			// Searching in child theme first
			$us_files_search_paths[] = trailingslashit( $us_stylesheet_directory );
		}
		// Parent theme
		$us_files_search_paths[] = trailingslashit( $us_template_directory );
		// The framework with files common for all themes
		$us_files_search_paths[] = $us_template_directory . '/framework/';
		// Can be overloaded if you decide to overload something from certain plugin
		$us_files_search_paths = apply_filters( 'us_files_search_paths', $us_files_search_paths );
	}
	if ( ! $all ) {
		if ( ! isset( $us_file_paths ) ) {
			$us_file_paths = apply_filters( 'us_file_paths', array() );
		}
		$filename = untrailingslashit( $filename );
		if ( ! isset( $us_file_paths[ $filename ] ) ) {
			$us_file_paths[ $filename ] = FALSE;
			foreach ( $us_files_search_paths as $search_path ) {
				if ( file_exists( $search_path . $filename ) ) {
					$us_file_paths[ $filename ] = $search_path . $filename;
					break;
				}
			}
		}

		return $us_file_paths[ $filename ];
	} else {
		$found = array();

		foreach ( $us_files_search_paths as $search_path ) {
			if ( file_exists( $search_path . $filename ) ) {
				$found[] = $search_path . $filename;
			}
		}

		return $found;
	}
}

/**
 * Load some specified template and pass variables to it's scope.
 *
 * (!) If you create a template that is loaded via this method, please describe the variables that it should receive.
 *
 * @param string $template_name Template name to include (ex: 'templates/form/form')
 * @param array $vars Array of variables to pass to a included templated
 */
function us_load_template( $template_name, $vars = NULL ) {

	// Searching for the needed file in a child theme, in the parent theme and, finally, in the framework
	$file_path = us_locate_file( $template_name . '.php' );

	// Template not found
	if ( $file_path === FALSE ) {
		do_action( 'us_template_not_found:' . $template_name, $vars );

		return;
	}

	$vars = apply_filters( 'us_template_vars:' . $template_name, (array) $vars );
	if ( is_array( $vars ) AND count( $vars ) > 0 ) {
		extract( $vars, EXTR_SKIP );
	}

	do_action( 'us_before_template:' . $template_name, $vars );

	include $file_path;

	do_action( 'us_after_template:' . $template_name, $vars );
}

/**
 * Get some specified template output with variables passed to it's scope.
 *
 * (!) If you create a template that is loaded via this method, please describe the variables that it should receive.
 *
 * @param string $template_name Template name to include (ex: 'templates/form/form')
 * @param array $vars Array of variables to pass to a included templated
 *
 * @return string
 */
function us_get_template( $template_name, $vars = NULL ) {
	ob_start();
	us_load_template( $template_name, $vars );

	return ob_get_clean();
}

/**
 * Get theme option or return default value
 *
 * @param string $name
 * @param mixed $default_value
 *
 * @return mixed
 */
function us_get_option( $name, $default_value = NULL ) {
	return usof_get_option( $name, $default_value );
}

/**
 * @var $us_query array Allows to use different global $wp_query in different context safely
 */
$us_wp_queries = array();

/**
 * Opens a new context to use a new custom global $wp_query
 *
 * (!) Don't forget to close it!
 */
function us_open_wp_query_context() {
	if ( is_array( $GLOBALS ) AND isset( $GLOBALS['wp_query'] ) ) {
		array_unshift( $GLOBALS['us_wp_queries'], $GLOBALS['wp_query'] );
	}
}

/**
 * Closes last context with a custom
 */
function us_close_wp_query_context() {
	if ( isset( $GLOBALS['us_wp_queries'] ) AND count( $GLOBALS['us_wp_queries'] ) > 0 ) {
		$GLOBALS['wp_query'] = array_shift( $GLOBALS['us_wp_queries'] );
		wp_reset_postdata();
	} else {
		// In case someone forgot to open the context
		wp_reset_query();
	}
}

/**
 * Get a value from multidimensional array by path
 *
 * @param array $arr
 * @param string|array $path <key1>[.<key2>[...]]
 * @param mixed $default
 *
 * @return mixed
 */
function us_arr_path( &$arr, $path, $default = NULL ) {
	$path = is_string( $path ) ? explode( '.', $path ) : $path;
	foreach ( $path as $key ) {
		if ( ! is_array( $arr ) OR ! isset( $arr[ $key ] ) ) {
			return $default;
		}
		$arr = &$arr[ $key ];
	}

	return $arr;
}

/**
 * Load and return some specific config or it's part
 *
 * @param string $path <config_name>[.<key1>[.<key2>[...]]]
 *
 * @oaram mixed $default Value to return if no data is found
 *
 * @return mixed
 */
function us_config( $path, $default = NULL, $reload = FALSE ) {
	global $us_template_directory;
	// Caching configuration values in a inner static value within the same request
	static $configs = array();
	// Defined paths to configuration files
	$config_name = strtok( $path, '.' );
	if ( ! isset( $configs[ $config_name ] ) OR $reload ) {
		$config_paths = array_reverse( us_locate_file( 'config/' . $config_name . '.php', TRUE ) );
		if ( empty( $config_paths ) ) {
			if ( WP_DEBUG ) {
				wp_die( 'Config not found: ' . $config_name );
			}
			$configs[ $config_name ] = array();
		} else {
			us_maybe_load_theme_textdomain();
			// Parent $config data may be used from a config file
			$config = array();
			foreach ( $config_paths as $config_path ) {
				$config = require $config_path;
				// Config may be forced not to be overloaded from a config file
				if ( isset( $final_config ) AND $final_config ) {
					break;
				}
			}
			$configs[ $config_name ] = apply_filters( 'us_config_' . $config_name, $config );
		}
	}

	$path = substr( $path, strlen( $config_name ) + 1 );
	if ( $path == '' ) {
		return $configs[ $config_name ];
	}

	return us_arr_path( $configs[ $config_name ], $path, $default );
}

/**
 * Get image size information as an array
 *
 * @param string $size_name
 *
 * @return array
 */
function us_get_intermediate_image_size( $size_name ) {
	global $_wp_additional_image_sizes;
	if ( isset( $_wp_additional_image_sizes[ $size_name ] ) ) {
		// Getting custom image size
		return $_wp_additional_image_sizes[ $size_name ];
	} else {
		// Getting standard image size
		return array(
			'width' => get_option( "{$size_name}_size_w" ),
			'height' => get_option( "{$size_name}_size_h" ),
			'crop' => get_option( "{$size_name}_crop" ),
		);
	}
}

/**
 * Transform some variable to elm's onclick attribute, so it could be obtained from JavaScript as:
 * var data = elm.onclick()
 *
 * @param mixed $data Data to pass
 *
 * @return string Element attribute ' onclick="..."'
 */
function us_pass_data_to_js( $data ) {
	return ' onclick=\'return ' . htmlspecialchars( json_encode( $data ), ENT_QUOTES, 'UTF-8' ) . '\'';
}

/**
 * Try to get variable from JSON-encoded post variable
 *
 * Note: we pass some params via json-encoded variables, as via pure post some data (ex empty array) will be absent
 *
 * @param string $name $_POST's variable name
 *
 * @return array
 */
function us_maybe_get_post_json( $name = 'template_vars' ) {
	if ( isset( $_POST[ $name ] ) AND is_string( $_POST[ $name ] ) ) {
		$result = json_decode( stripslashes( $_POST[ $name ] ), TRUE );
		if ( ! is_array( $result ) ) {
			$result = array();
		}

		return $result;
	} else {
		return array();
	}
}

/**
 * No js_composer enabled link parsing compatibility
 *
 * @param $value
 *
 * @return array
 */
function us_vc_build_link( $value ) {
	if ( function_exists( 'vc_build_link' ) ) {
		$result = vc_build_link( $value );
	} else {
		$result = array( 'url' => '', 'title' => '', 'target' => '', 'rel' => '' );
		$params_pairs = explode( '|', $value );
		if ( ! empty( $params_pairs ) ) {
			foreach ( $params_pairs as $pair ) {
				$param = explode( ':', $pair, 2 );
				if ( ! empty( $param[0] ) AND isset( $param[1] ) ) {
					$result[ $param[0] ] = rawurldecode( $param[1] );
				}
			}
		}
	}

	// Some of the values may have excess spaces, like the target's ' _blank' value.
	return array_map( 'trim', $result );
}

/**
 * Load theme's textdomain
 *
 * @param string $domain
 * @param string $path Relative path to seek in child theme and theme
 *
 * @return bool
 */
function us_maybe_load_theme_textdomain( $domain = 'us', $path = '/languages' ) {
	if ( is_textdomain_loaded( $domain ) ) {
		return TRUE;
	}
	$locale = apply_filters( 'theme_locale', is_admin() ? get_user_locale() : get_locale(), $domain );
	$filepath = us_locate_file( trailingslashit( $path ) . $locale . '.mo' );
	if ( $filepath === FALSE ) {
		return FALSE;
	}

	return load_textdomain( $domain, $filepath );
}

/**
 * Merge arrays, inserting $arr2 into $arr1 before/after certain key
 *
 * @param array $arr Modifyed array
 * @param array $inserted Inserted array
 * @param string $position 'before' / 'after' / 'top' / 'bottom'
 * @param string $key Associative key of $arr1 for before/after insertion
 *
 * @return array
 */
function us_array_merge_insert( array $arr, array $inserted, $position = 'bottom', $key = NULL ) {
	if ( $position == 'top' ) {
		return array_merge( $inserted, $arr );
	}
	$key_position = ( $key === NULL ) ? FALSE : array_search( $key, array_keys( $arr ) );
	if ( $key_position === FALSE OR ( $position != 'before' AND $position != 'after' ) ) {
		return array_merge( $arr, $inserted );
	}
	if ( $position == 'after' ) {
		$key_position ++;
	}

	return array_merge( array_slice( $arr, 0, $key_position, TRUE ), $inserted, array_slice( $arr, $key_position, NULL, TRUE ) );
}

/**
 * Recursively merge two or more arrays in a proper way
 *
 * @param array $array1
 * @param array $array2
 * @param       array ...
 *
 * @return array
 */
function us_array_merge( $array1, $array2 ) {
	$keys = array_keys( $array2 );
	// Is associative array?
	if ( array_keys( $keys ) !== $keys ) {
		foreach ( $array2 as $key => $value ) {
			if ( is_array( $value ) AND isset( $array1[ $key ] ) AND is_array( $array1[ $key ] ) ) {
				$array1[ $key ] = us_array_merge( $array1[ $key ], $value );
			} else {
				$array1[ $key ] = $value;
			}
		}
	} else {
		foreach ( $array2 as $value ) {
			if ( ! in_array( $value, $array1, TRUE ) ) {
				$array1[] = $value;
			}
		}
	}

	if ( func_num_args() > 2 ) {
		foreach ( array_slice( func_get_args(), 2 ) as $array2 ) {
			$array1 = us_array_merge( $array1, $array2 );
		}
	}

	return $array1;
}

/**
 * Combine user attributes with known attributes and fill in defaults from config when needed.
 *
 * @param array $atts Passed attributes
 * @param string $shortcode Shortcode name
 * @param string $param_name Shortcode's config param to take pairs from
 *
 * @return array
 */
function us_shortcode_atts( $atts, $shortcode, $param_name = 'atts' ) {
	$pairs = us_config( 'shortcodes.' . $shortcode . '.' . $param_name, array() );

	return shortcode_atts( $pairs, $atts, $shortcode );
}

/**
 * Get number of shares of the provided URL.
 *
 * @param string $url The url to count shares
 * @param array $providers Possible array values: 'facebook', 'twitter', 'linkedin', 'gplus', 'pinterest'
 *
 * @link https://gist.github.com/jonathanmoore/2640302 Great relevant code snippets
 *
 * Dev note: keep in mind that list of providers may differ for the same URL in different function calls.
 *
 * @return array Associative array of providers => share counts
 */
function us_get_sharing_counts( $url, $providers ) {
	$transient = 'us_sharing_count_' . md5( $url );
	// Will be used for array keys operations
	$flipped = array_flip( $providers );
	$cached_counts = get_transient( $transient );
	if ( is_array( $cached_counts ) ) {
		$counts = array_intersect_key( $cached_counts, $flipped );
		if ( count( $counts ) == count( $providers ) ) {
			// The data exists and is complete
			return $counts;
		}
	} else {
		$counts = array();
	}

	// Facebook share count
	if ( in_array( 'facebook', $providers ) AND ! isset( $counts['facebook'] ) ) {
		$fb_query = 'SELECT share_count FROM link_stat WHERE url = "';
		$remote_get_url = 'https://graph.facebook.com/fql?q=' . urlencode( $fb_query ) . $url . urlencode( '"' );
		$result = wp_remote_get( $remote_get_url, array( 'timeout' => 3 ) );
		if ( is_array( $result ) ) {
			$data = json_decode( $result['body'], TRUE );
		} else {
			$data = NULL;
		}
		if ( is_array( $data ) AND isset( $data['data'] ) AND isset( $data['data'][0] ) AND isset( $data['data'][0]['share_count'] ) ) {
			$counts['facebook'] = $data['data'][0]['share_count'];
		} else {
			$counts['facebook'] = '0';
		}
	}

	// Twitter share count
	if ( in_array( 'twitter', $providers ) AND ! isset( $counts['twitter'] ) ) {
		// Twitter is not supporting sharing counts API and has no plans for it at the moment
		$counts['twitter'] = '0';
	}

	// Google+ share count
	if ( in_array( 'gplus', $providers ) AND ! isset( $counts['gplus'] ) ) {
		// Cannot use the official API, as it requires a separate key, and even with this key doesn't work
		$result = wp_remote_get( 'https://plusone.google.com/_/+1/fastbutton?url=' . $url, array( 'timeout' => 3 ) );
		if ( is_array( $result ) AND preg_match( '~\<div[^\>]+id=\"aggregateCount\"[^\>]*\>([^\>]+)\<\/div\>~', $result['body'], $matches ) ) {
			$counts['gplus'] = $matches[1];
		} else {
			$counts['gplus'] = '0';
		}
	}

	// LinkedIn share count
	if ( in_array( 'linkedin', $providers ) AND ! isset( $counts['linkedin'] ) ) {
		$result = wp_remote_get( 'http://www.linkedin.com/countserv/count/share?url=' . $url . '&format=json', array( 'timeout' => 3 ) );
		if ( is_array( $result ) ) {
			$data = json_decode( $result['body'], TRUE );
		} else {
			$data = NULL;
		}
		$counts['linkedin'] = isset( $data['count'] ) ? $data['count'] : '0';
	}

	// Pinterest share count
	if ( in_array( 'pinterest', $providers ) AND ! isset( $counts['pinterest'] ) ) {
		$result = wp_remote_get( 'http://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url=' . $url, array( 'timeout' => 3 ) );
		if ( is_array( $result ) ) {
			$data = json_decode( rtrim( str_replace( 'receiveCount(', '', $result['body'] ), ')' ), TRUE );
		} else {
			$data = NULL;
		}
		$counts['pinterest'] = isset( $data['count'] ) ? $data['count'] : '0';
	}

	// VK share count
	if ( in_array( 'vk', $providers ) AND ! isset( $counts['vk'] ) ) {
		$result = wp_remote_get( 'http://vkontakte.ru/share.php?act=count&index=1&url=' . $url, array( 'timeout' => 3 ) );
		if ( is_array( $result ) ) {
			$data = intval( trim( str_replace( ');', '', str_replace( 'VK.Share.count(1, ', '', $result['body'] ) ) ) );
		} else {
			$data = NULL;
		}
		$counts['vk'] = ( ! empty( $data ) ) ? $data : '0';
	}

	// Caching the result for the next 2 hours
	set_transient( $transient, $counts, 2 * HOUR_IN_SECONDS );

	return $counts;
}

/**
 * Call language function with string existing in WordPress or supported plugins and prevent those strings from going into theme .po/.mo files
 *
 * @return string Translated text.
 */
function us_translate( $text, $domain = NULL ) {
	if ( $domain == NULL ) {
		return __( $text );
	} else {
		return __( $text, $domain );
	}
}

function us_translate_x( $text, $context, $domain = NULL ) {
	if ( $domain == NULL ) {
		return _x( $text, $context );
	} else {
		return _x( $text, $context, $domain );
	}
}

function us_translate_n( $single, $plural, $number, $domain = NULL ) {
	if ( $domain == NULL ) {
		return _n( $single, $plural, $number );
	} else {
		return _n( $single, $plural, $number, $domain );
	}
}

/**
 * Prepare a proper inline-css string from given css property
 *
 * @param array $props
 * @param bool $style_attr
 *
 * @return string
 */
function us_prepare_inline_css( $props, $style_attr = TRUE ) {
	$result = '';
	foreach ( $props as $prop => $value ) {
		// Do not apply if a value is empty, equals 0 or contains double minus --
		if ( empty( $value ) OR ( is_string( $value ) AND strpos( $value, '--' ) !== FALSE ) ) {
			continue;
		}
		switch ( $prop ) {
			// Properties that can be set either in percents or in pixels
			case 'height':
			case 'width':
				if ( is_string( $value ) AND strpos( $value, '%' ) !== FALSE ) {
					$result .= $prop . ':' . floatval( $value ) . '%;';
				} else {
					$result .= $prop . ':' . intval( $value ) . 'px;';
				}
				break;
			// Properties that can be set only in pixels
			case 'border-width':
				$result .= $prop . ':' . intval( $value ) . 'px;';
				break;
			// Properties with image values
			case 'background-image':
				if ( is_numeric( $value ) ) {
					$image = wp_get_attachment_image_src( $value, 'full' );
					if ( $image ) {
						$result .= $prop . ':url("' . $image[0] . '");';
					}
				} else {
					$result .= $prop . ':url("' . $value . '");';
				}
				break;
			// All other properties
			default:
				$result .= $prop . ':' . $value . ';';
				break;
		}
	}
	if ( $style_attr AND ! empty( $result ) ) {
		$result = ' style="' . esc_attr( $result ) . '"';
	}

	return $result;
}

/**
 * Prepares a minified version of CSS file
 *
 * @link http://manas.tungare.name/software/css-compression-in-php/
 * @param string $css
 *
 * @return string
 */
function us_minify_css( $css ) {
	// Remove comments
	$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );

	// Remove space around opening bracket
	$css = str_replace( array( ' {', '{ ' ), '{', $css );

	// Remove space after colons
	$css = str_replace( ': ', ':', $css );

	// Remove spaces
	$css = str_replace( ' > ', '>', $css );
	$css = str_replace( ' ~ ', '~', $css );

	// Remove whitespace
	$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );

	// Remove semicolon before closing bracket
	$css = str_replace( array( ';}', '; }' ), '}', $css );

	return $css;
}


/**
 * Prepares a minified version of JS file
 *
 * @link https://datayze.com/howto/minify-javascript-with-php.php
 * @param string $js
 *
 * @return string
 */
function us_minify_js( $js ) {
	// Remove comments
	$js = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $js );
	$js = preg_replace( '/(https?:)(\/\/[^\n]+)|(\/\/[^\n]+)/m', '$1$2', $js );

	$us_minify = new UpSolutionJsMinifier();
	return $us_minify->minify( $js );
}

/**
 * Perform request to US Portal API
 *
 * @param $url
 *
 * @return array|bool|mixed|object
 */
function us_api_remote_request( $url ) {

	if ( empty( $url ) ) {
		return FALSE;
	}

	$args = array(
		'headers' => array( 'Accept-Encoding' => '' ),
		'sslverify' => FALSE,
		'timeout' => 300,
		'user-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36',
	);
	$request = wp_remote_request( $url, $args );

	if ( is_wp_error( $request ) ) {
		//		echo $request->get_error_message();
		return FALSE;
	}

	$data = json_decode( $request['body'] );

	return $data;
}

/**
 * Get metabox option value
 *
 * @return string|array
 */
function usof_meta( $key, $args = array(), $post_id = NULL ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$value = '';
	if ( ! empty( $key ) ) {
		$value = get_post_meta( $post_id, $key, TRUE );
	}

	return $value;
}

/**
 * Clear square brackets from extra html tags
 *
 * @return string
 */
function us_paragraph_fix( $content ) {
	$array = array(
		'<p>[' => '[',
		']</p>' => ']',
		']<br />' => ']',
		']<br>' => ']',
	);

	$content = strtr( $content, $array );

	return $content;
}

/**
 * Get preloader numbers
 *
 * @return array
 */
function us_get_preloader_numeric_types() {
	$config = us_config( 'theme-options' );
	$result = array();

	if ( isset( $config['general']['fields']['preloader']['options'] ) ) {
		$options = $config['general']['fields']['preloader']['options'];
	} else {
		return array();
	}

	if ( is_array( $options ) ) {
		foreach ( $options as $option => $title ) {
			if ( intval( $option ) != 0 ) {
				$result[] = $option;
			}
		}

		return $result;
	} else {
		return array();
	}
}

/**
 * Convert HEX to RGBA
 *
 * @return string
 */
function us_hex2rgba( $color, $opacity = FALSE ) {
	$default = 'rgb(0,0,0)';

	// Return default if no color provided
	if ( empty( $color ) ) {
		return $default;
	}

	// Sanitize $color if "#" is provided
	if ( $color[0] == '#' ) {
		$color = substr( $color, 1 );
	}

	// Check if color has 6 or 3 characters and get values
	if ( strlen( $color ) == 6 ) {
		$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
	} elseif ( strlen( $color ) == 3 ) {
		$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
	} else {
		return $default;
	}

	// Convert hexadec to rgb
	$rgb = array_map( 'hexdec', $hex );

	// Check if opacity is set(rgba or rgb)
	if ( $opacity ) {
		if ( abs( $opacity ) > 1 ) {
			$opacity = 1.0;
		}
		$output = 'rgba(' . implode( ",", $rgb ) . ',' . $opacity . ')';
	} else {
		$output = 'rgb(' . implode( ",", $rgb ) . ')';
	}

	// Return rgb(a) color string
	return $output;
}

/**
 * Convert RGBA to HEX
 *
 * @return string
 */
function us_rgba2hex( $color ) {
	// Returns HEX in case of RGB is provided, otherwise returns as is
	$default = "#000000";

	if ( empty( $color ) ) {
		return $default;
	}

	$rgb = array();
	$regex = '#\((([^()]+|(?R))*)\)#';

	if ( preg_match_all( $regex, $color, $matches ) ) {
		$rgba = explode( ',', implode( ' ', $matches[1] ) );
		// Cuts first 3 values for RGB
		$rgb = array_slice( $rgba, 0, 3 );
	} else {
		return (string) $color;
	}

	$output = "#";

	foreach ( $rgb as $color ) {
		$hex_val = dechex( intval( $color ) );
		if ( strlen( $hex_val ) === 1 ) {
			$output .= '0' . $hex_val;
		} else {
			$output .= $hex_val;
		}
	}

	return $output;
}

function us_grid_query_offset( &$query ) {
	if ( ! isset( $query->query['_id'] ) OR $query->query['_id'] !== 'us_grid' ) {
		return;
	}

	global $us_grid_items_offset;

	$ppp = ( ! empty( $query->query['posts_per_page'] ) ) ? $query->query['posts_per_page'] : get_option( 'posts_per_page' );

	if ( $query->is_paged ) {
		$page_offset = $us_grid_items_offset + ( ( $query->query_vars['paged'] - 1 ) * $ppp );

		// Apply adjust page offset
		$query->set( 'offset', $page_offset );

	} else {
		// This is the first page. Just use the offset...
		$query->set( 'offset', $us_grid_items_offset );

	}

	remove_action( 'pre_get_posts', 'us_grid_query_offset' );
}

function us_grid_adjust_offset_pagination( $found_posts, $query ) {
	if ( ! isset( $query->query['_id'] ) OR $query->query['_id'] !== 'us_grid' ) {
		return $found_posts;
	}

	global $us_grid_items_offset;
	remove_filter( 'found_posts', 'us_grid_adjust_offset_pagination' );

	// Reduce WordPress's found_posts count by the offset...
	return $found_posts - $us_grid_items_offset;
}

/**
 * Get taxonomies for selection
 *
 * @param string $titles_format Titles format
 *
 * @return array
 */
function us_get_taxonomies( $titles_format = '<taxonomy> (<taxonomy_id>)' ) {
	static $taxonomies = NULL;
	if ( $taxonomies === NULL ) {
		$taxonomies = array();
		foreach ( get_taxonomies( array( 'show_ui' => TRUE ), 'objects' ) as $taxonomy ) {
			if ( empty( $taxonomy->object_type ) OR empty( $taxonomy->object_type[0] ) ) {
				continue;
			}
			$post_type = get_post_type_object( $taxonomy->object_type[0] );
			if ( empty( $post_type ) ) {
				continue;
			}
			$taxonomies[ $taxonomy->name ] = array(
				'post_type' => $post_type->labels->name,
				'taxonomy' => $taxonomy->labels->name,
			);
		}
	}

	$result = array();
	foreach ( $taxonomies as $taxonomy_id => $taxonomy_data ) {
		$result[ $taxonomy_id ] = strtr(
			$titles_format, array(
				// '<post_type>' => $taxonomy_data['post_type'],
				'<taxonomy>' => $taxonomy_data['taxonomy'],
				'<taxonomy_id>' => $taxonomy_id,
			)
		);
	}

	return $result;
}

/**
 * Get custom fields for selection
 *
 * @return array
 */
function us_get_custom_fields() {
	return array(
		'us_tile_additional_image' => __( 'Portfolio Page', 'us' ) . ': ' . __( 'Additional Image', 'us' ),
		'us_testimonial_author' => __( 'Testimonial', 'us' ) . ': ' . __( 'Author Name', 'us' ),
		'us_testimonial_role' => __( 'Testimonial', 'us' ) . ': ' . __( 'Author Role', 'us' ),
		'us_testimonial_company' => __( 'Testimonial', 'us' ) . ': ' . __( 'Author Company', 'us' ),
		'custom' => __( 'Custom Field', 'us' ),
	);
}

/**
 * Make the provided grid settings value consistent and proper
 *
 * @param $value array
 *
 * @return array
 */
function us_fix_grid_settings( $value ) {
	if ( empty( $value ) OR ! is_array( $value ) ) {
		$value = array();
	}
	if ( ! isset( $value['data'] ) OR ! is_array( $value['data'] ) ) {
		$value['data'] = array();
	}

	$options_defaults = array();
	foreach ( us_config( 'grid-settings.options', array() ) as $option_name => $option_group ) {
		foreach ( $option_group as $option_name => $option_field ) {
			$options_defaults[ $option_name ] = usof_get_default( $option_field );
		}
	}

	$elements_defaults = array();
	foreach ( us_config( 'grid-settings.elements', array() ) as $element_name => $element_settings ) {
		$elements_defaults[ $element_name ] = array();
		foreach ( $element_settings['params'] as $param_name => $param_field ) {
			$elements_defaults[ $element_name ][ $param_name ] = usof_get_default( $param_field );
		}
	}

	foreach ( $options_defaults as $option_name => $option_default ) {
		if ( ! isset( $value['default']['options'][ $option_name ] ) ) {
			$value['default']['options'][ $option_name ] = $option_default;
		}
	}
	foreach ( $value['data'] as $element_name => $element_values ) {
		$element_type = strtok( $element_name, ':' );
		if ( ! isset( $elements_defaults[ $element_type ] ) ) {
			continue;
		}
		foreach ( $elements_defaults[ $element_type ] as $param_name => $param_default ) {
			if ( ! isset( $value['data'][ $element_name ][ $param_name ] ) ) {
				$value['data'][ $element_name ][ $param_name ] = $param_default;
			}
		}
	}

	foreach ( array( 'default' ) as $state ) {
		if ( ! isset( $value[ $state ] ) OR ! is_array( $value[ $state ] ) ) {
			$value[ $state ] = array();
		}
		if ( ! isset( $value[ $state ]['layout'] ) OR ! is_array( $value[ $state ]['layout'] ) ) {
			if ( $state != 'default' AND isset( $value['default']['layout'] ) ) {
				$value[ $state ]['layout'] = $value['default']['layout'];
			} else {
				$value[ $state ]['layout'] = array();
			}
		}
		$state_elms = array();
		foreach ( $value[ $state ]['layout'] as $place => $elms ) {
			if ( ! is_array( $elms ) ) {
				$elms = array();
			}
			foreach ( $elms as $index => $elm_id ) {
				if ( ! is_string( $elm_id ) OR strpos( $elm_id, ':' ) == - 1 ) {
					unset( $elms[ $index ] );
				} else {
					$state_elms[] = $elm_id;
					if ( ! isset( $value['data'][ $elm_id ] ) ) {
						$value['data'][ $elm_id ] = array();
					}
				}
			}
			$value[ $state ]['layout'][ $place ] = array_values( $elms );
		}
		if ( ! isset( $value[ $state ]['layout']['hidden'] ) OR ! is_array( $value[ $state ]['layout']['hidden'] ) ) {
			$value[ $state ]['layout']['hidden'] = array();
		}
		$value[ $state ]['layout']['hidden'] = array_merge( $value[ $state ]['layout']['hidden'], array_diff( array_keys( $value['data'] ), $state_elms ) );
		// Fixing options
		if ( ! isset( $value[ $state ]['options'] ) OR ! is_array( $value[ $state ]['options'] ) ) {
			$value[ $state ]['options'] = array();
		}
		$value[ $state ]['options'] = array_merge( $options_defaults, ( $state != 'default' ) ? $value['default']['options'] : array(), $value[ $state ]['options'] );
	}

	return $value;
}

/**
 * Get fonts for selection
 *
 * @return array
 */
function us_get_fonts( $without_groups = FALSE ) {
	$options = array();

	// Regular Text
	$body_font = explode( '|', us_get_option( 'body_font_family', 'none' ), 2 );
	if ( $body_font[0] != 'none' ) {
		$options['body'] = $body_font[0] . ' (' . __( 'used in regular text', 'us' ) . ')';
	} else {
		$options['body'] = __( 'No font specified', 'us' );
	}

	// Headings
	$heading_font = explode( '|', us_get_option( 'heading_font_family', 'none' ), 2 );
	if ( $heading_font[0] != 'none' ) {
		$options['heading'] = $heading_font[0] . ' (' . __( 'used in headings', 'us' ) . ')';
	}

	// Uploaded Fonts
	$uploaded_fonts = us_get_option( 'uploaded_fonts', array() );
	if ( is_array( $uploaded_fonts ) AND count( $uploaded_fonts ) > 0 ) {
		if ( ! $without_groups ) {
			$options[] = array(
				'optgroup' => TRUE,
				'title' => __( 'Uploaded Fonts', 'us' ),
			);
		}
		$uploaded_font_families = array();
		foreach ( $uploaded_fonts as $uploaded_font ) {
			$uploaded_font_name = strip_tags( $uploaded_font['name'] );
			if ( $uploaded_font_name == '' OR in_array( $uploaded_font_name, $uploaded_font_families ) OR empty( $uploaded_font['files'] ) ) {
				continue;
			}
			$uploaded_font_families[] = $uploaded_font_name;
			$options[ $uploaded_font_name ] = $uploaded_font_name;
		}
	}

	// Additional Google Fonts
	$custom_fonts = us_get_option( 'custom_font', array() );
	if ( is_array( $custom_fonts ) AND count( $custom_fonts ) > 0 ) {
		if ( ! $without_groups ) {
			$options[] = array(
				'optgroup' => TRUE,
				'title' => __( 'Google Fonts (loaded from Google servers)', 'us' ),
			);
		}
		foreach ( $custom_fonts as $custom_font ) {
			$font_options = explode( '|', $custom_font['font_family'], 2 );
			$options[ $font_options[0] ] = $font_options[0];
		}
	}

	// Web Safe Fonts
	if ( ! $without_groups ) {
		$options[] = array(
			'optgroup' => TRUE,
			'title' => __( 'Web safe font combinations (do not need to be loaded)', 'us' ),
		);
	}
	$web_safe_fonts = us_config( 'web-safe-fonts' );
	foreach ( $web_safe_fonts as $web_safe_font ) {
		$options[ $web_safe_font ] = $web_safe_font;
	}

	return $options;
}

/**
 * Generate CSS font-family & font-weight of selected font
 *
 * @return string
 */
function us_get_font_css( $font_name ) {
	if ( empty( $font_name ) ) {
		return '';
	}
	static $font_css;
	if ( empty( $font_css ) ) {
		$prefixes = array( 'heading', 'body' );
		$font_options = $font_css = array();

		foreach ( $prefixes as $prefix ) {
			$font_options[ $prefix ] = explode( '|', us_get_option( $prefix . '_font_family', 'none' ), 2 );
		}

		$custom_fonts = us_get_option( 'custom_font', array() );
		if ( is_array( $custom_fonts ) AND count( $custom_fonts ) > 0 ) {
			foreach ( $custom_fonts as $custom_font ) {
				$font_option = explode( '|', $custom_font['font_family'], 2 );
				$font_options[ $font_option[0] ] = $font_option;
			}
		}

		$uploaded_fonts = us_get_option( 'uploaded_fonts', array() );
		if ( is_array( $uploaded_fonts ) AND count( $uploaded_fonts ) > 0 ) {
			foreach ( $uploaded_fonts as $uploaded_font ) {
				$font_options[ $uploaded_font['name'] ] = array(
					0 => strip_tags( $uploaded_font['name'] ),
					1 => $uploaded_font['weight'],
				);
			}
		}

		$web_safe_fonts = us_config( 'web-safe-fonts' );
		foreach ( $web_safe_fonts as $web_safe_font ) {
			$font_options[ $web_safe_font ] = array( $web_safe_font );
		}

		foreach ( $font_options as $prefix => $font ) {
			if ( $font[0] == 'none' ) {
				// Use the default font
				$font_css[ $prefix ][0] = '';
			} elseif ( strpos( $font[0], ',' ) === FALSE ) {
				// Use some specific font from Google Fonts
				if ( ! isset( $font[1] ) OR empty( $font[1] ) ) {
					// Fault tolerance for missing font-variants
					$font[1] = '400,700';
				}
				// The first active font-weight will be used for "normal" weight
				$font_css[ $prefix ][1] = intval( $font[1] );
				$fallback_font_family = us_config( 'google-fonts.' . $font[0] . '.fallback', 'sans-serif' );
				$font_css[ $prefix ][0] = 'font-family:"' . $font[0] . '", ' . $fallback_font_family . ';';
			} else {
				// Web-safe font combination
				$font_css[ $prefix ][0] = 'font-family:' . $font[0] . ';';
			}
		}
	}

	if ( isset( $font_css[ $font_name ] ) AND ! empty( $font_css[ $font_name ][0] ) ) {
		$result = $font_css[ $font_name ][0];
		if ( ! empty( $font_css[ $font_name ][1] ) ) {
			$result .= 'font-weight: ' . $font_css[ $font_name ][1] . ';';
		}

		return $result;
	} else {
		return '';
	}
}

/**
 * Get the remote IP address
 *
 * @return string
 */
function us_get_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return apply_filters( 'us_get_ip', $ip );
}

/**
 * Get Sidebars for selection
 *
 * @return array
 */
function us_get_sidebars() {
	$sidebars = array();
	global $wp_registered_sidebars;

	if ( is_array( $wp_registered_sidebars ) AND ! empty( $wp_registered_sidebars ) ) {
		foreach ( $wp_registered_sidebars as $sidebar ) {
			if ( $sidebar['id'] == 'default_sidebar' ) {
				// Add Default Sidebar to the beginning
				$sidebars = array_merge( array( $sidebar['id'] => $sidebar['name'] ), $sidebars );
			} else {
				$sidebars[ $sidebar['id'] ] = $sidebar['name'];
			}
		}
	}

	return $sidebars;
}

/**
 * Get post types for selection in Grid element
 *
 * @return array
 */
function us_grid_available_post_types( $reload = FALSE ) {
	static $available_posts_types = array();
	if ( count( $available_posts_types ) == 0 OR $reload ) {
		$posts_types_params = array(
			'show_in_menu' => TRUE,
		);
		$skip_post_types = array(
			'us_header',
			'us_page_block',
			'us_grid_layout',
			'attachment',
			'shop_order',
			'shop_coupon',
		);
		foreach ( get_post_types( $posts_types_params, 'objects', 'and' ) as $post_type_name => $post_type ) {
			if ( in_array( $post_type_name, $skip_post_types ) ) {
				continue;
			}
			$available_posts_types[ $post_type_name ] = $post_type->labels->name . ' (' . $post_type_name . ')';
		}
	}

	return $available_posts_types;
}

/**
 * Get post taxonomies for selection in Grid element
 *
 * @return array
 */
function us_grid_available_taxonomies() {

	// Predefine taxonomies for posts
	$available_taxonomies = array(
		'post' => array( 'category', 'post_tag' ),
	);

	$available_posts_types = us_grid_available_post_types();
	foreach ( $available_posts_types as $post_type => $name ) {
		if ( isset( $available_taxonomies[ $post_type ] ) ) {
			continue;
		}
		$post_taxonomies = get_taxonomies(
			array(
				'object_type' => array( $post_type ),
				'public' => TRUE,
			), 'names', 'and'
		);
		if ( is_array( $post_taxonomies ) AND count( $post_taxonomies ) > 0 ) {
			$available_taxonomies[ $post_type ] = array();
			foreach ( $post_taxonomies as $post_taxonomy ) {
				$available_taxonomies[ $post_type ][] = $post_taxonomy;
			}
		}
	}

	return $available_taxonomies;
}

/**
 * Get post link attributes for Grid elements
 *
 * @return array
 */
function us_grid_get_post_link( $link_atts = array() ) {
	$link_atts['href'] = apply_filters( 'the_permalink', get_permalink() );
	$link_atts['meta'] = ( ! empty( $link_atts['meta'] ) ) ? $link_atts['meta'] : '';
	$link_atts['meta'] .= ' rel="bookmark"';
	// Force opening in a new tab for "Link" post format
	if ( get_post_format() == 'link' ) {
		$link_atts['meta'] .= ' target="_blank"';
	}

	return $link_atts;
}

/**
 * Get custom link attributes for Grid elements
 *
 * @return array
 */
function us_grid_get_custom_link( $custom_link, $link_atts = array() ) {
	$custom_link_atts = usof_get_link_atts( $custom_link );
	$link_atts['href'] = ( ! empty( $custom_link_atts['href'] ) ) ? $custom_link_atts['href'] : '';
	$link_atts['meta'] = ( ! empty( $custom_link_atts['target'] ) ) ? ' target="' . esc_attr( $custom_link_atts['target'] ) . '"' : '';
	$link_atts = apply_filters( 'us_grid_element_custom_link', $link_atts );

	return $link_atts;
}

/**
 * Get Custom Post Types (CPT), which have frontend appearance
 *
 * @return array: name => title (plural label)
 */
function us_get_public_cpt() {
	$public_cpt = array();

	// Fetch all post types with specified arguments
	$args = array(
		'public' => TRUE,
		'publicly_queryable' => TRUE,
		'_builtin' => FALSE,
	);
	$post_types = get_post_types( $args, 'objects' );

	// Skip some predefined post types
	$skip_post_types = array(
		// Theme
		'us_portfolio',
		'us_testimonial',
		// WooCommerce
		'product',
		// bbPress
		'reply',
	);

	foreach ( $post_types as $post_type_name => $post_type ) {
		if ( ! in_array( $post_type_name, $skip_post_types ) ) {
			$public_cpt[ $post_type_name ] = $post_type->labels->name;
		}
	}

	return $public_cpt;
}

/**
 * Get value of specified area ID for current page
 *
 * @param string $area: header / titlebar / sidebar / footer
 *
 * @return string
 */
function us_get_page_area_id( $area ) {
	if ( empty( $area ) ) {
		return FALSE;
	}

	$public_cpt = array_keys( us_get_public_cpt() );

	// Defaults
	$area_id = us_get_option( $area . '_id' );

	// Portfolio Pages
	if ( is_singular( array( 'us_portfolio' ) ) ) {
		$area_id = us_get_option( $area . '_portfolio_id' );

		// Posts
	} elseif ( is_singular( array( 'post', 'attachment' ) ) ) {
		$area_id = us_get_option( $area . '_post_id' );

		// Search Results Page
	} elseif ( is_search() ) {
		$area_id = us_get_option( $area . '_search_id' );

		// Blog Home Page
	} elseif ( is_home() ) {
		$area_id = us_get_option( $area . '_blog_id' );

		// Shop & Products
	} elseif ( function_exists( 'is_woocommerce' ) AND is_woocommerce() ) {
		if ( is_product() ) {
			$area_id = us_get_option( $area . '_product_id' );
		} else {
			$area_id = us_get_option( $area . '_shop_id' );
			if ( ! is_search() AND ! is_tax() ) {
				if ( usof_meta( 'us_' . $area, array(), wc_get_page_id( 'shop' ) ) == 'hide' ) {
					$area_id = '';
				} elseif ( usof_meta( 'us_' . $area, array(), wc_get_page_id( 'shop' ) ) == 'custom' ) {
					$area_id = usof_meta( 'us_' . $area . '_id', array(), wc_get_page_id( 'shop' ) );
				}
			}
		}

		// Archive Pages
	} elseif ( is_archive() ) {
		$area_id = us_get_option( $area . '_archive_id' );

		// Supported custom post types
	} elseif ( ! empty( $public_cpt ) AND is_singular( $public_cpt ) ) {
		$post_type = get_post_type();
		// Hot fix for The Events Calendar titlebar
		if ( $area == 'titlebar' AND function_exists( 'tribe_is_event_query' ) AND tribe_is_event_query() ) {
			$post_type = 'tribe_events';
		}
		$area_id = us_get_option( $area . '_' . $post_type . '_id' );
	}

	// Forums archive page
	if ( is_post_type_archive( 'forum' ) ) {
		$area_id = us_get_option( $area . '_forum_id' );
	}

	// Specific page
	if ( is_singular() OR ( is_404() AND $page_404 = get_post( us_get_option( 'page_404' ) ) ) ) {
		if ( is_singular() ) {
			$postID = get_the_ID();
		} elseif ( is_404() ) {
			$postID = $page_404->ID;
		}
		if ( usof_meta( 'us_' . $area, array(), $postID ) == 'hide' ) {
			$area_id = '';
		}
		if ( usof_meta( 'us_' . $area, array(), $postID ) == 'custom' ) {
			$area_id = usof_meta( 'us_' . $area . '_id', array(), $postID );
		}
	}

	// Reset defaults
	if ( $area_id == '__defaults__' ) {
		$area_id = us_get_option( $area . '_id' );
	}
	
	return $area_id;
}

/**
 * Get current page's titlebar and footer content
 */
function us_get_current_titlebar_footer_content() {
	$content = '';

	$footer_id = us_get_page_area_id( 'footer' );
	$titlebar_id = us_get_page_area_id( 'titlebar' );

	// Output content of Page Block (us_page_block) posts
	if ( $footer_id != '' ) {
		$footer = get_post( (int) $footer_id );

		if ( $footer ) {
			$translated_footer_id = apply_filters( 'wpml_object_id', $footer->ID, 'us_page_block', TRUE );
			if ( $translated_footer_id != $footer->ID ) {
				$footer = get_post( $translated_footer_id );
			}
			$content .= $footer->post_content;
		}
	}
	if ( $titlebar_id != '' ) {
		$titlebar = get_post( (int) $titlebar_id );

		if ( $titlebar ) {
			$translated_titlebar_id = apply_filters( 'wpml_object_id', $titlebar->ID, 'us_page_block', TRUE );
			if ( $translated_titlebar_id != $titlebar->ID ) {
				$titlebar = get_post( $translated_titlebar_id );
			}
			$content .= $titlebar->post_content;
		}
	}

	return $content;
}
