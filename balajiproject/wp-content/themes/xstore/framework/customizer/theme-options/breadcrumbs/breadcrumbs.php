<?php
/**
 * The template created for displaying breadcrumbs options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

// section breadcrumbs
add_filter( 'et/customizer/add/sections', function ( $sections ) use ( $priorities ) {
	
	$args = array(
		'breadcrumbs' => array(
			'name'       => 'breadcrumbs',
			'title'      => esc_html__( 'Breadcrumbs', 'xstore' ),
			'icon'       => 'dashicons-carrot',
			'priority'   => $priorities['breadcrumbs'],
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/breadcrumbs' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $text_color_scheme, $paddings_empty, $padding_labels ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'breadcrumb_type' => array(
			'name'        => 'breadcrumb_type',
			'type'        => 'select',
			'settings'    => 'breadcrumb_type',
			'label'       => esc_html__( 'Breadcrumbs Style', 'xstore' ),
			'description' => esc_html__( 'Choose the breadcrumbs style or disable them.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 'left2',
			'choices'     => array(
				'left2'   => esc_html__( 'Left inline', 'xstore' ),
				'default' => esc_html__( 'Align center', 'xstore' ),
				'left'    => esc_html__( 'Align left', 'xstore' ),
				'disable' => esc_html__( 'Disable', 'xstore' ),
			),
			// 'transport' => 'postMessage',
			// 'js_vars'     => array(
			// 	array(
			// 		'element'  => '.page-heading',
			// 		'function' => 'toggleClass',
			// 		'class' => 'bc-type-left2',
			// 		'value' => 'left2'
			// 	),
			// 	array(
			// 		'element'  => '.page-heading',
			// 		'function' => 'toggleClass',
			// 		'class' => 'bc-type-default',
			// 		'value' => 'default'
			// 	),
			// 	array(
			// 		'element'  => '.page-heading',
			// 		'function' => 'toggleClass',
			// 		'class' => 'bc-type-left',
			// 		'value' => 'left'
			// 	),
			// 	array(
			// 		'element'  => '.page-heading',
			// 		'function' => 'toggleClass',
			// 		'class' => 'none',
			// 		'value' => 'disable'
			// 	),
			// ),
		),
		
		'breadcrumb_title_tag' => array(
			'name'        => 'breadcrumb_title_tag',
			'type'        => 'select',
			'settings'    => 'breadcrumb_title_tag',
			'label'       => esc_html__( 'Breadcrumbs Title tag', 'xstore' ),
			'description' => esc_html__( 'Choose the breadcrumbs main title tag.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 'h1',
			'choices'     => array(
				'h1'   => esc_html__( 'h1', 'xstore' ),
				'h2'   => esc_html__( 'h2', 'xstore' ),
				'h3'   => esc_html__( 'h3', 'xstore' ),
				'h4'   => esc_html__( 'h4', 'xstore' ),
				'h5'   => esc_html__( 'h5', 'xstore' ),
				'p'    => esc_html__( 'Paragraph', 'xstore' ),
				'span' => esc_html__( 'Span', 'xstore' ),
				'div'  => esc_html__( 'Div', 'xstore' ),
			),
		),
		
		'breadcrumb_category_title_tag' => array(
			'name'        => 'breadcrumb_category_title_tag',
			'type'        => 'select',
			'settings'    => 'breadcrumb_category_title_tag',
			'label'       => esc_html__( 'Breadcrumbs Categories Title tag', 'xstore' ),
			'description' => esc_html__( 'Choose the categories breadcrumbs main title tag.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 'h1',
			'choices'     => array(
				'h1'   => esc_html__( 'h1', 'xstore' ),
				'h2'   => esc_html__( 'h2', 'xstore' ),
				'h3'   => esc_html__( 'h3', 'xstore' ),
				'h4'   => esc_html__( 'h4', 'xstore' ),
				'h5'   => esc_html__( 'h5', 'xstore' ),
				'p'    => esc_html__( 'Paragraph', 'xstore' ),
				'span' => esc_html__( 'Span', 'xstore' ),
				'div'  => esc_html__( 'Div', 'xstore' ),
			),
		),
		
		'cart_special_breadcrumbs' => array(
			'name'        => 'cart_special_breadcrumbs',
			'type'        => 'toggle',
			'settings'    => 'cart_special_breadcrumbs',
			'label'       => esc_html__( 'Special breadcrumbs on cart, checkout, order page', 'xstore' ),
			'description' => esc_html__( 'Turn on to show step by step breadcrumbs on cart, checkout and order page.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 1,
		),
		
		'breadcrumb_bg' => array(
			'name'        => 'breadcrumb_bg',
			'type'        => 'background',
			'settings'    => 'breadcrumb_bg',
			'label'       => esc_html__( 'Breadcrumbs background', 'xstore' ),
			'description' => esc_html__( 'Controls the background style of breadcrumbs area.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => array(
				'background-color'      => '',
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
					'element' => '.page-heading',
				),
			),
		),
		
		'breadcrumb_color' => array(
			'name'        => 'breadcrumb_color',
			'type'        => 'select',
			'settings'    => 'breadcrumb_color',
			'label'       => esc_html__( 'Breadcrumbs text color', 'xstore' ),
			'description' => esc_html__( 'Choose the breadcrumbs text color scheme.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 'dark',
			'choices'     => $text_color_scheme,
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => '.page-heading',
					'function' => 'toggleClass',
					'class'    => 'bc-color-dark',
					'value'    => 'dark'
				),
				array(
					'element'  => '.page-heading',
					'function' => 'toggleClass',
					'class'    => 'bc-color-white',
					'value'    => 'white'
				),
			),
		),
		
		'breadcrumb_effect' => array(
			'name'        => 'breadcrumb_effect',
			'type'        => 'select',
			'settings'    => 'breadcrumb_effect',
			'label'       => esc_html__( 'Breadcrumbs effect', 'xstore' ),
			'description' => esc_html__( 'Choose the animation for the breadcrumbs area.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 'mouse',
			'choices'     => array(
				'none'        => esc_html__( 'None', 'xstore' ),
				'mouse'       => esc_html__( 'Parallax on mouse move', 'xstore' ),
				'text-scroll' => esc_html__( 'Text animation on scroll', 'xstore' ),
			),
			
			// 'transport' => 'postMessage',
			// 'js_vars'     => array(
			// 	array(
			// 		'element'  => '.page-heading',
			// 		'function' => 'toggleClass',
			// 		'class' => 'bc-effect-none',
			// 		'value' => 'none'
			// 	),
			// 	array(
			// 		'element'  => '.page-heading',
			// 		'function' => 'toggleClass',
			// 		'class' => 'bc-effect-mouse',
			// 		'value' => 'mouse'
			// 	),
			// 	array(
			// 		'element'  => '.page-heading',
			// 		'function' => 'toggleClass',
			// 		'class' => 'bc-effect-text-scroll',
			// 		'value' => 'text-scroll'
			// 	),
			// ),
		),
		
		'breadcrumb_padding' => array(
			'name'        => 'breadcrumb_padding',
			'type'        => 'dimensions',
			'settings'    => 'breadcrumb_padding',
			'label'       => esc_html__( 'Breadcrumbs paddings', 'xstore' ),
			'description' => sprintf( esc_html__( 'Controls the paddings for the breadcrumbs area. Leave empty to use default values. You also may set up your breadcrumbs settings in %1s when you have header overlap setting enabled', 'xstore' ), '<span class="et_edit" data-section="header_overlap_content_separator" style="color: #222;">' . esc_html__( 'Breadcrumbs settings', 'xstore' ) . '</span>' ),
			'section'     => 'breadcrumbs',
			'transport'   => 'auto',
			'default'     => $paddings_empty,
			'choices'     => array(
				'labels' => $padding_labels,
			),
			'output'      => array(
				array(
					'choice'   => 'padding-top',
					'element'  => '
					.page-heading, .et-header-overlap .page-heading',
					'property' => 'padding-top',
				),
				array(
					'choice'   => 'padding-bottom',
					'element'  => '
					.page-heading, .et-header-overlap .page-heading',
					'property' => 'padding-bottom',
				),
				array(
					'choice'   => 'padding-left',
					'element'  => '
					.page-heading, .et-header-overlap .page-heading',
					'property' => 'padding-left',
				),
				array(
					'choice'   => 'padding-right',
					'element'  => '
					.page-heading, .et-header-overlap .page-heading',
					'property' => 'padding-right',
				),
			),
		),
		
		'bc_breadcrumbs_font' => array(
			'name'        => 'bc_breadcrumbs_font',
			'type'        => 'typography',
			'settings'    => 'bc_breadcrumbs_font',
			'label'       => esc_html__( 'Breadcrumbs font', 'xstore' ),
			'description' => esc_html__( 'Use to change font family and font styles for the breadcrumbs links.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'color'          => '',
				'letter-spacing' => '',
				'text-transform' => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' =>
						'.page-heading .breadcrumbs,
					.page-heading .woocommerce-breadcrumb,
					.page-heading .bbp-breadcrumb,
					.page-heading .a-center,
					.page-heading .title,
					.page-heading a,
					.page-heading .span-title,
					[class*=" paged-"] .page-heading.bc-type-left2 .span-title,
					.bbp-breadcrumb-current,
					.page-heading .breadcrumbs a,
					.page-heading .woocommerce-breadcrumb a,
					.page-heading .bbp-breadcrumb a'
				),
			),
		
		),
		
		'bc_title_font' => array(
			'name'        => 'bc_title_font',
			'type'        => 'typography',
			'settings'    => 'bc_title_font',
			'label'       => esc_html__( 'Breadcrumbs title font', 'xstore' ),
			'description' => esc_html__( 'Use to change font family and font styles for the title in the breadcrumbs.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'color'          => '',
				'letter-spacing' => '',
				'text-transform' => '',
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' =>
						'.page-heading.bc-type-left2 .title,
					.page-heading.bc-type-left .title,
					.page-heading.bc-type-default .title,
					[class*=" paged-"] .page-heading .span-title:last-of-type,
					[class*=" paged-"] .page-heading.bc-type-left2 .span-title:last-of-type,
					.single-post .page-heading.bc-type-left2 #breadcrumb a:last-of-type,
					.bbp-breadcrumb-current',
				),
			),
		
		),
		
		'bc_page_numbers' => array(
			'name'        => 'bc_page_numbers',
			'type'        => 'toggle',
			'settings'    => 'bc_page_numbers',
			'label'       => esc_html__( 'Disable page numbers on shop pages', 'xstore' ),
			'description' => esc_html__( 'Turn on to disable Page numbers on shop pages.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 0,
		),
		
		'return_to_previous' => array(
			'name'        => 'return_to_previous',
			'type'        => 'toggle',
			'settings'    => 'return_to_previous',
			'label'       => esc_html__( '"Return to previous page" link', 'xstore' ),
			'description' => esc_html__( 'Turn on to show Return to previous page link.', 'xstore' ),
			'section'     => 'breadcrumbs',
			'default'     => 1,
		),
		
		'bc_return_font' => array(
			'name'            => 'bc_return_font',
			'type'            => 'typography',
			'settings'        => 'bc_return_font',
			'label'           => esc_html__( '"Return to previous page" font', 'xstore' ),
			'description'     => esc_html__( 'Use to change font family and font styles for the "return to previous page" link.', 'xstore' ),
			'section'         => 'breadcrumbs',
			'default'         => array(
				'font-family'    => '',
				'variant'        => '',
				'font-size'      => '',
				'line-height'    => '',
				'color'          => '',
				'letter-spacing' => '',
				'text-transform' => '',
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context' => array( 'editor', 'front' ),
					'element' => '.page-heading .back-history, .page-heading .breadcrumbs .back-history, .page-heading .woocommerce-breadcrumb .back-history, .page-heading .bbp-breadcrumb .back-history, .single-post .page-heading.bc-type-left2 #breadcrumb a:last-of-type',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'return_to_previous',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );