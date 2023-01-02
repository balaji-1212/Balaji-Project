<?php
/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit;

function et_get_menu_fields() {
	global $wp_registered_sidebars;
	$sidebar_option = array(''=>'Select widget area');
	foreach ($wp_registered_sidebars as $key => $sidebar) {
		$sidebar_option[$key] = $sidebar['name'];
	}

    $blocks = array();
    $blocks[''] = "";  

    $posts = get_posts( array(
        'post_type' => 'staticblocks',
        'numberposts' => -1,
    ) );

    if ( $posts ) {
        foreach ( $posts as $post ) {
			$blocks[$post->ID] = $post->post_title;
        }
    }

	return array(
		array(
			'id' => 'disable_titles',
			'type' => 'checkbox',
			'title' => esc_html__('Disable navigation label', 'xstore'),
			'width' => 'wide',
			'value' => 1,
			'levels' => array(0,1,2)
		),
		array(
			'id' => 'anchor',
			'type' => 'text',
			'title' => esc_html__('Anchor', 'xstore'),
			'width' => 'wide'
		),
		array(
			'id' => 'design',
			'type' => 'select',
			'title' => esc_html__('Design', 'xstore'),
			'width' => 'wide',
			'options' => array(
				'' => esc_html__('Select design option', 'xstore'),
				'dropdown' => esc_html__('Dropdown', 'xstore'),
				'mega-menu' => esc_html__('Mega menu', 'xstore'),
				'posts-subcategories' => esc_html__('Subcategories + Posts', 'xstore'),
				//'image-column' => 'Image column',
				//'image-no-space' => 'Image column no space',
			),
			'levels' => 0
		),
		array(
			'id' => 'columns',
			'type' => 'select',
			'title' => esc_html__('Columns', 'xstore'),
			'width' => 'wide',
			'options' => array(
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5,
				6 => 6,
			),
			'levels' => 0
		),
		array(
			'id' => 'column_width',
			'type' => 'text',
			'title' => esc_html__('Column width (for ex.: 100%)', 'xstore'),
			'width' => 'wide',
			'input_type' => 'number',
			'attributes' => array(
				'min' => 1,
				'max' => 300
			),
			'levels' => array(1)
		),
		array(
			'id' => 'column_height',
			'type' => 'text',
			'title' => esc_html__('Mega menu height (for ex.: 100px)', 'xstore'),
			'width' => 'wide',
			'input_type' => 'number',
			'attributes' => array(
				'step' => 0.1,
				'min' => 1,
//				'max' => 1000
			),
			'levels' => array(0)
		),
		array(
			'id' => 'sublist_width',
			'type' => 'text',
			'title' => esc_html__('Mega menu container width (for ex.: 1000px)', 'xstore'),
			'width' => 'wide',
			'input_type' => 'number',
			'attributes' => array(
				'min' => 1,
			),
			'levels' => array(0)
		),
		array(
			'id' => 'design2',
			'type' => 'select',
			'title' => esc_html__('Design', 'xstore'),
			'width' => 'wide',
			'options' => array(
				'' => esc_html__('Select design option', 'xstore'),
				'image' => esc_html__('Image', 'xstore'),
				'image-no-borders' => esc_html__('Image without spacing', 'xstore'),
				//'image-column' => 'Image column',
				//'image-no-space' => 'Image column no space',
			),
			'levels' => array(1,2)
		),
		/*array(
			'id' => 'block',
			'type' => 'select',
			'title' => 'Static block',
			'width' => 'wide',
			'options' => $blocks,
			'levels' => array(1)
		),*/
		array(
			'id' => 'static_block',
			'type' => 'select',
			'title' => esc_html__('Static block', 'xstore'),
			'desc' => esc_html__('Don\'t create subitems if you use static block!', 'xstore'),
			'width' => 'wide',
			'options' => $blocks,
			'levels' => array(0)
		),
		array(
			'id' => 'widget_area',
			'type' => 'select',
			'title' => esc_html__('Widget area', 'xstore'),
			'width' => 'wide',
			'options' => $sidebar_option,
			'levels' => array(1,2)
		),
		array (
			'id' => 'open_by_click',
			'type' => 'checkbox',
			'title' => esc_html__('Open submenu by click', 'xstore'),
			'width' => 'wide',
			'value' => 1,
			'levels' => array(0)
		),
		array(
			'id' => 'icon_type',
			'type' => 'select',
			'title' => esc_html__('Icons library', 'xstore'),
			'width' => 'wide',
			'options' => array(
				'fontawesome' => 'FontAwesome 4.7',
				'fontawesome-5' => 'FontAwesome 5+',
				'xstore-icons' => 'XStore icons',
			),
		),
		array(
			'id' => 'icon',
			'type' => 'text',
			'title' => esc_html__('Icon name', 'xstore'),
			'desc' => esc_html__('If you use FontAwesome icons library then FontAwesome support should be enabled in Theme Options -> Speed Optimization', 'xstore'),
			'width' => 'wide',
		),
		array(
			'id' => 'label',
			'type' => 'select',
			'title' => esc_html__('Label', 'xstore'),
			'width' => 'wide',
			'options' => array(
				'' => esc_html__('Select label', 'xstore'),
				'hot' => esc_html__('Hot', 'xstore'),
				'sale' => esc_html__('Sale', 'xstore'),
				'new' => esc_html__('New', 'xstore'),
			)
		),
		array(
			'id' => 'use_img',
			'type' => 'select',
			'title' => esc_html__('Use img like', 'xstore'),
			'width' => 'thin',
			'options' => array(
				'background' => esc_html__('Background', 'xstore'),
				'img' => esc_html__('Image', 'xstore'),
			),
			'levels' => 0
		),
		array(
			'id' => 'background_repeat',
			'type' => 'select',
			'title' => esc_html__('Background Repeat', 'xstore'),
			'width' => 'thin',
			'options' => array(
				'' => esc_html__('Background-repeat', 'xstore'),
				'no-repeat' => esc_html__('No Repeat', 'xstore'),
				'repeat' => esc_html__('Repeat All', 'xstore'),
				'repeat-x' => esc_html__('Repeat Horizontally', 'xstore'),
				'repeat-y' => esc_html__('Repeat Vertically', 'xstore'),
				'inherit' => esc_html__('Inherit', 'xstore'),
			),
			'levels' => array(0,1,2)
		),
		array(
			'id' => 'background_position',
			'type' => 'select',
			'title' => esc_html__('Background Position', 'xstore'),
			'width' => 'thin',
			'options' => array(
				'' => esc_html__('Background-position', 'xstore'),
				'left top' => esc_html__('Left Top', 'xstore'),
				'left center' => esc_html__('Left Center', 'xstore'),
				'left bottom' => esc_html__('Left Bottom', 'xstore'),
				'center top' => esc_html__('Center top', 'xstore'),
				'center center' => esc_html__('Center Center', 'xstore'),
				'center bottom' => esc_html__('Center Bottom', 'xstore'),
				'right top' => esc_html__('Right Top', 'xstore'),
				'right center' => esc_html__('Right Center', 'xstore'),
				'right bottom' => esc_html__('Right Bottom', 'xstore'),
			),
			'levels' => array(0,1,2)
		),

		array(
			'id' => 'item_visibility',
			'type' => 'select_default',
			'title' => esc_html__('Menu item is shown only for:', 'xstore'),
			'width' => 'wide',
			'options' => array(
				'all' => esc_html__('All users', 'xstore'),
				'logged' => esc_html__('Logged in users', 'xstore'),
				'unlogged' => esc_html__('Unlogged users', 'xstore'),
			),
		),
	);
}