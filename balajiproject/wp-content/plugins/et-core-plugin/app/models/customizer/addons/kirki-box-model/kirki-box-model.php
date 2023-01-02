<?php
/**
 * Plugin Name:   Kirki Box Control
 * Plugin URI:    https://wplemon.com/
 * Description:   Addon control for the Kirki Toolkit for WordPress.
 * Author:        Ari Stathopoulos
 * Author URI:    https://wplemon.com
 * Version:       1.0
 * Text Domain:   kirki-pro
 *
 * @package    KirkiBox
 * @category   Addon
 * @author     Ari Stathopoulos
 * @copyright  Copyright (c) 2019, Ari Stathopoulos
 * @license    https://opensource.org/licenses/MIT
 * @since      1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'kirki_box_model_register_control' ) ) {
	/**
	 * Registers the control with Kirki.
	 * 
	 * @since 1.0
	 * @param array $controls An array of controls registered with the Kirki Toolkit.
	 * @return array
	 */
	function kirki_box_model_register_control( $controls ) {
		if ( ! class_exists( 'Kirki_Box_Model_Control' ) ) {
			require_once dirname( __FILE__ ) . '/class-kirki-box-model-control.php';
		}
		$controls['kirki-box-model'] = 'Kirki_Box_Model_Control';
		return $controls;
	}
}
add_filter( 'kirki_control_types', 'kirki_box_model_register_control' );

if ( ! function_exists( 'kirki_box_model_register_control_type' ) ) {
	/**
	 * Registers the control type and make it eligible for
	 * JS templating in the Customizer.
	 * 
	 * @since 1.0
	 * @param object $wp_customize The Customizer object.
	 * @return void
	 */
	function kirki_box_model_register_control_type( $wp_customize ) {
		if ( ! class_exists( 'Kirki_Box_Model_Control' ) ) {
			require_once dirname( __FILE__ ) . '/class-kirki-box-model-control.php';
		}
		$wp_customize->register_control_type( 'Kirki_Box_Model_Control' );
	}
} 
add_action( 'customize_register', 'kirki_box_model_register_control_type' );

if ( ! function_exists( 'kirki_box_model_kirki_field_mods' ) ) {
	/**
	 * Includes the file containing modifications for the box-model field.
	 *
	 * @since 1.0
	 * @return void
	 */
	function kirki_box_model_kirki_field_mods() {
		if ( class_exists( 'Kirki' ) && ! class_exists( 'Kirki_Field_Box_Model' ) ) {
			require_once dirname( __FILE__ ) . '/class-kirki-field-box-model.php';
			if ( ! class_exists( 'Kirki_Output_Field_Box_Model' ) ) {
				require_once dirname( __FILE__ ) . '/class-kirki-output-field-box-model.php';
			}
		}
	}
}
add_action( 'init', 'kirki_box_model_kirki_field_mods' );

if ( ! function_exists( 'kirki_pro_box_model_kirki_output_control_classnames' ) ) {
	/**
	 * Adds a new class for the box-model field output.
	 *
	 * @since 1.0
	 * @param array $classnames An array of classnames depending on their field-type.
	 * @return array
	 */
	function kirki_pro_box_model_kirki_output_control_classnames( $classnames ) {
		$classnames['kirki-box-model'] = 'Kirki_Output_Field_Box_Model';
		return $classnames;
	}
}
add_filter( 'kirki_output_control_classnames', 'kirki_pro_box_model_kirki_output_control_classnames' );
