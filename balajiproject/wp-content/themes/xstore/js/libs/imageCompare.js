/**
 * Image Compare
 *
 * Modified by Stas to make it lightweight and use only options that are really needed
 *
 * @package    imageCompare.js
 * @since      1.0.0
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

// uncomment for packing
// import "../styles/index.scss";
// import { disableBodyScroll, enableBodyScroll } from "body-scroll-lock";

class ImageCompare {
    constructor(el, settings = {}) {
        const defaults = {
            // controlLineColor: "#FFFFFF",
            // controlShadow: true,
            // addCircle: false,
            // addCircleBlur: true,
            showLabels: false,
            labelOptions: {
                before: "Before",
                after: "After",
                onHover: false,
            },
            smoothing: true,
            smoothingAmount: 100,
            onHover: false,
            verticalMode: false,
            startingPoint: 50,
            // slideWidth: 50,
            // lineWidth: 2,
            prefix: 'etheme-image-comparison',
            addOverlay: false
        };

        this.settings = Object.assign(defaults, settings);

        this.safariAgent =
            navigator.userAgent.indexOf("Safari") != -1 &&
            navigator.userAgent.indexOf("Chrome") == -1;

        this.el = el;
        this.images = {};
        this.wrapper = null;
        this.control = null;
        this.arrowContainer = null;
        this.arrowAnimator = [];
        this.active = false;
        this.arrowCoordinates = {
            circle: [5, 3],
            standard: [8, 0],
        };
    }

    mount() {
        // Temporarily disable Safari smoothing
        if (this.safariAgent) {
            this.settings.smoothing = false;
        }

        this._getImages();
        this._shapeContainer();
        this._buildControl();
        this._events();
    }

    _events() {

        // Desktop events
        this.el.addEventListener("mousedown", (ev) => {
            this._activate(true);
            document.body.classList.add(this.settings.prefix+"-body");
            // disableBodyScroll(this.el, {reserveScrollBarGap: true});
            this._slideCompare(ev);
        });
        this.el.addEventListener(
            "mousemove",
            (ev) => this.active && this._slideCompare(ev)
        );

        this.el.addEventListener("mouseup", () => !this.settings.onHover && this._activate(false));
        document.body.addEventListener("mouseup", () => {
            document.body.classList.remove(this.settings.prefix + "-body");
            // enableBodyScroll(this.el);
            if ( !this.settings.onHover )
                this._activate(false);
        });

        // Mobile events
        this.control.addEventListener("touchstart", (e) => {
            this._activate(true);
            document.body.classList.add(this.settings.prefix+"-body");
            // disableBodyScroll(this.el, {reserveScrollBarGap: true});
        });

        this.el.addEventListener("touchmove", (ev) => {
            this.active && this._slideCompare(ev);
        });
        this.el.addEventListener("touchend", () => {
            this._activate(false);
            document.body.classList.remove(this.settings.prefix+"-body");
            // enableBodyScroll(this.el);
        });

        // hover

        this.el.addEventListener("mouseenter", () => {
            this.settings.onHover && this._activate(true);
            // let coord = this.settings.addCircle
            //     ? this.arrowCoordinates.circle
            //     : this.arrowCoordinates.standard;

            //     this.arrowAnimator.forEach((anim, i) => {
            //         anim.style.cssText = `
            // ${
            //             this.settings.verticalMode
            //                 ? `transform: translateY(${coord[1] * (i === 0 ? 1 : -1)}px);`
            //                 : `transform: translateX(${coord[1] * (i === 0 ? 1 : -1)}px);`
            //         }
            // `;
            //     });
        });

        // this.el.addEventListener("mouseleave", () => {
        // let coord = this.settings.addCircle
        //     ? this.arrowCoordinates.circle
        //     : this.arrowCoordinates.standard;

        //     this.arrowAnimator.forEach((anim, i) => {
        //         anim.style.cssText = `
        // ${
        //             this.settings.verticalMode
        //                 ? `transform: translateY(${
        //                     i === 0 ? `${coord[0]}px` : `-${coord[0]}px`
        //                 });`
        //                 : `transform: translateX(${
        //                     i === 0 ? `${coord[0]}px` : `-${coord[0]}px`
        //                 });`
        //         }
        // `;
        //     });
        // });
    }

    _slideCompare(ev) {
        let bounds = this.el.getBoundingClientRect();
        let x =
            ev.touches !== undefined
                ? ev.touches[0].clientX - bounds.left
                : ev.clientX - bounds.left;
        let y =
            ev.touches !== undefined
                ? ev.touches[0].clientY - bounds.top
                : ev.clientY - bounds.top;

        let position = this.settings.verticalMode
            ? (y / bounds.height) * 100
            : (x / bounds.width) * 100;

        if (position >= 0 && position <= 100) {
            // this.settings.verticalMode
            //     ? (this.control.style.top = `calc(${position}% - ${
            //         this.settings.slideWidth / 2
            //     }px)`)
            //     : (this.control.style.left = `calc(${position}% - ${
            //         this.settings.slideWidth / 2
            //     }px)`);
            // new
            this.settings.verticalMode
                ? (this.control.style.top = `calc(${position}% - (var(--divider-width, 45px) / 2))`)
                : (this.control.style.left = `calc(${position}% - (var(--divider-width, 45px) / 2))`);

            this.settings.verticalMode
                ? (this.wrapper.style.height = `calc(${position}%)`)
                : (this.wrapper.style.width = `calc(${100 - position}%)`);
        }
    }

    _activate(state) {
        this.active = state;
    }

    _shapeContainer() {
        let label_l = document.createElement("span");
        let label_r = document.createElement("span");

        label_l.classList.add(this.settings.prefix+"-label", this.settings.prefix+"-label-before");
        label_r.classList.add(this.settings.prefix+"-label", this.settings.prefix+"-label-after");

        if (this.settings.labelOptions.onHover) {
            label_l.classList.add("on-hover");
            label_r.classList.add("on-hover");
        }

        // if (this.settings.verticalMode) {
        //     label_l.classList.add("vertical");
        //     label_r.classList.add("vertical");
        // }

        label_l.innerHTML = this.settings.labelOptions.before || "Before";
        label_r.innerHTML = this.settings.labelOptions.after || "After";

        if (this.settings.showLabels) {
            this.el.prepend(label_l);
            // if ( !this.settings.verticalMode )
            this.wrapper.appendChild(label_r);
            // else
            //     this.el.appendChild(label_r);
        }

        // this.el.classList.add(
        //     this.settings.prefix,
        //     this.settings.prefix + (this.settings.verticalMode ? `-vertical` : `-horizontal`),
        // );

        if (this.settings.addOverlay) {
            let overlay = document.createElement("div");
            overlay.classList.add(this.settings.prefix + "-overlay");
            this.el.appendChild(overlay);
        }
    }

    _buildControl() {
        let control = document.createElement("div");
        let uiLine = document.createElement("div");
        let arrows = document.createElement("div");
        let arrowsWrapper = document.createElement("div");
        // let circle = document.createElement("div");

        // const arrowSize = "20";

        arrows.classList.add(this.settings.prefix+"-theme-wrapper");
        arrowsWrapper.classList.add(this.settings.prefix+"-arrows-wrapper");
        arrows.appendChild(arrowsWrapper);

        for (var idx = 0; idx <= 1; idx++) {
            let animator = document.createElement(`span`);

            let arrow =
                `<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">`;

            if ( !this.settings.verticalMode ) {
                arrow += (idx === 0 ?
                        `<path d="M17.976 22.8l-10.44-10.8 10.464-10.848c0.24-0.288 0.24-0.72-0.024-0.96-0.24-0.24-0.72-0.264-0.984 0l-10.92 11.328c-0.264 0.264-0.264 0.672 0 0.984l10.92 11.28c0.144 0.144 0.312 0.216 0.504 0.216 0.168 0 0.336-0.072 0.456-0.192 0.144-0.12 0.216-0.288 0.24-0.48 0-0.216-0.072-0.384-0.216-0.528z"></path>` :
                        `<path d="M17.88 11.496l-10.728-11.304c-0.264-0.264-0.672-0.264-0.96-0.024-0.144 0.12-0.216 0.312-0.216 0.504 0 0.168 0.072 0.336 0.192 0.48l10.272 10.8-10.272 10.8c-0.12 0.12-0.192 0.312-0.192 0.504s0.072 0.36 0.192 0.504c0.12 0.144 0.312 0.216 0.48 0.216 0.144 0 0.312-0.048 0.456-0.192l0.024-0.024 10.752-11.328c0.264-0.264 0.24-0.672 0-0.936z"></path>`
                );
            }
            else {
                arrow += (idx === 0 ?
                        `<path d="M23.688 16.944l-11.208-10.872c-0.264-0.264-0.696-0.24-0.96 0l-11.256 10.872c-0.12 0.12-0.192 0.288-0.216 0.48 0 0.192 0.072 0.36 0.216 0.504s0.312 0.216 0.48 0.216c0.168 0 0.36-0.072 0.48-0.216l10.776-10.392 10.752 10.368c0.216 0.216 0.576 0.264 0.936 0l0.024-0.024c0.12-0.12 0.192-0.312 0.192-0.48 0.024-0.168-0.072-0.336-0.216-0.456z"></path>` :
                        `<path d="M23.784 6.072c-0.264-0.264-0.672-0.264-0.984 0l-10.8 10.416-10.8-10.416c-0.264-0.264-0.672-0.264-0.984 0-0.144 0.12-0.216 0.312-0.216 0.48 0 0.192 0.072 0.36 0.192 0.504l11.28 10.896c0.096 0.096 0.24 0.192 0.48 0.192 0.144 0 0.288-0.048 0.432-0.144l0.024-0.024 11.304-10.92c0.144-0.12 0.24-0.312 0.24-0.504 0.024-0.168-0.048-0.36-0.168-0.48z"></path>`
                );
            }

            arrow += `</svg>`;

            //        let arrow = `<svg
            //  height="15"
            //  width="15"
            //   style="
            //   transform:
            //   scale(${this.settings.addCircle ? 0.7 : 1.5})
            //   rotateZ(${
            //            idx === 0
            //                ? this.settings.verticalMode
            //                ? `-90deg`
            //                : `180deg`
            //                : this.settings.verticalMode
            //                ? `90deg`
            //                : `0deg`
            //        }); height: ${arrowSize}px; width: ${arrowSize}px;
            //
            //   ${
            //            this.settings.controlShadow
            //                ? `
            //   -webkit-filter: drop-shadow( 0px 3px 5px rgba(0, 0, 0, .33));
            //   filter: drop-shadow( 0px ${
            //                    idx === 0 ? "-3px" : "3px"
            //                } 5px rgba(0, 0, 0, .33));
            //   `
            //                : ``
            //        }
            //   "
            //   xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 15 15">
            //   <path ${
            //            this.settings.addCircle
            //                ? `fill="transparent"`
            //                : `fill="${this.settings.controlLineColor}"`
            //        }
            //   stroke="${this.settings.controlLineColor}"
            //   stroke-linecap="round"
            //   stroke-width="${this.settings.addCircle ? 3 : 0}"
            //   d="M4.5 1.9L10 7.65l-5.5 5.4"
            //   />
            // </svg>`;

            animator.innerHTML += arrow;
            this.arrowAnimator.push(animator);
            arrowsWrapper.appendChild(animator);
        }

        // let coord = this.settings.addCircle
        //     ? this.arrowCoordinates.circle
        //     : this.arrowCoordinates.standard;

        //   this.arrowAnimator.forEach((anim, i) => {
        //       // anim.classList.add("icv-arrow-wrapper");
        //
        //       anim.style.cssText = `
        // ${
        //           this.settings.verticalMode
        //               ? `transform: translateY(${
        //                   i === 0 ? `${coord[0]}px` : `-${coord[0]}px`
        //               });`
        //               : `transform: translateX(${
        //                   i === 0 ? `${coord[0]}px` : `-${coord[0]}px`
        //               });`
        //       }
        // `;
        //   });

        control.classList.add(this.settings.prefix+"-control");

        control.style.cssText = `
    ${this.settings.verticalMode ? `height` : `width `}: var(--divider-width, 45px);
    ${this.settings.verticalMode ? `top` : `left `}: calc(${
            this.settings.startingPoint
        }% - (var(--divider-width, 45px) / 2));
    ${
            "ontouchstart" in document.documentElement
                ? ``
                : this.settings.smoothing
                ? `transition: ${this.settings.smoothingAmount}ms ease-out;`
                : ``
        }
    `;

        uiLine.classList.add(this.settings.prefix+"-control-line");

        uiLine.style.cssText = `
      ${this.settings.verticalMode ? `height` : `width `}: var(--divider-line-width, 2px);
      background: var(--divider-bg-color, #2962FF);
    `;

        let uiLine2 = uiLine.cloneNode(true);

        // circle.classList.add(this.settings.prefix+"-control-drag");
        // circle.style.cssText = `

        // ${
        //       this.settings.addCircleBlur &&
        //       `-webkit-backdrop-filter: blur(5px); backdrop-filter: blur(5px)`
        //   };

        //   border: ${this.settings.lineWidth}px solid ${this.settings.controlLineColor};
        //   ${
        //         this.settings.controlShadow &&
        //         `box-shadow: 0px 0px 15px rgba(0,0,0,0.33)`
        //     };
        // `;

        control.appendChild(uiLine);
        // this.settings.addCircle && control.appendChild(circle);
        control.appendChild(arrows);
        control.appendChild(uiLine2);

        this.arrowContainer = arrows;

        this.control = control;
        this.el.appendChild(control);
    }

    _getImages() {
        let children = this.el.querySelectorAll("img, ." + this.settings.prefix+"-label");
        this.el.innerHTML = "";
        children.forEach((img) => {
            this.el.appendChild(img);
        });

        let childrenImages = [...children].filter(
            (element) => element.nodeName.toLowerCase() === "img"
        );

        //  this.settings.verticalMode && [...children].reverse();
        this.settings.verticalMode && childrenImages.reverse();

        for (let idx = 0; idx <= 1; idx++) {
            let child = childrenImages[idx];

            child.classList.add(this.settings.prefix+"-img");
            child.classList.add(idx === 0 ? this.settings.prefix+`-img-a` : this.settings.prefix+`-img-b`);

            if (idx === 1) {
                let wrapper = document.createElement("div");
                // let afterUrl = childrenImages[1].src;
                wrapper.classList.add(this.settings.prefix+"-wrapper");
                wrapper.style.cssText = `
            width: ${100 - this.settings.startingPoint}%; 
            height: ${this.settings.startingPoint}%;

            ${
                    "ontouchstart" in document.documentElement
                        ? ``
                        : this.settings.smoothing
                        ? `transition: ${this.settings.smoothingAmount}ms ease-out;`
                        : ``
                }
        `;

                wrapper.appendChild(child);
                this.wrapper = wrapper;
                this.el.appendChild(this.wrapper);
            }
        }
    }
}