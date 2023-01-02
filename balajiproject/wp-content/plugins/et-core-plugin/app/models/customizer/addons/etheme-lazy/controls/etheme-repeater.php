<?php
/**
 * Customizer Control: repeater.
 *
 * @package     Kirki
 * @subpackage  Controls
 * @copyright   Copyright (c) 2020, David Vongries
 * @license     https://opensource.org/licenses/MIT
 * @since       2.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Repeater control
 */
class Kirki_Control_Etheme_Repeater extends Kirki_Control_Repeater {

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @access public
	 */
	public function enqueue() {
		// parent::enqueue();
		wp_enqueue_script(
		    'et-core-etheme-repeater',
		    ET_CORE_URL . 'app/models/customizer/frontend/js/repeater.js',
		    array( 
		    	'jquery',
		    	'customize-base',
		    	'wp-color-picker-alpha',
		    	'selectWoo',
		    	'jquery-ui-button',
		    	'jquery-ui-datepicker',
		    ),
		    ET_CORE_VERSION,
		    true
		);
	}

}
