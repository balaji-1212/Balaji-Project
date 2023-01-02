<?php

namespace wpai_woocommerce_add_on\libraries\importer;

require_once dirname(__FILE__) . '/ImportVariationBase.php';

/**
 * Import Variation Product.
 *
 * Class ImportVariationProduct
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportVariationProduct extends ImportVariationBase {

    /**
     * Set variation properties.
     *
     * @return mixed
     */
    public function setProperties() {
        // Set variation description.
        $this->setProperty('description', $this->getValue('product_variation_description'));
        // Is variation enabled.
        if ($this->getImportService()->isUpdateDataAllowed('is_update_status', $this->isNewProduct())) {
            $post_status = $this->getValue('product_enabled') == 'yes' ? 'publish' : 'private';
            $this->product->set_status($post_status);
        }
        if ($this->getImportService()->isUpdateDataAllowed('is_update_attributes', $this->isNewProduct())) {
            // Force updating variation attributes.
            $attributes = $this->product->get_attributes();
            if (!empty($attributes)) {
                foreach ($attributes as $attribute_name => $attribute_value) {
                    if ($this->getImportService()->isUpdateAttribute($attribute_name, $this->isNewProduct())) {
                        unset($attributes[$attribute_name]);
                    }
                }
            }
            $this->product->set_attributes($attributes);
        }
        // Set variation basic properties.
        parent::setProperties();
    }
}