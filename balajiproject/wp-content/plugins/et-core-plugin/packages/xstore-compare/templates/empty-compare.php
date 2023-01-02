<?php
/**
 * Description
 *
 * @package    empty-compare.php
 * @since      1.0.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

$empty_compare_content = etheme_get_option('xstore_compare_empty_page_content', '<h1 style="text-align: center;">Your compare is empty</h1><p style="text-align: center;">We invite you to get acquainted with an assortment of our shop. Surely you can find something for yourself!</p> ');

$woo_new_7_0_1_version = function_exists('etheme_woo_version_check') && etheme_woo_version_check();
$button_class = '';
if ( $woo_new_7_0_1_version ) {
    $button_class = wc_wp_theme_get_element_class_name( 'button' );
}

$element_options = array();
$element_options['attributes'] = array(
    'class="compare-empty empty-compare-block"',
);

if ( get_query_var('et_is_customize_preview', false) ) {
    $element_options['attributes'][] = 'data-title="' . esc_html__( 'Built-in Compare', 'xstore-core' ) . '"';
    $element_options['attributes'][] = 'data-element="xstore-compare"';
}
?>

<div <?php echo implode(' ', $element_options['attributes']); ?>>
    <?php if( empty( $empty_compare_content ) ): ?>
        <h1 style="text-align: center;"><?php echo esc_html__('Your compare is empty', 'xstore-core'); ?></h1>
        <p style="text-align: center;"><?php echo esc_html__('We invite you to get acquainted with an assortment of our shop. Surely you can find something for yourself!', 'xstore-core'); ?></p>
    <?php else: ?>
        <?php echo do_shortcode( $empty_compare_content ); ?>
    <?php endif; ?>
    <?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
        <p style="text-align: center;"><a class="btn black<?php echo esc_attr( $button_class ? ' ' . $button_class : '' ); ?>" href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><span><?php esc_html_e('Return To Shop', 'xstore-core') ?></span></a></p>
    <?php endif; ?>
</div>

<?php unset($element_options);
