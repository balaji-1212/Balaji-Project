<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );

use ETC\App\Controllers\Customizer;
/**
 * Etheme Customize Builders.
 *
 * Add builders to Wordpress Customizer.
 * Enqueue style, sctipts and templates.
 * Declare callbacks for builders ajax functions.
 * Save and load data for presets.
 * Generate siple elements html.
 *
 * @since   1.0.0
 * @version 1.4.17
 * @var     $elements {array} list of all builder elements.
 * @var     $presets  {array} list of all presets to import/export.
 * @var     $post     {array} list of all $_POST data.
 * @log
 * 1.4.12
 * Added save-name/edit-name actions
 * Added copy btn for conditions
 * 1.4.13
 * Added default_header
 * 1.0.14
 * FIXED: language conditions for all site
 * 1.0.15
 * Change contitions loading/editing system
 * NEW: disable_customizer_sections
 * NEW: condition_path
 * 1.0.16
 * Change fonts loading system
 * NEW: public $fonts
 * NEW: set_multiple_fonts
 * NEW: collect_multiple_fonts
 * 1.4.17
 * Added multiple date
 */



class Etheme_Customize_header_Builder {

	// ! Declare default variables
	public $elements = array();
	public $presets  = array();
	public $post     = array();
	public $et_multiple = false;
	public $fonts  = array();

	// ! Main construct/ setup variables
	function __construct(){
		$this->post = $_POST;
		$this->elements = require( ET_CORE_DIR . 'app/models/customizer/builder/elements.php' );
        $this->et_multiple = ( isset( $_REQUEST['et_multiple'] ) && $_REQUEST['et_multiple'] !='' ) ? $_REQUEST['et_multiple'] : false;
		$this->fonts['etheme-fonts'] = get_option( 'etheme-fonts', array() );
		$this->fonts['builder-fonts'] = $this->fonts['builder-fonts-option'] = get_option( 'et_header_builder_fonts', array() );
	}


	/**
	 * Add builder actions.
	 *
	 * Actions to add builder template, scripts and ajax action to Wordpress Customizer.
	 *
	 * @since   1.4.4
	 * @version 1.0.2
	 */
	public function actions(){

        if ($this->et_multiple){



            add_action( 'customize_controls_init', function() {
                global $wp_customize;
                $wp_customize->set_preview_url(  add_query_arg( 'et_multiple', $_GET['et_multiple'], get_home_url() ) );
            } );

            $this->disable_customizer_sections();

            if ( is_customize_preview() ) {
                $headers = get_option('et_multiple_headers', false);
                $headers = json_decode($headers, true);



                $products = get_option('et_multiple_single_product', false);
	            $products = json_decode($products, true);

                //$_GET['et_multiple']

                if ($headers && is_array($headers) && isset($headers[$this->et_multiple]) ){
                    $this->set_header_element_theme_mod($headers[$this->et_multiple]['options']);
                    $this->set_theme_mods($headers[$this->et_multiple]['options']);
	                $current_multiple = $this->et_multiple;

	                add_action( 'wp_enqueue_scripts', function () use ($current_multiple){
		                wp_dequeue_style( 'xstore-kirki-styles' );
		                if ( function_exists('etheme_theme_header_styles')) {
			                etheme_theme_header_styles();
		                }
		                // Enqueue specific styles
		                wp_enqueue_style(
			                'xstore-header-' . $current_multiple,
			                set_url_scheme( wp_upload_dir( null, false )['baseurl'] . '/xstore/' . $current_multiple . '.css' ),
			                array(),
			                get_option( 'xstore_kirki_css_version', ET_CORE_VERSION ),
			                'all'
		                );
	                }, 55 );
                }

	            if ($products && is_array($products) && isset($products[$this->et_multiple]) ){
		            $this->set_single_product_element_theme_mod($products[$this->et_multiple]['options']);
		            $current_multiple = $this->et_multiple;
		            add_action( 'wp_enqueue_scripts', function () use ($current_multiple){
			            wp_dequeue_style( 'xstore-kirki-styles' );
			            wp_enqueue_style(
				            'xstore-single-product-' . $current_multiple,
				            set_url_scheme( wp_upload_dir( null, false )['baseurl'] . '/xstore/' . $current_multiple . '.css' ),
				            array(),
				            get_option( 'xstore_kirki_css_version', ET_CORE_VERSION ),
				            'all'
			            );
		            }, 55 );
	            }
            }
        }

		if ( is_admin() ) {

			// ! Add scripts and templates
			add_action( 'customize_controls_enqueue_scripts', array( $this, 'scripts' ) );

			// ! Add callbacks for ajax actions
			add_action( 'wp_ajax_et_ajax_popup', array( $this, 'et_ajax_popup' ) );
			add_action( 'wp_ajax_et_ajax_get_element', array( $this, 'et_ajax_get_element' ) );
			add_action( 'wp_ajax_et_ajax_get_presets', array( $this, 'et_ajax_get_presets' ) );
			add_action( 'wp_ajax_et_ajax_template', array( $this, 'template' ) );
			add_action( 'wp_ajax_et_ajax_switch_default', array( $this, 'switch_default' ) );
			add_action( 'wp_ajax_et_ajax_get_remote_preset', array( $this, 'et_ajax_get_remote_preset' ) );

			// ! Add callbacks for multiple headers/conditions ajax actions
			add_action( 'wp_ajax_et_ajax_multiple', array( $this, 'et_ajax_multiple' ) );
			add_action( 'wp_ajax_et_ajax_conditions_data', array( $this, 'et_ajax_conditions_data' ) );
			add_action( 'wp_ajax_et_ajax_conditions_actions', array( $this, 'et_ajax_conditions_actions' ) );
			add_action( 'wp_ajax_et_ajax_builder', array( $this, 'et_ajax_builder' ) );

			add_action( 'customize_controls_print_footer_scripts', array( $this, 'etheme_builders_ajax_notices' ) );

			$_regenerate_xstore_header_builder = get_option( 'xstore_kirki_hb_render', 'generate' );
			if ( 'generate' ===  $_regenerate_xstore_header_builder ) {
				update_option( 'xstore_kirki_hb_render', 'regenerated', 'no' );
				add_action( 'admin_init', array($this, 'generate_header_builder_style'), 99 );
			}

			$_regenerate_xstore_single_product_style = get_option( 'xstore_kirki_sp_render', 'generate' );
			if ( 'generate' ===  $_regenerate_xstore_single_product_style ) {
				update_option( 'xstore_kirki_sp_render', 'regenerated', 'no' );
				add_action( 'admin_init', array($this, 'generate_single_product_style'), 100 );
			}

		} else {
//			add_action( 'etheme_last_style', array( $this, 'et_multiple_headers' ), 99 );
			remove_action('wp_enqueue_scripts', 'etheme_theme_header_styles', 45);
			add_action( 'wp_enqueue_scripts', array( $this, 'et_multiple_headers' ), 35 );
//			add_action( 'etheme_last_style', array( $this, 'et_multiple_single_product' ), 100 );
			add_action( 'wp_enqueue_scripts', array( $this, 'et_multiple_single_product' ), 40 );
		}

		add_action( 'customize_save_after', array( $this, 'generate_header_builder_style' ) );
		add_action( 'customize_save_after', array( $this, 'generate_single_product_style' ) );

		//add_action( 'admin_init', array( $this, 'default_header' ) );
	}

    /**
     * disable customizer sections and panels
     *
     * @since   4.1.6
     * @version 1.0.0
     */
    public function disable_customizer_sections(){
        add_filter( 'et/customizer/add/sections', function($sections){
            unset( $sections['general'] );
            unset( $sections['breadcrumbs'] );
            unset( $sections['mobile_panel'] );
            unset( $sections['style'] );
            unset( $sections['typography-content'] );
            unset( $sections['portfolio'] );
            unset( $sections['social-sharing'] );
            unset( $sections['general-page-not-found'] );
            unset( $sections['title_tagline'] );
            unset( $sections['general-optimization'] );
            unset( $sections['colors'] );
            unset( $sections['widgets-inspector-sidebar-widgets-main-sidebar'] );
            unset( $sections['custom_css'] );
            unset( $sections['static_front_page'] );
            unset( $sections['cei-section'] );
            unset( $sections['static_front_page'] );
            return $sections;
        }, 101);

		// rename woocommerce to open ability for remove_panel( 'woocommerce')
	    add_filter( 'et/customizer/add/panels', function($panels){
		    $panels['single_product_builder']['panel'] = 'woocommerce_holder';
		    return $panels;
	    }, 999);

        function remove_customize_panels( $wp_customize ) {
            //    $wp_customize->remove_panel( 'nav_menus');
            $wp_customize->remove_panel( 'style-custom_css');
            $wp_customize->remove_panel( 'blog');
            //$wp_customize->remove_panel( 'widgets');
            $wp_customize->remove_panel( 'title_tagline');
            $wp_customize->remove_panel( 'shop');
            $wp_customize->remove_panel( 'shop-elements');
            $wp_customize->remove_panel( 'cart-page');
//            $wp_customize->remove_panel( 'single_product_builder');
            // theme


	        if(isset($_REQUEST['autofocus'])) {
		        if( $_REQUEST['autofocus']['panel'] != 'single_product_builder'){
			        $wp_customize->remove_panel( 'single_product_builder');

		        } elseif($_REQUEST['autofocus']['panel'] != 'header-builder') {
			        $wp_customize->remove_panel( 'header-builder');
		        }
	        }
	        $wp_customize->remove_panel( 'woocommerce');

            $wp_customize->remove_panel( 'footer');
            // woocommerce
//            $wp_customize->remove_panel( 'woocommerce');
        }
        add_action( 'customize_register', 'remove_customize_panels',101 );
    }

