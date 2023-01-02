<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Portfolio shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Portfolio extends VC {

	function hooks() {
        $this->register_vc_portfolio();
    }

    function register_vc_portfolio() {

        $strings = $this->etheme_vc_shortcodes_strings();
        $counter = 0;
        $params = array(
            'name' => 'Portfolio',
            'base' => 'portfolio',
            'category' => $strings['category'],
            'icon' => ETHEME_CODE_IMAGES . 'vc/Portfolio.png',
            'description' => esc_html__('Display portfolio projects with filters', 'xstore-core'),
            'params' => array(
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
            ),
        );  
        vc_map($params);
    }
}
