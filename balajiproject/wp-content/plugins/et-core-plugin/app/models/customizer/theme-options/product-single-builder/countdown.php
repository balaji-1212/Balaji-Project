<?php
/**
 * The template created for displaying single product layout options
 *
 * @version 0.0.2
 * @since   6.0.0
 * @log     0.0.2
 * ADDED: buy_now_btn
 * ADDED: show single stock
 */

add_filter('et/customizer/add/sections', function ($sections) {

    $args = array(
        'single-product-page-countdown' => array(
            'name' => 'single-product-page-countdown',
            'title' => esc_html__('Countdown', 'xstore-core'),
            'panel' => 'single_product_builder',
            'icon' => 'dashicons-hourglass',
            'type' => 'kirki-lazy',
            'dependency' => array()
        )
    );

    return array_merge($sections, $args);
});

add_filter('et/customizer/add/fields/single-product-page-countdown', function ($fields) {
    $args = array();

    // Array of fields
    $args = array(

        'single_countdown_type' => array(
            'name' => 'single_countdown_type',
            'type'            => 'radio-image',
            'settings' => 'single_countdown_type',
            'label' => esc_html__('Type', 'xstore-core'),
            'description' => esc_html__('Choose the countdown type on the single product page.', 'xstore-core'),
            'section' => 'single-product-page-countdown',
            'default' => 'type2',
            'choices' => array(
                'type1' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/countdown/countdown-1.svg',
                'type2' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/countdown/countdown-2.svg',
                'type3' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/countdown/countdown-3.svg',
            ),
        ),

        'single_countdown_title' => array(
            'name' => 'single_countdown_title',
            'type' => 'etheme-text',
            'settings' => 'single_countdown_title',
            'label' => esc_html__('Title', 'xstore-core'),
            'description' => esc_html__('Text before', 'xstore-core'),
            'section' => 'single-product-page-countdown',
            'default' => esc_html__('{fire} Hurry up! Sale ends in:', 'xstore-core'),
            'active_callback' => array(
                array(
                    'setting' => 'single_countdown_type',
                    'operator' => '==',
                    'value' => 'type3',
                ),
            ),
        ),

    );

    return array_merge($fields, $args);

} );