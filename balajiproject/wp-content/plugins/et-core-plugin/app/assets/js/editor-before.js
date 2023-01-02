;(function($, window, document, undefined){
    $( window ).on( 'elementor:init', function() {
        if( typeof elementorPro == 'undefined' ) {
            // Post custom css
            elementor.hooks.addFilter( 'editor/style/styleText', function( css, view ){
                if ( null == view) return;
                let model = view.getEditModel(),
                ElementCSS = model.get( 'settings' ).get( 'xstore_element_custom_css' );

                if ( ElementCSS ) {
                    css += ElementCSS.replace( /selector/g, '.elementor-element.elementor-element-' + view.model.id );
                }

                return css;
            });
            // Page custom css
            elementor.hooks.addFilter( 'editor/style/styleText', function( css, view ){
                if ( null == view) return;
                let model = view.getEditModel(),
                PageCSS = model.get( 'settings' ).get( 'xstore_page_custom_css' );

                if ( PageCSS ) {
                    css += PageCSS;
                }

                return css;
            });

        }
    } );
})(jQuery, window, document);