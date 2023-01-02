<?php

namespace wpai_woocommerce_add_on\libraries\importer;

require_once dirname(__FILE__) . '/ImportProduct.php';

/**
 *
 * Import Simple Subscription Product
 *
 * Class ImportSimpleSubscriptionProduct
 * @package wpai_woocommerce_add_on\libraries\importer
 */
class ImportSimpleSubscriptionProduct extends ImportProduct {

    /**
     * Simple subscription meta data.
     *
     * @var array
     */
    protected $meta = [
        'subscription_price',
        'subscription_sign_up_fee',
        'subscription_trial_period',
        'subscription_period',
        'subscription_period_interval',
        'subscription_length',
        'subscription_limit',
    ];

    /**
     * @var string
     */
    protected $productType = 'subscription';

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
        // Import prices.
        $regular_price = $price = wc_clean( $this->getValue('product_subscription_price') );
        $sale_price = wc_clean( $this->getValue('product_sale_price') );
        if (!empty($sale_price)) {
            $price = $sale_price;
        }
        $this->setProperty('regular_price', $regular_price);
        $this->setProperty('price', $price);
        // Set simple subscription product meta data.
        foreach ($this->meta as $meta) {
            if ($this->isNewProduct() || $this->getImportService()->isUpdateCustomField($this->getPropertyMetaKey($meta))) {
                $value = $this->getValue('product_' . $meta);
                // reformat values
                if ($meta == 'subscription_length' && strpos($value, '-') !== FALSE) {
                    list($period, $value) = explode('-', $value);
                }elseif( $meta == 'subscription_length'){
                    // remove the trailing 's' and extraneous whitespace
                    $value = trim($value, "\ss");
                    // if there's not a space between the number and the interval then skip this
                    if( strpos($value, " ") !== FALSE ){
                        // the order is reversed when submitted via xpath
                        list($value, $period) = explode(' ', $value);
                    }
                }
                if ($meta == 'subscription_trial_period' && strpos($value, '-') !== FALSE) {
                    list($value, $trial_length) = explode('-', $value);
                    update_post_meta($this->getPid(), $this->getPropertyMetaKey('subscription_trial_length'), $trial_length);
                }elseif($meta == 'subscription_trial_period'){
                    // remove the trailing 's' and extraneous whitespace
                    $value = trim($value, "\ss");
                    // if there's not a space between the number and the interval then skip this
                    if( strpos($value, " ") !== FALSE ){
                        // the order is reversed when submitted via xpath
                        list($trial_length, $value) = explode(' ', $value);
                        update_post_meta($this->getPid(), $this->getPropertyMetaKey('subscription_trial_length'), $trial_length);
                    }
                }
                update_post_meta($this->getPid(), $this->getPropertyMetaKey($meta), $value);
            }
        }
    }
}