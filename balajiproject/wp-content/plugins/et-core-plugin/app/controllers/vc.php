<?php

namespace ETC\App\Controllers;

use ETC\App\Controllers\Base_Controller;

use ETC\App\Controllers\Shortcodes as Shortcodes;
use XStoreCore\Modules\WooCommerce\XStore_Compare;

/**
 * VC registration.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
class VC extends Base_Controller {
	
	/**
	 * Registered widgets.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	public static $shortcodes = null;
	
	public static $product_template = null;
	
	public function hooks() {
	 
		add_action( 'init', array( $this, 'register_vc' ), 11 );
		add_action( 'init', function () {
			add_filter( 'vc_iconpicker-type-xstore-icons', array( $this, 'vc_iconpicker_type_xstore_icons' ) );
		}, 12 );
		// Etheme content product shortcode included to vc grid type
		add_filter( 'vc_grid_item_shortcodes', array($this, 'add_vc_grid_shortcodes') );
		add_filter( 'vc_font_container_output_data', array($this,'vc_font_container_output_data'), 4, 20 );
		
		add_filter( 'vc_google_fonts_get_fonts_filter', array($this, 'add_fonts'), 1, 10 );
		
		add_action( 'admin_print_scripts-post.php', array( $this, 'registerBackendCss' ) );
		add_action( 'admin_print_scripts-post-new.php', array( $this, 'registerBackendCss' ) );
		
		add_action( 'admin_init', array( $this, 'registerBackendJs' ), 999 );
		
		
		add_action('ma/icon_fonts/select', function() {
			?>
			<option value="xstore-icons"><?php _e( 'XStore Icons', 'xstore-core' ); ?></option>
			<?php
		});
		
		add_filter('ma/icon_font/url', array($this, 'ma_xstore_icons_font_url'), 10, 2);

		add_action('ma/icon_fonts/grid', array($this, 'ma_xstore_icons_font_list'));
	}
	
	public function ma_xstore_icons_font_url($is_custom_url, $font) {
		if ( $font == 'xstore-icons' ) {
			$is_custom_url = get_template_directory_uri() . '/css/xstore-icons.css';
		}
		return $is_custom_url;
	}
	
	public function ma_xstore_icons_font_list($font) {
		if ( $font === 'xstore-icons' ) :
			$class = self::get_instance();
			$xstore_icons = $class->vc_iconpicker_type_xstore_icons(array()); // tweak to get only xstore-icons
			?>
            <div class="mpc-xstore-icons">
				<?php foreach( $xstore_icons as $icon_key ) :
					foreach( $icon_key as $icon_real_key => $icon_value) : ?>
                        <i class="et-icon <?php esc_attr_e( $icon_value ); ?>" title="<?php esc_attr_e( $icon_value ); ?>"></i>
					<?php endforeach; ?>
				<?php endforeach; ?>
                <div class="mpc-copyright" style="clear: both;"><?php _e( 'XStore Icons set website: <a href="https://xstore.8theme.com/xstore-icons/">https://xstore.8theme.com/xstore-icons/</a>.', 'xstore-core' ); ?></div>
            </div>
		<?php
		endif;
	}
	
	public function registerBackendCss() {
		wp_register_style( 'xstore-icons-font', false );
		wp_enqueue_style( 'xstore-icons-font' );
		wp_add_inline_style( 'xstore-icons-font',
			"@font-face {
              font-family: 'xstore-icons';
              src:
                url('" . get_template_directory_uri() . "/fonts/xstore-icons-light.ttf') format('truetype'),
                url('" . get_template_directory_uri() . "/fonts/xstore-icons-light.woff2') format('woff2'),
                url('" . get_template_directory_uri() . "/fonts/xstore-icons-light.woff') format('woff'),
                url('" . get_template_directory_uri() . "/fonts/xstore-icons-light.svg#xstore-icons') format('svg');
              font-weight: normal;
              font-style: normal;
            }"
		);
		wp_enqueue_style( 'xstore-icons-font-style', get_template_directory_uri() . '/css/xstore-icons.css' );
	}
	
	public function registerBackendJs() {
		
		wp_enqueue_script( 'xstore-vc-config', ET_CORE_URL . 'config/vc/js/config.js', array(), false, true );
		
	}
	
	public function vc_iconpicker_type_xstore_icons( $icons ) {
		$xstore_icons = array(
			array( 'et-icon et-cafecito-o' => 'et-cafecito-o' ),
			array( 'et-icon et-cafecito' => 'et-cafecito' ),
			array( 'et-icon et-dribbble-o' => 'et-dribbble-o' ),
			array( 'et-icon et-dribbble' => 'et-dribbble' ),
			array( 'et-icon et-kofi-o' => 'et-kofi-o' ),
			array( 'et-icon et-kofi' => 'et-kofi' ),
			array( 'et-icon et-line-o' => 'et-line-o' ),
			array( 'et-icon et-line' => 'et-line' ),
			array( 'et-icon et-patreon-o' => 'et-patreon-o' ),
			array( 'et-icon et-patreon' => 'et-patreon' ),
			array( 'et-icon et-strava-o' => 'et-strava-o' ),
			array( 'et-icon et-strava' => 'et-strava' ),
			array( 'et-icon et-reddit' => 'et-reddit' ),
			array( 'et-icon et-reddit-o' => 'et-reddit-o' ),
			array( 'et-icon et-discord' => 'et-discord' ),
			array( 'et-icon et-discord-o' => 'et-discord-o' ),
			array( 'et-icon et-downloads' => 'et-downloads' ),
			array( 'et-icon et-logout' => 'et-logout' ),
			array( 'et-icon et-clear-filter' => 'et-clear-filter' ),
			array( 'et-icon et-grid-2-columns' => 'et-grid-2-columns' ),
			array( 'et-icon et-grid-4-columns' => 'et-grid-4-columns' ),
			array( 'et-icon et-emoji-happy' => 'et-emoji-happy' ),
			array( 'et-icon et-emoji-neutral' => 'et-emoji-neutral' ),
			array( 'et-icon et-emoji-sad' => 'et-emoji-sad' ),
			array( 'et-icon et-home' => 'et-home' ),
			array( 'et-icon et-shop' => 'et-shop' ),
			array( 'et-icon et-facebook' => 'et-facebook' ),
			array( 'et-icon et-behance' => 'et-behance' ),
			array( 'et-icon et-youtube' => 'et-youtube' ),
			array( 'et-icon et-snapchat' => 'et-snapchat' ),
			array( 'et-icon et-instagram' => 'et-instagram' ),
			array( 'et-icon et-google-plus' => 'et-google-plus' ),
			array( 'et-icon et-pinterest' => 'et-pinterest' ),
			array( 'et-icon et-linkedin' => 'et-linkedin' ),
			array( 'et-icon et-rss' => 'et-rss' ),
			array( 'et-icon et-tripadvisor' => 'et-tripadvisor' ),
			array( 'et-icon et-twitter' => 'et-twitter' ),
			array( 'et-icon et-tumblr' => 'et-tumblr' ),
			array( 'et-icon et-vk' => 'et-vk' ),
			array( 'et-icon et-vimeo' => 'et-vimeo' ),
			array( 'et-icon et-skype' => 'et-skype' ),
			array( 'et-icon et-whatsapp' => 'et-whatsapp' ),
			array( 'et-icon et-houzz' => 'et-houzz' ),
			array( 'et-icon et-telegram' => 'et-telegram' ),
			array( 'et-icon et-etsy' => 'et-etsy' ),
			array( 'et-icon et-tik-tok' => 'et-tik-tok' ),
			array( 'et-icon et-twitch' => 'et-twitch' ),
			array( 'et-icon et-untapped' => 'et-untapped' ),
			array( 'et-icon et-facebook-o' => 'et-facebook-o' ),
			array( 'et-icon et-behance-o' => 'et-behance-o' ),
			array( 'et-icon et-youtube-o' => 'et-youtube-o' ),
			array( 'et-icon et-snapchat-o' => 'et-snapchat-o' ),
			array( 'et-icon et-instagram-o' => 'et-instagram-o' ),
			array( 'et-icon et-google-plus-o' => 'et-google-plus-o' ),
			array( 'et-icon et-pinterest-o' => 'et-pinterest-o' ),
			array( 'et-icon et-linkedin-o' => 'et-linkedin-o' ),
			array( 'et-icon et-rss-o' => 'et-rss-o' ),
			array( 'et-icon et-tripadvisor-o' => 'et-tripadvisor-o' ),
			array( 'et-icon et-twitter-o' => 'et-twitter-o' ),
			array( 'et-icon et-tumblr-o' => 'et-tumblr-o' ),
			array( 'et-icon et-vk-o' => 'et-vk-o' ),
			array( 'et-icon et-vimeo-o' => 'et-vimeo-o' ),
			array( 'et-icon et-skype-o' => 'et-skype-o' ),
			array( 'et-icon et-whatsapp-o' => 'et-whatsapp-o' ),
			array( 'et-icon et-houzz-o' => 'et-houzz-o' ),
			array( 'et-icon et-telegram-o' => 'et-telegram-o' ),
			array( 'et-icon et-etsy-o' => 'et-etsy-o' ),
			array( 'et-icon et-tik-tok-o' => 'et-tik-tok-o' ),
			array( 'et-icon et-twitch-o' => 'et-twitch-o' ),
			array( 'et-icon et-untapped-o' => 'et-untapped-o' ),
			array( 'et-icon et-exclamation' => 'et-exclamation' ),
			array( 'et-icon et-play-button' => 'et-play-button' ),
			array( 'et-icon et-left-arrow' => 'et-left-arrow' ),
			array( 'et-icon et-up-arrow' => 'et-up-arrow' ),
			array( 'et-icon et-right-arrow' => 'et-right-arrow' ),
			array( 'et-icon et-down-arrow' => 'et-down-arrow' ),
			array( 'et-icon et-info' => 'et-info' ),
			array( 'et-icon et-view' => 'et-view' ),
			array( 'et-icon et-heart' => 'et-heart' ),
			array( 'et-icon et-delete' => 'et-delete' ),
			array( 'et-icon et-zoom' => 'et-zoom' ),
			array( 'et-icon et-shopping-cart' => 'et-shopping-cart' ),
			array( 'et-icon et-shopping-cart-2' => 'et-shopping-cart-2' ),
			array( 'et-icon et-star' => 'et-star' ),
			array( 'et-icon et-360-degrees' => 'et-360-degrees' ),
			array( 'et-icon et-plus' => 'et-plus' ),
			array( 'et-icon et-transfer' => 'et-transfer' ),
			array( 'et-icon et-minus' => 'et-minus' ),
			array( 'et-icon et-compare' => 'et-compare' ),
			array( 'et-icon et-shopping-basket' => 'et-shopping-basket' ),
			array( 'et-icon et-loader-gif' => 'et-loader-gif' ),
			array( 'et-icon et-tick' => 'et-tick' ),
			array( 'et-icon et-coupon' => 'et-coupon' ),
			array( 'et-icon et-share-arrow' => 'et-share-arrow' ),
			array( 'et-icon et-diagonal-arrow' => 'et-diagonal-arrow' ),
			array( 'et-icon et-checked' => 'et-checked' ),
			array( 'et-icon et-circle' => 'et-circle' ),
			array( 'et-icon et-heart-o' => 'et-heart-o' ),
			array( 'et-icon et-grid-list' => 'et-grid-list' ),
			array( 'et-icon et-list-grid' => 'et-list-grid' ),
			array( 'et-icon et-share' => 'et-share' ),
			array( 'et-icon et-controls' => 'et-controls' ),
			array( 'et-icon et-burger' => 'et-burger' ),
			array( 'et-icon et-calendar' => 'et-calendar' ),
			array( 'et-icon et-chat' => 'et-chat' ),
			array( 'et-icon et-internet' => 'et-internet' ),
			array( 'et-icon et-message' => 'et-message' ),
			array( 'et-icon et-shopping-bag-o' => 'et-shopping-bag-o' ),
			array( 'et-icon et-shopping-bag' => 'et-shopping-bag' ),
			array( 'et-icon et-delivery' => 'et-delivery' ),
			array( 'et-icon et-square' => 'et-square' ),
			array( 'et-icon et-sent' => 'et-sent' ),
			array( 'et-icon et-more' => 'et-more' ),
			array( 'et-icon et-upload' => 'et-upload' ),
			array( 'et-icon et-phone-call' => 'et-phone-call' ),
			array( 'et-icon et-gift' => 'et-gift' ),
			array( 'et-icon et-left-arrow-2' => 'et-left-arrow-2' ),
			array( 'et-icon et-right-arrow-2' => 'et-right-arrow-2' ),
			array( 'et-icon et-time' => 'et-time' ),
			array( 'et-icon et-size' => 'et-size' ),
			array( 'et-icon et-play-button-2' => 'et-play-button-2' ),
			array( 'et-icon et-gallery' => 'et-gallery' ),
			array( 'et-icon et-user' => 'et-user' ),
			array( 'et-icon et-star-o' => 'et-star-o' ),
		);
		
		return array_merge( $icons, $xstore_icons );
	}
	
	/**
	 * Register widget args
	 *
	 * @return mixed|null|void
	 */
	public static function vc_args() {
		
		if ( ! is_null( self::$shortcodes ) ) {
			return self::$shortcodes;
		}
		
		return self::$shortcodes = apply_filters( 'etc/add/vc', array() );
	}
	
	/**
	 * Register Widgets
	 * @return null
	 */
	public function register_vc() {
		
		// Check for vc map to register
		if ( ! function_exists( 'vc_map' ) ) {
			return;
		}
		
		vc_remove_element( "vc_tour" );
		
		$args = self::vc_args();
		
		if ( ! is_array( $args ) ) {
			return;
		}
		
		foreach ( $args as $vc_class ) {
			$class    = $vc_class['class'];
			$function = $vc_class['function'];
			// include vc class if exist
			if ( isset( $vc_class['extra'] ) ) {
				include_once ET_CORE_DIR . 'app/controllers/vc/class/' . $vc_class['extra'] . '.php';
			}
			
			$class = $class::get_instance();
			$class->$function();
		}
		
		$this->register_shortcodes();
		
		$fields = self::fields();
		foreach ( $fields as $key ) {
			require_once ET_CORE_DIR . 'config/vc/fields/'.$key.'.php';
			vc_add_shortcode_param( 'xstore_' . str_replace('-', '_', $key), 'xstore_add_' . str_replace('-', '_', $key) . '_param' );
		}
	}
	
	public function register_shortcodes() {
		// Register theme shortcode
	    foreach (self::product_grid_fields() as $field) {
		    add_shortcode( 'etheme_product_'.$field, array($this, 'etheme_product_'.$field.'_render'));
	    }
	}
	
	public static function product_grid_fields() {
	    return array(
            'name',
            'image',
            'excerpt',
            'rating',
            'price',
            'sku',
            'brands',
            'categories',
            'stock',
            'buttons',
        );
	}
	
	public function add_vc_grid_shortcodes( $shortcodes ) {
		
		require_once vc_path_dir( 'CONFIG_DIR', 'content/vc-custom-heading-element.php' );
		$title_custom_heading = vc_map_integrate_shortcode( vc_custom_heading_element_params(), 'title_', esc_html__( 'Typography', 'xstore-core' ), array(
			'exclude' => array(
				'link',
				'source',
				'text',
				'css',
				'el_class',
				'css_animation'
			),
		), array(
			'element' => 'use_custom_fonts_title',
			'value'   => 'true',
		) );
		
		// This is needed to remove custom heading _tag and _align options.
		if ( is_array( $title_custom_heading ) && ! empty( $title_custom_heading ) ) {
			foreach ( $title_custom_heading as $key => $param ) {
				if ( is_array( $param ) && isset( $param['type'] ) && 'font_container' === $param['type'] ) {
					$title_custom_heading[ $key ]['value'] = '';
					if ( isset( $param['settings'] ) && is_array( $param['settings'] ) && isset( $param['settings']['fields'] ) ) {
						$sub_key = array_search( 'text_align', $param['settings']['fields'] );
						if ( false !== $sub_key ) {
							unset( $title_custom_heading[ $key ]['settings']['fields'][ $sub_key ] );
						} elseif ( isset( $param['settings']['fields']['text_align'] ) ) {
							unset( $title_custom_heading[ $key ]['settings']['fields']['text_align'] );
						}
						$sub_key = array_search( 'font_size', $param['settings']['fields'] );
						if ( false !== $sub_key ) {
							unset( $title_custom_heading[ $key ]['settings']['fields'][ $sub_key ] );
						} elseif ( isset( $param['settings']['fields']['font_size'] ) ) {
							unset( $title_custom_heading[ $key ]['settings']['fields']['font_size'] );
						}
						$sub_key = array_search( 'line_height', $param['settings']['fields'] );
						if ( false !== $sub_key ) {
							unset( $title_custom_heading[ $key ]['settings']['fields'][ $sub_key ] );
						} elseif ( isset( $param['settings']['fields']['line_height'] ) ) {
							unset( $title_custom_heading[ $key ]['settings']['fields']['line_height'] );
						}
						$sub_key = array_search( 'color', $param['settings']['fields'] );
						if ( false !== $sub_key ) {
							unset( $title_custom_heading[ $key ]['settings']['fields'][ $sub_key ] );
						} elseif ( isset( $param['settings']['fields']['color'] ) ) {
							unset( $title_custom_heading[ $key ]['settings']['fields']['color'] );
						}
					}
				}
			}
		}
		
		$horiz_align = array(
			__( 'Left', 'xstore-core' )    => 'left',
			__( 'Right', 'xstore-core' )   => 'right',
			__( 'Center', 'xstore-core' )  => 'center',
			__( 'Justify', 'xstore-core' ) => 'justify'
		);
		
		$text_transform = array(
			__( 'None', 'xstore-core' )       => '',
			__( 'Uppercase', 'xstore-core' )  => 'text-uppercase',
			__( 'Lowercase', 'xstore-core' )  => 'text-lowercase',
			__( 'Capitalize', 'xstore-core' ) => 'text-capitalize'
		);
		
		$compare_arr = array();
		
		$sorted_list = array(
			array(
				'type'        => 'sorted_list',
				'heading'     => __( 'Buttons layout', 'xstore-core' ),
				'param_name'  => 'sorting',
				'description' => __( 'Sorting the buttons layout', 'xstore-core' ),
				'value'       => 'cart,wishlist,q_view',
				'options'     => array(
					array(
						'cart',
						__( 'Add to cart', 'xstore-core' ),
					),
					array(
						'wishlist',
						__( 'Wishlist', 'xstore-core' ),
					),
					array(
						'q_view',
						__( 'Quick view', 'xstore-core' ),
					),
				),
			)
		);
		
		$sizes_select2 = array();
		
		if ( function_exists('etheme_get_image_sizes')) {
			
			$sizes = etheme_get_image_sizes();
			foreach ( $sizes as $size => $value ) {
				$sizes[ $size ] = $sizes[ $size ]['width'] . 'x' . $sizes[ $size ]['height'];
			}
			
			$sizes_select = array(
//			'shop_catalog'                  => 'shop_catalog',
				'woocommerce_thumbnail'         => 'woocommerce_thumbnail',
				'woocommerce_gallery_thumbnail' => 'woocommerce_gallery_thumbnail',
				'woocommerce_single'            => 'woocommerce_single',
//			'shop_thumbnail'                => 'shop_thumbnail',
//			'shop_single'                   => 'shop_single',
				'thumbnail'                     => 'thumbnail',
				'medium'                        => 'medium',
				'large'                         => 'large',
				'full'                          => 'full'
			);
			
			foreach ( $sizes_select as $item => $value ) {
				if ( isset( $sizes[ $item ] ) ) {
					$sizes_select2[ $item ] = $value . ' (' . $sizes[ $item ] . ')';
				} else {
					$sizes_select2[ $item ] = $value;
				}
			}
			
			$sizes_select2 = array_flip( $sizes_select2 );
			$sizes_select2['custom'] = 'custom';
			
		}
		
		if ( class_exists( 'YITH_Woocompare' ) || get_theme_mod('xstore_compare', false) ) {
			$compare_arr      = array(
				array(
					'type'       => 'xstore_button_set',
					'heading'    => esc_html__( 'Type', 'xstore-core' ),
					'param_name' => 'compare_type',
					'value'      => array(
						__( 'Icon', 'xstore-core' )        => 'icon',
						__( 'Text', 'xstore-core' )        => 'text',
						__( 'Icon + text', 'xstore-core' ) => 'icon-text',
						__( 'Button', 'xstore-core' )      => 'button',
					),
					'group'      => 'Compare',
					'dependency' => array( 'element' => 'compare', 'value' => 'true' )
				),
				array(
					'type'       => 'textfield',
					'heading'    => esc_html__( 'Font size', 'xstore-core' ),
					'param_name' => 'c_size',
					'group'      => 'Compare',
					'dependency' => array( 'element' => 'compare', 'value' => 'true' )
				),
				array(
					'type'       => 'xstore_button_set',
					'heading'    => esc_html__( 'Text transform', 'xstore-core' ),
					'param_name' => 'c_transform',
					'value'      => array(
						__( 'None', 'xstore-core' )       => '',
						__( 'Uppercase', 'xstore-core' )  => 'uppercase',
						__( 'Lowercase', 'xstore-core' )  => 'lowercase',
						__( 'Capitalize', 'xstore-core' ) => 'capitalize'
					),
					'group'      => 'Compare',
					'dependency' => array( 'element' => 'compare_type', 'value_not_equal_to' => 'icon' ),
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Button background color', 'xstore-core' ),
					'param_name'       => 'c_bg',
					'group'            => 'Compare',
					'dependency'       => array( 'element' => 'compare_type', 'value' => array( 'button', 'icon' ) ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'colorpicker',
					'heading'          => esc_html__( 'Button background color (hover)', 'xstore-core' ),
					'param_name'       => 'c_hover_bg',
					'group'            => 'Compare',
					'dependency'       => array( 'element' => 'compare_type', 'value' => array( 'button', 'icon' ) ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'textfield',
					'heading'          => 'Border radius',
					'param_name'       => 'c_radius',
					'group'            => 'Compare',
					'dependency'       => array( 'element' => 'compare_type', 'value' => array( 'button', 'icon' ) ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'             => 'textfield',
					'heading'          => esc_html__( 'Margins (top right bottom left)', 'xstore-core' ),
					'param_name'       => 'c_margin',
					'group'            => 'Compare',
					'description'      => esc_html__( 'Use this field to add element margin. For example 10px 20px 30px 40px', 'xstore-core' ),
					'dependency'       => array( 'element' => 'compare_type', 'value' => array( 'button', 'icon' ) ),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
			);
			$sorted_list      = array(
				array(
					'type'        => 'sorted_list',
					'heading'     => __( 'Buttons layout', 'xstore-core' ),
					'param_name'  => 'sorting',
					'description' => __( 'Sorting the buttons layout', 'xstore-core' ),
					'value'       => 'cart,wishlist,compare,q_view',
					'options'     => array(
						array(
							'cart',
							__( 'Add to cart', 'xstore-core' ),
						),
						array(
							'wishlist',
							__( 'Wishlist', 'xstore-core' ),
						),
						array(
							'compare',
							__( 'Compare', 'xstore-core' ),
						),
						array(
							'q_view',
							__( 'Quick view', 'xstore-core' ),
						),
					),
				)
			);
		}
		
		$shortcodes['etheme_product_name'] = array(
			'name'        => __( 'Product title', 'xstore-core' ),
			'base'        => 'etheme_product_name',
			'category'    => __( 'Content product by 8theme', 'xstore-core' ),
			'icon'        => ETHEME_CODE_IMAGES . 'vc/Autoscrolling-text.png',
			'description' => __( 'Show current product name', 'xstore-core' ),
			'post_type'   => 'vc_grid_item',
			'params'      => array_merge(
				array(
					array(
						'type'       => 'xstore_button_set',
						'heading'    => __( 'Add link', 'xstore-core' ),
						'param_name' => 'link',
						'value'      => array(
							__( 'Product link', 'xstore-core' ) => 'product_link',
							__( 'Custom link', 'xstore-core' )  => 'custom',
							__( 'None', 'xstore-core' )         => ''
						),
					),
					array(
						'type'       => 'vc_link',
						'heading'    => __( 'Custom link', 'xstore-core' ),
						'param_name' => 'url',
						'dependency' => array(
							'element' => 'link',
							'value'   => 'custom',
						),
					),
					array(
						'type'       => 'xstore_button_set',
						'heading'    => __( 'Cut product name', 'xstore-core' ),
						'param_name' => 'cutting',
						'value'      => array(
							__( 'None', 'xstore-core' )    => 'none',
							__( 'Words', 'xstore-core' )   => 'words',
							__( 'Letters', 'xstore-core' ) => 'letters'
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Count words/letters', 'xstore-core' ),
						'param_name' => 'count',
						'dependency' => array(
							'element'            => 'cutting',
							'value_not_equal_to' => 'none'
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Symbols', 'xstore-core' ),
						'param_name'  => 'symbols',
						'description' => esc_html__( 'Default "...".', 'xstore-core' ),
						'dependency'  => array(
							'element'            => 'cutting',
							'value_not_equal_to' => 'none'
						),
					),
					array(
						'type'       => 'xstore_button_set',
						'heading'    => __( 'Text align', 'xstore-core' ),
						'param_name' => 'align',
						'value'      => $horiz_align,
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Font size', 'xstore-core' ),
						'param_name' => 'size',
						'group'      => 'Typography'
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Letter spacing', 'xstore-core' ),
						'param_name' => 'spacing',
						'group'      => 'Typography'
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Line height', 'xstore-core' ),
						'param_name' => 'line_height',
						'group'      => 'Typography'
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Color', 'xstore-core' ),
						'param_name' => 'color',
						'group'      => 'Typography'
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Use custom font ?', 'xstore-core' ),
						'param_name'  => 'use_custom_fonts_title',
						'description' => esc_html__( 'Enable Google fonts.', 'xstore-core' ),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Extra class name', 'xstore-core' ),
						'param_name'  => 'el_class',
						'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'xstore-core' ),
					),
				),
				$title_custom_heading,
				array(
					array(
						'type'       => 'css_editor',
						'heading'    => esc_html__( 'CSS box', 'xstore-core' ),
						'param_name' => 'css',
						'group'      => esc_html__( 'Design', 'xstore-core' )
					),
				)
			),
		);
		
		$shortcodes['etheme_product_image'] = array(
			'name'      => __( 'Product image', 'xstore-core' ),
			'base'      => 'etheme_product_image',
			'category'  => __( 'Content product by 8theme', 'xstore-core' ),
			'icon'      => ETHEME_CODE_IMAGES . 'vc/Image.png',
			'post_type' => 'vc_grid_item',
			'params'    => array(
				array(
					'type'       => 'xstore_button_set',
					'heading'    => __( 'Add link', 'xstore-core' ),
					'param_name' => 'link',
					'value'      => array(
						__( 'Product link', 'xstore-core' ) => 'product_link',
						__( 'Custom link', 'xstore-core' )  => 'custom',
						__( 'None', 'xstore-core' )         => ''
					),
				),
				array(
					'type'        => 'vc_link',
					'heading'     => __( 'URL (Link)', 'xstore-core' ),
					'param_name'  => 'url',
					'dependency'  => array(
						'element' => 'link',
						'value'   => array( 'custom' ),
					),
					'description' => __( 'Add custom link.', 'xstore-core' ),
				),
				array(
					'type'        => 'xstore_button_set',
					'heading'     => __( 'Image alignment', 'xstore-core' ),
					'param_name'  => 'align',
					'value'       => array_diff( $horiz_align, array( __( 'Justify', 'xstore-core' ) => 'justify' ) ),
					'description' => __( 'Select image alignment.', 'xstore-core' ),
				),
				array(
					'type'        => 'dropdown',
					'heading'     => __( 'Image style', 'xstore-core' ),
					'param_name'  => 'style',
					'value'       => vc_get_shared( 'single image styles' ),
					'description' => __( 'Select image display style.', 'xstore-core' ),
				),
				array(
					'type'       => 'xstore_button_set',
					'heading'    => __( 'Hover effect', 'xstore-core' ),
					'param_name' => 'hover',
					'value'      => array(
						__( 'Disable', 'xstore-core' ) => '',
						__( 'Swap', 'xstore-core' )    => 'swap',
						__( 'Slider', 'xstore-core' )  => 'slider'
					),
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'size',
					'heading'    => esc_html__( 'Image size', 'xstore-core' ),
					'value'      => $sizes_select2,
					'default'    => 'woocommerce_thumbnail'
				),
				array(
					'type'        => 'textfield',
					'heading'    => esc_html__( 'Image size custom', 'xstore-core' ),
					'param_name'  => 'size_custom',
					'dependency'         => array(
						'element' => 'size',
						'value'   => array(
							'custom',
						),
					),
				),
				array(
					'type'       => 'xstore_button_set',
					'heading'    => __( 'Hide stock status', 'xstore-core' ),
					'param_name' => 'stock_status',
					'value'      => array(
						__( 'No', 'xstore-core' )  => 'no',
						__( 'Yes', 'xstore-core' ) => 'yes',
					),
				),
				array(
					'type'               => 'dropdown',
					'heading'            => __( 'Border color', 'xstore-core' ),
					'param_name'         => 'border_color',
					'value'              => vc_get_shared( 'colors' ),
					'std'                => 'grey',
					'dependency'         => array(
						'element' => 'style',
						'value'   => array(
							'vc_box_border',
							'vc_box_border_circle',
							'vc_box_outline',
							'vc_box_outline_circle',
						),
					),
					'description'        => __( 'Border color.', 'xstore-core' ),
					'param_holder_class' => 'vc_colored-dropdown',
				),
				array(
					'type'        => 'textfield',
					'heading'     => __( 'Extra class name', 'xstore-core' ),
					'param_name'  => 'el_class',
					'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'xstore-core' ),
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS box', 'xstore-core' ),
					'param_name' => 'css',
					'group'      => __( 'Design Options', 'xstore-core' ),
				),
			),
		);
		
		$shortcodes['etheme_product_excerpt'] = array(
			'name'      => __( 'Product excerpt', 'xstore-core' ),
			'base'      => 'etheme_product_excerpt',
			'category'  => __( 'Content product by 8theme', 'xstore-core' ),
			'icon'      => ETHEME_CODE_IMAGES . 'vc/Excerpt.png',
			'post_type' => 'vc_grid_item',
			'params'    => array_merge(
				array(
					array(
						'type'       => 'xstore_button_set',
						'heading'    => __( 'Cut excerpt', 'xstore-core' ),
						'param_name' => 'cutting',
						'value'      => array(
							__( 'None', 'xstore-core' )    => 'none',
							__( 'Words', 'xstore-core' )   => 'words',
							__( 'Letters', 'xstore-core' ) => 'letters'
						),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Count words/letters', 'xstore-core' ),
						'param_name' => 'count',
						'dependency' => array(
							'element'            => 'cutting',
							'value_not_equal_to' => 'none'
						),
					),
					array(
						'type'        => 'textfield',
						'heading'     => __( 'Symbols after string', 'xstore-core' ),
						'param_name'  => 'symbols',
						'description' => esc_html__( 'Default "...".', 'xstore-core' ),
						'dependency'  => array(
							'element'            => 'cutting',
							'value_not_equal_to' => 'none'
						),
					),
					array(
						'type'       => 'xstore_button_set',
						'heading'    => __( 'Text align', 'xstore-core' ),
						'param_name' => 'align',
						'value'      => $horiz_align,
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Font size', 'xstore-core' ),
						'param_name' => 'size',
						'group'      => 'Typography'
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Letter spacing', 'xstore-core' ),
						'param_name' => 'spacing',
						'group'      => 'Typography'
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Line height', 'xstore-core' ),
						'param_name' => 'line_height',
						'group'      => 'Typography'
					),
					array(
						'type'       => 'colorpicker',
						'heading'    => __( 'Color', 'xstore-core' ),
						'param_name' => 'color',
						'group'      => 'Typography'
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Use custom font ?', 'xstore-core' ),
						'param_name'  => 'use_custom_fonts_title',
						'description' => esc_html__( 'Enable Google fonts.', 'xstore-core' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Class', 'xstore-core' ),
						'param_name' => 'el_class',
					),
				),
				$title_custom_heading,
				array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS box', 'xstore-core' ),
						'param_name' => 'css',
						'group'      => __( 'Design Options', 'xstore-core' ),
					)
				)
			),
		);
		
		$shortcodes['etheme_product_rating'] = array(
			'name'      => __( 'Product rating', 'xstore-core' ),
			'base'      => 'etheme_product_rating',
			'category'  => __( 'Content product by 8theme', 'xstore-core' ),
			'icon'      => ETHEME_CODE_IMAGES . 'vc/Rating.png',
			'post_type' => 'vc_grid_item',
			'params'    => array(
				array(
					'type'       => 'checkbox',
					'heading'    => __( 'Show by default ?', 'xstore-core' ),
					'param_name' => 'default',
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Class', 'xstore-core' ),
					'param_name' => 'el_class',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS box', 'xstore-core' ),
					'param_name' => 'css',
					'group'      => __( 'Design Options', 'xstore-core' ),
				)
			),
		);
		
		$shortcodes['etheme_product_price'] = array(
			'name'      => __( 'Product price', 'xstore-core' ),
			'base'      => 'etheme_product_price',
			'category'  => __( 'Content product by 8theme', 'xstore-core' ),
			'icon'      => ETHEME_CODE_IMAGES . 'vc/Price.png',
			'post_type' => 'vc_grid_item',
			'params'    => array(
				array(
					'type'       => 'xstore_button_set',
					'heading'    => __( 'Text align', 'xstore-core' ),
					'param_name' => 'align',
					'value'      => $horiz_align,
				),
				array(
					"type"       => 'textfield',
					"heading"    => __( 'Font size', 'xstore-core' ),
					"param_name" => 'size',
					'group'      => 'Typography',
				),
				array(
					"type"        => "textfield",
					"heading"     => "Letter spacing",
					"param_name"  => "spacing",
					'group'       => 'Typography',
					'description' => esc_html__( 'Enter letter spacing', 'xstore-core' ),
				),
				array(
					"type"       => 'colorpicker',
					"heading"    => __( 'Regular price color', 'xstore-core' ),
					"param_name" => 'color',
					'group'      => 'Typography',
				),
				array(
					"type"       => 'colorpicker',
					"heading"    => __( 'Sale price color', 'xstore-core' ),
					"param_name" => 'color_sale',
					'group'      => 'Typography'
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS box', 'xstore-core' ),
					'param_name' => 'css',
					'group'      => __( 'Design Options', 'xstore-core' ),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Class', 'xstore-core' ),
					'param_name' => 'el_class',
				),
			),
		);
		$shortcodes['etheme_product_sku']   = array(
			'name'      => __( 'Product sku', 'xstore-core' ),
			'base'      => 'etheme_product_sku',
			'category'  => __( 'Content product by 8theme', 'xstore-core' ),
			'icon'      => ETHEME_CODE_IMAGES . 'vc/Sku.png',
			'post_type' => 'vc_grid_item',
			'params'    => array(
				array(
					'type'       => 'xstore_button_set',
					'heading'    => __( 'Text align', 'xstore-core' ),
					'param_name' => 'align',
					'value'      => $horiz_align,
				),
				array(
					'type'       => 'xstore_button_set',
					'heading'    => __( 'Text transform', 'xstore-core' ),
					'param_name' => 'transform',
					'value'      => $text_transform,
				),
				array(
					"type"       => 'textfield',
					"heading"    => __( 'Font size', 'xstore-core' ),
					"param_name" => 'size',
					'group'      => 'Typography',
				),
				array(
					"type"       => 'colorpicker',
					"heading"    => __( 'Color', 'xstore-core' ),
					"param_name" => 'color',
					'group'      => 'Typography',
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS box', 'xstore-core' ),
					'param_name' => 'css',
					'group'      => __( 'Design Options', 'xstore-core' ),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Class', 'xstore-core' ),
					'param_name' => 'el_class',
				),
			),
		);
		
		/* Product brands shortcode */
		$shortcodes['etheme_product_brands'] = array(
			'name'      => __( 'Product brands', 'xstore-core' ),
			'base'      => 'etheme_product_brands',
			'category'  => __( 'Content product by 8theme', 'xstore-core' ),
			'icon'      => ETHEME_CODE_IMAGES . 'vc/Brands.png',
			'post_type' => 'vc_grid_item',
			'params'    => array(
				array(
					'type'       => 'xstore_button_set',
					'heading'    => __( 'Text align', 'xstore-core' ),
					'param_name' => 'align',
					'value'      => $horiz_align,
				),
				array(
					'type'        => 'checkbox',
					'heading'     => __( 'Show image', 'xstore-core' ),
					'param_name'  => 'img',
					'description' => __( 'The image will be shown in case if product\'s brand has it', 'xstore-core' )
				),
				array(
					'type'       => 'xstore_button_set',
					'heading'    => __( 'Text transform', 'xstore-core' ),
					'param_name' => 'transform',
					'value'      => $text_transform,
				),
				array(
					"type"       => 'textfield',
					"heading"    => __( 'Font size', 'xstore-core' ),
					"param_name" => 'size',
					'group'      => 'Typography',
				),
				array(
					"type"       => 'textfield',
					"heading"    => __( 'Letter spacing', 'xstore-core' ),
					"param_name" => 'spacing',
					'group'      => 'Typography'
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS box', 'xstore-core' ),
					'param_name' => 'css',
					'group'      => __( 'Design Options', 'xstore-core' ),
				),
				array(
					'type'       => 'textfield',
					'heading'    => __( 'Class', 'xstore-core' ),
					'param_name' => 'el_class',
				),
			),
		);
		
		/* Product stock */
		$shortcodes['etheme_product_stock'] = array(
			'name'      => __( 'Product stock', 'xstore-core' ),
			'base'      => 'etheme_product_stock',
			'category'  => __( 'Content product by 8theme', 'xstore-core' ),
			'icon'      => ETHEME_CODE_IMAGES . 'vc/Stock-status.png',
			'post_type' => 'vc_grid_item',
			'params'    => array(
				array(
					'type'             => 'xstore_button_set',
					'heading'          => esc_html__( 'Stock type', 'xstore-core' ),
					'param_name'       => 'product_stock_type',
					'value'            => array(
						__( 'Default', 'xstore-core' )  => 'default',
						__( 'Advanced', 'xstore-core' ) => 'advanced',
					),
					'edit_field_class' => 'vc_col-sm-6 vc_column',
				),
				array(
					'type'       => 'xstore_button_set',
					'heading'    => __( 'Text align', 'xstore-core' ),
					'param_name' => 'align',
					'value'      => $horiz_align,
				),
				array(
					'type'       => 'colorpicker',
					'heading'    => esc_html__( 'Text color', 'xstore-core' ),
					'param_name' => 'color',
					'default'    => '#000'
				),
				array(
					'type'       => 'css_editor',
					'heading'    => __( 'CSS box', 'xstore-core' ),
					'param_name' => 'css',
					'group'      => __( 'Design Options', 'xstore-core' ),
				)
			),
		);
		
		$shortcodes['etheme_product_categories'] = array(
			'name'      => __( 'Product categories', 'xstore-core' ),
			'base'      => 'etheme_product_categories',
			'category'  => __( 'Content product by 8theme', 'xstore-core' ),
			'icon'      => ETHEME_CODE_IMAGES . 'vc/Categories.png',
			'post_type' => 'vc_grid_item',
			'params'    => array_merge(
				array(
					array(
						'type'       => 'xstore_button_set',
						'heading'    => __( 'Text align', 'xstore-core' ),
						'param_name' => 'align',
						'value'      => $horiz_align,
					),
					array(
						'type'        => 'checkbox',
						'heading'     => esc_html__( 'Use custom font ?', 'xstore-core' ),
						'param_name'  => 'use_custom_fonts_title',
						'description' => esc_html__( 'Enable Google fonts.', 'xstore-core' ),
					),
					array(
						'type'       => 'textfield',
						'heading'    => __( 'Class', 'xstore-core' ),
						'param_name' => 'el_class',
					),
				),
				$title_custom_heading,
				array(
					array(
						'type'       => 'css_editor',
						'heading'    => __( 'CSS box', 'xstore-core' ),
						'param_name' => 'css',
						'group'      => __( 'Design Options', 'xstore-core' ),
					)
				)
			),
		);
		
		$shortcodes['etheme_product_buttons'] = array(
			'name'      => __( 'Product buttons ', 'xstore-core' ),
			'base'      => 'etheme_product_buttons',
			'category'  => __( 'Content product by 8theme', 'xstore-core' ),
			'icon'      => ETHEME_CODE_IMAGES . 'vc/Fancy-button.png',
			'post_type' => 'vc_grid_item',
			'params'    => array_merge(
				$sorted_list,
				array(
					array(
						'type'       => 'xstore_button_set',
						'heading'    => esc_html__( 'Design type', 'xstore-core' ),
						'param_name' => 'type',
						'value'      => array(
							esc_html__( 'Horizontal', 'xstore-core' ) => '',
							esc_html__( 'Vertical', 'xstore-core' )   => 'vertical',
						),
					),
					array(
						'type'       => 'xstore_button_set',
						'heading'    => esc_html__( 'Align', 'xstore-core' ),
						'param_name' => 'align',
						'value'      => array(
							esc_html__( 'Left', 'xstore-core' )                  => 'start',
							esc_html__( 'Right', 'xstore-core' )                 => 'end',
							esc_html__( 'Center', 'xstore-core' )                => 'center',
							esc_html__( 'Stretch', 'xstore-core' )               => 'between',
							esc_html__( 'Stretch (no paddings)', 'xstore-core' ) => 'around',
						),
					),
					array(
						'type'       => 'xstore_button_set',
						'heading'    => esc_html__( 'Vertical align', 'xstore-core' ),
						'param_name' => 'v_align',
						'value'      => array(
							esc_html__( 'Top', 'xstore-core' )         => 'start',
							esc_html__( 'Bottom', 'xstore-core' )      => 'end',
							esc_html__( 'Middle', 'xstore-core' )      => 'center',
							esc_html__( 'Full height', 'xstore-core' ) => 'stretch',
						),
					),
					// Cart options
					array(
						'type'       => 'xstore_button_set',
						'heading'    => esc_html__( 'Type', 'xstore-core' ),
						'param_name' => 'cart_type',
						'value'      => array(
							esc_html__( 'Icon', 'xstore-core' )        => 'icon',
							esc_html__( 'Text', 'xstore-core' )        => 'text',
							esc_html__( 'Icon + text', 'xstore-core' ) => 'icon-text',
							esc_html__( 'Button', 'xstore-core' )      => 'button',
						),
						'group'      => 'Cart',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Font size', 'xstore-core' ),
						'param_name' => 'a_size',
						'group'      => 'Cart'
					),
					array(
						'type'       => 'xstore_button_set',
						'heading'    => esc_html__( 'Text transform', 'xstore-core' ),
						'param_name' => 'a_transform',
						'value'      => array(
							__( 'None', 'xstore-core' )       => '',
							__( 'Uppercase', 'xstore-core' )  => 'uppercase',
							__( 'Lowercase', 'xstore-core' )  => 'lowercase',
							__( 'Capitalize', 'xstore-core' ) => 'capitalize'
						),
						'group'      => 'Cart',
						'dependency' => array( 'element' => 'cart_type', 'value_not_equal_to' => 'icon' ),
					),
					array(
						'type'             => 'colorpicker',
						'heading'          => esc_html__( 'Button background color', 'xstore-core' ),
						'param_name'       => 'a_bg',
						'group'            => 'Cart',
						'dependency'       => array( 'element' => 'cart_type', 'value' => array( 'button', 'icon' ) ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type'             => 'colorpicker',
						'heading'          => esc_html__( 'Button background color (hover)', 'xstore-core' ),
						'param_name'       => 'a_hover_bg',
						'group'            => 'Cart',
						'dependency'       => array( 'element' => 'cart_type', 'value' => array( 'button', 'icon' ) ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type'             => 'textfield',
						'heading'          => 'Border radius',
						'param_name'       => 'a_radius',
						'group'            => 'Cart',
						'dependency'       => array( 'element' => 'cart_type', 'value' => array( 'button', 'icon' ) ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Margins (top right bottom left)', 'xstore-core' ),
						'param_name'       => 'a_margin',
						'group'            => 'Cart',
						'description'      => esc_html__( 'Use this field to add element margin. For example 10px 20px 30px 40px', 'xstore-core' ),
						'dependency'       => array( 'element' => 'cart_type', 'value' => array( 'button', 'icon' ) ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					
					// Wishlist options
					array(
						'type'       => 'xstore_button_set',
						'heading'    => esc_html__( 'Type', 'xstore-core' ),
						'param_name' => 'w_type',
						'value'      => array(
							__( 'Icon', 'xstore-core' )        => 'icon',
							__( 'Text', 'xstore-core' )        => 'text',
							__( 'Icon + text', 'xstore-core' ) => 'icon-text',
							__( 'Button', 'xstore-core' )      => 'button',
						),
						'group'      => 'Wishlist'
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Font size', 'xstore-core' ),
						'param_name' => 'w_size',
						'group'      => 'Wishlist'
					),
					array(
						'type'       => 'xstore_button_set',
						'heading'    => esc_html__( 'Text transform', 'xstore-core' ),
						'param_name' => 'w_transform',
						'value'      => array(
							__( 'None', 'xstore-core' )       => '',
							__( 'Uppercase', 'xstore-core' )  => 'uppercase',
							__( 'Lowercase', 'xstore-core' )  => 'lowercase',
							__( 'Capitalize', 'xstore-core' ) => 'capitalize'
						),
						'group'      => 'Wishlist',
						'dependency' => array( 'element' => 'w_type', 'value_not_equal_to' => 'icon' )
					),
					array(
						'type'             => 'colorpicker',
						'heading'          => esc_html__( 'Button background color', 'xstore-core' ),
						'param_name'       => 'w_bg',
						'group'            => 'Wishlist',
						'dependency'       => array( 'element' => 'w_type', 'value' => array( 'button', 'icon' ) ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type'             => 'colorpicker',
						'heading'          => esc_html__( 'Button background color (hover)', 'xstore-core' ),
						'param_name'       => 'w_hover_bg',
						'group'            => 'Wishlist',
						'dependency'       => array( 'element' => 'w_type', 'value' => array( 'button', 'icon' ) ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type'             => 'textfield',
						'heading'          => 'Border radius',
						'param_name'       => 'w_radius',
						'group'            => 'Wishlist',
						'dependency'       => array( 'element' => 'w_type', 'value' => array( 'button', 'icon' ) ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Margins (top right bottom left)', 'xstore-core' ),
						'param_name'       => 'w_margin',
						'group'            => 'Wishlist',
						'description'      => esc_html__( 'Use this field to add element margin. For example 10px 20px 30px 40px', 'xstore-core' ),
						'dependency'       => array( 'element' => 'w_type', 'value' => array( 'button', 'icon' ) ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					
					// Quick view
					array(
						'type'       => 'xstore_button_set',
						'heading'    => esc_html__( 'Type', 'xstore-core' ),
						'param_name' => 'quick_type',
						'value'      => array(
							__( 'Icon', 'xstore-core' )        => 'icon',
							__( 'Text', 'xstore-core' )        => 'text',
							__( 'Icon + text', 'xstore-core' ) => 'icon-text',
							__( 'Button', 'xstore-core' )      => 'button',
						),
						'group'      => 'Quick view'
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Font size', 'xstore-core' ),
						'param_name' => 'q_size',
						'group'      => 'Quick view'
					),
					array(
						'type'       => 'xstore_button_set',
						'heading'    => esc_html__( 'Text transform', 'xstore-core' ),
						'param_name' => 'q_transform',
						'value'      => array(
							__( 'None', 'xstore-core' )       => '',
							__( 'Uppercase', 'xstore-core' )  => 'uppercase',
							__( 'Lowercase', 'xstore-core' )  => 'lowercase',
							__( 'Capitalize', 'xstore-core' ) => 'capitalize'
						),
						'group'      => 'Quick view',
						'dependency' => array( 'element' => 'quick_type', 'value_not_equal_to' => 'icon' ),
					),
					array(
						'type'             => 'colorpicker',
						'heading'          => esc_html__( 'Button background color', 'xstore-core' ),
						'param_name'       => 'q_bg',
						'group'            => 'Quick view',
						'dependency'       => array( 'element' => 'quick_type', 'value' => array( 'button', 'icon' ) ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type'             => 'colorpicker',
						'heading'          => esc_html__( 'Button background color (hover)', 'xstore-core' ),
						'param_name'       => 'q_hover_bg',
						'group'            => 'Quick view',
						'dependency'       => array( 'element' => 'quick_type', 'value' => array( 'button', 'icon' ) ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type'             => 'textfield',
						'heading'          => 'Border radius',
						'param_name'       => 'q_radius',
						'group'            => 'Quick view',
						'dependency'       => array( 'element' => 'quick_type', 'value' => array( 'button', 'icon' ) ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Margins (top right bottom left)', 'xstore-core' ),
						'param_name'       => 'q_margin',
						'group'            => 'Quick view',
						'description'      => esc_html__( 'Use this field to add element margin. For example 10px 20px 30px 40px', 'xstore-core' ),
						'dependency'       => array( 'element' => 'quick_type', 'value' => array( 'button', 'icon' ) ),
						'edit_field_class' => 'vc_col-sm-6 vc_column',
					),
				),
				
				// Compare
				$compare_arr,
				array(
					array(
						'type'             => 'colorpicker',
						'heading'          => esc_html__( 'Text/icons color', 'xstore-core' ),
						'param_name'       => 'color',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
					),
					array(
						'type'             => 'colorpicker',
						'heading'          => esc_html__( 'Background color', 'xstore-core' ),
						'param_name'       => 'bg',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
					),
					array(
						'type'             => 'colorpicker',
						'heading'          => esc_html__( 'Text/icons hover color', 'xstore-core' ),
						'param_name'       => 'hover_color',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
					),
					array(
						'type'             => 'colorpicker',
						'heading'          => esc_html__( 'Background hover color', 'xstore-core' ),
						'param_name'       => 'hover_bg',
						'edit_field_class' => 'vc_col-sm-3 vc_column',
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Border radius', 'xstore-core' ),
						'param_name'       => 'radius',
						'edit_field_class' => 'vc_col-sm-4 vc_column',
					),
					array(
						'type'             => 'textfield',
						'heading'          => esc_html__( 'Paddings (top right bottom left)', 'xstore-core' ),
						'param_name'       => 'paddings',
						'description'      => esc_html__( 'Use this field to add element paddings. For example 10px 20px 30px 40px', 'xstore-core' ),
						'edit_field_class' => 'vc_col-sm-4 vc_column',
					),
					array(
						'type'       => 'textfield',
						'heading'    => esc_html__( 'Extra class', 'xstore-core' ),
						'param_name' => 'el_class',
					),
					array(
						'type'       => 'css_editor',
						'heading'    => esc_html__( 'CSS box', 'xstore-core' ),
						'param_name' => 'css',
						'group'      => __( 'Design Options', 'xstore-core' ),
					)
				)
			),
		);
		
		return $shortcodes;
	}
	
	/**
	 * If 'tag' field used this is list of allowed tags
	 * To modify this list, you should use add_filter('vc_font_container_get_allowed_tags','your_custom_function');
	 * vc_filter: vc_font_container_get_allowed_tags - to modify list of allowed tags by default
	 * @return array list of allowed tags
     * it is full copy of origin function because we cannot take it from origin class
	 */
	public function vc_font_container_get_allowed_tags() {
		$allowed_tags = array(
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			'p',
			'div',
		);
		
		return apply_filters( 'vc_font_container_get_allowed_tags', $allowed_tags );
	}
	
	function vc_font_container_output_data( $data, $fields, $values, $settings ) {
		$tooltip        = false;
		
		if ( ! empty( $fields ) ) {
			if ( isset( $fields['tag'] ) ) {
				$data['tag'] = '
	            <div class="vc_row-fluid vc_column">
	            	<div class="wpb_element_label">' . esc_html__( 'Element tag', 'xstore-core' ) . '</div>
	                <div class="vc_font_container_form_field-tag-container hidden">
	                    <select class="vc_font_container_form_field-tag-select">';
				$tags        = $this->vc_font_container_get_allowed_tags();
				foreach ( $tags as $tag ) {
					$data['tag'] .= '<option value="' . $tag . '" class="' . $tag . '" ' . ( $values['tag'] === $tag ? 'selected' : '' ) . '>' . $tag . '</option>';
				}
				$data['tag'] .= '</select>';
				$data['tag'] .= '</div>';
				$data['tag'] .= '<div class="xstore-vc-button-set et-font_container" data-type="tag">';
				$data['tag'] .= '<ul class="xstore-vc-button-set-list">';
				foreach ( $tags as $tag ) {
					$data['tag'] .= '<li class="vc-button-set-item' . ( $tooltip ? 'mtips mtips-top' : '' ) . ( $values['tag'] === $tag ? ' active' : '' ) . '" data-value="' . $tag . '">';
					$data['tag'] .= '<span>' . $tag . '</span>';
					if ( $tooltip ) {
						$data['tag'] .= '<span class="mt-mes">' . $tag . '</span>';
					}
					$data['tag'] .= '</li>';
				}
				$data['tag'] .= '</ul>';
				$data['tag'] .= '</div>';
				
				$data['tag'] .= '</div>';
			}
			
			if ( isset( $fields['text_align'] ) ) {
				
				$align = array(
					'left'    => esc_html__( 'left', 'xstore-core' ),
					'right'   => esc_html__( 'right', 'xstore-core' ),
					'center'  => esc_html__( 'center', 'xstore-core' ),
					'justify' => esc_html__( 'justify', 'xstore-core' ),
				);
				
				$data['text_align'] = '
	            <div class="vc_row-fluid vc_column">
	                <div class="wpb_element_label">' . esc_html__( 'Text align', 'xstore-core' ) . '</div>
	                <div class="vc_font_container_form_field-text_align-container hidden">
	                    <select class="vc_font_container_form_field-text_align-select">';
				foreach ( $align as $key => $value ) {
					$data['text_align'] .= '<option value="' . $key . '" class="' . $key . '" ' . ( $key === $values['text_align'] ? 'selected="selected"' : '' ) . '>' . $value . '</option>';
				}
				$data['text_align'] .= '</select>
	                </div>';
				$data['text_align'] .= '<div class="xstore-vc-button-set et-font_container" data-type="text_align">';
				$data['text_align'] .= '<ul class="xstore-vc-button-set-list">';
				foreach ( $align as $key => $value ) {
					$data['text_align'] .= '<li class="vc-button-set-item' . ( $tooltip ? 'mtips mtips-top' : '' ) . ( $key === $values['text_align'] ? ' active' : '' ) . '" data-value="' . $key . '">';
					$data['text_align'] .= '<span>' . $key . '</span>';
					if ( $tooltip ) {
						$data['text_align'] .= '<span class="mt-mes">' . $value . '</span>';
					}
					$data['text_align'] .= '</li>';
				}
				$data['text_align'] .= '</ul>';
				$data['text_align'] .= '</div>';
				$data['text_align'] .= '</div>';
			}
		}
		
		return $data;
	}
	
	public function add_fonts($fonts) {
		$fonts_list = '[{"font_family":"Catamaran","font_styles":"regular","font_types":"100 thin:100:normal,200 extra-light:200:normal,300 light:300:normal,400 regular:400:normal,500 medium:500:normal,600 semi-bold:600:normal,700 bold:700:normal,800 extra-bold:800:normal,900 black:900:normal"},{"font_family":"Cormorant Garamond","font_styles":"serif,regular,italic,300italic,400italic,500italic,600italic,700italic","font_types":"300 light regular:300:normal,300 light italic:300:italic,400 regular:400:normal,400 italic:400:italic,500 bold regular:500:normal,500 bold italic:500:italic,700 bold regular:700:normal,700 bold italic:700:italic"},{"font_family":"Norican","font_styles":"cursive","font_types":"400 regular:400:normal"},{"font_family":"Molle","font_styles":"400i","font_types":"400 italic:400:italic"},{"font_family":"Palanquin","font_styles":"regular","font_types":"100 thin:100:normal,200 extra-light:200:normal,300 light:300:normal,400 regular:400:normal,500 medium:500:normal,600 semi-bold:600:normal,700 bold:700:normal"},{"font_family":"Trirong","font_styles":"serif,regular,italic,100italic,200italic,300italic,400italic,500italic,600italic,700italic,800italic,900italic","font_types":"100 lighter regular:100:normal,100 lighter italic:100:italic,200 lighter regular:200:normal,200 lighter italic:200:italic,300 light regular:300:normal,300 light italic:300:italic,400 regular:400:normal,400 italic:400:italic,500 bold regular:500:normal,500 bold italic:500:italic,600 bold regular:600:normal,600 bold italic:600:italic,700 bold regular:700:normal,700 bold italic:700:italic,800 bolder regular:800:normal,800 bolder italic:800:italic,900 bolder regular:900:normal,900 bolder italic:900:italic"}, {"font_family":"Yantramanav","font_styles":"100,300,regular,500,700,900","font_types":"100 ultra-light regular:100:normal,300 light regular:300:normal,400 regular:400:normal,500 medium regular:500:normal,700 bold regular:700:normal,900 ultra-bold regular:900:normal"},{"font_family":"Poppins","font_styles":"regular","font_types":"300 light:300:normal,400 regular:400:normal,500 medium:500:normal,600 semi-bold:600:normal,700 bold:700:normal,800 extra-bold:800:normal,800 extra-bold italic:800:italic,900 black:900:normal, 900 black italic:900:italic"}]';
		
		$et_fonts      = get_option( 'etheme-fonts', false );
		$et_fonts_list = array();
		
		if ( $et_fonts ) {
			foreach ( $et_fonts as $value ) {
				$et_fonts_list[] = '{"font_family":"' . $value['name'] . '"}';
			}
		}
		
		$et_fonts_list = '[' . implode( ',', $et_fonts_list ) . ']';
		
		$fonts = array_merge( json_decode( $et_fonts_list ), $fonts, json_decode( $fonts_list ) );
		
		return $fonts;
    }
	
	public static function fields() {
		return array(
			'title-divider',
			'image-select',
			'slider',
			'responsive-size',
			'button-set',
			'uniqid'
		);
	}

	public function etheme_product_stock_render( $atts ) {
		
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		
		global $post, $woocommerce_loop;
		$id      = $post->ID;
		$product = wc_get_product( $id );
		if ( !is_object($product)) return;
		
		if ( ! $product->is_purchasable() ) {
			return;
		}
		
		$atts = shortcode_atts(
			array(
				'product_stock_type' => 'default',
				'align'              => 'left',
				'color'              => '#000',
				'css'                => '',
				'el_class'           => '',
			), $atts );
		
		$custom_template = $this->get_custom_product_template();
		if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
			$custom_template = $woocommerce_loop['custom_template'];
		}
		
		$out = $out_css = '';
		
		$options = array();
		
		if ( ! empty( $atts['css'] ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$atts['el_class'] .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
		}
		
		// add style one time on first load
		
		$options['inline_css'] = apply_filters( 'etheme_output_shortcodes_inline_css', isset($_GET['vc_editable']) );
		
		if ( ( ! $options['inline_css'] && ! isset( $woocommerce_loop[ 'custom_template_' . $custom_template . 'stock_css_added' ] ) ) || $options['inline_css'] ) {
			
			$options['selectors'] = array(
				'stock' => '.products-template-' . $custom_template . ' .content-product .product-stock-wrapper .stock'
			);
			
			$options['css'] = array(
				'stock' => array(
					'display: block',
					'position: static',
					'transform: none',
					'padding: 0',
					'margin: 0',
					'font-size: 1rem',
					'background: transparent'
				),
			);
			
			if ( ! empty( $atts['align'] ) ) {
				$options['css']['stock'][] = 'text-align: ' . $atts['align'];
			}
			
			if ( ! empty( $atts['color'] ) ) {
				$options['css']['stock'][] = 'color: ' . $atts['color'];
			}
			
			$options['output_css'] = array();
			
			if ( count( $options['css']['stock'] ) ) {
				$options['output_css'][] = $options['selectors']['stock'] . ' {' . implode( ';', $options['css']['stock'] ) . '}';
			}
		}
		
		if ( ! $options['inline_css'] ) {
			if ( ! isset( $woocommerce_loop[ 'custom_template_' . $custom_template . 'stock_css_added' ] ) ) {
				
				wp_add_inline_style( 'xstore-inline-css', implode( ' ', $options['output_css'] ) );
				
				$woocommerce_loop[ 'custom_template_' . $custom_template . 'stock_css_added' ] = 'true';
			}
		} else {
			$out_css .= '<style>' . implode( ' ', $options['output_css'] ) . '</style>';
		}
		
		ob_start();
		
		if ( $atts['product_stock_type'] == 'default' ) {
			echo wc_get_stock_html( $product ); // WPCS: XSS ok.
		} else {
			if ( $product->is_in_stock() ) {
				echo et_product_stock_line( $product );
			} else {
				echo wc_get_stock_html( $product ); // WPCS: XSS ok.
			}
		}
		
		$out = ob_get_clean();
		
		if ( ! empty( $out ) ) {
			$out = '<div class="product-stock-wrapper ' . $atts['el_class'] . '">' . $out . '</div>';
		}
		
		return $out_css . $out;
	}

    /* **************************** */
    /* === Product title render === */
    /* **************************** */
	public function etheme_product_name_render( $atts ) {
		
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		
		global $post, $woocommerce_loop;
		$id      = $post->ID;
		$product = wc_get_product($id);
		if ( !is_object($product)) return;
		
		if ( ! is_array( $atts ) ) {
			$atts = array();
		}
		
		extract( shortcode_atts(
				array(
					'align'              => '',
					'link'               => 'product_link',
					'url'                => '',
					'symbols'            => '...',
					'title_google_fonts' => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
					'cutting'            => '',
					'count'              => '',
					'spacing'            => '',
					'size'               => '',
					'line_height'        => '',
					'color'              => '',
					'el_class'           => '',
					'css'                => ''
				), $atts )
		);
		
		$full_name = $post_name = unicode_chars( $product->get_title() );
		
		// get the link
		$link     = ( $link != '' ) ? get_permalink() : '';
		$url      = vc_build_link( $url );
		$a_target = '_self';
		$a_title  = $class = $style = '';
		$el_class .= ( ! empty( $align ) ) ? ' text-' . $align : '';
		if ( ! empty( $css ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$el_class .= ' ' . vc_shortcode_custom_css_class( $css );
		}
		
		if ( isset( $url['url'] ) && strlen( $url['url'] ) > 0 ) {
			$link     = $url['url'];
			$a_title  = $url['title'];
			$a_target = strlen( $url['target'] ) > 0 ? $url['target'] : '_self';
		}
		
		if ( strlen( $post_name ) > 0 && $cutting != 'none' ) {
			if ( $cutting == 'letters' ) {
				$split = preg_split( '/(?<!^)(?!$)/u', $post_name );
			} else {
				$split = explode( ' ', $post_name );
			}
			
			$post_name = ( $count != '' && $count > 0 && ( count( $split ) >= $count ) ) ? '' : $post_name;
			if ( $post_name == '' ) {
				if ( $cutting == 'letters' ) {
					for ( $i = 0; $i < $count; $i ++ ) {
						$post_name .= $split[ $i ];
					}
				} else {
					for ( $i = 0; $i < $count; $i ++ ) {
						$post_name .= ' ' . $split[ $i ];
					}
				}
			}
			if ( strlen( $post_name ) < strlen( $full_name ) ) {
				$post_name .= $symbols;
			}
		}
		
		$custom_template = $this->get_custom_product_template();
		if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
			$custom_template = $woocommerce_loop['custom_template'];
		}
		
		$out = '';
		
		$options = array();
		
		$options['inline_css'] = apply_filters( 'etheme_output_shortcodes_inline_css', isset($_GET['vc_editable']) );
		
		if ( ( ! $options['inline_css'] && ! isset( $woocommerce_loop[ 'custom_template_' . $custom_template . 'title_css_added' ] ) ) || $options['inline_css'] ) {
			
			$options['selectors'] = array(
				'title' => '.products-template-' . $custom_template . ' .content-product .product-title'
			);
			
			$options['css'] = array(
				'title' => array()
			);
			
			if ( ! empty( $spacing ) ) {
				$options['css']['title'][] = 'letter-spacing: ' . $spacing;
			}
			
			if ( ! empty( $color ) ) {
				$options['css']['title'][] = 'color: ' . $color;
			}
			
			if ( ! empty( $size ) ) {
				$options['css']['title'][] = 'font-size: ' . $size;
			}
			
			$options['output_css'] = array();
			
			if ( count( $options['css']['title'] ) ) {
				$options['output_css'][] = $options['selectors']['title'] . ' {' . implode( ';', $options['css']['title'] ) . '}';
			}
		}
		
		// add style one time on first load
		
		if ( ! $options['inline_css'] ) {
			if ( ! isset( $woocommerce_loop[ 'custom_template_' . $custom_template . 'title_css_added' ] ) ) {
				
				wp_add_inline_style( 'xstore-inline-css', implode( ' ', $options['output_css'] ) );
				
				$woocommerce_loop[ 'custom_template_' . $custom_template . 'title_css_added' ] = 'true';
			}
		} else {
			
			$out .= '<style>' . implode( ' ', $options['output_css'] ) . '</style>';
		}
		
		unset( $options );
		
		$atts['title_link'] = '';
		$atts['title']      = $post_name;
		
		$out .= '<div class="' . $el_class . ' text-' . $align . '">';
		
		$out .= ( $link != '' ) ? '<a href="' . $link . '" title="' . $a_title . '" target="' . $a_target . '">' : '';
		
		$out .= Shortcodes::getHeading( 'title', $atts, 'product-title' );
		
		$out .= ( $link != '' ) ? '</a>' : '';
		
		$out .= '</div>';
		
		return $out; // usage of template variable post_data with argument "ID"
	}


/* **************************** */
/* === Product image render === */
/* **************************** */


	public function etheme_product_image_render( $atts ) {
		global $post;
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		
		extract( shortcode_atts(
				array(
					'link'         => 'product_link',
					'url'          => '',
					'align'        => '',
					'style'        => '',
					'hover'        => '',
					'size'         => 'woocommerce_thumbnail',
					'size_custom' => '',
					'stock_status' => 'no',
					'border_color' => '',
					'el_class'     => '',
					'css'          => ''
				), $atts )
		);
		
		// backward compatibility to woocommerce <= 3.8.0 because in newest versions few sizes were removed
		$size =
			str_replace(
				array( 'shop_thumbnail', 'shop_catalog', 'shop_single' ),
				array( 'woocommerce_gallery_thumbnail', 'woocommerce_thumbnail', 'woocommerce_single' ),
				$size
			);
		
		$id       = $post->ID;
		$product = wc_get_product($id);
		if ( !is_object($product)) return;
		$el_class .= ' ' . $style;
		$el_class .= ' hover-effect-' . $hover;
		// get the link
		$link     = ( $link != '' ) ? get_permalink() : '';
		$url      = vc_build_link( $url );
		$a_target = '_self';
		$a_title  = '';
		if ( isset( $url['url'] ) && strlen( $url['url'] ) > 0 ) {
			$link     = $url['url'];
			$a_title  = $url['title'];
			$a_target = strlen( $url['target'] ) > 0 ? $url['target'] : '_self';
		}
		
		// get the css
		if ( ! empty( $css ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$el_class .= ' ' . vc_shortcode_custom_css_class( $css );
		}
		
		// vc image style
		$el_class .= ( $border_color != '' ) ? ' vc_box_border_' . $border_color : ' vc_box_border_grey';
		
		// product image under
		$post_thumbnail_id = get_post_thumbnail_id( $id );
		$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
		$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
		$attributes        = array(
			'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
			'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
			'data-src'                => (isset($full_size_image[0])) ? $full_size_image[0] : '',
			'data-large_image'        => (isset($full_size_image[0])) ? $full_size_image[0] : '',
			'data-large_image_width'  => (isset($full_size_image[1])) ? $full_size_image[1] : '',
			'data-large_image_height' => (isset($full_size_image[2])) ? $full_size_image[2] : '',
		);
		
		$img = '';
		
		if ( $size == 'custom' ) {
			if ( ! in_array( $size_custom, array( 'thumbnail', 'medium', 'large', 'full' ) ) ) {
				$size = explode( 'x', $size_custom );
			} else {
				$size = $size_custom;
			}
			$img = etheme_get_image($post_thumbnail_id, $size);
		}
		else {
			$img = ( get_the_post_thumbnail( $id, $size ) != '' ) ? get_the_post_thumbnail( $id, $size, $attributes ) : wc_placeholder_img();
		}
		
		// echo product image
		ob_start();
		
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		
		?>
        <div class="wpb_single_image text-<?php echo esc_attr( $align );
		echo ( 'slider' == $hover ) ? ' arrows-hovered' : ''; ?>">
            <div class="product-image-wrapper vc_single_image-wrapper <?php echo esc_attr( $el_class ); ?>">
				<?php
				
				if ( $hover == 'slider' ) {
					echo '<div class="images-slider-wrapper">';
				}
				
				do_action( 'woocommerce_before_shop_loop_item' );
				if ( $stock_status != 'yes' ) {
					etheme_product_availability();
				}
				?>
				
				<?php if ( $link != '' ) { ?> <a class="product-content-image" href="<?php echo esc_url( $link ); ?>"
                                                 data-images="<?php echo etheme_get_image_list( 'woocommerce_thumbnail' ); ?>"> <?php } ?>
					<?php if ( $hover == 'swap' ) {
						echo etheme_get_second_image( $size );
					} ?>
					<?php echo ( '' != $img ) ? $img : wc_placeholder_img(); ?>
					<?php if ( $link != '' ) { ?> </a> <?php } ?>
				
				<?php if ( $hover == 'slider' ) {
					echo '</div>';
				} ?>
            </div>
        </div>
		
		<?php add_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 ); ?>
		
		
		<?php return ob_get_clean(); // usage of template variable post_data with argument "ID"
	}


/* **************************** */
/* === Product excerpt render === */
/* **************************** */


	public function etheme_product_excerpt_render( $atts ) {
		global $post, $woocommerce_loop;
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		
		$id      = $post->ID;
		$product = wc_get_product( $id );
		if ( !is_object($product)) return;
		
		$atts = shortcode_atts(
			array(
				'cutting'                => '',
				'count'                  => '',
				'align'                  => '',
				'title_google_fonts'     => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
				'use_custom_fonts_title' => '',
				'title_font_container'   => '',
				'title_use_theme_fonts'  => '',
				'symbols'                => '...',
				'spacing'                => '',
				'size'                   => '',
				'color'                  => '',
				'css'                    => '',
				'el_class'               => ''
			), $atts );
		
		$atts['el_class'] .= ( ! empty( $atts['align'] ) ) ? ' text-' . $atts['align'] : '';
		if ( ! empty( $atts['css'] ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$atts['el_class'] .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
		}
		
		$custom_template = $this->get_custom_product_template();
		if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
			$custom_template = $woocommerce_loop['custom_template'];
		}
		
		$out = '';
		
		$options = array();
		
		$options['excerpt'] = $options['short_descr'] = unicode_chars( $product->get_short_description() );
		if ( strlen( $options['short_descr'] ) > 0 && $atts['cutting'] != 'none' ) {
			if ( $atts['cutting'] == 'letters' ) {
				$split = preg_split( '/(?<!^)(?!$)/u', $options['short_descr'] );
			} else {
				$split = explode( ' ', $options['short_descr'] );
			}
			
			$options['excerpt'] = ( $atts['count'] != '' && $atts['count'] > 0 && ( count( $split ) >= $atts['count'] ) ) ? '' : $options['short_descr'];
			if ( $options['excerpt'] == '' ) {
				if ( $atts['cutting'] == 'letters' ) {
					for ( $i = 0; $i < $atts['count']; $i ++ ) {
						$options['excerpt'] .= $split[ $i ];
					}
				} else {
					for ( $i = 0; $i < $atts['count']; $i ++ ) {
						$options['excerpt'] .= ' ' . $split[ $i ];
					}
				}
			}
			if ( strlen( $options['excerpt'] ) < strlen( $options['short_descr'] ) ) {
				$options['excerpt'] .= $atts['symbols'];
			}
		}
		
		// add style one time on first load
		
		$options['inline_css'] = apply_filters( 'etheme_output_shortcodes_inline_css', isset($_GET['vc_editable']) );
		
		if ( ( ! $options['inline_css'] && ! isset( $woocommerce_loop[ 'custom_template_' . $custom_template . 'excerpt_css_added' ] ) ) || $options['inline_css'] ) {
			
			$options['selectors'] = array(
				'excerpt' => '.products-template-' . $custom_template . ' .content-product .excerpt'
			);
			
			$options['css'] = array(
				'excerpt' => array()
			);
			
			if ( ! empty( $atts['spacing'] ) ) {
				$options['css']['excerpt'][] = 'letter-spacing: ' . $atts['spacing'];
			}
			
			if ( ! empty( $atts['color'] ) ) {
				$options['css']['excerpt'][] = 'color: ' . $atts['color'];
			}
			
			if ( ! empty( $atts['size'] ) ) {
				$options['css']['excerpt'][] = 'font-size: ' . $atts['size'];
			}
			
			$options['output_css'] = array();
			
			if ( count( $options['css']['excerpt'] ) ) {
				$options['output_css'][] = $options['selectors']['excerpt'] . ' {' . implode( ';', $options['css']['excerpt'] ) . '}';
			}
			
		}
		
		if ( ! $options['inline_css'] ) {
			if ( ! isset( $woocommerce_loop[ 'custom_template_' . $custom_template . 'excerpt_css_added' ] ) ) {
				
				wp_add_inline_style( 'xstore-inline-css', implode( ' ', $options['output_css'] ) );
				
				$woocommerce_loop[ 'custom_template_' . $custom_template . 'excerpt_css_added' ] = 'true';
			}
		} else {
			$out .= '<style>' . implode( ' ', $options['output_css'] ) . '</style>';
		}
		
		$atts['title_link'] = '';
		$atts['title']      = $options['excerpt'];
		
		unset( $options );
		
		$out .= Shortcodes::getHeading( 'title', $atts, 'excerpt ' . $atts['el_class'] );
		
		return $out;
	}


/* **************************** */
/* === Product rating render === */
/* **************************** */


	public function etheme_product_rating_render( $atts ) {
		global $post;
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		
		$id      = $post->ID;
		$product = wc_get_product( $id );
		if ( !is_object($product)) return;
		
		extract( shortcode_atts(
				array(
					'default'  => '',
					'css'      => '',
					'el_class' => ''
				), $atts )
		);
		$rating_count = $product->get_rating_count();
		$review_count = $product->get_review_count();
		$average      = $product->get_average_rating();
		$out          = '';
		
		if ( ! empty( $css ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$el_class .= ' ' . vc_shortcode_custom_css_class( $css );
		}
		
		if ( $default ) {
			$rating_html = '<div class="woocommerce-product-rating ' . $el_class . '">';
			$rating_html .= '<div class="star-rating" title="' . sprintf( esc_attr__( 'Rated %s out of 5', 'xstore-core' ), $average ) . '">';
			$rating_html .= '<span style="width:' . ( ( $average / 5 ) * 100 ) . '%"><strong class="rating">' . $average . '</strong> ' . esc_html__( 'out of 5', 'xstore-core' ) . '</span>';
			$rating_html .= '</div>';
			$rating_html .= '</div>';
			$out         = apply_filters( 'woocommerce_product_get_rating_html', $rating_html, $average );
		} elseif ( $rating_count > 0 ) {
			$out .= '<div class="woocommerce-product-rating ' . $el_class . '">';
			$out .= wc_get_rating_html( $average, $rating_count );
			$out .= '</div>';
		}
		
		return $out;
	}


/* **************************** */
/* === Product price render === */
/* **************************** */


	public function etheme_product_price_render( $atts ) {
		global $post, $woocommerce_loop;
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		$id      = $post->ID;
		$product = wc_get_product( $id );
		if ( !is_object($product)) return;
		
		extract( shortcode_atts(
				array(
					'align'      => '',
					'spacing'    => '',
					'color'      => '',
					'color_sale' => '',
					'size'       => '',
					'css'        => '',
					'el_class'   => ''
				), $atts )
		);
		
		$el_class .= ( ! empty( $align ) ) ? ' text-' . $align : '';
		$out      = '';
		
		if ( ! empty( $css ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$el_class .= ' ' . vc_shortcode_custom_css_class( $css );
		}
		
		$custom_template = $this->get_custom_product_template();
		if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
			$custom_template = $woocommerce_loop['custom_template'];
		}
		
		$options = array();
		
		$options['inline_css'] = apply_filters( 'etheme_output_shortcodes_inline_css', isset($_GET['vc_editable']) );
		
		if ( ( ! $options['inline_css'] && ! isset( $woocommerce_loop[ 'custom_template_' . $custom_template . 'price_css_added' ] ) ) || $options['inline_css'] ) {
			
			$options['selectors'] = array();
			
			$options['selectors']['custom_template'] = '.products-template-' . $custom_template;
			$options['selectors']['price']           = $options['selectors']['custom_template'] . ' .content-product .price';
			$options['selectors']['sale_price']      = $options['selectors']['price'] . ' ins .amount';
			
			$options['css'] = array(
				'price'      => array(),
				'sale_price' => array()
			);
			
			if ( ! empty( $spacing ) ) {
				$options['css']['price'][] = 'letter-spacing: ' . $spacing;
			}
			
			if ( ! empty( $color ) ) {
				$options['css']['price'][] = 'color: ' . $color;
			}
			
			if ( ! empty( $size ) ) {
				$options['css']['price'][] = 'font-size: ' . $size;
			}
			
			// sale price
			if ( ! empty( $color_sale ) ) {
				$options['css']['sale_price'][] = 'color: ' . $color_sale;
			}
			
			$options['output_css'] = array();
			
			if ( count( $options['css']['price'] ) ) {
				$options['output_css'][] = $options['selectors']['price'] . ' {' . implode( ';', $options['css']['price'] ) . '}';
			}
			
			if ( count( $options['css']['sale_price'] ) ) {
				$options['output_css'][] = $options['selectors']['sale_price'] . ' {' . implode( ';', $options['css']['sale_price'] ) . '}';
			}
			
		}
		
		// add style one time on first load
		
		if ( ! $options['inline_css'] ) {
			if ( ! isset( $woocommerce_loop[ 'custom_template_' . $custom_template . 'price_css_added' ] ) ) {
				
				wp_add_inline_style( 'xstore-inline-css', implode( ' ', $options['output_css'] ) );
				
				$woocommerce_loop[ 'custom_template_' . $custom_template . 'price_css_added' ] = 'true';
			}
		} else {
			$out .= '<style>' . implode( ' ', $options['output_css'] ) . '</style>';
		}
		
		
		$out .= '<div class="price ' . $el_class . '">';
		
		$out .= $product->get_price_html();
		
		$out .= '</div>';
		
		unset( $options );
		
		return $out;
	}


/* **************************** */
/* === Product sku render === */
/* **************************** */


	public function etheme_product_sku_render( $atts ) {
		global $post, $woocommerce_loop;
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		$id      = $post->ID;
		$product = wc_get_product( $id );
		if ( !is_object($product)) return;
		
		extract( shortcode_atts(
				array(
					'align'     => '',
					'transform' => '',
					'color'     => '',
					'size'      => '',
					'css'       => '',
					'el_class'  => ''
				), $atts )
		);
		
		$el_class .= ( ! empty( $align ) ) ? ' text-' . $align : '';
		$el_class .= ( ! empty( $transform ) ) ? ' ' . $transform : '';
		
		$custom_template = $this->get_custom_product_template();
		if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
			$custom_template = $woocommerce_loop['custom_template'];
		}
		
		$options = array();
		
		$options['sku'] = $product->get_sku();
		$options['out'] = '';
		
		if ( ! empty( $css ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$el_class = ' ' . vc_shortcode_custom_css_class( $css );
		}
		
		$options['inline_css'] = apply_filters( 'etheme_output_shortcodes_inline_css', isset($_GET['vc_editable']) );
		
		if ( ( ! $options['inline_css'] && ! isset( $woocommerce_loop[ 'custom_template_' . $custom_template . 'sku_css_added' ] ) ) || $options['inline_css'] ) {
			
			$options['selectors'] = array(
				'sku' => '.products-template-' . $custom_template . ' .content-product .sku'
			);
			
			$options['css'] = array(
				'sku' => array()
			);
			
			if ( ! empty( $color ) ) {
				$options['css']['sku'][] = 'color: ' . $color;
			}
			
			if ( ! empty( $size ) ) {
				$options['css']['sku'][] = 'font-size: ' . $size;
			}
			
			$options['output_css'] = array();
			
			if ( count( $options['css']['sku'] ) ) {
				$options['output_css'][] = $options['selectors']['sku'] . ' {' . implode( ';', $options['css']['sku'] ) . '}';
			}
			
		}
		
		// add style one time on first load
		
		if ( ! $options['inline_css'] ) {
			if ( ! isset( $woocommerce_loop[ 'custom_template_' . $custom_template . 'sku_css_added' ] ) ) {
				
				wp_add_inline_style( 'xstore-inline-css', implode( ' ', $options['output_css'] ) );
				
				$woocommerce_loop[ 'custom_template_' . $custom_template . 'sku_css_added' ] = 'true';
			}
		} else {
			$options['out'] .= '<style>' . implode( ' ', $options['output_css'] ) . '</style>';
		}
		
		if ( strlen( $options['sku'] ) > 0 ) {
			
			$options['out'] .= '<div class="sku ' . $el_class . '">';
			
			$options['out'] .= $options['sku'];
			
			$options['out'] .= '</div>';
		}
		
		$out = $options['out'];
		
		unset( $options );
		
		return $out;
	}



/* **************************** */
/* === Product brands render === */
/* **************************** */


	public function etheme_product_brands_render( $atts ) {
		global $post, $woocommerce_loop;
		
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		
		// The check for option is missing, so if brands are disabled, category pages break
		if ( ! etheme_get_option( 'enable_brands', true ) ) {
			return;
		}
		
		if ( !is_object(wc_get_product($post->ID))) return;
		
		extract( shortcode_atts(
				array(
					'align'     => '',
					'transform' => '',
					'spacing'   => '',
					'size'      => '',
					'img'       => '',
					'css'       => '',
					'el_class'  => ''
				), $atts )
		);
		
		$el_class .= ( ! empty( $align ) ) ? ' text-' . $align : '';
		$el_class .= ( ! empty( $transform ) ) ? ' ' . $transform : '';
		
		if ( ! empty( $css ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$el_class = ' ' . vc_shortcode_custom_css_class( $css );
		}
		
		$custom_template = $this->get_custom_product_template();
		if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
			$custom_template = $woocommerce_loop['custom_template'];
		}
		
		$options = array();
		
		$options['inline_css'] = apply_filters( 'etheme_output_shortcodes_inline_css', isset($_GET['vc_editable']) );
		
		if ( ( ! $options['inline_css'] && ! isset( $woocommerce_loop[ 'custom_template_' . $custom_template . 'brands_css_added' ] ) ) || $options['inline_css'] ) {
			
			$options['selectors'] = array(
				'brands' => '.products-template-' . $custom_template . ' .content-product .product_brand'
			);
			
			$options['css'] = array(
				'brands' => array()
			);
			
			if ( ! empty( $spacing ) ) {
				$options['css']['brands'][] = 'letter-spacing: ' . $spacing;
			}
			
			if ( ! empty( $color ) ) {
				$options['css']['brands'][] = 'color: ' . $color;
			}
			
			if ( ! empty( $size ) ) {
				$options['css']['brands'][] = 'font-size: ' . $size;
			}
			
			$options['output_css'] = array();
			
			if ( count( $options['css']['brands'] ) ) {
				$options['output_css'][] = $options['selectors']['brands'] . ' {' . implode( ';', $options['css']['brands'] ) . '}';
			}
			
		}
		
		$terms = wp_get_post_terms( $post->ID, 'brand' );
		if ( count( $terms ) < 1 ) {
			return;
		}
		
		ob_start();
		
		// add style one time on first load
		
		if ( ! $options['inline_css'] ) {
			if ( ! isset( $woocommerce_loop[ 'custom_template_' . $custom_template . 'brands_css_added' ] ) ) {
				
				wp_add_inline_style( 'xstore-inline-css', implode( ' ', $options['output_css'] ) );
				
				$woocommerce_loop[ 'custom_template_' . $custom_template . 'brands_css_added' ] = 'true';
			}
		} else {
			echo '<style>' . implode( ' ', $options['output_css'] ) . '</style>';
		}
		
		$_i = 0;
		?>
        <div class="product_brand <?php echo esc_attr( $el_class ); ?>">
			<?php foreach ( $terms as $brand ) : $_i ++; ?>
				<?php
				$thumbnail_id = absint( get_term_meta( $brand->term_id, 'thumbnail_id', true ) ); ?>
                <a href="<?php echo get_term_link( $brand ); ?>">
					<?php if ( $thumbnail_id && $img ) {
						echo wp_get_attachment_image( $thumbnail_id, 'full' );
					} else { ?>
						<?php echo esc_html( $brand->name ); ?>
					<?php } ?>
                </a>
				<?php if ( count( $terms ) > $_i ) {
					echo ", ";
				} ?>
			<?php endforeach; ?>
        </div>
		<?php
		
		unset( $options );
		
		return ob_get_clean();
	}


/* **************************** */
/* === Product categories render === */
/* **************************** */


	public function etheme_product_categories_render( $atts ) {
		global $post;
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		
		$id      = $post->ID;
		$product = wc_get_product( $id );
		if ( !is_object($product)) return;
		
		$atts    = shortcode_atts(
			array(
				'cutting'                => '',
				'count'                  => '',
				'tag'                    => 'p',
				'align'                  => '',
				'title_link'             => '',
				'title'                  => '',
				'title_google_fonts'     => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
				'use_custom_fonts_title' => '',
				'title_font_container'   => '',
				'title_use_theme_fonts'  => '',
				'symbols'                => '...',
				'css'                    => '',
				'el_class'               => ''
			), $atts );
		
		$atts['el_class'] .= ' products-page-cats';
		$atts['el_class'] .= ( ! empty( $atts['align'] ) ) ? ' text-' . $atts['align'] : '';
		
		if ( ! empty( $atts['css'] ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$atts['el_class'] .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
		}
		
		$atts['title_link'] = '';
		$atts['title']      = $cats = wc_get_product_category_list( $id, ', ' );
		
		if ( ! $atts['use_custom_fonts_title'] ) {
			$out = '<div class="' . $atts['el_class'] . '">' . $cats . '</div>';
		} else {
			$out = Shortcodes::getHeading( 'title', $atts, $atts['el_class'] );
		}
		
		return $out; // usage of template variable post_data with argument "ID"
	}
	
    /* **************************** */
    /* === Product buttons render === */
    /* **************************** */

	public function etheme_product_buttons_render( $atts ) {
		global $post, $woocommerce_loop;
		
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		
		$id      = $post->ID;
		$product = wc_get_product( $id );
		if ( !is_object($product)) return;
		
		extract( shortcode_atts(
				array(
					// Buttons types
					'cart_type'    => 'icon',
					'w_type'       => 'icon',
					'compare_type' => 'icon',
					'quick_type'   => 'icon',
					// Sizes
					'a_size'       => '',
					'w_size'       => '',
					'q_size'       => '',
					'c_size'       => '',
					// Transforms
					'a_transform'  => '',
					'w_transform'  => '',
					'q_transform'  => '',
					'c_transform'  => '',
					// Background colors
					'a_bg'         => '',
					'w_bg'         => '',
					'q_bg'         => '',
					'c_bg'         => '',
					
					// Background colors (hover)
					'a_hover_bg'   => '',
					'w_hover_bg'   => '',
					'q_hover_bg'   => '',
					'c_hover_bg'   => '',
					
					// Border radius
					'a_radius'     => '',
					'w_radius'     => '',
					'q_radius'     => '',
					'c_radius'     => '',
					
					// Margins
					'a_margin'     => '',
					'w_margin'     => '',
					'q_margin'     => '',
					'c_margin'     => '',
					
					// Common options
					'align'        => 'start',
					'v_align'      => 'start',
					'type'         => '',
					'color'        => '',
					'bg'           => '',
					'hover_color'  => '',
					'hover_bg'     => '',
					
					// Paddings and radius
					'radius'       => '',
					'paddings'     => '',
					
					'el_class' => '',
					'css'      => '',
					'sorting'  => '',
				), $atts )
		);
		$out = $style = $footer_class = '';
		
		$sorting = ( ! empty( $sorting ) ) ? explode( ',', $sorting ) : array();
		
		$custom_template = $this->get_custom_product_template();
		if ( ! empty( $woocommerce_loop['custom_template'] ) ) {
			$custom_template = $woocommerce_loop['custom_template'];
		}
		
		$footer_class .= ( ! empty( $align ) ) ? ' justify-content-' . $align : '';
		$footer_class .= ( ! empty( $v_align ) ) ? ' align-items-' . $v_align : '';
		$footer_class .= ' ' . $type;
		
		if ( ! empty( $css ) && function_exists( 'vc_shortcode_custom_css_class' ) ) {
			$el_class .= ' ' . vc_shortcode_custom_css_class( $css );
		}
		
		$sorting = array_flip( $sorting );
		
		$options = array();
		
		$options['inline_css'] = apply_filters( 'etheme_output_shortcodes_inline_css', isset($_GET['vc_editable']) );
		
		if ( ( ! $options['inline_css'] && ! isset( $woocommerce_loop[ 'custom_template_' . $custom_template . 'buttons_css_added' ] ) ) || $options['inline_css'] ) {
			
			$options['item']                     = '.products-template-' . $custom_template . ' .footer-product2';
			$options['item_hover']               = $options['item'] . ':hover';
			$options['add_to_cart_button']       = $options['item'] . ' .add_to_cart_button';
			$options['add_to_cart_button']       .= ', ' . $options['item'] . ' .product_type_external';
			$options['add_to_cart_button']       .= ', ' . $options['item'] . ' .product_type_variable';
			$options['add_to_cart_button']       .= ', ' . $options['item'] . ' .product_type_simple';
			$options['add_to_cart_button_hover'] = $options['item'] . ' .add_to_cart_button:hover';
			$options['add_to_cart_button_hover'] .= ', ' . $options['item'] . ' .product_type_external:hover';
			$options['add_to_cart_button_hover'] .= ', ' . $options['item'] . ' .product_type_variable:hover';
			$options['add_to_cart_button_hover'] .= ', ' . $options['item'] . ' .product_type_simple:hover';
			
			$options['wishlist']       = $options['item'] . ' .et-wishlist-holder';
			$options['wishlist_hover'] = $options['wishlist'] . ':hover';

            $options['wishlist'] .= ','.$options['item'] . ' .xstore-wishlist';
            $options['wishlist_hover'] .= ','.$options['item'] . ' .xstore-wishlist:hover';
			
			$options['quick_view']       = $options['item'] . ' .show-quickly';
			$options['quick_view_hover'] = $options['quick_view'] . ':hover';
			
			$options['compare']       = $options['item'] . ' .compare';
			$options['compare_hover'] = $options['compare'] . ':hover';

            $options['compare'] .= ','.$options['item'] . ' .xstore-compare';
            $options['compare_hover'] .= ','.$options['item'] . ' .xstore-compare:hover';
			
			$options['selectors_color']       = $options['item'] . '> *,' . $options['item'] . ' .button,' . $options['item'] . ' a';
			$options['selectors_color_hover'] = $options['item'] . '> *:hover,' . $options['item'] . '> *:hover *, ' . $options['item'] . ' .button:hover, ' . $options['item'] . ' .yith-wcwl-wishlistexistsbrowse.show a:before';
			
			$options['selectors'] = array(
				'item'                     => array(),
				'item_hover'               => array(),
				'add_to_cart_button'       => array(),
				'add_to_cart_button_hover' => array(),
				'wishlist'                 => array(),
				'wishlist_hover'           => array(),
				'quick_view'               => array(),
				'quick_view_hover'         => array(),
				'compare'                  => array(),
				'compare_hover'            => array(),
				'selectors_color'          => array(),
				'selectors_color_hover'    => array(),
			);
			
			if ( $color ) {
				$options['selectors']['selectors_color'][] = 'color:' . $color;
			}
			
			if ( $hover_color ) {
				$options['selectors']['selectors_color_hover'][] = 'color:' . $hover_color;
			}
			
			if ( $bg ) {
				$options['selectors']['item'][] = 'background-color:' . $bg;
			}
			
			if ( $hover_bg ) {
				$options['selectors']['item_hover'][] = 'background-color:' . $hover_bg;
			}
			
			if ( $radius ) {
				$options['selectors']['item'][] = 'border-radius:' . $radius;
			}
			
			if ( $paddings ) {
				$options['selectors']['item'][] = 'padding:' . $paddings;
			}
			
			// add to cart button
			if ( $a_transform ) {
				$options['selectors']['add_to_cart_button'][] = 'text-transform: ' . $a_transform;
			}
			
			if ( $a_size ) {
				$options['selectors']['add_to_cart_button'][] = 'font-size: ' . $a_size;
			}
			
			if ( $a_bg ) {
				$options['selectors']['add_to_cart_button'][] = 'background-color:' . $a_bg;
			}
			
			if ( $a_radius ) {
				$options['selectors']['add_to_cart_button'][] = 'border-radius:' . $a_radius;
			}
			
			if ( $a_margin ) {
				$options['selectors']['add_to_cart_button'][] = 'margin:' . $a_margin;
			}
			
			if ( isset( $sorting['cart'] ) ) {
				$options['selectors']['add_to_cart_button'][] = 'order: ' . $sorting['cart'];
			}
			
			// add to cart button on hover
			if ( $a_hover_bg ) {
				$options['selectors']['add_to_cart_button_hover'][] = 'background-color:' . $a_hover_bg;
			}
			
			// wishlist
			if ( $w_transform ) {
				$options['selectors']['wishlist'][] = 'text-transform: ' . $w_transform;
			}
			if ( $w_size ) {
				$options['selectors']['wishlist'][] = 'font-size: ' . $w_size;
			}
			if ( $w_bg ) {
				$options['selectors']['wishlist'][] = 'background-color:' . $w_bg;
			}
			if ( $w_radius ) {
				$options['selectors']['wishlist'][] = 'border-radius:' . $w_radius;
			}
			if ( $w_margin ) {
				$options['selectors']['wishlist'][] = 'margin:' . $w_margin;
			}
			if ( isset( $sorting['wishlist'] ) ) {
				$options['selectors']['wishlist'][] = 'order: ' . $sorting['wishlist'];
			}
			
			// wishlist hover
			if ( $w_hover_bg ) {
				$options['selectors']['wishlist_hover'][] = 'background-color:' . $w_hover_bg;
			}
			
			// quick view
			if ( $q_transform ) {
				$options['selectors']['quick_view'][] = 'text-transform: ' . $q_transform;
			}
			if ( $q_size ) {
				$options['selectors']['quick_view'][] = 'font-size: ' . $q_size;
			}
			if ( $q_bg ) {
				$options['selectors']['quick_view'][] = 'background-color:' . $q_bg;
			}
			if ( $q_radius ) {
				$options['selectors']['quick_view'][] = 'border-radius:' . $q_radius;
			}
			if ( $q_margin ) {
				$options['selectors']['quick_view'][] = 'margin:' . $q_margin;
			}
			if ( isset( $sorting['q_view'] ) ) {
				$options['selectors']['quick_view'][] = 'order: ' . $sorting['q_view'];
			}
			
			// quick view hover
			if ( $q_hover_bg ) {
				$options['selectors']['quick_view_hover'][] = 'background-color:' . $q_hover_bg;
			}
			
			// compare
			if ( $c_transform ) {
				$options['selectors']['compare'][] = 'text-transform: ' . $c_transform;
			}
			if ( $c_size ) {
				$options['selectors']['compare'][] = 'font-size: ' . $c_size;
			}
			if ( $c_bg ) {
				$options['selectors']['compare'][] = 'background-color:' . $c_bg;
			}
			if ( $c_radius ) {
				$options['selectors']['compare'][] = 'border-radius:' . $c_radius;
			}
			if ( $c_margin ) {
				$options['selectors']['compare'][] = 'margin:' . $c_margin;
			}
			if ( isset( $sorting['compare'] ) ) {
				$options['selectors']['compare'][] = 'order: ' . $sorting['compare'];
			}
			
			// compare hover
			if ( $c_hover_bg ) {
				$options['selectors']['compare_hover'][] = 'background-color:' . $c_hover_bg;
			}
			
			// output css generate
			$options['output_css'] = array();
			
			if ( count( $options['selectors']['item'] ) ) {
				$options['output_css'][] = $options['item'] . ' {' . implode( ';', $options['selectors']['item'] ) . '}';
			}
			
			if ( count( $options['selectors']['item_hover'] ) ) {
				$options['output_css'][] = $options['item_hover'] . ' {' . implode( ';', $options['selectors']['item_hover'] ) . '}';
			}
			
			if ( count( $options['selectors']['add_to_cart_button'] ) ) {
				$options['output_css'][] = $options['add_to_cart_button'] . ' {' . implode( ';', $options['selectors']['add_to_cart_button'] ) . '}';
			}
			
			if ( count( $options['selectors']['add_to_cart_button_hover'] ) ) {
				$options['output_css'][] = $options['add_to_cart_button_hover'] . ' {' . implode( ';', $options['selectors']['add_to_cart_button_hover'] ) . '}';
			}
			
			if ( count( $options['selectors']['wishlist'] ) ) {
				$options['output_css'][] = $options['wishlist'] . ' {' . implode( ';', $options['selectors']['wishlist'] ) . '}';
			}
			
			if ( count( $options['selectors']['wishlist_hover'] ) ) {
				$options['output_css'][] = $options['wishlist_hover'] . ' {' . implode( ';', $options['selectors']['wishlist_hover'] ) . '}';
			}
			
			if ( count( $options['selectors']['quick_view'] ) ) {
				$options['output_css'][] = $options['quick_view'] . ' {' . implode( ';', $options['selectors']['quick_view'] ) . '}';
			}
			
			if ( count( $options['selectors']['quick_view_hover'] ) ) {
				$options['output_css'][] = $options['quick_view_hover'] . ' {' . implode( ';', $options['selectors']['quick_view_hover'] ) . '}';
			}
			
			if ( count( $options['selectors']['compare'] ) ) {
				$options['output_css'][] = $options['compare'] . ' {' . implode( ';', $options['selectors']['compare'] ) . '}';
			}
			
			if ( count( $options['selectors']['compare_hover'] ) ) {
				$options['output_css'][] = $options['compare_hover'] . ' {' . implode( ';', $options['selectors']['compare_hover'] ) . '}';
			}
			
			if ( count( $options['selectors']['selectors_color'] ) ) {
				$options['output_css'][] = $options['selectors_color'] . ' {' . implode( ';', $options['selectors']['selectors_color'] ) . '}';
			}
			
			if ( count( $options['selectors']['selectors_color_hover'] ) ) {
				$options['output_css'][] = $options['selectors_color_hover'] . ' {' . implode( ';', $options['selectors']['selectors_color_hover'] ) . '}';
			}
			
		}
		
		ob_start();
		
		// add style one time on first load
		
		if ( ! $options['inline_css'] ) {
			if ( ! isset( $woocommerce_loop[ 'custom_template_' . $custom_template . 'buttons_css_added' ] ) ) {
				
				wp_add_inline_style( 'xstore-inline-css', implode( ' ', $options['output_css'] ) );
				
				$woocommerce_loop[ 'custom_template_' . $custom_template . 'buttons_css_added' ] = 'true';
			}
		} else {
			echo '<style>' . implode( ' ', $options['output_css'] ) . '</style>';
		}
		
		if ( count( $sorting ) > 0 && ! array_key_exists( 'compare', $sorting ) ) {
			$footer_class .= ' compare-hidden';
		}
		if ( ( count( $sorting ) > 0 && array_key_exists( 'cart', $sorting ) ) || count( $sorting ) == 0 ) {
			$footer_class .= ' cart-type-' . $cart_type;
		}
		if ( ( count( $sorting ) > 0 && array_key_exists( 'compare', $sorting ) ) || count( $sorting ) == 0 ) {
			$footer_class .= ' compare-type-' . $compare_type;
		}
		
		unset( $options );

		?>
        <footer class="footer-product2 <?php echo esc_attr( $footer_class ); ?>">
            <div class="footer-inner <?php echo esc_attr( $el_class ); ?>">
				<?php
				if ( ( count( $sorting ) > 0 && array_key_exists( 'q_view', $sorting ) ) || count( $sorting ) == 0 ) : ?>

                    <span class="show-quickly type-<?php echo esc_attr( $quick_type ); ?>"
                          data-prodid="<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Quick View', 'xstore-core' ); ?></span>
				
				<?php endif; ?>
				
				<?php if ( ( count( $sorting ) > 0 && array_key_exists( 'cart', $sorting ) ) || count( $sorting ) == 0 ) {
					do_action( 'woocommerce_after_shop_loop_item' );
				}
				
				if ( ( count( $sorting ) > 0 && array_key_exists( 'wishlist', $sorting ) ) || count( $sorting ) == 0 ) {
					echo etheme_wishlist_btn( array( 'type' => $w_type ) );
				}
				?>

            </div>
        </footer>

		<?php return get_theme_mod('xstore_compare', false) ? str_replace('xstore-compare ', 'xstore-compare type-'.$compare_type .' ', ob_get_clean()) : ob_get_clean();
		
	}


	function etheme_productBrandBrandAutocompleteSuggester( $query, $slug = false ) {
		global $wpdb;
		$cat_id          = (int) $query;
		$query           = trim( $query );
		$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.term_id AS id, b.name as name, b.slug AS slug
            FROM {$wpdb->term_taxonomy} AS a
            INNER JOIN {$wpdb->terms} AS b ON b.term_id = a.term_id
            WHERE a.taxonomy = 'brand' AND (a.term_id = '%d' OR b.slug LIKE '%%%s%%' OR b.name LIKE '%%%s%%' )", $cat_id > 0 ? $cat_id : - 1, stripslashes( $query ), stripslashes( $query ) ), ARRAY_A );
		
		$result = array();
		if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
			foreach ( $post_meta_infos as $value ) {
				$data          = array();
				$data['value'] = $slug ? $value['slug'] : $value['id'];
				$data['label'] = __( 'Id', 'xstore-core' ) . ': ' . $value['id'] . ( ( strlen( $value['name'] ) > 0 ) ? ' - ' . __( 'Name', 'xstore-core' ) . ': ' . $value['name'] : '' ) . ( ( strlen( $value['slug'] ) > 0 ) ? ' - ' . __( 'Slug', 'xstore-core' ) . ': ' . $value['slug'] : '' );
				$result[]      = $data;
			}
		}
		
		return $result;
	}
	
	function etheme_productBrandBrandRenderByIdExact( $query ) {
		$query  = $query['value'];
		$cat_id = (int) $query;
		$term   = get_term( $cat_id, 'brand' );
		
		return self::etheme_productBrandTermOutput( $term );
	}
	
	function etheme_vc_shortcodes_strings() {
		return array(
			'category' => 'xstore-core',
			'heading'  => array(
				'use_custom_font' => esc_html__( 'Use custom font ?', 'xstore-core' ),
				'align'           => esc_html__( 'Horizontal align', 'xstore-core' ),
				'valign'          => esc_html__( 'Vertical align', 'xstore-core' ),
				'ajax'            => esc_html__( 'Lazy loading for this element', 'xstore-core' ),
				'el_class'        => esc_html__( 'Extra Class', 'xstore-core' ),
				'order'           => esc_html__( 'Order way', 'xstore-core' ),
				'orderby'         => esc_html__( 'Order by', 'xstore-core' ),
				'text_transform'  => esc_html__( 'Text transform', 'xstore-core' ),
				'font_size'       => esc_html__( 'Font size', 'xstore-core' ),
				'line_height'     => esc_html__( 'Line height', 'xstore-core' ),
				'image_size'      => esc_html__( 'Image size', 'xstore-core' ),
				'letter_spacing'  => esc_html__( 'Letter spacing', 'xstore-core' ),
			),
			'hint'     => array(
				'img_size'          => esc_html__( 'Enter image size (Ex.: "medium", "large" etc.) or enter size in pixels (Ex.: 200x100 (WxH))', 'xstore-core' ),
				'el_class'          => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'xstore-core' ),
				'rs_font_size'      => esc_html__( 'Responsive font-size', 'xstore-core' ),
				'rs_line_height'    => esc_html__( 'Responsive line height', 'xstore-core' ),
				'rs_letter_spacing' => esc_html__( 'Responsive letter spacing', 'xstore-core' ),
			),
			'value'    => array(
				'align'         => array(
					'<span class="dashicons dashicons-editor-alignleft"></span>'   => 'left',
					'<span class="dashicons dashicons-editor-aligncenter"></span>' => 'center',
					'<span class="dashicons dashicons-editor-alignright"></span>'  => 'right'
				),
				'valign'        => array(
					'<span class="dashicons dashicons-align-none" style="transform: rotate(90deg);"></span>'  => 'top',
					'<span class="dashicons dashicons-align-center"></span>'                                  => 'middle',
					'<span class="dashicons dashicons-align-none" style="transform: rotate(-90deg);"></span>' => 'bottom'
				),
				'valign2'       => array(
					esc_html__( 'Start', 'xstore-core' )  => 'start',
					esc_html__( 'Center', 'xstore-core' ) => 'center',
					esc_html__( 'End', 'xstore-core' )    => 'end'
				),
				'font_style'    => array(
					'type1' => array(
						esc_html__( 'Dark', 'xstore-core' )  => 'dark',
						esc_html__( 'Light', 'xstore-core' ) => 'light',
					),
					'type2' => array(
						esc_html__( 'Dark', 'xstore-core' )  => 'dark',
						esc_html__( 'Light', 'xstore-core' ) => 'white',
					),
				),
				'order'         => array(
					esc_html__( 'Ascending', 'xstore-core' )  => 'ASC',
					esc_html__( 'Descending', 'xstore-core' ) => 'DESC',
				),
				'order_reverse' => array(
					esc_html__( 'Descending', 'xstore-core' ) => 'DESC',
					esc_html__( 'Ascending', 'xstore-core' )  => 'ASC',
				),
			),
			'tooltip'  => array(
				'align'  => array(
					'left'    => esc_html( 'Left', 'xstore-core' ),
					'center'  => esc_html( 'Center', 'xstore-core' ),
					'right'   => esc_html( 'Right', 'xstore-core' ),
					'justify' => esc_html( 'Justify', 'xstore-core' ),
				),
				'valign' => array(
					'top'    => esc_html( 'Top', 'xstore-core' ),
					'middle' => esc_html( 'Middle', 'xstore-core' ),
					'bottom' => esc_html( 'Bottom', 'xstore-core' )
				),
			)
		);
	}
	
	function etheme_vc_blog_shortcodes_strings() {
		return array(
			'value' => array(
				'blog_hover' => array(
					esc_html__( 'Zoom', 'xstore-core' )     => 'zoom',
					esc_html__( 'Default', 'xstore-core' )  => 'default',
					esc_html__( 'Animated', 'xstore-core' ) => 'animated',
				),
				'orderby'    => array(
					esc_html__( 'Date', 'xstore-core' )                  => 'date',
					esc_html__( 'Order by post ID', 'xstore-core' )      => 'ID',
					esc_html__( 'Author', 'xstore-core' )                => 'author',
					esc_html__( 'Title', 'xstore-core' )                 => 'title',
					esc_html__( 'Last modified date', 'xstore-core' )    => 'modified',
					esc_html__( 'Post/page parent ID', 'xstore-core' )   => 'parent',
					esc_html__( 'Number of comments', 'xstore-core' )    => 'comment_count',
					esc_html__( 'Menu order/Page Order', 'xstore-core' ) => 'menu_order',
					esc_html__( 'Meta value', 'xstore-core' )            => 'meta_value',
					esc_html__( 'Meta value number', 'xstore-core' )     => 'meta_value_num',
					// esc_html__('Matches same order you passed in via the 'include' parameter.', 'xstore-core') => 'post__in'
					esc_html__( 'Random order', 'xstore-core' )          => 'rand',
				),
			),
		);
	}
	
	public static function etheme_productBrandTermOutput( $term ) {
		$term_slug  = $term->slug;
		$term_title = $term->name;
		$term_id    = $term->term_id;
		
		$term_slug_display = '';
		if ( ! empty( $term_slug ) ) {
			$term_slug_display = ' - ' . __( 'Sku', 'xstore-core' ) . ': ' . $term_slug;
		}
		
		$term_title_display = '';
		if ( ! empty( $term_title ) ) {
			$term_title_display = ' - ' . __( 'Title', 'xstore-core' ) . ': ' . $term_title;
		}
		
		$term_id_display = __( 'Id', 'xstore-core' ) . ': ' . $term_id;
		
		$data          = array();
		$data['value'] = $term_id;
		$data['label'] = $term_id_display . $term_title_display . $term_slug_display;
		
		return ! empty( $data ) ? $data : false;
	}
	
	public function get_brands_list_params() {
		
		$counter = 0;
		
		return array(
			array(
				'type'       => 'xstore_title_divider',
				'title'      => esc_html__( 'Layout settings', 'xstore-core' ),
				'group'      => esc_html__( 'Brand settings', 'xstore-core' ),
				'param_name' => 'brands_divider' . $counter ++
			),
			array(
				"type"       => "checkbox",
				"heading"    => esc_html__( "Display A-Z filter", 'xstore-core' ),
				"param_name" => "hide_a_z",
				"group"      => esc_html__( 'Brand settings', 'xstore-core' ),
				'value'      => array( esc_html__( 'Yes, please', 'xstore-core' ) => 'yes' )
			),
			array(
				'type'       => 'xstore_button_set',
				'heading'    => esc_html__( 'Columns', 'xstore-core' ),
				'param_name' => 'columns',
				'group'      => esc_html__( 'Brand settings', 'xstore-core' ),
				'value'      => array(
					__( '1', 'xstore-core' ) => '1',
					__( '2', 'xstore-core' ) => '2',
					__( '3', 'xstore-core' ) => '3',
					__( '4', 'xstore-core' ) => '4',
					__( '5', 'xstore-core' ) => '5',
					__( '6', 'xstore-core' ) => '6',
				),
			),
			array(
				'type'       => 'xstore_title_divider',
				'title'      => esc_html__( 'Styles', 'xstore-core' ),
				'group'      => esc_html__( 'Brand settings', 'xstore-core' ),
				'param_name' => 'brands_divider' . $counter ++
			),
			array(
				'type'       => 'xstore_button_set',
				'heading'    => esc_html__( 'Alignment', 'xstore-core' ),
				'param_name' => 'alignment',
				'group'      => esc_html__( 'Brand settings', 'xstore-core' ),
				'value'      => array(
					__( 'Left', 'xstore-core' )   => 'left',
					__( 'Center', 'xstore-core' ) => 'center',
					__( 'Right', 'xstore-core' )  => 'right',
				),
			),
			array(
				"type"       => "checkbox",
				"heading"    => esc_html__( "Display brands capital letter", 'xstore-core' ),
				"param_name" => "capital_letter",
				"group"      => esc_html__( 'Brand settings', 'xstore-core' ),
				'value'      => array( esc_html__( 'Yes, please', 'xstore-core' ) => 'yes' )
			),
			array(
				'type'       => 'xstore_title_divider',
				'title'      => esc_html__( 'Image settings', 'xstore-core' ),
				'group'      => esc_html__( 'Brand settings', 'xstore-core' ),
				'param_name' => 'brands_divider' . $counter ++
			),
			array(
				"type"       => "checkbox",
				"heading"    => esc_html__( "Brand image", 'xstore-core' ),
				"param_name" => "brand_image",
				"group"      => esc_html__( 'Brand settings', 'xstore-core' ),
				'value'      => array( esc_html__( 'Yes, please', 'xstore-core' ) => 'yes' )
			),
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Images size', 'xstore-core' ),
				'param_name' => 'size',
				'value'      => '',
				'group'      => esc_html__( 'Brand settings', 'xstore-core' ),
				'dependency' => array( 'element' => "brand_image", 'value' => array( 'yes' ) ),
			),
			array(
				'type'       => 'xstore_title_divider',
				'title'      => esc_html__( 'Content settings', 'xstore-core' ),
				'group'      => esc_html__( 'Brand settings', 'xstore-core' ),
				'param_name' => 'brands_divider' . $counter ++
			),
			array(
				"type"       => "checkbox",
				"heading"    => esc_html__( "Brand title", 'xstore-core' ),
				"param_name" => "brand_title",
				"group"      => esc_html__( 'Brand settings', 'xstore-core' ),
				'value'      => array( esc_html__( 'Yes, please', 'xstore-core' ) => 'yes' ),
				'std'        => 'yes'
			),
			array(
				"type"       => "checkbox",
				"heading"    => esc_html__( "Title with tooltip", 'xstore-core' ),
				"param_name" => "tooltip",
				"group"      => esc_html__( 'Brand settings', 'xstore-core' ),
				'value'      => array( esc_html__( 'Yes, please', 'xstore-core' ) => 'yes' )
			),
			array(
				"type"       => "checkbox",
				"heading"    => esc_html__( "Brand description ", 'xstore-core' ),
				"param_name" => "brand_desc",
				"group"      => esc_html__( 'Brand settings', 'xstore-core' ),
				'value'      => array( esc_html__( 'Yes, please', 'xstore-core' ) => 'yes' )
			),
			array(
				"type"       => "checkbox",
				"heading"    => esc_html__( "Hide empty", 'xstore-core' ),
				"param_name" => "hide_empty",
				"group"      => esc_html__( 'Brand settings', 'xstore-core' ),
				'value'      => array( esc_html__( 'Yes, please', 'xstore-core' ) => 'yes' )
			),
			array(
				"type"       => "checkbox",
				"heading"    => esc_html__( "Show Product Counts", 'xstore-core' ),
				"param_name" => "show_product_counts",
				"group"      => esc_html__( 'Brand settings', 'xstore-core' ),
				'value'      => array( esc_html__( 'Yes, please', 'xstore-core' ) => 'yes' )
			),
		);
	}

	function get_slider_params( $dependency = false ) {
		
		$counter         = 0;
		$slider_settings = array(
			array(
				'type'       => 'xstore_title_divider',
				'title'      => esc_html__( 'General settings', 'xstore-core' ),
				'group'      => esc_html__( 'Slider settings', 'xstore-core' ),
				'param_name' => 'slider_divider' . $counter ++
			),
			array(
				"type"        => "textfield",
				"heading"     => esc_html__( "Slider speed", 'xstore-core' ),
				"param_name"  => "slider_speed",
				"group"       => esc_html__( 'Slider settings', 'xstore-core' ),
				"description" => sprintf( esc_html__( 'Duration of transition between slides. Default: 300', 'xstore-core' ) ),
			),
			array(
				"type"       => "checkbox",
				"heading"    => esc_html__( "Slider autoplay", 'xstore-core' ),
				"param_name" => "slider_autoplay",
				"group"      => esc_html__( 'Slider settings', 'xstore-core' ),
				'value'      => array( esc_html__( 'Yes, please', 'xstore-core' ) => 'yes' )
			),
			array(
				"type"       => "checkbox",
				"heading"    => esc_html__( "Stop autoplay on mouseover", 'xstore-core' ),
				"param_name" => "slider_stop_on_hover",
				"group"      => esc_html__( 'Slider settings', 'xstore-core' ),
				'value'      => array( esc_html__( 'Yes, please', 'xstore-core' ) => 'yes' ),
				'dependency' => array(
					'element' => 'slider_autoplay',
					'value'   => 'yes',
				),
			),
			array(
				"type"        => "textfield",
				"heading"     => esc_html__( "Autoplay speed", 'xstore-core' ),
				"param_name"  => "slider_interval",
				"group"       => esc_html__( 'Slider settings', 'xstore-core' ),
				"description" => sprintf( esc_html__( 'Interval between slides. In milliseconds. Default: 1000', 'xstore-core' ) ),
				'dependency'  => array(
					'element' => 'slider_autoplay',
					'value'   => 'yes',
				),
			),
			array(
				"type"       => "checkbox",
				"heading"    => esc_html__( "Slider loop", 'xstore-core' ),
				"param_name" => "slider_loop",
				"group"      => esc_html__( 'Slider settings', 'xstore-core' ),
				'value'      => array( esc_html__( 'Yes, please', 'xstore-core' ) => 'yes' ),
			),
			array(
				'type'       => 'xstore_title_divider',
				'title'      => esc_html__( 'Navigation settings', 'xstore-core' ),
				'group'      => esc_html__( 'Slider settings', 'xstore-core' ),
				'param_name' => 'slider_divider' . $counter ++
			),
			array(
				"type"       => "checkbox",
				"heading"    => esc_html__( "Hide prev/next buttons", 'xstore-core' ),
				"param_name" => "hide_buttons",
				"group"      => esc_html__( 'Slider settings', 'xstore-core' ),
				'value'      => array( esc_html__( 'Yes, please', 'xstore-core' ) => 'yes' )
			),
			array(
				'type'       => 'xstore_button_set',
				'heading'    => esc_html__( 'Hide navigation only for', 'xstore-core' ),
				'param_name' => 'hide_buttons_for',
				'dependency' => array(
					'element' => 'hide_buttons',
					'value'   => 'yes',
				),
				'group'      => esc_html__( 'Slider settings', 'xstore-core' ),
				'value'      => array(
					__( 'Both', 'xstore-core' )    => '',
					__( 'Mobile', 'xstore-core' )  => 'mobile',
					__( 'Desktop', 'xstore-core' ) => 'desktop',
				),
			),
			array(
				'type'       => 'xstore_button_set',
				'heading'    => esc_html__( 'Pagination type', 'xstore-core' ),
				'param_name' => 'pagination_type',
				'group'      => esc_html__( 'Slider settings', 'xstore-core' ),
				'value'      => array(
					__( 'Hide', 'xstore-core' )    => 'hide',
					__( 'Bullets', 'xstore-core' ) => 'bullets',
					__( 'Lines', 'xstore-core' )   => 'lines',
				),
			),
			array(
				'type'       => 'xstore_button_set',
				'heading'    => esc_html__( 'Hide pagination only for', 'xstore-core' ),
				'param_name' => 'hide_fo',
				'dependency' => array(
					'element' => 'pagination_type',
					'value'   => array( 'bullets', 'lines' ),
				),
				'group'      => esc_html__( 'Slider settings', 'xstore-core' ),
				'value'      => array(
					__( 'Unset', 'xstore-core' )   => '',
					__( 'Mobile', 'xstore-core' )  => 'mobile',
					__( 'Desktop', 'xstore-core' ) => 'desktop',
				),
			),
			array(
				'type'       => 'xstore_title_divider',
				'title'      => esc_html__( 'Navigation colors', 'xstore-core' ),
				'group'      => esc_html__( 'Slider settings', 'xstore-core' ),
				'param_name' => 'slider_divider' . $counter ++,
				'dependency' => array(
					'element' => 'pagination_type',
					'value'   => array( 'bullets', 'lines' ),
				),
			),
			array(
				"type"             => "colorpicker",
				"heading"          => __( "Pagination default color", "xstore" ),
				"param_name"       => "default_color",
				'dependency'       => array(
					'element' => 'pagination_type',
					'value'   => array( 'bullets', 'lines' ),
				),
				"group"            => esc_html__( 'Slider settings', 'xstore-core' ),
				"value"            => '#e1e1e1',
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				"type"             => "colorpicker",
				"heading"          => __( "Pagination active color", "xstore" ),
				"param_name"       => "active_color",
				'dependency'       => array(
					'element' => 'pagination_type',
					'value'   => array( 'bullets', 'lines' ),
				),
				"group"            => esc_html__( 'Slider settings', 'xstore-core' ),
				"value"            => '#222',
				'edit_field_class' => 'vc_col-sm-6 vc_column',
			),
			array(
				'type'       => 'xstore_title_divider',
				'title'      => esc_html__( 'Responsive settings', 'xstore-core' ),
				'group'      => esc_html__( 'Slider settings', 'xstore-core' ),
				'subtitle'   => esc_html__( 'Number of slides per view', 'xstore-core' ),
				'param_name' => 'slider_divider' . $counter ++
			),
			array(
				"type"             => "textfield",
				"heading"          => esc_html__( "Large screens", 'xstore-core' ),
				"param_name"       => "large",
				"group"            => esc_html__( 'Slider settings', 'xstore-core' ),
				'edit_field_class' => 'vc_col-md-3 vc_col-xs-6 vc_column',
			),
			array(
				"type"             => "textfield",
				"heading"          => esc_html__( "On notebooks", 'xstore-core' ),
				"param_name"       => "notebook",
				"group"            => esc_html__( 'Slider settings', 'xstore-core' ),
				'edit_field_class' => 'vc_col-md-3 vc_col-xs-6 vc_column',
			),
			array(
				"type"             => "textfield",
				"heading"          => esc_html__( "On tablet portrait", 'xstore-core' ),
				"param_name"       => "tablet_land",
				"group"            => esc_html__( 'Slider settings', 'xstore-core' ),
				'edit_field_class' => 'vc_col-md-3 vc_col-xs-6 vc_column',
			),
			array(
				"type"             => "textfield",
				"heading"          => esc_html__( "On mobile", 'xstore-core' ),
				"param_name"       => "mobile",
				"group"            => esc_html__( 'Slider settings', 'xstore-core' ),
				'edit_field_class' => 'vc_col-md-3 vc_col-xs-6 vc_column',
			),
		);
		
		if ( is_array( $dependency ) ) {
			foreach ( $slider_settings as $setting => $value ) {
				if ( ! isset( $slider_settings[ $setting ]['dependency'] ) ) {
					$slider_settings[ $setting ]['dependency'] = $dependency;
				}
			}
		}
		
		return $slider_settings;
		
	}
	
	public function get_custom_product_template() {
	    if ( self::$product_template ) {
		    return self::$product_template;
	    }
	    if ( function_exists('etheme_get_custom_product_template') ) {
		    self::$product_template = etheme_get_custom_product_template();
		    return self::$product_template;
	    }
	    
		// set shop products custom template
		self::$product_template = get_theme_mod( 'custom_product_template', 'default' );
		return self::$product_template;
	 
	}
	
	public function is_admin() {
	    return is_admin();
	}
}
