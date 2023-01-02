<?php
/**
 * Description
 *
 * @package    wpseo.php
 * @since      1.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

add_filter('wpseo_breadcrumb_output', function ($output){
	$return_back = '';
	$return_to_previous = etheme_get_option('return_to_previous', 1);
	$return_to_previous = apply_filters('return_to_previous', $return_to_previous);
	if ( $return_to_previous ) {
		ob_start();
		etheme_back_to_page();
		$return_back = ob_get_clean();
	}
	if ( get_query_var( 'etheme_single_product_builder', false ) && get_query_var('is_single_product', false) ) {
		return str_replace('page-heading"><span>', 'page-heading"><span class="container block a-center pos-relative">' . $return_back, $output);
	}
	return str_replace('page-heading"><div>', 'page-heading"><div class="container a-center pos-relative">'.$return_back, $output);
}, 10, 1);

add_filter('wpseo_breadcrumb_output_class', function ($class){
	return 'bc-type-'.get_query_var('et_breadcrumbs-type', 'left2').
	       ' bc-effect-'.get_query_var('et_breadcrumbs-effect', 'mouse').
	       ' bc-color-'.get_query_var('et_breadcrumbs-color', 'dark').
	       ' page-heading';
}, 10);

add_filter('wpseo_breadcrumb_output_wrapper', function ($wrapper){
	return 'div';
}, 10);

add_filter('wpseo_breadcrumb_single_link_wrapper', function ($link_wrapper){
	if ( get_query_var( 'etheme_single_product_builder', false ) && get_query_var('is_single_product', false) ) {
		return $link_wrapper;
	}
	return 'div';
}, 10);

add_filter('wpseo_breadcrumb_single_link', function ($link_html){
	if ( get_query_var( 'etheme_single_product_builder', false ) && get_query_var('is_single_product', false) ) {
		return $link_html;
	}
	return str_replace('breadcrumb_last', 'breadcrumb_last title', $link_html);
}, 10);

