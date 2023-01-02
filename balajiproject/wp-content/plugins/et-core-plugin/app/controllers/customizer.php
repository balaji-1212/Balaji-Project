<?php

namespace ETC\App\Controllers;

use ETC\App\Controllers\Base_Controller;

/**
 * Create customizer controller.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models
 */
final class Customizer extends Base_Controller {
	/**
	 * Registered settings.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected static $fields = NULL;
	
	/**
	 * Loads all tabpane and return
	 *
	 * @return mixed|null|void
	 */
	public static function register_fields( $sectionsID ) {
		
		if ( isset( self::$fields[$sectionsID] ) ) {
			return self::$fields[$sectionsID];
		}
		
		return self::$fields[$sectionsID] = apply_filters( 'et/customizer/add/fields/' . $sectionsID, array() );
	}
	
	/**
	 * Loads all tabpane and return
	 *
	 * @return mixed|null|void
	 */
	public function get_fields_from_section( $section, $id ) {
		$option = array();
		$fields = self::register_fields( $section );
		
		foreach ( $fields as $field ) {
			\Kirki_Extended::add_field( 'et_kirki_options', $field );
		}
		
		foreach ( \Kirki_Extended::$fields as $kirki_field ) {
			if ( $section != $kirki_field['args']['section'] ) {
				continue;
			}
			
			if ( $id ==  $kirki_field['id'] ) {
				$option = $kirki_field;
			}
		}
		
		return $option;
	}
	
	/**
	 * Construct the class.
	 *
	 * @since 1.4.4
	 */
	public function hooks() {
		global $pagenow;
		
		require_once ET_CORE_DIR . 'packages/kirki/kirki.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/kirki-extended.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/kirki-field-extended.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/kirki-control-extended.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/kirki-settings-extended.php';
		
		require_once( ET_CORE_DIR . 'app/models/customizer/webfont-extend.php' );
		add_action( 'switch_theme', array( $this, 'regenerate_style_after_switch_theme' ), 10, 1 );
		$_regenerate_xstore_kirki = get_option( 'xstore_kirki_styles_render', 'generate' );
		if ( 'generate' ===  $_regenerate_xstore_kirki ) {
			update_option( 'xstore_kirki_styles_render', 'regenerated' );
			add_action( 'init', array( $this, 'customizer_style' ), 11 );
            add_action( 'et_regenerate_multiple_style', array( $this, 'customizer_style' ), 1, 11 );
		}
		add_action( 'wp_enqueue_scripts', array( $this, 'xstore_kirki_enqueue' ), 50 );
		
		// YITH Compare compatibility
		add_action( 'yith_woocompare_popup_head', array( $this, 'xstore_kirki_enqueue_inline' ), 50 );
		
		/**
		 * Load customize-builder.
		 *
		 * @since 1.4.3
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/class-ajax-search.php' );
		
		/**
		 * Load builder functions.
		 *
		 * @since 1.0.0
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/functions.php' );
		
		if ( 'customize.php' === $pagenow || is_admin() || isset( $_REQUEST['wp_customize'] ) || isset( $_REQUEST['customize_theme'] ) ) {
			/**
			 * Load customizer addons.
			 *
			 * @since 1.0.0
			 */
			require_once( ET_CORE_DIR . 'app/models/customizer/addons.php' );
			
			/**
			 * Customizer import/export plugin
			 *
			 * @since 2.1.4
			 */
			if ( ! defined( 'CEI_PLUGIN_DIR' ) ) {
				require_once( ET_CORE_DIR . 'packages/customizer-export-import/customizer-export-import.php' );
			}
		}
		
		// Enqueue frontend builder scripts
		add_action( 'customize_preview_init', array( $this, 'preview_init' ), 99 );
		
		add_action( 'init', array( $this, 'customizer_init' ) );
		
		add_action( 'customizer_after_including_fields', array( $this, 'customizer_field' ) );
		
		add_filter( 'wp_resource_hints', array( $this, 'resource_hints' ), 10, 2 );
		
		add_action( 'customize_save_after', array( $this, 'customizer_style' ) );
		
		add_action( 'customize_save_after', array( $this, 'remove_transients' ) );
		
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_control_script' ), 98 );
		
		
		// Partial Refresh.
		add_filter( 'customize_partial_render', array( $this, 'partial_render' ), null, 3 );
		add_filter( 'customize_dynamic_partial_args', array( $this, 'filter_dynamic_partial_args' ), 10, 2 );
		add_filter( 'customize_dynamic_partial_class', array( $this, 'filter_dynamic_partial_class' ), 10, 2 );
		
