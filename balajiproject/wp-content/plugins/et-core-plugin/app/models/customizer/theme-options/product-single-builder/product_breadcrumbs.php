<?php
/**
 * The template created for displaying single product breadcrumbs options
 *
 * @version 1.0.1
 * @since   1.5
 * last changes in 1.5.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'product_breadcrumbs' => array(
			'name'       => 'product_breadcrumbs',
			'title'      => esc_html__( 'Breadcrumbs', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-carrot',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/product_breadcrumbs', function ( $fields ) use ( $separators, $strings, $choices, $sep_style ) {
	$args = array();
	// Array of fields
	$args = array(
		
		// content separator
		'product_breadcrumbs_content_separator'                        => array(
			'name'     => 'product_breadcrumbs_content_separator',
			'type'     => 'custom',
			'settings' => 'product_breadcrumbs_content_separator',
			'section'  => 'product_breadcrumbs',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		
		// product_breadcrumbs_mode 
		'product_breadcrumbs_mode_et-desktop'                          => array(
			'name'        => 'product_breadcrumbs_mode_et-desktop',
			'type'        => 'radio-buttonset',
			'settings'    => 'product_breadcrumbs_mode_et-desktop',
			'label'       => esc_html__( 'Use breadcrumbs as', 'xstore-core' ),
			'description' => esc_html__( 'If you choose static element then you should remove breadcrumbs drag&drop element inside content', 'xstore-core' ),
			'section'     => 'product_breadcrumbs',
			'default'     => 'element',
			'multiple'    => 1,
			'choices'     => array(
				'default' => esc_html__( 'Static section', 'xstore-core' ),
				'element' => esc_html__( 'Builder element', 'xstore-core' ),
			),
		),
		
		// product_breadcrumbs_style
		'product_breadcrumbs_style_et-desktop'                         => array(
			'name'            => 'product_breadcrumbs_style_et-desktop',
			'type'            => 'radio-image',
			'settings'        => 'product_breadcrumbs_style_et-desktop',
			'label'           => esc_html__( 'Breadcrumbs Style', 'xstore-core' ),
			'section'         => 'product_breadcrumbs',
			'default'         => 'left2',
			'choices'         => array(
				'left2'   => ETHEME_CODE_CUSTOMIZER_IMAGES . '/breadcrumbs/Left-inline-type.svg',
				'default' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/breadcrumbs/Align-center.svg',
				'left'    => ETHEME_CODE_CUSTOMIZER_IMAGES . '/breadcrumbs/Align-left.svg',
				'inherit' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/breadcrumbs/Inherit.svg',
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_breadcrumbs_mode_et-desktop',
					'operator' => '!=',
					'value'    => 'element',
				),
			),
		),
		
		// product_breadcrumbs_return_to_previous
		'product_breadcrumbs_return_to_previous_et-desktop'            => array(
			'name'     => 'product_breadcrumbs_return_to_previous_et-desktop',
			'type'     => 'toggle',
			'settings' => 'product_breadcrumbs_return_to_previous_et-desktop',
			'label'    => esc_html__( 'Return to previous', 'xstore-core' ),
			'section'  => 'product_breadcrumbs',
			'default'  => 0,
			// 'active_callback' => array(
			// 	array(
			// 		'setting'  => 'product_breadcrumbs_mode_et-desktop',
			// 		'operator' => '!=',
			// 		'value'    => 'element',
			// 	),
			// ),
		),
		
		// product_breadcrumbs_product_title
		'product_breadcrumbs_product_title_et-desktop'                 => array(
			'name'            => 'product_breadcrumbs_product_title_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'product_breadcrumbs_product_title_et-desktop',
			'label'           => esc_html__( 'Move product name in breadcrumbs', 'xstore-core' ),
			'description'     => esc_html__( 'Turn on to show the product title in breadcrumbs.', 'xstore-core' ),
			'section'         => 'product_breadcrumbs',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'product_breadcrumbs_mode_et-desktop',
					'operator' => '!=',
					'value'    => 'element',
				),
			),
		),
		
		// product_breadcrumbs_product_title_duplicated
		'product_breadcrumbs_product_title_duplicated_et-desktop'      => array(
			'name'            => 'product_breadcrumbs_product_title_duplicated_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'product_breadcrumbs_product_title_duplicated_et-desktop',
			'label'           => esc_html__( 'Leave product name in main content too', 'xstore-core' ),
			'description'     => esc_html__( 'Turn on to show the product title in content too.', 'xstore-core' ),
			'section'         => 'product_breadcrumbs',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'product_breadcrumbs_mode_et-desktop',
					'operator' => '!=',
					'value'    => 'element',
				),
				array(
					'setting'  => 'product_breadcrumbs_product_title_et-desktop',
					'operator' => '!=',
					'value'    => 0
				)
			),
		),
		
		// product_breadcrumbs_type 
		'product_breadcrumbs_type_et-desktop'                          => array(
			'name'            => 'product_breadcrumbs_type_et-desktop',
			'type'            => 'radio-image',
			'settings'        => 'product_breadcrumbs_type_et-desktop',
			'label'           => esc_html__( 'Separator', 'xstore-core' ),
			'section'         => 'product_breadcrumbs',
			'default'         => 'type2',
			'multiple'        => 1,
			'choices'         => array(
				'type1' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/breadcrumbs/Style-breadcrumbs-1.svg',
				'type2' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/breadcrumbs/Style-breadcrumbs-2.svg',
				'type3' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/breadcrumbs/Style-breadcrumbs-3.svg',
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'product_breadcrumbs_type_et-desktop' => array(
					'selector'        => '.single-product .woocommerce-breadcrumb .delimeter',
					'render_callback' => function () {
						$separator_type = array(
							'type1' => '<i style="font-family: auto; font-size: 2em;">&#8226;</i>',
							'type2' => '<i class="et-icon et-' . ( is_rtl() ? 'right' : 'left' ) . '-arrow"></i>',
							'type3' => '<i style="font-family: auto; font-size: 2em;">&nbsp;&#47;&nbsp;</i>',
						);
						
						return $separator_type[ get_theme_mod( 'product_breadcrumbs_type_et-desktop', 'type2' ) ];
					}
				),
			),
		),
		
		// style separator
		'product_breadcrumbs_style_separator'                          => array(
			'name'            => 'product_breadcrumbs_style_separator',
			'type'            => 'custom',
			'settings'        => 'product_breadcrumbs_style_separator',
			'section'         => 'product_breadcrumbs',
			'default'         => $separators['style'],
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'product_breadcrumbs_mode_et-desktop',
					'operator' => '!=',
					'value'    => 'element',
				),
			),
		),
		
		// product_breadcrumbs_width
		'product_breadcrumbs_width_et-desktop'                         => array(
			'name'            => 'product_breadcrumbs_width_et-desktop',
			'type'            => 'select',
			'settings'        => 'product_breadcrumbs_width_et-desktop',
			'label'           => esc_html__( 'Content width', 'xstore-core' ),
			'section'         => 'product_breadcrumbs',
			'default'         => 'full_width',
			'choices'         => array(
				'full_width'         => esc_html__( 'Full-width', 'xstore-core' ),
				'full_width_content' => esc_html__( 'Full-width with content', 'xstore-core' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_breadcrumbs_mode_et-desktop',
					'operator' => '!=',
					'value'    => 'element',
				),
			),
		),
		
		// product_breadcrumbs_align
		// 'product_breadcrumbs_align_et-desktop'	=>	 array(
		// 'name'		  => 'product_breadcrumbs_align_et-desktop',
		// 	'type'        => 'radio-buttonset',
		// 	'settings'    => 'product_breadcrumbs_align_et-desktop',
		// 	'label'       => $strings['label']['alignment'],
		// 	'section'     => 'product_breadcrumbs',
		// 	'default'     => 'inherit',
		// 	'choices'     => $choices['alignment_with_inherit'],
		// 	'transport' => 'auto',
		// 	'output' => array(
		// 		array(
		// 'context'   => array('editor', 'front'),
		// 			'element' => '.single-product .page-heading',
		// 			'property' => 'text-align',
		// 		),
		// 	),
		// ),
		
		// typography separator 
		'product_breadcrumbs_typography_separator'                     => array(
			'name'     => 'product_breadcrumbs_typography_separator',
			'type'     => 'custom',
			'settings' => 'product_breadcrumbs_typography_separator',
			'section'  => 'product_breadcrumbs',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-editor-spellcheck"></span> <span style="padding-left: 3px;">' . esc_html__( 'Typography', 'xstore-core' ) . '</span></div>',
			'priority' => 10,
		),
		
		// product_breadcrumbs_zoom
		'product_breadcrumbs_zoom_et-desktop'                          => array(
			'name'      => 'product_breadcrumbs_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'product_breadcrumbs_zoom_et-desktop',
			'label'     => $strings['label']['content_zoom'],
			'section'   => 'product_breadcrumbs',
			'default'   => 100,
			'choices'   => array(
				'min'  => '0',
				'max'  => '300',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.single-product .page-heading',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				),
			),
		),
		
		// product_breadcrumbs_fonts
		'product_breadcrumbs_fonts_et-desktop'                         => array(
			'name'      => 'product_breadcrumbs_fonts_et-desktop',
			'type'      => 'typography',
			'settings'  => 'product_breadcrumbs_fonts_et-desktop',
			'label'     => esc_html__( 'Main breadcrumbs fonts', 'xstore-core' ),
			'section'   => 'product_breadcrumbs',
			'default'   => array(
				'font-family'    => '',
				'variant'        => 'regular',
				// 'font-size'      => '15px',
				// 'line-height'    => '1.5',
				// 'letter-spacing' => '0',
				// 'color'          => '#555',
				'text-transform' => 'inherit',
				// 'text-align'     => 'left',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.single-product .page-heading',
				),
			),
		),
		
		// product_breadcrumbs_return_to_previous_fonts
		'product_breadcrumbs_return_to_previous_fonts_et-desktop'      => array(
			'name'            => 'product_breadcrumbs_return_to_previous_fonts_et-desktop',
			'type'            => 'typography',
			'settings'        => 'product_breadcrumbs_return_to_previous_fonts_et-desktop',
			'label'           => esc_html__( 'Return to previous fonts', 'xstore-core' ),
			'section'         => 'product_breadcrumbs',
			'default'         => array(
				'font-family'    => 'inherit',
				'variant'        => 'regular',
				// 'font-size'      => '15px',
				// 'line-height'    => '1.5',
				// 'letter-spacing' => '0',
				// 'color'          => '#555',
				'text-transform' => 'inherit',
				// 'text-align'     => 'left',
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.single-product .page-heading .back-history',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_breadcrumbs_mode_et-desktop',
					'operator' => '!=',
					'value'    => 'element',
				),
				array(
					'setting'  => 'product_breadcrumbs_return_to_previous_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		
		// product_breadcrumbs_product_title_fonts 
		'product_breadcrumbs_product_title_fonts_et-desktop'           => array(
			'name'            => 'product_breadcrumbs_product_title_fonts_et-desktop',
			'type'            => 'typography',
			'settings'        => 'product_breadcrumbs_product_title_fonts_et-desktop',
			'label'           => esc_html__( 'Title fonts', 'xstore-core' ),
			'section'         => 'product_breadcrumbs',
			'default'         => array(
				'font-family'    => 'inherit',
				'variant'        => 'regular',
				// 'font-size'      => '15px',
				// 'line-height'    => '1.5',
				// 'letter-spacing' => '0',
				'color'          => '#555',
				'text-transform' => 'inherit',
				// 'text-align'     => 'left',
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.single-product .page-heading .title',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_breadcrumbs_mode_et-desktop',
					'operator' => '!=',
					'value'    => 'element',
				),
				array(
					'setting'  => 'product_breadcrumbs_product_title_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		
		// products_related_title_sizes
		'product_breadcrumbs_product_title_sizes_et-desktop'           => array(
			'name'            => 'product_breadcrumbs_product_title_sizes_et-desktop',
			'type'            => 'radio-buttonset',
			'settings'        => 'product_breadcrumbs_product_title_sizes_et-desktop',
			'label'           => $strings['label']['title_sizes'],
			'section'         => 'product_breadcrumbs',
			'default'         => 'inherit',
			'choices'         => array(
				'inherit' => esc_html__( 'Inherit', 'xstore-core' ),
				'custom'  => esc_html__( 'Custom', 'xstore-core' )
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_breadcrumbs_mode_et-desktop',
					'operator' => '!=',
					'value'    => 'element',
				),
				array(
					'setting'  => 'product_breadcrumbs_product_title_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		
		// product_breadcrumbs_product_title_size_proportion
		'product_breadcrumbs_product_title_size_proportion_et-desktop' => array(
			'name'            => 'product_breadcrumbs_product_title_size_proportion_et-desktop',
			'type'            => 'slider',
			'settings'        => 'product_breadcrumbs_product_title_size_proportion_et-desktop',
			'label'           => $strings['label']['title_size_proportion'],
			'section'         => 'product_breadcrumbs',
			'default'         => 1.71,
			'choices'         => array(
				'min'  => '0',
				'max'  => '5',
				'step' => '.1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_breadcrumbs_mode_et-desktop',
					'operator' => '!=',
					'value'    => 'element',
				),
				array(
					'setting'  => 'product_breadcrumbs_product_title_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'product_breadcrumbs_product_title_sizes_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .page-heading',
					'property' => '--h1-size-proportion',
				),
			),
		),
		
		// product_breadcrumbs_product_title_line_height
		'product_breadcrumbs_product_title_line_height_et-desktop'     => array(
			'name'            => 'product_breadcrumbs_product_title_line_height_et-desktop',
			'type'            => 'slider',
			'settings'        => 'product_breadcrumbs_product_title_line_height_et-desktop',
			'label'           => esc_html__( 'Title line height', 'xstore-core' ),
			'section'         => 'product_breadcrumbs',
			'default'         => 1.5,
			'choices'         => array(
				'min'  => '0',
				'max'  => '3',
				'step' => '.1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_breadcrumbs_mode_et-desktop',
					'operator' => '!=',
					'value'    => 'element',
				),
				array(
					'setting'  => 'product_breadcrumbs_product_title_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'product_breadcrumbs_product_title_sizes_et-desktop',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .page-heading',
					'property' => '--h1-line-height',
				),
			),
		),
		
		// product_breadcrumbs_background
		'product_breadcrumbs_background_et-desktop'                    => array(
			'name'        => 'product_breadcrumbs_background_et-desktop',
			'type'        => 'background',
			'settings'    => 'product_breadcrumbs_background_et-desktop',
			'label'       => $strings['label']['wcag_bg_color'],
			'description' => $strings['description']['wcag_bg_color'],
			'section'     => 'product_breadcrumbs',
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
					'element' => '.single-product .page-heading',
				),
			),
		),
		
		// product_breadcrumbs_color
		'product_breadcrumbs_color_et-desktop'                         => array(
			'name'        => 'product_breadcrumbs_color_et-desktop',
			'settings'    => 'product_breadcrumbs_color_et-desktop',
			'label'       => $strings['label']['wcag_color'],
			'description' => $strings['description']['wcag_color'],
			'type'        => 'kirki-wcag-tc',
			'section'     => 'product_breadcrumbs',
			'default'     => '#000000',
			'choices'     => array(
				'setting' => 'setting(product_breadcrumbs)(product_breadcrumbs_background_et-desktop)[background-color]',
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
					'element'  => '.single-product .page-heading',
					'property' => 'color'
				)
			)
		),
		
		// product_breadcrumbs_box_model
		'product_breadcrumbs_box_model_et-desktop'                     => array(
			'name'        => 'product_breadcrumbs_box_model_et-desktop',
			'settings'    => 'product_breadcrumbs_box_model_et-desktop',
			'label'       => $strings['label']['computed_box'],
			'description' => $strings['description']['computed_box'],
			'type'        => 'kirki-box-model',
			'section'     => 'product_breadcrumbs',
			'default'     => array(
				'margin-top'          => '0px',
				'margin-right'        => 'auto',
				'margin-bottom'       => '0px',
				'margin-left'         => 'auto',
				'border-top-width'    => '0px',
				'border-right-width'  => '0px',
				'border-bottom-width' => '0px',
				'border-left-width'   => '0px',
				'padding-top'         => '12px',
				'padding-right'       => '0px',
				'padding-bottom'      => '12px',
				'padding-left'        => '0px',
			),
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => 'body.single-product .page-heading',
				),
			),
			'transport'   => 'postMessage',
			'js_vars'     => box_model_output( 'body.single-product .page-heading' )
		),
		
		// product_breadcrumbs_border
		'product_breadcrumbs_border_et-desktop'                        => array(
			'name'      => 'product_breadcrumbs_border_et-desktop',
			'type'      => 'select',
			'settings'  => 'product_breadcrumbs_border_et-desktop',
			'label'     => $strings['label']['border_style'],
			'section'   => 'product_breadcrumbs',
			'default'   => 'solid',
			'choices'   => $choices['border_style'],
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .page-heading',
					'property' => 'border-style'
				),
			),
		),
		
		// product_breadcrumbs_border_color_custom
		'product_breadcrumbs_border_color_custom_et-desktop'           => array(
			'name'      => 'product_breadcrumbs_border_color_custom_et-desktop',
			'type'      => 'color',
			'settings'  => 'product_breadcrumbs_border_color_custom_et-desktop',
			'label'     => $strings['label']['border_color'],
			'section'   => 'product_breadcrumbs',
			'default'   => '#e1e1e1',
			'choices'   => array(
				'alpha' => true
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .page-heading',
					'property' => 'border-color',
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
