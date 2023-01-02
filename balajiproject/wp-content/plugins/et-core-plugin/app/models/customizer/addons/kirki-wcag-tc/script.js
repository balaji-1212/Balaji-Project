/* global wcagColors */
wp.customize.controlConstructor['kirki-wcag-tc'] = wp.customize.Control.extend({

	/**
	 * The current mode of operation.
	 *
	 * @since 1.0
	 */
	a11ySelectorMode: false,

	/**
	 * An array of accessible colors.
	 *
	 * @since 1.0
	 */
	colors: [],

	/**
	 * The maximum hue difference.
	 *
	 * @since 1.0
	 */
	maxHueDiff: 60,

	/**
	 * The steps for hue. Integer.
	 * Smaller values make for a more detailed search.
	 *
	 * @since 1.0
	 */
	stepHue: 15,

	/**
	 * The maximum Saturation for accessible colors.
	 *
	 * @since 1.0
	 */
	maxSaturation: 0.5,

	/**
	 * The steps for saturation. Float value, 0 to 1.
	 * Smaller values make for a more detailed search.
	 *
	 * @since 1.0
	 */
	stepSaturation: 0.1,

	/**
	 * The steps for lightness. Float value, 0 to 1.
	 * Smaller values make for a more detailed search.
	 *
	 * @since 1.0
	 */
	stepLightness: 0.05,

	/**
	 * The contrast threshold.
	 *
	 * @since 1.0
	 */
	contrastThreshold: 4.5,

	/**
	 * Triggered when the control is ready.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	ready: function() {
		var control = this;

		// Set the background color.
		this.setBackgroundColorDetails();

		// Get new array of accessible colors.
		// this.setControlColors();

		// Set control params if defined in the choices argument.
		if ( 'undefined' !== typeof control.params.choices.maxHueDiff ) {
			this.maxHueDiff = control.params.choices.maxHueDiff;
		}
		if ( 'undefined' !== typeof control.params.choices.stepHue ) {
			this.stepHue = control.params.choices.stepHue;
		}
		if ( 'undefined' !== typeof control.params.choices.maxSaturation ) {
			this.maxSaturation = control.params.choices.maxSaturation;
		}
		if ( 'undefined' !== typeof control.params.choices.stepSaturation ) {
			this.stepSaturation = control.params.choices.stepSaturation;
		}
		if ( 'undefined' !== typeof control.params.choices.stepLightness ) {
			this.stepLightness = control.params.choices.stepLightness;
		}
		if ( 'undefined' !== typeof control.params.choices.contrastThreshold ) {
			this.contrastThreshold = control.params.choices.contrastThreshold;
		}

		this.updateColorsDebounced  = _.debounce( _.bind( this.updateColors, this ), 100 );
		this.watchSettingsDebounced = _.debounce( _.bind( this.watchSettings, this ), 100 );

		this.visibility();

		setTimeout( function() {

			// Init tabs.
			control.initTabs();
		}, 300 );

		// Init the colorpicker.
		this.initColorpicker();

		// Generate the control.
		this.updateColorsDebounced();

		// Watch for changes.
		this.watchSettingsDebounced();
	},

	/**
	 * Hides ts of the control if so defined.
	 *
	 * @since 1.1
	 * @returns void
	 */
	visibility: function() {
		var show = this.params.choices.show || {};

		if ( 'undefined' === typeof show.auto ) {
			show.auto = true;
		}
		if ( 'undefined' === typeof show.recommended ) {
			show.recommended = true;
		}
		if ( 'undefined' === typeof show.custom ) {
			show.custom = true;
		}
		if ( ! show.auto && ! show.recommended && ! show.custom ) {
			this.container.hide();
			this.a11ySelectorMode = 'auto';
		} else {
			if ( ! show.auto ) {
				this.container.find( '.kirki-a11y-text-colorpicker-toggle-auto' ).addClass( 'hidden' );
				this.container.find( '.kirki-a11y-text-colorpicker-wrapper[data-id=auto]' ).addClass( 'hidden' );
			}
			if ( ! show.recommended ) {
				this.container.find( '.kirki-a11y-text-colorpicker-toggle-recommended' ).addClass( 'hidden' );
				this.container.find( '.kirki-a11y-text-colorpicker-wrapper[data-id=recommended]' ).addClass( 'hidden' );
				this.updateControlHTML = function() {};
			}
			if ( ! show.custom ) {
				this.container.find( '.kirki-a11y-text-colorpicker-toggle-custom' ).addClass( 'hidden' );
				this.container.find( '.kirki-a11y-text-colorpicker-wrapper[data-id=custom]' ).addClass( 'hidden' );
				this.initColorpicker = function() {};
			}
		}
	},

	/**
	 * Init the tabs for recommended/custom colors.
	 *
	 * @since 1.0
	 * @param {bool} forceTab - Whether we want to force the tab switch or not.
	 * @returns {void}
	 */
	initTabs: function( forceTab ) {
		var control = this,
			show    = this.params.choices.show || {};

		// Remove previous even listeners.
		jQuery( control.container.find( '.tabs button' ) ).off();

		// Handle clicking buttons.
		jQuery( control.container.find( '.tabs button' ) ).on( 'click', function( event ) {
			control.a11ySelectorMode = jQuery( event.target ).attr( 'data-mode' );
			control.initTabs( true );
			event.preventDefault();
			if ( 'auto' === control.a11ySelectorMode ) {
				jQuery( '.kirki-color-control[data-id="' + control.id + '"]' ).attr( 'value', control.getAutoColor() ).trigger( 'change' );
			}
		});

		if ( ! forceTab && ! control.a11ySelectorMode ) {

			if ( control.setting._value === control.getAutoColor() && false !== show.auto ) {
				control.a11ySelectorMode = 'auto';
			} else if ( control.isSelectedColorRecommended() && false !== show.recommended ) {
				control.a11ySelectorMode = 'recommended';
			} else {
				control.a11ySelectorMode = 'custom';
			}
			control.initTabs();
			return;
		}

		jQuery( control.container.find( '.tabs button' ) ).removeClass( 'active' );
		jQuery( control.container.find( '.tabs button[data-mode="' + control.a11ySelectorMode + '"]' ) ).addClass( 'active' );
		jQuery( control.container.find( '.mode-selectors' ) ).attr( 'data-value', control.a11ySelectorMode );
		jQuery( control.container.find( '.kirki-a11y-text-colorpicker-wrapper' ) ).hide();
		jQuery( control.container.find( '.kirki-a11y-text-colorpicker-wrapper[data-id="' + control.a11ySelectorMode + '"]' ) ).show();

		if ( 'auto' === control.a11ySelectorMode ) {
			jQuery( control.container.find( '.selected-color-previewer-auto' ) )
				.html( control.getAutoColor() )
				.css( 'background-color', this.getColor() )
				.css( 'color', control.getAutoColor() );
		}
	},

	/**
	 * Check if the selected color is one of the recommended colors or not.
	 *
	 * @since 1.0
	 * @returns {bool}
	 */
	isSelectedColorRecommended: function() {
		var isRecommended = false,
			control       = this;

		if ( this.colors ) {
			if ( 'undefined' !== typeof this.colors[ this.setting._value ] ) {
				isRecommended = true;
			}
			if ( ! isRecommended ) {
				_.each( this.colors, function( color ) {
					if ( ! isRecommended && color.hex && control.setting._value === color.hex ) {
						isRecommended = true;
						return true;
					}
				});
			}
		}

		return isRecommended;
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
			clear = jQuery( control.container.find( '.kirki-input-container[data-id="' + control.id + '"] .wp-picker-clear' ) );
			if ( clear.length ) {
				clear.click( function() {
					wp.customize.control( control.id ).setting.set( '' );
				});
			}
		}, 200 );

		// Saves our settings to the WP API
		picker.wpColorPicker({
			change: function() {

				// Small hack: the picker needs a small delay
				setTimeout( function() {
					wp.customize.control( control.id ).setting.set( picker.val() );
				}, 20 );
			}
		});
	},

	/**
	 * The main init method.
	 *
	 * @since 1.0
	 * @param {string} newColor - The new color.
	 * @returns {void}
	 */
	updateColors: function( newColor ) {
		var show = this.params.choices.show || {};

		if ( ! this.backgroundColorChanged( newColor ) ) {
			return;
		}

		// Update the colors in the object.
		this.setBackgroundColorDetails();

		// Handle auto mode.
		if ( 'auto' === this.a11ySelectorMode ) {

			// Change the value in the colorpicker.
			// Automatically sets the value in the customizer object
			jQuery( '.kirki-color-control[data-id="' + this.id + '"]' ).attr( 'value', this.getAutoColor() ).trigger( 'change' );

			// Display the value.
			jQuery( this.container.find( '.selected-color-previewer-auto' ) )
				.html( this.getAutoColor() )
				.css( 'background-color', this.getColor() )
				.css( 'color', this.getAutoColor() );
		} else if ( show.recommended ) {

			// Get new array of accessible colors.
			this.setControlColors();

			// Update the HTML for the control.
			this.updateControlHTML();
		}
	},

	/**
	 * Updates the HTML in the control. adding the colors we need.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	updateControlHTML: function() {
		var control         = this,
			colorsContainer = jQuery( control.container.find( '.kirki-a11y-text-colorpicker-wrapper[data-id="recommended"] .wrapper' ) ),
			html            = '',
			borderColor;

		// Reset the HTML.
		colorsContainer.html( '' );

		// Add colors as radio-buttons.
		_.each( control.colors, function( color ) {
			borderColor = ( 0.5 < color.lum ) ? 'rgba(0,0,0,.15)' : 'rgba(255,255,255,.15)';
			html += '<label>';
			html += '<input type="radio" value="' + color.hex + '" name="_customize-radio-' + control.id + '"';
			html += ( control.setting._value === color.hex ) ? ' checked' : '';
			html += ' ' + control.params.link;
			html += '/>';
			html += '<span class="a11y-text-selector-label" style="background-color:' + color.hex + ';border-color:' + borderColor + ';"></span>';
			html += '<span class="screen-reader-text">' + color.hex + '</span>';
			html += '</label>';
		});

		colorsContainer.append( html );

		// Watch for value changes and save if necessary.
		control.saveColorValueWatcher();
	},

	/**
	 * Watch defined controls and re-trigger results calculations when there's a change.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	watchSettings: function() {
		var control = this,
			setting = this.params.choices.setting,
			part    = ( -1 < setting.indexOf( '[' ) ) ? setting.split( '[' )[1].replace( ']', '' ) : false;

		setting = ( part ) ? setting.split( '[' )[0] : setting;

		wp.customize( setting, function( watchSetting ) {
			watchSetting.bind( function( to ) {
				if ( 'string' === typeof to && -1 < to.indexOf( '{' ) && -1 < to.indexOf( '}' ) ) {
					to = JSON.parse( to );
				}
				to = ( part && to[ part ] ) ? to[ part ] : to;
				if ( 'string' === typeof to ) {
					control.updateColors( control.toHex( to ) );
				}
			});
		});
	},

	/**
	 * Sets items in the this.colors array.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	setControlColors: function() {
		this.colors = wcagColors.getAll({
			color: this.backgroundColor.hex,
			maxHueDiff: this.backgroundColor.h ? this.maxHueDiff : 360,
			stepHue: this.stepHue,
			minSaturation: 0,
			maxSaturation: this.maxSaturation,
			stepSaturation: this.stepSaturation,
			stepLightness: this.stepLightness
		}).pluck({
			color: this.backgroundColor.hex,
			minContrast: this.contrastThreshold
		}).sortBy( 'contrast' ).colors;
	},

	/**
	 * Automatically gets the color with the most contrast.
	 *
	 * @since 1.0
	 * @returns {string}
	 */
	getAutoColor: function() {
		this.setBackgroundColorDetails();
		if ( wcagColors.getContrast( 0, this.backgroundColor.lum ) > wcagColors.getContrast( 1, this.backgroundColor.lum ) ) {
			return '#000000';
		}
		return '#ffffff';
	},

	/**
	 * Set the object's colors property.
	 *
	 * @since 1.0
	 * @returns {void}
	 */
	setBackgroundColorDetails: function() {
		this.backgroundColor = wcagColors.getColorProperties( this.getColor() );
	},

	/**
	 * Gets a color defined in the control's choices.
	 *
	 * @since 1.0
	 * @returns {string}
	 */
	getColor: function() {
		var setting = this.params.choices.setting,
			part    = ( -1 < setting.indexOf( '[' ) ) ? setting.split( '[' )[1].replace( ']', '' ) : false,
			value;

		setting = ( part ) ? setting.split( '[' )[0] : setting;
		value   = wp.customize( setting ).get();

		if ( 'string' === typeof to && -1 < value.indexOf( '{' ) && -1 < value.indexOf( '}' ) ) {
			value = JSON.parse( value );
		}

		if ( part && value[ part ] ) {
			value = value[ part ];
		}

		return this.toHex( value );
	},

	/**
	 * Check if the background color has changed.
	 *
	 * @since 1.0
	 * @param {string} newColor - The new color.
	 * @returns {bool}
	 */
	backgroundColorChanged: function( newColor ) {
		var control      = this,
			colorChanged = false;

		if ( newColor !== control.backgroundColor.hex ) {
			colorChanged = true;
			if ( newColor ) {
				control.backgroundColor = wcagColors.getColorProperties( newColor );
			}
		}
		return colorChanged;
	},

	toHex: function( color ) {
		var rgb, r, g, b;
		if ( -1 < color.indexOf( 'rgb' ) ) {
			rgb = color.match( /^rgba?\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*(,\s*(\d*)\.?(\d*)\s*)?\)$/ );
			if ( rgb ) {
				r     = ( '0' + parseInt( rgb[1], 10 ).toString( 16 ) ).slice( -2 );
				g     = ( '0' + parseInt( rgb[2], 10 ).toString( 16 ) ).slice( -2 );
				b     = ( '0' + parseInt( rgb[3], 10 ).toString( 16 ) ).slice( -2 );
				color = '#' + r + g + b;
			}
		}
		return color;
	},

	/**
	 * Sets the value for this control.
	 *
	 * @returns {void}
	 */
	saveColorValueWatcher: function() {
		var control = this;

		jQuery( control.container.find( '.kirki-a11y-text-colorpicker-wrapper[data-id="recommended"] input' ) ).on( 'change keyup paste click', function() {

			// Change the value in the colorpicker.
			// Automatically sets the value in the customizer object
			jQuery( '.kirki-color-control[data-id="' + control.id + '"]' ).attr( 'value', jQuery( this ).val() ).trigger( 'change' );

			// Focus on this element. Enhases keyboard-navigation experience.
			jQuery( this ).focus();
		});

		// Update the indicator value.
		this.setting.bind( 'change', function( to ) {
			jQuery( control.container.find( '.selected-color' ) ).html( to );
		});
	}
});
