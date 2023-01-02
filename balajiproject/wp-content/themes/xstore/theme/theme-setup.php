<?php  if ( ! defined('ABSPATH')) exit('No direct script access allowed');

define('ETHEME_THEME_NAME', 'XStore');
define('ETHEME_THEME_SLUG', 'xstore');

// **********************************************************************// 
// ! Specific functions only for this theme
// **********************************************************************//
if(!function_exists('etheme_theme_setup')) {

    add_action('after_setup_theme', 'etheme_theme_setup', 1);
    add_theme_support( 'woocommerce' );

    // ! Add support for woocommerce v3.0
    // we need only zoom part
    add_theme_support( 'wc-product-gallery-zoom' );
    // ! Default theme support
    //add_theme_support( 'wc-product-gallery-lightbox' );
    //add_theme_support( 'wc-product-gallery-slider' );

    function etheme_theme_setup(){
        add_theme_support( 'post-formats', array( 'video', 'quote', 'gallery' ) );
        // @todo check the questions from clients
//        add_theme_support( 'post-thumbnails', array('post', 'page', 'product', 'etheme_portfolio') );
	    add_theme_support( 'post-thumbnails' );
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'title-tag' );

        // customizer quick edit widgets 
        add_theme_support( 'customize-selective-refresh-widgets' );

        // header-footer-elementor plugin
        // https://github.com/Nikschavan/header-footer-elementor/
        // https://wordpress.org/plugins/header-footer-elementor/
        add_theme_support( 'header-footer-elementor' );
    }
}

// **********************************************************************// 
// ! Add header-footer-elementor compatibility
// **********************************************************************// 
add_action('wp', function() {
    if ( function_exists( 'hfe_render_header' ) ){
        if ( hfe_header_enabled() ) {
            remove_all_actions('etheme_header');
            remove_all_actions('etheme_header_mobile');
            add_action( 'etheme_header', 'hfe_render_header', 10 );
        }
        if ( hfe_footer_enabled() ) {
            remove_all_actions('etheme_footer');
            add_action( 'etheme_footer', 'hfe_render_footer', 10 );
        }
        if ( hfe_is_before_footer_enabled() ) {
            remove_all_actions('etheme_prefooter');
            add_action( 'etheme_prefooter', 'hfe_render_before_footer', 10 );
        }
    }
}, 100);

add_action('init', function () {
	// **********************************************************************//
	// ! add sizes for LQIP
	// **********************************************************************//
	$cropping = get_option( 'woocommerce_thumbnail_cropping', '1:1' );
	
	if ( 'uncropped' === $cropping ) {
		add_image_size( 'etheme-woocommerce-nimi', 10, 10 );
	} elseif ( 'custom' === $cropping ) {
		$width          = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_width', '4' ) );
		$height         = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_height', '3' ) );
		add_image_size( 'etheme-woocommerce-nimi', $width, $height );
	} else {
		$cropping_split = explode( ':', $cropping );
		$width          = max( 1, current( $cropping_split ) );
		$height         = max( 1, end( $cropping_split ) );
		add_image_size( 'etheme-woocommerce-nimi', $width, $height );
	}
	
	add_image_size( 'etheme-nimi', 10, 10 );
});