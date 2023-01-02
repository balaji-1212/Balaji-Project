<?php


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'etheme_kirki_box_shadow_register_control' ) ) {
	/**
	 * Registers the control with Kirki.
	 * 
	 * @since 1.0
	 * @param array $controls An array of controls registered with the Kirki Toolkit.
	 * @return array
	 */
	function etheme_kirki_box_shadow_register_control( $controls ) {
		if ( ! class_exists( 'Etheme_Control_Sortable' ) ) {
			require_once dirname( __FILE__ ) . '/class-etheme-sortable-control.php';
		}
		$controls['etheme-sortable'] = 'Etheme_Control_Sortable';
		return $controls;
	}
}
add_filter( 'kirki_control_types', 'etheme_kirki_box_shadow_register_control' );

if ( ! function_exists( 'etheme_kirki_box_shadow_register_control_type' ) ) {
	/**
	 * Registers the control type and make it eligible for
	 * JS templating in the Customizer.
	 * 
	 * @since 1.0
	 * @param object $wp_customize The Customizer object.
	 * @return void
	 */
	function etheme_kirki_box_shadow_register_control_type( $wp_customize ) {
		if ( ! class_exists( 'Etheme_Control_Sortable' ) ) {
			require_once dirname( __FILE__ ) . '/class-etheme-sortable-control.php';
		}
		$wp_customize->register_control_type( 'Etheme_Control_Sortable' );
	}
} 
add_action( 'customize_register', 'etheme_kirki_box_shadow_register_control_type' );





if ( ! function_exists( 'etheme_sortable_kirki_field_mods' ) ) {
	/**
	 * Includes the file containing modifications for the etheme-sortable field.
	 *
	 * @since 1.0
	 * @return void
	 */
	function etheme_sortable_kirki_field_mods() {
		if ( class_exists( 'Kirki' ) && ! class_exists( 'Etheme_Control_Sortable' ) ) {
			if ( ! class_exists( 'Etheme_Output_Field_Sortable' ) ) {
				require_once dirname( __FILE__ ) . '/class-etheme-output-field-etheme-sortable.php';
			}
		}
	}
}
add_action( 'init', 'etheme_sortable_kirki_field_mods' );

if ( ! function_exists( 'etheme_sortable_kirki_output_control_classnames' ) ) {
	/**
	 * Adds a new class for the etheme-sortable field output.
	 *
	 * @since 1.0
	 * @param array $classnames An array of classnames depending on their field-type.
	 * @return array
	 */
	function etheme_sortable_kirki_output_control_classnames( $classnames ) {
		$classnames['etheme-sortable'] = 'Etheme_Output_Field_Sortable';
		return $classnames;
	}
}
add_filter( 'kirki_output_control_classnames', 'etheme_sortable_kirki_output_control_classnames' );