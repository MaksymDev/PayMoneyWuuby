<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * The template for displaying Search Results Page
 */

$us_layout = US_Layout::instance();

get_header();

// Output Title Bar
us_load_template( 'templates/titlebar' );

// Get grid params from Theme Options
$template_vars = array(
	'items_layout' => us_get_option( 'search_layout', 'blog_classic' ),
	'type' => us_get_option( 'search_type', 'grid' ),
	'columns' => us_get_option( 'search_cols', 1 ),
	'img_size' => us_get_option( 'search_img_size', 'default' ),
	'items_gap' => us_get_option( 'search_items_gap', 5 ) . 'rem',
	'pagination' => us_get_option( 'search_pagination', 'regular' ),
);

?>
<div class="l-main">
	<div class="l-main-h i-cf">

		<main class="l-content"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemprop="mainContentOfPage"' : ''; ?>>
			<section class="l-section<?php echo ( us_get_option( 'row_height' ) == 'small' ) ? ' height_small' : ''; ?>">
				<div class="l-section-h i-cf">

					<?php do_action( 'us_before_search' ) ?>

					<?php us_load_template( 'templates/us_grid/listing', $template_vars ) ?>

					<?php do_action( 'us_after_search' ) ?>

				</div>
			</section>
		</main>

		<?php us_load_template( 'templates/sidebar' ) ?>

	</div>
</div>

<?php get_footer() ?>
