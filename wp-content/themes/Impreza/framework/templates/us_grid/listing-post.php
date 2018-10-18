<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output one post from Grid listing.
 *
 * (!) Should be called in WP_Query fetching loop only.
 * @link   https://codex.wordpress.org/Class_Reference/WP_Query#Standard_Loop
 *
 * @var $post_classes string CSS classes
 *
 * @action Before the template: 'us_before_template:templates/grid/listing-post'
 * @action After the template: 'us_after_template:templates/grid/listing-post'
 * @filter Template variables: 'us_template_vars:templates/grid/listing-post'
 */

global $us_grid_listing_post_atts;
$grid_layout_settings = $us_grid_listing_post_atts['grid_layout_settings'];
$type = $us_grid_listing_post_atts['type'];
$is_widget = $us_grid_listing_post_atts['is_widget'];

$post_classes = 'w-grid-item' . ( ( isset( $post_classes ) AND ! empty( $post_classes ) ) ? ' ' . $post_classes : '' );

// Aspect ratio class
if ( us_arr_path( $grid_layout_settings, 'default.options.ratio' ) ) {
	$post_classes .= ' ratio_' . us_arr_path( $grid_layout_settings, 'default.options.ratio' );
}

// Size class for Portfolio Pages
if ( $type != 'carousel' AND ! $is_widget AND usof_meta( 'us_tile_size' ) != '' ) {
	$post_classes .= ' size_' . usof_meta( 'us_tile_size' );
}

// Custom colors for Portfolio Pages
$inline_css = us_prepare_inline_css(
	array(
		'background-color' => usof_meta( 'us_tile_bg_color' ),
		'color' => usof_meta( 'us_tile_text_color' ),
	)
);

// Generate Overriding Link semantics to the whole grid item
$link_url = $link_atts = '';
$link = us_arr_path( $grid_layout_settings, 'default.options.link' );
if ( $link == 'post' OR $link == 'popup_post' ) {
	// Force custom link for Portfolio pages
	$portfolio_custom_link = json_decode( usof_meta( 'us_tile_link' ), TRUE );
	if ( $portfolio_custom_link AND ! empty( $portfolio_custom_link['url'] ) ) {
		$link_url = $portfolio_custom_link['url'];
		if ( ! empty( $portfolio_custom_link['target'] ) ) {
			$link_atts .= ' target="_blank"';
		// If link to an image, open it in a popup
		} elseif ( preg_match( "/\.(bmp|gif|jpeg|jpg|png)$/i", $link_url ) ) {
			$link_atts .= ' ref="magnificPopupGrid" title="' . esc_attr( strip_tags( get_the_title() ) ). '"';
		}
		$post_classes .= ' custom-link';
	} else {
		$link_url = apply_filters( 'the_permalink', get_permalink() );
		$link_atts .= ' rel="bookmark"';
	}
	// Force opening in a new tab for "Link" post format
	if ( get_post_format() == 'link' ) {
		$link_atts .= ' target="_blank"';
	}
} elseif ( $link == 'popup_post_image' ) {
	$tnail_id = get_post_thumbnail_id();
	$link_url = $tnail_id ? wp_get_attachment_image_src( $tnail_id, 'full' ) : FALSE;
	if ( $link_url ) {
		$link_url = $link_url[0];
		$link_atts .= ' ref="magnificPopupGrid" title="' . esc_attr( strip_tags( get_the_title() ) ). '"';
	}
}
$link_atts .= ' aria-label="' . esc_attr( strip_tags( get_the_title() ) ) . '"'; // needed for accessibility support

?>
<article <?php post_class( $post_classes ) ?> data-id="<?php the_ID() ?>">
	<div class="w-grid-item-h"<?php echo $inline_css ?>>
		<?php if ( $link_url ): ?>
			<a class="w-grid-item-anchor" href="<?php echo esc_url( $link_url ) ?>"<?php echo $link_atts ?>></a>
		<?php endif; ?>
		<?php us_output_builder_elms( $grid_layout_settings, 'default', 'middle_center', 'grid' ); ?>
	</div>
</article>
<?php
