wp.customize.controlConstructor['kirki-box-model'] = wp.customize.Control.extend({

	/**
	 * Triggered when the control is ready.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	ready: function() {
		var control = this;

		control.container.find( 'input.box-model-input' ).on( 'change keyup paste', function() {
			control.saveValue( jQuery( this ).data( 'target-option' ), jQuery( this ).val() );
		});
	},

	/**
	 * Saves the value.
	 */
	saveValue: function( property, value ) {
		var input       = this.container.find( '.hidden-value' ),
			valueObject = ( _.isObject( this.setting._value ) ) ? this.setting._value : {};

		valueObject[ property ] = value;
		jQuery( input ).attr( 'value', JSON.stringify( valueObject ) ).trigger( 'change' );
		this.setting.set( valueObject );
	}
});
