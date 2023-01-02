<?php
/**
 * The template created for displaying single product compare options
 *
 * @version 0.0.1
 * @since   8.3.9
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'single-product-page-compare' => array(
			'name'       => 'single-product-page-compare',
			'title'      => esc_html__( 'Compare', 'xstore' ),
			'panel'      => 'single-product-page',
			'icon'       => 'dashicons-update-alt',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/single-product-page-compare' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(

        'xstore_compare_single_product_position' => array(
            'name'            => 'xstore_compare_single_product_position',
            'type'            => 'select',
            'settings'        => 'xstore_compare_single_product_position',
            'label'           => esc_html__( 'Position', 'xstore' ),
            'section'  => 'single-product-page-compare',
            'default'         => 'after_cart_form',
            'choices'         => array(
                'none'      => esc_html__( 'Nowhere', 'xstore' ),
//                'on_image'      => esc_html__( 'On product image', 'xstore' ),
                'after_atc'     => esc_html__( 'After add to cart', 'xstore' ),
                'before_cart_form' => esc_html__( 'Before cart form', 'xstore' ),
                'after_cart_form' => esc_html__( 'After cart form', 'xstore' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // product_compare_label_add_to_compare
        'product_compare_label_add_to_compare'              => array(
            'name'     => 'product_compare_label_add_to_compare',
            'type'     => 'etheme-text',
            'settings' => 'product_compare_label_add_to_compare',
            'label'    => esc_html__( 'Add to compare text', 'xstore' ),
            'section'  => 'single-product-page-compare',
            'default'  => esc_html__( 'Add to compare', 'xstore' ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // product_compare_label_browse_compare
        'product_compare_label_browse_compare'              => array(
            'name'     => 'product_compare_label_browse_compare',
            'type'     => 'etheme-text',
            'settings' => 'product_compare_label_browse_compare',
            'label'    => esc_html__( 'Browse compare text', 'xstore' ),
            'section'  => 'single-product-page-compare',
            'default'  => esc_html__( 'Delete from compare', 'xstore' ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'product_compare_tooltip' => array(
            'name'     => 'product_compare_tooltip',
            'type'     => 'toggle',
            'settings' => 'product_compare_tooltip',
            'label'    => __( 'Add tooltip', 'xstore' ),
            'section'  => 'single-product-page-compare',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'product_compare_only_icon' => array(
            'name'     => 'product_compare_only_icon',
            'type'     => 'toggle',
            'settings' => 'product_compare_only_icon',
            'label'    => __( 'Only icon', 'xstore' ),
            'section'  => 'single-product-page-compare',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'product_compare_redirect_on_remove' => array(
            'name'     => 'product_compare_redirect_on_remove',
            'type'     => 'toggle',
            'settings' => 'product_compare_redirect_on_remove',
            'label'    => __( 'Redirect to compare on remove', 'xstore' ),
            'section'  => 'single-product-page-compare',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // go to product single product compare
        'go_to_section_xstore_compare'                 => array(
            'name'     => 'go_to_section_xstore_compare',
            'type'     => 'custom',
            'settings' => 'go_to_section_xstore_compare',
            'section'  => 'single-product-page-compare',
            'default'  => '<span class="et_edit" data-parent="xstore-compare" data-section="xstore_compare" style="padding: 5px 7px; border-radius: 5px; background: #222; color: #fff;">' . esc_html__( 'Global compare settings', 'xstore' ) . '</span>',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_compare',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),
	
	);
	
	return array_merge( $fields, $args );
	
} );