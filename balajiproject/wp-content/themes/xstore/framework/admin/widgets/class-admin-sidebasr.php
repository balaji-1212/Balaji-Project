<?php  if ( ! defined('ETHEME_FW')) exit('No direct script access allowed');

// **********************************************************************//
// ! Custom sidebars
// **********************************************************************//

class Etheme_Admin_Sidebars {
	// ! Just leave it here
	function __construct() {
		global $wp_version;

		add_action( 'sidebar_admin_page',  array($this,'global_scripts'), 20 );
		if (
			version_compare( $wp_version, '5.8', '>=' )
			&& apply_filters( 'gutenberg_use_widgets_block_editor', true )
			&& apply_filters( 'use_widgets_block_editor', true )
		){
			add_action( 'sidebar_admin_page',  array($this,'scripts'), 30 );
			add_action( 'wp_ajax_et_ajax_widgets_form', array($this, 'ajax_widgets_form') );
		} else {
			add_action( 'sidebar_admin_page',  array($this,'old_form'), 30 );
		}
		add_action('wp_ajax_etheme_add_sidebar', array($this,'etheme_add_sidebar_action'));
		add_action('wp_ajax_etheme_delete_sidebar', array($this,'etheme_delete_sidebar'));
	}

	public function etheme_add_sidebar_action(){
		if (!wp_verify_nonce($_GET['_wpnonce_etheme_widgets'],'etheme-add-sidebar-widgets') ) die( 'Security check' );
		if($_GET['etheme_sidebar_name'] == '') die('Empty Name');
		$option_name = 'etheme_custom_sidebars';
		if(!get_option($option_name) || get_option($option_name) == '') delete_option($option_name);

		$new_sidebar = $_GET['etheme_sidebar_name'];

		$result = etheme_add_sidebar($new_sidebar);

		if($result) die($result);
		else die('error');
	}

	public function etheme_delete_sidebar(){
		$option_name = 'etheme_custom_sidebars';
		$del_sidebar = trim($_GET['etheme_sidebar_name']);

		if(get_option($option_name)) {
			$et_custom_sidebars = etheme_get_stored_sidebar();
			foreach($et_custom_sidebars as $key => $value){
				if($value == $del_sidebar)
					unset($et_custom_sidebars[$key]);
			}

			$result = update_option($option_name, $et_custom_sidebars);
		}

		if($result) die('Deleted');
		else die('error');
	}

	public function scripts(){
		echo get_template_part( 'framework/admin/widgets/templates/scripts' );
	}

	public function global_scripts() {
		echo get_template_part( 'framework/admin/widgets/templates/scripts', 'global' );
	}

	public function ajax_widgets_form(){
		wp_send_json($this->widgets_form());
    }

	public function old_form(){
	    echo $this->widgets_form();
		echo get_template_part( 'framework/admin/widgets/templates/scripts', 'old' );
    }

	public function widgets_form(){
		ob_start();
		echo get_template_part( 'framework/admin/widgets/templates/form', 'new-area' );
		return ob_get_clean();
	}
}

new Etheme_Admin_Sidebars;