<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Brands shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
class Brands extends VC {

    function hooks() {

        if( !function_exists('etheme_get_option') || !etheme_get_option( 'enable_brands', 1 ) )
        return;

        $this->register_brands_categories();
    }

    function register_brands_categories() {

        add_filter( 'vc_autocomplete_etheme_brands_ids_callback', array( $this, 'etheme_productBrandBrandAutocompleteSuggester' ), 10, 1 ); // Get suggestion(find). Must return an array
        add_filter( 'vc_autocomplete_etheme_brands_ids_render', array( $this, 'etheme_productBrandBrandRenderByIdExact' ), 10, 1 ); // Render exact category by id. Must return an array (label,value)
        
        $strings = $this->etheme_vc_shortcodes_strings();
        $order_by_values = array(
            '',
            esc_html__( 'As IDs provided order', 'xstore-core' ) => 'ids_order',
            esc_html__( 'ID', 'xstore-core' ) => 'ID',
            esc_html__( 'Title', 'xstore-core' ) => 'name',
            esc_html__( 'Quantity', 'xstore-core' ) => 'count',
        );

        $counter = 0;
        $params = array(
            'name' => 'Brands Carousel',
            'base' => 'etheme_brands',
            'icon' => ETHEME_CODE_IMAGES . 'vc/Brands.png',
            'description' => esc_html__('Display slider of the product brands', 'xstore-core'),
            'category' => $strings['category'],
            'params' => array_merge(array(
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Data settings', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
                array(
                  'type' => 'xstore_slider',
                  'heading' => esc_html__('Number of brands', 'xstore-core'),
                  'hint' => esc_html__('Set 0 to show all brands', 'xstore-core'),
                  'param_name' => 'number',
                  'min' => 0,
                  'max' => 50,
                  'step' => 1,
                  'default' => 12,
                  'units' => '',
                ),
                array(
                  'type' => 'dropdown',
                  'heading' => $strings['heading']['orderby'],
                  'param_name' => 'orderby',
                  'value' => $order_by_values,
                  'hint' => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
                  'edit_field_class' => 'vc_col-md-6 vc_column',
                ),
                array(
                  'type' => 'xstore_button_set',
                  'heading' => $strings['heading']['order'],
                  'param_name' => 'order',
                  'value' => $strings['value']['order'],
                  'hint' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
                  'edit_field_class' => 'vc_col-md-6 vc_column',
                ),
                array(
                  'type' => 'autocomplete',
                  'heading' => esc_html__( 'Brands', 'xstore-core' ),
                  'param_name' => 'ids',
                  'settings' => array(
                    'multiple' => true,
                    'sortable' => true,
                  ),
                  'save_always' => true,
                  'hint' => esc_html__( 'List of product brands', 'xstore-core' ),
                ),
	            array(
		            'type' => 'checkbox',
		            'heading' => esc_html__('Show only names', 'xstore-core'),
		            'param_name' => 'show_only_name',
	            ),
                array(
                    'type' => 'checkbox',
                    'heading' => esc_html__('Hide empty brands', 'xstore-core'),
                    'param_name' => 'hide_empty',
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
                )
            ), $this->get_slider_params())
        );

        vc_map($params);
    }

}
