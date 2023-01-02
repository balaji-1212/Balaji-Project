/**
 * Single page import scripts
 *
 * @version 1.0.0
 * @since   2.2.0
 */
jQuery(document).ready(function($){
    if( typeof vc == 'undefined' || ! $('#post-body-content').length ) return;

    load_template( 'primary' );

    $(document).on( 'click', '.et_page-import', function(e){
        load_template( 'popup' );
        $('body').addClass('et_panel-popup-on');
    });

    $(document).on('click', '.et_try-to-load', function(e){
        $(this).parents( '.et_popup-import-content' ).find( '.et-error:not(.rewrite-notice)' ).addClass( 'hidden' );
        load_content();
    });

    $(document).on('click', '#et_rewrite-page', function(e){
        if ($(this).attr('checked')){
           $( '.rewrite-notice' ).removeClass( 'hidden' );
           $( '.rewrite-info' ).addClass( 'hidden' );
        } else {
            $( '.rewrite-notice' ).addClass( 'hidden' );
            $( '.rewrite-info' ).removeClass( 'hidden' );
        }
    });


    /**
     * Load and show template for popup
     *
     * @version 1.0.0
     * @since   2.2.0
     * @param   {string} type - template to show
     */
    function load_template(type){
        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: ajaxurl, 
            data: {
                'action'   : 'et_ajax_single_product_import_template',
                'template' : 'popup',
                'helper': 'plugins'
            },
            success: function(response){
                var html = '';
                if ( type == 'primary' ) {
                    html = $( response['template'] ).find('.et_page-import' );
                    $( '.composer-switch' ).append( html ).append('<div class="et_panel-popup et_popup-import-single-page size-lg"></div>');
                } else {
                    html = $( response['template'] ).find( '.et_panel-popup' ).html();
                    $( '.et_panel-popup' ).html( html );
                }
            },
            error: function(data) {
                console.error( 'Error while load template' );
            }
        });
    }

    /**
     * Load remote page content
     *
     * @version 1.0.0
     * @since 2.2.0
     */
    function load_content(){

        var rewrite       = $( '#et_rewrite-page' ).prop( 'checked' ),
            popup_content = $('.et_popup-import-content'),
            url           = $( '#et_single-page-import' ).val(),
            css_import    = $( '#et_custom-css' ).prop( 'checked' );

        popup_content.addClass('ajax-processing');

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: ajaxurl, 
            data: {
                'action'  : 'et_ajax_load_page_content',
                'url'     : url,
            },
            success: function(response){
                var response = JSON.parse(response);

                if ( response['error'] ) {
                    $( '.' + response['error'] ).removeClass( 'hidden' );
                    popup_content.removeClass('ajax-processing');
                } else {
                    var content = $(response['data']).find('.import-content-holder');



                    if ( ! content || ! response['data'] || content.hasClass('error') ) {
                        $( '.' + content.attr('data-error') ).removeClass( 'hidden' );
                        popup_content.removeClass('ajax-processing');
                    } else if( ! content.html().length ) {
                        $( '.url-error' ).removeClass( 'hidden' );
                        popup_content.removeClass('ajax-processing');
                    } else {

                        if (content.attr('plugins') && $('.et_step-plugins').length ) {

                            var plugins = content.attr('plugins').split(' '),
                                show    = false;

                            $.each(plugins, function(i,t) {
                                if ( t ) {
                                    var plugin_li = $( '.et_popup-import-plugin.' + t );
                                    if ( plugin_li.length ) {
                                        $( '.et_popup-import-plugin.' + t ).css('display', 'flex');
                                        show = true;
                                    }
                                }
                            });

                            if ( show ) {
                                $( '.et_popup-step.et_step-import' ).addClass('hidden');
                                $('.et_step-plugins').removeClass('hidden');
                                popup_content.removeClass('ajax-processing');
                            } else {
                                render_template(content.html(), rewrite);
                                if ( content.attr('plugins') ) {
                                    install_revsliders(url, content.attr('revslider'));
                                } else {
                                    popup_content.removeClass('ajax-processing');
                                }
                            }

                            
                        } else {
                            render_template(content.html(), rewrite);
                            if ( content.attr('plugins') && content.attr('plugins').includes('revslider') ) {
                                install_revsliders(url, content.attr('revslider'));
                            } else {
                                popup_content.removeClass('ajax-processing');
                            }
                        }

                        $('.et_install-page-content').on('click', function(){
                            popup_content.addClass('ajax-processing');
                            $(document).find('.et_step-plugins').addClass('ajax-processing');
                            setTimeout(function(){
                                render_template( content.html(), rewrite);
                                if ( content.attr('plugins').includes('revslider') ) {
                                    install_revsliders(url, content.attr('revslider'));
                                }
                            }, 50);
                        });

                        if ( css_import ) {
                            install_custom_css(content.attr('custom_css'), rewrite);
                        }
                    }
                }
            },
            error: function(data) {
                console.error('Error while content importing');
            },
            complete: function(){
            }
        });
    }


    /**
     * install sliders
     *
     * @version 1.0.0
     * @since 2.2.0
     */
    function install_revsliders(url, i){

        var popup_content = $('.et_popup-import-content');

        popup_content.addClass('ajax-processing');

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: ajaxurl, 
            data: {
                'action'  : 'et_ajax_import_page_sliders',
                'url'     : url,
                'i'       : i,
            },
            success: function(response){
                console.log(response);
            },
            error: function(data) {
                console.error('Error while revsliders importing');
            },
            complete: function(){
                popup_content.removeClass('ajax-processing');
            }
        });
    }

    /**
     * Insert remote content in to the page 
     *
     * @version 1.0.0
     * @since 2.2.0
     */
    function render_template(data, rewrite){
        if ( rewrite ) {
            $( '#wpbakery_content .vc_control.column_delete.vc_column-delete' ).trigger( 'click' );
        }

        var models;
        _.each(vc.filters.templates, function(callback) {
            html = callback(data)
        }), models = vc.storage.parseContent({}, data), _.each(models, function(model) {
            vc.shortcodes.create(model)
        }), vc.closeActivePanel();

        $( '.et_popup-step' ).addClass('hidden');
        $( '.et_popup-step.et_step-final' ).removeClass('hidden');
        $( '.et_popup-import-content' ).removeClass('ajax-processing');
    }


    /**
     * Insert page custom css
     *
     * @version 1.0.0
     * @since 2.2.0
     */
    function install_custom_css(css, rewrite){
        if ( css ) {
            css = atob(css);
            var page_custom_css_holder = $('input[name="vc_post_custom_css"]');
            if ( ! rewrite ) {
                var page_custom_css = $('input[name="vc_post_custom_css"]').attr('value');
                css = page_custom_css + css;
            }
            page_custom_css_holder.attr('value', css);
        }
    }
});