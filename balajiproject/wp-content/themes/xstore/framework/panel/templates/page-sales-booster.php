<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Template "Etheme Custom Fonts" for 8theme dashboard.
 *
 * @since   7.0.0
 * @version 1.0.0
 */

if ( class_exists('Etheme_Sales_Booster_Backend') ) {
	$dir_uri = get_template_directory_uri();
	$icons_type = ( etheme_get_option('bold_icons', 0) ) ? 'bold' : 'light';
	wp_register_style( 'xstore-icons-font', false );
	wp_enqueue_style( 'xstore-icons-font' );
	wp_add_inline_style( 'xstore-icons-font',
		"@font-face {
		  font-family: 'xstore-icons';
		  src:
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".ttf') format('truetype'),
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".woff2') format('woff2'),
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".woff') format('woff'),
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".svg#xstore-icons') format('svg');
		  font-weight: normal;
		  font-style: normal;
		}"
	);
	wp_enqueue_style( 'xstore-icons-font-style', $dir_uri . '/css/xstore-icons.css' );
	$Etheme_Sales_Booster_Backend = new Etheme_Sales_Booster_Backend();
	$Etheme_Sales_Booster_Backend->sales_booster_page();
}