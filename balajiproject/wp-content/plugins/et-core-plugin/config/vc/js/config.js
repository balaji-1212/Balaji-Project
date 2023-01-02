(function ($) {

    var vc_panel = $('#vc_ui-panel-edit-element');

    vc_panel.on('vcPanel.shown', function () {
        if (typeof tinyMCE !== 'undefined') {
            if (tinyMCE.get('wpb_tinymce_content')) {
                var _formated_content = tinyMCE.get('wpb_tinymce_content').getContent();
                _formated_content = _formated_content.replace(/<\/p><p>\s<\/p>/g, '</p>');
            }
            tinyMCE.EditorManager.execCommand('mceRemoveEditor', true, 'wpb_tinymce_content');
        }

        $('.vc_wrapper-param-type-xstore_title_divider').each(function () {
            var $divider = $(this);
            var $fields = $divider.nextUntil('.vc_wrapper-param-type-xstore_title_divider');
            var $wrapper = $('<div class="et-td-wrapper"></div>');
            var $content = $('<div class="et-td-content"></div>');

            $divider.before($wrapper);
            $wrapper.append($divider);

            if ($fields.length) {
                $content.append($fields);
                $wrapper.append($content);
            }

            if ( $divider.hasClass('css_box_tabs') ) {

                var $tabs_wrapper = $('<div class="et-tabs-css-wrapper">'+
                    '<span class="et_tab mtips active" data-query="global"><i class="vc-composer-icon vc-c-icon-layout_default"></i><span class="mt-mes">Desktop</span></span>'+
                    '<span class="et_tab mtips" data-query="tablet"><i class="vc-composer-icon vc-c-icon-layout_landscape-tablets"></i><span class="mt-mes">Tablet landscape</span></span>'+
                    '<span class="et_tab mtips" data-query="ipad"><i class="vc-composer-icon vc-c-icon-layout_portrait-tablets"></i><span class="mt-mes">Tablet portrait</span></span>'+
                    '<span class="et_tab mtips" data-query="mobile"><i class="vc-composer-icon vc-c-icon-layout_portrait-smartphones"></i><span class="mt-mes">Mobile</span></span>'+
                '</div>');

                $divider.append($tabs_wrapper);

            }
        });

        if (typeof tinyMCE !== 'undefined') {
            tinyMCE.EditorManager.execCommand('mceAddEditor', true, 'wpb_tinymce_content');
            if (typeof _formated_content !== typeof undefined) {
                tinyMCE.get('wpb_tinymce_content').setContent(_formated_content);
            }
        }

        vc_panel.trigger('etDivider.added');

    });

    // fix to prevent multipopups window yeahahhhhhh
    $(document).ajaxComplete(function (event, xhr, settings) {
        if ( xhr.status == 200 && xhr.responseText && settings.data !== undefined && typeof settings.data == 'string' && 0 <= settings.data.indexOf( 'action=vc_edit_form' ) ) {
            if ( wp.media ) {
                wp.media.view.Modal.prototype.on('close', function () {
                    setTimeout(function () {
                        $('.supports-drag-drop').css('display', 'none');
                    }, 1000)
                });
            }
        }
    });

    // tabs 
    vc_panel.on('click', '.et-tabs-css-wrapper .et_tab', function() {
        $(this).parent().find('.et_tab').removeClass('active');
        $(this).addClass('active');
        $(this).parents('.et-td-wrapper').find('.et_css-query').addClass('vc_dependent-hidden');
        $(this).parents('.et-td-wrapper').find('.et_css-query-'+$(this).attr('data-query')).removeClass('vc_dependent-hidden');
    });

    function hide_title_divider($divider) {
        var $wrapper = $divider.parent('.et-td-wrapper');
        if ($divider.hasClass('vc_dependent-hidden')) {
            $wrapper.addClass('vc_dependent-hidden');
        } else {
            $wrapper.removeClass('vc_dependent-hidden');
        }
    }

    vc_panel.on('change', '.wpb_el_type_xstore_title_divider', function () {
        hide_title_divider($(this))
    });

    vc_panel.on('etDivider.added', function () {
        $('.wpb_el_type_xstore_title_divider').each(function () {
            hide_title_divider($(this))
        });
    });

    $('#vc_ui-panel-edit-element').on('vcPanel.shown', function () {
        var vc_panel = $(this);

        // hints 
        vc_panel.find('.vc_shortcode-param').each(function () {
            var $this = $(this);
            var settings = $this.data('param_settings');

            if (typeof settings != 'undefined' && typeof settings.hint != 'undefined') {
                $this.find('.wpb_element_label').addClass('with-mtips').append('<span class="mtips mtips-right"><span class="dashicons dashicons-editor-help" style="vertical-align: -20%;margin-left: 3px;cursor: help;"></span><span class="mt-mes">' + settings.hint + '</span></span>');
            }
        });

        // sliders
        var $sliders = $('.xstore-vc-slider');
        $sliders.each(function () {
            var $this = $(this);
            var $slider = $this.find('input.xstore-slider-field-value');
            var $text = $this.find('.xstore-slider-field-value');

            $slider.on('change input', function() {
                $text.html($(this).val());
            });

        });

        // font-size options 
        transferCustomSizeOptions({
            oldSizes: {
                desktop: $('[data-vc-shortcode-param-name="title_font_container"] .vc_font_container_form_field-font_size-input'),
                tablet: $('[data-vc-shortcode-param-name="title_font_container"] .vc_font_container_form_field-font_size-input'),
                mobile: $('[data-vc-shortcode-param-name="title_font_container"] .vc_font_container_form_field-font_size-input'),
            },
            newOptSelector: $('.title_responsive_font_size'),
        });

        transferCustomSizeOptions({
            oldSizes: {
                desktop: $('[data-vc-shortcode-param-name="subtitle_font_container"] .vc_font_container_form_field-font_size-input'),
                tablet: $('[data-vc-shortcode-param-name="subtitle_font_container"] .vc_font_container_form_field-font_size-input'),
                mobile: $('[data-vc-shortcode-param-name="subtitle_font_container"] .vc_font_container_form_field-font_size-input'),
            },
            newOptSelector: $('.subtitle_responsive_font_size'),
        });

        transferCustomSizeOptions({
            oldSizes: {
                desktop: $('.et_old-title-font_size-wrapper input.wpb_vc_param_value'),
                tablet: $('.et_old-title-font_size-wrapper input.wpb_vc_param_value'),
                mobile: $('.et_old-title-font_size-wrapper input.wpb_vc_param_value'),
            },
            newOptSelector: $('.title_responsive_font_size'),
            responsive: {
                tablet: '32px',
                mobile: '24px'
            }
        });

        transferCustomSizeOptions({
            oldSizes: {
                desktop: $('.et_old-subtitle-font_size-wrapper input.wpb_vc_param_value'),
                tablet: $('.et_old-subtitle-font_size-wrapper input.wpb_vc_param_value'),
                mobile: $('.et_old-subtitle-font_size-wrapper input.wpb_vc_param_value'),
            },
            newOptSelector: $('.subtitle_responsive_font_size'),
        });

        // line-height options 
        transferCustomSizeOptions({
            oldSizes: {
                desktop: $('[data-vc-shortcode-param-name="title_font_container"] .vc_font_container_form_field-line_height-input'),
                tablet: $('[data-vc-shortcode-param-name="title_font_container"] .vc_font_container_form_field-line_height-input'),
                mobile: $('[data-vc-shortcode-param-name="title_font_container"] .vc_font_container_form_field-line_height-input'),
            },
            newOptSelector: $('.title_responsive_line_height'),
        });

        transferCustomSizeOptions({
            oldSizes: {
                desktop: $('[data-vc-shortcode-param-name="subtitle_font_container"] .vc_font_container_form_field-line_height-input'),
                tablet: $('[data-vc-shortcode-param-name="subtitle_font_container"] .vc_font_container_form_field-line_height-input'),
                mobile: $('[data-vc-shortcode-param-name="subtitle_font_container"] .vc_font_container_form_field-line_height-input'),
            },
            newOptSelector: $('.subtitle_responsive_line_height'),
        });

        transferCustomSizeOptions({
            oldSizes: {
                desktop: $('.et_old-title-line_height-wrapper input.wpb_vc_param_value'),
                tablet: $('.et_old-title-line_height-wrapper input.wpb_vc_param_value'),
                mobile: $('.et_old-title-line_height-wrapper input.wpb_vc_param_value'),
            },
            newOptSelector: $('.title_responsive_line_height'),
            responsive: {
                tablet: '36px',
                mobile: '28px'
            }
        });

        transferCustomSizeOptions({
            oldSizes: {
                desktop: $('.et_old-subtitle-line_height-wrapper input.wpb_vc_param_value'),
                tablet: $('.et_old-subtitle-line_height-wrapper input.wpb_vc_param_value'),
                mobile: $('.et_old-subtitle-line_height-wrapper input.wpb_vc_param_value'),
            },
            newOptSelector: $('.subtitle_responsive_line_height'),
        });

        // letter-spacing options 
        transferCustomSizeOptions({
            oldSizes: {
                desktop: $('.et_old-title-letter_spacing-wrapper input.wpb_vc_param_value'),
                tablet: $('.et_old-title-letter_spacing-wrapper input.wpb_vc_param_value'),
                mobile: $('.et_old-title-letter_spacing-wrapper input.wpb_vc_param_value'),
            },
            newOptSelector: $('.title_responsive_letter_spacing'),
        });

        transferCustomSizeOptions({
            oldSizes: {
                desktop: $('.et_old-subtitle-letter_spacing-wrapper input.wpb_vc_param_value'),
                tablet: $('.et_old-subtitle-letter_spacing-wrapper input.wpb_vc_param_value'),
                mobile: $('.et_old-subtitle-letter_spacing-wrapper input.wpb_vc_param_value'),
            },
            newOptSelector: $('.subtitle_responsive_letter_spacing'),
        });

        function transferCustomSizeOptions(args) {
            if (args.newOptSelector.length == 0) return;

            $.each(args.oldSizes, function (key, value) {
                if (!value.val()) return;
                var units = parseInt(value.val());
                units = value.val().replace(units, '');
                args.newOptSelector.find('.xstore-rs-unit select').val(units);
                if ( args.responsive === undefined ) {
                    args.newOptSelector.find('input[data-id="desktop"], input[data-id="tablet"], input[data-id="mobile"]').val(parseFloat(value.val()));
                }
                else {
                    args.newOptSelector.find('input[data-id="desktop"]').val(parseFloat(value.val()));
                    args.newOptSelector.find('input[data-id="tablet"]').val(parseFloat(args.responsive.tablet));
                    args.newOptSelector.find('input[data-id="mobile"]').val(parseFloat(args.responsive.mobile));
                }
                args.newOptSelector.find('.xstore-rs-item').removeClass('hidden');
                args.newOptSelector.find('.xstore-rs-trigger').addClass('opened');
                args.newOptSelector.find('.xstore-rs-value').val('');
                value.val('');
            });
        }

        $('.et_font-size-wrapper').each(function () {
            var $this = $(this);
            var $fontContainer = $(this).parent().find('.vc_wrapper-param-type-font_container');
            var $fontSizeOld = $fontContainer.find('.vc_font_container_form_field-font_size-container').parent();

            var $fontSizeOld2 = $(this).parent().find('.et_old-font_size-wrapper');

            $fontSizeOld.addClass('hidden').before($this);
            $fontSizeOld2.addClass('hidden').before($this);
        });

        $('.et_line-height-wrapper').each(function () {
            var $this = $(this);
            var $fontContainer = $(this).parent().find('.vc_wrapper-param-type-font_container');
            var $lineHeightSizeOld = $fontContainer.find('.vc_font_container_form_field-line_height-container').parent();

            var $fontSizeOld2 = $(this).parent().find('.et_old-line_height-wrapper');

            $lineHeightSizeOld.addClass('hidden').before($this);
            $fontSizeOld2.addClass('hidden').before($this);
        });

        $('.et_letter-spacing-wrapper').each(function () {
            var $this = $(this);
            var $fontContainer = $(this).parent().find('.vc_wrapper-param-type-font_container');
            var $spacingOld = $fontContainer.find('.vc_font_container_form_field-letter_spacing-container').parent();

            var $spacingOld2 = $(this).parent().find('.et_old-letter_spacing-wrapper');

            $spacingOld.addClass('hidden').before($this);
            $spacingOld2.addClass('hidden').before($this);
        });

        //Size options
        $('.xstore-rs-wrapper').each(function () {
            var $this = $(this);
            setInputsValue($this);
            setMainValue($this);
        });

        $('.xstore-rs-input, .xstore-rs-unit select').on('change', function () {
            var $wrapper = $(this).parents('.xstore-rs-wrapper');
            setMainValue($wrapper);
        });

        $('.xstore-rs-trigger').on('click', function () {
            var $wrapper = $(this).parents('.xstore-rs-wrapper');
            $(this).toggleClass('opened');
            $wrapper.find('.xstore-rs-item.tablet,.xstore-rs-item.mobile').toggleClass('hidden');
        });

        function setMainValue($this) {
            var $mainInput = $this.find('.xstore-rs-value');
            var results = {
                param_type: 'xstore_responsive_size',
                data: {}
            };

            var units = $this.find('.xstore-rs-unit select').val();
            units = units == null ? '' : units;
            results.data['units'] = units;

            $this.find('.xstore-rs-input').each(function (index, elm) {
                var value = $(elm).val();
                var responsive = $(elm).data('id');
                if (value) {
                    results.data[responsive] = value + units;
                }
            });

            if ($.isEmptyObject(results.data)) {
                results = '';
            } else {
                results = window.btoa(JSON.stringify(results));
            }

            $mainInput.val(results).trigger('change');
        }

        function setInputsValue($this) {
            var $mainInput = $this.find('.xstore-rs-value');
            var mainInputVal = $mainInput.val();
            var toggle = {};

            if (mainInputVal) {
                var parseVal = JSON.parse(window.atob(mainInputVal));

                $.each(parseVal.data, function (key, value) {
                    $this.find('.xstore-rs-input').each(function (index, element) {
                        var dataid = $(element).data('id');

                        if (dataid == key) {
                            $(element).val(parseFloat(value));
                            //Toggle
                            toggle[dataid] = value;
                        }
                    });

                    if ( key == 'units' ) {
                        $this.find('.xstore-rs-unit select').val(value);
                    }

                });
            }

            //Toggle
            function size(obj) {
                var size = 0, key;
                for (key in obj) {
                    if (obj.hasOwnProperty(key)) size++;
                }
                return size;
            };

            var size = size(toggle);

            if (size >= 2) {
                $this.find('.xstore-rs-item').removeClass('hidden');
            }
        }

        // Image select 
        $('.xstore-vc-image-select').each(function () {
            var $select = $(this);
            var $input = $select.find('.xstore-vc-image-select-input');
            var inputValue = $input.attr('value');
            $select.find('li[data-value="' + inputValue + '"]').addClass('active');
            $select.find('li').click(function () {
                var $this = $(this),
                    dataValue = $this.data('value');

                $this.siblings().removeClass('active');
                $this.addClass('active');
                $input.attr('value', dataValue).trigger('change');
            });
        });

        $('.xstore-vc-button-set').each(function () {
            var $this = $(this);
            var currentValue = $this.hasClass('et-font_container') ? $this.parent().find('.vc_font_container_form_field-'+$(this).data('type')+'-select').val() : $this.find('.xstore-vc-button-set-value').val();

            $this.find('[data-value="' + currentValue + '"]').addClass('active');
        });

        $('.vc-button-set-item').on('click', function () {
            var $this = $(this);
            var $button_set = $this.parents('.xstore-vc-button-set');
            var value = $this.data('value');

            $this.addClass('active');
            $this.siblings().removeClass('active');
            $button_set.find('.xstore-vc-button-set-value').val(value).trigger('change');

            if ( $button_set.hasClass('et-font_container') ) {
                $button_set.parent().find('.vc_font_container_form_field-' + $($button_set).data('type') + '-select').val(value).trigger('change');
            }
        });

    });

    $(document).on('click', '.et_popup-import-single-page .et_popup-import-plugin-btn:not(.et_plugin-installing, .et_plugin-installed)', function (e) {
        e.preventDefault();
        var popup = '';
        if ($(this).hasClass('et_core-plugin')) {
            popup = $('.etheme-registration');
        } else {
            popup = $('.et_panel-popup.panel-popup-import.active, .et_panel-popup.et_popup-import-single-page');
        }
        var $el = $(this),
            li = $el.parents('li'),
            data = {
                action: 'envato_setup_plugins',
                helper:'plugins',
                slug: $el.attr('data-slug'),
                wpnonce: popup.find('.et_plugin-nonce').attr('data-plugin-nonce'),
            },
            current_item_hash = '';

        popup.find('.et_popup-import-plugins').addClass('ajax-processing');
        $el.addClass('et_plugin-installing');
        li.addClass('processing');

        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: data,
            success: function (response) {
                if (response.hash != current_item_hash) {
                    $.ajax({
                        method: "POST",
                        url: response.url,
                        data: response,
                        success: function (response) {
                            li.addClass('activated');
                            $el.removeClass('et_plugin-installing').addClass('et_plugin-installed green-color').text('Activated').attr('style', null);
                            $el.parents('.et_popup-import-plugin').find('.dashicons').addClass('dashicons-yes-alt green-color hidden').removeClass('dashicons-warning orange-color red-color');
                            if ($el.hasClass('et_core-plugin')) {
                                $('.etheme-page-nav .mtips').removeClass('inactive mtips');
                                window.location = $('.etheme-page-nav .et-nav-portfolio').attr('href');
                                $el.css('pointer-events', 'none');
                                $('.mt-mes').remove();
                            }
                        },
                        error: function () {
                            //$el.removeClass('et_plugin-installing').addClass('et_plugin-installed red-color').text('Failed').attr('style', null);
                            //$el.parents('.et_popup-import-plugin').find('.dashicons').addClass('red-color').removeClass('orange-color');
                        },
                        complete: function () {
                            li.removeClass('processing');
                            $el.removeClass('loading');
                            popup.find('.et_popup-import-plugins').removeClass('ajax-processing');
                            // second chance for plugins
                            if (!$el.hasClass('et_second-try')) {
                                $el.removeClass('et_plugin-installed').addClass('et_second-try');
                                $el.trigger('click');
                            } else if (popup.hasClass('et_popup-import-single-page')) {
                                popup.find('.et_install-page-content').removeClass('et-button-grey2').addClass('et-button-green').html(popup.find('.et_install-page-content').attr('data-text'));
                            }
                        }
                    });
                    if(response.slug == 'et-core-plugin'){
                        if (! response.ET_VERSION_COMPARE){
                        } else {
                            $('.et_step-versions-compare').addClass('required');
                            var version_info = $('.et-theme-version-info');
                            var version_info_html = version_info.html();
                            version_info_html = version_info_html.replace('{{{version}}}', response.ET_CORE_THEME_MIN_VERSION);
                            version_info.html(version_info_html);
                            $('body').removeClass('et_step-child_theme-step')
                        }
                    }
                } else {
                    $el.removeClass('et_plugin-installing').addClass('et_plugin-installed installing-error red-color').text('Failed').attr('style', null);
                }
            },
            error: function (response) {
                $el.removeClass('et_plugin-installing').addClass('et_plugin-installed installing-error red-color').text('Failed').attr('style', null);
                li.removeClass('processing');
                $el.removeClass('loading');
                popup.find('.et_popup-import-plugins').removeClass('ajax-processing');
            },
            complete: function (response) {
            }
        });
    });

})(jQuery);