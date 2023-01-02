<?php
/**
 * The template created for displaying global sections options
 *
 * @version 0.0.1
 * @since   4.4
 */

// section general
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'site_sections' => array(
			'name'        => 'site_sections',
			'title'       => esc_html__( 'Global Sections', 'xstore-core' ),
			'description' => sprintf( __( 'Assign Global sections throughout your website. Check the page structure <a href="%s" rel="nofollow" target="_blank">here</a>. You can create as many as you want using our <a href="%s" target="_blank">Static Blocks</a>.', 'xstore-core' ), etheme_documentation_url('147-page-structure', false), admin_url( 'edit.php?post_type=staticblocks' ) ) . ' ' .
			                 sprintf( __( 'Remind how to use static blocks watching the <a href="%s" rel="nofollow" target="_blank">Video tutorial</a>.', 'xstore-core' ), 'https://www.youtube.com/watch?v=gY-x4m47Duo' ),
			'icon'        => 'dashicons-editor-insertmore',
			'priority'    => 2,
			'type'        => 'kirki-lazy',
			'dependency'  => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/site_sections', function ( $fields ) use ( $separators, $strings, $choices, $icons ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'site_sections_separator' => array(
			'name'     => 'site_sections_separator',
			'type'     => 'custom',
			'settings' => 'site_sections_separator',
			'section'  => 'site_sections',
			'default'  => $separators['content'],
			'priority' => 1,
		),
	);
	
	return array_merge( $fields, $args );
} );

add_filter( 'et/customizer/add/fields', function ( $fields ) use ( $separators, $is_customize_preview, $strings, $choices, $icons, $mobile_panel_elements ) {
	$args = array();
	
	$positions = array(
		''                        => esc_html__( '- Select -', 'xstore-core' ),
		'before_site_wrapper'     => esc_html__( 'Before Site Wrapper', 'xstore-core' ),
		'before_header'           => esc_html__( 'Before Header', 'xstore-core' ),
		'after_header'            => esc_html__( 'After Header', 'xstore-core' ),
		'before_template_content' => esc_html__( 'Before Template Content', 'xstore-core' ),
		'after_template_content'  => esc_html__( 'After Template Content', 'xstore-core' ),
		'before_prefooter'        => esc_html__( 'Before Prefooter', 'xstore-core' ),
		'after_prefooter'         => esc_html__( 'After Prefooter', 'xstore-core' ),
		'before_footer'           => esc_html__( 'Before Footer', 'xstore-core' ),
		'after_footer'            => esc_html__( 'After Footer', 'xstore-core' ),
		'after_site_wrapper'      => esc_html__( 'After Site Wrapper', 'xstore-core' ),
	);
	
	$sections = $is_customize_preview ? et_b_get_posts(
		array(
			'post_per_page' => -1,
			'nopaging'      => true,
			'post_type'     => 'staticblocks',
			'with_none' => true
		)
	) : array();
	
	// Array of fields
	$args = array(
		
		'site_sections' => array(
			'name'         => 'site_sections',
			'type'         => 'repeater',
			'settings'     => 'site_sections',
			'label'        => esc_html__( 'Sections', 'xstore-core' ),
			'section'      => 'site_sections',
			'priority'     => 9,
			'dynamic'      => false,
			'row_label'    => array(
				'type'  => 'field',
				'value' => esc_html__( 'Item', 'xstore-core' ),
				'field' => 'position',
			),
			'button_label' => esc_html__( 'Add new item', 'xstore-core' ),
			'default'      => array(
				array(
					'position'    => 'before_site_wrapper',
					'staticblock' => '',
				)
			),
			'fields'       => array(
				'position'    => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Position', 'xstore-core' ),
					'choices' => $positions
				),
				'staticblock' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Generic Sections', 'xstore-core' ),
					'choices' => $sections,
				),
			),
			'transport'    => 'postMessage',
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
