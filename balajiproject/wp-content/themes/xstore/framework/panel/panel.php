<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Etheme Admin Panel Dashboard.
 *
 * Add admin panel dashboard pages to admin menu.
 * Output dashboard pages.
 *
 * @since   5.0.0
 * @version 1.0.8
 */

class EthemeAdmin{
	/**
	 * Theme name
	 *
	 * @var string
	 */
	protected $theme_name;
	
	/**
	 * Panel page
	 *
	 * @var array
	 */
	protected $page = array();
	
	protected $settingJsConfig = array();
	
	protected static $instance = null;
	
	// ! Main construct/ add actions
	public function main_construct(){
		add_action( 'admin_menu', array( $this, 'et_add_menu_page' ) );
		add_action( 'admin_head', array( $this, 'et_add_menu_page_target') );
		add_action( 'wp_ajax_et_ajax_panel_popup', array($this, 'et_ajax_panel_popup') );
		
        // Enable svg support
		add_filter( 'upload_mimes', [ $this, 'add_svg_support' ] );
		add_filter( 'wp_check_filetype_and_ext', array( $this, 'correct_svg_filetype' ), 10, 5 );
		
		if ( isset($_REQUEST['helper']) && $_REQUEST['helper']){
			$this->require_class($_REQUEST['helper']);
		}
		
		add_action( 'wp_ajax_et_panel_ajax', array($this, 'et_panel_ajax') );
		
		$current_theme         = wp_get_theme();
		$this->theme_name      = strtolower( preg_replace( '#[^a-zA-Z]#', '', $current_theme->get( 'Name' ) ) );
		
		add_action( 'admin_init', array( $this, 'admin_redirects' ), 30 );
		add_action('admin_init',array($this,'add_page_admin_script'), 1140);
		
		if(!is_child_theme()){
			add_action( 'after_switch_theme', array( $this, 'switch_theme' ) );
		}
		
		if ( ! $this->set_page_data() ){
			return;
		}
		
		if (isset($this->page['class']) && ! empty($this->page['class'])){
			$this->require_class($this->page['class']);
		}
		
		// Stas
		$this->init_vars();
	}
	
	public static function add_svg_support( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
	
	/**
	 * Correct SVG file uploads to make them pass the WP check.
	 *
	 * WP upload validation relies on the fileinfo PHP extension, which causes inconsistencies.
	 * E.g. json file type is application/json but is reported as text/plain.
	 * ref: https://core.trac.wordpress.org/ticket/45633
	 *
	 * @access public
	 * @since 4.3.4
	 * @param array       $data                      Values for the extension, mime type, and corrected filename.
	 * @param string      $file                      Full path to the file.
	 * @param string      $filename                  The name of the file (may differ from $file due to
	 *                                               $file being in a tmp directory).
	 * @param string[]    $mimes                     Array of mime types keyed by their file extension regex.
	 * @param string|bool $real_mime                 The actual mime type or false if the type cannot be determined.
	 *
	 * @return array
	 */
	public function correct_svg_filetype( $data, $file, $filename, $mimes, $real_mime = false ) {
		
		// If both ext and type are.
		if ( ! empty( $data['ext'] ) && ! empty( $data['type'] ) ) {
			return $data;
		}
		
		$wp_file_type = wp_check_filetype( $filename, $mimes );
		
		if ( 'svg' === $wp_file_type['ext'] ) {
			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		}
		
		return $data;
	}
	
	public function init_vars() {
		$this->settingJsConfig = array(
			'ajaxurl'          => admin_url( 'admin-ajax.php' ),
			'resetOptions'     => __( 'All your settings will be reset to default values. Are you sure you want to do this ?', 'xstore' ),
			'pasteYourOptions' => __( 'Please, paste your options there.', 'xstore' ),
			'loadingOptions'   => __( 'Loading options', 'xstore' ) . '...',
			'ajaxError'        => __( 'Ajax error', 'xstore' ),
			'audioPlaceholder' => ETHEME_BASE_URI.'framework/panel/images/audio.png',
		);
		return $this->settingJsConfig;
	}
	
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * enqueue scripts for current panel page
	 *
	 * @version  1.0.2
	 * @since  7.0.0
	 */
	public function add_page_admin_script(){
		if ( isset($this->page['script']) && ! empty($this->page['script']) ){
			wp_enqueue_script('etheme_panel_global',ETHEME_BASE_URI.'framework/panel/js/global.min.js', array('jquery','etheme_admin_js'), false,true);
			wp_enqueue_script('etheme_panel_'.$this->page['script'],ETHEME_BASE_URI.'framework/panel/js/'.$this->page['script'].'.js', array('jquery','etheme_admin_js'), false,true);
		}

		if (
			isset($this->page['template'])
			&& ! empty($this->page['template'])
            && etheme_is_activated()
            && get_option('et_documentation_beacon', false) !== 'off'
        ){
			wp_enqueue_script('etheme_panel_documentation',ETHEME_BASE_URI.'framework/panel/js/documentation.js', array('jquery','etheme_admin_js'), false,true);
		}

		wp_enqueue_script( 'jquery_lazyload', ETHEME_BASE_URI . '/js/libs/jquery.lazyload.js', array('jquery') );
	}
	
	public function add_page_admin_settings_scripts() {
		
		wp_enqueue_script( 'xstore_panel_settings_admin_js', ETHEME_BASE_URI.'framework/panel/js/settings/save_action.js', array('wp-color-picker') );
		
		wp_localize_script( 'xstore_panel_settings_admin_js', 'XStorePanelSettingsConfig', $this->settingJsConfig );
	}
	
	public function add_page_admin_settings_xstore_icons() {
		$dir_uri = get_template_directory_uri();
		$icons_type = ( etheme_get_option('bold_icons', 0) ) ? 'bold' : 'light';
		wp_register_style( 'xstore-icons-font', false );
		wp_enqueue_style( 'xstore-icons-font' );
		wp_add_inline_style( 'xstore-icons-font',
			"@font-face {
		  font-family: 'xstore-icons';
		  src:
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".ttf') format('truetype'),
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".woff2') format('woff2'),
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".woff') format('woff'),
		    url('".$dir_uri."/fonts/xstore-icons-".$icons_type.".svg#xstore-icons') format('svg');
		  font-weight: normal;
		  font-style: normal;
		}"
		);
		wp_enqueue_style( 'xstore-icons-font-style', $dir_uri . '/css/xstore-icons.css' );
	}
	
	/**
	 * Set panel page data
	 *
	 * @version  1.0.2
	 * @since  7.0.0
	 * @log added sales_booster actions
	 */
	public function set_page_data(){
		if (! isset($_REQUEST['page'])){
			return false;
		}
		switch ( $_REQUEST['page'] ) {
			case 'et-panel-system-requirements':
				$this->page['template'] = 'system-requirements';
				break;
			case 'et-panel-changelog':
				$this->page['template'] = 'changelog';
				break;
			case 'et-panel-support':
				$this->page['template'] = 'support';
				$this->page['class'] = 'youtube';
				$this->page['script'] = 'support.min';
				break;
			case 'et-panel-demos':
				$this->page['template'] = 'demos';
				$this->page['script'] = 'demos.min';
				break;
			case 'et-panel-custom-fonts':
				$this->page['template'] = 'custom-fonts';
				break;
			case 'et-panel-sales-booster':
				$this->page['script'] = 'sales_booster.min';
				$this->page['template'] = 'sales-booster';
				$this->page['class'] = 'sales_booster';
				break;
			case 'et-panel-maintenance-mode':
				$this->page['script'] = 'maintenance_mode.min';
				$this->page['template'] = 'maintenance-mode';
				$this->page['class'] = 'maintenance_mode';
				break;
			case 'et-panel-social':
				$this->page['script'] = 'instagram.min';
				$this->page['template'] = 'instagram';
				$this->page['class'] = 'instagram';
				break;
			case 'et-panel-plugins':
				$this->page['script'] = 'plugins.min';
				$this->page['template'] = 'plugins';
				$this->page['class'] = 'plugins';
				break;
			case 'et-panel-email-builder':
				$this->page['script'] = 'email_builder.min';
				$this->page['template'] = 'email-builder';
				$this->page['class'] = 'email_builder';
				break;
			default:
				$this->page['template'] = 'welcome';
				$this->page['script'] = 'welcome.min';
				break;
		}
		return true;
	}
	
