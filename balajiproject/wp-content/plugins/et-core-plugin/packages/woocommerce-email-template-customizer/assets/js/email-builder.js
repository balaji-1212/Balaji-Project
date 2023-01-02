'use strict';

let styleSection = 'style',
    contentSection = 'content',
    viWecEditorArea = '#viwec-email-editor-content',
    viWecChange = true,

    viWecFontWeightOptions = [
        {id: 300, text: 300},
        {id: 400, text: 400},
        {id: 500, text: 500},
        {id: 600, text: 600},
        {id: 700, text: 700},
        {id: 800, text: 800},
        {id: 900, text: 900}
    ],

    viWecAlignmentOptions = [
        {value: "left", title: "Left", icon: "dashicons dashicons-editor-alignleft", checked: true,},
        {value: "center", title: "Center", icon: "dashicons dashicons-editor-aligncenter", checked: false,},
        {value: "right", title: "Right", icon: "dashicons dashicons-editor-alignright", checked: false,}
    ],

    viWecMapObj = viWecParams.shortcode_for_replace;


let viWecShortcodeList = viWecParams.shortcode.map((item) => {
    return {text: item, value: item};
});

if (viWecParams.sc_3rd_party_for_text_editor) {
    for (let sc in viWecParams.sc_3rd_party_for_text_editor) {
        viWecShortcodeList.push(viWecParams.sc_3rd_party_for_text_editor[sc]);
    }
}

let viWecShortcodeListValue = '';
if (viWecParams.shortcode) {
    for (let sc of viWecParams.shortcode) {
        viWecShortcodeListValue += '<li>' + sc + '</li>'
    }
}

if (viWecParams.sc_3rd_party) {
    for (let sc of viWecParams.sc_3rd_party) {
        viWecShortcodeListValue += '<li>' + sc + '</li>'
    }
}

if (ViWec === undefined) {
    var ViWec = {};
}

(function () {
    var cache = {};
    window.viWecTmpl = function viWecTmpl(str, data) {
        var fn = /^[-a-zA-Z0-9]+$/.test(str) ? cache[str] = cache[str] || viWecTmpl(document.getElementById(str).innerHTML) :
            new Function("obj", "var p=[],print=function(){p.push.apply(p,arguments);};" + "with(obj){p.push('" +
                str.replace(/[\r\t\n]/g, " ")
                    .split("{%").join("\t")
                    .replace(/((^|%})[^\t]*)'/g, "$1\r")
                    .replace(/\t=(.*?)%}/g, "',$1,'")
                    .split("\t").join("');")
                    .split("%}").join("p.push('")
                    .split("\r").join("\\'")
                + "');}return p.join('');");
        // Provide some basic currying to the user
        return data ? fn(data) : fn;
    };
})();

