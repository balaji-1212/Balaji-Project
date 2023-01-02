<?php global $post; ?>
<div id="product_video_container">
	<?php esc_html_e('Upload your Video in 3 formats: MP4, OGG and WEBM', 'xstore-core') ?>
	<ul class="product_video">
		<?php

		$product_video_code = get_post_meta( $post->ID, '_product_video_code', true );
		$product_image_gallery = '';

		if ( metadata_exists( 'post', $post->ID, '_product_video_gallery' ) ) {
			$product_image_gallery = get_post_meta( $post->ID, '_product_video_gallery', true );
		}

		$video_attachments = false;

		if(isset($product_image_gallery) && $product_image_gallery != '') {
			$video_attachments = get_posts( array(
				'post_type' => 'attachment',
				'include' => $product_image_gallery
			) );
		}

		if ( $video_attachments )
			foreach ( $video_attachments as $attachment ) {;
				echo '<li class="video" data-attachment_id="' . $attachment->id . '">
				                Name: ' . $attachment->post_name . '
								Format: ' . $attachment->post_mime_type . '
								<ul class="actions">
									<li><a href="#" class="delete" title="' . esc_html__( 'Delete image', 'xstore-core' ) . '">' . esc_html__( 'Delete', 'xstore-core' ) . '</a></li>
								</ul>
							</li>';
			}
		?>
	</ul>

	<input type="hidden" id="product_video_gallery" name="product_video_gallery" value="<?php echo esc_attr( $product_image_gallery ); ?>" />

</div>
<p class="add_product_video hide-if-no-js">
	<a href="#"><?php esc_html_e( 'Add product gallery video', 'xstore-core' ); ?></a>
</p>
<p class="product_video_autoplay">
    <input
        id="et_product_video_autoplay"
        type="checkbox"
        name="et_product_video_autoplay"
        <?php echo ( get_post_meta( $post->ID, '_product_video_autoplay', true ) ) ? 'checked' : ''; ?>
    >
    <label for="et_product_video_autoplay"><?php esc_html_e( 'Autoplay product gallery video. Video will be muted.', 'xstore-core' ); ?></label>
</>
<p>
	<?php esc_html_e('Or you can use YouTube or Vimeo iframe code', 'xstore-core'); ?>
</p>
<div class="product_iframe_video">

	<textarea name="et_video_code" id="et_video_code" rows="7"><?php echo esc_attr( $product_video_code ); ?></textarea>

</div>

<p>
	<?php echo sprintf(__('Also you may <a href="%s" target="_blank">change the positions</a> of your video in product gallery.', 'xstore-core'),
		(get_option( 'etheme_single_product_builder', false ) ? admin_url( '/customize.php?autofocus[section]=product_gallery' ) : admin_url( '/customize.php?autofocus[section]=single-product-page-layout' ))
    ); ?>
</p>

<script type="text/javascript">
    jQuery(document).ready(function($){

        // Uploading files
        var product_gallery_frame;
        var $image_gallery_ids = $('#product_video_gallery');
        var $product_images = $('#product_video_container ul.product_video');

        $('.add_product_video').on( 'click', 'a', function( event ) {

            var $el = $(this);
            var attachment_ids = $image_gallery_ids.val();

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if ( product_gallery_frame ) {
                product_gallery_frame.open();
                return;
            }

            // Create the media frame.
            product_gallery_frame = wp.media.frames.downloadable_file = wp.media({
                // Set the title of the modal.
                title: '<?php esc_html_e( 'Add Images to Product Gallery', 'xstore-core' ); ?>',
                button: {
                    text: '<?php esc_html_e( 'Add to gallery', 'xstore-core' ); ?>',
                },
                multiple: true,
                library : { type : 'video'}
            });

            // When an image is selected, run a callback.
            product_gallery_frame.on( 'select', function() {

                var selection = product_gallery_frame.state().get('selection');

                selection.map( function( attachment ) {

                    attachment = attachment.toJSON();

                    if ( attachment.id ) {
                        attachment_ids = attachment_ids ? attachment_ids + "," + attachment.id : attachment.id;

                        $product_images.append('\
									<li class="video" data-attachment_id="' + attachment.id + '">\
										Video\
										<ul class="actions">\
											<li><a href="#" class="delete" title="<?php esc_html_e( 'Delete video', 'xstore-core' ); ?>"><?php esc_html_e( 'Delete', 'xstore-core' ); ?></a></li>\
										</ul>\
									</li>');
                    }

                } );

                $image_gallery_ids.val( attachment_ids );
            });

            // Finally, open the modal.
            product_gallery_frame.open();
        });

        // Image ordering
        $product_images.sortable({
            items: 'li.video',
            cursor: 'move',
            scrollSensitivity:40,
            forcePlaceholderSize: true,
            forceHelperSize: false,
            helper: 'clone',
            opacity: 0.65,
            placeholder: 'wc-metabox-sortable-placeholder',
            start:function(event,ui){
                ui.item.css('background-color','#f6f6f6');
            },
            stop:function(event,ui){
                ui.item.removeAttr('style');
            },
            update: function(event, ui) {
                var attachment_ids = '';

                $('#product_video_container ul li.video').css('cursor','default').each(function() {
                    var attachment_id = $(this).attr( 'data-attachment_id' );
                    attachment_ids = attachment_ids + attachment_id + ',';
                });

                $image_gallery_ids.val( attachment_ids );
            }
        });

        // Remove images
        $('#product_video_container').on( 'click', 'a.delete', function() {

            $(this).closest('li.video').remove();

            var attachment_ids = '';

            $('#product_video_container ul li.video').css('cursor','default').each(function() {
                var attachment_id = $(this).attr( 'data-attachment_id' );
                attachment_ids = attachment_ids + attachment_id + ',';
            });

            $image_gallery_ids.val( attachment_ids );

            return false;
        } );

    });
</script>
