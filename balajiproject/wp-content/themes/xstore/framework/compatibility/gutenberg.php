<?php
/**
 * Gutenberg actions/filters
 *
 * @package    gutenberg.php
 * @since      8.3.4
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

add_action('init', function () {
	if ( get_theme_mod( 'disable_block_css', 0 ) ) {
		remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
		remove_action( 'in_admin_header', 'wp_global_styles_render_svg_filters' );
	}
});