<?php global $etheme_global; ?>
<?php 
    $gallery_slider = $etheme_global['gallery_slider'];
    $vertical_slider = $etheme_global['vertical_slider'];
    $show_thumbs = $etheme_global['show_thumbs'];
?>
<div class="col-md-4 product-summary-center">
    <div class="summary-content">
        <?php
            /**
             * woocommerce_single_product_summary hook
             *
             * @hooked woocommerce_template_single_title - 5 
             * @hooked woocommerce_template_single_rating - 10
             * @hooked woocommerce_template_single_price - 10
             * @hooked woocommerce_template_single_excerpt - 20
             * @hooked woocommerce_template_single_add_to_cart - 30
             * @hooked woocommerce_template_single_meta - 40
             * @hooked woocommerce_template_single_sharing - 50
             */
            do_action( 'woocommerce_single_product_summary' );
        ?>
        
    </div>
</div>

<div class="<?php echo esc_attr( $etheme_global['image_class'] ); ?> product-images <?php  if ( $vertical_slider && $gallery_slider ) : echo ' with-vertical-slider'; endif;?> <?php echo ( !$show_thumbs ) ? 'product-thumbnails-hidden' : 'product-thumbnails-shown'; ?>">
    <?php
        /**
         * woocommerce_before_single_product_summary hook
         *
         * @hooked woocommerce_show_product_sale_flash - 10
         * @hooked woocommerce_show_product_images - 20
         */
        do_action( 'woocommerce_before_single_product_summary' );
    ?>
</div><!-- Product images/ END -->

<div class="<?php echo esc_attr( $etheme_global['infor_class'] ); ?> product-information">
    <div class="product-information-inner">
        <?php woocommerce_template_single_excerpt(); ?>
    </div>
    <?php etheme_size_guide(); ?>
</div><!-- Product information/ END -->