<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Outputs page's Sidebar
 */

$sidebar_id = us_get_page_area_id( 'sidebar' );

// Output the content
if ( $sidebar_id != '' ) {
	?>
	<aside class="l-sidebar <?php echo esc_attr( $sidebar_id ) ?>"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemscope itemtype="https://schema.org/WPSideBar"' : ''; ?>>
		<?php dynamic_sidebar( $sidebar_id ); ?>
	</aside>
	<?php
}
