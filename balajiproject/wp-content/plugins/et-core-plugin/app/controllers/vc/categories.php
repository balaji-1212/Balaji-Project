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
class Categories extends VC {

	function hooks(){

		$this->register_etheme_categories();

		if( class_exists( 'Vc_Vendor_Woocommerce' ) ) {
			$Vc_Vendor_Woocommerce = new \Vc_Vendor_Woocommerce();
		    add_filter( 'vc_autocomplete_etheme_categories_ids_callback', array($Vc_Vendor_Woocommerce, 'productCategoryCategoryAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array
		    add_filter( 'vc_autocomplete_etheme_categories_ids_render', array($Vc_Vendor_Woocommerce, 'productCategoryCategoryRenderByIdExact',), 10, 1 ); // Render exact category by id. Must return an array(label,value)
		}
	}

	function register_etheme_categories() {
		
		$is_admin = $this->is_admin();
		
		$strings = $this->etheme_vc_shortcodes_strings();
		
		$sizes_select2 = array();
		
		if ( $is_admin && function_exists('etheme_get_image_sizes')) {
			$sizes = etheme_get_image_sizes();
			foreach ( $sizes as $size => $value ) {
				$sizes[ $size ] = $sizes[ $size ]['width'] . 'x' . $sizes[ $size ]['height'];
			}
			
			$sizes_select = array(
				'woocommerce_thumbnail'         => 'woocommerce_thumbnail',
				'woocommerce_gallery_thumbnail' => 'woocommerce_gallery_thumbnail',
				'woocommerce_single'            => 'woocommerce_single',
//				'shop_catalog'                  => 'shop_catalog',
//				'shop_thumbnail'                => 'shop_thumbnail',
//				'shop_single'                   => 'shop_single',
				'thumbnail'                     => 'thumbnail',
				'medium'                        => 'medium',
				'large'                         => 'large',
				'full'                          => 'full'
			);
			
			foreach ( $sizes_select as $item => $value ) {
				if ( isset( $sizes[ $item ] ) ) {
					$sizes_select2[ $item ] = $value . ' (' . $sizes[ $item ] . ')';
				} else {
					$sizes_select2[ $item ] = $value;
				}
			}
			
			$sizes_select2 = array_flip( $sizes_select2 );
			
			$sizes_select2 = array_merge(array(
				esc_html__('Select size', 'xstore-core') => ''
			), $sizes_select2);
			
		}
		
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
			'name' => 'Product Categories',
			'base' => 'etheme_categories',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Products.png',
			'description' => esc_html__('Display slider or grid of the product categories', 'xstore-core'),
			'category' => $strings['category'],
			'params' => array_merge(array(
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Content', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Title', 'xstore-core'),
					'param_name' => 'title'
				),
				array(
					'type' => 'sorted_list',
					'heading' => esc_html__( 'Text fields', 'xstore-core' ),
					'param_name' => 'sorting',
					'hint' => esc_html__( 'Sorting the texts layout', 'xstore-core' ),
					'value' => 'name,products',
					'options' => array(
						array(
							'name',
							esc_html__( 'Category name', 'xstore-core' ),
						),
						array(
							'products',
							esc_html__( 'Products', 'xstore-core' ),
						),
					),
					'default' => 'name,products',
					'hint' => 'Sort fields how you want or disable one of theme. To disable all please click on checkbox right',
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Disable category name and products count ', 'xstore-core' ),
					'param_name' => 'hide_all',
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Remove space between items', 'xstore-core' ),
					'param_name' => 'no_space',
					'value' => 1,
				),
				array(
					'type' => 'dropdown',
					'heading' => $strings['heading']['image_size'],
					'param_name' => 'image_size',
					'value' => $sizes_select2,
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
						esc_html__('Menu', 'xstore-core') => 'menu'
					),
					'images_value' => array(
						'grid'   => ET_CORE_SHORTCODES_IMAGES . 'categories/Grid.svg',
						'slider'   => ET_CORE_SHORTCODES_IMAGES . 'categories/Slider.svg',
						'menu'   => ET_CORE_SHORTCODES_IMAGES . 'categories/Menu.svg',
					),
					'et_tooltip' => true,
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
                    'dependency' => array('element' => 'display_type', 'value' => array('grid')),
                    'edit_field_class' => 'vc_col-md-6 vc_column',
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
                    'group' => esc_html__('Data Settings', 'xstore-core'),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Categories', 'xstore-core' ),
					'group' => esc_html__('Data Settings', 'xstore-core'),
					'param_name' => 'ids',
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
					),
					'save_always' => true,
					'hint' => esc_html__( 'List of product categories', 'xstore-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => $strings['heading']['orderby'],
					'group' => esc_html__('Data Settings', 'xstore-core'),
					'param_name' => 'orderby',
					'value' => $order_by_values,
					'save_always' => true,
					'hint' => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => $strings['heading']['order'],
					'group' => esc_html__('Data Settings', 'xstore-core'),
					'param_name' => 'order',
					'value' => $strings['value']['order'],
					'save_always' => true,
					'hint' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Number of categories', 'xstore-core'),
					'group' => esc_html__('Data Settings', 'xstore-core'),
					'param_name' => 'number'
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Parent ID', 'xstore-core'),
					'group' => esc_html__('Data Settings', 'xstore-core'),
					'param_name' => 'parent',
					'hint' => esc_html__('Get direct children of this term (only terms whose explicit parent is this value). If 0 is passed, only top-level terms are returned. Default is an empty string.', 'xstore-core')
				),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Design', 'xstore-core' ),
                    'group' => esc_html__('Design', 'xstore-core'),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Style', 'xstore-core'),
					'param_name' => 'style',
					'group' => esc_html__('Design', 'xstore-core'),
					'value' => array( 
						'Default' => 'default',
						'Title with background' => 'with-bg',
						'Zoom' => 'zoom',
						'Diagonal' => 'diagonal',
						'Classic' => 'classic',
					),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Products count as label', 'xstore-core' ),
					'group' => esc_html__('Design', 'xstore-core'),
					'param_name' => 'count_label',
				),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Alignment', 'xstore-core' ),
                    'group' => esc_html__('Design', 'xstore-core'),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'xstore_button_set',
					'heading' => $strings['heading']['align'],
					'param_name' => 'text_align',
					'group' => esc_html__('Design', 'xstore-core'),
					'value' => $strings['value']['align'],
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => $strings['heading']['valign'],
					'group' => esc_html__('Design', 'xstore-core'),
					'param_name' => 'valign',
					'value' => array( 
						'Center' => 'center',
						'Top' => 'top',
						'Bottom' => 'bottom',
					),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Text transform', 'xstore-core' ),
                    'group' => esc_html__('Design', 'xstore-core'),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'xstore_button_set',
					'param_name' => 'text_transform',
					'group' => esc_html__('Design', 'xstore-core'),
					'value' => array( 
						'Uppercase' => 'uppercase',
						'Lowercase' => 'lowercase',
						'Capitalize' => 'capitalize',
						'None' => 'none'
					),
				),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Styles', 'xstore-core' ),
                    'group' => esc_html__('Design', 'xstore-core'),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Color scheme', 'xstore-core'),
					'param_name' => 'text_color',
					'group' => esc_html__('Design', 'xstore-core'),
					'value' => array(
						'White' => 'white',
						'Dark' => 'dark',
						'Custom' => 'custom'
					),
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Category name color', 'xstore-core'),
					'param_name' => 'title_color',
					'group' => esc_html__('Design', 'xstore-core'),
					'value' => '#000',
					'dependency' => array(
						'element' => 'text_color',
						'value' => 'custom'
					),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Product count color', 'xstore-core'),
					'param_name' => 'subtitle_color',
					'group' => esc_html__('Design', 'xstore-core'),
					'value' => '#000',
					'dependency' => array(
						'element' => 'text_color',
						'value' => 'custom'
					),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__('Background color', 'xstore-core'),
					'param_name' => 'bg_color',
					'group' => esc_html__('Design', 'xstore-core'),
					'dependency' => array(
						'element' => 'style',
						'value' => 'with-bg'
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Category name font size', 'xstore-core'),
					'param_name' => 'title_size',
					'group' => esc_html__('Design', 'xstore-core'),
					'dependency' => array(
						'element' => 'text_color',
						'value' => 'custom'
					),
					'hint' => esc_html__('Write font size for element with dimentions. Example 14px, 15em, 20%', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),

				array(
					'type' => 'textfield',
					'heading' => esc_html__('Product count font size', 'xstore-core'),
					'param_name' => 'subtitle_size',
					'group' => esc_html__('Design', 'xstore-core'),
					'dependency' => array(
						'element' => 'text_color',
						'value' => 'custom'
					),
					'hint' => esc_html__('Write font size for element with dimentions. Example 14px, 15em, 20%', 'xstore-core'),
					'edit_field_class' => 'vc_col-md-6 vc_column',
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Speed', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
				),
				array(
					'type' => 'checkbox',
					'heading' => $strings['heading']['ajax'],
					'param_name' => 'ajax',
				),
			), $this->get_slider_params( array( 'element' => 'display_type', 'value' => array('slider') ) ))
		);  

		vc_map($params);
	}

}
