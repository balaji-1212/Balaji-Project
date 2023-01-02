<?php if ( ! defined( 'ET_CORE_DIR' ) ) exit( 'No direct script access allowed' );

/**
* Button set
*/
if ( ! function_exists( 'xstore_add_button_set_param' ) ) {
	function xstore_add_button_set_param( $settings, $value ) {
		$value = ( $value == '' && isset( $settings['default'] ) ) ? $settings['default'] : $value;
        $tooltip = isset($settings['et_tooltip']) && $settings['et_tooltip'];
        $tooltip_values = isset($settings['tooltip_values']) ? $settings['tooltip_values'] : array();

        if ( count($tooltip_values) ) 
            $tooltip = true;

        $output = '<div class="xstore-vc-button-set">';
            $output .= '<input type="hidden" class="xstore-vc-button-set-value wpb_vc_param_value" name="' . esc_attr( $settings['param_name'] ) . '" value="' . esc_attr( $value ) . '">';
            $output .= '<ul class="xstore-vc-button-set-list">';
                foreach ( $settings['value'] as $key => $val ) {
                    $output .= '<li class="vc-button-set-item' . ( $tooltip ? ' mtips mtips-top' : '') . ( $value == $val ? ' active' : '' ) . '" data-value="' . esc_html( $val ) . '">';
                        $output .= '<span>' . $key . '</span>';
                        if ( $tooltip ) {
                            $tooltip_value = $key;
                            if ( count($tooltip_values) && isset($tooltip_values[$val]) ) $tooltip_value = $tooltip_values[$val];
                            $output .= '<span class="mt-mes">' . $tooltip_value . '</span>';
                        }
                    $output .= '</li>';
                }
            $output .= '</ul>';
        $output .= '</div>';

        return $output;
    }

}
