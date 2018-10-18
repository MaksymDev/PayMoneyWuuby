<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * Form nonce
 *
 * @var $action      string Nonce Action
 * @var $name        string Nonce Name
 */

$action = isset( $action ) ? $action : '';
$name = isset( $name ) ? $name : '';
if ( ! empty( $action ) AND ! empty( $name ) ) {
	wp_nonce_field( $action, $name );
}