	/**
	 * Require page classes
	 *
	 * require page classes when ajax process and return the callbacks for ajax requests
	 *
	 * @version  1.0.0
	 * @since  7.0.0
	 * @param string $class class filename
	 */
	public function require_class($class=''){
		if (! $class){
			return;
		}
		require_once( apply_filters('etheme_file_url', ETHEME_CODE . 'panel/classes/'.$class.'.php') );
	}
	
	/**
	 * Global panel ajax
	 *
	 * require page classes when ajax process and return the callbacks for ajax requests
	 *
	 * @version  1.0.2
	 * @since  7.0.0
	 * @todo remove this
	 * @log added sales_booster actions
	 */
	public function et_panel_ajax(){
		if ( isset($_POST['action_type']) ){
			switch ( $_POST['action_type'] ) {
				case 'et_instagram_user_add':
					$this->require_class('instagram');
					$class = new Instagram();
					$class->et_instagram_user_add();
					break;
				case 'et_instagram_user_remove':
					$this->require_class('instagram');
					$class = new Instagram();
					$class->et_instagram_user_remove();
					break;
				case 'et_instagram_save_settings':
					$this->require_class('instagram');
					$class = new Instagram();
					$class->et_instagram_save_settings();
					break;
				case 'et_email_builder_switch_default':
					$this->require_class('email_builder');
					$class = new Email_builder();
					$class->et_email_builder_switch_default();
					break;
				case 'et_documentation_beacon':
					$this->require_class('youtube');
					$class = new YouTube();
					$class->et_documentation_beacon();
					break;
				case 'et_email_builder_switch_dev_mode_default':
					$this->require_class('email_builder');
					$class = new Email_builder();
					$class->et_email_builder_switch_dev_mode_default();
					break;
				case 'et_maintenance_mode_switch_default':
					$this->require_class('maintenance_mode');
					$class = new Maintenance_mode();
					$class->et_maintenance_mode_switch_default();
					break;
				case 'et_sales_booster_fake_sale_popup_switch_default':
					$this->require_class('sales_booster');
					$class = new Sales_Booster();
					$class->et_sales_booster_fake_sale_popup_switch_default();
					break;
				case 'et_sales_booster_progress_bar_switch_default':
					$this->require_class('sales_booster');
					$class = new Sales_Booster();
					$class->et_sales_booster_progress_bar_switch_default();
					break;
				case 'et_sales_booster_request_quote_switch_default':
					$this->require_class('sales_booster');
					$class = new Sales_Booster();
					$class->et_sales_booster_request_quote_switch_default();
					break;
                case 'et_sales_booster_cart_checkout_countdown_switch_default':
                    $this->require_class('sales_booster');
                    $class = new Sales_Booster();
                    $class->et_sales_booster_cart_checkout_countdown_switch_default();
                    break;
                case 'et_sales_booster_cart_checkout_progress_bar_switch_default':
                    $this->require_class('sales_booster');
                    $class = new Sales_Booster();
                    $class->et_sales_booster_cart_checkout_progress_bar_switch_default();
                    break;
                case 'et_sales_booster_fake_live_viewing_switch_default':
                    $this->require_class('sales_booster');
                    $class = new Sales_Booster();
                    $class->et_sales_booster_fake_live_viewing_switch_default();
                    break;
				case 'et_sales_booster_fake_product_sales_switch_default':
					$this->require_class('sales_booster');
					$class = new Sales_Booster();
					$class->et_sales_booster_fake_product_sales_switch_default();
					break;
                case 'et_sales_booster_safe_checkout_switch_default':
                    $this->require_class('sales_booster');
                    $class = new Sales_Booster();
                    $class->et_sales_booster_safe_checkout_switch_default();
                    break;
				case 'et_sales_booster_floating_menu_switch_default':
					$this->require_class('sales_booster');
					$class = new Sales_Booster();
					$class->et_sales_booster_floating_menu_switch_default();
					break;
				case 'et_sales_booster_estimated_delivery_switch_default':
					$this->require_class('sales_booster');
					$class = new Sales_Booster();
					$class->et_sales_booster_estimated_delivery_switch_default();
					break;
				case 'et_sales_booster_customer_reviews_images_switch_default':
					$this->require_class('sales_booster');
					$class = new Sales_Booster();
					$class->et_sales_booster_customer_reviews_images_switch_default();
					break;
				default:
					break;
			}
		}
	}
	
	/**
	 * Add admin panel dashboard pages to admin menu.
	 *
	 * @since   5.0.0
	 * @version 1.0.3
	 */
	public function et_add_menu_page(){
		$system = new Etheme_System_Requirements();
		$system->system_test();
		$result = $system->result();
		
		$is_et_core = class_exists('ETC\App\Controllers\Admin\Import');
		$is_activated = etheme_is_activated();
		$is_wc = class_exists('WooCommerce');
		$info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_orange-color); font-size: 22px;"></span></span>';
		$update_info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_green-color); font-size: 22px;"></span></span>';
		
		$icon = ETHEME_CODE_IMAGES . 'wp-icon.svg';
		$label = 'XStore';
		$show_pages = array(
			'welcome',
			'system_requirements',
			'demos',
			'plugins',
			'customize',
			'email_builder',
			'sales_booster',
			'custom_fonts',
			'maintenance_mode',
			'social',
			'support',
			'changelog',
			'sponsors'
		);
		
		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
		
		if ( count($xstore_branding_settings) && isset($xstore_branding_settings['control_panel'])) {
			if ( $xstore_branding_settings['control_panel']['icon'] )
				$icon = $xstore_branding_settings['control_panel']['icon'];
			if ( $xstore_branding_settings['control_panel']['label'] )
				$label = $xstore_branding_settings['control_panel']['label'];
			
			$show_pages_parsed = array();
			foreach ( $show_pages as $show_page ) {
				if ( isset($xstore_branding_settings['control_panel']['page_'.$show_page]))
					$show_pages_parsed[] = $show_page;
			};
			$show_pages = $show_pages_parsed;
		}
		
		$is_update_support = 'active';

		$is_subscription = false;
		
		if (
		$is_activated
		){
			if (
				isset($xstore_branding_settings['control_panel'])
				&& isset($xstore_branding_settings['control_panel']['hide_updates'])
				&& $xstore_branding_settings['control_panel']['hide_updates'] == 'on'
			){
				$is_update_support = 'active';
				$is_update_available = false;
			} else {
				$check_update = new ETheme_Version_Check();
				$is_update_available = $check_update->is_update_available();
				$is_update_support = 'active'; //$check_update->get_support_status();

				$is_subscription = $check_update->is_subscription;
			}
			
		} else {
			$is_update_available = false;
		}
		
		if ($is_activated && $is_update_support !='active' && $result){
			if ($is_update_support == 'expire-soon'){
				$info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_orange-color); font-size: 22px;"></span></span>';
			} else {
				$info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_red-color); font-size: 22px;"></span></span>';
			}
		} else if ($is_activated && $is_update_available && $result ){
			$info = $update_info;
		} elseif(!$is_activated){
			$info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_orange-color); font-size: 22px;"></span></span>';
			//$info = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_red-color); font-size: 22px;"></span></span>';
		}
		
		add_menu_page(
			$label . ' ' . ( ( !$is_activated || !$result || $is_update_available || $is_update_support !='active' ) ? $info : '' ),
			$label . ' ' . ( ( !$is_activated || !$result || $is_update_available || $is_update_support !='active' ) ? $info : '' ),
			'manage_options',
			'et-panel-welcome',
			array( $this, 'etheme_panel_page' ),
			$icon,
			65
		);
		
		if ( in_array('welcome', $show_pages) ) {
			add_submenu_page(
				'et-panel-welcome',
				esc_html__( 'Dashboard', 'xstore' )  .' '. ( !$is_activated || ($is_update_support !='active' && $result) ? $info : ''),
				esc_html__( 'Dashboard', 'xstore' ) .' '. ( !$is_activated || ($is_update_support !='active' && $result) ? $info : ''),
				'manage_options',
				'et-panel-welcome',
				array( $this, 'etheme_panel_page' )
			);
		}
		
		if ( $is_activated ) {
			
			if ( in_array('demos', $show_pages) ) {
				add_submenu_page(
					'et-panel-welcome',
					esc_html__( 'Import Demos', 'xstore' ),
					esc_html__( 'Import Demos', 'xstore' ),
					'manage_options',
					'et-panel-demos',
					array( $this, 'etheme_panel_page' )
				);
			}

            if ( in_array('system_requirements', $show_pages) ) {


                $server_label = esc_html__( 'Server Requirements', 'xstore' );

                if (!$result && $is_activated){
                    $server_label = esc_html__( 'Server Reqs.', 'xstore' );
                    $server_label .= ' ' . $info;
                }

                add_submenu_page(
                    'et-panel-welcome',
                    $server_label,
                    $server_label,
                    'manage_options',
                    'et-panel-system-requirements',
                    array( $this, 'etheme_panel_page' )
                );
            }
			
			if ( in_array('plugins', $show_pages) ) {
				add_submenu_page(
					'et-panel-welcome',
					esc_html__( 'Plugins Installer', 'xstore' ),
					esc_html__( 'Plugins Installer', 'xstore' ),
					'manage_options',
					'et-panel-plugins',
					array( $this, 'etheme_panel_page' )
				);
			}
			
		}
		else {
            if ( in_array('system_requirements', $show_pages) ) {


                $server_label = esc_html__( 'Server Requirements', 'xstore' );

                if (!$result && $is_activated){
                    $server_label = esc_html__( 'Server Reqs.', 'xstore' );
                    $server_label .= ' ' . $info;
                }

                add_submenu_page(
                    'et-panel-welcome',
                    $server_label,
                    $server_label,
                    'manage_options',
                    'et-panel-system-requirements',
                    array( $this, 'etheme_panel_page' )
                );
            }
        }

