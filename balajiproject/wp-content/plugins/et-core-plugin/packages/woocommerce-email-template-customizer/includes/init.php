<?php

namespace VIWEC\INC;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Auto load class
spl_autoload_register( function ( $class ) {
	$prefix   = __NAMESPACE__;
	$base_dir = __DIR__;
	$len      = strlen( $prefix );

	if ( strncmp( $prefix, $class, $len ) !== 0 ) {
		return;
	}

	$relative_class = strtolower( substr( $class, $len ) );
	$relative_class = strtolower( str_replace( '_', '-', $relative_class ) );
	$file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

	if ( file_exists( $file ) ) {
		require_once $file;
	} else {
		return;
	}
} );


/*
 * Initialize Plugin
 */

class Init {
	protected static $instance = null;
	public static $img_map;
	protected $cache_products = [];
	protected $cache_posts = [];

	private function __construct() {
		$this->define_params();
		$this->class_init();

		// add_action( 'init', array( $this, 'plugin_textdomain' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ), 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_run_file' ), 9999 );
		// add_action( 'admin_footer', array( $this, 'admin_footer' ) );
		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );

	}

	public static function init() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function define_params() {
	    $socials = [
			'facebook',
			'twitter',
			'instagram',
			'youtube',
			'linkedin',
			'whatsapp',
			'pinterest',
			'telegram',
			'tik-tok',
			'untapped',
			'vk',
		];
	    $socials_rendered = array();
		foreach ( $socials as $social ) {
			$socials_rendered[$social] = [
				''                => esc_html__( 'Disable', 'xstore-core' ),
				$social . '-black'        => esc_html__( 'Black', 'xstore-core' ),
				$social . '-white'        => esc_html__( 'White', 'xstore-core' ),
				$social . '-color'        => esc_html__( 'Color', 'xstore-core' ),
				$social . '-white-border' => esc_html__( 'White border', 'xstore-core' ),
				$social . '-black-border' => esc_html__( 'Black border', 'xstore-core' ),
				$social . '-color-border' => esc_html__( 'Color border', 'xstore-core' ),
				$social . '-color-white'  => esc_html__( 'Color - White', 'xstore-core' ),
				$social . '-black-white'  => esc_html__( 'Black - White', 'xstore-core' ),
				$social . '-white-color'  => esc_html__( 'White - Color', 'xstore-core' )
			];
	    }
		self::$img_map = apply_filters( 'viwec_image_map', [
			'infor_icons' => [
				'home'     => [
					'home-black'        => esc_html__( 'Black', 'xstore-core' ),
					'home-white'        => esc_html__( 'White', 'xstore-core' ),
					'home-white-border' => esc_html__( 'White/Border', 'xstore-core' ),
					'home-black-border' => esc_html__( 'Black/Border', 'xstore-core' ),
					'home-black-white'  => esc_html__( 'Black/White', 'xstore-core' ),
					'home-white-black'  => esc_html__( 'White/Black', 'xstore-core' ),
				],
				'email'    => [
					'email-black'        => esc_html__( 'Black', 'xstore-core' ),
					'email-white'        => esc_html__( 'White', 'xstore-core' ),
					'email-white-border' => esc_html__( 'White/Border', 'xstore-core' ),
					'email-black-border' => esc_html__( 'Black/Border', 'xstore-core' ),
					'email-black-white'  => esc_html__( 'Black/White', 'xstore-core' ),
					'email-white-black'  => esc_html__( 'White/Black', 'xstore-core' ),
				],
				'phone'    => [
					'phone-black'        => esc_html__( 'Black', 'xstore-core' ),
					'phone-white'        => esc_html__( 'White', 'xstore-core' ),
					'phone-white-border' => esc_html__( 'White/Border', 'xstore-core' ),
					'phone-black-border' => esc_html__( 'Black/Border', 'xstore-core' ),
					'phone-black-white'  => esc_html__( 'Black/White', 'xstore-core' ),
					'phone-white-black'  => esc_html__( 'White/Black', 'xstore-core' ),
				],
				'location' => [
					'location-white'        => esc_html__( 'Black', 'xstore-core' ),
					'location-black'        => esc_html__( 'White', 'xstore-core' ),
					'location-white-border' => esc_html__( 'White/Border', 'xstore-core' ),
					'location-black-border' => esc_html__( 'Black/Border', 'xstore-core' ),
					'location-black-white'  => esc_html__( 'Black/White', 'xstore-core' ),
					'location-white-black'  => esc_html__( 'White/Black', 'xstore-core' ),
				],
			],

			'social_icons' => $socials_rendered,
        
        ] );

	}

	public function class_init() {
		Email_Builder::init();
		if ( !get_option('etheme_built_in_email_builder_dev_mode', false) ) {
			Email_Trigger::init();
		}
		Compatible::init();
		include_once VIWEC_DIR . 'compatible' . DIRECTORY_SEPARATOR . 'email-template-customizer.php';
		include_once VIWEC_INCLUDES . 'functions.php';
	}

	public function register_libs_scripts() { 
		$scripts = [ 'tab', 'accordion', 'select2', 'dimmer', 'transition', 'modal' ];
		foreach ( $scripts as $script ) {
			wp_register_script( VIWEC_SLUG . '-' . $script, VIWEC_JS . 'libs/' . $script . '.min.js', [ 'jquery' ], VIWEC_VER );
		}

		$styles = [ 'tab', 'menu', 'accordion', 'select2', 'dimmer', 'transition', 'modal', 'button' ];
		foreach ( $styles as $style ) {
			wp_register_style( VIWEC_SLUG . '-' . $style, VIWEC_CSS . 'libs/' . $style . '.min.css', '', VIWEC_VER );
		}
	}

	public function register_exe_scripts() {
		$scripts = [ 'inputs', 'email-builder', 'properties', 'components' ];
		foreach ( $scripts as $script ) {
			wp_register_script( VIWEC_SLUG . '-' . $script, VIWEC_JS . $script . '.js', [ 'jquery' ], VIWEC_VER );
		}

		$styles = [ 'email-builder', 'admin' ];
		foreach ( $styles as $style ) {
			wp_register_style( VIWEC_SLUG . '-' . $style, VIWEC_CSS . $style . '.css', '', VIWEC_VER );
		}
	}

	public function admin_enqueue() {
		$this->register_libs_scripts();
		$this->register_exe_scripts();
		if ( !function_exists('get_current_screen')) return;
		global $post;
		switch ( get_current_screen()->id ) {
			case 'viwec_template':
				wp_enqueue_editor();
				wp_enqueue_media();
				wp_enqueue_script( 'wc-enhanced-select' );
				wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), [ 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ], false );
				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_script( 'jquery-ui-draggable' );
				wp_enqueue_script( 'wp-color-picker' );

				foreach ( [ 'tab', 'accordion', 'select2', 'dimmer', 'transition', 'modal', 'inputs', 'email-builder', 'properties', 'components' ] as $script ) {
					wp_enqueue_script( VIWEC_SLUG . '-' . $script );
				}
				foreach ( [ 'tab', 'menu', 'accordion', 'select2', 'dimmer', 'transition', 'modal', 'button', 'email-builder' ] as $style ) {
					wp_enqueue_style( VIWEC_SLUG . '-' . $style );
				}

				$samples      = Email_Samples::sample_templates();
				$hide_rule    = Utils::get_hide_rules_data();
				$hide_element = Utils::get_hide_elements_data();

				$params = [
					'ajaxUrl'             => admin_url( 'admin-ajax.php' ),
					'nonce'               => wp_create_nonce( 'viwec_nonce' ),
					'product'             => VIWEC_IMAGES . 'product.png',
					'post'                => VIWEC_IMAGES . 'post.png',
					'placeholder'         => VIWEC_IMAGES . 'placeholder.jpg',
					'emailTypes'          => Utils::get_email_ids_grouped(),
					'samples'             => $samples,
					'subjects'            => Email_Samples::default_subject(),
					'adminBarStt'         => Utils::get_admin_bar_stt(),
					'suggestProductPrice' => wc_price( 20 ),
					'homeUrl'             => home_url(),
					'siteUrl'             => site_url(),
					'shopUrl'             => wc_get_endpoint_url( 'shop' ),
					'adminEmail'          => get_bloginfo( 'admin_email' ),
					'adminPhone'          => get_user_meta( get_current_user_id(), 'billing_phone', true ) ?? '202-000-0000',
					'hide_rule'           => $hide_rule,
					'hide_element'        => $hide_element,
				];

				foreach ( self::$img_map['social_icons'] as $type => $data) {
//				    $data = self::$img_map['social_icons_options'];
					foreach ( $data as $key => $text ) {
//						$url = $key ? VIWEC_IMAGES . 'socials/' . $type . '/' . $key . '.svg' : ''; new but svg is not sending gmail
						$url = $key ? VIWEC_IMAGES . 'new-socials/' . $type . '/' . $key . '.png' : '';

						$params['social_icons'][ $type ][] = [ 'id' => $url, 'text' => $text, 'slug' => $key ];
					}
				}
				foreach ( self::$img_map['infor_icons'] as $type => $data ) {
					foreach ( $data as $key => $text ) {
						$params['infor_icons'][ $type ][] = [ 'id' => VIWEC_IMAGES . $key . '.png', 'text' => $text, 'slug' => $key ];
					}
				}

				$params['shortcode']             = array_keys( Utils::shortcodes() );
				$params['shortcode_for_replace'] = array_merge( Utils::shortcodes(), Utils::get_register_shortcode_for_replace() );

				$params['sc_3rd_party']                 = Utils::get_register_shortcode_for_builder();
				$params['sc_3rd_party_for_text_editor'] = Utils::get_register_shortcode_for_text_editor();

				$params['post_categories']    = $this->get_categories( 'category' );
				$params['product_categories'] = $this->get_categories( 'product_cat' );

				$email_structure = ( get_post_meta( $post->ID, 'viwec_email_structure', true ) );
				if ( $email_structure ) {
					$email_structure             = html_entity_decode( $email_structure );
					$json_decode_email_structure = json_decode( $email_structure, true );

					array_walk_recursive( $json_decode_email_structure, function ( $value, $key ) {
						if ( in_array( $key, [ 'data-coupon-include-product', 'data-coupon-exclude-product' ] ) ) {
							$value                = explode( ',', $value );
							$this->cache_products = array_merge( $this->cache_products, $value );
						}

						if ( in_array( $key, [ 'data-include-post-id', 'data-exclude-post-id' ] ) ) {
							$value             = explode( ',', $value );
							$this->cache_posts = array_merge( $this->cache_posts, $value );
						}
					} );

					$products_temp = [ [ 'id' => '', 'text' => '' ] ];
					$posts_temp    = [];

					if ( ! empty( $this->cache_products ) ) {
						$this->cache_products = array_values( array_unique( $this->cache_products ) );

						$products = wc_get_products( [ 'limit' => - 1, 'include' => $this->cache_products ] );
						if ( ! empty( $products ) ) {
							foreach ( $products as $p ) {
								$products_temp[] = [ 'id' => (string) $p->get_id(), 'text' => $p->get_name() ];
							}
						}
					}

					if ( ! empty( $this->cache_posts ) ) {
						$this->cache_posts = array_values( array_unique( $this->cache_posts ) );

						$posts = get_posts( [ 'numberposts' => 5, 'include' => $this->cache_posts ] );
						if ( ! empty( $posts ) ) {
							foreach ( $posts as $p ) {
								$posts_temp[] = [ 'id' => $p->ID, 'text' => $p->post_title, 'content' => do_shortcode( $p->post_content ) ];
							}
						}
					}

					wp_localize_script( VIWEC_SLUG . '-email-builder', 'viWecCachePosts', $posts_temp );
					wp_localize_script( VIWEC_SLUG . '-email-builder', 'viWecCacheProducts', $products_temp );
					wp_localize_script( VIWEC_SLUG . '-email-builder', 'viWecLoadTemplate', $email_structure );
				}

				$params['i18n'] = I18n::init();

				if ( ! empty( $_GET['sample'] ) ) {
					if ( ! isset( $_GET['action'] ) || $_GET['action'] !== 'edit' ) {
						$style            = ! empty( $_GET['style'] ) ? sanitize_text_field( $_GET['style'] ) : 'basic';
						$params['addNew'] = [ 'type' => sanitize_text_field( $_GET['sample'] ), 'style' => $style ];
					}
				}

				global $viwec_params;
				$viwec_params = $params;

				wp_localize_script( VIWEC_SLUG . '-inputs', 'viWecParams', $params );
				break;

			case 'edit-viwec_template':
				foreach ( [ 'form', 'segment', 'button', 'admin' ] as $style ) {
					wp_enqueue_style( VIWEC_SLUG . '-' . $style );
				}
				break;

			//Premium
			case 'viwec_template_page_viwec-auto-update':
				foreach ( [ 'form', 'segment', 'button', 'icon' ] as $style ) {
					wp_enqueue_style( VIWEC_SLUG . '-' . $style );
				}
				break;

			case 'viwec_template_page_viwec_report':
				wp_enqueue_style( VIWEC_SLUG . '-segment' );
				wp_enqueue_style( VIWEC_SLUG . '-admin' );
				break;
		}
	}

	public function get_categories( $type ) {
		$cats       = [];
		$categories = get_terms( $type, 'orderby=name&hide_empty=0' );
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $cat ) {
				$cats[] = [ 'id' => $cat->term_id, 'text' => $cat->name ];
			}
		}

		return $cats;
	}

	public function enqueue_run_file() {
		if ( !function_exists('get_current_screen')) return;
		if ( get_current_screen()->id === 'viwec_template' ) {
			Utils::enqueue_admin_scripts( [ 'run' ], [ 'jquery', 'jquery-ui-sortable', 'jquery-ui-draggable', 'wp-color-picker' ] );
		}
	}

	public function admin_body_class( $class ) {
		$admin_bar = Utils::get_admin_bar_stt();
		$class     = $admin_bar ? $class : $class . ' viwec-admin-bar-hidden';

		return $class;
	}

	public function admin_footer() {
		if ( !function_exists('get_current_screen')) return;
		if ( get_current_screen()->id === 'edit-viwec_template' ) {
			?>
            <div id="viwec-in-all-email-page">
				<?php do_action( 'villatheme_support_' . VIWEC_SLUG ); ?>
            </div>
		<?php }
	}

	public function auto_update() {
		do_action( 'villatheme_auto_update' );
	}

}

Init::init();

