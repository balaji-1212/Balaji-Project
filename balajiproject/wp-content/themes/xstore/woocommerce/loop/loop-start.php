<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */
global $woocommerce_loop;

// Store column count for displaying the grid
$loop = (get_query_var('et_cat-cols') && !apply_filters( 'wc_loop_is_shortcode', wc_get_loop_prop( 'is_shortcode' ) ) ) ? get_query_var('et_cat-cols') : wc_get_loop_prop( 'columns' );
$woocommerce_loop['doing_ajax'] = defined( 'DOING_AJAX' ) && DOING_AJAX;

$view_mode = get_query_var('et_view-mode');
if( !empty($woocommerce_loop['view_mode'])) {
	$view_mode = $woocommerce_loop['view_mode'];
} else {
	$woocommerce_loop['view_mode'] = $view_mode;
}

if ( get_query_var('view_mode_smart', false) && !apply_filters( 'wc_loop_is_shortcode', wc_get_loop_prop( 'is_shortcode' ) ) ) {
	if ( isset( $_GET['et_columns-count'] ) ) {
		$loop = $_GET['et_columns-count'];
	}
	else {
		$view_mode_smart_active = get_query_var('view_mode_smart_active', 4);;
		$loop = $view_mode_smart_active != 'list' ? $view_mode_smart_active : 4;
		$view_mode = $view_mode_smart_active == 'list' ? 'list' : $view_mode;
	}
}

$view_class = 'products-' . (($view_mode == 'list') ? 'list' : 'grid');

if ( ! empty( $woocommerce_loop['isotope'] ) && $woocommerce_loop['isotope'] || etheme_get_option( 'products_masonry', 0 ) && get_query_var( 'et_is-woocommerce-archive', false ) && woocommerce_products_will_display() ) {
    $view_class .= ' et-isotope';
}

$product_view = etheme_get_option('product_view', 'disable');
if( !empty($woocommerce_loop['product_view'])) {
	$product_view = $woocommerce_loop['product_view'];
}
// moved to woo.php as filter // on testing now
//if ( !$woocommerce_loop['doing_ajax'] ) {
//	etheme_enqueue_style( 'woocommerce-archive', true );
//	if ( $product_view && ! in_array( $product_view, array( 'disable', 'custom' ) ) ) {
//		etheme_enqueue_style( 'product-view-' . $product_view, true );
//	}
//	else {
//	    // enqueue styles if nothing set via loop
//		$local_product_view = etheme_get_option('product_view', 'disable');
//		if ( $local_product_view != 'disable' )
//			etheme_enqueue_style( 'product-view-' . $local_product_view, true );
//    }
//
//	$custom_template = get_query_var( 'et_custom_product_template' );
//	if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
//		$custom_template = $woocommerce_loop['custom_template'];
//		etheme_enqueue_style( 'content-product-custom', true );
//	}
//
//	if ( get_query_var( 'et_is-swatches', false ) ) {
//		etheme_enqueue_style( "swatches-style", true );
//	}
//
//	if ( get_query_var('et_is-quick-view', false) ) {
//		etheme_enqueue_style( "quick-view", true );
//		if ( get_query_var('et_is-quick-view-type', 'popup') == 'off_canvas' ) {
//			etheme_enqueue_style( "off-canvas", true );
//        }
//    }
//}

$custom_template = get_query_var( 'et_custom_product_template' );
if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
    $custom_template = $woocommerce_loop['custom_template'];
	if ( !$woocommerce_loop['doing_ajax'] ) {
		etheme_enqueue_style( 'content-product-custom', true );
	}
}

if ( $product_view == 'custom' && $custom_template != '' ) {
	$view_class .= ' products-with-custom-template';
	$view_class .= ' products-with-custom-template-' . ( $view_mode == 'list' ? 'list' : 'grid' );
	$view_class .= ' products-template-'.$custom_template;
}

$view_class .= isset($woocommerce_loop['product_loop_class']) ? ' ' . $woocommerce_loop['product_loop_class'] : '';

if ( !apply_filters( 'wc_loop_is_shortcode', wc_get_loop_prop( 'is_shortcode' ) ) ) {
	$view_class .= ( etheme_get_option( 'ajax_product_filter', 0 ) || etheme_get_option( 'ajax_product_pagination', 0 ) ) ? ' with-ajax' : '';
	if ( get_query_var('et_product-bordered-layout', 0)) {
		$view_class .= ' products-bordered-layout';
    }
	if ( get_query_var('et_product-no-space', 0)) {
		$view_class .= ' products-no-space';
	}
	if ( get_query_var('et_product-shadow-hover', 0)) {
		$view_class .= ' products-hover-shadow';
	}
	
	$variable_products_detach = etheme_get_option( 'variable_products_detach', false );
	$show_attributes          = etheme_get_option( 'variation_product_name_attributes', true );
	
	if ( $variable_products_detach && $show_attributes ) {
		add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_true' );
	}
}

$attr = array(
        'class' =>
            ( wc_get_loop_prop( 'etheme_elementor_product_widget' ) ) ?
            array() :
            array(
                'row products products-loop',
                $view_class,
                'row-count-'.esc_attr( $loop )
            )
);
if ( !empty($woocommerce_loop['bordered_layout']) ) {
    $attr['class'][] = 'products-bordered-layout';
}

if ( !empty($woocommerce_loop['no_spacing_grid']) ) {
	$attr['class'][] = 'products-no-space';
}

if ( !empty($woocommerce_loop['hover_shadow']) ) {
	$attr['class'][] = 'products-hover-shadow';
}

$attr_rendered = array(
    'class="'.implode(' ', $attr['class']).'"'
);

if ( !wc_get_loop_prop( 'etheme_elementor_product_widget' ) )
	$attr_rendered[] = 'data-row-count="'.esc_attr( $loop ).'"';

if ($product_view == 'custom' && $custom_template != '' )
	$attr_rendered[] = 'data-post-id="'.esc_attr( $custom_template ).'"';

?>
<div <?php echo implode(' ', $attr_rendered); ?>>

<?php if ( !apply_filters( 'wc_loop_is_shortcode', wc_get_loop_prop( 'is_shortcode' ) ) && (etheme_get_option( 'ajax_product_filter', 0 ) || etheme_get_option( 'ajax_product_pagination', 0 )) ): ?>
	<?php etheme_loader( true, 'product-ajax' ); ?>
    <div class="ajax-content clearfix">
<?php endif ?>