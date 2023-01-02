/**
 * Customizer search panel scripts
 *
 * all search scripts here
 *
 * @version 0.1
 * @since 0.1
 */

var etCustomizerSearch;

;(function($) {
    "use strict"

    /*****************************************************************
     * Customizer Search functions
     *
     * Functions that bring a life for Customizer Search
     * 
     * {function} init                 - primary initing
     * {function} et_add_search        - add search form
     * {function} et_search_procces    - search procces
     * {function} et_render_results    - render search results
     * {function} et_bind_results      - binds customize events for search results
     * {function} et_show_results      - show search results
     * {function} et_clear_results     - reset search
     * {function} et_clear_search      - reset search by .et_clear-search click
     * {function} et_get_all_controls  - get all customizer controls data
     * {function} et_get_controls      - get controls that are matched search request
     *
     * @version 0.1
     * @since 0.1
     ******************************************************************/
    etCustomizerSearch = {
    	/**
         * Primary initing
         *
         * @version 0.1
         * @since 0.1
         */
        init: function() {
            this.et_add_search();
            this.et_search_procces();
            this.et_clear_search();
        },// End of init

        /**
         * Add search form
         *
         * Move search form to their place
         *
         * @version 0.1
         * @since 0.1
         */
        et_add_search: function(){
        	var search = $( '#et_search-form' ).html();
            $( '#et_search-form' ).remove();

            $( '#customize-header-actions' ).after( search );
        	// $( '#customize-info' ).append( search );
        },// End of et_add_search

        /**
         * Search procces
         *
         * @version 0.1
         * @since 0.1
         */
        et_search_procces: function(){
            var timer = null;
            $(document).on( 'keyup', '#et_customizer-search', function(e){
                e.preventDefault();
                var search_wrapper = $(this).parent();
                var search_value = $(this).val();
                search_wrapper.addClass('loading');

                clearTimeout(timer); 
                timer = setTimeout(function(){

                    if( ! search_value.length ) {
                        etCustomizerSearch.et_clear_results();
                        $( '#customize-theme-controls' ).removeClass( 'et_hide-controls' );
                        $('.wp-full-overlay-sidebar-content').removeClass('search-is-opened');
                        search_wrapper.removeClass('loading');
                        return;
                    }

                    // Avoid ajax search
                    if ( ! xstoreCustomizerSearch.etcCore ) {
                        let results = etCustomizerSearch.et_render_results( search_value, [] );
                        etCustomizerSearch.et_handle_results($(this),results);
                        search_wrapper.removeClass('loading');
                        return;
                    }

                    // Do ajax search
                    var result = etCustomizerSearch.searchString(search_value);

                    result.done(function(result){
                        let results = etCustomizerSearch.et_render_results( search_value, result );
                        etCustomizerSearch.et_handle_results($(this),results);
                        search_wrapper.removeClass('loading');
                    });

                }, 1000);
            });
        },// End of et_search_procces

        /**
         * Render search results
         *
         * @version 0.1
         * @since 0.1
         *
         * @param  {string} search - value of #et_customizer-search input
         * @return {string} - html to push in to #et_customizer-search-results
         */
        et_render_results: function(search, ajaxControls){

            var results = $( '#et_customizer-search-results' );

            if ( search.length < 3 ) {
                return '<li style="text-align: center; padding: 0 7px;">' + results.attr( 'data-length' ) + '</li>';
            }

            var normalControls = etCustomizerSearch.et_get_controls(search);
            var controls = $.extend([], ajaxControls, normalControls);

            if ( controls.length < 1 ) {
                return '<li style="text-align: center; padding: 0 7px;">' + results.attr( 'data-empty' ) + '</li>';
            }

            var html = controls.map(function(index) {
   
            if ( index.label === '' ){
                return;
            } 

            if ( index.id && index.id.includes( '_et-mobile' ) ) {
                return;
            }

		    var path = index.panelName;

            if ( index.sectionName != '' ) {
                path = path + ' ▸ ' + index.sectionName;
            }
            return'\
                <li \
                    id="accordion-section-' + index.section + '"\
                    class="accordion-section control-section control-section-default et_search-result"\
                    aria-owns="sub-accordion-section-' + index.section + '"\
                    data-section="' + index.section + '">\
                    <h3 class="accordion-section-title" tabindex="0">'
                        + index.label +
                        '<span class="screen-reader-text">Press return or enter to open this section</span>\
                    </h3>\
                    <span class="search-setting-path">' + path + '</i></span>\
                </li>\
            ';
			});

        	return html;
        },// End of et_render_results

        /**
         * Binds customize events for search results
         *
         * @version 0.1
         * @since 0.1
         *
         */
        et_bind_results: function(){
            $.each( $( '#et_customizer-search-results li.et_search-result' ), function(){
                var id = $(this).attr( 'data-section' );
                $(this).bind( 'click', function(){
                    etCustomizerSearch.et_clear_results();

                    $( '#customize-theme-controls' ).removeClass( 'et_hide-controls' );
                    $('.wp-full-overlay-sidebar-content').removeClass('search-is-opened');

                    wp.customize.section( id ).focus();
                });
            });
        },// End of et_bind_results

        /**
         * Handle search result to show
         *
         * @version 3.3.8
         * @since 3.3.8
         *
         * @param {string} html - results
         */
        et_handle_results: function($this,results){
            $( '#customize-theme-controls' ).addClass( 'et_hide-controls' );
            $('.wp-full-overlay-sidebar-content').addClass('search-is-opened');
            $this.parent().removeClass('empty').addClass('with-results');

            etCustomizerSearch.et_show_results( results );
            etCustomizerSearch.et_bind_results();
        },// End of et_handle_results

        /**
         * Show search results
         *
         * @version 0.1
         * @since 0.1
         *
         * @param {string} html - html elmenets to show
         */
        et_show_results: function(html){
        	$( '#et_customizer-search-results' ).html( html );
        },// End of et_show_results

        /**
         * Reset search
         * Clear search form input,
         * Clear search results wrapper
         *
         * @version 0.1
         * @since 0.1
         */
        et_clear_results: function(){
            $( '#et_customizer-search' ).val('');
        	$( '#et_customizer-search-results' ).html( '' );
            $('.et_search-wrapper').addClass('empty').removeClass('with-results');
        },// End of et_clear_results

        /**
         * Reset search
         * by .et_clear-search click
         * Clear search form input,
         * Clear search results wrapper
         *
         * @version 0.1
         * @since 0.1
         */
        et_clear_search: function(){
            $( '.et_clear-search' ).on( 'click', function(){
                etCustomizerSearch.et_clear_results();
                $( '#customize-theme-controls' ).removeClass( 'et_hide-controls' );
                $('.wp-full-overlay-sidebar-content').removeClass('search-is-opened');
            });
            
        },// End of et_clear_search

        /**
         * Get all customizer controls data
         *
         * @version 0.1
         * @since 0.1
         *
         * @return {array} - all customizer controls
         */
        et_get_all_controls: function(){
            var controls = $.map( _wpCustomizeSettings.controls, function(control, index) {
                $.map( _wpCustomizeSettings.sections, function(section, index) {
                    if ( control.section == section.id ) {
                        $.map(_wpCustomizeSettings.panels, function(panel, index) {
                            if ( section.panel == '' ) {
                                control.panelName = section.title;
                            }

                            if ( section.panel == panel.id ) {
                                control.sectionName = section.title;
                                control.panel       = section.panel;
                                control.panelName   = panel.title;
                            }
                        });
                    }
                });
                return [control];
            });
            return controls;
        },// End of et_clear_results

        searchString: function (query) {
            query = query.trim();

            return wp.ajax.send({
                url: xstoreCustomizerSearch.ajaxUrl,
                data: {
                    customizernonce: xstoreCustomizerSearch.nonce,
                    search: query
                }
            });
        },

        /**
         * Get controls that are matched search request
         *
         * @version 0.1
         * @since 0.1
         *
         * @param  {string} search - value of #et_customizer-search input
         * @return {array} - controls that are matched search request
         */
        et_get_controls: function(search) {

           var controls = etCustomizerSearch.et_get_all_controls();

            return controls.filter(control => {
            
                if (control.panelName == null) control.panelName = '';
                if (control.sectionName == null) control.sectionName = '';

                const regex = new RegExp(search, 'gi');

                return control.label.toString().match(regex) || control.panelName.toString().match(regex) || control.sectionName.toString().match(regex);
            });
        }// End of et_get_controls
    };// End of etCustomizerSearch

   	$(document).ready(function(){
		etCustomizerSearch.init();
    });
})(jQuery);