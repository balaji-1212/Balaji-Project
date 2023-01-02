/**
 * Description
 *
 * @package    save_action.js
 * @since      1.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

jQuery(document).ready(function ($) {

    var xstore_branding_global = {
        popup: $('.et_panel-popup'),
        closePopupIcon: '<span class="et_close-popup et-button-cancel et-button"><i class="et-admin-icon et-delete"></i></span>',
        spinner: '<div class="et-loader ">\
					<svg class="loader-circular" viewBox="25 25 50 50">\
					<circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>\
					</svg>\
				</div>',
        i: 0,
        j: 0,

        // 'dashboard_logo': $(document).find('.etheme-logo .logo-img').html(),
        // 'top_bar_logo_src': $(document).find('.toplevel_page_et-panel-welcome .wp-menu-image img').attr('src')
    };

    var xstore_branding_global_functions = {
        openPopup: function () {
            $('body').addClass('et_panel-popup-on');
            xstore_branding_global.popup.html(xstore_branding_global.spinner);
        },
        closePopup: function (response, closeIcon, refresh) {
            xstore_branding_global.popup.html('').addClass('loading');

            if ((typeof closeIcon == "boolean" && closeIcon == true) || typeof closeIcon == "undefined")
                xstore_branding_global.popup.prepend(xstore_branding_global.closePopupIcon);

            xstore_branding_global.popup.append(response.response.icon);
            xstore_branding_global.popup.append(response.response.msg);
            xstore_branding_global.popup.addClass('active').removeClass('loading');

            if (typeof refresh == "boolean" && refresh == true)
                window.location = window.location.href;
        }
    };

    // save submit action
    $('form.xstore-panel-settings').on('submit', function (e) {
        e.preventDefault();

        var tabs = [],
            all_settings = [],
            tabs_names = [];

        var settings_name = $(this).attr('data-settings-name');
        var localTabOnly = $(this).attr('data-save-tab');

        xstore_branding_global_functions.openPopup();

        $(this).parent().parent().find('.et-tabs-content').each(function () {
            var tab = $(this).attr('data-tab-content');
            if ( !!localTabOnly ) {
                if ( tab !== localTabOnly ) return;
            }
            if (tab === 'import') return;
            tabs.push(tab);
            $(this).find('.xstore-panel-sortable, .xstore-panel-repeater').each(function (){
                var positions = $(this).find('.xstore-panel-sortable-items').sortable('toArray', {
                    attribute: 'data-name'
                });
                $(this).find('.option-val').val(positions);
                // $(this).find('.option-val').val('');
            });
            var form_serialize = $(this).find('form').serializeArray();
            form_serialize = form_serialize.concat(
                $(this).find('.xstore-panel-option-switcher input[type=checkbox]:not(:checked)').map(
                    function() {
                        return {"name": this.name, "value": ''}
                    }).get()
            );
            all_settings.push(form_serialize);
        });

        ajaxSave( tabs, all_settings, settings_name);

    } );

    var ajaxSave = function ( tabs, all_settings, name ) {
        $.ajax({
            method: "POST",
            url: XStorePanelSettingsConfig.ajaxurl,
            dataType: 'JSON',
            data: {
                action: 'xstore_panel_settings_save',
                settings: all_settings[xstore_branding_global['i']],
                type: tabs[xstore_branding_global['i']],
                settings_name: name
            },
            success: function (response) {
                tabs.slice(xstore_branding_global['i'], tabs.length);
                all_settings.slice(xstore_branding_global['i'], all_settings.length);
                if ( xstore_branding_global['i'] < tabs.length ) {
                    ajaxSave( tabs, all_settings, name);
                    xstore_branding_global['i']++;
                }
                else {
                    xstore_branding_global_functions.closePopup(response);
                    xstore_branding_global['i'] = 0;
                }
            },
            error: function () {
                xstore_branding_global['i'] = 0;
                alert(XStorePanelSettingsConfig.ajaxError);
            }
        });
    };

});