(function(api){
  "use strict";

  /**
   * Extend control functionality
   *
   * @param control
   * @param id
   * @param options
   */
	window.extendControl = function(control, id, options){
		// need to fetch default setting used for control
		var setting = options.params.settings.default;
		// Post Parameter (post var)
		// api.previewerSync.registerRedirectTag(setting, options.params.postvar);

		// Style Output
		api.previewerSync.registerStyleOutput(setting, options);

		// only Register Partial Refresh when control created dynamically
		if(options.params.dynamic) {
			api.previewerSync.registerPartialRefresh(setting, control.section.get(), options.params.partial_refresh)
		}

		// Active Callback
		// api.activeCallback.registerActiveRule(control, options.params.active_rule);
	};

	/**
	* Initialize control
	*
	* @param control
	* @param id
	* @param options
	*/
	window.initializeControl = function(control, id, options){
		var sectionId = options.params.section;
		// if control is normal, extend control right away
		if(!control.params.dynamic) {
			window.extendControl(control, id, options);
		}

		api.section.bind(sectionId, function(section){
			if(section.loaded && control.params.dynamic) {
				window.extendControl(control, id, options);
			}
		});
	};

	api.controlConstructor.default = api.Control.extend({
		initialize: function( id, options ) {
			api.Control.prototype.initialize.call( this, id, options );
			window.initializeControl(this, id, options);
		},
	});

	/**
	 * A dynamic color-alpha control.
	 *
	 * @class
	 * @augments wp.customize.Control
	 * @augments wp.customize.Class
	 */
	wp.customize.kirkiDynamicControl = wp.customize.Control.extend( {

		initialize: function( id, options ) {
			var control = this,
				args    = options || {};

			args.params = args.params || {};
			if ( ! args.params.type ) {
				args.params.type = 'kirki-generic';
			}
			if ( ! args.params.content ) {
				args.params.content = jQuery( '<li></li>' );
				args.params.content.attr( 'id', 'customize-control-' + id.replace( /]/g, '' ).replace( /\[/g, '-' ) );
				args.params.content.attr( 'class', 'customize-control customize-control-' + args.params.type );
			}

			control.propertyElements = [];
			wp.customize.Control.prototype.initialize.call( control, id, args );

			window.initializeControl(control, id, args);
		},

		/**
		 * Add bidirectional data binding links between inputs and the setting(s).
		 *
		 * This is copied from wp.customize.Control.prototype.initialize(). It
		 * should be changed in Core to be applied once the control is embedded.
		 *
		 * @private
		 * @returns {null}
		 */
		_setUpSettingRootLinks: function() {
			var control = this,
				nodes   = control.container.find( '[data-customize-setting-link]' );

			nodes.each( function() {
				var node = jQuery( this );

				wp.customize( node.data( 'customizeSettingLink' ), function( setting ) {
					var element = new wp.customize.Element( node );
					control.elements.push( element );
					element.sync( setting );
					element.set( setting() );
				} );
			} );
		},

		/**
		 * Add bidirectional data binding links between inputs and the setting properties.
		 *
		 * @private
		 * @returns {null}
		 */
		_setUpSettingPropertyLinks: function() {
			var control = this,
				nodes;

			if ( ! control.setting ) {
				return;
			}

			nodes = control.container.find( '[data-customize-setting-property-link]' );

			nodes.each( function() {
				var node = jQuery( this ),
					element,
					propertyName = node.data( 'customizeSettingPropertyLink' );

				element = new wp.customize.Element( node );
				control.propertyElements.push( element );
				element.set( control.setting()[ propertyName ] );

				element.bind( function( newPropertyValue ) {
					var newSetting = control.setting();
					if ( newPropertyValue === newSetting[ propertyName ] ) {
						return;
					}
					newSetting = _.clone( newSetting );
					newSetting[ propertyName ] = newPropertyValue;
					control.setting.set( newSetting );
				} );
				control.setting.bind( function( newValue ) {
					if ( newValue[ propertyName ] !== element.get() ) {
						element.set( newValue[ propertyName ] );
					}
				} );
			} );
		},

		/**
		 * @inheritdoc
		 */
		ready: function() {
			var control = this;

			control._setUpSettingRootLinks();
			control._setUpSettingPropertyLinks();

			wp.customize.Control.prototype.ready.call( control );

			control.deferred.embedded.done( function() {
				control.initKirkiControl( control );
			} );
		},

		/**
		 * Embed the control in the document.
		 *
		 * Override the embed() method to do nothing,
		 * so that the control isn't embedded on load,
		 * unless the containing section is already expanded.
		 *
		 * @returns {null}
		 */
		embed: function() {
			var control   = this,
				sectionId = control.section();

			if ( ! sectionId ) {
				return;
			}

			wp.customize.section( sectionId, function( section ) {
				if ( 'kirki-expanded' === section.params.type || section.expanded() || wp.customize.settings.autofocus.control === control.id ) {
					control.actuallyEmbed();
				} else {
					section.expanded.bind( function( expanded ) {
						if ( expanded ) {
							control.actuallyEmbed();
						}
					} );
				}
			} );
		},

		/**
		 * Deferred embedding of control when actually
		 *
		 * This function is called in Section.onChangeExpanded() so the control
		 * will only get embedded when the Section is first expanded.
		 *
		 * @returns {null}
		 */
		actuallyEmbed: function() {
			var control = this;
			if ( 'resolved' === control.deferred.embedded.state() ) {
				return;
			}
			control.renderContent();
			control.deferred.embedded.resolve(); // This triggers control.ready().
		},

		/**
		 * This is not working with autofocus.
		 *
		 * @param {object} [args] Args.
		 * @returns {null}
		 */
		focus: function( args ) {
			var control = this;
			control.actuallyEmbed();
			wp.customize.Control.prototype.focus.call( control, args );
		},

		/**
		 * Additional actions that run on ready.
		 *
		 * @param {object} [args] Args.
		 * @returns {null}
		 */
		initKirkiControl: function( control ) {
			if ( 'undefined' !== typeof kirki.control[ control.params.type ] ) {
				kirki.control[ control.params.type ].init( control );
				return;
			}

			// Save the value
			this.container.on( 'change keyup paste click', 'input', function() {
				control.setting.set( jQuery( this ).val() );
			} );
		}
	} );

})(wp.customize);


