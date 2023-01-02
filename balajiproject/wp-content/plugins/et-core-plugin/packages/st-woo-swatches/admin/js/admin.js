var $        = jQuery.noConflict(),
ST_WC_SWATCH = ST_WC_SWATCH || {};

(function($){
	"use strict";

	ST_WC_SWATCH.colorSwatch = function() {

		$(".st-color-swatch-picker").wpColorPicker();
	},

	ST_WC_SWATCH.imageSwatch = function() {

		var $frame;

		$("body").on("click", ".st-image-swatch-picker", function( event ){

			event.preventDefault();

			var $el = $( this );

			// If the media frame already exists, reopen it.
			if ( $frame ) {
				$frame.open();
				return;
			}

			// Create the media frame.
			$frame = wp.media({
				title 	 : $el.data( 'title' ),
				multiple : false,
				library  : {
					type : 'image'
				},
				button 	 : {
					text : $el.data( 'button' )
				}
			});

			// When an image is selected, run a callback.
			$frame.on("select", function(){

				var $attachment       = $frame.state().get( 'selection' ).first().toJSON(),
				    $attachment_image = $attachment.sizes.thumbnail ? $attachment.sizes.thumbnail.url : $attachment.url;

				var	$attachment_id = $attachment.id,
					$image_html    = '<div class="st-image-swatch-image">' + 
						'<img src="' + $attachment_image + '" />' +
						'<a href="javascript:void(0);" class="st-image-swatch-image-remove" title="' + $el.data('remove') + '"></a>' +
					'</div>';

				if( $el.hasClass('attribute-screen') ) {

					$el.siblings( 'input.st-image-swatch-id' ).val( $attachment_id );
					$el.parent().prev(".st-image-swatch-image-holder").append( $image_html );
				} else {

					$("#st-term-modal-container").find("input.st-image-swatch-id").val( $attachment_id );
					$("#st-term-modal-container").find(".st-image-swatch-image-holder").append( $image_html );
				}
				
				setTimeout(function(){
					$el.addClass('hidden');
				}, 10);				   
			});

			// Finally, open the modal.
			$frame.open();			
		});

		$("body").on("click", ".st-image-swatch-image-remove", function(event){

			event.preventDefault();

			var $el = $( this ),
				$parent = $el.parent(),
				$input = $el.parents('.st-image-swatch-image-holder').next(".st-image-swatch-holder").find("input.st-image-swatch-id"),
				$button = $el.parents('.st-image-swatch-image-holder').next(".st-image-swatch-holder").find("button.st-image-swatch-picker");

			$button.removeClass("hidden");
			$input.val('');
			$parent.remove();
		});
	},

	ST_WC_SWATCH.termModalbox = {

		init  : function() {

			var $modal = $( "#st-term-modal-container" );

			ST_WC_SWATCH.termModalbox.openModal( $modal );
			ST_WC_SWATCH.termModalbox.closeModal( $modal );
			ST_WC_SWATCH.termModalbox.addTerm( $modal );
		},

		openModal  : function( $modal ) {

			$("body").on("click", ".st-add-new-attribute", function(e){
				e.preventDefault();

				var $button 	= $(this),
					$tpl_tax    = wp.template("st-input-term-tax"),
					$data       = {
						tax : $button.closest( '.woocommerce_attribute' ).data( 'taxonomy' ),
						type: $button.data("type")
					};

					$data.type = $data.type.replace( '-sq', '' );

				$modal.find(".st-term-swatch").html( $("#tmpl-"+$data.type).html() );
				$modal.find(".st-term-tax").html( $tpl_tax( $data ) );
				
				if( "st-color-swatch" == $data.type ) {

					$modal.find(".st-color-swatch-picker").wpColorPicker();					
				}

				$modal.show();
			});
		},
		
		closeModal : function( $modal ) {

			$("body").on("click", "#st-term-modal-container .media-modal-close, #st-term-modal-container .media-modal-backdrop, #st-term-modal-container .st-term-cancel", function(e){
				e.preventDefault();
				$modal.find( '.st-term-name, .st-term-slug' ).val( '' );
				$modal.find(".st-term-insert").html( $modal.find(".st-term-insert").data('label') );

				$modal.hide();
			});
		},
		
		addTerm    : function( $modal ) {

			$("body").on("click", "#st-term-modal-container .st-term-insert", function(e){
				e.preventDefault();

				var $button = $(this),
				    $error  = false,
				    $data   = {},
				    $loader = '<span class="button-dot-loader"> <span class="dot dot-1"></span> <span class="dot dot-2"></span> <span class="dot dot-3"></span> </span>';

				$modal.find(".st-term-input").each(function(){
					var $this = $(this);

					if ( $this.attr( 'name' ) != 'slug' && !$this.val() ) {
						$this.addClass( 'error' );
						$error = true;
					} else {
						$this.removeClass( 'error' );
					}

					$data[$this.attr( 'name' )] = $this.val();
				});

				if( $error ) {
					return;
				}

				wp.ajax.send('stwc_add_new_term', {
					data   : $data,
					beforeSend : function( res ) {
						$button.html( $loader );
					},
					error  : function( res ) {
						$button.html('<span class="' + res.btn_span + '"> </span>');
						alert( res.msg );
						$button.html( res.btn_txt );
					},
					success: function ( res ) {
						var $metabox = $(".st-add-new-attribute").closest( '.woocommerce_attribute.wc-metabox' ),
							$select  = $metabox.find( 'select.attribute_values' );

						// console.log( $select );

						$select.append( '<option value="' + res.id + '" selected="selected">' + res.name + '</option>' );
						$select.change();

						$button.html( res.btn_txt );
						$modal.find(".media-modal-close").trigger('click');
					}
				});
			});
		},		
	},

	ST_WC_SWATCH.documentOnReady = {

		init : function() {

			ST_WC_SWATCH.colorSwatch();
			ST_WC_SWATCH.imageSwatch();
			ST_WC_SWATCH.termModalbox.init();			
		}
	};

	ST_WC_SWATCH.ajaxComplete = function( event, request, options ){

		if( !options.hasOwnProperty( 'data') ) {
			return;
		}

		if( 0 <= options.data.indexOf('st-image-swatch') || 0 <= options.data.indexOf('st-color-swatch') ) {

			if ( request && 4 === request.readyState && 200 === request.status
				&& options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

				var $res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
				if ( ! $res || $res.errors ) {
					return;
				}
			}

			if( 0 <= options.data.indexOf('st-image-swatch') ) {

				$("button.st-image-swatch-picker").removeClass("hidden");
				$("input.st-image-swatch-id").val('');
				$(".st-image-swatch-image-holder").html('');
			}

			if( 0 <= options.data.indexOf('st-color-swatch') ) {

				$(".wp-color-result").removeAttr('style');
				$(".wp-color-result").trigger('click');
			}
		}
	};

	$(document).ready( ST_WC_SWATCH.documentOnReady.init );

	$(document).ajaxComplete( ST_WC_SWATCH.ajaxComplete );
})(jQuery);