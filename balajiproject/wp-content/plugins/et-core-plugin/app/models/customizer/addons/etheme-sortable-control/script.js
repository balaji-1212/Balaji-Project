/**
 * Triggered control to show/hide options.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
jQuery(document).on( 'click', '.etheme-sortable .et_opener', function(e) {
    var et_options = jQuery(this).closest('li').find('.et_options');
    if (et_options.hasClass('hidden')) {
        jQuery(this).removeClass( 'dashicons-arrow-down-alt2' ).addClass( 'dashicons-arrow-up-alt2' );
        et_options.removeClass('hidden');
    } else {
        jQuery(this).addClass( 'dashicons-arrow-down-alt2' ).removeClass( 'dashicons-arrow-up-alt2' );
        et_options.addClass('hidden');
    }
});

/**
 * Setup control extend function.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
wp.customize.controlConstructor['etheme-sortable'] = wp.customize.Control.extend({

    /**
     * Triggered when the control is ready.
     *
     * @since   1.0.0
     * @version 1.0.0
     */
	ready: function() {
		var control  = this;
		var choices = control.container.find('.etheme-sortable--sortable');

        // Init sortable
		jQuery( choices ).sortable({
            // update choices position
            update: function(event, ui){
                var data  = control.setting._value,
                    input = control.container.find( '.hidden-value' );
                var new_data = {};

                choices.find( 'li.kirki-sortable-item' ).each( function() {
                    var id = jQuery(this).attr('data-id');
                    new_data[id] = data[id];
                });

               jQuery( input ).attr( 'value', JSON.stringify( new_data ) ).trigger( 'change' );
                control.setting.set(new_data);
            }
        });

        // Init triggers for option types
		choices.find( 'li' ).each( function() {

            // visibility trigger
			jQuery( this ).find( 'i.visibility' ).click( function() {
				if ( jQuery( this ).closest('.kirki-sortable-item').hasClass('visible') ) {
					jQuery( this ).closest('.kirki-sortable-item').removeClass('visible').addClass('invisible');
				} else {
					jQuery( this ).closest('.kirki-sortable-item').removeClass('invisible').addClass('visible');
				}
				var id = jQuery( this ).closest('li').attr('data-id');
				control.et_change_visibility(id,control);
			});

            // input field trigger
			jQuery( this ).find('.et_option-type-input input').on('keyup', function(e){
                control.et_set_data({
                    id       : jQuery( this ).attr( 'id' ),
                    parentid : jQuery( this ).parents('li.kirki-sortable-item').attr('data-id'),
                    value    : jQuery( this ).attr( 'value' ),
                    control  : control,
                    live     : true
                }); 
			});

            // select field trigger
            jQuery( this ).find('.et_option-type-select select').on('change', function(e){
                control.et_set_data({
                    id       : jQuery( this ).attr( 'id' ),
                    parentid : jQuery( this ).parents('li.kirki-sortable-item').attr('data-id'),
                    value    : jQuery( this ).attr( 'value' ),
                    control  : control,
                    live     : true
                });
            });

            // toggle field trigger
            jQuery( this ).find('.et_option-type-toggle input').on('change', function(e){
                control.et_set_data({
                    id       : jQuery( this ).attr( 'id' ),
                    parentid : jQuery( this ).parents('li.kirki-sortable-item').attr('data-id'),
                    value    : ( jQuery( this ).attr('checked') ) ? true : false,
                    control  : control,
                    live     : true
                });
            });

            // range field triggers
            jQuery( this ).find('.et_option-type-range input[type="text"]').on('keyup', function(e){
                jQuery( this ).parents('.et_option-type-range').find('input[type="range"]').attr( 'value', jQuery( this ).attr( 'value' ));
                control.et_set_data({
                    id       : jQuery( this ).attr( 'id' ),
                    parentid : jQuery( this ).parents('li.kirki-sortable-item').attr('data-id'),
                    value    : jQuery( this ).attr( 'value' ),
                    control  : control,
                    live     : true
                });
            });


            jQuery( this ).find('.et_option-type-range input[type="range"]').on('mousemove change', function(e){
                jQuery( this ).parents('.et_option-type-range').find('input[type="text"]').attr( 'value', jQuery( this ).attr( 'value' ));
            });

            jQuery( this ).find('.et_option-type-range input[type="range"]').on('mouseup', function(e){
                jQuery( this ).parents('.et_option-type-range').find('input[type="text"]').attr( 'value', jQuery( this ).attr( 'value' ));
                jQuery( this ).parents('.et_option-type-range').find('input[type="text"]').trigger('keyup');
            });

            jQuery( this ).find( '.et_option-type-range .slider-reset' ).on('click', function(){
                let defaultv = jQuery( this ).parents( '.et_option-type-range' ).find( 'input[type="range"]' ).attr('data-default');
                jQuery( this ).parents('.et_option-type-range').find('input[type="text"]').attr( 'value', defaultv );
                jQuery( this ).parents('.et_option-type-range').find('input[type="text"]').attr( 'value', defaultv );
                jQuery( this ).parents('.et_option-type-range').find('input[type="text"]').trigger('keyup');
            });
            // End of range field triggers

            // box-moda field trigger
            jQuery( this ).find('.et_option-type-box-modal input').on('keyup', function(e){
                var id = jQuery( this ).attr( 'data-id' );
                var fieldID = jQuery( this ).parents('li.et_option-type-box-modal').attr('id');
                var parentid = jQuery( this ).parents('li.kirki-sortable-item').attr('data-id');
                var value = jQuery( this ).attr( 'value' );
                control.et_change_box_modal(id,parentid,fieldID,value,control); 
            }); 

            // wpColorPicker field trigger
            jQuery( this ).find('.et_color-field-holder').wpColorPicker({
                change: function() {
                    var $this = jQuery(this);
                    if ( jQuery( '.wp-picker-container' ).hasClass('wp-picker-active')  ) {
                        setTimeout( function() {
                            control.et_set_data({
                                id       :  $this.attr( 'id' ),
                                parentid : $this.parents('li.kirki-sortable-item').attr('data-id'),
                                value    : $this.attr('value'),
                                control  : control,
                                live     : true
                            });
                        }, 20 );
                    }
                    
                }
            });
		} );
	},

    /**
     * Set data to settings object
     *
     * @since   1.0.0
     * @version 1.0.0
     * 
     * @param {Object} args - {
     *    {string}  id       - id of option field
     *    {string}  parentid - id of option parent li tag
     *    {string}  value    - value for save
     *    {object}  control  - customizer control object
     *    {boolean} live     - refresh or not live preview
     * }
     */
    et_set_data : function( args ){
        var data  = args['control'].setting._value,
            input = args['control'].container.find( '.hidden-value' );

        data[args['parentid']]['fields'][args['id']]['value'] = args['value'];

        if ( args['live'] ) {
            jQuery( input ).attr( 'value', JSON.stringify( data ) ).trigger( 'change' );
        } 
        args['control'].setting.set(data);
    },

     /**
     * Set data to settings object
     *
     * @since   1.0.0
     * @version 1.0.0
     * 
     * @param {string} id       - id of option field
     * @param {string} parentid - id of option parent li tag
     * @param {string} value    - value for save
     * @param {object} control  - customizer control object
     * @param {string} fieldID  - id of box_modal field
     */
    et_change_box_modal : function(id,parentid,fieldID,value,control){
        var data  = control.setting._value,
            input = control.container.find( '.hidden-value' );
        data[parentid]['fields'][fieldID]['fields'][id] = value;

        jQuery( input ).attr( 'value', JSON.stringify( data ) ).trigger( 'change' );
        control.setting.set(data);
    },

     /**
     * Set visibility to settings object
     *
     * @since   1.0.0
     * @version 1.0.0
     * 
     * @param {string} id      - id of option field
     * @param {object} control - customizer control object
     */
	et_change_visibility : function( id, control ){
		var data  = control.setting._value,
            input = control.container.find( '.hidden-value' );

			if ( data[id]['visibility'] ) {
				data[id]['visibility'] = false;
			} else {
				data[id]['visibility'] = true;
			}
		
		jQuery( input ).attr( 'value', JSON.stringify( data ) ).trigger( 'change' );
        control.setting.set(data);				
	},
});