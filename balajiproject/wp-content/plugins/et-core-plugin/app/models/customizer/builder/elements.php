<?php if ( ! defined('ABSPATH')) exit('No direct script access allowed');
/**
 * Init elements for builder
 *
 * @since   1.0.0
 * @version 1.0.0
 */

return array(
    'connect_block' => array(
        'title'   => 'Connection block',
        'parent'  => 'header_builder_elements',
        'section' => 'connect_block_package',
        'element_info' => esc_html__('Connection block allows you to place elements one next to another in the horizontal or vertical position, align elements within connection block width and manage space between the elements. Use for example to place search, wishlist and cart without additional space that could appear if you place them in 3 separate columns.', 'xstore-core'),
        'icon'    => 'dashicons-share-alt',
        'class'   => 'et-stuck-block',
        'location' => array( 'header', 'product-single' )
    ),
    'logo' => array(
        'title'   => 'Logo',
        'parent'  => 'logo',
        'section' => 'logo_content_separator',
        'icon'    => 'dashicons-format-image',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'main_menu' => array(
        'title'   => 'Main Menu',
        'parent'  => 'main_menu',
        'section' => 'menu_content_separator',
        'section2' => 'menu_dropdown_style_separator',
        'icon'    => 'dashicons-menu',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'secondary_menu' => array(
        'title'   => 'Secondary menu',
        'parent'  => 'main_menu_2',
        'section' => 'menu_2_content_separator',
        'section2' => 'menu_dropdown_style_separator',
        'icon'    => 'dashicons-menu',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'all_departments' => array(
        'title'   => 'All Departments',
        'parent'  => 'secondary_menu',
        'section' => 'secondary_menu_content_separator',
        'section2' => 'menu_dropdown_style_separator',
        'icon'    => 'dashicons-menu',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'mobile_menu' => array(
        'title'   => 'Mobile Menu',
        'parent'  => 'mobile_menu',
        'section' => 'mobile_menu_content_separator',
        'icon'    => 'dashicons-menu',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'cart' => array(
        'title'   => 'Cart',
        'parent'  => ( class_exists('WooCommerce') ) ? 'cart' : 'cart_off',
        'section' => ( class_exists('WooCommerce') ) ? 'cart_content_separator' : 'cart_off_text',
        'icon'    => 'dashicons-cart',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'wishlist' => array(
        'title'   => 'Wishlist',
        'parent'  => 'wishlist',
        'section' => 'wishlist_content_separator',
        'icon'    => 'dashicons-heart',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'compare' => array(
	    'title'   => 'Compare',
        'parent'  => 'compare',
	    'section' => 'compare_content_separator',
	    'icon'    => 'dashicons-update-alt',
	    'class'   => '',
	    'location' => array( 'header' )
    ),
    'account' => array(
        'title'   => 'Account',
        'parent'  => 'account',
        'section' => 'account_content_separator',
        'icon'    => 'dashicons-admin-users',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'search' => array(
        'title'   => 'Search',
        'parent'  => 'search',
        'section' => 'search_content_separator',
        'icon'    => 'dashicons-search',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'header_socials' => array(
        'title'   => 'Socials',
        'parent'  => 'header_socials',
        'section' => 'header_socials_content_separator',
        'icon'    => 'dashicons-facebook',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'contacts' => array(
        'title'   => 'Contacts',
        'parent'  => 'contacts',
        'section' => 'contacts_content_separator',
        'icon'    => 'dashicons-phone',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'newsletter' => array(
        'title'   => 'Newsletter',
        'parent'  => 'newsletter',
        'section' => 'newsletter_content_separator',
        'icon'    => 'dashicons-email-alt',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'button' => array(
        'title'   => 'Button',
        'parent'  => 'button',
        'section' => 'button_content_separator',
        'icon'    => 'dashicons-editor-removeformatting',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'promo_text' => array(
        'title'   => 'Promo text',
        'parent'  => 'promo_text',
        'section' => 'promo_text_content_separator',
        'icon'    => 'dashicons-megaphone',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'html_block1' => array(
        'title'   => 'Html Block 1',
        'parent'  => 'html_blocks',
        'section' => 'html_block1',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'html_block2' => array(
        'title'   => 'Html Block 2',
        'parent'  => 'html_blocks',
        'section' => 'html_block2',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'html_block3' => array(
        'title'   => 'Html Block 3',
        'parent'  => 'html_blocks',
        'section' => 'html_block3',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'html_block4' => array(
	    'title'   => 'Html Block 4',
	    'parent'  => 'html_blocks',
	    'section' => 'html_block4',
	    'icon'    => 'dashicons-editor-code',
	    'class'   => '',
	    'location' => array( 'header' )
    ),
    'html_block5' => array(
	    'title'   => 'Html Block 5',
	    'parent'  => 'html_blocks',
	    'section' => 'html_block5',
	    'icon'    => 'dashicons-editor-code',
	    'class'   => '',
	    'location' => array( 'header' )
    ),
    'header_widget1' => array(
        'title'   => 'Widget 1',
        'parent'  => 'header_widgets',
        'section' => 'header_widget1',
        'icon'    => 'dashicons-category',
        'class'   => '',
        'location' => array( 'header' )
    ),
    'header_widget2' => array(
        'title'   => 'Widget 2',
        'parent'  => 'header_widgets',
        'section' => 'header_widget2',
        'icon'    => 'dashicons-category',
        'class'   => '',
        'location' => array( 'header' )
    ),











    'etheme_woocommerce_template_woocommerce_breadcrumb' => array(
        'title'   => 'Breadcrumbs',
        'parent'  => 'product_breadcrumbs',
        'section' => 'product_breadcrumbs_content_separator',
        'icon'    => 'dashicons-carrot',
        'class'   => '',
        'location' => array( 'product-single' )
    ),

    'etheme_woocommerce_show_product_images' => array(
        'title'   => 'Gallery',
        'parent'  => 'product_gallery',
        'section' => 'product_gallery_content_separator',
        'icon'    => 'dashicons-format-gallery',
        'class'   => '',
        'location' => array( 'product-single' )
    ),

    'etheme_woocommerce_template_single_title' => array(
        'title'   => 'Title',
        'parent'  => 'product_title',
        'section' => 'product_title_style_separator',
        'icon'    => 'dashicons-welcome-write-blog',
        'class'   => '',
        'location' => array( 'product-single' )
    ),

    'etheme_woocommerce_template_single_price' => array(
        'title'   => 'Price',
        'parent'  => 'product_price',
        'section' => 'product_price_style_separator',
        'icon'    => 'dashicons-tag',
        'class'   => '',
        'location' => array( 'product-single' )
    ),

    'etheme_woocommerce_template_single_rating' => array(
        'title'   => 'Product rating',
        'parent'  => 'product_rating',
        'section' => 'product_rating_content_separator',
        'icon'    => 'dashicons-star-filled', 
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_template_single_excerpt' => array(
        'title'   => 'Short description',
        'parent'  => 'product_short_description',
        'section' => 'product_short_description_style_separator',
        'icon'    => 'dashicons-clipboard',
        'class'   => '',
        'location' => array( 'product-single' )
    ),

    'etheme_product_single_size_guide' => array(
        'title'   => 'Sizing guide',
        'parent'  => 'product_size_guide',
        'section' => 'product_size_guide_content_separator',
        'icon'    => 'dashicons-image-crop',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_button' => array(
        'title'   => 'Button',
        'parent'  => 'single-button',
        'section' => 'single_product_button_content_separator',
        'icon'    => 'dashicons-editor-removeformatting',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_request_quote' => array(
	    'title'   => 'Request quote',
        'parent'  => 'single-request-quote',
	    'section' => 'single_product_request_quote_button_content_separator',
	    'icon'    => 'dashicons-editor-help',
	    'class'   => '',
	    'location' => array( 'product-single' )
    ),
    'etheme_product_bought_together' => array(
        'title'   => 'Bought together',
        'parent'  => 'single-bought-together',
        'section' => 'single_product_bought_together_content_separator',
        'icon'    => 'dashicons-products',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_template_single_add_to_cart' => array(
        'title'   => 'Add to cart',
        'parent'  => 'product_cart_form',
        'section' => 'product_cart_style_separator',
        'icon'    => 'dashicons-cart',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_wishlist' => array(
        'title'   => 'Wishlist',
        'parent'  => 'product_wishlist',
        'section' => 'product_wishlist_content_separator',
        'icon'    => 'dashicons-heart',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_compare' => array(
        'title'   => 'Compare',
        'parent'  => 'product_compare',
        'section' => 'product_compare_style_separator',
        'icon'    => 'dashicons-update-alt',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_template_single_meta' => array(
        'title'   => 'Product meta',
        'parent'  => 'product_meta',
        'section' => 'product_meta_content_separator',
        'icon'    => 'dashicons-format-aside', 
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_template_single_sharing' => array(
        'title'   => 'Sharing',
        'parent'  => 'product_sharing',
        'section' => 'product_sharing_content_separator',
        'icon'    => 'dashicons-share',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_output_product_data_tabs' => array(
        'title'   => 'Tabs',
        'parent'  => 'product_tabs',
        'section' => 'product_tabs_content_separator',
        'icon'    => 'dashicons-index-card',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_output_related_products' => array(
        'title'   => 'Related products',
        'parent'  => 'products_related',
        'section' => 'products_related_content_separator',
        'icon'    => 'dashicons-networking',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_output_upsell_products' => array(
        'title'   => 'Upsell products',
        'parent'  => 'products_upsell',
        'section' => 'products_upsell_content_separator',
        'icon'    => 'dashicons-products',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_woocommerce_output_cross_sells_products' => array(
	    'title'   => 'Cross-sells products',
        'parent'  => 'products_cross_sell',
	    'section' => 'products_cross_sell_content_separator',
	    'icon'    => 'dashicons-products',
	    'class'   => '',
	    'location' => array( 'product-single' )
    ),
    'etheme_product_single_widget_area_01' => array(
        'title'   => 'Sidebar',
        'parent'  => 'single_product_layout',
        'section' => 'single_product_layout_content_separator',
        'icon'    => 'dashicons-category',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_custom_html_01' => array(
        'title'   => 'Custom HTML 01',
        'parent'  => 'single_product_html_blocks',
        'section' => 'single_product_html_block1_content_separator',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_custom_html_02' => array(
        'title'   => 'Custom HTML 02',
        'parent'  => 'single_product_html_blocks',
        'section' => 'single_product_html_block1_content_separator',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_custom_html_03' => array(
        'title'   => 'Custom HTML 03',
        'parent'  => 'single_product_html_blocks',
        'section' => 'single_product_html_block1_content_separator',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_custom_html_04' => array(
	    'title'   => 'Custom HTML 04',
	    'parent'  => 'single_product_html_blocks',
	    'section' => 'single_product_html_block1_content_separator',
	    'icon'    => 'dashicons-editor-code',
	    'class'   => '',
	    'location' => array( 'product-single' )
    ),
    'etheme_product_single_custom_html_05' => array(
	    'title'   => 'Custom HTML 05',
	    'parent'  => 'single_product_html_blocks',
	    'section' => 'single_product_html_block1_content_separator',
	    'icon'    => 'dashicons-editor-code',
	    'class'   => '',
	    'location' => array( 'product-single' )
    ),
    'etheme_product_single_additional_custom_block' => array(
        'title'   => 'Additional Custom Block',
        'section' => '',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
    'etheme_product_single_product_description' => array(
        'title'   => 'Full Description',
        'section' => '',
        'icon'    => 'dashicons-editor-code',
        'class'   => '',
        'location' => array( 'product-single' )
    ),
);