<?php

class QLWAPP_Premium_Controller {

  protected static $instance;

  function add_menu() {
    add_submenu_page(QLWAPP_DOMAIN, esc_html__('Premium', 'wp-whatsapp-chat'), sprintf('%s <i class="dashicons dashicons-awards"></i>', esc_html__('Premium', 'wp-whatsapp-chat')), 'edit_posts', QLWAPP_DOMAIN . '_premium', array($this, 'add_panel'));
  }

  function add_panel() {
    global $submenu;
    include (QLWAPP_PLUGIN_DIR . '/includes/view/backend/pages/parts/header.php');
    include (QLWAPP_PLUGIN_DIR . '/includes/view/backend/pages/premium.php');
  }

  function init() {
    add_action('admin_menu', array($this, 'add_menu'));
  }

  public static function instance() {
    if (!isset(self::$instance)) {
      self::$instance = new self();
      self::$instance->init();
    }
    return self::$instance;
  }

}

QLWAPP_Premium_Controller::instance();
