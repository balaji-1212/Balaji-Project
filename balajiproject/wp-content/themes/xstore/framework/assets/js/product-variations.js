var EtVariationGalleryAdmin = {
	init: function() {
		this.GWPAdmin();
		this.ImageUploader();
		this.Sortable();
		this.EventsOnChange();
	},

	_createClass: function() {
		function defineProperties(target, props) { 
			for (var i = 0; i < props.length; i++) { 
				var descriptor = props[i]; 
				descriptor.enumerable = descriptor.enumerable || false; 
				descriptor.configurable = true; 
				descriptor.writable = true; 

				Object.defineProperty(target, descriptor.key, descriptor); 
			} 
		} 
		return function (Constructor, protoProps, staticProps) { 
			if (protoProps) defineProperties(Constructor.prototype, protoProps); 
			if (staticProps) defineProperties(Constructor, staticProps); 
				return Constructor; 
		}; 
	},

	GWPAdmin: function() {
		if (jQuery().gwp_live_feed) {
            jQuery().gwp_live_feed();
        }
        if (jQuery().gwp_deactivate_popup) {
            jQuery().gwp_deactivate_popup('et-variation-gallery');
        }
	},

	ImageUploader: function() {
		jQuery(document).off('click', '.add-et-variation-gallery-image');
        jQuery(document).on('click', '.add-et-variation-gallery-image', this.AddImage);
        jQuery(document).on('click', '.remove-et-variation-gallery-image', this.RemoveImage);

        jQuery('.woocommerce_variation').each(function () {
            var optionsWrapper = jQuery(this).find('.options:first');

            var galleryWrapper = jQuery(this).find('.et-variation-gallery-wrapper');
            galleryWrapper.insertBefore(optionsWrapper);
        });
	},

	AddImage: function(event) {
        var jQueryel = this;

        event.preventDefault();
        event.stopPropagation();

        var file_frame = void 0;
        var product_variation_id = jQuery(this).data('product_variation_id');
        var loop = jQuery(this).data('product_variation_loop');

        if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {

            // If the media frame already exists, reopen it.
            if (file_frame) {
                file_frame.open();
                return;
            }

            // Create the media frame.
            file_frame = wp.media.frames.select_image = wp.media({
                title: et_variation_gallery_admin.choose_image,
                button: {
                    text: et_variation_gallery_admin.add_image
                },
                library: {
                    type: ['image'], // [ 'video', 'image' ]
                },
                states: [
					new wp.media.controller.Library({
						filterable: 'all',
						multiple: true
					})
				]
            });

            // When an image is selected, run a callback.
            file_frame.on('select', function () {

                var images = file_frame.state().get('selection').toJSON();

                var html = images.map(function (image) {
                    if (image.type === 'image') {
                        var id = image.id,
                            image_sizes = image.sizes;
                        image_sizes = image_sizes === undefined ? {} : image_sizes;
                        var thumbnail = image_sizes.thumbnail,
                            full = image_sizes.full;


                        var url = thumbnail ? thumbnail.url : full.url;
                        var template = wp.template('et-variation-gallery-image');
                        return template({ id: id, url: url, product_variation_id: product_variation_id, loop: loop });
                    }
                }).join('');

                jQuery(jQueryel).parent().prev().find('.et-variation-gallery-images').append(html);

                // Variation Changed
                EtVariationGalleryAdmin.Sortable();
                EtVariationGalleryAdmin.VariationChanged(jQueryel);
            });

            // Show the modal.
            file_frame.open();
        }
	},

	VariationChanged: function(jQueryel) {
		jQuery(jQueryel).closest('.woocommerce_variation').addClass('variation-needs-update');
        jQuery('button.cancel-variation-changes, button.save-variation-changes').removeAttr('disabled');
        jQuery('#variable_product_options').trigger('woocommerce_variations_input_changed');
	},

	RemoveImage: function(event) {
		var this2 = this;

        event.preventDefault();
        event.stopPropagation();

        // Variation Changed
        EtVariationGalleryAdmin.VariationChanged(this);

        _.delay(function () {
            jQuery(this2).parent().remove();
        }, 1);
	},

	Sortable: function() {
		jQuery('.et-variation-gallery-images').sortable({
            items: 'li.image',
            cursor: 'move',
            scrollSensitivity: 40,
            forcePlaceholderSize: true,
            forceHelperSize: false,
            helper: 'clone',
            opacity: 0.65,
            placeholder: 'et-variation-gallery-sortable-placeholder',
            start: function start(event, ui) {
                ui.item.css('background-color', '#f6f6f6');
            },
            stop: function stop(event, ui) {
                ui.item.removeAttr('style');
            },
            update: function update() {
                EtVariationGalleryAdmin.VariationChanged(this);
            }
        });
	},

	EventsOnChange: function() {
        jQuery('#woocommerce-product-data').on('woocommerce_variations_loaded', function () {
            EtVariationGalleryAdmin.ImageUploader();
            EtVariationGalleryAdmin.Sortable();
        });

        jQuery('#variable_product_options').on('woocommerce_variations_added', function () {
            EtVariationGalleryAdmin.ImageUploader();
            EtVariationGalleryAdmin.Sortable();
        });
	}

};

jQuery(document).ready(function($){
	// Variations Gallery Images 
	EtVariationGalleryAdmin.init();
});