<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output single USOF Field
 *
 * Multiple selector
 *
 * @var $name   string Field name
 * @var $id     string Field ID
 * @var $field  array Field options
 * @var $values array Set of values for the current and relevant fields
 */

if ( isset( $field['place_if'] ) AND ! $field['place_if'] ) {
	return;
}
if ( ! isset( $field['type'] ) ) {
	if ( WP_DEBUG ) {
		wp_die( $name . ' has no defined type' );
	}

	return;
}
$show_field = ( ! isset( $field['show_if'] ) OR usof_execute_show_if( $field['show_if'], $values ) );

// Options Wrapper
if ( $field['type'] == 'wrapper_start' ) {
	$row_classes = '';
	if ( isset( $field['classes'] ) AND ! empty( $field['classes'] ) ) {
		$row_classes .= ' ' . $field['classes'];
	}
	echo '<div class="usof-form-wrapper ' . $name . $row_classes . '" data-name="' . $name . '" data-id="' . $id . '" ';
	echo 'style="display: ' . ( $show_field ? 'block' : 'none' ) . '">';
	if ( isset( $field['title'] ) AND ! empty( $field['title'] ) ) {
		echo '<div class="usof-form-wrapper-title">' . $field['title'] . '</div>';
	}
	echo '<div class="usof-form-wrapper-content">';
	if ( isset( $field['show_if'] ) AND is_array( $field['show_if'] ) AND ! empty( $field['show_if'] ) ) {
		// Showing conditions
		echo '<div class="usof-form-wrapper-showif hidden"' . us_pass_data_to_js( $field['show_if'] ) . '></div>';
	}

	return;
} elseif ( $field['type'] == 'wrapper_end' ) {
	echo '</div></div>';

	return;
}

$field['std'] = isset( $field['std'] ) ? $field['std'] : NULL;
$value = isset( $values[$name] ) ? $values[$name] : $field['std'];

// Options Group
if ( $field['type'] == 'group' ) {
	global $usof_options;

	$group_classes = ( ! empty( $field['classes'] ) ) ? ' ' . $field['classes'] : '' ;
	if ( isset( $field[ 'is_accordion' ] ) AND $field[ 'is_accordion' ] ) {
		$group_classes .= ' type_accordion';
	} else {
		$group_classes .= ' type_simple';
	}
	if ( isset( $field[ 'is_sortable' ] ) AND $field[ 'is_sortable' ] ) {
		$group_classes .= ' sortable';
	}
	echo '<div class="usof-form-group' . $group_classes . '" data-name="' . $name . '"';
	if ( ! empty( $field['title'] ) ) {
		echo ' data-params_title="' . rawurlencode( $field['title'] ) . '"';
	}
	echo 'style="display: ' . ( $show_field ? 'block' : 'none' ) . '">';
	echo '<div class="usof-form-group-prototype hidden">';
	us_load_template( 'vendor/usof/templates/fields/group_param', array(
		'params_values' => array(),
		'field' => $field,
	) );
	echo '</div>';

	if ( is_array( $value ) AND count( $value ) > 0 ) {
		foreach ( $value as $index => $params_values ) {
			us_load_template( 'vendor/usof/templates/fields/group_param', array(
				'params_values' => $params_values,
				'name' => $name,
				'field' => $field,
			) );
		}
	}
	echo '<span class="usof-form-group-add" title="' . us_translate( 'Add' ) . '"><span class="usof-preloader"></span></span>';
	$translations = array(
		'deleteConfirm' => __( 'Are you sure want to delete the element?', 'us' ),
	);
	echo '<span class="usof-form-group-translations hidden"' . us_pass_data_to_js( $translations ) . '></span>';
	if ( isset( $field['show_if'] ) AND is_array( $field['show_if'] ) AND ! empty( $field['show_if'] ) ) {
		// Showing conditions
		echo '<div class="usof-form-row-showif hidden"' . us_pass_data_to_js( $field['show_if'] ) . '></div>';
	}
	echo '</div>';

	return;
}

$row_classes = ' type_' . $field['type'];
if ( $field['type'] != 'message' AND ( ! isset( $field['classes'] ) OR strpos( $field['classes'], 'desc_' ) === FALSE ) ) {
	$row_classes .= ' desc_3';
}
if ( isset( $field['classes'] ) AND ! empty( $field['classes'] ) ) {
	$row_classes .= ' ' . $field['classes'];
}
echo '<div class="usof-form-row' . $row_classes . '" data-name="' . $name . '" data-id="' . $id . '" ';
echo 'style="display: ' . ( $show_field ? 'block' : 'none' ) . '">';
if ( isset( $field['title'] ) AND ! empty( $field['title'] ) ) {
	echo '<div class="usof-form-row-title"><span>' . $field['title'] . '</span>';
	if ( isset( $field['description'] ) AND ! empty( $field['description'] ) AND ( ! empty( $field['classes'] ) AND strpos( $field['classes'], 'desc_4' ) !== FALSE ) ) {
		echo '<div class="usof-form-row-desc">';
		echo '<div class="usof-form-row-desc-icon"></div>';
		echo '<div class="usof-form-row-desc-text">' . $field['description'] . '</div>';
		echo '</div>';
	}
	echo '</div>';
}
echo '<div class="usof-form-row-field"><div class="usof-form-row-control">';
// Including the field control itself
us_load_template(
	'vendor/usof/templates/fields/' . $field['type'], array(
		'name' => $name,
		'id' => $id,
		'field' => $field,
		'value' => $value,
		'is_metabox' => ( isset( $is_metabox ) ) ? $is_metabox : FALSE,
	)
);
echo '</div>';
if ( isset( $field['description'] ) AND ! empty( $field['description'] ) AND ( empty( $field['classes'] ) OR strpos( $field['classes'], 'desc_4' ) === FALSE ) ) {
	echo '<div class="usof-form-row-desc">';
	echo '<div class="usof-form-row-desc-icon"></div>';
	echo '<div class="usof-form-row-desc-text">' . $field['description'] . '</div>';
	echo '</div>';
}
echo '<div class="usof-form-row-state"></div>';
echo '</div>'; // .usof-form-row-field
if ( isset( $field['show_if'] ) AND is_array( $field['show_if'] ) AND ! empty( $field['show_if'] ) ) {
	// Showing conditions
	echo '<div class="usof-form-row-showif"' . us_pass_data_to_js( $field['show_if'] ) . '></div>';
}
echo '</div>';
