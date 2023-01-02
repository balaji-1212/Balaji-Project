<?php
/**
 * The template created for displaying single product layout/sidebar options
 *
 * @version 1.0.0
 * @since   0.0.1
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'single_product_layout' => array(
			'name'       => 'single_product_layout',
			'title'      => esc_html__( 'Layout', 'xstore-core' ),
			'panel'      => 'single_product_builder',
			'icon'       => 'dashicons-align-left ',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/single_product_layout', function ( $fields ) use ( $separators, $sidebars, $strings, $box_models, $choices ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'single_product_layout_content_separator'                       => array(
			'name'     => 'single_product_layout_content_separator',
			'type'     => 'custom',
			'settings' => 'single_product_layout_content_separator',
			'section'  => 'single_product_layout',
			'default'  => $separators['content'],
		),
		
		// single_product_full_width
		'single_product_full_width_et-desktop'                          => array(
			'name'     => 'single_product_full_width_et-desktop',
			'type'     => 'toggle',
			'settings' => 'single_product_full_width_et-desktop',
			'label'    => esc_html__( 'Full width layout', 'xstore-core' ),
			'section'  => 'single_product_layout',
			'default'  => 0,
		),
		
		// single_product_sidebar_mode
		'single_product_sidebar_mode_et-desktop'                        => array(
			'name'        => 'single_product_sidebar_mode_et-desktop',
			'type'        => 'radio-buttonset',
			'settings'    => 'single_product_sidebar_mode_et-desktop',
			'label'       => esc_html__( 'Use sidebar as', 'xstore-core' ),
			'description' => esc_html__( 'If you choose static element then you should remove sidebar drag&drop element inside content', 'xstore-core' ),
			'section'     => 'single_product_layout',
			'default'     => 'element',
			'multiple'    => 1,
			'choices'     => array(
				'default' => esc_html__( 'Static section', 'xstore-core' ),
				'element' => esc_html__( 'Builder element', 'xstore-core' ),
			),
		),
		
		// single_product_sidebar
		'single_product_sidebar_et-desktop'                             => array(
			'name'            => 'single_product_sidebar_et-desktop',
			'type'            => 'radio-image',
			'settings'        => 'single_product_sidebar_et-desktop',
			'label'           => esc_html__( 'Sidebar position', 'xstore-core' ),
			'description'     => esc_html__( 'Choose the position of the sidebar for the single product page.', 'xstore-core' ),
			'section'         => 'single_product_layout',
			'default'         => 'without',
			'choices'         => $sidebars,
			'active_callback' => array(
				array(
					'setting'  => 'single_product_sidebar_mode_et-desktop',
					'operator' => '==',
					'value'    => 'default',
				),
			),
		),
		
		// single_product_widget_area_1
		'single_product_widget_area_1_et-desktop'                       => array(
			'name'     => 'single_product_widget_area_1_et-desktop',
			'type'     => 'select',
			'settings' => 'single_product_widget_area_1_et-desktop',
			'label'    => esc_html__( 'Select sidebar area', 'xstore-core' ),
			'section'  => 'single_product_layout',
			'default'  => 'single-sidebar',
			'multiple' => 1,
			'choices'  => etheme_get_sidebars(),
		),
		
		// single_product_sidebar_sticky
		'single_product_sidebar_sticky_et-desktop'                      => array(
			'name'            => 'single_product_sidebar_sticky_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'single_product_sidebar_sticky_et-desktop',
			'label'           => esc_html__( 'Enable sticky sidebar', 'xstore-core' ),
			'description'     => esc_html__( 'Turn on to make the sidebar permanently visible while scrolling at the single product page.', 'xstore-core' ),
			'section'         => 'single_product_layout',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'single_product_sidebar_mode_et-desktop',
					'operator' => '==',
					'value'    => 'default',
				),
			),
		),
		
		// go_to_section
		'go_to_section_sidebar-widgets-prefooter'                       => array(
			'name'     => 'go_to_section_sidebar-widgets-prefooter',
			'type'     => 'custom',
			'settings' => 'go_to_section_sidebar-widgets-prefooter',
			'section'  => 'single_product_layout',
			'default'  => '<span class="et_edit" data-section="sidebar-widgets-prefooter" data-parent="panel-widgets" style="padding: 5px 7px; border-radius: 5px; background: #222; color: #fff;">' . esc_html__( 'Sidebar widget areas', 'xstore-core' ) . '</span>',
		),
		
		// single_product_widget_area_1_widget_toggle
		'single_product_widget_area_1_widget_toggle_et-desktop'         => array(
			'name'     => 'single_product_widget_area_1_widget_toggle_et-desktop',
			'type'     => 'toggle',
			'settings' => 'single_product_widget_area_1_widget_toggle_et-desktop',
			'label'    => esc_html__( 'Widgets with toggle', 'xstore-core' ),
			'section'  => 'single_product_layout',
			'default'  => 0,
		),
		
		// single_product_widget_area_1_widget_toggle_actions
		'single_product_widget_area_1_widget_toggle_actions_et-desktop' => array(
			'name'            => 'single_product_widget_area_1_widget_toggle_actions_et-desktop',
			'type'            => 'select',
			'settings'        => 'single_product_widget_area_1_widget_toggle_actions_et-desktop',
			'label'           => esc_html__( 'Select action on toggle', 'xstore-core' ),
			'section'         => 'single_product_layout',
			'default'         => 'opened',
			'multiple'        => 1,
			'choices'         => array(
				'opened'     => esc_html__( 'Opened', 'xstore-core' ),
				'closed'     => esc_html__( 'Closed', 'xstore-core' ),
				'mob_closed' => esc_html__( 'Closed on mobile', 'xstore-core' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'single_product_widget_area_1_widget_toggle_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		
		// single_product_widget_area_1_widget_scroll
		'single_product_widget_area_1_widget_scroll_et-desktop'         => array(
			'name'     => 'single_product_widget_area_1_widget_scroll_et-desktop',
			'type'     => 'toggle',
			'settings' => 'single_product_widget_area_1_widget_scroll_et-desktop',
			'label'    => esc_html__( 'Widgets with scroll', 'xstore-core' ),
			'section'  => 'single_product_layout',
			'default'  => 0,
		),
		
		// style separator
		'single_product_layout_style_separator'                         => array(
			'name'     => 'single_product_layout_style_separator',
			'type'     => 'custom',
			'settings' => 'single_product_layout_style_separator',
			'section'  => 'single_product_layout',
			'default'  => $separators['style'],
		),
		
		// single_product_widget_area_1_title_fonts
		'single_product_widget_area_1_title_fonts_et-desktop'           => array(
			'name'      => 'single_product_widget_area_1_title_fonts_et-desktop',
			'label'     => esc_html__( 'Widget title fonts', 'xstore-core' ),
			'settings'  => 'single_product_widget_area_1_title_fonts_et-desktop',
			'section'   => 'single_product_layout',
			'default'   => array(
				// 'font-family'    => '',
				// 'variant'        => 'regular',
				// 'font-size'      => '',
				// 'line-height'    => '1.5',
				// 'letter-spacing' => '0',
				// 'color'          => '#555',
				'text-transform' => 'capitalize',
				// 'text-align'     => 'left',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.single-product .sidebar-widget .widget-title',
				),
			),
		),
		
		// single_product_widget_area_1_title_size_proportion
		'single_product_widget_area_1_title_size_proportion_et-desktop' => array(
			'name'      => 'single_product_widget_area_1_title_size_proportion_et-desktop',
			'type'      => 'slider',
			'settings'  => 'single_product_widget_area_1_title_size_proportion_et-desktop',
			'label'     => esc_html__( 'Widget title size (proportion)', 'xstore-core' ),
			'section'   => 'single_product_layout',
			'default'   => 1,
			'choices'   => array(
				'min'  => '0',
				'max'  => '3',
				'step' => '.1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product .sidebar-widget .widget-title',
					'property' => '--h5-size-proportion',
				),
			),
		),
		
		// single_product_widget_area_1_widget_spacing
		'single_product_widget_area_1_widget_spacing_et-desktop'        => array(
			'name'      => 'single_product_widget_area_1_widget_spacing_et-desktop',
			'type'      => 'slider',
			'settings'  => 'single_product_widget_area_1_widget_spacing_et-desktop',
			'label'     => esc_html__( 'Space between widgets', 'xstore-core' ),
			'section'   => 'single_product_layout',
			'default'   => 60,
			'choices'   => array(
				'min'  => '0',
				'max'  => '200',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'     => array( 'editor', 'front' ),
					'element'     => '.single-product .sidebar',
					'property'    => '--space-between-widgets',
					'media_query' => '@media only screen and (min-width: 993px)',
					'units'       => 'px'
				),
			),
		),
		
		// single_product_widget_area_1_widget_scroll_height
		'single_product_widget_area_1_widget_scroll_height_et-desktop'  => array(
			'name'            => 'single_product_widget_area_1_widget_scroll_height_et-desktop',
			'type'            => 'slider',
			'settings'        => 'single_product_widget_area_1_widget_scroll_height_et-desktop',
			'label'           => esc_html__( 'Max height of widget content', 'xstore-core' ),
			'section'         => 'single_product_layout',
			'default'         => 300,
			'choices'         => array(
				'min'  => '50',
				'max'  => '500',
				'step' => '1',
			),
			'active_callback' => array(
				array(
					'setting'  => 'single_product_widget_area_1_widget_scroll_et-desktop',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => '.single-product.s_widgets-with-scroll .sidebar .sidebar-widget:not(.sidebar-slider):not(.etheme_widget_satick_block) > ul, .single-product.s_widgets-with-scroll .shop-filters .sidebar-widget:not(.sidebar-slider):not(.etheme_widget_satick_block) > ul, .single-product.s_widgets-with-scroll .sidebar .sidebar-widget:not(.sidebar-slider):not(.etheme_widget_satick_block) > div, .single-product.s_widgets-with-scroll .shop-filters .sidebar-widget:not(.sidebar-slider):not(.etheme_widget_satick_block) > div',
					'property' => 'max-height',
					'units'    => 'px'
				)
			),
		),
		
		'single_product_sidebar_box_model_et-desktop'                 => array(
			'name'            => 'single_product_sidebar_box_model_et-desktop',
			'settings'        => 'single_product_sidebar_box_model_et-desktop',
			'label'           => $strings['label']['computed_box'],
			'description'     => $strings['description']['computed_box'],
			'type'            => 'kirki-box-model',
			'section'         => 'single_product_layout',
			'default'         => $box_models['col_paddings'],
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'     => array( 'editor', 'front' ),
					'element'     => '.single-product .sidebar',
					'media_query' => '@media only screen and (min-width: 922px)'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'single_product_sidebar_mode_et-desktop',
					'operator' => '==',
					'value'    => 'default',
				),
			),
		),
		
		// single_product_sidebar_border
		'single_product_sidebar_border_et-desktop'                    => array(
			'name'            => 'single_product_sidebar_border_et-desktop',
			'type'            => 'select',
			'settings'        => 'single_product_sidebar_border_et-desktop',
			'label'           => $strings['label']['border_style'],
			'section'         => 'single_product_layout',
			'default'         => 'solid',
			'choices'         => $choices['border_style'],
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'     => array( 'editor', 'front' ),
					'element'     => '.single-product .sidebar',
					'property'    => 'border-style',
					'media_query' => '@media only screen and (min-width: 922px)'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'single_product_sidebar_mode_et-desktop',
					'operator' => '==',
					'value'    => 'default',
				),
			),
		),
		
		// single_product_sidebar_border_color_custom
		'single_product_sidebar_border_color_custom_et-desktop'       => array(
			'name'            => 'single_product_sidebar_border_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'single_product_sidebar_border_color_custom_et-desktop',
			'label'           => $strings['label']['border_color'],
			'section'         => 'single_product_layout',
			'default'         => '#e1e1e1',
			'choices'         => array(
				'alpha' => true
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'     => array( 'editor', 'front' ),
					'element'     => '.single-product .sidebar',
					'property'    => 'border-color',
					'media_query' => '@media only screen and (min-width: 922px)'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'single_product_sidebar_mode_et-desktop',
					'operator' => '==',
					'value'    => 'default',
				),
			),
		),
		'single_product_widget_area_1_box_model_et-desktop'           => array(
			'name'            => 'single_product_widget_area_1_box_model_et-desktop',
			'settings'        => 'single_product_widget_area_1_box_model_et-desktop',
			'label'           => $strings['label']['computed_box'],
			'description'     => $strings['description']['computed_box'],
			'type'            => 'kirki-box-model',
			'section'         => 'single_product_layout',
			'default'         => $box_models['empty'],
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'     => array( 'editor', 'front' ),
					'element'     => '.single-product-custom-widget-area',
					'media_query' => '@media only screen and (min-width: 922px)'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'single_product_sidebar_mode_et-desktop',
					'operator' => '==',
					'value'    => 'element',
				),
			),
		),
		
		// single_product_widget_area_1_border
		'single_product_widget_area_1_border_et-desktop'              => array(
			'name'            => 'single_product_widget_area_1_border_et-desktop',
			'type'            => 'select',
			'settings'        => 'single_product_widget_area_1_border_et-desktop',
			'label'           => $strings['label']['border_style'],
			'section'         => 'single_product_layout',
			'default'         => 'solid',
			'choices'         => $choices['border_style'],
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'     => array( 'editor', 'front' ),
					'element'     => '.single-product-custom-widget-area',
					'property'    => 'border-style',
					'media_query' => '@media only screen and (min-width: 922px)'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'single_product_sidebar_mode_et-desktop',
					'operator' => '==',
					'value'    => 'element',
				),
			),
		),
		
		// single_product_widget_area_1_border_color_custom
		'single_product_widget_area_1_border_color_custom_et-desktop' => array(
			'name'            => 'single_product_widget_area_1_border_color_custom_et-desktop',
			'type'            => 'color',
			'settings'        => 'single_product_widget_area_1_border_color_custom_et-desktop',
			'label'           => $strings['label']['border_color'],
			'section'         => 'single_product_layout',
			'default'         => '#e1e1e1',
			'choices'         => array(
				'alpha' => true
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'     => array( 'editor', 'front' ),
					'element'     => '.single-product-custom-widget-area',
					'property'    => 'border-color',
					'media_query' => '@media only screen and (min-width: 922px)'
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'single_product_sidebar_mode_et-desktop',
					'operator' => '==',
					'value'    => 'element',
				),
			),
		),
	);
	
	return array_merge( $fields, $args );
	
} );
