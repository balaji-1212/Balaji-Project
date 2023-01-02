<?php
/**
 * The template created for displaying header overlap options
 *
 * @version 1.0.1
 * @since   1.4.1
 * last changes in 1.5.4
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'header_overlap' => array(
			'name'        => 'header_overlap',
			'title'       => esc_html__( 'Header overlap & transparent', 'xstore-core' ),
			'description' => _( 'If you want to use header with the overlap & transparent effect for certain pages use <a href="#" class="et_open-multiple">Multiple headers</a> option' ),
			'panel'       => 'header-builder',
			'icon'        => 'dashicons-archive',
			'type'        => 'kirki-lazy',
			'dependency'  => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


add_filter( 'et/customizer/add/fields/header_overlap', function ( $fields ) use ( $separators, $strings ) {
	$args = array();
	
	// Array of fields
	$args = array(
		// content separator
		'header_overlap_content_separator'      => array(
			'name'     => 'header_overlap_content_separator',
			'type'     => 'custom',
			'settings' => 'header_overlap_content_separator',
			'section'  => 'header_overlap',
			'default'  => $separators['content'],
			'priority' => 10,
		),
		
		// header_overlap
		'header_overlap_et-desktop'             => array(
			'name'        => 'header_overlap_et-desktop',
			'type'        => 'toggle',
			'settings'    => 'header_overlap_et-desktop',
			'label'       => esc_html__( 'Header overlap', 'xstore-core' ),
			'description' => esc_html__( 'Use conditions to make this options work only on specific pages you chose', 'xstore-core' ),
			'section'     => 'header_overlap',
			'default'     => '0',
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => 'body',
					'function' => 'toggleClass',
					'class'    => 'et_b_dt_header-overlap',
					'value'    => true
				),
				array(
					'element'  => 'body',
					'function' => 'toggleClass',
					'class'    => 'et_b_dt_header-not-overlap',
					'value'    => false
				),
			),
		),
		
		// header_overlap
		'header_overlap_et-mobile'              => array(
			'name'        => 'header_overlap_et-mobile',
			'type'        => 'toggle',
			'settings'    => 'header_overlap_et-mobile',
			'label'       => esc_html__( 'Header overlap', 'xstore-core' ),
			'description' => esc_html__( 'Use conditions to make this options work only on specific pages you chose', 'xstore-core' ),
			'section'     => 'header_overlap',
			'default'     => '0',
			'transport'   => 'postMessage',
			'js_vars'     => array(
				array(
					'element'  => 'body',
					'function' => 'toggleClass',
					'class'    => 'et_b_mob_header-overlap',
					'value'    => true
				),
				array(
					'element'  => 'body',
					'function' => 'toggleClass',
					'class'    => 'et_b_mob_header-not-overlap',
					'value'    => false
				),
			),
		),
		
		// breadcrumb_padding
		'overlap_breadcrumb_padding_et-desktop' => array(
			'name'        => 'overlap_breadcrumb_padding_et-desktop',
			'type'        => 'dimensions',
			'settings'    => 'overlap_breadcrumb_padding_et-desktop',
			'label'       => esc_html__( 'Custom Breadcrumbs paddings (overlap only)', 'xstore-core' ),
			'description' => sprintf( esc_html__( 'Controls the paddings for the breadcrumbs area. Leave empty to use default values. You also may set up your breadcrumbs settings in %1s', 'xstore-core' ), '<span class="et_edit" data-parent="breadcrumbs" data-section="breadcrumb_padding" style="color: #222;">' . esc_html__( 'Breadcrumbs settings', 'xstore-core' ) . '</span>' ),
			'section'     => 'header_overlap',
			'default'     => array(
				'padding-top'    => '13em',
				'padding-right'  => '',
				'padding-bottom' => '5em',
				'padding-left'   => '',
			),
			'choices'     => array(
				'labels' => $strings['label']['paddings'],
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'      => 'padding-top',
					'context'     => array( 'editor', 'front' ),
					'element'     => '
					.et_b_dt_header-overlap .page-heading',
					'property'    => 'padding-top',
					'media_query' => '@media only screen and (min-width: 993px)',
				),
				array(
					'choice'      => 'padding-bottom',
					'context'     => array( 'editor', 'front' ),
					'element'     => '
					.et_b_dt_header-overlap .page-heading',
					'property'    => 'padding-bottom',
					'media_query' => '@media only screen and (min-width: 993px)',
				),
				array(
					'choice'      => 'padding-left',
					'context'     => array( 'editor', 'front' ),
					'element'     => '
					.et_b_dt_header-overlap .page-heading',
					'property'    => 'padding-left',
					'media_query' => '@media only screen and (min-width: 993px)',
				),
				array(
					'choice'      => 'padding-right',
					'context'     => array( 'editor', 'front' ),
					'element'     => '
					.et_b_dt_header-overlap .page-heading',
					'property'    => 'padding-right',
					'media_query' => '@media only screen and (min-width: 993px)',
				),
			),
		),
		
		// breadcrumb_padding
		'overlap_breadcrumb_padding_et-mobile'  => array(
			'name'        => 'overlap_breadcrumb_padding_et-mobile',
			'type'        => 'dimensions',
			'settings'    => 'overlap_breadcrumb_padding_et-mobile',
			'label'       => esc_html__( 'Custom Breadcrumbs paddings (overlap only)', 'xstore-core' ),
			'description' => sprintf( esc_html__( 'Controls the paddings for the breadcrumbs area. Leave empty to use default values. You also may set up your breadcrumbs settings in %1s', 'xstore-core' ), '<span class="et_edit" data-parent="breadcrumbs" data-section="breadcrumb_padding" style="color: #222;">' . esc_html__( 'Breadcrumbs settings', 'xstore-core' ) . '</span>' ),
			'section'     => 'header_overlap',
			'default'     => array(
				'padding-top'    => '11em',
				'padding-right'  => '',
				'padding-bottom' => '1.2em',
				'padding-left'   => '',
			),
			'choices'     => array(
				'labels' => $strings['label']['paddings'],
			),
			'transport'   => 'auto',
			'output'      => array(
				array(
					'choice'      => 'padding-top',
					'context'     => array( 'editor', 'front' ),
					'element'     => '
					.et_b_mob_header-overlap .page-heading',
					'property'    => 'padding-top',
					'media_query' => '@media only screen and (max-width: 992px)',
				),
				array(
					'choice'      => 'padding-bottom',
					'context'     => array( 'editor', 'front' ),
					'element'     => '
					.et_b_mob_header-overlap .page-heading',
					'property'    => 'padding-bottom',
					'media_query' => '@media only screen and (max-width: 992px)',
				),
				array(
					'choice'      => 'padding-left',
					'context'     => array( 'editor', 'front' ),
					'element'     => '
					.et_b_mob_header-overlap .page-heading',
					'property'    => 'padding-left',
					'media_query' => '@media only screen and (max-width: 992px)',
				),
				array(
					'choice'      => 'padding-right',
					'context'     => array( 'editor', 'front' ),
					'element'     => '
					.et_b_mob_header-overlap .page-heading',
					'property'    => 'padding-right',
					'media_query' => '@media only screen and (max-width: 992px)',
				),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