jQuery(document).ready(function ($) {

    $.fn.handleRow = function () {

        if (this.find('.viwec-layout-handle-outer').length === 0) {
            this.append(viWecTmpl('viwec-input-handle-outer', {}));
        }

        this.on('click', '.viwec-delete-row-btn', () => {
            this.remove();
            ViWec.Builder.clearTab();
            ViWec.Builder.activeTab('components');
        });

        this.on('click', '.viwec-duplicate-row-btn', () => {
            let clone = this.clone();
            clone.find('.viwec-column-sortable').columnSortAble();
            clone.handleRow().handleColumn();
            this.after(clone);
        });

        this.on('click', '.viwec-edit-outer-row-btn', () => {
            ViWec.Builder.removeFocus();
            this.addClass('viwec-block-focus');
            ViWec.Builder.selectedEl = this.find('.viwec-layout-row');
            ViWec.Builder.loadLayoutControl();
        });

        return this;
    };

    $.fn.handleElement = function () {
        this.append(`<div class="viwec-element-handle"><span class="dashicons dashicons-admin-page viwec-duplicate-element-btn" title="Duplicate"></span>
                <span class="dashicons dashicons-no-alt viwec-delete-element-btn" title="Delete"></span></div>`);
    };

    $.fn.columnSortAble = function () {
        $(this).sortable({
            cursor: 'move',
            cursorAt: {left: 40, top: 18},
            placeholder: 'viwec-placeholder',
            connectWith: ".viwec-column-sortable",
            thisColumn: '',
            accept: '.viwec-content-draggable',
            start: function (ev, ui) {
                // ui.placeholder.height(30);
                ui.helper.addClass('viwec-is-dragging');
                this.thisColumn = ui.helper.closest('.viwec-column');
            },
            stop: function (ev, ui) {
                let style = ui.item.get(0).style;
                style.position = style.top = style.left = style.right = style.bottom = style.height = style.width = '';
                ui.item.removeClass('viwec-is-dragging');
                if (ui.item.offsetParent().find('.viwec-element').length) {
                    ui.item.offsetParent().removeClass('viwec-column-placeholder');
                }
                if (!(this.thisColumn.find('.viwec-element').length)) {
                    this.thisColumn.addClass('viwec-column-placeholder');
                }
                ui.item.click();
                viWecChange = true;
            }
        });
    };


    $.fn.handleColumn = function () {
        this.on('click', (e) => {
            if (this.hasClass('viwec-column-placeholder') || this.find('.viwec-column-placeholder').length) {
                ViWec.Builder.removeFocus();
                ViWec.Builder.selectedEl = this.find('.viwec-column-sortable').addClass('viwec-block-focus');
                ViWec.Builder.loadLayoutControl('editColumn');
            }
        });

        this.on('click', '.viwec-column-edit', () => {
            ViWec.Builder.removeFocus();
            ViWec.Builder.selectedEl = this.find('.viwec-column-sortable').addClass('viwec-block-focus');
            ViWec.Builder.loadLayoutControl('editColumn');
        });

        return this;
    };

    ViWec.viWecReplaceShortcode = (text) => {
        if (!text || typeof text !== 'string') return text;

        var re = new RegExp(Object.keys(viWecMapObj).join("|"), "gm");
        text = text.replace(re, function (matched) {
            return viWecMapObj[matched];
        });

        return text;
    };

    ViWec.Components = {
        _categories: {},
        _components: {
            baseProp: {}
        },

        init() {
            this.registerCategory('sample', 'Sample');
            this.registerCategory('layout', 'Layout');
            this.registerCategory('content', 'Content');
            this.registerCategory('recover', 'Default template');
        },

        registerCategory(id, name) {
            if (!this._categories[id]) this._categories[id] = {name: name, elements: []};
        },

        get: function (type) {
            return this._components[type];
        },

        add(data) {
            let categoryType = data.category || 'content';
            if (this._categories[categoryType]) this._categories[categoryType].elements.push(data.type);

            if (data.inheritProp) {
                let inheritProperties = [];
                for (let property of data.inheritProp) {
                    if (this._components.baseProp[property]) {
                        inheritProperties = [...inheritProperties, ...this._components.baseProp[property].properties];
                    }
                }

                if (!data.properties) data.properties = [];
                data.properties = [...data.properties, ...inheritProperties];
            }

            this._components[data.type] = data;
        },

        addBaseProp(data) {
            this._components.baseProp[data.type] = data;
        },

        render: function (type) {
            let component = this._components[type], section, attributesArea = $('#viwec-attributes-list');

            if (!component) return;

            //set to viewer
            var bindOnChangeToViewer = function (component, property, element) {

                return property.input.on('propertyChange', function (event, value, input) {
                    viWecChange = true;

                    let viewValue = ViWec.viWecReplaceShortcode(value);

                    if (property.outputValue) value = property.outputValue;

                    if (property.htmlAttr) {
                        if (["style", 'childStyle'].indexOf(property.htmlAttr) > -1) {
                            let unit = property.unit ? property.unit : '';
                            element = ViWec.StyleManager.setStyle(element, property.key, value, unit);
                        } else if (property.htmlAttr === "innerHTML") {
                            if (property.renderShortcode) {
                                let clone = element.clone();
                                element = element.html(value).hide();

                                let virElement = element.parent().find('.viwec-text-view');
                                if (virElement.length === 0) {
                                    clone = clone.removeClass().html(viewValue).addClass('viwec-text-view');
                                    element.after(clone);
                                } else {
                                    virElement.html(viewValue);
                                }
                            } else {
                                element.html(value);
                            }
                        } else {
                            element = element.attr(property.htmlAttr, value);
                        }
                    }

                    if (typeof component.onChange === 'function') {
                        element = component.onChange(element, property, value, input);
                    }
                    if (typeof property.onChange === 'function') {
                        element = property.onChange(element, value, viewValue, input, component, property);
                    }

                    return element;
                });
            };

            let currentKey = '';

            //render control
            if (component.name) attributesArea.append(`<div id="viwec-component-name">Component: ${component.name}</div>`);

            for (let i in component.properties) {
                var property = component.properties[i];
                var element = ViWec.Builder.selectedEl;

                if (property.visible === false || property.target && !element.find(property.target).length) continue;
                if (property.target && element.find(property.target).length) element = element.find(property.target);

                if (property.data) {
                    property.data["key"] = property.key;
                    if (property.name) property.data["header"] = property.name;
                } else {
                    property.data = {"key": property.key};
                    if (property.name) property.data["header"] = property.name;
                }

                if (!property.inputType) continue;

                property.input = property.inputType.init(property.data);


                if (property.init) {
                    property.inputType.setValue(property.init(element.get(0)));
                } else if (property.htmlAttr) {
                    let value;
                    if (property.htmlAttr === "style") {
                        value = ViWec.StyleManager.getStyle(element, property);
                    } else if (property.htmlAttr === "childStyle") {
                        value = ViWec.StyleManager.getStyle(element, property);
                    } else if (property.htmlAttr === "innerHTML") {
                        value = element.html();
                    } else {
                        value = element.attr(property.htmlAttr);
                    }

                    if (!value && property.default) {
                        value = property.default;
                    }

                    if (value) {
                        property.inputType.setValue(value); //set to control
                    }
                }

                bindOnChangeToViewer(component, property, element);

                section = property.section ? property.section : '';
                if (section) {

                    if (attributesArea.find(`.viwec-${section}`).length === 0) {
                        attributesArea.append(`<div class="viwec-${section} vi-ui accordion styled fluid">
                                                <div class="title active">
                                                    <i class="dropdown icon"></i>
                                                    ${section.replace(/^./, section[0].toUpperCase())}
                                                </div>
                                                <div class="content active ${section}-properties">
                                            </div></div>`);
                    }

                    if (property.inputType === SectionInput) {
                        attributesArea.find(`.viwec-${section} .${section}-properties`).append(viWecTmpl("viwec-input-sectioninput", property.data));
                        currentKey = property.key ? property.key : currentKey;
                    } else {
                        if (!property.hidden) {
                            let row = $(viWecTmpl('viwec-property', property));
                            row.find('.input').append(property.input);
                            if (typeof property.setup === 'function') row = property.setup(row); //Add custom events

                            attributesArea.find(`.viwec-${section} .${currentKey}`).append(row);
                            if (typeof property.inputType.subInit === 'function') {
                                property.inputType.subInit(element);
                            }
                        }

                    }

                    if (property.inputType.afterInit) {
                        property.inputType.afterInit(property.input);
                    }
                }
            }

            $('.vi-ui.accordion').accordion();

            if (component.init) component.init(ViWec.Builder.selectedEl.get(0));
        }
    };


    ViWec.Blocks = {
        _blocks: {},

        get: function (type) {
            return this._blocks[type];
        },

        add: function (type, data) {
            data.type = type;
            this._blocks[type] = data;
        },
    };


    ViWec.Builder = {
        component: {},
        dragMoveMutation: false,
        isPreview: false,
        designerMode: false,

        init: function (callback) {
            var self = this;

            self.selectedEl = null;
            self.initCallback = callback;
            self.dragElement = null;

            self.loadControlGroups();
            self.initDragDrop();
            self.initHandleBox();
            self.loadContentControl();
            self.loadBackgroundControl();
            self.initQuickAddLayout();
            self.globalEvent();
        },

        /* controls */
        loadControlGroups: function () {
            let componentsList = $("#viwec-components-list"), item = {}, component = {};
            componentsList.empty();

            for (let group in ViWec.Components._categories) {
                componentsList.append(`<div class="vi-ui accordion styled fluid">
                                <div class="title active">
                                    <i class="dropdown icon"></i>
                                   ${ViWec.Components._categories[group].name}
                                </div>
                                <div class="content active" data-section="${group}">
                                    <ul></ul>
                                </div>
                            </div>`);

                let componentsSubList = componentsList.find('div[data-section="' + group + '"] ul');
                let components = ViWec.Components._categories[group].elements;
                group = group === 'layout' ? 'layout' : 'content';

                for (let i in components) {
                    let componentType = components[i], controlBtn;
                    component = ViWec.Components.get(componentType);

                    if (component) {
                        if (typeof component.setup === 'function') {
                            item = component.setup();
                            if (typeof component.onChange === 'function') component.onChange(item);
                        } else {
                            let classes = component.classes || '',
                                unLock = classes.includes('viwec-pro-version'),
                                dragAble = unLock ? '' : `viwec-${group}-draggable`,
                                unlockNotice = unLock ? "<div class='viwec-unlock-notice'><a href='#'>Unlock this feature</a></div>" : '',
                                lockIcon = unLock ? "<div class='dashicons dashicons-lock'></div>" : '',
                                info = component.info || '';

                            controlBtn = `<div class="viwec-control-btn ${dragAble} ${classes}" data-type="${componentType}" data-drag-type="component">
                                            ${lockIcon} ${unlockNotice} ${info}
                                            <div class="viwec-control-icon">
                                                <i class="viwec-ctrl-icon-${component.icon}"></i>
                                            </div>
                                            <div class="viwec-ctrl-title">${component.name}</div>`;

                            item = $(`<li  data-section="${group}">
                                    ${controlBtn}
                                    </div></li>`);
                        }

                        componentsSubList.append(item);
                        if (group === 'layout') {
                            $('#viwec-quick-add-layout .viwec-layout-list').append(controlBtn);
                        }
                    }
                }
            }

            $('.vi-ui.accordion').accordion();
        },

        activeTab: (tab) => {
            $('#viwec-control-panel .item, #viwec-control-panel .tab').removeClass('active');
            $(`#viwec-control-panel [data-tab=${tab}]`).addClass('active');
        },

        clearTab: () => {
            $('#viwec-control-panel #viwec-attributes-list').empty();
        },

        loadLayoutControl: function (dataType) {
            this.clearTab();
            this.activeTab('editor');
            let type = dataType || this.selectedEl.data('type');
            ViWec.Components.render(type);
        },

        loadContentControl: function () {
            let self = this, body = $('#viwec-email-editor-wrapper');
            body.on('click', '.viwec-element', function (e) {
                self.removeFocus();
                $(this).addClass('viwec-element-focus');
                self.clearTab();
                self.activeTab('editor');
                let type = $(this).data('type');
                self.selectedEl = $(this);
                ViWec.Components.render(type);
            });
        },

        loadBackgroundControl: function () {
            let self = this;
            $('.viwec-edit-bgcolor-btn span').on('click', function (e) {
                self.clearTab();
                self.activeTab('editor');
                self.selectedEl = $('#viwec-email-editor-wrapper');
                ViWec.Components.render('background');
            });
        },

        initHandleBox: function () {
            let self = this, body = $('#viwec-email-editor-wrapper');

            body.on('click', '.viwec-delete-element-btn', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                let $this = $(this);
                let thisColumn = $this.closest('.viwec-column');
                $this.closest('.viwec-element').remove();
                self.clearTab();
                self.activeTab('components');
                if (thisColumn.find('.viwec-element').length === 0) {
                    thisColumn.addClass('viwec-column-placeholder');
                }
            });

            body.on('click', '.viwec-duplicate-element-btn', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                let currentEl = $(this).closest('.viwec-element');
                currentEl.after(currentEl.clone());
                self.removeFocus();
            });

            body.on('click', function (e) {
                if ($(e.target).is('.viwec-layout-row')) {
                    self.removeFocus();
                    self.selectedEl = $(e.target);
                    self.selectedEl.closest('.viwec-block').addClass('viwec-block-focus');
                    self.loadLayoutControl();
                }
            })
        },

        removeFocus: function () {
            $('body .viwec-element-focus').removeClass('viwec-element-focus');
            $('body .viwec-block-focus').removeClass('viwec-block-focus');
            this.clearTab();
            this.activeTab('components');
        },

        /* drag and drop */
        initDragDrop: function () {
            let self = this;
            $('.viwec-layout-draggable').draggable({
                cursor: 'move',
                cursorAt: {left: 40, top: 15},
                helper: function () {
                    let type = $(this).data('type'), colsQty;
                    self.component = ViWec.Components.get(type);
                    colsQty = self.component.cols;
                    return viWecTmpl('viwec-block', {type: type, colsQty: colsQty});
                },
                start: function (e, ui) {
                    ui.helper.addClass('viwec-is-dragging');
                },
                stop: function (e, ui) {
                    ui.helper.handleRow();
                    ui.helper.find('.viwec-column').each(function (i, _this) {
                        $(_this).handleColumn();
                    });
                    ui.helper.removeClass('viwec-is-dragging');
                    viWecChange = true;
                },
                connectToSortable: viWecEditorArea
            });

            $('.viwec-sortable').sortable({
                cursor: 'move',
                placeholder: 'viwec-placeholder',
                handle: '.dashicons-move',
                cancel: '',
                cursorAt: {left: 40, top: 18},
                start: function (e, ui) {
                    // ui.placeholder.height(30);
                    ui.helper.addClass('viwec-is-dragging');
                },
                stop: function (ev, ui) {
                    ui.item.css({'max-width': '600px', 'width': 'auto', 'height': 'auto', 'z-index': 'unset'});
                    ui.item.find('.viwec-column-sortable').columnSortAble();
                    ui.item.removeClass('viwec-is-dragging');
                    viWecChange = true;
                }
            });

            $('.viwec-content-draggable').draggable({
                cursor: 'move',
                cursorAt: {left: 40, top: 15},
                helper: function () {
                    let $this = jQuery(this), html;

                    if ($this.data("drag-type") === "component") {
                        self.component = ViWec.Components.get($this.data("type"));
                    } else {
                        self.component = ViWec.Blocks.get($this.data("type"));
                    }

                    if (self.component.dragHtml) {
                        html = self.component.dragHtml;
                    } else {
                        html = self.component.html;
                    }

                    if ($(viWecEditorArea).children().length === 0) {
                        let row = $(viWecTmpl('viwec-block', {type: 'layout/grid1cols', colsQty: 1}));
                        row.handleRow().handleColumn();
                        row.find('.viwec-column-sortable').columnSortAble();
                        $('.viwec-sortable').append(row);
                    }

                    return `<div class='viwec-element' style="font-size:15px;border-radius: 0; overflow: hidden;line-height: 22px;" data-type="${$this.data('type')}">${html}</div>`;
                },
                start: function (ev, ui) {
                    ui.helper.addClass('viwec-is-dragging');
                },
                drag: function (ev, ui) {
                },
                stop: function (ev, ui) {
                    ui.helper.handleElement();
                    ui.helper.removeClass('viwec-is-dragging');
                    ui.helper.css('z-index', '');
                    ui.helper.click();
                    viWecChange = true;
                    $('#viwec-element-search input.viwec-search').val('').trigger('keyup');
                },
                connectToSortable: '.viwec-column-sortable'
            });
        },
        initQuickAddLayout: function () {
            $('#viwec-quick-add-layout .viwec-control-btn').on('click', function () {
                let type = $(this).data('type'), colsQty, row;
                self.component = ViWec.Components.get(type);
                colsQty = self.component.cols;
                row = $(viWecTmpl('viwec-block', {type: type, colsQty: colsQty}));
                row.handleRow().handleColumn();
                row.find('.viwec-column-sortable').columnSortAble();
                $('.viwec-sortable').append(row);
                $(this).closest('.viwec-layout-list').toggle();
            });
        },

        globalEvent: function () {
            let $this = this, body = $('body');

            body.on('click', function (e) {
                if ($(e.target).is('#wpwrap') || $(e.target).is('#viwec-email-editor-wrapper')) {
                    $this.removeFocus();
                    $('.viwec-layout-list').hide();
                }
            });
        }
    };

    ViWec.StyleManager = {

        setStyle: function (element, styleProp, value, unit) {
            return element.css(styleProp, value + unit);
        },

        _getCssStyle: function (element, property, key = null) {
            let styleProp = key ? key : property.key;

            if (styleProp === 'width' && property.unit && property.unit === '%') {
                let child = parseInt(element.css('width'));
                let parent = parseInt(element.parent().css('width'));
                if (parent > 0) {
                    return Math.round((child / parent) * 100) + '%';
                }
            } else {
                let el = element.get(0), css;
                if (el) {
                    if (el.style && el.style.length > 0 && el.style[styleProp])//check inline
                        css = el.style[styleProp];
                    else if (el.currentStyle)	//check defined css
                        css = el.currentStyle[styleProp];
                    else if (window.getComputedStyle) {
                        css = document.defaultView.getDefaultComputedStyle ?
                            document.defaultView.getDefaultComputedStyle(el, null).getPropertyValue(styleProp) :
                            window.getComputedStyle(el, null).getPropertyValue(styleProp);
                    }
                    if (css === 'transparent') css = '';
                    return css;
                }
            }
        },

        getStyle: function (element, property, key) {
            return this._getCssStyle(element, property, key);
        }
    };

});

