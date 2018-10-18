<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Outputs page's Title Bar
 *
 * (!) Should be called after the current $wp_query is already defined
 *
 * @action Before the template: 'us_before_template:templates/titlebar'
 * @action After the template: 'us_after_template:templates/titlebar'
 * @filter Template variables: 'us_template_vars:templates/titlebar'
 */

$titlebar_id = us_get_page_area_id( 'titlebar' );

// Output content of Page Block (us_page_block) post
$titlebar_content = '';
if ( $titlebar_id != '' ) {

	$titlebar = get_post( (int) $titlebar_id );

	us_open_wp_query_context();
	if ( $titlebar ) {
		$translated_titlebar_id = apply_filters( 'wpml_object_id', $titlebar->ID, 'us_page_block', TRUE );
		if ( $translated_titlebar_id != $titlebar->ID ) {
			$titlebar = get_post( $translated_titlebar_id );
		}
		global $wp_query, $vc_manager, $us_is_in_titlebar, $us_titlebar_id;
		$us_is_in_titlebar = TRUE;
		$us_titlebar_id = $translated_titlebar_id;
		$wp_query = new WP_Query(
			array(
				'p' => $translated_titlebar_id,
				'post_type' => 'any',
			)
		);
		if ( ! empty( $vc_manager ) AND is_object( $vc_manager ) ) {
			$vc_manager->vc()->addPageCustomCss( $translated_titlebar_id );
			$vc_manager->vc()->addShortcodesCustomCss( $translated_titlebar_id );
		}
		$titlebar_content = $titlebar->post_content;
	}
	us_close_wp_query_context();

	// Apply filters to Page Block content and echoing it ouside of us_open_wp_query_context,
	// so all WP widgets (like WP Nav Menu) would work as they should
	echo apply_filters( 'us_page_block_the_content', $titlebar_content );

	$us_is_in_titlebar = FALSE;
}
