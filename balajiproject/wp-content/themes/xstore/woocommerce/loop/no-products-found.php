<?php
/**
 * Displayed when no products are found matching the current query.
 *
 * Override this template by copying it to yourtheme/woocommerce/loop/no-products-found.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<?php if ( etheme_get_option( 'ajax_product_filter', 0 ) || etheme_get_option( 'ajax_product_pagination', 0 ) ): ?>
    <?php

    global $woocommerce_loop;
    // Store column count for displaying the grid
    $loop = (get_query_var('et_cat-cols') && !apply_filters( 'wc_loop_is_shortcode', wc_get_loop_prop( 'is_shortcode' ) ) ) ? get_query_var('et_cat-cols') : wc_get_loop_prop( 'columns' );

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

    if($view_mode == 'list') {
        $view_class = 'products-list';
    }else{
        $view_class = 'products-grid';
    }
    
    if ( ! empty( $woocommerce_loop['isotope'] ) && $woocommerce_loop['isotope'] || etheme_get_option( 'products_masonry', 0 ) && get_query_var('et_is-woocommerce-archive', false) && woocommerce_products_will_display() ) {
        $view_class .= ' et-isotope';
    }

    $product_view = etheme_get_option('product_view', 'disable');
    if( !empty($woocommerce_loop['product_view'])) {
        $product_view = $woocommerce_loop['product_view'];
    }

    $custom_template = get_query_var('et_custom_product_template');
    if( !empty($woocommerce_loop['custom_template'])) {
        $custom_template = $woocommerce_loop['custom_template'];
    }

    if ( $product_view == 'custom' && $custom_template != '' ) {
        $view_class .= ' products-with-custom-template';
        $view_class .= ' products-with-custom-template-' . ( $view_mode == 'list' ? 'list' : 'grid' );
        $view_class .= ' products-template-'.$custom_template;
    }

    $view_class .= isset($woocommerce_loop['product_loop_class']) ? ' ' . $woocommerce_loop['product_loop_class'] : '';

    $view_class .= (etheme_get_option( 'ajax_product_filter', 0 ) || etheme_get_option( 'ajax_product_pagination', 0 )) ? ' with-ajax' : '';

    if ( etheme_get_option( 'top_toolbar', 1 ) ) {
        if ( ! wc_get_loop_prop( 'is_shortcode' ) ) {
	        etheme_enqueue_style('filter-area', true ); ?>
            <div class="filter-wrap">
            <div class="filter-content">
        <?php }
        /**
         * woocommerce_before_shop_loop hook
         *
         * @hooked woocommerce_result_count - 20
         * @hooked woocommerce_catalog_ordering - 30
         * @hooked etheme_grid_list_switcher - 35
         */
        do_action( 'woocommerce_before_shop_loop' );
        if ( ! wc_get_loop_prop( 'is_shortcode' ) ) { ?>
            </div>
            </div>
        <?php }
    }
    etheme_shop_filters_sidebar();

?>
<div class="row products-loop <?php echo esc_attr( $view_class ); ?> row-count-<?php echo esc_attr( $loop ); ?>"<?php if ($product_view == 'custom' && $custom_template != '' ) : ?> data-post-id="<?php echo esc_attr( $custom_template ); ?>"<?php endif; ?> data-row-count="<?php echo esc_attr( $loop ); ?>">
	<?php etheme_loader( true, 'product-ajax' ); ?>
    <div class="ajax-content clearfix">
		<?php endif ?>
        <div class="empty-category-block">
            <h2><?php esc_html_e('No products were found', 'xstore'); ?></h2>
            <p class="not-found-info">
				<?php echo (isset($_GET['s'])) ? esc_html__('No items matched your search', 'xstore') . ' <strong>' . esc_html($_GET['s']) . '.</strong>' : ''; ?><br/>
				<?php esc_html_e('Check your spelling or search again with less specific terms.', 'xstore') ?></p>
            <p><a class="btn black medium" href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><span><?php esc_html_e('Return To Shop', 'xstore') ?></span></a></p>
        </div>
		<?php if ( etheme_get_option( 'ajax_product_filter', 0 ) || etheme_get_option( 'ajax_product_pagination', 0 ) ): ?>
    </div>
</div>
<?php endif ?>
