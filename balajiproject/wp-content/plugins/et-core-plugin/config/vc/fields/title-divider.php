<?php if ( ! defined( 'ET_CORE_DIR' ) ) exit( 'No direct script access allowed' );

/**
* Xstore title divider
*/
if ( ! function_exists( 'xstore_add_title_divider_param' ) ) {
	function xstore_add_title_divider_param( $settings, $value ) {
        $input = '<input id="' . esc_attr( $settings[ 'param_name' ] ) . '" class="wpb_vc_param_value" name="' . esc_attr( $settings[ 'param_name' ] ) . '" value="" type="hidden">';

        $extra_class = isset( $settings[ 'extra_class' ] ) ? $settings[ 'extra_class' ] : '';
        $title = isset( $settings[ 'title' ] ) ? '<div class="et_element_title '.$extra_class.'">' . $settings[ 'title' ] . '</div>' : '';
        $subtitle = isset( $settings[ 'subtitle' ] ) ? '<span class="et_element_subtitle">' . $settings[ 'subtitle' ] . '</span>' : '';

        return $input . $title . $subtitle;
    }
    
}
