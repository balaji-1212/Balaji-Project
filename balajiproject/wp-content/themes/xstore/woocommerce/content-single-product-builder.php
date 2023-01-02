<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */
defined( 'ABSPATH' ) || exit;

global $etheme_global, $product;

//$l = etheme_page_config();
/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );
if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

$sidebar = get_query_var('et_sidebar', 'left');
$content_class = get_query_var('et_content-class', 'col-md-9');

$etheme_global['class'][] = 'single-product-builder';
if ( etheme_get_option( 'product_reviews_in_two_columns_et-desktop', 1 ) )
	$etheme_global['class'][] = 'reviews-two-columns';

if ( etheme_get_option( 'single_product_sidebar_mode_et-desktop', 'element' ) != 'default') {
	$sidebar = 'without';
	$content_class = 'col-md-12';
}

if ( etheme_get_option('stretch_add_to_cart_et-desktop', false) ) {
	$etheme_global['class'][] = 'stretch-add-to-cart-button';
}

if ( $sidebar != '' && !in_array($sidebar, array('without', 'no_sidebar')) && etheme_get_option('single_product_sidebar_sticky_et-desktop', false) && !get_query_var('is_yp') ) {
	wp_enqueue_script('sticky-kit');
	wp_enqueue_script('sticky_sidebar');
}

?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( $etheme_global['class'], $product ); ?>>
	
	<div class="row">
		<div class="<?php echo esc_attr( $content_class ); ?> sidebar-position-<?php echo esc_attr( $sidebar ); ?>">
			<div class="row">
				<?php if ( function_exists('single_product_bulder_content_callback')) 
					echo single_product_bulder_content_callback();
				?>
			</div>
		</div>
		
		<?php if($sidebar != '' && !in_array($sidebar, array('without', 'no_sidebar')) ): ?>
			<div class="<?php echo esc_attr( get_query_var('et_sidebar-class', 'col-md-3') ); ?> sidebar single-product-sidebar sidebar-<?php echo esc_attr( $sidebar ); ?>
                <?php echo ( etheme_get_option('single_product_sidebar_sticky_et-desktop', false) && !get_query_var('is_yp') ) ? ' sticky-sidebar' : ''; ?>">
				<?php
				if ( etheme_get_option('brands_location', 'sidebar') == 'sidebar' && ! etheme_xstore_plugin_notice() ) etheme_product_brand_image();
                $sidebar_name = etheme_get_option( 'single_product_widget_area_1_et-desktop', 'single-sidebar' );
				if ( !dynamic_sidebar( $sidebar_name ) || is_active_sidebar( $sidebar_name ) ): endif;
				?>
			</div>
		<?php endif; ?>
	</div>

</div>

<?php
if ( class_exists( 'WC_Structured_Data' ) ) {
	global $wp_filter;
	$summary_actions            = $wp_filter['woocommerce_single_product_summary'];
	$actions_2_remove = array();
	if ( ! empty( $summary_actions ) && isset( $summary_actions ) ) {
		foreach ( $summary_actions as $item_priority => $item_value) {
			foreach ( $item_value as $action => $args ) {
				if ( strpos($action, 'generate_product_data') === false) {
					$actions_2_remove[] = array(
						'name' => $action,
						'priority' => $item_priority
					);
				}
			}
		}
		foreach ($actions_2_remove as $action) {
			remove_action('woocommerce_single_product_summary', $action['name'], $action['priority']);
		}
	}
	
	do_action( 'woocommerce_single_product_summary' );
}

?>

<?php do_action( 'woocommerce_after_single_product' ); ?>