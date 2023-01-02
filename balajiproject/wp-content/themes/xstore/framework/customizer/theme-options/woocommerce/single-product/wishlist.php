<?php
/**
 * The template created for displaying single product wishlist options
 *
 * @version 0.0.1
 * @since   8.3.8
 */

add_filter( 'et/customizer/add/sections', function ( $sections ) {
	
	$args = array(
		'single-product-page-wishlist' => array(
			'name'       => 'single-product-page-wishlist',
			'title'      => esc_html__( 'Wishlist', 'xstore' ),
			'panel'      => 'single-product-page',
			'icon'       => 'dashicons-heart',
			'type'       => 'kirki-lazy',
			'dependency' => array()
		)
	);
	
	return array_merge( $sections, $args );
} );

$hook = class_exists( 'ETC_Initial' ) ? 'et/customizer/add/fields/single-product-page-wishlist' : 'et/customizer/add/fields';
add_filter( $hook, function ( $fields ) {
	$args = array();
	
	// Array of fields
	$args = array(

        'xstore_wishlist_single_product_position' => array(
            'name'            => 'xstore_wishlist_single_product_position',
            'type'            => 'select',
            'settings'        => 'xstore_wishlist_single_product_position',
            'label'           => esc_html__( 'Position', 'xstore' ),
            'section'  => 'single-product-page-wishlist',
            'default'         => 'after_cart_form',
            'choices'         => array(
                'none'      => esc_html__( 'Nowhere', 'xstore' ),
                'on_image'      => esc_html__( 'On product image', 'xstore' ),
                'after_atc'     => esc_html__( 'After add to cart', 'xstore' ),
                'before_cart_form' => esc_html__( 'Before cart form', 'xstore' ),
                'after_cart_form' => esc_html__( 'After cart form', 'xstore' ),
            ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // product_wishlist_label_add_to_wishlist
        'product_wishlist_label_add_to_wishlist'              => array(
            'name'     => 'product_wishlist_label_add_to_wishlist',
            'type'     => 'etheme-text',
            'settings' => 'product_wishlist_label_add_to_wishlist',
            'label'    => esc_html__( 'Add to wishlist text', 'xstore' ),
            'section'  => 'single-product-page-wishlist',
            'default'  => esc_html__( 'Add to wishlist', 'xstore' ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // product_wishlist_label_browse_wishlist
        'product_wishlist_label_browse_wishlist'              => array(
            'name'     => 'product_wishlist_label_browse_wishlist',
            'type'     => 'etheme-text',
            'settings' => 'product_wishlist_label_browse_wishlist',
            'label'    => esc_html__( 'Browse wishlist text', 'xstore' ),
            'section'  => 'single-product-page-wishlist',
            'default'  => esc_html__( 'Browse wishlist', 'xstore' ),
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'product_wishlist_tooltip' => array(
            'name'     => 'product_wishlist_tooltip',
            'type'     => 'toggle',
            'settings' => 'product_wishlist_tooltip',
            'label'    => __( 'Add tooltip', 'xstore' ),
            'section'  => 'single-product-page-wishlist',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'product_wishlist_only_icon' => array(
            'name'     => 'product_wishlist_only_icon',
            'type'     => 'toggle',
            'settings' => 'product_wishlist_only_icon',
            'label'    => __( 'Only icon', 'xstore' ),
            'section'  => 'single-product-page-wishlist',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        'product_wishlist_redirect_on_remove' => array(
            'name'     => 'product_wishlist_redirect_on_remove',
            'type'     => 'toggle',
            'settings' => 'product_wishlist_redirect_on_remove',
            'label'    => __( 'Redirect to wishlist on remove', 'xstore' ),
            'section'  => 'single-product-page-wishlist',
            'default'  => false,
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),

        // go to product single product wishlist
        'go_to_section_xstore_wishlist'                 => array(
            'name'     => 'go_to_section_xstore_wishlist',
            'type'     => 'custom',
            'settings' => 'go_to_section_xstore_wishlist',
            'section'  => 'single-product-page-wishlist',
            'default'  => '<span class="et_edit" data-parent="xstore-wishlist" data-section="xstore_wishlist" style="padding: 5px 7px; border-radius: 5px; background: #222; color: #fff;">' . esc_html__( 'Global wishlist settings', 'xstore' ) . '</span>',
            'active_callback' => array(
                array(
                    'setting'  => 'xstore_wishlist',
                    'operator' => '==',
                    'value'    => true,
                ),
            )
        ),
	
	);
	
	return array_merge( $fields, $args );
	
} );