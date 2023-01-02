jQuery(document).ready(function ($) {
    'use strict';

    let viWecSampleId, viWecSampleStyle = 'basic';

    if (typeof viWecCacheProducts === 'undefined') var viWecCacheProducts = [];

    if (typeof viWecCachePosts === 'undefined') var viWecCachePosts = [];

    const i18n = viWecParams.i18n;

    ViWec.Components.init();

//Functions

    window.viWecFunctions = {
        propertyOnChange: function (element, value) {
            if (value) {
                element.closest('span').show();
            } else {
                element.closest('span').hide();
            }
        },

        changeSampleTemplate: function () {
            if (!(viWecSampleId && viWecSampleStyle)) return;
            if (!confirm(i18n.change_template_confirm)) return;

            if (!viWecParams.samples || !viWecParams.samples[viWecSampleId] || !viWecParams.samples[viWecSampleId][viWecSampleStyle] || !viWecParams.samples[viWecSampleId][viWecSampleStyle].data) {
                alert('This style is not exist');
                return;
            }
            this.doChangeSampleTemplate(viWecSampleId, viWecSampleStyle)
        },

        doChangeSampleTemplate(id, style) {
            ViWec.viWecDrawTemplate(JSON.parse(viWecParams.samples[id][style].data));

            let subject = viWecParams.subjects[id] ?? '';
            $('#title').val(subject);
            $('#title-prompt-text').addClass('screen-reader-text');
            $('select[name=viwec_settings_type]').val(id).trigger('change');
            viWecChange = true;
        }
    };


    const productsSuggestRender = (row = 1, col = 2) => {
        let core = '', colWidth = (100 / col) + '%';
        for (let i = 0; i < row; i++) {
            core += '<tr>';
            for (let j = 1; j <= col; j++) {
                core += j !== 1 ? '<td class="viwec-product-distance" width="0" style="padding-left: 10px;"></td>' : '';

                core += `<td class="" style="width: ${colWidth}" valign="top">
                                <img class="viwec-product-image" width="100%" style="padding-bottom: 5px;" src="${viWecParams.product}">
                                <div class="viwec-product-name">Product name</div>
                                <div class="viwec-product-price">${viWecParams.suggestProductPrice}</div>
                          </td>`;
            }
            core += '</tr>';

            if (row > 1 && i !== row - 1) {
                core += '<tr><td class="viwec-product-h-distance" style="padding-top: 10px;"></td></tr>';
            }
        }
        return core;
    };


//Sample

    ViWec.Components.add({
        type: "sample_opt_1",
        category: 'sample',
        name: i18n.sample,
        setup: function () {
            let options = {};
            options.placeholder = {id: '', text: i18n.select_email_type};
            options.default = {id: 'default', text: 'Default template'};

            for (let i in viWecParams.emailTypes) {
                options[i] = [];
                for (let j in viWecParams.emailTypes[i]) {
                    if (j !== 'default') {
                        options[i].push({id: j, text: viWecParams.emailTypes[i][j]});
                    }
                }
            }

            let typeSelect = SelectGroupInput.init({key: 'viwec_samples', classes: 'viwec-samples-type', options: options});

            return $('<div class="viwec-sample-group"></div>').append(typeSelect);
        },

        onChange: function (element) {
            element.on('propertyChange', function (event, value, input) {
                if (!value) return;

                if ($(input).hasClass('viwec-samples-type')) {
                    viWecSampleId = value;

                    let options = [];

                    if (viWecParams.samples[value] !== undefined && Object.keys(viWecParams.samples[value]).length > 0) {
                        let samples = viWecParams.samples[value];
                        for (let id in samples) {
                            options.push({id: id, text: samples[id].name || ''})
                        }
                    }

                    let newStyleSelect = options.length > 1 ? SelectInput.init({key: 'viwec_samples', classes: 'viwec-samples-style', options: options}) : '';
                    let target = element.find('.viwec-samples-style');
                    if (target.length > 0) {
                        target.parent().replaceWith(newStyleSelect);
                    } else {
                        element.append(newStyleSelect);
                    }

                    if (typeof viWecParams.addNew !== 'undefined') {
                        return;
                    }

                    if (options.length > 0) {
                        viWecSampleStyle = options[0].id;
                        viWecFunctions.changeSampleTemplate();
                    }
                }

                if ($(input).hasClass('viwec-samples-style')) {
                    if (typeof viWecParams.addNew !== 'undefined') {
                        return;
                    }
                    viWecSampleStyle = value;
                    viWecFunctions.changeSampleTemplate();
                }

            });
        }
    });


//Layout

    ViWec.Components.add({
        type: "editColumn",
        category: 'hidden',
        inheritProp: ['padding', 'background', 'border']
    });

    ViWec.Components.add({
        type: "layout/grid1cols",
        category: 'layout',
        name: i18n['1_column'],
        icon: '1col',
        cols: 1,
        inheritProp: ['edit_cols', 'padding', 'background', 'border']
    });

    ViWec.Components.add({
        type: "layout/grid2cols",
        category: 'layout',
        name: i18n['2_columns'],
        icon: '2cols',
        cols: 2,
        inheritProp: ['edit_cols', 'padding', 'background', 'border']
    });

    ViWec.Components.add({
        type: "layout/grid3cols",
        category: 'layout',
        name: i18n['3_columns'],
        icon: '3cols',
        cols: 3,
        inheritProp: ['edit_cols', 'padding', 'background', 'border']
    });

    ViWec.Components.add({
        type: "layout/grid4cols",
        category: 'layout',
        name: i18n['4_columns'],
        icon: '4cols',
        cols: 4,
        inheritProp: ['edit_cols', 'padding', 'background', 'border']
    });

//Content

    ViWec.Components.add({
        type: "background",
        category: 'hidden',
        icon: '',
        html: ``,
        inheritProp: ['background']
    });

    ViWec.Components.add({
        type: "html/text",
        name: i18n['text'],
        icon: 'text',
        html: `<div class="viwec-text-content" contenteditable="true">Text</div>`,
        properties: [
            {
                key: "text_editor_header",
                inputType: SectionInput,
                name: false,
                section: contentSection,
                data: {header: "Text Editor"},
            },
            {
                key: "text",
                htmlAttr: 'innerHTML',
                target: '.viwec-text-content',
                section: contentSection,
                inputType: TextEditor,
                renderShortcode: true
            },
        ],
        inheritProp: ['line_height', 'background', 'padding', 'border']
    });

    ViWec.Components.add({
        type: "html/image",
        name: i18n['image'],
        icon: 'image',
        html: `<img src="${viWecParams.placeholder}" class="viwec-image" style="max-width: 100%; ">`,
        properties: [{
            key: "image_header",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: i18n['image']},
        }, {
            // name: "Select Image",
            key: "src",
            htmlAttr: "src",
            target: 'img',
            section: contentSection,
            col: 16,
            inputType: ImgInput,
            data: {text: i18n['select'], classes: 'viwec-open-bg-img'}
        }, {
            key: "image_header",
            inputType: SectionInput,
            name: false,
            section: styleSection,
            data: {header: "Size"},
        }, {
            name: "Width (px)",
            key: "width",
            htmlAttr: "childStyle",
            target: 'img',
            section: styleSection,
            col: 16,
            inputType: NumberInput,
            unit: 'px',
            data: {min: 0, max: 600, step: 1}
        }],
        inheritProp: ['alignment', 'padding', 'background']//, 'border']
    });

    ViWec.Components.add({
        type: "html/button",
        name: i18n['button'],
        icon: 'button',
        html: `<a href="#" class="viwec-button viwec-background viwec-padding" 
                style="border-style:solid;display:inline-block;padding: 10px 20px;text-decoration: none;text-align: center;max-width: 100%;background-color: #dddddd">
                    <span class="viwec-text-content">Button</span>
                </a>`,

        properties: [{
            key: "text_header",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Text"},
        }, {
            key: "text",
            htmlAttr: 'innerHTML',
            target: '.viwec-text-content',
            section: contentSection,
            col: 16,
            inputType: TextInput,
            renderShortcode: true,
            data: {shortcodeTool: true}
        }, {
            key: "link_button",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Link"},
        }, {
            key: "href",
            htmlAttr: "href",
            target: 'a',
            section: contentSection,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true}
        },
            {
                key: "button_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Button"},
            },
            {
                name: "Border width",
                key: "border-width",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 8,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 10, step: 1}
            }, {
                name: "Border radius",
                key: "border-radius",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 8,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 50, step: 1}
            }, {
                name: "Border color",
                key: "border-color",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 8,
                inputType: ColorInput
            },
            {
                name: "Border style",
                key: "border-style",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 8,
                data: {
                    options: [
                        {id: 'solid', text: 'Solid'},
                        {id: 'dotted', text: 'Dotted'},
                        {id: 'dashed', text: 'Dashed'},
                    ]
                },
                inputType: SelectInput
            },
            {
                name: "Button color",
                key: "background-color",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 8,
                inputType: ColorInput
            },
            {
                name: "Width (px)",
                key: "width",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 8,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 600}
            },
            {
                key: "padding_el_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Padding (px)"}
            },
            {
                name: "Left",
                key: "padding-left",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 4,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 20, max: 250},
            },
            {
                name: "Top",
                key: "padding-top",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 4,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 20},
            },
            {
                name: "Right",
                key: "padding-right",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 4,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 20, max: 250},
            }, {
                name: "Bottom",
                key: "padding-bottom",
                htmlAttr: "childStyle",
                target: 'a',
                section: styleSection,
                col: 4,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 20},
            }],
        inheritProp: ['text', 'alignment', 'margin']//, 'background']
    });

    ViWec.Components.add({
        type: "html/order_detail",
        name: i18n['order_detail'],
        icon: 'order-detail', html: viWecTmpl('order-detail-template-1', {}),
        properties: [
            {
                key: "select_template",
                inputType: SectionInput,
                name: false,
                section: contentSection,
                data: {header: i18n['template']}
            },
            {
                key: "data-template",
                htmlAttr: "data-template",
                section: contentSection,
                col: 16,
                inputType: SelectInput,
                data: {
                    options: [
                        {id: '1', text: i18n['default']},
                        {id: '2', text: i18n['vertical_text']},
                        {id: '3', text: i18n['horizontal_text']},
                    ]
                },
                onChange: (element, value, input, component, property) => {
                    if (value) {
                        let newEl = viWecTmpl(`order-detail-template-${value}`, {});
                        element.find('.viwec-order-detail').remove();
                        element.append(newEl);
                        element.click();
                    }
                    return element;
                }
            },

            {
                key: "translate_text",
                inputType: SectionInput,
                name: false,
                target: '.viwec-text-quantity',
                section: contentSection,
                data: {header: i18n['translate_text']}
            },
            {
                name: i18n['product'],
                key: "product",
                target: '.viwec-text-product',
                htmlAttr: "innerHTML",
                section: contentSection,
                col: 16,
                inputType: TextInput
            },
            {
                name: i18n['quantity'],
                key: "quantity",
                target: '.viwec-text-quantity',
                htmlAttr: "innerHTML",
                section: contentSection,
                col: 16,
                inputType: TextInput
            },
            {
                name: i18n['price'],
                key: "price",
                target: '.viwec-text-price',
                htmlAttr: "innerHTML",
                section: contentSection,
                col: 16,
                inputType: TextInput
            },

            {
                key: "show_sku",
                inputType: SectionInput,
                name: false,
                section: contentSection,
                data: {header: i18n['show_sku']}
            },
            {
                name: i18n['enable'],
                key: "show_sku",
                htmlAttr: "show_sku",
                section: contentSection,
                col: 16,
                inputType: CheckboxInput,
                onChange(element, value) {
                    if (value) {
                        element.find('.viwec-product-name, .viwec-p-name').append('<small class="viwec-product-sku"> (#SKU)</small>');
                    } else {
                        element.find('.viwec-product-sku').remove();
                    }
                    return element;
                }
            },

            {
                key: "table_style",
                inputType: SectionInput,
                name: false,
                target: '.viwec-item-row',
                section: styleSection,
                data: {header: i18n['order_items']}
            },
            {
                name: "Background",
                key: "background-color",
                target: '.viwec-item-row',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: ColorInput
            },
            {
                name: "Image size",
                key: "width",
                target: '.viwec-product-img',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            },
            {
                name: "Border width",
                key: "border-width",
                target: '.viwec-item-row',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 20},
            },
            {
                name: "Border color",
                key: "border-color",
                target: '.viwec-item-row',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: ColorInput,
                data: {id: 20},
            },
            {
                name: "Items distance",
                key: "padding-top",
                target: '.viwec-product-distance',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput,
            },
            {
                key: "product_name",
                inputType: SectionInput,
                name: false,
                target: '.viwec-product-name',
                section: styleSection,
                data: {header: i18n['product_name']}
            },
            {
                name: "Font size (px)",
                key: "font-size",
                target: '.viwec-product-name',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            },
            {
                name: "Font weight",
                key: "font-weight",
                target: '.viwec-product-name',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: SelectInput,
                data: {options: viWecFontWeightOptions}
            },
            {
                name: "Color",
                key: "color",
                target: '.viwec-product-name',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: ColorInput
            },
            {
                name: "Line height (px)",
                key: "line-height",
                target: '.viwec-product-name',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            },
            {
                key: "col_header",
                inputType: SectionInput,
                name: "Column ratio",
                target: '.viwec-text-price',
                section: styleSection,
                // data: {header: "Column ratio"},
            },
            {
                name: i18n['last_column_width'] + ' (%)',
                key: "width",
                htmlAttr: "childStyle",
                target: '.viwec-text-price',
                section: styleSection,
                col: 16,
                unit: '%',
                inputType: NumberInput,
            },
            {
                key: "product_name_1",
                inputType: SectionInput,
                name: false,
                target: '.viwec-item-style-1',
                section: styleSection,
                data: {header: i18n['text']}
            },
            {
                name: "Font size (px)",
                key: "font-size",
                target: '.viwec-item-style-1',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            },
            {
                name: "Color",
                key: "color",
                target: '.viwec-item-style-1',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: ColorInput
            },
            {
                name: "Line height (px)",
                key: "line-height",
                target: '.viwec-item-style-1',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            },
            {
                key: "product_price",
                inputType: SectionInput,
                name: false,
                target: '.viwec-product-price',
                section: styleSection,
                data: {header: i18n['product_price']}
            },
            {
                name: "Font size (px)",
                key: "font-size",
                target: '.viwec-product-price',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            },
            {
                name: "Font weight",
                key: "font-weight",
                target: '.viwec-product-price',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: SelectInput,
                data: {
                    options: viWecFontWeightOptions
                }
            },
            {
                name: "Color",
                key: "color",
                target: '.viwec-product-price',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: ColorInput
            },
            {
                name: "Line height (px)",
                key: "line-height",
                target: '.viwec-product-price',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            },
            {
                key: "product_quantity",
                inputType: SectionInput,
                name: false,
                target: '.viwec-product-quantity',
                section: styleSection,
                data: {header: i18n['product_quantity']}
            },
            {
                name: "Font size (px)",
                key: "font-size",
                target: '.viwec-product-quantity',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            },
            {
                name: "Font weight",
                key: "font-weight",
                target: '.viwec-product-quantity',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: SelectInput,
                data: {options: viWecFontWeightOptions}
            },
            {
                name: "Color",
                key: "color",
                target: '.viwec-product-quantity',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: ColorInput
            },
            {
                name: "Line height (px)",
                key: "line-height",
                target: '.viwec-product-quantity',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            }
        ],
        inheritProp: ['padding', 'background']//, 'border'] //'text',
    });

    ViWec.Components.add({
        type: "html/order_subtotal",
        name: i18n['order_subtotal'],
        icon: 'order-subtotal',
        html: viWecTmpl('order-subtotal-template', {}),
        properties: [
            {
                key: "translate_text",
                inputType: SectionInput,
                name: false,
                section: contentSection,
                data: {header: i18n['translate_text']}
            }, {
                name: i18n['subtotal'],
                key: "subtotal",
                target: '.viwec-text-subtotal',
                htmlAttr: "innerHTML",
                section: contentSection,
                col: 16,
                inputType: TextInput
            }, {
                name: i18n['discount'],
                key: "discount",
                target: '.viwec-text-discount',
                htmlAttr: "innerHTML",
                section: contentSection,
                col: 16,
                inputType: TextInput
            }, {
                name: i18n['shipping'],
                key: "shipping",
                target: '.viwec-text-shipping',
                htmlAttr: "innerHTML",
                section: contentSection,
                col: 16,
                inputType: TextInput
            }, {
                name: i18n['refund_fully'],
                key: "refund-full",
                target: '.viwec-text-refund-full',
                htmlAttr: "innerHTML",
                section: contentSection,
                col: 16,
                inputType: TextInput
            }, {
                name: i18n['refund_partial'],
                key: "refund-part",
                target: '.viwec-text-refund-part',
                htmlAttr: "innerHTML",
                section: contentSection,
                col: 16,
                inputType: TextInput
            },
            {
                key: "col_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Column ratio"},
            },
            {
                name: i18n['last_column_width'] + " (%)",
                key: "width",
                htmlAttr: "childStyle",
                target: '.viwec-td-right',
                section: styleSection,
                col: 16,
                unit: '%',
                inputType: NumberInput,

            },
            {
                key: "alignment_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Alignment"},
            },
            {
                name: "Left",
                key: "text-align",
                htmlAttr: "childStyle",
                target: '.viwec-td-left',
                validValues: ["", "text-left", "text-center", "text-right"],
                section: styleSection,
                col: 8,
                inputType: RadioButtonInput,
                data: {
                    extraClass: "left",
                    options: viWecAlignmentOptions,
                },
            },
            {
                name: "Right",
                key: "text-align",
                htmlAttr: "childStyle",
                target: '.viwec-td-right',
                validValues: ["", "text-left", "text-center", "text-right"],
                section: styleSection,
                col: 8,
                inputType: RadioButtonInput,
                data: {
                    extraClass: "right",
                    options: viWecAlignmentOptions,
                },
            },
            {
                key: "border_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Border"},
            },
            {
                name: "Border width",
                key: "border-width",
                target: '.viwec-order-subtotal-style',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 0},
            },
            {
                name: "Border color",
                key: "border-color",
                target: '.viwec-order-subtotal-style',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: ColorInput,
            },
            {
                key: "padding_el_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Padding (px)"}
            }, {
                // name: "Padding",
                key: "padding",
                target: '.viwec-order-subtotal-style',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 16,
                unit: 'px',
                inputType: NumberInput,
            }
        ],
        inheritProp: ['text', 'margin', 'background']//, 'border']
    });

    ViWec.Components.add({
        type: "html/order_total",
        name: i18n['order_total'],
        icon: 'order-total',
        html: viWecTmpl('order-total-template', {}),

        properties: [
            {
                key: "translate_text",
                inputType: SectionInput,
                name: false,
                section: contentSection,
                data: {header: i18n['translate_text']}
            },
            {
                name: i18n['total'],
                key: "order_total",
                target: '.viwec-text-total',
                htmlAttr: "innerHTML",
                section: contentSection,
                col: 16,
                inputType: TextInput
            },
            {
                key: "col_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: i18n['column_ratio']},
            },
            {
                name: i18n['last_column_width'] + " (%)",
                key: "width",
                htmlAttr: "childStyle",
                target: '.viwec-td-right',
                section: styleSection,
                col: 16,
                unit: '%',
                inputType: NumberInput,

            },
            {
                key: "alignment_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Alignment"},
            },
            {
                name: "Left",
                key: "text-align",
                htmlAttr: "childStyle",
                target: '.viwec-td-left',
                validValues: ["", "text-left", "text-center", "text-right"],
                section: styleSection,
                col: 8,
                inputType: RadioButtonInput,
                data: {extraClass: "left", options: viWecAlignmentOptions},
            },
            {
                name: "Right",
                key: "text-align",
                htmlAttr: "childStyle",
                target: '.viwec-td-right',
                validValues: ["", "text-left", "text-center", "text-right"],
                section: styleSection,
                col: 8,
                inputType: RadioButtonInput,
                data: {extraClass: "right", options: viWecAlignmentOptions},
            },
            {
                key: "border_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Border"},
            },
            {
                name: "Border width",
                key: "border-width",
                target: '.viwec-order-total-style',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 0},
            },
            {
                name: "Border color",
                key: "border-color",
                target: '.viwec-order-total-style',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: ColorInput,
            },
            {
                key: "padding_el_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Padding (px)"}
            }, {
                // name: "Padding",
                key: "padding",
                target: '.viwec-order-total-style',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 16,
                unit: 'px',
                inputType: NumberInput,
            }
        ],
        inheritProp: ['text', 'background', 'margin']//, 'border']
    });

    ViWec.Components.add({
        type: "html/shipping_method",
        name: i18n['shipping_method'],
        icon: 'shipping-address',
        html: viWecTmpl('order-shipping-method', {}),

        properties: [
            {
                key: "translate_text",
                inputType: SectionInput,
                name: false,
                section: contentSection,
                data: {header: i18n['translate_text']}
            },
            {
                name: i18n['shipping_method'],
                key: "shipping_method",
                target: '.viwec-text-shipping',
                htmlAttr: "innerHTML",
                section: contentSection,
                col: 16,
                inputType: TextInput
            },
            {
                key: "col_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: i18n['column_ratio']},
            },
            {
                name: i18n['last_column_width'] + " (%)",
                key: "width",
                htmlAttr: "childStyle",
                target: '.viwec-td-right',
                section: styleSection,
                col: 16,
                unit: '%',
                inputType: NumberInput,

            },
            {
                key: "alignment_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Alignment"},
            },
            {
                name: "Left",
                key: "text-align",
                htmlAttr: "childStyle",
                target: '.viwec-td-left',
                validValues: ["", "text-left", "text-center", "text-right"],
                section: styleSection,
                col: 8,
                inputType: RadioButtonInput,
                data: {extraClass: "left", options: viWecAlignmentOptions},
            },
            {
                name: "Right",
                key: "text-align",
                htmlAttr: "childStyle",
                target: '.viwec-td-right',
                validValues: ["", "text-left", "text-center", "text-right"],
                section: styleSection,
                col: 8,
                inputType: RadioButtonInput,
                data: {extraClass: "right", options: viWecAlignmentOptions},
            },
            {
                key: "border_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Border"},
            },
            {
                name: "Border width",
                key: "border-width",
                target: '.viwec-shipping-method-style',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 0},
            },
            {
                name: "Border color",
                key: "border-color",
                target: '.viwec-shipping-method-style',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: ColorInput,
            },
            {
                key: "padding_el_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Padding (px)"}
            }, {
                // name: "Padding",
                key: "padding",
                target: '.viwec-shipping-method-style',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 16,
                unit: 'px',
                inputType: NumberInput,
            }
        ],
        inheritProp: ['text', 'background', 'margin']//, 'border']
    });

    ViWec.Components.add({
        type: "html/payment_method",
        name: i18n['payment_method'],
        icon: 'payment-method',
        html: viWecTmpl('order-payment-method', {}),

        properties: [
            {
                key: "translate_text",
                inputType: SectionInput,
                name: false,
                section: contentSection,
                data: {header: i18n['translate_text']}
            },
            {
                name: i18n['payment_method'],
                key: "payment_method",
                target: '.viwec-text-payment',
                htmlAttr: "innerHTML",
                section: contentSection,
                col: 16,
                inputType: TextInput
            },
            {
                key: "col_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: i18n['column_ratio']},
            },
            {
                name: i18n['last_column_width'] + " (%)",
                key: "width",
                htmlAttr: "childStyle",
                target: '.viwec-td-right',
                section: styleSection,
                col: 16,
                unit: '%',
                inputType: NumberInput,

            },
            {
                key: "alignment_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Alignment"},
            },
            {
                name: "Left",
                key: "text-align",
                htmlAttr: "childStyle",
                target: '.viwec-td-left',
                validValues: ["", "text-left", "text-center", "text-right"],
                section: styleSection,
                col: 8,
                inputType: RadioButtonInput,
                data: {extraClass: "left", options: viWecAlignmentOptions},
            },
            {
                name: "Right",
                key: "text-align",
                htmlAttr: "childStyle",
                target: '.viwec-td-right',
                validValues: ["", "text-left", "text-center", "text-right"],
                section: styleSection,
                col: 8,
                inputType: RadioButtonInput,
                data: {extraClass: "right", options: viWecAlignmentOptions},
            },
            {
                key: "border_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Border"},
            },
            {
                name: "Border width",
                key: "border-width",
                target: '.viwec-payment-method-style',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 0},
            },
            {
                name: "Border color",
                key: "border-color",
                target: '.viwec-payment-method-style',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: ColorInput,
            },
            {
                key: "padding_el_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Padding (px)"}
            }, {
                // name: "Padding",
                key: "padding",
                target: '.viwec-payment-method-style',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 16,
                unit: 'px',
                inputType: NumberInput,
            }
        ],
        inheritProp: ['text', 'background', 'margin']//, 'border']
    });

    ViWec.Components.add({
        type: "html/order_note",
        name: i18n['order_note'],
        icon: 'order-note',
        html: viWecTmpl('order-note', {}),

        properties: [
            {
                key: "translate_text",
                inputType: SectionInput,
                name: false,
                section: contentSection,
                data: {header: i18n['translate_text']}
            },
            {
                name: i18n['order_note'],
                key: "order_note",
                target: '.viwec-text-note',
                htmlAttr: "innerHTML",
                section: contentSection,
                col: 16,
                inputType: TextInput
            },
            {
                key: "col_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: i18n['column_ratio']},
            },
            {
                name: i18n['last_column_width'] + " (%)",
                key: "width",
                htmlAttr: "childStyle",
                target: '.viwec-td-right',
                section: styleSection,
                col: 16,
                unit: '%',
                inputType: NumberInput,

            },
            {
                key: "alignment_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Alignment"},
            },
            {
                name: "Left",
                key: "text-align",
                htmlAttr: "childStyle",
                target: '.viwec-td-left',
                validValues: ["", "text-left", "text-center", "text-right"],
                section: styleSection,
                col: 8,
                inputType: RadioButtonInput,
                data: {extraClass: "left", options: viWecAlignmentOptions},
            },
            {
                name: "Right",
                key: "text-align",
                htmlAttr: "childStyle",
                target: '.viwec-td-right',
                validValues: ["", "text-left", "text-center", "text-right"],
                section: styleSection,
                col: 8,
                inputType: RadioButtonInput,
                data: {extraClass: "right", options: viWecAlignmentOptions},
            },
            {
                key: "border_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Border"},
            },
            {
                name: "Border width",
                key: "border-width",
                target: '.viwec-note-style',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 0},
            },
            {
                name: "Border color",
                key: "border-color",
                target: '.viwec-note-style',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: ColorInput,
            },
            {
                key: "padding_el_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Padding (px)"}
            }, {
                // name: "Padding",
                key: "padding",
                target: '.viwec-note-style',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 16,
                unit: 'px',
                inputType: NumberInput,
            }
        ],
        inheritProp: ['text', 'background', 'margin']//, 'border']
    });

    ViWec.Components.add({
        type: "html/billing_address",
        name: i18n['billing_address'],
        icon: 'billing-address',
        html: `<div>
            John Doe</br>
            Ap #867-859 Sit Rd.</br>
            Azusa, NY 10001</br>
            United States (US)</br>
            0123456789</br>
            johndoe@domain.com
            </div>`,
        inheritProp: ['text', 'alignment', 'padding', 'background']//, 'border']
    });

    ViWec.Components.add({
        type: "html/shipping_address",
        name: i18n['shipping_address'],
        icon: 'shipping-address',
        html: `<div>
            John Doe</br>
            Ap #867-859 Sit Rd.</br>
            Azusa, NY 10001</br>
            United States (US)</br>
            </div>`,
        inheritProp: ['text', 'alignment', 'padding', 'background']//, 'alignment', 'border']
    });

    ViWec.Components.add({
        type: "html/suggest_product",
        name: i18n['products'],
        icon: 'product',
        html: '<table class="viwec-suggest-product" width="100%" align="center" style="text-align: center"  border="0" cellpadding="0" cellspacing="0">' + productsSuggestRender(1, 2) + '</table>',
        properties: [
            {
                key: "suggest_product",
                inputType: SectionInput,
                name: false,
                section: contentSection,
                data: {header: i18n['product_type']},
            },
            {
                name: i18n['product_type'],
                key: "data-product_type",
                htmlAttr: 'data-product_type',
                section: contentSection,
                inputType: SelectInput,
                default: 'newest',
                data: {
                    options: [
                        {id: 'newest', text: i18n['newest']},
                        {id: 'related', text: i18n['related']},
                        {id: 'category', text: i18n['category']},
                        {id: 'on_sale', text: i18n['on_sale']},
                        {id: 'featured', text: i18n['featured']},
                        {id: 'up_sell', text: i18n['up_sell']},
                        {id: 'cross_sell', text: i18n['cross_sell']},
                        {id: 'best_seller', text: i18n['best_seller']},
                        {id: 'best_rated', text: i18n['best_rated']}
                    ]
                },
            },
            {
                name: i18n['max_row'],
                key: "data-max_row",
                htmlAttr: 'data-max_row',
                section: contentSection,
                inputType: SelectInput,
                col: 8,
                default: 1,
                data: {
                    options: [
                        {id: 1, text: "1",},
                        {id: 2, text: "2"},
                        {id: 3, text: "3"},
                        {id: 4, text: "4"}
                    ]
                },
                onChange: function (element, value) {
                    element.find('table').html(productsSuggestRender(value, element.attr('data-column')));
                    element.trigger('click');
                    return element;
                }
            },
            {
                name: i18n['column'],
                key: "data-column",
                htmlAttr: 'data-column',
                section: contentSection,
                inputType: SelectInput,
                default: 2,
                col: 8,
                data: {
                    options: [
                        {id: 1, text: "1",},
                        {id: 2, text: "2",},
                        {id: 3, text: "3"},
                        {id: 4, text: "4"}
                    ]
                },
                onChange: function (element, value) {
                    element.find('table').html(productsSuggestRender(element.attr('data-max_row'), value));
                    element.trigger('click');
                    return element;
                }
            },
            {
                key: "add_to_cart_option",
                inputType: SectionInput,
                name: false,
                section: contentSection,
                data: {header: i18n['auto_add_to_cart']},
            },
            {
                name: i18n['enable'],
                key: "data-auto-atc",
                htmlAttr: 'data-auto-atc',
                section: contentSection,
                inputType: CheckboxInput,
                col: 16,
            },
            {
                key: "product_image",
                inputType: SectionInput,
                name: false,
                target: '.viwec-product-image',
                section: styleSection,
                data: {header: i18n['image']},
            }, {
                name: "Width (px)",
                key: "width",
                htmlAttr: "childStyle",
                target: '.viwec-product-image',
                section: styleSection,
                col: 16,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 600, step: 1}
            },
            {
                key: "product_name",
                inputType: SectionInput,
                name: false,
                target: '.viwec-product-name',
                section: styleSection,
                data: {header: i18n['product_name']}
            },
            {
                name: i18n['character_limit'],
                key: "character-limit",
                htmlAttr: "data-character_limit",
                default: 30,
                section: styleSection,
                col: 16,
                inputType: NumberInput
            },
            {
                name: "Font size (px)",
                key: "font-size",
                target: '.viwec-product-name',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            },
            {
                name: "Font weight",
                key: "font-weight",
                target: '.viwec-product-name',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: SelectInput,
                data: {
                    options: viWecFontWeightOptions
                }
            }, {
                name: "Color",
                key: "color",
                target: '.viwec-product-name',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: ColorInput
            }, {
                name: "Line height (px)",
                key: "line-height",
                target: '.viwec-product-name',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            }, {
                key: "product_price",
                inputType: SectionInput,
                name: false,
                target: '.viwec-product-price',
                section: styleSection,
                data: {header: i18n['product_price']}
            }, {
                name: "Font size (px)",
                key: "font-size",
                target: '.viwec-product-price',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            }, {
                name: "Font weight",
                key: "font-weight",
                target: '.viwec-product-price',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: SelectInput,
                data: {
                    options: viWecFontWeightOptions
                }
            }, {
                name: "Color",
                key: "color",
                target: '.viwec-product-price',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: ColorInput
            }, {
                name: "Line height (px)",
                key: "line-height",
                target: '.viwec-product-price',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            },
            {
                key: "product_space",
                inputType: SectionInput,
                name: false,
                target: '.viwec-suggest-product',
                section: styleSection,
                data: {header: i18n['product_distance'] + " (px)"}
            },
            {
                name: i18n['vertical'],
                key: "padding-left",
                target: '.viwec-product-distance',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput,
            },
            {
                name: i18n['horizontal'],
                key: "padding-top",
                target: '.viwec-product-h-distance',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput,
            }
        ],
        inheritProp: ['padding', 'background']//, 'border'] //'text',
    });

    ViWec.Components.add({
        type: "html/coupon",
        name: i18n['coupon'],
        icon: 'coupon',
        html: `<div class="viwec-coupon" style="display: inline-block;border:2px solid #cfcfcf; padding: 15px 30px; background-color: #eeeeee;"><div class="viwec-coupon-text">COUPONCODE</div></div>`,

        properties: [
            {
                key: "coupon_setting",
                inputType: SectionInput,
                name: false,
                section: contentSection,
                data: {header: i18n['settings']},
            },
            {
                name: i18n['coupon_type'],
                key: "data-coupon-type",
                htmlAttr: 'data-coupon-type',
                section: contentSection,
                classes: 'viwec-coupon-type',
                col: 16,
                inputType: SelectInput,
                data: {
                    options: [
                        {id: '1', text: i18n['existing_coupon']},
                        {id: '2', text: i18n['generate_coupon']},
                    ]
                },
                onChange(element, value) {
                    let selectCoupon = $('.viwec-select-coupon');
                    let generateCoupon = $('.viwec-generate-coupon');
                    switch (value) {
                        case '1':
                            selectCoupon.show();
                            generateCoupon.hide();
                            element.click();
                            break;
                        case '2':
                            selectCoupon.hide();
                            generateCoupon.show();
                            element.find('.viwec-coupon-text').text('COUPONCODE');
                            break;

                    }
                    return element;
                }
            },
            {
                name: i18n['select_coupon'],
                key: "data-coupon-code",
                htmlAttr: 'innerHTML',
                target: '.viwec-coupon-text',
                section: contentSection,
                classes: 'viwec-select-coupon',
                col: 16,
                inputType: SelectInput,
                setup(row) {
                    $('.viwec-coupon-type').find('select').val() === '1' ? row.show() : row.hide();
                    row.find('select').select2({
                        width: '100%',
                        minimumInputLength: 2,
                        ajax: {
                            url: viWecParams.ajaxUrl,
                            dataType: 'json',
                            type: "POST",
                            quietMillis: 50,
                            delay: 250,
                            data: function (params) {
                                return {q: params.term, action: 'viwec_search_coupon', nonce: viWecParams.nonce};
                            },
                            processResults: function (data) {
                                return {results: data};
                            },
                        },
                    });

                    return row;
                }
            },
            {
                name: i18n['discount_type'],
                key: "data-discount-type",
                htmlAttr: 'data-discount-type',
                section: contentSection,
                classes: 'viwec-generate-coupon',
                col: 16,
                inputType: SelectInput,
                data: {
                    options: [
                        {id: 'percentage', text: i18n['percentage_discount']},
                        {id: 'fixed_cart', text: i18n['fixed_cart_discount']},
                        {id: 'fixed_product', text: i18n['fixed_product_discount']},
                    ]
                },
                setup(row) {
                    $('.viwec-coupon-type').find('select').val() === '2' ? row.css('display', 'inline-block') : row.hide();
                    return row;
                }
            },
            {
                name: i18n['coupon_amount'],
                key: "data-coupon-amount",
                htmlAttr: 'data-coupon-amount',
                section: contentSection,
                classes: 'viwec-generate-coupon',
                col: 16,
                inputType: TextInput,
                setup(row) {
                    $('.viwec-coupon-type').find('select').val() === '2' ? row.css('display', 'inline-block') : row.hide();
                    return row;
                }
            },
            {
                name: i18n['expire_after_x_days'],
                key: "data-coupon-expiry-date",
                htmlAttr: 'data-coupon-expiry-date',
                section: contentSection,
                classes: 'viwec-generate-coupon',
                col: 16,
                inputType: TextInput,
                setup(row) {
                    $('.viwec-coupon-type').find('select').val() === '2' ? row.css('display', 'inline-block') : row.hide();
                    return row;
                }
            },
            {
                name: i18n['minimum_spend'],
                key: "data-coupon-min-spend",
                htmlAttr: 'data-coupon-min-spend',
                section: contentSection,
                classes: 'viwec-generate-coupon',
                col: 16,
                inputType: NumberInput,
                setup(row) {
                    $('.viwec-coupon-type').find('select').val() === '2' ? row.css('display', 'inline-block') : row.hide();
                    return row;
                }
            },
            {
                name: i18n['maximum_spend'],
                key: "data-coupon-max-spend",
                htmlAttr: 'data-coupon-max-spend',
                section: contentSection,
                classes: 'viwec-generate-coupon',
                col: 16,
                inputType: NumberInput,
                setup(row) {
                    $('.viwec-coupon-type').find('select').val() === '2' ? row.css('display', 'inline-block') : row.hide();
                    return row;
                }
            },
            {
                name: i18n['products'],
                key: "data-coupon-include-product",
                htmlAttr: 'data-coupon-include-product',
                section: contentSection,
                classes: 'viwec-generate-coupon',
                col: 16,
                inputType: Select2Input,
                data: {options: viWecCacheProducts, multiple: true},
                setup(row) {
                    let $_this = this;
                    $('.viwec-coupon-type').find('select').val() === '2' ? row.css('display', 'inline-block') : row.hide();

                    row.find('select').select2({
                        width: '100%',
                        cache: true,
                        minimumInputLength: 3,
                        ajax: {
                            url: viWecParams.ajaxUrl,
                            dataType: 'json',
                            type: "GET",
                            quietMillis: 50,
                            delay: 250,
                            data: function (params) {
                                return {
                                    term: params.term,
                                    action: 'woocommerce_json_search_products_and_variations',
                                    security: wc_enhanced_select_params.search_products_nonce
                                };
                            },
                            processResults: function (data) {
                                var terms = [];
                                if (data) {
                                    $.each(data, function (id, text) {
                                        terms.push({id: id, text: text});
                                    });
                                }

                                $_this.data.options = [...$_this.data.options, ...terms];

                                return {results: terms};
                            },
                        },
                    });
                    return row;
                }
            },
            {
                name: i18n['exclude_products'],
                key: "data-coupon-exclude-product",
                htmlAttr: 'data-coupon-exclude-product',
                section: contentSection,
                classes: 'viwec-generate-coupon',
                col: 16,
                inputType: Select2Input,
                data: {options: viWecCacheProducts, multiple: true},
                setup(row) {
                    let $_this = this;
                    $('.viwec-coupon-type').find('select').val() === '2' ? row.css('display', 'inline-block') : row.hide();

                    row.find('select').select2({
                        width: '100%',
                        cache: true,
                        minimumInputLength: 3,
                        ajax: {
                            url: viWecParams.ajaxUrl,
                            dataType: 'json',
                            type: "GET",
                            quietMillis: 50,
                            delay: 250,
                            data: function (params) {
                                return {
                                    term: params.term,
                                    action: 'woocommerce_json_search_products_and_variations',
                                    security: wc_enhanced_select_params.search_products_nonce
                                };
                            },
                            processResults: function (data) {
                                var terms = [];
                                if (data) {
                                    $.each(data, function (id, text) {
                                        terms.push({id: id, text: text});
                                    });
                                }

                                $_this.data.options = [...$_this.data.options, ...terms];

                                return {results: terms};
                            },
                        },
                    });
                    return row;
                }
            },
            {
                name: i18n['categories'],
                key: "data-coupon-include-categories",
                htmlAttr: 'data-coupon-include-categories',
                section: contentSection,
                classes: 'viwec-generate-coupon',
                col: 16,
                inputType: Select2Input,
                data: {options: viWecParams.product_categories, multiple: true},
                setup(row) {
                    $('.viwec-coupon-type').find('select').val() === '2' ? row.css('display', 'inline-block') : row.hide();

                    row.find('select').select2({width: '100%'});
                    return row;
                }
            },
            {
                name: i18n['exclude_categories'],
                key: "data-coupon-exclude-categories",
                htmlAttr: 'data-coupon-exclude-categories',
                section: contentSection,
                classes: 'viwec-generate-coupon',
                col: 16,
                inputType: Select2Input,
                data: {options: viWecParams.product_categories, multiple: true},
                setup(row) {
                    $('.viwec-coupon-type').find('select').val() === '2' ? row.css('display', 'inline-block') : row.hide();

                    row.find('select').select2({width: '100%'});
                    return row;
                }
            },
            {
                name: i18n['usage_limit_per_coupon'],
                key: "data-coupon-limit-quantity",
                htmlAttr: 'data-coupon-limit-quantity',
                section: contentSection,
                classes: 'viwec-generate-coupon',
                col: 16,
                inputType: NumberInput,
                setup(row) {
                    $('.viwec-coupon-type').find('select').val() === '2' ? row.css('display', 'inline-block') : row.hide();
                    return row;
                }
            },
            {
                name: i18n['limit_usage_to_x_items'],
                key: "data-coupon-limit-items",
                htmlAttr: 'data-coupon-limit-items',
                section: contentSection,
                classes: 'viwec-generate-coupon',
                col: 16,
                inputType: NumberInput,
                setup(row) {
                    $('.viwec-coupon-type').find('select').val() === '2' ? row.css('display', 'inline-block') : row.hide();
                    return row;
                }
            },
            {
                name: i18n['usage_limit_per_user'],
                key: "data-coupon-limit-users",
                htmlAttr: 'data-coupon-limit-users',
                section: contentSection,
                classes: 'viwec-generate-coupon',
                col: 16,
                inputType: NumberInput,
                setup(row) {
                    $('.viwec-coupon-type').find('select').val() === '2' ? row.css('display', 'inline-block') : row.hide();
                    return row;
                }
            },
            {
                name: i18n['allow_free_shipping'],
                key: "data-coupon-allow-free-shipping",
                htmlAttr: 'data-coupon-allow-free-shipping',
                section: contentSection,
                classes: 'viwec-generate-coupon',
                col: 16,
                inputType: CheckboxInput,
                data: {
                    options: [
                        {id: 'no', text: "No"},
                        {id: 'yes', text: "Yes"},
                    ]
                },
                setup(row) {
                    $('.viwec-coupon-type').find('select').val() === '2' ? row.css('display', 'inline-block') : row.hide();
                    return row;
                }
            },
            {
                name: i18n['individual_use_only'],
                key: "data-coupon-individual",
                htmlAttr: 'data-coupon-individual',
                section: contentSection,
                classes: 'viwec-generate-coupon',
                col: 16,
                inputType: CheckboxInput,
                setup(row) {
                    $('.viwec-coupon-type').find('select').val() === '2' ? row.css('display', 'inline-block') : row.hide();
                    return row;
                }
            },
            {
                name: i18n['exclude_sale_items'],
                key: "data-coupon-exclude-sale",
                htmlAttr: 'data-coupon-exclude-sale',
                section: contentSection,
                classes: 'viwec-generate-coupon',
                col: 16,
                inputType: CheckboxInput,
                setup(row) {
                    $('.viwec-coupon-type').find('select').val() === '2' ? row.css('display', 'inline-block') : row.hide();
                    return row;
                }
            },
            {
                key: "button_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: i18n['coupon']}
            },
            {
                name: "Border width",
                key: "border-width",
                htmlAttr: "childStyle",
                target: '.viwec-coupon',
                section: styleSection,
                col: 8,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 10, step: 1}
            },
            {
                name: "Border radius",
                key: "border-radius",
                htmlAttr: "childStyle",
                target: '.viwec-coupon',
                section: styleSection,
                col: 8,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 50, step: 1}
            },
            {
                name: "Border color",
                key: "border-color",
                htmlAttr: "childStyle",
                target: '.viwec-coupon',
                section: styleSection,
                col: 8,
                inputType: ColorInput
            },
            {
                name: "Border style",
                key: "border-style",
                htmlAttr: "childStyle",
                target: '.viwec-coupon',
                section: styleSection,
                col: 8,
                data: {
                    options: [
                        {id: 'solid', text: 'Solid'},
                        {id: 'dotted', text: 'Dotted'},
                        {id: 'dashed', text: 'Dashed'},
                    ]
                },
                inputType: SelectInput
            },
            {
                name: "Background color",
                key: "background-color",
                htmlAttr: "childStyle",
                target: '.viwec-coupon',
                section: styleSection,
                col: 8,
                inputType: ColorInput
            },
            {
                key: "padding_el_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Padding (px)"}
            },
            {
                name: "Left",
                key: "padding-left",
                htmlAttr: "childStyle",
                target: '.viwec-coupon',
                section: styleSection,
                col: 4,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 20, max: 250}

            },
            {
                name: "Top",
                key: "padding-top",
                htmlAttr: "childStyle",
                target: '.viwec-coupon',
                section: styleSection,
                col: 4,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 20}

            },
            {
                name: "Right",
                key: "padding-right",
                htmlAttr: "childStyle",
                target: '.viwec-coupon',
                section: styleSection,
                col: 4,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 20, max: 250}

            },
            {
                name: "Bottom",
                key: "padding-bottom",
                htmlAttr: "childStyle",
                target: '.viwec-coupon',
                section: styleSection,
                col: 4,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 20}
            }
        ],
        inheritProp: ['text', 'alignment', 'margin']//, 'background']
    });

    const postUrlsTemp = (row = 1, col = 2, currentTitle, currentContent) => {
        row = parseInt(row);
        col = parseInt(col);
        currentTitle = currentTitle || 'Lorem ipsum dolor sit amet, consectetur adipiscing elit';
        currentContent = currentContent || 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque venenatis...';
        let core = '', colWidth = (100 / col) + '%';
        for (let i = 0; i < row; i++) {
            core += '<tr>';
            for (let j = 1; j <= col; j++) {

                core += j !== 1 ? '<td class="viwec-post-distance" style="padding-left: 10px;"></td>' : '';

                if (col === 1) {
                    core += `<td style="width: ${colWidth}" valign="top">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="25%"><img src="${viWecParams.post}" ></td>
                                        <td style="padding-left: 10px;"> 
                                            <div class="viwec-post-title" style="font-size: 15px;">${currentTitle}</div>
                                            <div class="viwec-post-content" style="font-size: 12px; color: #999">${currentContent}</div>
                                        </td>
                                    </tr>
                                 </table>
                          </td>`;
                } else {
                    core += `<td style="width: ${colWidth}" valign="top">
                            <img src="${viWecParams.post}" style="padding-bottom: 5px;">
                            <div class="viwec-post-title" style="font-size: 15px;">${currentTitle}</div>
                            <div class="viwec-post-content" style="font-size: 12px; color: #999">${currentContent}</div>
                          </td>`;
                }

            }
            core += '</tr>';

            if (row > 1 && i !== row - 1) {
                core += '<tr><td class="viwec-post-h-distance" style="padding-top: 10px;"></td></tr>';
            }
        }
        return core;
    };

    ViWec.Components.add({
        type: "html/post",
        name: i18n['post'],
        icon: 'post',
        html: `<div class="viwec-post" style=""><table width="100%" border="0" cellpadding="0" cellspacing="0">` + postUrlsTemp() + '</table></div>',

        properties: [
            {
                key: "post_setting",
                inputType: SectionInput,
                name: false,
                section: contentSection,
                data: {header: i18n['post']},
            },
            {
                name: i18n['categories'],
                key: "data-post-category",
                htmlAttr: 'data-post-category',
                section: contentSection,
                inputType: SelectInput,
                data: {options: [...[{id: '', text: 'All category'}], ...viWecParams.post_categories]},
            },
            {
                name: i18n['include_posts'],
                key: "data-include-post-id",
                htmlAttr: 'data-include-post-id',
                section: contentSection,
                inputType: Select2Input,
                data: {options: viWecCachePosts, multiple: true},
                setup(row) {
                    let $_this = this;
                    row.find('select').select2({
                        width: '100%',
                        minimumInputLength: 2,
                        ajax: {
                            url: viWecParams.ajaxUrl,
                            dataType: 'json',
                            type: "POST",
                            quietMillis: 50,
                            delay: 250,
                            data: function (params) {
                                return {q: params.term, action: 'viwec_search_post', nonce: viWecParams.nonce};
                            },
                            processResults: function (data) {
                                $_this.data.options = [...$_this.data.options, ...data];
                                return {results: data};
                            },
                        },
                    });

                    return row;
                },
            },
            {
                name: i18n['exclude_posts'],
                key: "data-exclude-post-id",
                htmlAttr: 'data-exclude-post-id',
                section: contentSection,
                inputType: Select2Input,
                data: {options: viWecCachePosts, multiple: true},
                setup(row) {
                    let $_this = this;
                    row.find('select').select2({
                        width: '100%',
                        minimumInputLength: 2,
                        ajax: {
                            url: viWecParams.ajaxUrl,
                            dataType: 'json',
                            type: "POST",
                            quietMillis: 50,
                            delay: 250,
                            data: function (params) {
                                return {q: params.term, action: 'viwec_search_post', nonce: viWecParams.nonce};
                            },
                            processResults: function (data) {
                                $_this.data.options = [...$_this.data.options, ...data];
                                return {results: data};
                            },
                        },
                    });

                    return row;
                },
            },
            {
                name: i18n['max_row'],
                key: "data-max_row",
                htmlAttr: 'data-max_row',
                section: contentSection,
                inputType: SelectInput,
                col: 8,
                default: 1,
                data: {
                    options: [
                        {id: 1, text: "1",},
                        {id: 2, text: "2"},
                        {id: 3, text: "3"},
                        {id: 4, text: "4"},
                        {id: 5, text: "5"},
                        {id: 6, text: "6"},
                        {id: 7, text: "7"},
                        {id: 8, text: "8"},
                        {id: 9, text: "9"},
                        {id: 10, text: "10"},
                    ]
                },
                onChange: function (element, value) {
                    let currentTitle = element.find('.viwec-post-title').first().text(),
                        currentContent = element.find('.viwec-post-content').first().text();
                    element.find('table').html(postUrlsTemp(value, element.attr('data-column'), currentTitle, currentContent));
                    element.trigger('click');
                    return element;
                }
            },
            {
                name: i18n['column'],
                key: "data-column",
                htmlAttr: 'data-column',
                section: contentSection,
                inputType: SelectInput,
                default: 2,
                col: 8,
                data: {
                    options: [
                        {id: 1, text: "1",},
                        {id: 2, text: "2",},
                        {id: 3, text: "3"},
                        {id: 4, text: "4"}
                    ]
                },
                onChange: function (element, value) {
                    let currentTitle = element.find('.viwec-post-title').first().text(),
                        currentContent = element.find('.viwec-post-content').first().text();
                    element.find('table').html(postUrlsTemp(element.attr('data-max_row'), value, currentTitle, currentContent));
                    element.trigger('click');
                    return element;
                }
            },
            {
                key: "width",
                target: '.viwec-post',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 0,
                inputType: NumberInput,
            },
            {
                key: "post_title",
                inputType: SectionInput,
                name: false,
                target: '.viwec-post-title',
                section: styleSection,
                data: {header: i18n['post_title']}
            },
            {
                name: i18n['character_limit'],
                key: "data-title-limit",
                htmlAttr: "data-character-limit",
                section: styleSection,
                col: 16,
                inputType: NumberInput,
                sampleTitle: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
                onChange: function (element, value) {
                    let text = this.sampleTitle;
                    if (value) {
                        let threeDots = text.length > value && value > 0 ? '...' : '';
                        text = text.substring(0, value);
                        element.find('.viwec-post-title').text(text + threeDots);
                    } else {
                        element.find('.viwec-post-title').text(text);
                    }
                    return element;
                }
            },
            {
                name: "Font size (px)",
                key: "font-size",
                target: '.viwec-post-title',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            },
            {
                name: "Font weight",
                key: "font-weight",
                target: '.viwec-post-title',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: SelectInput,
                data: {options: viWecFontWeightOptions}
            },
            {
                name: "Color",
                key: "color",
                target: '.viwec-post-title',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: ColorInput
            },
            {
                name: "Line height (px)",
                key: "line-height",
                target: '.viwec-post-title',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            },
            {
                key: "post_content",
                inputType: SectionInput,
                name: false,
                target: '.viwec-post-content',
                section: styleSection,
                data: {header: i18n['post_content']}
            },
            {
                name: i18n['character_limit'],
                key: "data-content-limit",
                htmlAttr: "data-content-limit",
                section: styleSection,
                col: 16,
                inputType: NumberInput,
                data: {value: 80, max: 200},
                sampleTitle: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque venenatis placerat lacus eget venenatis. Quisque volutpat libero ac nisi consectetur egestas a sed neque. Maecenas a ultrices sem. Sed in efficitur risus. Phasellus ultricies volutpat sem, at maximus tortor porta quis. Cras vehicula malesuada risus ut varius. Suspendisse potenti. Nunc ultrices nunc nec elit placerat sagittis.',
                onChange: function (element, value, viewValue, input) {
                    let text = this.sampleTitle;
                    if (value) {
                        let threeDots = text.length > value && parseInt(value) !== 0 ? '...' : '';
                        text = text.substring(0, value);
                        element.find('.viwec-post-content').text(text + threeDots);
                    } else {
                        element.attr(this.key, 0);
                        element.find('.viwec-post-content').text('');
                    }
                    return element;
                },
            },
            {
                name: "Font size (px)",
                key: "font-size",
                target: '.viwec-post-content',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            },
            {
                name: "Font weight",
                key: "font-weight",
                target: '.viwec-post-content',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: SelectInput,
                data: {
                    options: viWecFontWeightOptions
                }
            },
            {
                name: "Color",
                key: "color",
                target: '.viwec-post-content',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                inputType: ColorInput
            },
            {
                name: "Line height (px)",
                key: "line-height",
                target: '.viwec-post-content',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput
            },
            {
                key: "post_space",
                inputType: SectionInput,
                name: false,
                target: '.viwec-post',
                section: styleSection,
                data: {header: i18n['post_distance'] + " (px)"}
            },
            {
                name: i18n['vertical'],
                key: "padding-left",
                target: '.viwec-post-distance',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput,
            },
            {
                name: i18n['horizontal'],
                key: "padding-top",
                target: '.viwec-post-h-distance',
                htmlAttr: "childStyle",
                section: styleSection,
                col: 8,
                unit: 'px',
                inputType: NumberInput,
            }
        ],
        inheritProp: ['alignment', 'background', 'border', 'margin']
    });

    ViWec.Components.add({
        type: "html/contact",
        name: i18n['contact'],
        icon: 'address-book',
        html: `<table class="viwec-contact" width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr><td><a href="${viWecParams.homeUrl}" class="viwec-home-link" ><img class="viwec-home-icon" src="${viWecParams.infor_icons.home[0].id}" style='padding-right: 5px;'><span class="viwec-home-text">${viWecParams.homeUrl}</span></a></td></tr>
                <tr><td><a href="${viWecParams.adminEmail}" class="viwec-email-link" ><img class="viwec-email-icon" src="${viWecParams.infor_icons.email[0].id}" style='padding-right: 5px;'><span class="viwec-email-text">${viWecParams.adminEmail}</span></a></td></tr>
                <tr><td><a href="#" class="viwec-phone-link" ><img class="viwec-phone-icon" src="${viWecParams.infor_icons.phone[0].id}" style='padding-right: 5px;'><span class="viwec-phone-text">${viWecParams.adminPhone}</span></a></td></tr>
            </table>`,
        properties: [{
            key: "home",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Home"},
        }, {
            name: "Icon",
            key: "home",
            target: '.viwec-home-icon',
            htmlAttr: "src",
            section: contentSection,
            col: 16,
            inputType: SelectInput,
            data: {options: viWecParams.infor_icons.home}
        }, {
            name: "Text",
            key: "home_text",
            target: '.viwec-home-text',
            htmlAttr: "innerHTML",
            section: contentSection,
            renderShortcode: true,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true},
        }, {
            name: "URL",
            key: "home_link",
            target: '.viwec-home-link',
            htmlAttr: "href",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true},
        }, {
            key: "email",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Email"},
        }, {
            name: "Icon",
            key: "email",
            target: '.viwec-email-icon',
            htmlAttr: "src",
            section: contentSection,
            col: 16,
            inputType: SelectInput,
            data: {options: viWecParams.infor_icons.email}
        }, {
            name: "Email",
            key: "email_link",
            target: '.viwec-email-link',
            htmlAttr: "href",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true},
            onChange: function (element, value, viewValue, input, component, property) {
                element.find('.viwec-email-text').html(viewValue);
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            key: "phone",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Phone"},
        }, {
            name: "Icon",
            key: "phone",
            target: '.viwec-phone-icon',
            htmlAttr: "src",
            section: contentSection,
            col: 16,
            inputType: SelectInput,
            data: {options: viWecParams.infor_icons.phone}
        }, {
            name: "Number",
            key: "phone_text",
            target: '.viwec-phone-text',
            htmlAttr: "innerHTML",
            section: contentSection,
            col: 16,
            inputType: TextInput,
        }],

        inheritProp: ['text', 'alignment', 'padding', 'background']//, 'border']
    });

    ViWec.Components.add({
        type: "html/menu",
        name: i18n['menu_bar'],
        icon: 'menu',
        html: `<div class="viwec-menu-bar" width="100%"  border="0" cellpadding="0" cellspacing="0" style="display: flex">
                    <div style="flex-grow: 1"><a href="#" class="viwec-menu-link-1">Item 1</a></div>
                    <div style="flex-grow: 1"><a href="#" class="viwec-menu-link-2">Item 2</a></div>
                    <div style="flex-grow: 1"><a href="#" class="viwec-menu-link-3">Item 3</a></div>
                    <div style="flex-grow: 1"><a href="#" class="viwec-menu-link-4">Item 4</a></div>
            </div>`,
        properties: [{
            key: "menu_bar_1",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Link 1"},
        }, {
            name: "Text",
            key: "link1",
            target: '.viwec-menu-link-1',
            htmlAttr: "innerHTML",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            name: "Link",
            key: "link1",
            target: '.viwec-menu-link-1',
            htmlAttr: "href",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true},
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            key: "menu_bar_2",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Link 2"},
        }, {
            name: "Text",
            key: "link2",
            target: '.viwec-menu-link-2',
            htmlAttr: "innerHTML",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            // data: {shortcodeTool: true},
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            name: "Link",
            key: "link2",
            target: '.viwec-menu-link-2',
            htmlAttr: "href",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true},
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            key: "menu_bar_3",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Link 3"},
        }, {
            name: "Text",
            key: "link3",
            target: '.viwec-menu-link-3',
            htmlAttr: "innerHTML",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            // data: {shortcodeTool: true},
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            name: "Link",
            key: "link3",
            target: '.viwec-menu-link-3',
            htmlAttr: "href",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true},
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            key: "menu_bar_4",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Link 4"},
        }, {
            name: "Text",
            key: "link4",
            target: '.viwec-menu-link-4',
            htmlAttr: "innerHTML",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            // data: {shortcodeTool: true},
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            name: "Link",
            key: "link4",
            target: '.viwec-menu-link-4',
            htmlAttr: "href",
            section: contentSection,
            col: 16,
            inputType: TextInput,
            data: {shortcodeTool: true},
            onChange: function (element, value, input, component, property) {
                viWecFunctions.propertyOnChange(element, value);
                return element;
            }
        }, {
            key: "Direction",
            inputType: SectionInput,
            name: false,
            section: styleSection,
            data: {header: "Direction"},
        }, {
            // name: "Direction",
            key: "direction",
            target: '.viwec-menu-bar',
            htmlAttr: "data-direction",
            section: styleSection,
            col: 16,
            inputType: SelectInput,
            data: {options: [{id: 'horizontal', text: 'Horizontal'}, {id: 'vertical', text: 'Vertical'}]},
            onChange(element, value, input, component, property) {
                if (value === 'vertical') element.css('display', 'block');
                if (value === 'horizontal') element.css('display', 'flex');
                return element;
            }
        }],
        inheritProp: ['text', 'alignment', 'padding', 'background']//, 'border']
    });


    let socialProperties = [], socialFirstHtml = '';
    if (viWecParams.social_icons) {
        for (let social in viWecParams.social_icons) {
            let socialName = social[0].toUpperCase() + social.substring(1);

            socialProperties.push({
                    key: social,
                    inputType: SectionInput,
                    section: contentSection,
                    data: {header: socialName},
                },
                {
                    name: "Icon",
                    key: social,
                    target: `.viwec-${social}-icon`,
                    htmlAttr: "src",
                    section: contentSection,
                    col: 16,
                    inputType: SelectInput,
                    data: {options: viWecParams.social_icons[social]},
                },
                {
                    name: `${socialName} URL`,
                    key: `${social}_url`,
                    target: `.viwec-${social}-link`,
                    htmlAttr: "href",
                    section: contentSection,
                    col: 16,
                    inputType: TextInput,
                    data: {title: `https://your_${social}_url`},
                    onChange: function (element, value, input, component, property) {
                        viWecFunctions.propertyOnChange(element, value);
                        return element;
                    }
                });

            socialFirstHtml += `<span class="viwec-social-direction"><a href="" class="viwec-${social}-link" ><img class="viwec-${social}-icon" width="32" src="${viWecParams.social_icons[social][7].id}"></a></span>`;
        }
    }

    socialProperties.push({
            key: "social_image",
            inputType: SectionInput,
            name: false,
            section: styleSection,
            data: {header: "Image"},
        },
        {
            name: "Direction",
            key: "direction",
            target: '.viwec-social-direction',
            htmlAttr: "data-direction",
            section: styleSection,
            col: 16,
            inputType: SelectInput,
            data: {options: [{id: 'horizontal', text: 'Horizontal'}, {id: 'vertical', text: 'Vertical'}]},
            onChange(element, value, input, component, property) {
                if (value === 'vertical') element.css('display', 'block');
                if (value === 'horizontal') element.css('display', 'inline-block');
                return element;
            }
        },
        {
            name: "Width",
            key: "data-width",
            htmlAttr: "data-width",
            section: styleSection,
            col: 16,
            inputType: NumberInput,
            data: {value: 32, max: 48},
            onChange(element, value, input, component, property) {
                if (value) element.find('img').width(value);

                return element;
            }
        });

    ViWec.Components.add({
        type: "html/social",
        name: i18n['socials'],
        icon: 'social',
        html: `<div class="viwec-social" border="0" cellpadding="0" cellspacing="0">${socialFirstHtml}</div>`,
        properties: socialProperties,
        inheritProp: ['alignment', 'padding', 'background']//, 'border'] //'text',
    });

    ViWec.Components.add({
        type: "html/divider",
        name: i18n['divider'],
        icon: 'divider',
        html: `<hr style="border-top: 1px solid; border-bottom:none; margin: 10px 0;">`,
        properties: [{
            key: "text_header",
            inputType: SectionInput,
            name: false,
            section: styleSection,
            data: {header: "Border"},
        }, {
            name: 'Color',
            key: "border-top-color",
            htmlAttr: "childStyle",
            target: 'hr',
            section: styleSection,
            col: 8,
            inputType: ColorInput,
        }, {
            name: 'Width',
            key: "border-top-width",
            htmlAttr: "childStyle",
            target: 'hr',
            section: styleSection,
            col: 8,
            unit: 'px',
            inputType: NumberInput,
            data: {min: 1, step: 1}
        }],
        inheritProp: ['padding', 'background']
    });

    ViWec.Components.add({
        type: "html/spacer",
        name: i18n['spacer'],
        icon: 'spacer',
        html: `<div class="viwec-spacer" style="padding-top: 18px" title="Spacer"></div>`,
        properties: [
            {
                key: "spacer",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Height"},
            },
            {
                key: "padding-top",
                htmlAttr: "childStyle",
                target: '.viwec-spacer',
                section: styleSection,
                col: 16,
                unit: 'px',
                inputType: NumberInput,
                data: {id: 20},
            }
        ],
        // inheritProp: ['background']//
    });

    ViWec.Components.add({
        type: "html/wc_hook",
        name: 'WC Hook',//i18n['spacer'],
        icon: 'hook',
        html: `<div class="viwec-hook-interface">Hook: woocommerce_email_before_order_table</div>`,
        info: `<div class="viwec dashicons dashicons-info red"><span class="info-content-popup">
                    <strong><u>Example:</u></strong>
                    <br/>
                    <strong>woocommerce_email_before_order_table</strong>
                    <br/>
                    - Show Direct bank transfer payment account info
                    <br>
                    <strong>woocommerce_email_order_meta</strong>
                    <br>
                    - Show custom checkout fields 
                    <br>
                    ...
                </span></div>`,

        properties: [
            {
                key: "wc_hook",
                inputType: SectionInput,
                name: false,
                section: contentSection,
                data: {header: "Select hook"},
            },
            {
                key: "data-wc-hook",
                htmlAttr: "data-wc-hook",
                section: contentSection,
                col: 16,
                inputType: SelectInput,
                data: {
                    options: [
                        {id: 'woocommerce_email_before_order_table', text: 'woocommerce_email_before_order_table'},
                        {id: 'woocommerce_email_after_order_table', text: 'woocommerce_email_after_order_table'},
                        {id: 'woocommerce_email_order_meta', text: 'woocommerce_email_order_meta'}
                    ]
                },
                onChange(element, value) {
                    element.find('.viwec-hook-interface').text('Hook: ' + value);
                    return element;
                }
            },
        ],
    });

    ViWec.Components.add({
        type: "html/recover_heading",
        name: 'Heading',//i18n['spacer'],
        icon: 'header',
        category: 'recover',
        html: `<div>The heading of original email will be transferred here</div>`,
        inheritProp: ['text', 'alignment', 'padding', 'background']
    });

    ViWec.Components.add({
        type: "html/recover_content",
        name: 'Content',//i18n['spacer'],
        icon: 'transfer',
        category: 'recover',
        html: `<div>The content of original email will be transferred here</div>`,
    });

});





