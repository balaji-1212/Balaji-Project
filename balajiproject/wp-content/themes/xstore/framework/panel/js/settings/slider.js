/**
 * Description
 *
 * @package    slider.js
 * @since      1.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */
jQuery(document).ready(function ($) {
    $('form.xstore-panel-settings .xstore-panel-option-slider input[type=range]').on("input change", function () {
        $(this).parent().find('.value').text($(this).val());
    });
});