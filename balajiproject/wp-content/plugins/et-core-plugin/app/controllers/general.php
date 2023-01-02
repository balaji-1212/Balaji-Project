<?php
namespace ETC\App\Controllers;

use ETC\App\Controllers\Base_Controller;

/**
 * Import controller.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controller
 */
class General extends Base_Controller {

	function hooks() {
		// Allow HTML in term (category, tag) descriptions
		foreach ( array( 'pre_term_description' ) as $filter ) {
			remove_filter( $filter, 'wp_filter_kses' );
		}

		foreach ( array( 'term_description' ) as $filter ) {
			remove_filter( $filter, 'wp_kses_data' );
		}

		add_filter( 'style_loader_src', array( $this, 'etheme_remove_cssjs_ver' ), 10, 2 );
		add_filter( 'script_loader_src', array( $this, 'etheme_remove_cssjs_ver' ), 10, 2 );
		add_action( 'init', array( $this, 'etheme_disable_emojis' ) );
		add_action( 'init', array( $this, 'etheme_disable_rest_api' ), 999 );
		add_action( 'init', array( $this, 'etheme_disable_embeds' ), 9999 );


		add_filter( 'jpeg_quality', array( $this, 'set_jpeg_quality' ) );
		add_filter( 'wp_editor_set_quality', array( $this, 'set_jpeg_quality' ) );
		add_filter( 'big_image_size_threshold', array( $this, 'set_big_image_size_threshold' ) );
	}

	function etheme_remove_cssjs_ver( $src ) {
		if ( function_exists( 'etheme_get_option' ) && etheme_get_option( 'cssjs_ver', 0 ) ) {
			
            // ! Do not do it for revslider and essential-grid.
			if ( strpos( $src, 'revslider' ) || strpos( $src, 'essential-grid' ) ) return $src;

			if( strpos( $src, '?ver=' ) ) $src = remove_query_arg( 'ver', $src );
		}
		return $src;   
	}

	function etheme_disable_emojis() {
		if ( function_exists( 'etheme_get_option' ) && etheme_get_option( 'disable_emoji', 0 ) ) {
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		}
	}
	
	function etheme_disable_embeds() {
		if ( get_theme_mod( 'disable_embeds', 1 ) ) {

			// Turn off oEmbed auto discovery.
			add_filter( 'embed_oembed_discover', '__return_false' );
			
			// Don't filter oEmbed results.
			remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
			
			// Remove oEmbed discovery links.
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
			
			// Remove oEmbed-specific JavaScript from the front-end and back-end.
			remove_action( 'wp_head', 'wp_oembed_add_host_js' );
			add_filter( 'tiny_mce_plugins', array( $this, 'disable_embeds_tiny_mce_plugin' ) );
			
			// Remove all embeds rewrite rules.
			add_filter( 'rewrite_rules_array', array( $this, 'disable_embeds_rewrites' ) );
			
			// Remove filter of the oEmbed result before any HTTP requests are made.
			remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
			
		}
	}

	function etheme_disable_rest_api(){
		if ( get_theme_mod( 'disable_rest_api', 0 ) ){
			// Remove the REST API endpoint.
			remove_action( 'rest_api_init', 'wp_oembed_register_route' );
		}
	}
	
	public function disable_embeds_tiny_mce_plugin( $plugins ) {
		return array_diff( $plugins, array( 'wpembed' ) );
	}
	
	public function disable_embeds_rewrites( $rules ) {
		foreach ( $rules as $rule => $rewrite ) {
			if ( !is_array($rewrite) && false !== strpos( $rewrite, 'embed=true' ) ) {
				unset( $rules[ $rule ] );
			}
		}
		
		return $rules;
	}
	
	/**
	 * Modify the image quality and set it to chosen Global Options value.
	 *
	 * @since 4.3.4
	 * @return string The new image quality.
	 */
	public function set_jpeg_quality() {
		return get_theme_mod('pw_jpeg_quality', 82);
	}
	
	/**
	 * Modify WP's big image size threshold.
	 *
	 * @since 4.3.4
	 * @return string The new threshold.
	 */
	public function set_big_image_size_threshold() {
		$threshold = get_theme_mod('wp_big_image_size_threshold', 2560);
		$threshold = '0' === $threshold ? false : $threshold;
		
		return $threshold;
	}
}