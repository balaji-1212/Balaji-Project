/**
 * Description
 *
 * @package    switch.js
 * @since      1.0.0
 * @author     andrey
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */
jQuery(document).ready(function ($) {
    $(document).on('change', '.xstore-panel-option-switcher input[type="checkbox"]', function () {
        // $(this).parent().toggleClass('opened');
        if ( $(this).is(':checked')) {
            $(this).val('on');
        }
        else {
            $(this).val('');
        }
    });
});
