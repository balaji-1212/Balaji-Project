/**
 * Description
 *
 * @package    repeater.js
 * @since      1.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */
jQuery(document).ready(function ($) {
    $('.xstore-panel-repeater .add-item').on('click', function() {
        let sortable_items = $(this).parent().find('.xstore-panel-sortable-items');
        let name = $(this).parent().find('.option-val').attr('name');
        let count_items = sortable_items.find('.sortable-item').length;
        let new_row = $(this).parent().find('.sortable-item-template').html();
        new_row = new_row.replaceAll('{{name}}', name + '_' + (count_items + 1));
        new_row = new_row.replaceAll('{{item_number}}', (count_items + 1));
        sortable_items.append(new_row);
    });
    $('.xstore-panel-repeater .remove-item').on('click', function() {
        let sortable_items = $(this).parent().find('.xstore-panel-sortable-items');
        let count_items = sortable_items.find('.sortable-item').length;
        if ( count_items <= 1) {
            alert('You cannot remove last item');
            return;
        }
        sortable_items.find('.sortable-item').last().remove();
    });
});
