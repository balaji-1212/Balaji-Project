<?php
/**
 * The template created for displaying single button options
 *
 * @version 1.0.1
 * @since   1.5
 * last changes in 1.5.5
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'single-request-quote' => array(
			'name'       => 'single-request-quote',
			'title'      => esc_html__( 'Request a quote', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-editor-help',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/single-request-quote', function ( $fields ) use ( $separators, $strings, $choices, $box_models ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'single_product_request_quote_button_content_separator' => array(
			'name'     => 'single_product_request_quote_button_content_separator',
			'type'     => 'custom',
			'settings' => 'single_product_request_quote_button_content_separator',
			'section'  => 'single-request-quote',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		
		// request_quote_icon
		'request_quote_icon_et-desktop'                         => array(
			'name'            => 'request_quote_icon_et-desktop',
			'type'            => 'radio-image',
			'settings'        => 'request_quote_icon_et-desktop',
			'label'           => $strings['label']['icon'],
			'description'     => $strings['description']['icons_style'],
			'section'         => 'single-request-quote',
			'default'         => 'type1',
			'choices'         => array(
				'type1'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/request-quote/type1.svg',
				'custom' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-custom.svg',
				'none'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/global/icon-none.svg',
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'request_quote_icon_et-desktop' => array(
					'selector'        => '.single-product-request-quote-wrapper',
					'render_callback' => 'single_product_request_quote_callback',
				),
			),
		),
		
		// request_quote_icon_custom
		'request_quote_icon_custom_et-desktop'                  => array(
			'name'            => 'request_quote_icon_custom_et-desktop',
			'type'            => 'image',
			'settings'        => 'request_quote_icon_custom_et-desktop',
			'label'           => $strings['label']['custom_image_svg'],
			'description'     => $strings['description']['custom_image_svg'],
			'section'         => 'single-request-quote',
			'default'         => '',
			'choices'         => array(
				'save_as' => 'array',
			),
			'active_callback' => array(
				array(
					'setting'  => 'request_quote_icon_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'request_quote_icon_custom_et-desktop' => array(
					'selector'        => '.single-product-request-quote-wrapper',
					'render_callback' => 'single_product_request_quote_callback',
				),
			),
		),
		
		// request_quote_button_text
		'request_quote_button_text_et-desktop'                  => array(
			'name'      => 'request_quote_button_text_et-desktop',
			'type'      => 'etheme-text',
			'settings'  => 'request_quote_button_text_et-desktop',
			'label'     => $strings['label']['button_text'],
			'section'   => 'single-request-quote',
			'default'   => esc_html__( 'Ask an expert', 'xstore-core' ),
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_single-request-quote-button span:not(.et_b-icon)',
					'function' => 'html',
				),
			),
		),
		
		// style separator
		'request_quote_button_style_separator'                  => array(
			'name'     => 'request_quote_button_style_separator',
			'type'     => 'custom',
			'settings' => 'request_quote_button_style_separator',
			'section'  => 'single-request-quote',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// request_quote_button_fonts
		'request_quote_button_fonts_et-desktop'                 => array(
			'name'      => 'request_quote_button_fonts_et-desktop',
			'type'      => 'typography',
			'settings'  => 'request_quote_button_fonts_et-desktop',
			'label'     => $strings['label']['fonts'],
			'section'   => 'single-request-quote',
			'default'   => array(
				// 'font-family'    => '',
				// 'variant'        => 'regular',
				// 'font-size'      => '15px',
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
					'element' => '.et_b_single-request-quote-button',
				),
			),
		),
		
		// request_quote_button_zoom
		'request_quote_button_zoom_et-desktop'                  => array(
			'name'      => 'request_quote_button_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'request_quote_button_zoom_et-desktop',
			'label'     => $strings['label']['button_size_proportion'],
			'section'   => 'single-request-quote',
			'default'   => 1,
			'choices'   => array(
				'min'  => '.2',
				'max'  => '3',
				'step' => '.01',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_single-request-quote-button',
					'property'      => 'font-size',
					'value_pattern' => 'calc(var(--content-zoom, 1rem) * $)'
				),
			),
		),
		
		// request_quote_button_content_align
		'request_quote_button_content_align_et-desktop'         => array(
			'name'        => 'request_quote_button_content_align_et-desktop',
			'type'        => 'radio-buttonset',
			'settings'    => 'request_quote_button_content_align_et-desktop',
			'label'       => $strings['label']['alignment'],
			'description' => $strings['description']['size_bigger_attention'],
			'section'     => 'single-request-quote',
			'default'     => 'start',
			'choices'     => $choices['alignment'],
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.single-product-request-quote-wrapper',
					'function' => 'toggleClass',
					'class'    => 'justify-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.single-product-request-quote-wrapper',
					'function' => 'toggleClass',
					'class'    => 'justify-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.single-product-request-quote-wrapper',
					'function' => 'toggleClass',
					'class'    => 'justify-content-end',
					'value'    => 'end'
				),
			),
		),
		
		// request_quote_button_background_custom
		'request_quote_button_background_custom_et-desktop'     => array(
			'name'      => 'request_quote_button_background_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'request_quote_button_background_custom_et-desktop',
			'label'     => $strings['label']['bg_color'],
			'section'   => 'single-request-quote',
			'default'   => '#000000',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_single-request-quote-button',
					'property' => 'background-color',
				),
			),
		),
		
		'request_quote_button_color_et-desktop'                   => array(
			'name'        => 'request_quote_button_color_et-desktop',
			'settings'    => 'request_quote_button_color_et-desktop',
			'label'       => $strings['label']['wcag_color'],
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'single-request-quote',
			'default'     => '#ffffff',
			'choices'     => array(
				'setting' => 'setting(single-request-quote)(request_quote_button_background_custom_et-desktop)',
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
					'element'  => '.et_b_single-request-quote-button',
					'property' => 'color',
				),
			),
		),
		
		// request_quote_button_background_hover_custom
		'request_quote_button_background_hover_custom_et-desktop' => array(
			'name'      => 'request_quote_button_background_hover_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'request_quote_button_background_hover_custom_et-desktop',
			'label'     => esc_html__( 'Background color (hover)', 'xstore-core' ),
			'section'   => 'single-request-quote',
			'default'   => '#ffffff',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_single-request-quote-button:hover',
					'property' => 'background-color',
				),
			),
		),
		
		'request_quote_button_hover_color_et-desktop'   => array(
			'name'        => 'request_quote_button_hover_color_et-desktop',
			'settings'    => 'request_quote_button_hover_color_et-desktop',
			'label'       => $strings['label']['wcag_color_hover'],
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'single-request-quote',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'setting(single-request-quote)(request_quote_button_background_hover_custom_et-desktop)',
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
					'element'  => '.et_b_single-request-quote-button:hover',
					'property' => 'color',
				),
			),
		),
		
		// request_quote_button_border_radius
		'request_quote_button_border_radius_et-desktop' => array(
			'name'      => 'request_quote_button_border_radius_et-desktop',
			'type'      => 'slider',
			'settings'  => 'request_quote_button_border_radius_et-desktop',
			'label'     => $strings['label']['border_radius'],
			'section'   => 'single-request-quote',
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
					'element'  => '.et_b_single-request-quote-button',
					'property' => 'border-radius',
					'units'    => 'px'
				),
			),
		),
		
		'request_quote_button_box_model_et-desktop'                  => array(
			'name'        => 'request_quote_button_box_model_et-desktop',
			'settings'    => 'request_quote_button_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'single-request-quote',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '10px',
				'margin-left'         => '0px',
				'border-top-width'    => '1px',
				'border-right-width'  => '1px',
				'border-bottom-width' => '1px',
				'border-left-width'   => '1px',
				'padding-top'         => '5px',
				'padding-right'       => '10px',
				'padding-bottom'      => '5px',
				'padding-left'        => '10px',
			),
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et_b_single-request-quote-button'
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.et_b_single-request-quote-button' )
		),
		
		// request_quote_button_border
		'request_quote_button_border_et-desktop'                     => array(
			'name'      => 'request_quote_button_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'request_quote_button_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'single-request-quote',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_single-request-quote-button',
					'property' => 'border-style'
				),
			),
		),
		
		// request_quote_button_border_color_custom
		'request_quote_button_border_color_custom_et-desktop'        => array(
			'name'        => 'request_quote_button_border_color_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'request_quote_button_border_color_custom_et-desktop',
			'label'       => $strings['label']['border_color'],
			'description' => $strings['description']['border_color'],
			'section'     => 'single-request-quote',
			'default'     => '#e1e1e1',
			'choices'     => array(
				'alpha' => true
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_single-request-quote-button',
					'property' => 'border-color',
				),
			),
		),
		
		// popup
		
		// request_quote_popup_content
		'request_quote_popup_content_et-desktop'                     => array(
			'name'            => 'request_quote_popup_content_et-desktop',
			'type'            => 'editor',
			'settings'        => 'request_quote_popup_content_et-desktop',
			'label'           => esc_html__( 'Text', 'xstore-core' ),
			'section'         => 'single-request-quote',
			'default'         => 'You may add any content here from Customizer->WooCommerce->Single Product Builder->Request a quote',
			//			'active_callback' => array(
			//				array(
			//					'setting'  => 'request_quote_popup_sections_et-desktop',
			//					'operator' => '!=',
			//					'value'    => 1,
			//				),
			//			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'request_quote_popup_content_et-desktop' => array(
					'selector'        => '.single-product-request-quote-wrapper',
					'render_callback' => 'single_product_request_quote_callback',
				),
			),
		),
		
		// style separator
		'request_quote_popup_style_separator'                        => array(
			'name'     => 'request_quote_popup_style_separator',
			'type'     => 'custom',
			'settings' => 'request_quote_popup_style_separator',
			'section'  => 'single-request-quote',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// request_quote_popup_content_alignment
		//		'request_quote_popup_content_alignment_et-desktop'	=>	 array(
// 'name'		  => 'request_quote_popup_content_alignment_et-desktop',
		//			'type'        => 'radio-buttonset',
		//			'settings'    => 'request_quote_popup_content_alignment_et-desktop',
		//			'label'       => $strings['label']['alignment'],
		//			'section'  => 'single-request-quote',
		//			'default'     => 'start',
		//			'choices'     => $choices['alignment'],
		//			'transport' => 'postMessage',
		//			'js_vars'     => array(
		//				array(
		//					'element'  => '.et_b_single-request-quote-popup .et-popup-content',
		//					'function' => 'toggleClass',
		//					'class' => 'align-start',
		//					'value' => 'start'
		//				),
		//				array(
		//					'element'  => '.et_b_single-request-quote-popup .et-popup-content',
		//					'function' => 'toggleClass',
		//					'class' => 'align-center',
		//					'value' => 'center'
		//				),
		//				array(
		//					'element'  => '.et_b_single-request-quote-popup .et-popup-content',
		//					'function' => 'toggleClass',
		//					'class' => 'align-end',
		//					'value' => 'end'
		//				),
		//			),
		//		),
		
		// request_quote_popup_content_width_height
		'request_quote_popup_content_width_height_et-desktop'        => array(
			'name'      => 'request_quote_popup_content_width_height_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'request_quote_popup_content_width_height_et-desktop',
			'label'     => esc_html__( 'Popup width and height', 'xstore-core' ),
			'section'   => 'single-request-quote',
			'default'   => 'auto',
			'multiple'  => 1,
			'choices'   => array(
				'auto'   => esc_html__( 'Auto', 'xstore-core' ),
				'custom' => esc_html__( 'Custom', 'xstore-core' ),
			),
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_single-request-quote-popup .et-popup-content',
					'function' => 'toggleClass',
					'class'    => 'et-popup-content-custom-dimenstions',
					'value'    => 'custom'
				),
			),
		),
		
		// request_quote_popup_content_width_height_custom
		'request_quote_popup_content_width_height_custom_et-desktop' => array(
			'name'      => 'request_quote_popup_content_width_height_custom_et-desktop',
			'type'      => 'dimensions',
			'settings'  => 'request_quote_popup_content_width_height_custom_et-desktop',
			'section'   => 'single-request-quote',
			'default'   => array(
				'width'  => '550px',
				'height' => '250px',
			),
			'choices'   => array(
				'labels' => array(
					'width'  => esc_html__( 'Width (for custom only)', 'xstore-core' ),
					'height' => esc_html__( 'Height (for custom only)', 'xstore-core' ),
				),
			),
			// 'active_callback' => array(
			// 	array(
			// 		'setting'  => 'request_quote_popup_content_width_height_et-desktop',
			// 		'operator' => '==',
			// 		'value'    => 'custom',
			// 	),
			// ),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'width',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_single-request-quote-popup .et-popup-content-custom-dimenstions',
					'property' => 'width',
				),
				array(
					'choice'   => 'height',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_single-request-quote-popup .et-popup-content-custom-dimenstions',
					'property' => 'height',
				)
			),
		),
		
		// request_quote_popup_background
		'request_quote_popup_background_et-desktop'                  => array(
			'name'        => 'request_quote_popup_background_et-desktop',
			'type'        => 'background',
			'settings'    => 'request_quote_popup_background_et-desktop',
			'label'       => $strings['label']['wcag_bg_color'],
			'description' => $strings['description']['wcag_bg_color'],
			'section'     => 'single-request-quote',
			'default'     => array(
				'background-color'      => '#ffffff',
				'background-image'      => '',
				'background-repeat'     => 'no-repeat',
				'background-position'   => 'center center',
				'background-size'       => '',
				'background-attachment' => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et_b_single-request-quote-popup .et-popup-content',
				),
			),
		),
		
		'request_quote_popup_color_et-desktop' => array(
			'name'        => 'request_quote_popup_color_et-desktop',
			'settings'    => 'request_quote_popup_color_et-desktop',
			'label'       => $strings['label']['wcag_color'],
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'single-request-quote',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'setting(single-request-quote)(request_quote_popup_background_et-desktop)[background-color]',
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
					'element'  => '.et_b_single-request-quote-popup .et-popup-content, .et_b_single-request-quote-popup .et-close-popup',
					'property' => 'color'
				)
			)
		),
		
		'request_quote_popup_box_model_et-desktop'           => array(
			'name'        => 'request_quote_popup_box_model_et-desktop',
			'settings'    => 'request_quote_popup_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'single-request-quote',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => '0px',
				'margin-bottom'       => '0px',
				'margin-left'         => '0px',
				'border-top-width'    => '0px',
				'border-right-width'  => '0px',
				'border-bottom-width' => '0px',
				'border-left-width'   => '0px',
				'padding-top'         => '15px',
				'padding-right'       => '15px',
				'padding-bottom'      => '15px',
				'padding-left'        => '15px',
			),
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.et_b_single-request-quote-popup .et-popup-content',
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( '.et_b_single-request-quote-popup .et-popup-content' )
		),
		
		// request_quote_popup_border
		'request_quote_popup_border_et-desktop'              => array(
			'name'      => 'request_quote_popup_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'request_quote_popup_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'single-request-quote',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_single-request-quote-popup .et-popup-content',
					'property' => 'border-style'
				),
			),
		),
		
		// request_quote_popup_border_color_custom
		'request_quote_popup_border_color_custom_et-desktop' => array(
			'name'        => 'request_quote_popup_border_color_custom_et-desktop',
			'type'        => 'color',
			'settings'    => 'request_quote_popup_border_color_custom_et-desktop',
			'label'       => $strings['label']['border_color'],
			'description' => $strings['description']['border_color'],
			'section'     => 'single-request-quote',
			'default'     => '#e1e1e1',
			'choices'     => array(
				'alpha' => true
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.et_b_single-request-quote-popup .et-popup-content',
					'property' => 'border-color',
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