    /**
     * Get condition path
     *
     * @since   4.1.6
     * @version 1.0.0
     */
	public function condition_path($builder, $key, $separator){
	    if (!$separator){
            $separator = '/';
        }
        if ( $builder == 'header' ) {
            $conditions = $this->get_json_option('et_multiple_conditions');
        } elseif( $builder == 'single-product' ){
            $conditions = $this->get_json_option('et_multiple_single_product_conditions');
        }

        $titles = $this->condition_default_select_data();

        $return = '';
        foreach ( $conditions as $v ) {
            if ( $key == $v['header'] ) {
                if ( $builder == 'header' ) {
                    $return .= $titles[$v['primary']]['title'];
                } else {
                    if ( is_array( $v['primary'] ) ) {
                        $post_type = get_post_type_object($v['primary']['post_type']);
                        $return .= $post_type->label . $separator . $v['primary']['title'];
                    } elseif ( ! empty( $v['primary'] ) )  {
                        $return .= $separator. $titles[$v['primary']]['title'];
                    }
                }

                if ( isset( $v['secondary'] ) ) {
                    if ( is_array( $v['secondary'] ) ) {
                        $post_type = get_post_type_object($v['secondary']['post_type']);
                        $return .= $separator . $post_type->label . $separator . $v['secondary']['title'];
                    } elseif ( ! empty( $v['secondary'] ) )  {
                        $return .= $separator. $titles[$v['secondary']]['title'];
                    }
                }
                if ( isset( $v['third'] ) && ! empty( $v['third'] ) ) {
                    $atts	          = array();
                    $atts['selected'] = $v['third'];

                    if ( $builder == 'header' ) {
                        $atts['data']     = $v['secondary'];
                    } else {
                        $atts['data']     = $v['primary'];
                    }

                    $selected = $this->condition_select_data($atts);
                    $return .= $separator. $selected[0]['text'];
                }
                return $return;
            }

        }


    }

	/**
	 * Ajax notices for builders
	 *
	 * @since   1.5.4
	 * @version 1.0.0
	 */
	public function etheme_builders_ajax_notices() {
		$notices = require_once( ET_CORE_DIR . 'app/models/customizer/builder/ajax-notices.php' );
		echo '<script type="text/html" id="etheme_ajax-notices">' . json_encode($notices) . '</script>';
	}

	/**
	 * Add script and styles to Wordpress Customizer
	 *
	 * @since   1.0.0
	 * @version 1.0.1
	 */
	public function scripts() {
		// wp_enqueue_style( 'builder',  ET_CORE_URL . 'app/models/customizer/builder/css/builder.css', array('etheme_admin_css') );
		// if ( is_rtl() ) {
		// 	wp_enqueue_style( 'builder-rtl',  ET_CORE_URL . 'app/models/customizer/builder/css/builder-rtl.css', array('etheme_admin_css', 'builder') );
		// }

		// wp_enqueue_script('etheme-builder', ET_CORE_URL . 'app/models/customizer/builder/js/builder.min.js', array(
		// 	'customize-controls',
		// 	'jquery-ui-resizable',
		// 	'jquery-ui-droppable',
		// 	'jquery-ui-draggable',
		// ));
	}

	/**
	 * Get remote preset
	 *
	 * @since   1.4.1
	 * @version 1.0.0
	 */
	public function et_ajax_get_remote_preset(){
		$url  = 'http://8theme.com/import/xstore-headers/';
		$url  = $url . $this->post['preset']  .'.json';
		$data = file_get_contents( $url );
		$data = json_encode( $data );
		echo $data;
		die();
	}

	/**
	 * Get presets data.
	 *
	 * et_set_presets() callback.
	 * This function in "framework/builder/js/builder.js".
	 * Get data from preset files located in "framework/builder/presets".
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 * @return  {string} presets structure and settings on json string
	 */
	public function et_ajax_get_presets(){
		$file = $this->post['preset'] . '.json';

		if ( isset( $this->post['header-for-version'] ) ) {
			$file = $this->post['preset'] . '.options.json';
			$url  = ET_CORE_DIR . 'app/models/customizer/builder/headers/' . $file;
		} else {
			$url  = ET_CORE_DIR . 'app/models/customizer/builder/presets/' . $file;
		}

		$data = file_get_contents( $url );
		$data = json_decode( $data, true );
		$this->post['template'] = 'header';
		$data['template'] = $this->template( $data );
		$data = json_encode( $data );
		echo $data;
		die();
	}

	/**
	 * Return elements html.
	 *
	 * et_connect_block.removeAllInside() callback.
	 * This function in "framework/builder/js/builder.js".
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 * @return  {html} html of element
	 */
	public function et_ajax_get_element(){
		$elements = array();
		$html     = '';
		if ( $this->post['elements'] == 'product-column' ) {
			$args = array(
				'id'     => ( isset($this->post['id']) && $this->post['id'] ) ? $this->post['id'] : $this->generate_random( 5 ),
				'index'  => $this->post['index'],
				'sticky' => $this->post['sticky']
			);
			$html .= $this->generate_html( $args, 'product-column' );
		} else {
			if ( $this->post['elements'] && count( $this->post['elements'] ) ) {       
				foreach ($this->post['elements'] as $key => $value) {
					$elements[$key] = $this->elements[$key];
				}

				foreach ($elements as $key => $value){
					$args = array(
						'id'       => $this->generate_random( 5 ),
						'element'  => $key,
						'icon'     => $value['icon'],
						'title'    => $value['title'],
						'section'  => $value['section'],
						'parent'   => isset( $value['parent'] ) ? $value['parent'] : '',
						'section2' => ( isset( $value['section2'] ) ) ? '<span class="dashicons dashicons-networking et_edit mtips" data-section="'.$value['section2'].'"><span class="mt-mes">'.esc_html__( 'Dropdown settings', 'xstore-core' ).'</span></span>' : '',
						'element_info' => ( isset( $element['element_info'] ) ) ? '<span class="dashicons dashicons-lightbulb mtips mtips-lg"><span class="mt-mes">'.$element['element_info'].'</span></span>' : '',
					);

					$html .= $this->generate_html( $args );
				}
			}
		}

		echo $html;
		die();
	}

