/* global confirm custom fonts */

jQuery(function($){

    function readURL( file ) {
        if ( file ) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var out = '<div class=\"et_pre-file\">';
                out += '<p class=\"et_pre-name\">' + file.name + '</p>';
                out += '</div>';
                $('.et-file-zone').after(out);
            }
            reader.readAsDataURL(file);
        }
    }

    $('#et-fonts').change(function(){
        $.each( this.files, function( e, t ) {
           readURL(t);
        });
    });

    var form = '\
            <form id="et-fonts-uploader" enctype="multipart/form-data" method="post">\
                <div class="et-file-zone">\
                    <label for="et-fonts">Choose fonts for upload</label>\
                    <input name="et-fonts" type="file" id="et-fonts" accept=".eot, .woff2, .woff, .ttf, .otf">\
                </div>\
                <p><input id="et-upload" class="et-button et-button-green" name="et-upload" type="submit" value="upload"></p>\
            </form>\
        ';

    $(document).on( 'click', '.add-form', function(e) {
        e.preventDefault();
        if ( $( '#et-fonts-uploader' ).length > 0 ) return;
        $(this).after(form);
        $(this).remove();
    });

    $(document).on( 'click', '.et_expost-fonts', function(e) {
        e.preventDefault();
            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: ajaxurl, 
                data: {
                    'action' : 'et_ajax_fonts_export',
                },
                success: function(response){
                    var dataStr      = "data:text/json;charset=utf-8," + encodeURIComponent( JSON.stringify( response ) );
                    var dlAnchorElem = document.getElementById('et_download-export-file');
                    dlAnchorElem.setAttribute("href",     dataStr     );
                    dlAnchorElem.setAttribute("download", "fonts.json");
                    dlAnchorElem.click();
                },
                error: function(data) {
                    alert('Error while exporting');
                }
            });
    });


    


    $(document).on( 'click', '.et_font-remover', function(e) {
        e.preventDefault();
        if ( ! confirm( 'Are you sure?' ) ) return;

        var this_btn = $(this);
        var switch_args = [];
        var loader = '\
            <div class="et-loader">\
                <svg viewBox="0 0 187.3 93.7" preserveAspectRatio="xMidYMid meet">\
                    <path stroke="#ededed" class="outline" fill="none" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1 c-8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z"></path>\
                    <path class="outline-bg" opacity="0.05" fill="none" stroke="#ededed" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="M93.9,46.4c9.3,9.5,13.8,17.9,23.5,17.9s17.5-7.8,17.5-17.5s-7.8-17.6-17.5-17.5c-9.7,0.1-13.3,7.2-22.1,17.1c-8.9,8.8-15.7,17.9-25.4,17.9s-17.5-7.8-17.5-17.5s7.8-17.5,17.5-17.5S86.2,38.6,93.9,46.4z"></path>\
                </svg>\
            </div>\
        ';

        this_btn.parents( 'li' ).prepend( loader );
        this_btn.parents( 'li' ).addClass( 'et_font-removing' );

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
              url: ajaxurl, 
              data: {
                'action' : 'et_ajax_fonts_remove',
                'id' : this_btn.data( 'id' )
              },
              success: function(response){

                if ( response['status'] == 'success' ){
                    switch_args['add'] = 'et_font-removed';
                    switch_args['remove'] = 'et_font-removing';

                    class_switch( this_btn.parents( 'li' ), switch_args );
                    if ( this_btn.parents('li').parent().find('> li').length == 1) {
                        this_btn.parents('.et_fonts-info').remove();
                    }
                    this_btn.parents( 'li' ).remove();
                } else {
                    switch_args['add'] = 'et_font-removed-fail';
                    switch_args['remove'] = 'et_font-removing';

                    class_switch( this_btn.parents( 'li' ), switch_args );

                    $.each( response['messages'], function( i, t ) {
                        this_btn.after( '<span class="et_font-error">' + t + '</span>' );
                    });
                }
             },
              error: function(data) {
                alert('Error while deleting');
              }
          });
    });

    // ! Switch element classes
    function class_switch( element, args ){
        element.addClass( args['add'] ).removeClass( args['remove'] );
    }

});