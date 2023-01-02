<?php

include_once(QLWAPP_PLUGIN_DIR . 'includes/models/Box.php');

include_once(QLWAPP_PLUGIN_DIR . 'includes/controllers/QLWAPP_Controller.php');

class QLWAPP_Box_Controller extends QLWAPP_Controller {

  protected static $instance;

  function add_menu() {
    add_submenu_page(QLWAPP_DOMAIN, esc_html__('Box', 'wp-whatsapp-chat'), esc_html__('Box', 'wp-whatsapp-chat'), 'manage_options', QLWAPP_DOMAIN . '_box', array($this, 'add_panel'));
  }

  function add_panel() {
    global $submenu;
    $box_model = new QLWAPP_Box();
    $box = $box_model->get();
    $button_model = new QLWAPP_Button();
    $button = $button_model->get();
    include (QLWAPP_PLUGIN_DIR . '/includes/view/backend/pages/parts/header.php');
    include (QLWAPP_PLUGIN_DIR . '/includes/view/backend/pages/box.php');
  }

  function init() {
    add_action('wp_ajax_qlwapp_save_box', array($this, 'ajax_qlwapp_save_box'));
    add_action('admin_menu', array($this, 'add_menu'));
  }

  public function ajax_qlwapp_save_box() {
    $box_model = new QLWAPP_Box();
    if (current_user_can('manage_options')) {
      if (check_ajax_referer('qlwapp_save_box', 'nonce', false) && isset($_REQUEST['form_data'])) {
        $form_data = array();
        parse_str($_REQUEST['form_data'], $form_data);
        if (is_array($form_data)) {
          $box_model->save($form_data);
          return parent::success_save($form_data);
        }
        return parent::error_reload_page();
      }
      return parent::error_access_denied();
    }
  }

  public static function instance() {
    if (!isset(self::$instance)) {
      self::$instance = new self();
      self::$instance->init();
    }
    return self::$instance;
  }

}

QLWAPP_Box_Controller::instance();
