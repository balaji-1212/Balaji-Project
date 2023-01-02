<?php

namespace cmb2_tabs\inc;

class Assets {

	public function __construct() {
		// on testing now from 8.3.5 @todo check if ok and remove comments
		// there are no logic to load all options everywhere and start some functions
		// do it only if edit action is started
		if ( !isset($_GET['action']) || $_GET['action'] != 'edit') return;
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
	}


	public function admin_assets() {
		// Css
		wp_enqueue_style( 'dtheme-cmb2-tabs', plugin_dir_url( __DIR__ ) . '/assets/css/cmb2-tabs.css', array(), '1.0.1' );

		// Js
		wp_enqueue_script( 'jquery-ui' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'dtheme-cmb2-tabs', plugin_dir_url( __DIR__ ) . '/assets/js/cmb2-tabs.js', array( 'jquery-ui-tabs' ) );
	}

}