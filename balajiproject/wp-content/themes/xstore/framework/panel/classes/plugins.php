<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Etheme Admin Panel Plugins Class.
 *
 *
 * @since   7.1.0
 * @version 1.0.1
 * @log 1.0.1
 * ADDED: ET_CORE_THEME_MIN_VERSION
 */
class Plugins{
	/**
	 * theme name
	 *
	 * @var string
	 */
	protected $theme_name;
	/**
	 * TGMPA instance storage
	 *
	 * @var object
	 */
	protected $tgmpa_instance;

	/**
	 * TGMPA Menu slug
	 *
	 * @var string
	 */
	protected $tgmpa_menu_slug = 'tgmpa-install-plugins';

	/**
	 * TGMPA Menu url
	 *
	 * @var string
	 */
	protected $tgmpa_url = 'themes.php?page=tgmpa-install-plugins';

	// ! Main construct/ add actions
	function __construct(){
		if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
			add_action( 'init', array( $this, 'get_tgmpa_instanse' ), 30 );
			add_action( 'init', array( $this, 'set_tgmpa_url' ), 40 );
		}
		add_action( 'wp_ajax_et_plugins_actions', array($this, 'et_plugins_actions') );
		add_action( 'wp_ajax_et_delete_transient', array($this, 'et_delete_transient') );
		add_filter( 'tgmpa_load', array( $this, 'tgmpa_load' ), 10, 1 );
		add_action( 'wp_ajax_envato_setup_plugins', array( $this, 'ajax_plugins' ) );
	}

	/**
	 * Delete transient by ajax
	 *
	 * @version  1.0.0
	 * @since  6.3.4
	 */
	public function et_delete_transient() {
		if (delete_transient($_POST['transient'])) {
			wp_send_json('success');
		}
	}

	/**
	 * Plugins page actions
	 *
	 * @version  1.0.0
	 * @since  6.3.4
	 */
	public function et_plugins_actions() {
		check_admin_referer( 'envato_setup_nonce', 'wpnonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json('no access ');
		}
		if ( isset($_POST['type']) && $_POST['type'] === 'deactivate'  ) { // phpcs:ignore WordPress.Security

			$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );

			foreach ( $instance->plugins as $plugin ) {
				if ( $plugin['slug'] === $_POST['slug'] ) {
					deactivate_plugins( $plugin['file_path'] );
				}
			}
		}
		wp_send_json('success');
	}

	/**
	 * is tgmpa load
	 *
	 * @version  1.0.0
	 * @since  6.3.4
	 */
	public function tgmpa_load() {
		return is_admin() || current_user_can( 'install_themes' );
	}

	/**
	 * Get configured TGMPA instance
	 *
	 * @access public
	 * @since 1.1.2
	 */
	public function get_tgmpa_instanse() {
		$this->tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
	}

	/**
	 * Update $tgmpa_menu_slug and $tgmpa_parent_slug from TGMPA instance
	 *
	 * @access public
	 * @since 1.1.2
	 */
	public function set_tgmpa_url() {
		$this->tgmpa_menu_slug = ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
		$this->tgmpa_menu_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug );
		$tgmpa_parent_slug = ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && $this->tgmpa_instance->parent_slug !== 'themes.php' ) ? 'admin.php' : 'themes.php';
		$this->tgmpa_url = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_url', $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug );
	}


	public function _get_plugins( $instance = false ) {
		if ( ! $instance){
			$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		}
		$plugins  = array(
			'all'      => array(), // Meaning: all plugins which still have open actions.
			'install'  => array(),
			'update'   => array(),
			'activate' => array(),
		);

		foreach ( $instance->plugins as $slug => $plugin ) {
			$new_is_plugin_active = (
				( ! empty( $instance->plugins[ $slug ]['is_callable'] ) && is_callable( $instance->plugins[ $slug ]['is_callable'] ) )
				|| in_array( $instance->plugins[ $slug ]['file_path'], (array) get_option( 'active_plugins', array() ) ) || is_plugin_active_for_network( $instance->plugins[ $slug ]['file_path'] )
			);

			if ( $new_is_plugin_active && false === $instance->does_plugin_have_update( $slug ) ) {
				// No need to display plugins if they are installed, up-to-date and active.
				continue;
			} else {
				$plugins['all'][ $slug ] = $plugin;

				if ( ! $instance->is_plugin_installed( $slug ) ) {
					$plugins['install'][ $slug ] = $plugin;
				} else {
					if ( false !== $instance->does_plugin_have_update( $slug ) ) {
						$plugins['update'][ $slug ] = $plugin;
					}

					if ( $instance->can_plugin_activate( $slug ) ) {
						$plugins['activate'][ $slug ] = $plugin;
					}
				}
			}
		}
		return $plugins;
	}

	/**
	 * Plugin actions called by ajax
	 *
	 * @version  1.0.1
	 * @since  6.3.4
	 * @loa 1.0.1
	 * ADDED: ET_CORE_THEME_MIN_VERSION
	 *
	 */
	public function ajax_plugins() {
		if ( ! check_ajax_referer( 'envato_setup_nonce', 'wpnonce' ) || empty( $_POST['slug'] ) ) {
			wp_send_json_error( array( 'error' => 1, 'message' => esc_html__( 'No Slug Found', 'xstore' ) ) );
		}
		$json = array();
		// send back some json we use to hit up TGM
		$plugins = $this->_get_plugins();

		// what are we doing with this plugin?
		foreach ( $plugins['activate'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-activate',
					'action2'       => - 1,
					'message'       => esc_html__( 'Activating Plugin', 'xstore' ),
				);
				break;
			}
		}
		foreach ( $plugins['update'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-update',
					'action2'       => - 1,
					'message'       => esc_html__( 'Updating Plugin', 'xstore' ),
				);
				break;
			}
		}
		foreach ( $plugins['install'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-install',
					'action2'       => - 1,
					'message'       => esc_html__( 'Installing Plugin', 'xstore' ),
				);
				break;
			}
		}
		if ( $json ) {
			$json['hash'] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin
			wp_send_json( $json );
		} else {
			$array = array(
				'done' => 1,
				'slug' => $_POST['slug'],
				'message' => esc_html__( 'Success', 'xstore' )
			);
			if ( $_POST['slug'] = 'et-core-plugin'){
				if ( get_template_directory() !== get_stylesheet_directory() ) {
					$theme = wp_get_theme( 'xstore' );
				} else {
					$theme = wp_get_theme();
				}
				$array['ET_CORE_THEME_MIN_VERSION'] =  ET_CORE_THEME_MIN_VERSION;
				$array['ET_CURRENT_THEME_VERSION']  =  $theme->version;
				$array['ET_VERSION_COMPARE'] = version_compare( $theme->version, ET_CORE_THEME_MIN_VERSION, '<' );

			}
			wp_send_json($array);
		}
		exit;
	}

	/**
	 * Plugin actions called by ajax
	 *
	 * @version  1.0.0
	 * @since  6.3.4
	 * @param string $version the demo version name
	 * @return array plugins list
	 */
	public function get_popup_plugin_list($version){
		$versions = etheme_get_demo_versions();
		$version = $versions[$version];
		$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		$plugins  = array(
			'all'      => array(), // Meaning: all plugins which still have open actions.
			'install'  => array(),
			'update'   => array(),
			'activate' => array(),
		);
		foreach ( $instance->plugins as $slug => $plugin ) {
			$new_is_plugin_active = (
				( ! empty( $instance->plugins[ $slug ]['is_callable'] ) && is_callable( $instance->plugins[ $slug ]['is_callable'] ) )
				|| in_array( $instance->plugins[ $slug ]['file_path'], (array) get_option( 'active_plugins', array() ) ) || is_plugin_active_for_network( $instance->plugins[ $slug ]['file_path'] )
			);

//			if ( $new_is_plugin_active && false === $instance->does_plugin_have_update( $slug ) ) {
			if ( $new_is_plugin_active && ! $instance->does_plugin_have_update( $slug )) {
				// No need to display plugins if they are installed, up-to-date and active.
				continue;
			} else {
				$plugins['all'][ $slug ] = $plugin;

				if ( ! $instance->is_plugin_installed( $slug ) ) {
					$plugins['install'][ $slug ] = $plugin;
				} else {
					if ( false !== $instance->does_plugin_have_update( $slug ) ) {
						$plugins['update'][ $slug ] = $plugin;
						// unset($plugins['all'][ $slug ]);
					}

					if ( $instance->can_plugin_activate( $slug ) ) {
						$plugins['activate'][ $slug ] = $plugin;
					}
				}
			}
		}

		$required = array_filter($plugins['all'], function($el) {
			return $el['required'];
		});

		$to_show = array();
		foreach ( $version['plugins'] as $item ) {
			if (isset($plugins['all'][$item]) && $plugins['all'][$item]){
				$to_show[$item]=$plugins['all'][$item];
			}
		}

		$return = array_merge($required,$to_show);

		foreach ($return as $key => $value) {
			if ( array_key_exists($key, $plugins['install']) ) {
				$return[$key]['btn_text'] = esc_html__( 'Install', 'xstore' );
				$return[$key]['btn_type'] = 'install';
			} else if( array_key_exists($key, $plugins['activate']) ){
				$return[$key]['btn_text'] = esc_html__( 'Activate', 'xstore' );
				$return[$key]['btn_type'] = 'activate';
			} else if(array_key_exists($key, $plugins['update'])){
				$return[$key]['btn_text'] = esc_html__( 'Update', 'xstore' );
				$return[$key]['btn_type'] = 'update';
//				unset($return[$key]);
			}
		}

		return $return;
	}
}

new Plugins();