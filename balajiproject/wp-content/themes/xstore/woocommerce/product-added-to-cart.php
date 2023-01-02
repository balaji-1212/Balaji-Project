<?php
/**
 * Description
 *
 * @package    product-added-to-cart.php
 * @since      1.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

$cart = WC()->cart->get_cart();

if ( ! $cart ) return;

remove_filter( 'wp_get_attachment_image_attributes', 'etheme_lazy_attachment_attrs', 10, 3 );

$cart_item = $cart[$cart_current_item_key];

$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_current_item_key );

$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_current_item_key );

$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_current_item_key );

$element_options = array();

ob_start();

wc_print_notice( wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_current_item_key ) . ' ' . esc_html__('has been added to your cart.', 'xstore' ) ) );

?>

<table class="shop_table shop_table_responsive" cellspacing="0">
    <tbody>
        <tr class="woocommerce-cart-form__cart-item woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_current_item_key ) ); ?>" data-key="<?php echo esc_attr($cart_current_item_key); ?>">
            <td class="product-name col-md-3" data-title="<?php esc_attr_e( 'Product', 'xstore' ); ?>">
                <div class="product-thumbnail">
			        <?php
			        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_current_item_key );
			
			        if ( ! $_product->is_visible() || ! $product_permalink){
				        echo wp_kses_post( $thumbnail );
			        } else {
				        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
			        }
			        ?>
                </div>
            </td>
            <td class="product-details col-md-9">
                <div class="cart-item-details align-start">
                    
                    <h4 class="product-title">
                        <?php
                            if ( ! $_product->is_visible() || ! $product_permalink  ){
                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_current_item_key ) );
                            } else {
                                echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s" class="product-title">%s</a>', esc_url( $product_permalink ) , $_product->get_name() ), $cart_item, $cart_current_item_key ) );
                            }
                        ?>
                    </h4>
                    
                    <?php
			
			        do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_current_item_key );
			
			        // Meta data
			        echo wc_get_formatted_cart_item_data( $cart_item );
			
			        // Backorder notification
			        if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) )
				        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'xstore' ) . '</p>', $product_id ) );
			        ?>
			        <?php
			        echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
				        '<a href="%s" class="remove remove_from_cart_button text-underline block popup-remove-from-cart" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">%s</a>',
				        esc_url( wc_get_cart_remove_url( $cart_current_item_key ) ),
				        __( 'Remove this item', 'xstore' ),
				        esc_attr( $product_id ),
				        esc_attr( $cart_current_item_key ),
				        esc_attr( $_product->get_sku() ),
				        esc_html__('Remove', 'xstore')
			        ), $cart_current_item_key );
			        ?>
                </div>

                <div class="product-quantity align-start">
		            <?php
		            if ( ! $_product->is_sold_individually() && $_product->is_purchasable() ) {
			            remove_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
			            remove_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
			            add_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
			            add_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
			            echo '<div class="quantity-wrapper clearfix">';
                            woocommerce_quantity_input(
                                array(
                                    'input_value' => $cart_item['quantity'],
                                    'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $_product->get_min_purchase_quantity(), $_product ),
                                    'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $_product->get_max_purchase_quantity(), $_product ),
                                ),
                                $_product
                            );
                            remove_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
                            remove_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
                            echo '<span class="quantity">' . ' &times; ' .
                                     apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_current_item_key ) .
                                 '</span>';
			            echo '</div>';
		            }
		            ?>
		
                </div>
	
	            <?php
                    if ( in_array('popup_added_to_cart', (array)get_theme_mod('product_sku_locations', array('cart', 'popup_added_to_cart', 'mini-cart'))) && wc_product_sku_enabled() ) {
                        add_filter('product_meta_elements', function () {
                            return array('sku');
                        });
                        global $product;
                        $product = $_product;
                        woocommerce_template_single_meta();
                    }
	            ?>
                
            </td>
        </tr>
        <tr class="order-total">
            <th><?php esc_html_e('Subtotal', 'xstore'); ?></th>
            <?php echo
                '<td data-title="'.esc_attr('Subtotal', 'xstore') . '">' .
                       apply_filters( 'woocommerce_cart_item_subtotal',
                           WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ),
                           $cart_item, $cart_current_item_key ) .
                '</td>'; ?>
        </tr>
    </tbody>
</table>

<?php do_action('etheme_product_added_to_cart_section_01'); ?>
    
<?php $element_options['product_section_01'] = ob_get_clean();

ob_start(); ?>

    <a class="flex-inline btn light big et-close-mfp mob-hide"><i class="et-icon et_b-icon et-left-arrow-2"></i><span><?php esc_html_e('Back to shop','xstore'); ?></span></a>
<a class="flex-inline btn light big" href="<?php echo wc_get_cart_url(); ?>"><?php esc_html_e('View Cart','xstore'); ?></a>
<a class="flex-inline btn black big" href="<?php echo wc_get_checkout_url(); ?>"><?php esc_html_e('Checkout','xstore'); ?></a>

<?php do_action('etheme_product_added_to_cart_section_02');

if ( get_theme_mod('ajax_added_product_notify_popup_progress_bar', false) ) {
    echo do_shortcode('[etheme_sales_booster_cart_checkout_progress_bar]');
}

$element_options['product_section_02'] = ob_get_clean();

$element_options['linked_products_ids'] = array();
$element_options['linked_products_ids']['upsell_ids'] = array();
$element_options['linked_products_ids']['upsell_ids_not_in'] = array();

$element_options['linked_products_ids']['cross-sell_ids'] = array();
$element_options['linked_products_ids']['cross-sell_ids_not_in'] = array();

$cart_content_linked_products_type = get_theme_mod('ajax_added_product_notify_popup_products_type', 'upsell');

if ( $cart_content_linked_products_type != 'none' ) {
	$_product_linked     = $_product;
	$_product_4_linked_ids = array( $product_id );
	if ( $_product->get_type() == 'variation' ) {
		$parent_id               = $_product->get_parent_id();
		$_product_4_linked_ids[] = $parent_id;
		$_product_linked       = wc_get_product( $parent_id );
	}
	
	if ( $cart_content_linked_products_type == 'upsell' ) {
		
		$element_options['linked_products_ids']['upsell_ids']        =
			array_merge( $element_options['linked_products_ids']['upsell_ids'], array_map( 'absint', $_product_linked->get_upsell_ids() ) );
		$element_options['linked_products_ids']['upsell_ids_not_in'] =
			array_merge( $element_options['linked_products_ids']['upsell_ids_not_in'], $_product_4_linked_ids );
		
	}
	else {
		$element_options['linked_products_ids']['cross-sell_ids']        =
			array_merge( $element_options['linked_products_ids']['cross-sell_ids'], array_map( 'absint', $_product_linked->get_cross_sell_ids() ) );
		$element_options['linked_products_ids']['cross-sell_ids_not_in'] =
			array_merge( $element_options['linked_products_ids']['cross-sell_ids_not_in'], $_product_4_linked_ids );
	}
}

 ob_start();
global $woocommerce_loop;
add_filter('etheme_product_content_settings', function ($args){
    return
        array(
            'product_page_productname',
            'product_page_price',
            'product_page_addtocart'
        );
});

do_action('etheme_product_added_popup');
$woocommerce_loop['popup-added-to-cart'] = true;
$woocommerce_loop['product_view'] = 'disable';
    et_mini_cart_linked_products(
            $cart_content_linked_products_type,
            $element_options['linked_products_ids'],
            array(
                'as_widget' => false,
                'slides_per_view' => get_theme_mod('ajax_added_product_notify_popup_products_per_view_et-desktop', 4),
                'slides_per_view_mobile' => 2,
                'hide_out_stock' => true
            )
    );
unset($woocommerce_loop['product_view']);
unset($woocommerce_loop['popup-added-to-cart']);

do_action( 'etheme_product_added_to_cart_section_03' );

$element_options['product_section_03'] = str_replace('carousel-area', '', ob_get_clean());

    echo json_encode(
        array(
            'product_section_01'       => $element_options['product_section_01'],
            'product_section_02'       => $element_options['product_section_02'],
            'product_section_03'       => $element_options['product_section_03'],
        )
    );
