<?php
/**
 * Cross-sells
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce, $woocommerce_loop;

$is_cart = is_cart() || is_checkout();

if ( $is_cart || !isset($cross_sells) ) {
	$cross_sells = WC()->cart->get_cross_sells();
}
$cross_sell_location = 'none';
if ( is_product() ) {
	$cross_sell_location = etheme_get_option( 'cross_sell_location', 'none' );
}

$posts_per_page = apply_filters( 'cross_sell_limit', get_theme_mod('cart_products_cross_sell_limit_et-desktop', 7) );
$cross_sell_type    = ( $cross_sell_location == 'sidebar' ) ? 'widget' : 'slider';
if ( $is_cart ) {
	$cross_sell_type = get_theme_mod('cart_products_cross_sell_type_et-desktop', 'slider');
}
$cross_sell_type    = apply_filters( 'cross_sell_type', $cross_sell_type );

if ( sizeof( $cross_sells ) == 0 ) return;

if ( $cross_sell_type != 'widget' ) {
	echo '<h2 class="products-title cross-sell-products-title"><span>' . apply_filters( 'woocommerce_product_cross_sells_products_heading', __( 'You may be interested in&hellip;', 'xstore' ) ) . '</span></h2>';
}

$meta_query = WC()->query->get_meta_query();

$args = array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => 1,
	'posts_per_page'      => $posts_per_page,
	'orderby'             => $orderby,
	'post__in'            => $cross_sells,
	'meta_query'          => $meta_query
);

if ( $cross_sell_type == 'widget' ) {
	$slider_args = array(
		'before'          => '<h4 class="widget-title"><span>' . apply_filters( 'woocommerce_product_cross_sells_products_heading', __( 'You may be interested in&hellip;', 'xstore' ) ) . '</span></h4>',
		'slider_autoplay' => false,
		'slider_speed'    => '0',
		'autoheight'      => false,
		'large'           => 1,
		'notebook'        => 1,
		'tablet_land'     => 1,
		'tablet_portrait' => 1,
		'mobile'          => 1,
		'attr'            => 'data-slidesPerColumn="3"',
		'widget'          => true,
		'echo'            => false
	);
	
	$slider_args['wrapper_class'] = 'cross-sell-products';
	
	$product_view = etheme_get_option( 'product_view', 'disable' );
	if ( ! empty( $woocommerce_loop['product_view'] ) ) {
		$product_view = $woocommerce_loop['product_view'];
	}
	
	$custom_template = etheme_get_option( 'custom_product_template', 'default' );
	if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
		$custom_template = $woocommerce_loop['custom_template'];
	}
	
	if ( $product_view == 'custom' && $custom_template != '' ) {
		$slider_args['class'] = ' products-with-custom-template products-template-' . $custom_template;
		$slider_args['attr']  = 'data-post-id="' . $custom_template . '"';
	}
	
	echo '<div class="sidebar-slider">' . etheme_slider( $args, 'product', $slider_args ) . '</div>';
} elseif ( $cross_sell_type == 'slider' ) {
	$slider_args = array(
		'title'           => '',
		'slider_autoplay' => false,
		'slider_speed'    => '0',
		'autoheight'      => false,
		'echo'            => true,
	);
	
	$slider_args['wrapper_class'] = 'cross-sell-products';
	
	$cols_gap            = 10;
	$cols_gap            = apply_filters( 'cross_sell_cols_gap', $cols_gap );
	$slider_args['attr'] = 'data-space="' . $cols_gap . '"';
	
	$product_view = etheme_get_option( 'product_view', 'disable' );
	if ( ! empty( $woocommerce_loop['product_view'] ) ) {
		$product_view = $woocommerce_loop['product_view'];
	}
	
	$custom_template = etheme_get_option( 'custom_product_template', 'default' );
	if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
		$custom_template = $woocommerce_loop['custom_template'];
	}
	
	if ( $product_view == 'custom' && $custom_template != '' ) {
		$slider_args['class'] = ' products-with-custom-template products-template-' . $custom_template;
		$slider_args['attr']  .= ' data-post-id="' . $custom_template . '"';
	}
	
	$cross_sell_slides = array();
	$cross_sell_slides = apply_filters( 'cross_sell_slides', $cross_sell_slides );
	
	if ( isset( $cross_sell_slides['large'] ) && ! empty( $cross_sell_slides['large'] ) ) {
		$slider_args['large'] = $cross_sell_slides['large'];
	}
	
	if ( $is_cart ) {
		$slider_args['large'] = $slider_args['notebook'] = get_theme_mod( 'cart_products_cross_sell_per_view_et-desktop', 4 );
	}
	
	if ( isset( $cross_sell_slides['notebook'] ) && ! empty( $cross_sell_slides['notebook'] ) ) {
		$slider_args['notebook'] = $cross_sell_slides['notebook'];
	}
	
	if ( isset( $cross_sell_slides['tablet_land'] ) && ! empty( $cross_sell_slides['tablet_land'] ) ) {
		$slider_args['tablet_land'] = $cross_sell_slides['tablet_land'];
	}
	
	if ( isset( $cross_sell_slides['tablet_portrait'] ) && ! empty( $cross_sell_slides['tablet_portrait'] ) ) {
		$slider_args['tablet_portrait'] = $slider_args['mobile'] = $cross_sell_slides['tablet_portrait'];
	}
	
	etheme_slider( $args, 'product', $slider_args );
} else {
	$columns                                = get_theme_mod('cart_products_cross_sell_per_view_et-desktop', 4);
	$columns                                = apply_filters( 'cross_sell_columns', $columns );
	$woocommerce_loop['product_loop_class'] = 'cross-sell-products';
	etheme_products( $args, false, $columns );
}

wp_reset_query();