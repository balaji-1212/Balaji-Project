<?php
    /**
     * The template for displaying header wishlist block
     *
     * @since   1.4.0
     * @version 1.0.4
     * last changes in 2.2.4
     */
 ?>

<?php 

    global $et_wishlist_icons, $et_builder_globals;

    $element_options = array();
    $element_options['built_in_wishlist'] = get_theme_mod('xstore_wishlist', false) && class_exists('WooCommerce');
    $element_options['is_YITH_WCWL'] = class_exists('YITH_WCWL');

    $html = '';

    if ( !$element_options['built_in_wishlist'] && !$element_options['is_YITH_WCWL'] ) : ?>
        <div class="et_element et_b_header-wishlist" data-title="<?php esc_html_e( 'Wishlist', 'xstore-core' ); ?>">
            <span class="flex flex-wrap full-width align-items-center currentColor">
                <span class="flex-inline justify-content-center align-items-center flex-nowrap">
                    <?php esc_html_e( 'Wishlist ', 'xstore-core' ); ?> 
                    <span class="mtips" style="text-transform: none;">
                        <i class="et-icon et-exclamation" style="margin-left: 3px; vertical-align: middle; font-size: 75%;"></i>
                        <span class="mt-mes"><?php echo current_user_can( 'edit_theme_options' ) ? sprintf(
                            /* translators: %s: URL to header image configuration in Customizer. */
                                __( 'Please, enable <a style="text-decoration: underline" href="%s" target="_blank">Built-in Wishlist</a>.', 'xstore-core'),
                                admin_url( 'customize.php?autofocus[section]=xstore-wishlist' )) :
                                __( 'Please, enable Built-in Wishlist.', 'xstore-core'); ?></span>
                    </span>
                </span>
            </span>
        </div>
    <?php return; 
    endif;

    $element_options['wishlist_style'] = get_theme_mod( 'wishlist_style_et-desktop', 'type1' );
    $element_options['wishlist_style'] = apply_filters('wishlist_style', $element_options['wishlist_style']);
    
    $element_options['wishlist_type_et-desktop'] = get_theme_mod( 'wishlist_icon_et-desktop', 'type1' );
    $element_options['wishlist_type_et-desktop'] = apply_filters('wishlist_icon', $element_options['wishlist_type_et-desktop']);

    if ( !get_theme_mod('bold_icons', 0) ) { 
        $element_options['wishlist_icons'] = $et_wishlist_icons['light'];
    }
    else {
        $element_options['wishlist_icons'] = $et_wishlist_icons['bold'];
    }

    $element_options['icon_custom'] = get_theme_mod('wishlist_icon_custom_svg_et-desktop', '');
    $element_options['icon_custom'] = apply_filters('wishlist_icon_custom', $element_options['icon_custom']);
    $element_options['icon_custom'] = isset($element_options['icon_custom']['id']) ? $element_options['icon_custom']['id'] : '';

    if ( $element_options['wishlist_type_et-desktop'] == 'custom' ) {
        if ( $element_options['icon_custom'] != '' ) {
	        $element_options['wishlist_icons']['custom'] = etheme_get_svg_icon($element_options['icon_custom']);
        }
        else {
            $element_options['wishlist_icons']['custom'] = $element_options['wishlist_icons']['type1'];
        }
    }
    
    $element_options['wishlist_icon'] = $element_options['wishlist_icons'][$element_options['wishlist_type_et-desktop']];

    $element_options['wishlist_quantity_et-desktop'] = get_theme_mod( 'wishlist_quantity_et-desktop', '1' );
    $element_options['wishlist_quantity_position_et-desktop'] = ( $element_options['wishlist_quantity_et-desktop'] ) ? ' et-quantity-' . get_theme_mod( 'wishlist_quantity_position_et-desktop', 'right' ) : '';

    $element_options['wishlist_content_position_et-desktop'] = get_theme_mod( 'wishlist_content_position_et-desktop', 'right' );

    $element_options['wishlist_content_type_et-desktop'] = get_theme_mod( 'wishlist_content_type_et-desktop', 'dropdown' );

    $element_options['wishlist_dropdown_position_et-desktop'] = get_theme_mod( 'wishlist_dropdown_position_et-desktop', 'right' );

    if ( $et_builder_globals['in_mobile_menu'] ) {
        $element_options['wishlist_style'] = 'type1';
        $element_options['wishlist_quantity_et-desktop'] = false;
        $element_options['wishlist_quantity_position_et-desktop'] = '';
        $element_options['wishlist_content_type_et-desktop'] = 'none';
    }

    $element_options['not_wishlist_page'] = true;
    if ( $element_options['built_in_wishlist'] ) {
        $wishlist_page_id = get_theme_mod('xstore_wishlist_page', '');
        if ( ! empty( $wishlist_page_id ) && is_page( $wishlist_page_id ) || (isset($_GET['et-wishlist-page']) && is_account_page()) ) {
            $element_options['not_wishlist_page'] = false;
        }
    }
    elseif ( function_exists('yith_wcwl_object_id') ) {
        $wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );
        if ( ! empty( $wishlist_page_id ) && is_page( $wishlist_page_id ) ) {
            $element_options['not_wishlist_page'] = false;
        }
    }

    // filters 
    $element_options['etheme_mini_wishlist_content_type'] = apply_filters('etheme_mini_wishlist_content_type', $element_options['wishlist_content_type_et-desktop']);

    $element_options['etheme_mini_wishlist_content'] = $element_options['etheme_mini_wishlist_content_type'] != 'none';
    $element_options['etheme_mini_wishlist_content'] = apply_filters('etheme_mini_wishlist_content', $element_options['etheme_mini_wishlist_content']);

    $element_options['etheme_mini_wishlist_content_position'] = apply_filters('etheme_mini_wishlist_content_position', $element_options['wishlist_content_position_et-desktop']);

    $element_options['wishlist_off_canvas'] = $element_options['etheme_mini_wishlist_content_type'] == 'off_canvas';
    $element_options['wishlist_off_canvas'] = apply_filters('wishlist_off_canvas', $element_options['wishlist_off_canvas']);

    // header wishlist classes 
    $element_options['wrapper_class'] = ' flex align-items-center';
    if ( $et_builder_globals['in_mobile_menu'] ) $element_options['wrapper_class'] .= ' justify-content-inherit';
    $element_options['wrapper_class'] .= ' wishlist-' . $element_options['wishlist_style'];
    $element_options['wrapper_class'] .= ' ' . $element_options['wishlist_quantity_position_et-desktop'];
    $element_options['wrapper_class'] .= ( $element_options['wishlist_off_canvas'] ) ? ' et-content-' . $element_options['etheme_mini_wishlist_content_position'] : '';
    $element_options['wrapper_class'] .= ( !$element_options['wishlist_off_canvas'] && $element_options['wishlist_dropdown_position_et-desktop'] != 'custom' ) ? ' et-content-' . $element_options['wishlist_dropdown_position_et-desktop'] : '';
    $element_options['wrapper_class'] .= ( $element_options['wishlist_off_canvas'] && $element_options['etheme_mini_wishlist_content'] && $element_options['not_wishlist_page']) ? ' et-off-canvas et-off-canvas-wide et-content_toggle' : ' et-content-dropdown et-content-toTop';
    $element_options['wrapper_class'] .= ( $element_options['wishlist_quantity_et-desktop'] && $element_options['wishlist_icon'] == '' ) ? ' static-quantity' : '';
    $element_options['wrapper_class'] .= ( $et_builder_globals['in_mobile_menu'] ) ? '' : ' et_element-top-level';

    $element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
    $element_options['attributes'] = array();

    if ( $element_options['is_customize_preview'] ) 
        $element_options['attributes'] = array(
            'data-title="' . esc_html__( 'Wishlist', 'xstore-core' ) . '"',
            'data-element="wishlist"'
        );

    if ( !$element_options['built_in_wishlist'])
        wp_enqueue_script('et_wishlist');

    if ( $element_options['wishlist_off_canvas'] || $element_options['is_customize_preview'] ) {
        // could be via default wp
	    if ( function_exists('etheme_enqueue_style')) {
		    etheme_enqueue_style( 'off-canvas' );
	    }
    }
    
    if ( $element_options['etheme_mini_wishlist_content_type'] || $element_options['is_customize_preview'] ) {
	    if ( function_exists('etheme_enqueue_style')) {
		    etheme_enqueue_style( 'cart-widget' );
	    }
    }
?>

<div class="et_element et_b_header-wishlist <?php echo $element_options['wrapper_class']; ?>" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
    <?php echo header_wishlist_callback(); ?>
</div>

<?php unset($element_options);