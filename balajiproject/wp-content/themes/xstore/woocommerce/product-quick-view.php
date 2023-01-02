<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
global $product, $etheme_global, $post;

$etheme_global['quick_view'] = true;

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

$element_options['images_type'] = etheme_get_option( 'quick_images', 'slider' );
$element_options['product_type'] = $product->get_type();
$element_options['variable_products_detach'] = etheme_get_option('variable_products_detach', false);

if ( $element_options['variable_products_detach'] ) {
	add_filter( 'woocommerce_product_variation_get_average_rating', 'etheme_product_variation_rating', 10, 2 );
	if ( etheme_get_option('variation_product_name_attributes', true) ) {
		add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_true' );
	}
}

remove_all_actions( 'woocommerce_product_thumbnails' );

add_filter( 'woocommerce_sale_flash', 'etheme_woocommerce_sale_flash', 20, 3 );
add_filter( 'woocommerce_get_availability_class', 'etheme_wc_get_availability_class', 20, 2 );

// disable autoheight if SPBuilder
add_filter('single_product_main_gallery_autoheight', '__return_false');

// to allow static block in qv
add_filter('etheme_static_block_prevent_setup_post', '__return_true');

if ( etheme_get_option( 'quick_view_layout', 'default' ) == 'centered' ) {
	$element_options['class'] = 'text-center';
}

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

$element_options['product_class']   = array();
$element_options['product_class'][] = 'product-content';
$element_options['product_class'][] = 'quick-view-layout-' . etheme_get_option( 'quick_view_layout', 'default' );
if ( etheme_get_option('stretch_add_to_cart_et-desktop', false) ) {
	$element_options['product_class'][] = 'stretch-add-to-cart-button';
}
$element_options['product_class'][] = ( $product->is_sold_individually() ) ? 'sold-individually' : '';

$element_options['quick_descr'] = etheme_get_option( 'quick_descr', 1 );

if ( etheme_get_option('product_variable_price_from', false)) {
	add_filter( 'woocommerce_format_price_range', function ( $price, $from, $to ) {
		return sprintf( '%s %s', esc_html__( 'From:', 'xstore' ), wc_price( $from ) );
	}, 10, 3 );
}

ob_start();

etheme_enqueue_style('single-product' );
etheme_enqueue_style('single-post-meta' );
//etheme_enqueue_style('star-rating' );

if ( in_array('quick_gallery', $element_options['product_content']) ) {
    
    if ( in_array( $element_options['images_type'], array( 'slider', 'grid' ) ) ): ?>
        <?php
        /**
         * woocommerce_before_single_product_summary hook
         *
         * @hooked woocommerce_show_product_sale_flash - 10
         * @hooked woocommerce_show_product_images - 20
         */
        do_action( 'woocommerce_before_single_product_summary' );
    
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
            <div class="main-images">
                <?php
                woocommerce_show_product_sale_flash();
                echo str_replace( 'src', $new_attr, $image );
                ?>
            </div>
            <?php
        } else { ?>
            <div class="main-images">
                <?php
                woocommerce_show_product_sale_flash();
                the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ) );
                ?>
            </div>
        <?php }
    endif; ?>
    
    <?php
    
}

$element_options['html_col_one'] = ob_get_clean();

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
			$product_type = $product->get_type();
			
			if ( etheme_is_catalog() ) {
				echo sprintf( '<div class="cart"><a rel="nofollow" href="%s" class="button single_add_to_cart_button show-product">%s</a></div>',
					esc_url( $product->get_permalink() ),
					__( 'Show details', 'xstore' ) );
			} else {
				switch ($product_type) {
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

if ( $estimated_delivery_exists )
	remove_action( 'woocommerce_product_meta_end', array( $estimated_delivery, 'output' ), 15 );

$element_options['html_col_two'] = ob_get_clean();

echo json_encode(
	array(
		'html_col_one'       => $element_options['html_col_one'],
		'html_col_two'       => $element_options['html_col_two'],
		'classes'            => esc_attr( implode( ' ', wc_get_product_class( $element_options['product_class'], $product->get_ID() ) ) ),
		// for popup type if gallery shown or not
		'has_first_column' => in_array('quick_gallery', $element_options['product_content']),
		'col_one_classes'    => esc_attr( implode( ' ', $element_options['class'] ) ),
		'quick_image_height' => esc_attr( etheme_get_option( 'quick_image_height', '' ) )
	)
);