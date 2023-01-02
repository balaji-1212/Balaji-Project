<?php

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

$sidebar = get_query_var('et_sidebar', 'left');
if ( !$sidebar || in_array($sidebar, array('without', 'no_sidebar'))) return;

$sidebar_sticky = etheme_get_option( 'shop_sticky_sidebar', 0 ) && !get_query_var('is_yp');
if ( $sidebar_sticky ) {
    if ( !(get_query_var('et_sidebar', 'left') == 'off_canvas' || ( get_query_var('is_mobile', false) && get_query_var('et_sidebar-mobile', 'bottom') == 'off_canvas'))) {
	    wp_enqueue_script( 'sticky-kit' );
	    wp_enqueue_script( 'sticky_sidebar' );
    }
}

?>

<div class="<?php echo esc_attr( get_query_var('et_sidebar-class', 'col-md-3') ); ?> sidebar sidebar-<?php echo esc_attr( get_query_var('et_sidebar', 'left') ); ?>
    <?php echo (etheme_get_option('shop_sidebar_hide_mobile', 0)) ? ' hidden-xs' : '' ; ?>
    <?php if ( $sidebar_sticky ) echo ' sticky-sidebar'; ?>">
    <?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('shop-sidebar')):

        if(!get_query_var('et_is-woocommerce', false)) return;
        // **********************************************************************//
        // ! Categories
        // **********************************************************************//
        $args = array(
            'widget_id' => 'woocommerce_product_categories',
            'before_widget' => '<div id="product_categories-1" class="sidebar-widget widget_product_categories widget_onsale">',
            'after_widget' => '</div><!-- //sidebar-widget -->',
            'before_title' => apply_filters('etheme_sidebar_before_title', '<h4 class="widget-title"><span>' ),
            'after_title' => apply_filters('etheme_sidebar_after_title', '</span></h4>'),
        );

        $instance = array(
            'title' => 'Categories',
            'hierarchical' => true,
            'count' => false,
            'dropdown' => false,
            'orderby' => ''
        );

        $widget = new WC_Widget_Product_Categories();
        $widget->widget($args, $instance);

    endif; ?>
</div>