//        if ( ! etheme_is_activated() && ! class_exists( 'Kirki' ) ) {
		// add_submenu_page(
		//     'et-panel-welcome',
		//     esc_html__( 'Setup Wizard', 'xstore' ),
		//     esc_html__( 'Setup Wizard', 'xstore' ),
		//     'manage_options',
		//     admin_url( 'themes.php?page=xstore-setup' ),
		//     ''
		// );
//        } elseif( ! etheme_is_activated() ){
//
//        } elseif( ! class_exists( 'Kirki' ) ){
//            add_submenu_page(
//                'et-panel-welcome',
//                esc_html__( 'Plugins installer', 'xstore' ),
//                esc_html__( 'Plugins installer', 'xstore' ),
//	            'manage_options',
//	            'et-panel-plugins',
//	            array( $this, 'etheme_panel_page' )
//            );
//        }
//        else {
//
//            add_submenu_page(
//                'et-panel-welcome',
//                esc_html__( 'Install Plugins', 'xstore' ),
//                esc_html__( 'Install Plugins', 'xstore' ),
//                'manage_options',
//                admin_url( 'themes.php?page=install-required-plugins&plugin_status=all' ),
//                ''
//            );
//        }
		
		if ( $is_activated && $is_et_core ) {
			
			if ( ! class_exists( 'Kirki' ) ) {
//                add_submenu_page(
//                    'et-panel-welcome',
//                    'Theme Options',
//                    'Theme Options',
//                    'manage_options',
//                    admin_url( 'themes.php?page=install-required-plugins&plugin_status=all' ),
//                    ''
//                );
			}
			else {
				if ( in_array('customize', $show_pages) ) {
					add_submenu_page(
						'et-panel-welcome',
						'Theme Options',
						'Theme Options',
						'manage_options',
						wp_customize_url(),
						''
					);
					add_submenu_page(
						'et-panel-welcome',
						'Header Builder',
						'Header Builder',
						'manage_options',
						admin_url( '/customize.php?autofocus[panel]=header-builder' ),
						''
					);
					add_submenu_page(
						'et-panel-welcome',
						'Single Product Builder',
						'Single Product Builder',
						'manage_options',
						( get_option( 'etheme_single_product_builder', false ) ? admin_url( '/customize.php?autofocus[panel]=single_product_builder' ) : admin_url( '/customize.php?autofocus[section]=single_product_builder' ) ),
						''
					);
				}
			}

            if ( $is_wc ) {
                if ( in_array( 'email_builder', $show_pages ) ) {
                    add_submenu_page(
                        'et-panel-welcome',
                        esc_html__( 'Email Builder', 'xstore' ),
                        esc_html__( 'Email Builder', 'xstore' ),
                        'manage_options',
                        'et-panel-email-builder',
                        array( $this, 'etheme_panel_page' )
                    );
                }

                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Built-in Wishlist', 'xstore' ),
                    esc_html__( 'Built-in Wishlist', 'xstore' ),
                    'manage_options',
                    admin_url( '/customize.php?autofocus[section]=xstore-wishlist' ),
                    ''
                );

                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Built-in Compare', 'xstore' ),
                    esc_html__( 'Built-in Compare', 'xstore' ),
                    'manage_options',
                    admin_url( '/customize.php?autofocus[section]=xstore-compare' ),
                    ''
                );

                if ( $is_et_core && in_array( 'sales_booster', $show_pages ) ) {
                    add_submenu_page(
                        'et-panel-welcome',
                        esc_html__( 'Sales Booster', 'xstore' ),
                        esc_html__( 'Sales Booster', 'xstore' ),
                        'manage_options',
                        'et-panel-sales-booster',
                        array( $this, 'etheme_panel_page' )
                    );
                }

            }

            if ( in_array('social', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Authorization APIs', 'xstore' ),
                    esc_html__( 'Authorization APIs', 'xstore' ),
                    'manage_options',
                    'et-panel-social',
                    array( $this, 'etheme_panel_page' )
                );
            }
			
			if ( in_array('maintenance_mode', $show_pages) ) {
				add_submenu_page(
					'et-panel-welcome',
					esc_html__( 'Maintenance Mode', 'xstore' ),
					esc_html__( 'Maintenance Mode', 'xstore' ),
					'manage_options',
					'et-panel-maintenance-mode',
					array( $this, 'etheme_panel_page' )
				);
			}

            if ( in_array('custom_fonts', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Custom Fonts', 'xstore' ),
                    esc_html__( 'Custom Fonts', 'xstore' ),
                    'manage_options',
                    'et-panel-custom-fonts',
                    array( $this, 'etheme_panel_page' )
                );
            }

            if ( in_array('support', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Tutorials & Support', 'xstore' ),
                    esc_html__( 'Tutorials & Support', 'xstore' ),
                    'manage_options',
                    'et-panel-support',
                    array( $this, 'etheme_panel_page' )
                );
            }

            if ( in_array('changelog', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    esc_html__( 'Changelog', 'xstore' ),
                    esc_html__( 'Changelog', 'xstore' ),
                    'manage_options',
                    'et-panel-changelog',
                    array( $this, 'etheme_panel_page' )
                );
            }
        }

        else {
            if ( in_array('customize', $show_pages) ) {
                add_submenu_page(
                    'et-panel-welcome',
                    'Theme Options',
                    'Theme Options',
                    'manage_options',
                    admin_url( 'themes.php?page=install-required-plugins&plugin_status=all' ),
                    ''
                );
            }
        }
		
		if ( $is_activated && in_array('sponsors', $show_pages) ) {
			
			add_submenu_page(
				'et-panel-welcome',
				esc_html__( 'SEO Experts', 'xstore' ),
				esc_html__( 'SEO Experts', 'xstore' ),
				'manage_options',
				'https://overflowcafe.com/am/aff/go/8theme',
				''
			);
			
			//add_submenu_page(
			//	'et-panel-welcome',
			//	esc_html__( 'Customization Services', 'xstore' ),
			//	esc_html__( 'Customization Services', 'xstore' ),
			//	'manage_options',
			//	'https://wpkraken.io/?ref=8theme',
			//	''
			//);

//	        add_submenu_page(
//		        'et-panel-welcome',
//		        esc_html__( 'Woocommerce Hosting', 'xstore' ),
//		        esc_html__( 'Woocommerce Hosting', 'xstore' ),
//		        'manage_options',
//		        'http://www.bluehost.com/track/8theme',
//		        ''
//	        );
			
			add_submenu_page(
				'et-panel-welcome',
				esc_html__( 'Get WPML Plugin', 'xstore' ),
				esc_html__( 'Get WPML Plugin', 'xstore' ),
				'manage_options',
				'https://wpml.org/?aid=46060&affiliate_key=YI8njhBqLYnp&dr',
				''
			);
//			add_submenu_page(
//				'et-panel-welcome',
//				esc_html__( 'Hosting Service', 'xstore' ),
//				esc_html__( 'Hosting Service', 'xstore' ),
//				'manage_options',
//				'https://www.siteground.com/index.htm?afcode=37f764ca72ceea208481db0311041c62',
//				''
//			);
//            if (!$is_subscription){
//                add_submenu_page(
//                    'et-panel-welcome',
//                    esc_html__( 'Go Unlimited', 'xstore' ),
//                    esc_html__( 'Go Unlimited', 'xstore' ),
//                    'manage_options',
//                    'https://www.8theme.com/#price-section-anchor',
//                    ''
//                );
//            }


//	        add_submenu_page(
//		        'et-panel-welcome',
//		        esc_html__( 'WooCommerce Plugins', 'xstore' ),
//		        esc_html__( 'WooCommerce Plugins', 'xstore' ),
//		        'manage_options',
//		        'https://yithemes.com/product-category/plugins/?refer_id=1028760',
//		        ''
//	        );

//            if ( $is_et_core ) {
//		        add_submenu_page(
//			        'et-panel-welcome',
//			        esc_html__( 'Rate Theme', 'xstore' ),
//			        esc_html__( 'Rate Theme', 'xstore' ),
//			        'manage_options',
//			        'https://themeforest.net/item/xstore-responsive-woocommerce-theme/reviews/15780546',
//			        ''
//		        );
//	        }
		}
	}
	
	/**
	 * Add target blank to some dashboard pages.
	 *
	 * @since   6.2
	 * @version 1.0.0
	 */
	public function et_add_menu_page_target() {
		ob_start(); ?>
		<script type="text/javascript">
            jQuery(document).ready( function($) {
                $('#adminmenu .wp-submenu a[href*=themeforest]').attr('target','_blank');
            });
		</script>
		<?php echo ob_get_clean();
	}
	
	/**'
	 * Show Add admin panel dashboard pages.
	 *
	 * @since   5.0.0
	 * @version 1.0.4
	 */
	public function etheme_panel_page(){
		ob_start();
		get_template_part( 'framework/panel/templates/page', 'header' );
		get_template_part( 'framework/panel/templates/page', 'navigation' );
		echo '<div class="et-row etheme-page-content">';
		
		if (isset($this->page['template']) && ! empty($this->page['template'])){
			get_template_part( 'framework/panel/templates/page', $this->page['template'] );
		}
		echo '</div>';
		get_template_part( 'framework/panel/templates/page', 'footer' );
		echo ob_get_clean();
	}
	
	/**
	 * Load content for panel popups
	 *
	 * @since   6.0.0
	 * @version 1.0.1
	 * @log 1.0.2
	 * ADDED: et_ajax_panel_popup header param
	 */
	public function et_ajax_panel_popup(){
		$response = array();
		
		if ( isset( $_POST['type'] ) && $_POST['type'] == 'instagram' ) {
			ob_start();
			get_template_part( 'framework/panel/templates/popup-instagram', 'content' );
			$response['content'] = ob_get_clean();
		} else {
			
			if (! isset( $_POST['header'] ) || $_POST['header'] !== 'false'){
				ob_start();
				get_template_part( 'framework/panel/templates/popup-import', 'head' );
				$response['head'] = ob_get_clean();
			} else {
				$response['head'] = '';
			}
			
			ob_start();
			get_template_part( 'framework/panel/templates/popup-import', 'content');
			$response['content'] = ob_get_clean();
		}
		wp_send_json($response);
	}
	
	/**
	 * Redirect after theme was activated
	 *
	 * @since   6.0.0
	 * @version 1.0.0
	 */
	public function admin_redirects() {
		ob_start();
		if ( ! get_transient( '_' . $this->theme_name . '_activation_redirect' ) || get_option( 'envato_setup_complete', false ) ) {
			return;
		}
		delete_transient( '_' . $this->theme_name . '_activation_redirect' );
		wp_safe_redirect( admin_url( 'admin.php?page=et-panel-welcome' ) );
		exit;
	}
	
	public function switch_theme() {
		set_transient( '_' . $this->theme_name . '_activation_redirect', 1 );
	}


