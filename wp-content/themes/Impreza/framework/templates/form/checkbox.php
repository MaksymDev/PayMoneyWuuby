<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output a checkbox
 *
 * @var $name        string Field name
 * @var $title       string Field title
 * @var $description string Field description
 * @var $id          string Field id
 * @var $checked     Bool checked
 * @var $classes     string Additional field classes
 *
 * @action Before the template: 'us_before_template:templates/form/textfield'
 * @action After the template: 'us_after_template:templates/form/textfield'
 * @filter Template variables: 'us_template_vars:templates/form/textfield'
 */

$name = isset( $name ) ? $name : '';
$checked = ( isset( $checked ) AND $checked ) ? 'checked' : '';

// Do not show this field if checkbox title is empty
if ( ! isset( $title ) OR empty( $title ) ) {
	return;
}

if ( ! isset( $id ) ) {
	global $us_form_index;
	$id = 'us_form_' . $us_form_index . '_' . $name;
}
$classes = ( isset( $classes ) AND ! empty( $classes ) ) ? ( ' ' . $classes ) : '';

// Always required field
$classes .= ' required';
$required_atts = 'data-required="true" aria-required="true"';

?>
<div class="w-form-row for_checkbox<?php echo $classes ?>">
	<div class="w-form-row-field">
		<?php do_action( 'us_form_field_start', $vars ) ?>
		<label for="<?php echo $id ?>">
			<input class="screen-reader-text" type="checkbox" name="<?php echo esc_attr( $name ) ?>" <?php echo esc_attr( $checked ) ?> id="<?php echo $id ?>" <?php echo $required_atts ?>/>
			<i></i>
			<span><?php echo strip_tags( $title, '<a><br><strong>' ) ?></span>
		</label>
		<?php do_action( 'us_form_field_end', $vars ) ?>
	</div>
	<div class="w-form-row-state"></div>
	<?php if ( isset( $description ) AND ! empty( $description ) ): ?>
		<div class="w-form-row-description"><?php echo $description ?></div>
	<?php endif; ?>
</div>
