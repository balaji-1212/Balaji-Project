<?php
/**
 * The template created for displaying product tabs options
 *
 * @since   1.5.0
 * @version 1.0.1
 * last changes in 1.5.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_tabs' => array(
			'name'       => 'product_tabs',
			'title'      => esc_html__( 'Tabs', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-index-card',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/product_tabs', function ( $fields ) use ( $separators, $strings, $choices, $sep_style ) {
	$sections = et_b_get_posts(
		array(
			'post_per_page' => -1,
			'nopaging'      => true,
			'post_type'     => 'staticblocks',
			'with_none' => true
		)
	);
	
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'product_tabs_content_separator'      => array(
			'name'     => 'product_tabs_content_separator',
			'type'     => 'custom',
			'settings' => 'product_tabs_content_separator',
			'section'  => 'product_tabs',
			'default'  => $separators['content'],
		),
		
		// product_tabs_type
		'product_tabs_type_et-desktop'        => array(
			'name'     => 'product_tabs_type_et-desktop',
			'type'     => 'radio-image',
			'settings' => 'product_tabs_type_et-desktop',
			'label'    => $strings['label']['type'],
			'section'  => 'product_tabs',
			'default'  => 'underline',
			'priority' => 10,
			'choices'  => array(
				'simple'    => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/tabs/Style-tab-simple.svg',
				'folders'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/tabs/Style-tab-classic.svg',
				'overline'  => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/tabs/Style-tab-overline.svg',
				'underline' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/tabs/Style-tab-underline.svg',
				'accordion' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/woocommerce/single-product/tabs/Style-tab-accordion.svg',
			),
		),
		
		// product_tabs_vertical
		'product_tabs_style_et-desktop'       => array(
			'name'            => 'product_tabs_style_et-desktop',
			'type'            => 'radio-buttonset',
			'settings'        => 'product_tabs_style_et-desktop',
			'label'           => $strings['label']['style'],
			'section'         => 'product_tabs',
			'default'         => 'horizontal',
			'choices'         => array(
				'horizontal' => $choices['direction']['type1']['hor'],
				'vertical'   => $choices['direction']['type1']['ver'],
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_tabs_type_et-desktop',
					'operator' => '!=',
					'value'    => 'accordion',
				),
			)
		),
		
		// product_tabs_sortable
		'product_tabs_sortable'               => array(
			'name'        => 'product_tabs_sortable',
			'type'        => 'sortable',
			'label'       => $strings['label']['elements'],
			'description' => esc_html__( 'On/Off elements you need. Drag&Drop to change order.', 'xstore-core' ),
			'section'     => 'product_tabs',
			'settings'    => 'product_tabs_sortable',
			'default'     => array(
				'description',
				'additional_information',
				'reviews',
				'et_custom_tab_01',
				'et_custom_tab_02',
				'single_custom_tab_01',
			),
			'choices'     => array(
				'description'            => esc_html__( 'Description', 'xstore-core' ),
				'additional_information' => esc_html__( 'Additional information', 'xstore-core' ),
				'reviews'                => esc_html__( 'Reviews', 'xstore-core' ),
				'et_custom_tab_01'       => esc_html__( 'Custom tab 01', 'xstore-core' ),
				'et_custom_tab_02'       => esc_html__( 'Custom tab 02', 'xstore-core' ),
				'single_custom_tab_01'   => esc_html__( 'Single product Custom tab', 'xstore-core' ),
			)
		),
		
		// product_tabs_first_closed
		'product_first_tab_opened_et-desktop' => array(
			'name'            => 'product_first_tab_opened_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'product_first_tab_opened_et-desktop',
			'label'           => esc_html__( 'Open first tab', 'xstore-core' ),
			'section'         => 'product_tabs',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'product_tabs_type_et-desktop',
					'operator' => '==',
					'value'    => 'accordion',
				),
				array(
					'setting'  => 'product_tabs_opened_et-desktop',
					'operator' => '==',
					'value'    => 0,
				)
			)
		),
		
		'product_tabs_opened_et-desktop'            => array(
			'name'            => 'product_tabs_opened_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'product_tabs_opened_et-desktop',
			'label'           => esc_html__( 'Open all tabs', 'xstore-core' ),
			'section'         => 'product_tabs',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'product_tabs_type_et-desktop',
					'operator' => '==',
					'value'    => 'accordion',
				),
				array(
					'setting'  => 'product_first_tab_opened_et-desktop',
					'operator' => '==',
					'value'    => 0,
				)
			)
		),
		
		// product_reviews_two_columns
		'product_reviews_in_two_columns_et-desktop' => array(
			'name'     => 'product_reviews_in_two_columns_et-desktop',
			'type'     => 'toggle',
			'settings' => 'product_reviews_in_two_columns_et-desktop',
			'label'    => esc_html__( 'Reviews in two columns', 'xstore-core' ),
			'section'  => 'product_tabs',
			'default'  => 1,
		),
		
		// product_reviews
		'product_reviews_et-desktop'                => array(
			'name'            => 'product_reviews_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'product_reviews_et-desktop',
			'label'           => esc_html__( 'Reviews outside of tabs', 'xstore-core' ),
			'section'         => 'product_tabs',
			'default'         => 0,
			'active_callback' => function () {
				if ( ! in_array( 'reviews', get_theme_mod( 'product_tabs_sortable', array() ) ) ) {
					return true;
				}
				
				return false;
			}
		),
		
		'product_reviews_collapsed_et-mobile'                         => array(
			'name'            => 'product_reviews_collapsed_et-mobile',
			'type'            => 'toggle',
			'settings'        => 'product_reviews_collapsed_et-mobile',
			'label'           => esc_html__( 'Collapsed reviews on mobile', 'xstore-core' ),
			'description'     => esc_html__( 'Turn on to make reviews shown only on button click on mobile devices.', 'xstore-core' ),
			'section'         => 'product_tabs',
			'default'         => 0,
			'active_callback' => function () {
				if ( ! in_array( 'reviews', get_theme_mod( 'product_tabs_sortable', array() ) ) && get_theme_mod( 'product_reviews_et-desktop', false ) ) {
					return true;
				}
				
				return false;
			}
		),
		
		// style separator
		'product_tabs_navigation_style_separator'                     => array(
			'name'     => 'product_tabs_navigation_style_separator',
			'type'     => 'custom',
			'settings' => 'product_tabs_navigation_style_separator',
			'section'  => 'product_tabs',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-customizer"></span> <span style="padding-left: 3px;">' . esc_html__( 'Tabs navigation', 'xstore-core' ) . '</span></div>',
		),
		
		// product_tabs_align
		'product_tabs_navigation_align_et-desktop'                    => array(
			'name'      => 'product_tabs_navigation_align_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'product_tabs_navigation_align_et-desktop',
			'label'     => $strings['label']['alignment'],
			'section'   => 'product_tabs',
			'default'   => 'center',
			'choices'   => array(
				'start'  => esc_html__( 'Start', 'xstore-core' ),
				'center' => esc_html__( 'Center', 'xstore-core' ),
				'end'    => esc_html__( 'End', 'xstore-core' ),
			),
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.woocommerce-tabs.horizontal .wc-tabs',
					'function' => 'toggleClass',
					'class'    => 'justify-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.woocommerce-tabs.horizontal .wc-tabs',
					'function' => 'toggleClass',
					'class'    => 'justify-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.woocommerce-tabs.horizontal .wc-tabs',
					'function' => 'toggleClass',
					'class'    => 'justify-content-end',
					'value'    => 'end'
				),
				array(
					'element'  => '.woocommerce-tabs.vertical .wc-tabs',
					'function' => 'toggleClass',
					'class'    => 'align-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.woocommerce-tabs.vertical .wc-tabs',
					'function' => 'toggleClass',
					'class'    => 'align-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.woocommerce-tabs.vertical .wc-tabs',
					'function' => 'toggleClass',
					'class'    => 'align-content-end',
					'value'    => 'end'
				),
			),
		),
		
		// product_tabs_fonts
		'product_tabs_navigation_fonts_et-desktop'                    => array(
			'name'      => 'product_tabs_navigation_fonts_et-desktop',
			'type'      => 'typography',
			'settings'  => 'product_tabs_navigation_fonts_et-desktop',
			'section'   => 'product_tabs',
			'default'   => array(
				// 'font-family'    => '',
				// 'variant'        => 'regular',
				// 'font-size'      => '14px',
				// 'line-height'    => '1.5',
				// 'letter-spacing' => '0',
				// 'color'          => '#555',
				'text-transform' => 'uppercase',
				// 'text-align'     => 'left',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.wc-tabs [role="tab"]',
				),
			),
		),
		
		// product_tabs_navigation_background
		'product_tabs_navigation_background_et-desktop'               => array(
			'name'            => 'product_tabs_navigation_background_et-desktop',
			'type'            => 'select',
			'settings'        => 'product_tabs_navigation_background_et-desktop',
			'label'           => $strings['label']['colors'],
			'section'         => 'product_tabs',
			'default'         => 'current',
			'choices'         => $choices['colors'],
			'active_callback' => array(
				array(
					'setting'  => 'product_tabs_type_et-desktop',
					'operator' => 'in',
					'value'    => array( 'folders', 'accordion' ),
				),
			),
			'output'          => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.woocommerce-tabs.type-folders .wc-tabs .et-woocommerce-tab:not(.active) a, .woocommerce-tabs.type-accordion .wc-tabs .et-woocommerce-tab:not(.active) a',
					'property'      => 'color',
					'value_pattern' => 'var(--$-color)'
				),
			),
		),
		
		// product_tabs_navigation_background_custom
		'product_tabs_navigation_background_custom_et-desktop'        => array(
			'name'            => 'product_tabs_navigation_background_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'product_tabs_navigation_background_custom_et-desktop',
			'label'           => esc_html__( 'Background', 'xstore-core' ),
			'section'         => 'product_tabs',
			'choices'         => array(
				'alpha' => true
			),
			'default'         => '#ffffff',
			'active_callback' => array(
				array(
					'setting'  => 'product_tabs_type_et-desktop',
					'operator' => 'in',
					'value'    => array( 'folders', 'accordion' ),
				),
				array(
					'setting'  => 'product_tabs_navigation_background_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.woocommerce-tabs.type-folders .wc-tabs .et-woocommerce-tab, .woocommerce-tabs.type-accordion .wc-tabs .et-woocommerce-tab',
					'property' => 'background-color',
				),
			),
		),
		'product_tabs_navigation_color_custom_et-desktop'             => array(
			'name'            => 'product_tabs_navigation_color_custom_et-desktop',
			'settings'        => 'product_tabs_navigation_color_custom_et-desktop',
			'label'           => $strings['label']['wcag_color'],
			'description'     => $strings['description']['wcag_color'],
			'type'            => 'kirki-wcag-tc',
			'section'         => 'product_tabs',
			'default'         => '#555555',
			'choices'         => array(
				'setting' => 'setting(product_tabs)(product_tabs_navigation_background_custom_et-desktop)',
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
			'active_callback' => array(
				array(
					'setting'  => 'product_tabs_type_et-desktop',
					'operator' => 'in',
					'value'    => array( 'folders', 'accordion' ),
				),
				array(
					'setting'  => 'product_tabs_navigation_background_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.woocommerce-tabs.type-folders .wc-tabs .et-woocommerce-tab:not(.active) a, .woocommerce-tabs.type-accordion .wc-tabs .et-woocommerce-tab:not(.active) a',
					'property' => 'color'
				)
			),
		),
		
		// product_tabs_navigation_active_background_custom
		'product_tabs_navigation_active_background_custom_et-desktop' => array(
			'name'            => 'product_tabs_navigation_active_background_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'product_tabs_navigation_active_background_custom_et-desktop',
			'label'           => esc_html__( 'Background (active)', 'xstore-core' ),
			'section'         => 'product_tabs',
			'choices'         => array(
				'alpha' => true
			),
			'default'         => '#ffffff',
			'active_callback' => array(
				array(
					'setting'  => 'product_tabs_type_et-desktop',
					'operator' => 'in',
					'value'    => array( 'folders', 'accordion' ),
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.woocommerce-tabs.type-folders .wc-tabs .et-woocommerce-tab.active, .woocommerce-tabs.type-accordion .wc-tabs .et-woocommerce-tab.active',
					'property' => 'background-color',
				),
			),
		),
		
		// product_tabs_navigation_active_color_custom
		'product_tabs_navigation_active_color_custom_et-desktop'      => array(
			'name'      => 'product_tabs_navigation_active_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_tabs_navigation_active_color_custom_et-desktop',
			'label'     => esc_html__( 'Color (active)', 'xstore-core' ),
			'section'   => 'product_tabs',
			'default'   => '#000',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.wc-tabs .et-woocommerce-tab.active a',
					'property' => 'color',
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.woocommerce-tabs.type-overline .wc-tabs .et-woocommerce-tab:before, .woocommerce-tabs.type-underline .wc-tabs .et-woocommerce-tab:before',
					'property' => 'background-color'
				)
			),
		),
		
		// product_tabs_size
		'product_tabs_navigation_size_et-desktop'                     => array(
			'name'      => 'product_tabs_navigation_size_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_tabs_navigation_size_et-desktop',
			'label'     => esc_html__( 'Tabs zoom (%)', 'xstore-core' ),
			'section'   => 'product_tabs',
			'default'   => 100,
			'choices'   => array(
				'min'  => '0',
				'max'  => '200',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.wc-tabs',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.woocommerce-tabs.type-accordion .wc-tabs .et-woocommerce-tab',
					'property'      => 'font-size',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		),
		
		// product_tabs_vertical_proportion
		'product_tabs_vertical_proportion_et-desktop'                 => array(
			'name'            => 'product_tabs_vertical_proportion_et-desktop',
			'type'            => 'slider',
			'settings'        => 'product_tabs_vertical_proportion_et-desktop',
			'label'           => esc_html__( 'Tabs width (%)', 'xstore-core' ),
			'section'         => 'product_tabs',
			'default'         => 20,
			'choices'         => array(
				'min'  => '1',
				'max'  => '99',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_tabs_type_et-desktop',
					'operator' => '!=',
					'value'    => 'accordion',
				),
				array(
					'setting'  => 'product_tabs_style_et-desktop',
					'operator' => '==',
					'value'    => 'vertical',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.woocommerce-tabs.vertical .wc-tabs',
					'property' => 'flex-basis',
					'units'    => '%'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.woocommerce-tabs.vertical .wc-tab',
					'property'      => 'flex-basis',
					'value_pattern' => 'calc(100% - $%)'
				),
			),
		),
		
		// product_tabs_scroll
		'product_tabs_scroll_et-desktop'                              => array(
			'name'      => 'product_tabs_scroll_et-desktop',
			'type'      => 'toggle',
			'settings'  => 'product_tabs_scroll_et-desktop',
			'label'     => esc_html__( 'Tabs content scroll', 'xstore-core' ),
			'section'   => 'product_tabs',
			'default'   => 0,
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.woocommerce-tabs',
					'function' => 'toggleClass',
					'class'    => 'tabs-with-scroll',
					'value'    => true
				),
			),
		),
		
		// product_tabs_height
		'product_tabs_height_et-desktop'                              => array(
			'name'        => 'product_tabs_height_et-desktop',
			'type'        => 'slider',
			'settings'    => 'product_tabs_height_et-desktop',
			'label'       => esc_html__( 'Tab content max-height', 'xstore-core' ),
			'description' => esc_html__( 'Works if option "Tabs content scroll" is enabled', 'xstore-core' ),
			'section'     => 'product_tabs',
			'default'     => 250,
			'choices'     => array(
				'min'  => 50,
				'max'  => 800,
				'step' => 1,
			),
			// 'active_callback' => array(
			// 	array(
			// 		'setting'  => 'product_tabs_scroll_et-desktop',
			// 		'operator' => '==',
			// 		'value'    => true,
			// 	),
			// ),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.tabs-with-scroll .wc-tab',
					'property' => 'max-height',
					'units'    => 'px'
				)
			)
		),
		
		// product_tabs_spacing
		'product_tabs_navigation_spacing_et-desktop'                  => array(
			'name'            => 'product_tabs_navigation_spacing_et-desktop',
			'type'            => 'slider',
			'settings'        => 'product_tabs_navigation_spacing_et-desktop',
			'label'           => esc_html__( 'Space between (px)', 'xstore-core' ),
			'section'         => 'product_tabs',
			'default'         => 0,
			'choices'         => array(
				'min'  => '-1',
				'max'  => '80',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_tabs_type_et-desktop',
					'operator' => '!=',
					'value'    => 'accordion',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.woocommerce-tabs.horizontal .wc-tabs',
					'property'      => 'margin',
					'value_pattern' => '0 -$px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.woocommerce-tabs.horizontal .wc-tabs:after',
					'property'      => 'left',
					'value_pattern' => '$px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.woocommerce-tabs.horizontal .wc-tabs:after',
					'property'      => 'right',
					'value_pattern' => '$px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.woocommerce-tabs.horizontal .wc-tabs .et-woocommerce-tab',
					'property'      => 'margin',
					'value_pattern' => '0 $px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.woocommerce-tabs.vertical .wc-tabs',
					'property'      => 'margin',
					'value_pattern' => '-$px 0'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.woocommerce-tabs.vertical .wc-tabs:after, .woocommerce-tabs.vertical.type-overline .wc-tabs:after, .woocommerce-tabs.vertical.type-underline .wc-tabs:after',
					'property'      => 'top',
					'value_pattern' => '$px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.woocommerce-tabs.vertical .wc-tabs:after, .woocommerce-tabs.vertical.type-overline .wc-tabs:after, .woocommerce-tabs.vertical.type-underline .wc-tabs:after',
					'property'      => 'bottom',
					'value_pattern' => '$px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.woocommerce-tabs.vertical .wc-tabs .et-woocommerce-tab',
					'property'      => 'margin',
					'value_pattern' => '$px 0'
				),
			),
		),
		
		// product_tabs_accordion_navigation_spacing
		'product_tabs_accordion_navigation_spacing_et-desktop'        => array(
			'name'            => 'product_tabs_accordion_navigation_spacing_et-desktop',
			'type'            => 'slider',
			'settings'        => 'product_tabs_accordion_navigation_spacing_et-desktop',
			'label'           => esc_html__( 'Space inside tabs titles (px)', 'xstore-core' ),
			'section'         => 'product_tabs',
			'default'         => 10,
			'choices'         => array(
				'min'  => '0',
				'max'  => '40',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_tabs_type_et-desktop',
					'operator' => '==',
					'value'    => 'accordion',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.type-accordion .wc-tabs .et-woocommerce-tab a',
					'property' => 'padding-top',
					'units'    => 'px'
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.type-accordion .wc-tabs .et-woocommerce-tab a',
					'property' => 'padding-bottom',
					'units'    => 'px'
				)
			),
		),
		
		// style separator
		'product_tabs_style_separator'                                => array(
			'name'     => 'product_tabs_style_separator',
			'type'     => 'custom',
			'settings' => 'product_tabs_style_separator',
			'section'  => 'product_tabs',
			'default'  => $separators['style'],
		),
		
		// product_tabs_box_model
		'product_tabs_box_model_et-desktop'                           => array(
			'name'      => 'product_tabs_box_model_et-desktop',
			'type'      => 'kirki-box-model',
			'settings'  => 'product_tabs_box_model_et-desktop',
			'label'     => $strings['label']['computed_box'],
			'section'   => 'product_tabs',
			'default'   => array(
				'margin-top'          => '50px',
				'margin-right'        => '0px',
				'margin-bottom'       => '20px',
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
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.woocommerce-tabs',
				),
			),
			'transport' => 'postMessage',
			'js_vars'   => box_model_output( '.woocommerce-tabs' )
		),
		
		// product_tabs_border
		'product_tabs_border_et-desktop'                              => array(
			'name'      => 'product_tabs_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'product_tabs_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'product_tabs',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.woocommerce-tabs',
					'property' => 'border-style',
				),
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.woocommerce-tabs.type-accordion .wc-tabs .et-woocommerce-tab ~ .et-woocommerce-tab,
					.woocommerce-tabs.type-accordion .wc-tabs .wc-tab',
					'property' => 'border-top-style',
				),
			),
		),
		
		// product_tabs_border_color_custom
		'product_tabs_border_color_custom_et-desktop'                 => array(
			'name'      => 'product_tabs_border_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_tabs_border_color_custom_et-desktop',
			'label'     => $strings['label']['border_color'],
			'section'   => 'product_tabs',
			'default'   => '#e1e1e1',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.woocommerce-tabs,
					.woocommerce-tabs.type-accordion .wc-tabs .et-woocommerce-tab ~ .et-woocommerce-tab,
					.woocommerce-tabs.type-accordion .wc-tabs .wc-tab',
					'property' => 'border-color',
				),
			),
		),
		
		// advanced separator
		'product_tabs_advanced_separator'                             => array(
			'name'     => 'product_tabs_advanced_separator',
			'type'     => 'custom',
			'settings' => 'product_tabs_advanced_separator',
			'section'  => 'product_tabs',
			'default'  => $separators['advanced'],
		),
		
		// product_tabs_custom_tab_01_title
		'product_tabs_custom_tab_01_title_et-desktop'                 => array(
			'name'     => 'product_tabs_custom_tab_01_title_et-desktop',
			'type'     => 'etheme-text',
			'settings' => 'product_tabs_custom_tab_01_title_et-desktop',
			'label'    => esc_html__( 'Custom tab 01 title ', 'xstore-core' ),
			'section'  => 'product_tabs',
			'default'  => get_theme_mod( 'custom_tab_title', '' ),
		),
		
		// product_tabs_custom_tab_01_content
		'product_tabs_custom_tab_01_content_et-desktop'               => array(
			'name'            => 'product_tabs_custom_tab_01_content_et-desktop',
			'type'            => 'editor',
			'settings'        => 'product_tabs_custom_tab_01_content_et-desktop',
			'label'           => esc_html__( 'Custom tab 01 content ', 'xstore-core' ),
			'section'         => 'product_tabs',
			'default'         => get_theme_mod( 'custom_tab', '' ),
			'active_callback' => array(
				array(
					'setting'  => 'product_tabs_custom_tab_01_sections_et-desktop',
					'operator' => '!=',
					'value'    => 1,
				),
			)
		),
		
		// product_tabs_custom_tab_01_sections
		'product_tabs_custom_tab_01_sections_et-desktop'              => array(
			'name'     => 'product_tabs_custom_tab_01_sections_et-desktop',
			'type'     => 'toggle',
			'settings' => 'product_tabs_custom_tab_01_sections_et-desktop',
			'label'    => $strings['label']['use_static_block'],
			'section'  => 'product_tabs',
			'default'  => 0,
		),
		
		// product_tabs_custom_tab_01_section
		'product_tabs_custom_tab_01_section_et-desktop'               => array(
			'name'            => 'product_tabs_custom_tab_01_section_et-desktop',
			'type'            => 'select',
			'settings'        => 'product_tabs_custom_tab_01_section_et-desktop',
			'label'           => sprintf( esc_html__( 'Choose %1s', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: #555">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
			'section'         => 'product_tabs',
			'default'         => '',
			'priority'        => 10,
			'choices'         => $sections,
			'active_callback' => array(
				array(
					'setting'  => 'product_tabs_custom_tab_01_sections_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			)
		),
		
		// product_tabs_custom_tab_02_title
		'product_tabs_custom_tab_02_title_et-desktop'                 => array(
			'name'     => 'product_tabs_custom_tab_02_title_et-desktop',
			'type'     => 'etheme-text',
			'settings' => 'product_tabs_custom_tab_02_title_et-desktop',
			'label'    => esc_html__( 'Custom tab 02 title ', 'xstore-core' ),
			'section'  => 'product_tabs',
			'default'  => '',
		),
		
		// product_tabs_custom_tab_02_content
		'product_tabs_custom_tab_02_content_et-desktop'               => array(
			'name'            => 'product_tabs_custom_tab_02_content_et-desktop',
			'type'            => 'editor',
			'settings'        => 'product_tabs_custom_tab_02_content_et-desktop',
			'label'           => esc_html__( 'Custom tab 02 content ', 'xstore-core' ),
			'section'         => 'product_tabs',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'product_tabs_custom_tab_02_sections_et-desktop',
					'operator' => '!=',
					'value'    => 1,
				),
			)
		),
		
		// product_tabs_custom_tab_02_sections
		'product_tabs_custom_tab_02_sections_et-desktop'              => array(
			'name'     => 'product_tabs_custom_tab_02_sections_et-desktop',
			'type'     => 'toggle',
			'settings' => 'product_tabs_custom_tab_02_sections_et-desktop',
			'label'    => $strings['label']['use_static_block'],
			'section'  => 'product_tabs',
			'default'  => 0,
		),
		
		// product_tabs_custom_tab_02_section
		'product_tabs_custom_tab_02_section_et-desktop'               => array(
			'name'            => 'product_tabs_custom_tab_02_section_et-desktop',
			'type'            => 'select',
			'settings'        => 'product_tabs_custom_tab_02_section_et-desktop',
			'label'           => sprintf( esc_html__( 'Choose %1s', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: #555">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
			'section'         => 'product_tabs',
			'default'         => '',
			'priority'        => 10,
			'choices'         => $sections,
			'active_callback' => array(
				array(
					'setting'  => 'product_tabs_custom_tab_02_sections_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			)
		),
	);
	
	unset($sections);
	
	return array_merge( $fields, $args );
	
} );
