!(function (e) {
    "use strict";
    jQuery(window).on("elementor:init", function () {

        var t = elementor.modules.controls.BaseData.extend({
            isPostSearchReady: !1,
            dataQueryOption: function () {
                var e = this,
                    t = e.model.get("data_options");
                return !(!t && "object" != typeof t) && t;
            },
            onReady: function () {
            	var t = this;
                this.dataQueryOption() && ( this.onInputChange(), this.isPostSearchReady || this.getSelected() );
            },
            onInputChange: function () {
                var t = this, 
                n = this.getControlValue();

                if (n && 0 !== n.length) {
                    _.isArray(n) || (n = [n]);
                }
                t.ui.select.select2({
                		placeholder: t.model.get("placeholder") ? t.model.get("placeholder") : "Search",
                		minimumInputLength: t.model.get("mininput") ? t.model.get("mininput") : 0,
                		language: {
                			noResults: function() {
                				return 'Type something to search';
                			}
                		}
                	}
                );

                t.ui.select.on('select2:open', function () {
                	t.$previewContainer = jQuery('.select2-results__options[role="tree"]:visible');

                	var typingTimer;
                	var doneTypingInterval = 1500;
                	var $input = ( t.model.get("multiple") ) ? t.$el.find('input.select2-search__field') : jQuery('.select2-container .select2-dropdown .select2-search input.select2-search__field');

					jQuery(document).on('keyup', $input, function () {
                		clearTimeout(typingTimer);

                		typingTimer = setTimeout(function() {
                            let search = $input.val();
                            if ( '' == search ) {
                                return;
                            }

                            n = t.getControlValue();

                            if (n && 0 !== n.length) {
                                _.isArray(n) || (n = [n]);
                            }
                			t.addControlSpinner();
                			e.ajax({
                				type: "POST",
                				url: ajaxproduct.ajaxurl,
                				data: {
                					action: 'select2_control',
                					options: t.model.get("data_options"),
                                    old_option: n,
                                    security: t.$el.find(".elementor-control-input-wrapper").attr('data-nonce'),
                					search: search,
                				},
                                dataType: "json",
                                success: function (e) {
                                	t.removeControlSpinner();
                                	(t.isPostSearchReady = !0), t.model.set("options", e ), t.render();
                                	t.ui.select.select2('open');
                                },
                			});
                		}, doneTypingInterval);
                	});

                    $input.on('keydown', function () {
                        clearTimeout(typingTimer);
                    });

                });

            },
            getSelected: function () {
            	var t = this,
            	o = this.dataQueryOption(),
            	n = this.getControlValue();
            	if (n && 0 !== n.length) {
            		_.isArray(n) || (n = [n]);
            		t.addControlSpinner();
            		e.ajax({
            			url: ajaxurl,
            			type: "POST",
            			data: {
            				action: 'select2_control',
            				options: t.model.get("data_options"),
                            security: t.$el.find(".elementor-control-input-wrapper").attr('data-nonce'),
            				id: n 
            			},
            			success: function (e) {
            				t.removeControlSpinner();
            				(t.isPostSearchReady = !0), t.model.set("options", e), t.render();
            			},
            		});
            	}
            },
            addControlSpinner: function () {
            	this.ui.select.prop("disabled", !0), this.$el.find(".elementor-control-input-wrapper").before('<span class="elementor-control-spinner">&nbsp;<i class="eicon-spinner eicon-animation-spin"></i>&nbsp;</span>');
            },           
            removeControlSpinner: function () {
            	this.ui.select.prop("disabled", !1), this.$el.find(".elementor-control-input-wrapper").parent().remove('.elementor-control-spinner');
            },
            onBeforeDestroy: function () {
            	this.ui.select.data("select2") && this.ui.select.select2("destroy"), this.$el.remove();
            },
        });

        elementor.addControlView("etheme-ajax-product", t);
    });
})(jQuery);
