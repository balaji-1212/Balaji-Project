wp.customize.controlConstructor['kirki-box-shadow'] = wp.customize.Control.extend({

	/**
	 * Triggered when the control is ready.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	ready: function() {
		var control = this;

		control.setVisually();
		control.initColorpicker();
		control.adjustbackGround();

		// Init draggable.
		jQuery( control.container.find( '.preview-object' ) ).draggable({
			containment: 'parent',

			// Create.
			create: function() {
				var width   = jQuery( control.container.find( '.preview-wrapper' ) ).width(),
					height  = jQuery( control.container.find( '.preview-wrapper' ) ).height(),
					left    = width / 2 - 25 - control.getFromStringValue( 'horizontalLength' ),
					top     = height / 2 - 25 - control.getFromStringValue( 'verticalLength' );

				control.updateCoordinatesBox();
				jQuery( control.container.find( '.preview-object' ) ).css( 'top', top ).css( 'left', left );
				control.updatePreviewShadow();
			},

			// Handle dragging.
			drag: function( event, ui ) {
				control.updatePartValue( 'horizontalLength', control.calculateHorizontalBoxShadow( ui.position.left ) );
				control.updatePartValue( 'verticalLength', control.calculateVerticalBoxShadow( ui.position.top ) );
			}
		});

		// Listen for changes to the blur-radius.
		jQuery( control.container.find( 'input[data-context="blur-radius"]' ) ).on( 'change', function() {
			control.updatePartValue( 'blurRadius', jQuery( this ).val() );
		});

		// Listen for changes to the spread-radius.
		jQuery( control.container.find( 'input[data-context="spread-radius"]' ) ).on( 'change', function() {
			control.updatePartValue( 'spreadRadius', jQuery( this ).val() );
		});

		// Listen for color changes.
		jQuery( control.container.find( '.kirki-color-control' ) ).on( 'change', function() {
			control.updatePartValue( 'color', jQuery( this ).val() );
		});

		// Listen for changes to inset.
		jQuery( control.container.find( 'input[data-context="inset"]' ) ).on( 'change click', function() {
			control.updatePartValue( 'inset', jQuery( this ).is( ':checked' ) ? 'inset' : '' );
		});
	},

	/**
	 * Gets a part from the string value depending on the context.
	 *
	 * @since 1.0.1
	 * @param {string} part - Can be one of the following:
	 *                            horizontalLength
	 *                            verticalLength
	 *                            blurRadius
	 *                            spreadRadius
	 *                            color
	 *                            inset
	 * @param {string} value - The string value.
	 * @returns {string}
	 */
	getFromStringValue: function( part, value ) {
		var isInset;
		value   = value || jQuery( this.container.find( '.hidden-value' ) ).attr( 'value' );
		value   = value.split( ' ' );
		isInset = ( 'inset' === value[0] );

		if ( isInset ) {
			value.splice( 0, 1 );
		}

		switch ( part ) {
			case 'inset':
				return isInset ? 'inset' : '';
			case 'horizontalLength':
				return parseInt( value[0], 10 );
			case 'verticalLength':
				return parseInt( value[1], 10 );
			case 'blurRadius':
				return parseInt( value[2], 10 );
			case 'spreadRadius':
				return parseInt( value[3], 10 );
			case 'color':
				return value[4];
		}
	},

	/**
	 * Visually updates the UI.
	 *
	 * @since 1.0
	 * @returns {Object} this
	 */
	setVisually: function() {
		jQuery( this.container.find( '.blur-radius' ) ).val( this.getFromStringValue( 'blurRadius' ) );
		jQuery( this.container.find( '.spread-radius' ) ).val( this.getFromStringValue( 'spreadRadius' ) );
		jQuery( this.container.find( '.kirki-color-control' ) ).val( this.getFromStringValue( 'color' ) );
		jQuery( this.container.find( '.inset' ) ).attr( 'checked', this.getFromStringValue( 'inset' ) ? true : false );
	},

	/**
	 * Updates the box-shadow in the preview.
	 *
	 * @since 1.0
	 * @param {string} value - The value.
	 * @returns {Object} this
	 */
	updatePreviewShadow: function( value ) {
		var el;

		// Get the value if not defined.
		value = value || jQuery( this.container.find( '.hidden-value' ) ).attr( 'value' );

		// Change the element based on whether the shadow is inset or outset.
		el = ( -1 < value.indexOf( 'inset' ) ) ? '.preview-wrapper' : '.preview-object';

		// If we did not define a value, get it from the DOM.
		value  = value || jQuery( this.container.find( '.hidden-value' ) ).attr( 'value' );

		// Update shadows.
		jQuery( this.container.find( '.preview-wrapper, .preview-object' ) ).css( 'box-shadow', '' );
		jQuery( this.container.find( el ) ).css( 'box-shadow', value );
	},

	/**
	 * Calculates the horizontal length of our box-shadow
	 * by comparing the left position to the center of the container.
	 *
	 * @since 1.0
	 * @param {int} left - The position from left.
	 * @returns {int} - horizontal offset.
	 */
	calculateHorizontalBoxShadow: function( left ) {
		var width = jQuery( this.container.find( '.preview-wrapper' ) ).width();
		return 0 - parseInt( left + 25 - width / 2, 10 );
	},

	/**
	 * Calculates the vertical length of our box-shadow
	 * by comparing the top position to the center of the container.
	 *
	 * @param {int} top - The position from top.
	 * @returns {int} - vertical offset.
	 */
	calculateVerticalBoxShadow: function( top ) {
		var height = jQuery( this.container.find( '.preview-wrapper' ) ).height();
		return 0 - parseInt( top + 25 - height / 2, 10 );
	},

		/**
	 * Init the colorpicker.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	initColorpicker: function() {
		var control = this,
			picker = jQuery( control.container.find( '.kirki-color-control' ) ),
			clear;

		// Tweaks to make the "clear" buttons work.
		setTimeout( function() {
			clear = jQuery( control.container.find( '.kirki-input-container .wp-picker-clear' ) );
			if ( clear.length ) {
				clear.click( function() {
					control.updatePartValue( 'color', '' );
					control.adjustbackGround();
				});
			}
		}, 200 );

		// Saves our settings to the WP API
		picker.wpColorPicker({
			change: function() {

				// Small hack: the picker needs a small delay
				setTimeout( function() {
					control.updatePartValue( 'color', picker.val() );
					control.adjustbackGround();
				}, 20 );
			}
		});
	},

	/**
	 * Updates the coordinates box.
	 *
	 * @since 1.0
	 * @returns {Object} this
	 */
	updateCoordinatesBox: function() {
		jQuery( this.container.find( '.coordinates' ) ).html( this.getFromStringValue( 'horizontalLength' ) + ', ' + this.getFromStringValue( 'verticalLength' ) );
	},

	/**
	 * Adjust the background color so that the shadow is visible.
	 *
	 * @since 1.0
	 */
	adjustbackGround: function() {
		if ( 0.6 > this.getRelativeLuminance( jQuery.Color( this.getFromStringValue( 'color' ) ).alpha( 1 ) ) ) {
			jQuery( this.container.find( '.preview-wrapper' ) ).css( 'background-color', '#fff' );
			jQuery( this.container.find( '.preview-object' ) ).css( 'background-color', '#f2f2f2' );
			jQuery( this.container.find( '.preview-object' ) ).css( 'color', '#333' );
		} else {
			jQuery( this.container.find( '.preview-wrapper' ) ).css( 'background-color', '#000' );
			jQuery( this.container.find( '.preview-object' ) ).css( 'background-color', '#333' );
			jQuery( this.container.find( '.preview-object' ) ).css( 'color', '#f2f2f2' );
		}
	},

	/**
	 * Gets the relative luminance from RGB.
	 * Formula: http://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef
	 *
	 * @since 1.0
	 * @param {Object} $color - a jQuery.Color object.
	 * @returns {float}
	 */
	getRelativeLuminance: function( $color ) {
		return 0.2126 * this.qGetLumPart( $color.red() ) + 0.7152 * this.qGetLumPart( $color.green() ) + 0.0722 * this.qGetLumPart( $color.blue() );
	},

	/**
	 * Get luminocity for a part.
	 *
	 * @since 1.0
	 * @param {int|float} val - The value.
	 * @returns {float}
	 */
	qGetLumPart: function( val ) {
		val = val / 255;
		if ( 0.03928 >= val ) {
			return val / 12.92;
		}
		return Math.pow( ( ( val + 0.055 ) / 1.055 ), 2.4 );
	},

	/**
	 * Updates the value.
	 *
	 * @since 1.0.1
	 * @param {string} param - Which parameter has changed in the value.
	 * @param {string} paramVal - The parameter's value.
	 * @returns {void}
	 */
	updatePartValue: function( param, paramVal ) {
		var value = jQuery( this.container.find( '.hidden-value' ) ).attr( 'value' ),
			valueParts = {
				horizontalLength: this.getFromStringValue( 'horizontalLength', value ),
				verticalLength: this.getFromStringValue( 'verticalLength', value ),
				blurRadius: this.getFromStringValue( 'blurRadius', value ),
				spreadRadius: this.getFromStringValue( 'spreadRadius', value ),
				color: this.getFromStringValue( 'color', value ),
				inset: this.getFromStringValue( 'inset', value )
			};

		// Update the value we want to set.
		valueParts[ param ] = paramVal;

		// Build the combined value.
		value = valueParts.horizontalLength + 'px ' + valueParts.verticalLength + 'px ' + valueParts.blurRadius + 'px ' + valueParts.spreadRadius + 'px ' + valueParts.color;

		//Add inset if used.
		if ( valueParts.inset ) {
			value = 'inset ' + value;
		}

		// Update the value.
		jQuery( this.container.find( '.hidden-value' ) ).attr( 'value', value ).trigger( 'change' );

		// Update previews.
		this.updatePreviewShadow();
		this.updateCoordinatesBox();
	}
});
