<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
global $product, $etheme_global, $post;

$etheme_global['quick_view'] = true;
$etheme_global['quick_view_canvas'] = true;

$element_options                    = array();
$element_options['product_content'] = etheme_get_option( 'quick_view_content',
	array(
		'quick_gallery',
		'quick_product_name',
		'quick_price',
		'quick_rating',
		'quick_short_descr',
		'quick_add_to_cart',
		'quick_wishlist',
		'quick_categories',
		'quick_share',
		'product_link'
	)
);
$element_options['product_type'] = $product->get_type();
$element_options['variable_products_detach'] = etheme_get_option('variable_products_detach', false);

if ( $element_options['variable_products_detach'] ) {
	add_filter( 'woocommerce_product_variation_get_average_rating', 'etheme_product_variation_rating', 10, 2 );
    if ( etheme_get_option('variation_product_name_attributes', true) ) {
	    add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_true' );
    }
}

remove_all_actions( 'woocommerce_product_thumbnails' );

add_filter( 'woocommerce_get_availability_class', 'etheme_wc_get_availability_class', 20, 2 );

// disable autoheight if SPBuilder
add_filter('single_product_main_gallery_autoheight', '__return_false');

// to allow static block in qv
add_filter('etheme_static_block_prevent_setup_post', '__return_true');

$element_options['images_type'] = etheme_get_option( 'quick_images', 'slider' );
$element_options['content_pos'] = etheme_get_option( 'quick_view_content_position', 'right' );

$element_options['product_class']   = array();
if ( etheme_get_option('stretch_add_to_cart_et-desktop', false) ) {
	$element_options['product_class'][] = 'stretch-add-to-cart-button';
}
$element_options['product_class'][] = ( $product->is_sold_individually() ) ? 'sold-individually' : '';

$element_options['class'] = array();

if ( in_array( $element_options['images_type'], array( 'slider', 'grid' ) ) ) {
	$element_options['class'][] = 'swipers-couple-wrapper swiper-entry';
	if ( $element_options['images_type'] == 'slider' ) {
		$element_options['class'][] = 'arrows-hovered';
	} else {
		$etheme_global['quick_view_gallery_grid'] = true;
		$element_options['class'][]               = 'swiper-grid';
	}
}

$element_options['quick_descr'] = etheme_get_option( 'quick_descr', 1 );

if ( etheme_get_option('product_variable_price_from', false)) {
	add_filter( 'woocommerce_format_price_range', function ( $price, $from, $to ) {
		return sprintf( '%s %s', esc_html__( 'From:', 'xstore' ), wc_price( $from ) );
	}, 10, 3 );
}

ob_start();

$estimated_delivery_exists = false;
if ( get_option('xstore_sales_booster_settings_estimated_delivery') ) {
	$estimated_delivery_exists = true;
	$estimated_delivery = Etheme_Sales_Booster_Estimated_Delivery::get_instance();
	$estimated_delivery->init($product);
	$estimated_delivery->add_actions();
	$estimated_delivery->args['original_tag'] = $estimated_delivery->args['tag'];
	add_action( 'woocommerce_product_meta_end', array( $estimated_delivery, 'output' ), 15 );
}

$safe_checkout_exists = false;
if ( get_option('xstore_sales_booster_settings_safe_checkout') ) {
    $safe_checkout_exists = true;
    $safe_checkout = Etheme_Sales_Booster_Safe_Checkout::get_instance();
    $safe_checkout->init($product);
    $safe_checkout->add_actions();
    if ( $safe_checkout->settings['shown_on_quick_view'] == 'on' ) {
        add_action( 'woocommerce_product_meta_start', array( $safe_checkout, 'output' ), 15 );
    }
}

