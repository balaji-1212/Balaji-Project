<?php

namespace wpai_woocommerce_add_on\libraries\importer;

use WC_Product_Attribute;

require_once dirname(__FILE__) . '/ImportProduct.php';

/**
 * Import Variation Product.
 *
 * Class ImportVariationProduct
 * @package wpai_woocommerce_add_on\libraries\importer
 */
abstract class ImportVariationBase extends ImportProduct {

    /**
     * @var \WC_Product_Variation
     */
    public $product;

    /**
     * @var string
     */
    protected $productType = 'product_variation';

    /**
     * @return void
     */
    public function import() {
        parent::import();
    }

    /**
     *  Define attributes properties.
     */
    public function prepareAttributesProperties() {
        if (!$this->getImportService()->isUpdateDataAllowed('is_update_attributes', $this->isNewProduct())) {
            return TRUE;
        }
        $currentAttributes = get_post_meta($this->getProduct()->get_parent_id(), '_product_attributes', TRUE);
        $parent = new \WC_Product_Variable($this->getProduct()->get_parent_id());
        /** @var WC_Product_Attribute $attribute */
        foreach ($parent->get_attributes() as $key => $attribute) {
            // Prefill taxonomy attributes with term ids.
            if ($attribute->is_taxonomy() && isset($currentAttributes[$key]) && empty($currentAttributes[$key]['value'])) {
                $ttids = [];
                $term_ids = $attribute->get_options();
                foreach ($term_ids as $term_id) {
                    $term = get_term_by("term_id", $term_id, $attribute->get_taxonomy());
                    if ($term && !is_wp_error($term)) {
                        $ttids[] = $term->term_taxonomy_id;
                    }
                }
                $currentAttributes[$key]['value'] = implode("|", $ttids);
            }
        }
        if (empty($currentAttributes)) {
            $currentAttributes = array();
        }
        $parentAttributes = array();
        $parsedVariationAttributes = array();
        $attributes = $this->getAttributesProperties();
        /** @var WC_Product_Attribute $attribute */
        foreach ($attributes as $i => $attribute) {
            // Prepare attribute name.
            $parentAttributeName = $attribute->is_taxonomy() ? sanitize_title($attribute->get_taxonomy()) : sanitize_title($attribute->get_name());
            // Init parent attribute value.
            $parentAttributeValues = [];
            // Add attribute to array, but don't set values.
            $parentAttributes[$parentAttributeName] = array(
                'name' 			=> $attribute->get_name(),
                'value' 		=> '',
                'position' 		=> $i,
                'is_visible' 	=> $attribute->get_visible(),
                'is_variation' 	=> $attribute->get_variation(),
                'is_taxonomy' 	=> $attribute->is_taxonomy()
            );
            // Check is current attribute saved as taxonomy term.
            if ($attribute->is_taxonomy()) {
                // Get attribute terms.
                $terms = $attribute->get_terms();
                if (empty($terms)) {
                    $terms = $attribute->get_options();
                }
                if (!empty($terms)) {
                    // Collect attribute terms to assign them to parent product
                    // during call sync function
                    foreach ($terms as $term) {
                        $parentAttributeValues[] = is_int($term) ? $term : $term->term_taxonomy_id;
                    }
                    // Get first attribute term slug and assign it to variation.
                    if ($attribute->get_variation()) {
                        $term = get_term($terms[0], $attribute->get_taxonomy());
                        if (empty($term) || is_wp_error($term)) {
                            $term = get_term_by("term_taxonomy_id", $terms[0], $attribute->get_taxonomy());
                        }
                        if ($term && !is_wp_error($term)) {
                            $parsedVariationAttributes[sanitize_title($attribute->get_name())] = $term->slug;
                        }
                    }
                }
            } else {
                $parentAttributeValues = $attribute->get_options();
                if ($attribute->get_variation()) {
                    $parsedVariationAttributes[sanitize_title($attribute->get_name())] = $attribute->get_data()['value'];
                }
            }

            // Collect variation attribute values in _product_attributes.
            if ($parentAttributeValues) {
                if (isset($currentAttributes[sanitize_title($attribute->get_name())])) {
                    $options = explode("|", $currentAttributes[sanitize_title($attribute->get_name())]['value']);
                    $options = array_map('trim', $options);
                    foreach ($parentAttributeValues as $value) {
                        if (!in_array($value, $options)) {
                            $options[] = $value;
                        }
                    }
                    $parentAttributes[sanitize_title($attribute->get_name())]['value'] = implode("|", array_filter($options));
                } else {
                    $parentAttributes[sanitize_title($attribute->get_name())]['value'] = implode("|", $parentAttributeValues);
                }
            }
        }
        $variationAttributes = array();
        $attributes = $this->getProduct()->get_attributes();
        $attributes = array_filter($attributes);
        foreach ($attributes as $attributeName => $attribute_value) {
            $isAddNew = FALSE;
            // Don't touch existing attributes, add new attributes.
            if ( ! $this->isNewProduct() && $this->getImport()->options['update_all_data'] == "no" && $this->getImport()->options['is_update_attributes'] && $this->getImport()->options['update_attributes_logic'] == 'add_new') {
                if (isset($currentAttributes[$attributeName])) {
                    $isAddNew = TRUE;
                }
            }
            if (!$this->getImportService()->isUpdateAttribute($attributeName, $this->isNewProduct()) || $isAddNew) {
                $variationAttributes[$attributeName] = $attribute_value;
            }
        }
        $variationAttributes = array_merge($parsedVariationAttributes, $variationAttributes);
        $this->setProperty('attributes', $variationAttributes);
        // Sort & merge parent attributes with attributes from variation.
        $parsedAttributes = $this->getAttributesData();
        $parentAttributes = array_merge($currentAttributes, $parentAttributes);
        foreach ($parsedAttributes['attribute_names'] as $position => $attribute_slug) {
            if (isset($parentAttributes[$attribute_slug])) {
                $parentAttributes[$attribute_slug]['position'] = $position;
            }
        }
        update_post_meta($this->getProduct()->get_parent_id(), '_product_attributes', $parentAttributes);
    }
}