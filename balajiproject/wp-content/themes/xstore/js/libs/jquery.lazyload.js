/*!
 * Lazy Load - JavaScript plugin for lazy loading images
 *
 * Copyright (c) 2007-2019 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   https://appelsiini.net/projects/lazyload
 *
 * Version: 2.0.0-rc.2
 *
 */

(function (root, factory) {
    if (typeof exports === "object") {
        module.exports = factory(root);
    } else if (typeof define === "function" && define.amd) {
        define([], factory);
    } else {
        root.LazyLoad = factory(root);
    }
}) (typeof global !== "undefined" ? global : this.window || this.global, function (root) {

    "use strict";

    if (typeof define === "function" && define.amd){
        root = window;
    }

    const defaults = {
        src: "data-src",
        srcset: "data-srcset",
        selector: ".lazyload:not(.zoomImg, .et-lazy-loaded, .rs-lazyload)",
        root: null,
        rootMargin: "200px",
        threshold: 0
    };

    /**
     * Merge two or more objects. Returns a new object.
     * @private
     * @param {Boolean}  deep     If true, do a deep (or recursive) merge [optional]
     * @param {Object}   objects  The objects to merge together
     * @returns {Object}          Merged values of defaults and options
     */
    const extend = function ()  {

        let extended = {};
        let deep = true;
        let i = 0;
        let length = arguments.length;

        /* Check if a deep merge */
        if (Object.prototype.toString.call(arguments[0]) === "[object Boolean]") {
            deep = arguments[0];
            i++;
        }

        /* Merge the object into the extended object */
        let merge = function (obj) {
            for (let prop in obj) {
                if (Object.prototype.hasOwnProperty.call(obj, prop)) {
                    /* If deep merge and property is an object, merge properties */
                    if (deep && Object.prototype.toString.call(obj[prop]) === "[object Object]") {
                        extended[prop] = extend(true, extended[prop], obj[prop]);
                    } else {
                        extended[prop] = obj[prop];
                    }
                }
            }
        };

        /* Loop through each object and conduct a merge */
        for (; i < length; i++) {
            let obj = arguments[i];
            merge(obj);
        }

        return extended;
    };

    function LazyLoad(images, options) {
        this.settings = extend(defaults, options || {});
        this.images = images || document.querySelectorAll(this.settings.selector);
        this.observer = null;
        this.init();
    }

    LazyLoad.prototype = {
        init: function() {

            /* Without observers load everything and bail out early. */
            if (!root.IntersectionObserver) {
                this.loadImages();
                return;
            }

            let self = this;
            let observerConfig = {
                root: this.settings.root,
                rootMargin: this.settings.rootMargin,
                threshold: [this.settings.threshold]
            };

            this.observer = new IntersectionObserver(function(entries) {
                Array.prototype.forEach.call(entries, function (entry) {
                    // if (entry.isIntersecting || entry.target.classList.contains('et-lazy-force-load')) {
                    if (entry.isIntersecting) {
                        // if ( entry.target.classList.contains('et-lazy-force-load') ) {
                            if ( entry.target.closest('.swiper-wrapper') !== null ) {
                                Array.prototype.forEach.call(entry.target.closest('.swiper-wrapper').querySelectorAll(self.settings.selector), function (new_image) {
                                    let src = new_image.getAttribute(self.settings.src);
                                    let srcset = new_image.getAttribute(self.settings.srcset);
                                    if ("img" === new_image.tagName.toLowerCase()) {
                                        if (new_image.classList.contains('et-lazy-loaded')) {
                                            self.observer.unobserve(new_image);
                                        }
                                        if (src) {
                                            new_image.src = src;
                                            if (typeof etTheme !== 'undefined' && typeof etTheme.isotope && etTheme.isotope !== undefined)
                                                etTheme.isotope();
                                        }
                                        if (srcset) {
                                            new_image.srcset = srcset;
                                            if (typeof etTheme !== 'undefined' && typeof etTheme.isotope && etTheme.isotope !== undefined)
                                                etTheme.isotope();
                                        }
                                        new_image.classList.remove('et-lazyload-fadeIn');
                                        new_image.classList.remove('et-lazyload-force-load');
                                        new_image.classList.add('et-lazy-loaded');
                                    } else {
                                        new_image.style.backgroundImage = "url(" + src + ")";
                                        self.observer.unobserve(new_image);
                                    }
                                    // jQuery(new_image).addClass('sdfd');
                                    // let swiper = jQuery(new_image).parents('.swiper-container').data('swiper');
                                    // console.log(swiper);
                                    // swiper.init();
                                });
                                // @todo update swiper for this images
                                // window.dispatchEvent(new Event('resize'));
                            }
                        // }
                        let src = entry.target.getAttribute(self.settings.src);
                        let srcset = entry.target.getAttribute(self.settings.srcset);
                        if ("img" === entry.target.tagName.toLowerCase()) {
                            if ( entry.target.classList.contains('et-lazy-loaded') ) {
                                self.observer.unobserve(entry.target);
                            }
                            if (src) {
                                entry.target.src = src;
                                if ( typeof etTheme !== 'undefined' && typeof etTheme.isotope !== 'undefined' && etTheme.isotope !== undefined )
                                    etTheme.isotope();
                            }
                            if (srcset) {
                                entry.target.srcset = srcset;
                                if ( typeof etTheme !== 'undefined' && typeof etTheme.isotope && etTheme.isotope !== undefined )
                                    etTheme.isotope();
                            }
                            entry.target.classList.remove('et-lazyload-fadeIn');
                            entry.target.classList.remove('et-lazyload-force-load');
                            entry.target.classList.add('et-lazy-loaded');
                        } else {
                            entry.target.style.backgroundImage = "url(" + src + ")";
                            self.observer.unobserve(entry.target);
                        }
                    }
                });
            }, observerConfig);

            Array.prototype.forEach.call(this.images, function (image) {
                // console.log(image);
                // console.log(image.parentElement);
                self.observer.observe(image);
                // this.settings.selector
                // if ( image.closest('.swiper-wrapper') !== null )
                //     Array.prototype.forEach.call(image.closest('.swiper-wrapper').querySelectorAll(self.settings.selector), function (new_image) {
                //         new_image.classList.add('et-lazy-force-load');
                //         // self.observer.observe(new_image);
                //     });
            });
        },

        loadAndDestroy: function () {
            if (!this.settings) { return; }
            this.loadImages();
            this.destroy();
        },

        loadImages: function () {
            if (!this.settings) { return; }

            let self = this;
            Array.prototype.forEach.call(this.images, function (image) {
                let src = image.getAttribute(self.settings.src);
                let srcset = image.getAttribute(self.settings.srcset);
                if ("img" === image.tagName.toLowerCase()) {
                    if (src) {
                        image.src = src;
                        if ( typeof etTheme !== 'undefined' && typeof etTheme.isotope && etTheme.isotope !== undefined )
                            etTheme.isotope();
                    }
                    if (srcset) {
                        image.srcset = srcset;
                        if ( typeof etTheme !== 'undefined' && typeof etTheme.isotope && etTheme.isotope !== undefined )
                            etTheme.isotope();
                    }
                    image.classList.remove('et-lazyload-fadeIn');
                    image.classList.add('et-lazy-loaded');

                } else {
                    image.style.backgroundImage = "url('" + src + "')";
                }
            });
        },

        destroy: function () {
            if (!this.settings) { return; }
            this.observer.disconnect();
            this.settings = null;
        }
    };

    root.lazyload = function(images, options) {
        return new LazyLoad(images, options);
    };

    if (root.jQuery) {
        const $ = root.jQuery;
        $.fn.lazyload = function (options) {
            options = options || {};
            options.attribute = options.attribute || "data-src";
            new LazyLoad($.makeArray(this), options);
            return this;
        };
    }

    return LazyLoad;
});
