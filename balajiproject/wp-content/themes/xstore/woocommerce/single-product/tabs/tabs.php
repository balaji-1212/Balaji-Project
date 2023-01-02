<?php
/**
 * Single Product tabs
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */

etheme_enqueue_style('tabs');

if ( get_option( 'etheme_single_product_builder', false ) && function_exists('etheme_core_hooks') ) {
    wc_get_template( 'single-product/tabs/single-product-builder-tabs.php' );
    return;
}

$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

$close_tab = etheme_get_option('first_tab_closed', 0);

$et_tabs['custom_tab'] = etheme_get_option( 'custom_tab_title', '' );
$et_tabs['custom_tab1'] = etheme_get_custom_field('custom_tab1_title');
$et_tabs['check'] = ( ! empty( $et_tabs['custom_tab'] ) || ! empty( $et_tabs['custom_tab1'] ) ) ? 1 : 0;
$tabs_type = etheme_get_option('tabs_type', 'tabs-default');

if ( ( ! empty( $product_tabs ) || $et_tabs['check'] ) && $tabs_type != 'disable' ) : $i=0; ?>
<?php if (etheme_get_option( 'single_layout', 'default' ) == 'center' && etheme_get_option('tabs_location', 'after_content') == 'after_content') : ?>
<div data-vc-full-width="true" data-vc-full-width-init="false" class="vc_row wpb_row tabs-full-width">
<?php endif ?>
    <div class="woocommerce-tabs wc-tabs-wrapper tabs <?php etheme_option('tabs_type'); ?> <?php echo (etheme_get_option('tabs_scroll', 0) && $tabs_type == 'accordion') ? 'tabs-with-scroll' : ''; ?>">
        <ul class="wc-tabs tabs-nav">
            <?php foreach ( $product_tabs as $key => $product_tab ) : $i++; ?>
                <li <?php if($i == 1 && $close_tab) echo 'class="tab_closed"'; ?>>
                    <a href="#tab_<?php echo esc_attr($key) ?>" id="tab_<?php echo esc_attr($key) ?>" class="tab-title <?php if($i == 1 && !$close_tab) echo 'opened'; ?>"><span><?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?></span></a>
                </li>
            <?php endforeach; ?>

            
            <?php if ( $et_tabs['custom_tab1'] && $et_tabs['custom_tab1'] != '' ) : ?>
                <li>
                    <a href="#tab_7" id="tab_7" class="tab-title <?php if( empty( $product_tabs ) && ! empty( $et_tabs['custom_tab1'] ) ) echo 'opened'; ?>"><span><?php echo esc_html($et_tabs['custom_tab1']); ?></span></a>
                </li>
            <?php endif; ?>  
        
            <?php if ( $et_tabs['custom_tab'] && $et_tabs['custom_tab'] != '' ) : ?>
                <li>
                    <a href="#tab_9" id="tab_9" class="tab-title <?php if( empty( $product_tabs ) && empty( $et_tabs['custom_tab1'] ) && ! empty( $et_tabs['custom_tab'] ) ) echo 'opened'; ?>"><span><?php echo esc_html($et_tabs['custom_tab']); ?></span></a>
                </li>                
            <?php endif; ?> 
        </ul>

        <?php $i = 0; foreach ( $product_tabs as $key => $product_tab ) : $i++; ?>
            <?php if ( $tabs_type == 'accordion' ) : ?>
                <div class="accordion-title <?php if($i == 1 && $close_tab) echo 'tab_closed'; ?>"><a href="#tab_<?php echo esc_attr($key) ?>" id="tab_<?php echo esc_attr($key) ?>" class="tab-title <?php if($i == 1 && !$close_tab) echo 'opened'; ?>"><span><?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?></span></a></div>
            <?php endif; ?>
            <div class="tab-content tab-<?php echo esc_attr($key) ?>" id="content_tab_<?php echo esc_attr($key) ?>" <?php if($i == 1 && !$close_tab) echo 'style="display:block;"'; ?>>
                <div class="tab-content-inner">
                    <div class="tab-content-scroll">
                        <?php call_user_func( $product_tab['callback'], esc_attr($key), $product_tab ) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php if ( $et_tabs['custom_tab1'] && $et_tabs['custom_tab1'] != '' ) : ?>
	        <?php if ( $tabs_type == 'accordion' ) : ?>
                <div class="accordion-title"><a href="#tab_7" id="tab_7" class="tab-title <?php if( empty( $product_tabs ) && ! empty( $et_tabs['custom_tab1'] ) ) echo 'opened'; ?>"><span><?php echo esc_html($et_tabs['custom_tab1']); ?></span></a></div>
	        <?php endif; ?>
            <div id="content_tab_7" class="tab-content" <?php if( empty( $product_tabs ) && ! empty( $et_tabs['custom_tab1'] ) ) echo 'style="display:block;"'; ?>>
                <div class="tab-content-inner">
                    <div class="tab-content-scroll">
                        <?php echo do_shortcode(etheme_get_custom_field('custom_tab1')); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>  
        
        <?php if ( $et_tabs['custom_tab'] && $et_tabs['custom_tab'] != '' ) : ?>
	        <?php if ( $tabs_type == 'accordion' ) : ?>
                <div class="accordion-title"><a href="#tab_9" id="tab_9" class="tab-title <?php if( empty( $product_tabs ) && empty( $et_tabs['custom_tab1'] ) && ! empty( $et_tabs['custom_tab'] ) ) echo 'opened'; ?>"><span><?php echo esc_html($et_tabs['custom_tab']); ?></span></a></div>
            <?php endif; ?>
            <div id="content_tab_9" class="tab-content" <?php if( empty( $product_tabs ) && empty( $et_tabs['custom_tab1'] ) && ! empty( $et_tabs['custom_tab'] ) ) echo 'style="display:block;"'; ?>>
                <div class="tab-content-inner">
                    <div class="tab-content-scroll">
                        <?php echo do_shortcode(etheme_get_option('custom_tab', '')); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?> 
        <?php do_action( 'woocommerce_product_after_tabs' ); ?>
    </div>
<?php if (etheme_get_option( 'single_layout', 'default' ) == 'center' && etheme_get_option('tabs_location', 'after_content') == 'after_content' ) : ?>
</div>
<div class="vc_row-full-width vc_clearfix"></div>
    <?php // ! WC Marketplace fix ?>
    <?php if ( class_exists( 'WCMp_Ajax' ) ): ?>
        <script>
        jQuery(document).ready(function($) {
            $('.goto_more_offer_tab').click(function (e) {   
                if (!$('#tab_singleproductmultivendor').hasClass('opened')) {
                    $('#tab_singleproductmultivendor').click();
                }     
            }); 
        });
        </script>
    <?php endif; ?>
<?php endif; ?>
<?php endif; ?>