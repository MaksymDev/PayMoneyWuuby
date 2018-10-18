<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * @var $grid_layout_settings         array selected Grid Layout settings
 * @var $post_type                    string WordPress post type name to show
 * @var $type                         string layout type: 'grid' / 'masonry' / 'carousel'
 * @var $columns                      int Columns quantity
 * @var $items_gap                    string Gap between items, ex: '10px' / '1em' / '3%'
 * @var $pagination                   string Pagination type: regular / none / ajax / infinite
 * @var $items_layout                 string|int Grid Layout ID
 * @var $title_size                   string Post Title size
 * @var $breakpoint_1_width           int responsive option
 * @var $breakpoint_1_cols            int responsive option
 * @var $breakpoint_1_autoplay        int responsive option
 * @var $breakpoint_2_width           int responsive option
 * @var $breakpoint_2_cols            int responsive option
 * @var $breakpoint_2_autoplay        int responsive option
 * @var $breakpoint_3_width           int responsive option
 * @var $breakpoint_3_cols            int responsive option
 * @var $breakpoint_3_autoplay        int responsive option
 * @var $filter_html                  string Filter HTML
 * @var $el_class                     string Additional classes that will be appended to the main .w-grid container
 * @var $grid_elm_id                  string DOM element ID
 * @var $is_widget                    bool if used in widget
 * @var $data_atts                    string Data for JS
 */

// Variables defaults
$classes = $list_classes = $css_listing = $css_layout = '';

$us_grid_index = isset( $us_grid_index ) ? intval( $us_grid_index ) : 0;
$post_type = isset( $post_type ) ? $post_type : 'post';
$type = isset( $type ) ? $type : 'grid';
$columns = isset( $columns ) ? intval( $columns ) : 2;
$items_gap = isset( $items_gap ) ? trim( $items_gap ) : '';
$items_layout = isset( $items_layout ) ? $items_layout : 'blog_classic';
$title_size = isset( $title_size ) ? trim( $title_size ) : '';
$el_class = isset( $el_class ) ? trim( $el_class ) : '';
$is_widget = isset( $is_widget ) ? $is_widget : FALSE;
$pagination = isset( $pagination ) ? $pagination : 'none';

$breakpoint_1_width = isset( $breakpoint_1_width ) ? $breakpoint_1_width : us_get_option( 'blog_breakpoint_1_width' );
$breakpoint_1_cols = isset( $breakpoint_1_cols ) ? $breakpoint_1_cols : us_get_option( 'blog_breakpoint_1_cols' );
$breakpoint_1_autoplay = isset( $breakpoint_1_autoplay ) ? $breakpoint_1_autoplay : FALSE;

$breakpoint_2_width = isset( $breakpoint_2_width ) ? $breakpoint_2_width : us_get_option( 'blog_breakpoint_2_width' );
$breakpoint_2_cols = isset( $breakpoint_2_cols ) ? $breakpoint_2_cols : us_get_option( 'blog_breakpoint_2_cols' );
$breakpoint_2_autoplay = isset( $breakpoint_2_autoplay ) ? $breakpoint_2_autoplay : FALSE;

$breakpoint_3_width = isset( $breakpoint_3_width ) ? $breakpoint_3_width : us_get_option( 'blog_breakpoint_3_width' );
$breakpoint_3_cols = isset( $breakpoint_3_cols ) ? $breakpoint_3_cols : us_get_option( 'blog_breakpoint_3_cols' );
$breakpoint_3_autoplay = isset( $breakpoint_3_autoplay ) ? $breakpoint_3_autoplay : FALSE;

$grid_elm_id = ( ! empty( $grid_elm_id ) ) ? empty( $grid_elm_id ) : 'us_grid_' . $us_grid_index;

$filter_html = isset( $filter_html ) ? $filter_html : '';
$data_atts = isset( $data_atts ) ? $data_atts : '';

// Additional classes for "w-grid"
$classes .= ' type_' . $type;
$classes .= ' layout_' . $items_layout;
if ( $columns != 1 AND $type != 'carousel' ) {
	$classes .= ' cols_' . $columns;
}
if ( $pagination == 'regular' ) {
	$classes .= ' with_pagination';
}
if ( ! empty( $el_class ) ) {
	$classes .= ' ' . $el_class;
}
if ( us_arr_path( $grid_layout_settings, 'default.options.fixed' ) ) {
	$classes .= ' height_fixed';
} elseif ( us_arr_path( $grid_layout_settings, 'default.options.overflow' ) ) {
	$classes .= ' overflow_hidden';
}
if ( us_arr_path( $grid_layout_settings, 'default.options.link' ) == 'popup_post' ) {
	$classes .= ' lightbox_page';
}
if ( $filter_html != '' ) {
	$classes .= ' with_filters';
}

