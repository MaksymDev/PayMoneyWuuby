<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * @var $query_args                   array Arguments for the new WP_Query. If not set, current global $wp_query will be used instead.
 * @var $us_grid_index                int Grid element number on page
 * @var $post_id                      int post or page where Grid element is placed
 * @var $post_type                    string WordPress post type name to show
 * @var $type                         string layout type: 'grid' / 'masonry' / 'carousel'
 * @var $columns                      int Columns quantity
 * @var $exclude_items                bool Exclude some items from the Grid
 * @var $items_offset                 int Items to skip
 * @var $pagination                   string Pagination type: regular / none / ajax / infinite
 * @var $pagination_btn_text          string Pagination Button text
 * @var $pagination_btn_style         string Pagination Button Style: block / link / btn
 * @var $items_layout                 string|int Grid Layout ID
 * @var $img_size                     string Post Image size
 * @var $carousel_arrows              bool used in Carousel type
 * @var $carousel_dots                bool used in Carousel type
 * @var $carousel_center              bool used in Carousel type
 * @var $carousel_autoplay            bool used in Carousel type
 * @var $carousel_interval            bool used in Carousel type
 * @var $carousel_slideby             bool used in Carousel type
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
 * @var $wp_query                     object Current WP_Query
 */

$us_grid_index = isset( $us_grid_index ) ? intval( $us_grid_index ) : 0;
$post_id = isset( $post_id ) ? $post_id : NULL;
$post_type = isset( $post_type ) ? $post_type : 'post';
$type = isset( $type ) ? $type : 'grid';
$exclude_items = isset( $exclude_items ) ? $exclude_items : 'none';
$items_offset = isset( $items_offset ) ? $items_offset : 0;
$items_layout = isset( $items_layout ) ? $items_layout : 'blog_classic';
$img_size = isset( $img_size ) ? $img_size : 'default';

$pagination = isset( $pagination ) ? $pagination : 'none';
$pagination_btn_text = isset( $pagination_btn_text ) ? $pagination_btn_text : __( 'Load More', 'us' );
$pagination_btn_style = isset( $pagination_btn_style ) ? $pagination_btn_style : 'block';

$breakpoint_1_width = isset( $breakpoint_1_width ) ? $breakpoint_1_width : us_get_option( 'blog_breakpoint_1_width' );
$breakpoint_1_cols = isset( $breakpoint_1_cols ) ? $breakpoint_1_cols : us_get_option( 'blog_breakpoint_1_cols' );
$breakpoint_1_autoplay = isset( $breakpoint_1_autoplay ) ? $breakpoint_1_autoplay : FALSE;

$breakpoint_2_width = isset( $breakpoint_2_width ) ? $breakpoint_2_width : us_get_option( 'blog_breakpoint_2_width' );
$breakpoint_2_cols = isset( $breakpoint_2_cols ) ? $breakpoint_2_cols : us_get_option( 'blog_breakpoint_2_cols' );
$breakpoint_2_autoplay = isset( $breakpoint_2_autoplay ) ? $breakpoint_2_autoplay : FALSE;

$breakpoint_3_width = isset( $breakpoint_3_width ) ? $breakpoint_3_width : us_get_option( 'blog_breakpoint_3_width' );
$breakpoint_3_cols = isset( $breakpoint_3_cols ) ? $breakpoint_3_cols : us_get_option( 'blog_breakpoint_3_cols' );
$breakpoint_3_autoplay = isset( $breakpoint_3_autoplay ) ? $breakpoint_3_autoplay : FALSE;

$carousel_arrows = isset( $carousel_arrows ) ? $carousel_arrows : FALSE;
$carousel_dots = isset( $carousel_dots ) ? $carousel_dots : FALSE;
$carousel_center = isset( $carousel_center ) ? $carousel_center : FALSE;
$carousel_autoplay = isset( $carousel_autoplay ) ? $carousel_autoplay : FALSE;
$carousel_interval = isset( $carousel_interval ) ? $carousel_interval : 3;
$carousel_slideby = isset( $carousel_slideby ) ? $carousel_slideby : FALSE;

$filter_html = isset( $filter_html ) ? $filter_html : '';

$is_widget = isset( $is_widget ) ? $is_widget : FALSE;

// Global preloader type
$preloader_type = us_get_option( 'preloader' );
if ( ! in_array( $preloader_type, us_get_preloader_numeric_types() ) ) {
	$preloader_type = 1;
}

echo '</div>';

// Output preloader for Carousel and Filter
if ( $filter_html != '' ) {
	?>
	<div class="w-grid-preloader">
		<div class="g-preloader type_<?php echo $preloader_type; ?>">
			<div></div>
		</div>
	</div>
	<?php
} elseif ( $type == 'carousel' ) {
	?>
	<div class="g-preloader type_<?php echo $preloader_type; ?>">
		<div></div>
	</div>
	<?php
}

