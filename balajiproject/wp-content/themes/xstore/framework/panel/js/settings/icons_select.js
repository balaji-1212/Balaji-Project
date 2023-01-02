/**
 * Description
 *
 * @package    icons_select.js
 * @since      1.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */
jQuery(document).ready(function ($) {
    $(document).on('change', 'form.xstore-panel-settings .xstore-panel-option-icons-select select', function () {
        $(this).parent().find('.xstore-panel-option-icon-preview .et-icon').attr('class', 'et-icon ' + $(this).val().replace('et_icon', 'et'));
    });
});