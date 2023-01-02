<?php
/**
 * @param $post_to_update_id
 * @param $import_id
 * @param $iteration
 *
 * @throws \Exception
 */
function pmwi_pmxi_do_not_update_existing($post_to_update_id, $import_id, $iteration) {
	
	if ( 'product_variation' == get_post_type($post_to_update_id) ) {
	
		$args = array(
			'post_type' => 'product_variation',
			'meta_query' => array(
				array(
					'key' => '_sku',
					'value' => get_post_meta($post_to_update_id, '_sku', true),
				)
			)
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ){
			$duplicate_id = $query->post->ID;
			if ($duplicate_id) {
				$postRecord = new PMXI_Post_Record();	
				$postRecord->clear();
				$postRecord->getBy(array(
					'post_id'   => $duplicate_id,
					'import_id' => $import_id
				));	
				if ( ! $postRecord->isEmpty() ) $postRecord->set(array('iteration' => $iteration))->update();
			}
		}	
	
	} else {
		
		$import = new PMXI_Import_Record();
		
		$import->getById($import_id);
		
		if ( in_array($import->options['matching_parent'], array('first_is_parent_id', 'first_is_variation')) ) {
			
			$postRecord = new \PMXI_Post_Record();
			$postRecord->clear();
            // Find corresponding article among previously imported.
            $postRecord->getBy(array(
                'unique_key' => 'Variation of ' . $post_to_update_id,
                'import_id'  => $import_id
            ));
            // Backward compatibility for matching first variation by  parent product SKU.
            if ($postRecord->isEmpty()) {
                $postRecord->getBy(array(
                    'unique_key' => 'Variation ' . get_post_meta($post_to_update_id, '_sku', TRUE),
                    'import_id'  => $import_id
                ));
            }

			if ( ! $postRecord->isEmpty() ) $postRecord->set(array('iteration' => $iteration))->update();
			
		}
	}
}