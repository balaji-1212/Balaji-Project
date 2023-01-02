'use strict';

//Properties
jQuery(document).ready(function ($) {

    ViWec.Components.addBaseProp({
        type: 'padding',
        name: 'Padding',
        properties: [{
            key: "padding_header",
            inputType: SectionInput,
            name: false,
            section: styleSection,
            data: {header: "Padding (px)"}
        }, {
            name: "Left",
            key: "padding-left",
            htmlAttr: "style",
            section: styleSection,
            col: 4,
            unit: 'px',
            inputType: NumberInput,
            data: {value: 20},
        }, {
            name: "Top",
            key: "padding-top",
            htmlAttr: "style",
            section: styleSection,
            col: 4,
            unit: 'px',
            inputType: NumberInput,
            data: {value: 20},
        }, {
            name: "Right",
            key: "padding-right",
            htmlAttr: "style",
            section: styleSection,
            col: 4,
            unit: 'px',
            inputType: NumberInput,
            data: {value: 20},
        }, {
            name: "Bottom",
            key: "padding-bottom",
            htmlAttr: "style",
            section: styleSection,
            col: 4,
            unit: 'px',
            inputType: NumberInput,
            data: {value: 20},
        }]
    });

    ViWec.Components.addBaseProp({
        type: 'margin',
        name: 'Margin',
        properties: [{
            key: "margin_header",
            inputType: SectionInput,
            name: false,
            section: styleSection,
            data: {header: "Margin (px)"}
        }, {
            name: "Left",
            key: "padding-left",
            htmlAttr: "style",
            section: styleSection,
            col: 4,
            unit: 'px',
            inputType: NumberInput,
            data: {value: 20},
        }, {
            name: "Top",
            key: "padding-top",
            htmlAttr: "style",
            section: styleSection,
            col: 4,
            unit: 'px',
            inputType: NumberInput,
            data: {value: 20},
        }, {
            name: "Right",
            key: "padding-right",
            htmlAttr: "style",
            section: styleSection,
            col: 4,
            unit: 'px',
            inputType: NumberInput,
            data: {value: 20},
        }, {
            name: "Bottom",
            key: "padding-bottom",
            htmlAttr: "style",
            section: styleSection,
            col: 4,
            unit: 'px',
            inputType: NumberInput,
            data: {value: 20},
        }]
    });

    ViWec.Components.addBaseProp({
        type: 'col_padding',
        name: 'Columns distance',
        properties: [{
            key: "distance_header",
            inputType: SectionInput,
            name: false,
            section: styleSection,
            data: {header: "Columns distance"},
        }, {
            name: "Left",
            key: "padding-left",
            htmlAttr: "style",
            target: '.viwec-column',
            section: styleSection,
            col: 4,
            unit: 'px',
            inputType: NumberInput,
            data: {value: 20},
        }, {
            name: "Top",
            key: "padding-top",
            htmlAttr: "style",
            target: '.viwec-column',
            section: styleSection,
            col: 4,
            unit: 'px',
            inputType: NumberInput,
            data: {value: 20},
        }, {
            name: "Right",
            key: "padding-right",
            htmlAttr: "style",
            target: '.viwec-column',
            section: styleSection,
            col: 4,
            unit: 'px',
            inputType: NumberInput,
            data: {value: 20},
        }, {
            name: "Bottom",
            key: "padding-bottom",
            htmlAttr: "style",
            target: '.viwec-column',
            section: styleSection,
            col: 4,
            unit: 'px',
            inputType: NumberInput,
            data: {value: 20},
        }]
    });

    ViWec.Components.addBaseProp({
        type: 'alignment',
        name: 'Alignment',

        properties: [{
            key: "alignment_header",
            inputType: SectionInput,
            name: false,
            section: styleSection,
            data: {header: "Alignment"},
        }, {
            key: "text-align",
            htmlAttr: "style",
            validValues: ["", "text-left", "text-center", "text-right"],
            section: styleSection,
            inputType: RadioButtonInput,
            data: {
                extraClass: "",
                options: [{
                    value: "left",
                    title: "text-left",
                    icon: "dashicons dashicons-editor-alignleft",
                    checked: true,
                }, {
                    value: "center",
                    title: "Center",
                    icon: "dashicons dashicons-editor-aligncenter",
                    checked: false,
                }, {
                    value: "right",
                    title: "Right",
                    icon: "dashicons dashicons-editor-alignright",
                    checked: false,
                }],
            },
            onChange: function (element, value, input, component, property) {
                $(input).parent().parent().find('i').removeClass('viwec-i-active');
                $(input).parent().find('i').addClass('viwec-i-active');
                return element;
            },
        }]
    });

    ViWec.Components.addBaseProp({
        type: 'background',
        name: 'Background',
        properties: [{
            key: "background_header",
            inputType: SectionInput,
            name: false,
            section: styleSection,
            data: {header: "Background"},
        }, {
            name: "Image",
            key: "background-image",
            htmlAttr: "style",
            section: styleSection,
            col: 8,
            inputType: BgImgInput,
            data: {text: 'Select', classes: 'viwec-open-bg-img'}
        }, {
            name: "Color",
            key: "background-color",
            htmlAttr: "style",
            section: styleSection,
            col: 8,
            inputType: ColorInput
        }]
    });

    ViWec.Components.addBaseProp({
        type: 'border',
        name: 'Border',
        properties: [
            {
                key: "border_header",
                inputType: SectionInput,
                name: false,
                section: styleSection,
                data: {header: "Border"},
            },
            {
                name: "Color",
                key: "border-color",
                htmlAttr: "style",
                section: styleSection,
                col: 8,
                inputType: ColorInput,
            },
            {
                name: "Border style",
                key: "border-style",
                htmlAttr: "style",
                section: styleSection,
                col: 8,
                inputType: SelectInput,
                data: {
                    options: [
                        {id: 'solid', text: 'Solid'},
                        {id: 'dotted', text: 'Dotted'},
                        {id: 'dashed', text: 'Dashed'},
                    ]
                }
            },
            {
                groupName: "Width (px)",
                name: "Left",
                key: "border-left-width",
                htmlAttr: "style",
                section: styleSection,
                col: 4,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 50, step: 1}
            },
            {
                name: "Top",
                key: "border-top-width",
                htmlAttr: "style",
                section: styleSection,
                col: 4,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 50, step: 1}
            },
            {
                name: "Right",
                key: "border-right-width",
                htmlAttr: "style",
                section: styleSection,
                col: 4,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 50, step: 1}
            },
            {
                name: "Bottom",
                key: "border-bottom-width",
                htmlAttr: "style",
                section: styleSection,
                col: 4,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 50, step: 1}
            },
            {
                groupName: "Radius (px)",
                name: "Top left",
                key: "border-top-left-radius",
                htmlAttr: "style",
                section: styleSection,
                col: 8,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 50, step: 1}
            },
            {
                name: "Top right",
                key: "border-top-right-radius",
                htmlAttr: "style",
                section: styleSection,
                col: 8,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 50, step: 1}
            },
            {
                name: "Bottom left",
                key: "border-bottom-left-radius",
                htmlAttr: "style",
                section: styleSection,
                col: 8,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 50, step: 1}
            },
            {
                name: "Bottom right",
                key: "border-bottom-right-radius",
                htmlAttr: "style",
                section: styleSection,
                col: 8,
                inputType: NumberInput,
                unit: 'px',
                data: {min: 0, max: 50, step: 1}
            },
        ]
    });

    ViWec.Components.addBaseProp({
        type: 'line_height',
        name: 'Line height (px)',
        properties: [{
            key: "line_height_header",
            inputType: SectionInput,
            name: false,
            section: styleSection,
            data: {header: "Line height (px)"},
        }, {
            key: "line-height",
            htmlAttr: "style",
            section: styleSection,
            unit: 'px',
            col: 16,
            inputType: NumberInput
        }]
    });

    ViWec.Components.addBaseProp({
        type: 'text',
        name: 'Text',
        properties: [{
            key: "text_header",
            inputType: SectionInput,
            name: false,
            section: styleSection,
            data: {header: "Text"},
        }, {
            name: "Font size (px)",
            key: "font-size",
            htmlAttr: "style",
            section: styleSection,
            unit: 'px',
            col: 8,
            inputType: NumberInput
        }, {
            //     name: "Font",
            //     key: "font-family",
            //     htmlAttr: "style",
            //     //     section: styleSection,
            //     col: 4,
            //     inline: true,
            //     inputType: SelectInput,
            //     data: {
            //         options: [{
            //             value: "",
            //             text: "Default"
            //         }, {
            //             value: "Arial, Helvetica, sans-serif",
            //             text: "Arial"
            //         }, {
            //             value: 'Lucida Sans Unicode", "Lucida Grande", sans-serif',
            //             text: 'Lucida Grande'
            //         }, {
            //             value: 'Palatino Linotype", "Book Antiqua", Palatino, serif',
            //             text: 'Palatino Linotype'
            //         }, {
            //             value: '"Times New Roman", Times, serif',
            //             text: 'Times New Roman'
            //         }, {
            //             value: "Georgia, serif",
            //             text: "Georgia, serif"
            //         }, {
            //             value: "Tahoma, Geneva, sans-serif",
            //             text: "Tahoma"
            //         }, {
            //             value: 'Comic Sans MS, cursive, sans-serif',
            //             text: 'Comic Sans'
            //         }, {
            //             value: 'Verdana, Geneva, sans-serif',
            //             text: 'Verdana'
            //         }, {
            //             value: 'Impact, Charcoal, sans-serif',
            //             text: 'Impact'
            //         }, {
            //             value: 'Arial Black, Gadget, sans-serif',
            //             text: 'Arial Black'
            //         }, {
            //             value: 'Trebuchet MS, Helvetica, sans-serif',
            //             text: 'Trebuchet'
            //         }, {
            //             value: 'Courier New", Courier, monospace',
            //             text: 'Courier New", Courier, monospace'
            //         }, {
            //             value: 'Brush Script MT, sans-serif',
            //             text: 'Brush Script'
            //         }]
            //     }
            // }, {
            name: "Font weight",
            key: "font-weight",
            htmlAttr: "style",
            section: styleSection,
            col: 8,
            inputType: SelectInput,
            data: {
                options: viWecFontWeightOptions
            }
        }, {
            name: "Color",
            key: "color",
            htmlAttr: "style",
            section: styleSection,
            col: 8,
            inputType: ColorInput
        }, {
            name: "Line height (px)",
            key: "line-height",
            htmlAttr: "style",
            section: styleSection,
            col: 8,
            unit: 'px',
            inputType: NumberInput
        }]
    });

    ViWec.Components.addBaseProp({
        type: 'edit_cols',
        name: 'Columns',
        properties: [{
            key: "cols_header",
            inputType: SectionInput,
            name: false,
            section: contentSection,
            data: {header: "Columns"},
        }, {
            key: "cols",
            htmlAttr: 'data-cols',
            section: contentSection,
            inputType: NumberInput,
            data: {min: 1, max: 4, step: 1},
            onChange(element, value, input, component, property) {
                if (value > 0 && value <= 4) {
                    let currentCols, diff,
                        dataType = `layout/grid${value}cols`,
                        width = (100 / value) + '%';

                    currentCols = element.find('.viwec-column');
                    diff = Math.abs(currentCols.length - value);
                    if (currentCols.length > value) {
                        for (let i = 0; i < diff; i++) {
                            element.find('.viwec-column').last().remove();
                        }
                    } else {
                        for (let i = 0; i < diff; i++) {
                            let lastRow = element.find('.viwec-column').last();
                            let clone = lastRow.clone();
                            clone.find('.viwec-layout-sortable').columnSortAble();
                            lastRow.after(clone);
                        }
                    }

                    element.find('.viwec-column').width(width);
                    element.attr('data-type', dataType);
                    element.find('.viwec-layout-inner').attr('data-type', dataType);
                }
                return element;
            }
        }],
    });
});