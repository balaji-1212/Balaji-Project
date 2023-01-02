<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Brands List shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
class Brands_List extends VC {

	function hooks(){

        if( !function_exists('etheme_get_option') || !etheme_get_option( 'enable_brands', 1 ) )
        return;

        $this->register_etheme_brands_list();
	}

    function register_etheme_brands_list() {

        add_filter( 'vc_autocomplete_etheme_brands_list_ids_callback', array( $this, 'etheme_productBrandBrandAutocompleteSuggester' ), 10, 1 ); // Get suggestion(find). Must return an array
        add_filter( 'vc_autocomplete_etheme_brands_list_ids_render', array( $this, 'etheme_productBrandBrandRenderByIdExact' ), 10, 1 ); // Render exact category by id. Must return an array (label,value)

        $strings = $this->etheme_vc_shortcodes_strings();
        $counter = 0;
        $params = array(
            'name' => 'Brands List',
            'base' => 'etheme_brands_list',
            'icon' => ETHEME_CODE_IMAGES . 'vc/Brands.png',
            'description' => esc_html__('Display list of the product brands with filter (Requires Masonry option)', 'xstore-core'),
            'category' => $strings['category'],
            'params' => array_merge(array(
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Data settings', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
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
            ), $this->get_brands_list_params()
            )
        );

        vc_map($params);
    }
}
