<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * The template for displaying Archive Pages
 */

$us_layout = US_Layout::instance();

get_header();

// Output Title Bar
$titlebar_vars = array();
if ( is_category() OR is_tag() OR is_tax() ) {
	$term = get_queried_object();
	if ( $term ) {
		$taxonomy = $term->taxonomy;
		$term = $term->term_id;
	}
	$titlebar_vars['subtitle'] = nl2br( get_term_field( 'description', $term, $taxonomy, 'edit' ) );
}
us_load_template( 'templates/titlebar', $titlebar_vars );

// Get grid params from Theme Options
$template_vars = array(
	'items_layout' => us_get_option( 'archive_layout', 'blog_classic' ),
	'type' => us_get_option( 'archive_type', 'grid' ),
	'columns' => us_get_option( 'archive_cols', 1 ),
	'img_size' => us_get_option( 'archive_img_size', 'default' ),
	'items_gap' => us_get_option( 'archive_items_gap', 5 ) . 'rem',
	'pagination' => us_get_option( 'archive_pagination', 'regular' ),
);

?>
<div class="l-main">
	<div class="l-main-h i-cf">

		<main class="l-content"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemprop="mainContentOfPage"' : ''; ?>>
			<section class="l-section<?php echo ( us_get_option( 'row_height' ) == 'small' ) ? ' height_small' : ''; ?>">
				<div class="l-section-h i-cf">

					<?php do_action( 'us_before_archive' ) ?>

					<?php us_load_template( 'templates/us_grid/listing', $template_vars ) ?>

					<?php do_action( 'us_after_archive' ) ?>

				</div>
			</section>
		</main>

		<?php us_load_template( 'templates/sidebar' ) ?>

	</div>
</div>

<?php get_footer() ?>
