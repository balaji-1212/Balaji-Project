/**
 * Fix for WooCommerce select2 when select have less then 5 items
 *
 * @version 1.0.0
 * @since 1.0.0
 */
;(function ($) {
    "use strict";
    etTheme.autoinit.WooSelect2Fix = function () {
        $('.woocommerce-widget-layered-nav-dropdown').on('select2:open', function (e) {
            $(this).addClass('et_just-opened');
            setTimeout(function(){
                $('.et_just-opened').removeClass('et_just-opened');
            }, 150);
        }).on('select2:selecting', function (e) {
            if ($(this).hasClass('et_just-opened')){
                e.preventDefault();
            }
        }).on('select2:closing', function (e) {
            if ($(this).hasClass('et_just-opened')){
                e.preventDefault();
            }
        });
    };// End of WooSelect2Fix
})(jQuery);