/**
 * Description
 *
 * @package    colorpicker.js
 * @since      1.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

jQuery(document).ready(function ($) {
    var colorPickerOptions = {
        change: function (event, ui) {
            setTimeout(function () {
                if (event.originalEvent) {
                    $(event.target).trigger('change');
                    $(document).find('#et_branding-live-css').append(':root{--et_admin_'+$(event.target).data('css-var')+': '+$(event.target).val()+' !important;}');
                }
            }, 1);
        },
        clear: function() {
            var default_val =  defaultColor = $(this).parent().find('.color-field').data('default'),
                css_var =  $(this).parent().find('.color-field').data('css-var');
            $(document).find('#et_branding-live-css').append(':root{--et_admin_'+css_var+': '+default_val+' !important;}');
        }
    };

    $('form.xstore-panel-settings .color-field').wpColorPicker(colorPickerOptions);
});