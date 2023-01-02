<?php
/**
 * Own built-in compare
 *
 * @package    XStore_Compare.php
 * @since      4.3.8
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

namespace XStoreCore\Modules\WooCommerce;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class XStore_Compare {

    public static $instance = null;

	public static $is_enabled = false;
	public static $page_id = 0;
	public static $show_in_catalog_mode = true;
	public static $inited = false;
	public static $products_ids = [];
	public static $products = [];
	
	public static $templates_path;
    public static $single_product_builder;
	
	public static $key = 'xstore-compare';
	public static $compare_page_id = null;
    public static $compare_page = '';

	public static $settings = array();
	
	/**
	 * Holds product ids stored in cookie
	 */
	const COOKIE_KEY = 'xstore_compare_ids';
	
	/**
	 * Holds user unique key
	 */
	const USER_KEY = 'xstore_compare_u';

    /**
     * Initialize all
     */
	public function init() {

	    if ( !get_theme_mod('xstore_compare', false) ) return;

//		add_action( 'wp_ajax_xstore_add_to_compare', array( $this, 'add_to_compare_action' ) );
//		add_action( 'wp_ajax_nopriv_xstore_add_to_compare', array( $this, 'add_to_compare_action' ) );

        add_action( 'wp_ajax_xstore_update_user_compare', array( $this, 'update_user_compare' ) );
        add_action( 'wp_ajax_nopriv_xstore_update_user_compare', array( $this, 'update_user_compare' ) );

        add_action( 'wp_ajax_xstore_get_user_compare', array( $this, 'get_user_compare' ) );
        add_action( 'wp_ajax_nopriv_xstore_get_user_compare', array( $this, 'get_user_compare' ) );

//        add_action( 'wp_ajax_xstore_compare_page_action', array( $this, 'global_page_actions' ) );
//        add_action( 'wp_ajax_nopriv_xstore_compare_page_action', array( $this, 'global_page_actions' ) );

        add_action( 'wp_ajax_xstore_compare_fragments', array( $this, 'fragments' ) );
        add_action( 'wp_ajax_nopriv_xstore_compare_fragments', array( $this, 'fragments' ) );

        add_action( 'wp_ajax_xstore_get_compare_product_info', array( $this, 'get_compare_product_info' ) );
        add_action( 'wp_ajax_nopriv_xstore_get_compare_product_info', array( $this, 'get_compare_product_info' ) );

        add_action( 'wp_ajax_xstore_empty_compare_page', array( $this, 'get_empty_page_content' ) );
        add_action( 'wp_ajax_nopriv_xstore_empty_compare_page', array( $this, 'get_empty_page_content' ) );

//        add_filter('woocommerce_create_pages', array($this, 'add_create_compare_page'));
//        add_action('woocommerce_system_status_tool_executed', array ($this, 'set_compare_page'), 10, 1);

        // Add a post display state for special WC pages.
        add_filter( 'display_post_states', array( $this, 'add_display_post_states' ), 10, 2 );

        add_filter('woocommerce_account_menu_items', array($this, 'add_link_to_account_menu'), 10, 2);
        add_filter('woocommerce_get_endpoint_url', array($this, 'add_endpoint_link_to_account_menu'), 10, 4);

//		if ( !class_exists('WooCommerce')) return;
		$this->define_constants();
//		$this->define_settings();
		$this->assets();
		if ( !is_admin() ) {
            $this->actions();
        }
    }

    /**
     * Define namespace constants
     */
	public function define_constants() {
		define('XStore_Compare_Version', '1.0');
	}

    /**
     * Define default settings of compare button
     */
	public function define_settings() {
	    self::$settings = array(
            'show_icon' => true,
            'custom_icon' => false,
            'add_icon_class' => 'et-compare',
            'remove_icon_class' => 'et-compare',
            'add_text' => get_theme_mod('xstore_compare_label_add_to_compare', esc_html__('Add to Compare', 'xstore-core')),
            'remove_text' => get_theme_mod('xstore_compare_label_browse_compare', esc_html__('Remove', 'xstore-core')),
//            'animated_hearts' => get_theme_mod('xstore_compare_animated_hearts', true),
            'redirect_on_remove' => false,
            'is_single' => false,
            'is_spb' => false, // single product builder
            'only_icon' => true,
            'has_tooltip' => false
        );
	    switch (get_theme_mod('xstore_compare_icon', 'type1')) {
//            case 'type2':
//                self::$settings['add_icon_class'] = 'et-star';
//                self::$settings['remove_icon_class'] = 'et-star-o';
//                break;
            case 'none':
                self::$settings['show_icon'] = false;
                self::$settings['only_icon'] = false;
                break;
            case 'custom':
                $icon_custom = get_theme_mod( 'xstore_compare_icon_custom_svg', '' );
                $icon_custom = isset( $icon_custom['id'] ) ? $icon_custom['id'] : '';
                if ( $icon_custom != '' ) {
                    self::$settings['custom_icon'] = etheme_get_svg_icon( $icon_custom );
                }
                break;
        }
	    self::$compare_page_id = absint( get_theme_mod('xstore_compare_page', '') );
	    if ( !self::$compare_page_id ) {
            $compare_page_ghost_id = absint(get_option( 'woocommerce_myaccount_page_id' ));
            if ( $compare_page_ghost_id )
                self::$compare_page = add_query_arg('et-compare-page', '', get_permalink($compare_page_ghost_id));
            else
                self::$compare_page = home_url();
        }
	    else {
            self::$compare_page = get_permalink(self::$compare_page_id);
        }
    }

    /**
     * Enqueue style/scripts action
     */
	public function assets() {
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts'), 30 );
	}

    /**
     * Register and enqueue scripts and styles
     */
    public function enqueue_scripts(){
        // Enqueue the script.
        wp_register_script(
            self::$key,
            ET_CORE_URL . 'packages/'.self::$key.'/assets/js/script.min.js',
            array(
                'jquery',
                'etheme',
                'js-cookie'
            ),
            XStore_Compare_Version,
            false
        );

        wp_localize_script(
            self::$key,
            str_replace('-', '_', self::$key).'_params',
            [
                'ajaxurl' => admin_url('admin-ajax.php'),
                'confirmQuestion' => esc_html__('Are you sure?', 'xstore-core'),
                'no_active_checkbox' => esc_html__('Please, choose any product by clicking checkbox', 'xstore-core'),
                'no_products_available' => esc_html__('Sorry, there are no products available for this action', 'xstore-core'),
                'is_loggedin' => is_user_logged_in(),
                'compare_id' => $this->get_cookie_key(),
                'compare_page_url' => self::$compare_page,
//                'animated_hearts' => self::$settings['animated_hearts'],
                'view_compare' => esc_html__('View compare', 'xstore-core'),
                'days_cache' => $this->get_days_cache(),
                'notify_type' => get_theme_mod('xstore_compare_notify_type', 'alert'),
                'placeholder_image' => function_exists('wc_placeholder_img') ? wc_placeholder_img() : ''
            ]
        );

        wp_enqueue_script( self::$key );

        wp_register_style(
            self::$key.'-page',
            ET_CORE_URL . 'packages/'.self::$key.'/assets/css/compare-page'.(defined('ETHEME_MIN_CSS') ? ETHEME_MIN_CSS : '').'.css',
            false,
            XStore_Compare_Version,
            'all' );

        $compare_page_css = '@media only screen and (max-width: '.(get_theme_mod('site_width', 1170) + 50 + 10).'px) {';
        $compare_page_css .= '.xstore-compare-table-arrow[data-side=left]{ '.(get_query_var('et_is-rtl', false) ? 'right' : 'left').': -5px; }';
        $compare_page_css .= '.xstore-compare-table-arrow[data-side=right]{ '.(get_query_var('et_is-rtl', false) ? 'left' : 'right').': -5px; }';
        $compare_page_css .= '}';

        if ( self::$settings['show_icon'] ) {
            $compare_icon_deps_styles = '.woocommerce-compare .page-heading .title:before, .empty-compare-block:before';
            if ( self::$settings['custom_icon'] ) {
                $icon_custom = get_theme_mod( 'xstore_compare_icon_custom_svg', '' );
                $icon_custom = isset( $icon_custom['id'] ) ? $icon_custom['id'] : '';
                if ( $icon_custom ) {
                    $compare_icon_code = 'content: \'\'; color: transparent; background: center no-repeat url('.wp_get_attachment_url($icon_custom).'); background-size: contain;';
                    $compare_icon_code .= 'display: inline-block;';
                    $compare_icon_code .= 'width: 1em; height: 1em;';

                    $compare_page_css .= $compare_icon_deps_styles . '{'.$compare_icon_code.'}';
                }
            }
        }

        if ( $compare_page_css )
            wp_add_inline_style(self::$key.'-page', $compare_page_css);

    }

    /**
     * Getter of products count already added in compare
     * @return int
     */
	public function get_products_count() {
	    return count(self::$products_ids);
    }

    /**
     * Get html of compare quantity for header builder compare/mobile panel compare elements
     * @param bool $updated_count
     */
    function header_compare_quantity($updated_count = false) {

        if ( $updated_count ) {
            $count = $updated_count;
        }
        else {
            $count = get_query_var(self::$key.'_products_count', false);

            if (!$count) {
                $count = $this->get_products_count();
                set_query_var(self::$key.'_products_count', $count);
            }
        } ?>
        <span class="et-compare-quantity et-quantity count-<?php echo $count; ?>">
          <?php echo wp_specialchars_decode( $count ); ?>
        </span>
        <?php
    }

    /**
     * Header mini-compare content
     */
    public function header_mini_compare() {

        $this->header_mini_compare_products(); ?>

        <div class="woocommerce-mini-cart__footer-wrapper">
            <div class="product_list-popup-footer-wrapper" <?php if ( $this->get_products_count() < 1 ) : ?>style="display: none"<?php endif; ?>>
                <p class="buttons mini-cart-buttons">
                    <a href="<?php echo esc_url( self::$compare_page ); ?>"
                       class="button btn-view-compare wc-forward"><?php _e( 'View Compare', 'xstore-core' ); ?></a>
                </p>
            </div>
        </div>

        <?php
    }

    /**
     * Header mini-compare products
     * @param bool $updated_products
     */
    public function header_mini_compare_products($updated_products = false) {

        $products = $updated_products ? $updated_products : self::$products;
        $products = array_reverse( $products );

        $limit = get_theme_mod( 'mini-compare-items-count', 3 );
        $limit = is_numeric( $limit ) ? $limit : 3;

        $add_remove_ajax = true;
        $compare_class  = 'et_b_compare-dropdown product_list_widget cart_list';

        ?>
        <div class="<?php esc_attr_e( $compare_class ); ?>">
            <?php if ( ! empty( $products ) ) : ?>

                <?php $is_yith_wcbm_frontend = class_exists('YITH_WCBM_Frontend'); ?>

                <?php
                if ($is_yith_wcbm_frontend) {
                    remove_filter( 'woocommerce_product_get_image', array( \YITH_WCBM_Frontend::get_instance(), 'show_badge_on_product' ), 999 );
                }
                ?>

                <ul class="cart-widget-products">
                    <?php
                    $i = 0;
                    $trash_bin = defined( 'ETHEME_BASE_URI' ) ? ETHEME_BASE_URI . 'theme/assets/images/trash-bin.gif' : '';
                    $sku_enabled = in_array('mini-compare', (array)get_theme_mod('product_sku_locations', array('cart', 'popup_added_to_cart', 'mini-cart'))) && wc_product_sku_enabled();
                    foreach ( $products as $product_info ) {
                        $i++;
                        if ( $i > $limit ) {
                            break;
                        }

                        $post_object = get_post( $product_info['id'] );
                        setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
                        $_product = wc_get_product($product_info['id']);

                        if ( ! $_product ) {
                            continue;
                        }

                        $product_name = $_product->get_title();
                        $thumbnail    = $_product->get_image();
                        ?>
                        <li class="woocommerce-mini-compare-item">
                            <?php if ( ! $_product->is_visible() ) : ?>
                                <a class="product-mini-image">
                                    <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . '&nbsp;'; ?>
                                </a>
                            <?php else : ?>
                                <a href="<?php echo esc_url( $_product->get_permalink() ); ?>"
                                   class="product-mini-image">
                                    <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ) . '&nbsp;'; ?>
                                </a>
                            <?php endif; ?>

                            <div class="product-item-right" data-row-id="<?php esc_attr_e( $product_info['id'] ); ?>">

                                <h4 class="product-title">
                                    <a href="<?php echo esc_url( $_product->get_permalink() ); ?>"><?php echo wp_specialchars_decode( $product_name ); ?></a>
                                </h4>

                                <?php if ( $add_remove_ajax ) : ?>
                                    <a href="<?php echo add_query_arg( 'remove_from_compare', $product_info['id'], esc_url( self::$compare_page ) ); ?>"
                                       data-id="<?php echo esc_attr($product_info['id']); ?>"
                                       class="remove xstore-minicompare-remove remove_from_compare"
                                       title="<?php echo esc_attr__( 'Remove this product', 'xstore-core' ); ?>"><i
                                                class="et-icon et-delete et-remove-type1"></i><i
                                                class="et-trash-wrap et-remove-type2"><img
                                                    src="<?php echo $trash_bin; ?>"
                                                    alt="<?php echo esc_attr__( 'Remove this product', 'xstore-core' ); ?>"></i></a>
                                <?php endif; ?>

                                <div class="descr-box">
                                    <?php
                                    if ( $sku_enabled && $_product->get_sku() ) : ?>
                                        <div class="product_meta">
                                            <span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'xstore-core' ); ?>
                                                <span class="sku"><?php echo esc_html( ( $sku = $_product->get_sku() ) ? $sku : esc_html__( 'N/A', 'xstore-core' ) ); ?></span>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php

                if ($is_yith_wcbm_frontend) {
                    add_filter( 'woocommerce_product_get_image', array( \YITH_WCBM_Frontend::get_instance(), 'show_badge_on_product' ), 999 );
                }

                wp_reset_postdata();
                ?>
            <?php else : ?>
                <p class="empty"><?php esc_html_e( 'No products in the compare.', 'xstore-core' ); ?></p>
            <?php endif; ?>
        </div><!-- end product list -->
    <?php }

    /**
     * Fragments for ajax-refresh on adding/removing product in compare
     */
    public function fragments() {

        $data = array(
            'fragments' => array()
        );

        if ( isset($_POST['products_count'])) {
            ob_start();
            $this->header_compare_quantity($_POST['products_count']);
            $data['fragments']['span.et-compare-quantity'] = ob_get_clean();
        }

        ob_start();
            if ( isset($_POST['products'])) {
                $this->header_mini_compare_products(
                    array_map(
                        function ($_product) {
                            return (array)json_decode(stripcslashes($_product));
                        },
                        $_POST['products']));
            }
            else {
                $this->header_mini_compare_products(array());
            }
        $data['fragments']['.et_b_compare-dropdown'] = ob_get_clean();

        wp_send_json( $data );
    }

    /**
     * Getter of product info by product_id param set from ajax params in js
     */
    public function get_compare_product_info() {
	    if ( !isset($_POST['product_id']) || empty($_POST['product_id']) ) die();
        add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_true' );
        $post_object = get_post($_POST['product_id']);
        setup_postdata($GLOBALS['post'] =& $post_object); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
	    $_product = wc_get_product($_POST['product_id']);
	    if ( !$_product ) {
	        wp_reset_postdata();
	        die();
	    }

        $image = wp_get_attachment_image($_product->get_image_id(), 'woocommerce_thumbnail');
        $product_link = get_permalink($_POST['product_id']);
        $product_name = $_product->get_name();

        remove_filter( 'woocommerce_product_variation_title_include_attributes', '__return_true' );

        wp_reset_postdata();
        wp_send_json( array(
            'product_link' => $product_link,
            'product_image' => $image ? $image : wc_placeholder_img(),
            'product_title' => sprintf(__('<a href="%s">%s</a> has been added to the compare', 'xstore-core'), $product_link, $product_name ),
        ) );
    }

    /**
     * Getter of empty page. Used for ajax setters after all products were removed on compare page.
     */
    public function get_empty_page_content() {
        ob_start();
        $this->empty_page_template();
        $ob_clean = ob_get_clean();
        wp_send_json( array('page_content' => $ob_clean) );
    }

    /**
     * Create compare page based on woocommerce tools.
     * @param $pages
     * @return mixed
     */
    public function add_create_compare_page($pages) {
	    $compare_shortcode = 'xstore_compare_page';
        $pages['xstore_compare'] = array(
            'name'    => _x( 'xstore-compare', 'Page slug', 'xstore-core' ),
            'title'   => _x( 'XStore Compare', 'Page title', 'xstore-core' ),
            'content' => '<!-- wp:shortcode -->[' . $compare_shortcode . ']<!-- /wp:shortcode -->',
        );
	    return $pages;
    }

    /**
     * Set theme mod if not set yet of compare id once we are in woocommerce tools and install-page action.
     * @param $tool
     */
