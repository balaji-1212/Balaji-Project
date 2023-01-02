(function (api, $) {
	"use strict";

	api.bind('preview-ready', function () {
		/**
		 * Listen if dynamic setting being added on customizer panel and assign on previewer
		 */
		api.preview.bind('customize-add-setting', function (setting) {
			if (!api.has(setting.id)) {
				api.create(setting.id, setting.value, {
					id: setting.id
				});
			}
		});
	});

	if (!api.styleOutput) {
		api.styleOutput = {};
	}

	api.styleOutput = {
		rulesCache: [],
	};

	api.styleOutput.settingName = function (setting) {
		var regexp = new RegExp(window.outputSetting.settingPattern);
		var match = regexp.exec(setting);
		setting = Array.isArray(match) && match.length >= 2 ? match[2] : setting;
		return  setting.replace(/\[/g, '_').replace(/\]/g, '');
	};

	/**
	 * Bind setting to style output
	 *
	 * @param object
	 */
	 api.styleOutput.bindSetting = function (object) {
	 	var styleOutput = api.styleOutput;

	 	styleOutput.rulesCache[object.id] = object.output;

	 	api(object.id, function (setting) {
	 		setting.bind(function (value) {
			 	var styles = '';

			 	_.each( object.options.params.output, function( output ) {
			 		if ( ! output.function ) {
			 			output.function = 'style';
			 		}
			 		if ( 'style' === output.function ) {
			 			styles += kirkiPostMessage.css.fromOutput( output, value, object.options.params.type );
			 		} else {
			 			kirkiPostMessage[ output.function ].fromOutput( output, value, object.options.params.type );
			 		}
			 	} );
			 	kirkiPostMessage.styleTag.addData( styleOutput.settingName(object.id), styles );
			});
	 	});
	 };

	/**
	 * Listen to customizer panel event
	 */
	api.styleOutput.initialize = function () {
		api.preview.bind('register-style-output', function (object) {
			api.styleOutput.bindSetting(object);
		});

		api.preview.bind('active-callback-control-output', function (object) {
			api.styleOutput.bindSetting(object);
		});

		api.preview.bind('register-all-style-output', function (objects) {
			_.each(objects, function (object) {
				api.styleOutput.bindSetting(object);
			});
		});
	};

	api.bind('preview-ready', function () {
		api.styleOutput.initialize();
	});




	if (!api.partialRefresh) {
	    api.partialRefresh = {};
	}

	api.partialRefresh = {
	    partialRefreshCache: []
	};

	/**
	 * Add partial setting
	 *
	 * @param object
	 */
	api.partialRefresh.bindSetting = function (object) {
	    var partial = new api.selectiveRefresh.Partial(
	        object.id,
	        object.param
	    );
	    api.selectiveRefresh.partial.add(partial);
	};

	/**
	 * Bind Setting for partial refresh
	 */
	api.partialRefresh.initialize = function () {
	    api.preview.bind('register-partial-refresh', function (object) {
	        api.partialRefresh.bindSetting(object);
	    });

	    api.preview.bind('register-all-partial-refresh', function (objects) {
	        _.each(objects, function (object) {
	            api.partialRefresh.bindSetting(object);
	        });
	    });
	};

	/**
	 * Initialize partial refresh
	 */
	api.bind('preview-ready', function () {
	    api.partialRefresh.initialize();
	});



})(wp.customize, jQuery);

