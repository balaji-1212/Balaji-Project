<?php if ( ! defined( 'ET_CORE_DIR' ) ) exit( 'No direct script access allowed' );

/**
* xstore responsive size param
*/
if ( ! function_exists( 'xstore_add_responsive_size_param' ) ) {
	function xstore_add_responsive_size_param( $settings, $value ) {

        $units_default = array( 'px', 'em', 'rem', 'vw', 'vh', '%' );

        if ( isset($settings['empty_units']) && $settings['empty_units'] ) 
            $units_default[''] = '';

        $units = !isset($settings['units']) ? $units_default : array();
        $output = '<div class="xstore-rs-wrapper ' . esc_attr( $settings['param_name'] ) . '">'; 
            $output .= '<div class="xstore-rs-item desktop">';
                $output .= '<span class="xstore-rs-icon mtips mtips-top"><i class="dashicons dashicons-desktop"></i><span class="mt-mes">'.esc_attr('Global', 'xstore-core').'</span></span>';
                $output .= '<input type="number" min="1" class="xstore-rs-input" data-id="desktop">';
            $output .= '</div>';
            $output .= '<div class="xstore-rs-trigger"><i class="dashicons dashicons-arrow-right-alt2"></i></div>';

            $output .= '<div class="xstore-rs-item tablet hidden">';
                $output .= '<span class="xstore-rs-icon mtips mtips-top"><i class="dashicons dashicons-tablet"></i><span class="mt-mes">'.esc_attr('Tablet', 'xstore-core').'</span></span>';
                $output .= '<input type="number" min="1" class="xstore-rs-input" data-id="tablet">';
            $output .= '</div>';

            $output .= '<div class="xstore-rs-item mobile hidden">';
                $output .= '<span class="xstore-rs-icon mtips mtips-top"><i class="dashicons dashicons-smartphone"></i><span class="mt-mes">'.esc_attr('Mobile', 'xstore-core').'</span></span>';
                $output .= '<input type="number" min="1" class="xstore-rs-input" data-id="mobile">';
            $output .= '</div>';

            $output .= '<div class="xstore-rs-unit">';
            if ( is_array($units) && count($units) ) {
                $output .= '<select>';
                    foreach ($units as $key) {
                        $output .= '<option>'.$key.'</option>';
                    }
                $output .= '</select>';
            }
            $output .= '</div>';

            $output .= '<input type="hidden" name="' . esc_attr( $settings['param_name'] ) . '" class="wpb_vc_param_value xstore-rs-value" value="' . esc_attr( $value ) . '">';
        $output .= '</div>';

	    return $output;
    }
    
}
