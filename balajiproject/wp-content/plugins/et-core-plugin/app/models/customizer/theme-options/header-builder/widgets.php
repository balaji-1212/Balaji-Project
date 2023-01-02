<?php
/**
 * The template created for displaying widgets options
 *
 * @version 1.0.2
 * @since   1.4.0
 * last changes in 2.2.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'header_widgets' => array(
			'name'       => 'header_widgets',
			'title'      => esc_html__( 'Widgets', 'xstore-core' ),
			'panel'      => 'header-builder',
			'icon'       => 'dashicons-align-left',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields/header_widgets', function ( $fields ) use ( $sep_style ) {
	$sidebars = etheme_get_sidebars();
	
	$args = array();
	
	// Array of fields
	$args = array(
		
		// content separator
		'header_widget1_content_separator' => array(
			'name'     => 'header_widget1_content_separator',
			'type'     => 'custom',
			'settings' => 'header_widget1_content_separator',
			'section'  => 'header_widgets',
			'priority' => 1,
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-align-left"></span> <span style="padding-left: 3px;">' . esc_html__( 'Widget 1', 'xstore-core' ) . '</span></div>',
		),
		
		// header_widget1
		'header_widget1'                   => array(
			'name'            => 'header_widget1',
			'type'            => 'select',
			'settings'        => 'header_widget1',
			'label'           => esc_html__( 'Select widget area', 'xstore-core' ),
			'section'         => 'header_widgets',
			'default'         => '',
			'multiple'        => 1,
			'choices'         => $sidebars,
			'transport'       => 'postMessage',
			'priority'        => 2,
			'partial_refresh' => array(
				'header_widget1' => array(
					'selector'        => '.header-widget1',
					'render_callback' => function () {
						$header_widget1 = get_theme_mod( 'header_widget1', '' );
						ob_start();
						if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $header_widget1 ) ):
						endif;
						
						return ob_get_clean();
					}
				),
			),
		),
		
		'go_to_section_panel-widgets1'     => array(
			'name'     => 'go_to_section_panel-widgets1',
			'type'     => 'custom',
			'settings' => 'go_to_section_panel-widgets1',
			'section'  => 'header_widgets',
			'priority' => 3,
			'default'  => '<span class="et_edit" data-parent="panel-widgets" data-section="sidebar-widgets-prefooter" style="padding: 5px 7px; border-radius: 5px; background: #222; color: #fff;">' . esc_html__( 'Widget areas', 'xstore-core' ) . '</span>',
		),
		
		// content separator
		'header_widget2_content_separator' => array(
			'name'     => 'header_widget2_content_separator',
			'type'     => 'custom',
			'settings' => 'header_widget2_content_separator',
			'section'  => 'header_widgets',
			'priority' => 4,
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-align-left"></span> <span style="padding-left: 3px;">' . esc_html__( 'Widget 2', 'xstore-core' ) . '</span></div>',
		),
		
		// header_widget2
		'header_widget2'                   => array(
			'name'            => 'header_widget2',
			'type'            => 'select',
			'settings'        => 'header_widget2',
			'label'           => esc_html__( 'Select widget area', 'xstore-core' ),
			'section'         => 'header_widgets',
			'default'         => '',
			'multiple'        => 1,
			'choices'         => $sidebars,
			'transport'       => 'postMessage',
			'priority'        => 5,
			'partial_refresh' => array(
				'header_widget2' => array(
					'selector'        => '.header-widget2',
					'render_callback' => function () {
						$header_widget2 = get_theme_mod( 'header_widget2', '' );
						ob_start();
						if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $header_widget2 ) ):
						endif;
						
						return ob_get_clean();
					}
				),
			),
		),
		
		'go_to_section_panel-widgets2'    => array(
			'name'     => 'go_to_section_panel-widgets2',
			'type'     => 'custom',
			'settings' => 'go_to_section_panel-widgets2',
			'section'  => 'header_widgets',
			'priority' => 6,
			'default'  => '<span class="et_edit" data-parent="panel-widgets" data-section="sidebar-widgets-prefooter" style="padding: 5px 7px; border-radius: 5px; background: #222; color: #fff;">' . esc_html__( 'Widget areas', 'xstore-core' ) . '</span>',
		),
		
		// content separator
		'header_banner_content_separator' => array(
			'name'     => 'header_banner_content_separator',
			'type'     => 'custom',
			'settings' => 'header_banner_content_separator',
			'section'  => 'header_widgets',
			'priority' => 7,
			'default'  => '<div style="' . $sep_style . '"><span class="dashicons dashicons-align-left"></span> <span style="padding-left: 3px;">' . esc_html__( 'Header banner', 'xstore-core' ) . '</span></div>',
		),
		
		// header_banner_position
		'header_banner_position'          => array(
			'name'        => 'header_banner_position',
			'type'        => 'select',
			'settings'    => 'header_banner_position',
			'label'       => esc_html__( 'Position of header banner widget area', 'xstore-core' ),
			'description' => esc_html__( 'Choose the position of the widget area. Works out of customizer preview only!', 'xstore-core' ),
			'section'     => 'header_widgets',
			'default'     => 'disable',
			'multiple'    => 1,
			'priority'    => 8,
			'choices'     => array(
				'top'     => esc_html__( 'Above header', 'xstore-core' ),
				'bottom'  => esc_html__( 'Under header', 'xstore-core' ),
				'disable' => esc_html__( 'Disable', 'xstore-core' ),
			),
		),
	
	);
	
	return array_merge( $fields, $args );
	
} );