// Output pagination for not Carousel type
if ( $wp_query->max_num_pages > 1 AND $type != 'carousel' ) {

	// Next page elements may have sliders, so we preloading the needed assets now
	if ( us_get_option( 'ajax_load_js', 0 ) == 0 ) {
		wp_enqueue_script( 'us-royalslider' );
	}

	// Add lang variable if WPML is active
	if ( class_exists( 'SitePress' ) ) {
		global $sitepress;
		if ( $sitepress->get_default_language() != $sitepress->get_current_language() ) {
			$json_data['template_vars']['lang'] = $sitepress->get_current_language();
		}
	}

	if ( $pagination == 'infinite' ) {
		$is_infinite = TRUE;
		$pagination = 'ajax';
	}
	if ( $pagination == 'regular' ) {
		the_posts_pagination(
			array(
				'mid_size' => 3,
				'before_page_number' => '<span>',
				'after_page_number' => '</span>',
			)
		);
	} elseif ( $pagination == 'ajax' ) {
		$loadmore_class = '';
		if ( $pagination_btn_style == 'btn' ) {
			$loadmore_class = ' w-btn color_primary style_solid';
		}
		?>
		<div class="g-loadmore type_<?php echo $pagination_btn_style ?>">
			<a class="g-loadmore-btn<?php echo $loadmore_class ?>" href="javascript:void(0)">
				<span><?php echo $pagination_btn_text ?></span>
			</a>
			<div class="g-preloader type_<?php echo $preloader_type; ?>">
				<div></div>
			</div>
		</div>
		<?php
	}
}

// Define and output all JSON data
$json_data = array(

	// Controller options
	'ajax_url' => admin_url( 'admin-ajax.php' ),
	'permalink_url' => get_permalink(),
	'action' => 'us_ajax_grid',
	'max_num_pages' => $wp_query->max_num_pages,
	'infinite_scroll' => ( isset( $is_infinite ) ? $is_infinite : 0 ),

	// Grid listing template variables that will be passed to this file in the next call
	'template_vars' => array(
		'query_args' => $query_args,
		'post_id' => $post_id,
		'us_grid_index' => $us_grid_index,
		'exclude_items' => $exclude_items,
		'items_offset' => $items_offset,
		'items_layout' => $items_layout,
		'type' => $type,
		'columns' => $columns,
		'img_size' => $img_size,
	),

	// Carousel settings
	'carousel_settings' => array(
		'items' => $columns,
		'nav' => intval( ! ! $carousel_arrows ),
		'dots' => intval( ! ! $carousel_dots ),
		'center' => intval( ! ! $carousel_center ),
		'autoplay' => intval( ! ! $carousel_autoplay ),
		'timeout' => intval( $carousel_interval * 1000 ),
		'autoheight' => intval( $columns == 1 ),
		'slideby' => ( $carousel_slideby ? 'page' : '1' ),
	),
	'carousel_breakpoints' => array(
		intval( $breakpoint_1_width ) => array(
			'items' => $columns,
		),
		intval( $breakpoint_2_width ) => array(
			'items' => min( intval( $breakpoint_1_cols ), $columns ),
			'autoplay' => intval( ! ! $breakpoint_1_autoplay ),
			'autoplayHoverPausev' => intval( ! ! $breakpoint_1_autoplay ),
		),
		intval( $breakpoint_3_width ) => array(
			'items' => min( intval( $breakpoint_2_cols ), $columns ),
			'autoplay' => intval( ! ! $breakpoint_2_autoplay ),
			'autoplayHoverPausev' => intval( ! ! $breakpoint_2_autoplay ),
		),
		0 => array(
			'items' => min( intval( $breakpoint_3_cols ), $columns ),
			'autoHeight' => TRUE,
			'autoplay' => intval( ! ! $breakpoint_3_autoplay ),
			'autoplayHoverPause' => intval( ! ! $breakpoint_3_autoplay ),
		),
	),
);
?>
	<div class="w-grid-json hidden"<?php echo us_pass_data_to_js( $json_data ) ?>></div>
<?php

// Output popup semantics
if ( us_arr_path( $grid_layout_settings, 'default.options.link' ) == 'popup_post' ) {

	if ( $post_type == 'post' ) {
		$show_popup_arrows = us_get_option( 'post_nav', 0 );
	} elseif ( $post_type == 'us_portfolio' ) {
		$show_popup_arrows = us_get_option( 'portfolio_nav', 0 );
	} else {
		$show_popup_arrows = TRUE;
	}

	$popup_width = trim( us_arr_path( $grid_layout_settings, 'default.options.popup_width' ) );
	if ( ! empty( $popup_width ) AND strpos( $popup_width, 'px' ) === FALSE AND strpos( $popup_width, '%' ) === FALSE ) {
		$popup_width = $popup_width . 'px';
	}
	?>
	<div class="l-popup">
		<div class="l-popup-overlay"></div>
		<div class="l-popup-wrap">
			<div class="l-popup-box">
				<div class="l-popup-box-content"<?php if ( ! empty( $popup_width ) ) {
					echo ' style="max-width: ' . esc_attr( $popup_width ) . ';"';
				} ?>>
					<div class="g-preloader type_<?php echo $preloader_type; ?>">
						<div></div>
					</div>
					<iframe class="l-popup-box-content-frame" allowfullscreen></iframe>
				</div>
			</div>
			<?php if ( $show_popup_arrows ) { ?>
				<div class="l-popup-arrow to_next" title="Next"></div>
				<div class="l-popup-arrow to_prev" title="Previous"></div>
			<?php } ?>
			<div class="l-popup-closer"></div>
		</div>
	</div>
	<?php
}

echo '</div>';