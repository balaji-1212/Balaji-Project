<?php
/**
 * Description
 *
 * @package    wcmp.php
 * @since      8.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

// **********************************************************************//
// ! WC Marketplace fix
// **********************************************************************//
if ( class_exists( 'WCMp_Ajax' ) ) {
    add_action( 'wp_head', 'single_product_multiple_vendor_class' );
}

if ( ! function_exists( 'single_product_multiple_vendor_class' ) ) :
    function single_product_multiple_vendor_class(){
        ?>
        <script type="text/javascript">
            var themeSingleProductMultivendor = '#content_tab_singleproductmultivendor';
        </script>
        <?php
    }
endif;

add_action( 'wp_enqueue_scripts', function (){
    $plugin_4x_version = class_exists('MVX');
    if ( ($plugin_4x_version && function_exists('mvx_is_store_page') && mvx_is_store_page()) ||
        (!$plugin_4x_version && function_exists('wcmp_is_store_page') && wcmp_is_store_page()) ) {
        etheme_enqueue_style( 'star-rating' );
        etheme_enqueue_style( 'comments' );

        wp_enqueue_script( 'comment-reply' );
    }
}, 35 );

add_filter('do_shortcode_tag', function ($content, $shortcode, $atts) {
    if ( in_array($shortcode, array('wcmp_vendor', 'mvx_vendor'))) {
        if ( !get_query_var( 'et_is-loggedin', false) ) {
            etheme_enqueue_style( 'account-page' );
            $content = str_replace(
                array('wcmp-dashboard', 'mvx-dashboard'),
                array('wcmp-dashboard woocommerce-account', 'mvx-dashboard woocommerce-account'),
                $content );
        }
    }
    return $content;
},10,3);

add_action('wp', function () {
    add_action( 'woocommerce_before_shop_loop', function () {
        $plugin_4x_version = class_exists('MVX');
        if ( wc_get_loop_prop( 'is_shortcode' ) || ($plugin_4x_version && function_exists('mvx_is_store_page') && mvx_is_store_page() ||
                (!$plugin_4x_version && function_exists('wcmp_is_store_page') && wcmp_is_store_page())
            ) ) {
            etheme_enqueue_style('filter-area', true ); ?>
            <div class="filter-wrap">
            <div class="filter-content">
        <?php }
    }, 0 );

    add_action( 'woocommerce_before_shop_loop', function () {
        $plugin_4x_version = class_exists('MVX');
        if ( wc_get_loop_prop( 'is_shortcode' ) || ($plugin_4x_version && function_exists('mvx_is_store_page') && mvx_is_store_page()) ||
            (!$plugin_4x_version && function_exists('wcmp_is_store_page') && wcmp_is_store_page())
        ) { ?>
            </div>
            </div>
        <?php }
    }, 99999 );
}, 60);