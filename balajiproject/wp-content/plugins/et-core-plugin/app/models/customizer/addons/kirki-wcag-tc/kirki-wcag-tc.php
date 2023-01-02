<?php
/**
 * Plugin Name:   Kirki Accessible-Text Colorpicker
 * Plugin URI:    https://wplemon.com/downloads/kirki-wcag-text-color/
 * Description:   Addon control for the Kirki Toolkit for WordPress. Adds a new colorpicker control to the WordPress Customizer, allowing developers to build colorpickers that automatically suggest accessible text colors depending on the value of a background color.
 * Author:        Ari Stathopoulos
 * Author URI:    https://wplemon.com
 * Version:       1.1.1
 * Text Domain:   kirki-pro
 *
 * @package    KirkiAccessibleColorpicker
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

if ( ! function_exists( 'kirki_wcag_text_color_register_control' ) ) {
	/**
	 * Registers the control with Kirki.
	 * 
	 * @since 1.0
	 * @param array $controls An array of controls registered with the Kirki Toolkit.
	 * @return array
	 */
	function kirki_wcag_text_color_register_control( $controls ) {
		if ( ! class_exists( 'Kirki_WCAG_Text_Color' ) ) {
			require_once dirname( __FILE__ ) . '/class-kirki-wcag-text-color.php';
		}
		$controls['kirki-wcag-tc'] = 'Kirki_WCAG_Text_Color';
		return $controls;
	}
}
add_filter( 'kirki_control_types', 'kirki_wcag_text_color_register_control' );

if ( ! function_exists( 'kirki_wcag_text_color_register_control_type' ) ) {
	/**
	 * Registers the control type and make it eligible for
	 * JS templating in the Customizer.
	 * 
	 * @since 1.0
	 * @param object $wp_customize The Customizer object.
	 * @return void
	 */
	function kirki_wcag_text_color_register_control_type( $wp_customize ) {
		if ( ! class_exists( 'Kirki_WCAG_Text_Color' ) ) {
			require_once dirname( __FILE__ ) . '/class-kirki-wcag-text-color.php';
		}
		$wp_customize->register_control_type( 'Kirki_WCAG_Text_Color' );
	}
} 
add_action( 'customize_register', 'kirki_wcag_text_color_register_control_type' );