//    public function set_compare_page($tool) {
//	    if ( $tool['id'] == 'install_pages') {
//            $compare_page_id = get_option('woocommerce_xstore_compare_page_id');
//            if ( $compare_page_id && get_theme_mod('xstore_compare_page', '') == '' ) {
//                set_theme_mod('xstore_compare_page', $compare_page_id);
//            }
//        }
//    }

    /**
     * Add a post display state for special compare page in the page list table.
     *
     * @param array   $post_states An array of post display states.
     * @param WP_Post $post        The current post object.
     */
    public function add_display_post_states($post_states, $post) {
        if ( (int)get_theme_mod('xstore_compare_page', '') === $post->ID ) {
            $post_states['xstore_page_for_compare'] = __( 'Built-in Compare page', 'xstore-core' );
        }

        return $post_states;
    }

    /**
     * Add compare link to account menu.
     * @param $items
     * @param $endpoints
     * @return array|false|int|string
     */
    public function add_link_to_account_menu($items, $endpoints) {
        $add_anywhere = true;
        if ( array_key_exists('customer-logout', $items)) {
            $logout_position = array_search('customer-logout', array_keys($items));
            if ( $logout_position > 1 ) {
                $items = array_slice( $items, 0, $logout_position, true ) +
                    array( 'xstore-compare' => esc_html__( 'Compare', 'xstore-core' ) ) +
                    array_slice( $items, $logout_position, count( $items ) - $logout_position, true );
                $add_anywhere = false;
            }
        }

        if ( $add_anywhere )
            $items['xstore-compare'] = __( 'Compare', 'xstore-core' );

        return $items;
    }

    /**
     * Filter xstore-compare link in account menu items.
     * @param $url
     * @param $endpoint
     * @param $value
     * @param $permalink
     * @return bool|false|string|void
     */
    public function add_endpoint_link_to_account_menu($url, $endpoint, $value, $permalink) {
        if ( $endpoint == 'xstore-compare')
            return self::$compare_page;
        return $url;
    }

    /**
     * Main actions of compare functionality.
     */
	public function actions() {

        self::$templates_path = plugin_dir_path( __FILE__ ) . '/templates/';
        self::$single_product_builder = get_option( 'etheme_single_product_builder', false );
//        $this->reset_products();
        add_action( 'wp_login', array($this, 'update_ids_after_login'), 10, 2);
        add_action('wp', array($this, 'check_compare_items'));
        add_action('wp', function () {

            if ( !self::$inited ) {
                $this->init_added_products();
            }

            $this->define_settings();

            add_shortcode('xstore_compare_page', array($this, 'page_template'));
//            add_shortcode('xstore_compare_button', array($this, 'print_button'));
//            delete_user_meta(get_current_user_id(), $this->get_cookie_key());
//            $this->reset_products();
//            $this->test_save_user_compare();

            $single_product_action = false;
            if ( !self::$single_product_builder ) {
                switch (get_theme_mod('xstore_compare_single_product_position', 'after_cart_form')) {
                    case 'before_cart_form':
                        $single_product_action = 'woocommerce_before_add_to_cart_form';
                        $single_product_priority = 5;
                        break;
                    case 'after_cart_form':
                        $single_product_action = 'woocommerce_after_add_to_cart_form';
                        $single_product_priority = 15;
                        break;
                    case 'after_atc':
                        $single_product_action = 'woocommerce_after_add_to_cart_button';
                        $single_product_priority = 7; // 10 in for buy now button
                        break;
                    case 'on_image':
                        $single_product_action = 'woocommerce_before_single_product_summary';
                        $single_product_priority = 5;
                        break;
                }
            }
            if ($single_product_action)
                add_action($single_product_action, array($this, 'print_button_single'), $single_product_priority);

            // before etheme_sticky_add_to_cart()
            add_action( 'etheme_sticky_add_to_cart_before', function () {
                add_filter('xstore_compare_single_product_settings', array($this, 'compare_btn_only_icon'), 999, 2);
            }, 1 );
            add_action( 'etheme_sticky_add_to_cart_after', function () {
                remove_filter('xstore_compare_single_product_settings', array($this, 'compare_btn_only_icon'), 999, 2);
            }, 10 );

//            add_filter('etheme_compare_btn_output', array($this, 'old_compare_btn_filter'), 10, 2);
            // Add link or button in the products list.
//            if ( 'yes' === get_option( 'yith_woocompare_compare_button_in_product_page', 'yes' ) ) {
//                add_action( 'woocommerce_single_product_summary', array( $this, 'old_compare_btn_filter' ), 35 );
//            }
//            if ( 'yes' === get_option( 'yith_woocompare_compare_button_in_products_list', 'no' ) ) {
                add_action( 'woocommerce_after_shop_loop_item', array( $this, 'old_compare_btn_filter' ), 20 );
//            }

            if ( $this->is_compare_page() ) {
                // prevent load account page styles
                add_filter('etheme_enqueue_account_page_style', '__return_false');

                wp_enqueue_style( self::$key . '-page' );
                add_filter('body_class', array($this, 'add_body_classes'));

                add_filter('theme_mod_ajax_added_product_notify_type', function ($old_value) {
                    return in_array($old_value, array('mini_cart', 'popup')) ? 'alert' : $old_value;
                });
            }
        });

        add_action( 'wp', array($this, 'ghost_compare_page'), 7 );

        add_action( 'wp_loaded', array( $this, 'no_script_add_to_compare' ), 20 );
        add_action( 'wp_loaded', array( $this, 'no_script_remove_compare_product' ), 20 );

//		if ( wp_doing_ajax() ) {
//            $this->init_added_products();
//            $this->define_settings();
//            add_filter('etheme_compare_btn_output', array($this, 'old_compare_btn_filter'), 10, 2);
//        }
		
//		$cookie_key = $this->get_cookie_key();
//		var_dump( $_COOKIE);
//		if ( isset( $_COOKIE[$cookie_key] ) ) { // @codingStandardsIgnoreLine.
//		    $products = explode( '|', $_COOKIE[$cookie_key]);
//		    $product_ids = array();
//			foreach ( $products as $key => $value ) {
//                $products[$key] = wp_unslash( (array)json_decode( $value) );
//				$product_ids[] = $products[$key]['id'];
//		    }
//			var_dump( $products);
//			var_dump( $product_ids);
//		    var_dump( json_decode($_COOKIE[$cookie_key]) );
//		    var_dump( wp_unslash( json_decode($_COOKIE[$cookie_key]) ));
//			$products = wp_parse_id_list( (array) explode( '|', wp_unslash( json_decode($_COOKIE[$cookie_key]) ) ) ); // @codingStandardsIgnoreLine.
//            var_dump( $products);
//		}
	}

    /**
     * Filter for body classes.
     * @param $classes
     * @return mixed
     */
	public function add_body_classes($classes) {
        $classes[] = 'woocommerce-compare';
        $classes[] = 'xstore-compare-page';
        $classes[] = 'xstore-compare-owner';
	    return $classes;
    }

    public function init_added_products() {
        $added_products = $this->get_products();
        self::$products_ids = $added_products['ids'];
        self::$products = $added_products['products'];
        self::$inited = true;
    }
    /**
     * Checker if it is compare page or has [xstore_compare_page] shortcode on this page.
     * @return bool
     */
	public function is_compare_page() {
        return ( self::$compare_page_id && is_page( self::$compare_page_id ) ) || (isset($_GET['et-compare-page']) && is_account_page()) || (class_exists('WooCommerce') && wc_post_content_has_shortcode( 'xstore_compare_page' ));
	}

    public function test_save_user_compare() {
        $products = $this->get_products();
        $cookie_key = $this->get_cookie_key();
        $saved_products = get_user_meta(get_current_user_id(), $cookie_key, true);
//        write_log('$saved_products');
//        write_log($saved_products);
        if ( !$saved_products ) {
            $saved_products = [];
        }
        else {
            $saved_products_local = explode('|', $saved_products);
            $saved_products = [];
            foreach ($saved_products_local as $local_product_info ) {
                $product_info = (array)json_decode($local_product_info);
                $saved_products[$product_info['id']] = $product_info;
            }

        }

        $merge = array_merge($saved_products, $products['products']);
        $filtered = array();
        $filtered_json = array();
        foreach ($merge as $item_key => $item_value) {
            $filtered[$item_value['id']] = $item_value;
            $filtered_json[$item_value['id']] = json_encode($item_value);
        }

//        write_log(array_values($filtered));
//        write_log(implode('|', array_values($filtered)));
//        write_log(json_encode(implode('|', array_values($filtered))));
        $ready_products = array_values($filtered_json);

        self::$products_ids = array_keys($filtered);

        self::$products = array_values($filtered);

    }

    /**
     * Updates user compare with the items set from unlogged state and merged with the ones set before.
     * @param $user_login
     * @param $user
     */
	public function update_ids_after_login( $user_login, $user){

        $products = $this->get_products();
        $cookie_key = $this->get_cookie_key();
        $saved_products = get_user_meta($user->ID, $cookie_key, true);
//        write_log('$saved_products');
//        write_log($saved_products);
        if ( !$saved_products ) {
            $saved_products = [];
        }
        else {
            $saved_products_local = explode('|', $saved_products);
            $saved_products = [];
            foreach ($saved_products_local as $local_product_info ) {
                $product_info = (array)json_decode($local_product_info);
                $saved_products[$product_info['id']] = $product_info;
            }

        }

        $merge = array_merge($saved_products, $products['products']);
        $filtered = array();
        $filtered_json = array();
        foreach ($merge as $item_key => $item_value) {
            $filtered[$item_value['id']] = $item_value;
            $filtered_json[$item_value['id']] = json_encode($item_value);
        }

//        write_log(array_values($filtered));
//        write_log(implode('|', array_values($filtered)));
//        write_log(json_encode(implode('|', array_values($filtered))));
        $ready_products = array_values($filtered_json);
//        write_log($ready_products);
        update_user_meta($user->ID, $cookie_key, implode('|', $ready_products) );

        unset( $_COOKIE[$cookie_key] );
//            var_dump(implode('|', $products_to_leave));
        setcookie($cookie_key, implode('|', $ready_products), time() + ($this->get_days_cache() * WEEK_IN_SECONDS));
        $_COOKIE[ $cookie_key ] = implode('|', $ready_products);
        self::$products_ids = array_keys($filtered);
        self::$products = array_values($filtered);
    }

    /**
     * Update compare products set for specific user id.
     */
	public function update_user_compare() {
        $user_id = get_current_user_id();
//        $products_to_update = isset($products) && $products ? $products : $_POST['products'];

//        write_log($user_id);
//        write_log($_POST['products']);
//        write_log(stripcslashes($_POST['products']) );
        update_user_meta($user_id, $this->get_cookie_key(), (isset($_POST['products']) ? $_POST['products'] : '') );

        echo wp_json_encode(array('success' => true));
        exit;
    }

    /**
     * Getter of compare products from specific user.
     */
    public function get_user_compare() {
//	    write_log($this->get_products($_POST['cookie_key']));
        echo wp_json_encode( array('success' => true, 'products' => $this->get_products($_POST['cookie_key'])) );

        exit;
    }

    /**
     * Getter of created user unique key.
     * @return mixed|string
     */
    public function get_user_key() {

        $user_id = get_current_user_id();
        $cookie = $this->get_cookie_key();

        if ( ! ($user_key = get_user_meta( $user_id, self::USER_KEY, true )) ) {

            $user_key = strtoupper( substr( base_convert( md5( self::USER_KEY . $user_id ), 16, 32), 0, 12) );

            update_user_meta( $user_id, self::USER_KEY, $user_key );
        }

        return $user_key;
    }