//	Stas fields
	
	public $xstore_panel_section_settings, $settings_name;
	
	protected function enqueue_settings_scripts($script) {
		wp_enqueue_script('etheme_panel_'.$script, ETHEME_BASE_URI.'framework/panel/js/settings/'.$script.'.js', array('jquery','etheme_admin_js'), false,true);
		wp_localize_script( 'xstore_panel_settings_'.$script, 'XStorePanelSettings'.ucfirst($script).'Config', $this->settingJsConfig );
	}
	
	// don't name setting with key of elements it will break saving for this field
	public function xstore_panel_settings_repeater_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $default = '', $template = array() ) {
		$this->enqueue_settings_scripts( 'sortable' );
		$this->enqueue_settings_scripts( 'repeater' );
		
		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$selected_value = $settings[ $section ][ $setting ];
		} else {
			$selected_value = $default;
		}
		
		$values_2_save = $selected_value;
		if ( is_array( $selected_value ) ) {
			$values_2_save = array();
			foreach ( $selected_value as $item_value => $item_name ) {
				$values_2_save[] = $item_value;
			}
			$values_2_save = implode( ',', $values_2_save );
		}
		
		$sorted_list_parsed = array();
		if ( ! empty( $values_2_save ) ) {
			$sorted_list_values = explode( ',', $values_2_save );
			foreach ( $sorted_list_values as $item ) {
				$sorted_list_parsed[ $item ] = array(
					'callbacks' => $template
				);
//			foreach ( $template as $template_item => $template_item_value) {
//			    $current_template = $template;
//				$current_template[$template_item]['args'][1] = $item.'_'.$template_item_value['args'][1];
//				$sorted_list_parsed[$item] = array(
//                    'callbacks' => $template
//                );
//		    }
			}
		}
