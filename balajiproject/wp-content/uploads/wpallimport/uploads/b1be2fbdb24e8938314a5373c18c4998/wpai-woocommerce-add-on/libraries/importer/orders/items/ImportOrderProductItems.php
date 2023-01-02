<?php

namespace wpai_woocommerce_add_on\libraries\importer;

/**
 * Class ImportOrderProductItems
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportOrderProductItems extends ImportOrderItemsBase {

    /**
     * @var int
     */
    public $prices_include_tax = 0;

    public $tax_rates = array();

    /**
     *  Importing fee items
     */
    public function import() {

        $this->prices_include_tax = ('yes' === get_option('woocommerce_prices_include_tax', 'no'));

        $tax_classes = \WC_Tax::get_tax_classes();

        if ($tax_classes) {
            // Add Standard tax class
            if (!in_array('', $tax_classes)) {
                $tax_classes[] = '';
            }

            foreach ($tax_classes as $class) {
                foreach (\WC_Tax::get_rates_for_tax_class(sanitize_title($class)) as $rate_key => $rate) {
                    $this->tax_rates[$rate->tax_rate_id] = $rate;
                }
            }
        }

        if ($this->isNewOrder() || $this->getImport()->options['update_all_data'] == 'yes' || $this->getImport()->options['is_update_products']) {
            if (!$this->isNewOrder() && ($this->getImport()->options['update_all_data'] == 'yes' || $this->getImport()->options['is_update_products'] && $this->getImport()->options['update_products_logic'] == 'full_update')) {
                $previously_updated_order = get_option('wp_all_import_previously_updated_order_' . $this->getImport()->id, FALSE);
                if (empty($previously_updated_order) || $previously_updated_order != $this->getArticleData('ID')) {
                    $this->getOrder()->remove_order_items('line_item');
                    $this->wpdb->query($this->wpdb->prepare("DELETE FROM {$this->wpdb->prefix}pmxi_posts WHERE import_id = %d AND post_id = %d AND unique_key LIKE %s;", $this->getImport()->id, $this->getOrderID(), '%' . $this->wpdb->esc_like('line-item') . '%'));
                }
            }
            $this->_import_line_items();
        }
    }

    /**
     * @return bool
     */
    protected function _import_line_items() {

        $is_product_founded = FALSE;

        switch ($this->getImport()->options['pmwi_order']['products_source']) {
            // Get data from existing products
            case 'existing':

                foreach ($this->getValue('products') as $productIndex => $productItem) {

                    if (empty($productItem['sku']) || empty($productItem['qty'])) {
                        continue;
                    }

                    $args = [
                        'post_type' => 'product',
                        'meta_key' => '_sku',
                        'meta_value' => $productItem['sku'],
                        'meta_compare' => '=',
                    ];

                    $product = FALSE;

                    $query = new \WP_Query($args);
                    while ($query->have_posts()) {
                        $query->the_post();
                        $product = WC()->product_factory->get_product($query->post->ID);
                        break;
                    }
                    wp_reset_postdata();

                    if (empty($product)) {
                        $args['post_type'] = 'product_variation';
                        $query = new \WP_Query($args);
                        while ($query->have_posts()) {
                            $query->the_post();
                            $product = WC()->product_factory->get_product($query->post->ID);
                            break;
                        }
                        wp_reset_postdata();
                    }

                    if ($product) {

                        $is_product_founded = TRUE;
                        $item_price = empty($productItem['price_per_unit']) ? $product->get_price() : $productItem['price_per_unit'];
                        $item_qty = $productItem['qty'];
                        $item_subtotal = (float) $item_price * (int) $item_qty;
                        $item_subtotal_tax = 0;
                        $line_taxes = array();

                        foreach ($productItem['tax_rates'] as $key => $tax_rate) {
                            if (empty($tax_rate['code'])) {
                                continue;
                            }

                            $tax_class = FALSE;
                            if (!empty($this->tax_rates[$tax_rate['code']])) {
                                $tax_class = $this->tax_rates[$tax_rate['code']];
                            }
                            else {
                                foreach ($this->tax_rates as $rate_id => $rate) {
                                    if (strtolower($rate->tax_rate_name) == strtolower($tax_rate['code']) || strtolower($rate->tax_rate_class) == strtolower($tax_rate['code'])) {
                                        $tax_class = $rate;
                                        break;
                                    }
                                }
                            }

                            $line_tax = 0;

                            switch ($tax_rate['calculate_logic']) {
                                case 'percentage':

                                    if (!empty($tax_rate['percentage_value']) and is_numeric($tax_rate['percentage_value'])) {
                                        $line_tax = \WC_Tax::round(($item_subtotal / 100) * $tax_rate['percentage_value']);
                                        $item_subtotal_tax += $line_tax;
                                    }
                                    break;

                                case 'per_unit';

                                    if (!empty($tax_rate['amount_per_unit']) and is_numeric($tax_rate['amount_per_unit'])) {
                                        $line_tax = \WC_Tax::round($tax_rate['amount_per_unit'] * $item_qty);
                                        $item_subtotal_tax += $line_tax;
                                    }
                                    break;

                                // Look up tax rate code
                                default:

                                    $found_rates = [];
                                    foreach ($this->tax_rates as $tax_rate_object) {
                                        if (strtolower($tax_rate_object->tax_rate_name) == strtolower($tax_rate['code'])) {
                                            $found_rates[] = $tax_rate_object;
                                        }
                                    }

                                    if (!empty($found_rates)) {
                                        $matched_tax_rates = array();
                                        $found_priority = array();

                                        foreach ($found_rates as $found_rate) {
                                            if (in_array($found_rate->tax_rate_priority, $found_priority)) {
                                                continue;
                                            }

                                            $matched_tax_rates[$found_rate->tax_rate_id] = array(
                                                'rate' => $found_rate->tax_rate,
                                                'label' => $found_rate->tax_rate_name,
                                                'shipping' => $found_rate->tax_rate_shipping ? 'yes' : 'no',
                                                'compound' => $found_rate->tax_rate_compound ? 'yes' : 'no'
                                            );

                                            $found_priority[] = $found_rate->tax_rate_priority;
                                        }
                                        $line_tax = array_sum(\WC_Tax::calc_tax($item_subtotal, $matched_tax_rates, TRUE));
                                        $item_subtotal_tax += $line_tax;
                                    }

                                    break;
                            }

                            if ($tax_class) {
                                $line_taxes[$tax_class->tax_rate_id] = $line_tax;
                            }
                        }

                        $variation = array();

                        $variation_str = '';

                        if ($product instanceOf \WC_Product_Variation) {
                            $variation = $product->get_variation_attributes();

                            if (!empty($variation)) {
                                foreach ($variation as $key => $value) {
                                    $variation_str .= $key . '-' . $value;
                                }
                            }
                        }

                        $product_item_unique_key = 'line-item-' . $product->get_id() . '-' . $variation_str;
                        if (!empty($productItem['unique_key'])) {
                            $product_item_unique_key .= $productItem['unique_key'];
                        }

                        $product_item = new \PMXI_Post_Record();
                        $product_item->getBy(array(
                            'import_id' => $this->getImport()->id,
                            'post_id' => $this->getOrderID(),
                            'unique_key' => $product_item_unique_key
                        ));

                        if ($product_item->isEmpty()) {
                            $item_id = FALSE;

                            // in case when this is new order just add new line items
                            if (!$item_id) {
                                $item_id = $this->getOrder()->add_product(
                                    $product,
                                    $item_qty,
                                    array(
                                        'variation' => $variation,
                                        'totals' => array(
                                            'subtotal' => $item_subtotal,
                                            'subtotal_tax' => $item_subtotal_tax,
                                            'total' => $item_subtotal,
                                            'tax' => $item_subtotal_tax,
                                            'tax_data' => array(
                                                'total' => $line_taxes,
                                                'subtotal' => $line_taxes
                                            ) // Since 2.2
                                        )
                                    )
                                );
                            }

                            if (!$item_id) {
                                $this->getLogger() and call_user_func($this->getLogger(), __('- <b>WARNING</b> Unable to create order line product.', \PMWI_Plugin::TEXT_DOMAIN));
                            } else {
                                $product_item->set(array(
                                    'import_id' => $this->getImport()->id,
                                    'post_id' => $this->getOrderID(),
                                    'unique_key' => $product_item_unique_key,
                                    'product_key' => 'line-item-' . $item_id,
                                    'iteration' => $this->getImport()->iteration
                                ))->save();

                                if (!empty($productItem['meta_name'])) {
                                    foreach ($productItem['meta_name'] as $key => $meta_name) {
                                        wc_add_order_item_meta($item_id, $meta_name, isset($productItem['meta_value'][$key]) ? $productItem['meta_value'][$key] : '');
                                    }
                                }
                            }
                        } else {
                            $item_id = str_replace('line-item-', '', $product_item->product_key);
                            $is_updated = $this->getOrder()->update_product(
                                $item_id,
                                $product,
                                array(
                                    'qty' => $item_qty,
                                    'tax_class' => $product->get_tax_class(),
                                    'totals' => array(
                                        'subtotal' => $item_subtotal,
                                        'subtotal_tax' => $item_subtotal_tax,
                                        'total' => $item_subtotal,
                                        'tax' => $item_subtotal_tax,
                                        'tax_data' => array(
                                            'total' => $line_taxes,
                                            'subtotal' => $line_taxes
                                        ) // Since 2.2
                                    ),
                                    'variation' => $variation
                                )
                            );
                            if ($is_updated) {
                                $product_item->set(array(
                                    'iteration' => $this->getImport()->iteration
                                ))->save();

                                if (!empty($productItem['meta_name'])) {
                                    foreach ($productItem['meta_name'] as $key => $meta_name) {
                                        wc_update_order_item_meta($item_id, $meta_name, isset($productItem['meta_value'][$key]) ? $productItem['meta_value'][$key] : '');
                                    }
                                }
                            }
                        }
                    }
                }

                break;

            // Manually import product order data and do not try to match to existing products
            default:

                $is_product_founded = TRUE;

                foreach ($this->getValue('manual_products') as $productIndex => $productItem) {

                    if (empty($productItem['sku']) || empty($productItem['qty'])) {
                        continue;
                    }

                    $item_price = $productItem['price_per_unit'];
                    $item_qty = $productItem['qty'];
                    $item_subtotal = $item_price * $item_qty;
                    $item_subtotal_tax = 0;
                    $line_taxes = array();

                    foreach ($productItem['tax_rates'] as $key => $tax_rate) {
                        if (empty($tax_rate['code'])) {
                            continue;
                        }

                        $tax_class = FALSE;
                        if (!empty($this->tax_rates[$tax_rate['code']])) {
                            $tax_class = $this->tax_rates[$tax_rate['code']];
                        } else {
                            foreach ($this->tax_rates as $rate_id => $rate) {
                                if (strtolower($rate->tax_rate_name) == strtolower($tax_rate['code']) || strtolower($rate->tax_rate_class) == strtolower($tax_rate['code'])) {
                                    $tax_class = $rate;
                                    break;
                                }
                            }
                        }

                        $line_tax = 0;

                        switch ($tax_rate['calculate_logic']) {
                            case 'percentage':
                                if (!empty($tax_rate['percentage_value']) and is_numeric($tax_rate['percentage_value'])) {
                                    $line_tax = \WC_Tax::round(($item_subtotal / 100) * $tax_rate['percentage_value']);
                                    $item_subtotal_tax += $line_tax;
                                }
                                break;
                            case 'per_unit';
                                if (!empty($tax_rate['amount_per_unit']) and is_numeric($tax_rate['amount_per_unit'])) {
                                    $line_tax = \WC_Tax::round($tax_rate['amount_per_unit'] * $item_qty);
                                    $item_subtotal_tax += $line_tax;
                                }
                                break;
                            // Look up tax rate code
                            default:
                                $found_rates = [];
                                foreach ($this->tax_rates as $tax_rate_object) {
                                    if (strtolower($tax_rate_object->tax_rate_name) == strtolower($tax_rate['code'])) {
                                        $found_rates[] = $tax_rate_object;
                                    }
                                }

                                if (!empty($found_rates)) {
                                    $matched_tax_rates = array();
                                    $found_priority = array();

                                    foreach ($found_rates as $found_rate) {
                                        if (in_array($found_rate->tax_rate_priority, $found_priority)) {
                                            continue;
                                        }

                                        $matched_tax_rates[$found_rate->tax_rate_id] = array(
                                            'rate' => $found_rate->tax_rate,
                                            'label' => $found_rate->tax_rate_name,
                                            'shipping' => $found_rate->tax_rate_shipping ? 'yes' : 'no',
                                            'compound' => $found_rate->tax_rate_compound ? 'yes' : 'no'
                                        );

                                        $found_priority[] = $found_rate->tax_rate_priority;
                                    }
                                    $line_tax = array_sum(\WC_Tax::calc_tax($item_subtotal, $matched_tax_rates, TRUE));
                                    $item_subtotal_tax += $line_tax;
                                }
                                break;
                        }

                        if ($tax_class) {
                            $line_taxes[$tax_class->tax_rate_id] = $line_tax;
                        }
                    }

                    $product_item_unique_key = 'manual-line-item-' . $productIndex . '-' . $productItem['sku'];
                    if (!empty($productItem['unique_key'])) {
                        $product_item_unique_key .= $productItem['unique_key'];
                    }

                    $product_item = new \PMXI_Post_Record();
                    $product_item->getBy(array(
                        'import_id' => $this->getImport()->id,
                        'post_id' => $this->getOrderID(),
                        'unique_key' => $product_item_unique_key
                    ));

                    if ($product_item->isEmpty()) {
                        $item_id = wc_add_order_item($this->getOrderID(), array(
                            'order_item_name' => $productItem['sku'],
                            'order_item_type' => 'line_item'
                        ));

                        if (!$item_id) {
                            $this->getLogger() and call_user_func($this->getLogger(), __('- <b>WARNING</b> Unable to create order line product.', \PMWI_Plugin::TEXT_DOMAIN));
                        } else {
                            wc_add_order_item_meta($item_id, '_qty', wc_stock_amount($item_qty));
                            wc_add_order_item_meta($item_id, '_tax_class', '');

                            wc_add_order_item_meta($item_id, '_line_subtotal', wc_format_decimal($item_subtotal));
                            wc_add_order_item_meta($item_id, '_line_total', wc_format_decimal($item_subtotal));
                            wc_add_order_item_meta($item_id, '_line_subtotal_tax', wc_format_decimal($item_subtotal_tax));
                            wc_add_order_item_meta($item_id, '_line_tax', wc_format_decimal($item_subtotal_tax));
                            wc_add_order_item_meta($item_id, '_line_tax_data', array(
                                'total' => $line_taxes,
                                'subtotal' => $line_taxes
                            ));

                            if (!empty($productItem['meta_name'])) {
                                foreach ($productItem['meta_name'] as $key => $meta_name) {
                                    wc_add_order_item_meta($item_id, $meta_name, isset($productItem['meta_value'][$key]) ? $productItem['meta_value'][$key] : '');
                                }
                            }

                            $product_item->set(array(
                                'import_id' => $this->getImport()->id,
                                'post_id' => $this->getOrderID(),
                                'unique_key' => $product_item_unique_key,
                                'product_key' => 'manual-line-item-' . $item_id,
                                'iteration' => $this->getImport()->iteration
                            ))->save();
                        }
                    } else {
                        $item_id = str_replace('manual-line-item-', '', $product_item->product_key);

                        if (is_numeric($item_id)) {
                            wc_update_order_item($item_id, array(
                                'order_item_name' => $productItem['sku'],
                                'order_item_type' => 'line_item'
                            ));

                            wc_update_order_item_meta($item_id, '_qty', wc_stock_amount($item_qty));
                            wc_update_order_item_meta($item_id, '_tax_class', '');

                            wc_update_order_item_meta($item_id, '_line_subtotal', wc_format_decimal($item_subtotal));
                            wc_update_order_item_meta($item_id, '_line_total', wc_format_decimal($item_subtotal));
                            wc_update_order_item_meta($item_id, '_line_subtotal_tax', wc_format_decimal($item_subtotal_tax));
                            wc_update_order_item_meta($item_id, '_line_tax', wc_format_decimal($item_subtotal_tax));
                            wc_update_order_item_meta($item_id, '_line_tax_data', array(
                                'total' => $line_taxes,
                                'subtotal' => $line_taxes
                            ));

                            if (!empty($productItem['meta_name'])) {
                                foreach ($productItem['meta_name'] as $key => $meta_name) {
                                    wc_update_order_item_meta($item_id, $meta_name, isset($productItem['meta_value'][$key]) ? $productItem['meta_value'][$key] : '');
                                }
                            }

                            $product_item->set(array(
                                'iteration' => $this->getImport()->iteration
                            ))->save();
                        }
                    }
                }
                break;
        }
        return $is_product_founded;
    }
}