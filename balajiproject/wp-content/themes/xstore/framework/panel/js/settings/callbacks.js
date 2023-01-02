/**
 * Description
 *
 * @package    callbacks.js
 * @since      1.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */
jQuery(document).ready(function ($) {
   $(document).find('[data-callbacks]').each( function (){
       var _this = $(this);
       var callbacks = $(this).attr('data-callbacks');
       var callbacks2 = callbacks.split(',');
       var values_compare = [];
       $.each(callbacks2, function() {
           var callback = this.split(':');
           values_compare = version_compare($('#'+callback[0]).val(), callback[0], callback[1], values_compare);
           $(document).on('change', '#'+callback[0], function (){
               // var check_val = $('#'+callback[0]).val();
               var check_val = this.value;
               values_compare = version_compare(check_val, callback[0], callback[1], values_compare);
               if ( values_compare.length < 1) {
                   _this.slideDown();
               }
               else {
                   _this.slideUp();
               }
           });
       });
   });
   function version_compare (first_value, first_name, needed_value, values_compare){
       if ( first_value == needed_value) {
           // delete values_compare[callback[0]];
           values_compare = $.grep(values_compare, function( a ) {
               return a !== first_name;
           });
       }
       else {
           values_compare.push(first_name);
       }
       return values_compare;
   }
});