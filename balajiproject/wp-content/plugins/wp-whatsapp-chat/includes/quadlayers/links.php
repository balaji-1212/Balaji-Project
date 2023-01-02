<?php

class QLWAPP_Admin_Links {

	protected static $_instance;

	function __construct() {
		add_filter( 'plugin_action_links_' . QLWAPP_PLUGIN_BASENAME, array( $this, 'add_action_links' ) );
	}

	public function add_action_links( $links ) {
		$links[] = '<a target="_blank" href="' . QLWAPP_PURCHASE_URL . '">' . esc_html__( 'Premium', 'wp-whatsapp-chat' ) . '</a>';
		$links[] = '<a target="_blank" href="' . QLWAPP_DOCUMENTATION_URL . '">' . esc_html__( 'Documentation', 'wp-whatsapp-chat' ) . '</a>';
		$links[] = '<a href="' . admin_url( 'admin.php?page=' . sanitize_title( QLWAPP_PREFIX ) ) . '">' . esc_html__( 'Settings', 'wp-whatsapp-chat' ) . '</a>';
		return $links;
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

}

QLWAPP_Admin_Links::instance();
