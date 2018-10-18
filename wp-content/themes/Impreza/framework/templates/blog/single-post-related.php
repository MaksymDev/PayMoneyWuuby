<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Outputs Similar Posts with the same tags or categories
 *
 * @action Before the template: 'us_before_template:templates/blog/single-post-related'
 * @action After the template: 'us_after_template:templates/blog/single-post-related'
 */

$current_post_id = get_the_ID();
$title = us_get_option( 'post_related_title' );
$output_type = us_get_option( 'post_related_type' );
$query_args = array(
	'post_type' => 'post',
	'ignore_sticky_posts' => 1,
	'paged' => 1,
	'post__not_in' => array( $current_post_id ),
	'showposts' => us_get_option( 'post_related_quantity', 3 ),
);

if ( $output_type == 'category' ) {
	if ( has_category() ) {
		$category_ids = wp_get_post_categories( $current_post_id, array( 'fields' => 'ids' ) );
		$query_args['category__in'] = $category_ids;
	} else {
		return;
	}
} else {
	if ( has_tag() ) {
		$tag_ids = wp_get_post_tags( $current_post_id, array( 'fields' => 'ids' ) );
		$query_args['tag__in'] = $tag_ids;
	} else {
		return;
	}
}

// Set posts order
$orderby = us_get_option( 'post_related_orderby', 'rand' );
$orderby_translate = array(
	'date' => 'date',
	'date_asc' => 'date',
	'modified' => 'modified',
	'modified_asc' => 'modified_asc',
	'alpha' => 'title',
	'rand' => 'rand',
);
$order_translate = array(
	'date' => 'DESC',
	'date_asc' => 'ASC',
	'modified' => 'DESC',
	'modified_asc' => 'ASC',
	'alpha' => 'ASC',
	'rand' => '',
);
if ( $orderby == 'rand' ) {
	$query_args['orderby'] = 'rand';
} else {
	$query_args['orderby'] = array(
		$orderby_translate[$orderby] => $order_translate[$orderby],
	);
}

// Overload global wp_query to use it in the inner templates
us_open_wp_query_context();
global $wp_query;
$wp_query = new WP_Query( $query_args );

if ( $wp_query->have_posts() ) {
	$template_vars = array(
		'type' => 'grid',
		'items_layout' => us_get_option( 'post_related_layout', 'blog_classic' ),
		'img_size' => us_get_option( 'post_related_img_size', 'default' ),
		'columns' => us_get_option( 'post_related_cols', 3 ),
		'items_gap' => us_get_option( 'post_related_items_gap', 0 ) . 'rem',
		'pagination' => 'none',
	);
	?>
	<section class="l-section for_related">
		<div class="l-section-h i-cf">
			<h4><?php echo strip_tags( $title, '<br>' ) ?></h4>
			<?php us_load_template( 'templates/us_grid/listing', $template_vars ) ?>
		</div>
	</section><?php
}

us_close_wp_query_context();
