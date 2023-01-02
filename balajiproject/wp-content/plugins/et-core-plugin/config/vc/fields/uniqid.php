<?php if ( ! defined( 'ET_CORE_DIR' ) ) exit( 'No direct script access allowed' );

/**
* Xstore unique id 
*/
if ( ! function_exists( 'xstore_add_uniqid_param' ) ) {
	function xstore_add_uniqid_param( $settings, $value ) {
		// if ( !$value ) {
			$value = 'et_custom_uniqid_new_' . uniqid();
		// }
		$output = '<input type="text" class="wpb_vc_param_value wpb-textinput hidden ' . $settings['param_name'] . ' textfield" name="' . $settings['param_name'] . '" value="' . esc_attr( $value ) . '" />';

		return $output;
    }
    
}
