<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Blog carousel shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Blog_Carousel extends VC {

	function hooks() {

		add_filter( 'vc_autocomplete_et_blog_carousel_include_callback', 'vc_include_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_et_blog_carousel_include_render', 'vc_include_field_render', 10, 1 );

		add_filter( 'vc_autocomplete_et_blog_carousel_taxonomies_callback', 'vc_autocomplete_taxonomies_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_et_blog_carousel_taxonomies_render', 'vc_autocomplete_taxonomies_field_render', 10, 1 );

		add_filter( 'vc_autocomplete_et_blog_carousel_exclude_filter_callback', 'vc_autocomplete_taxonomies_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_et_blog_carousel_exclude_filter_render', 'vc_autocomplete_taxonomies_field_render', 10, 1 );

		add_filter( 'vc_autocomplete_et_blog_carousel_exclude_callback', 'vc_exclude_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_et_blog_carousel_exclude_render', 'vc_exclude_field_render', 10, 1 );

		$this->register_vc_blog_carousel();
	}

	function register_vc_blog_carousel() {

		$strings = $this->etheme_vc_shortcodes_strings();
		$blog_strings = $this->etheme_vc_blog_shortcodes_strings();
		$strings['value'] = array_merge($strings['value'], $blog_strings['value']);

		$counter = 0;
		$params = array(
			'name' => 'Blog Carousel',
			'base' => 'et_blog_carousel',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Blog.png',
			'description' => esc_html__('Display slider with posts', 'xstore-core'),
			'category' => $strings['category'],
			'params' => array_merge(
				array(
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
							esc_html__( 'Post', 'xstore-core' ) => 'post',
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
					// 		'value' => 'custom',
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
							// 'no_hide' => true, // In UI after select doesn't hide an select carousel, default false
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
							'value' => 'post',
						),
					),
					array(
						'type' => 'xstore_slider',
						'heading' => esc_html__( 'Items per page', 'xstore-core' ),
						'param_name' => 'items_per_page',
						'min' => -1,
						'max' => 30,
						'step' => 1,
						'default' => 10,
						'units' => '',
						'hint' => esc_html__( 'Use "-1" to show all posts.', 'xstore-core' ),
					),
					array(
						'type' => 'xstore_title_divider',
						'title' => esc_html__( 'Content', 'xstore-core' ),
						'param_name' => 'divider'.$counter++
					),
					array(
						'type' => 'dropdown',
						'heading' => esc_html__( 'Posts layout', 'xstore-core' ),
						'param_name' => 'slide_view',
						'value' => array(
							esc_html__('Simple grid', 'xstore-core') => 'vertical',
							esc_html__('List', 'xstore-core') => 'horizontal',
							esc_html__('Grid with date label', 'xstore-core') => 'timeline2',
						),
					),
					array(
						'type' => 'xstore_button_set',
						'heading' => esc_html__( 'Blog hover effect', 'xstore-core' ),
						'param_name' => 'blog_hover',
						'value' => $strings['value']['blog_hover'],
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type' => 'xstore_button_set',
						'heading' => esc_html__( 'Blog align', 'xstore-core' ),
						'param_name' => 'blog_align',
						'value' => $strings['value']['align'],
						'edit_field_class' => 'vc_col-sm-6 vc_column',
						'tooltip_values' => $strings['tooltip']['align']
					),
	                array(
	                    'type' => 'checkbox',
	                    'heading' => esc_html__('Hide image', 'xstore-core'),
	                    'param_name' => 'hide_img',
	                ),
					array(
						'type' => 'textfield',
						'heading' => esc_html__( 'Images size', 'xstore-core' ),
						'hint' => $strings['hint']['img_size'],
						'param_name' => 'size',
						'value' => '',
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
						'hint' => sprintf( esc_html__( 'Select how to sort retrieved posts. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
						'group' => esc_html__( 'Data Settings', 'xstore-core' ),
						'param_holder_class' => 'vc_grid-data-type-not-ids',
						'dependency' => array(
							'element' => 'post_type',
							'value' => 'post',
						),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type' => 'xstore_button_set',
						'heading' => $strings['heading']['order'],
						'param_name' => 'order',
						'value' => $strings['value']['order_reverse'],
						'hint' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s.', 'xstore-core' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
						'group' => esc_html__( 'Data Settings', 'xstore-core' ),
						'param_holder_class' => 'vc_grid-data-type-not-ids',
						'dependency' => array(
							'element' => 'post_type',
							'value' => 'post',
						),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
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
						'type' => 'autocomplete',
						'heading' => esc_html__( 'Exclude', 'xstore-core' ),
						'param_name' => 'exclude',
						'hint' => esc_html__( 'Exclude posts, pages, etc. by title.', 'xstore-core' ),
						'group' => esc_html__( 'Data Settings', 'xstore-core' ),
						'settings' => array(
							'multiple' => true,
						),
						'param_holder_class' => 'vc_grid-data-type-not-ids',
						'dependency' => array(
							'element' => 'post_type',
							'value' => 'post',
							'callback' => 'vc_grid_exclude_dependency_callback',
						),
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
				),
				$this->get_slider_params()
			)
		);

		vc_map($params);
	}
}
