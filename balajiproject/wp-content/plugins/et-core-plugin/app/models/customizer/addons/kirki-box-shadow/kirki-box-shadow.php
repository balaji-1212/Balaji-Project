<?php
/**
 * Plugin Name:   Kirki Box Control
 * Plugin URI:    https://wplemon.com/
 * Description:   Addon control for the Kirki Toolkit for WordPress.
 * Author:        Ari Stathopoulos
 * Author URI:    https://wplemon.com
 * Version:       1.0.1
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

if ( ! function_exists( 'kirki_box_shadow_register_control' ) ) {
	/**
	 * Registers the control with Kirki.
	 * 
	 * @since 1.0
	 * @param array $controls An array of controls registered with the Kirki Toolkit.
	 * @return array
	 */
	function kirki_box_shadow_register_control( $controls ) {
		if ( ! class_exists( 'Kirki_Box_Shadow_Control' ) ) {
			require_once dirname( __FILE__ ) . '/class-kirki-box-shadow-control.php';
		}
		$controls['kirki-box-shadow'] = 'Kirki_Box_Shadow_Control';
		return $controls;
	}
}
add_filter( 'kirki_control_types', 'kirki_box_shadow_register_control' );

if ( ! function_exists( 'kirki_box_shadow_register_control_type' ) ) {
	/**
	 * Registers the control type and make it eligible for
	 * JS templating in the Customizer.
	 * 
	 * @since 1.0
	 * @param object $wp_customize The Customizer object.
	 * @return void
	 */
	function kirki_box_shadow_register_control_type( $wp_customize ) {
		if ( ! class_exists( 'Kirki_Box_Shadow_Control' ) ) {
			require_once dirname( __FILE__ ) . '/class-kirki-box-shadow-control.php';
		}
		$wp_customize->register_control_type( 'Kirki_Box_Shadow_Control' );
	}
} 
add_action( 'customize_register', 'kirki_box_shadow_register_control_type' );
