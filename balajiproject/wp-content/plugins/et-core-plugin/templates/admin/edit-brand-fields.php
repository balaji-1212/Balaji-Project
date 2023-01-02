<tr class="form-field">
	<th scope="row" valign="top"><label><?php  echo $thumbnail; ?></label></th>
	<td>
		<div id="brand_thumbnail" style="float:left;margin-right:10px;">
			<img src="<?php echo $image; ?>" width="60px" height="60px" />
		</div>
		<div style="line-height:60px;">
			<input type="hidden" id="brand_thumbnail_id" name="brand_thumbnail_id" value="<?php echo $thumbnail_id; ?>" />
			<button type="submit" class="upload_image_button button"><?php echo $upload; ?></button>
			<button type="submit" class="remove_image_button button"><?php echo $remove; ?></button>
		</div>
		<script type="text/javascript">
            jQuery(function($){
                $(document).ready(function ($) {

                    // Uploading files
                    var file_frame;

                    $(document).on("click", ".upload_image_button", function (event) {

                        event.preventDefault();

                        // If the media frame already exists, reopen it.
                        if (file_frame) {
                            file_frame.open();
                            return;
                        }

                        // Create the media frame.
                        file_frame = wp.media.frames.downloadable_file = wp.media({
    						title: "<?php esc_html_e( 'Choose an image', 'xstore-core' ) ?>",
    						button: {
    							text: "<?php esc_html_e( 'Use image', 'xstore-core' ) ?>",
    						},
                            multiple: false
                        });

                        // When an image is selected, run a callback.
                        file_frame.on("select", function () {
                            attachment = file_frame.state().get("selection").first().toJSON();

                            $("#brand_thumbnail_id").val(attachment.id);
                            $("#brand_thumbnail img").attr("src", attachment.url);
                            $(".remove_image_button").show();
                        });

                        // Finally, open the modal.
                        file_frame.open();
                    });

                    $(document).on("click", ".remove_image_button", function (event) {
                        $("#brand_thumbnail img").attr("src", "<?php echo wc_placeholder_img_src() ?>");
                        $("#brand_thumbnail_id").val("");
                        $(".remove_image_button").hide();
                        return false;
                    });
                });
            });
		</script>
		<div class="clear"></div>
	</td>
</tr>
