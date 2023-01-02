<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Etheme Admin Create and witch child theme.
 *
 *
 * @since   7.1.0
 * @version 1.0.0
 */
class ChildTheme{
	protected $theme_name;
	function __construct(){
		add_action( 'wp_ajax_et_create_child_theme', array($this, 'et_create_child_theme') );
		$current_theme         = wp_get_theme();
		$this->theme_name      = strtolower( preg_replace( '#[^a-zA-Z]#', '', $current_theme->get( 'Name' ) ) );
	}
	public function et_create_child_theme(){
		// don't remove any variables because they are used in child-theme-css.php and maybe somewhere else
		$parent_theme_title = 'XStore';
		$parent_theme_template = 'xstore';
		$parent_theme_name = get_stylesheet();
		$parent_theme_dir = get_stylesheet_directory();

		// isset($_REQUEST['theme_name']) && isset($_REQUEST['theme_template']) && current_user_can('manage_options')

		$new_theme_title = $_POST['theme_name'];
		$new_theme_template = $_POST['theme_template'];

		// Turn a theme name into a directory name
		$new_theme_name = sanitize_title( $new_theme_title );
		$theme_root = get_theme_root();

		// Validate theme name
		$new_theme_path = $theme_root.'/'.$new_theme_name;
		if ( !file_exists( $new_theme_path ) ) {
			// Create Child theme
			wp_mkdir_p( $new_theme_path );

			$plugin_folder = get_template_directory().'/framework/thirdparty/child-theme/';

			// Make style.css
			ob_start();
			require $plugin_folder.'child-theme-css.php';
			$css = ob_get_clean();

			global $wp_filesystem;

			if ( empty( $wp_filesystem ) ) {
				require_once ( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
			}
			$wp_filesystem->put_contents( $new_theme_path.'/style.css', $css, FS_CHMOD_FILE );

			// Copy functions.php
			copy( $plugin_folder.'functions.php', $new_theme_path.'/functions.php' );

			// Copy screenshot
			copy( $plugin_folder.'screenshot.png', $new_theme_path.'/screenshot.png' );

			// Make child theme an allowed theme (network enable theme)
			$allowed_themes = get_site_option( 'allowedthemes' );
			$allowed_themes[ $new_theme_name ] = true;
			update_site_option( 'allowedthemes', $allowed_themes );
		}

		// Switch to theme
		if($parent_theme_template !== $new_theme_name){
			update_option('xstore_has_child', $new_theme_name);
			switch_theme( $new_theme_name, $new_theme_name );

			$response = array();
			$response['type'] = 'success';
			$response['new_theme_title'] = $new_theme_title;
			$response['new_theme_path'] = 'wp-content/themes/' . $new_theme_name;
		} else {
			$response['type'] = 'error';
		}
		wp_send_json($response);
	}
}

new ChildTheme();