// Apply isotope script for Masonry type
if ( $type == 'masonry' AND $columns > 1 ) {
	if ( us_get_option( 'ajax_load_js', 0 ) == 0 ) {
		wp_enqueue_script( 'us-isotope' );
	}
	$classes .= ' with_isotope';
	if ( $type == 'grid' ) {
		$classes .= ' isotope_fit_rows';
	}
}

// Output attributes for Carousel type
if ( $type == 'carousel' ) {
	if ( us_get_option( 'ajax_load_js', 0 ) == 0 ) {
		wp_enqueue_script( 'us-owl' );
	}

	$list_classes = ' owl-carousel';
}

// Generate items gap via CSS
if ( ! empty( $items_gap ) ) {
	if ( $columns != 1 ) {
		$css_listing .= '#' . $grid_elm_id . ' .w-grid-item { padding: ' . $items_gap . '}';
		if ( ! empty( $filter_html ) AND $pagination == 'none' ) {
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-list { margin: ' . $items_gap . ' -' . $items_gap . ' -' . $items_gap . '}';
		}
		if ( ! empty( $filter_html ) AND $pagination != 'none' ) {
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-list { margin: ' . $items_gap . ' -' . $items_gap . '}';
		}
		if ( empty( $filter_html ) AND $pagination != 'none' ) {
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-list { margin: -' . $items_gap . ' -' . $items_gap . ' ' . $items_gap . '}';
		}
		if ( empty( $filter_html ) AND $pagination == 'none' ) {
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-list { margin: -' . $items_gap . '}';
		}
		// Force gap between neighbour "w-grid" elements
		$css_listing .= '.w-grid + #' . $grid_elm_id . ' .w-grid-list { margin-top: ' . $items_gap . '}';
		// Force left & right gaps for grid-list in fullwidth section
		$css_listing .= '.l-section.width_full .vc_row > .vc_col-sm-12 > div > div > #' . $grid_elm_id . ' .w-grid-list { margin-left: ' . $items_gap . '; margin-right: ' . $items_gap . '}';
		// Force top & bottom gaps for grid-list in fullheight section
		$css_listing .= '.l-section.height_auto .vc_row > .vc_col-sm-12 > div > div > #' . $grid_elm_id . ':first-child .w-grid-list { margin-top: ' . $items_gap . '}';
		$css_listing .= '.l-section.height_auto .vc_row > .vc_col-sm-12 > div > div > #' . $grid_elm_id . ':last-child .w-grid-list { margin-bottom: ' . $items_gap . '}';
	} elseif ( $type != 'carousel' ) {
		$css_listing .= '#' . $grid_elm_id . ' .w-grid-item:not(:last-child) { margin-bottom: ' . $items_gap . '}';
		$css_listing .= '#' . $grid_elm_id . ' .g-loadmore { margin-top: ' . $items_gap . '}';
	}
} else {
	$classes .= ' no_gap';
}

