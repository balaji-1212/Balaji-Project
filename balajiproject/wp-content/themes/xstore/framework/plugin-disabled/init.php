<?php
/**
 * Init theme part while core plugin is disabled.
 *
 * @since   7.1.0
 * @version 1.0.0
 */
add_action( 'wp_enqueue_scripts', function (){
	$theme = wp_get_theme();
	wp_enqueue_style("plugin-disabled", get_template_directory_uri().'/framework/plugin-disabled/css/style.css', array(), $theme->version);
}, 150 );

add_action( 'etheme_header', function (){
	return get_template_part( 'framework/plugin-disabled/templates/header' );
} );

//etheme_copyrights_content