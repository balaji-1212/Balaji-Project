<?php
/**
 * The template created for displaying single product cart form options
 *
 * @since   1.5.0
 * @version 1.0.2
 * last changes in 1.5.5
 * @log     1.0.2
 * ADDED: buy_now_btn
 * ADDED: show single stock
 *
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_cart_form' => array(
			'name'       => 'product_cart_form',
			'title'      => esc_html__( 'Add to cart & quantity', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-cart',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/product_cart_form', function ( $fields ) use ( $separators, $strings, $choices, $sep_style ) {
	$args = array();
	
	// Array of fields
	$args = array(
		// content separator
		'product_quantity_content_separator'        => array(
			'name'     => 'product_quantity_content_separator',
			'type'     => 'custom',
			'settings' => 'product_quantity_content_separator',
			'section'  => 'product_cart_form',
			'default'  => $separators['content'],
		),
		
		// product_quantity_style
		'product_quantity_style_et-desktop'         => array(
			'name'      => 'product_quantity_style_et-desktop',
			'type'      => 'radio-image',
			'settings'  => 'product_quantity_style_et-desktop',
			'label'     => $strings['label']['style'],
			'section'   => 'product_cart_form',
			'default'   => 'simple',
			'priority'  => 10,
			'choices'   => array(
				'simple' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/product-add-to-cart/1.svg',
				'circle' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/product-add-to-cart/2.svg',
				'square' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/product-add-to-cart/3.svg',
                'select' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/product-add-to-cart/5.svg',
				'none'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/product-add-to-cart/4.svg',
			),
//			'transport' => 'postMessage',
//			'js_vars'   => array(
//				array(
//					'element'  => '.quantity-wrapper',
//					'function' => 'toggleClass',
//					'class'    => 'type-simple',
//					'value'    => 'simple'
//				),
//				array(
//					'element'  => '.quantity-wrapper',
//					'function' => 'toggleClass',
//					'class'    => 'type-circle',
//					'value'    => 'circle'
//				),
//				array(
//					'element'  => '.quantity-wrapper',
//					'function' => 'toggleClass',
//					'class'    => 'type-square',
//					'value'    => 'square'
//				),
//				array(
//					'element'  => '.quantity-wrapper',
//					'function' => 'toggleClass',
//					'class'    => 'type-none',
//					'value'    => 'none'
//				),
//				array(
//					'element'  => '.quantity-wrapper span.et-icon',
//					'function' => 'toggleClass',
//					'class'    => 'none',
//					'value'    => 'none'
//				),
//			),
		),
		
		// product_cart_form_direction
		'product_cart_form_direction_et-desktop'    => array(
			'name'      => 'product_cart_form_direction_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'product_cart_form_direction_et-desktop',
			'label'     => $strings['label']['direction'],
			'section'   => 'product_cart_form',
			'default'   => 'row',
			'choices'   => $choices['direction']['type2'],
			'transport' => 'postMessage',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product-builder form.cart, .single-product-builder form.cart .woocommerce-variation-add-to-cart',
					'property' => 'flex-direction',
				),
			),
			'js_vars'   => array(
				array(
					'element'  => '.single-product-builder form.cart span.hidden',
					'function' => 'toggleClass',
					'class'    => 'dir-column',
					'value'    => 'column'
				),
				array(
					'element'  => '.single-product-builder form.cart, .single-product-builder form.cart .woocommerce-variation-add-to-cart',
					'function' => 'css',
					'property' => 'flex-direction',
				),
			),
		),

        'product_quantity_select_ranges' => array(
            'name'        => 'product_quantity_select_ranges',
            'type'        => 'etheme-textarea',
            'settings'    => 'product_quantity_select_ranges',
            'label'       => esc_html__( 'Quantity ranges', 'xstore-core' ),
            'description' => esc_html__( 'Add variants and allow the customer to choose the products quantity shown in select. Enter each value in one line and can use the range e.g "1-5".', 'xstore-core' ),
            'section'     => 'product_cart_form',
            'default'     => get_theme_mod('shop_quantity_select_ranges', '1-5'),
            'active_callback' => array(
                array(
                    'setting' => 'product_quantity_style_et-desktop',
                    'operator' => '==',
                    'value' => 'select',
                ),
            ),
        ),
		
		// content separator
		'product_cart_style_separator'              => array(
			'name'     => 'product_cart_style_separator',
			'type'     => 'custom',
			'settings' => 'product_cart_style_separator',
			'section'  => 'product_cart_form',
			'default'  => $separators['style'],
		),
		
		// product_add_to_cart_fonts
		'product_add_to_cart_fonts_et-desktop'      => array(
			'name'      => 'product_add_to_cart_fonts_et-desktop',
			'type'      => 'typography',
			'settings'  => 'product_add_to_cart_fonts_et-desktop',
			'section'   => 'product_cart_form',
			'default'   => array(
				// 'font-family'    => '',
				// 'variant'        => 'regular',
				// 'font-size'      => '14px',
				// 'line-height'    => '1.5',
				// 'letter-spacing' => '0',
				// 'color'          => '#555',
				'text-transform' => 'none',
				// 'text-align'     => 'left',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.single-product-builder .single_add_to_cart_button, .single-product-builder .etheme-sticky-cart .etheme_custom_add_to_cart.single_add_to_cart_button',
				),
			),
		),
		
		// product_add_to_cart_spacing
		'product_add_to_cart_spacing_et-desktop'    => array(
			'name'      => 'product_add_to_cart_spacing_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_add_to_cart_spacing_et-desktop',
			'label'     => $strings['label']['elements_spacing'],
			'section'   => 'product_cart_form',
			'default'   => 15,
			'choices'   => array(
				'min'  => '0',
				'max'  => '30',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-add-to-cart-button-spacing',
					'units'    => 'px'
				)
			)
		),
		
		// product_add_to_cart_size
		'product_add_to_cart_size_et-desktop'       => array(
			'name'      => 'product_add_to_cart_size_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_add_to_cart_size_et-desktop',
			'label'     => $strings['label']['size_proportion'],
			'section'   => 'product_cart_form',
			'default'   => 1,
			'choices'   => array(
				'min'  => '0',
				'max'  => '5',
				'step' => '.1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-add-to-cart-button-proportion',
				)
			)
		),
		
		// product_add_to_cart_min_width
		'product_add_to_cart_min_width_et-desktop'  => array(
			'name'      => 'product_add_to_cart_min_width_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_add_to_cart_min_width_et-desktop',
			'label'     => esc_html__( 'Min width (px)', 'xstore-core' ),
			'section'   => 'product_cart_form',
			'default'   => 120,
			'choices'   => array(
				'min'  => '90',
				'max'  => '350',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product-builder .single_add_to_cart_button',
					'property' => 'min-width',
					'units'    => 'px'
				)
			)
		),
		
		// product_add_to_cart_min_height
		'product_add_to_cart_min_height_et-desktop' => array(
			'name'      => 'product_add_to_cart_min_height_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_add_to_cart_min_height_et-desktop',
			'label'     => $strings['label']['min_height'],
			'section'   => 'product_cart_form',
			'default'   => 40,
			'choices'   => array(
				'min'  => '30',
				'max'  => '150',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product-builder .single_add_to_cart_button',
					'property' => 'min-height',
					'units'    => 'px'
				)
			)
		),
		
		// product_add_to_cart_radius
		'product_add_to_cart_radius_et-desktop'     => array(
			'name'      => 'product_add_to_cart_radius_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_add_to_cart_radius_et-desktop',
			'label'     => $strings['label']['border_radius'],
			'section'   => 'product_cart_form',
			'default'   => 0,
			'choices'   => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product-builder .single_add_to_cart_button, .single-product-builder .single_add_to_cart_button.button, .single-product-builder .etheme-sticky-cart .etheme_custom_add_to_cart.single_add_to_cart_button,
					.single-product-builder .single_add_to_cart_button:hover, .single-product-builder .single_add_to_cart_button.button:hover, .single-product-builder .etheme-sticky-cart .etheme_custom_add_to_cart.single_add_to_cart_button:hover,
					.single-product-builder .single_add_to_cart_button:focus, .single-product-builder .single_add_to_cart_button.button:focus, .single-product-builder .etheme-sticky-cart .etheme_custom_add_to_cart.single_add_to_cart_button:focus',
					'property' => 'border-radius',
					'units'    => 'px'
				)
			)
		),
		
		// product_cart_background_custom
		'product_cart_background_custom_et-desktop' => array(
			'name'        => 'product_cart_background_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'product_cart_background_custom_et-desktop',
			'label'       => esc_html__( 'Button background color', 'xstore-core' ),
			'description' => $strings['description']['wcag_bg_color'],
			'section'     => 'product_cart_form',
			'default'     => '#222222',
			'choices'     => array(
				'alpha' => true,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-add-to-cart-background-color'
				),
			),
		),
		
		'product_cart_color_et-desktop'                          => array(
			'name'        => 'product_cart_color_et-desktop',
			'settings'    => 'product_cart_color_et-desktop',
			'label'       => esc_html__( 'Button color', 'xstore-core' ),
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'product_cart_form',
			'default'     => '#ffffff',
			'choices'     => array(
				'setting' => 'setting(product_cart_form)(product_cart_background_custom_et-desktop)',
				// 'maxHueDiff'          => 60,   // Optional.
				// 'stepHue'             => 15,   // Optional.
				// 'maxSaturation'       => 0.5,  // Optional.
				// 'stepSaturation'      => 0.1,  // Optional.
				// 'stepLightness'       => 0.05, // Optional.
				// 'precissionThreshold' => 6,    // Optional.
				// 'contrastThreshold'   => 4.5   // Optional.
				'show'    => array(
					// 'auto'        => false,
					// 'custom'      => false,
					'recommended' => false,
				),
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-add-to-cart-color',
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product-builder .single_add_to_cart_button, .single-product-builder .etheme-sticky-cart .etheme_custom_add_to_cart.single_add_to_cart_button',
					'property' => '--loader-side-color'
				),
			),
		),
		
		// product_cart_background_custom_hover
		'product_cart_background_custom_hover_et-desktop'        => array(
			'name'        => 'product_cart_background_custom_hover_et-desktop',
			'type'        => 'color',
			'settings'    => 'product_cart_background_custom_hover_et-desktop',
			'label'       => esc_html__( 'Button background color (hover)', 'xstore-core' ),
			'description' => $strings['description']['wcag_bg_color'],
			'section'     => 'product_cart_form',
			'default'     => '#555555',
			'choices'     => array(
				'alpha' => true,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-add-to-cart-hover-background-color',
				),
			),
		),
		'product_cart_color_hover_et-desktop'                    => array(
			'name'        => 'product_cart_color_hover_et-desktop',
			'settings'    => 'product_cart_color_hover_et-desktop',
			'label'       => esc_html__( 'Button color (hover)', 'xstore-core' ),
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'product_cart_form',
			'default'     => '#ffffff',
			'choices'     => array(
				'setting' => 'setting(product_cart_form)(product_cart_background_custom_hover_et-desktop)',
				// 'maxHueDiff'          => 60,   // Optional.
				// 'stepHue'             => 15,   // Optional.
				// 'maxSaturation'       => 0.5,  // Optional.
				// 'stepSaturation'      => 0.1,  // Optional.
				// 'stepLightness'       => 0.05, // Optional.
				// 'precissionThreshold' => 6,    // Optional.
				// 'contrastThreshold'   => 4.5   // Optional.
				'show'    => array(
					// 'auto'        => false,
					// 'custom'      => false,
					'recommended' => false,
				),
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-add-to-cart-hover-color',
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product-builder .single_add_to_cart_button:hover, .single-product-builder .single_add_to_cart_button:focus, .single-product-builder .single_add_to_cart_button:hover:focus, .single-product-builder .etheme-sticky-cart .etheme_custom_add_to_cart.single_add_to_cart_button:hover',
					'property' => '--loader-side-color'
				),
			),
		),
		
		// style separator
		'product_cart_buy_now_style_separator'                   => array(
			'name'            => 'product_cart_buy_now_style_separator',
			'type'            => 'custom',
			'settings'        => 'product_cart_buy_now_style_separator',
			'section'         => 'product_cart_form',
			'default'         => '<div style="' . $sep_style . '"><span class="dashicons dashicons-feedback"></span> <span style="padding-left: 3px;">' . esc_html__( 'Buy now button styles', 'xstore-core' ) . '</span></div>',
			'active_callback' => array(
				array(
					'setting'  => 'buy_now_btn',
					'operator' => '==',
					'value'    => 1,
				),
			)
		),
		
		// product_cart_buy_now_color
		'product_cart_buy_now_color_et-desktop'                  => array(
			'name'            => 'product_cart_buy_now_color_et-desktop',
			'type'            => 'color',
			'settings'        => 'product_cart_buy_now_color_et-desktop',
			'label'           => esc_html__( 'Button color', 'xstore-core' ),
			'section'         => 'product_cart_form',
			'default'         => '#ffffff',
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'buy_now_btn',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-buy-now-button-color'
				),
			),
		),
		
		// product_cart_buy_now_background_color
		'product_cart_buy_now_background_color_et-desktop'       => array(
			'name'            => 'product_cart_buy_now_background_color_et-desktop',
			'type'            => 'color',
			'settings'        => 'product_cart_buy_now_background_color_et-desktop',
			'label'           => esc_html__( 'Button background color', 'xstore-core' ),
			'section'         => 'product_cart_form',
			'default'         => '#339438',
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'buy_now_btn',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-buy-now-button-background-color'
				),
			),
		),
		
		// product_cart_buy_now_color_hover
		'product_cart_buy_now_color_hover_et-desktop'            => array(
			'name'            => 'product_cart_buy_now_color_hover_et-desktop',
			'type'            => 'color',
			'settings'        => 'product_cart_buy_now_color_hover_et-desktop',
			'label'           => esc_html__( 'Button color (hover)', 'xstore-core' ),
			'section'         => 'product_cart_form',
			'default'         => '#ffffff',
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'buy_now_btn',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-buy-now-button-color-hover',
				),
			),
		),
		
		// product_cart_buy_now_background_color_hover
		'product_cart_buy_now_background_color_hover_et-desktop' => array(
			'name'            => 'product_cart_buy_now_background_color_hover_et-desktop',
			'type'            => 'color',
			'settings'        => 'product_cart_buy_now_background_color_hover_et-desktop',
			'label'           => esc_html__( 'Button background color (hover)', 'xstore-core' ),
			'section'         => 'product_cart_form',
			'default'         => '#2e7d32',
			'choices'         => array(
				'alpha' => true,
			),
			'transport'       => 'auto',
			'active_callback' => array(
				array(
					'setting'  => 'buy_now_btn',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-buy-now-button-background-color-hover'
				),
			),
		),
		
		// content separator
		'product_form_content_separator'                         => array(
			'name'     => 'product_form_content_separator',
			'type'     => 'custom',
			'settings' => 'product_form_content_separator',
			'section'  => 'product_cart_form',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-feedback"></span> <span style="padding-left: 3px;">' . esc_html__( 'Cart form', 'xstore-core' ) . '</span></div>',
		),
		'product_cart_form_box_model_et-desktop'                 => array(
			'name'        => 'product_cart_form_box_model_et-desktop',
			'settings'    => 'product_cart_form_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'product_cart_form',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '15px',
				'margin-left'         => '0px',
				'border-top-width'    => '0px',
				'border-right-width'  => '0px',
				'border-bottom-width' => '0px',
				'border-left-width'   => '0px',
				'padding-top'         => '0px',
				'padding-right'       => '0px',
				'padding-bottom'      => '0px',
				'padding-left'        => '0px',
			),
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.single-product-builder form.cart'
				)
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.single-product-builder form.cart' )
		),
		
		// product_cart_form_border
		'product_cart_form_border_et-desktop'                    => array(
			'name'      => 'product_cart_form_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'product_cart_form_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'product_cart_form',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product-builder form.cart',
					'property' => 'border-style'
				)
			)
		),
		
		// product_cart_form_border_color_custom
		'product_cart_form_border_color_custom_et-desktop'       => array(
			'name'      => 'product_cart_form_border_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_cart_form_border_color_custom_et-desktop',
			'section'   => 'product_cart_form',
			'choices'   => array(
				'alpha' => true
			),
			'default'   => '#e1e1e1',
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product-builder form.cart',
					'property' => 'border-color',
				),
			),
		),
		
		// advanced separator
		'product_cart_advanced_separator'                        => array(
			'name'     => 'product_cart_advanced_separator',
			'type'     => 'custom',
			'settings' => 'product_cart_advanced_separator',
			'section'  => 'product_cart_form',
			'default'  => $separators['advanced'],
		),
		
		// stretch_add_to_cart
		'stretch_add_to_cart_et-desktop'                         => array(
			'name'     => 'stretch_add_to_cart_et-desktop',
			'type'     => 'toggle',
			'settings' => 'stretch_add_to_cart_et-desktop',
			'label'    => esc_html__( 'Stretch add to cart button', 'xstore-core' ),
			'section'  => 'product_cart_form',
			'default'  => 0,
		),
		
		'ajax_add_to_cart'              => array(
			'name'        => 'ajax_add_to_cart',
			'type'        => 'toggle',
			'settings'    => 'ajax_add_to_cart',
			'label'       => esc_html__( 'AJAX add to cart for simple and variable products', 'xstore-core' ),
			'description' => esc_html__( 'Turn on to enable adding to cart without page refresh for the simple and variable products. Important: third party plugins may have conflict with this.', 'xstore-core' ),
			'section'     => 'product_cart_form',
			'default'     => 1,
		),
		
		// sticky_add_to_cart
		'sticky_add_to_cart_et-desktop' => array(
			'name'      => 'sticky_add_to_cart_et-desktop',
			'type'      => 'toggle',
			'settings'  => 'sticky_add_to_cart_et-desktop',
			'label'     => esc_html__( 'Sticky add to cart bar', 'xstore-core' ),
			'section'   => 'product_cart_form',
			'default'   => 0,
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.etheme-sticky-cart',
					'function' => 'toggleClass',
					'class'    => 'dt-hide',
					'value'    => false
				),
				array(
					'element'  => '.etheme-sticky-cart',
					'function' => 'toggleClass',
					'class'    => 'mob-hide',
					'value'    => false
				),
			),
		),
		
		'sticky_added_to_cart_message' => array(
			'name'      => 'sticky_added_to_cart_message',
			'type'      => 'toggle',
			'settings'  => 'sticky_added_to_cart_message',
			'label'     => esc_html__( 'Fixed added to cart message', 'xstore-core' ),
			'section'   => 'product_cart_form',
			'default'   => 1,
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => 'body.single-product',
					'function' => 'toggleClass',
					'class'    => 'sticky-message-on',
					'value'    => true
				),
				array(
					'element'  => 'body.single-product',
					'function' => 'toggleClass',
					'class'    => 'sticky-message-off',
					'value'    => false
				),
			),
		),
		
		// buy now btn
		'buy_now_btn'                  => array(
			'name'     => 'buy_now_btn',
			'type'     => 'toggle',
			'settings' => 'buy_now_btn',
			'label'    => esc_html__( 'Buy Now button', 'xstore-core' ),
			'section'  => 'product_cart_form',
			'default'  => 0,
		),
		
		// show single stock
		'show_single_stock'            => array(
			'name'     => 'show_single_stock',
			'type'     => 'toggle',
			'settings' => 'show_single_stock',
			'label'    => esc_html__( 'Show product stock status', 'xstore-core' ),
			'section'  => 'product_cart_form',
			'default'  => 0,
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
