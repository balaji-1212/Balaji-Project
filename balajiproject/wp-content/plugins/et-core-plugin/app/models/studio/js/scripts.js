!function(e, t) {
    window.et_studio = window.et_studio || {};
    // set default etheme
    var etheme = {
        Views: {},
        Models: {},
        Collections: {},
        Behaviors: {},
        Layout: null,
        Manager: null
    };
    etheme.Models.Template = Backbone.Model.extend({
        defaults: {
            template_id: 0,
            title: "",
            type: "",
            thumbnail: "",
            url: "",
            tags: [],
            cats: [],
            folder : ''
        }
    });
    elementorCommon.ajax.addRequest("get_et_filters_data",{
        data: {},
        success: function(response) {
            et_studio.library.et_filters = response;
        }
    });
    etheme.Collections.Template = Backbone.Collection.extend({
        model: etheme.Models.Template
    });
    etheme.Behaviors.InsertTemplate = Marionette.Behavior.extend({
        ui: {
            insertButton: ".et-studio-insert-button"
        },
        events: {
            "click @ui.insertButton": "onInsertButtonClick"
        },
        onInsertButtonClick: function() {
            et_studio.library.insertTemplate({
                model: this.view.model
            });
        }
    });
    etheme.Views.EmptyTemplateCollection = Marionette.ItemView.extend({
        id: "elementor-template-library-templates-empty",
        template: "#tmpl-et-studio-empty",
        ui: {
            title: ".elementor-template-library-blank-title",
            message: ".elementor-template-library-blank-message"
        },
        modesStrings: {
            empty: {
                title: EthemeStudioJsData.Texts.EmptyTitle,
                message: EthemeStudioJsData.Texts.EmptyMessage
            },
            noResults: {
                title: EthemeStudioJsData.Texts.NoResultsTitle,
                message: EthemeStudioJsData.Texts.NoResultsMessage
            }
        },
        getCurrentMode: function() {
            return et_studio.library.getFilter("text") ? "noResults" : "empty";
        },
        onRender: function() {
            var mode = this.modesStrings[this.getCurrentMode()];
            this.ui.title.html(mode.title);
            this.ui.message.html(mode.message);
        }
    });
    etheme.Views.Loading = Marionette.ItemView.extend({
        template: "#tmpl-et-studio-loading",
        id: "et-studio-loading"
    });

    // set logo options ready
    etheme.Views.Logo = Marionette.ItemView.extend({
        template: "#tmpl-et-studio-logo",
        className: "tmpl-et-studio-logo",
    });

    etheme.Views.BackButton = Marionette.ItemView.extend({
        template: "#tmpl-et-studio-back",
        id: "elementor-template-library-header-preview-back",
        className: "tmpl-et-studio-back",
        events: function() {
            return {
                click: "onClick"
            }
        },
        onClick: function() {
            et_studio.library.showBlocksView();
            e('.elementor-templates-modal__header__close.elementor-templates-modal__header__close--normal.elementor-templates-modal__header__item').removeClass('hidden');
        }
    });

    // set menu options ready
    etheme.Views.Menu = Marionette.ItemView.extend({
        template: "#tmpl-et-studio-menu",
        id: "elementor-template-library-header-menu",
        className: "tmpl-et-studio-menu",
    });


    etheme.Views.ResponsiveMenu = Marionette.ItemView.extend({
        template: "#tmpl-et-studio-menu-responsive",
        id: "elementor-template-library-header-menu-responsive",
        className: "tmpl-et-studio-menu-responsive",

    });

    // add sync btn
    etheme.Views.Actions = Marionette.ItemView.extend({
        template: "#tmpl-et-studio-actions",
        id: "elementor-template-library-header-actions",
        ui: {
            sync: "#et-studio-sync i"
        },
        events: {
            "click @ui.sync": "onSyncClick"
        },
        onSyncClick: function() {
            var _this = this;
            _this.ui.sync.addClass("eicon-animation-spin"),
                et_studio.library.requestLibraryData({
                    onUpdate: function() {
                        _this.ui.sync.removeClass("eicon-animation-spin"),
                            et_studio.library.updateBlocksView()
                    },
                    forceUpdate: !0,
                    forceSync: !0
                });
            e('.et-studio-search input').attr('value', '');
        }
    });
    etheme.Views.InsertWrapper = Marionette.ItemView.extend({
        template: "#tmpl-et-studio-header-insert",
        id: "elementor-template-library-header-preview",
        behaviors: {
            insertTemplate: {
                behaviorClass: etheme.Behaviors.InsertTemplate
            }
        }
    });
    etheme.Views.Preview = Marionette.ItemView.extend({
        template: "#tmpl-et-studio-preview",
        className: "et-studio-preview",
        ui: {
            iframe: "> iframe",
        },
        onRender: function() {
            var _this = this,
                views = (new etheme.Views.Loading).render();

            _this.ui.iframe.html('').attr("src", _this.getOption("url"));
            _this.$el.append(views.el);
            _this.ui.iframe.on("load", function() {
                _this.$el.parents('#et-studio-modal').find('.tmpl-et-studio-menu-responsive li a').on('click', function() {
                    _this.ui.iframe.html('').attr('src', '').hide();
                    _this.$el.parents('#et-studio-modal').find('#et-studio-loading').show();
                    _this.$el.parents('#et-studio-modal').find('.tmpl-et-studio-menu-responsive li a').removeClass('active');
                    jQuery(this).addClass('active');
                    _this.ui.iframe.attr('data-device', jQuery(this).attr('data-device'));
                    _this.ui.iframe.attr("src", _this.getOption("url")).show(); // 02 to make sticky header refresh
                    _this.ui.iframe.on("load", function() { // 03
                        setTimeout(function () {
                            _this.$el.parents('#et-studio-modal').find('#et-studio-loading').hide();
                        }, 900);
                    });
                });
                setTimeout(function () {
                    _this.$el.parents('#et-studio-modal').find('#et-studio-loading').hide();
                }, 900);
            })
        }
    });
    etheme.Views.TemplateCollection = Marionette.CompositeView.extend({
        template: "#tmpl-et-studio-templates",
        id: "tmpl-et-studio-templates",
        childViewContainer: "#et-studio-templates-list",
        emptyView: function() {
            return new etheme.Views.EmptyTemplateCollection;
        },
        ui: {
            templatesWindow: ".et-studio-templates-window",
        },
        getChildView: function(e) {
            return etheme.Views.Template;
        },
        initialize: function() {
        },

        onFilterClick: function(container) {
            e('.et_studio-filter').on('click', function(){
                var value = e(this).attr('data-filter');
                e('.et_studio-filter').removeClass('active');
                e(this).addClass('active');
                container.addClass('filtered');
                container.isotope({
                    filter: function() {
                        var cats = e(this).find('.et-studio-template-body').attr('data-cats');
                        return cats.includes(value);
                    },
                });
            });
        },

        onSearchChange: function(container) {
            e('.et-studio-search input').on('keyup', function(){
                var value = e(this).val(),
                    search = e(this).parents('.et-studio-search');

                search.addClass('loading');
                container.addClass('filtered');
                if ( value.length > 3 ) {
                    container.isotope({
                        filter: function(  ) {
                            var tags = e(this).find('.et-studio-template-body').attr('data-tags');
                            return tags.includes(value);
                        }
                    });
                } else {
                    container.isotope({
                        filter: function() {
                            var tags = e(this).find('.et-studio-template-body').attr('data-tags');
                            return tags.includes('all');
                        }
                    });
                }
                if ( !container.data('isotope').filteredItems.length ) {
                    e('.et-studio-empty-search-request').html(value);
                    e('.et-studio-empty-search').removeClass('hidden');
                }else{
                    e('.et-studio-empty-search').addClass('hidden');
                }
                setTimeout(function () {
                    search.removeClass('loading');
                }, 500);
            });


        },

        setIsotope: function(container) {
            var img_count = container.find('img').length;
            this.$childViewContainer.imagesLoaded(container).always( function( instance ) {
                container.isotope({
                    itemSelector: '.et-studio-template',
                });
                container.find('.et-studio-template').each(function() {
                    var e_this = e(this),
                        element_top = e_this.offset().top,
                        element_height = e_this.outerHeight();

                    if (element_top+element_height > e('#et-studio-modal .dialog-message').outerHeight() + 500){
                    }
                    else {
                        e_this.addClass('loaded');
                    }

                    e('#et-studio-modal .dialog-message').scroll(function () {
                        var element_top = e_this.offset().top,
                            window_scroll = e(this).scrollTop(),
                            is_on_screen  = ( window_scroll > element_top );
                        if (!is_on_screen) return;
                        e_this.addClass('loaded');
                    });
                });
                e('.et-studio-loader').addClass('disabled');
            }).progress(function( instance, image ) {
                var size = parseInt(e('.et-studio-loaded-images').attr('data-size'))+1;
                e('.et-studio-loaded-images').attr('data-size', size);
                var percent_val = size / img_count * 100;
                e('.et-studio-loaded-images').html(percent_val.toFixed(1) + '%');
            });

            this.onSearchChange(container);
            this.onFilterClick(container);
        },

        onRenderCollection: function() {
            var container = this.$childViewContainer;
            this.setIsotope(container);
            this.updatePerfectScrollbar();
        },

        updatePerfectScrollbar: function() {
            this.perfectScrollbar || (this.perfectScrollbar = new PerfectScrollbar(this.ui.templatesWindow[0],{
                suppressScrollX: !0
            }));
            this.perfectScrollbar.isRtl = !1;
            this.perfectScrollbar.update();
        },

        onRender: function() {
            this.updatePerfectScrollbar();
        }
    }),
        etheme.Views.Template = Marionette.ItemView.extend({
            template: "#tmpl-et-studio-template",
            className: "et-studio-template elementor-template-library-template elementor-template-library-template-remote elementor-template-library-template-block",
            ui: {
                previewButton: ".et-template-preview-button, .et-template-preview"
            },
            events: {
                "click @ui.previewButton": "onPreviewButtonClick"
            },
            behaviors: {
                insertTemplate: {
                    behaviorClass: etheme.Behaviors.InsertTemplate
                }
            },
            onPreviewButtonClick: function() {
                et_studio.library.showPreviewView(this.model);
                e('.elementor-templates-modal__header__close.elementor-templates-modal__header__close--normal.elementor-templates-modal__header__item').addClass('hidden');
            }
        }),
        etheme.Modal = elementorModules.common.views.modal.Layout.extend({
            getModalOptions: function() {
                return {
                    id: "et-studio-modal",
                    hide: {
                        onOutsideClick: !1,
                        onEscKeyPress: !0,
                        onBackgroundClick: !1
                    }
                }
            },
            getTemplateActionButton: function(e) {
                var template = Marionette.TemplateCache.get("#tmpl-et-studio-insert-button");
                return Marionette.Renderer.render(template);
            },
            showLogo: function(e) {
                this.getHeaderView().logoArea.show(new etheme.Views.Logo(e))
            },
            showDefaultHeader: function() {
                this.showLogo();
                var view = this.getHeaderView();
                view.tools.show(new etheme.Views.Actions);
                view.menuArea.show(new etheme.Views.Menu);
            },
            showPreviewView: function(model) {
                var HeaderView = this.getHeaderView();

                HeaderView.menuArea.show(new etheme.Views.ResponsiveMenu);
                HeaderView.logoArea.show(new etheme.Views.BackButton);
                HeaderView.tools.show(new etheme.Views.InsertWrapper({
                    model: model
                }));
                this.modalContent.show(new etheme.Views.Preview({
                    url: model.get("url")
                }));
            },
            showBlocksView: function(collection) {
                this.modalContent.show(new etheme.Views.TemplateCollection({
                    collection: collection
                }))
            }
        }),
        etheme.Manager = function() {
            function showStudioBtn(previewContents) {
                var selector = previewContents.find('.elementor-add-new-section .elementor-add-section-drag-title');
                selector.length && selector.before(EthemeStudioJsData.Btn.Html);
            }

            function bindPreview() {
                var _this = this;
                var previewContents = window.elementor.$previewContents;
                var interval = setInterval(function() {
                    showStudioBtn(previewContents);
                    previewContents.find(".elementor-add-new-section").length > 0 && clearInterval(interval);
                }, 100);

                // for add sections on quick click on each section
                previewContents.on("click.onAddElement", ".elementor-editor-section-settings .elementor-editor-element-add", function (e) {
                    let localPreviewContent = jQuery(this).closest(".elementor-top-section");
                    let content = localPreviewContent.prev(".elementor-add-section");

                    _this.atIndex = localPreviewContent.index() - 1;
                    _this.localInsertSection = content;

                    content.find(".elementor-add-et-button").length || showStudioBtn(content);
                });

                previewContents.on("click.onAddTemplateButton", ".elementor-add-et-button", _modal.showModal.bind(_modal));
            }
            var __modal, ___modal, ____modal, _modal = this;

                this.atIndex = -1,
                this.localInsertSection = null,
                this.channels = {
                    //tabs: Backbone.Radio.channel("tabs"),
                    //templates: Backbone.Radio.channel("templates")
                },
                this.updateBlocksView = function() {
                    _modal.getModal().showBlocksView(___modal)
                }
                ,
                this.getFilter = function(filter) {
                    return _modal.channels.templates.request("filter:" + filter)
                },
                this.showModal = function() {
                    _modal.getModal().showModal();
                    _modal.showBlocksView();
                }
                ,
                this.closeModal = function() {
                    this.getModal().hideModal();
                }
                ,
                this.getModal = function() {
                    return __modal || (__modal = new etheme.Modal);
                }
                ,
                this.init = function() {
                    t.on("preview:loaded", bindPreview.bind(this));
                }
                ,
                this.showBlocksView = function() {
                    _modal.getModal().showDefaultHeader();
                    _modal.loadTemplates(function() {
                        _modal.getModal().showBlocksView(___modal);
                    });
                }
                ,
                this.showPreviewView = function(model) {
                    _modal.getModal().showPreviewView(model);
                }
                ,
                this.loadTemplates = function(model) {
                    _modal.requestLibraryData({
                        onBeforeUpdate: _modal.getModal().showLoadingView.bind(_modal.getModal()),
                        onUpdate: function() {
                            _modal.getModal().hideLoadingView();
                            model && model();
                        }
                    });
                },
            this.requestLibraryData = function(model) {
                if (___modal && !model.forceUpdate)
                    return void (model.onUpdate && model.onUpdate());
                model.onBeforeUpdate && model.onBeforeUpdate();
                var templates = {
                    data: {},
                    success: function(response) {
                        ___modal = new etheme.Collections.Template(response.templates);
                        model.onUpdate && model.onUpdate();
                    }
                };
                model.forceSync && (templates.data.sync = !0);
                elementorCommon.ajax.addRequest("get_et_library_data", templates);
            }
                ,
                this.requestTemplateData = function(id, folder, _function) {
                    var data = {
                        unique_id: id,
                        data: {
                            edit_mode: !0,
                            display: !0,
                            template_id: id,
                            folder: folder
                        }
                    };
                    _function && jQuery.extend(!0, data, _function);
                    elementorCommon.ajax.addRequest("get_et_template_data", data);
                }
                ,
                this.insertTemplate = function(data) {
                    var template = data.model,
                        _this = this;

                    _this.getModal().showLoadingView();
                    _this.requestTemplateData(template.get("template_id"), template.get("folder"), {
                        success: function(response) {
                            _this.getModal().hideLoadingView();
                            _this.getModal().hideModal();

                            var options = {};

                            -1 !== _this.atIndex && (options.at = _this.atIndex);

                            $e.run("document/elements/import", {
                                model: template,
                                data: response,
                                options: options
                            });
                            _this.atIndex = -1;

                        },
                        error: function(response) {
                            _this.showErrorDialog(response);
                        },
                        complete: function(response) {
                            _this.getModal().hideLoadingView();
                            if ( _this.localInsertSection !== null ) {
                                _this.localInsertSection.find(".elementor-add-section-close").click();
                            }
                        }
                    });
                }
                ,
                this.showErrorDialog = function(data) {
                    if ("object" == typeof data) {
                        var message = "";

                        _.each(data, function(e) {
                            message += "<div>" + e.message + ".</div>";
                        });

                        data = message;
                    } else
                        data ? data += "." : data = "<i>&#60;The error message is empty&#62;</i>";
                    _modal.getErrorDialog().setMessage('The following error(s) occurred while processing the request:<div id="elementor-template-library-error-info">' + data + "</div>").show();
                }
                ,
                this.getErrorDialog = function() {
                    return ____modal || (____modal = elementorCommon.dialogsManager.createWidget("alert", {
                        id: "elementor-template-library-error-dialog",
                        headerMessage: "An error occurred"
                    }));
                }
        }
        ,
        window.et_studio.library = new etheme.Manager,
        window.et_studio.library.init()
}(jQuery, window.elementor);
