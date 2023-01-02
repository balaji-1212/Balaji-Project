<?php
/**
 * The template created for saving in customizer header builder elements (hidden in customizer)
 *
 * @version 1.0.1
 * @since   1.4.0
 * last changes in 1.5.5
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'header_builder_elements' => array(
			'name'  => 'header_builder_elements',
			'title' => false,
			'panel' => 'header-builder',
			'icon'  => false,
		)
	);
	
	return array_merge( $sections, $args );
} );


add_filter( 'et/customizer/add/fields', function ( $fields ) use ( $strings ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'connect_block_package' => array(
			'name'            => 'connect_block_package',
			'type'            => 'repeater',
			'label'           => false, // to prevent searching
			'section'         => 'header_builder_elements',
			'priority'        => 10,
			'row_label'       => array(
				'type'  => 'field',
				'value' => esc_html__( 'block', 'xstore-core' ),
				'field' => 'connect_block',
			),
			'button_label'    => esc_html__( 'Add new block', 'xstore-core' ),
			'settings'        => 'connect_block_package',
			'fields'          => array(
				'id'        => array(
					'type'    => 'text',
					'label'   => esc_html__( 'id', 'xstore-core' ),
					'default' => '',
				),
				'data'      => array(
					'type'    => 'text',
					'label'   => esc_html__( 'data', 'xstore-core' ),
					'default' => '',
				),
				'type'      => array(
					'type'  => 'text',
					'label' => $strings['label']['type'],
				),
				'separator' => array(
					'type'  => 'text',
					'label' => esc_html__( 'separator', 'xstore-core' ),
				),
				'align'     => array(
					'type'    => 'text',
					'label'   => esc_html__( 'align', 'xstore-core' ),
					'default' => 'center'
				),
				'spacing'   => array(
					'type'    => 'text',
					'label'   => esc_html__( 'spacing', 'xstore-core' ),
					'default' => '5'
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'connect_block_package' => array(
					'selector'        => '.header-wrapper',
					'render_callback' => 'header_callback'
				),
			),
		),
		
		'connect_block_mobile_package' => array(
			'name'            => 'connect_block_mobile_package',
			'type'            => 'repeater',
			'label'           => false, // to prevent searching
			'section'         => 'header_builder_elements',
			'priority'        => 10,
			'row_label'       => array(
				'type'  => 'field',
				'value' => esc_html__( 'block ', 'xstore-core' ),
				'field' => 'connect_block_mobile_package',
			),
			'button_label'    => esc_html__( 'Add new block', 'xstore-core' ),
			'settings'        => 'connect_block_mobile_package',
			'fields'          => array(
				'id'        => array(
					'type'    => 'text',
					'label'   => esc_html__( 'id', 'xstore-core' ),
					'default' => '',
				),
				'data'      => array(
					'type'    => 'text',
					'label'   => esc_html__( 'data', 'xstore-core' ),
					'default' => '',
				),
				'type'      => array(
					'type'  => 'text',
					'label' => $strings['label']['type'],
				),
				'separator' => array(
					'type'  => 'text',
					'label' => esc_html__( 'separator', 'xstore-core' ),
				),
				'align'     => array(
					'type'    => 'text',
					'label'   => esc_html__( 'align', 'xstore-core' ),
					'default' => 'center'
				),
				'spacing'   => array(
					'type'    => 'text',
					'label'   => esc_html__( 'spacing', 'xstore-core' ),
					'default' => '5'
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'connect_block_mobile_package' => array(
					'selector'        => '.mobile-header-wrapper',
					'render_callback' => 'mobile_header_callback'
				),
			),
		),
		
		'header_top_elements' => array(
			'name'            => 'header_top_elements',
			'type'            => 'text',
			'settings'        => 'header_top_elements',
			'label'           => false, // to prevent searching
			'section'         => 'header_builder_elements',
			'default'         => '',
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_top_elements' => array(
					'selector'        => '.header-wrapper .header-top-wrapper > .header-top > .et-row-container > .et-wrap-columns',
					'render_callback' => 'header_top_callback'
				),
			),
		),
		
		'header_mobile_top_elements' => array(
			'name'            => 'header_mobile_top_elements',
			'type'            => 'text',
			'settings'        => 'header_mobile_top_elements',
			'label'           => false, // to prevent searching
			'section'         => 'header_builder_elements',
			'default'         => '',
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_mobile_top_elements' => array(
					'selector'        => '.mobile-header-wrapper .header-top-wrapper > .header-top > .et-row-container > .et-wrap-columns',
					'render_callback' => 'mobile_header_top_callback'
				),
			),
		),
		
		'header_main_elements' => array(
			'name'            => 'header_main_elements',
			'type'            => 'text',
			'settings'        => 'header_main_elements',
			'label'           => false, // to prevent searching
			'section'         => 'header_builder_elements',
			'default'         => '',
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_main_elements' => array(
					'selector'        => '.header-wrapper .header-main-wrapper > .header-main > .et-row-container > .et-wrap-columns',
					'render_callback' => 'header_main_callback'
				),
			),
		),
		
		'header_mobile_main_elements' => array(
			'name'            => 'header_mobile_main_elements',
			'type'            => 'text',
			'settings'        => 'header_mobile_main_elements',
			'label'           => false, // to prevent searching
			'section'         => 'header_builder_elements',
			'default'         => '',
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_mobile_main_elements' => array(
					'selector'        => '.mobile-header-wrapper .header-main-wrapper > .header-main > .et-row-container > .et-wrap-columns',
					'render_callback' => 'mobile_header_main_callback'
				),
			),
		),
		
		'header_bottom_elements' => array(
			'name'            => 'header_bottom_elements',
			'type'            => 'text',
			'settings'        => 'header_bottom_elements',
			'label'           => false, // to prevent searching
			'section'         => 'header_builder_elements',
			'default'         => '',
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_bottom_elements' => array(
					'selector'        => '.header-wrapper .header-bottom-wrapper > .header-bottom > .et-row-container > .et-wrap-columns',
					'render_callback' => 'header_bottom_callback'
				),
			),
		),
		
		'header_mobile_bottom_elements' => array(
			'name'            => 'header_mobile_bottom_elements',
			'type'            => 'text',
			'settings'        => 'header_mobile_bottom_elements',
			// 'label'    => esc_html__( 'header_mobile_bottom_elements', 'xstore-core' ),
			'label'           => false, // to prevent searching
			'section'         => 'header_builder_elements',
			'default'         => '',
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'header_mobile_bottom_elements' => array(
					'selector'        => '.mobile-header-wrapper .header-bottom-wrapper > .header-bottom > .et-row-container > .et-wrap-columns',
					'render_callback' => 'mobile_header_bottom_callback'
				),
			),
		),
	);
	
	return array_merge( $fields, $args );
} );
