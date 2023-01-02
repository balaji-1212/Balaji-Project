/**
 * Description
 *
 * @package    tabs.js
 * @since      1.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */
jQuery(document).ready(function ($) {
    $(document).on('click', '.xstore-panel-option-tab .tab-title', function () {
        $(this).parent().toggleClass('opened');
    });
} );
