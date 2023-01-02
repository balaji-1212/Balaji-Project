<?php
/**
 * Template for showing grid/slider of recommended to buy together products.
 *
 * @package    bought-together.php
 * @since      8.1.7
 * @version    1.0.1
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

global $product;

if ( !$product->is_in_stock() ) return;

if ( !in_array($product->get_type(), array('simple', 'external')) ) return;

$current_product_id = $product->get_id();
$current_product_permalink = $product->get_permalink();
$et_bought_together_ids = et_get_product_bought_together_ids( $product );
$redirect_link = wc_get_cart_url();
if ( get_query_var( 'etheme_single_product_builder', false ) ) {
    switch (get_theme_mod('single_product_bought_together_redirect', 'cart')) {
        case 'checkout':
            $redirect_link = wc_get_checkout_url();
            break;
        case 'product':
            $redirect_link = $current_product_permalink;
            break;
    }
	$title = get_theme_mod('single_product_bought_together_products_title', $title);
}
if ( sizeof( $et_bought_together_ids ) === 0 && !array_filter( $et_bought_together_ids ) ) {
    return;
}

array_unshift( $et_bought_together_ids, $current_product_id );

$meta_query = WC()->query->get_meta_query();

$args = apply_filters( 'etheme_product_et_bought_together_query_args', array(
    'post_type'           => array('product', 'product_variation'),
    'ignore_sticky_posts' => 1,
    'no_found_rows'       => 1,
    'posts_per_page'      => -1,
    'orderby'             => 'post__in',
    'post__in'            => $et_bought_together_ids,
    'meta_query'          => $meta_query
) );

unset( $args['meta_query'] );

$products = new WP_Query( $args );

$add_to_cart_checkbox 	= '';
$total_price 			= 0;
$total_price_suffix		= 0;
$count 					= 0;

if ( $products->have_posts() ) :
	wp_enqueue_script( 'et_single_product_bought_together');
?>
<div class="bought-together-products-wrapper">
    <div class="bought-together-products">
        <?php if ( $title ) echo '<h3 class="title products-title text-left"><span>'.$title.'</span></h3>'; ?>
        <div class="row">
                <div class="col-md-8">
                <?php

                $slider_args = array(
                    'slider_autoplay' => false,
                    'slider_speed'    => '0',
                    'slider_space' => 30,
                    'autoheight'      => false,
                    'large'           => $large,
                    'notebook'        => $large,
                    'tablet_land'     => $large,
                    'tablet_portrait' => $mobile,
                    'mobile'          => $mobile,
                    'echo'            => false,
                    'wrapper_class' => 'bought-together-products-slider',
                    'navigation_position' => 'middle-inside',
                    'pagination_type' => 'bullets'
                );

                global$woocommerce_loop;
                $woocommerce_loop['product_content_elements'] = array(
                    'product_page_productname',
                    'product_page_productrating',
                    'product_page_price',
                );
                echo etheme_slider( $args, 'product', $slider_args );
                unset($woocommerce_loop['product_content_elements']);

                while ( $products->have_posts() ) : $products->the_post();

                    global $product;
                    $product_type = $product->get_type();
                    $product_id = $product->get_id();
                    $in_stock = $product->is_in_stock();
                    $available_text = $product->get_availability()['availability'];
                    
                    if ( !$in_stock && 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) continue;

                    $display_price = wc_get_price_to_display( $product );

                    $input_value = $product->get_min_purchase_quantity();
                    $checked = ( $current_product_id == $product_id ) ? 'checked disabled' : 'checked';
                    if ( $in_stock ) {
                        $total_price += $display_price;
                        $count++;
                    }
                    else {
	                    $checked = 'disabled';
                    }

                    $input_args = array(
                        $checked,
                        'name="product-checkbox['.$product_id.']"',
                        'type="checkbox"',
                        'class="product-checkbox"',
                        'data-price="'  . $display_price . '"',
                        'data-product-id="' . $product_id . '"',
                        'data-product-type="' . $product_type . '"',
                        'data-product-quantity="'.$input_value.'"'
                    );

                    $add_to_cart_checkbox .= '<label>'.
                        '<input ' . implode(' ', $input_args) . '/>
                            <span class="product-title">' . get_the_title() . (!$in_stock ? ' ('.$available_text.')' : '') . '</span> ' .
                            '<span class="price">' . $product->get_price_html() . '</span>' .
                        '</label>';
                    if ( $in_stock ) {
                        ob_start();
                        woocommerce_quantity_input(
                            array(
                                'input_name' => 'quantity[' . $product_id . ']',
                                'input_value' => $input_value, // phpcs:ignore WordPress.Security.NonceVerification.Missing
                                'min_value' => apply_filters('woocommerce_quantity_input_min', 0, $product),
                                'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                                'placeholder' => '0',
                            )
                        );
                        $add_to_cart_checkbox .= '<div class="hidden" style="display: none">' . ob_get_clean() . '</div>';
                    }
                    endwhile;

                ?>

                </div>
                <div class="col-md-4">
                    <form action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $redirect_link ) ); ?>" method="post" enctype='multipart/form-data'>

                        <?php echo '<div class="bought-together-products-list">' . $add_to_cart_checkbox . '</div>'; ?>

                        <?php if( $total_price > 0 ) : ?>
                            <div class="total-price-wrapper">
                                <?php
                                $total_price_html = '<div class="total-price">' . wc_price( $total_price ) . '</div>';
                                $total_price = sprintf( __( '%s <div class="total-products">For %s item(s)</div>', 'xstore' ), $total_price_html, $count );
                                echo wp_kses_post( $total_price );
                                ?>
                            </div>
                            <?php if ( !get_query_var('et_is-catalog', false) || ! etheme_get_option( 'just_catalog_price', 0 ) ) : ?>
                                <div class="bought-together-button-wrapper">
                                    <input type="hidden" name="et_bought_together_add_to_cart">
                                    <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($current_product_id); ?>">
                                    <button type="submit" class="button btn active bought-together-button"><?php echo esc_html__( 'Add all to cart', 'xstore' ); ?></button>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </form>
                </div>
            </div> <?php // .row ?>
    </div>
</div>
<?php

endif;

wp_reset_postdata();
wc_reset_loop();
