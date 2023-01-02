<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Etheme Admin Panel Plugins Class.
 *
 *
 * @since   7.2.0
 * @version 1.0.0
 *
 */
class Email_builder{
	
	// ! Main construct/ add actions
	function __construct(){
	}
	
	public function et_email_builder_switch_default(){
		$_POST['value'] = $_POST['value'] == 'false' ? false : true;
		update_option( 'etheme_built_in_email_builder', $_POST['value']);
		die();
	}
	
	public function et_email_builder_switch_dev_mode_default(){
		$_POST['value'] = $_POST['value'] == 'false' ? false : true;
		update_option( 'etheme_built_in_email_builder_dev_mode', $_POST['value'], false);
		die();
	}
}

new Email_builder();