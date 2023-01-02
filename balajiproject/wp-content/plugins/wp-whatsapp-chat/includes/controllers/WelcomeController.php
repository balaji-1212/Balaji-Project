<?php

class QLWAPP_Welcome_Controller {

	protected static $instance;

	function add_menu() {
		add_menu_page( QLWAPP_PLUGIN_NAME, QLWAPP_PLUGIN_NAME, 'edit_posts', QLWAPP_DOMAIN, array( $this, 'add_panel' ), 'dashicons-whatsapp' );

		add_submenu_page( QLWAPP_DOMAIN, esc_html__( 'Welcome', 'wp-whatsapp-chat' ), esc_html__( 'Welcome', 'wp-whatsapp-chat' ), 'edit_posts', QLWAPP_DOMAIN, array( $this, 'add_panel' ) );
	}

	function add_panel() {
		global $submenu;
		include QLWAPP_PLUGIN_DIR . '/includes/view/backend/pages/parts/header.php';
		include QLWAPP_PLUGIN_DIR . '/includes/view/backend/pages/welcome.php';
	}

	function init() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}
		return self::$instance;
	}

}

QLWAPP_Welcome_Controller::instance();
