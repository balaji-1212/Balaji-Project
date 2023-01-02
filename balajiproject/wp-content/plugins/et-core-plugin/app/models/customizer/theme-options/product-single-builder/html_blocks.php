<?php
/**
 * The template created for displaying single product custom html options
 *
 * @version 1.0.0
 * @since   0.0.1
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'single_product_html_blocks' => array(
			'name'        => 'single_product_html_blocks',
			'title'       => esc_html__( 'Custom HTML', 'xstore-core' ),
			'description' => esc_html__( 'Most used classes for elements' ) . '<br/><code>
			.justify-content-start, 
			.justify-content-end, 
			.justify-content-center, 
			.text-lowercase, 
			.text-capitalize, 
			.text-uppercase, 
			.text-underline, 
			.flex, 
			.flex-inline, 
			.block, 
			.inline-block, 
			.none, 
			.list-style-none, 
			.full-width
			</code>',
			'panel'       => 'single_product_builder',
			'icon'        => 'dashicons-editor-code',
			'type'        => 'kirki-lazy',
			'dependency'  => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


add_filter( 'et/customizer/add/fields/single_product_html_blocks', function ( $fields ) use ( $strings, $sep_style ) {
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
		'single_product_html_block1_content_separator' => array(
			'name'     => 'single_product_html_block1_content_separator',
			'type'     => 'custom',
			'settings' => 'single_product_html_block1_content_separator',
			'section'  => 'single_product_html_blocks',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-settings"></span> <span style="padding-left: 3px;">' . esc_html__( 'Html block 1', 'xstore-core' ) . '</span></div>',
			'priority' => 1,
		),
		
		// html_block1
		'single_product_html_block1'                   => array(
			'name'            => 'single_product_html_block1',
			'type'            => 'editor',
			'settings'        => 'single_product_html_block1',
			'label'           => esc_html__( 'Html block 1', 'xstore-core' ),
			'description'     => $strings['label']['editor_control'],
			'section'         => 'single_product_html_blocks',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'single_product_html_block1_sections',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'single_product_html_block1' => array(
					'selector'        => '.single_product-html_block1',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'html_backup' => 'single_product_html_block1',
						) );
					},
				),
			),
			'priority'        => 2,
		),
		
		// html_block1_sections
		'single_product_html_block1_sections'          => array(
			'name'            => 'single_product_html_block1_sections',
			'type'            => 'toggle',
			'settings'        => 'single_product_html_block1_sections',
			'label'           => $strings['label']['use_static_block'],
			'section'         => 'single_product_html_blocks',
			'default'         => 0,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'single_product_html_block1_sections' => array(
					'selector'        => '.single_product-html_block1',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'section'         => 'single_product_html_block1_section',
							'sections'        => 'single_product_html_block1_sections',
							'html_backup'     => 'single_product_html_block1',
							'section_content' => true
						) );
					},
				),
			),
			'priority'        => 3,
		),
		
		// html_block1_section
		'single_product_html_block1_section'           => array(
			'name'            => 'single_product_html_block1_section',
			'type'            => 'select',
			'settings'        => 'single_product_html_block1_section',
			'label'           => sprintf( esc_html__( 'Choose %1s for Html Block 1', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: #555">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
			'section'         => 'single_product_html_blocks',
			'default'         => '',
			'choices'         => $sections,
			'active_callback' => array(
				array(
					'setting'  => 'single_product_html_block1_sections',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'single_product_html_block1_section' => array(
					'selector'        => '.single_product-html_block1',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'section'         => 'single_product_html_block1_section',
							'sections'        => 'single_product_html_block1_sections',
							'html_backup'     => 'single_product_html_block1',
							'section_content' => true
						) );
					},
				),
			),
			'priority'        => 4,
		),
		
		// content separator
		'single_product_html_block2_content_separator' => array(
			'name'     => 'single_product_html_block2_content_separator',
			'type'     => 'custom',
			'settings' => 'single_product_html_block2_content_separator',
			'section'  => 'single_product_html_blocks',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-settings"></span> <span style="padding-left: 3px;">' . esc_html__( 'Html block 2', 'xstore-core' ) . '</span></div>',
			'priority' => 5,
		),
		
		// html_block2
		'single_product_html_block2'                   => array(
			'name'            => 'single_product_html_block2',
			'type'            => 'editor',
			'settings'        => 'single_product_html_block2',
			'label'           => esc_html__( 'Html block 2', 'xstore-core' ),
			'description'     => $strings['label']['editor_control'],
			'section'         => 'single_product_html_blocks',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'single_product_html_block2_sections',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'single_product_html_block2' => array(
					'selector'        => '.single_product-html_block2',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'html_backup' => 'single_product_html_block2',
						) );
					},
				),
			),
			'priority'        => 6,
		),
		
		// html_block2_sections
		'single_product_html_block2_sections'          => array(
			'name'            => 'single_product_html_block2_sections',
			'type'            => 'toggle',
			'settings'        => 'single_product_html_block2_sections',
			'label'           => $strings['label']['use_static_block'],
			'section'         => 'single_product_html_blocks',
			'default'         => 0,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'single_product_html_block2_sections' => array(
					'selector'        => '.single_product-html_block1',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'section'         => 'single_product_html_block2_section',
							'sections'        => 'single_product_html_block2_sections',
							'html_backup'     => 'single_product_html_block2',
							'section_content' => true
						) );
					},
				),
			),
			'priority'        => 7,
		),
		
		// html_block2_section
		'single_product_html_block2_section'           => array(
			'name'            => 'single_product_html_block2_section',
			'type'            => 'select',
			'settings'        => 'single_product_html_block2_section',
			'label'           => sprintf( esc_html__( 'Choose %1s for Html Block 2', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: #555">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
			'section'         => 'single_product_html_blocks',
			'default'         => '',
			'choices'         => $sections,
			'active_callback' => array(
				array(
					'setting'  => 'single_product_html_block2_sections',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'single_product_html_block2_section' => array(
					'selector'        => '.single_product-html_block2',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'section'         => 'single_product_html_block2_section',
							'sections'        => 'single_product_html_block2_sections',
							'html_backup'     => 'single_product_html_block2',
							'section_content' => true
						) );
					},
				),
			),
			'priority'        => 8,
		),
		
		// content separator
		'single_product_html_block3_content_separator' => array(
			'name'     => 'single_product_html_block3_content_separator',
			'type'     => 'custom',
			'settings' => 'single_product_html_block3_content_separator',
			'section'  => 'single_product_html_blocks',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-settings"></span> <span style="padding-left: 3px;">' . esc_html__( 'Html block 3', 'xstore-core' ) . '</span></div>',
			'priority' => 9,
		),
		
		// html_block3
		'single_product_html_block3'                   => array(
			'name'            => 'single_product_html_block3',
			'type'            => 'editor',
			'settings'        => 'single_product_html_block3',
			'label'           => esc_html__( 'Html block 3', 'xstore-core' ),
			'description'     => $strings['label']['editor_control'],
			'section'         => 'single_product_html_blocks',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'single_product_html_block3_sections',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'single_product_html_block3' => array(
					'selector'        => '.single_product-html_block3',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'html_backup' => 'single_product_html_block3',
						) );
					},
				),
			),
			'priority'        => 10,
		),
		
		// html_block3_sections
		'single_product_html_block3_sections'          => array(
			'name'            => 'single_product_html_block3_sections',
			'type'            => 'toggle',
			'settings'        => 'single_product_html_block3_sections',
			'label'           => $strings['label']['use_static_block'],
			'section'         => 'single_product_html_blocks',
			'default'         => 0,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'single_product_html_block3_sections' => array(
					'selector'        => '.single_product-html_block3',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'section'         => 'single_product_html_block3_section',
							'sections'        => 'single_product_html_block3_sections',
							'html_backup'     => 'single_product_html_block3',
							'section_content' => true
						) );
					},
				),
			),
			'priority'        => 11,
		),
		
		// html_block3_section
		'single_product_html_block3_section'           => array(
			'name'            => 'single_product_html_block3_section',
			'type'            => 'select',
			'settings'        => 'single_product_html_block3_section',
			'label'           => sprintf( esc_html__( 'Choose %1s for Html Block 3', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: #555">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
			'section'         => 'single_product_html_blocks',
			'default'         => '',
			'choices'         => $sections,
			'active_callback' => array(
				array(
					'setting'  => 'single_product_html_block3_sections',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'single_product_html_block3_section' => array(
					'selector'        => '.single_product-html_block3',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'section'         => 'single_product_html_block3_section',
							'sections'        => 'single_product_html_block3_sections',
							'html_backup'     => 'single_product_html_block3',
							'section_content' => true
						) );
					},
				),
			),
			'priority'        => 12,
		),
		
		// content separator
		'single_product_html_block4_content_separator' => array(
			'name'     => 'single_product_html_block4_content_separator',
			'type'     => 'custom',
			'settings' => 'single_product_html_block4_content_separator',
			'section'  => 'single_product_html_blocks',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-settings"></span> <span style="padding-left: 3px;">' . esc_html__( 'Html block 4', 'xstore-core' ) . '</span></div>',
			'priority' => 13,
		),
		
		// html_block4
		'single_product_html_block4'                   => array(
			'name'            => 'single_product_html_block4',
			'type'            => 'editor',
			'settings'        => 'single_product_html_block4',
			'label'           => esc_html__( 'Html block 4', 'xstore-core' ),
			'description'     => $strings['label']['editor_control'],
			'section'         => 'single_product_html_blocks',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'single_product_html_block4_sections',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'single_product_html_block4' => array(
					'selector'        => '.single_product-html_block4',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'html_backup' => 'single_product_html_block4',
						) );
					},
				),
			),
			'priority'        => 14,
		),
		
		// html_block4_sections
		'single_product_html_block4_sections'          => array(
			'name'            => 'single_product_html_block4_sections',
			'type'            => 'toggle',
			'settings'        => 'single_product_html_block4_sections',
			'label'           => $strings['label']['use_static_block'],
			'section'         => 'single_product_html_blocks',
			'default'         => 0,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'single_product_html_block4_sections' => array(
					'selector'        => '.single_product-html_block4',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'section'         => 'single_product_html_block4_section',
							'sections'        => 'single_product_html_block4_sections',
							'html_backup'     => 'single_product_html_block4',
							'section_content' => true
						) );
					},
				),
			),
			'priority'        => 15,
		),
		
		// html_block4_section
		'single_product_html_block4_section'           => array(
			'name'            => 'single_product_html_block4_section',
			'type'            => 'select',
			'settings'        => 'single_product_html_block4_section',
			'label'           => sprintf( esc_html__( 'Choose %1s for Html block 4', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: #555">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
			'section'         => 'single_product_html_blocks',
			'default'         => '',
			'choices'         => $sections,
			'active_callback' => array(
				array(
					'setting'  => 'single_product_html_block4_sections',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'single_product_html_block4_section' => array(
					'selector'        => '.single_product-html_block4',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'section'         => 'single_product_html_block4_section',
							'sections'        => 'single_product_html_block4_sections',
							'html_backup'     => 'single_product_html_block4',
							'section_content' => true
						) );
					},
				),
			),
			'priority'        => 16,
		),
		
		// content separator
		'single_product_html_block5_content_separator' => array(
			'name'     => 'single_product_html_block5_content_separator',
			'type'     => 'custom',
			'settings' => 'single_product_html_block5_content_separator',
			'section'  => 'single_product_html_blocks',
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-admin-settings"></span> <span style="padding-left: 3px;">' . esc_html__( 'Html block 5', 'xstore-core' ) . '</span></div>',
			'priority' => 17,
		),
		
		// html_block5
		'single_product_html_block5'                   => array(
			'name'            => 'single_product_html_block5',
			'type'            => 'editor',
			'settings'        => 'single_product_html_block5',
			'label'           => esc_html__( 'Html block 5', 'xstore-core' ),
			'description'     => $strings['label']['editor_control'],
			'section'         => 'single_product_html_blocks',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'single_product_html_block5_sections',
					'operator' => '!=',
					'value'    => '1',
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'single_product_html_block5' => array(
					'selector'        => '.single_product-html_block5',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'html_backup' => 'single_product_html_block5',
						) );
					},
				),
			),
			'priority'        => 18,
		),
		
		// html_block5_sections
		'single_product_html_block5_sections'          => array(
			'name'            => 'single_product_html_block5_sections',
			'type'            => 'toggle',
			'settings'        => 'single_product_html_block5_sections',
			'label'           => $strings['label']['use_static_block'],
			'section'         => 'single_product_html_blocks',
			'default'         => 0,
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'single_product_html_block5_sections' => array(
					'selector'        => '.single_product-html_block5',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'section'         => 'single_product_html_block5_section',
							'sections'        => 'single_product_html_block5_sections',
							'html_backup'     => 'single_product_html_block5',
							'section_content' => true
						) );
					},
				),
			),
			'priority'        => 19,
		),
		
		// html_block5_section
		'single_product_html_block5_section'           => array(
			'name'            => 'single_product_html_block5_section',
			'type'            => 'select',
			'settings'        => 'single_product_html_block5_section',
			'label'           => sprintf( esc_html__( 'Choose %1s for Html block 5', 'xstore-core' ), '<a href="' . etheme_documentation_url('47-static-blocks', false) . '" target="_blank" style="color: #555">' . esc_html__( 'static block', 'xstore-core' ) . '</a>' ),
			'section'         => 'single_product_html_blocks',
			'default'         => '',
			'choices'         => $sections,
			'active_callback' => array(
				array(
					'setting'  => 'single_product_html_block5_sections',
					'operator' => '==',
					'value'    => 1,
				),
			),
			'transport'       => 'postMessage',
			'partial_refresh' => array(
				'single_product_html_block5_section' => array(
					'selector'        => '.single_product-html_block5',
					'render_callback' => function () {
						return html_blocks_callback( array(
							'section'         => 'single_product_html_block5_section',
							'sections'        => 'single_product_html_block5_sections',
							'html_backup'     => 'single_product_html_block5',
							'section_content' => true
						) );
					},
				),
			),
			'priority'        => 20,
		),
	
	);
	
	unset($sections);
	
	return array_merge( $fields, $args );
	
} );
