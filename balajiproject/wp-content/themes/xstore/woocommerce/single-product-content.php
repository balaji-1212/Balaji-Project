<?php global $etheme_global; ?>

<?php
    $gallery_slider = $etheme_global['gallery_slider'];
    $vertical_slider = $etheme_global['vertical_slider'];
    $show_thumbs = $etheme_global['show_thumbs'];
?>

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

<?php 
    if( $etheme_global['layout'] == 'large' ) {
        ?>
        </div>
        <div class="row">
        <?php
    } 
?>

<div class="<?php echo esc_attr( $etheme_global['infor_class'] ); ?> product-information">
    <div class="product-information-inner">
        <div class="fixed-content">
            <?php if(etheme_get_option('product_name_signle', 0) && !etheme_get_option('product_name_single_duplicated', 0)):  ?>
                <h4 class="title"><?php esc_html_e('Product Information', 'xstore'); ?></h4>
            <?php endif; ?>
        
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
</div><!-- Product information/ END -->
<?php 
    if( $etheme_global['layout'] == 'large' ) {
        ?>
            <div class="<?php echo esc_attr( $etheme_global['infor_class'] ); ?>">
                <?php do_action( 'woocommerce_after_single_product_summary' ); ?>
            </div>
        <?php
    } 
?>