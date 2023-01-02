<?php
/**
 * The template created for saving in customizer header builder elements (hidden in customizer)
 *
 * @version 1.0.0
 * @since   1.4.0
 */
add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'single_product_builder_elements' => array(
			'name'  => 'single_product_builder_elements',
			'title' => false,
			'panel' => 'single_product_builder',
			'icon'  => false,
		)
	);
	
	return array_merge( $sections, $args );
} );

add_filter( 'et/customizer/add/fields', function ( $fields ) use ( $strings ) {
	$args = array();
	
	// Array of fields
	$args = array(
		'product_single_elements' => array(
			'name'     => 'product_single_elements',
			'type'     => 'text',
			'settings' => 'product_single_elements',
			'label'    => false, // to prevent searching
			'section'  => 'single_product_builder_elements',
			'default'  => '{"element-oCMF7":{"title":"Section1","width":"100","index":1,"align":"start","sticky":"false","data":{"element-lpYyv":{"element":"etheme_woocommerce_template_woocommerce_breadcrumb","index":0}}},"element-raHwF":{"title":"Section2","width":"30","index":2,"align":"start","sticky":"false","data":{"sA6vX":{"element":"etheme_woocommerce_show_product_images","index":0}}},"element-TFML4":{"title":"Section3","width":"35","index":3,"align":"start","sticky":"false","data":{"su2ri":{"element":"etheme_woocommerce_template_single_title","index":0},"pcrn2":{"element":"etheme_woocommerce_template_single_price","index":1},"ZhZAb":{"element":"etheme_woocommerce_template_single_rating","index":2},"DBsjn":{"element":"etheme_woocommerce_template_single_excerpt","index":3},"oXjuP":{"element":"etheme_woocommerce_template_single_add_to_cart","index":4},"element-Zwwrj":{"element":"etheme_product_single_wishlist","index":5},"4XneW":{"element":"etheme_woocommerce_template_single_meta","index":6},"WP7Ne":{"element":"etheme_woocommerce_template_single_sharing","index":7}}},"element-fgcNP":{"title":"Section4","width":"25","index":4,"align":"start","sticky":"element-TFML4","data":{"HK48p":{"element":"etheme_product_single_widget_area_01","index":0}}},"element-nnrkj":{"title":"Section5","width":"100","index":5,"align":"start","sticky":"false","data":{"BJZsk":{"element":"etheme_woocommerce_output_product_data_tabs","index":0}}},"element-aKxrL":{"title":"Section6","width":"100","index":6,"align":"start","sticky":"false","data":{"qyJz2":{"element":"etheme_woocommerce_output_related_products","index":0}}},"element-a8Rd9":{"title":"Section7","width":"100","index":7,"align":"start","sticky":"false","data":{"sbu5J":{"element":"etheme_woocommerce_output_upsell_products","index":0}}}}',
			// 'transport' => 'postMessage',
			// 'partial_refresh' => array(
			// 	'product_single_elements' => array(
			// 		'selector'  => '.single-product-builder > .row > div > .row',
			// 		'render_callback' => 'single_product_bulder_content_callback'
			// 	),
			// ),
		),
		
		'product_archive_elements' => array(
			'name'     => 'product_archive_elements',
			'type'     => 'text',
			'settings' => 'product_archive_elements',
			'label'    => false, // to prevent searching
			'section'  => 'single_product_builder_elements',
			'default'  => ''
		),
		
		'connect_block_product_single_package' => array(
			'name'         => 'connect_block_product_single_package',
			'type'         => 'repeater',
			'label'        => false, // to prevent searching
			'section'      => 'single_product_builder_elements',
			'priority'     => 10,
			'row_label'    => array(
				'type'  => 'field',
				'value' => esc_html__( 'block ', 'xstore-core' ),
				'field' => 'connect_block_product_single_package',
			),
			'button_label' => esc_html__( 'Add new block', 'xstore-core' ),
			'settings'     => 'connect_block_product_single_package',
			'fields'       => array(
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
			// 'transport' => 'postMessage',
			// 'partial_refresh' => array(
			// 	'connect_block_product_single_package' => array(
			// 		'selector'  => '.single-product-builder > .row > div > .row',
			// 		'render_callback' => 'single_product_bulder_content_callback'
			// 	),
			// ),
		),
		
		'connect_block_product_archive_package' => array(
			'name'         => 'connect_block_product_archive_package',
			'type'         => 'repeater',
			// 'label'       => esc_html__( 'connect block product archive package', 'xstore-core' ),
			'label'        => false, // to prevent searching
			'section'      => 'single_product_builder_elements',
			'priority'     => 10,
			'row_label'    => array(
				'type'  => 'field',
				'value' => esc_html__( 'block ', 'xstore-core' ),
				'field' => 'connect_block_product_archive_package',
			),
			'button_label' => esc_html__( 'Add new block', 'xstore-core' ),
			'settings'     => 'connect_block_product_archive_package',
			'fields'       => array(
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
			)
		),
	
	
	);
	
	return array_merge( $fields, $args );
	
} );
