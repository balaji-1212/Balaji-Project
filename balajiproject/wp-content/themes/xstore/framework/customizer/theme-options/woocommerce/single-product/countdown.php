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
            'title' => esc_html__('Countdown', 'xstore'),
            'panel' => 'single-product-page',
            'icon' => 'dashicons-hourglass',
            'type' => 'kirki-lazy',
            'dependency' => array()
        )
    );

    return array_merge($sections, $args);
});

$hook = class_exists('ETC_Initial') ? 'et/customizer/add/fields/single-product-page-countdown' : 'et/customizer/add/fields';
add_filter($hook, function ($fields) {
    $args = array();

    // Array of fields
    $args = array(

        'single_countdown_type' => array(
            'name' => 'single_countdown_type',
            'type' => 'radio-image',
            'settings' => 'single_countdown_type',
            'label' => esc_html__('Type', 'xstore'),
            'description' => esc_html__('Choose the countdown type on the single product page.', 'xstore'),
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
            'label' => esc_html__('Title', 'xstore'),
            'description' => esc_html__('Text before', 'xstore'),
            'section' => 'single-product-page-countdown',
            'default' => esc_html__('{fire} Hurry up! Sale ends in:', 'xstore'),
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

});