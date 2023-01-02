<?php
/**
 * The template created for displaying footer layout options
 *
 * @version 0.0.1
 * @since   6.0.0
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'footer-layout' => array(
			'name'        => 'footer-layout',
			'title'       => esc_html__( 'Footer layout', 'xstore' ),
			'description' => esc_html__( 'Remember that you can create footer using static blocks.', 'xstore' ) . ' <a href="https://www.youtube.com/watch?v=gY-x4m47Duo" rel="nofollow" target="_blank">' . esc_html__( 'Watch the tutorial', 'xstore' ) . '</a>.',
			'panel'       => 'footer',
			'icon'        => 'dashicons-schedule',
			'type'        => 'kirki-lazy',
			'dependency'  => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/footer-layout' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'footer_columns' => array(
			'name'        => 'footer_columns',
			'type'        => 'select',
			'settings'    => 'footer_columns',
			'label'       => esc_html__( 'Footer columns', 'xstore' ),
			'description' => esc_html__( 'Controls the number of columns in footer. You can add footer content at Appearance > Widgets. You can use static blocks widgets in footer to create custom layout.', 'xstore' ),
			'section'     => 'footer-layout',
			'default'     => 4,
			'choices'     => array(
				1 => esc_html__( '1 Column', 'xstore' ),
				2 => esc_html__( '2 Columns', 'xstore' ),
				3 => esc_html__( '3 Columns', 'xstore' ),
				4 => esc_html__( '4 Columns', 'xstore' ),
			),
		),
		
		'footer_demo' => array(
			'name'        => 'footer_demo',
			'type'        => 'toggle',
			'settings'    => 'footer_demo',
			'label'       => esc_html__( 'Show footer demo blocks', 'xstore' ),
			'description' => esc_html__( 'Turn off to hide default demo content of the footer.', 'xstore' ),
			'section'     => 'footer-layout',
			'default'     => 1,
		),
		
		'footer_fixed' => array(
			'name'        => 'footer_fixed',
			'type'        => 'toggle',
			'settings'    => 'footer_fixed',
			'label'       => esc_html__( 'Footer fixed', 'xstore' ),
			'description' => esc_html__( 'Turn on to get sliding effect for the footer (footer appears under the content during scroll).', 'xstore' ),
			'section'     => 'footer-layout',
			'default'     => 0,
		),
		
		'footer_widgets_open_close' => array(
			'name'        => 'footer_widgets_open_close',
			'type'        => 'toggle',
			'settings'    => 'footer_widgets_open_close',
			'label'       => esc_html__( 'Footer widgets toggle on mobile', 'xstore' ),
			'description' => esc_html__( 'Turn off to hide close widgets in the footer on mobile.', 'xstore' ),
			'section'     => 'footer-layout',
			'default'     => 1,
		),
		
		'footer_widgets_open_close_type' => array(
			'name'            => 'footer_widgets_open_close_type',
			'type'            => 'select',
			'settings'        => 'footer_widgets_open_close_type',
			'label'           => esc_html__( 'Footer widgets toggle action on mobile', 'xstore' ),
			'description'     => esc_html__( 'Type of widget content.', 'xstore' ),
			'section'         => 'footer-layout',
			'default'         => 'closed_mobile',
			'choices'         => array(
				'open_mobile'   => esc_html__( 'Open always', 'xstore' ),
				// 'closed' => esc_html__( 'Collapsed always', 'xstore' ),
				'closed_mobile' => esc_html__( 'Collapsed', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'footer_widgets_open_close',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
	);
	
	return array_merge( $fields, $args );
	
} );