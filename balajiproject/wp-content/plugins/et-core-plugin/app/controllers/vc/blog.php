<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Blog shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Blog extends VC {

	function hooks() {

		add_filter( 'vc_autocomplete_et_blog_include_callback', 'vc_include_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_et_blog_include_render', 'vc_include_field_render', 10, 1 );

		add_filter( 'vc_autocomplete_et_blog_taxonomies_callback', 'vc_autocomplete_taxonomies_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_et_blog_taxonomies_render', 'vc_autocomplete_taxonomies_field_render', 10, 1 );

		add_filter( 'vc_autocomplete_et_blog_exclude_filter_callback', 'vc_autocomplete_taxonomies_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_et_blog_exclude_filter_render', 'vc_autocomplete_taxonomies_field_render', 10, 1 );

		add_filter( 'vc_autocomplete_et_blog_exclude_callback', 'vc_exclude_field_search', 10, 1 );
		add_filter( 'vc_autocomplete_et_blog_exclude_render', 'vc_exclude_field_render', 10, 1 );

		$this->register_vc_blog();
	}

	function register_vc_blog() {

		$strings = $this->etheme_vc_shortcodes_strings();
		$blog_strings = $this->etheme_vc_blog_shortcodes_strings();
		$strings['value'] = array_merge($strings['value'], $blog_strings['value']);

		$counter = 0;
		$params = array(
			'name' => 'Blog',
			'base' => 'et_blog',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Blog.png',
			'description' => esc_html__('Display posts in grid', 'xstore-core'),
			'category' => $strings['category'],
			'params' => array(
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Data', 'xstore-core' ),
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
				array(
					'type' => 'textarea_safe',
					'heading' => esc_html__( 'Custom query', 'xstore-core' ),
					'param_name' => 'custom_query',
					'hint' => esc_html__( 'Build custom query according to <a href="http://codex.wordpress.org/Function_Reference/query_posts">WordPress Codex</a>.', 'xstore-core' ),
					'dependency' => array(
						'element' => 'post_type',
						'value' => 'custom',
					),
				),
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
						'value_not_equal_to' => array( 'ids', 'custom' ),
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
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'xstore_slider',
					'heading' => esc_html__( 'Limit', 'xstore-core' ),
					'param_name' => 'items_limit',
					'min' => 1,
					'max' => 200,
					'step' => 1,
					'default' => 10,
					'units' => '',
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'xstore_title_divider',
					'title' => esc_html__( 'Content', 'xstore-core' ),
					'param_name' => 'divider'.$counter++
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
					'hint' => esc_html__( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'xstore-core' ),
					'group' => esc_html__( 'Data Settings', 'xstore-core' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array( 'ids', 'custom' ),
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => $strings['heading']['order'],
					'param_name' => 'order',
					'value' => $strings['value']['order_reverse'],
					'group' => esc_html__( 'Data Settings', 'xstore-core' ),
					'param_holder_class' => 'vc_grid-data-type-not-ids',
					'hint' => esc_html__( 'Select sorting order.', 'xstore-core' ),
					'dependency' => array(
						'element' => 'post_type',
						'value_not_equal_to' => array( 'ids', 'custom' ),
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
						'value_not_equal_to' => array( 'ids', 'custom' ),
						'callback' => 'vc_grid_exclude_dependency_callback',
					),
				),
			),
		);

		vc_map($params);
	}
}