	/**
	 * Return popup html.
	 *
	 * et_connect_block.popup() callback.
	 * This function in "framework/builder/js/builder.js".
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 * @return  {html} html of connect_block popup
	 */
	public function et_ajax_popup(){
		require_once( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/popup-connect-block.php' );
		die();
	}

	/**
	 * Get builder template.
	 *
	 * Used to display builder html.
	 * et_ajax_get_presets() callback when load preset.
	 * This function in "framework/builder/js/builder.js".
	 *
	 * @since   1.0.0
	 * @version 1.0.1
	 * @return  {html} html of builder templates
	 * @log
	 * 1.0.1
	 * Changed paths to some template files
	 */
	public function template( $ajax = false ) {
		ob_start();

		$id = $ajax;

        if ( isset($this->post['builder']) && isset($this->post['et_multiple']) ){
            $id = $this->post['et_multiple'];
        }

        add_filter( 'Etheme_Customize_Builder_ajax', function($type) use ($id){ return $id; } );

        switch ( $this->post['template'] ) {
				case 'header':
					require_once( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/header.php' );
					break;
				case 'popup-headers':
					require_once( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/popup-headers.php' );
					break;
				case 'popup-headers-mob':
					require_once( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/popup-headers-mob.php' );
					break;
				case 'popup-presets':
					require_once( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/popup-presets.php' );
					break;
				case 'popup-preset':
					if ( isset( $this->post['type'] ) && $this->post['type'] == 'edit' ) {
						$preset_id = $this->post['id'];
						add_filter( 'popup_preset_type', function($type){ return 'edit'; } );
						add_filter( 'popup_preset_id', function($id) use ($preset_id){ return $preset_id; } );
					}
					require_once( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/popup-preset.php' );
					break;
				case 'presets-custom':
					require_once( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/presets-custom.php' );
					break;
				case 'multiple-section':
					require_once( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/conditions/multiple-section.php' );
					break;
				case 'multiple-headers':
					require_once( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/conditions/multiple-'.$this->post['builder'].'.php' );
					break;
				case 'multiple-condition':
					require_once( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/conditions/multiple-condition-'.$this->post['builder'].'.php' );
					break;
				case 'product-single':
					require_once( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/product-single.php' );
					break;
				case 'product-archive':
					require_once( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/product-archive.php' );
					break;
				case 'popup-section-options':
					require_once( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/popup-section-options.php' );
					break;
                case 'multiple-popup-create':
                    require_once( ET_CORE_DIR . 'app/models/customizer/builder/template-parts/conditions/multiple-popup-create.php' );
                    break;
				default:
					# code...
					break;
			}
		if ( $ajax ) {
			return ob_get_clean();
		}
		die();
	}

	/**
	 * Multiple headers
	 *
	 * Setup Multiple headers for frontend.
	 *
	 * @since   1.4.4
	 * @version 1.0.1
	 * @return  {boolean} false or nothing.
	 */
	public function generate_header_builder_style( $requested = 'all' ){

		if ( '' == $requested || is_a( $requested, 'WP_Customize_Manager' ) || empty($requested) ) {
			$requested = 'all';
		}

		$headers   = $this->get_json_option('et_multiple_headers');

		foreach ( $headers as $options => $option ) {
			// Check if we want to save specific header
			//if ( 'all' !== $requested && $requested !== $options ) {
			//	continue;
			//}

			$this->set_theme_mods($option['options']);
			$Customizer = Customizer::get_instance( 'ETC\App\Models\Customizer' );
			$Customizer->customizer_style($options);
		}

	}

	/**
	 * Multiple headers
	 *
	 * Setup Multiple headers for frontend.
	 *
	 * @since   1.4.4
	 * @version 1.0.0
	 * @return  {boolean} false or nothing.
	 */
	public function et_multiple_headers(){
		if ( is_customize_preview() ) {
			if ( function_exists('etheme_theme_header_styles')) {
				etheme_theme_header_styles();
			}
		   return false;
		}

		$headers   = $this->get_json_option('et_multiple_headers');
		$condition = $this->get_current_condition();
		$loaded = false;

		if ( count( $condition ) ) {
			$condition = end( $condition );
			if ( isset( $headers[$condition['header']] ) ) {
				do_action('etheme_load_multiple_styles');
				// Change theme mod for elements location according to the condition
				$this->set_header_element_theme_mod($headers[$condition['header']]['options']);
				$this->set_theme_mods($headers[$condition['header']]['options']);
				wp_dequeue_style( 'xstore-kirki-styles' );
				if ( function_exists('etheme_theme_header_styles')) {
					etheme_theme_header_styles();
				}
				// Enqueue specific styles
				wp_enqueue_style(
					'xstore-header-' . $condition['header'],
					set_url_scheme( wp_upload_dir( null, false )['baseurl'] . '/xstore/' . $condition['header'] . '.css' ),
					array(),
					get_option( 'xstore_kirki_css_version', ET_CORE_VERSION ),
					'all'
				);
				$loaded = true;
			}
		}
		if ( !$loaded && function_exists('etheme_theme_header_styles')) {
			etheme_theme_header_styles();
		}
	}

	/**
	 * Multiple headers
	 *
	 * Setup Multiple headers for frontend.
	 *
	 * @since   1.4.4
	 * @version 1.0.1
	 * @return  {boolean} false or nothing.
	 */
	public function generate_single_product_style( $requested = 'all' ){

		if ( '' == $requested || is_a( $requested, 'WP_Customize_Manager' ) || empty($requested) ) {
			$requested = 'all';
		}

		$products   = $this->get_json_option('et_multiple_single_product');

		foreach ( $products as $options => $option ) {
			// Check if we want to save specific product
//			if ( 'all' !== $requested && $requested !== $options ) {
//				continue;
//			}
			$this->set_theme_mods($option['options']);
			$Customizer = Customizer::get_instance( 'ETC\App\Models\Customizer' );
			$Customizer->customizer_style($options);
		}

	}

	/**
	 * Multiple single product
	 *
	 * Setup Multiple headers for frontend.
	 *
	 * @since   1.5.0
	 * @version 1.0.0
	 * @return  {boolean} false or nothing.
	 */
	public function et_multiple_single_product(){
		if ( is_customize_preview() ) {
		   return false;
		}

		if ( ! get_option( 'etheme_single_product_builder', false ) ) {
		   return false;
		}

		$headers   = $this->get_json_option('et_multiple_single_product');
		$condition = $this->get_current_condition_single_product();

		if ( count( $condition ) ) {
			$condition = end( $condition );

			if ( isset( $headers[$condition['header']] ) ) {
				do_action('etheme_load_multiple_styles');
				// Set single product elements
				$this->set_single_product_element_theme_mod($headers[$condition['header']]['options']);
				wp_localize_script( 'etheme', 'etMultiple', array( 'singleProduct'=>$condition['header'] ) );
//				$this->set_theme_mods($headers[$condition['header']]['options']);
				wp_dequeue_style( 'xstore-kirki-styles' );
				// Enqueue specific styles
				wp_enqueue_style(
					'xstore-single-product-' . $condition['header'],
					set_url_scheme( wp_upload_dir( null, false )['baseurl'] . '/xstore/' . $condition['header'] . '.css' ),
					array(),
					get_option( 'xstore_kirki_css_version', ET_CORE_VERSION ),
					'all'
				);
			}
		}
	}

	/**
	 * Get current condition single product
	 *
	 * Filter conditions and return page custom header condition.
	 *
	 * @since   1.5.0
	 * @version 1.0.1
	 * @return  {array} current conditions data.
	 * 1.0.2
	 * Added sub-items checkbox
	 * Added languages select
	 */
	public function get_current_condition_single_product(){
		$condition  = array();
		if ( class_exists('WooCommerce') && is_product() ) {

			$conditions = $this->get_json_option( 'et_multiple_single_product_conditions' );
			
			if ( count((array)$conditions) < 1) {
				return $condition;
			}
			
			$current_post_id = get_the_ID();
			$current_post_type = get_post_type($current_post_id);
			
			add_filter( 'et_multiple_conditions_get_the_id', function($id) use ($current_post_id){ return $current_post_id; } );
			add_filter( 'et_multiple_conditions_get_post_type', function($post_type) use ($current_post_type){ return $current_post_type; } );

			if ( defined('ICL_LANGUAGE_CODE') ){
				$conditions = array_filter( $conditions, function($var){
					if (isset($var['language'])){
						return( $var['language'] == ICL_LANGUAGE_CODE );
					}
				});
			}

			$condition = array_filter( $conditions, function($var){
				if ( is_array($var['primary']) && isset( $var['primary']['post_type'] ) && $var['third'] != '' ) {
					$id = apply_filters('et_multiple_conditions_get_the_id', 0);
					$post_type = apply_filters('et_multiple_conditions_get_post_type', 'post');
					return( $var['primary']['post_type'] == $post_type && $var['third'] == $id );
				}
			});

			if ( ! count($condition) ) {
				$condition = array_filter( $conditions,function($var){
					if ( is_array($var['primary']) && isset( $var['primary']['post_type'] ) && $var['third'] != '' && $var['primary']['type'] == 'in' ) {
						$id = apply_filters('et_multiple_conditions_get_the_id', 0);
						$post_type = apply_filters('et_multiple_conditions_get_post_type', 'post');
						$isd = wp_get_post_terms( $id, $var['primary']['slug'], array('fields' => 'ids'));
						return( $var['primary']['post_type'] == $post_type && in_array($var['third'], $isd) );
					}
				});
			}

			// sub-items checkbox
			if ( ! count($condition) ) {
				$condition = array_filter( $conditions,function($var){
					if (
						isset($var['sub-items'])
						&& $var['sub-items'] == 'true'
						&& is_array($var['primary'])
						&& isset( $var['primary']['post_type'] ) &&
						$var['third'] != '' &&
						$var['primary']['type'] == 'in'
					) {
						$id = apply_filters('et_multiple_conditions_get_the_id', 0);
						$post_type = apply_filters('et_multiple_conditions_get_post_type', 'post');
						$terms = wp_get_post_terms( $id, $var['primary']['slug'], array('fields' => 'all'));
						$return = false;
						foreach ( $terms as $item ) {
							if ($item->parent == $var['third']){
								$return = true;
							}
						}
						return ( $return && $var['primary']['post_type'] == $post_type );
					}
				});
			}

			if ( ! count($condition) ) {
				$condition = array_filter($conditions,function($var){
					return( $var['primary']['type'] == 'all' && $var['primary']['slug'] != 'in_product' );
				});
			}

			if ( ! count($condition) && defined('ICL_LANGUAGE_CODE') ){
				$condition = array_filter( $conditions, function($var){
					if (isset($var['language'])){
						return( $var['language'] == ICL_LANGUAGE_CODE );
					}
				});
			}

		}
		return $condition;
	}

	/**
	 * Get current condition
	 *
	 * Filter conditions and return page custom header condition.
	 *
	 * @since   1.4.4
	 * @version 1.0.3
	 * @return  {array/object/null} current conditions data.
	 * @log
	 * 1.0.2
	 * Added sub-items checkbox
	 * Added languages select
	 * 1.0.3
	 * FIXED: language conditions for all site
	 */
	public function get_current_condition(){
		$condition  = array();
		$conditions = $this->get_json_option( 'et_multiple_conditions' );
		
		if ( count( (array)$conditions) < 1) {
			return $condition;
		}
		
		$current_post_id = get_the_ID();
		$current_post_type = get_post_type($current_post_id);
		
		add_filter( 'et_multiple_conditions_get_the_id', function($id) use ($current_post_id){
			return $current_post_id;
		} );
		add_filter( 'et_multiple_conditions_get_post_type', function($post_type) use ($current_post_type){ return $current_post_type; } );

		if ( defined('ICL_LANGUAGE_CODE') ){
			$conditions = array_filter( $conditions, function($var){
				if (isset($var['language'])){
					return( $var['language'] == ICL_LANGUAGE_CODE );
				}
			});
		}

		if ( is_singular() ) {
			$condition = array_filter( $conditions,  function($var){
				return($var['primary'] == 'singular');
			});

			$condition = array_filter( $condition, function($var){
				if ( is_array($var['secondary']) && isset( $var['secondary']['post_type'] ) && $var['third'] != '' ) {
					$id = apply_filters('et_multiple_conditions_get_the_id', 0);
					$post_type = apply_filters('et_multiple_conditions_get_post_type', 'post');
					return( $var['secondary']['post_type'] == $post_type && $var['third'] == $id );
				}
			});

			if ( ! count($condition) ) {
				$condition = array_filter( $conditions,function($var){
					if ( is_array($var['secondary']) && isset( $var['secondary']['post_type'] ) && $var['third'] != '' && $var['secondary']['type'] == 'in' ) {
						$id = apply_filters('et_multiple_conditions_get_the_id', 0);
						$post_type = apply_filters('et_multiple_conditions_get_post_type', 'post');
						$isd = wp_get_post_terms( $id, $var['secondary']['slug'], array('fields' => 'ids'));
						return( $var['secondary']['post_type'] == $post_type && in_array($var['third'], $isd) );
					}
				});
			}

			// child of
			if ( ! count($condition) ) {
				$condition = array_filter( $conditions,function($var){
					if ( is_array($var['secondary']) && isset( $var['secondary']['post_type'] ) && $var['third'] != '' && $var['secondary']['type'] == 'child_of' ) {
						$id = apply_filters('et_multiple_conditions_get_the_id', 0);
						return( $var['third'] == wp_get_post_parent_id($id) );
					}
				});
			}

			// sub-items checkbox
			if ( ! count($condition) ) {
				$condition = array_filter( $conditions,function($var){
					if (
						isset($var['sub-items'])
						&& $var['sub-items'] == 'true'
						&& is_array($var['secondary'])
						&& isset( $var['secondary']['post_type'] )
						&& $var['third'] != ''
						&& $var['secondary']['type'] == 'in'
					) {
						$id = apply_filters('et_multiple_conditions_get_the_id', 0);
						$post_type = apply_filters('et_multiple_conditions_get_post_type', 'post');
						$terms = wp_get_post_terms( $id, $var['secondary']['slug'], array('fields' => 'all'));
						$return = false;
						foreach ( $terms as $item ) {
							if ($item->parent == $var['third']){
								$return = true;
							}
						}
						return ( $return && $var['secondary']['post_type'] == $post_type );
					}
				});
			}

			if ( ! count($condition) ) {
				$condition = array_filter($conditions,function($var){
					if ( is_array($var['secondary']) && isset( $var['secondary']['post_type'] ) && $var['third'] == '' ) {
						$post_type = apply_filters('et_multiple_conditions_get_post_type', 'post');
						return( $var['secondary']['post_type'] == $post_type );
					}
				});
			}

			if ( ! count($condition) && is_front_page() ) {
				$condition = array_filter($conditions,function($var){
					return( $var['secondary'] == 'front_page' );
				});
			}

			if ( ! count($condition) && is_attachment() ) {
				$condition = array_filter($conditions, function($var){
				return( $var['secondary'] == 'attachment' );
				});
			}

			if ( ! count($condition) ) {
				$condition = array_filter($conditions,function($var){
					return( $var['secondary'] == 'all_singular' );
				});
			}

		} elseif( is_404() ){
			$condition = array_filter( $conditions,  function($var){
				return( $var['secondary'] == 'not_found' );
			});
		} elseif( is_archive() ) {

			$condition = array_filter( $conditions,  function($var){
				return($var['primary'] == 'archives');
			});

			$condition = array_filter( $condition, function($var){
				if ( is_array($var['secondary']) && isset( $var['secondary']['type'] ) && $var['third'] != '' ) {
					return( $var['secondary']['type'] == 'taxonomy' && is_tax($var['secondary']['slug'], $var['third']) );
				}
			});

			// sub-items checkbox
			if ( ! count($condition) ) {
				$condition = array_filter( $conditions,function($var){
					if (
						isset($var['sub-items'])
						&& $var['sub-items'] == 'true'
						&& is_array($var['secondary'])
						&& isset( $var['secondary']['post_type'] )
						&& $var['third'] != ''
					) {
						$id = apply_filters('et_multiple_conditions_get_the_id', 0);
						$terms = wp_get_post_terms( $id, $var['secondary']['slug'], array('fields' => 'all'));
						$return = false;
						foreach ( $terms as $item ) {
							if ($item->parent == $var['third']){
								$return = true;
							}
						}
						return ( $return && $var['secondary']['type'] == 'taxonomy');
					}
				});
			}

			if ( ! count($condition) && is_category() ) {
				$condition = array_filter( $conditions,  function($var){
					if ( is_array($var['secondary']) && isset( $var['secondary']['type'] ) && $var['secondary']['post_type'] == 'post' && $var['third'] != '' ) {
						return( is_category($var['third']) );
					}
				});
			}

			if ( ! count($condition) && is_tag() ) {
				$condition = array_filter( $conditions,  function($var){
					if ( is_array($var['secondary']) && isset( $var['secondary']['type'] ) && $var['secondary']['post_type'] == 'post' && $var['third'] != '' ) {
						return( is_tag($var['third']) );
					}
				});
			}

			if ( ! count($condition) && is_author() ) {
				$condition = array_filter( $conditions,  function($var){
					return($var['secondary'] == 'author');
				});
			}

			if ( ! count($condition) && ( is_day() || is_month() || is_year() ) ) {
				$condition = array_filter( $conditions,  function($var){
					return($var['secondary'] == 'date');
				});
			}

			if ( ! count($condition) && is_search() ) {
				$condition = array_filter( $conditions,  function($var){
					return($var['secondary'] == 'search');
				});
			}

			if ( ! count($condition) ) {
				$condition = array_filter( $conditions,  function($var){
					if ( is_array($var['secondary']) && isset( $var['secondary']['type'] ) && $var['secondary']['type'] == 'post_type_archive') {
						return( is_post_type_archive($var['secondary']['post_type']) );
					}
				});
			}

			if ( ! count($condition) ) {
				$condition = array_filter($conditions,function($var){
					return( $var['secondary'] == 'all_archives' );
				});
			}

			if ( ! count($condition) ) {
				$condition = array_filter( $conditions,  function($var){
					if ( is_array($var['secondary']) && isset( $var['secondary']['type'] ) && $var['third'] == '' ) {
						return( $var['secondary']['type'] == 'taxonomy' && is_tax($var['secondary']['slug']) );
					}
				});
			}
		}

		$page          = (array) get_query_var('et_page-id', array( 'id' => 0, 'type' => 'page' ));
		$page_id       = (int) $page['id'];
		$posts_page_id = (int) get_option( 'page_for_posts' );
		$wc_shop_page_id = ( function_exists('wc_get_page_id') ) ? wc_get_page_id('shop') : false;

		if ( $page_id === $posts_page_id ) {
			if ( ! count($condition) ) {
				$condition = array_filter($conditions,function($var){
					$page          = (array) get_query_var('et_page-id', array( 'id' => 0, 'type' => 'page' ));
					$page_id       = $page['id'];
					return( ( !is_array($var['secondary']) && $var['secondary'] == 'all_archives' ) || $var['third'] == $page_id );
				});
			}
		}
		elseif ( $wc_shop_page_id && $page_id === $wc_shop_page_id ) {
			if ( ! count($condition) ) {
				$condition = array_filter($conditions,function($var){
					$page          = (array) get_query_var('et_page-id', array( 'id' => 0, 'type' => 'page' ));
					$page_id       = $page['id'];
					return( $var['third'] == $page_id );
				});
			}
		}
		// FIXED: language conditions when no condition selected for all site(Default Header)
		// if ( ! count($condition) && defined('ICL_LANGUAGE_CODE') ){
		// 	$condition = array_filter( $conditions, function($var){
		// 		if (isset($var['language'])){
		// 			return( $var['language'] == ICL_LANGUAGE_CODE );
		// 		}
		// 	});
		// }

		// FIXED: Conditions for all site
		if(! count($condition)){
			$condition = array_filter( $conditions,  function($var){
				return( $var['primary'] == 'site'  );
			});
		}

		// FIXED: language conditions for all site
		if ( ! count($condition) && defined('ICL_LANGUAGE_CODE') ){
			$condition = array_filter( $conditions, function($var){
				if (isset($var['language'])){
					return( $var['language'] == ICL_LANGUAGE_CODE && $var['primary'] == 'site' );
				}
			});
		}
		
		return $condition;
	}

	/**
	 * Setup theme mods
	 *
	 * Setup header options for a current page based on it condition.
	 *
	 * @since   1.4.4
	 * @version 1.0.4
	 * @param   {array} $data Header options for a current condition.
	 */
	public function set_theme_mods($data){
		foreach ($data as $key => $value) {

			$value = apply_filters( 'et_render_multiple_theme_mod', $value, $key );

			if ( in_array( $key, array('header_socials_package_et-desktop', 'contacts_package_et-desktop', 'promo_text_package') ) ) {
				if ( ! is_array($value) ) {
					$value = json_decode(urldecode($value), true);
				}
			}
			
			if ( ! in_array( $key, array('header_top_elements', 'header_main_elements', 'header_bottom_elements', 'connect_block_package','header_mobile_top_elements','header_mobile_main_elements','header_mobile_bottom_elements','connect_block_mobile_package') ) ) {

				 if ( strpos($key, 'fonts_et-desktop') ) {
					 $this->collect_multiple_fonts($value);
				 }

				add_filter( "theme_mod_" . $key, function($current_mod) use ($value){
					if ( $value == 'false' ){
						$value = false;
					} elseif ( $value == 'true' ){
						$value = true;
					}
					return $value;
				} );
			}
		}
		$this->set_multiple_fonts();
	}

	/**
	 * Setup theme mods
	 *
	 * Setup header elements options for a current page based on it condition.
	 *
	 * @since   3.0.2
	 * @param   {array} $data Header options for a current condition.
	 */
	public function set_header_element_theme_mod($data){

	    if (isset($data['connect_block_package'])){
            add_filter( 'theme_mod_connect_block_package', function($current_mod) use ($data){
                return json_decode(base64_decode($data['connect_block_package']), true);
            } );
        }
        if (isset($data['connect_block_mobile_package'])){
            add_filter( 'theme_mod_connect_block_mobile_package', function($current_mod) use ($data){
                return json_decode(base64_decode($data['connect_block_mobile_package']), true);
            } );
        }
        if (isset($data['header_top_elements'])){
            add_filter( 'theme_mod_header_top_elements', function($current_mod) use ($data){
                return base64_decode($data['header_top_elements']);
            } );
        }
        if (isset($data['header_main_elements'])){
            add_filter( 'theme_mod_header_main_elements', function($current_mod) use ($data){
                return base64_decode($data['header_main_elements']);
            } );
        }

        if (isset($data['header_bottom_elements'])){
            add_filter( 'theme_mod_header_bottom_elements', function($current_mod) use ($data){
                return base64_decode($data['header_bottom_elements']);
            } );
        }

        if (isset($data['header_mobile_top_elements'])){
            add_filter( 'theme_mod_header_mobile_top_elements', function($current_mod) use ($data){
                return base64_decode($data['header_mobile_top_elements']);
            } );
        }

        if (isset($data['header_mobile_main_elements'])){
            add_filter( 'theme_mod_header_mobile_main_elements', function($current_mod) use ($data){
                return base64_decode($data['header_mobile_main_elements']);
            } );
        }

        if (isset($data['header_mobile_bottom_elements'])){
            add_filter( 'theme_mod_header_mobile_bottom_elements', function($current_mod) use ($data){
                return base64_decode($data['header_mobile_bottom_elements']);
            } );
        }
	}

	/**
	 * Setup theme mods
	 *
	 * Setup single product elements options for a current page based on it condition.
	 *
	 * @since   3.0.2
	 * @version 1.0.4
	 * @param   {array} $data single product for current condition.
	 */
	public function set_single_product_element_theme_mod($data){
		foreach ($data as $key => $value) {
			switch ($key){
				case 'product_single_elements':
					add_filter( 'theme_mod_product_single_elements', function($current_mod) use ($data){
						return base64_decode($data['product_single_elements']);
					} );
					break;
				
				case 'connect_block_product_single_package':
					add_filter( 'theme_mod_connect_block_product_single_package', function($current_mod) use ($data){
						return json_decode(base64_decode($data['connect_block_product_single_package']), true);
					} );
					break;
                case 'html_block2':
                    // Do nothing!
                    break;
				default:

					if ( strpos($key, 'fonts_et-desktop') ) {
						$this->collect_multiple_fonts($value);
					}
					
					add_filter( "theme_mod_" . $key, function($current_mod) use ($value){
						if ( $value == 'false' ){
							$value = false;
						} elseif ( $value == 'true' ){
							$value = true;
						}
						return $value;
					} );
					break;
			}
		}
		$this->set_multiple_fonts();
	}

	/**
	 * Get builder template
	 *
	 * Ajax action for import export.
	 * etThemeBuilder.etPresets.import_export() callback.
	 * This functions in "framework/builder/js/builder.js".
	 *
	 * @since   1.4.4
	 * @version 1.0.1
	 * @return  {json} string with data needed for action.
	 */
	public function et_ajax_builder(){
		if ($this->post['builder']!='header'){
			$this->post['template'] = 'product-single';
		} else {
			$this->post['template'] = 'header';
		}
		$options = array_merge( $this->post['options'] , $this->decode_special_options( $this->post['options'] ) );
		$data['builder'] = $this->template( $options );
		echo json_encode( $data );
		die();
	}

	/**
	 * Get builder template
	 *
	 * normalize customize repeater value
	 * in some cases it returns null value in array
	 *
	 * @since   4.1.6
	 * @version 1.0.0
	 * @return  {value} string of value
	 */
	public function et_normalize_customize_repeater_value($data){
		$data = str_replace('+', '%2b', $data);
		$data = json_decode(urldecode($data), true);
		foreach ($data as $key => $value) {
			if (! is_array($value)){
				unset($data[$key]);
			}
		}
		return urlencode(json_encode($data));
	}
  
	/**
	 * Multiple ajax actions 
	 *
	 * Ajax actions for header part of multiple headers.
	 * etThemeBuilder.etMultipleHeaders.et_headers_actions(), etThemeBuilder.etMultipleHeaders.et_load() callbacks.
	 * This functions in "framework/builder/js/builder.js".
	 * Callbacks for add/save/edit/configurate/remove actions.
	 *
	 * @since   1.5.1
	 * @version 1.0.5
	 * @return  {json} string with data needed for action.
	 * @log
	 * 1.0.3
	 * Added save-name/edit-name actions
	 * Added copy btn for conditions
	 * 1.0.4
	 * Added copy_default
	 * 1.0.5
	 * Added date
     *
	 */
	public function et_ajax_multiple(){
		if (  $this->post['builder'] == 'header' ) {
			$headers = $this->get_json_option('et_multiple_headers');
		} elseif( $this->post['builder'] == 'single-product' ){
			$headers = $this->get_json_option('et_multiple_single_product');
		}

		$Customizer = Customizer::get_instance( 'ETC\App\Models\Customizer' );

		switch ( $this->post['type'] ) {
			case 'add':
				$id = $this->generate_random( 5 );
				$headers[$id]['title'] = $this->post['header'];
				$headers[$id]['options'] = isset($this->post['options']) ? $this->post['options'] : array();

				$headers[$id]['options']['html_block1'] =  isset($headers[$id]['options']['html_block1']) ? base64_decode( $this->post['options']['html_block1'] ) : '';
				$headers[$id]['options']['html_block2'] =  isset($headers[$id]['options']['html_block2']) ? base64_decode( $this->post['options']['html_block2'] ) : '';
				$headers[$id]['options']['html_block3'] =  isset($headers[$id]['options']['html_block3']) ? base64_decode( $this->post['options']['html_block3'] ) : '';
				$headers[$id]['options']['html_block4'] =  isset($headers[$id]['options']['html_block4']) ? base64_decode( $this->post['options']['html_block4'] ) : '';
				$headers[$id]['options']['html_block5'] =  isset($headers[$id]['options']['html_block5']) ? base64_decode( $this->post['options']['html_block5'] ) : '';
				$headers[$id]['date']['published'] = time();

				$data['id'] = $id;

				break;
			case 'save':

                foreach ($this->post['options'] as $key => $value){
                    $headers[$this->post['header']]['options'][$key] = $value;
                }

				if (isset($this->post['options']['header_socials_package_et-desktop'])){
					$headers[$this->post['header']]['options']['header_socials_package_et-desktop'] = $this->et_normalize_customize_repeater_value($this->post['options']['header_socials_package_et-desktop']);
				}

				if (isset($this->post['options']['contacts_package_et-desktop'])){
					$headers[$this->post['header']]['options']['contacts_package_et-desktop'] = $this->et_normalize_customize_repeater_value($this->post['options']['contacts_package_et-desktop']);
				}

				if (isset($this->post['options']['promo_text_package'])){
					$headers[$this->post['header']]['options']['promo_text_package'] = $this->et_normalize_customize_repeater_value($this->post['options']['promo_text_package']);
				}

                if (isset($this->post['options']['header_vertical_section1_content']) && $this->post['options']['header_vertical_section1_content'] == 'empty'){
	                $headers[$this->post['header']]['options']['header_vertical_section1_content'] = array();
                }

				if (isset($this->post['options']['header_vertical_section2_content']) && $this->post['options']['header_vertical_section2_content'] == 'empty'){
					$headers[$this->post['header']]['options']['header_vertical_section2_content'] = array();
				}

				if (isset($this->post['options']['header_vertical_section3_content']) && $this->post['options']['header_vertical_section3_content'] == 'empty'){
					$headers[$this->post['header']]['options']['header_vertical_section3_content'] = array();
				}

				if (isset($headers[$this->post['header']]['options']['html_block1']) && isset($this->post['options']['html_block1']) ){
                    $headers[$this->post['header']]['options']['html_block1'] =  base64_decode( $this->post['options']['html_block1'] );
                }
                if (isset($headers[$this->post['header']]['options']['html_block2']) && isset($this->post['options']['html_block2']) ){
                    $headers[$this->post['header']]['options']['html_block2'] =  base64_decode( $this->post['options']['html_block2'] );
                }
                if (isset($headers[$this->post['header']]['options']['html_block3']) && isset($this->post['options']['html_block3']) ){
                    $headers[$this->post['header']]['options']['html_block3'] =  base64_decode( $this->post['options']['html_block3'] );
                }
				if (isset($headers[$this->post['header']]['options']['html_block4']) && isset($this->post['options']['html_block4']) ){
					$headers[$this->post['header']]['options']['html_block4'] =  base64_decode( $this->post['options']['html_block4'] );
				}
				if (isset($headers[$this->post['header']]['options']['html_block5']) && isset($this->post['options']['html_block5']) ){
					$headers[$this->post['header']]['options']['html_block5'] =  base64_decode( $this->post['options']['html_block5'] );
				}

				if ( isset( $this->post['options']['search_placeholder_et-desktop'] ) && isset($this->post['options']['search_placeholder_et-desktop']) ) {
					$headers[$this->post['header']]['options']['search_placeholder_et-desktop'] = apply_filters( 'et_save_multiple_theme_mod', $this->post['options']['search_placeholder_et-desktop'] );
				}

				$headers[$this->post['header']]['date']['modified'] = time();

				//if (  $this->post['builder'] == 'header' ) {
					// Regenrate css for header
					$this->generate_header_builder_style( $this->post['header'] );
				//} elseif( $this->post['builder'] == 'single-product' ){
					// Regenrate css for product
					$this->generate_single_product_style( $this->post['header'] );
				//}
				break;
			case 'remove':
				unset($headers[$this->post['header']]);

				if (  $this->post['builder'] == 'header' ) {
					$conditions = $this->get_json_option('et_multiple_conditions');
				} elseif( $this->post['builder'] == 'single-product' ){
					$conditions = $this->get_json_option('et_multiple_single_product_conditions');
				}

				foreach ( $conditions as $key => $value ) {
					if ( $value['header'] == $this->post['header'] ) {
						// Remove css file for header
						$Customizer->delete_css_file($value['header']);
						unset($conditions[$key]);
					}
					
				}

				foreach ( $conditions as $key => $value ) {
					if ( ! is_array( $value ) ) {
						// Remove css file for header
						$Customizer->delete_css_file($value['header']);
						unset($conditions[$key]);
					}
				}

				if (  $this->post['builder'] == 'header' ) {
					update_option('et_multiple_conditions', json_encode($conditions));

				} elseif( $this->post['builder'] == 'single-product' ){
					update_option('et_multiple_single_product_conditions', json_encode($conditions));
				}
				break;
			case 'get_option':
				// Get slected controls to load
				$data['controls'] = $Customizer->load_selected_control( array_combine( $this->post['options_list'] , $this->post['options_list'] ) );

				echo json_encode( $data );
				die();
				break;
			case 'copy':
				$id = $this->generate_random( 5 );
				$headers[$id] = $headers[$this->post['header']];
				$headers[$id]['title'] .= '(copy)';
				$data['id'] = $id;
				break;
			case 'copy_default':
				$id = $this->generate_random( 5 );
				$headers[$id] = $headers[$this->post['header']];
				$headers[$id]['title'] .= '(copy)';

				if ($this->post['builder'] == 'header'){
                    $headers[$id]['options'] = array();
                } else {
                    $headers[$id]['options'] = $this->post['options'];
                    $headers[$id]['options']['html_block1'] =  base64_decode( $this->post['options']['html_block1'] );
                    $headers[$id]['options']['html_block2'] =  base64_decode( $this->post['options']['html_block2'] );
                    $headers[$id]['options']['html_block3'] =  base64_decode( $this->post['options']['html_block3'] );
					$headers[$id]['options']['html_block4'] =  base64_decode( $this->post['options']['html_block4'] );
					$headers[$id]['options']['html_block5'] =  base64_decode( $this->post['options']['html_block5'] );
                    $headers[$id]['options']['search_placeholder_et-desktop'] = apply_filters( 'et_save_multiple_theme_mod', $this->post['options']['search_placeholder_et-desktop'] );
                }


				$data['id'] = $id;
				break;
			case 'save-name':
				$headers[$this->post['header']]['title'] = $this->post['title'];
				break;
			default:
				die();
				break;
		}

		$new_option = json_encode($headers);

		if ( $this->post['type'] == 'remove' || $new_option || $this->post['type'] == 'copy' ) {
			if (  $this->post['builder'] == 'header' ) {
				update_option('et_multiple_headers', $new_option);
			} elseif( $this->post['builder'] == 'single-product' ){
				update_option('et_multiple_single_product', $new_option);
			}
           // if (  $this->post['builder'] == 'header' ) {
                // Regenrate css for header
                $this->generate_header_builder_style($this->post['header']);
            //}

            //if ($this->post['builder'] == 'single-product'){
	            $this->generate_single_product_style( $this->post['header'] );
           // }
		}
		

		$this->post['template'] = 'multiple-headers';
		$data['headers'] = $this->template( true );

		echo json_encode( $data );
		die();
	}

	/**
	 * Decode special options
	 *
	 * use base64_decode() to decode array of options.
	 *
	 * @since   1.4.4
	 * @version 1.0.1
	 * @param   {array} $data options for decode.
	 * @return  {array}       array with base64 decoded options.
	 */
	public function decode_special_options($data){
		$return  = array();
		$options = array( 'header_top_elements','header_main_elements','header_bottom_elements','connect_block_package','header_mobile_top_elements','header_mobile_main_elements','header_mobile_bottom_elements','connect_block_mobile_package', 'product_single_elements', 'connect_block_product_single_package' );

		foreach ( $options as $value ) {
			if ( isset( $data[$value] ) ) {
				$return[$value] = base64_decode( $data[$value] );
			}
		}
		return $return;
	}

	/**
	 * Multiple headers conditions ajax actions 
	 *
	 * Ajax actions for conditions part of multiple headers.
	 * etThemeBuilder.etMultipleHeaders.et_headers_actions(), etThemeBuilder.etMultipleHeaders.et_conditions_actions() callbacks.
	 * This functions in "framework/builder/js/builder.js".
	 * Callbacks for add/save_all actions.
	 *
	 * @since   1.4.4
	 * @version 1.0.1
	 * @return  {json} string with data needed for action.
	 */
	public function et_ajax_conditions_actions(){
		switch ( $this->post['type'] ) {
			case 'add':
				$this->post['template'] = 'multiple-condition';
				$data['template']       = $this->template( 'et_add' );
				break;
			case 'save_all':

				if (  $this->post['builder'] == 'header' ) {
					$conditions = $this->get_json_option('et_multiple_conditions');
				} elseif( $this->post['builder'] == 'single-product' ){
					$conditions = $this->get_json_option('et_multiple_single_product_conditions');
				}

				if ( isset( $this->post['data_save'] ) && is_array( $this->post['data_save'] ) ) {
					foreach ($this->post['data_save'] as $key => $value) {
					    if (is_array($value) && isset($value['header'])){
                            $id = ( ! $value['condition'] ) ? $this->generate_random(7) : $value['condition'];
                            $conditions[$id] = $value;
                        }
					}
				}

				if ( isset( $this->post['data_remove'] ) && is_array( $this->post['data_remove'] ) ) {
					foreach ( $this->post['data_remove'] as $key => $value ) {
						unset($conditions[$value]);
					}
				}

				foreach ( $conditions as $key => $value ) {
					if ( ! is_array( $value ) ) {
						unset($conditions[$key]);
					}
				}

				if (  $this->post['builder'] == 'header' ) {
					$data['result'] = update_option('et_multiple_conditions', json_encode($conditions));

				} elseif( $this->post['builder'] == 'single-product' ){
					$data['result'] = update_option('et_multiple_single_product_conditions', json_encode($conditions));

				}

				$this->post['template'] = 'multiple-headers';
				$data['headers']        = $this->template( true );
				break;
			default:
				die();
				break;
		}
		echo json_encode($data);
		die();
	}

	/**
	 * Ajax conditions select2 data
	 *
	 * Return data for condition selects
	 * etThemeBuilder.etMultipleHeaders.et_conditions() callbacks.
	 * This functions in "framework/builder/js/builder.js".
	 *
	 * @since   1.4.4
	 * @version 1.0.0
	 * @return  {json} string with data needed for action.
	 */
	public function et_ajax_conditions_data(){
		echo json_encode( $this->condition_select_data( $_REQUEST ) );
		die();
	}


	/**
	 * Conditions select2 data
	 *
	 * Return data for condition selects
	 *
	 * @since   1.4.4
	 * @version 1.0.2
	 * @param   {array} $atts data for WP_Query or get_terms.
	 * @return  {array}       data for select2
	 */
	public function condition_select_data($atts){
		$data = array();
		if ( $atts['data'] ) {
			if ( $atts['data']['type'] == 'all' || $atts['data']['type'] == 'child_of' ) {
				 $args = array(
					'post_type'      => $atts['data']['post_type'],
					'post_status'    => 'any',
					'posts_per_page' => -1,
					'orderby'        => 'ID',
					'order'          => 'asc',
				);

				if ( isset($atts['search']) && $atts['search'] ) {
					$args['s'] = $atts['search'];
				}
				if ( isset($atts['selected']) && $atts['selected'] ) {
					$args['post__in'] = array($atts['selected']);
				}

				if ( defined('WPML_TM_VERSION') && defined('WPML_ST_VERSION') ) {
					$args['suppress_filters'] = true;
				}

				if( defined('WPML_TM_VERSION') && defined('WPML_ST_VERSION') && $atts['selected'] ){
					global $wpdb;

					$where = "WHERE post_type = '".$atts['data']['post_type']."'";
					$and   = "AND ID = '" . $atts['selected'] . "'";

					$posts = $wpdb->get_results ( " SELECT ID,post_title FROM  $wpdb->posts " . $where . $and );

				} else {
					$query = new WP_Query();
					$posts = $query->query($args);
				}
			
				foreach ($posts as $key => $value) {
					if( defined('WPML_TM_VERSION') && defined('WPML_ST_VERSION') ){
						$language_details = apply_filters( 'wpml_post_language_details', NULL, $value->ID ) ;
						$value->post_title = $value->post_title . ' (' . $language_details['language_code'] . ')';
					}

					$data[] = array(
						'id' => $value->ID,
						'text' => $value->post_title
					);
				}
			} elseif ( $atts['data']['type'] == 'in' || $atts['data']['type'] == 'taxonomy' ) {
				$args = array(
					'taxonomy' => $atts['data']['slug']
				);
				if ( isset($atts['search']) && $atts['search']) {
					$args['name__like'] = $atts['search'];
				}
				if ( isset($atts['selected']) && $atts['selected'] ) {
					$args['include'] = $atts['selected'];
				}

				if ( defined('WPML_TM_VERSION') && defined('WPML_ST_VERSION') ) {
					$args['suppress_filters'] = true;
				}

				$args['hide_empty'] = false;

				if ( defined('WPML_TM_VERSION') && defined('WPML_ST_VERSION') && $atts['selected'] ) {
					global $wpdb;
					$where   = "WHERE term_id = '" . $atts['selected'] . "'";
					$terms = $wpdb->get_results ( " SELECT term_id,name FROM  $wpdb->terms " . $where );
				} else {
					$terms = get_terms( $args );
				}

				foreach ($terms as $key => $value) {
					if (isset($value->count)){
						$value->name = $value->name . ' (' . $value->count . ')';
					}

					if( defined('WPML_TM_VERSION') && defined('WPML_ST_VERSION') ){

						$language_code = apply_filters( 'wpml_element_language_code', null, array( 'element_id'=> $value->term_id, 'element_type'=> $atts['data']['slug'] ) );
						$value->name = $value->name . ' (' . $language_code . ')';
					}

					$data[] = array(
						'id' => $value->term_id,
						'text' => $value->name
					);
				}
			} 
		}
		return $data;
	}

	/**
	 * Get all single
	 *
	 * Return data for condition all singular select
	 *
	 * @since   1.4.4
	 * @version 1.0.0
	 * @return  {array} data for condition all singular select
	 */
	public function get_all_single($type = false) {
		$post_types = get_post_types(
			array(
				'show_in_nav_menus' => true,
			),
			'objects'
		);

		$options = array();

		if ( ! $type ) {
			$options = array(
				'select' => array(
					'title' => __( 'Select', 'xstore-core' )
				),
				'all_singular' => array(
					'title' => __( 'All Singular', 'xstore-core' )
				),
				'front_page' => array(
					'title' => __( 'Front page', 'xstore-core' )
				),
			);
		}

		foreach ( $post_types as $post_type => $post_type_object ) {

			if ( $type && $type != $post_type ) {
				continue;
			}

			$post_type_taxonomies = get_object_taxonomies( $post_type, 'objects' );
			$post_type_taxonomies = wp_filter_object_list(
				$post_type_taxonomies,
				array(
					'public'             => true,
					'show_in_nav_menus'  => true,
					'publicly_queryable' => true,
				)
			);

			$tax = array();

			if ( count( $post_type_taxonomies ) ) {
				foreach ( $post_type_taxonomies as $slug => $object ) {
					$tax[] = array(
						'post_type' => $post_type,
						'type' => 'in',
						'slug' => $slug,
						'title' => sprintf( __( 'In %s', 'xstore-core' ), $object->labels->singular_name )
					);
				}
			}

			if ( $post_type_object->hierarchical ) {

				$tax[] = array(
					'post_type' => $post_type,
					'type' => 'child_of',
					'slug' => 'child',
					'title' => sprintf( __( 'Child of %s', 'xstore-core' ), $post_type_object->labels->name )
				);
			}

			if ( count( $tax ) ) {
				$options[ $post_type ] = array(
					'label' => $post_type_object->labels->singular_name,
					'options' => array_merge(
						array( array(
							'post_type' => $post_type,
							'type' => 'all',
							'slug' => 'all',
							'title' => sprintf( __( 'All %s', 'xstore-core' ), $post_type_object->labels->name )
						) ),
						$tax
					),
				);
			}
		}

		if ( ! $type ) {
			$options['attachment'] = array(
				'title' => __( 'Attachment', 'xstore-core' )
			);
			$options['not_found'] = array(
				'title' => __( 'Not found(404)', 'xstore-core' )
			);
		}
		return $options;
	}

	/**
	 * Get all archives
	 *
	 * Return data for condition all archives select
	 *
	 * @since   1.4.4
	 * @version 1.0.0
	 * @return  {array} data for condition all archives select
	 */
	public function get_all_archive() {
		$post_type_args = array(
			'show_in_nav_menus' => true,
		);

		if ( ! empty( $args['post_type'] ) ) {
			$post_type_args['name'] = $args['post_type'];
		}

		$_post_types = get_post_types( $post_type_args, 'objects' );
		$post_types  = [];

		foreach ( $_post_types as $post_type => $object ) {
			$post_types[ $post_type ] = $object->label;
		}

		$taxonomies =  array(
			'select' => array(
				'title' => __( 'Select', 'xstore-core' )
			),
			'all_archives' => array(
				'title' => __( 'All Archives', 'xstore-core' )
			),
			'author' => array(
				'title' => __( 'Author Archive', 'xstore-core' )
			),
			'date' => array(
				'title' => __( 'Date Archive', 'xstore-core' )
			),
			'search' => array(
				'title' => __( 'Search Results', 'xstore-core' )
			),
		);

		foreach ( $post_types as $post_type => $label ) {
			$post_type_object = get_post_type_object( $post_type );
			$post_type_taxonomies = get_object_taxonomies( $post_type, 'objects' );
			$post_type_taxonomies = wp_filter_object_list(
				$post_type_taxonomies,
				array(
					'public' => true,
					'show_in_nav_menus' => true,
				)
			);
			$tax = array();

			if ( $post_type_object->has_archive ) {
				$tax[] = array(
					'post_type' => $post_type,
					'type' => 'post_type_archive',
					'slug' => 'post_type_archive',
					'title' => $post_type_object->label
				);
			}

			if ( count( $post_type_taxonomies ) ) {
				foreach ( $post_type_taxonomies as $slug => $object ) {
					$tax[] = array(
						'post_type' => $post_type,
						'type' => 'taxonomy',
						'slug' => $slug,
						'title' => $object->label
					);
				}
			}

			if ( count( $tax ) ) {
				$taxonomies[ $post_type ] = array(
					'label' => $post_type_object->labels->name . ' ',
					'post_type' => $post_type,
					'options' => $tax,
				);
			}
		}
		return $taxonomies;
	}

	/**
	 * Condition default select data
	 *
	 * Used to get titles of Condition default select data.
	 *
	 * @since   1.4.4
	 * @version 1.0.0
	 * @return  {array} titles for default select data
	 */
	public function condition_default_select_data(){
		return array(
			'select' => array(
				'title' => __( 'Select', 'xstore-core' )
			),
			'all_archives' => array(
				'title' => __( 'All Archives', 'xstore-core' )
			),
			'author' => array(
				'title' => __( 'Author Archive', 'xstore-core' )
			),
			'date' => array(
				'title' => __( 'Date Archive', 'xstore-core' )
			),
			'search' => array(
				'title' => __( 'Search Results', 'xstore-core' )
			),
			'attachment' => array(
				'title' => __( 'Attachment', 'xstore-core' )
			),'not_found' => array(
				'title' => __( 'Not found(404)', 'xstore-core' )
			),
			'all_singular' => array(
				'title' => __( 'All Singular', 'xstore-core' )
			),
			'front_page' => array(
				'title' => __( 'Front page', 'xstore-core' )
			),
			'site' => array(
				'title' => __( 'Entire Site', 'xstore-core' )
			),
			'archives' => array(
				'title' => __( 'Archives', 'xstore-core' )
			),
			'singular' => array(
				'title' => __( 'Singular', 'xstore-core' )
			)
		);
	}

	/**
	 * Condition default select data
	 *
	 * Get option from wp_option and json_decode() it.
	 *
	 * @since   1.4.4
	 * @version 1.0.0
	 * @param   {string} $option wp_options option name
	 * @return  {array}          json decoded option or empty array
	 */
	public function get_json_option($option){
		if ( ! $option  ) {
			return array();
		}
		
		$option = get_option($option, false);
		$option = json_decode($option, true);
		if ( ! is_array($option) ) {
			$option = array();
		}
		return $option;
	}

	/**
	 * Generate random string.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 * @param   {integer} $length  length of random string
	 * @return  {string} random string
	 */
	public function generate_random( $length = 5 ){
		$string = '';
		$characters = '23456789ABCDEFHJKLMNPRTVWXYZabcdefghijklmnopqrstuvwxyz';
		for ( $i = 0; $i < $length; $i++ ) {
			$string .= $characters[mt_rand( 0, strlen( $characters ) -1 )];
		}
		return $string;
	}

	/**
	 * Generate html.
	 *
	 * Generate html blocks by params
	 *
	 * @since   1.0.1
	 * @version 1.0.1
	 * @param   {string} $element element that should be generated
	 * @param   {array}  $args    params for element
	 * @return  {html} html block
	 */
	public function generate_html( $args, $element = 'element' ){
		if ( $element == 'element' ) {
			return sprintf(
				'<div class="et_customizer-element ui-state-default %s" data-size="1" data-id="%s" data-element="%s">
					<span class="et_name"><span class="dashicons %s"></span>%s</span>
					<span class="et_actions">
						%s
						<span class="dashicons dashicons-admin-generic et_edit mtips" data-parent="%s" data-section="%s"><span class="mt-mes">'.esc_html__('Settings', 'xstore-core').'</span></span>%s
						<span class="dashicons dashicons-trash et_remove mtips"><span class="mt-mes">'.esc_html__('Remove', 'xstore-core').'</span></span>
					</span>
				</div>',
				isset( $args['class'] ) ? $args['class'] : '',
				$args['id'],
				$args['element'],
				$args['icon'],
				$args['title'],
				isset( $args['element_info'] ) ? $args['element_info'] : '',
				isset( $args['parent'] ) ? $args['parent'] : '',
				$args['section'],
				$args['section2']
			);
		} elseif( $element == 'product-column' ){
			$width = 30;
			return sprintf(
				'<div class="et_column et_col-sm-3 et_product-block ui-sortable-handle" data-id="element-%1$s" data-index="%2$s" data-title="%3$s" data-sticky="false">
					<div class="et_column-actions">
						<span class="dashicons dashicons-move"></span>
						<span class="dashicons dashicons-admin-generic et_style-column"></span>
						<span>%3$s</span>
						<span class="et_remove-column dashicons dashicons-trash"></span>
					</div>
					<div class="et_column-content-wrapper"><div class="et_column-content" data-name="%4$s">%5$s</div></div>
					<div class="et_column-settings">
						%7$s
						<div class="block-setting block-align customize-control-kirki-radio-buttonset flex align-items-center">
							<span class="et-title">%8$s</span>
							<div class="buttonset">
								<input class="switch-input screen-reader-text" type="radio" value="start" name="_customize-radio-block_align-%2$s" id="block_alignstart-%2$s" checked>

								<label for="block_alignstart-%2$s" class="switch-label switch-label-off">
									<span class="dashicons dashicons-editor-alignleft"></span>
									<span class="image-clickable"></span>
								</label>
								<input class="switch-input screen-reader-text" type="radio" value="center" name="_customize-radio-block_align-%2$s" id="block_aligncenter-%2$s">
									<label for="block_aligncenter-%2$s" class="switch-label switch-label-off">
										<span class="dashicons dashicons-editor-aligncenter"></span>
									<span class="image-clickable"></span>
								</label>
								<input class="switch-input screen-reader-text" type="radio" value="end" name="_customize-radio-block_align-%2$s" id="block_alignend-%2$s">
									<label for="block_alignend-%2$s" class="switch-label switch-label-off">
										<span class="dashicons dashicons-editor-alignright"></span>
									<span class="image-clickable"></span>
								</label>        
							</div>
						</div>
						<div class="block-setting block-width customize-control-kirki-slider flex align-items-center" data-default="' . $width . '">
							<div class="et-title">%6$s (&#37)</div>
							<div class="wrapper">
								<input type="range" min="0" max="100" step="1" value="' . $width . '" data-customize-setting-link="top_header_height">
								<span class="value">
									<input type="text" value="' . $width . '">
									<span class="suffix"></span>
								</span>
							</div>
						</div>
					</div>
				</div>',
				$this->generate_random( 5 ),
				$args['index'],
				esc_html__('Section', 'xstore-core') . $args['index'],
				esc_html__('Drop here', 'xstore-core'),
				'',
				esc_html__('Width', 'xstore-core'),
				( $args['sticky'] === 'true' ) ? 
				'<div class="customize-control-kirki-toggle et_column-edit et-custom-toggle">
					<label class="block-setting">
						<span class="et-title">'.esc_html__('Sticky', 'xstore-core').'</span>
						<span class="switch"></span>
					</label>
				 </div>' : '',
				 esc_html__('Alignment', 'xstore-core')
			);
		} else {
			return sprintf(
				'<div class="et_sortable et_column et_has-element et_col-sm-1" data-index="%s">
					<div class="et_customizer-element ui-state-default" data-offset="%s" data-size="%s" data-element="%s" data-id="%s">
						<span class="et_name"><span class="dashicons %s"></span>%s</span>
						<span class="et_actions">
							%s
							<span class="dashicons dashicons-admin-generic et_edit mtips" data-parent="%s" data-section="%s"><span class="mt-mes">'.esc_html__('Settings', 'xstore-core').'</span></span>%s
							<span class="dashicons dashicons-trash et_remove mtips"><span class="mt-mes">'.esc_html__('Remove', 'xstore-core').'</span></span>
						</span>
					</div>
				</div>',
				$args['index'],
				$args['offset'],
				$args['size'],
				$args['element'],
				$args['id'],
				$args['icon'],
				$args['title'],
				isset( $args['element_info'] ) ? $args['element_info'] : '',
				isset( $args['parent'] ) ? $args['parent'] : '',
				$args['section'],
				$args['section2']
			);
		}
	}

	/**
	 * Switch options.
	 *
	 * et_switch_default_header() callback.
	 * This function in "builder/js/builder.js".
	 * Switch default/builder header options
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function switch_default(){
		if ( $this->post['value'] === 'false' ) {
			$this->post['value'] = false;
		}
		update_option( 'etheme_'.$this->post['type'], $this->post['value'] );
		die();
	}

	/**
	 * Default header.
	 *
	 * @since   3.1.0
	 * @version 1.0.0
	 */
	public function default_header(){
		return;
		if ( false === get_theme_mod('header_top_elements', false) && false === get_theme_mod('header_main_elements', false) && false === get_theme_mod('header_bottom_elements', false) ) {
			$file = 'default1.json';
			$url  = ET_CORE_DIR . 'app/models/customizer/builder/presets/' . $file;
			$data = file_get_contents( $url );
			$data = json_decode( $data, true );
			if ($data){
				foreach ($data as $key => $value) {
					if ($key == 'options'){
						foreach ($value  as $k => $v) {
							set_theme_mod( $k, $v );
						}
					} else {
						set_theme_mod( $key, $value );
					}

					$Customizer = Customizer::get_instance( 'ETC\App\Models\Customizer' );
					$Customizer->customizer_style('kirki-styles');

				}
			}
		}
	}


	/**
	 * Collect multiple fonts.
	 *
	 * @since   4.3.4
	 * @version 1.0.0
	 */
	public function collect_multiple_fonts($value){
		if (
			is_array($this->fonts['etheme-fonts'])
			&& count($this->fonts['etheme-fonts'])
			&& ! empty( $value['font-family'] )
		) {
			if ( array_search( str_replace('"', '', $value['font-family']), array_column( $this->fonts['etheme-fonts'], 'name') ) === false ) {
				if ( ! isset( $value['font-weight'] ) ) {
					$value['font-weight'] = '';
				}
				$family = $value['font-family'] . ':' . $value['font-weight'] . '|' . $value['font-family'] . ':' . $value['variant'];
				if ( ! in_array( $value['font-family'], array( 'initial', 'inherit' ) ) ) {
					// Push new font to old ones
					array_push( $this->fonts['builder-fonts'], array('family' => $family, 'name' => str_replace( ' ', '', $value['font-family'] ) ) );
				}
			}
		}
	}

	/**
	 * Set multiple fonts.
	 *
	 * @since   4.3.4
	 * @version 1.0.0
	 */
	public function set_multiple_fonts(){
		if (
			is_array($this->fonts['builder-fonts'])
			&& count($this->fonts['builder-fonts'])
		) {
			$fonts = $this->fonts['builder-fonts'];

			if ( count($this->fonts['builder-fonts-option']) ){
				add_filter( "pre_option_et_header_builder_fonts", function($option) use ($fonts){
					return $fonts;
				} );
			} else {
				add_option( 'et_header_builder_fonts', array(), '', 'no' );
			}
		}
	}

	/**
	 * show multiple date.
	 *
	 * @since   4.3.6
	 * @version 1.0.0
	 */
	public function show_multiple_date($multiple){
        if ( isset($multiple['date']) ) {
	        $date = '';
	        if (isset($multiple['date']['modified'])){
		        $date .= '<span class="text-nowrap">' . 'Last Modified' . '</span>';
		        $date .= '<span class="text-nowrap">' . date("Y/m/d", $multiple['date']['modified']) . '</span>';
	        } else {
		        $date .= '<span class="text-nowrap">' . 'Published' . '</span>';
		        $date .= '<span class="text-nowrap">' . date("Y/m/d", $multiple['date']['published']) . '</span>';
	        }
	        echo '<span class="et_header-conditions-time">' . $date . '</span>';
        }
	}
}

$Etheme_Customize_header_Builder = new Etheme_Customize_header_Builder();
$Etheme_Customize_header_Builder -> actions();