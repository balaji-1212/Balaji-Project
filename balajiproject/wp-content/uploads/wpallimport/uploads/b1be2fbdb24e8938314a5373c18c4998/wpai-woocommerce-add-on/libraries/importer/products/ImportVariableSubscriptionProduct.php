<?php

namespace wpai_woocommerce_add_on\libraries\importer;

require_once dirname(__FILE__) . '/ImportProduct.php';
require_once dirname(__FILE__) . '/ImportVariableProduct.php';

/**
 *
 * Import Variable Subscription Product
 *
 * Class ImportVariableSubscriptionProduct
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportVariableSubscriptionProduct extends ImportVariableProduct {

    /**
     * Simple subscription meta data.
     *
     * @var array
     */
    protected $meta = [
        'subscription_price',
        'subscription_sign_up_fee',
        'subscription_period',
        'subscription_period_interval',
        'subscription_trial_period',
        'subscription_length',
        'subscription_limit',
    ];

    /**
     * @var string
     */
    protected $productType = 'variable-subscription';

    /**
     * @return void
     */
    public function import() {
        parent::import();
    }

    /**
     *  Define general properties for grouped product.
     */
    public function prepareGeneralProperties() {
        parent::prepareGeneralProperties();
        // Set simple subscription product meta data.
        foreach ($this->meta as $meta) {
            if ($this->isNewProduct() || $this->getImportService()->isUpdateCustomField($this->getPropertyMetaKey($meta))) {
                $value = $this->getValue('product_' . $meta);
                if ($meta == 'subscription_length' && strpos($value, '-') !== FALSE) {
                    list($period, $value) = explode('-', $value);
                }
                if ($meta == 'subscription_trial_period' && strpos($value, '-') !== FALSE) {
                    list($period, $value) = explode('-', $value);
                    update_post_meta($this->getPid(), $this->getPropertyMetaKey('subscription_trial_length'), $period);
                }
                update_post_meta($this->getPid(), $this->getPropertyMetaKey($meta), $value);
            }
        }
    }
}