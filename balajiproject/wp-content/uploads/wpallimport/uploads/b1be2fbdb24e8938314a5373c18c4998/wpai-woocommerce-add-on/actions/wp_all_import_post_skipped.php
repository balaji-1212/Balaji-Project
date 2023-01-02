<?php

/**
 * @param $pid
 * @param $import_id
 * @param $current_xml_node
 */
function pmwi_wp_all_import_post_skipped($pid, $import_id, $current_xml_node) {
    if (empty($pid)) {
        return;
    }
    $import = new PMXI_Import_Record();
    $import->getById($import_id);
    if (!$import->isEmpty() and in_array($import->options['custom_type'], array(
            'product',
            'product_variation'
        ))
    ) {
        // Update variations iteration when parent product skipped.
        if ('product' == get_post_type($pid)) {
            $product = new WC_Product_Variable($pid);
            $variation_ids = $product->get_children();
            if (!empty($variation_ids)) {
                foreach ($variation_ids as $variation_id) {
                    $postRecord = new \PMXI_Post_Record();
                    $postRecord->clear();
                    $postRecord->getBy([
                        'post_id' => $variation_id,
                        'import_id' => $import_id
                    ]);
                    if (!$postRecord->isEmpty()) {
                        $postRecord->set(array('iteration' => $import->iteration))
                            ->update();
                    }
                }
            }
        }
    }
}