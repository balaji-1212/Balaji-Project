<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * The Look shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class The_Look extends VC {

	function hooks() {
		$this->register_the_look();
	}

	function register_the_look() {

		add_filter( 'vc_autocomplete_et_the_look_include_callback','vc_include_field_search', 10, 1 ); // Get suggestion(find). Must return an array
		add_filter( 'vc_autocomplete_et_the_look_include_render','vc_include_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)

      	// Narrow data taxonomies
		add_filter( 'vc_autocomplete_et_the_look_taxonomies_callback','vc_autocomplete_taxonomies_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_et_the_look_taxonomies_render','vc_autocomplete_taxonomies_field_render', 10, 1 );

      	// Narrow data taxonomies for exclude_filter
		add_filter( 'vc_autocomplete_et_the_look_exclude_filter_callback','vc_autocomplete_taxonomies_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_et_the_look_exclude_filter_render','vc_autocomplete_taxonomies_field_render', 10, 1 );

		add_filter( 'vc_autocomplete_et_the_look_exclude_callback','vc_exclude_field_search', 10, 1 ); // Get suggestion(find). Must return an array
		add_filter( 'vc_autocomplete_et_the_look_exclude_render', 'vc_exclude_field_render', 10, 1 ); // Render exact product. Must return an array (label,value)

		$strings = $this->etheme_vc_shortcodes_strings();
		$blog_strings = $this->etheme_vc_blog_shortcodes_strings();
		$strings['value'] = array_merge($strings['value'], $blog_strings['value']);

		$counter = 0;
		$params = array(
			'name' => 'The Look',
			'base' => 'et_the_look',
			'category' => $strings['category'],
			'content_element' => true,
			'icon' => ETHEME_CODE_IMAGES . 'vc/Products.png',
			'description' => esc_html__('Display products with banner', 'xstore-core'),
			'as_child' => array('only' => 'et_looks'),            
			'is_container' => false,
			'params' => array(
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Data settings', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__( 'Data source', 'xstore-core' ),
					'param_name' => 'post_type',
					'value' => array(
						esc_html__( 'Product', 'xstore-core' ) => 'product',
						// esc_html__( 'Custom query', 'xstore-core' ) => 'custom',
						esc_html__( 'List of IDs', 'xstore-core' ) => 'ids',
					),
					'hint' => esc_html__( 'Select content type for your grid.', 'xstore-core' )
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Include only', 'xstore-core' ),
					'param_name' => 'include',
					'hint' => esc_html__( 'Add posts, pages, etc. by title.', 'xstore-core' ),
					'settings' => array(
						'multiple' => true,
						'sortable' => true,
						'groups' => true,
					),
					'dependency' => array(
						'element' => 'post_type',
						'value' => 'ids',
              			//'callback' => 'vc_grid_include_dependency_callback',
					),
				),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Exclude', 'xstore-core' ),
					'param_name' => 'exclude',
					'hint' => esc_html__( 'Exclude posts, pages, etc. by title.', 'xstore-core' ),
					'settings' => array(
						'multiple' => true,
					),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array( 'ids', 'custom' ),
						'callback' => 'vc_grid_exclude_dependency_callback',
					),
				),
          		// Custom query tab
				// array(
				// 	'type' => 'textarea_safe',
				// 	'heading' => esc_html__( 'Custom query', 'xstore-core' ),
				// 	'param_name' => 'custom_query',
				// 	'hint' => __( 'Build custom query according to <a href="http://codex.wordpress.org/Function_Reference/query_posts">WordPress Codex</a>.', 'xstore-core' ),
				// 	'dependency' => array(
				// 		'element' => 'post_type',
				// 		'value' => array( 'custom' ),
				// 	),
				// ),
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'Narrow data source', 'xstore-core' ),
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
              			// 'values' => $taxonomies_for_filter,
					),
					'param_holder_class' => 'vc_not-for-custom',
					'hint' => esc_html__( 'Enter categories, tags or custom taxonomies.', 'xstore-core' ),
					'dependency' => array(
						'element' => 'post_type',
						'value' => 'product',
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Items per page', 'xstore-core' ),
					'param_name' => 'items_per_page',
					'hint' => esc_html__( 'Number of items to show per page.', 'xstore-core' ),
					'value' => '10',
		            /*'dependency' => array(
		              'element' => 'style',
		              'value' => array( 'lazy', 'load-more', 'pagination' ),
		            ),
		            'edit_field_class' => 'vc_col-sm-6 vc_column',*/
        		),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Layout', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
                array(
                    'type' => 'xstore_slider',
                    'heading' => esc_html__( 'Columns number', 'xstore-core' ),
                    'param_name' => 'columns',
                    'min' => 3,
                    'max' => 4,
                    'step' => 1,
                    'default' => 3,
                    'units' => '',
                ),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Product View', 'xstore-core'),
					'param_name' => 'product_view',
					'value' => array( 
						'Unset' => '', 
						'Default' => 'default',
						'Buttons on hover' => 'mask',
						'Information mask' => 'info',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Product View Color', 'xstore-core'),
					'param_name' => 'product_view_color',
					'value' => array( 
						'Unset' => '', 
						'White' => 'white',
						'Dark' => 'dark',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Data Settings', 'xstore-core' ),
                    'group' => esc_html__( 'Data Settings', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'dropdown',
					'heading' => $strings['heading']['orderby'],
					'param_name' => 'orderby',
					'value' => $strings['value']['orderby'],
					'hint' => esc_html__( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'xstore-core' ),
					'group' => esc_html__( 'Data Settings', 'xstore-core' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'dependency' => array(
						'element' => 'post_type',
						'value' => 'product',
					),
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => $strings['heading']['order'],
					'param_name' => 'order',
					'group' => esc_html__( 'Data Settings', 'xstore-core' ),
					'value' => $strings['value']['order_reverse'],
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
					'hint' => esc_html__( 'Select sorting order.', 'xstore-core' ),
					'dependency' => array(
						'element' => 'post_type',
						'value' => 'product',
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Meta key', 'xstore-core' ),
					'param_name' => 'meta_key',
					'hint' => esc_html__( 'Input meta key for grid ordering.', 'xstore-core' ),
					'group' => esc_html__( 'Data Settings', 'xstore-core' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => array(
						'element' => 'orderby',
						'value' => array( 'meta_value', 'meta_value_num' ),
					),
				),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Image', 'xstore-core' ),
                    'group' => esc_html__( 'Banner', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'attach_image',
					'heading' => esc_html__('Banner Image', 'xstore-core'),
					'param_name' => 'img',
					'group' => esc_html__( 'Banner', 'xstore-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Banner size', 'xstore-core' ),
					'param_name' => 'img_size',
					'hint' => $strings['hint']['img_size'],
					'group' => esc_html__( 'Banner', 'xstore-core' ),
				),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Content', 'xstore-core' ),
                    'group' => esc_html__( 'Banner', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'textarea_html',
					'holder' => 'div',
					'heading' => 'Banner Mask Text',
					'param_name' => 'content',
					'group' => esc_html__( 'Banner', 'xstore-core' ),
				),
				array(
					'type' => 'vc_link',
					'heading' => esc_html__('Link', 'xstore-core'),
					'param_name' => 'link',
					'group' => esc_html__( 'Banner', 'xstore-core' ),
				),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Alignment', 'xstore-core' ),
                    'group' => esc_html__( 'Banner', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'xstore_button_set',
					'heading' => $strings['heading']['align'],
					'param_name' => 'align',
					'group' => esc_html__( 'Banner', 'xstore-core' ),
					'value' => $strings['value']['align'],
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => $strings['heading']['align'],
					'param_name' => 'valign',
					'group' => esc_html__( 'Banner', 'xstore-core' ),
					'value' => $strings['value']['valign'],
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Design', 'xstore-core' ),
                    'group' => esc_html__( 'Banner', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'xstore_button_set',
					'param_name' => 'type',
					'heading' => esc_html__( 'Type', 'xstore-core' ),
					'group' => esc_html__( 'Banner', 'xstore-core' ),
					'value' => array( 
						__('None', 'xstore-core') => 3,
						__('Zoom In', 'xstore-core') => 2,
						// __('Slide right', 'xstore-core') => 6,
						__('Zoom out', 'xstore-core') => 4,
						__('Scale out', 'xstore-core') => 5
					),
					'default' => 3
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Color scheme', 'xstore-core'),
					'param_name' => 'font_style',
					'group' => esc_html__( 'Banner', 'xstore-core' ),
					'value' => $strings['value']['font_style']['type1']
				),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Advanced settings', 'xstore-core' ),
                    'group' => esc_html__( 'Banner', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Banner position', 'xstore-core'),
					'param_name' => 'banner_pos',
					'hint' => esc_html__('Banner position number. From 1 to number of products.', 'xstore-core'),
					'group' => esc_html__( 'Banner', 'xstore-core' ),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Banner double size', 'xstore-core' ),
					'param_name' => 'banner_double',
					'group' => esc_html__( 'Banner', 'xstore-core' ),
					'value' => 1
				),
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'xstore-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'Design for product images', 'xstore-core' )
				),
			)

		);  

		vc_map($params);
	}
}