//	public function global_page_actions() {
//        $products = $_POST['products'];
//        if ( $products ) {
//            foreach ($products as $value) {
//                $product_info = (array)json_decode($value);
//                $products_ids[] = $product_info['id'];
//                $products[] = $product_info;
//            }
//        }
//        echo wp_json_encode( array('success' => true) );
//
//        exit;
//    }

//	public function add_to_compare_action() {
//
//		check_ajax_referer( 'xstore-compare-add', 'security' );
//
//	    $data = array(
//	        'group' => '',
//            'product_id' => isset($_POST['product_id']) ? $_POST['product_id'] : null,
//            'security' => isset($_POST['security']) ? $_POST['security'] : ''
//        );
//	    if ( !$data['product_id'] ) return false;
//
//	    $group = '';
//	    if (isset($_POST['group'])) {
//	        $group = $_POST['group'];
//	    }
//
////	    write_log( class_exists( 'WooCommerce'));
////	    write_log( function_exists( 'wc_setcookie'));
//
////		write_log( $_COOKIE[ $this->get_cookie_key() ]);
//
//	    if ( !$this->is_product_in_compare($data['product_id'])) {
//		    $all_products = $this->get_products();
//		    $all_products[$data['product_id']] = array(
//		        'id' => $data['product_id'],
//		        'time' => time()
//            );
//
////		    write_log( time());
////		    write_log( WEEK_IN_SECONDS);
////		    write_log( time() + intval(WEEK_IN_SECONDS));
////		    setcookie( $this->get_cookie_key(), json_encode( $all_products ), (time() + intval(WEEK_IN_SECONDS)) );
////		    write_log( $_COOKIE[ $this->get_cookie_key() ] );
//
//	    }
//		echo wp_json_encode( array('success' => true) );
//
//		exit;
//    }

    /**
     * Getter of cookie key
     * @return string
     */
    public static function get_cookie_key(){
        return self::COOKIE_KEY . '_' . (is_multisite() ? get_current_blog_id() : 0);
    }

    public static function get_days_cache() {
        $cache_days = 7;
        switch (get_theme_mod('xstore_compare_cache_time', 'week')) {
            case 'week':
                $cache_days = 7;
                break;
            case 'month':
                $cache_days = 31;
                break;
            case '3months':
                $cache_days = 31*3;
                break;
            case 'year':
                $cache_days = 365;
                break;
        }
	    return $cache_days;
    }

    /**
     * Reset of compare products
     */
    public function reset_products() {
        $cookie_key = $this->get_cookie_key();
        unset( $_COOKIE[$cookie_key] );
        setcookie($cookie_key, null, 0);
    }

    /**
     * Getter of compare products
     * @param null $cookie_key
     * @param null $user_id
     * @return array[]
     */
    public function get_products($cookie_key = null, $user_id = null) {
	    $products = [];
	    $products_ids = [];

        $cached_products = '';
        $user_cached_products = false;

	    $cookie_key = $cookie_key ? $cookie_key : $this->get_cookie_key();
//	    var_dump($cookie_key);

	    // if user is logged in take products info from usermeta
//        var_dump(is_user_logged_in());
        if ( $user_id ) {
            $cached_products = get_user_meta($user_id, $cookie_key, true );
            $user_cached_products = true;
        }
        elseif ( is_user_logged_in() ) {
            $cached_products = get_user_meta(get_current_user_id(), $cookie_key, true );
            $user_cached_products = true;
        }
//        write_log($cached_products);
//        var_dump(get_current_user_id());

//        var_dump($cached_products);

        // if user is not logged in or does not have any products set to his usermeta take it from cookies
	    if ( !$user_cached_products && isset( $_COOKIE[$cookie_key] ) && !empty( $_COOKIE[$cookie_key]) ) { // @codingStandardsIgnoreLine.
		    $cached_products = $_COOKIE[$cookie_key];
	    }

//	    write_log($cached_products);

	    if ( $cached_products != '' && $cached_products = explode( '|', $cached_products) ) {
//	        write_log($cached_products);
            foreach ($cached_products as $value) {
//                write_log($value);
                $product_info = (array)json_decode(stripcslashes($value));
//                write_log($product_info);
                $products_ids[] = $product_info['id'];
                $products[] = $product_info;
            }
        }
	
	    return ['ids' => $products_ids, 'products' => $products];
    }

    /**
     * Checker if product id is in the list of already added products
     * @param $product_id
     * @return bool
     */
	public function is_product_in_compare($product_id) {
	    return in_array($product_id, self::$products_ids);
    }

    public function old_compare_btn_filter() {
//        ob_start();
//        if ( isset($args['class']) ) {
//            $args['class'] = (array)$args['class'];
//        }
        $args = array();
        $args['only_icon'] = false;
        $args['has_tooltip'] = false;
        $args = apply_filters('xstore_compare_product_settings', $args);
        $this->print_button(null, $args);
//        return ob_get_clean();
    }

    public function compare_btn_required_text($args) {
        $args['only_icon'] = false;
        $args['has_tooltip'] = false;
        return $args;
    }

    public function compare_btn_only_icon($args) {
        $args['only_icon'] = true;
        $args['has_tooltip'] = false;
        return $args;
    }

    public function old_compare_btn_filter_quick_view($args=array()) {
        if ( isset($args['class']) ) {
            $args['class'] = (array)$args['class'];
        }
        $args['is_single'] = true;
        $args['has_tooltip'] = false;
        $args['only_icon'] = false;
//        $args['redirect_on_remove'] = get_theme_mod('product_compare_redirect_on_remove', false);
        $args['add_text'] = get_theme_mod('product_compare_label_add_to_compare', esc_html__('Add to compare', 'xstore-core'));
        $args['remove_text'] = get_theme_mod('product_compare_label_browse_compare', esc_html__('Delete from compare', 'xstore-core'));
        $this->print_button(null, $args);

    }
    /**
     * Print single product compare button. Based on origin print_button() but with custom options set for button.
     * @param null $productId
     * @param array $custom_settings
     */
    public function print_button_single($productId = null, $custom_settings = array()) {

        $custom_settings['is_single'] = true;
        $custom_settings['has_tooltip'] = get_theme_mod('product_compare_tooltip', false);
        $custom_settings['redirect_on_remove'] = get_theme_mod('product_compare_redirect_on_remove', false);
        $custom_settings['add_text'] = get_theme_mod('product_compare_label_add_to_compare', esc_html__('Add to compare', 'xstore-core'));
        $custom_settings['remove_text'] = get_theme_mod('product_compare_label_browse_compare', esc_html__('Delete from compare', 'xstore-core'));
        $custom_settings['only_icon'] = get_theme_mod('product_compare_only_icon', false);
//        $custom_settings['class'] = array('single-compare');
        $custom_settings = apply_filters('xstore_compare_single_product_settings', $custom_settings);

	    $this->print_button($productId, $custom_settings);
    }

    /**
     * Print compare button
     * @param null $productId
     * @param array $custom_settings
     */
	public function print_button($productId = null, $custom_settings = array()) {
		global $product;

		// if it is doing_ajax and settings are not defined then init it again
//		if ( count(self::$settings) < 1) {
        if ( wp_doing_ajax() ) {
            $this->init_added_products();
            $this->define_settings();
        }
		$settings = wp_parse_args( $custom_settings, self::$settings );

        $productId = $productId ? $productId : $product->get_ID();
		$add_action = !$this->is_product_in_compare($productId);
		
		$attributes = array(
			'class' => array(
				self::$key,
			),
			'data-action'=> $add_action?'add':'remove',
            'data-id' => $productId,
            'data-settings'=> array(),
		);

		if ( $settings['custom_icon'] ) {
            $attributes['data-settings']['iconAdd'] = false;
            $attributes['data-settings']['iconRemove'] = false;
        }
		else {
            if (!empty($settings['add_icon_class'])) {
                $attributes['data-settings']['iconAdd'] = $settings['add_icon_class'];
            }

            if (!empty($settings['remove_icon_class'])) {
                $attributes['data-settings']['iconRemove'] = $settings['remove_icon_class'];
            }
        }

        if ( !empty($settings['add_text']) ) {
            $attributes['data-settings']['addText'] = $settings['add_text'];
        }

        if ( !empty($settings['remove_text']) ) {
            $attributes['data-settings']['removeText'] = $settings['remove_text'];
        }

		if ( $settings['has_tooltip'] ) {
		    $attributes['class'][] = 'mtips';
            $attributes['class'][] = 'mtips-top';
        }

		if ( isset($settings['class']) ) {
            $attributes['class'] = array_merge($attributes['class'], (array)$settings['class']);
        }
        if ( $settings['is_single'] ) {
            $attributes['class'][] = self::$key . '-single';
            $attributes['class'][] = 'pos-relative';
        }

        if ( $settings['only_icon'] ) {
            $attributes['class'][] = 'xstore-compare-icon';
        }

//        if ( $add_action && $settings['animated_hearts'] ) {
//            $attributes['class'][] = 'xstore-compare-has-animation';
//        }

        if ( $settings['redirect_on_remove'] ) {
            $attributes['class'][] = 'xstore-compare-redirect';
            if ( !$add_action )
                $attributes['class'][] = 'xstore-compare-redirect-ready';
        }

        $attributes['data-settings'] = json_encode($attributes['data-settings']);
        $attributes['class'] = implode(' ', array_unique($attributes['class']));
		
		$attributes_rendered = array();
		foreach ($attributes as $attribute_key => $attribute_value) {
			$attributes_rendered[] = $attribute_key."='".$attribute_value."'";
		}

        $href = add_query_arg(($add_action?'add_to_compare':'remove_compare'), $productId, self::$compare_page);
		if ( $settings['is_single']) echo '<div class="single-compare">';
		?>
		<a href="<?php echo esc_url($href); ?>" <?php echo implode(' ', $attributes_rendered); ?>>
            <?php if ( $settings['show_icon'] || $settings['only_icon']) :

                if ($settings['custom_icon']) { ?>
                    <span class="et-icon"><?php echo $settings['custom_icon']; ?></span>
                <?php }

                else if ( ($add_action && !empty($settings['add_icon_class'])) || (!$add_action && !empty($settings['remove_icon_class'])) ) { ?>
                    <span class="et-icon <?php echo $add_action ? $settings['add_icon_class'] : $settings['remove_icon_class']; ?>"></span>
                <?php }

            endif;

            if ( !$settings['only_icon'] ) {
                if ($add_action) :
                    echo '<span class="button-text et-element-label">' . $settings['add_text'] . '</span>';
                else:
                    echo '<span class="button-text et-element-label">' . $settings['remove_text'] . '</span>';
                endif;
            }

            if ( $settings['has_tooltip'] ) { ?>
                <span class="mt-mes"><?php echo $add_action ? $settings['add_text'] : $settings['remove_text']; ?></span>
            <?php } ?>
        </a>
		<?php
        if ( $settings['is_single']) echo '</div>';
	}

    /**
     * Check all compare items for errors.
     */
    public function check_compare_items() {
        if ( is_admin() ) return;
        $return = true;
        $result = $this->check_compare_item_validity();
        $woo_exists = function_exists('wc_add_notice');
        if ( count($result['errors']) ) {
            foreach ($result['errors'] as $error) {
                if (is_wp_error($error) && $woo_exists) {
                    wc_add_notice($error->get_error_message(), 'error');
                    $return = false;
                }
            }
        }

//        $result = $this->check_cart_item_stock();
//
//        if ( is_wp_error( $result ) ) {
//            wc_add_notice( $result->get_error_message(), 'error' );
//            $return = false;
//        }

        return $return;

    }

    /**
     * Looks through compare items and checks the posts are not trashed or deleted.
     *
     * @return bool|WP_Error
     */
    public function check_compare_item_validity() {
        $return = [
            'success' => true,
            'errors' => []
        ];
        $products_ids_to_reset = [];

        foreach ( self::$products as $product_info) {
            $post_object = get_post( $product_info['id'] );
            setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
            $product = wc_get_product($product_info['id']);
            if ( ! $product || ! $product->exists() || 'trash' === $product->get_status() ) {
                $return['errors'][] = new \WP_Error( 'invalid', sprintf( __( 'Sorry, "%s" is no longer available and was removed from your compare.', 'xstore-core' ), $product->get_name() ) );
                $products_ids_to_reset[] = $product_info['id'];
            }
            wp_reset_postdata();
        }
        $return['success'] = count($return['errors']) < 1;

//        echo '$products_ids_to_reset + ';

//        var_dump($products_ids_to_reset);

        if ( count($products_ids_to_reset) > 0) {
            $products_to_leave = [];
            $products_filtered = array_filter(self::$products, function ($val) use ($products_ids_to_reset) {
                return !in_array($val['id'], $products_ids_to_reset);
            });

//            echo '$products_filtered + ';
//            var_dump($products_filtered);

            foreach ($products_filtered as $product_info) {
                $products_to_leave[] = json_encode($product_info);
            }
            $cookie_key = $this->get_cookie_key();
            unset( $_COOKIE[$cookie_key] );
//            var_dump(implode('|', $products_to_leave));
            setcookie($cookie_key, implode('|', $products_to_leave), time() + ($this->get_days_cache() * WEEK_IN_SECONDS));
            $_COOKIE[ $cookie_key ] = implode('|', $products_to_leave);
            self::$products_ids = array_diff(self::$products_ids, $products_ids_to_reset);
            self::$products = $products_filtered;
        }

        return $return;
    }

    /**
     * Create ghost compare page for customers who didn't set origin compare page in settings
     * modifying myaccount page with custom get params to filter old content with new one
     */
    public function ghost_compare_page() {
        if ( !(isset($_GET['et-compare-page']) && is_account_page() ) ) return;

        add_filter('pre_get_document_title', function ($empty_title) {
            return __('Compare', 'xstore-core');
        });

        // seo nofollow/noindex this page content
        add_action('wp_head', function () {
            echo "\n\t\t<!-- 8theme SEO v1.0.0 -->";
            echo '<meta name="robots" content="noindex, nofollow">';
            echo "\t\t<!-- 8theme SEO -->\n\n";
        });

        // filter page title in breadcrumbs only and remove it after breadcrumbs are shown
        add_action('etheme_page_heading', function () {
            add_filter('the_title', array($this, 'filter_ghost_compare_page_title'), 10, 2);
        }, 5);

        add_action('etheme_page_heading', function () {
            remove_filter('the_title', array($this, 'filter_ghost_compare_page_title'), 10, 2);
        }, 20);

        // load styles
        wp_enqueue_style( self::$key . '-page' );

        // add body classes
        add_filter('body_class', array($this, 'add_body_classes'));

        // modify [woocommerce_my_account] shortcode with the content of compare page
        add_filter('do_shortcode_tag', function ($content, $shortcode, $atts) {
            if ( $shortcode == 'woocommerce_my_account') {
                $content = $this->page_template($atts);
            }
            return $content;
        },10,3);
    }

    public function filter_ghost_compare_page_title($post_title, $post_id) {
        return $post_id == absint(get_option( 'woocommerce_myaccount_page_id' )) ? esc_html__('Compare', 'xstore-core') : $post_title;
    }

    public function escape_text($safe_text, $text) {
        return $text;
    }

    public function add_to_cart_icon($text) {
        global $et_cart_icons;
//		$settings = $this->get_settings_for_display();
        $cart_type = get_theme_mod( 'cart_icon_et-desktop', 'type1' );
        $cart_type = apply_filters('cart_icon', $cart_type);

        $icon_custom = get_theme_mod('cart_icon_custom_svg_et-desktop', '');
        $icon_custom = apply_filters('cart_icon_custom', $icon_custom);
        $icon_custom = isset($icon_custom['id']) ? $icon_custom['id'] : '';

        $cart_icon = false;

        $cart_icons = !get_theme_mod('bold_icons', 0) ? $et_cart_icons['light'] : $et_cart_icons['bold'];

        if ( $cart_type == 'custom' ) {
            if ( $icon_custom != '' ) {
                $cart_icons['custom'] = etheme_get_svg_icon($icon_custom);
            }
            else {
                $cart_icons['custom'] = $cart_icons['type1'];
            }
        }

        $cart_icon = $cart_icons[$cart_type];

        return $cart_icon ? $cart_icon . '<span class="button-text">'.$text.'</span>' : $text;
    }

    /**
     * Based on WooCommerce wc_display_product_attributes() but without output as template
     * @param $product
     * @return mixed
     */
    public function get_product_attributes($product) {
        $product_attributes = array();

        // Display weight and dimensions before attribute list.
        $display_dimensions = apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() );

        if ( $display_dimensions && $product->has_weight() ) {
            $product_attributes['weight'] = array(
                'label' => __( 'Weight', 'xstore-core' ),
                'value' => wc_format_weight( $product->get_weight() ),
            );
        }

        if ( $display_dimensions && $product->has_dimensions() ) {
            $product_attributes['dimensions'] = array(
                'label' => __( 'Dimensions', 'xstore-core' ),
                'value' => wc_format_dimensions( $product->get_dimensions( false ) ),
            );
        }

        // Add product attributes to list.
        $attributes = array_filter( $product->get_attributes(), 'wc_attributes_array_filter_visible' );

        foreach ( $attributes as $attribute ) {
            $values = array();

            if ( $attribute->is_taxonomy() ) {
                $attribute_taxonomy = $attribute->get_taxonomy_object();
                $attribute_values   = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

                foreach ( $attribute_values as $attribute_value ) {
                    $value_name = esc_html( $attribute_value->name );

                    if ( $attribute_taxonomy->attribute_public ) {
                        $values[] = '<a href="' . esc_url( get_term_link( $attribute_value->term_id, $attribute->get_name() ) ) . '" rel="tag">' . $value_name . '</a>';
                    } else {
                        $values[] = $value_name;
                    }
                }
            } else {
                $values = $attribute->get_options();

                foreach ( $values as &$value ) {
                    $value = make_clickable( esc_html( $value ) );
                }
            }

            $product_attributes[ 'attribute_' . sanitize_title_with_dashes( $attribute->get_name() ) ] = array(
                'label' => wc_attribute_label( $attribute->get_name() ),
                'value' => apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values ),
            );
        }

        /**
         * Hook: woocommerce_display_product_attributes.
         *
         * @since 3.6.0.
         * @param array $product_attributes Array of atributes to display; label, value.
         * @param WC_Product $product Showing attributes for this product.
         */
        return array_values(apply_filters( 'woocommerce_display_product_attributes', $product_attributes, $product ));
    }
    /**
     * Compare page shortcode content
     * @param $atts
     * @param null $content
     * @return false|string
     */
	public function page_template($atts, $content=null) {

		$atts = shortcode_atts( array(
            'design' => 'table'
		), $atts );

//        $this->check_compare_items();

        $own_compare = true;
        $products = self::$products;

		if ( count($products) < 1) {
		    ob_start();
		    $this->empty_page_template();
		    $return = ob_get_clean();
		}
		else {
		    add_filter('pre_option_woocommerce_cart_redirect_after_add', '__return_false');
            $compare_page_args = array(
                'own_compare' => $own_compare,
                'products' => array_reverse($products),
                'compare_url' => self::$compare_page,
                'global_actions' => array(
                    'add' => esc_html__('Add to cart', 'xstore-core'),
                    'remove' => $own_compare ? esc_html__('Remove', 'xstore-core') : esc_html__('Remove from my compare', 'xstore-core')
                )
            );
            if ( !$own_compare ) {
                $compare_page_args['global_actions']['add_compare'] = esc_html__('Add to my compare', 'xstore-core');
            }
			ob_start();
            wc_print_notices();
		    switch ($atts['design']) {
                case 'table':
                    $this->render_table_products(self::get_instance(), $compare_page_args);
                    break;
		    }
            $return = ob_get_clean();
		}
		return $return;
	}

    /**
     * Load empty compare page template
     */
	public function empty_page_template() {
        // load direct path because this function used in ajax
		include_once plugin_dir_path( __FILE__ ) . '/templates/empty-compare.php';
	}

    /**
     * Load compare page template
     * @param $instance
     * @param $compare_page_args
     */
	public function render_table_products($instance, $compare_page_args) {
	    include_once self::$templates_path . '/compare.php';
	}

    public function no_script_add_to_compare() {
        if ( ! isset( $_REQUEST['add_to_compare'] ) || ! is_numeric( wp_unslash( $_REQUEST['add_to_compare'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            return;
        }

        $product_id = (int)$_REQUEST['add_to_compare'];
        $products = $this->get_products();

        if ( in_array($product_id, $products['ids'])) return;

        $products['ids'][] = $product_id;
        $products['products'][] = array(
            'id' => $product_id,
            'time' => strtotime( 'now' )
        );

        $this->no_script_update_compare($products['products']);
    }

    public function no_script_remove_compare_product() {
        if ( ! isset( $_REQUEST['remove_compare'] ) || ! is_numeric( wp_unslash( $_REQUEST['remove_compare'] ) ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
            return;
        }

        $product_id_to_reset = (int)$_REQUEST['remove_compare'];
        $products = $this->get_products();

        if ( !in_array($product_id_to_reset, $products['ids'])) return;

        $products_filtered = array_filter($products['products'], function ($val) use ($product_id_to_reset) {
            return $val['id'] != $product_id_to_reset;
        });

        $this->no_script_update_compare($products_filtered);
    }

    public function no_script_update_compare($products) {
        $cookie_key = $this->get_cookie_key();

        $filtered = array();
        $filtered_json = array();
        foreach ($products as $item_key => $item_value) {
            $filtered[$item_value['id']] = $item_value;
            $filtered_json[$item_value['id']] = json_encode($item_value);
        }

        $ready_products = array_values($filtered_json);

        unset( $_COOKIE[$cookie_key] );
//
        setcookie($cookie_key, implode('|', $ready_products), time() + ($this->get_days_cache() * WEEK_IN_SECONDS));
        $_COOKIE[ $cookie_key ] = implode('|', $ready_products);
        self::$products_ids = array_keys($filtered);
        self::$products = array_values($filtered);
        self::$inited = true;

        if ( is_user_logged_in() ) {
            update_user_meta(get_current_user_id(), $this->get_cookie_key(), $_COOKIE[ $cookie_key ]);
        }
    }

    /**
     * Returns the instance.
     *
     * @return object
     * @since  4.3.8
     */
    public static function get_instance( $shortcodes = array() ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $shortcodes );
        }

        return self::$instance;
    }
}

$compare = new XStore_Compare;
$compare->init();