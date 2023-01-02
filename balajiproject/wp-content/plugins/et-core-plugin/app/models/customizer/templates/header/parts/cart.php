<?php
	/**
	 * The template for displaying header cart
	 *
	 * @since   1.4.0
	 * @version 1.0.6
	 * last changes in 2.2.4
	 */
 ?>

<?php

	global $et_cart_icons, $et_builder_globals;

	$element_options = array();
	$element_options['is_woocommerce_et-desktop'] = class_exists('WooCommerce');
	$element_options['cart_style'] = get_theme_mod( 'cart_style_et-desktop', 'type1' );
	$element_options['cart_style'] = apply_filters('cart_style', $element_options['cart_style']);
    $element_options['cart_type_et-desktop'] = get_theme_mod( 'cart_icon_et-desktop', 'type1' );
    $element_options['cart_type_et-desktop'] = apply_filters('cart_icon', $element_options['cart_type_et-desktop']);

    $element_options['icon_custom'] = get_theme_mod('cart_icon_custom_svg_et-desktop', '');
    $element_options['icon_custom'] = apply_filters('cart_icon_custom', $element_options['icon_custom']);
    $element_options['icon_custom'] = isset($element_options['icon_custom']['id']) ? $element_options['icon_custom']['id'] : '';

	$element_options['cart_icon'] = false;

	if ( !get_theme_mod('bold_icons', 0) ) { 
		$element_options['cart_icons_et-desktop'] = $et_cart_icons['light'];
	}
	else {
		$element_options['cart_icons_et-desktop'] = $et_cart_icons['bold'];
	}

    if ( $element_options['cart_type_et-desktop'] == 'custom' ) {
        if ( $element_options['icon_custom'] != '' ) {
	        $element_options['cart_icons_et-desktop']['custom'] = etheme_get_svg_icon($element_options['icon_custom']);
        }
        else {
            $element_options['cart_icons_et-desktop']['custom'] = $element_options['cart_icons_et-desktop']['type1'];
        }
    }
    
    if ( $element_options['is_woocommerce_et-desktop'] ) $element_options['cart_icon'] = $element_options['cart_icons_et-desktop'][$element_options['cart_type_et-desktop']];

	$element_options['cart_quantity_et-desktop'] = get_theme_mod( 'cart_quantity_et-desktop', '1' );
	$element_options['cart_quantity_position_et-desktop'] = ( $element_options['cart_quantity_et-desktop'] ) ? ' et-quantity-' . get_theme_mod( 'cart_quantity_position_et-desktop', 'right' ) : '';

	$element_options['cart_content_type_et-desktop'] = get_theme_mod( 'cart_content_type_et-desktop', 'dropdown' );
	$element_options['cart_content_position_et-desktop'] = get_theme_mod( 'cart_content_position_et-desktop', 'right' );
	$element_options['cart_dropdown_position_et-desktop'] = get_theme_mod( 'cart_dropdown_position_et-desktop', 'right' );

	$element_options['not_cart_checkout'] = ( $element_options['is_woocommerce_et-desktop'] && !(is_cart() || is_checkout()) ) ? true : false;
    $element_options['not_cart_checkout'] = apply_filters('etheme_cart_content_shown_cart_checkout_pages', $element_options['not_cart_checkout']);

	if ( isset($et_builder_globals['in_mobile_menu']) && $et_builder_globals['in_mobile_menu'] ) {
        $element_options['cart_style'] = 'type1';
        $element_options['cart_quantity_et-desktop'] = false;
        $element_options['cart_quantity_position_et-desktop'] = '';
        $element_options['cart_content_alignment'] = ' justify-content-inherit';
	 	$element_options['cart_content_type_et-desktop'] = 'none';
    }

	$element_options['etheme_mini_cart_content_type'] = apply_filters('etheme_mini_cart_content_type', $element_options['cart_content_type_et-desktop']);

	$element_options['etheme_mini_cart_content_position'] = apply_filters('etheme_mini_cart_content_position', $element_options['cart_content_position_et-desktop']);

	$element_options['etheme_mini_cart_content'] = $element_options['etheme_mini_cart_content_type'] != 'none';
	$element_options['etheme_mini_cart_content'] = apply_filters('etheme_mini_cart_content', $element_options['etheme_mini_cart_content']);

	$element_options['cart_off_canvas'] = $element_options['etheme_mini_cart_content_type'] == 'off_canvas';
	$element_options['cart_off_canvas'] = apply_filters('cart_off_canvas', $element_options['cart_off_canvas']);

	// header cart classes 
	$element_options['wrapper_class'] = ' flex align-items-center';
	if ( isset($et_builder_globals['in_mobile_menu']) && $et_builder_globals['in_mobile_menu'] ) $element_options['wrapper_class'] .= ' justify-content-inherit';
	$element_options['wrapper_class'] .= ' cart-' . $element_options['cart_style'];
	$element_options['wrapper_class'] .= ' ' . $element_options['cart_quantity_position_et-desktop'];
	$element_options['wrapper_class'] .= ( $element_options['cart_off_canvas'] ) ? ' et-content-' . $element_options['etheme_mini_cart_content_position'] : '';
	$element_options['wrapper_class'] .= ( !$element_options['cart_off_canvas'] && $element_options['cart_dropdown_position_et-desktop'] != 'custom' ) ? ' et-content-' . $element_options['cart_dropdown_position_et-desktop'] : '';
	$element_options['wrapper_class'] .= ( $element_options['cart_off_canvas'] && $element_options['etheme_mini_cart_content'] && $element_options['not_cart_checkout']) ? ' et-off-canvas et-off-canvas-wide et-content_toggle' : ' et-content-dropdown et-content-toTop';
	$element_options['wrapper_class'] .= ( $element_options['cart_quantity_et-desktop'] && $element_options['cart_icon'] == '' ) ? ' static-quantity' : '';
	$element_options['wrapper_class'] .= ( ( isset($et_builder_globals['in_mobile_menu']) && $et_builder_globals['in_mobile_menu'] ) ? '' : ' et_element-top-level' );

	$element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
	$element_options['attributes'] = array();
	if ( $element_options['is_customize_preview'] ) 
		$element_options['attributes'] = array(
			'data-title="' . esc_html__( 'Cart', 'xstore-core' ) . '"',
			'data-element="cart"'
		);
	
	if ( $element_options['cart_off_canvas'] || $element_options['is_customize_preview'] ) {
		// could be via default wp
		if ( function_exists('etheme_enqueue_style')) {
			etheme_enqueue_style( 'off-canvas' );
		}
    }
	
	if ( $element_options['etheme_mini_cart_content'] || $element_options['is_customize_preview'] ) {
		if ( function_exists('etheme_enqueue_style')) {
			etheme_enqueue_style( 'cart-widget' );
		}
    }

    if ( get_option('xstore_sales_booster_settings_progress_bar', get_theme_mod('booster_progress_bar_et-desktop', false)) ) {
        wp_enqueue_script( 'cart_progress_bar');
    }

?>	

<div class="et_element et_b_header-cart <?php echo ( $element_options['is_woocommerce_et-desktop'] ) ? $element_options['wrapper_class'] : ''; ?>" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
	<?php echo header_cart_callback(); ?>
</div>

<?php unset($element_options);