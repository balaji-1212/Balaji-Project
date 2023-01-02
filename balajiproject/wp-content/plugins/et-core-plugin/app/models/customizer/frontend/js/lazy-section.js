(function($, api) {
	"use strict";

	api.sectionConstructor.default = api.Section.extend({
		expand : function( params ) {
			var section = this.container[1];

			if( !$(section).hasClass( 'et-customizer-section-loaded' ) ) {
				$(section).addClass('et-customizer-section-loaded').trigger('et-customizer-section-opened');
			}

			api.Section.prototype.expand.call(this, params);
		}
	});

	api.sectionConstructor['kirki-lazy'] = api.sectionConstructor.default.extend({
		loaded : false,
		loadingContainer: null,
		opened: false,
		dependency: null,
		promise: null,
		sectionControls: [],

		ready: function() {
			var section = this;

			api.sectionConstructor.default.prototype.ready.call( section );

			section.deferred.embedded.done(function() {
				section.setupSectionActions();
			});
		},

		/**
		 * Setup section when loaded
		 */
		setupSectionActions: function(){

			var section = this, sectionLoadingTemplate, descriptionContainer;

			sectionLoadingTemplate = wp.template( 'et-lazy-section-loader' );

			section.loadingContainer = $(sectionLoadingTemplate({
				loading: 'Loading Control'
			}));

			descriptionContainer = section.container.find( '.section-meta:first' );

			descriptionContainer.after( section.loadingContainer );
		},

		/**
		 * Allow an active panel to be contextually active even when it has no active controls.
		 *
		 * @returns {boolean} Whether contextually active.
		 */
		isContextuallyActive: function() {
			var section = this;
			return section.active();
		},
		/**
		 * Load Control
		 *
		 * @returns $.promise
		 */
		loadControl : function () {
			var section = this;
			section.dependency = [];
			section.buildDependency(section.params.id);
			return wp.ajax.send({
				url: lazySetting.ajaxUrl,
				data: {
					customizernonce: lazySetting.nonce,
					sections: section.dependency.reverse()
				}
			});
		},

		/**
		 * Build recursive dependency tree
		 * @param sectionID
		 * @returns {*}
		 */
		buildDependency: function(sectionID){
			var element = this;
			var section = api.section(sectionID);
			var dependency = section.params.dependency;

			if(!section.loaded && element.dependency.indexOf(sectionID) <= 0) {
				element.dependency.push(sectionID);
				if(dependency.length > 0){
					_.each(dependency, function(sectionID){
						element.buildDependency(sectionID);
					});
				}
			}
		},

		/**
		 * Create Customizer Setting
		 *
		 * @param settingParams
		 * @param id
		 */
		createSetting: function(settingParams, id) {
			if (!api.has(id)) {
				var setting = new api.Setting(id, settingParams.value, settingParams);
				api.add( id, setting );

				// Send Dynamic Setting to Customizer Preview
				api.previewer.send('customize-add-setting', {
					id: id,
					value: settingParams.value,
					params: settingParams
				});
			}
		},

		/**
		 * Create Customizer Control
		 * @param option
		 * @param id
		 */
		createControl: function(option, id) {
			var _type = option.type;
			var controlApi = option.type;
			var control = api.controlConstructor[ _type ] || api.Control;
			control = new control( id, {
				params: option
			});
			controlApi = api.control.add( control.id, control );

			this.sectionControls.push(controlApi);
		},

		/**
		 * Proceed to create control by response
		 *
		 * @param responses
		 */
		addControl : function (responses) {
			var that = this;
			var secid;

			if(responses.length === 0) {
				that.finishLoading(that.id);
			}

			// Assign Control & Setting
			_.each(responses, function(response, sectionID){
				var section = api.section(sectionID);
				// Create Setting
				_.each(response, function(option){
					section.createSetting(option['setting'], option['settingId']);
				});

				// Create Control
				_.each(response, function(option, id){
					section.createControl(option['control'], id);
				});

			});

			// Send complete flag
			_.each(responses, function(response, sectionID){
				secid = sectionID;
				that.finishLoading(sectionID);
			});

			extendKirkiDependencies.init(this.sectionControls, secid);
		},

		finishLoading: function(sectionID){
			var section = api.section(sectionID);
			section.loaded = true;
			api.section.trigger(section.id, section);

			section.loadingContainer.remove();

			var sectionControls = this.sectionControls;
			setTimeout(function() {
				if(section.promise) {
					section.promise.resolve();
					section.et_add_options_devices(section);
					kirkiTooltipAdd(sectionControls);
					api.section.trigger(section.id, section);
				}
			}, 500);
		},

		et_add_options_devices : function(section) {
			var id  = '#accordion-section-' + section.id;

			if ( ! ( $( id ).is( '.et_device-added' ) ) ) {
				$( id ).addClass( 'et_device-added' );
				var devices = $('#customize-footer-actions .devices-wrapper').html(),
					id = '#' + $(id).attr('aria-owns');

				$.each( $( id ).find( 'li[id*=_et-mobile], li[id*=_et-tablet], li[id*=_et-desktop]' ), function() {
					var _id = $(this).attr( 'id' );
					_id = _id.replace( '_et-mobile', '' );
					_id = _id.replace( '_et-tablet', '' );
					_id = _id.replace( '_et-desktop', '' );

					if ( $('li[id*='+_id+'_et]').length > 1 ) {
						$(this).addClass( 'multi-device' ).prepend( devices );
					}
				});
			}

			if ( section.id == 'mobile_panel' )
				$('.devices-wrapper .preview-mobile').trigger('click');
		},

		/**
		 * Give promise to caller
		 *
		 * @returns $.Deferred
		 */
		loadControlPromise: function(){
			var section = this;
			this.promise = $.Deferred();

			var control = section.loadControl();
			control.done(section.addControl.bind(this));

			return this.promise;
		},

		/**
		 * handle expand
		 *
		 * @param params
		 * @returns {*|Boolean}
		 */
		expand : function(params) {
			var section = this;

			api.sectionConstructor.default.prototype.expand.call(this, params);

			if(!section.loaded)
			{
				return this.loadControlPromise();
			}
		}
	});


	function kirkiTooltipAdd( controls ) {
		_.each( controls, function( control ) {

			if ( 'undefined' === typeof control.params.tooltip ) {
				return;
			}
			let trigger,
				controlID,
				content;
			trigger   = '<span class="tooltip-trigger" data-setting="' + control.id + '"><span class="dashicons dashicons-editor-help"></span></span>';
			controlID = '#customize-control-' + control.id;
			content   = '<div class="tooltip-content hidden" data-setting="' + control.id + '">' + control.params.tooltip.content + '</div>';

			// Add the trigger & content.
			jQuery( '<div class="tooltip-wrapper">' + trigger + content + '</div>' ).prependTo( $(controlID) );

			// Handle onclick events.
			jQuery( '.tooltip-trigger[data-setting="' + control.id + '"]' ).on( 'click', function() {
				jQuery( '.tooltip-content[data-setting="' + control.id + '"]' ).toggleClass( 'hidden' );
			} );
		} );

		jQuery(document).ready(function($) {
			// Close tooltips if we click anywhere else.
			jQuery( document ).mouseup( function( e ) {

				if ( ! jQuery( '.tooltip-content' ).is( e.target ) ) {
					if ( ! jQuery( '.tooltip-content' ).hasClass( 'hidden' ) ) {
						jQuery( '.tooltip-content' ).addClass( 'hidden' );
					}
				}
			} );
		});
	}
})(jQuery, wp.customize);
