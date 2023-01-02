/**
 * Fix for Elementor in "Size Guide" popup
 *
 * @version 1.0.0
 * @since 1.0.0
 */
;(function ($) {
    "use strict";
    etTheme.autoinit.elementorInSizeGuide = function () {
        $(document).on('et_ajax_popup_loaded', function(){
            $('.et-popup-content.et-popup-content-custom-dimenstions').find('.elementor-element').each(function() {
                elementorFrontend.elementsHandler.runReadyTrigger( $( this ) );
            });
        });
    };// End of elementorInSizeGuide
})(jQuery);