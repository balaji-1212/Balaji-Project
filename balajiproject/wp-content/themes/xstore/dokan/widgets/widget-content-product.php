<?php
/**
 * Dokan Widget Content Product Template
 *
 * @since 2.4
 *
 * @package dokan
 */
?>

<?php if ( $r->have_posts() ) : ?>
    <ul class="dokan-bestselling-product-widget product_list_widget">
    <?php while ( $r->have_posts() ): $r->the_post() ?>
        <?php global $product; ?>
        <li>
            <a href="<?php echo esc_url( get_permalink( dokan_get_prop( $product, 'id' ) ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>" class="product-list-image">
                <?php echo wp_kses_post($product->get_image()); ?>
            </a>
         <div class="product-item-right">

            <p class="product-title"><a href="<?php echo esc_url( get_permalink( dokan_get_prop( $product, 'id' ) ) ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>"><?php echo esc_html( $product->get_title() ); ?></a></p>

            <?php  echo ( ! empty( $show_rating ) ) ? wc_get_rating_html( $product->get_average_rating() ) : '';  ?>

             <div class="price">
                <?php echo wp_kses_post($product->get_price_html()); ?>
            </div>
        </li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p><?php esc_html_e( 'No products found', 'xstore' ); ?></p>
<?php endif; ?>
