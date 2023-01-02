<?php
/**
 * The template created for enqueueing all files for single_product panel
 *
 * @version 1.0.1
 * @since   0.0.1
 */

if ( get_option( 'etheme_single_product_builder', false ) ) {
	$elements = array(
		'panel',
		// 'single_product_presets',
		'single_product_layout',
		'product_breadcrumbs',
		'product_navigation',
		'product_gallery',
		'variations-gallery',
		'product_sale_label',
		'product_title',
		'product_price',
		'product_rating',
		'product_short_description',
		'button',
		'request-quote',
		'product_size_guide',
		'product_cart_form',
        'countdown',
		'product_wishlist',
		'product_compare',
		'product_meta',
		'product_sharing',
		'product_tabs',
		'products_related',
		'products_upsell',
		'products_cross_sell',
		'bought-together',
		'html_blocks',
		'import_export',
		'builder_elements',
	);
} else {
	$elements = array(
		'panel-section'
	);
}

foreach ( $elements as $key ) {
	require_once( ET_CORE_DIR . 'app/models/customizer/theme-options/product-single-builder/' . $key . '.php' );
}