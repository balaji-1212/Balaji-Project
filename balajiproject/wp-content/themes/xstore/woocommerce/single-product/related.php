<?php
/**
 * Related Products
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

global $product, $woocommerce_loop;

$posts_per_page = etheme_get_option( 'related_limit', 10 );
$posts_per_page = apply_filters( 'related_limit', $posts_per_page );
$related_type   = etheme_get_option( 'related_type', 'slider' );
$related_type   = apply_filters( 'related_type', $related_type );

// updated for woocommerce v3.0
$related = array_map( 'absint', array_values( wc_get_related_products( $product->get_id(), $posts_per_page ) ) );

if ( sizeof( $related ) == 0 ) {
	return;
}

$heading = apply_filters( 'woocommerce_product_related_products_heading', esc_html__( 'Related products', 'xstore' ) );

if ( $related_type != 'widget' ) {
	echo '<h2 class="products-title related-products-title"><span>' . apply_filters( 'upsell_products_title', $heading ) . '</span></h2>';
}

$args = apply_filters( 'woocommerce_related_products_args', array(
	'post_type'           => 'product',
	'ignore_sticky_posts' => 1,
	'no_found_rows'       => 1,
	'posts_per_page'      => $posts_per_page,
	'orderby'             => $orderby,
	'post__in'            => $related,
	'post__not_in'        => array( $product->get_id() )
) );

if ( $related_type == 'widget' ) {
	$slider_args = array(
		'before'          => '<h4 class="widget-title"><span>' . $heading . '</span></h4>',
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
	
	$slider_args['wrapper_class'] = 'related-products';
	
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
} elseif ( $related_type == 'slider' ) {
	
	$slider_args = array(
		'slider_autoplay' => false,
		'slider_speed'    => false,
		'large'           => 4,
		'notebook'        => 4,
		'tablet_land'     => 3,
		'tablet_portrait' => 2,
		'echo'            => true,
		'autoheight'      => false
	);
	
	$cols_gap            = 10;
	$cols_gap            = apply_filters( 'related_cols_gap', $cols_gap );
	$slider_args['attr'] = 'data-space="' . $cols_gap . '"';
	
	$slider_args['wrapper_class'] = 'related-products';
	
	$product_view = etheme_get_option( 'product_view', 'disable' );
	if ( ! empty( $woocommerce_loop['product_view'] ) ) {
		$product_view = $woocommerce_loop['product_view'];
	}
	
	$custom_template = etheme_get_option( 'custom_product_template', 'default' );
	if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
		$custom_template = $woocommerce_loop['custom_template'];
	}
	
	if ( $product_view == 'custom' && $custom_template != '' ) {
		$slider_args['class'] = 'products-with-custom-template products-template-' . $custom_template;
		$slider_args['attr']  .= ' data-post-id="' . $custom_template . '"';
	}
	
	$slides         = etheme_get_option( 'related_slides',
		array(
			'padding-top'  => '',
			'padding-right'  => '',
			'padding-bottom' => '',
			'padding-left' => '',
		)
	);
	$related_slides = isset( $slides['padding-top'] ) ? array(
		'large'           => $slides['padding-top'],
		'notebook'        => $slides['padding-right'],
		'tablet_land'     => $slides['padding-bottom'],
		'tablet_portrait' => $slides['padding-left']
	) : array();
	
	$related_slides = apply_filters( 'related_slides', $related_slides );
	if ( is_array( $slides ) ) {
		// large
		if ( ! empty( $related_slides['large'] ) ) {
			$slider_args['large'] = $related_slides['large'];
		}
		
		if ( ! empty( $related_slides['notebook'] ) ) {
			$slider_args['notebook'] = $related_slides['notebook'];
		}
		
		if ( ! empty( $related_slides['tablet_land'] ) ) {
			$slider_args['tablet_land'] = $related_slides['tablet_land'];
		}
		
		if ( ! empty( $related_slides['tablet_portrait'] ) ) {
			$slider_args['tablet_portrait'] = $slider_args['mobile'] = $related_slides['tablet_portrait'];
		}
	}
	
	etheme_slider( $args, 'product', $slider_args );
} else {
	$columns                                = etheme_get_option( 'related_columns', 4 );
	$columns                                = apply_filters( 'related_columns', $columns );
	$woocommerce_loop['product_loop_class'] = 'related-products';
	etheme_products( $args, false, $columns );
}

wp_reset_postdata();