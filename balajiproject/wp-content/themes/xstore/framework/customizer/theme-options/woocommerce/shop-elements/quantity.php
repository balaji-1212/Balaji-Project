<?php
/**
 * The template created for displaying shop icons options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'shop-quantity' => array(
			'name'       => 'shop-quantity',
			'title'      => esc_html__( 'Quantity', 'xstore' ),
			'panel'      => 'shop-elements',
			'icon'       => 'dashicons-chart-pie',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
	
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/shop-quantity' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $light_sep_style ) {
	$args = array();
	
	// Array of fields
	$args = array(
        'shop_quantity_type' => array(
            'name' => 'shop_quantity_type',
            'type' => 'select',
            'settings' => 'shop_quantity_type',
            'label' => esc_html__('Quantity type', 'xstore'),
            'description' => esc_html__('Choose the quantity type.', 'xstore'),
            'section' => 'shop-quantity',
            'default' => 'input',
            'choices' => array(
                'input' => esc_html__('Input', 'xstore'),
                'select' => esc_html__('Select', 'xstore'),
            ),
        ),

        'shop_quantity_select_ranges' => array(
            'name'        => 'shop_quantity_select_ranges',
            'type'        => 'etheme-textarea',
            'settings'    => 'shop_quantity_select_ranges',
            'label'       => esc_html__( 'Quantity ranges', 'xstore' ),
            'description' => esc_html__( 'Add variants and allow the customer to choose the products quantity shown in select. Enter each value in one line and can use the range e.g "1-5".', 'xstore' ),
            'section'     => 'shop-quantity',
            'default'     => '1-5',
            'active_callback' => array(
                array(
                    'setting' => 'shop_quantity_type',
                    'operator' => '==',
                    'value' => 'select',
                ),
            ),
        ),
	);

    if ( get_option( 'etheme_single_product_builder', false ) ) {
        $args['go_to_section_spb_product_cart_form_direction'] = array(
            'name' => 'go_to_section_spb_product_cart_form_direction',
            'type' => 'custom',
            'settings' => 'go_to_section_spb_product_cart_form_direction',
            'section' => 'shop-quantity',
            'default' => '<span class="et_edit" data-parent="product_cart_form" data-section="product_cart_form_direction_et-desktop" style="padding: 5px 7px; border-radius: 5px; background: #222; color: #fff;">' . esc_html__('Single Product Builder quantity', 'xstore') . '</span>',
        );
    }
	
	return array_merge( $fields, $args );
	
} );