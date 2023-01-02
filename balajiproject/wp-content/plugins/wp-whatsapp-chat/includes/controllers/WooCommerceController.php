<?php

require_once QLWAPP_PLUGIN_DIR . 'includes/models/WooCommerce.php';

require_once QLWAPP_PLUGIN_DIR . 'includes/controllers/QLWAPP_Controller.php';

class QLWAPP_WooCommerce_Controller extends QLWAPP_Controller {

	protected static $instance;

	function add_menu() {
		if ( class_exists( 'WooCommerce' ) ) {
			add_submenu_page( QLWAPP_DOMAIN, esc_html__( 'WooCommerce (Beta)', 'wp-whatsapp-chat' ), esc_html__( 'WooCommerce (Beta)', 'wp-whatsapp-chat' ), 'manage_options', QLWAPP_DOMAIN . '_woocommerce', array( $this, 'add_panel' ) );
		}
	}

	function woocommerce_init() {
		if ( class_exists( 'WooCommerce' ) ) {
			$woocommerce_model = new QLWAPP_WooCommerce();
			$woocommerce       = $woocommerce_model->get();

			$position          = (string) $woocommerce['position'];
			$position_priority = (int) $woocommerce['position_priority'];

			// Add Product Button.
			if ( is_product() && 'none' !== $position ) {
				add_action( $position, array( $this, 'product_button' ), $position_priority );
			}
		}
	}

	function product_button( $product ) {
		global $qlwapp;

		$obj = get_queried_object();

		$product = wc_get_product( $obj->ID );

		if ( is_file( $file = apply_filters( 'qlwapp_box_template', QLWAPP_PLUGIN_DIR . 'template/box.php' ) ) ) {
			include_once QLWAPP_PLUGIN_DIR . 'includes/models/Box.php';
			include_once QLWAPP_PLUGIN_DIR . 'includes/models/Contact.php';
			include_once QLWAPP_PLUGIN_DIR . 'includes/models/Display.php';
			include_once QLWAPP_PLUGIN_DIR . 'includes/models/WooCommerce.php';

			$box_model         = new QLWAPP_Box();
			$contact_model     = new QLWAPP_Contact();
			$woocommerce_model = new QLWAPP_WooCommerce();
			$display_model     = new QLWAPP_Display();
			$display_service   = new QLWAPP_DisplayServices_Controller();

			$contacts = $contact_model->get_contacts_reorder();
			$display  = $display_model->get();
			$button   = $woocommerce_model->get();
			$box      = $box_model->get();

			include_once $file;
		}
	}

	function add_panel() {

		global $submenu;

		$woocommerce_model = new QLWAPP_WooCommerce();
		$woocommerce       = $woocommerce_model->get();

		$positions = array(
			'none'                                       => esc_html__( 'None', 'wp-whatsapp-chat' ),
			'woocommerce_before_add_to_cart_form'        => esc_html__( 'Before "Add To Cart" form', 'wp-whatsapp-chat' ),
			'woocommerce_before_add_to_cart_button'      => esc_html__( 'Before "Add To Cart" button', 'wp-whatsapp-chat' ),
			'woocommerce_after_add_to_cart_button'       => esc_html__( 'After "Add To Cart" button', 'wp-whatsapp-chat' ),
			'woocommerce_after_add_to_cart_form'         => esc_html__( 'After "Add To Cart" form', 'wp-whatsapp-chat' ),
			'woocommerce_product_additional_information' => esc_html__( 'After "Additional information"', 'wp-whatsapp-chat' ),
		);

		include QLWAPP_PLUGIN_DIR . '/includes/view/backend/pages/parts/header.php';
		include QLWAPP_PLUGIN_DIR . '/includes/view/backend/pages/woocommerce.php';
	}

	function init() {
		add_action( 'wp_ajax_qlwapp_save_woocommerce', array( $this, 'ajax_qlwapp_save_woocommerce' ) );
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'wp', array( $this, 'woocommerce_init' ) );
	}

	public function ajax_qlwapp_save_woocommerce() {
		$woocommerce_model = new QLWAPP_WooCommerce();
		if ( current_user_can( 'manage_options' ) ) {
			if ( check_ajax_referer( 'qlwapp_save_woocommerce', 'nonce', false ) && isset( $_REQUEST['form_data'] ) ) {
				$form_data = array();
				parse_str( $_REQUEST['form_data'], $form_data );
				if ( is_array( $form_data ) ) {
					$woocommerce_model->save( $form_data );
					return parent::success_save( $form_data );
				}
				return parent::error_reload_page();
			}
			return parent::error_access_denied();
		}
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
			self::$instance->init();
		}
		return self::$instance;
	}

}

QLWAPP_WooCommerce_Controller::instance();
