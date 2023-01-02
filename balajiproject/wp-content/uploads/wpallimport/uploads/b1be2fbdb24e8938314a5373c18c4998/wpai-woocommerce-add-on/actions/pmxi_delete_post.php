<?php

/**
 * @param $ids
 * @param $import
 */
function pmwi_pmxi_delete_post($ids, $import) {
    if (!empty($ids)) {
        foreach ($ids as $pid) {
            $post_type = get_post_type($pid);
            switch ($post_type) {
                case 'product_variation':
                    $variation = new \WC_Product_Variation($pid);
                    // Add parent product to sync circle after import completed.
                    $productStack = get_option('wp_all_import_product_stack_' . XmlImportWooCommerceService::getInstance()->getImport()->id, array());
                    if (!in_array($variation->get_parent_id(), $productStack)) {
                        $productStack[] = $variation->get_parent_id();
                        update_option('wp_all_import_product_stack_' . XmlImportWooCommerceService::getInstance()->getImport()->id, $productStack);
                    }
                    // Clean up lookup table if product was deleted via cron.
                    if (!empty($_GET['import_id'])) {
                        $data_store = WC_Data_Store::load( 'product' );
                        $data_store->delete_from_lookup_table( $pid, 'wc_product_meta_lookup' );
                        wc_delete_product_transients( wp_get_post_parent_id( $pid ) );
                    }
                    break;
                case 'product':
                    // Clean up lookup table if product was deleted via cron.
                    if (!empty($_GET['import_id'])) {
                        $data_store = WC_Data_Store::load('product-variable');
                        $data_store->delete_variations($pid, true);
                        $data_store->delete_from_lookup_table($pid, 'wc_product_meta_lookup');
                        $parent_id = wp_get_post_parent_id($pid);

                        if ($parent_id) {
                            wc_delete_product_transients($parent_id);
                        }
                    }
                    break;
                case 'shop_order':
                    // Clean up lookup table if product was deleted via cron.
                    if (!empty($_GET['import_id'])) {
                        global $wpdb;
                        $refunds = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type = 'shop_order_refund' AND post_parent = %d", $pid ) );
                        if ( ! is_null( $refunds ) ) {
                            foreach ( $refunds as $refund ) {
                                wp_delete_post( $refund->ID, true );
                            }
                        }
                    }
                    break;
                default:
                    break;
            }
        }
    }
}