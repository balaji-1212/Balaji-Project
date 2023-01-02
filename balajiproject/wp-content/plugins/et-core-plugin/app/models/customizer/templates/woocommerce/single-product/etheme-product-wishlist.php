<?php 
/**
 * The template for single product wishlist
 *
 * @since   1.5.0
 * @version 1.0.0
 */

add_filter('yith_wcwl_add_to_wishlist_params', 'etheme_yith_wcwl_add_to_wishlist_params', 1, 10);
add_filter('yith_wcwl_add_to_wishlist_icon_html', 'etheme_yith_wcwl_add_to_wishlist_icon_html', 2, 999);
if ( function_exists('etheme_wishlist_btn') ) {
    $custom_options = array(
        'class' => 'single-wishlist',
    );
    if ( get_theme_mod('xstore_wishlist', false) ) {
        $custom_icon = false;
        $custom_options = array(
            'has_tooltip' => get_theme_mod('product_wishlist_tooltip', false),
            'redirect_on_remove' => get_theme_mod('product_wishlist_redirect_on_remove', false),
            'add_text' => get_theme_mod('product_wishlist_label_add_to_wishlist', esc_html__('Add to wishlist', 'xstore-core')),
            'remove_text' => get_theme_mod('product_wishlist_label_browse_wishlist', esc_html__('Browse wishlist', 'xstore-core')),
            'only_icon' => get_theme_mod('product_wishlist_only_icon', false),
        );
        switch (get_theme_mod('product_wishlist_icon_et-desktop', 'type1')) {
            case 'type1':
                $custom_options['custom_icon'] = false;
                $custom_options['add_icon_class'] = 'et-heart';
                $custom_options['remove_icon_class'] = 'et-heart-o';
                break;
            case 'type2':
                $custom_options['custom_icon'] = false;
                $custom_options['add_icon_class'] = 'et-star';
                $custom_options['remove_icon_class'] = 'et-star-o';
                break;
            case 'custom':
                $icon_custom = get_theme_mod('product_wishlist_icon_custom_svg_et-desktop', '');
                $icon_custom = isset($icon_custom['id']) ? $icon_custom['id'] : '';
                if ($icon_custom != '') {
                    $custom_options['custom_icon'] = etheme_get_svg_icon($icon_custom);
                    $custom_options['add_icon_class'] = false;
                    $custom_options['remove_icon_class'] = false;
                } else {
                    $custom_options['custom_icon'] = false;
                    $custom_options['add_icon_class'] = 'et-heart';
                    $custom_options['remove_icon_class'] = 'et-heart-o';
                }
                break;
            case 'none':
                $custom_options['show_icon'] = false;
                $custom_options['custom_icon'] = false;
                $custom_options['add_icon_class'] = false;
                $custom_options['remove_icon_class'] = false;
                break;
        }
    }
    echo etheme_wishlist_btn(array_merge(array(
        'is_single' => true,
        'is_spb' => true), $custom_options)
    );
}
remove_filter('yith_wcwl_add_to_wishlist_icon_html', 'etheme_yith_wcwl_add_to_wishlist_icon_html', 2, 999);
remove_filter('yith_wcwl_add_to_wishlist_params', 'etheme_yith_wcwl_add_to_wishlist_params', 1, 10);