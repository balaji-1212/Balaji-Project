<?php
/**
 * The template for displaying product widget entries
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-widget-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.5.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product; ?>

<div class="swiper-slide">
    <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>" class="product-list-image">
        <?php 
            $thumbnail_id = get_post_thumbnail_id( $product->get_id() );
            if ( ! empty( $thumbnail_id ) ) {
	            $thumbnail = $product->get_image( 'woocommerce_gallery_thumbnail');
//                $thumbnail = $product->get_image( 'woocommerce_gallery_thumbnail', array( 'class' => 'swiper-lazy attachment-shop_thumbnail size-shop_thumbnail wp-post-image' ) );
                echo wp_kses_post($thumbnail);
//                etheme_loader(true, 'swiper-lazy-preloader');
            } else {
                $thumbnail = $product->get_image();
                echo wp_kses_post($thumbnail);
            }
        ?>
    </a>

    <div class="product-item-right">

        <p class="product-title"><a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>"><?php echo wp_specialchars_decode($product->get_title()); ?></a></p>

        <?php if ( ! empty( $show_rating ) ) : ?>
            <?php echo wc_get_rating_html( $product->get_average_rating() ); ?>
        <?php endif; ?>

        <div class="price">
            <?php echo wp_kses_post($product->get_price_html()); ?>
        </div>

    </div>
</div>