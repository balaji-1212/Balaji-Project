<?php
/**
 * The template created for displaying product sale label options
 *
 * @version 1.0.1
 * @since   1.5
 * last changes in 1.5.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_sale_label' => array(
			'name'       => 'product_sale_label',
			'title'      => esc_html__( 'Sale label', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-awards',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/product_sale_label', function ( $fields ) use ( $separators, $strings ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator 
		'product_sale_label_content_separator'            => array(
			'name'     => 'product_sale_label_content_separator',
			'type'     => 'custom',
			'settings' => 'product_sale_label_content_separator',
			'section'  => 'product_sale_label',
			'default'  => $separators['content'],
		),
		
		// product_sale_label_sale_type
		'product_sale_label_type_et-desktop'              => array(
			'name'      => 'product_sale_label_type_et-desktop',
			'type'      => 'radio-image',
			'settings'  => 'product_sale_label_type_et-desktop',
			'label'     => $strings['label']['type'],
			'section'   => 'product_sale_label',
			'default'   => 'square',
			'choices'   => array(
				'circle' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/global/sale-label/Style-sale-label-1.svg',
				'square' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/global/sale-label/Style-sale-label-2.svg',
				'none'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/global/sale-label/None.svg',
			),
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.single-sale',
					'function' => 'toggleClass',
					'class'    => 'dt-hide',
					'value'    => 'none'
				),
				array(
					'element'  => '.single-sale',
					'function' => 'toggleClass',
					'class'    => 'mob-hide',
					'value'    => 'none'
				),
				array(
					'element'  => '.single-sale',
					'function' => 'toggleClass',
					'class'    => 'type-circle',
					'value'    => 'circle'
				),
				array(
					'element'  => '.single-sale',
					'function' => 'toggleClass',
					'class'    => 'type-square',
					'value'    => 'square'
				),
			),
		),
		
		// product_sale_label_percentage
		'product_sale_label_percentage_et-desktop'        => array(
			'name'     => 'product_sale_label_percentage_et-desktop',
			'type'     => 'toggle',
			'settings' => 'product_sale_label_percentage_et-desktop',
			'label'    => esc_html__( 'Value percentage', 'xstore-core' ),
			'section'  => 'product_sale_label',
			'default'  => 0,
		),
		
		// product_sale_label_sale_text 
		'product_sale_label_text_et-desktop'              => array(
			'name'      => 'product_sale_label_text_et-desktop',
			'type'      => 'etheme-text',
			'settings'  => 'product_sale_label_text_et-desktop',
			'label'     => esc_html__( 'Sale text', 'xstore-core' ),
			'section'   => 'product_sale_label',
			'default'   => esc_html__( 'Sale', 'xstore-core' ),
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.single-sale:not(.with-percentage)',
					'function' => 'html',
				),
			),
		),
		
		// style separator 
		'product_sale_label_style_separator'              => array(
			'name'     => 'product_sale_label_style_separator',
			'type'     => 'custom',
			'settings' => 'product_sale_label_style_separator',
			'section'  => 'product_sale_label',
			'default'  => $separators['style'],
		),
		
		// product_sale_label_sale_position
		'product_sale_label_position_et-desktop'          => array(
			'name'      => 'product_sale_label_position_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'product_sale_label_position_et-desktop',
			'label'     => esc_html__( 'Position', 'xstore-core' ),
			'section'   => 'product_sale_label',
			'default'   => 'right',
			'priority'  => 10,
			'choices'   => array(
				'left'  => esc_html__( 'Left', 'xstore-core' ),
				'right' => esc_html__( 'Right', 'xstore-core' ),
			),
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.single-sale',
					'function' => 'toggleClass',
					'class'    => 'right',
					'value'    => 'right'
				),
				array(
					'element'  => '.single-sale',
					'function' => 'toggleClass',
					'class'    => 'left',
					'value'    => 'left'
				),
			),
		),
		
		// product_sale_label_sale_position_asix
		'product_sale_label_position_asix_et-desktop'     => array(
			'name'      => 'product_sale_label_position_asix_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_sale_label_position_asix_et-desktop',
			'label'     => esc_html__( 'Asix (px)', 'xstore-core' ),
			'section'   => 'product_sale_label',
			'default'   => 12,
			'choices'   => array(
				'min'  => '-50',
				'max'  => '50',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.onsale.single-sale',
					'property' => 'top',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.onsale.single-sale.left',
					'property' => 'left',
					'units'    => 'px'
				),
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body.rtl .onsale.single-sale.left',
                    'property' => 'right',
                    'units'    => 'px'
                ),
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body.rtl .onsale.single-sale.left',
                    'property' => 'left',
                    'value_pattern'    => 'auto'
                ),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.onsale.single-sale.right',
					'property' => 'right',
					'units'    => 'px'
				),
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body.rtl .onsale.single-sale.right',
                    'property' => 'left',
                    'units'    => 'px'
                ),
                array(
                    'context'  => array( 'editor', 'front' ),
                    'element'  => 'body.rtl .onsale.single-sale.right',
                    'property' => 'right',
                    'value_pattern'    => 'auto'
                ),
			),
		),
		
		// product_sale_label_sale_zoom
		'product_sale_label_content_zoom_et-desktop'      => array(
			'name'      => 'product_sale_label_content_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_sale_label_content_zoom_et-desktop',
			'label'     => esc_html__( 'Text zoom (proportion)', 'xstore-core' ),
			'section'   => 'product_sale_label',
			'default'   => 1,
			'choices'   => array(
				'min'  => '.7',
				'max'  => '5',
				'step' => '.1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--single-sale-zoom-proportion'
				),
			),
		),
		
		// product_sale_label_sale_zoom
		'product_sale_label_zoom_et-desktop'              => array(
			'name'      => 'product_sale_label_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_sale_label_zoom_et-desktop',
			'label'     => esc_html__( 'Height-width (min)', 'xstore-core' ) . esc_html__( '"for circle type only"', 'xstore-core' ),
			'section'   => 'product_sale_label',
			'default'   => 50,
			'choices'   => array(
				'min'  => '10',
				'max'  => '200',
				'step' => '1',
			),
			// 'active_callback' => array(
			// 	array(
			// 		'setting'  => 'product_sale_label_type_et-desktop',
			// 		'operator' => '==',
			// 		'value'    => 'circle',
			// 	),
			// ),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-sale.type-circle',
					'property' => 'min-height',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-sale.type-circle',
					'property' => 'min-width',
					'units'    => 'px'
				),
			),
		),
		
		// product_sale_label_sale_border_radius
		'product_sale_label_border_radius_et-desktop'     => array(
			'name'      => 'product_sale_label_border_radius_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_sale_label_border_radius_et-desktop',
			'label'     => $strings['label']['border_radius'] . esc_html__( '"for circle type only"', 'xstore-core' ),
			'section'   => 'product_sale_label',
			'default'   => 50,
			'choices'   => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			// 'active_callback' => array(
			// 	array(
			// 		'setting'  => 'product_sale_label_type_et-desktop',
			// 		'operator' => '==',
			// 		'value'    => 'circle',
			// 	),
			// ),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-sale.type-circle',
					'property' => 'border-radius',
					'units'    => 'px'
				),
			),
		),
		
		// product_sale_label_sale_background_custom
		'product_sale_label_background_custom_et-desktop' => array(
			'name'        => 'product_sale_label_background_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'product_sale_label_background_custom_et-desktop',
			'label'       => $strings['label']['wcag_bg_color'],
			'description' => $strings['description']['wcag_bg_color'],
			'section'     => 'product_sale_label',
			'default'     => '#c62828',
			'choices'     => array(
				'alpha' => true
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.onsale.single-sale',
					'property' => 'background-color'
				),
			),
		),
		
		// product_sale_label_sale_color
		'product_sale_label_color_et-desktop'             => array(
			'name'        => 'product_sale_label_color_et-desktop',
			'settings'    => 'product_sale_label_color_et-desktop',
			'label'       => $strings['label']['wcag_color'],
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'product_sale_label',
			'default'     => '#ffffff',
			'choices'     => array(
				'setting' => 'setting(product_sale_label)(product_sale_label_background_custom_et-desktop)',
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
					'element'  => '.onsale.single-sale',
					'property' => 'color'
				)
			),
		),
	
	
	);
	
	return array_merge( $fields, $args );
	
} );
