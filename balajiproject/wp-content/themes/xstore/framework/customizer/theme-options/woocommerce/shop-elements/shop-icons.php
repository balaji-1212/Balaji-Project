<?php
/**
 * The template created for displaying shop icons options
 *
 * @version 0.0.1
 * @since   6.0.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'shop-icons' => array(
			'name'       => 'shop-icons',
			'title'      => esc_html__( 'Product badges', 'xstore' ),
			'panel'      => 'shop-elements',
			'icon'       => 'dashicons-tag',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
	
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/shop-icons' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) use ( $light_sep_style ) {
	$args = array();
	
	// Array of fields
	$args = array(
		
		'separator_of_new_label' => array(
			'name'     => 'separator_of_new_label',
			'type'     => 'custom',
			'settings' => 'separator_of_new_label',
			'section'  => 'shop-icons',
			'default'  => '<div style="' . $light_sep_style . '">' . esc_html__( 'New label settings', 'xstore' ) . '</div>',
		),
		
		'product_new_label_range' => array(
			'name'        => 'product_new_label_range',
			'type'        => 'slider',
			'settings'    => 'product_new_label_range',
			'label'       => esc_html__( 'New label range', 'xstore' ),
			'description' => esc_html__( 'Controls the new label days limit on products. Set 0 to never add label.', 'xstore' ),
			'section'     => 'shop-icons',
			'default'     => 0,
			'choices'     => array(
				'min'  => 0,
				'max'  => 365,
				'step' => 1,
			),
		),
		
		'new_label_icon_color' => array(
			'name'            => 'new_label_icon_color',
			'type'            => 'color',
			'settings'        => 'new_label_icon_color',
			'label'           => esc_html__( 'New label color', 'xstore' ),
			'description'     => esc_html__( 'Choose the new label color.', 'xstore' ),
			'section'         => 'shop-icons',
			'default'         => '#ffffff',
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_new_label_range',
					'operator' => '!=',
					'value'    => 0,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_new-label-color',
				),
			),
		),
		
		'new_label_icon_bg_color' => array(
			'name'            => 'new_label_icon_bg_color',
			'type'            => 'color',
			'settings'        => 'new_label_icon_bg_color',
			'label'           => esc_html__( 'New label background color', 'xstore' ),
			'description'     => esc_html__( 'Choose the new label background color.', 'xstore' ),
			'section'         => 'shop-icons',
			'default'         => '#2e7d32',
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_new_label_range',
					'operator' => '!=',
					'value'    => 0,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_new-label-bg-color',
				),
			),
		),
		
		'separator_of_outofstock_label' => array(
			'name'     => 'separator_of_outofstock_label',
			'type'     => 'custom',
			'settings' => 'separator_of_outofstock_label',
			'section'  => 'shop-icons',
			'default'  => '<div style="' . $light_sep_style . '">' . esc_html__( 'Out of stock settings', 'xstore' ) . '</div>',
		),
		
		'out_of_icon' => array(
			'name'        => 'out_of_icon',
			'type'        => 'toggle',
			'settings'    => 'out_of_icon',
			'label'       => esc_html__( 'Enable "Out of stock" label', 'xstore' ),
			'description' => esc_html__( 'Turn on to show the "Out of stock" label.', 'xstore' ),
			'section'     => 'shop-icons',
			'default'     => 1,
		),
		
		'separator_of_featured_label' => array(
			'name'     => 'separator_of_featured_label',
			'type'     => 'custom',
			'settings' => 'separator_of_featured_label',
			'section'  => 'shop-icons',
			'default'  => '<div style="' . $light_sep_style . '">' . esc_html__( 'Hot label settings', 'xstore' ) . '</div>',
		),
		
		'featured_label' => array(
			'name'        => 'featured_label',
			'type'        => 'toggle',
			'settings'    => 'featured_label',
			'label'       => esc_html__( 'Enable "Hot" label', 'xstore' ),
			'description' => sprintf(__( 'Turn on to show the "Hot" label on featured products you may set from admin. <a href="%s" rel="nofollow" target="_blank">See details</a>', 'xstore' ), 'https://www.modernmarketingpartners.com/2015/01/19/set-featured-products-woocommerce/'),
			'section'     => 'shop-icons',
			'default'     => 0,
		),
		
		'featured_label_icon_color' => array(
			'name'            => 'featured_label_icon_color',
			'type'            => 'color',
			'settings'        => 'featured_label_icon_color',
			'label'           => esc_html__( 'Hot label color', 'xstore' ),
			'description'     => esc_html__( 'Choose the hot label color.', 'xstore' ),
			'section'         => 'shop-icons',
			'default'         => '#ffffff',
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'featured_label',
					'operator' => '==',
					'value'    => true,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_hot-label-color',
				),
			),
		),
		
		'featured_label_icon_bg_color' => array(
			'name'            => 'featured_label_icon_bg_color',
			'type'            => 'color',
			'settings'        => 'featured_label_icon_bg_color',
			'label'           => esc_html__( 'Hot label background color', 'xstore' ),
			'description'     => esc_html__( 'Choose the hot label background color.', 'xstore' ),
			'section'         => 'shop-icons',
			'default'         => '#f57f17',
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'featured_label',
					'operator' => '==',
					'value'    => true,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_hot-label-bg-color',
				),
			),
		),
		
		'separator_of_sale_label' => array(
			'name'     => 'separator_of_sale_label',
			'type'     => 'custom',
			'settings' => 'separator_of_sale_label',
			'section'  => 'shop-icons',
			'default'  => '<div style="' . $light_sep_style . '">' . esc_html__( 'Sale label settings', 'xstore' ) . '</div>',
		),
		
		'sale_icon' => array(
			'name'        => 'sale_icon',
			'type'        => 'toggle',
			'settings'    => 'sale_icon',
			'label'       => esc_html__( 'Enable "Sale" label', 'xstore' ),
			'description' => esc_html__( 'Turn on to show the "Sale" label.', 'xstore' ),
			'section'     => 'shop-icons',
			'default'     => 1,
		),
		
		'sale_icon_text' => array(
			'name'            => 'sale_icon_text',
			'type'            => 'etheme-text',
			'settings'        => 'sale_icon_text',
			'label'           => esc_html__( '"Sale" Label Text', 'xstore' ),
			'description'     => esc_html__( 'Use to change the sale text.', 'xstore' ),
			'section'         => 'shop-icons',
			'default'         => esc_html__( 'Sale', 'xstore' ),
			'active_callback' => array(
				array(
					'setting'  => 'sale_icon',
					'operator' => '==',
					'value'    => true,
				),
			)
		),
		
		'sale_icon_color' => array(
			'name'            => 'sale_icon_color',
			'type'            => 'color',
			'settings'        => 'sale_icon_color',
			'label'           => esc_html__( 'Sale label color', 'xstore' ),
			'description'     => esc_html__( 'Choose the sale label color.', 'xstore' ),
			'section'         => 'shop-icons',
			'default'         => '#ffffff',
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'sale_icon',
					'operator' => '==',
					'value'    => true,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_on-sale-color',
				),
			),
		),
		
		'sale_icon_bg_color' => array(
			'name'            => 'sale_icon_bg_color',
			'type'            => 'color',
			'settings'        => 'sale_icon_bg_color',
			'label'           => esc_html__( 'Sale label background color', 'xstore' ),
			'description'     => esc_html__( 'Choose the sale label background color.', 'xstore' ),
			'section'         => 'shop-icons',
			'default'         => '#c62828',
			'choices'         => array(
				'alpha' => true,
			),
			'active_callback' => array(
				array(
					'setting'  => 'sale_icon',
					'operator' => '==',
					'value'    => true,
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_on-sale-bg-color',
				),
			),
		),
		
		'sale_br_radius' => array(
			'name'            => 'sale_br_radius',
			'type'            => 'slider',
			'settings'        => 'sale_br_radius',
			'label'           => esc_html__( 'Sale & New label border radius (%)', 'xstore' ),
			'description'     => esc_html__( 'Controls the border radius of the sale label.', 'xstore' ),
			'section'         => 'shop-icons',
			'default'         => 0,
			'choices'         => array(
				'min'  => 0,
				'max'  => 50,
				'step' => 1,
			),
			'active_callback' => array(
				array(
					array(
						'setting'  => 'sale_icon',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'product_new_label_range',
						'operator' => '!=',
						'value'    => 0,
					)
				),
			),
			'transport'       => 'auto',
			'output'          => array(
				array(
					'context'  => array( 'editor', 'front' ),
					'element'  => 'body',
					'property' => '--et_on-sale-radius',
					'units'    => '%'
				),
			),
		),
		
		'sale_icon_size' => array(
			'name'            => 'sale_icon_size',
			'type'            => 'etheme-text',
			'settings'        => 'sale_icon_size',
			'label'           => esc_html__( 'Sale & New label size', 'xstore' ),
			'description'     => esc_html__( 'Controls the size of the sale label. In em, for example, 3.75x3.75.', 'xstore' ),
			'section'         => 'shop-icons',
			'default'         => '',
			'active_callback' => array(
				array(
					array(
						'setting'  => 'sale_icon',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => 'product_new_label_range',
						'operator' => '!=',
						'value'    => 0,
					)
				)
			),
		),
		
		'sale_percentage' => array(
			'name'            => 'sale_percentage',
			'type'            => 'toggle',
			'settings'        => 'sale_percentage',
			'label'           => esc_html__( 'Show sale percentage', 'xstore' ),
			'description'     => esc_html__( 'Turn on to calculate the percentage discount for the products.', 'xstore' ),
			'section'         => 'shop-icons',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'sale_icon',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
		
		'sale_percentage_variable' => array(
			'name'            => 'sale_percentage_variable',
			'type'            => 'toggle',
			'settings'        => 'sale_percentage_variable',
			'label'           => esc_html__( 'Show sale percentage for variable products', 'xstore' ),
			'description'     => esc_html__( 'Turn on to calculate the percentage discount for the variable products.', 'xstore' ),
			'section'         => 'shop-icons',
			'default'         => 0,
			'active_callback' => array(
				array(
					'setting'  => 'sale_icon',
					'operator' => '==',
					'value'    => true,
				),
				array(
					'setting'  => 'sale_percentage',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
	);
	
	return array_merge( $fields, $args );
	
} );