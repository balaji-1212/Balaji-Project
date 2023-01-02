<?php
/**
 * The template created for displaying style options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) use ( $priorities ) {
	
	$args = array(
		'style' => array(
			'name'       => 'style',
			'title'      => esc_html__( 'Styling/Colors', 'xstore' ),
			'icon'       => 'dashicons-admin-customizer',
			'priority'   => $priorities['styling'],
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/style' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $light_sep_style, $light_buttons, $borders_empty, $border_radius, $border_radius_labels, $border_styles, $bordered_sep_style, $bordered_buttons, $border_labels, $dark_buttons, $active_sep_style, $active_buttons, $dark_sep_style ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'dark_styles' => array(
			'name'        => 'dark_styles',
			'type'        => 'toggle',
			'settings'    => 'dark_styles',
			'label'       => esc_html__( 'Dark version', 'xstore' ),
			'description' => esc_html__( 'Turn on to switch site to dark styles.', 'xstore' ),
			'section'     => 'style',
			'default'     => 0,
		),
		
		'activecol' => array(
			'name'        => 'activecol',
			'type'        => 'color',
			'settings'    => 'activecol',
			'label'       => esc_html__( 'Main Color', 'xstore' ),
			'description' => esc_html__( 'Choose the main color for the site (color of links, active buttons and elements like pagination, sale price, portfolio project mask, blog image mask etc).', 'xstore' ),
			'section'     => 'style',
			'default'     => '#a4004f',
			'choices'     => array(
				'alpha' => false
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_active-color',
				),
			)
		),
		
		'background_img' => array(
			'name'        => 'background_img',
			'type'        => 'background',
			'settings'    => 'background_img',
			'label'       => esc_html__( 'Site Background', 'xstore' ),
			'description' => esc_html__( 'Choose the background of the site. Visible if boxed layout is enabled.', 'xstore' ),
			'section'     => 'style',
			'default'     => array(
				'background-color'      => '#ffffff',
				'background-image'      => '',
				'background-repeat'     => '',
				'background-position'   => '',
				'background-size'       => '',
				'background-attachment' => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => 'body',
				),
				array(
					'choice'   => 'background-color',
					'context'  => array( 'editor', 'front' ),
					'element'  => '.etheme-sticky-cart',
					'property' => 'background-color'
				),
			),
		),
		
		'container_bg'              => array(
			'name'        => 'container_bg',
			'type'        => 'color',
			'settings'    => 'container_bg',
			'label'       => esc_html__( 'Container Background Color', 'xstore' ),
			'description' => esc_html__( 'Choose the background color of the template container. Template container covers the whole visible area if wide layout is enabled.', 'xstore' ),
			'section'     => 'style',
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_container-bg-color'
				),
			),
		),
		
		// search_zoom
		'form_inputs_border_radius' => array(
			'name'      => 'form_inputs_border_radius',
			'type'      => 'slider',
			'settings'  => 'form_inputs_border_radius',
			'label'     => esc_html__( 'Inputs border radius', 'xstore' ),
			'default'   => 0,
			'choices'   => array(
				'min'  => '0',
				'max'  => '50',
				'step' => '1',
			),
			'section'   => 'style',
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_inputs-border-radius',
					'units'    => 'px'
				),
			),
		),
		
		'forms_inputs_bg' => array(
			'name'        => 'forms_inputs_bg',
			'type'        => 'color',
			'settings'    => 'forms_inputs_bg',
			'label'       => esc_html__( 'Inputs background color', 'xstore' ),
			'description' => esc_html__( 'Controls the background color of the all the inputs.', 'xstore' ),
			'section'     => 'style',
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_inputs-bg-color'
				),
			),
		),
		
		'forms_inputs_br' => array(
			'name'        => 'forms_inputs_br',
			'type'        => 'color',
			'settings'    => 'forms_inputs_br',
			'label'       => esc_html__( 'Inputs border color', 'xstore' ),
			'description' => esc_html__( 'Controls the border color of the all the inputs.', 'xstore' ),
			'section'     => 'style',
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_inputs-border-color',
				),
			),
		),
		
		'slider_arrows_colors' => array(
			'name'     => 'slider_arrows_colors',
			'type'     => 'select',
			'settings' => 'slider_arrows_colors',
			'label'    => esc_html__( 'Make all slider\'s arrows without background or with your custom color', 'xstore' ),
			'section'  => 'style',
			'default'  => 'transparent',
			'choices'  => array(
				'transparent' => esc_html__( 'Transparent', 'xstore' ),
				'custom'      => esc_html__( 'Custom', 'xstore' ),
			),
		),
		
		'slider_arrows_bg_color' => array(
			'name'            => 'slider_arrows_bg_color',
			'type'            => 'color',
			'settings'        => 'slider_arrows_bg_color',
			'label'           => esc_html__( 'Slider arrows background color', 'xstore' ),
			'section'         => 'style',
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_arrows-bg-color',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'slider_arrows_colors',
					'operator' => '==',
					'value'    => 'custom',
				),
			)
		),
		
		'slider_arrows_color' => array(
			'name'      => 'slider_arrows_color',
			'type'      => 'color',
			'settings'  => 'slider_arrows_color',
			'label'     => esc_html__( 'Slider arrows color', 'xstore' ),
			'section'   => 'style',
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_arrows-color',
				),
			),
		),
		
		'bold_icons' => array(
			'name'        => 'bold_icons',
			'type'        => 'toggle',
			'settings'    => 'bold_icons',
			'label'       => esc_html__( 'Bold weight for icons', 'xstore' ),
			'description' => esc_html__( 'Turn on to make all the default icons (cart, search, wishlist, arrows etc) bold.', 'xstore' ),
			'section'     => 'style',
			'default'     => 0,
		),
		
		'separator_of_light_btn' => array(
			'name'     => 'separator_of_light_btn',
			'type'     => 'custom',
			'settings' => 'separator_of_light_btn',
			'section'  => 'style',
			'default'  => '<div style="' . $light_sep_style . '">' . esc_html__( 'Light buttons', 'xstore' ) . '</div>',
		),
		
		'light_buttons_fonts' => array(
			'name'      => 'light_buttons_fonts',
			'type'      => 'typography',
			'settings'  => 'light_buttons_fonts',
			'section'   => 'style',
			'default'   => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				// 'letter-spacing' => '',
				// 'color'          => '#555',
				'text-transform' => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => $light_buttons['regular'],
				),
			),
		),
		
		'light_buttons_bg' => array(
			'name'      => 'light_buttons_bg',
			'type'      => 'multicolor',
			'settings'  => 'light_buttons_bg',
			'label'     => esc_html__( 'Light buttons background', 'xstore' ),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bg-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bg-color-hover',
				),
			),
		),
		
		'light_buttons_color' => array(
			'name'      => 'light_buttons_color',
			'type'      => 'multicolor',
			'settings'  => 'light_buttons_color',
			'label'     => esc_html__( 'Buttons text color', 'xstore' ),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-color-hover',
				),
			),
		),
		
		'light_buttons_border_color' => array(
			'name'        => 'light_buttons_border_color',
			'type'        => 'multicolor',
			'settings'    => 'light_buttons_border_color',
			'label'       => esc_html__( 'Light buttons border color', 'xstore' ),
			'description' => esc_html__( 'Controls the light buttons border color', 'xstore' ),
			'section'     => 'style',
			'choices'     => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'     => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-br-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-br-color-hover',
				),
			),
		),
		
		'light_buttons_border_width' => array(
			'name'        => 'light_buttons_border_width',
			'type'        => 'dimensions',
			'settings'    => 'light_buttons_border_width',
			'label'       => esc_html__( 'Light buttons border width', 'xstore' ),
			'description' => esc_html__( 'Controls the light buttons border width', 'xstore' ),
			'section'     => 'style',
			'default'     => $borders_empty,
			'choices'     => array(
				'labels' => $border_labels,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'light_buttons_border_radius' => array(
			'name'      => 'light_buttons_border_radius',
			'type'      => 'dimensions',
			'settings'  => 'light_buttons_border_radius',
			'label'     => esc_html__( 'Light buttons border radius', 'xstore' ),
			'section'   => 'style',
			'default'   => $border_radius,
			'choices'   => array(
				'labels' => $border_radius_labels,
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'border-top-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-top-left-radius',
					// 'suffix' => '!important'
				),
				array(
					'choice'   => 'border-top-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-top-right-radius',
					// 'suffix' => '!important'
				),
				array(
					'choice'   => 'border-bottom-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-bottom-right-radius',
					// 'suffix' => '!important'
				),
				array(
					'choice'   => 'border-bottom-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-bottom-left-radius',
					// 'suffix' => '!important'
				),
			),
		),
		
		'light_buttons_border_width_hover' => array(
			'name'        => 'light_buttons_border_width_hover',
			'type'        => 'dimensions',
			'settings'    => 'light_buttons_border_width_hover',
			'label'       => esc_html__( 'Light buttons border width (hover)', 'xstore' ),
			'description' => esc_html__( 'Controls the light buttons border width on hover', 'xstore' ),
			'section'     => 'style',
			'default'     => $borders_empty,
			'choices'     => array(
				'labels' => $border_labels,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['hover'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['hover'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['hover'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['hover'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'light_buttons_border_style' => array(
			'name'        => 'light_buttons_border_style',
			'type'        => 'select',
			'settings'    => 'light_buttons_border_style',
			'label'       => esc_html__( 'Light buttons border style', 'xstore' ),
			'description' => esc_html__( 'Controls the light buttons border style', 'xstore' ),
			'section'     => 'style',
			'default'     => 'none',
			'choices'     => $border_styles,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['regular'],
					'property' => 'border-style'
				),
			),
		),
		
		'light_buttons_border_style_hover' => array(
			'name'        => 'light_buttons_border_style_hover',
			'type'        => 'select',
			'settings'    => 'light_buttons_border_style_hover',
			'label'       => esc_html__( 'Light buttons border style (hover)', 'xstore' ),
			'description' => esc_html__( 'Controls the light buttons border style on hover', 'xstore' ),
			'section'     => 'style',
			'default'     => 'none',
			'choices'     => $border_styles,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $light_buttons['hover'],
					'property' => 'border-style'
				),
			),
		),
		
		'separator_sep_bordered' => array(
			'name'     => 'separator_sep_bordered',
			'type'     => 'custom',
			'settings' => 'separator_sep_bordered',
			'section'  => 'style',
			'default'  => '<div style="' . $bordered_sep_style . '">' . esc_html__( 'Bordered buttons', 'xstore' ) . '</div>',
		
		),
		
		'bordered_buttons_fonts' => array(
			'name'      => 'bordered_buttons_fonts',
			'type'      => 'typography',
			'settings'  => 'bordered_buttons_fonts',
			'section'   => 'style',
			'default'   => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				// 'letter-spacing' => '',
				// 'color'          => '#555',
				'text-transform' => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => $bordered_buttons['regular'],
				),
			),
		),
		
		'bordered_buttons_bg' => array(
			'name'      => 'bordered_buttons_bg',
			'type'      => 'multicolor',
			'settings'  => 'bordered_buttons_bg',
			'label'     => esc_html__( 'Bordered buttons background', 'xstore' ),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bordered-bg-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bordered-bg-color-hover',
				),
			),
		),
		
		'bordered_buttons_color' => array(
			'name'      => 'bordered_buttons_color',
			'type'      => 'multicolor',
			'settings'  => 'bordered_buttons_color',
			'label'     => esc_html__( 'Buttons text color', 'xstore' ),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bordered-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bordered-color-hover',
				),
			),
		),
		
		'bordered_buttons_border_color' => array(
			'name'        => 'bordered_buttons_border_color',
			'type'        => 'multicolor',
			'settings'    => 'bordered_buttons_border_color',
			'label'       => esc_html__( 'Bordered buttons border color', 'xstore' ),
			'description' => esc_html__( 'Controls the bordered buttons border color', 'xstore' ),
			'section'     => 'style',
			'choices'     => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'     => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bordered-br-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-bordered-br-color-hover',
				),
			),
		),
		
		'bordered_buttons_border_width' => array(
			'name'        => 'bordered_buttons_border_width',
			'type'        => 'dimensions',
			'settings'    => 'bordered_buttons_border_width',
			'label'       => esc_html__( 'Bordered buttons border width', 'xstore' ),
			'description' => esc_html__( 'Controls the bordered buttons border width', 'xstore' ),
			'section'     => 'style',
			'default'     => array(
				'border-top'    => '1px',
				'border-right'  => '1px',
				'border-bottom' => '1px',
				'border-left'   => '1px',
			),
			'choices'     => array(
				'labels' => $border_labels,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'bordered_buttons_border_radius' => array(
			'name'      => 'bordered_buttons_border_radius',
			'type'      => 'dimensions',
			'settings'  => 'bordered_buttons_border_radius',
			'label'     => esc_html__( 'Bordered buttons border radius', 'xstore' ),
			'section'   => 'style',
			'default'   => $border_radius,
			'choices'   => array(
				'labels' => $border_radius_labels,
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'border-top-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-top-left-radius',
					// 'suffix' => '!important'
				),
				array(
					'choice'   => 'border-top-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-top-right-radius',
					// 'suffix' => '!important'
				),
				array(
					'choice'   => 'border-bottom-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-bottom-right-radius',
					// 'suffix' => '!important'
				),
				array(
					'choice'   => 'border-bottom-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-bottom-left-radius',
					// 'suffix' => '!important'
				),
			),
		),
		
		'bordered_buttons_border_width_hover' => array(
			'name'        => 'bordered_buttons_border_width_hover',
			'type'        => 'dimensions',
			'settings'    => 'bordered_buttons_border_width_hover',
			'label'       => esc_html__( 'Bordered buttons border width (hover)', 'xstore' ),
			'description' => esc_html__( 'Controls the bordered buttons border width on hover', 'xstore' ),
			'section'     => 'style',
			'default'     => array(
				'border-top'    => '1px',
				'border-right'  => '1px',
				'border-bottom' => '1px',
				'border-left'   => '1px',
			),
			'choices'     => array(
				'labels' => $border_labels,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['hover'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['hover'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['hover'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['hover'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'bordered_buttons_border_style' => array(
			'name'        => 'bordered_buttons_border_style',
			'type'        => 'select',
			'settings'    => 'bordered_buttons_border_style',
			'label'       => esc_html__( 'Bordered buttons border style', 'xstore' ),
			'description' => esc_html__( 'Controls the bordered buttons border style', 'xstore' ),
			'section'     => 'style',
			'default'     => 'solid',
			'choices'     => $border_styles,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $bordered_buttons['regular'],
					'property' => 'border-style'
				),
			),
		),
		
		'separator_dark_sep' => array(
			'name'     => 'separator_dark_sep',
			'type'     => 'custom',
			'settings' => 'separator_dark_sep',
			'section'  => 'style',
			'default'  => '<div style="' . $dark_sep_style . '">' . esc_html__( 'Dark buttons', 'xstore' ) . '</div>',
		),
		
		'dark_buttons_fonts' => array(
			'name'      => 'dark_buttons_fonts',
			'type'      => 'typography',
			'settings'  => 'dark_buttons_fonts',
			'section'   => 'style',
			'default'   => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				// 'letter-spacing' => '',
				// 'color'          => '#555',
				'text-transform' => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => $dark_buttons['regular'],
				),
			),
		),
		
		'dark_buttons_bg' => array(
			'name'      => 'dark_buttons_bg',
			'type'      => 'multicolor',
			'settings'  => 'dark_buttons_bg',
			'label'     => esc_html__( 'Dark buttons background', 'xstore' ),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-dark-bg-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-dark-bg-color-hover',
				),
			),
		),
		
		'dark_buttons_color' => array(
			'name'      => 'dark_buttons_color',
			'type'      => 'multicolor',
			'settings'  => 'dark_buttons_color',
			'label'     => esc_html__( 'Buttons text color', 'xstore' ),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-dark-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-dark-color-hover',
				),
			),
		
		),
		
		'dark_buttons_border_color' => array(
			'name'        => 'dark_buttons_border_color',
			'type'        => 'multicolor',
			'settings'    => 'dark_buttons_border_color',
			'label'       => esc_html__( 'Dark buttons border color', 'xstore' ),
			'description' => esc_html__( 'Controls the dark buttons border color', 'xstore' ),
			'section'     => 'style',
			'choices'     => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'     => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-dark-br-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-dark-br-color-hover',
				),
			),
		),
		
		'dark_buttons_border_width' => array(
			'name'        => 'dark_buttons_border_width',
			'type'        => 'dimensions',
			'settings'    => 'dark_buttons_border_width',
			'label'       => esc_html__( 'Dark buttons border width', 'xstore' ),
			'description' => esc_html__( 'Controls the dark buttons border width', 'xstore' ),
			'section'     => 'style',
			'default'     => $borders_empty,
			'choices'     => array(
				'labels' => $border_labels,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'dark_buttons_border_radius' => array(
			'name'      => 'dark_buttons_border_radius',
			'type'      => 'dimensions',
			'settings'  => 'dark_buttons_border_radius',
			'label'     => esc_html__( 'Dark buttons border radius', 'xstore' ),
			'section'   => 'style',
			'default'   => $border_radius,
			'choices'   => array(
				'labels' => $border_radius_labels,
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'border-top-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-top-left-radius',
				),
				array(
					'choice'   => 'border-top-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-top-right-radius',
				),
				array(
					'choice'   => 'border-bottom-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-bottom-right-radius',
				),
				array(
					'choice'   => 'border-bottom-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-bottom-left-radius',
				),
			),
		),
		
		'dark_buttons_border_width_hover' => array(
			'name'        => 'dark_buttons_border_width_hover',
			'type'        => 'dimensions',
			'settings'    => 'dark_buttons_border_width_hover',
			'label'       => esc_html__( 'Dark buttons border width (hover)', 'xstore' ),
			'description' => esc_html__( 'Controls the dark buttons border width on hover', 'xstore' ),
			'section'     => 'style',
			'default'     => $borders_empty,
			'choices'     => array(
				'labels' => $border_labels,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['hover'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['hover'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['hover'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['hover'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'dark_buttons_border_style' => array(
			'name'        => 'dark_buttons_border_style',
			'type'        => 'select',
			'settings'    => 'dark_buttons_border_style',
			'label'       => esc_html__( 'Dark buttons border style', 'xstore' ),
			'description' => esc_html__( 'Controls the dark buttons border style', 'xstore' ),
			'section'     => 'style',
			'default'     => 'none',
			'choices'     => $border_styles,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['regular'],
					'property' => 'border-style'
				),
			),
		),
		
		'dark_buttons_border_style_hover' => array(
			'name'        => 'dark_buttons_border_style_hover',
			'type'        => 'select',
			'settings'    => 'dark_buttons_border_style_hover',
			'label'       => esc_html__( 'Dark buttons border style (hover)', 'xstore' ),
			'description' => esc_html__( 'Controls the dark buttons border style on hover', 'xstore' ),
			'section'     => 'style',
			'default'     => 'none',
			'choices'     => $border_styles,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $dark_buttons['hover'],
					'property' => 'border-style'
				),
			),
		),
		
		'separator_active_sep' => array(
			'name'     => 'separator_active_sep',
			'type'     => 'custom',
			'settings' => 'separator_active_sep',
			'section'  => 'style',
			'default'  => '<div style="' . $active_sep_style . '">' . esc_html__( 'Active buttons', 'xstore' ) . '</div>',
		),
		
		'active_buttons_fonts' => array(
			'name'      => 'active_buttons_fonts',
			'type'      => 'typography',
			'settings'  => 'active_buttons_fonts',
			'section'   => 'style',
			'default'   => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				// 'letter-spacing' => '',
				// 'color'          => '#555',
				'text-transform' => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => $active_buttons['regular'],
				),
			),
		),
		
		'active_buttons_bg' => array(
			'name'      => 'active_buttons_bg',
			'type'      => 'multicolor',
			'settings'  => 'active_buttons_bg',
			'label'     => esc_html__( 'Active buttons background', 'xstore' ),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-active-bg-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-active-bg-color-hover',
				),
			),
		),
		
		'active_buttons_color' => array(
			'name'      => 'active_buttons_color',
			'type'      => 'multicolor',
			'settings'  => 'active_buttons_color',
			'label'     => esc_html__( 'Buttons text color', 'xstore' ),
			'section'   => 'style',
			'choices'   => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'   => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-active-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-active-color-hover',
				),
			),
		),
		
		'active_buttons_border_color' => array(
			'name'        => 'active_buttons_border_color',
			'type'        => 'multicolor',
			'settings'    => 'active_buttons_border_color',
			'label'       => esc_html__( 'Active buttons border color', 'xstore' ),
			'description' => esc_html__( 'Controls the Active buttons border color', 'xstore' ),
			'section'     => 'style',
			'choices'     => array(
				'regular' => esc_html__( 'Regular', 'xstore' ),
				'hover'   => esc_html__( 'Hover', 'xstore' ),
			),
			'default'     => array(
				'regular' => '',
				'hover'   => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'regular',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-active-br-color',
				),
				array(
					'choice'   => 'hover',
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body, [data-mode="dark"]',
					'property' => '--et_btn-active-br-color-hover',
				),
			)
		),
		
		'active_buttons_border_width' => array(
			'name'        => 'active_buttons_border_width',
			'type'        => 'dimensions',
			'settings'    => 'active_buttons_border_width',
			'label'       => esc_html__( 'Active buttons border width', 'xstore' ),
			'description' => esc_html__( 'Controls the Active buttons border width', 'xstore' ),
			'section'     => 'style',
			'default'     => $borders_empty,
			'choices'     => array(
				'labels' => $border_labels,
			),
			'transport'   => 'postMessage',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'active_buttons_border_radius' => array(
			'name'      => 'active_buttons_border_radius',
			'type'      => 'dimensions',
			'settings'  => 'active_buttons_border_radius',
			'label'     => esc_html__( 'Active buttons border radius', 'xstore' ),
			'section'   => 'style',
			'default'   => $border_radius,
			'choices'   => array(
				'labels' => $border_radius_labels,
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'choice'   => 'border-top-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-top-left-radius',
				),
				array(
					'choice'   => 'border-top-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-top-right-radius',
				),
				array(
					'choice'   => 'border-bottom-right-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-bottom-right-radius',
				),
				array(
					'choice'   => 'border-bottom-left-radius',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-bottom-left-radius',
				),
			),
		),
		
		'active_buttons_border_width_hover' => array(
			'name'        => 'active_buttons_border_width_hover',
			'type'        => 'dimensions',
			'settings'    => 'active_buttons_border_width_hover',
			'label'       => esc_html__( 'Active buttons border width (hover)', 'xstore' ),
			'description' => esc_html__( 'Controls the Active buttons border width on hover', 'xstore' ),
			'section'     => 'style',
			'default'     => $borders_empty,
			'choices'     => array(
				'labels' => $border_labels,
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'   => 'border-top',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['hover'],
					'property' => 'border-top-width'
				),
				array(
					'choice'   => 'border-bottom',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['hover'],
					'property' => 'border-bottom-width'
				),
				array(
					'choice'   => 'border-left',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['hover'],
					'property' => 'border-left-width'
				),
				array(
					'choice'   => 'border-right',
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['hover'],
					'property' => 'border-right-width'
				),
			),
		),
		
		'active_buttons_border_style' => array(
			'name'        => 'active_buttons_border_style',
			'type'        => 'select',
			'settings'    => 'active_buttons_border_style',
			'label'       => esc_html__( 'Active buttons border style', 'xstore' ),
			'description' => esc_html__( 'Controls the Active buttons border style', 'xstore' ),
			'section'     => 'style',
			'default'     => 'none',
			'choices'     => $border_styles,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['regular'],
					'property' => 'border-style'
				),
			),
		),
		
		'active_buttons_border_style_hover' => array(
			'name'        => 'active_buttons_border_style_hover',
			'type'        => 'select',
			'settings'    => 'active_buttons_border_style_hover',
			'label'       => esc_html__( 'Active buttons border style (hover)', 'xstore' ),
			'description' => esc_html__( 'Controls the Active buttons border style on hover', 'xstore' ),
			'section'     => 'style',
			'default'     => 'none',
			'choices'     => $border_styles,
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => $active_buttons['hover'],
					'property' => 'border-style'
				),
			),
		),
	
	);
	
	
	return array_merge( $fields, $args );
	
} );