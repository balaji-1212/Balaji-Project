<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Scroll Text Item shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Scroll_Text_Item extends VC {

	function hooks() {
		$this->register_scroll_text_item();
	}

	function register_scroll_text_item() {

		$strings = $this->etheme_vc_shortcodes_strings();
		$counter = 0;
		vc_map(array(
			'name' => 'Scroll Text Content',
			'base' => 'etheme_scroll_text_item',
			'category' => $strings['category'],
			'content_element' => true,
			'icon' => ETHEME_CODE_IMAGES . 'vc/Autoscrolling-text.png',
			'description' => esc_html__('Display item promo texts', 'xstore-core'),
			'as_child' => array('only' => 'etheme_scroll_text'),            
			'is_container' => false,
			'params' => array ( 
		        array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Content', 'xstore-core' ),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'textarea',
					'holder' => 'div',
					'heading' => 'Text',
					'param_name' => 'content',
					'value' => 'Lorem ipsum dolor ...'
				),
	 	       array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Tooltip / button settings', 'xstore-core' ),
		            'param_name' => 'divider'.$counter++
		        ),
				array (
					'type' => 'checkbox',
					'heading' => esc_html__('Use tooltip instead of link', 'xstore-core'),
					'param_name' => 'tooltip',
				),
				array (
					'type' => 'textfield',
					'heading' => esc_html__('Tooltip title', 'xstore-core'),
					'param_name' => 'tooltip_title',
					'dependency' =>  array('element' => 'tooltip', 'value' => 'true' ),
				),
				array (
					'type' => 'textarea',
					'heading' => esc_html__('Tooltip content', 'xstore-core'),
					'param_name' => 'tooltip_content',
					'dependency' =>  array('element' => 'tooltip', 'value' => 'true' ),
				),
				array (
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Tooltip content position', 'xstore-core'),
					'param_name' => 'tooltip_content_pos',
					'value' => array (
						'Bottom' => 'bottom',
						'Top' => 'top',
					),
					'dependency' =>  array('element' => 'tooltip', 'value' => 'true' ),
				),
				array(
					'type' => 'vc_link',
					'heading' => esc_html__('Button link', 'xstore-core'),
					'param_name' => 'button_link',
					'dependency' =>  array('element' => 'tooltip', 'value_not_equal_to' => 'true' ),
				),
		        array(
		            'type' => 'xstore_title_divider',
		            'title' => esc_html__( 'Advanced', 'xstore-core' ),
		            'param_name' => 'divider'.$counter++
		        ),
				array(
					'type' => 'textfield',
					'heading' => $strings['heading']['el_class'],
					'param_name' => 'el_class',
					'hint' => $strings['hint']['el_class']
				),
			),
		)
	);
	}

}
