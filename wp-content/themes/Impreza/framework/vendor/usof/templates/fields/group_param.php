<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Theme Options Field: Group
 *
 * Grouped options
 *
 * @var   $field array Group options
 * @var   $params_values array Group values
 *
 */

$result_html = '<div class="usof-form-group-item">';
if ( ! empty( $field['title'] ) ) {
	$param_title = $field['title'];
	foreach ( $field['params'] as $param_name => $param ) {
	if ( strpos( $param_title, '{{' . $param_name . '}}' ) !== false ) {
			$param_value = isset( $params_values[$param_name] ) ? $params_values[$param_name] : $field['params'][$param_name]['std'];
			$param_title = str_replace( '{{' . $param_name . '}}', $param_value, $param_title );
		}
	}
	$result_html .= '<div class="usof-form-group-item-title">' . $param_title . '</div>';
}
$param_content_styles = '';
if ( isset( $field['is_accordion'] ) AND $field['is_accordion'] ) {
	$param_content_styles = ' style="display: none;"';
}
$result_html .= '<div class="usof-form-group-item-content"' . $param_content_styles . '>';
ob_start();
foreach ( $field['params'] as $param_name => $param ) {
	us_load_template(
		'vendor/usof/templates/field', array(
			'name' => $param_name,
			'id' => 'usof_' . $param_name,
			'field' => $param,
			'values' => $params_values,
		)
	);
}
$result_html .= ob_get_clean();
$result_html .= '</div>';
$result_html .= '<div class="usof-form-group-item-controls">';
if ( isset( $field['is_sortable'] ) AND $field['is_sortable'] ) {
	$result_html .= '<div class="usof-control-move" title="' . us_translate( 'Move' ) . '"></div>';
}
$result_html .= '<div class="usof-control-delete" title="' . us_translate( 'Delete' ) . '"></div>';
$result_html .= '</div></div>';

echo $result_html;
