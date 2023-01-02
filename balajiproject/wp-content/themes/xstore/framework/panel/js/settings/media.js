/**
 * Description
 *
 * @package    media.js
 * @since      1.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */
jQuery(document).ready(function ($) {
    $(document).on('click', 'form.xstore-panel-settings .button-upload-file', function (e) {
        var fileUploader = '',
            title = $(this).data('title'),
            buttonTitle = $(this).data('button-title'),
            setting = $(this).data('option-name'),
            removeButton = $(this).next('.button-remove-file'),
            parent = $(this).parents('.et-tabs-content'),
            fileType = $(this).data('file-type'),
            saveAs = $(this).data('save-as'),
            attachment,
            saveValue;

        e.preventDefault();

        fileUploader = wp.media({
            title: title,
            button: {
                text: buttonTitle
            },
            multiple: false,  // Set this to true to allow multiple files to be selected.
            library:
                {
                    type: [fileType]
                }
        })
            .on('select', function () {
                attachment = fileUploader.state().get('selection').first().toJSON();
                saveValue = attachment.url;
                // if ( saveAs == 'id' ) {
                //     saveValue = attachment.id
                // }
                if ($.inArray(fileType, ['image', 'image/svg+xml']) > -1) {
                    $(parent).find('.' + setting + '_preview').html('<img src="' + attachment.url + '">');
                } else if (fileType == 'audio') {
                    $(parent).find('.' + setting + '_preview').html('<img src="' + XStorePanelSettingsConfig.audioPlaceholder + '">');
                }
                $(parent).find('#' + setting).val(saveValue).trigger('change');
                removeButton.show();
            })
            .open();
    });

    $(document).on('click', 'form.xstore-panel-settings .button-remove-file', function (e) {
        let setting = $(this).data('option-name'),
            fileType = $(this).data('file-type'),
            parent = $(this).parents('.et-tabs-content');
        $(parent).find('.' + setting + '_preview').html('');
        $(parent).find('#' + setting).val('').trigger('change');
        $(this).hide();
    });
});