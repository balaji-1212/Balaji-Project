<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Category shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Category extends VC {

	function hooks(){
        $this->register_vc_category();
	}

	function register_vc_category() {

        // Necessary hooks for blog autocomplete fields
        add_filter( 'vc_autocomplete_et_category_fp_id_callback', 'vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
        add_filter( 'vc_autocomplete_et_category_fp_id_render', 'vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
        add_filter( 'vc_autocomplete_et_category_sp_id_callback', 'vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
        add_filter( 'vc_autocomplete_et_category_sp_id_render', 'vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)
        add_filter( 'vc_autocomplete_et_category_tp_id_callback', 'vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
        add_filter( 'vc_autocomplete_et_category_tp_id_render', 'vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)

        if( class_exists('Vc_Vendor_Woocommerce')) {
        	$Vc_Vendor_Woocommerce = new \Vc_Vendor_Woocommerce();
            add_filter( 'vc_autocomplete_et_category_taxonomies_callback', array($Vc_Vendor_Woocommerce, 'productCategoryCategoryAutocompleteSuggester', ), 10, 1 ); // Get suggestion(find). Must return an array
            add_filter( 'vc_autocomplete_et_category_taxonomies_render', array($Vc_Vendor_Woocommerce, 'productCategoryCategoryRenderByIdExact',), 10, 1 ); // Render exact category by id. Must return an array (label,value)
        }

        $strings = $this->etheme_vc_shortcodes_strings();

        $order_by_values = array(
        	'',
        	esc_html__( 'Date', 'xstore-core' ) => 'date',
        	esc_html__( 'ID', 'xstore-core' ) => 'ID',
        	esc_html__( 'Author', 'xstore-core' ) => 'author',
        	esc_html__( 'Title', 'xstore-core' ) => 'title',
        	esc_html__( 'Modified', 'xstore-core' ) => 'modified',
        	esc_html__( 'Random', 'xstore-core' ) => 'rand',
        	esc_html__( 'Comment count', 'xstore-core' ) => 'comment_count',
        	esc_html__( 'Menu order', 'xstore-core' ) => 'menu_order',
        	esc_html__( 'Price', 'xstore-core' ) => 'price',

        );

        $counter = 0;
        $params = array(
        	'name' => 'Advanced Block Of Products',
        	'base' => 'et_category',
        	'icon' => ETHEME_CODE_IMAGES . 'vc/Products.png',
            'description' => esc_html__('Display 3 product of chosen category with See all button', 'xstore-core'),
        	'category' => $strings['category'],
        	'params' => array(
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Data settings', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
        		array(
        			'type' => 'autocomplete',
        			'heading' => esc_html__( 'Categories or tags', 'xstore-core' ),
        			'param_name' => 'taxonomies',
        			'settings' => array(
        				'multiple' => true,
                        // is multiple values allowed? default false
                        // 'sortable' => true, // is values are sortable? default false
        				'min_length' => 1,
                        // min length to start search -> default 2
                        // 'no_hide' => true, // In UI after select doesn't hide an select list, default false
        				'groups' => true,
                        // In UI show results grouped by groups, default false
        				'unique_values' => true,
                        // In UI show results except selected. NB! You should manually check values in backend, default false
        				'display_inline' => true,
                        // In UI show results inline view, default false (each value in own line)
        				'delay' => 500,
                        // delay for search. default 500
        				'auto_focus' => true,
                        // auto focus input, default true
        			),
        			'hint' => esc_html__( 'Enter one category, tag or custom taxonomy.', 'xstore-core' ),
        		),
        		array(
        			'type' => 'xstore_button_set',
        			'heading' => esc_html__('Products type', 'xstore-core'),
        			'param_name' => 'products_type',
        			'value' => array( 
                        esc_html__('All', 'xstore-core') => '', 
                        esc_html__('Featured', 'xstore-core') => 'featured', 
                        esc_html__('Sale', 'xstore-core') => 'sale', 
                        esc_html__('Recently viewed', 'xstore-core') => 'recently_viewed', 
                        esc_html__('Bestsellings', 'xstore-core') => 'bestsellings'
                    ),
        			'dependency' => array('element' => 'custom', 'value_not_equal_to' => 'true')
        		),
        		array(
        			'type' => 'dropdown',
        			'heading' => $strings['heading']['orderby'],
        			'param_name' => 'orderby',
        			'value' => $order_by_values,
        			'hint' => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        			'dependency' => array('element' => 'custom', 'value_not_equal_to' => 'true'),
                    'edit_field_class' => 'vc_col-sm-6 vc_column',
        		),
        		array(
        			'type' => 'xstore_button_set',
        			'heading' => $strings['heading']['order'],
        			'param_name' => 'order',
        			'value' => $strings['value']['order'],
        			'hint' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
        			'dependency' => array('element' => 'custom', 'value_not_equal_to' => 'true'),
                    'edit_field_class' => 'vc_col-sm-6 vc_column',
        		),
        		array(
        			'type' => 'xstore_button_set',
        			'heading' => esc_html__('Hide out of stock products', 'xstore-core'),
        			'param_name' => 'hide_out_stock',
        			'value' => array(
        				'No' => '',
        				'Yes' => 'yes',
        			),
        			'dependency' => array('element' => 'custom', 'value_not_equal_to' => 'true')
        		),
        		array(
        			'type' => 'checkbox',
        			'heading' => esc_html__('Select custom products', 'xstore-core'),
        			'param_name' => 'custom',
        			'hint' => esc_html__('Select custom products, if not then products will be gotten randomly', 'xstore-core'),
        			'default' => false
        		),
        		array(
        			'type' => 'autocomplete',
        			'heading' => esc_html__( 'First Product', 'xstore-core' ),
        			'param_name' => 'fp_id',
        			'settings' => array(
        				'multiple' => true,
        				'sortable' => true,
        				'groups' => true,
        			),
        			'hint' => esc_html__( 'Add product by title.', 'xstore-core' ),
        			'dependency' => array('element' => 'custom', 'value' => 'true')
        		),
        		array(
        			'type' => 'autocomplete',
        			'heading' => esc_html__( 'Second Product', 'xstore-core' ),
        			'param_name' => 'sp_id',
        			'settings' => array(
        				'multiple' => true,
        				'sortable' => true,
        				'groups' => true,
        			),
        			'hint' => esc_html__( 'Add product by title.', 'xstore-core' ),
        			'dependency' => array('element' => 'custom', 'value' => 'true')
        		),
        		array(
        			'type' => 'autocomplete',
        			'heading' => esc_html__( 'Third Product', 'xstore-core' ),
        			'param_name' => 'tp_id',
        			'settings' => array(
        				'multiple' => true,
        				'sortable' => true,
        				'groups' => true,
        			),
        			'hint' => esc_html__( 'Add product by title.', 'xstore-core' ),
        			'dependency' => array('element' => 'custom', 'value' => 'true')
        		),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Element Colors', 'xstore-core' ),
                    'group' => esc_html__( 'Style', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
        		array(
        			'type' => 'colorpicker',
        			'heading' => esc_html__('Title color', 'xstore-core'),
        			'param_name' => 'title_color',
        			'group' => esc_html__( 'Style', 'xstore-core' ),
                    'edit_field_class' => 'vc_col-sm-6 vc_column',
        		),
        		array(
        			'type' => 'colorpicker',
        			'heading' => esc_html__('Title background color', 'xstore-core'),
        			'param_name' => 'head_bg',
        			'group' => esc_html__( 'Style', 'xstore-core' ),
                    'edit_field_class' => 'vc_col-sm-6 vc_column',
        		),
                array(
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Background color', 'xstore-core'),
                    'param_name' => 'content_bg',
                    'group' => esc_html__( 'Style', 'xstore-core' ),
                    'edit_field_class' => 'vc_col-sm-6 vc_column',
                ),
                array(
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Divider color', 'xstore-core'),
                    'param_name' => 'sep_color',
                    'group' => esc_html__( 'Style', 'xstore-core' ),
                    'edit_field_class' => 'vc_col-sm-6 vc_column',
                ),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Border settings', 'xstore-core' ),
                    'group' => esc_html__( 'Style', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
        		array(
        			'type' => 'textfield',
                    'heading' => esc_html__('Border radius', 'xstore-core'),
        			'param_name' => 'radius',
        			'group' => esc_html__( 'Style', 'xstore-core' ),
                    'edit_field_class' => 'vc_col-sm-3 vc_column',
        		),
        		array (
        			'type' => 'textfield',
                    'heading' => esc_html__( 'Border width', 'xstore-core' ),
        			'param_name' => 'b_width',
        			'group' => esc_html__( 'Style', 'xstore-core' ),
                    'edit_field_class' => 'vc_col-sm-3 vc_column',
        		),
                array(
                    'type' => 'dropdown',
                    'heading' => esc_html__('Border style', 'xstore-core'),
                    'param_name' => 'b_style',
                    'value' => array(
                        '' => esc_html__('Unset', 'xstore-core'),
                        'solid' => esc_html__('Solid', 'xstore-core'),
                        'dashed' => esc_html__('Dashed', 'xstore-core'),
                        'dotted' => esc_html__('Dotted', 'xstore-core'),
                        'double' => esc_html__('Double', 'xstore-core'),
                        'groove' => esc_html__('Groove', 'xstore-core'),
                        'ridge' => esc_html__('Ridge', 'xstore-core'),
                        'inset' => esc_html__('Inset', 'xstore-core'),
                        'outset' => esc_html__('Outset', 'xstore-core'),
                    ),
                    'group' => esc_html__( 'Style', 'xstore-core' ),
                    'edit_field_class' => 'vc_col-sm-3 vc_column',
                ),
        		array(
        			'type' => 'colorpicker',
        			'heading' => esc_html__('Border color', 'xstore-core'),
        			'param_name' => 'b_color',
        			'group' => esc_html__( 'Style', 'xstore-core' ),
        			'edit_field_class' => 'vc_col-sm-3 vc_column',
        		),
        	),
	);

	vc_map($params);
	}

}