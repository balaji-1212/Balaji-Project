<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( get_option( 'etheme_single_product_builder', false ) && function_exists('etheme_core_hooks') ) {
	wc_get_template_part( 'content', 'single-product-builder' );
	return;
}

global $etheme_global, $product;

$layout = get_query_var('et_product-layout', 'default');
$etheme_global = etheme_get_single_product_class( $layout );
$sidebar = get_query_var('et_sidebar', 'left');

$thumbs_slider_mode = etheme_get_option('thumbs_slider_mode', 'enable');
$gallery_slider = $thumbs_slider_mode == 'enable' || ( $thumbs_slider_mode == 'enable_mob' && get_query_var('is_mobile') );

$thumbs_slider = etheme_get_option('thumbs_slider_vertical', 'horizontal');

$enable_slider = etheme_get_custom_field('product_slider', get_the_ID());

$stretch_slider = etheme_get_option('stretch_product_slider', 1);

$slider_direction = etheme_get_custom_field('slider_direction', get_the_ID());

$vertical_slider = $thumbs_slider == 'vertical';

if ( $slider_direction == 'vertical' ) {
	$vertical_slider = true;
}
elseif($slider_direction == 'horizontal') {
	$vertical_slider = false;
}

$show_thumbs = $thumbs_slider != 'disable';

if ( $layout == 'large' && $stretch_slider ) {
	$show_thumbs = false;
	$etheme_global['class'][] = 'stretch-swiper-slider ';
}
if ( $slider_direction == 'disable' ) {
	$show_thumbs = false;
}
elseif ( in_array($slider_direction, array('vertical', 'horizontal') ) ) {
	$show_thumbs = true;
}
if ( $enable_slider == 'on' || ($enable_slider == 'on_mobile' && get_query_var('is_mobile') ) ) {
	$gallery_slider = true;
}
elseif ( $enable_slider == 'off' || ($enable_slider == 'on_mobile' && !get_query_var('is_mobile') ) ) {
	$gallery_slider = false;
	$show_thumbs = false;
}
$etheme_global['gallery_slider'] = $gallery_slider;
$etheme_global['vertical_slider'] = $vertical_slider;
$etheme_global['show_thumbs'] = $show_thumbs;

$etheme_global['class'][] = 'single-product';

/**
 * woocommerce_before_single_product hook
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

?>
	
	<div id="product-<?php the_ID(); ?>" <?php wc_product_class( $etheme_global['class'], $product ); ?>>
		
		<div class="row">
			<div class="<?php echo esc_attr( get_query_var('et_content-class', 'col-md-9') ); ?> product-content sidebar-position-<?php echo esc_attr( $sidebar ); ?>">
				<div class="row">
					<?php wc_get_template_part( 'single-product-content', $layout ); ?>
				</div>
			
			</div> <!-- CONTENT/ END -->
			
			<?php if($sidebar != '' && !in_array($sidebar, array('without', 'no_sidebar')) ): ?>
				<div class="<?php echo esc_attr( get_query_var('et_sidebar-class', 'col-md-3') ); ?> single-product-sidebar sidebar-<?php echo esc_attr( $sidebar ); ?>">
					<?php if ( etheme_get_option('brands_location', 'sidebar') == 'sidebar' && ! etheme_xstore_plugin_notice() ) etheme_product_brand_image(); ?>
					<?php if(etheme_get_option('upsell_location', 'sidebar') == 'sidebar') woocommerce_upsell_display(); ?>
					<?php if(etheme_get_option('cross_sell_location', 'none') == 'sidebar') etheme_cross_sell_display(); ?>
					<?php dynamic_sidebar('single-sidebar'); ?>
				</div>
			<?php endif; ?>
		</div>
		
		<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_output_related_products - 20 [REMOVED in woo.php]
		 */
		if(etheme_get_option('tabs_location', 'after_content') == 'after_content' && $layout != 'large') {
			do_action( 'woocommerce_after_single_product_summary' );
		}
		else {
		    // it is enqueued by default for woocommerce_after_single_product_summary but in any other case
            // let's load it directly
			etheme_bought_together();
		}
		?>
		
		<?php if(etheme_get_option('product_posts_links', 1) && function_exists('etheme_project_links')): ?>
			<?php etheme_project_links(array()); ?>
		<?php endif; ?>
		
		<?php
		if(etheme_get_custom_field('additional_block') != '') {
			echo '<div class="product-extra-content">';
			etheme_static_block(etheme_get_custom_field('additional_block'), true);
			echo '</div>';
		}
		?>
  
		<?php if(etheme_get_option('upsell_location', 'sidebar') == 'after_content') woocommerce_upsell_display(); ?>
		
		<?php if(etheme_get_option('cross_sell_location', 'none') == 'after_content') etheme_cross_sell_display(); ?>
		
		<?php if(etheme_get_option('show_related', 1)) woocommerce_output_related_products(); ?>
	
	</div><!-- #product-<?php the_ID(); ?> -->

<?php
add_filter('etheme_woocommerce_template_single_add_to_cart_hooks', '__return_false' );
do_action( 'woocommerce_after_single_product' );
?>