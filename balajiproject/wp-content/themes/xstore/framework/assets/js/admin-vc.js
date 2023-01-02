jQuery(document).ready(function ($) {
    $(document).ready(function () {
        setTimeout(function () {
            $('.et-tab-label.vc_tta-section-append').removeClass('vc_tta-section-append').addClass('et-tab-append');
        }, 1000);
    });
    $(document).on('click', '#et_tabs', function (event) {
        setTimeout(function () {
            $('.et-tab-label.vc_tta-section-append').removeClass('vc_tta-section-append').addClass('et-tab-append');
        }, 1000);
    });
    $(document).on('click', '.et-tab-label.et-tab-append', function (event) {
        if (typeof vc == 'undefined') return;

        var newTabTitle = 'Tab',
            params,
            shortcode,
            modelId = $(this).parents('.wpb_et_tabs').data('model-id'),
            prepend = false;

        params = {
            shortcode: "et_tab",
            params: {
                title: newTabTitle
            },
            parent_id: modelId,
            order: _.isBoolean(prepend) && prepend ? vc.add_element_block_view.getFirstPositionIndex() : vc.shortcodes.getNextOrder(),
            prepend: prepend
        }
        shortcode = vc.shortcodes.create(params);
    });
});