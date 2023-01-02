<?php
namespace ETC\App\Controllers\Vc;

use ETC\App\Controllers\VC;

/**
 * Scroll Text shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/VC
 */
class Scroll_Text extends VC {

    function hooks() {
        $this->register_scroll_text();
    }

    function register_scroll_text() {

        $strings = $this->etheme_vc_shortcodes_strings();
        $counter = 0;
        $params = array(
            'name' => 'Autoscrolling Text',
            'base' => 'etheme_scroll_text',
            'icon' => ETHEME_CODE_IMAGES . 'vc/Autoscrolling-text.png',
            'description' => esc_html__('Display slider with promo texts', 'xstore-core'),
            'content_element' => true,
            'is_container' => true,
            'js_view' => 'VcColumnView',
            'show_settings_on_create' => true,
            'as_parent' => array(
                'only' => 'etheme_scroll_text_item',
            ),
            'category' => $strings['category'],
            'params' => array(
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Style', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Custom height value', 'xstore-core'),
                    'hint' => esc_html__('Enter height value with dimensions for ex. 30px', 'xstore-core'),
                    'param_name' => 'height_value',
                ),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Slider settings', 'xstore-core' ),
                    'param_name' => 'divider'.$counter++
                ),
                array(
                    'type' => 'xstore_button_set',
                    'heading' => esc_html__('Transition style', 'xstore-core'),
                    'param_name' => 'transition_effect',
                    'value' => array(
                        esc_html__('Slide', 'xstore-core') => 'slide',
                        esc_html__('Fade', 'xstore-core') => 'fade',
                        esc_html__('Cube', 'xstore-core') => 'cube',
                        esc_html__('Coverflow', 'xstore-core') => 'coverflow',
                        esc_html__('Flip', 'xstore-core') => 'flip',
                    )
                ),
                array(
                    'type' => 'textfield',
                    'heading' => esc_html__('Autoplay speed', 'xstore-core'),
                    'param_name' => 'slider_interval',
                    'hint' => esc_html__( 'Interval between slides. In milliseconds. Default: 7000', 'xstore-core' ),
                ),
                array(
                    'type' => 'xstore_title_divider',
                    'title' => esc_html__( 'Colors', 'xstore-core' ),
                    'group' => esc_html__('Design', 'xstore-core'),
                    'param_name' => 'divider'.$counter++
                ),
                array (
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Color', 'xstore-core'),
                    'param_name' => 'color',
                    'group' => esc_html__('Design', 'xstore-core'),
                    'value' => '#ffffff',
                    'edit_field_class' => 'vc_col-md-4 vc_col-xs-6 vc_column',
                ),
                array (
                    'type' => 'colorpicker',
                    'heading' => esc_html__('Background color', 'xstore-core'),
                    'param_name' => 'bg_color',
                    'group' => esc_html__('Design', 'xstore-core'),
                    'value' => '#222',
                    'edit_field_class' => 'vc_col-md-4 vc_col-xs-6 vc_column',
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
        );

        vc_map($params);
    }

}
