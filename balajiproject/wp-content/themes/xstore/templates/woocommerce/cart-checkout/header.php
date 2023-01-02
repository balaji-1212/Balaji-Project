<?php

global $wp;

add_filter('etheme_use_desktop_style', '__return_true');

if ( get_query_var('et_is_customize_preview', false) ) {
	add_filter('is_customize_preview', '__return_true');
}
$logo = get_theme_mod('cart_checkout_logo_img_et-desktop', '');
$retina_logo = get_theme_mod('cart_checkout_retina_logo_img_et-desktop', '');
if ( $logo ) {
    add_filter('logo_img', function ($img) use ($logo) {
        return $logo;
    });
    add_filter('theme_mod_headers_sticky_logo_img_et-desktop', function ($img) use ($logo) {
	    return $logo;
    });
}

if ( $retina_logo ) {
    add_filter('theme_mod_retina_logo_img_et-desktop', function ($img) use ($retina_logo) {
        return $retina_logo;
    });
}
add_filter('theme_mod_logo_align_et-desktop', function () {
    return 'start';
});

add_filter('theme_mod_headers_sticky_logo_img_et-desktop', '__return_empty_string');
add_filter('etheme_logo_simple', '__return_true');

$element_options = array();
$header_options = array();
$header_options['class'] = get_theme_mod( 'cart_checkout_main_header_sticky_et-desktop', '1' ) && get_query_var('et_cart-checkout-layout', 'default') != 'separated' ? 'sticky' : '';

$cart_checkout = Etheme_WooCommerce_Cart_Checkout::get_instance();

$part_data = array(
	'logo' => array(
		'size' => 3,
		'offset' => 0
	),
	'steps' => array(
		'size' => 9,
		'offset' => 0
	),
);

$check_pages = $cart_checkout->check_page();

if ( get_query_var('et_cart-checkout-layout', 'default') == 'separated' && !$check_pages['is_order']) {
    $part_data['logo']['size'] = $part_data['steps']['size'] = 12;
}

//$page_id = get_query_var('et_page-id', array( 'id' => 0, 'type' => 'page' ));
//$page_id = $page_id['id'];
//$is_checkout = $page_id == wc_get_page_id( 'checkout' ) || get_query_var( 'et_is-checkout', false );
//$is_cart = $page_id == wc_get_page_id( 'cart' ) || get_query_var( 'et_is-cart', false );
//$is_order = false;
//
//// Handle checkout actions.
//if ( ! empty( $wp->query_vars['order-pay'] ) ) {
//} elseif ( isset( $wp->query_vars['order-received'] ) ) {
//    $is_order = true;
//}
ob_start();
$cart_checkout->header_steps(true);
$steps = ob_get_clean();
?>

<div class="header-main-wrapper <?php echo esc_attr($header_options['class']); ?>">
    <div class="header-main" data-title="<?php esc_html_e( 'Header main', 'xstore' ); ?>">
        <div class="et-row-container<?php echo (!get_theme_mod( 'cart_checkout_main_header_wide_et-desktop', '0' ) && get_query_var('et_cart-checkout-layout', 'default') != 'separated') ? ' et-container' : ''; ?>">
            <div class="et-wrap-columns flex <?php echo get_query_var('et_cart-checkout-layout', 'default') == 'separated' ? ' flex-wrap' : ''; ?> align-items-center"><?php
                    foreach ( $part_data as $key => $value ) :
                        $col_class = array();
                        $col_class[] = 'et_col-xs-' . $value['size'];
                        $col_class[] = 'et_col-xs-offset-' . $value['offset'];
                        ?>
                        <div class="et_column <?php echo esc_attr(implode(' ', $col_class)); ?>">
                            <?php
                            switch ($key) {
                                case 'steps':
	                                $element_options['attributes'] = array();
	                                if ( get_query_var('et_is_customize_preview', false) )
		                                $element_options['attributes'] = array(
			                                'data-title="' . esc_html__( 'Cart/Checkout steps', 'xstore' ) . '"',
			                                'data-element="cart-checkout-layout"'
		                                );
                                    echo '<div class="et_element cart-checkout-nav'.(( get_query_var('et_cart-checkout-layout', 'default') != 'default' ) ? '-simple': '').' '.(( get_query_var('et_cart-checkout-layout', 'default') == 'separated' && !$check_pages['is_order']) ? 'align-start justify-content-start' : 'align-end justify-content-end').' et_element-top-level" ' . implode( ' ', $element_options['attributes'] ) . '>'.$steps.'</div>';
                                    break;
                                default:
                                    require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/' . $key . '.php' );
                                break;
                            }
                            ?>
                        </div>
                    <?php endforeach;
            ?></div><?php // to prevent empty spaces in DOM content ?>
        </div>
    </div>
</div>