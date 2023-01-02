jQuery(document).ready(function ($) {
    'use strict';

    let previewContent = $('.viwec-email-preview-content');
    let modalParent = previewContent.parents('.modal');

    ViWec.Builder.init();

    $('.viwec-show-sub-actions').on('click', function () {
        $('.viwec-actions-back').slideToggle();
    });

    $('.viwec-order-id-test').on('change', function () {
        viWecChange = true;
    });

    if (viWecShortcodeListValue) {
        let title = $('#title[name=post_title]'), titlePos,
            viWecQuickSC = $('.viwec-subject-quick-shortcode');

        title.on('focusout', function () {
            titlePos = this.selectionStart;
        });

        viWecQuickSC.append('<ul>' + viWecShortcodeListValue + '</ul>');
        viWecQuickSC.on('click', '.dashicons-menu', function () {
            viWecQuickSC.find('ul').toggle('fast');
        });

        viWecQuickSC.on('click', 'li', function () {
            let currentText = title.val(),
                sc = $(this).text(), newText;

            if (titlePos) {
                let before = currentText.substr(0, titlePos);
                let after = currentText.substr(titlePos + 1);
                newText = before + sc + after;
            } else {
                newText = currentText + sc;
            }

            title.val(newText).focus();
            $('#title-prompt-text').addClass('screen-reader-text');
            viWecQuickSC.find('ul').toggle('fast');
        });
    }

    //Rebuild viewer from db

    const viWecAttributeGroup = (key) => {
        if (['padding-left', 'padding-right', 'padding-top', 'padding-bottom'].includes(key)) {
            key = 'padding';
        }

        if (['border-top-left-radius', 'border-top-right-radius', 'border-bottom-left-radius', 'border-bottom-right-radius'].includes(key)) {
            key = 'border-radius';
        }

        if (['border-top-width', 'border-left-width', 'border-bottom-width', 'border-right-width'].includes(key)) {
            key = 'border-width';
        }

        return key;
    };

    const viWecFixColor = (string) => {
        if (string) {
            let patern = /rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?\)/i;
            let rgb = string.match(patern);
            if (rgb) {
                let hex = viWecRgb2hex(rgb[0]);
                string = string.replace(patern, hex);
            }
            let pattern000 = /rgba?[\s+]?\([\s+]?(0)[\s+]?,[\s+]?(0)[\s+]?,[\s+]?(0)[\s+]?,[\s+]?(0)[\s+]?\)/i;
            let rgba000 = string.match(pattern000);
            if (rgba000) {
                string = 'transparent';
            }
            string = string.replace(/"/gi, "");
            return string;
        }
        return '';
    };

    const viWecGetElCss = (element, type) => {
        let component = ViWec.Components._components[type],
            properties = component.properties,
            content = {}, attrs = {}, childStyle = {}, style = {};

        style['width'] = $(element).css('width');

        for (let i in properties) {
            if (properties[i].htmlAttr) {
                let _element = properties[i].target ? $(element).find(properties[i].target) : $(element);
                let _key = viWecAttributeGroup(properties[i].key);

                switch (properties[i].htmlAttr) {
                    case 'innerHTML':
                        content[_key] = _element.html();
                        break;

                    case 'style':
                        let css;
                        css = ViWec.StyleManager.getStyle(_element, properties[i], _key); //_element.css(_key)
                        if (css) style[_key] = viWecFixColor(css);

                        break;

                    case 'childStyle':
                        if (!childStyle[properties[i].target]) childStyle[properties[i].target] = {};
                        let childCss = ViWec.StyleManager.getStyle(_element, properties[i], _key);

                        if (childCss) childStyle[properties[i].target][_key] = viWecFixColor(childCss);

                        break;

                    default :
                        let attr = _element.attr(properties[i].htmlAttr),
                            defaultValue = properties[i].default ? properties[i].default : '';
                        attr = attr ? attr : defaultValue;
                        attrs[_key] = attr;
                        break;
                }
            }
        }

        return {type: type, style: style, content: content, attrs: attrs, childStyle: childStyle};
    };

    const viWecGetRowCss = (element, type) => {

        let component = ViWec.Components._components[type],
            properties = component ? component.properties : '',
            style = {};

        if (properties) {
            for (let i in properties) {
                if (properties[i].htmlAttr) {
                    let _key = viWecAttributeGroup(properties[i].key);
                    // let css = element.css(_key);
                    let el = (element.get(0)), css;

                    if (el.style && el.style.length > 0 && el.style[_key])//check inline
                        css = el.style[_key];
                    else if (el.currentStyle)	//check defined css
                        css = el.currentStyle[_key];
                    else if (window.getComputedStyle) {
                        css = document.defaultView.getDefaultComputedStyle ?
                            document.defaultView.getDefaultComputedStyle(el, null).getPropertyValue(_key) :
                            window.getComputedStyle(el, null).getPropertyValue(_key);
                    }

                    if (css) style[_key] = viWecFixColor(css);

                }
            }
            style['width'] = element.css('width')
        }
        return style;
    };

    const getEmailStructure = () => {
        let dataArray = {}, container = $('#viwec-email-editor-wrapper');

        dataArray['style_container'] = {
            'background-color': viWecFixColor(container.css('background-color')),
            'background-image': container.css('background-image')
        };
        dataArray['rows'] = {};

        $(viWecEditorArea).find('.viwec-layout-row').each(function (rowIndex, row) { //loop rows
            //get row style
            let type = $(row).attr('data-type'), dataCols = $(row).attr('data-cols'),
                rowOuterStyle = viWecGetRowCss($(row), type);

            dataArray['rows'][rowIndex] = {props: {style_outer: rowOuterStyle, type: type, dataCols: dataCols}, cols: {}}; //style_inner: rowInnerStyle,

            //get columns
            let col = $(row).find('.viwec-column-sortable');
            if (col.length) {
                col.each(function (colIndex, col) { //loop cols
                    // let colStyle = viWecFixColor($(col).attr('style'));
                    let colStyle = viWecGetRowCss($(col), 'layout/grid1cols');
                    dataArray['rows'][rowIndex]['cols'][colIndex] = {props: {style: colStyle}, elements: {}};

                    //get elements
                    let elements = $(col).find('.viwec-element');
                    if (elements.length) {
                        elements.each(function (elIndex, element) {
                            let type = $(element).data('type');
                            dataArray['rows'][rowIndex]['cols'][colIndex]['elements'][elIndex] = viWecGetElCss(element, type);
                        })
                    }
                })
            }
        });
        // console.log(dataArray);
        // debugger
        return JSON.stringify(dataArray);
    };

    $('#save-post').on('click', function () {
        $("input[name=post_status]").val('draft');
    });

    $('form').on('submit', function (e) {
        $(window).unbind('beforeunload');
        $("<input/>").attr({type: 'hidden', name: 'viwec_email_structure', value: getEmailStructure()}).appendTo("form#post");
        return true;
    });

    function viWecPreview() {
        let data = {action: 'viwec_preview_template', nonce: viWecParams.nonce, data: getEmailStructure(), order_id: $('.viwec-order-id-test').val()};
        let button = $(this);
        let modal = $('.vi-ui.modal');

        if (viWecChange === false) {
            modal.modal('show');
            if (button.hasClass('mobile')) {
                previewContent.addClass('viwec-mobile-preview');
            }
            if (button.hasClass('desktop')) {
                previewContent.removeClass('viwec-mobile-preview');
            }
        } else {
            $.ajax({
                url: viWecParams.ajaxUrl,
                type: 'post',
                data: data,
                beforeSend: function () {
                    button.addClass('loading').unbind();
                },
                success: function (res) {
                    if (res) {
                        modal.find('.viwec-email-preview-content').html(res);
                        modal.modal('show');

                        $('.viwec-email-preview-content a').on('click', function (e) {
                            e.preventDefault();
                            e.stopImmediatePropagation();
                        });
                    }
                },
                error: function (res) {
                    console.log(res);
                },
                complete: function () {
                    button.removeClass('loading');
                    button.bind('click', viWecPreview);
                    viWecChange = false;
                    if (button.hasClass('mobile')) {
                        previewContent.addClass('viwec-mobile-preview');
                    }
                    if (button.hasClass('desktop')) {
                        previewContent.removeClass('viwec-mobile-preview');
                    }
                }
            });
        }
    }

    $('.viwec-preview-email-btn').on('click', viWecPreview);

    function viWecSendTestEmail() {
        let button = $('.viwec-send-test-email-btn');
        let email = $('.viwec-to-email').val();

        if (!email) {
            alert('Please input your email');
            $('.vi-ui.modal').modal('hide');
            return;
        }

        let data = {
            action: 'viwec_send_test_email',
            nonce: viWecParams.nonce,
            data: getEmailStructure(),
            order_id: $('.viwec-order-id-test').val(),
            email: email
        };
        $.ajax({
            url: viWecParams.ajaxUrl,
            type: 'post',
            dataType: 'json',
            data: data,
            beforeSend: function () {
                button.addClass('loading').unbind();
            },
            success: function (res) {
                let resultNotice = $('.viwec-send-test-email-result');
                res.success ? resultNotice.html(`<span class="viwec-success">${res.data}</span>`) : resultNotice.html(`<span class="viwec-error">${res.data}</span>`);
                res.success ? resultNotice.removeClass('et-error').addClass('et-success') : resultNotice.removeClass('et-success').addClass('et-error');
                setTimeout(function () {
                    $('.viwec-send-test-email-result').html('');
                }, 3000);
            },
            error: function (res) {
                console.log(res);
            },
            complete: function () {
                button.removeClass('loading').bind('click', viWecSendTestEmail);
                $('.vi-ui.modal').modal('hide');
            }
        });
    }

    $('.viwec-send-test-email-btn').on('click', viWecSendTestEmail);

    $('.viwec-mobile-view').on('click', function () {
        previewContent.addClass('viwec-mobile-preview');
    });

    $('.viwec-pc-view').on('click', function () {
        previewContent.removeClass('viwec-mobile-preview');
    });

    ViWec.viWecDrawTemplate = (viWecTemplate) => {
        if (!viWecTemplate) {
            return;
        }
        $(viWecEditorArea).empty();

        $('#viwec-email-editor-wrapper').css(viWecTemplate['style_container']);

        for (let rowIndex in viWecTemplate['rows']) {
            if ($.isEmptyObject(viWecTemplate['rows'][rowIndex]['cols'])) continue;

            let row = $(viWecTmpl('viwec-block', {
                type: viWecTemplate['rows'][rowIndex].props.type,
                colsQty: viWecTemplate['rows'][rowIndex].props.dataCols
            }));
            row.find('.viwec-layout-row').css(viWecTemplate['rows'][rowIndex].props.style_outer);

            row.find('.viwec-column').each(function (colIndex) {
                let col = $(this);
                if (!$.isEmptyObject(viWecTemplate['rows'][rowIndex]['cols'][colIndex].elements)) {
                    col.removeClass('viwec-column-placeholder')
                }

                col.find('.viwec-column-sortable').css(viWecTemplate['rows'][rowIndex]['cols'][colIndex].props.style);

                for (let elIndex in viWecTemplate['rows'][rowIndex]['cols'][colIndex]['elements']) {
                    let el = viWecTemplate['rows'][rowIndex]['cols'][colIndex]['elements'][elIndex],
                        type = el.type, style = el.style, content = el.content,
                        attrs = el.attrs.length !== 0 ? el.attrs : {}, childStyle = el.childStyle;

                    let component = ViWec.Components._components[type];

                    if (typeof component === 'undefined') continue;

                    let properties = component.properties,
                        element = $(`<div class="viwec-element" data-type="${type}"></div>`).append(component.html);

                    for (let i in properties) {
                        if (properties[i].htmlAttr && properties[i].visible !== false) {
                            let _element = properties[i].target ? element.find(properties[i].target) : element;
                            switch (properties[i].htmlAttr) {
                                case 'innerHTML':
                                    if (properties[i].renderShortcode) {
                                        let clone = _element.clone();
                                        clone = clone.removeClass().html(ViWec.viWecReplaceShortcode(content[properties[i].key])).addClass('viwec-text-view');
                                        _element.html(content[properties[i].key]).hide();
                                        _element.after(clone);
                                    } else {
                                        _element.html(content[properties[i].key]);
                                    }
                                    if (typeof properties[i].onChange === 'function') {
                                        properties[i].onChange(_element, content[properties[i].key]);
                                    }
                                    break;
                                case 'style':
                                    if (style) _element.css(style);
                                    break;
                                case 'childStyle':
                                    if (childStyle[properties[i].target]) _element.css(childStyle[properties[i].target]);
                                    break;
                                default:
                                    _element.attr(properties[i].htmlAttr, attrs[properties[i].key]);
                                    if (typeof properties[i].onChange === 'function') {
                                        let viewValue = ViWec.viWecReplaceShortcode(attrs[properties[i].key]);
                                        properties[i].onChange(_element, attrs[properties[i].key], viewValue);
                                    }
                                    break;
                            }
                        }
                    }
                    element.handleElement();
                    col.find('.viwec-column-sortable').append(element);
                }

                col.handleColumn();
                col.find('.viwec-column-sortable').columnSortAble();
                row.find('.viwec-flex').append(col);
            });

            row.handleRow();

            row.appendTo(viWecEditorArea);
        }
    };

    if (typeof viWecLoadTemplate !== 'undefined') {
        let viWecTemplate = JSON.parse(viWecLoadTemplate);
        ViWec.viWecDrawTemplate(viWecTemplate);
    }

    $('.viwec-export-data').on('click', function () {
        let data = getEmailStructure();
        let regex = new RegExp(viWecParams.siteUrl, 'g');
        data = data.replace(regex, '{_site_url}');
        $('#viwec-exim-data').val(data);
    });

    $('.viwec-import-data').on('click', function () {
        let data = $('#viwec-exim-data').val();
        data = data.replace(/{_site_url}/g, viWecParams.siteUrl);
        if (data) {
            ViWec.viWecDrawTemplate(JSON.parse(data));
        }
    });

    $('.viwec-copy-data').on('click', function () {
        $('#viwec-exim-data').select();
        document.execCommand("copy");
    });

    $('#viwec-element-search input.viwec-search').on('keyup', function () {
        let keyword = $(this).val().toUpperCase(), li = $('#viwec-components-list li');
        for (let i = 0; i < li.length; i++) {
            let a = $(li[i]).find('.viwec-ctrl-title');
            let txtValue = a.text();
            if (txtValue.toUpperCase().indexOf(keyword) > -1) {
                $(li[i]).show()
            } else {
                $(li[i]).hide()
            }
        }
    });

    const runApp = {
        init() {
            let emailTypeSelect = $('.viwec-set-email-type');
            this.setupPage();
            this.setupPreviewModal();
            this.emailTypeChange();
            this.hideRules(emailTypeSelect.val());
            this.hideElements(emailTypeSelect.val());
            this.addNewTemplate();
        },

        setupPage() {
            $(window).bind('beforeunload');

            //Toggle admin menu
            if ($(document).width() <= 1400) {
                $('body').addClass('folded');
            }

            //Remove metabox handle
            $('.hndle').removeClass('hndle');

            //Block enter key
            $('form').bind('keypress', function (e) {
                if (e.keyCode === 13) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                }
            });

            //Hide quick shortcode
            $(document).on('click', function (e) {
                if (!$(e.target).is('.viwec-quick-shortcode-list') && !$(e.target).is('.dashicons.dashicons-menu')) {
                    $('.viwec-quick-shortcode-list').hide();
                    $('.viwec-subject-quick-shortcode ul').hide();
                }
            });

            //Init select2 to rule
            $('.viwec-select2').select2({placeholder: $(this).attr('data-placeholder')});

            //Init control panel tab
            $(`#viwec-control-panel .menu .item`).tab();

            $('.viwec-toggle-admin-bar').on('click', function () {
                let _this = $(this);
                _this.toggleClass('dashicons-arrow-left dashicons-arrow-right', 1000);
                $('body.wp-admin').toggleClass('viwec-admin-bar-hidden', 1000);

                $.ajax({
                    url: ajaxurl,
                    type: 'post',
                    dataType: 'json',
                    data: {action: 'viwec_change_admin_bar_stt'},
                });
            });

            $('.viwec-quick-add-layout-btn').on('click', function () {
                $('.viwec-layout-list').toggle();
            });
        },

        setupPreviewModal() {
            //Block links
            $('body').on('click', '.viwec-email-preview a, #viwec-email-editor-wrapper a', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
            });
        },

        addNewTemplate() {
            if (typeof viWecParams.addNew !== 'undefined') {
                let id = viWecParams.addNew.type || '', style = viWecParams.addNew.style || '';
                if (id && style) {
                    viWecFunctions.doChangeSampleTemplate(id, style);
                }

                $('.viwec-samples-type').val(id).trigger('change');
                $('.viwec-samples-style').val(style).trigger('change');

                delete viWecParams.addNew;
            }
        },

        emailTypeChange() {
            // $('select.viwec-input[name=viwec_settings_type]').on('change', function () {
            //     let couponOptions = $('.wacv-abandoned-cart-coupon');
            //     $(this).val() === 'abandoned_cart' ? couponOptions.show() : couponOptions.hide();
            // });

            $('.viwec-set-email-type').on('change', function () {
                let emailType = $(this).val();
                runApp.hideRules(emailType);
                runApp.hideElements(emailType);
            });
        },

        hideRules(type) {
            let rules = $('#viwec-email-rules');
            rules.show();
            rules.find('.viwec-setting-row').show();
            let args = viWecParams.hide_rule || '';
            let list = args[type] || '';
            if (!list) return;
            if (list.length === 4) rules.hide();
            for (let el of list) {
                rules.find(`.viwec-setting-row[data-attr=${el}]`).hide();
            }
        },

        hideElements(type) {
            $('#viwec-components-list .viwec-control-btn').parent().show();
            let args = viWecParams.hide_element || '';
            let list = args[type] || '';
            if (!list) return;
            for (let el of list) {
                $(`#viwec-components-list .viwec-control-btn[data-type='${el}']`).parent().hide();
            }
        }
    };

    runApp.init();
});