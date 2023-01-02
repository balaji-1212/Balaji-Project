<?php if ( ! defined( 'ET_CORE_DIR' ) ) exit( 'No direct script access allowed' );

/**
* Xstore slider param
*/
if ( ! function_exists( 'xstore_add_slider_param' ) ) {
	function xstore_add_slider_param( $settings, $value ) {
        $value = ! $value ? $settings['default'] : $value;
        $output = '<div class="xstore-vc-slider">';
            $output .= '<input type="range" id="' . esc_attr( $settings['param_name'] ) . '" class="xstore-slider-field-value wpb_vc_param_value" name="' . esc_attr( $settings['param_name'] ) . '" 
         min="' . esc_attr( $settings['min'] ) . '" max="' . esc_attr( $settings['max'] ) . '" value="' . esc_attr( $value ) . '" step="' . esc_attr( $settings['step'] ) . '">';
            $output .= '<span class="xstore-slider-field-value-display"><span class="xstore-slider-field-value">' . (float)esc_attr( $value ) . '</span><span class="xstore-slider-field-units">' . esc_attr( $settings['units'] ) . '</span>';
        $output .= '</div>';

        return $output;
    }
    
}