//		foreach ($sorted_list_values as $item) {
//			$sorted_list_parsed[$item] = $default[$item];
//		}
		if ( count($sorted_list_parsed))
			$sorted_list_parsed = array_merge($sorted_list_parsed, $default);
		
		ob_start();
		?>
		<div class="xstore-panel-option xstore-panel-repeater">
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) : ?>
					<p class="description"><?php echo esc_html( $setting_descr ); ?></p>
				<?php endif; ?>
			
			</div>
			<div class="xstore-panel-sortable-items">
				<?php
				$i=0;
				foreach ( $sorted_list_parsed as $item_value => $item_name) { $i++;?>
					<div class="sortable-item" data-name="<?php echo esc_attr($item_value); ?>">
						<h4 class="sortable-item-title">
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="down-arrow" fill="currentColor" width=".85em" height=".85em" viewBox="0 0 24 24">
                                <path d="M23.784 6.072c-0.264-0.264-0.672-0.264-0.984 0l-10.8 10.416-10.8-10.416c-0.264-0.264-0.672-0.264-0.984 0-0.144 0.12-0.216 0.312-0.216 0.48 0 0.192 0.072 0.36 0.192 0.504l11.28 10.896c0.096 0.096 0.24 0.192 0.48 0.192 0.144 0 0.288-0.048 0.432-0.144l0.024-0.024 11.304-10.92c0.144-0.12 0.24-0.312 0.24-0.504 0.024-0.168-0.048-0.36-0.168-0.48z"></path>
                            </svg>
							<?php echo esc_html__('Item', 'xstore') . ' ' . $i; ?>
						</h4>
						<div class="settings">
							<div class="settings-inner">
								<?php
								if ( isset($item_name['callbacks'])) {
									foreach ( $item_name['callbacks'] as $callback ) {
										$callback['args'][1] = $item_value.'_'.$callback['args'][1];
										call_user_func_array( $callback['callback'], $callback['args'] );
									}
								}
								?>
							</div>
						</div>
					</div>
				<?php }
				?>
			</div>
			<div class="sortable-item-template hidden">
				<div class="sortable-item" data-name="{{name}}">
					<h4 class="sortable-item-title">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="down-arrow" fill="currentColor" width=".85em" height=".85em" viewBox="0 0 24 24">
                            <path d="M23.784 6.072c-0.264-0.264-0.672-0.264-0.984 0l-10.8 10.416-10.8-10.416c-0.264-0.264-0.672-0.264-0.984 0-0.144 0.12-0.216 0.312-0.216 0.48 0 0.192 0.072 0.36 0.192 0.504l11.28 10.896c0.096 0.096 0.24 0.192 0.48 0.192 0.144 0 0.288-0.048 0.432-0.144l0.024-0.024 11.304-10.92c0.144-0.12 0.24-0.312 0.24-0.504 0.024-0.168-0.048-0.36-0.168-0.48z"></path>
                        </svg>
						<?php echo esc_html__('Item', 'xstore') . ' {{item_number}}'; ?>
					</h4>
					<div class="settings">
						<div class="settings-inner">
							<?php
							foreach ( $template as $template_callback ) {
								$template_callback['args'][1] = '{{name}}_'.$template_callback['args'][1];
								call_user_func_array( $template_callback['callback'], $template_callback['args'] );
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<input type="button" class="add-item et-button no-loader" value="<?php echo esc_attr('Add new item', 'xstore'); ?>">
			<input type="button" class="remove-item et-button et-button-semiactive no-loader" value="<?php echo esc_attr('Remove last item', 'xstore'); ?>">
			<input type="hidden" class="option-val" name="<?php echo esc_attr($setting); ?>" value="<?php echo esc_attr($values_2_save); ?>">
		</div>
		<?php
		echo ob_get_clean();
	}
	
	public function xstore_panel_settings_sortable_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $default = '', $active_callbacks = array() ) {
		
		$this->enqueue_settings_scripts('sortable');
		
		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$selected_value = $settings[ $section ][ $setting ];
		} else {
			$selected_value = $default;
		}
		
		$values_2_save = $selected_value;
		if ( is_array($selected_value)) {
			$values_2_save = array();
			foreach ( $selected_value as $item_value => $item_name ) {
				$values_2_save[] = $item_value;
			}
			$values_2_save = implode(',', $values_2_save);
		}
		
		$sorted_list_parsed = array();
		$sorted_list_values = explode(',', $values_2_save);
		foreach ($sorted_list_values as $item) {
			if ( !isset($default[$item])) continue;
			$sorted_list_parsed[$item] = $default[$item];
		}
		$sorted_list_parsed = array_merge($sorted_list_parsed, $default);
		
		$class = '';
		$to_hide = false;
		$attr = array();
		if ( count($active_callbacks) ) {
			
			$this->enqueue_settings_scripts('callbacks');
			
			$attr['data-callbacks'] = array();
			foreach ( $active_callbacks as $key) {
				if ( isset($settings[ $key['section'] ]) ) {
					if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
					}
					else {
						$to_hide = true;
					}
				}
				elseif ( $key['value'] != $key['default'] ) {
					$to_hide = true;
				}
				$attr['data-callbacks'][] = $key['name'].':'.$key['value'];
			}
			$attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
			unset($attr['data-callbacks']);
		}
		
		if ( $to_hide ) {
			$class .= ' hidden';
		}
		
		ob_start();
		?>
		<div class="xstore-panel-option xstore-panel-sortable<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
			<?php if ( $setting_title || $setting_descr) { ?>
				<div class="xstore-panel-option-title">
					
					<?php if ( $setting_title ) { ?>
						<h4><?php echo esc_html( $setting_title ); ?>:</h4>
					<?php } ?>
					
					<?php if ( $setting_descr ) : ?>
						<p class="description"><?php echo esc_html( $setting_descr ); ?></p>
					<?php endif; ?>
				
				</div>
			<?php } ?>
			<div class="xstore-panel-sortable-items">
				<?php
				foreach ( $sorted_list_parsed as $item_value => $item_name) {
					if ( !$item_name['name'] ) continue;
					$with_options = isset($item_name['callbacks']);
					$visibility_setting_name = $item_value . '_visibility';
					if ( isset($settings[ $section ]) ) {
						if ( isset( $settings[ $section ][ $visibility_setting_name ] ) && $settings[ $section ][ $visibility_setting_name ] ) {
							$visibility_setting_value = true;
						}
						else {
							$visibility_setting_value = false;
						}
					}
					else {
						$visibility_setting_value = isset($item_name['visible']) ? $item_name['visible'] : true;
					}
					?>
					<div class="sortable-item<?php if(!$visibility_setting_value) {echo ' disabled'; }?><?php if (!$with_options) {?> no-settings<?php } ?>" data-name="<?php echo esc_attr($item_value); ?>">
						<h4 class="sortable-item-title">
							<?php if ( $with_options) : ?>
								<svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="down-arrow" fill="currentColor" width=".85em" height=".85em" viewBox="0 0 24 24">
									<path d="M23.784 6.072c-0.264-0.264-0.672-0.264-0.984 0l-10.8 10.416-10.8-10.416c-0.264-0.264-0.672-0.264-0.984 0-0.144 0.12-0.216 0.312-0.216 0.48 0 0.192 0.072 0.36 0.192 0.504l11.28 10.896c0.096 0.096 0.24 0.192 0.48 0.192 0.144 0 0.288-0.048 0.432-0.144l0.024-0.024 11.304-10.92c0.144-0.12 0.24-0.312 0.24-0.504 0.024-0.168-0.048-0.36-0.168-0.48z"></path>
								</svg>
							<?php endif;
							echo esc_html( $item_name['name'] ); ?>
							<span class="item-visibility">
                                <input class="screen-reader-text" type="checkbox" id="<?php echo esc_attr($visibility_setting_name); ?>" name="<?php echo esc_attr($visibility_setting_name); ?>"
                                <?php if($visibility_setting_value) echo 'checked'; ?>>
                                <label for="<?php echo esc_attr($visibility_setting_name); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1.14em" viewBox="0 0 24 24" width="1.14em" class="show-item"><path d="M0 0h24v24H0V0z" fill="none"/>
                                        <path d="M12 6c3.79 0 7.17 2.13 8.82 5.5C19.17 14.87 15.79 17 12 17s-7.17-2.13-8.82-5.5C4.83 8.13 8.21 6 12 6m0-2C7 4 2.73 7.11 1 11.5 2.73 15.89 7 19 12 19s9.27-3.11 11-7.5C21.27 7.11 17 4 12 4zm0 5c1.38 0 2.5 1.12 2.5 2.5S13.38 14 12 14s-2.5-1.12-2.5-2.5S10.62 9 12 9m0-2c-2.48 0-4.5 2.02-4.5 4.5S9.52 16 12 16s4.5-2.02 4.5-4.5S14.48 7 12 7z"/>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" height="1.14em" viewBox="0 0 24 24" width="1.14em" class="hide-item">
                <path d="M0 0h24v24H0V0zm0 0h24v24H0V0zm0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"/>
                <path d="M12 6c3.79 0 7.17 2.13 8.82 5.5-.59 1.22-1.42 2.27-2.41 3.12l1.41 1.41c1.39-1.23 2.49-2.77 3.18-4.53C21.27 7.11 17 4 12 4c-1.27 0-2.49.2-3.64.57l1.65 1.65C10.66 6.09 11.32 6 12 6zm-1.07 1.14L13 9.21c.57.25 1.03.71 1.28 1.28l2.07 2.07c.08-.34.14-.7.14-1.07C16.5 9.01 14.48 7 12 7c-.37 0-.72.05-1.07.14zM2.01 3.87l2.68 2.68C3.06 7.83 1.77 9.53 1 11.5 2.73 15.89 7 19 12 19c1.52 0 2.98-.29 4.32-.82l3.42 3.42 1.41-1.41L3.42 2.45 2.01 3.87zm7.5 7.5l2.61 2.61c-.04.01-.08.02-.12.02-1.38 0-2.5-1.12-2.5-2.5 0-.05.01-.08.01-.13zm-3.4-3.4l1.75 1.75c-.23.55-.36 1.15-.36 1.78 0 2.48 2.02 4.5 4.5 4.5.63 0 1.23-.13 1.77-.36l.98.98c-.88.24-1.8.38-2.75.38-3.79 0-7.17-2.13-8.82-5.5.7-1.43 1.72-2.61 2.93-3.53z"/>
                </svg>
                                </label>
                            </span>
						</h4>
						<div class="settings">
							<div class="settings-inner">
								<?php
								if ( $with_options ) {
									foreach ( $item_name['callbacks'] as $callback ) {
										call_user_func_array( $callback['callback'], $callback['args'] );
									}
								}
								//                                    if ( isset($item_name['callback']) )
								//                                        call_user_func_array( $item_name['callback'], $item_name['args'] );
								?>
							</div>
						</div>
					</div>
				<?php }
				?>
			</div>
			<input type="hidden" class="option-val" name="<?php echo esc_attr($setting); ?>" value="<?php echo esc_html($values_2_save); ?>">
		</div>
		<?php
		echo ob_get_clean();
	}
	
	public function xstore_panel_settings_colorpicker_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $default = '', $config_var = '' ) {
		
		$this->enqueue_settings_scripts('colorpicker');
		
		$settings = $this->xstore_panel_section_settings;
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-color">
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) : ?>
					<p class="description"><?php echo esc_html( $setting_descr ); ?></p>
				<?php endif; ?>
			
			</div>
			<div class="xstore-panel-option-input">
				<input type="text" data-alpha="true" id="<?php echo esc_attr($setting); ?>" name="<?php echo esc_attr($setting); ?>"
				       class="color-field color-picker"
				       value="<?php echo ( isset( $settings[ $section ][ $setting ] ) ) ? esc_attr( $settings[ $section ][ $setting ] ) : ''; ?>"
				       <?php if ( $default ) : ?>data-default="<?php echo esc_attr($default); ?>"<?php endif; ?>
				       data-css-var="<?php echo esc_attr( $config_var ); ?>"/>
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	public function xstore_panel_settings_upload_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $type = 'image', $save_as = 'url', $js_selector = '', $js_img_var = '' ) {
		
		wp_enqueue_media();
		
		$this->enqueue_settings_scripts('media');
		
		$settings = $this->xstore_panel_section_settings;
		
		ob_start(); ?>
  
		<div class="xstore-panel-option xstore-panel-option-upload">
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) : ?>
					<p class="description"><?php echo esc_html( $setting_descr ); ?></p>
				<?php endif; ?>
			
			</div>
			<div class="xstore-panel-option-input">
				<div class="<?php echo esc_attr( $setting ); ?>_preview xstore-panel-option-file-preview">
					<?php
					if ( ! empty( $settings[ $section ][ $setting ] ) ) {
						$url = $settings[ $section ][ $setting ];
						if ( $type == 'audio' ) {
							$url = ETHEME_BASE_URI.'framework/panel/images/audio.png';
						}
						echo '<img src="' . esc_url( $url ) . '" />';
					}
					?>
				</div>
				<div class="file-upload-container">
					<div class="upload-field-input">
						<input type="text" id="<?php echo esc_html( $setting ); ?>"
						       name="<?php echo esc_html( $setting ); ?>"
						       value="<?php echo ( isset( $settings[ $section ][ $setting ] ) ) ? esc_html( $settings[ $section ][ $setting ] ) : ''; ?>"
						       <?php if ( $js_selector ) : ?>data-js-selector="<?php echo esc_attr( $js_selector ); ?>"<?php endif; ?>
							<?php if ( $js_img_var ) : ?> data-js-img-var="<?php echo esc_attr( $js_img_var ); ?>" <?php endif; ?>/>
					</div>
					<div class="upload-field-buttons">
						<input type="button"
						       data-title="<?php esc_html_e( 'Login Screen Background Image', 'xstore' ); ?>"
						       data-button-title="<?php esc_html_e( 'Use File', 'xstore' ); ?>"
						       data-option-name="<?php echo esc_html( $setting ); ?>"
						       class="et-button et-button-dark-grey no-loader button-upload-file button-default"
						       value="<?php esc_html_e( 'Upload', 'xstore' ); ?>"
						       data-file-type="<?php echo esc_attr( $type ); ?>"
						       data-save-as="<?php echo esc_attr($save_as); ?>"/>
						<input type="button"
						       data-option-name="<?php echo esc_html( $setting ); ?>"
						       class="et-button et-button-semiactive no-loader button-remove-file button-default <?php echo ( ! isset( $settings[ $section ][ $setting ] ) || '' === $settings[ $section ][ $setting ] ) ? 'hidden' : ''; ?>"
						       value="<?php esc_html_e( 'Remove', 'xstore' ); ?> "/>
					</div>
				</div>
			</div>
		</div>
		<?php echo ob_get_clean();
	}
	
	/**
	 * Description of the function.
	 *
	 * @param string $section
	 * @param string $setting
	 * @param string $setting_title
	 * @param string $setting_descr
	 * @param false  $default
	 * @return void
	 *
	 * @since 1.0.0
	 *
	 */
	public function xstore_panel_settings_switcher_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $default = false ) {
	 
		$this->enqueue_settings_scripts('switch');
		
		$settings = $this->xstore_panel_section_settings;

//		$value = $settings[ $section ][ $setting ] ?? $default;
//		$value = $value === 'no' ? false : $value;
		
		if ( isset($settings[ $section ]) ) {
			if ( isset( $settings[ $section ][ $setting ] ) && $settings[ $section ][ $setting ] == 'on' ) {
				$value = true;
			}
			else {
				$value = false;
			}
		}
		else {
			$value = $default;
		}
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-switcher">
			<div class="xstore-panel-option-input">
				<h4>
					<label for="<?php echo esc_attr($setting); ?>">
						<?php echo esc_html( $setting_title ); ?>:
						<input class="screen-reader-text" id="<?php echo esc_attr($setting); ?>"
						       name="<?php echo esc_attr($setting); ?>"
						       type="checkbox"
						       value="<?php if($value) echo 'on'; ?>"
							<?php if($value) echo 'checked'; ?>>
						<span class="switch"></span>
					</label>
				</h4>
			</div>
			<div class="xstore-panel-option-title">
				<?php if ( $setting_descr ) :
					echo '<p class="description">'. $setting_descr . '</p>';
				endif; ?>
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	public function xstore_panel_settings_select_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $options = array(), $default = '', $active_callbacks = array() ) {
		
		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$selected_value = $settings[ $section ][ $setting ];
		} else {
			$selected_value = $default;
		}
		
		$class = '';
		$to_hide = false;
		$attr = array();
		if ( count($active_callbacks) ) {
			
			$this->enqueue_settings_scripts('callbacks');
			
			$attr['data-callbacks'] = array();
			foreach ( $active_callbacks as $key) {
				if ( isset($settings[ $key['section'] ]) ) {
					if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
					}
					else {
						$to_hide = true;
					}
				}
                elseif ( $key['value'] != $key['default'] ) {
					$to_hide = true;
				}
				$attr['data-callbacks'][] = $key['name'].':'.$key['value'];
			}
			$attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
			unset($attr['data-callbacks']);
		}
		
		if ( $to_hide ) {
			$class .= ' hidden';
		}
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-select<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) :
                    echo '<p class="description">' . $setting_descr . '</p>';
				endif; ?>
			
			</div>
			<div class="xstore-panel-option-select">
				<select name="<?php echo esc_attr($setting); ?>" id="<?php echo esc_attr($setting); ?>">
					<?php foreach ( $options as $key => $value ) { ?>
						<option value="<?php echo esc_attr($key); ?>"
							<?php if($key == $selected_value) echo 'selected'; ?>>
							<?php echo esc_attr($value); ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	public function xstore_panel_settings_icons_select( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $options = array(), $default = '' ) {
		
		$this->enqueue_settings_scripts('icons_select');
		
		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$selected_value = $settings[ $section ][ $setting ];
		} else {
			$selected_value = $default;
		}
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-icons-select">
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) : ?>
					<p class="description"><?php echo esc_html( $setting_descr ); ?></p>
				<?php endif; ?>
			
			</div>
			<div class="xstore-panel-option-select">
				<select name="<?php echo esc_attr($setting); ?>" id="<?php echo esc_attr($setting); ?>">
					<?php foreach ( $options as $key => $value ) { ?>
						<option value="<?php echo esc_attr($key); ?>"
							<?php if($key == $selected_value) echo 'selected'; ?>>
							<?php echo esc_attr($value); ?></option>
					<?php } ?>
				</select>
				<div class="<?php echo esc_attr( $setting ); ?>_preview xstore-panel-option-icon-preview">
					<i class="et-icon <?php echo str_replace( 'et_icon', 'et', $selected_value ); ?>"></i>
				</div>
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	public function xstore_panel_settings_slider_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $min = 0, $max = 50, $default = 12, $step = 1, $postfix = '', $active_callbacks = array() ) {
		
		$this->enqueue_settings_scripts('slider');
		
		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$value = $settings[ $section ][ $setting ];
		} else {
			$value = $default;
		}
		
		$class = '';
		$to_hide = false;
		$attr = array();
		if ( count($active_callbacks) ) {
			
			$this->enqueue_settings_scripts('callbacks');
			
			$attr['data-callbacks'] = array();
			foreach ( $active_callbacks as $key) {
				if ( isset($settings[ $key['section'] ]) ) {
					if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
					}
					else {
						$to_hide = true;
					}
				}
                elseif ( $key['value'] != $key['default'] ) {
					$to_hide = true;
				}
				$attr['data-callbacks'][] = $key['name'].':'.$key['value'];
			}
			$attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
			unset($attr['data-callbacks']);
		}
		
		if ( $to_hide ) {
			$class .= ' hidden';
		}
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-slider<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) : ?>
					<p class="description"><?php echo esc_html( $setting_descr ); ?></p>
				<?php endif; ?>
			
			</div>
			<div class="xstore-panel-option-input">
				<input type="range" id="<?php echo esc_attr($setting); ?>" name="<?php echo esc_attr($setting); ?>"
				       min="<?php echo esc_attr($min); ?>" max="<?php echo esc_attr($max); ?>" value="<?php echo esc_attr( $value ); ?>"
				       step="<?php echo esc_attr($step); ?>">
				<span class="value"
				      <?php if ( $postfix ) { ?>data-postfix="<?php echo esc_html($postfix); ?>" <?php } ?>><?php echo esc_attr( $value ); ?></span>
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	/**
	 * Description of the function.
	 *
	 * @param       $title
	 * @param array $active_callbacks - multiple array with must-have values as
	 *                                'name' => name of option to compare,
	 *                                'value' => value of option to compare,
	 *                                'section' => section of option to compare,
	 *                                'default' => default value of option for backward compatibility then
	 *
	 * @return void
	 *
	 * @since 1.0.0
	 *
	 */
