var $              = jQuery.noConflict(),
	ST_WC_FRONT_SWATCH = ST_WC_FRONT_SWATCH || {},
	ST_WC_Chosen_attribute = '',
	ST_WC_Show_selected_title = false,
	ST_WC_Triggered_variations = [];

(function($){
	"use strict";

	ST_WC_FRONT_SWATCH.onSelect = function() {

		$("body").on("click", "ul.st-swatch-preview span.st-custom-attribute", function(){

			if ( $(this).parents().is( '.swiper-entry, .products-loop, .et-quick-view-wrapper, .etheme-product-grid-item, .etheme-product-list-item' ) ) return;

			var $attribute = $(this).closest("[data-attribute]").data("attribute"),
				$select    = $("select#"+$attribute ),
				$value 	   = $(this).data("value"),
				$li		   = $(this).parent("li");

			// Check if this combination is available
			// var $swatches   = $(this).parents('.variations_form.cart');
			// var $variations = $.parseJSON( $swatches.attr("data-product_variations") );
			// ST_WC_FRONT_SWATCH.productLoop.updateAttributes( $swatches, $variations );

			if ( $value != undefined && ! $select.find( 'option[value="' + $value + '"]' ).length ) {
				$select.val(" ").trigger("change");
				$li.siblings().removeClass("selected");
				window.alert( sten_wc_params.i18n_no_matching_variations_text );
				return;
			}

			if( $li.hasClass("selected") ) {

				$li.removeClass("selected");
				$select.val(" ");

				if ( sten_wc_params.is_singular_product == 1 ) {
					if ( sten_wc_params.is_customize_preview ) {
						$li.css({
							'border-color':$li.data("border-color")
						});
					}
					if ( ST_WC_Show_selected_title ) {
						$li.parents('tr').find(' > th label').attr('data-swatch-name', null);
					}
				}

			} else {

				$li.addClass('selected').siblings().removeClass("selected");
				$select.val( $value );

				if ( sten_wc_params.is_singular_product == 1 ) {
					if ( sten_wc_params.is_customize_preview ) {
						$li.removeAttr("style");
						$li.siblings().css({
							'border-color':$li.data("border-color")
						});
						$li.css({
							'border-color':$li.data("active-border-color")
						});
					}
					if ( ST_WC_Show_selected_title ) {
						$li.parents('tr').find(' > th label').attr('data-swatch-name', $(this).data('name'));
					}
				}

				ST_WC_Chosen_attribute = $(this).closest("[data-attribute]").data("attribute");
			}

			$select.trigger("change");
		});

	},

	ST_WC_FRONT_SWATCH.singleProductPrimaryAttribute = function() {
		$(document).find('.variations_form.cart').on('reset_data', function () {
			var $swatches = $(this);

			if (
				! etConfig.woocommerceSettings.primary_attribute
				|| etConfig.woocommerceSettings.primary_attribute  == 'et_none'
				|| ST_WC_Chosen_attribute != 'pa_' + etConfig.woocommerceSettings.primary_attribute
			){
				return
			} else {
				ST_WC_Chosen_attribute  = '';
			}

			setTimeout(function(){

				let $attributes         = ST_WC_FRONT_SWATCH.productLoop.getChosenAttributes( $swatches ),
					$current_attributes = $attributes.data;

				if (
					! $attributes.count
					|| ! $attributes.chosenCount
					|| $attributes.count == $attributes.chosenCount
				){
					return;
				}

				let $variations = $.parseJSON( $swatches.attr("data-product_variations") ),
				 	$matching_variations = ST_WC_FRONT_SWATCH.productLoop.findMatchingVariations( $variations, $current_attributes ),
					$variation = $matching_variations.shift(),
				 	main_image = $('.woocommerce-main-image').first(),
				 	thumbnail = $('.thumbnail-item a').first();

				if (
					!main_image.find('img').attr('data-o_src')
					&& !main_image.find('img').attr('data-o_data-src')
				){
					main_image.attr('data-o_href', main_image.attr('href'));
					main_image.find('img').attr({
						'data-o_src': main_image.find('img').attr('src'),
						'data-o_data-src':  main_image.find('img').attr('src'),
						'data-o_data-large_image' : main_image.find('img').attr('data-large_image')
					});
				}

				main_image.attr('href', $variation.image.full_src);

				main_image.find('img').attr({
					// 'src': $variation.image.thumb_src,
					'src': $variation.image.src,
					'data-src': $variation.image.src,
					'data-large_image': $variation.image.full_src,
					'srcset' : ''
				});

				main_image.parent().find('img.zoomImg').attr('src', main_image.find('img').attr('data-large_image'));
				thumbnail.attr('href', $variation.image.full_src).attr('data-large', $variation.image.full_src).attr('data-small', $variation.image.gallery_thumbnail_src);
				thumbnail.find('img').attr('src', $variation.image.gallery_thumbnail_src).attr('srcset', $variation.image.gallery_thumbnail_src);

				// Timeout 50 because woocommerce have timeout 20 !
			}, 50);
		});
	}

			ST_WC_FRONT_SWATCH.onSelectUpdateAttribute = function() {

			$("body.single-product form.variations_form").on('woocommerce_update_variation_values', function(){
				$( this ).find("ul.st-swatch-preview").each(function(){
					var	$this      = this,
						$attribute = $($this).data("attribute"),
						$select    = document.getElementById( $attribute ), // $("select#"+$attribute ), - it has issues in other languages ( jquery selector doesn't accepts special chars )
						$options   = $($select).find("option"),
						$eq   	   = $($select).find("option").eq(1),
						$li 	   = $($this).find("li"),
						$current   = $($select).find("option:selected"),
						$selects   = [],
						$selected  = '';

					$options.each(function(){
						if ($(this).val() !== '') {
							$selects.push( $(this).val() );
							$selected = $current ? $current.val() : $eq.val();
						}
					});

					$li.each(function(){
						var $this = this,
							$value = $($this).find("span.st-custom-attribute").attr("data-value");

						$($this).removeClass("selected sten-li-disabled").addClass("sten-li-disabled");

						if( $selects.indexOf( $value.toString() ) !== -1 ) {

							$($this).removeClass("sten-li-disabled");
							if( $value.toString() == $selected ) {
								$($this).addClass("selected");
							}
						}
					});
				});
			});
		},

		ST_WC_FRONT_SWATCH.onClear = function(){

			$("body.single-product").on("click", "a.reset_variations", function(){

				var $selected = $(this).closest('form.variations_form').find("ul.st-swatch-preview li.selected");

				$selected.removeClass( 'selected' );

				if( sten_wc_params.is_singular_product == 1 ) {

					if ( sten_wc_params.is_customize_preview ) {
						$selected.css({
							'border-color': $selected.data("border-color")
						});
					}

					if ( ST_WC_Show_selected_title ) {
						$(this).closest('form.variations_form').find('label[data-swatch-name]').attr('data-swatch-name', null);
					}
				}
			});
		},

		ST_WC_FRONT_SWATCH.productLoop = {

			init : function() {
				ST_WC_FRONT_SWATCH.productLoop.itemSwatches();
				ST_WC_FRONT_SWATCH.productLoop.addToCart();
				ST_WC_FRONT_SWATCH.productLoop.showMoreAttr();
			},

			itemSwatches: function() {

				$("div.st-swatch-in-loop").each(function(){

					var	$swatches   = $(this),
						$terms      = $swatches.find("span.st-custom-attribute:not(.sten-disabled)"),
						$reset_btn  = $swatches.find("a.sten-reset-loop-variation, .et-delete"),
						$product    = $swatches.closest(".product-type-variable"),
						$variations = $.parseJSON( $swatches.attr("data-product_variations") ),
						$uls 		= $swatches.find("ul.has-default-attribute");

					// add disable for all btns
					$product.find("a.add_to_cart_button").addClass('et-st-disabled');
					$(".st-swatch-btn-ready").removeClass("et-st-disabled");

					// force update for out of stock products
					ST_WC_FRONT_SWATCH.productLoop.updateAttributes( $swatches, $variations );
					// !force update for out of stock products

					// enable variation default attribute by default in product loop
					$uls.each( function(){

						var $ul           = $(this),
							$ul_reset_btn = $ul.parent("div.st-swatch-in-loop").find("a.sten-reset-loop-variation");

						$ul_reset_btn.show();

						var	$attributes         = ST_WC_FRONT_SWATCH.productLoop.getChosenAttributes( $swatches ),
							$current_attributes = $attributes.data;

						if( $attributes.count === $attributes.chosenCount ) {

							ST_WC_FRONT_SWATCH.productLoop.updateAttributes( $swatches, $variations );

							var $matching_variations = ST_WC_FRONT_SWATCH.productLoop.findMatchingVariations( $variations, $current_attributes ),
								$variation = $matching_variations.shift();

							if( $variation ) {

								ST_WC_FRONT_SWATCH.productLoop.foundVariation( $product, $swatches, $variation );
							} else {

								$ul_reset_btn.trigger( 'click' );
							}
						} else {

							ST_WC_FRONT_SWATCH.productLoop.updateAttributes( $swatches, $variations );
						}
					});

					// swatch click
					$terms.on( "click", function(){

						var $term = $(this),
							$li = $term.parent("li");

						if( $term.hasClass('sten-disabled') ) {
							return false;
						}

						$product.find("span.st-custom-attribute").removeClass("sten-disabled sten-enabled");
						$product.find("span.st-custom-attribute").parent("li").removeClass("sten-li-disabled sten-li-enabled");

						if( $li.hasClass("selected") ) {

							if ( sten_wc_params.is_customize_preview ) {
								$li.css({
									'border-color': $li.data("border-color")
								});
							}

							// it makes custom attributed deleted at once it was added
							// if ( ST_WC_Show_selected_title && $term.parents().is( '.et-quick-view-wrapper' ) ) {
							// 	$li.parents('.st-swatch-in-loop').find('.et_attribute-name[data-for='+($term.parents('.et_st-default-holder').attr('data-et-holder'))+']').attr('data-swatch-name', null);
							// }
						}

						if( !$li.hasClass("selected") ) {

							$li.addClass("selected").siblings().removeClass("selected");

							if ( ST_WC_Show_selected_title && $term.parents().is( '.et-quick-view-wrapper' ) ) {
								$li.parents('.st-swatch-in-loop').find('.et_attribute-name[data-for='+$term.parents('.et_st-default-holder').attr('data-et-holder')+']').attr('data-swatch-name', $term.attr('data-name'));
							}

							if( sten_wc_params.is_customize_preview ) {

								$li.removeAttr("style");
								$li.siblings().css({
									'border-color':$li.data("border-color")
								});
								$li.css({
									'border-color':$li.data("active-border-color")
								});
							}

							$reset_btn.slideDown();
						}

						var	$attributes         = ST_WC_FRONT_SWATCH.productLoop.getChosenAttributes( $swatches ),
							$current_attributes = $attributes.data;

						if( $attributes.count === $attributes.chosenCount ) {

							ST_WC_FRONT_SWATCH.productLoop.updateAttributes( $swatches, $variations );

							var $matching_variations = ST_WC_FRONT_SWATCH.productLoop.findMatchingVariations( $variations, $current_attributes ),
								$variation = $matching_variations.shift();

							if( $variation ) {

								ST_WC_FRONT_SWATCH.productLoop.foundVariation( $product, $swatches, $variation );
							} else {

								$reset_btn.trigger( 'click' );
							}
						} else {

							if (
								etConfig.woocommerceSettings.primary_attribute
								&& etConfig.woocommerceSettings.primary_attribute  !='et_none'
								&& $(this).closest("[data-attribute]").data("attribute") == 'pa_' + etConfig.woocommerceSettings.primary_attribute
							){
								let $matching_variations = ST_WC_FRONT_SWATCH.productLoop.findMatchingVariations( $variations, $current_attributes ),
									$variation = $matching_variations.shift();

								ST_WC_FRONT_SWATCH.productLoop.variationsImageUpdate( $product, $variation );
							}

							ST_WC_FRONT_SWATCH.productLoop.updateAttributes( $swatches, $variations );
						}
					});

					// reset click
					$reset_btn.on( "click", function(){

						$swatches.removeAttr( 'data-variation_id' );

						if( sten_wc_params.is_customize_preview ) {

							var $sel = $swatches.find("ul.st-swatch-preview li.selected");
							$sel.css({
								'border-color':$sel.data("border-color")
							});
						}

						$swatches.find("ul.st-swatch-preview li").removeClass("selected sten-li-disabled sten-li-enabled");
						$swatches.find("ul.st-swatch-preview span").removeClass("sten-disabled sten-enabled");

						let $btn = $product.find("a.add_to_cart_button");
						let $btn_text = $btn;
						if ( $product.is('.etheme-product-grid-item, .etheme-product-list-item') ) {
							if ( $product.find('.st-swatch-popup').length && !$product.find('.st-swatch-popup .quantity-wrapper').length ) {
								// $btn = $product.find('.st-swatch-in-loop a.add_to_cart_button');
								$btn_text = $product.find('.st-swatch-in-loop a.add_to_cart_button');
							}
							else {
								// $btn = $product.find('.etheme-product-grid-button, .etheme-product-list-button');
								$btn_text = $product.find('.etheme-product-grid-button .button-text, .etheme-product-list-button .button-text');
							}

							// $product.removeClass('inactive-hover');

						}
						$btn.addClass('et-st-disabled')
							.removeClass("st-swatch-btn-ready st-swatch-btn-readmore st-swatch-btn-text-changed added loading")
							.attr('aria-label',sten_wc_params.select_options_btn_text + ' ' + sten_wc_params.read_more_for_btn_text + ' "' + $btn.attr('data-product_name') +'"');
						$btn_text
							.text( sten_wc_params.select_options_btn_text );
						$product.find("a.added_to_cart").remove();

						var	$price        = $product.find("span.price").not( '.price-cloned' ),
							$price_cloned = $product.find( '.price-cloned' );

						if ( $price_cloned.length ) {

							$price.html( $price_cloned.html() );
							$price_cloned.remove();
						}

						// refresh wishlist on grid
						if ( etConfig['woocommerceSettings']['wishlist_for_variations']) {
							$swatches.trigger('reset_data');
						}

						// force update for out of stock products
						$variations = $.parseJSON( $swatches.attr("data-product_variations") );
						ST_WC_FRONT_SWATCH.productLoop.updateAttributes( $swatches, $variations );
						// !force update for out of stock products

						ST_WC_FRONT_SWATCH.productLoop.variationsImageUpdate( $product, false );

						// Update Countdown
						ST_WC_FRONT_SWATCH.productLoop.variationsCountdownUpdate( $product, false );

						ST_WC_FRONT_SWATCH.productLoop.variationsLoopQty($product, false);

						if ( $(this).parents().is( '.et-quick-view-wrapper' ) ) {
							$(this).parents('.et-quick-view-wrapper').find('.price:not(.price_cloned)').remove();
							$(this).parents('.et-quick-view-wrapper').find('.price_cloned').removeClass('price_cloned').attr('style', null);

							if ( ST_WC_Show_selected_title ) {
								$(this).parents('.et-quick-view-wrapper').find('.et_attribute-name[data-swatch-name]').attr('data-swatch-name', null);
							}
						}

						$(this).slideUp();
					});
				});
			},

			getChosenAttributes: function ( $swatches ) {

				var	$data   = {},
					$count  = 0,
					$chosen = 0,
					$swatch = $swatches.find("ul.st-swatch-preview");

				$swatch.each(function(){

					var $attr_name = 'attribute_' + $(this).data("attribute"),
						$value     = $(this).find("li.selected span.st-custom-attribute").attr("data-value") || '';

					$value = $value.toString();

					if ( $value.length > 0 ) {

						$chosen++;
					}

					$count++;
					$data[ $attr_name ] = $value;
				});

				return {
					'count': $count,
					'chosenCount': $chosen,
					'data': $data,
				};
			},

			updateAttributes: function( $swatches, $variations ) {

				var	$attributes              = ST_WC_FRONT_SWATCH.productLoop.getChosenAttributes( $swatches ),
					$current_attributes      = $attributes.data,
					$available_options_count = 0,
					$swatch                  = $swatches.find( 'ul.st-swatch-preview');

				$swatch.each(function( $idx, $ele ){

					var	$current_attribute_swatch = $( $ele ),
						$current_attribute_name   = "attribute_" + $current_attribute_swatch.data("attribute"),
						$selected_attr_val        = $current_attribute_swatch.find("li.selected span.st-custom-attribute").data("value"),
						$selected_attr_val_valid  = true,
						$check_attributes         = $.extend( true, {}, $current_attributes );

					$check_attributes[ $current_attribute_name ] = '';
					var $matching_variations = ST_WC_FRONT_SWATCH.productLoop.findMatchingVariations( $variations, $check_attributes );

					//console.log($matching_variations);

					// Loop through matching variations
					for( var $num in $matching_variations ) {

						if ( typeof $matching_variations[ $num ] !== 'undefined' ) {

							var $variation_attributes = $matching_variations[ $num ].attributes;

							for( var $attribute_name in $variation_attributes ) {

								if( $variation_attributes.hasOwnProperty( $attribute_name ) ) {

									var	$attribute_val    = $variation_attributes[ $attribute_name ],
										$variation_active = '';

									if ( $attribute_name === $current_attribute_name ) {
										if ( $matching_variations[ $num ].variation_is_active  ) {

											$variation_active = 'sten-enabled';
										}

										// ! Add et-out-of-stock class
										// if ( ! $matching_variations[ $num ].is_in_stock  ) {
										// 	$swatch.find( 'span.st-custom-attribute[data-value="' + $attribute_val + '"]' ).parent( "li" ).addClass( "et-out-of-stock" );
										// } else {
										// 	$swatch.find( 'span.st-custom-attribute[data-value="' + $attribute_val + '"]' ).parent( "li" ).removeClass( "et-out-of-stock" );
										// }

										if( $attribute_val ) {

											$current_attribute_swatch.find('span.st-custom-attribute[data-value="' + $attribute_val + '"]').addClass("sten-enabled");
										} else {
											// apply for all swatches
											$current_attribute_swatch.find("span.st-custom-attribute").addClass("sten-enabled");
										}
									}
								}
							}
						}
					}



					$available_options_count = $current_attribute_swatch.find('span.st-custom-attribute.sten-enabled' ).length;

					if ( $selected_attr_val &&
						( $available_options_count === 0 || $current_attribute_swatch
							.find('span.st-custom-attribute.sten-enabled[data-value="' + ST_WC_FRONT_SWATCH.productLoop.addSlashes( $selected_attr_val ) + '"]' )
							.length === 0 ) ) {
						$selected_attr_val_valid = false;
					}

					// Disable terms if not available
					$current_attribute_swatch.find('span.st-custom-attribute:not(.sten-enabled)' ).addClass("sten-disabled");
					$current_attribute_swatch.find('span.st-custom-attribute:not(.sten-enabled)' ).parent("li").addClass("sten-li-disabled");

					// Choose selected value.
					if ( $selected_attr_val ) {

						// If the previously selected value is no longer available,
						// fall back to the placeholder (it's going to be there).
						if ( !$selected_attr_val_valid ) {

							$current_attribute_swatch.find("li.selected").removeClass('selected');
						}
					} else {

						$current_attribute_swatch.find("li.selected").removeClass('selected');
					}
				});
			},

			findMatchingVariations: function( $variations, $current_attributes ) {

				var $matching = [];

				for( var $i = 0; $i < $variations.length; $i++ ){

					var $variation = $variations[$i];

					if(  ST_WC_FRONT_SWATCH.productLoop.isMatch( $variation.attributes, $current_attributes ) ) {

						$matching.push( $variation );
					}
				}

				return $matching;
			},

			isMatch: function( $variation_attributes, $current_attributes ) {

				var $match = true;

				for( var $attr_name in $variation_attributes ) {

					if( $variation_attributes.hasOwnProperty( $attr_name ) ) {

						var $val1 = $variation_attributes[ $attr_name ],
							$val2 = $current_attributes[ $attr_name ];

						if( ( typeof $val1 !== "undefined" ) &&
							( $val1 !== null ) &&
							( typeof $val2 !== "undefined" ) &&
							( $val2 !== null ) &&
							( $val1.length !== 0 ) &&
							( $val2.length !== 0 ) &&
							( $val1 !== $val2 ) ) {

							$match = false;
						}
					}
				}

				return $match;
			},

			foundVariation: function( $product, $swatches, $variation ) {

				var	$price       = $product.find("span.price").not( '.price-cloned' ),
					$price_clone = $price.clone().addClass( 'price-cloned' ).css( 'display', 'none' );

				$swatches.attr("data-variation_id", $variation.variation_id );

				if( $variation.price_html ) {

					if ( !$product.find( '.price-cloned' ).length ) {

						$product.append($price_clone);
					}

					$price.replaceWith( $variation.price_html );
				} else {

					if ( $product.find( '.price-cloned' ).length ) {

						$price.replaceWith( $price_clone.html() );
						$price_clone.remove();
					}
				}

				// refresh wishlist on grid
				if ( etConfig['woocommerceSettings']['wishlist_for_variations']) {
					$swatches.trigger('found_variation', [$variation]);
				}

				// Update Image
				ST_WC_FRONT_SWATCH.productLoop.variationsImageUpdate( $product, $variation );

				// Update Countdown
				ST_WC_FRONT_SWATCH.productLoop.variationsCountdownUpdate( $product, $variation );

				// change add to cart button text
				ST_WC_FRONT_SWATCH.productLoop.changeAddToCartBtnText( $product, $variation );
			},

			// Reinit variations qty, availability_html
			variationsQuickViewUpdate: function ($product, $variation){
				let quick_view = $('.et-quick-view-wrapper');

				quick_view.find('.stock, .product-stock').remove();
				if ($variation.availability_html) {
					quick_view.find('.st-swatch-in-loop').before($variation.availability_html);
				}

				let sku = quick_view.find('.sku_wrapper .sku');
				if ( $variation.sku ) {
					if ( ! sku.attr( 'data-o_content' ) ) {
						sku.attr( 'data-o_content' , sku.html() );
					}
					sku.html( $variation.sku );
				} else {
					sku.html(sku.attr('data-o_content'));
				}

				let gtin = quick_view.find('.gtin');
				if ( $variation._et_gtin ) {
					if ( ! gtin.attr( 'data-o_content' ) ) {
						gtin.attr( 'data-o_content' , gtin.text() );
					}
					gtin.text( $variation._et_gtin );
				} else {
					if ( undefined !== gtin.attr( 'data-o_content' ) )
						gtin.text(gtin.attr('data-o_content'));
				}

				let xstore_wishlist = quick_view.find('.xstore-wishlist');

				if ( !!xstore_wishlist ) {
					if ( $variation.variation_id ) {
						if ( ! xstore_wishlist.attr( 'data-o_id' ) ) {
							xstore_wishlist.attr( 'data-o_id' , xstore_wishlist.data('id') );
						}
						xstore_wishlist.attr( 'data-id', $variation.variation_id );
					} else {
						if ( undefined !== xstore_wishlist.attr( 'data-o_id' ) )
							xstore_wishlist.attr('data-id', xstore_wishlist.attr('data-o_id'));
					}
					if ( undefined !== typeof etTheme.xstore_wishlist && "undefined" !== typeof etTheme.xstore_wishlist){
						etTheme.xstore_wishlist.switch_variation_button(xstore_wishlist, parseInt($variation.variation_id));
					}
				}

				let xstore_compare = quick_view.find('.xstore-compare');

				if ( !!xstore_compare ) { 
					if ( $variation.variation_id ) {
						if ( ! xstore_compare.attr( 'data-o_id' ) ) {
							xstore_compare.attr( 'data-o_id' , xstore_compare.data('id') );
						}
						xstore_compare.attr( 'data-id', $variation.variation_id );
					} else {
						if ( undefined !== xstore_compare.attr( 'data-o_id' ) )
							xstore_compare.attr('data-id', xstore_compare.attr('data-o_id'));
					}
					if ( undefined !== typeof etTheme.xstore_compare && "undefined" !== typeof etTheme.xstore_compare){
						etTheme.xstore_compare.switch_variation_button(xstore_compare, parseInt($variation.variation_id));
					}
				}

				ST_WC_FRONT_SWATCH.productLoop.variationsLoopQty($product, $variation, quick_view.find('input.qty'));
			},

			normalizeProductId: function ($product, $variation){
				let swatch = $product.find('.st-swatch-in-loop'),
					swatch_id = swatch.attr('data-product_id'),
					btn =  $product.find('a.add_to_cart_button'),
					product_id = btn.attr('data-product_id');

				if (swatch_id && ! product_id) {
					btn.attr('data-product_id', swatch_id);
				}
			},

			variationsLoopQty: function ($product, $variation, $element){
				if (!$variation) {
					let input = $product.find('input.qty');
					let select = $product.find('select.qty');
					if ( input.length ) {
						input.attr('max', input.attr('data-o_max'));
						input.attr('min', input.attr('data-o_min'));
					}
					else if ( select.length ) {
						select.find('option').attr('disabled', null);
					}
				}
				else if ( $element.length && $variation.is_in_stock ){
					if ( $variation.max_qty ) {
						if ($element[0].nodeName == 'SELECT') {
							let options = $element.find('option');
							options.attr('disabled', 1);
							let min_qty = $variation.min_qty;
							let max_qty = $variation.max_qty;
							$(options).each(function () {
								let _this_val = this.value;
								if ((max_qty == '' || _this_val <= max_qty) && (_this_val >= min_qty || min_qty == '')) {
									$(this).attr('disabled', null);
								} else {
									$element.val($element.find('option:not([disabled]):first').val());
								}
							});
						} else {
							$element.attr('min', $variation.min_qty);
							$element.attr('max', $variation.max_qty);

							if ($variation.max_qty < $element.val()) {
								$element.parent().find('.plus').trigger('click');
							}
						}
					}
					else {
						if ($element[0].nodeName == 'SELECT') {
							$element.find('option').attr('disabled', null);
						}
						else {
							if ( $element.attr( 'data-o_max' ) !== null ) {
								$element.attr( 'data-o_min' , $element.attr('min') );
								$element.attr( 'data-o_max' , $element.attr('max') );
							}
							$element.attr('min', $variation.min_qty);
							$element.attr('max', '');
						}
					}
				}
			},

			// Primary Qty init, change add to cart btn data-quantity attr
			variationsQuickViewQty: function() {
				let quick_view = $('.et-quick-view-wrapper');
				let qty = quick_view.find('input.qty, select.qty');

				if ( ! qty.length ) return;

				qty.attr('min', 1);
				qty.on( 'change', function (e){
					let qty_q = qty.val();
					if ( qty_q == 0 ) qty_q = 1;
					quick_view.find('a.button.product_type_variable').attr('data-quantity',qty_q);
				});
			},

			variationsImageUpdate: function( $product, $variation ) {

				var $product_img = $product.find("img.wp-post-image, img.attachment-woocommerce_thumbnail, img.attachment-shop_catalog");
				var $parent = $product.find( '.st-swatch-in-loop' );

				// ! Etheme change sku for product quick-view-popup
				if ( $parent.parents().is( '.et-quick-view-wrapper' ) ) {

					ST_WC_FRONT_SWATCH.productLoop.variationsQuickViewUpdate($product, $variation);

					if ( $parent.parents('.et-quick-view-wrapper').find('.price_cloned').length < 1) {
						var $parent_price = $parent.parents('.et-quick-view-wrapper').find('p.price:not(.price_cloned)');
						$parent_price.after($parent_price.clone().addClass('price_cloned').hide());
					}

					if ( $variation.price_html ) {
						$parent.parents('.et-quick-view-wrapper').find('p.price:not(.price_cloned)').html($variation.price_html);
					}

				} else {
					ST_WC_FRONT_SWATCH.productLoop.normalizeProductId($product, $variation);
					ST_WC_FRONT_SWATCH.productLoop.variationsLoopQty($product, $variation, $product.find('input.qty, select.qty'));
					// open popup with selected attributes
					// reset it because this part of code works also on reset action
					$product.find('.show-quickly').attr('data-attributes', null);
					if ( !!$variation )
						$product.find('.show-quickly').attr('data-attributes', JSON.stringify($variation.attributes));
				}

				if ( $parent.parents().is( '.et-quick-view-wrapper' ) && $variation && $variation.st_image_src && $variation.image.src && $variation.st_image_src.length > 1 ) {
					// ST_WC_FRONT_SWATCH.productLoop.setVariationAttr( $product_img, 'src', $variation.st_image_src[0] );
					// ST_WC_FRONT_SWATCH.productLoop.setVariationAttr( $product_img, 'srcset', $variation.st_image_src[0] );
					// ST_WC_FRONT_SWATCH.productLoop.setVariationAttr( $product_img, 'sizes', $variation.image.sizes );

					ST_WC_FRONT_SWATCH.productLoop.setVariationAttr( $product_img, 'src', $variation.image.full_src );
					if ( $product_img.attr('srcset') ) {
						ST_WC_FRONT_SWATCH.productLoop.setVariationAttr( $product_img, 'srcset', $variation.image.full_src );
					}
					ST_WC_FRONT_SWATCH.productLoop.setVariationAttr( $product_img, 'sizes', $variation.sizes );

				} else if( $variation && $variation.st_image_src && $variation.image.src && $variation.st_image_src.length > 1 ) {

					if ( $product_img.parents().is( '.product-image-wrapper' ) ) {

						var image_wrap = $( $product_img ).parents( '.product-image-wrapper' );
						var image_link = image_wrap.find('.product-content-image');

						if ( !$parent.is('.st-swatch-popup') ) {
							image_wrap.addClass( 'variation-changed' );
						}

					}

					ST_WC_FRONT_SWATCH.productLoop.setVariationAttr( $product_img, 'src', $variation.st_image_src[0] );
					if ( $product_img.attr('srcset') ) {
						ST_WC_FRONT_SWATCH.productLoop.setVariationAttr( $product_img, 'srcset', $variation.st_image_srcset );
					}
					ST_WC_FRONT_SWATCH.productLoop.setVariationAttr( $product_img, 'sizes', $variation.st_image_sizes );

				} else {

					if ( $product_img.parents().is( '.product-image-wrapper' ) ) {

						var image_wrap = $( $product_img ).parents( '.product-image-wrapper' );
						var image_link = image_wrap.find('.product-content-image');

						if ( !$parent.is('.st-swatch-popup') ) {
							image_wrap.removeClass( 'variation-changed' );
						}

					}

					ST_WC_FRONT_SWATCH.productLoop.resetVariationAttr( $product_img, 'src' );
					if ( $product_img.attr('srcset') ) {
						ST_WC_FRONT_SWATCH.productLoop.resetVariationAttr( $product_img, 'srcset' );
					}
					ST_WC_FRONT_SWATCH.productLoop.resetVariationAttr( $product_img, 'sizes' );

				}
			},

			variationsCountdownUpdate: function( $product, $variation ) {
				var countdown = $product.find( '.product-sale-counter' );
				if (countdown.length) {
					if ( !$variation ) {
						countdown.addClass('hidden');
					}
					else {
						if (!!$variation._sale_price_end) {
							countdown.attr('data-start', $variation._sale_price_start);
							countdown.attr('data-final', $variation._sale_price_end);
							if (etTheme.countdown !== undefined)
								etTheme.countdown();
							countdown.removeClass('hidden');
						} else {
							countdown.addClass('hidden');
						}
					}
				}
			},

			changeAddToCartBtnText: function( $product, $variation ) {

				var $btn = $product.find("a.add_to_cart_button"),
					$btn_text = $btn,
					$txt = '',
					$label = '';

				if ( $product.is('.etheme-product-grid-item, .etheme-product-list-item') ) {
					if ( $product.find('.st-swatch-popup').length && !$product.find('.st-swatch-popup .quantity-wrapper').length ) {
						// $btn = $product.find('.st-swatch-in-loop a.add_to_cart_button');
						$btn_text = $product.find('.st-swatch-in-loop a.add_to_cart_button');
					}
					else {
						// $btn = $product.find('.etheme-product-grid-button, .etheme-product-list-button');
						$btn_text = $product.find('.etheme-product-grid-button .button-text, .etheme-product-list-button .button-text');
					}

					// $product.addClass('inactive-hover');
				}

				$btn.removeClass("added");
				if( Object.keys( $variation.attributes ).length === $product.find( "ul.st-swatch-preview" ).length ) {

					if ( $variation.is_in_stock === true ) {
						$txt = sten_wc_params.add_to_cart_btn_text;
						$label = sten_wc_params.add_to_cart_btn_text;
						$btn.addClass("st-swatch-btn-ready").removeClass("st-swatch-btn-readmore").removeClass("et-st-disabled");
					} else {

						$txt = sten_wc_params.read_more_btn_text;
						$label = sten_wc_params.read_more_btn_text;
						$label += ' ' + sten_wc_params.read_more_about_btn_text;
						$btn.addClass("st-swatch-btn-readmore et-st-disabled").removeClass("st-swatch-btn-ready");
					}
				} else {

					$txt = sten_wc_params.select_options_btn_text;
					$label = sten_wc_params.select_options_btn_text;
					$label += ' ' + sten_wc_params.read_more_for_btn_text;
					$btn.removeClass( "st-swatch-btn-ready st-swatch-btn-readmore et-st-disabled" );
				}

				$label += ' "' + $btn.attr('data-product_name') +'"';
				$btn.attr('aria-label', $label);
				$btn.addClass( "st-swatch-btn-text-changed" );
				$btn_text.text($txt);

				let buy_now = $product.find("a.add_to_cart_button.et_product_variable-in-quick-view");

				if( buy_now.length && $variation.is_in_stock ){
					buy_now.text(buy_now.attr('data-text'));
				}
			},

			setVariationAttr: function ( $el, $attr, $value ) {
				if ( undefined === $el.attr( 'data-o_' + $attr ) ) {
					$el.attr( 'data-o_' + $attr, ( !$el.attr( $attr ) ) ? '' : $el.attr( $attr ) );
				}
				if ( false === $value ) {
					$el.removeAttr( $attr );
				} else {
					$el.attr( $attr, $value );
				}
			},

			resetVariationAttr: function ( $el, $attr ) {
				$el.parents( '.content-product, .product-content' ).find( '.et-out-of-stock' ).removeClass('et-out-of-stock');

				if ( undefined !== $el.attr( 'data-o_' + $attr ) ) {

					$el.attr( $attr, $el.attr( 'data-o_' + $attr ) );
				}

				if ( $el && $el.hasClass('lazyloaded') ) {
					$el.removeClass('lazyloaded').addClass('lazyload');
					etTheme.global_image_lazy();
				}
			},

			addSlashes: function ( $string ) {

				$string = $string.toString();
				$string = $string.replace( /'/g, '\\\'' );
				$string = $string.replace( /"/g, '\\\"' );

				return $string;
			},

			addToCart: function(){
				$(document).on("click", ".et-st-disabled", function(e){
					if (etConfig.woocommerceSettings.is_smart_addtocart && ! $(this).parents().is( '.et-quick-view-wrapper' )) {
						e.preventDefault();
					}
					if ($(this).hasClass('et_product_variable-in-quick-view') || $(this).hasClass('product_type_variable') ){
						window.location = $(this).attr('href');
						return false;
					}
				});

				$(document).on("click", "a.product_type_variable.add_to_cart_button.st-swatch-btn-ready", function(e){

					e.preventDefault();

					var	$btn          = $( this ),
						$swatches     = $btn.closest(".product-type-variable").find("div.st-swatch-in-loop"),
						$variation_id = $swatches.attr( 'data-variation_id' );

					if( typeof $variation_id == "undefined" || $variation_id == '' ) {

						return true;
					}

					var	$product_id = $btn.data("product_id"),
						$quantity   = $btn.attr("data-quantity"),
						$item       = {};

					$swatches.find("ul.st-swatch-preview").each(function(){

						var	$attribute     = $(this).data("attribute"),
							$attribute_val = $(this).find("li.selected span").data("value");

						$item[ "attribute_" + $attribute ] = $attribute_val;
					});

					$btn.removeClass( 'added' );
					$btn.addClass( 'loading' );

					var $data = {
						action       :  'sten_wc_product_loop_add_to_cart',
						product_id   :  $product_id,
						quantity     :  $quantity,
						variation_id :  $variation_id,
						variation    :  $item,
					};

					$( 'body' ).trigger( 'adding_to_cart', [ $btn, $data ] );

					$.ajax({
						type    : "POST",
						url     : sten_wc_params.ajax_url,
						data    : $data,
						success : function ( $response ) {

							if( !$response ) {

								return false;
							}

							if ( $response.error && $response.product_url ) {

								window.location = $response.product_url;
								return false;
							}

							// update cart fragment
							var $fragments = $response.fragments,
								$cart_hash = $response.cart_hash;

							if ( $fragments ) {

								$.each( $fragments, function ( $key, $value ) {
									$( $key ).replaceWith( $value );
								} );
							}

							$( '.quick-view-popup .mfp-close, .et-quick-view-canvas .et-close-popup' ).trigger( 'click' );
							$btn.addClass( 'added' );
							$( 'body' ).trigger( 'added_to_cart', [ $fragments, $cart_hash, $btn ] );
							$btn.parents( '.product-type-variable' ).find( '.st-swatch-in-loop .et-delete' ).trigger( 'click' );

							// Redirect for buy now btn
							if ($btn.hasClass('et_product_variable-in-quick-view') && $btn.attr('data-redirect')){
								window.location = $btn.attr('data-redirect');
								return false;
							}
						},
						error   : function ( $error ) {
							console.log( $error );
						}
					});

					e.preventDefault();
				});
			},

			showMoreAttr: function (){
				$(document).on('click', '.et_show-more-attr', function (){
					var loop = $(this).parents('.st-swatch-in-loop');
					loop.find('ul.st-swatch-preview li.hidden:not(.et_show-more-attr)').removeClass('hidden');
					loop.find('ul.st-swatch-preview li.et_show-more-attr').addClass('hidden');
				});
			},
		};

	ST_WC_FRONT_SWATCH.documentOnReady = {

		init : function() {

			ST_WC_Show_selected_title = sten_wc_params.show_selected_title == 'both';

			if ($.inArray(sten_wc_params.show_selected_title, ['mobile', 'desktop']) > -1) {
				let w_width = $(window).width();
				ST_WC_Show_selected_title = (sten_wc_params.show_selected_title == 'mobile' && w_width < 992) || (sten_wc_params.show_selected_title == 'desktop' && w_width >= 993);
			}

			ST_WC_FRONT_SWATCH.onSelect();
			ST_WC_FRONT_SWATCH.singleProductPrimaryAttribute();
			ST_WC_FRONT_SWATCH.onClear();
			ST_WC_FRONT_SWATCH.onSelectUpdateAttribute();

			ST_WC_FRONT_SWATCH.productLoop.init();

		},

		ET_In_Popup: function(){
			$(document).on( 'click', '.st-swatch-et-disabled li span', function(e) {
				if ( $(this).parents().is( '.et-quick-view-wrapper' ) ) return;

				var parent = $(this).parents( '.st-swatch-in-loop' );
				var content_product = parent.parents('.content-product');

				parent.addClass( 'active' );
				$(this).parents('.product-details').addClass('st-swatch-popup-active');

				$.each( parent.closest('div').find( 'div.et_st-default-holder' ), function() {
					var et_html = $(this).html();
					et_html = et_html.replace( 'st-swatch-size-small', 'st-swatch-size-normal' );
					et_html = et_html.replace( 'st-swatch-et-disabled', 'st-swatch-et-anabled' );
					$(this).parents( '.st-swatch-in-loop' ).find( '.st-swatch-preview-wrap .et_st-popup-holder' ).append( et_html );
				});

				var height = parent.find( 'div.et_st-default-holder' ).height();

				parent.find( 'div.et_st-default-holder' ).css( 'min-height', height );
				parent.find( 'div.et_st-default-holder ul' ).remove();
				parent.find( '.sten-reset-loop-variation' ).remove();
				if (!content_product.find('.product-image-wrapper').is('.variation-changed')) {
					content_product.find('.product-image-wrapper').addClass('variation-changed')
				}

				ST_WC_FRONT_SWATCH.productLoop.itemSwatches();
			});

			$(document).on( 'click',  '.st-swatch-in-loop .et-delete', function(e) {
				if ( $(this).parents().is( '.et-quick-view-wrapper' ) ) return;

				var parent = $(this).parents( '.st-swatch-in-loop' );
				var content_product = parent.parents('.content-product');

				$.each( $(this).parents( '.st-swatch-in-loop' ).find( 'div.st-swatch-preview-wrap .et_st-popup-holder ul' ), function() {
					var et_html = $(this).removeClass( 'st-swatch-et-anabled' ).addClass( 'st-swatch-et-disabled' );
					var attribute = $(this).data( 'attribute' );
					$(this).parents( '.st-swatch-in-loop' ).find( 'div.et_st-default-holder[data-et-holder="' + attribute + '"]' ).prepend( et_html );
				});

				$(this).parents( '.st-swatch-in-loop' ).removeClass( 'active' );
				$(this).parents('.product-details').removeClass('st-swatch-popup-active');
				$(this).parents( '.st-swatch-in-loop' ).find( '.st-swatch-preview-wrap .et_st-popup-holder ul' ).remove();
				if (content_product.find('.product-image-wrapper').is('.variation-changed')) {
					content_product.find('.product-image-wrapper').removeClass('variation-changed')
				}

				ST_WC_FRONT_SWATCH.productLoop.itemSwatches();
			});
		},
	};

	$(document).ready( ST_WC_FRONT_SWATCH.documentOnReady.init );
	$(document).ready( ST_WC_FRONT_SWATCH.documentOnReady.ET_In_Popup );


})(jQuery);