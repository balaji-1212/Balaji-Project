/**
 * woocommerce-composite-products plugin compatibility
 *
 * @version 1.0.0
 * @since 1.0.0
 */
;(function ($) {
    "use strict";
    etTheme.autoinit.wcp_compatibility = function () {
        $(document).on('wc-composite-component-loaded', function(e){
            etTheme.global_image_lazy();
        });

        $(document).on('click', '.composited_product_images .composited_product_image', function (e) {
                e.preventDefault();
                var items = [],
                    options = {};

                if ( etTheme.et_global['w_width'] < 992 ) {
                    options = {
                        captionEl: false,
                        tapToClose: true,
                    };
                }

                var imgs = $(this).parents('.composited_product_images').find('img');

                $.each(imgs, function () {
                    items.push({
                        src: $(this).data('large_image'),
                        w: $(this).data('large_image_width'),
                        h: $(this).data('large_image_height'),
                    });
                });


                var pswpElement = document.querySelectorAll('.pswp')[0];
                var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
                gallery.init();
            });
    };
})(jQuery);