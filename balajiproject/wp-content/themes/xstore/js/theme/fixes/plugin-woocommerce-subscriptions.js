/**
 * Rewrite Buy Now Button (Xstore theme js function) fix for woocommerce-subscriptions plugin compatibility
 */
;(function ($) {
    "use strict";
    /**
     * Buy Now Button
     *
     * @version 1.0.0
     * @since 7.0.3
     */
    // if (etConfig['woocommerceSettings']['is_woocommerce'] && etConfig['woocommerceSettings']['buy_now_btn']) {
        etTheme.autoinit.buyNowBtn = etTheme.buyNowBtn = function () {
            // old code from parent theme
            // $(document).on('click', '.et-single-buy-now', function(e){
            //     $(this).closest('form').append('<input type="hidden" name="et_buy_now" value="true">');
            // });

            // new code from child theme (fix for woo subscription plugin)
            $(document).on('click', '.et-single-buy-now', function(e){
                if ( $(this).hasClass('et-buy-now-clicked') ) {
                    e.preventDefault();
                    return;
                }
                $(this).closest('form').append('<input type="hidden" name="et_buy_now" value="true">');
                if ( $(this).parents('.product-type-subscription').length ) {
                    e.preventDefault();
                    $(this).closest('form').find('[name="add-to-cart"]').trigger('click');
                }
                $(this).addClass('et-buy-now-clicked');
            });

        };// End of buyNowBtn
    // };
})(jQuery);