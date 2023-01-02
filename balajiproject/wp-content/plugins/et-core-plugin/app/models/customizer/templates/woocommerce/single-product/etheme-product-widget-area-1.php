<?php 
/**
 * The template for single product widget area content
 *
 * @since   1.5.0
 * @version 1.0.1
 */

$options = array();
$options['sidebar_name'] = get_theme_mod( 'single_product_widget_area_1_et-desktop', 'single-sidebar' ); ?>
<div class="sidebar single-product-custom-widget-area" data-key="single-product-custom-widget-area">
<?php 
	if ( etheme_get_option('brands_location', 'sidebar') == 'sidebar' && function_exists('etheme_product_brand_image') ) etheme_product_brand_image();
    if ( !dynamic_sidebar( $options['sidebar_name'] ) || is_active_sidebar( $options['sidebar_name'] ) ):
    endif;
?>
</div>
<?php
unset($options);