		add_filter( 'customize_dynamic_setting_args', array( $this, 'filter_dynamic_setting_args' ), 10, 2 );
		add_filter( 'customize_dynamic_setting_class', array( $this, 'filter_dynamic_setting_class' ), 10, 2 );
		
		add_filter( 'query_vars', array( $this, 'query_vars' ) );
		
		add_action( 'parse_request', array( $this, 'ajax_request' ) );
		
		add_action( 'wp_loaded', array( $this, 'etheme_include_sections_and_panels' ), -1 );
		
		add_action( 'customize_register', array( $this, 'register_control_types' ), 16 );
		
		add_filter( 'kirki_control_types', array( $this, 'kirki_control_types' ), 12 );
		
		add_filter( 'kirki_control_types_exclude', array( $this, 'kirki_control_types_exclude' ), 12 );
		
		// add_filter( 'customize_loaded_components', function( $components ) {
		// 	foreach ( $components as $key => $val ) {
		// 		if ( 'widgets' === $val || 'widgets' === $val ) {
		// 			unset( $components[$key] );
		// 		}
		// 	}
		
		// 	return $components;
		// });
		
	}

	public function regenerate_style_after_switch_theme( $themename ) {
		update_option( 'xstore_kirki_styles_render', 'generate' );
	}

	public function register_control_types() {
		$customizer = $this->wp_customize();
		$control_types = $this->kirki_control_types();
		unset( $control_types['repeater'] );
		
		foreach ( $control_types as $control_type ) {
			$customizer->register_control_type( $control_type );
		}
	}
	
	public function kirki_control_types( $control_types = array() ) {
		
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/controls/etheme-repeater.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/controls/link.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/controls/color.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/controls/text.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/controls/textarea.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/controls/generic.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/controls/number.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/controls/radio.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/controls/select.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/controls/radio-image.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/controls/radio-buttonset.php';
		$control_types['repeater'] = 'Kirki_Control_Etheme_Repeater';
		$control_types['etheme-textarea'] = 'Kirki_Control_Etheme_TextArea';
		$control_types['etheme-text'] = 'Kirki_Control_Etheme_Text';
		$control_types['etheme-link'] = 'Kirki_Control_Etheme_Link';
		$control_types['kirki-color'] = 'Kirki_Control_Color_Extended';
		$control_types['kirki-generic'] = 'Kirki_Control_Generic_Extended';
		$control_types['kirki-number'] = 'Kirki_Control_Number_Extended';
		$control_types['kirki-radio'] = 'Kirki_Control_Radio_Extended';
		$control_types['kirki-select'] = 'Kirki_Control_Select_Extended';
		$control_types['kirki-radio-image'] = 'Kirki_Control_Radio_Image_Extended';
		$control_types['kirki-radio-buttonset'] = 'Kirki_Control_Radio_Buttonset_Extended';
		
		return $control_types;
	}
	
	public function kirki_control_types_exclude( $skip_control_types ) {
		
		$skip_control_types[] = 'Kirki_Control_Code';
		$skip_control_types[] = 'Kirki_Control_Color';
		$skip_control_types[] = 'Kirki_Control_Generic';
		$skip_control_types[] = 'Kirki_Control_Number';
		$skip_control_types[] = 'Kirki_Control_Radio';
		$skip_control_types[] = 'Kirki_Control_Select';
		$skip_control_types[] = 'Kirki_Control_Radio_Image';
		$skip_control_types[] = 'Kirki_Control_Radio_Buttonset';
		
		return $skip_control_types;
	}
	
	public function preview_init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'previewer_script' ) );
	}
	
	function etheme_include_sections_and_panels() {
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/setting.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/partial.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/kirki-control-extended.php';
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/kirki-settings-extended.php';
	}
	
	/**
	 * Initiate customizer options.
	 *
	 * @since 3.2.5
	 */
	public function customizer_init() {
		// Run builder
		$this->get_model()->_run();
	}
	
	/**
	 * Initiate customizer options.
	 *
	 * @since 3.2.5
	 */
	public function customizer_field() {
		// Run builder
		$this->get_model()->_customizer_field();
	}
	
	/**
	 * Initiate customizer options.
	 *
	 * @since 3.2.5
	 */
	public function pre_build_settings() {
		
		\Kirki::add_config( 'et_kirki_options', array(
			'capability'    => 'manage_network_plugins',
			'option_type'   => 'theme_mod',
		) );
		
		// Get requested fields
		foreach ( apply_filters( 'et/customizer/add/sections', array() ) as $section ) {
			$fields = self::register_fields( $section['name'] );
			
			foreach ( $fields as $field ) {
				\Kirki_Extended::add_field( 'et_kirki_options', $field );
			}
			
		}
		
	}
	
	/**
	 *
	 *
	 * @since 3.0.1
	 */
	public function load_selected_control($data) {
		$this->pre_build_settings();
		
		$result = array();
		$customizer = $this->wp_customize();
		
		foreach ( \Kirki_Extended::$fields as $key => $field ) {
			
			if ( !isset( $field['name'] ) ) {
				continue;
			}
			
			if ( array_key_exists( $field['name'] , $data ) ) {
				// skip for normal fields
				if ( isset( $field['dynamic'] ) && false === $field['dynamic']) {
					continue;
				}
				(\Kirki_Modules_Selective_Refresh::get_instance())->register_partials( $customizer );
				$result[$field['section']][$field['section']][$field['name']] = $this->build_my_lazy( $field, $field['section'] );
			}
			
		}
		
		return $result;
		
	}
	
	/**
	 * Build css file from dynamic one.
	 *
	 * @since 3.0.1
	 */
	public function customizer_style( $file_name ) {
		if ( is_a( $file_name, 'WP_Customize_Manager' ) || '' == $file_name ) {
			$file_name = 'kirki-styles';
		}

		$this->pre_build_settings();
		// Write stules to css file
		$this->get_model()->generate( $file_name );
	}
	
	/**
	 * Delete css file.
	 *
	 * @since 3.0.1
	 */
	public function delete_css_file( $file_name ) {
		// Delete css file
		$this->get_model()->delete( $file_name );
	}
	
	/**
	 * Enqueue xstore kirki styles.
	 *
	 * @since 3.0.1
	 */
	public function xstore_kirki_enqueue() {
		if ( did_action('etheme_load_multiple_styles') ) return;
		wp_enqueue_style(
			'xstore-kirki-styles',
			$this->get_model()->get_url(),
			array(),
			get_option( 'xstore_kirki_css_version', ET_CORE_VERSION ),
			'all'
		);
	}
	
	public function xstore_kirki_enqueue_inline() {
		?>
		<link rel="stylesheet" href="<?php echo $this->get_model()->get_url(); ?>?ver=<?php echo esc_attr( get_option( 'xstore_kirki_css_version', ET_CORE_VERSION ) ); ?>" type="text/css" media="all" /> <?php // phpcs:ignore ?>
		<?php
	}
	
	/**
	 * Remove some transients after customizer saving.
	 *
	 * @since 4.0.4
	 *
	 * @return void
	 */
	public function remove_transients() {
		// remove mega menu cache transient
		delete_transient('xstore-menu-hash-latest-time');
	}
	
	/**
	 * Add preconnect for Google Fonts.
	 *
	 * @access public
	 * @param array  $urls           URLs to print for resource hints.
	 * @param string $relation_type  The relation type the URLs are printed.
	 * @return array $urls           URLs to print for resource hints.
	 */
	public function resource_hints( $urls, $relation_type ) {
		$fonts_to_load = get_option( 'et_header_builder_fonts', false );
		
		if ( false === $fonts_to_load ) {
			return $urls;
		}
		
		if ( ! empty( $fonts_to_load ) && 'preconnect' === $relation_type ) {
			$urls[] = array(
				'href' => 'https://fonts.gstatic.com',
				'crossorigin',
			);
		}
		return $urls;
	}
	
	
	/**
	 * Enqueue control for customizer script
	 *
	 * @since 3.2.4
	 */
	public function enqueue_control_script() {
		// deque
		wp_dequeue_script( 'wp-color-picker-alpha' );
		wp_dequeue_script( 'selectWoo' );
		wp_dequeue_script( 'kirki-custom-sections' );
		wp_dequeue_script( 'kirki_field_dependencies' );
		wp_dequeue_script( 'kirki_panel_and_section_icons' );
		wp_dequeue_script( 'kirki_post_meta_previewed_controls' );
		wp_dequeue_script( 'kirki_post_meta_previewed_preview' );
		wp_dequeue_script( 'kirki_auto_postmessage' );
		wp_dequeue_script( 'kirki-preset' );
		wp_dequeue_script( 'kirki-tooltip' );
		wp_dequeue_script( 'webfont-loader' );
		wp_dequeue_script( 'etheme-builder' );
		wp_dequeue_script( 'kirki-script' );
		wp_dequeue_script( 'kirki_box_model_control' );
		wp_dequeue_script( 'kirki_wcag_text_color' );
		wp_dequeue_script( 'wcag_colors' );
		
		// Build the suffix for the script.
		$suffix  = '';
		$suffix .= ( ! defined( 'SCRIPT_DEBUG' ) || true !== SCRIPT_DEBUG ) ? '.min' : '';
		
		// The Kirki plugin URL.
		$kirki_url = trailingslashit( \Kirki::$url );
		
		// Enqueue ColorPicker.
		wp_register_script( 'wp-color-picker-alpha', trailingslashit( \Kirki::$url ) . 'assets/vendor/wp-color-picker-alpha/wp-color-picker-alpha.js', array( 'wp-color-picker', 'wp-i18n' ), KIRKI_VERSION, true );
		
		// Enqueue selectWoo.
		wp_enqueue_script( 'selectWoo', trailingslashit( \Kirki::$url ) . 'assets/vendor/selectWoo/js/selectWoo.full.js', array( 'jquery' ), '1.0.1', true );
		
		wp_enqueue_style( 'kirki_box_model_control', apply_filters( 'kirki_box_model_control_url', plugins_url( __FILE__ ) ) . '/styles.css', [], '1.0' );
		
		wp_enqueue_script( 'kirki_box_shadow_control', apply_filters( 'kirki_box_shadow_control_url', plugins_url( __FILE__ ) ) . '/script.js', [ 'jquery', 'customize-base', 'customize-controls', 'jquery-ui-draggable' ], '1.0.1', false );
		wp_enqueue_style( 'kirki_box_shadow_control', apply_filters( 'kirki_box_shadow_control_url', plugins_url( __FILE__ ) ) . '/styles.css', [], '1.0.1' );
		
		$url = str_replace( ABSPATH, trailingslashit( home_url() ), __DIR__ ); // phpcs:ignore PHPCompatibility.Keywords
		wp_enqueue_style( 'kirki_wcag_text_color', apply_filters( 'kirki_wcag_text_color_url', $url ) . '/styles.css', [ 'wp-color-picker' ], '1.1.1' );
		
		// Enqueue the script.
		wp_register_script(
			'xstore-script',
			ET_CORE_URL . 'app/models/customizer/frontend/js/script.js',
			array(
				'jquery',
				'customize-base',
				'customize-controls',
				// 'wcag_colors',
				'wp-color-picker-alpha',
				'selectWoo',
				'jquery-ui-button',
				'jquery-ui-datepicker',
			),
			KIRKI_VERSION,
			false
		);
		
		wp_localize_script(
			'xstore-script',
			'kirkiL10n',
			array(
				'isScriptDebug'        => ( defined( 'SCRIPT_DEBUG' ) && true === SCRIPT_DEBUG ),
				'noFileSelected'       => esc_html__( 'No File Selected', 'xstore-core' ),
				'remove'               => esc_html__( 'Remove', 'xstore-core' ),
				'default'              => esc_html__( 'Default', 'xstore-core' ),
				'selectFile'           => esc_html__( 'Select File', 'xstore-core' ),
				'standardFonts'        => esc_html__( 'Standard Fonts', 'xstore-core' ),
				'googleFonts'          => esc_html__( 'Google Fonts', 'xstore-core' ),
				'defaultCSSValues'     => esc_html__( 'CSS Defaults', 'xstore-core' ),
				'defaultBrowserFamily' => esc_html__( 'Default Browser Font-Family', 'xstore-core' ),
				'homeurl'			   => home_url(),
			)
		);
		
		wp_register_script( 'kirki-custom-sections', trailingslashit( \Kirki::$url ) . 'modules/custom-sections/sections.js', array( 'jquery', 'customize-base', 'customize-controls' ), KIRKI_VERSION, false );
		wp_register_script( 'kirki_post_meta_previewed_controls', trailingslashit( \Kirki::$url ) . 'modules/post-meta/customize-controls.js', array( 'jquery', 'customize-controls' ), KIRKI_VERSION, true );
		
		wp_register_script( 'kirki-preset', trailingslashit( \Kirki::$url ) . 'modules/preset/preset.js', array( 'jquery' ), KIRKI_VERSION, true );
		
		wp_register_script(
			'et-customizer-active-callback',
			ET_CORE_URL . 'app/models/customizer/frontend/js/active-callback.js',
			array( 'underscore' ),
			ET_CORE_VERSION,
			true
		);
		
		wp_register_script(
			'etheme-builder',
			ET_CORE_URL . 'app/models/customizer/builder/js/builder.min.js',
			array(
				'customize-controls',
				'jquery-ui-resizable',
				'jquery-ui-droppable',
				'jquery-ui-draggable',
				'et-customizer-active-callback',
			)
		);






        wp_localize_script( 'etheme-builder', 'BuilderSetting', array(
            'et_multiple' => ( isset( $_GET['et_multiple'] ) && $_GET['et_multiple'] !='' ) ? $_GET['et_multiple'] : false,
        ) );





		
		wp_enqueue_script(
			'et-customizer-sync',
			ET_CORE_URL . 'app/models/customizer/frontend/js/sync.js',
			array( 'underscore', 'customize-controls' ),
			ET_CORE_VERSION,
			true
		);
		
		wp_enqueue_script(
			'et-core-lazy-section',
			ET_CORE_URL . 'app/models/customizer/frontend/js/lazy-section.js',
			array(
				'jquery',
				'underscore',
				'xstore-script',
				'et-customizer-active-callback',
				'etheme-builder',
				'kirki-preset',
				'kirki-custom-sections',
				'kirki_post_meta_previewed_controls',
				'customize-base',
				'customize-controls',
			),
			ET_CORE_VERSION,
			true
		);

		$ajaxUrl = add_query_arg( array( 'customizer' => 'et_core' ), esc_url( home_url( '/', 'relative' ) ) );

		if (isset( $_GET['et_multiple'] )){
            $ajaxUrl = add_query_arg( array( 'et_multiple' => $_GET['et_multiple'] ), $ajaxUrl );
        }
		
		wp_localize_script( 'et-core-lazy-section', 'lazySetting', array(
			'ajaxUrl' =>  $ajaxUrl,
			'nonce'   => wp_create_nonce( 'et_core_customizer' ),
		) );
		
		
		wp_localize_script( 'et-customizer-sync', 'partialSetting', array(
			'patternTemplate' => 'partial({section})({id})',
		) );
		
		
		// style
		wp_enqueue_style( 'builder',  ET_CORE_URL . 'app/models/customizer/builder/css/builder.css', array('etheme_admin_css') );
		if ( is_rtl() ) {
			wp_enqueue_style( 'builder-rtl',  ET_CORE_URL . 'app/models/customizer/builder/css/builder-rtl.css', array('etheme_admin_css', 'builder') );
		}

		// Enqueue the style.
		wp_enqueue_style(
			'xstore-kirki-styles',
			"{$kirki_url}controls/css/styles.css",
			array(),
			KIRKI_VERSION
		);
		
		wp_enqueue_style( 'kirki-custom-sections', trailingslashit( \Kirki::$url ) . 'modules/custom-sections/sections.css', array(), KIRKI_VERSION );
		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'selectWoo', trailingslashit( \Kirki::$url ) . 'assets/vendor/selectWoo/css/selectWoo.css', array(), '1.0.1' );
		wp_enqueue_style( 'kirki-selectWoo', trailingslashit( \Kirki::$url ) . 'assets/vendor/selectWoo/kirki.css', array(), KIRKI_VERSION );
		
	}
	
	public function previewer_script() {
		wp_dequeue_script( 'kirki_auto_css_vars' );		

		wp_enqueue_script(
			'et-core-lazy-preview',
			ET_CORE_URL . 'app/models/customizer/frontend/js/preview.js',
			array(
				'jquery',
				'underscore',
				'customize-preview',
			),
			ET_CORE_VERSION,
			true
		);
		
		wp_localize_script( 'et-core-lazy-preview', 'outputSetting', [
			'excludeFont'     => array(),
			'settingPattern'  => 'setting\(([^)]+)\)\(([^)]+)\)',
			'inlinePrefix'    => 'et_style_',
			'redirectTag'     => array(),
			'redirectSetting' => [
				'changeNotice' => wp_kses( __( "Change you made not showing on this page.<br/> Do you want to be redirected to the appropriate page to see change you just made?", 'xstore-core' ), wp_kses_allowed_html() ),
				'yes'          => esc_html__( 'Yes', 'xstore-core' ),
			]
		] );

	}
	
	
	/**
	 * Register query var for lazy section ajax
	 *
	 * @since 3.2.4
	 */
	public function query_vars( $vars ) {
		$vars[] = 'customizer';
		$vars[] = 'sections';
		$vars[] = 'search';
		$vars[] = 'customizernonce';
        $vars[] = 'et_multiple';
		
		return $vars;
	}
	
	/**
	 * Handle ajax for fetching lazy load section
	 *
	 * @since 3.2.4
	 */
	public function ajax_request( $wp ) {
		
		if ( ! array_key_exists( 'customizer', $wp->query_vars ) ) {
			return;
		}
		
		
		if ( ! isset( $wp->query_vars['customizernonce'] ) ) {
			return;
		}
		
		if ( ! wp_verify_nonce( $wp->query_vars['customizernonce'], 'et_core_customizer' ) ) {
			return;
		}
		
		add_filter( 'wp_doing_ajax', '__return_true' );

		if ( isset( $wp->query_vars['sections'] ) ) {
			$section = $wp->query_vars['sections'];
			$this->get_lazy_section_control( $section );
		}
		
		if ( isset( $wp->query_vars['search'] ) ) {
			$search = $wp->query_vars['search'];
			$this->get_search_result( $search );
		}
		
		exit();
		
	}
	
	/**
	 * Return search result
	 *
	 * @param string $search Search keyword.
	 */
	public function get_search_result( $search ) {
		$fields  = $this->pre_build_settings();
		
		$results = array();
		
		foreach ( \Kirki_Extended::$fields as $key => $field ) {
			$field['label']       = isset( $field['label'] ) ? $field['label'] : '';
			$field['description'] = isset( $field['description'] ) ? $field['description'] : '';
			
			$match                = $this->match_search( $search, implode( ' ', array(
				$field['label'],
				$field['description'],
			) ) );
			
			if ( $match > 0 ) {
				$results[] = array(
					'id'            => $field['args']['name'],
					'type'          => $field['type'],
					'label'         => $field['args']['label'],
					'description'   => $field['description'],
					'settings'      => $field['args']['settings'],
					'section'       => $field['args']['section'],
					'panelName'     => str_replace(array('_','-'), ' ', $field['args']['section']),
					'sectionName'   => '',
					'match'         => $match,
				);
			}
		}
		
		wp_send_json_success( $results );
	}
	
	public function match_search( $keywords, $description ) {
		preg_match_all( '/\w+/i', $keywords, $words );
		$total = 0;
		
		foreach ( $words[0] as $search ) {
			$found = preg_match_all( "/($search)/i", $description );
			
			if ( 0 === $found ) {
				return 0;
			} else {
				$total += $found;
			}
		}
		
		return $total;
	}
	
	/**
	 * Get lazy section control
	 *
	 * @since 3.2.4
	 */
	public function get_lazy_section_control( $sections ) {
		
		$results = array();
		
		if ( ! is_array( $sections ) ) {
			return;
		}
		
		$sectionsID = isset( $sections[0] ) ? $sections[0] : null;
		
		if ( is_null( $sectionsID ) ) {
			return;
		}
		
		// Get requested fields
		$fields = self::register_fields( $sectionsID );
		require_once ET_CORE_DIR . 'packages/kirki/kirki.php';
		require_once( ET_CORE_DIR . 'app/models/customizer/webfont-extend.php' );
		
		/**
		 * Load customize-builder.
		 *
		 * @since 1.4.3
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/class-ajax-search.php' );
		require_once( ET_CORE_DIR . 'app/models/customizer/functions.php' );
		/**
		 * Load customizer addons.
		 *
		 * @since 1.0.0
		 */
		require_once( ET_CORE_DIR . 'app/models/customizer/addons.php' );
		
		/**
		 * Customizer import/export plugin
		 *
		 * @since 2.1.4
		 */
		if ( ! defined( 'CEI_PLUGIN_DIR' ) ) {
			require_once( ET_CORE_DIR . 'packages/customizer-export-import/customizer-export-import.php' );
		}
		
		$this->get_model()->_run();
		
		$customizer = $this->wp_customize();
		
		\Kirki::add_config( 'et_kirki_options', array(
			'option_type'   => 'theme_mod',
			'capability'    => 'manage_network_plugins',
		) );
		
		foreach ( $fields as $field ) {
			\Kirki::add_field( 'et_kirki_options', $field );
		}
		
		foreach ( \Kirki::$fields as $kirki_field ) {
			if ( $sectionsID != $kirki_field['args']['section']) {
				continue;
			}
			// skip for normal fields
			if ( isset( $kirki_field['dynamic'] ) && false === $kirki_field['dynamic']) {
				continue;
			}
			(\Kirki_Modules_Selective_Refresh::get_instance())->register_partials( $customizer );
			$option[ $kirki_field['name'] ] = $this->build_my_lazy( $kirki_field, $sectionsID );
		}
		
		$results[$sectionsID] = $option;
		
		wp_send_json_success( $results );
	}
	
	/**
	 * Prepare lazy option to fit with customizer setting
	 *
	 * @since 3.2.4
	 */
	public function build_my_lazy( $option, $section_id ) {
		
		$result = array();
		
		// force assign section & dynamic control.
		$option['section'] = $section_id;
		$field             = $option;
		
		$setting_id          = \Etheme_Lazy_Default_Setting::create_lazy_setting( $section_id, $option['name'] );
		$result['settingId'] = $setting_id;
		
		// assign setting json.
		$setting_instance  = $this->do_add_setting( $field, $setting_id );
		$result['setting'] = $setting_instance->json();
		
		// assign control json.
		$field['id'] = $option['name'];
		$field['settings'] = $setting_id;
		$control_instance  = $this->do_add_control( $field, $setting_instance );
		$result['control'] = $control_instance->json();

		//New Multiple header option
        if ( isset($_GET['et_multiple']) && $_GET['et_multiple'] ){

	        $headers = get_option('et_multiple_headers', false);
	        $single_product = get_option('et_multiple_single_product', false);

	        if ($headers || $single_product){
		        $headers = json_decode($headers, true);
		        $single_product = json_decode($single_product, true);
		        $multiple_id = $_GET['et_multiple'];

		        if (is_array($headers) && isset($headers[$multiple_id]) && array_key_exists($field['id'], $headers[$multiple_id]['options'])){
			        $result['setting']['value'] = $headers[$multiple_id]['options'][$field['id']];

			        if($result['setting']['value'] == 'false'){
				        $result['setting']['value'] = false;
			        } elseif($result['setting']['value'] == 'true') {
				        $result['setting']['value'] = true;
			        }

			        $result['setting']['is_multiple'] = true;
			        $result['setting']['real_id'] = $field['id'];
		        }

		        if (is_array($single_product) && isset($single_product[$multiple_id]) && array_key_exists($field['id'], $single_product[$multiple_id]['options'])){
			        $result['setting']['value'] = $single_product[$multiple_id]['options'][$field['id']];

			        if($result['setting']['value'] == 'false'){
				        $result['setting']['value'] = false;
			        } elseif($result['setting']['value'] == 'true') {
				        $result['setting']['value'] = true;
			        }

			        $result['setting']['is_multiple'] = true;
			        $result['setting']['real_id'] = $field['id'];


		        }
            }
        }

		//Tooltip
		if ( isset( $field['tooltip'] ) && ! empty( $field['tooltip'] ) ) {
			// Get the control ID and properly format it for the tooltips.
			$id = str_replace( '[', '-', str_replace( ']', '', $field['settings'] ) );
			// Add the tooltips content.
			$result['control']['tooltip'] = array(
				'id'      => $id,
				'content' => $field['tooltip'],
			);
		}
		$result['control']['settings']['default'] = $setting_id;
		$result['control']['dynamic'] = isset( $result['control']['dynamic'] ) ? $result['control']['dynamic'] : true;
		// $result['control']['link'] = 'data-customize-setting-link="'.$setting_id .'"';
		$result['control']['default']   = isset( $field['default'] ) ? $field['default'] : 0;
		
		// $result['control']['js_vars']   = isset( $field['js_vars'] ) ? $field['js_vars'] : array();
		$result['control']['output']    = isset( $result['control']['output'] ) ? $result['control']['output'] : array();
		$result['control']['output'] 	= array_merge( $result['control']['output'], $field['js_vars'] );
		
		$result['control']['value'] = $result['setting']['value'];
		$result['control']['required'] = $field['required'];
		
		if ( $field['args']['active_callback'] != '__return_true') {
			$result['control']['active_rule'] = $field['args']['active_callback'];
		}
		
		if ( !empty( $field['args']['partial_refresh'] ) ) {
			foreach ( $field['args']['partial_refresh'] as $partial_refresh ) {
				$partial[$option['name']] = $partial_refresh;
			}
		}
		
		$result['control']['partial_refresh'] = isset( $partial ) ? $partial : array();
		
		if ($field['type'] === 'kirki-image' && isset( $result['control']['choices']['save_as'] ) &&!isset($result['setting']['value']['url'])) {
			$result['setting']['value'] = array('url' => '');
		}
		
		return $result;
		
	}
	
	public function do_add_setting( $setting, $setting_id ) {
		$setting_instance = new \Kirki_Settings_Extended( $setting, $setting_id );
		
		return $setting_instance;
	}
	
	public function do_add_control( $field, $setting = null ) {
		
		if ( null !== $setting && $setting instanceof \WP_Customize_Setting ) {
			$field['settings'] = $setting->id;
		}
		
		$control_instance = new \Kirki_Control_Settings( $field );
		
		return $control_instance;
	}
	
	public function wp_customize() {
		global $wp_customize;
		
		if ( empty( $wp_customize ) || ! ( $wp_customize instanceof \WP_Customize_Manager ) ) {
			require_once ABSPATH . WPINC . '/class-wp-customize-manager.php';
			$wp_customize = new \WP_Customize_Manager();
		}
		
		return $wp_customize;
	}
	
	/**
	 * Find setting class for option
	 *
	 * @param string $class Class name for setting.
	 * @param \WP_Customize_Setting|string $id Customize Setting object, or ID.
	 *
	 * @return string
	 */
	public function filter_dynamic_setting_class( $class, $id ) {
		if ( preg_match( \Etheme_Lazy_Default_Setting::$lazy_pattern, $id, $matches ) ) {
			
			$option = $this->get_fields_from_section( $matches['section'], $matches['id'] );
			
			$kirki_control_types = array(
				'default'     => 'Etheme_Lazy_Default_Setting',
				'repeater' 	  => 'Kirki_Settings_Repeater_Setting',
				'user_meta'   => 'Kirki_Setting_User_Meta',
				'site_option' => 'Kirki_Setting_Site_Option',
			);
			
			$classname = false;
			
			if ( isset( $option['option_type'] ) && array_key_exists( $option['option_type'], $kirki_control_types ) ) {
				$classname = $kirki_control_types[ $option['option_type'] ];
			}
			
			if ( ! isset( $option['type'] ) || ! array_key_exists( $option['type'], $kirki_control_types ) ) {
				$option['type'] = 'default';
			}
			
			$class = ! $classname ? $kirki_control_types[ $option['type'] ] : $classname;
			
		}
		
		return $class;
	}
	
	/**
	 * Find setting arguemnt for option
	 *
	 * @param array $setting_args Array of properties for the new WP_Customize_Setting. Default empty array.
	 * @param \WP_Customize_Setting|string $setting_id Customize Setting object, or ID.
	 *
	 * @return mixed
	 */
	public function filter_dynamic_setting_args( $setting_args, $setting_id ) {
		require_once ET_CORE_DIR . 'app/models/customizer/addons/etheme-lazy/setting.php';
		if ( preg_match( \Etheme_Lazy_Default_Setting::$lazy_pattern, $setting_id, $matches ) ) {
			
			$option = $this->get_fields_from_section( $matches['section'], $matches['id'] );
			
			$args = array(
				'id'           		=> $setting_id,
				'type'              => $option['option_type'],
				'default'           => $option['default'],
				'capability'        => $option['capability'],
				'transport'         => $option['transport'],
				'sanitize_callback' => $option['sanitize_callback'],
			);
			
			$setting_args = $args;
			
		}
		
		return $setting_args;
	}
	
	
	public function partial_render( $rendered, \WP_Customize_Partial $partial, $container_context ) {
		if ( preg_match( \Lazy_Partial_Extended::$pattern, $partial->id, $matches ) ) {
			
			$option = $this->get_fields_from_section( $matches['section'], $matches['id'] );
			
			if ( $option ) {
				
				foreach ( $option['partial_refresh'] as $partial ) {
					ob_start();
					$return_render = call_user_func( $partial['render_callback'], $this, $container_context );
					$ob_render     = ob_get_clean();
					
					if ( null !== $return_render && '' !== $ob_render ) {
						_doing_it_wrong( __FUNCTION__, esc_html__( 'Partial render must echo the content or return the content string (or array), but not both.', 'xstore-core' ), '4.5.0' );
					}
				}
				
				$rendered = null !== $return_render ? $return_render : $ob_render;
			}
		}
		
		return $rendered;
	}
	
	public function filter_dynamic_partial_args( $args, $id ) {
		if ( preg_match( \Lazy_Partial_Extended::$pattern, $id, $matches ) ) {
			
			$option = $this->get_fields_from_section( $matches['section'], $matches['id'] );
			
			foreach ( $option['partial_refresh'] as $partial ) {
				$args   = array(
					'selector'            => $partial['selector'],
					'settings'            => array( \Etheme_Lazy_Default_Setting::create_lazy_setting( $matches['section'], $option['args']['settings'] ) ),
					'container_inclusive' => false,
					'fallback_refresh'    => false,
				);
			}
			
		}
		
		return $args;
	}
	
	public function filter_dynamic_partial_class( $class, $id ) {
		if ( preg_match( \Lazy_Partial_Extended::$pattern, $id, $matches ) ) {
			
			$class = 'Lazy_Partial_Extended';
		}
		
		return $class;
	}
	
}
