<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Etheme Admin Panel Plugins Class.
 *
 *
 * @since   7.2.0
 * @version 1.0.0
 *
 */
class Maintenance_mode{
	
	// ! Main construct/ add actions
	function __construct(){
	}
	
	public function et_maintenance_mode_switch_default(){
		$_POST['value'] = $_POST['value'] == 'false' ? false : true;
		update_option( 'etheme_maintenance_mode', $_POST['value']);
		die();
	}
}

new Maintenance_mode();