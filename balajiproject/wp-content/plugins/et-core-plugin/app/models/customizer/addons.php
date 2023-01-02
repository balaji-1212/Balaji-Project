<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}
/**
 * Load & init customizer addons
 *
 * @since   1.0.0
 * @version 1.0.0
 */

/**
 * Load & init kirki-box-model
 *
 * @since   1.0.0
 * @version 1.0.0
 */
require_once( 'addons/kirki-box-model/kirki-box-model.php' );
add_filter( 'kirki_box_model_control_url', 'etheme_kirki_box_model_control_url' );
function etheme_kirki_box_model_control_url() {
	return ET_CORE_URL . 'app/models/customizer/addons/kirki-box-model';
}

/**
 * Load & init kirki-box-shadow
 *
 * @since   1.0.0
 * @version 1.0.0
 */
require_once( 'addons/kirki-box-shadow/kirki-box-shadow.php' );
add_filter( 'kirki_box_shadow_control_url', 'etheme_kirki_box_shadow_control_url' );
function etheme_kirki_box_shadow_control_url() {
	return ET_CORE_URL . 'app/models/customizer/addons/kirki-box-shadow';
}

/**
 * Load & init kirki-wcag-tc
 *
 * @since   1.0.0
 * @version 1.0.0
 */
require_once( 'addons/kirki-wcag-tc/kirki-wcag-tc.php' );
add_filter( 'kirki_wcag_text_color_url', 'etheme_kirki_wcag_text_color_url' );
function etheme_kirki_wcag_text_color_url() {
	return ET_CORE_URL . 'app/models/customizer/addons/kirki-wcag-tc';
}

/**
 * Load lazy section class
 *
 * @since   1.0.0
 * @version 1.0.0
 */
add_action( 'customize_register', 'etheme_include_sections_and_panels' );
function etheme_include_sections_and_panels() {
	require_once( 'addons/etheme-lazy/lazy.php' );
}

/**
 * Load & init etheme-sortable-control
 *
 * @since   1.0.0
 * @version 1.0.0
 */
// require_once( 'addons/etheme-sortable-control/etheme-sortable-control.php' );
// add_filter( 'etheme_sortable_url', 'etheme_sortable_new_url' );
// function etheme_sortable_new_url() {
// 	return ET_CORE_URL . 'app/models/customizer/addons/etheme-sortable-control';
// }