// Generate columns responsive CSS
if ( $type != 'carousel' AND ! $is_widget ) {

	if ( $columns > intval( $breakpoint_1_cols ) ) {
		$css_listing .= '@media (max-width:' . ( intval( $breakpoint_1_width ) - 1 ) . 'px) {';
		if ( intval( $breakpoint_1_cols ) == 1 AND ! empty( $items_gap ) ) {
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-list { margin: 0 }';
		}
		$css_listing .= '#' . $grid_elm_id . ' .w-grid-item { width:' . 100 / intval( $breakpoint_1_cols ) . '%;';
		if ( intval( $breakpoint_1_cols ) == 1 AND ! empty( $items_gap ) ) {
			$css_listing .= 'padding: 0; margin-bottom: ' . $items_gap;
		}
		$css_listing .= '}';
		if ( $post_type == 'us_portfolio' AND intval( $breakpoint_1_cols ) != 1 ) {
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-item.size_2x1,';
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-item.size_2x2 {';
			$css_listing .= 'width:' . 200 / intval( $breakpoint_1_cols ) . '%}';
		}
		$css_listing .= '}';
	}

	if ( $columns > intval( $breakpoint_2_cols ) ) {
		$css_listing .= '@media (max-width:' . ( intval( $breakpoint_2_width ) - 1 ) . 'px) {';
		if ( intval( $breakpoint_2_cols ) == 1 AND ! empty( $items_gap ) ) {
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-list { margin: 0 }';
		}
		$css_listing .= '#' . $grid_elm_id . ' .w-grid-item { width:' . 100 / intval( $breakpoint_2_cols ) . '%;';
		if ( intval( $breakpoint_2_cols ) == 1 AND ! empty( $items_gap ) ) {
			$css_listing .= 'padding: 0; margin-bottom: ' . $items_gap;
		}
		$css_listing .= '}';
		if ( $post_type == 'us_portfolio' AND intval( $breakpoint_2_cols ) != 1 ) {
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-item.size_2x1,';
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-item.size_2x2 {';
			$css_listing .= 'width:' . 200 / intval( $breakpoint_2_cols ) . '%}';
		}
		$css_listing .= '}';
	}

	if ( $columns > intval( $breakpoint_3_cols ) ) {
		$css_listing .= '@media (max-width:' . ( intval( $breakpoint_3_width ) - 1 ) . 'px) {';
		if ( intval( $breakpoint_3_cols ) == 1 AND ! empty( $items_gap ) ) {
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-list { margin: 0 }';
		}
		$css_listing .= '#' . $grid_elm_id . ' .w-grid-item { width:' . 100 / intval( $breakpoint_3_cols ) . '%;';
		if ( intval( $breakpoint_3_cols ) == 1 AND ! empty( $items_gap ) ) {
			$css_listing .= 'padding: 0; margin-bottom: ' . $items_gap;
		}
		$css_listing .= '}';
		if ( $post_type == 'us_portfolio' AND intval( $breakpoint_3_cols ) != 1 ) {
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-item.size_2x1,';
			$css_listing .= '#' . $grid_elm_id . ' .w-grid-item.size_2x2 {';
			$css_listing .= 'width:' . 200 / intval( $breakpoint_3_cols ) . '%}';
		}
		$css_listing .= '}';
	}
}

// Add Post Title font-size for current listing only
if ( $title_size != '' ) {
	$css_listing .= '#' . $grid_elm_id . ' .w-grid-item-elm.post_title { font-size: ' . $title_size . '}';
}

// Calculate items aspect ratio
$grid_elm_ratio_w = $grid_elm_ratio_h = 1;
$grid_elm_ratio = us_arr_path( $grid_layout_settings, 'default.options.ratio' );
if ( $grid_elm_ratio == '4x3' ) {
	$grid_elm_ratio_w = 4;
	$grid_elm_ratio_h = 3;
} elseif ( $grid_elm_ratio == '3x2' ) {
	$grid_elm_ratio_w = 3;
	$grid_elm_ratio_h = 2;
} elseif ( $grid_elm_ratio == '2x3' ) {
	$grid_elm_ratio_w = 2;
	$grid_elm_ratio_h = 3;
} elseif ( $grid_elm_ratio == '3x4' ) {
	$grid_elm_ratio_w = 3;
	$grid_elm_ratio_h = 4;
} elseif ( $grid_elm_ratio == '16x9' ) {
	$grid_elm_ratio_w = 16;
	$grid_elm_ratio_h = 9;
} elseif ( $grid_elm_ratio == 'custom' ) {
	$grid_elm_ratio_w = floatval( str_replace( ',', '.', preg_replace( '/^[^\d.,]+$/', '', us_arr_path( $grid_layout_settings, 'default.options.ratio_width' ) ) ) );
	if ( $grid_elm_ratio_w <= 0 ) {
		$grid_elm_ratio_w = 1;
	}
	$grid_elm_ratio_h = floatval( str_replace( ',', '.', preg_replace( '/^[^\d.,]+$/', '', us_arr_path( $grid_layout_settings, 'default.options.ratio_height' ) ) ) );
	if ( $grid_elm_ratio_h <= 0 ) {
		$grid_elm_ratio_h = 1;
	}
}
if ( us_arr_path( $grid_layout_settings, 'default.options.fixed' ) ) {

	// Apply grid item aspect ratio
	$css_layout .= '#' . $grid_elm_id . ' .w-grid-item-h:before {';
	$css_layout .= 'padding-bottom: ' . number_format( $grid_elm_ratio_h / $grid_elm_ratio_w * 100, 4 ) . '%}';

	// Fix aspect ratio regarding Portfolio custom size and items gap
	if ( empty( $items_gap ) ) {
		$items_gap = '0px'; // needed for CSS calc function
	}
	if ( $post_type == 'us_portfolio' AND $type != 'carousel' AND ! $is_widget ) {
		$css_layout .= '@media (min-width:' . intval( $breakpoint_3_width ) . 'px) {';
		$css_layout .= '#' . $grid_elm_id . ' .w-grid-item.size_1x2 .w-grid-item-h:before {';
		$css_layout .= 'padding-bottom: calc(' . ( $grid_elm_ratio_h * 2 ) / $grid_elm_ratio_w * 100 . '% + ' . $items_gap . ' + ' . $items_gap . ')}';
		$css_layout .= '#' . $grid_elm_id . ' .w-grid-item.size_2x1 .w-grid-item-h:before {';
		$css_layout .= 'padding-bottom: calc(' . $grid_elm_ratio_h / ( $grid_elm_ratio_w * 2 ) * 100 . '% - ' . $items_gap . ' * ' . $grid_elm_ratio_h / $grid_elm_ratio_w . ')}';
		$css_layout .= '#' . $grid_elm_id . ' .w-grid-item.size_2x2 .w-grid-item-h:before {';
		$css_layout .= 'padding-bottom: calc(' . $grid_elm_ratio_h / $grid_elm_ratio_w * 100 . '% - ' . $items_gap . ' * ' . 2 * ( $grid_elm_ratio_h / $grid_elm_ratio_w - 1 ) . ')}';
		$css_layout .= '}';
	}
}

// Generate Grid Layout settings CSS
$item_bg_color = us_arr_path( $grid_layout_settings, 'default.options.color_bg' );
$item_text_color = us_arr_path( $grid_layout_settings, 'default.options.color_text' );
$item_border_radius = us_arr_path( $grid_layout_settings, 'default.options.border_radius' );
$item_box_shadow = us_arr_path( $grid_layout_settings, 'default.options.box_shadow' );
$item_box_shadow_hover = us_arr_path( $grid_layout_settings, 'default.options.box_shadow_hover' );

$css_layout .= '#' . $grid_elm_id . ' .w-grid-item-h {';
if ( ! empty( $item_bg_color ) ) {
	$css_layout .= 'background-color:' . $item_bg_color . ';';
}
if ( ! empty( $item_text_color ) ) {
	$css_layout .= 'color:' . $item_text_color . ';';
}
if ( ! empty( $item_border_radius ) ) {
	$css_layout .= 'border-radius:' . $item_border_radius . 'rem;';
	$css_layout .= 'z-index: 3;';
}
if ( ! empty( $item_box_shadow ) OR ! empty( $item_box_shadow_hover ) ) {
	$css_layout .= 'box-shadow:';
	$css_layout .= '0 ' . number_format( $item_box_shadow / 10, 2 ) . 'rem ' . number_format( $item_box_shadow / 5, 2 ) . 'rem rgba(0,0,0,0.1),';
	$css_layout .= '0 ' . number_format( $item_box_shadow / 3, 2 ) . 'rem ' . number_format( $item_box_shadow, 2 ) . 'rem rgba(0,0,0,0.1);';
	$css_layout .= 'transition-duration: 0.3s;';
}
$css_layout .= '}';
if ( $item_box_shadow_hover != $item_box_shadow ) {
	$css_layout .= '.no-touch #' . $grid_elm_id . ' .w-grid-item-h:hover { box-shadow:';
	$css_layout .= '0 ' . number_format( $item_box_shadow_hover / 10, 2 ) . 'rem ' . number_format( $item_box_shadow_hover / 5, 2 ) . 'rem rgba(0,0,0,0.1),';
	$css_layout .= '0 ' . number_format( $item_box_shadow_hover / 3, 2 ) . 'rem ' . number_format( $item_box_shadow_hover, 2 ) . 'rem rgba(0,0,0,0.15);';
	$css_layout .= 'z-index: 4;';
	$css_layout .= '}';
}

// Generate Grid Layout elements CSS
$css_data = array();
foreach ( $grid_layout_settings['data'] as $elm_id => $elm ) {
	$elm_class = 'usg_' . str_replace( ':', '_', $elm_id );

	// Elements settings
	$css_layout .= '#' . $grid_elm_id . ' .' . $elm_class . '{';
	$css_layout .= ( isset( $elm['font'] ) ) ? us_get_font_css( $elm['font'] ) : '';
	$css_layout .= ( isset( $elm['font_size'] ) AND ! empty( $elm['font_size'] ) ) ? 'font-size:' . $elm['font_size'] . ';' : '';
	$css_layout .= ( isset( $elm['line_height'] ) AND ! empty( $elm['line_height'] ) ) ? 'line-height:' . $elm['line_height'] . ';' : '';
	$css_layout .= ( isset( $elm['text_styles'] ) AND in_array( 'bold', $elm['text_styles'] ) ) ? 'font-weight: bold;' : '';
	$css_layout .= ( isset( $elm['text_styles'] ) AND in_array( 'uppercase', $elm['text_styles'] ) ) ? 'text-transform: uppercase;' : '';
	$css_layout .= ( isset( $elm['text_styles'] ) AND in_array( 'italic', $elm['text_styles'] ) ) ? 'font-style: italic;' : '';
	$css_layout .= ( isset( $elm['width'] ) AND ! empty( $elm['width'] ) ) ? 'width:' . $elm['width'] . '; flex-shrink: 0;' : '';
	$css_layout .= ( isset( $elm['border_radius'] ) AND ! empty( $elm['border_radius'] ) ) ? 'border-radius:' . $elm['border_radius'] . 'rem;' : '';
	$css_layout .= ( isset( $elm['color_bg'] ) AND ! empty( $elm['color_bg'] ) ) ? 'background-color:' . $elm['color_bg'] . ';' : '';
	$css_layout .= ( isset( $elm['color_border'] ) AND ! empty( $elm['color_border'] ) ) ? 'border-color:' . $elm['color_border'] . ';' : '';
	$css_layout .= ( isset( $elm['color_text'] ) AND ! empty( $elm['color_text'] ) ) ? 'color:' . $elm['color_text'] . ';' : '';
	$css_layout .= ( isset( $elm['bg_gradient'] ) AND $elm['bg_gradient'] AND isset( $elm['color_grad'] ) AND ! empty( $elm['color_grad'] ) ) ? 'background: linear-gradient( transparent, ' . $elm['color_grad'] . ');' : '';
	$css_layout .= '}';
	if ( isset( $elm['font_size_mobiles'] ) AND ! empty( $elm['font_size_mobiles'] ) ) {
		$css_layout .= '@media (max-width: ' . ( intval( $breakpoint_3_width ) - 1 ) . 'px) {';
		$css_layout .= '#' . $grid_elm_id . ' .' . $elm_class . '{';
		$css_layout .= 'font-size:' . $elm['font_size_mobiles'] . ' !important;';
		$css_layout .= '}}';
	}
	if ( isset( $elm['line_height_mobiles'] ) AND ! empty( $elm['line_height_mobiles'] ) ) {
		$css_layout .= '@media (max-width: ' . ( intval( $breakpoint_3_width ) - 1 ) . 'px) {';
		$css_layout .= '#' . $grid_elm_id . ' .' . $elm_class . '{';
		$css_layout .= 'line-height:' . $elm['line_height_mobiles'] . ' !important;';
		$css_layout .= '}}';
	}
	if ( isset( $elm['hide_below'] ) AND $elm['hide_below'] != 0 ) {
		$css_layout .= '@media (max-width: ' . ( $elm['hide_below'] - 1 ) . 'px) {';
		$css_layout .= '#' . $grid_elm_id . ' .' . $elm_class . '{ display: none !important; }';
		$css_layout .= '}';
	}

	// CSS of Hover effects
	if ( isset( $elm['hover'] ) AND $elm['hover'] ) {
		$css_layout .= '#' . $grid_elm_id . ' .' . $elm_class . '{';
		$css_layout .= isset( $elm['transition_duration'] ) ? 'transition-duration:' . $elm['transition_duration'] . 's;' : '';
		if ( isset( $elm['scale'] ) AND isset( $elm['translateX'] ) AND isset( $elm['translateY'] ) ) {
			$css_layout .= 'transform: scale(' . $elm['scale'] . ') translate(' . $elm['translateX'] . '%,' . $elm['translateY'] . '%);';
		}
		$css_layout .= ( isset( $elm['opacity'] ) AND intval( $elm['opacity'] ) != 1 ) ? 'opacity:' . $elm['opacity'] . ';' : '';
		$css_layout .= '}';

		$css_layout .= '#' . $grid_elm_id . ' .w-grid-item-h:hover .' . $elm_class . '{';
		if ( isset( $elm['scale_hover'] ) AND isset( $elm['translateX_hover'] ) AND isset( $elm['translateY_hover'] ) ) {
			$css_layout .= 'transform: scale(' . $elm['scale_hover'] . ') translate(' . $elm['translateX_hover'] . '%,' . $elm['translateY_hover'] . '%);';
		}
		$css_layout .= isset( $elm['opacity_hover'] ) ? 'opacity:' . $elm['opacity_hover'] . ';' : '';
		$css_layout .= ( isset( $elm['color_bg_hover'] ) AND ! empty( $elm['color_bg_hover'] ) ) ? 'background-color:' . $elm['color_bg_hover'] . ';' : '';
		$css_layout .= ( isset( $elm['color_border_hover'] ) AND ! empty( $elm['color_border_hover'] ) ) ? 'border-color:' . $elm['color_border_hover'] . ';' : '';
		$css_layout .= ( isset( $elm['color_text_hover'] ) AND ! empty( $elm['color_text_hover'] ) ) ? 'color:' . $elm['color_text_hover'] . ';' : '';
		$css_layout .= '}';
	}

	// CSS Design Options
	if ( isset( $elm['design_options'] ) AND ! empty( $elm['design_options'] ) AND is_array( $elm['design_options'] ) ) {
		foreach ( $elm['design_options'] as $key => $value ) {
			if ( $value === '' ) {
				continue;
			}
			$key = explode( '_', $key );
			if ( ! isset( $css_data[ $key[2] ] ) ) {
				$css_data[ $key[2] ] = array();
			}
			if ( ! isset( $css_data[ $key[2] ][ $elm_class ] ) ) {
				$css_data[ $key[2] ][ $elm_class ] = array();
			}
			if ( ! isset( $css_data[ $key[2] ][ $elm_class ][ $key[0] ] ) ) {
				$css_data[ $key[2] ][ $elm_class ][ $key[0] ] = array();
			}
			$css_data[ $key[2] ][ $elm_class ][ $key[0] ][ $key[1] ] = $value;
		}
	}
}

foreach ( array( 'default' ) as $state ) {
	if ( ! isset( $css_data[ $state ] ) ) {
		continue;
	}
	foreach ( $css_data[ $state ] as $elm_class => $props ) {
		$css_layout .= '#' . $grid_elm_id . ' .' . $elm_class . '{';
		foreach ( $props as $prop => $values ) {
			// Add absolute positioning if its values not empty
			if ( $prop === 'position' AND ! empty( $values ) ) {
				$css_layout .= 'position: absolute;';
			}
			// Add solid border if its values not empty
			if ( $prop === 'border' AND ! empty( $values ) ) {
				$css_layout .= 'border-style: solid; border-width: 0;';
			}
			if ( count( $values ) == 4 AND count( array_unique( $values ) ) == 1 AND $prop !== 'position' ) {
				// All the directions have the same value, so grouping them together
				$values = array_values( $values );

				if ( $prop === 'border' ) {
					$css_layout .= $prop . '-width:' . $values[0] . ';';
				} else {
					$css_layout .= $prop . ':' . $values[0] . ';';
				}
			} else {
				foreach ( $values as $dir => $val ) {
					if ( $prop === 'position' ) {
						$css_prop = $dir;
					} elseif ( $prop === 'border' ) {
						$css_prop = $prop . '-' . $dir . '-width';
					} else {
						$css_prop = $prop . '-' . $dir;
					}
					$css_layout .= $css_prop . ':' . $val . ';';
				}
			}
		}
		$css_layout .= "}";
	}
}

// Output the Grid semantics
echo '<div class="w-grid' . $classes . '" id="' . $grid_elm_id . '">';
echo '<style id="' . $grid_elm_id . '_css">' . us_minify_css( $css_listing ) . '</style>';
echo '<style>' . us_minify_css( $css_layout ) . '</style>'; // TODO make this depends on the previous Grid Layout of a page
echo $filter_html;
echo '<div class="w-grid-list' . $list_classes . '"' . $data_atts . '>';
