<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * The template for displaying the 404 page
 */

$page_404 = get_post( us_get_option( 'page_404' ) );

// Output specific page
if ( $page_404 ) {
	if ( class_exists( 'SitePress' ) ) {
		$page_404 = get_post( apply_filters( 'wpml_object_id', $page_404->ID, 'page', TRUE ) );
	}

	get_header();

	us_load_template( 'templates/titlebar' );
	?>
	<div class="l-main">
		<div class="l-main-h i-cf">
			<main class="l-content"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemprop="mainContentOfPage"' : ''; ?>>

				<?php
				do_action( 'us_before_page' );

				$the_content = apply_filters( 'the_content', $page_404->post_content );
				echo $the_content;
		
				do_action( 'us_after_page' );
				?>

			</main>

			<?php us_load_template( 'templates/sidebar' ) ?>

		</div>
	</div>
	<?php
	get_footer();

// Output predefined layout
} else {
	$us_layout = US_Layout::instance();
	$us_layout->sidebar_pos = 'none';

	get_header();
	?>
	<div class="l-main">
		<div class="l-main-h i-cf">
			<div class="l-content">
				<section class="l-section">
					<div class="l-section-h i-cf">

						<?php do_action( 'us_before_404' ) ?>

						<div class="page-404">
							<?php
							$the_content = '<h1>' . us_translate( 'Page not found' ) . '</h1>';
							$the_content .= '<p>' . __( 'The link you followed may be broken, or the page may have been removed.', 'us' ) . '</p>';
							echo apply_filters( 'us_404_content', $the_content );
							?>
						</div>

						<?php do_action( 'us_after_404' ) ?>

					</div>
				</section>
			</div>
		</div>
	</div>
	<?php
	get_footer();
}
