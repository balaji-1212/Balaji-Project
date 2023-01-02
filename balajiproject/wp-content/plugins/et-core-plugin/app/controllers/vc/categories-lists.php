<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Categories lists shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Categories_lists extends VC {

	function hooks(){
		
		$this->register_etheme_categories_lists();

		if( class_exists('Vc_Vendor_Woocommerce')) {
			$Vc_Vendor_Woocommerce = new \Vc_Vendor_Woocommerce();
            add_filter( 'vc_autocomplete_etheme_categories_lists_ids_callback', array($Vc_Vendor_Woocommerce, 'productCategoryCategoryAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array
            add_filter( 'vc_autocomplete_etheme_categories_lists_ids_render', array($Vc_Vendor_Woocommerce, 'productCategoryCategoryRenderByIdExact',), 10, 1 ); // Render exact category by id. Must return an array (label,value)
            add_filter( 'vc_autocomplete_etheme_categories_lists_exclude_callback', array($Vc_Vendor_Woocommerce, 'productCategoryCategoryAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array
            add_filter( 'vc_autocomplete_etheme_categories_lists_exclude_render', array($Vc_Vendor_Woocommerce, 'productCategoryCategoryRenderByIdExact',), 10, 1 ); // Render exact category by id. Must return an array (label,value)
        }
    }

    function register_etheme_categories_lists() {

        $strings = $this->etheme_vc_shortcodes_strings();
    	$order_by_values = array(
    		'',
    		esc_html__( 'ID', 'xstore-core' ) => 'ID',
    		esc_html__( 'Title', 'xstore-core' ) => 'name',
    		esc_html__( 'Modified', 'xstore-core' ) => 'modified',
    		esc_html__( 'Products count', 'xstore-core' ) => 'count',
    		esc_html__( 'As IDs provided order', 'xstore-core' ) => 'include',
    	);

        $counter = 0;
    	$params = array(
    		'name' => 'Product Categories Lists',
    		'base' => 'etheme_categories_lists',
    		'icon' => ETHEME_CODE_IMAGES . 'vc/Products.png',
            'description' => esc_html__('Display product categories with subcategories', 'xstore-core'),
    		'category' => $strings['category'],
    		'params' => array_merge(array(
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Content', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
    			array(
    				'type' => 'textfield',
    				'heading' => esc_html__('Number of categories', 'xstore-core'),
    				'param_name' => 'number'
    			),
    			array(
    				'type' => 'autocomplete',
    				'heading' => esc_html__( 'Categories', 'xstore-core' ),
    				'param_name' => 'ids',
    				'settings' => array(
    					'multiple' => true,
    					'sortable' => true,
    				),
    				'save_always' => true,
    				'hint' => esc_html__( 'List of product categories', 'xstore-core' ),
    			),
    			array(
    				'type' => 'textfield',
    				'heading' => esc_html__('Subcategories limit', 'xstore-core'),
    				'param_name' => 'quantity'
    			),
    			array(
    				'type' => 'autocomplete',
    				'heading' => esc_html__( 'Exclude Categories', 'xstore-core' ),
    				'param_name' => 'exclude',
    				'settings' => array(
    					'multiple' => true,
    					'sortable' => true,
    				),
    				'save_always' => true,
    				'hint' => esc_html__( 'List of product categories to exclude', 'xstore-core' ),
    			),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Layout', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
    			array(
    				'type' => 'xstore_image_select',
    				'heading' => esc_html__('Display type', 'xstore-core'),
    				'param_name' => 'display_type',
    				'value' => array( 
    					esc_html__('Grid', 'xstore-core') => 'grid',
    					esc_html__('Slider', 'xstore-core') => 'slider',
    				),
                    'images_value' => array(
                        'grid'   => ET_CORE_SHORTCODES_IMAGES . 'product-categories-lists/Grid.svg',
                        'slider'   => ET_CORE_SHORTCODES_IMAGES . 'product-categories-lists/Slider.svg',
                    ),
                    'et_tooltip' => true,
    			),
                array(
                    'type' => 'xstore_button_set',
                    'heading' => esc_html__('Image position', 'xstore-core'),
                    'param_name' => 'img_position',
                    'value' => array( 
                        '<span class="dashicons dashicons-align-none" style="transform: rotate(90deg);"></span>' => 'top', 
                        '<span class="dashicons dashicons-align-none"></span>' => 'left', 
                        '<span class="dashicons dashicons-align-none" style="transform: rotate(-180deg);"></span>' => 'right'
                    ),
                    'tooltip_values' => array_merge($strings['tooltip']['align'], $strings['tooltip']['valign']),
                ),
                array(
                    'type' => 'xstore_slider',
                    'heading' => esc_html__( 'Columns', 'xstore-core' ),
                    'param_name' => 'columns',
                    'min' => 2,
                    'max' => 6,
                    'step' => 1,
                    'default' => 2,
                    'units' => '',
                    'dependency' => array('element' => 'display_type', 'value' => array('grid'))
                ),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Hover type', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
    			array(
    				'type' => 'xstore_button_set',
    				'param_name' => 'hover_type',
    				'value' => array( 
    					esc_html__('Default', 'xstore-core') => 'default',
    					esc_html__('Disable', 'xstore-core') => 'disable',
    				)
    			),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Advanced', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
                array(
                    'type' => 'textfield',
                    'heading' => $strings['heading']['el_class'],
                    'param_name' => 'class',
                    'description' => $strings['hint']['el_class']
                ),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Data settings', 'xstore-core' ),
                    'group' => esc_html__( 'Data settings', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
    			array(
    				'type' => 'dropdown',
    				'heading' => $strings['heading']['orderby'],
    				'param_name' => 'orderby',
                    'group' => esc_html__( 'Data settings', 'xstore-core' ),
    				'value' => $order_by_values,
    				'save_always' => true,
    				'hint' => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
                    'edit_field_class' => 'vc_col-md-6 vc_column',
    			),
    			array(
    				'type' => 'xstore_button_set',
    				'heading' => $strings['heading']['order'],
    				'param_name' => 'order',
                    'group' => esc_html__( 'Data settings', 'xstore-core' ),
    				'value' => $strings['value']['order'],
    				'save_always' => true,
    				'hint' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
                    'edit_field_class' => 'vc_col-md-6 vc_column',
    			),
    		), $this->get_slider_params())
    	);  

    	vc_map($params);
    }

}