public function xstore_panel_settings_tab_field_start($title, $active_callbacks = array()) {
	
	$this->enqueue_settings_scripts('tab');
	
	$class = '';
	$to_hide = false;
	$attr = array();
	if ( count($active_callbacks) ) {
		
		$this->enqueue_settings_scripts('callbacks');
		
		$attr['data-callbacks'] = array();
		foreach ( $active_callbacks as $key) {
			if ( isset($settings[ $key['section'] ]) ) {
				if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
				}
				else {
					$to_hide = true;
				}
			}
			elseif ( $key['value'] != $key['default'] ) {
				$to_hide = true;
			}
			$attr['data-callbacks'][] = $key['name'].':'.$key['value'];
		}
		$attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
		unset($attr['data-callbacks']);
	}
	
	if ( $to_hide ) {
		$class .= ' hidden';
	}
	
	?>
	<div class="xstore-panel-option xstore-panel-option-tab <?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
		<?php echo '<h4 class="tab-title">' . $title; ?>
		<svg version="1.1" xmlns="http://www.w3.org/2000/svg" class="down-arrow" fill="currentColor" width=".85em" height=".85em" viewBox="0 0 24 24">
			<path d="M23.784 6.072c-0.264-0.264-0.672-0.264-0.984 0l-10.8 10.416-10.8-10.416c-0.264-0.264-0.672-0.264-0.984 0-0.144 0.12-0.216 0.312-0.216 0.48 0 0.192 0.072 0.36 0.192 0.504l11.28 10.896c0.096 0.096 0.24 0.192 0.48 0.192 0.144 0 0.288-0.048 0.432-0.144l0.024-0.024 11.304-10.92c0.144-0.12 0.24-0.312 0.24-0.504 0.024-0.168-0.048-0.36-0.168-0.48z"></path>
		</svg>
		<?php echo '</h4>'; ?>
		<div class="tab-content">
			<?php
			}
			
			public function xstore_panel_settings_tab_field_end() {
			?>
		</div>
	</div>
	<?php
}
	
	public function xstore_panel_settings_input_number_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $min = 0, $max = 100, $default = '', $step = 1, $active_callbacks = array() ) {
		
		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$value = $settings[ $section ][ $setting ];
		} else {
			$value = $default;
		}
		
		$class = '';
		$to_hide = false;
		$attr = array();
		if ( count($active_callbacks) ) {
			
			$this->enqueue_settings_scripts('callbacks');
			
			$attr['data-callbacks'] = array();
			foreach ( $active_callbacks as $key) {
				if ( isset($settings[ $key['section'] ]) ) {
					if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
					}
					else {
						$to_hide = true;
					}
				}
                elseif ( $key['value'] != $key['default'] ) {
					$to_hide = true;
				}
				$attr['data-callbacks'][] = $key['name'].':'.$key['value'];
			}
			$attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
			unset($attr['data-callbacks']);
		}
		
		if ( $to_hide ) {
			$class .= ' hidden';
		}
		
		ob_start(); ?>

        <div class="xstore-panel-option xstore-panel-option-input<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
            <div class="xstore-panel-option-title">

                <h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) : ?>
                    <p class="description"><?php echo esc_html( $setting_descr ); ?></p>
				<?php endif; ?>

            </div>
            <div class="xstore-panel-option-input">
                <input type="number" id="<?php echo esc_attr($setting); ?>" name="<?php echo esc_attr($setting); ?>"
                       min="<?php echo esc_attr($min); ?>" max="<?php echo esc_attr($max); ?>" step="<?php echo esc_attr($step); ?>"
                       value="<?php echo esc_attr($value); ?>">
            </div>
        </div>
		
		<?php echo ob_get_clean();
	}
	
	public function xstore_panel_settings_input_text_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $placeholder = '', $default = '', $active_callbacks = array() ) {
		
		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$value = $settings[ $section ][ $setting ];
		} else {
			$value = $default;
		}
		
		$class = '';
		$to_hide = false;
		$attr = array();
		if ( count($active_callbacks) ) {
			
			$this->enqueue_settings_scripts('callbacks');
			
			$attr['data-callbacks'] = array();
			foreach ( $active_callbacks as $key) {
				if ( isset($settings[ $key['section'] ]) ) {
					if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
					}
					else {
						$to_hide = true;
					}
				}
                elseif ( $key['value'] != $key['default'] ) {
					$to_hide = true;
				}
				$attr['data-callbacks'][] = $key['name'].':'.$key['value'];
			}
			$attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
			unset($attr['data-callbacks']);
		}
		
		if ( $to_hide ) {
			$class .= ' hidden';
		}
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-input<?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) :
					echo '<p class="description">' . $setting_descr . '</p>';
				endif; ?>
			
			</div>
			<div class="xstore-panel-option-input">
				<input type="text" id="<?php echo esc_attr($setting); ?>" name="<?php echo esc_attr($setting); ?>"
				       placeholder="<?php echo esc_attr( $placeholder ); ?>"
				       value="<?php echo esc_attr($value); ?>">
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	// @todo not used
	public function xstore_panel_settings_button_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $options = array(), $active_callbacks = array() ) {
		
		$settings = $this->xstore_panel_section_settings;
		
		$class = '';
		$to_hide = false;
		$attr = array();
		if ( count($active_callbacks) ) {
			
			$this->enqueue_settings_scripts('callbacks');
			
			$attr['data-callbacks'] = array();
			foreach ( $active_callbacks as $key) {
				if ( isset($settings[ $key['section'] ]) ) {
					if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
					}
					else {
						$to_hide = true;
					}
				}
				elseif ( $key['value'] != $key['default'] ) {
					$to_hide = true;
				}
				$attr['data-callbacks'][] = $key['name'].':'.$key['value'];
			}
			$attr[] = 'data-callbacks="'. implode(',', $attr['data-callbacks']) . '"';
			unset($attr['data-callbacks']);
		}
		
		if ( $to_hide ) {
			$class .= ' hidden';
		}
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-button <?php echo esc_attr($class); ?>" <?php echo implode(' ', $attr); ?>>
			<?php if ( $setting_title || $setting_descr ) : ?>
				<div class="xstore-panel-option-title">
					
					<?php if ( $setting_title ) : ?>
						<h4><?php echo esc_html( $setting_title ); ?>:</h4>
					<?php endif; ?>
					
					<?php if ( $setting_descr ) : ?>
						<p class="description"><?php echo esc_html( $setting_descr ); ?></p>
					<?php endif; ?>
				
				</div>
			<?php endif; ?>
			<div class="xstore-panel-option-input">
				<a class="et-button no-loader"
				   href="<?php echo esc_url($options['url']); ?>"
				   target="<?php echo esc_attr($options['target']); ?>">
					<?php echo esc_html($options['text']); ?>
				</a>
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	public function xstore_panel_settings_textarea_field( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $default = '' ) {
		global $allowedposttags;
		
		$settings = $this->xstore_panel_section_settings;
		
		if ( isset( $settings[ $section ][ $setting ] ) ) {
			$value = $settings[ $section ][ $setting ];
		} else {
			$value = $default;
		}
		
		ob_start(); ?>
		
		<div class="xstore-panel-option xstore-panel-option-code-editor">
			<div class="xstore-panel-option-title">
				
				<h4><?php echo esc_html( $setting_title ); ?>:</h4>
				
				<?php if ( $setting_descr ) :
					echo '<p class="description">' . $setting_descr . '</p>';
				endif; ?>
			
			</div>
			<div class="xstore-panel-option-input">
                    <textarea id="<?php echo esc_attr($setting); ?>" name="<?php echo esc_attr($setting); ?>"
                              style="width: 100%; height: 120px;"
                              class="regular-textarea"><?php echo wp_kses( $value, $allowedposttags ); ?></textarea>
			</div>
		</div>
		
		<?php echo ob_get_clean();
	}
	
	public function xstore_panel_settings_save() {
        $settings_name = isset( $_POST['settings_name'] ) ? $_POST['settings_name'] : $this->settings_name;
		$all_settings            = (array)get_option( $settings_name, array() );
		
		$local_settings          = isset( $_POST['settings'] ) ? $_POST['settings'] : array();
		if ( isset( $_POST['type'] ) ) {
			$local_settings_key = $_POST['type'];
		}
		else {
			switch ( $settings_name ) {
				case 'xstore_sales_booster_settings':
					$local_settings_key = 'fake_sale_popup';
					break;
				default:
					$local_settings_key = 'general';
			}
		}
		$updated                 = false;
		$local_settings_parsed   = array();
		
		foreach ( $local_settings as $setting ) {
//			$local_settings_parsed[ $local_settings_key ][ $setting['name'] ] = $setting['value'];
            // if ( $this->settings_name == 'xstore_sales_booster_settings' )
			$local_settings_parsed[ $local_settings_key ][ $setting['name'] ] = stripslashes( $setting['value'] );
		}
		
		$all_settings = array_merge( $all_settings, $local_settings_parsed );
		
		update_option( $settings_name, $all_settings );
		$updated = true;
		
		if ( in_array($local_settings_key, array('fake_live_viewing', 'fake_product_sales'))) {
			$product_ids = (array)get_transient('etheme_'.$local_settings_key.'_ids', array());
			if ( count($product_ids) ) {
				foreach ($product_ids as $product_id) {
					if ( $product_id )
						delete_transient('etheme_'.$local_settings_key.'_' . $product_id);
				}
			}
		}
		
		$this_response['response'] = array(
			'msg'  => '<h4 style="margin-bottom: 15px;">' . ( ( $updated ) ? esc_html__( 'Settings successfully saved!', 'xstore' ) : esc_html__( 'Settings saving error!', 'xstore' ) ) . '</h4>',
			'icon' => ( $updated ) ? '<img src="' . ETHEME_BASE_URI . ETHEME_CODE . 'assets/images/success-icon.png" alt="installed icon" style="margin-top: 15px;"><br/><br/>' : '',
		);
		
		wp_send_json( $this_response );
	}
}
$EtAdmin = new EthemeAdmin();
$EtAdmin->main_construct();