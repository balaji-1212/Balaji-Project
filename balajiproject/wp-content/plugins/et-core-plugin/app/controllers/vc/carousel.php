<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Carousel shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Carousel extends VC {

	function hooks(){
        $this->register_custom_carousel();
    }

    function register_custom_carousel() {

        $strings = $this->etheme_vc_shortcodes_strings();
        $counter = 0;
        $params = array(
            'name' => 'Custom Carousel',
            'base' => 'etheme_carousel',
            'icon' => ETHEME_CODE_IMAGES . 'vc/Custom-carousel.png',
            'description' => esc_html__('Display slider with elements (text, image, banner or products) ', 'xstore-core'),
            'content_element' => true,
            'is_container' => true,
            'js_view' => 'VcColumnView',
            'as_parent' => array(
                'only' => 'banner,vc_column_text,et_category,vc_single_image',
            ),
            'category' => $strings['category'],
            'params' => array_merge(array(
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Advanced', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
                array(
                    'type' => 'xstore_button_set',
                    'heading' => esc_html__('Add space between slides', 'xstore-core'),
                    'param_name' => 'space',
                    'value' => array( 
                        esc_html__('Yes', 'xstore-core') => 'yes',
                        esc_html__('No', 'xstore-core') => 'no',
                    )
                ),
                array(
                    'type' => 'textfield',
                    'heading' => $strings['heading']['el_class'],
                    'param_name' => 'el_class'
                ),
            ), $this->get_slider_params())
        );

        vc_map($params);
    }

}
