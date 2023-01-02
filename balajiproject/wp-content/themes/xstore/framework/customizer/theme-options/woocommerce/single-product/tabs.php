<?php
/**
 * The template created for displaying tabs options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'tabs-settings' => array(
			'name'       => 'tabs-settings',
			'title'      => esc_html__( 'Tabs', 'xstore' ),
			'panel'      => 'single-product-page',
			'icon'       => 'dashicons-index-card',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );


$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/tabs-settings' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'tabs_type' => array(
			'name'        => 'tabs_type',
			'type'        => 'select',
			'settings'    => 'tabs_type',
			'label'       => esc_html__( 'Tabs type', 'xstore' ),
			'description' => esc_html__( 'Choose the design type of the tabs.', 'xstore' ),
			'section'     => 'tabs-settings',
			'default'     => 'tabs-default',
			'choices'     => array(
				'tabs-default' => esc_html__( 'Default', 'xstore' ),
				'left-bar'     => esc_html__( 'Left Bar', 'xstore' ),
				'accordion'    => esc_html__( 'Accordion', 'xstore' ),
				'disable'      => esc_html__( 'Disable', 'xstore' ),
			),
		),
		
		'first_tab_closed' => array(
			'name'            => 'first_tab_closed',
			'type'            => 'toggle',
			'settings'        => 'first_tab_closed',
			'label'           => esc_html__( 'Close first tab by default', 'xstore' ),
			'description'     => esc_html__( 'Turn on if you want to keep the first tab closed.', 'xstore' ),
			'section'         => 'tabs-settings',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'tabs_type',
					'operator' => '!=',
					'value'    => 'disable',
				),
			)
		),
		
		'tabs_scroll' => array(
			'name'            => 'tabs_scroll',
			'type'            => 'toggle',
			'settings'        => 'tabs_scroll',
			'label'           => esc_html__( 'Tabs content scroll', 'xstore' ),
			'description'     => esc_html__( 'Turn on to add height for the tab content and enable scroll if content is higher.', 'xstore' ),
			'section'         => 'tabs-settings',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'tabs_type',
					'operator' => '==',
					'value'    => 'accordion',
				),
			)
		),
		
		'tab_height' => array(
			'name'            => 'tab_height',
			'type'            => 'slider',
			'settings'        => 'tab_height',
			'label'           => esc_html__( 'Tab content height', 'xstore' ),
			'description'     => esc_html__( 'Add the max height of the tab content. In pixels.', 'xstore' ),
			'section'         => 'tabs-settings',
			'default'         => 250,
			'choices'         => array(
				'min'  => 50,
				'max'  => 800,
				'step' => 1,
			),
			'active_callback' => array(
				array(
					'setting'  => 'tabs_type',
					'operator' => '==',
					'value'    => 'accordion',
				),
				array(
					'setting'  => 'tabs_scroll',
					'operator' => '==',
					'value'    => true,
				),
			),
			'output'          => array(
				array(
					'etheme-context' => array( 'editor', 'front' ),
					'element'        => '.tabs-with-scroll.accordion .tab-content .tab-content-inner',
					'property'       => 'max-height',
					'units'          => 'px'
				)
			)
		),
		
		'tabs_location' => array(
			'name'            => 'tabs_location',
			'type'            => 'select',
			'settings'        => 'tabs_location',
			'label'           => esc_html__( 'Location of product tabs', 'xstore' ),
			'description'     => esc_html__( 'Controls the location of the product tabs.', 'xstore' ),
			'section'         => 'tabs-settings',
			'default'         => 'after_content',
			'choices'         => array(
				'after_image'   => esc_html__( 'Next to image', 'xstore' ),
				'after_content' => esc_html__( 'Under content', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'tabs_type',
					'operator' => '!=',
					'value'    => 'disable',
				),
			)
		),
		
		'reviews_position' => array(
			'name'            => 'reviews_position',
			'type'            => 'select',
			'settings'        => 'reviews_position',
			'label'           => esc_html__( 'Reviews position', 'xstore' ),
			'description'     => esc_html__( 'Controls the position of the reviews tab.', 'xstore' ),
			'section'         => 'tabs-settings',
			'default'         => 'tabs',
			'choices'         => array(
				'tabs'    => esc_html__( 'Tabs', 'xstore' ),
				'outside' => esc_html__( 'Next to tabs', 'xstore' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'tabs_type',
					'operator' => '!=',
					'value'    => 'disable',
				),
			)
		),
		
		'product_reviews_collapsed_et-mobile' => array(
			'name'            => 'product_reviews_collapsed_et-mobile',
			'type'            => 'toggle',
			'settings'        => 'product_reviews_collapsed_et-mobile',
			'label'           => esc_html__( 'Collapsed reviews on mobile', 'xstore' ),
			'description'     => esc_html__( 'Turn on to make reviews shown only on button click on mobile devices.', 'xstore' ),
			'section'         => 'tabs-settings',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'reviews_position',
					'operator' => '==',
					'value'    => 'outside',
				),
			)
		),
		
		'custom_tab_title' => array(
			'name'            => 'custom_tab_title',
			'type'            => 'etheme-text',
			'settings'        => 'custom_tab_title',
			'label'           => esc_html__( 'Custom Tab Title', 'xstore' ),
			'description'     => esc_html__( 'Add the title to use custom tab.', 'xstore' ),
			'section'         => 'tabs-settings',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'tabs_type',
					'operator' => '!=',
					'value'    => 'disable',
				),
			)
		),
		
		'custom_tab' => array(
			'name'            => 'custom_tab',
			'type'            => 'editor',
			'settings'        => 'custom_tab',
			'label'           => esc_html__( 'Custom tab content', 'xstore' ),
			'description'     => esc_html__( 'Add custom tab content. Use HTML, static block shortcode. Do not use JS, PHP code.', 'xstore' ),
			'section'         => 'tabs-settings',
			'default'         => '',
			'active_callback' => array(
				array(
					'setting'  => 'tabs_type',
					'operator' => '!=',
					'value'    => 'disable',
				),
			)
		),
	);
	
	return array_merge( $fields, $args );
	
} );