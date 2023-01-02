<?php
/**
 * Description
 *
 * @package    compare.php
 * @since      4.3.8
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly


$table_type_advanced = false;
$compare_fields = array_merge(get_theme_mod('xstore_compare_page_content', array(
    'action',
    'product',
    'title',
    'quantity',
    'price',
    'rating',
    'button',
    'excerpt',
    'stock_status',
    'brand',
    'sku',
//    'gtin',
//    'attributes',
)), array(
    'checkbox',
));

if (!get_theme_mod('enable_brands', true))
    unset($compare_fields[array_search('brand', $compare_fields)]);

$fields_strings = array(
    'action' => esc_html__('Action', 'xstore-core'),
    'product' => esc_html__('Image', 'xstore-core'),
    'button' => esc_html__('Button', 'xstore-core'),
    'title' => esc_html__('Title', 'xstore-core'),
    'quantity' => esc_html__('Quantity', 'xstore-core'),
    'sku' => esc_html__('Sku', 'xstore-core'),
    'gtin' => esc_html__('Gtin', 'xstore-core'),
    'price' => esc_html__('Price', 'xstore-core'),
    'brand' => esc_html__('Brand', 'xstore-core'),
    'rating' => esc_html__('Rating', 'xstore-core'),
    'excerpt' => esc_html__('Excerpt', 'xstore-core'),
    'stock_status' => esc_html__('Stock status', 'xstore-core'),
    'checkbox' => esc_html__('Select all', 'xstore-core'),
);

extract($compare_page_args);

$show_global_actions = in_array('action', $compare_fields);

$element_options = array();
$element_options['form_attributes'] = array(
    'class="xstore-compare-form"',
    'action="' . esc_url($compare_url) . '"',
);

if (get_query_var('et_is_customize_preview', false)) {
    $element_options['form_attributes'][] = 'data-title="' . esc_html__('Built-in Compare', 'xstore-core') . '"';
    $element_options['form_attributes'][] = 'data-element="xstore-compare"';
}

$all_attributes = array();

$ghost_products_limit = count($products) >= 4 ? 0 : (4 - count($products));
$ghost_products = array();

$shop_url = get_permalink(wc_get_page_id('shop'));

?>

    <form <?php echo implode(' ', $element_options['form_attributes']); ?>>
        <div class="xstore-compare-table-wrapper">
            <table class="xstore-compare-table">
                <tbody class="xstore-compare-items">
                <?php
                $has_sku = wc_product_sku_enabled();
                $products_count = 0;
                $product_table_info = array();
                foreach ($products as $product_info) {
                    add_filter('woocommerce_product_variation_title_include_attributes', '__return_true');
                    $post_object = get_post($product_info['id']);
                    setup_postdata($GLOBALS['post'] =& $post_object); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
                    $product = wc_get_product($product_info['id']);
                    if (!$product) {
                        wp_reset_postdata();
                        continue;
                    }

                    $products_count++;

                    $product_table_info[$products_count] = array();
                    $product_table_info[$products_count]['id'] = $product_info['id'];

                    $thumbnail = woocommerce_get_product_thumbnail();
                    $product_table_info[$products_count]['thumbnail'] = $thumbnail;
//                    $product_quantity = 1; // @todo get quantity

                    foreach ($compare_fields as $field) :
                        if (!$show_global_actions && $field == 'checkbox') continue;
                        ?>
                        <?php switch ($field) {
                        case 'checkbox':
                            ob_start();
                            ?>
                            <input type="checkbox" name="product-<?php echo esc_attr($product_info['id']); ?>">
                            <?php
                            $product_table_info[$products_count][$field] = ob_get_clean();
                            break;
                        case 'quantity':
                            remove_action('woocommerce_before_quantity_input_field', 'et_quantity_minus_icon');
                            remove_action('woocommerce_after_quantity_input_field', 'et_quantity_plus_icon');
                            add_action('woocommerce_before_quantity_input_field', 'et_quantity_minus_icon');
                            add_action('woocommerce_after_quantity_input_field', 'et_quantity_plus_icon');
//                                    if ( $product->is_type( 'grouped' ) && $product->has_child() ) {
//                                        $grouped_products = $product->get_children();
//                                        foreach ( $grouped_products as $grouped_product_child ) {
//                                            $grouped_product_child        = wc_get_product( $grouped_product_child );
//                                            if ( ! $grouped_product_child->is_purchasable() || $grouped_product_child->has_options() || ! $grouped_product_child->is_in_stock() ) {
//                                                woocommerce_template_loop_add_to_cart();
//                                            } elseif ( $grouped_product_child->is_sold_individually() ) {
//                                                echo '<input type="checkbox" name="' . esc_attr( 'quantity[' . $grouped_product_child->get_id() . ']' ) . '" value="1" class="wc-grouped-product-add-to-cart-checkbox" id="' . esc_attr( 'quantity-' . $grouped_product_child->get_id() ) . '" />';
//                                                echo '<label for="' . esc_attr( 'quantity-' . $grouped_product_child->get_id() ) . '" class="screen-reader-text">' . esc_html__( 'Buy one of this item', 'woocommerce' ) . '</label>';
//                                            } else {
//                                                do_action( 'woocommerce_before_add_to_cart_quantity' );
//
//                                                woocommerce_quantity_input(
//                                                    array(
//                                                        'input_name'  => 'quantity[' . $grouped_product_child->get_id() . ']',
//                                                        'input_value' => $product_quantity,
//                                                        'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $grouped_product_child->get_min_purchase_quantity(), $grouped_product_child ),
//                                                        'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $grouped_product_child->get_max_purchase_quantity(), $grouped_product_child ),
//                                                        'placeholder' => '0',
//                                                    )
//                                                );
//
//                                                do_action( 'woocommerce_after_add_to_cart_quantity' );
//                                            }
//                                        }
//                                    }
//                                    else {
                            ob_start();
                            if (!in_array($product->get_type(), array('variable')) && !$product->is_sold_individually() && $product->is_purchasable()) {
                                echo '<div class="quantity-wrapper clearfix">';
                                woocommerce_quantity_input(
                                    array(),
                                    $product
                                );
                                echo '</div>';
                            }
                            $product_table_info[$products_count][$field] = ob_get_clean();
//                                    }
                            remove_action('woocommerce_before_quantity_input_field', 'et_quantity_minus_icon');
                            remove_action('woocommerce_after_quantity_input_field', 'et_quantity_plus_icon');
                            break;
                        case 'brand':
                            $brands = wp_get_post_terms($product_info['id'], 'brand');
                            ob_start();
                            if (!is_wp_error($brands) && count($brands) > 0) {
                                foreach ($brands as $brand) {
                                    $thumbnail_id = absint(get_term_meta($brand->term_id, 'thumbnail_id', true));
                                    echo '<a href="' . get_term_link($brand) . '">';
                                    if ($thumbnail_id)
                                        echo wp_get_attachment_image($thumbnail_id, 'full');
                                    else
                                        echo esc_html($brand->name);
                                    echo '</a>';
                                }
                            }
                            $product_table_info[$products_count][$field] = ob_get_clean();
                            unset($brands);
                            break;
                        case 'button':
                            ob_start();
//                            add_filter('esc_html', array($instance, 'escape_text'), 10, 2);
//                            add_filter('woocommerce_product_add_to_cart_text', array($instance, 'add_to_cart_icon'), 10);
                            woocommerce_template_loop_add_to_cart();
//                            remove_filter('woocommerce_product_add_to_cart_text', array($instance, 'add_to_cart_icon'), 10);
//                            remove_filter('esc_html', array($instance, 'escape_text'), 10, 2);
                            $product_table_info[$products_count][$field] = ob_get_clean();
                            break;
                        case 'excerpt':
                            ob_start();
                            echo get_the_excerpt();
                            $product_table_info[$products_count][$field] = ob_get_clean();
                            break;
                        case 'sku':
                            $has_local_sku = $has_sku && $product->get_sku();
                            ob_start();
                            if ($has_local_sku) :
                                // wp_enqueue_style('etheme-single-post-meta');
                                ?>
                                <div class="product_meta mob-hide">
                                            <span class="sku_wrapper">
                                                <span class="sku"><?php echo esc_html(($sku = $product->get_sku()) ? $sku : esc_html__('N/A', 'xstore-core')); ?></span>
                                            </span>
                                </div>
                            <?php endif;
                            $product_table_info[$products_count][$field] = ob_get_clean();
                            break;
                        case 'gtin':
                            $gtin = get_post_meta($product_info['id'], '_et_gtin', true);
                            ob_start();
                            if ($gtin) : ?>
                                <div class="product_meta mob-hide">
                                        <span class="gtin_wrapper">
                                            <span class="gtin"><?php echo esc_html($gtin); ?></span>
                                        </span>
                                </div>
                            <?php endif;
                            $product_table_info[$products_count][$field] = ob_get_clean();
                            break;
                        case 'title':
                            ob_start(); ?>
                            <a href="<?php echo esc_url($product->get_permalink()); ?>"
                               class="product-title">
                                <?php
                                echo $product->get_name();
                                ?>
                            </a>
                            <?php $product_table_info[$products_count][$field] = ob_get_clean();
                            break;
                        case 'product':
                            ob_start();
                            if (!$product->is_visible()) : ?>
                                <a class="xstore-compare-image">
                                    <?php echo str_replace(array('http:', 'https:'), '', $thumbnail) . ''; ?>
                                </a>
                            <?php else : ?>
                                <a href="<?php echo esc_url($product->get_permalink()); ?>"
                                   class="xstore-compare-image">
                                    <?php echo str_replace(array('http:', 'https:'), '', $thumbnail) . ''; ?>
                                </a>
                            <?php endif;

                            $product_table_info[$products_count][$field] = ob_get_clean();
                            break;
                        case 'price':
                            $product_table_info[$products_count][$field] = WC()->cart->get_product_price($product);
                            break;
                        case 'rating':
                            ob_start();
                            $link_ratings = round($product->get_average_rating());
                            echo '<a href="' . apply_filters('woocommerce_rating_filter_link', add_query_arg('rating_filter', $link_ratings, $shop_url)) . '">';
                            woocommerce_template_loop_rating();
                            echo '</a>';
                            $product_table_info[$products_count][$field] = ob_get_clean();
                            break;
                        case 'stock_status':
                            $availability = $product->get_availability();
                            ob_start();
                            wc_get_template(
                                'single-product/stock.php',
                                array(
                                    'product' => $product,
                                    'class' => $availability['class'],
                                    'availability' => $product->is_in_stock() ? esc_html__('In stock', 'xstore-core') : $availability['availability'],
                                )
                            );
                            $product_table_info[$products_count][$field] = ob_get_clean();
                            break;
                        case 'action':
                            ob_start(); ?>

                            <?php
                            if ($own_compare) :
                                $instance->print_button($product_info['id'], array(
                                    'only_icon' => false,
                                    'has_tooltip' => false,
                                    'custom_icon' => '<svg width="1em" height="1em" viewBox="0 0 10 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M3.44584 4.10767C3.44022 4.10669 3.4368 4.10718 3.43339 4.10742C3.27592 4.10986 3.14945 4.23999 3.15189 4.39722V10.9043C3.15067 10.9805 3.17948 11.0527 3.23295 11.1074C3.28641 11.1621 3.35795 11.1934 3.43461 11.1943H3.43851C3.59428 11.1943 3.72269 11.0684 3.72465 10.9141C3.72489 10.9097 3.72489 10.9053 3.72465 10.9043V4.39771C3.72562 4.32129 3.69706 4.24902 3.64384 4.19385C3.59037 4.13916 3.51908 4.1084 3.44584 4.10767Z" fill="currentColor"/>
                                                    <path d="M9.4351 2.28906C9.42924 2.28857 9.42533 2.28857 9.42435 2.28906L8.95267 2.28931C8.91592 2.28931 8.8945 2.28906 8.86283 2.28906H6.84818V1.79468C6.84818 1.34961 6.48588 0.987549 6.04105 0.987549H3.95902C3.51395 0.987549 3.15189 1.34961 3.15189 1.79468V2.28906L1.14042 2.28931C1.10741 2.28931 1.08699 2.28906 1.05424 2.28906H0.571082C0.496375 2.28906 0.425819 2.31763 0.371863 2.36963C0.316932 2.42334 0.28617 2.49487 0.285194 2.57104C0.283973 2.64746 0.312781 2.71997 0.366248 2.7749C0.419715 2.82983 0.491248 2.86084 0.567908 2.86182H0.809608V12.2056C0.809608 12.6504 1.17167 13.0127 1.61649 13.0127H8.38334C8.82865 13.0127 9.19096 12.6504 9.19096 12.2056V2.86182H9.42777C9.50346 2.86182 9.57474 2.83301 9.62797 2.78076C9.68265 2.72754 9.7139 2.65601 9.71488 2.57935C9.71683 2.42139 9.59086 2.29102 9.4351 2.28906ZM3.72465 2.28906V1.79468C3.72465 1.66553 3.82987 1.5603 3.95902 1.5603H6.04105C6.16996 1.5603 6.27494 1.66553 6.27494 1.79468V2.28906H3.72465ZM8.61771 2.86182V12.2056C8.61771 12.3345 8.51273 12.4395 8.38334 12.4395H1.61649C1.48734 12.4395 1.38236 12.3345 1.38236 12.2056V2.86182H3.39042C3.42216 2.8667 3.45316 2.8667 3.47953 2.86182H6.51371C6.54594 2.8667 6.5767 2.8667 6.60306 2.86182H8.61771Z" fill="currentColor"/>
                                                    <path d="M5.00736 4.10767C5.00175 4.10669 4.99833 4.10718 4.99491 4.10742C4.83744 4.10986 4.71122 4.23999 4.71366 4.39722V10.9043C4.71268 10.9805 4.74125 11.0527 4.79447 11.1079C4.84745 11.1626 4.91898 11.1929 4.99613 11.1943H5.00004C5.1558 11.1943 5.28422 11.0674 5.28641 10.9116V4.39771C5.28739 4.32104 5.25883 4.24878 5.2056 4.19385C5.15214 4.13916 5.08085 4.1084 5.00736 4.10767Z" fill="currentColor"/>
                                                    <path d="M6.27494 4.39722V10.9043C6.27396 10.9805 6.30326 11.0527 6.35648 11.1074C6.40971 11.1626 6.48148 11.1934 6.55814 11.1943H6.56156C6.71732 11.1943 6.84574 11.0674 6.84818 10.9116V4.39771C6.84916 4.32056 6.82035 4.24829 6.76713 4.19385C6.7139 4.1394 6.64359 4.10889 6.5684 4.10742C6.56449 4.10645 6.55961 4.10645 6.55668 4.10742C6.39945 4.10986 6.27299 4.23999 6.27494 4.39722Z" fill="currentColor"/>
                                                </svg>',
                                    'remove_text' => esc_html__('Delete', 'xstore-core'),
                                )); ?>
                            <?php else :
                                $instance->print_button($product_info['id'], array(
                                    'only_icon' => false,
                                    'has_tooltip' => false,
                                ));
                            endif; ?>
                            <?php
                            $product_table_info[$products_count][$field] = ob_get_clean();
                            break;
                        case 'attributes':
                            $product_attributes = $instance->get_product_attributes($product);
                            foreach ($product_attributes as $product_attribute) {
                                if (empty($product_attribute['value'])) continue;
                                $label_parsed = str_replace(' ', '-', $product_attribute['label']);
                                $product_table_info[$products_count]['attribute_' . $label_parsed] = $product_attribute['value'];
                                if (!in_array('attribute_' . $label_parsed, $compare_fields)) {
//                                            $compare_fields[] = 'attribute_' . $product_attribute['label'];
                                    // insert product attributes on the position of attributes should be shown
                                    array_splice($compare_fields, array_search($field, $compare_fields), 0, array('attribute_' . $label_parsed)); // splice in at index of attributes
                                    $fields_strings['attribute_' . $label_parsed] = $product_attribute['label'];
                                }
                            }
                            break;
                        default:
                            # code...
                            break;
                    } ?>

                    <?php endforeach; ?>

                    <?php
                    remove_filter('woocommerce_product_variation_title_include_attributes', '__return_true');
                    wp_reset_postdata();
                }

                for ($i = 1; $i <= $ghost_products_limit; $i++) {
                    $products_count++;
                    $product_table_info[$products_count] = array();
                    $product_table_info[$products_count]['id'] = 'ghost-product-'.$i;
                    $product_table_info[$products_count]['thumbnail'] = wc_placeholder_img();
                    foreach ($compare_fields as $field) :
                        if (!$show_global_actions && $field == 'checkbox') continue;
                        ?>
                        <?php switch ($field) {
                        case 'product':
                            ob_start(); ?>
                                <span class="xstore-compare-image">
                                    <?php echo str_replace(array('http:', 'https:'), '', $product_table_info[$products_count]['thumbnail']) . ''; ?>
                                </span>
                            <?php

                            $product_table_info[$products_count][$field] = ob_get_clean();
                            break;
                        default:
                            $product_table_info[$products_count][$field] = '';
                            break;
                    }

                    endforeach;
                }

                // keep the checkbox last item in the table
                unset($compare_fields[array_search('checkbox', $compare_fields)]);
                $compare_fields = array_unique(array_merge($compare_fields, array(
                    'checkbox',
                )));
                foreach ($compare_fields as $compare_field) {
                    if ($compare_field == 'attributes') continue;
                    $table_row_content = '';
                    $table_row_content_empty = true;
                    foreach ($product_table_info as $product_table_info_key) {
                        if ($table_row_content_empty && isset($product_table_info_key[$compare_field]) && !empty($product_table_info_key[$compare_field]))
                            $table_row_content_empty = false;
                        $table_row_content .=
                            '<td class="xstore-compare-' . (str_replace(' ', '-', $compare_field) . (in_array($compare_field, array('quantity', 'price', 'stock_status')) ? ' mob-hide' : '')) . '" data-product_id="' . $product_table_info_key['id'] . '">' .
                            (isset($product_table_info_key[$compare_field]) ? $product_table_info_key[$compare_field] : '').
                            '</td>';
                    }
                    if (!$table_row_content_empty && $table_row_content) {
                        echo '<tr class="xstore-compare-row-' . (str_replace(' ', '-', $compare_field) . (in_array($compare_field, array('quantity', 'price', 'stock_status')) ? ' mob-hide' : '')) . '">';
                            echo '<td class="xstore-compare-' . (str_replace(' ', '-', $compare_field)) . '">';
                                if ($compare_field == 'checkbox')
                                    echo (isset($fields_strings[$compare_field]) ? '<span class="flex align-items-center"><span><input type="checkbox" name="product-bulk-select" id="compare-products-select"></span>'.'<label class="et-element-label" for="compare-products-select">'.$fields_strings[$compare_field].'</label>' : "").'</span>';
                                else
                                    echo(isset($fields_strings[$compare_field]) ? $fields_strings[$compare_field] : '');
                            echo '</td>' . $table_row_content;
                        echo '</tr>';
                    }
                }

                if ($products_count < 1) {
                    $woo_new_7_0_1_version = function_exists('etheme_woo_version_check') && etheme_woo_version_check();
                    $button_class = '';
                    if ($woo_new_7_0_1_version) {
                        $button_class = wc_wp_theme_get_element_class_name('button');
                    } ?>
                    <tr>
                        <td colspan="<?php echo count($compare_fields); ?>">
                            <h4 style="text-align: center;"><?php echo esc_html__('It seems all products from your compare are missing on this web-site', 'xstore-core') ?></h4>
                            <p style="text-align: center;"><?php echo esc_html__('We invite you to get acquainted with an assortment of our shop. Surely you can find something for yourself!', 'xstore-core'); ?></p>
                            <?php if (wc_get_page_id('shop') > 0) : ?>
                                <p style="text-align: center;"><a
                                            class="btn black<?php echo esc_attr($button_class ? ' ' . $button_class : ''); ?>"
                                            href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><span><?php esc_html_e('Return To Shop', 'xstore-core') ?></span></a>
                                </p>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php }
                ?>
                </tbody>
            </table>
            <span class="xstore-compare-table-arrow" data-side="right"></span>
            <span class="xstore-compare-table-arrow" data-side="left"></span>
        </div>
        <div class="form-actions">
            <div class="flex flex-wrap justify-content-between">
                <?php if ($show_global_actions) : ?>
                    <div class="xstore-compare-apply-actions flex align-items-center">
                        <select>
                            <?php
                            $global_active_action = array_key_first($global_actions);
                            foreach ($global_actions as $global_action => $global_action_text) {
                                echo '<option' . ($global_active_action == $global_action ? ' selected' : '') . ' value="' . esc_attr($global_action) . '">' . $global_action_text . '</option>';
                            } ?>
                        </select>
                        <a class="btn black xstore-compare-actions"><?php echo esc_html__('Apply', 'xstore-core'); ?></a>
                    </div>
                <?php else: ?>
                    <div></div>
                <?php endif; ?>
                <div>
                    <?php if (wc_get_page_id('shop') > 0) : ?>
                        <a class="btn black compare-more-products"
                           href="<?php echo get_permalink(wc_get_page_id('shop')); ?>"><span><?php esc_html_e('Compare more products', 'xstore-core') ?></span></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </form>

<?php
unset($element_options);
