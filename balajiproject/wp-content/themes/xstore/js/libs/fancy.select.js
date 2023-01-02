/**
 * Fancy Select Plugin. 
 *
 * Converts a <select> field to a clickable text box and triggers a div to 
 * appear instead of the dropdown. The div can be positioned as required by the
 * developer
 *
 * Also optionally allows for keyboard entry and filtering of the result set.
 */

/**
 * Case insensitive contains. Used for the keyboard filtering of the select
 * we don't want to exclude things that have a not matching case
 */
jQuery.expr[':'].Contains = function(a,i,m) {
    return (a.textContent || a.innerText || "").toUpperCase().indexOf(m[3].toUpperCase())>=0;
};

(function($) {
	"use strict";

	$.fn.fancySelect = function(config) {
		config = jQuery.extend({
			containerClass: 'fancy-select',
			resultsClass: 'fancy-select-results',
			placeholderClass: 'fancy-select-replaced',
			itemHoverClass: 'fancy-select-hover',
			selectOpenClass : 'fancy-select-open',
			selectHoverClass: 'fancy-select-hovered',
			allowTextFilter: false,
			
			onItemEnter: function() {
				$(this).addClass(config.itemHoverClass);
			},
			onItemLeave: function() {
				$(this).removeClass(config.itemHoverClass);
			},
			onOptionSelect: function() {
				//
			},
			onSelectOpen: function() {
				$(this).addClass(config.selectOpenClass);
				$(this).siblings(config.getResultsSelector()).show();
			},
			onSelectClose: function() {
				$(this).siblings(config.getResultsSelector()).hide();
				$(this).removeClass(config.selectOpenClass);
			},
			onSelectEnter: function() {
				$(this).addClass(config.selectHoverClass);
			},
			onSelectLeave: function() {
				$(this).removeClass(config.selectHoverClass);
			},
			getResultsSelector: function() {
				return "." + config.resultsClass;
			},
			getPlaceholderSelector: function() {
				return "." + config.placeholderClass;
			},
			generateResultsContainer: function() {
				return $("<div></div>")
					.addClass(config.resultsClass);
			},
			generatePlaceholderContainer: function() {
				return $("<div></div>")
					.addClass(config.placeholderClass);
			},
			generateReplacedRow: function(elem) {
				return $("<li />")
					.text($(elem).text())
					.data("value", $(elem).val());
			},
			generateFilterInput: function(elem) {
				return $('<input type="text" />')
					.addClass('fancy-placeholder fancy-placeholder-input');
			},
			generateContainer: function() {
				return $("<div></div>")
					.addClass(config.containerClass);
			}	
		}, config);
	
		/**
		 * Global document click handler.
		 *
		 * This is required so that the user does not have the close the select
		 * explicitly, clicking elsewhere on the page will close each of the
		 * opened selects for the user.
		 */
		$(document).click(function(e) {
			if(e === undefined) {
				e = window.event;
			}

			// if clicked element is a child of either the container or elements
			if($(e.target).parents(config.getResultsSelector()).length > 0) {
				return;
			}
			
			// if clicked element is the replaced section then ignore it
			if($(e.target).parents(config.getPlaceholderSelector()).length > 0) {
				return;
			}
			
			// close all popups
			$(config.getPlaceholderSelector()).each(function() {
				config.onSelectClose.call(this);
			});
		});


		/**
		 * Global keydown.
		 *
		 * If the supports the user using a keyboard (ala a normal select)
		 */
		$(document).keydown(function(e) {
			if(/38|40|13/.test(e.keyCode)) {
				$(config.getResultsSelector()).filter(":visible").each(function() {
					var current, 
						first, 
						up = 38, 
						down = 40, 
						select = 13;
					
					current = $("li", this)
						.filter(":visible")
						.filter("." + config.itemHoverClass);

					if(current.length < 1) {
						if(e.keyCode == down) {
							config.onItemEnter.call(current.first());
						}
						
						return;
					}

					// return, and selected li so I choose you
					if(e.keyCode == select) {
						$(current).click();

						return e.preventDefault();
					}

					// get the index of the current one. Need to
					// work out if we're able to go up
					var list = $("li:visible", this);
					var i = list.index($(current).get(0));
					var pos = i + ((e.keyCode == down) ? 1 : - 1);

					if(pos > 0 && pos < list.length) {
						config.onItemLeave.call(current);
						config.onItemEnter.call($("li:visible", this).eq(pos));
					}
				});
			}
		});
		
		/**
		 * Representation of a replaced select element.
		 *
		 * Has one constructor argument required and that is the original select 
		 * field we're replacing.
		 *
		 * The other configuration comes from the config.
		 */
		function FancySelect(orig) {
			this.select = orig;
			this.container = config.generateContainer();
			this.results = config.generateResultsContainer().css({
				'position': 'absolute',
				'display': 'none'
			});
			
			return this.init();
		}
		
		/**
		 * Replace the built in select with the container and the results.
		 */
		FancySelect.prototype.init = function() {
			var list = $("<ul></ul>"),
				selected,
				self = this;
			
			// convert all options to list items and save the values as data 
			// attributes
			this.select.children('option').each(function(i, elem) {
				list.append(config.generateReplacedRow($(elem)));
			});
			
			this.results.append(list);

			// Convert the ye old select tag to a simple div and add the classes
			// required change the select form value to a hidden field.
			selected = $("option:selected", this.select);

			// setup the default place holder.
			//
			// either it will just be a string of text or an input field. The 
			// input field allows a user to filter the list but we keep the p 
			// there, just do several layers of magic
			this.placeholder_p = $('<p class="fancy-placeholder fancy-placeholder-text"></p>')
				.append(selected.text())
				.data('default', selected.text());
			
			this.placeholder = config.generatePlaceholderContainer(config)
				.click(function() {
					// if the related select is open then close them all else 
					// close everything apart from the one which is needed
					var hidden = (self.results.is(":hidden"));

					$(config.getPlaceholderSelector()).each(function() {
						config.onSelectClose.call(this);
					});

					if(hidden) {
						config.onSelectOpen.call(this);

						// if we have an input then focus that field so users can type
						if(config.allowTextFilter) self.focus();
					}
				}).hover(function() {
					config.onSelectEnter.call(this);
				}, function() {
					config.onSelectLeave.call(this);
				})
				.append(this.placeholder_p);
			
			if(config.allowTextFilter) {
				this.placeholder.append(this.createFilterInput());
			}
			
			this.container.append(this.placeholder);
			this.container.append(this.results);
			this.container.append(this.val);
			this.container.hide();
			
			this.select.after(this.container);
			this.select.hide();
			this.container.show();
			
			return this;
		};
	
		/**
		 * Set the passed list item as the selected option in the
		 * dropdrown
		 *
		 * @param string
		 */
		FancySelect.prototype.setSelectedItem = function(li) {
			if(config.allowTextFilter) {
				var input = this.placeholder.find(".fancy-placeholder-input");

				input.clearingOnSelection = true;
				input.blur();
				
				input.clearingOnSelection = false;	
				input.val("");
			}

			this.placeholder.find(".fancy-placeholder-text").text($(li).text());
			this.select.val($(li).data('value'));
		};
		
		/**
		 * Focus event to the FancySelect. Should focus the text field
		 * placeholder if enabled.
		 */
		FancySelect.prototype.focus = function() {
			$(this.placeholder).find("input").focus();
		};
		
		/**
		 * Create the replacement input field and all the events that
		 * go along with that.
		 */
		FancySelect.prototype.createFilterInput = function() {
			var self = this;
			
			this.input = config.generateFilterInput(self);
			this.input.keydown(function(e) {
				// on keydown we can't test for values, but we can be safely 
				// assume and say if any key other than direction and return 
				// then there will be input.
				if(!(/37|38|39|40|13/.test(e.keyCode))) {
					self.placeholder_p.text("");
				}
					
				// if the dropdown is hidden (i.e we focused directly to the input) then
				// make sure it is open
				if(self.results.is(":hidden")) {
					config.onSelectOpen.call(self.placeholder);
				}
			}).keyup(function(e) {
				// strip out any parentheses values from the input as the 
				// selector barfs it
				var token = $(this).val().replace(/[\(|\)]/gi, '');

				if(token.length < 1) {
					self.placeholder_p.text(self.placeholder_p.data('default'));

					$("li", self.results).show();
				}
				else {
					self.placeholder_p.text("");
					
					$("li:not(:Contains("+ token + "))", self.results)
						.hide()
						.removeClass(config.itemHoverClass);
						
					var winners = $("li:Contains("+ token + ")", self.results);
					winners.show();
						
					$(":first", winners).addClass(config.itemHoverClass);
				}
				
				// remove the selection on anything that is now hidden
				$("li."+ config.itemHoverClass+":hidden", self.results).each(function() {
					config.onItemLeave(this);
				});
				
			}).blur(function() {
				if($(this).val() < 1 && !this.clearingOnSelection) {
					self.placeholder_p.text(self.placeholder_p.data('default'));
				}
			});
				
			self.input.attr('tabIndex', self.select.tabIndex);
			
			return self.input;
		};
		
		return this.each(function() {
			var select = $(this);
			var replacement = new FancySelect(select);
			var results = replacement.results;
			
			$("li", results).click(function(e) {
				replacement.setSelectedItem(this);

				config.onOptionSelect.call(this);
				config.onSelectClose.call(replacement.placeholder);
				
				return e.preventDefault();
				
			}).hover(function() {
				// unhover all the other list items
				$("li:visible", results).each(function() {
					config.onItemLeave.call(this);
				});
				
				config.onItemEnter.call(this);
				
			}, function() {
				config.onItemLeave.call(this);		
			});
		});
	};
})(jQuery);