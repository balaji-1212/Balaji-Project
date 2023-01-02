<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' ); ?>
<script type="text/javascript" id="et-global-widgets">
    // ! New wp.media for widgets
    jQuery(document).ready(function ($) {
        $(document).on("click", ".etheme_upload-image", function (e) {
            e.preventDefault();
            var $button = $(this);

            // Create the media frame.
            var file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select or upload image',
                library: { // remove these to show all
                    type: 'image' // specific mime
                },
                button: {
                    text: 'Select'
                },
                multiple: false  // Set to true to allow multiple files to be selected
            });

            // When an image is selected, run a callback.
            file_frame.on('select', function () {
                // We set multiple to false so only get one image from the uploader
                var attachment = file_frame.state().get('selection').first().toJSON();
                var parent = $button.parents('.media-widget-control');
                var thumb = '<img class="attachment-thumb" src="' + attachment.url + '">';

                parent.find('.placeholder.etheme_upload-image').addClass('hidden');
                parent.find('.attachment-thumb').remove();
                parent.find('.etheme_media-image').prepend(thumb);
                parent.find('input.widefat').attr('value', attachment.url);
                parent.find('input.widefat').change();
            });

            // Finally, open the modal
            file_frame.open();
        });
    });
</script>