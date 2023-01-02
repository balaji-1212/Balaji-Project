<?php if ( ! defined( 'ET_CORE_DIR' ) ) exit( 'No direct script access allowed' );

/**
* Add image select
*/
if( ! function_exists( 'xstore_add_image_select_param' ) ) {
	function xstore_add_image_select_param( $settings, $value ) {
		$settings_value = array_flip( $settings['value'] );
		$value = ( ! $value && isset( $settings['std'] ) ) ? $settings['std'] : $value;
		$tooltip = isset($settings['et_tooltip']) && $settings['et_tooltip'];
        $tooltip_values = isset($settings['tooltip_values']) ? $settings['tooltip_values'] : array();
        
        if ( count($tooltip_values) ) 
            $tooltip = true;

		$output = '<ul class="xstore-vc-image-select">';
			$output .= '<input type="hidden" class="xstore-vc-image-select-input wpb_vc_param_value" name="' . esc_attr( $settings['param_name'] ) . '" value="' . esc_attr( $value ) . '">';
			foreach ( $settings['value'] as $key => $val ) {
				$output .= '<li data-value="' . esc_attr( $val ) . '" class="' . ( $tooltip ? 'mtips mtips-top' : '') . ( $value == $val ? ' active' : '' ) . '">';
				$output .= '<img src="' . esc_url( $settings['images_value'][$val] ) . '" alt="'. esc_attr( $settings_value[$val] ) .'">';
				if ( $tooltip ) {
                    $tooltip_value = $settings_value[$val];
                    if ( count($tooltip_values) && isset($tooltip_values[$val]) ) $tooltip_value = $tooltip_values[$val];
                    $output .= '<span class="mt-mes">' . $tooltip_value . '</span>';
                }
				$output .= '</li>';
			}
		$output .= '</ul>';

		return $output;
	}
}
