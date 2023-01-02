<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Template "Etheme Custom Fonts" for 8theme dashboard.
 *
 * @since   7.0.0
 * @version 1.0.0
 */

if ( class_exists('Etheme_Custom_Fonts') ) {
	$custom_fonts = new Etheme_Custom_Fonts();
	$custom_fonts->render();
}