?>

    <span class="et-close-popup et-toggle pos-absolute full-<?php echo esc_attr($element_options['content_pos']); ?> top">
        <svg xmlns="http://www.w3.org/2000/svg" width="0.8em" height="0.8em" viewBox="0 0 24 24">
            <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
        </svg>
    </span>
    <div class="et-content product-content">
        <div class="et-content-inner">
			<?php
			
			foreach ( $element_options['product_content'] as $item ) {
				switch ( $item ) {
					case 'quick_product_name':
						?>
                        <h3 class="product-name">
                            <?php echo '<a href="'. esc_attr( $product->get_permalink() ) . '">'.
                                $product->get_title().
                            '</a>'; ?>
                        </h3>
						<?php
						break;
					case 'quick_rating':
					    if ( $element_options['variable_products_detach'] && $element_options['product_type'] == 'variation') {
						    woocommerce_template_loop_rating();
					    }
					    else {
						    woocommerce_template_single_rating();
					    }
						break;
					case 'quick_price':
                        woocommerce_template_single_price();
						break;
					case 'quick_gallery':
						add_filter( 'woocommerce_sale_flash', 'etheme_woocommerce_sale_flash', 20, 3 );
						if ( in_array( $element_options['images_type'], array( 'slider', 'grid' ) ) ): ?>
                            <div class="main-images <?php echo implode( ' ', $element_options['class'] ); ?>">
                                <?php
                                    /**
                                     * woocommerce_before_single_product_summary hook
                                     *
                                     * @hooked woocommerce_show_product_sale_flash - 10
                                     * @hooked woocommerce_show_product_images - 20
                                     */
                                    do_action( 'woocommerce_before_single_product_summary' );
                                ?>
                            </div>
                        <?php
						else: ?>
							<?php
							$images_loading_type = get_theme_mod( 'images_loading_type_et-desktop', 'lazy' );
							if ( $images_loading_type == 'lqip' ) {
								$placeholder = wp_get_attachment_image_src( get_post_thumbnail_id(), 'etheme-woocommerce-nimi' );
								$new_attr    = 'src="' . $placeholder[0] . '" data-src';
								$image       = get_the_post_thumbnail(
									$post->ID,
									apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ),
									array(
										'class' => 'lazyload lazyload-lqip et-lazyload-fadeIn',
										// 'data-lazy_timeout' => '300',
									)
								); ?>
                                <div class="main-images <?php echo implode( ' ', $element_options['class'] ); ?>">
									<?php
									woocommerce_show_product_sale_flash();
									echo str_replace( 'src', $new_attr, $image );
									?>
                                </div>
								<?php
							} else { ?>
                                <div class="main-images <?php echo implode( ' ', $element_options['class'] ); ?>">
									<?php woocommerce_show_product_sale_flash(); ?>
									<?php the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ) ); ?>
                                </div>
							<?php }
						
						endif;
						break;
					case 'quick_categories':
					    if ( $estimated_delivery_exists )
						    $estimated_delivery->args['tag'] = 'span';
						woocommerce_template_single_meta();
						if ( $estimated_delivery_exists )
							$estimated_delivery->args['tag'] = $estimated_delivery->args['original_tag'];
						break;
					case 'quick_share':
						?>
                        <div class="product-share">
							<?php echo do_shortcode( '[share title="' . esc_html__( 'Share:', 'xstore' ) . '" text="' . get_the_title() . '"]' ); ?>
                        </div>
						<?php
						break;
					case 'quick_wishlist':
						// tweak to remove icon from wishlist
						add_filter( 'yith_wcwl_add_to_wishlist_params', function ( $additional_params ) {
							$additional_params['icon'] = 'custom';
							
							return $additional_params;
						} );
                        $element_options['built_in_wishlist'] = get_theme_mod('xstore_wishlist', false);
                        if ( $element_options['built_in_wishlist'] && class_exists('XStoreCore\Modules\WooCommerce\XStore_Wishlist')) {
                            $element_options['built_in_wishlist_instance'] = XStoreCore\Modules\WooCommerce\XStore_Wishlist::get_instance();
                            add_filter('etheme_wishlist_btn_output', array($element_options['built_in_wishlist_instance'], 'old_wishlist_btn_filter_quick_view'), 10, 2);
                        }
						echo etheme_wishlist_btn();
						break;
					case 'quick_short_descr':
                        if ( get_option('xstore_sales_booster_settings_fake_live_viewing') ) {
                                $rendered_string = etheme_set_fake_live_viewing_count(
                                    $element_options['product_type'] == 'variation' ?
                                        $product->get_parent_id() :
                                $product->get_ID());

                            echo '<p class="sales-booster-live-viewing">' . $rendered_string . '</p>';
                        }
						if ( $element_options['variable_products_detach'] && $element_options['product_type'] == 'variation') {
							$custom_excerpt = $product->get_description();
							if ( !empty($custom_excerpt)) {
							    echo '<div class="woocommerce-product-details__short-description">'.$custom_excerpt.'</div>';
							}
							else {
								woocommerce_template_single_excerpt();
                            }
						}
						else {
							woocommerce_template_single_excerpt();
						}
						break;
					case 'product_link':
						if ( ! $element_options['quick_descr'] ) : ?>
                            <a href="<?php the_permalink(); ?>" class="show-full-details">
								<?php esc_html_e( 'Show full details', 'xstore' ); ?>
                            </a>
						<?php
                        else :
	                        $element_options['length']      = etheme_get_option( 'quick_descr_length', 120 );
	                        $element_options['length']      = ( $element_options['length'] ) ? $element_options['length'] : 120;
	                        $element_options['description'] = etheme_trunc( etheme_strip_shortcodes( get_the_content() ), $element_options['length'] );
	                        $element_options['description'] = trim( $element_options['description'] );
	
	                        if ( ! empty( $element_options['description'] ) ): ?>
                                <div class="quick-view-excerpts">
                                    <div class="excerpt-title"><?php esc_html_e( 'More Details', 'xstore' ); ?></div>
                                    <div class="excerpt-content">
                                        <div class="excerpt-content-inner">
					                        <?php echo wp_kses_post( $element_options['description'] );
					                        // if ( in_array( 'product_link', $element_options['product_content'] ) ): ?>
                                                <div>
                                                    <a href="<?php the_permalink(); ?>" class="show-full-details">
								                        <?php esc_html_e( 'Show full details', 'xstore' ); ?></a>
                                                </div>
					                        <?php // endif; ?>
                                        </div>
                                    </div>
                                </div>
	                        <?php endif;
                        endif;
						break;
					case 'quick_add_to_cart':
						
						$advanced_stock = etheme_get_option('advanced_stock_status', false) &&
                              in_array('quick_view', (array) etheme_get_option( 'advanced_stock_locations', array('single_product', 'quick_view') ) ) && 'yes' === get_option( 'woocommerce_manage_stock' );
                        if ( $advanced_stock ) {
                            add_filter( 'woocommerce_get_stock_html', 'etheme_advanced_stock_status_html', 2, 10);
                        }
						
						add_action( 'woocommerce_before_add_to_cart_button', 'etheme_show_single_stock', 10 );
						add_filter( 'woocommerce_available_variation', 'etheme_show_single_variation_stock', 10, 2 );
						
						// add quantity
						add_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
						add_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
						
						do_action( 'et_quick_view_swatch' );
						
						if ( etheme_is_catalog() ) {
							echo sprintf( '<div class="cart"><a rel="nofollow" href="%s" class="button single_add_to_cart_button show-product">%s</a></div>',
								esc_url( $product->get_permalink() ),
								__( 'Show details', 'xstore' ) );
						} else {
						    switch ($element_options['product_type']) {
                                case 'simple':
	                                woocommerce_template_single_add_to_cart();
                                break;
                                case 'variation':
	                                do_action( 'woocommerce_simple_add_to_cart' );
                                    break;
							    case 'variable':
								    echo '<div class="quantity-wrapper">';
								    woocommerce_quantity_input( array(), $product, true );
								    woocommerce_template_loop_add_to_cart();
								    echo '</div>';
								    if ( etheme_get_option( 'buy_now_btn', 0 ) ) {
									    etheme_buy_now_btn('variable-quick-view');
								    }
								    break;
                                default:
	                                woocommerce_template_loop_add_to_cart();
                                    break;
						    }
						}
						
						remove_action( 'woocommerce_before_quantity_input_field', 'et_quantity_minus_icon' );
						remove_action( 'woocommerce_after_quantity_input_field', 'et_quantity_plus_icon' );
						
						if ( $advanced_stock ) {
                            remove_filter( 'woocommerce_get_stock_html', 'etheme_advanced_stock_status_html', 2, 10);
                        }

                        if ( get_theme_mod('xstore_compare', false) && class_exists('XStoreCore\Modules\WooCommerce\XStore_Compare')) {
                            $element_options['built_in_compare_instance'] = XStoreCore\Modules\WooCommerce\XStore_Compare::get_instance();
                            $element_options['built_in_compare_instance']->old_compare_btn_filter_quick_view();
                        }
						break;
					default:
						break;
				}
			}
			?>
        </div>
    </div>

<?php

if ( $estimated_delivery_exists )
	remove_action( 'woocommerce_product_meta_end', array( $estimated_delivery, 'output' ), 15 );

echo json_encode(
	array(
		'html'    => ob_get_clean(),
		'classes' => esc_attr( implode( ' ', wc_get_product_class( $element_options['product_class'], $product->get_ID() ) ) )
	)
);