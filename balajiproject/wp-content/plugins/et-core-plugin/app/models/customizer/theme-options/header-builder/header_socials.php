<?php
/**
 * The template created for displaying header socials options
 *
 * @version 1.0.1
 * @since   1.4.0
 * last changes in 1.5.4
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'header_socials' => array(
			'name'       => 'header_socials',
			'title'      => esc_html__( 'Socials', 'xstore-core' ),
			'panel'      => 'header-builder',
			'icon'       => 'dashicons-facebook',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/header_socials', function ( $fields ) use ( $separators, $strings, $choices, $icons ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'header_socials_content_separator'            => array(
			'name'     => 'header_socials_content_separator',
			'type'     => 'custom',
			'settings' => 'header_socials_content_separator',
			'section'  => 'header_socials',
			'default'  => $separators['content'],
			'priority' => 1,
		),
		
		// header_socials_type
		'header_socials_type_et-desktop'              => array(
			'name'            => 'header_socials_type_et-desktop',
			'type'            => 'radio-image',
			'settings'        => 'header_socials_type_et-desktop',
			'label'           => $strings['label']['type'],
			'section'         => 'header_socials',
			'default'         => 'type1',
			'multiple'        => 1,
			'choices'         => array(
				'type1' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/header_socials/Social-icon-1.svg',
				'type2' => ETHEME_CODE_CUSTOMIZER_IMAGES . '/header/header_socials/Social-icon-2.svg'
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_socials_type' => array(
					'selector'        => '.et_b_header-socials',
					'render_callback' => 'header_socials_callback'
				),
			),
			'priority'        => 2,
		),
		
		// header_socials_direction
		'header_socials_direction_et-desktop'         => array(
			'name'      => 'header_socials_direction_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'header_socials_direction_et-desktop',
			'label'     => $strings['label']['direction'],
			'section'   => 'header_socials',
			'default'   => 'hor',
			'choices'   => $choices['direction']['type1'],
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.et_b_header-socials.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'flex-col',
					'value'    => 'ver'
				),
				array(
					'element'  => '.et_b_header-socials.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'flex-row',
					'value'    => 'hor'
				),
			),
			'priority'  => 3,
		),
		
		// style separator
		'header_socials_style_separator'              => array(
			'name'     => 'header_socials_style_separator',
			'type'     => 'custom',
			'settings' => 'header_socials_style_separator',
			'section'  => 'header_socials',
			'default'  => $separators['style'],
			'priority' => 10,
		),
		
		// header_socials_content_alignment
		'header_socials_content_alignment_et-desktop' => array(
			'name'      => 'header_socials_content_alignment_et-desktop',
			'type'      => 'radio-buttonset',
			'settings'  => 'header_socials_content_alignment_et-desktop',
			'label'     => $strings['label']['alignment'],
			'section'   => 'header_socials',
			'default'   => 'start',
			'choices'   => $choices['alignment'],
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.header-wrapper .et_b_header-socials.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'justify-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.header-wrapper .et_b_header-socials.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'justify-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.header-wrapper .et_b_header-socials.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'justify-content-end',
					'value'    => 'end'
				),
			),
		),
		
		// header_socials_content_alignment
		'header_socials_content_alignment_et-mobile'  => array(
			'name'      => 'header_socials_content_alignment_et-mobile',
			'type'      => 'radio-buttonset',
			'settings'  => 'header_socials_content_alignment_et-mobile',
			'label'     => $strings['label']['alignment'],
			'section'   => 'header_socials',
			'default'   => 'start',
			'choices'   => $choices['alignment'],
			'transport' => 'postMessage',
			'js_vars'   => array(
				array(
					'element'  => '.mobile-header-wrapper .et_b_header-socials.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-start',
					'value'    => 'start'
				),
				array(
					'element'  => '.mobile-header-wrapper .et_b_header-socials.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-center',
					'value'    => 'center'
				),
				array(
					'element'  => '.mobile-header-wrapper .et_b_header-socials.et_element-top-level',
					'function' => 'toggleClass',
					'class'    => 'mob-justify-content-end',
					'value'    => 'end'
				),
			),
		),
		
		// header_socials_elements_zoom
		'header_socials_elements_zoom_et-desktop'     => array(
			'name'      => 'header_socials_elements_zoom_et-desktop',
			'type'      => 'slider',
			'settings'  => 'header_socials_elements_zoom_et-desktop',
			'label'     => esc_html__( 'Icons zoom (%)', 'xstore-core' ),
			'section'   => 'header_socials',
			'default'   => 100,
			'choices'   => array(
				'min'  => '30',
				'max'  => '250',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_header-socials.et_element-top-level',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				)
			)
		),
		
		// header_socials_elements_zoom 
		'header_socials_elements_zoom_et-mobile'      => array(
			'name'      => 'header_socials_elements_zoom_et-mobile',
			'type'      => 'slider',
			'settings'  => 'header_socials_elements_zoom_et-mobile',
			'label'     => esc_html__( 'Icons zoom (%)', 'xstore-core' ),
			'section'   => 'header_socials',
			'default'   => 100,
			'choices'   => array(
				'min'  => '30',
				'max'  => '250',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.mobile-header-wrapper .et_b_header-socials.et_element-top-level',
					'property'      => '--content-zoom',
					'value_pattern' => 'calc($em * .01)'
				)
			)
		),
		
		// header_socials_elements_spacing
		'header_socials_elements_spacing_et-desktop'  => array(
			'name'      => 'header_socials_elements_spacing_et-desktop',
			'type'      => 'slider',
			'settings'  => 'header_socials_elements_spacing_et-desktop',
			'label'     => esc_html__( 'Margin between elements (px)', 'xstore-core' ),
			'section'   => 'header_socials',
			'default'   => 10,
			'choices'   => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				// array(
				// 'context'	=> array('editor', 'front'),
				// 	'element' => '.et_b_header-socials.et_element-top-level',
				// 	'property' => 'margin',
				// 	'value_pattern' => '0 -$px'
				// ),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_header-socials.et_element-top-level.flex-row a',
					'property'      => 'margin',
					'value_pattern' => '0 $px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.et_b_header-socials.et_element-top-level.flex-col a + a',
					'property'      => 'margin',
					'value_pattern' => '$px 0 0 0'
				)
			)
		),
		
		// header_socials_elements_spacing
		'header_socials_elements_spacing_et-mobile'   => array(
			'name'      => 'header_socials_elements_spacing_et-mobile',
			'type'      => 'slider',
			'settings'  => 'header_socials_elements_spacing_et-mobile',
			'label'     => esc_html__( 'Margin between elements (px)', 'xstore-core' ),
			'section'   => 'header_socials',
			'default'   => 5,
			'choices'   => array(
				'min'  => '0',
				'max'  => '100',
				'step' => '1',
			),
			'transport' => 'auto',
			'output'    => array(
				// array(
				// 'context'	=> array('editor', 'front'),
				// 	'element' => '.mobile-header-wrapper .et_b_header-socials.et_element-top-level',
				// 	'property' => 'margin',
				// 	'value_pattern' => '0 -$px'
				// ),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.mobile-header-wrapper .et_b_header-socials.et_element-top-level.flex-row a',
					'property'      => 'margin',
					'value_pattern' => '0 $px'
				),
				array(
					'context'       => array( 'editor', 'front' ),
					'element'       => '.mobile-header-wrapper .et_b_header-socials.et_element-top-level.flex-col a + a',
					'property'      => 'margin',
					'value_pattern' => '$px 0 0 0'
				)
			)
		),
		
		// advanced separator
		'header_socials_advanced_separator'           => array(
			'name'     => 'header_socials_advanced_separator',
			'type'     => 'custom',
			'settings' => 'header_socials_advanced_separator',
			'section'  => 'header_socials',
			'default'  => $separators['advanced'],
			'priority' => 10,
		),
		
		// header_socials_target
		'header_socials_target_et-desktop'            => array(
			'name'            => 'header_socials_target_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'header_socials_target_et-desktop',
			'label'           => $strings['label']['target_blank'],
			'section'         => 'header_socials',
			'default'         => 0,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_socials_target' => array(
					'selector'        => '.et_b_header-socials',
					'render_callback' => 'header_socials_callback'
				),
			),
		),
		
		// header_socials_no_follow
		'header_socials_no_follow_et-desktop'         => array(
			'name'            => 'header_socials_no_follow_et-desktop',
			'type'            => 'toggle',
			'settings'        => 'header_socials_no_follow_et-desktop',
			'label'           => $strings['label']['rel_no_follow'],
			'section'         => 'header_socials',
			'default'         => 0,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_socials_no_follow' => array(
					'selector'        => '.et_b_header-socials',
					'render_callback' => 'header_socials_callback'
				),
			),
		),
	);
	
	return array_merge( $fields, $args );
	
} );

add_filter( 'et/customizer/add/fields', function ( $fields ) use ( $separators, $strings, $choices, $icons ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// header_socials_package
		'header_socials_package_et-desktop' => array(
			'name'            => 'header_socials_package_et-desktop',
			'type'            => 'repeater',
			'settings'        => 'header_socials_package_et-desktop',
			'label'           => $strings['label']['elements'],
			'section'         => 'header_socials',
			'priority'        => 4,
			'dynamic'         => false,
			'row_label'       => array(
				'type'  => 'field',
				'value' => esc_html__( 'Social ', 'xstore-core' ),
				'field' => 'social_name',
			),
			'button_label'    => esc_html__( 'Add new social', 'xstore-core' ),
			'default'         => array(
				array(
					'social_name' => esc_html__( 'Facebook', 'xstore-core' ),
					'social_url'  => '#',
					'social_icon' => 'et_icon-facebook'
				),
				array(
					'social_name' => esc_html__( 'Twitter', 'xstore-core' ),
					'social_url'  => '#',
					'social_icon' => 'et_icon-twitter'
				),
				array(
					'social_name' => esc_html__( 'Instagram', 'xstore-core' ),
					'social_url'  => '#',
					'social_icon' => 'et_icon-instagram'
				),
				array(
					'social_name' => esc_html__( 'Youtube', 'xstore-core' ),
					'social_url'  => '#',
					'social_icon' => 'et_icon-youtube'
				),
				array(
					'social_name' => esc_html__( 'Linkedin', 'xstore-core' ),
					'social_url'  => '#',
					'social_icon' => 'et_icon-linkedin'
				),
			),
			'fields'          => array(
				'social_name' => array(
					'type'    => 'text',
					'label'   => esc_html__( 'Social name', 'xstore-core' ),
					'default' => '',
				),
				'social_url'  => array(
					'type'        => 'link',
					'label'       => __( 'Link Control', 'xstore-core' ),
					'description' => esc_html__( 'Add link to your social account.', 'xstore-core' ),
					'default'     => '#',
				),
				'social_icon' => array(
					'type'    => 'select',
					'default' => 'et_icon-facebook',
					'choices' => $icons['socials'],
				)
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_socials_package_et-desktop' => array(
					'selector'        => '.et_b_header-socials',
					'render_callback' => 'header_socials_callback'
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
