jQuery(document).ready(function ($) {
    // ! Set major-update message
    if ($('.et_major-version').length > 0) {
        $.each($('.themes .theme'), function (i, t) {
            if ($(this).data('slug') == 'xstore') {
                $(this).find('.update-message').append('<p class="et_major-update">' + $('.et_major-version').data('message') + '</p>');
            }
        });
        // ! show it for multisites
        $.each($('.plugin-update-tr.active'), function (i, t) {
            if ($(this).is('#xstore-update')) {
                $(this).find('.update-message').append('<p class="et_major-update">' + $('.et_major-version').data('message') + '</p>');
            }
        });
    }
});