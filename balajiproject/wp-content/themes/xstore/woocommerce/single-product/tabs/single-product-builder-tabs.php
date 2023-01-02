<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */

$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

$element_options = array();
$element_options['product_tabs_type'] = etheme_get_option( 'product_tabs_type_et-desktop', 'underline' );
$element_options['product_tabs_style'] = etheme_get_option( 'product_tabs_style_et-desktop', 'horizontal' );
$element_options['product_tabs_style'] = ( $element_options['product_tabs_type'] != 'accordion' ) ? $element_options['product_tabs_style'] : 'horizontal';
$element_options['product_tabs_style'] = get_query_var('is_mobile') ? 'horizontal' : $element_options['product_tabs_style'];
$element_options['product_tabs_closed'] = ( $element_options['product_tabs_type'] == 'accordion' && !etheme_get_option( 'product_first_tab_opened_et-desktop', 0 ) );
$element_options['product_tabs_opened'] = $element_options['product_tabs_type'] == 'accordion' && get_theme_mod('product_tabs_opened_et-desktop', 0);
$element_options['product_tabs_scroll'] = etheme_get_option( 'product_tabs_scroll_et-desktop', 0 );
$element_options['product_tabs_title_arrow'] = '';
if ( $element_options['product_tabs_type'] == 'accordion' ) {
	$element_options['product_tabs_title_arrow'] = '<span class="open-child"></span>';
}
$element_options['product_tabs_navigation_align'] = etheme_get_option( 'product_tabs_navigation_align_et-desktop', 'center' );

$element_options['classes'] = 'type-'.$element_options['product_tabs_type'];
$element_options['classes'] .= ( $element_options['product_tabs_type'] != 'accordion' ) ? ' ' . $element_options['product_tabs_style'] : '';
$element_options['classes'] .= ( $element_options['product_tabs_scroll'] ) ? ' tabs-with-scroll' : '';
if ( !$element_options['product_tabs_opened'] )
    $element_options['classes'] .= ( $element_options['product_tabs_closed'] ) ? ' closed-first-tab' : '';
else
    $element_options['classes'] .= ' opened-all-tabs';
$element_options['classes'] .= ( $element_options['product_tabs_type'] == 'accordion' ) ? ' toggles-by-arrow' : '';
if ( $element_options['product_tabs_type'] == 'accordion' ) {
	etheme_enqueue_style('toggles-by-arrow');
}

$element_options['ul_classes'] = ( $element_options['product_tabs_style'] == 'horizontal' ) ? ' justify-content-' . $element_options['product_tabs_navigation_align'] : ' align-content-' . $element_options['product_tabs_navigation_align'];

if ( ! empty( $product_tabs ) ) : ?>

	<div class="woocommerce-tabs et-clearfix et_element wc-tabs-wrapper <?php echo esc_attr( $element_options['classes'] ); ?>" data-title="<?php esc_attr_e('Tabs', 'xstore'); ?>">
		<div class="tabs wc-tabs <?php echo esc_attr( $element_options['ul_classes'] ); ?>" role="tablist">
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
				<div class="<?php echo esc_attr( $key ); ?>_tab et-woocommerce-tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
					<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo '' . $element_options['product_tabs_title_arrow']; ?><?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?></a>
				</div>
				<?php if ( $element_options['product_tabs_type'] == 'accordion' ) : ?>
					<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
						<?php if ( isset( $product_tab['callback'] ) ) { call_user_func( $product_tab['callback'], $key, $product_tab ); } ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
		
		<?php if ( $element_options['product_tabs_type'] != 'accordion' ) : 

		foreach ( $product_tabs as $key => $product_tab ) : ?>

			<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
						<?php if ( isset( $product_tab['callback'] ) ) { call_user_func( $product_tab['callback'], $key, $product_tab ); } ?>
					</div>

		<?php endforeach;

		endif; ?>
		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div>

<?php endif; ?>
