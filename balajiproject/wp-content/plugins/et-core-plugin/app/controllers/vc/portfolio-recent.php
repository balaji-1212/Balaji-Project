<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Portfolio Recent shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Portfolio_Recent extends VC {

	function hooks() {
		$this->register_vc_portfolio_recent();
	}
	
	function register_vc_portfolio_recent() {

		$strings = $this->etheme_vc_shortcodes_strings();
		$counter = 0;
		$params = array(
			'name' => 'Recent Portfolio',
			'base' => 'portfolio_recent',
			'icon' => ETHEME_CODE_IMAGES . 'vc/Portfolio.png',
			'category' => $strings['category'],
			'description' => esc_html__('Display portfolio projects by date', 'xstore-core'),
			'params' => array(
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Title', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'textfield',
					'param_name' => 'title',
				),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Data settings', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
				array(
					'type' => 'textfield',
					'heading' => esc_html__('Limit', 'xstore-core'),
					'param_name' => 'limit',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__('Columns', 'xstore-core'),
					'param_name' => 'columns',
					'value' => array(
						'',
						2,
						3,
						4,
						5,
						6
					)
				),
				array(
					'type' => 'xstore_button_set',
					'heading' => esc_html__('Order way', 'xstore-core'),
					'param_name' => 'order',
					'value' => array(
						esc_html__( 'Descending', 'xstore-core' ) => 'DESC',
						esc_html__( 'Ascending', 'xstore-core' )  => 'ASC',
					),
				),
			),
		);  
		vc_map($params);
	}
}