/* jshint -W079 */
/* jshint unused:false */
if ( _.isUndefined( window.kirkiSetSettingValue ) ) {
	_.isUndefined( window.kirkiSetSettingValue )
	var kirkiSetSettingValue = { // eslint-disable-line vars-on-top

		/**
		 * Set the value of the control.
		 *
		 * @since 3.0.0
		 * @param string setting The setting-ID.
		 * @param mixed  value   The value.
		 */
		set: function( setting, value ) {

			/**
			 * Get the control of the sub-setting.
			 * This will be used to get properties we need from that control,
			 * and determine if we need to do any further work based on those.
			 */
			var $this = this,
				subControl = wp.customize.settings.controls[ setting ],
				valueJSON;

			// If the control doesn't exist then return.
			if ( _.isUndefined( subControl ) ) {
				return true;
			}

			// First set the value in the wp object. The control type doesn't matter here.
			$this.setValue( setting, value );

			// Process visually changing the value based on the control type.
			switch ( subControl.type ) {

				case 'kirki-background':
					if ( ! _.isUndefined( value['background-color'] ) ) {
						$this.setColorPicker( $this.findElement( setting, '.kirki-color-control' ), value['background-color'] );
					}
					$this.findElement( setting, '.placeholder, .thumbnail' ).removeClass().addClass( 'placeholder' ).html( 'No file selected' );
					_.each( [ 'background-repeat', 'background-position' ], function( subVal ) {
						if ( ! _.isUndefined( value[ subVal ] ) ) {
							$this.setSelectWoo( $this.findElement( setting, '.' + subVal + ' select' ), value[ subVal ] );
						}
					} );
					_.each( [ 'background-size', 'background-attachment' ], function( subVal ) {
						jQuery( $this.findElement( setting, '.' + subVal + ' input[value="' + value + '"]' ) ).prop( 'checked', true );
					} );
					valueJSON = JSON.stringify( value ).replace( /'/g, '&#39' );
					jQuery( $this.findElement( setting, '.background-hidden-value' ).attr( 'value', valueJSON ) ).trigger( 'change' );
					break;

				case 'kirki-code':
					jQuery( $this.findElement( setting, '.CodeMirror' ) )[0].CodeMirror.setValue( value );
					break;

				case 'checkbox':
				case 'kirki-switch':
				case 'kirki-toggle':
					value = ( 1 === value || '1' === value || true === value ) ? true : false;
					jQuery( $this.findElement( setting, 'input' ) ).prop( 'checked', value );
					wp.customize.instance( setting ).set( value );
					break;

				case 'kirki-select':
					$this.setSelectWoo( $this.findElement( setting, 'select' ), value );
					break;

				case 'kirki-slider':
					jQuery( $this.findElement( setting, 'input' ) ).prop( 'value', value );
					jQuery( $this.findElement( setting, '.kirki_range_value .value' ) ).html( value );
					break;

				case 'kirki-generic':
					if ( _.isUndefined( subControl.choices ) || _.isUndefined( subControl.choices.element ) ) {
						subControl.choices.element = 'input';
					}
					jQuery( $this.findElement( setting, subControl.choices.element ) ).prop( 'value', value );
					break;

				case 'kirki-color':
					$this.setColorPicker( $this.findElement( setting, '.kirki-color-control' ), value );
					break;

				case 'kirki-multicheck':
					$this.findElement( setting, 'input' ).each( function() {
						jQuery( this ).prop( 'checked', false );
					} );
					_.each( value, function( subValue, i ) {
						jQuery( $this.findElement( setting, 'input[value="' + value[ i ] + '"]' ) ).prop( 'checked', true );
					} );
					break;

				case 'kirki-multicolor':
					_.each( value, function( subVal, index ) {
						$this.setColorPicker( $this.findElement( setting, '.multicolor-index-' + index ), subVal );
					} );
					break;

				case 'kirki-radio-buttonset':
				case 'kirki-radio-image':
				case 'kirki-radio':
				case 'kirki-dashicons':
				case 'kirki-color-palette':
				case 'kirki-palette':
					jQuery( $this.findElement( setting, 'input[value="' + value + '"]' ) ).prop( 'checked', true );
					break;

				case 'kirki-typography':
					_.each( [ 'font-family', 'variant' ], function( subVal ) {
						if ( ! _.isUndefined( value[ subVal ] ) ) {
							$this.setSelectWoo( $this.findElement( setting, '.' + subVal + ' select' ), value[ subVal ] );
						}
					} );
					_.each( [ 'font-size', 'line-height', 'letter-spacing', 'word-spacing' ], function( subVal ) {
						if ( ! _.isUndefined( value[ subVal ] ) ) {
							jQuery( $this.findElement( setting, '.' + subVal + ' input' ) ).prop( 'value', value[ subVal ] );
						}
					} );

					if ( ! _.isUndefined( value.color ) ) {
						$this.setColorPicker( $this.findElement( setting, '.kirki-color-control' ), value.color );
					}
					valueJSON = JSON.stringify( value ).replace( /'/g, '&#39' );
					jQuery( $this.findElement( setting, '.typography-hidden-value' ).attr( 'value', valueJSON ) ).trigger( 'change' );
					break;

				case 'kirki-dimensions':
					_.each( value, function( subValue, id ) {
						jQuery( $this.findElement( setting, '.' + id + ' input' ) ).prop( 'value', subValue );
					} );
					break;

				case 'kirki-repeater':

					// Not yet implemented.
					break;

				case 'kirki-custom':

					// Do nothing.
					break;
				default:
					jQuery( $this.findElement( setting, 'input' ) ).prop( 'value', value );
			}
		},

		/**
		 * Set the value for colorpickers.
		 * CAUTION: This only sets the value visually, it does not change it in th wp object.
		 *
		 * @since 3.0.0
		 * @param object selector jQuery object for this element.
		 * @param string value    The value we want to set.
		 */
		setColorPicker: function( selector, value ) {
			selector.attr( 'data-default-color', value ).data( 'default-color', value ).wpColorPicker( 'color', value );
		},

		/**
		 * Sets the value in a selectWoo element.
		 * CAUTION: This only sets the value visually, it does not change it in th wp object.
		 *
		 * @since 3.0.0
		 * @param string selector The CSS identifier for this selectWoo.
		 * @param string value    The value we want to set.
		 */
		setSelectWoo: function( selector, value ) {
			jQuery( selector ).selectWoo().val( value ).trigger( 'change' );
		},

		/**
		 * Sets the value in textarea elements.
		 * CAUTION: This only sets the value visually, it does not change it in th wp object.
		 *
		 * @since 3.0.0
		 * @param string selector The CSS identifier for this textarea.
		 * @param string value    The value we want to set.
		 */
		setTextarea: function( selector, value ) {
			jQuery( selector ).prop( 'value', value );
		},

		/**
		 * Finds an element inside this control.
		 *
		 * @since 3.0.0
		 * @param string setting The setting ID.
		 * @param string element The CSS identifier.
		 */
		findElement: function( setting, element ) {
			return wp.customize.control( setting ).container.find( element );
		},

		/**
		 * Updates the value in the wp.customize object.
		 *
		 * @since 3.0.0
		 * @param string setting The setting-ID.
		 * @param mixed  value   The value.
		 */
		setValue: function( setting, value, timeout ) {
			timeout = ( _.isUndefined( timeout ) ) ? 100 : parseInt( timeout, 10 );
			wp.customize.instance( setting ).set( {} );
			setTimeout( function() {
				wp.customize.instance( setting ).set( value );
			}, timeout );
		}
	};
}
var kirki = {

	initialized: false,

	/**
	 * Initialize the object.
	 *
	 * @since 3.0.17
	 * @returns {null}
	 */
	initialize: function() {
		var self = this;

		// We only need to initialize once.
		if ( self.initialized ) {
			return;
		}

		setTimeout( function() {
			kirki.util.webfonts.standard.initialize();
			kirki.util.webfonts.google.initialize();
		}, 150 );

		// Mark as initialized.
		self.initialized = true;
	}
};

// Initialize the kirki object.
kirki.initialize();
var kirki = kirki || {};
kirki = jQuery.extend( kirki, {

	/**
	 * An object containing definitions for controls.
	 *
	 * @since 3.0.16
	 */
	control: {

		/**
		 * The radio control.
		 *
		 * @since 3.0.17
		 */
		'kirki-radio': {

			/**
			 * Init the control.
			 *
			 * @since 3.0.17
			 * @param {Object} control - The customizer control object.
			 * @returns {null}
			 */
			init: function( control ) {
				var self = this;

				// Render the template.
				self.template( control );

				// Init the control.
				kirki.input.radio.init( control );

			},

			/**
			 * Render the template.
			 *
			 * @since 3.0.17
			 * @param {Object} control - The customizer control object.
			 * @param {Object} control.params - The control parameters.
			 * @param {string} control.params.label - The control label.
			 * @param {string} control.params.description - The control description.
			 * @param {string} control.params.inputAttrs - extra input arguments.
			 * @param {string} control.params.default - The default value.
			 * @param {Object} control.params.choices - Any extra choices we may need.
			 * @param {string} control.id - The setting.
			 * @returns {null}
			 */
			template: function( control ) {
				var template = wp.template( 'kirki-input-radio' );
				control.container.html( template( {
					label: control.params.label,
					description: control.params.description,
					'data-id': control.id,
					inputAttrs: control.params.inputAttrs,
					'default': control.params.default,
					value: kirki.setting.get( control.id ),
					choices: control.params.choices
				} ) );
			}
		},

		/**
		 * The generic control.
		 *
		 * @since 3.0.16
		 */
		'kirki-generic': {

			/**
			 * Init the control.
			 *
			 * @since 3.0.17
			 * @param {Object} control - The customizer control object.
			 * @param {Object} control.params - Control parameters.
			 * @param {Object} control.params.choices - Define the specifics for this input.
			 * @param {string} control.params.choices.element - The HTML element we want to use ('input', 'div', 'span' etc).
			 * @returns {null}
			 */
			init: function( control ) {
				var self = this;

				// Render the template.
				self.template( control );

				// Init the control.
				if ( ! _.isUndefined( control.params ) && ! _.isUndefined( control.params.choices ) && ! _.isUndefined( control.params.choices.element ) && 'textarea' === control.params.choices.element ) {
					kirki.input.textarea.init( control );
					return;
				}
				kirki.input.genericInput.init( control );
			},

			/**
			 * Render the template.
			 *
			 * @since 3.0.17
			 * @param {Object}  control - The customizer control object.
			 * @param {Object}  control.params - The control parameters.
			 * @param {string}  control.params.label - The control label.
			 * @param {string}  control.params.description - The control description.
			 * @param {string}  control.params.inputAttrs - extra input arguments.
			 * @param {string}  control.params.default - The default value.
			 * @param {Object}  control.params.choices - Any extra choices we may need.
			 * @param {boolean} control.params.choices.alpha - should we add an alpha channel?
			 * @param {string}  control.id - The setting.
			 * @returns {null}
			 */
			template: function( control ) {
				var args = {
						label: control.params.label,
						description: control.params.description,
						'data-id': control.id,
						inputAttrs: control.params.inputAttrs,
						choices: control.params.choices,
						value: kirki.setting.get( control.id )
					},
					template;

				if ( ! _.isUndefined( control.params ) && ! _.isUndefined( control.params.choices ) && ! _.isUndefined( control.params.choices.element ) && 'textarea' === control.params.choices.element ) {
					template = wp.template( 'kirki-input-textarea' );
					control.container.html( template( args ) );
					return;
				}
				template = wp.template( 'kirki-input-generic' );
				control.container.html( template( args ) );
			}
		},

		/**
		 * The number control.
		 *
		 * @since 3.0.26
		 */
		'kirki-number': {

			/**
			 * Init the control.
			 *
			 * @since 3.0.26
			 * @param {Object} control - The customizer control object.
			 * @returns {null}
			 */
			init: function( control ) {
				var self = this;

				// Render the template.
				self.template( control );

				// Init the control.
				kirki.input.number.init( control );
			},

			/**
			 * Render the template.
			 *
			 * @since 3.0.27
			 * @param {Object}  control - The customizer control object.
			 * @param {Object}  control.params - The control parameters.
			 * @param {string}  control.params.label - The control label.
			 * @param {string}  control.params.description - The control description.
			 * @param {string}  control.params.inputAttrs - extra input arguments.
			 * @param {string}  control.params.default - The default value.
			 * @param {Object}  control.params.choices - Any extra choices we may need.
			 * @param {string}  control.id - The setting.
			 * @returns {null}
			 */
			template: function( control ) {
				var template = wp.template( 'kirki-input-number' );

				control.container.html(
					template( args = {
						label: control.params.label,
						description: control.params.description,
						'data-id': control.id,
						inputAttrs: control.params.inputAttrs,
						choices: control.params.choices,
						value: kirki.setting.get( control.id )
					} )
				);
			}
		},
	}
} );
/* global kirkiL10n */
var kirki = kirki || {};
kirki = jQuery.extend( kirki, {

	/**
	 * An object containing definitions for input fields.
	 *
	 * @since 3.0.16
	 */
	input: {

		/**
		 * Radio input fields.
		 *
		 * @since 3.0.17
		 */
		radio: {

			/**
			 * Init the control.
			 *
			 * @since 3.0.17
			 * @param {Object} control - The control object.
			 * @param {Object} control.id - The setting.
			 * @returns {null}
			 */
			init: function( control ) {
				var input = jQuery( 'input[data-id="' + control.id + '"]' );

				// Save the value
				input.on( 'change keyup paste click', function() {
					kirki.setting.set( control.id, jQuery( this ).val() );
				} );
			}
		},

		/**
		 * Generic input fields.
		 *
		 * @since 3.0.17
		 */
		genericInput: {

			/**
			 * Init the control.
			 *
			 * @since 3.0.17
			 * @param {Object} control - The control object.
			 * @param {Object} control.id - The setting.
			 * @returns {null}
			 */
			init: function( control ) {
				var input = jQuery( 'input[data-id="' + control.id + '"]' );

				// Save the value
				input.on( 'change keyup paste click', function() {
					kirki.setting.set( control.id, jQuery( this ).val() );
				} );
			}
		},

		/**
		 * Generic input fields.
		 *
		 * @since 3.0.17
		 */
		textarea: {

			/**
			 * Init the control.
			 *
			 * @since 3.0.17
			 * @param {Object} control - The control object.
			 * @param {Object} control.id - The setting.
			 * @returns {null}
			 */
			init: function( control ) {
				var textarea = jQuery( 'textarea[data-id="' + control.id + '"]' );

				// Save the value
				textarea.on( 'change keyup paste click', function() {
					kirki.setting.set( control.id, jQuery( this ).val() );
				} );
			}
		},

		/**
		 * Number fields.
		 *
		 * @since 3.0.26
		 */
		number: {

			/**
			 * Init the control.
			 *
			 * @since 3.0.17
			 * @param {Object} control - The control object.
			 * @param {Object} control.id - The setting.
			 * @returns {null}
			 */
			init: function( control ) {

				var element = jQuery( 'input[data-id="' + control.id + '"]' ),
					value   = control.setting._value,
					up,
					down;

				// Make sure we use default values if none are define for some arguments.
				control.params.choices = _.defaults( control.params.choices, {
					min: 0,
					max: 100,
					step: 1
				} );

				// Make sure we have a valid value.
				if ( isNaN( value ) || '' === value ) {
					value = ( 0 > control.params.choices.min && 0 < control.params.choices.max ) ? 0 : control.params.choices.min;
				}
				value = parseFloat( value );

				// If step is 'any', set to 0.001.
				control.params.choices.step = ( 'any' === control.params.choices.step ) ? 0.001 : control.params.choices.step;

				// Make sure choices are properly formtted as numbers.
				control.params.choices.min  = parseFloat( control.params.choices.min );
				control.params.choices.max  = parseFloat( control.params.choices.max );
				control.params.choices.step = parseFloat( control.params.choices.step );

				up   = jQuery( '.kirki-input-container[data-id="' + control.id + '"] .plus' );
				down = jQuery( '.kirki-input-container[data-id="' + control.id + '"] .minus' );

				up.click( function() {
					var oldVal = parseFloat( element.val() ),
						newVal;

					newVal = ( oldVal >= control.params.choices.max ) ? oldVal : oldVal + control.params.choices.step;

					element.val( newVal );
					element.trigger( 'change' );
				} );

				down.click( function() {
					var oldVal = parseFloat( element.val() ),
						newVal;

					newVal = ( oldVal <= control.params.choices.min ) ? oldVal : oldVal - control.params.choices.step;

					element.val( newVal );
					element.trigger( 'change' );
				} );

				element.on( 'change keyup paste click', function() {
					var val = jQuery( this ).val();
					if ( isNaN( val ) ) {
						val = parseFloat( val, 10 );
						val = ( isNaN( val ) ) ? 0 : val;
						jQuery( this ).attr( 'value', val );
					}
					kirki.setting.set( control.id, val );
				} );
			}

		},
	}
} );
var kirki = kirki || {};
kirki = jQuery.extend( kirki, {

	/**
	 * An object containing definitions for settings.
	 *
	 * @since 3.0.16
	 */
	setting: {

		/**
		 * Gets the value of a setting.
		 *
		 * This is a helper function that allows us to get the value of
		 * control[key1][key2] for example, when the setting used in the
		 * customizer API is "control".
		 *
		 * @since 3.0.16
		 * @param {string} setting - The setting for which we're getting the value.
		 * @returns {mixed} Depends on the value.
		 */
		get: function( setting ) {
			var parts        = setting.split( '[' ),
				foundSetting = '',
				foundInStep  = 0,
				currentVal   = '';

			_.each( parts, function( part, i ) {
				part = part.replace( ']', '' );

				if ( 0 === i ) {
					foundSetting = part;
				} else {
					foundSetting += '[' + part + ']';
				}

				if ( ! _.isUndefined( wp.customize.instance( foundSetting ) ) ) {
					currentVal  = wp.customize.instance( foundSetting ).get();
					foundInStep = i;
				}

				if ( foundInStep < i ) {
					if ( _.isObject( currentVal ) && ! _.isUndefined( currentVal[ part ] ) ) {
						currentVal = currentVal[ part ];
					}
				}
			} );

			return currentVal;
		},

		/**
		 * Sets the value of a setting.
		 *
		 * This function is a bit complicated because there any many scenarios to consider.
		 * Example: We want to save the value for my_setting[something][3][something-else].
		 * The control's setting is my_setting[something].
		 * So we need to find that first, then figure out the remaining parts,
		 * merge the values recursively to avoid destroying my_setting[something][2]
		 * and also take into account any defined "key" arguments which take this even deeper.
		 *
		 * @since 3.0.16
		 * @param {object|string} element - The DOM element whose value has changed,
		 *                                  or an ID.
		 * @param {mixed}         value - Depends on the control-type.
		 * @param {string}        key - If we only want to save an item in an object
		 *                                  we can define the key here.
		 * @returns {null}
		 */
		set: function( element, value, key ) {
			var setting,
				parts,
				currentNode   = '',
				foundNode     = '',
				subSettingObj = {},
				currentVal,
				subSetting,
				subSettingParts;

			// Get the setting from the element.
			setting = element;
			if ( _.isObject( element ) ) {
				if ( jQuery( element ).attr( 'data-id' ) ) {
					setting = element.attr( 'data-id' );
				} else {
					setting = element.parents( '[data-id]' ).attr( 'data-id' );
				}
			}

			if ( 'undefined' !== typeof wp.customize.control( setting ) ) {
				wp.customize.control( setting ).setting.set( value );
				return;
			}

			parts = setting.split( '[' );

			// Find the setting we're using in the control using the customizer API.
			_.each( parts, function( part, i ) {
				part = part.replace( ']', '' );

				// The current part of the setting.
				currentNode = ( 0 === i ) ? part : '[' + part + ']';

				// When we find the node, get the value from it.
				// In case of an object we'll need to merge with current values.
				if ( ! _.isUndefined( wp.customize.instance( currentNode ) ) ) {
					foundNode  = currentNode;
					currentVal = wp.customize.instance( foundNode ).get();
				}
			} );

			// Get the remaining part of the setting that was unused.
			subSetting = setting.replace( foundNode, '' );

			// If subSetting is not empty, then we're dealing with an object
			// and we need to dig deeper and recursively merge the values.
			if ( '' !== subSetting ) {
				if ( ! _.isObject( currentVal ) ) {
					currentVal = {};
				}
				if ( '[' === subSetting.charAt( 0 ) ) {
					subSetting = subSetting.replace( '[', '' );
				}
				subSettingParts = subSetting.split( '[' );
				_.each( subSettingParts, function( subSettingPart, i ) {
					subSettingParts[ i ] = subSettingPart.replace( ']', '' );
				} );

				// If using a key, we need to go 1 level deeper.
				if ( key ) {
					subSettingParts.push( key );
				}

				// Converting to a JSON string and then parsing that to an object
				// may seem a bit hacky and crude but it's efficient and works.
				subSettingObj = '{"' + subSettingParts.join( '":{"' ) + '":"' + value + '"' + '}'.repeat( subSettingParts.length );
				subSettingObj = JSON.parse( subSettingObj );

				// Recursively merge with current value.
				jQuery.extend( true, currentVal, subSettingObj );
				value = currentVal;

			} else {
				if ( key ) {
					currentVal = ( ! _.isObject( currentVal ) ) ? {} : currentVal;
					currentVal[ key ] = value;
					value = currentVal;
				}
			}
			wp.customize.control( foundNode ).setting.set( value );
		}
	}
} );
/* global ajaxurl */
var kirki = kirki || {};
kirki = jQuery.extend( kirki, {

	/**
	 * A collection of utility methods.
	 *
	 * @since 3.0.17
	 */
	util: {

		/**
		 * A collection of utility methods for webfonts.
		 *
		 * @since 3.0.17
		 */
		webfonts: {

			/**
			 * Google-fonts related methods.
			 *
			 * @since 3.0.17
			 */
			google: {

				/**
				 * An object containing all Google fonts.
				 *
				 * to set this call this.setFonts();
				 *
				 * @since 3.0.17
				 */
				fonts: {},

				/**
				 * Init for google-fonts.
				 *
				 * @since 3.0.17
				 * @returns {null}
				 */
				initialize: function() {
					var self = this;

					self.setFonts();
				},

				/**
				 * Set fonts in this.fonts
				 *
				 * @since 3.0.17
				 * @returns {null}
				 */
				setFonts: function() {
					var self = this;

					// No need to run if we already have the fonts.
					if ( ! _.isEmpty( self.fonts ) ) {
						return;
					}

					// Make an AJAX call to set the fonts object (alpha).
					jQuery.post( ajaxurl, { 'action': 'kirki_fonts_google_all_get' }, function( response ) {

						// Get fonts from the JSON array.
						self.fonts = JSON.parse( response );
					} );
				},

				/**
				 * Gets all properties of a font-family.
				 *
				 * @since 3.0.17
				 * @param {string} family - The font-family we're interested in.
				 * @returns {Object}
				 */
				getFont: function( family ) {
					var self = this,
						fonts = self.getFonts();

					if ( 'undefined' === typeof fonts[ family ] ) {
						return false;
					}
					return fonts[ family ];
				},

				/**
				 * Gets all properties of a font-family.
				 *
				 * @since 3.0.17
				 * @param {string} order - How to order the fonts (alpha|popularity|trending).
				 * @param {int}    number - How many to get. 0 for all.
				 * @returns {Object}
				 */
				getFonts: function( order, category, number ) {
					var self        = this,
						ordered     = {},
						categorized = {},
						plucked     = {};

					// Make sure order is correct.
					order  = order || 'alpha';
					order  = ( 'alpha' !== order && 'popularity' !== order && 'trending' !== order ) ? 'alpha' : order;

					// Make sure number is correct.
					number = number || 0;
					number = parseInt( number, 10 );

					// Order fonts by the 'order' argument.
					if ( 'alpha' === order ) {
						ordered = jQuery.extend( {}, self.fonts.items );
					} else {
						_.each( self.fonts.order[ order ], function( family ) {
							ordered[ family ] = self.fonts.items[ family ];
						} );
					}

					// If we have a category defined get only the fonts in that category.
					if ( '' === category || ! category ) {
						categorized = ordered;
					} else {
						_.each( ordered, function( font, family ) {
							if ( category === font.category ) {
								categorized[ family ] = font;
							}
						} );
					}

					// If we only want a number of font-families get the 1st items from the results.
					if ( 0 < number ) {
						_.each( _.first( _.keys( categorized ), number ), function( family ) {
							plucked[ family ] = categorized[ family ];
						} );
						return plucked;
					}

					return categorized;
				},

				/**
				 * Gets the variants for a font-family.
				 *
				 * @since 3.0.17
				 * @param {string} family - The font-family we're interested in.
				 * @returns {Array}
				 */
				getVariants: function( family ) {
					var self = this,
						font = self.getFont( family );

					// Early exit if font was not found.
					if ( ! font ) {
						return false;
					}

					// Early exit if font doesn't have variants.
					if ( _.isUndefined( font.variants ) ) {
						return false;
					}

					// Return the variants.
					return font.variants;
				}
			},

			/**
			 * Standard fonts related methods.
			 *
			 * @since 3.0.17
			 */
			standard: {

				/**
				 * An object containing all Standard fonts.
				 *
				 * to set this call this.setFonts();
				 *
				 * @since 3.0.17
				 */
				fonts: {},

				/**
				 * Init for google-fonts.
				 *
				 * @since 3.0.17
				 * @returns {null}
				 */
				initialize: function() {
					var self = this;

					self.setFonts();
				},

				/**
				 * Set fonts in this.fonts
				 *
				 * @since 3.0.17
				 * @returns {null}
				 */
				setFonts: function() {
					var self = this;

					// No need to run if we already have the fonts.
					if ( ! _.isEmpty( self.fonts ) ) {
						return;
					}

					// Make an AJAX call to set the fonts object.
					jQuery.post( ajaxurl, { 'action': 'kirki_fonts_standard_all_get' }, function( response ) {

						// Get fonts from the JSON array.
						self.fonts = JSON.parse( response );
					} );
				},

				/**
				 * Gets the variants for a font-family.
				 *
				 * @since 3.0.17
				 * @returns {Array}
				 */
				getVariants: function() {
					return [ 'regular', 'italic', '700', '700italic' ];
				}
			},

			/**
			 * Figure out what this font-family is (google/standard)
			 *
			 * @since 3.0.20
			 * @param {string} family - The font-family.
			 * @returns {string|false} - Returns string if found (google|standard)
			 *                           and false in case the font-family is an arbitrary value
			 *                           not found anywhere in our font definitions.
			 */
			getFontType: function( family ) {
				var self = this;

				// Check for standard fonts first.
				if (
					'undefined' !== typeof self.standard.fonts[ family ] || (
						'undefined' !== typeof self.standard.fonts.stack &&
						'undefined' !== typeof self.standard.fonts.stack[ family ]
					)
				) {
					return 'standard';
				}

				// Check in googlefonts.
				if ( 'undefined' !== typeof self.google.fonts.items[ family ] ) {
					return 'google';
				}
				return false;
			},

			/**
			 * Gets all properties of a font-family.
			 *
			 * @since 4.2.5
			 * @param {string} family - The font-family we're interested in.
			 * @returns {Object}
			 */
			getFont: function( family ) {
				var self = this,
					fontType = self.getFontType(family);

				if ( !fontType) return false;

				if ( fontType == 'google') {
					return self.google.fonts.items[ family ];
				}
				else {
					return self.standard.fonts.stack[ family ];
				}
			}
		},

		validate: {
			cssValue: function( value ) {

				var validUnits = [ 'fr', 'rem', 'em', 'ex', '%', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ch', 'vh', 'vw', 'vmin', 'vmax' ],
					numericValue,
					unit;

				// Early exit if value is not a string or a number.
				if ( 'string' !== typeof value || 'number' !== typeof value ) {
					return true;
				}

				// Whitelist values.
				if ( 0 === value || '0' === value || 'auto' === value || 'inherit' === value || 'initial' === value ) {
					return true;
				}

				// Skip checking if calc().
				if ( 0 <= value.indexOf( 'calc(' ) && 0 <= value.indexOf( ')' ) ) {
					return true;
				}

				// Get the numeric value.
				numericValue = parseFloat( value );

				// Get the unit
				unit = value.replace( numericValue, '' );

				// Allow unitless.
				if ( ! value ) {
					return;
				}

				// Check the validity of the numeric value and units.
				return ( ! isNaN( numericValue ) && -1 < jQuery.inArray( unit, validUnits ) );
			}
		},

		/**
		 * Parses HTML Entities.
		 *
		 * @since 3.0.34
		 * @param {string} str - The string we want to parse.
		 * @returns {string}
		 */
		parseHtmlEntities: function( str ) {
			var parser = new DOMParser,
				dom    = parser.parseFromString(
					'<!doctype html><body>' + str, 'text/html'
				);

			return dom.body.textContent;
		}
	}
} );
/* global kirki */

// _.each( kirki.control, function( obj, type ) {
// 	wp.customize.controlConstructor[ type ] = wp.customize.kirkiDynamicControl.extend( {} );
// } );
// wp.customize.controlConstructor['kirki-image'] = wp.customize.ImageControl.extend({
//   initialize: function( id, options ) {
//     wp.customize.Control.prototype.initialize.call( this, id, options );
//     window.initializeControl(this, id, options);
//   },
// });
wp.customize.controlConstructor['kirki-image'] = wp.customize.controlConstructor.default.extend({
	/**
	 * Init the control.
	 *
	 * @since 3.0.34
	 * @param {Object} control - The customizer control object.
	 * @returns {null}
	 */
	ready: function() {
		var self = this;

		// Render the template.
		self.template( self );

		// Init the control.
		self.init( self );
	},

	/**
	 * Render the template.
	 *
	 * @since 3.0.34
	 * @param {Object}  control - The customizer control object.
	 * @param {Object}  control.params - The control parameters.
	 * @param {string}  control.params.label - The control label.
	 * @param {string}  control.params.description - The control description.
	 * @param {string}  control.params.inputAttrs - extra input arguments.
	 * @param {string}  control.params.default - The default value.
	 * @param {Object}  control.params.choices - Any extra choices we may need.
	 * @param {string}  control.id - The setting.
	 * @returns {null}
	 */
	template: function( control ) {
		var template = wp.template( 'kirki-input-image' );
		control.container.html(
			template( args = {
				label: control.params.label,
				description: control.params.description,
				'data-id': control.id,
				inputAttrs: control.params.inputAttrs,
				choices: control.params.choices,
				value: kirki.setting.get( control.setting.id )
			} )
		);
	},

	/**
	 * Init the control.
	 *
	 * @since 3.0.34
	 * @param {Object} control - The control object.
	 * @returns {null}
	 */
	init: function( control ) {
		var value         = kirki.setting.get( control.setting.id ),
			saveAs        = ( ! _.isUndefined( control.params.choices ) && ! _.isUndefined( control.params.choices.save_as ) ) ? control.params.choices.save_as : 'url',
			preview       = control.container.find( '.placeholder, .thumbnail' ),
			previewImage  = ( 'array' === saveAs ) ? value.url : value,
			removeButton  = control.container.find( '.image-upload-remove-button' ),
			defaultButton = control.container.find( '.image-default-button' );

		// Make sure value is properly formatted.
		value = ( 'array' === saveAs && _.isString( value ) ) ? { url: value } : value;

		// Tweaks for save_as = id.
		if ( ( 'id' === saveAs || 'ID' === saveAs ) && '' !== value ) {
			wp.media.attachment( value ).fetch().then( function() {
				setTimeout( function() {
					var url = wp.media.attachment( value ).get( 'url' );
					preview.removeClass().addClass( 'thumbnail thumbnail-image' ).html( '<img src="' + url + '" alt="" />' );
				}, 700 );
			} );
		}

		// If value is not empty, hide the "default" button.
		if ( ( 'url' === saveAs && '' !== value ) || ( 'array' === saveAs && ! _.isUndefined( value.url ) && '' !== value.url ) ) {
			control.container.find( 'image-default-button' ).hide();
		}

		// If value is empty, hide the "remove" button.
		if ( ( 'url' === saveAs && '' === value ) || ( 'array' === saveAs && ( _.isUndefined( value.url ) || '' === value.url ) ) ) {
			removeButton.hide();
		}

		// If value is default, hide the default button.
		if ( value === control.params.default ) {
			control.container.find( 'image-default-button' ).hide();
		}

		if ( '' !== previewImage ) {
			preview.removeClass().addClass( 'thumbnail thumbnail-image' ).html( '<img src="' + previewImage + '" alt="" />' );
		}

		control.container.on( 'click', '.image-upload-button', function( e ) {
			var image = wp.media( { multiple: false } ).open().on( 'select', function() {

				// This will return the selected image from the Media Uploader, the result is an object.
				var uploadedImage = image.state().get( 'selection' ).first(),
					jsonImg       = uploadedImage.toJSON(),
					previewImage  = jsonImg.url;

				if ( ! _.isUndefined( jsonImg.sizes ) ) {
					previewImage = jsonImg.sizes.full.url;
					if ( ! _.isUndefined( jsonImg.sizes.medium ) ) {
						previewImage = jsonImg.sizes.medium.url;
					} else if ( ! _.isUndefined( jsonImg.sizes.thumbnail ) ) {
						previewImage = jsonImg.sizes.thumbnail.url;
					}
				}

				if ( 'array' === saveAs ) {
					kirki.setting.set( control.id, {
						id: jsonImg.id,
						url: jsonImg.sizes.full.url,
						width: jsonImg.width,
						height: jsonImg.height
					} );
				} else if ( 'id' === saveAs ) {
					kirki.setting.set( control.id, jsonImg.id );
				} else {
					kirki.setting.set( control.id, ( ( ! _.isUndefined( jsonImg.sizes ) ) ? jsonImg.sizes.full.url : jsonImg.url ) );
				}

				if ( preview.length ) {
					preview.removeClass().addClass( 'thumbnail thumbnail-image' ).html( '<img src="' + previewImage + '" alt="" />' );
				}
				if ( removeButton.length ) {
					removeButton.show();
					defaultButton.hide();
				}
			} );

			e.preventDefault();
		} );

		control.container.on( 'click', '.image-upload-remove-button', function( e ) {

			var preview,
				removeButton,
				defaultButton;

			e.preventDefault();

			kirki.setting.set( control.id, '' );

			preview       = control.container.find( '.placeholder, .thumbnail' );
			removeButton  = control.container.find( '.image-upload-remove-button' );
			defaultButton = control.container.find( '.image-default-button' );

			if ( preview.length ) {
				preview.removeClass().addClass( 'placeholder' ).html( kirkiL10n.noFileSelected );
			}
			if ( removeButton.length ) {
				removeButton.hide();
				if ( jQuery( defaultButton ).hasClass( 'button' ) ) {
					defaultButton.show();
				}
			}
		} );

		control.container.on( 'click', '.image-default-button', function( e ) {

			var preview,
				removeButton,
				defaultButton;

			e.preventDefault();

			kirki.setting.set( control.id, control.params.default );

			preview       = control.container.find( '.placeholder, .thumbnail' );
			removeButton  = control.container.find( '.image-upload-remove-button' );
			defaultButton = control.container.find( '.image-default-button' );

			if ( preview.length ) {
				preview.removeClass().addClass( 'thumbnail thumbnail-image' ).html( '<img src="' + control.params.default + '" alt="" />' );
			}
			if ( removeButton.length ) {
				removeButton.show();
				defaultButton.hide();
			}
		} );
	}

});

wp.customize.controlConstructor['etheme-text'] = wp.customize.controlConstructor.default.extend({
	ready: function() {
		var control = this;

		this.container.on( 'change click keyup paste', 'input', function() {
			control.setting.set( jQuery( this ).val() );
		});
	}
});

wp.customize.controlConstructor['etheme-link'] = wp.customize.controlConstructor.default.extend({
	ready: function() {
		var control = this;

		this.container.on( 'change click keyup paste', 'input', function() {
			// console.log(jQuery( this ).val());
			control.setting.set( jQuery( this ).val() );
		});
	}
});

wp.customize.controlConstructor['jeg-textarea'] = wp.customize.controlConstructor.default.extend({
	ready: function() {
		var control = this;

		this.container.on( 'change click keyup paste', 'textarea', function() {
			control.setting.set( $( this ).val() );
		});
	}
});

wp.customize.controlConstructor['kirki-select'] = wp.customize.controlConstructor.default.extend({

	ready: function() {

		'use strict';

		var control = this;
		this.init(control);
		// Init the control.
		var element  = jQuery( 'select[data-id="' + control.id + '"]' ),
			multiple = parseInt( element.data( 'multiple' ), 10 ),
			selectValue,
			selectWooOptions = {
				escapeMarkup: function( markup ) {
					return markup;
				}
			};
			if ( control.params.placeholder ) {
				selectWooOptions.placeholder = control.params.placeholder;
				selectWooOptions.allowClear = true;
			}

		if ( 1 < multiple ) {
			selectWooOptions.maximumSelectionLength = multiple;
		}
		jQuery( element ).selectWoo( selectWooOptions ).on( 'change', function() {
			selectValue = jQuery( this ).val();
			selectValue = ( null === selectValue && 1 < multiple ) ? [] : selectValue;
			kirki.setting.set( control.id, selectValue );
		} );

	},

	init: function( control ) {
		var self = this;
		// Render the template.
		self.template( control );
	},

	template: function( control ) {
		var template = wp.template( 'kirki-input-select' );
		control.container.html( template( {
			label: control.params.label,
			description: control.params.description,
			'data-id': control.id,
			inputAttrs: control.params.inputAttrs,
			choices: control.params.choices,
			value: kirki.setting.get( control.setting.id ),
			multiple: control.params.multiple || 1,
			placeholder: control.params.placeholder
		} ) );
	}

});


/* global kirkiControlLoader */
wp.customize.controlConstructor['kirki-background'] = wp.customize.controlConstructor.default.extend( {

	// When we're finished loading continue processing
	ready: function() {

		'use strict';

		var control = this;
		// Init the control.
		if ( ! _.isUndefined( window.kirkiControlLoader ) && _.isFunction( kirkiControlLoader ) ) {
			kirkiControlLoader( control );
		} else {
			control.initKirkiControl();
		}
	},

	initKirkiControl: function() {

		var control = this,
			value   = control.setting._value,
			picker  = control.container.find( '.kirki-color-control' );

		// Hide unnecessary controls if the value doesn't have an image.
		if ( _.isUndefined( value['background-image'] ) || '' === value['background-image'] ) {
			control.container.find( '.background-wrapper > .background-repeat' ).hide();
			control.container.find( '.background-wrapper > .background-position' ).hide();
			control.container.find( '.background-wrapper > .background-size' ).hide();
			control.container.find( '.background-wrapper > .background-attachment' ).hide();
		}

		// Color.
		picker.wpColorPicker( {
			change: function() {
				setTimeout( function() {
					control.saveValue( 'background-color', picker.val() );
				}, 100 );
			}
		} );

		// Background-Repeat.
		control.container.on( 'change', '.background-repeat select', function() {
			control.saveValue( 'background-repeat', jQuery( this ).val() );
		} );

		// Background-Size.
		control.container.on( 'change click', '.background-size input', function() {
			control.saveValue( 'background-size', jQuery( this ).val() );
		} );

		// Background-Position.
		control.container.on( 'change', '.background-position select', function() {
			control.saveValue( 'background-position', jQuery( this ).val() );
		} );

		// Background-Attachment.
		control.container.on( 'change click', '.background-attachment input', function() {
			control.saveValue( 'background-attachment', jQuery( this ).val() );
		} );

		// Background-Image.
		control.container.on( 'click', '.background-image-upload-button', function( e ) {
			var image = wp.media( { multiple: false } ).open().on( 'select', function() {

				// This will return the selected image from the Media Uploader, the result is an object.
				var uploadedImage = image.state().get( 'selection' ).first(),
					previewImage   = uploadedImage.toJSON().sizes.full.url,
					imageUrl,
					imageID,
					imageWidth,
					imageHeight,
					preview,
					removeButton;

				if ( ! _.isUndefined( uploadedImage.toJSON().sizes.medium ) ) {
					previewImage = uploadedImage.toJSON().sizes.medium.url;
				} else if ( ! _.isUndefined( uploadedImage.toJSON().sizes.thumbnail ) ) {
					previewImage = uploadedImage.toJSON().sizes.thumbnail.url;
				}

				imageUrl    = uploadedImage.toJSON().sizes.full.url;
				imageID     = uploadedImage.toJSON().id;
				imageWidth  = uploadedImage.toJSON().width;
				imageHeight = uploadedImage.toJSON().height;

				// Show extra controls if the value has an image.
				if ( '' !== imageUrl ) {
					control.container.find( '.background-wrapper > .background-repeat, .background-wrapper > .background-position, .background-wrapper > .background-size, .background-wrapper > .background-attachment' ).show();
				}

				control.saveValue( 'background-image', imageUrl );
				preview      = control.container.find( '.placeholder, .thumbnail' );
				removeButton = control.container.find( '.background-image-upload-remove-button' );

				if ( preview.length ) {
					preview.removeClass().addClass( 'thumbnail thumbnail-image' ).html( '<img src="' + previewImage + '" alt="" />' );
				}
				if ( removeButton.length ) {
					removeButton.show();
				}
			} );

			e.preventDefault();
		} );

		control.container.on( 'click', '.background-image-upload-remove-button', function( e ) {

			var preview,
				removeButton;

			e.preventDefault();

			control.saveValue( 'background-image', '' );

			preview      = control.container.find( '.placeholder, .thumbnail' );
			removeButton = control.container.find( '.background-image-upload-remove-button' );

			// Hide unnecessary controls.
			control.container.find( '.background-wrapper > .background-repeat' ).hide();
			control.container.find( '.background-wrapper > .background-position' ).hide();
			control.container.find( '.background-wrapper > .background-size' ).hide();
			control.container.find( '.background-wrapper > .background-attachment' ).hide();

			if ( preview.length ) {
				preview.removeClass().addClass( 'placeholder' ).html( 'No file selected' );
			}
			if ( removeButton.length ) {
				removeButton.hide();
			}
		} );
	},

	/**
	 * Saves the value.
	 */
	saveValue: function( property, value ) {

		var control = this,
			input   = jQuery( '#customize-control-' + control.id.replace( '[', '-' ).replace( ']', '' ) + ' .background-hidden-value' ),
			val     = control.setting._value;

		val[ property ] = value;

		jQuery( input ).attr( 'value', JSON.stringify( val ) ).trigger( 'change' );
		control.setting.set( val );
	}
} );
wp.customize.controlConstructor['kirki-color-palette'] = wp.customize.kirkiDynamicControl.extend( {} );
wp.customize.controlConstructor['kirki-dashicons'] = wp.customize.kirkiDynamicControl.extend( {} );
wp.customize.controlConstructor['kirki-date'] = wp.customize.kirkiDynamicControl.extend( {

	initKirkiControl: function() {
		var control  = this,
			selector = control.selector + ' input.datepicker';

		// Init the datepicker
		jQuery( selector ).datepicker( {
			dateFormat: 'yy-mm-dd'
		} );

		// Save the changes
		this.container.on( 'change keyup paste', 'input.datepicker', function() {
			control.setting.set( jQuery( this ).val() );
		} );
	}
} );
/* global dimensionkirkiL10n */
wp.customize.controlConstructor['kirki-dimension'] = wp.customize.kirkiDynamicControl.extend( {

	initKirkiControl: function() {

		var control = this,
			value;

		// Notifications.
		control.kirkiNotifications();

		// Save the value
		this.container.on( 'change keyup paste', 'input', function() {

			value = jQuery( this ).val();
			control.setting.set( value );
		} );
	},

	/**
	 * Handles notifications.
	 */
	kirkiNotifications: function() {

		var control        = this,
			acceptUnitless = ( 'undefined' !== typeof control.params.choices && 'undefined' !== typeof control.params.choices.accept_unitless && true === control.params.choices.accept_unitless );

		wp.customize( control.id, function( setting ) {
			setting.bind( function( value ) {
				var code = 'long_title';

				if ( false === kirki.util.validate.cssValue( value ) && ( ! acceptUnitless || isNaN( value ) ) ) {
					setting.notifications.add( code, new wp.customize.Notification(
						code,
						{
							type: 'warning',
							message: dimensionkirkiL10n['invalid-value']
						}
					) );
				} else {
					setting.notifications.remove( code );
				}
			} );
		} );
	}
} );
/* global dimensionskirkiL10n */
wp.customize.controlConstructor['kirki-dimensions'] = wp.customize.kirkiDynamicControl.extend( {

	initKirkiControl: function() {

		var control     = this,
			subControls = control.params.choices.controls,
			value       = {},
			subsArray   = [],
			i;

		_.each( subControls, function( v, i ) {
			if ( true === v ) {
				subsArray.push( i );
			}
		} );

		for ( i = 0; i < subsArray.length; i++ ) {
			value[ subsArray[ i ] ] = control.setting._value[ subsArray[ i ] ];
			control.updateDimensionsValue( subsArray[ i ], value );
		}
	},

	/**
	 * Updates the value.
	 */
	updateDimensionsValue: function( context, value ) {

		var control = this;

		control.container.on( 'change keyup paste', '.' + context + ' input', function() {
			value[ context ] = jQuery( this ).val();

			// Notifications.
			control.kirkiNotifications();

			// Save the value
			control.saveValue( value );
		} );
	},

	/**
	 * Saves the value.
	 */
	saveValue: function( value ) {

		var control  = this,
			newValue = {};

		_.each( value, function( newSubValue, i ) {
			newValue[ i ] = newSubValue;
		} );

		control.setting.set( newValue );
	},

	/**
	 * Handles notifications.
	 */
	kirkiNotifications: function() {

		var control = this;

		wp.customize( control.id, function( setting ) {
			setting.bind( function( value ) {
				var code = 'long_title',
					subs = {},
					message;

				setting.notifications.remove( code );

				_.each( value, function( val, direction ) {
					if ( false === kirki.util.validate.cssValue( val ) ) {
						subs[ direction ] = val;
					} else {
						delete subs[ direction ];
					}
				} );

				if ( ! _.isEmpty( subs ) ) {
					message = dimensionskirkiL10n['invalid-value'] + ' (' + _.values( subs ).toString() + ') ';
					setting.notifications.add( code, new wp.customize.Notification( code, {
						type: 'warning',
						message: message
					} ) );
					return;
				}
				setting.notifications.remove( code );
			} );
		} );
	}
} );
/* global tinyMCE */
wp.customize.controlConstructor['kirki-editor'] = wp.customize.controlConstructor.default.extend( {
	ready: function() {
		var control = this,
			element = control.container.find( 'textarea' ),
			id      = 'kirki-editor-' + control.id.replace( '[', '' ).replace( ']', '' ),
			editor;

		if ( wp.editor && wp.editor.initialize ) {
			wp.editor.initialize( id, {
				tinymce: {
					wpautop: true
				},
				quicktags: true,
				mediaButtons: true
			} );
		}

		editor = tinyMCE.get( id );

		if ( editor ) {
			// fix active mode by default because in visual mode is bugged
			setTimeout(function () {
				jQuery('#wp-' + id + '-wrap').find('.switch-html').trigger('click');
			});
			editor.onChange.add( function( ed ) {
				var content;

				ed.save();
				content = editor.getContent();
				element.val( content ).trigger( 'change' );
				wp.customize.instance( control.setting.id ).set( content );
			} );
		}
	}
} );
wp.customize.controlConstructor['kirki-multicheck'] = wp.customize.kirkiDynamicControl.extend( {

	initKirkiControl: function() {

		var control = this;

		// Save the value
		control.container.on( 'change', 'input', function() {
			var value = [],
				i = 0;

			// Build the value as an object using the sub-values from individual checkboxes.
			jQuery.each( control.params.choices, function( key ) {
				if ( control.container.find( 'input[value="' + key + '"]' ).is( ':checked' ) ) {
					control.container.find( 'input[value="' + key + '"]' ).parent().addClass( 'checked' );
					value[ i ] = key;
					i++;
				} else {
					control.container.find( 'input[value="' + key + '"]' ).parent().removeClass( 'checked' );
				}
			} );

			// Update the value in the customizer.
			control.setting.set( value );
		} );
	}
} );
/* global kirkiControlLoader */
wp.customize.controlConstructor['kirki-multicolor'] = wp.customize.controlConstructor.default.extend( {

	// When we're finished loading continue processing
	ready: function() {

		'use strict';

		var control = this;

		// Init the control.
		if ( ! _.isUndefined( window.kirkiControlLoader ) && _.isFunction( kirkiControlLoader ) ) {
			kirkiControlLoader( control );
		} else {
			control.initKirkiControl();
		}
	},

	initKirkiControl: function() {

		'use strict';

		var control = this,
			colors  = control.params.choices,
			keys    = Object.keys( colors ),
			value   = this.params.value,
			i       = 0;

		// Proxy function that handles changing the individual colors
		function kirkiMulticolorChangeHandler( control, value, subSetting ) {

			var picker = control.container.find( '.multicolor-index-' + subSetting ),
				args   = {
					change: function() {

						// Color controls require a small delay.
						setTimeout( function() {

							// Set the value.
							control.saveValue( subSetting, picker.val() );

							// Trigger the change.
							control.container.find( '.multicolor-index-' + subSetting ).trigger( 'change' );
						}, 100 );
					}
				};

			if ( _.isObject( colors.irisArgs ) ) {
				_.each( colors.irisArgs, function( irisValue, irisKey ) {
					args[ irisKey ] = irisValue;
				} );
			}

			// Did we change the value?
			picker.wpColorPicker( args );
		}

		// Colors loop
		while ( i < Object.keys( colors ).length ) {
			kirkiMulticolorChangeHandler( this, value, keys[ i ] );
			i++;
		}
	},

	/**
	 * Saves the value.
	 */
	saveValue: function( property, value ) {

		var control = this,
			input   = control.container.find( '.multicolor-hidden-value' ),
			val     = control.setting._value;

		val[ property ] = value;

		jQuery( input ).attr( 'value', JSON.stringify( val ) ).trigger( 'change' );
		control.setting.set( val );
	}
} );
wp.customize.controlConstructor['kirki-palette'] = wp.customize.kirkiDynamicControl.extend( {} );
wp.customize.controlConstructor['kirki-radio-buttonset'] = wp.customize.controlConstructor.default.extend( {
	ready: function() {
		var control = this;

		// Change the value
		this.container.on( 'click', 'input', function() {
			control.setting.set( jQuery( this ).val() );
		});
	}
} );
wp.customize.controlConstructor['kirki-radio-image'] = wp.customize.controlConstructor.default.extend( {
	ready: function() {
		var control = this;
		// Change the value
		this.container.on( 'click', 'input', function() {
			control.setting.set( jQuery( this ).val() );
		});
	}
} );

wp.customize.controlConstructor['code_editor'] = wp.customize.CodeEditorControl.extend( {
	initialize: function( id, options ) {
		var control = this;
		control.deferred = _.extend( control.deferred || {}, {
			codemirror: jQuery.Deferred()
		} );

		wp.customize.Control.prototype.initialize.call( control, id, options );
		window.initializeControl(this, id, options);
	}
} );

wp.customize.controlConstructor['kirki-slider'] = wp.customize.kirkiDynamicControl.extend( {

	initKirkiControl: function() {
		var control      = this,
			changeAction = ( 'postMessage' === control.setting.transport ) ? 'mousemove change' : 'change',
			rangeInput   = control.container.find( 'input[type="range"]' ),
			textInput    = control.container.find( 'input[type="text"]' ),
			value        = control.setting._value;

		// Set the initial value in the text input.
		textInput.attr( 'value', value );

		// If the range input value changes copy the value to the text input.
		rangeInput.on( 'mousemove change', function() {
			textInput.attr( 'value', rangeInput.val() );
		} );

		// Save the value when the range input value changes.
		// This is separate from the above because of the postMessage differences.
		// If the control refreshes the preview pane,
		// we don't want a refresh for every change
		// but 1 final refresh when the value is changed.
		rangeInput.on( changeAction, function() {
			control.setting.set( rangeInput.val() );
		} );

		// If the text input value changes,
		// copy the value to the range input
		// and then save.
		textInput.on( 'input paste change', function() {
			rangeInput.attr( 'value', textInput.val() );
			control.setting.set( textInput.val() );
		} );

		// If the reset button is clicked,
		// set slider and text input values to default
		// and hen save.
		control.container.find( '.slider-reset' ).on( 'click', function() {
			textInput.attr( 'value', control.params.default );
			rangeInput.attr( 'value', control.params.default );
			control.setting.set( textInput.val() );
		} );
	}
} );
wp.customize.controlConstructor['kirki-sortable'] = wp.customize.controlConstructor.default.extend( {

	// When we're finished loading continue processing
	ready: function() {

		'use strict';

		var control = this;

		// Init sortable.
		jQuery( control.container.find( 'ul.sortable' ).first() ).sortable( {

			// Update value when we stop sorting.
			update: function() {
				control.setting.set( control.getNewVal() );
			}
		} ).disableSelection().find( 'li' ).each( function() {

			// Enable/disable options when we click on the eye of Thundera.
			jQuery( this ).find( 'i.visibility' ).click( function() {
				jQuery( this ).toggleClass( 'dashicons-visibility-faint' ).parents( 'li:eq(0)' ).toggleClass( 'invisible' );
			} );
		} ).click( function() {

			// Update value on click.
			control.setting.set( control.getNewVal() );
		} );
	},

	/**
	 * Getss thhe new vvalue.
	 *
	 * @since 3.0.35
	 * @returns {Array}
	 */
	getNewVal: function() {
		var items  = jQuery( this.container.find( 'li' ) ),
			newVal = [];
		_.each ( items, function( item ) {
			if ( ! jQuery( item ).hasClass( 'invisible' ) ) {
				newVal.push( jQuery( item ).data( 'value' ) );
			}
		} );
		return newVal;
	}
} );
wp.customize.controlConstructor['kirki-switch'] = wp.customize.kirkiDynamicControl.extend( {

	initKirkiControl: function() {

		'use strict';

		var control       = this,
			checkboxValue = control.setting._value;

		// Save the value
		this.container.on( 'change', 'input', function() {
			checkboxValue = ( jQuery( this ).is( ':checked' ) ) ? true : false;
			control.setting.set( checkboxValue );
		} );
	}
} );
wp.customize.controlConstructor['kirki-toggle'] = wp.customize.kirkiDynamicControl.extend( {

	ready: function() {

		var control = this,
			checkboxValue = control.setting._value;

		// Save the value
		this.container.on( 'change', 'input', function() {
			checkboxValue = ( jQuery( this ).is( ':checked' ) ) ? true : false;
			control.setting.set( checkboxValue );
		} );
	}
} );
wp.customize.controlConstructor['kirki-color'] = wp.customize.kirkiDynamicControl.extend( {

	ready: function() {
		'use strict';

		var control = this;
		this.init(control);

		var picker  = control.container.find( '.kirki-color-control');

		if ( undefined !== control.params.choices ) {
			picker.wpColorPicker( control.params.choices );
		} else {
			jQuery( picker ).wpColorPicker();
		}

		control.container.find('.wp-picker-clear').on('click', function(){
			setTimeout( function() {
				control.setting.set( picker.val() );
			}, 100 );
		});

		picker.wpColorPicker({
			change: function(){
				setTimeout( function() {
					control.setting.set( picker.val() );
				}, 100 );
			}
		});

	},

	/**
	 * Init the control.
	 *
	 * @since 3.0.16
	 * @param {Object} control - The customizer control object.
	 * @returns {null}
	 */
	init: function( control ) {
		var self = this;

		// Render the template.
		self.template( control );

	},

	/*
	 * Render the template.
	 *
	 * @since 3.0.16
	 * @param {Object}     control - The customizer control object.
	 * @param {Object}     control.params - The control parameters.
	 * @param {string}     control.params.label - The control label.
	 * @param {string}     control.params.description - The control description.
	 * @param {string}     control.params.mode - The colorpicker mode. Can be 'full' or 'hue'.
	 * @param {bool|array} control.params.palette - false if we don't want a palette,
	 *                                              true to use the default palette,
	 *                                              array of custom hex colors if we want a custom palette.
	 * @param {string}     control.params.inputAttrs - extra input arguments.
	 * @param {string}     control.params.default - The default value.
	 * @param {Object}     control.params.choices - Any extra choices we may need.
	 * @param {boolean}    control.params.choices.alpha - should we add an alpha channel?
	 * @param {string}     control.id - The setting.
	 * @returns {null}
	 */
	template: function( control ) {
		var template = wp.template( 'kirki-input-color' );
		control.container.html( template( {
			label: control.params.label,
			description: control.params.description,
			'data-id': control.id,
			mode: control.params.mode,
			inputAttrs: control.params.inputAttrs,
			'data-palette': control.params.palette,
			'data-default-color': control.params.default,
			'data-alpha': control.params.choices.alpha,
			value: kirki.setting.get( control.setting.id )
		} ) );
	}

} );
wp.customize.controlConstructor['kirki-typography'] = wp.customize.controlConstructor.default.extend( {

	ready: function() {

		'use strict';

		var control = this,
			value   = control.setting._value,
			picker;

		control.renderFontSelector();
		control.renderBackupFontSelector();
		control.renderVariantSelector();

		// Font-size.
		if ( 'undefined' !== typeof control.params.default['font-size'] ) {
			this.container.on( 'change keyup paste', '.font-size input', function() {
				control.saveValue( 'font-size', jQuery( this ).val() );
			} );
		}

		// Line-height.
		if ( 'undefined' !== typeof control.params.default['line-height'] ) {
			this.container.on( 'change keyup paste', '.line-height input', function() {
				control.saveValue( 'line-height', jQuery( this ).val() );
			} );
		}

		// Margin-top.
		if ( 'undefined' !== typeof control.params.default['margin-top'] ) {
			this.container.on( 'change keyup paste', '.margin-top input', function() {
				control.saveValue( 'margin-top', jQuery( this ).val() );
			} );
		}

		// Margin-bottom.
		if ( 'undefined' !== typeof control.params.default['margin-bottom'] ) {
			this.container.on( 'change keyup paste', '.margin-bottom input', function() {
				control.saveValue( 'margin-bottom', jQuery( this ).val() );
			} );
		}

		// Letter-spacing.
		if ( 'undefined' !== typeof control.params.default['letter-spacing'] ) {
			value['letter-spacing'] = ( jQuery.isNumeric( value['letter-spacing'] ) ) ? value['letter-spacing'] + 'px' : value['letter-spacing'];
			this.container.on( 'change keyup paste', '.letter-spacing input', function() {
				value['letter-spacing'] = ( jQuery.isNumeric( jQuery( this ).val() ) ) ? jQuery( this ).val() + 'px' : jQuery( this ).val();
				control.saveValue( 'letter-spacing', value['letter-spacing'] );
			} );
		}

		// Word-spacing.
		if ( 'undefined' !== typeof control.params.default['word-spacing'] ) {
			this.container.on( 'change keyup paste', '.word-spacing input', function() {
				control.saveValue( 'word-spacing', jQuery( this ).val() );
			} );
		}

		// Text-align.
		if ( 'undefined' !== typeof control.params.default['text-align'] ) {
			this.container.on( 'change', '.text-align input', function() {
				control.saveValue( 'text-align', jQuery( this ).val() );
			} );
		}

		// Text-transform.
		if ( 'undefined' !== typeof control.params.default['text-transform'] ) {
			jQuery( control.selector + ' .text-transform select' ).selectWoo().on( 'change', function() {
				control.saveValue( 'text-transform', jQuery( this ).val() );
			} );
		}

		// Text-decoration.
		if ( 'undefined' !== typeof control.params.default['text-decoration'] ) {
			jQuery( control.selector + ' .text-decoration select' ).selectWoo().on( 'change', function() {
				control.saveValue( 'text-decoration', jQuery( this ).val() );
			} );
		}

		// Color.
		if ( 'undefined' !== typeof control.params.default.color ) {
			picker = this.container.find( '.kirki-color-control' );
			picker.wpColorPicker( {
				change: function() {
					setTimeout( function() {
						control.saveValue( 'color', picker.val() );
					}, 100 );
				}
			} );
		}
	},

	/**
	 * Adds the font-families to the font-family dropdown
	 * and instantiates selectWoo.
	 */
	renderFontSelector: function() {

		var control         = this,
			selector        = control.selector + ' .font-family select',
			data            = [],
			standardFonts   = [],
			googleFonts     = [],
			value           = control.setting._value,
			fonts           = control.getFonts(),
			fontSelect,
			controlFontFamilies;

		// Format standard fonts as an array.
		if ( ! _.isUndefined( fonts.standard ) ) {
			_.each( fonts.standard, function( font ) {
				standardFonts.push( {
					id: font.family.replace( /&quot;/g, '&#39' ),
					text: font.label
				} );
			} );
		}

		// Format google fonts as an array.
		if ( ! _.isUndefined( fonts.google ) ) {
			_.each( fonts.google, function( font ) {
				let postfix = (font.category == 'serif' || font.category == 'sans-serif') ? ', ' + font.category : '';
				googleFonts.push( {
					id: font.family,
					text: font.family + postfix,
				} );
			} );
		}

		// Do we have custom fonts?
		controlFontFamilies = {};
		if ( ! _.isUndefined( control.params ) && ! _.isUndefined( control.params.choices ) && ! _.isUndefined( control.params.choices.fonts ) && ! _.isUndefined( control.params.choices.fonts.families ) ) {
			controlFontFamilies = control.params.choices.fonts.families;
		}

		// Combine forces and build the final data.
		data = jQuery.extend( {}, controlFontFamilies, {
			default: {
				text: kirkiL10n.defaultCSSValues,
				children: [
					{ id: '', text: kirkiL10n.defaultBrowserFamily },
					{ id: 'initial', text: 'initial' },
					{ id: 'inherit', text: 'inherit' }
				]
			},
			standard: {
				text: kirkiL10n.standardFonts,
				children: standardFonts
			},
			google: {
				text: kirkiL10n.googleFonts,
				children: googleFonts
			}
		} );

		if ( kirkiL10n.isScriptDebug ) {
			console.info( 'Kirki Debug: Font families for control "' + control.id + '":' );
			console.info( data );
		}

		data = _.values( data );

		// Instantiate selectWoo with the data.
		fontSelect = jQuery( selector ).selectWoo( {
			data: data
		} );

		// Set the initial value.
		if ( value['font-family'] || '' === value['font-family'] ) {
			value['font-family'] = kirki.util.parseHtmlEntities( value['font-family'].replace( /'/g, '"' ) );
			fontSelect.val( value['font-family'] ).trigger( 'change' );
		}

		// When the value changes
		fontSelect.on( 'change', function() {

			// Set the value.
			control.saveValue( 'font-family', jQuery( this ).val() );

			// Re-init the font-backup selector.
			control.renderBackupFontSelector();

			// Re-init variants selector.
			control.renderVariantSelector();
		} );
	},

	/**
	 * Adds the font-families to the font-family dropdown
	 * and instantiates selectWoo.
	 */
	renderBackupFontSelector: function() {

		var control       = this,
			selector      = control.selector + ' .font-backup select',
			standardFonts = [],
			value         = control.setting._value,
			fontFamily    = value['font-family'],
			fonts         = control.getFonts(),
			fontSelect;

		if ( _.isUndefined( value['font-backup'] ) || null === value['font-backup'] ) {
			value['font-backup'] = '';
		}

		// Hide if we're not on a google-font.
		if ( 'inherit' === fontFamily || 'initial' === fontFamily || 'google' !== kirki.util.webfonts.getFontType( fontFamily ) ) {
			jQuery( control.selector + ' .font-backup' ).hide();
			return;
		}
		jQuery( control.selector + ' .font-backup' ).show();

		// Format standard fonts as an array.
		if ( ! _.isUndefined( fonts.standard ) ) {
			_.each( fonts.standard, function( font ) {
				standardFonts.push( {
					id: font.family.replace( /&quot;/g, '&#39' ),
					text: font.label
				} );
			} );
		}

		// Instantiate selectWoo with the data.
		fontSelect = jQuery( selector ).selectWoo( {
			data: standardFonts
		} );

		// Set the initial value.
		if ( 'undefined' !== typeof value['font-backup'] ) {
			fontSelect.val( value['font-backup'].replace( /'/g, '"' ) ).trigger( 'change' );
		}

		// When the value changes
		fontSelect.on( 'change', function() {

			// Set the value.
			control.saveValue( 'font-backup', jQuery( this ).val() );
		} );
	},

	/**
	 * Renders the variants selector using selectWoo
	 * Displays font-variants for the currently selected font-family.
	 */
	renderVariantSelector: function() {

		var control    = this,
			value      = control.setting._value,
			fontFamily = value['font-family'],
			selector   = control.selector + ' .variant select',
			data       = [],
			isValid    = false,
			fontType   = kirki.util.webfonts.getFontType( fontFamily ),
			variants   = [ '', 'regular', 'italic', '700', '700italic' ],
			fontWeight,
			variantSelector,
			fontStyle;

		if ( 'google' === fontType ) {
			variants = kirki.util.webfonts.google.getVariants( fontFamily );
		}

		// Check if we've got custom variants defined for this font.
		if ( ! _.isUndefined( control.params ) && ! _.isUndefined( control.params.choices ) && ! _.isUndefined( control.params.choices.fonts ) && ! _.isUndefined( control.params.choices.fonts.variants ) ) {

			// Check if we have variants for this font-family.
			if ( ! _.isUndefined( control.params.choices.fonts.variants[ fontFamily ] ) ) {
				variants = control.params.choices.fonts.variants[ fontFamily ];
			}
		}
		if ( kirkiL10n.isScriptDebug ) {
			console.info( 'Kirki Debug: Font variants for font-family "' + fontFamily + '":' );
			console.info( variants );
		}

		if ( 'inherit' === fontFamily || 'initial' === fontFamily || '' === fontFamily ) {
			value.variant = 'inherit';
			variants      = [ '' ];
			jQuery( control.selector + ' .variant' ).hide();
		}

		if ( 1 >= variants.length ) {
			jQuery( control.selector + ' .variant' ).hide();

			value.variant = variants[0];

			control.saveValue( 'variant', value.variant );

			if ( '' === value.variant || ! value.variant ) {
				fontWeight = '';
				fontStyle  = '';
			} else {
				fontWeight = ( ! _.isString( value.variant ) ) ? '400' : value.variant.match( /\d/g );
				fontWeight = ( ! _.isObject( fontWeight ) ) ? '400' : fontWeight.join( '' );
				fontStyle  = ( value.variant && -1 !== value.variant.indexOf( 'italic' ) ) ? 'italic' : 'normal';
			}

			control.saveValue( 'font-weight', fontWeight );
			control.saveValue( 'font-style', fontStyle );

			return;
		}

		jQuery( control.selector + ' .font-backup' ).show();

		jQuery( control.selector + ' .variant' ).show();
		_.each( variants, function( variant ) {
			if ( value.variant === variant ) {
				isValid = true;
			}
			data.push( {
				id: variant,
				text: variant
			} );
		} );
		if ( ! isValid ) {
			value.variant = 'regular';
		}

		if ( jQuery( selector ).hasClass( 'select2-hidden-accessible' ) ) {
			jQuery( selector ).selectWoo( 'destroy' );
			jQuery( selector ).empty();
		}

		// Instantiate selectWoo with the data.
		variantSelector = jQuery( selector ).selectWoo( {
			data: data
		} );
		variantSelector.val( value.variant ).trigger( 'change' );
		variantSelector.on( 'change', function() {
			control.saveValue( 'variant', jQuery( this ).val() );
			if ( 'string' !== typeof value.variant ) {
				value.variant = variants[0];
			}

			fontWeight = ( ! _.isString( value.variant ) ) ? '400' : value.variant.match( /\d/g );
			fontWeight = ( ! _.isObject( fontWeight ) ) ? '400' : fontWeight.join( '' );
			fontStyle  = ( -1 !== value.variant.indexOf( 'italic' ) ) ? 'italic' : 'normal';

			control.saveValue( 'font-weight', fontWeight );
			control.saveValue( 'font-style', fontStyle );
		} );
	},

	/**
	 * Get fonts.
	 */
	getFonts: function() {
		var control            = this,
			initialGoogleFonts = kirki.util.webfonts.google.getFonts(),
			googleFonts        = {},
			googleFontsSort    = 'alpha',
			googleFontsNumber  = 0,
			standardFonts      = {};

		// Get google fonts.
		if ( ! _.isEmpty( control.params.choices.fonts.google ) ) {
			if ( 'alpha' === control.params.choices.fonts.google[0] || 'popularity' === control.params.choices.fonts.google[0] || 'trending' === control.params.choices.fonts.google[0] ) {
				googleFontsSort = control.params.choices.fonts.google[0];
				if ( ! isNaN( control.params.choices.fonts.google[1] ) ) {
					googleFontsNumber = parseInt( control.params.choices.fonts.google[1], 10 );
				}
				googleFonts = kirki.util.webfonts.google.getFonts( googleFontsSort, '', googleFontsNumber );

			} else {
				_.each( control.params.choices.fonts.google, function( fontName ) {
					if ( 'undefined' !== typeof initialGoogleFonts[ fontName ] && ! _.isEmpty( initialGoogleFonts[ fontName ] ) ) {
						googleFonts[ fontName ] = initialGoogleFonts[ fontName ];
					}
				} );
			}
		} else {
			googleFonts = kirki.util.webfonts.google.getFonts( googleFontsSort, '', googleFontsNumber );
		}

		// Get standard fonts.
		if ( ! _.isEmpty( control.params.choices.fonts.standard ) ) {
			_.each( control.params.choices.fonts.standard, function( fontName ) {
				if ( 'undefined' !== typeof kirki.util.webfonts.standard.fonts[ fontName ] && ! _.isEmpty( kirki.util.webfonts.standard.fonts[ fontName ] ) ) {
					standardFonts[ fontName ] = {};
					if ( 'undefined' !== kirki.util.webfonts.standard.fonts[ fontName ].stack && ! _.isEmpty( kirki.util.webfonts.standard.fonts[ fontName ].stack ) ) {
						standardFonts[ fontName ].family = kirki.util.webfonts.standard.fonts[ fontName ].stack;
					} else {
						standardFonts[ fontName ].family = googleFonts[ fontName ];
					}
					if ( 'undefined' !== kirki.util.webfonts.standard.fonts[ fontName ].label && ! _.isEmpty( kirki.util.webfonts.standard.fonts[ fontName ].label ) ) {
						standardFonts[ fontName ].label = kirki.util.webfonts.standard.fonts[ fontName ].label;
					} else if ( ! _.isEmpty( standardFonts[ fontName ] ) ) {
						standardFonts[ fontName ].label = standardFonts[ fontName ];
					}
				} else {
					standardFonts[ fontName ] = {
						family: fontName,
						label: fontName
					};
				}
			} );
		} else {
			_.each( kirki.util.webfonts.standard.fonts, function( font, id ) {
				standardFonts[ id ] = {
					family: font.stack,
					label: font.label
				};
			} );
		}
		return {
			google: googleFonts,
			standard: standardFonts
		};
	},

	/**
	 * Saves the value.
	 */
	saveValue: function( property, value ) {

		var control = this,
			input   = control.container.find( '.typography-hidden-value' ),
			val     = control.setting._value;

		val[ property ] = value;

		jQuery( input ).attr( 'value', JSON.stringify( val ) ).trigger( 'change' );
		control.setting.set( val );
	}
} );


wp.customize.controlConstructor['kirki-box-model'] = wp.customize.controlConstructor.default.extend( {
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
} );

wp.customize.controlConstructor['kirki-box-shadow'] = wp.customize.controlConstructor.default.extend({

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
var wcagColors = wcagColors || {};
wcagColors = {
	colors: [],

	/**
	 * Get all relative colors.
	 *
	 * @param {Object} params - The parameters for the colors.
	 * @param {string} params.color - A color formatted as RGB, HSL or HEX.
	 * @param {int}    params.hue - If a color is not provided, we can alternatively provide a hue.
	 * @param {float}  params.minSaturation - The minimum saturation the returned colors can have (0-1).
	 * @param {float}  params.maxSaturation - The maximum saturation the returned colors can have (0-1).
	 * @param {float}  params.stepSaturation - The increments in saturation while searching for colors (0-1).
	 * @param {float}  params.stepLightness - Each lightness step. Smaller numbers are more detailed but slower.
	 * @param {int}    params.minHueDiff - The minimum hue difference (0-359).
	 * @param {int}    params.maxHueDiff - The maximum hue difference (0-359).
	 * @param {int}    params.stepHue - How many degrees to turn the colorwheel on each iteration.
	 * @returns {Object} - this
	 */
	getAll( params ) {
		var allColors = [],
			hueOffset,
			hueUp,
			hueDown,
			newColors,
			i;

		// If we got a string color and not a hue make sure we get the hue.
		if ( ! params.hue && params.color ) {
			params.hue = this.getColorProperties( params.color ).h;
		}

		params.minSaturation  = params.minSaturation || 0;
		params.maxSaturation  = params.maxSaturation || 1;
		params.stepSaturation = params.stepSaturation || 0.1;
		params.stepLightness  = params.stepLightness || 0.1;
		params.minHueDiff     = params.minHueDiff || 0;
		params.maxHueDiff     = params.maxHueDiff || 360;
		params.stepHue        = params.stepHue || 15;

		if ( 0 === params.maxHueDiff ) {
			return this.getAllColorsForHue( params.hue, params );
		}

		for ( hueOffset = params.minHueDiff; hueOffset <= params.maxHueDiff; hueOffset += params.stepHue ) {

			// Calculate Hue Up.
			hueUp = ( 359 < params.hue + hueOffset ) ? params.hue + hueOffset - 359 : params.hue + hueOffset;

			// Calculate Hue Down.
			hueDown = ( 0 > params.hue + hueOffset ) ? params.hue + hueOffset + 360 : params.hue + hueOffset;

			// Add colors from the UP hue.
			newColors = this.getAllColorsForHue( hueUp, params );
			for ( i = 0; i < newColors.length; i++ ) {
				if ( -1 === allColors.indexOf( newColors[ i ] ) ) {
					allColors.push( newColors[ i ] );
				}
			}

			// Add colors from the DOWN hue.
			newColors = this.getAllColorsForHue( hueDown, params );
			for ( i = 0; i < newColors.length; i++ ) {
				if ( -1 === allColors.indexOf( newColors[ i ] ) ) {
					allColors.push( newColors[ i ] );
				}
			}
		}

		return Object.assign({}, this, {
			colors: this.removeDuplicateColors( allColors )
		});
	},

	/**
	 * Get an array of colors that fulfil the provided criteria.
	 *
	 * @param {Object}        criteria- The provided criteria.
	 * @param {string|Object} criteria.color - The color we want to check against.
	 * @param {int}           criteria.minHueDiff - Minimum hue difference required.
	 * @param {int}           criteria.maxHueDiff - Maximum hue difference required.
	 * @param {int}           criteria.minContrast - The minimum contrast required to pass.
	 * @returns {Object} - this
	 */
	pluck( criteria ) {
		var validColors = [],
			pass,
			i;

		if ( 'string' === typeof criteria.color ) {
			criteria.color = this.getColorProperties( criteria.color );
		} else if ( criteria.color.r && criteria.color.g && criteria.color.b ) {
			criteria.color = this.getColorProperties( 'rgb(' + criteria.color.r + ',' + criteria.color.g + ',' + criteria.color.b + ')' );
		} else if ( criteria.color.h && criteria.color.s && criteria.color.l ) {
			criteria.color = this.getColorProperties( 'hsl(' + criteria.color.h + ',' + criteria.color.s + ',' + criteria.color.l + ')' );
		}

		for ( i = 0; i < this.colors.length; i++ ) {
			pass = true;

			// Minimum hue-diff check.
			if ( 'undefined' !== typeof criteria.minHueDiff && criteria.minHueDiff > Math.abs( this.colors[ i ].hue - criteria.color.hue ) ) {
				pass = false;
			}

			// Maximum hue-diff check.
			if ( 'undefined' !== typeof criteria.maxHueDiff && criteria.maxHueDiff < Math.abs( this.colors[ i ].hue - criteria.color.hue ) ) {
				pass = false;
			}

			// Minimum contrast check.
			if ( 'undefined' !== typeof criteria.minContrast ) {
				this.colors[ i ].contrast = this.getContrast( criteria.color.lum, this.colors[ i ].lum );
				if ( criteria.minContrast > this.colors[ i ].contrast ) {
					pass = false;
				}
			}

			// If we passed the tests add to array.
			if ( pass ) {
				validColors.push( this.colors[ i ] );
			}
		}

		return Object.assign({}, this, {
			colors: validColors
		});
	},

	/**
	 * Query a string color and get its properties.
	 *
	 * @param {string} color - The color we want to query. Can be formatted as hex, rgb, rgba, hsl, hsla.
	 * @returns {Object} - {r,g,b,h,s,l,hex,lum}.
	 */
	getColorProperties: function( color ) {
		var hex, rgb, hsl, col;

		if ( -1 !== color.indexOf( 'hsl' ) ) {
			col = color.replace( 'hsla', '' ).replace( '.hsl', '' ).replace( '(', '' ).replace( ')', '' ).split( ',' );
			hsl = {
				h: parseInt( col[0], 10 ),
				s: parseInt( col[1], 10 ) / 100,
				l: parseInt( col[2], 10 ) / 100
			};
			rgb = this.hslToRgb( hsl.h, hsl.s, hsl.l );
			hex = this.rgbToHex( rgb.r, rgb.g, rgb.b );
		} else if ( -1 !== color.indexOf( 'rgb' ) ) {
			col = color.replace( 'rgba', '' ).replace( '.rgb', '' ).replace( '(', '' ).replace( ')', '' ).split( ',' );
			rgb = {
				r: col[0],
				g: col[1],
				b: col[2]
			};
			hsl = this.rgbToHsl( rgb.r, rgb.g, rgb.b );
			hex = this.rgbToHex( rgb.r, rgb.g, rgb.b );
		} else {
			hex = /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test( color ) ? color : '#ffffff';
			rgb = this.hexToRgb( hex );
			hsl = this.rgbToHsl( rgb.r, rgb.g, rgb.b );
		}
		return {
			r: rgb.r,
			g: rgb.g,
			b: rgb.b,
			h: hsl.h,
			s: hsl.s,
			l: hsl.l,
			hex: hex,
			lum: this.getRelativeLuminance( rgb )
		};
	},

	/**
	 * Get all colors for a given hue.
	 *
	 * @param {int} hue - The hue of the color.
	 * @param {Object} args - Additional arguments.
	 * @param {float} args.minSaturation - The minimum saturation the returned colors can have (0-1).
	 * @param {float} args.maxSaturation - The maximum saturation the returned colors can have (0-1).
	 * @param {float} args.stepSaturation - The increments in saturation while searching for colors (0-1).
	 * @param {float} args.stepLightness - Each lightness step.
	 *                                     smaller numbers are more detailed,
	 *                                     larger numbers have bigger "steps" between colors.
	 * @returns {Array}
	 */
	getAllColorsForHue: function( hue, args ) {
		var colors = [],
			saturation,
			lightness,
			color;

		for ( saturation = args.minSaturation; args.maxSaturation >= saturation; saturation += args.stepSaturation ) {
			for ( lightness = 0; 1.001 >= lightness; lightness += args.stepLightness ) {
				if ( 0 <= hue && 359 >= hue ) {
					color = this.hslToRgb( hue, saturation, lightness );
					colors.push({
						r: color.r,
						g: color.g,
						b: color.b,
						h: hue,
						s: saturation,
						l: lightness,
						hex: this.rgbToHex( color.r, color.g, color.b ),
						lum: this.getRelativeLuminance( color )
					});
				}
			}
		}
		return colors;
	},

	/**
	 * Converts an RGB object to a HEX string.\
	 *
	 * @param {int} r - Red.
	 * @param {int} g - Green.
	 * @param {int} b - Blue.
	 * @returns {string}
	 */
	rgbToHex: function( r, g, b ) {
		return '#' + ( ( 1 << 24 ) + ( r << 16 ) + ( g << 8 ) + b ).toString( 16 ).slice( 1 );
	},

	/**
	 * Convert hex color to RGB.
	 * See https://stackoverflow.com/a/5624139
	 *
	 * @since 1.0
	 * @param {string} hex - The hex color.
	 * @returns {Object}
	 */
	hexToRgb: function( hex ) {
		var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i,
			result;

		// Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF").
		hex = hex.replace( shorthandRegex, function( m, r, g, b ) {
			return r + r + g + g + b + b;
		});

		result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec( hex );
		return result ? {
			r: parseInt( result[1], 16 ),
			g: parseInt( result[2], 16 ),
			b: parseInt( result[3], 16 )
		} : null;
	},

	/**
	 * Converts an HSL color value to RGB. Conversion formula
	 * adapted from http://en.wikipedia.org/wiki/HSL_color_space.
	 * Assumes h, s, and l are contained in the set [0, 1] and
	 * returns r, g, and b in the set [0, 255].
	 *
	 * @param {int} h - Hue
	 * @param {float} s - Saturation
	 * @param {float} l - Lightness
	 * @return {Array} - RGB representation
	 */
	hslToRgb: function( h, s, l ) {
		var c, h_, x, r1, g1, b1, m;

		// Calculate chroma.
		c = ( 1 - Math.abs( ( 2 * l ) - 1 ) ) * s;

		// Find a point (r1, g1, b1) along the bottom three faces of the RGB cube,
		// with the same hue and chroma as our color
		// using the intermediate value X for the second largest component of this color.
		h_ = h / 60;
		x  = c * ( 1 - Math.abs( ( h_ % 2 ) - 1 ) );
		r1, g1, b1;

		if ( 'undefined' === typeof h || isNaN( h ) || null === h ) {
			r1 = g1 = b1 = 0;
		} else {
			if ( 0 <= h_ && 1 > h_ ) {
				r1 = c;
				g1 = x;
				b1 = 0;
			} else if ( 1 <= h_ && 2 > h_ ) {
				r1 = x;
				g1 = c;
				b1 = 0;
			} else if ( 2 <= h_ && 3 > h_ ) {
				r1 = 0;
				g1 = c;
				b1 = x;
			} else if ( 3 <= h_ && 4 > h_ ) {
				r1 = 0;
				g1 = x;
				b1 = c;
			} else if ( 4 <= h_ && 5 > h_ ) {
				r1 = x;
				g1 = 0;
				b1 = c;
			} else if ( 5 <= h_ && 6 > h_ ) {
				r1 = c;
				g1 = 0;
				b1 = x;
			}
		}

		// Find r, g, and b by adding the same amount to each component to match lightness.
		m = l - ( c / 2 );

		// Normalise to range [0,255] by multiplying with 255.
		return {
			r: Math.round( ( r1 + m ) * 255 ),
			g: Math.round( ( g1 + m ) * 255 ),
			b: Math.round( ( b1 + m ) * 255 )
		};
	},

	/**
	 * Converts an RGB color value to HSL. Conversion formula
	 * adapted from http://en.wikipedia.org/wiki/HSL_color_space.
	 * Assumes r, g, and b are contained in the set [0, 255] and
	 * returns h (0-359), s (0-1), and l (0-1)
	 *
	 * @param {int} r - The red color value
	 * @param {int} g - The green color value
	 * @param {int} b - The blue color value
	 * @return {Array} - The HSL representation
	 */
	rgbToHsl: function( r, g, b ) {
		var max, min, h, s, l, d;

		r /= 255;
		g /= 255;
		b /= 255;
		max = Math.max( r, g, b );
		min = Math.min( r, g, b );
		l = ( max + min ) / 2;

		if ( max === min ) { // Achromatic.
			return {
				h: 0,
				s: 0,
				l: l
			};
		}
		d = max - min;
		s = 0.5 < l ? d / ( 2 - max - min ) : d / ( max + min );
		switch ( max ) {
			case r: h = ( g - b ) / d + ( g < b ? 6 : 0 ); break;
			case g: h = ( b - r ) / d + 2; break;
			case b: h = ( r - g ) / d + 4; break;
		}
		h /= 6;

		return {
			h: h * 360,
			s: s,
			l: l
		};
	},

	/**
	 * Get contrast between 2 luminosities.
	 *
	 * @since 1.0
	 * @param {float} lum1 - 1st Luminosity.
	 * @param {float} lum2 - 2nd Luminosity.
	 * @returns {float}
	 */
	getContrast: function( lum1, lum2 ) {
		return this.roundFloat( Math.max( ( lum1 + 0.05 ) / ( lum2 + 0.05 ), ( lum2 + 0.05 ) / ( lum1 + 0.05 ) ) );
	},

	/**
	 * Gets the relative luminance from RGB.
	 * Formula: http://www.w3.org/TR/2008/REC-WCAG20-20081211/#relativeluminancedef
	 *
	 * @since 1.0
	 * @param {Object} color - an RGB color {r,g,b}.
	 * @returns {float}
	 */
	getRelativeLuminance: function( color ) {
		return this.roundFloat( 0.2126 * this.getLumPart( color.r ) + 0.7152 * this.getLumPart( color.g ) + 0.0722 * this.getLumPart( color.b ) );
	},

	/**
	 * Get luminocity for a part.
	 *
	 * @since 1.0
	 * @param {int|float} val - The value.
	 * @returns {float}
	 */
	getLumPart: function( val ) {
		val = val / 255;
		if ( 0.03928 >= val ) {
			return val / 12.92;
		}
		return Math.pow( ( ( val + 0.055 ) / 1.055 ), 2.4 );
	},

	/**
	 * Round a float.
	 * See https://stackoverflow.com/a/5624139
	 *
	 * @since 1.0
	 * @param {float} number - The number we want to round.
	 * @returns {float}
	 */
	roundFloat: function( number ) {
		return Math.round( number * 100 ) / 100;
	},

	/**
	 * Returns {this} with the colors array sorted.
	 *
	 * @param {string} sortBy - What do we want to sort by?
	 *                          Acceptable values: r,g,b,h,s,l,lum,hex,contrast
	 * @returns {Object}
	 */
	sortBy: function( sortBy ) {
		return Object.assign({}, this, {
			colors: this.colors.sort( function( a, b ) {
				return b[ sortBy ] - a[ sortBy ];
			})
		});
	},

	/**
	 * Removes duplicate entries from colors.
	 *
	 * @param {Array} colors - An array of colors.
	 * @return {Object}
	 */
	removeDuplicateColors: function( colors ) {
		var uniques = {},
			i;
		for ( i = 0; i < colors.length; i++ ) {
			uniques[ colors[ i ].hex ] = colors[ i ];
		}
		return Object.keys( uniques ).map( function( v ) {
			return uniques[ v ];
		});
	},

	/**
	 * Returns an array of Hex colors.
	 *
	 * @returns {Array}
	 */
	getHexArray: function() {
		var hexes = [],
			i;
		for ( i = 0; i < this.colors.length; i++ ) {
			hexes.push( this.colors[ i ].hex );
		}
		return hexes;
	}
};

/* global wcagColors */
wp.customize.controlConstructor['kirki-wcag-tc'] = wp.customize.controlConstructor.default.extend({

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
