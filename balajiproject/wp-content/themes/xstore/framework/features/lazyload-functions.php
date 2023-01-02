<?php
/**
 * Description
 *
 * @package    lazyload.php
 * @since      8.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

// remove lazy on email sent
add_action('woocommerce_email_before_order_table', function(){
	remove_filter( 'wp_get_attachment_image_attributes', 'etheme_lazy_attachment_attrs', 10, 3 );
});
add_action('woocommerce_email_after_order_table', function() {
	add_filter( 'wp_get_attachment_image_attributes', 'etheme_lazy_attachment_attrs', 10, 3 );
});
// woocommerce email header/footer
add_action( 'woocommerce_email_header', function(){
	remove_filter( 'wp_get_attachment_image_attributes', 'etheme_lazy_attachment_attrs', 10, 3 );
});
add_action('woocommerce_email_footer', function() {
	add_filter( 'wp_get_attachment_image_attributes', 'etheme_lazy_attachment_attrs', 10, 3 );
});

add_action( 'wp', 'etheme_lazy_attachment' );

if ( !function_exists('etheme_lazy_attachment')) {
	function etheme_lazy_attachment() {
		add_filter( 'wp_get_attachment_image_attributes', 'etheme_lazy_attachment_attrs', 10, 3 );
	}
}

// **********************************************************************//
// ! add LQIP attr
// **********************************************************************//

if ( !function_exists('etheme_lazy_attachment_attrs')) {
	function etheme_lazy_attachment_attrs( $attr, $attachment, $size ) {
		
		if ( strpos( $attr['class'], 'lazyload' ) !== false || isset( $_GET['vc_editable'] ) ) {
			return $attr;
		}
		
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return $attr;
		}
		
		if ( is_admin() ) {
			return $attr;
		}
		
		$type = get_theme_mod( 'images_loading_type_et-desktop', 'lazy' );
		
		switch ( $type ) {
			case 'lqip':
				// Set LQIP
				if ( $size == 'woocommerce_thumbnail' ) {
					$placeholder = wp_get_attachment_image_src( $attachment->ID, 'etheme-woocommerce-nimi' );
				} else {
					$placeholder = wp_get_attachment_image_src( $attachment->ID, 'etheme-nimi' );
				}

//			$placeholder = $placeholder[0];
			if ( strpos($attr['class'], 'attachment-woocommerce_single') === false  ) {
				$attr['data-src'] = $attr['src'];
			}
				$attr['src']   = $placeholder[0];
				$attr['class'] .= ' lazyload lazyload-lqip et-lazyload-fadeIn';
				break;
			case 'lazy':
				// return $attr;
				$attr['class'] .= ' lazyload lazyload-simple et-lazyload-fadeIn';
				if ( ! isset( $attr['data-src'] ) ) {
					$attr['data-src'] = $attr['src'];
					// only for single product image zoom
//				$attr['data-l-src'] = $attr['src'];
//				if ( isset($attr['data-etheme-single-main']) ){
//					return $attr;
//				}
				}
//			else {
//				$attr['data-src']  = $attr['src'];
//			}
//			unset( $attr['src'] );
				$attr['src'] = etheme_placeholder_image( $size, $attachment->ID );
				break;
			default:
				return $attr;
				break;
		}

//	$attr['data-sizes']    = 'auto';
		
		// Set srcset
		if ( isset( $attr['srcset'] ) ) {
			$attr['data-srcset'] = $attr['srcset'];
			// $attr['srcset'] = $srcset;
			unset( $attr['srcset'] );
		}
		
		return $attr;
	}
}

if( ! function_exists( 'etheme_placeholder_image' ) ) {
	function etheme_placeholder_image( $size, $id = false) {
		
		$uploaded = get_theme_mod( 'preloader_images', '' );
		
		if( ! empty( $uploaded ) && is_array( $uploaded ) && ! empty( $uploaded['url'] ) && ! empty( $uploaded['id'] ) ) {
			return $uploaded['url'];
		}
		
		if ( !$id ) {
			return ETHEME_BASE_URI . 'images/lazy' . ( get_theme_mod( 'dark_styles', 0 ) ? '-dark' : '' ) . '.png';
		}
		
		// Get size from array
		if( is_array( $size) ) {
			$width = $size[0];
			$height = $size[1];
		} else {
			// Take it from the original image
			$image = wp_get_attachment_image_src($id, $size);
			$image = wp_get_attachment_image_src($id, $size);

			if ($image) {
				$width = $image[1];
				$height = $image[2];
			} else {
				$width = 1;
				$height = 1;
			}
		}
		
		$placeholder_size = etheme_get_placeholder_size( $width, $height );
		$width = $placeholder_size[0];
		$height = $placeholder_size[1];
		
		$placeholder_image = (int)get_option( 'xstore_placeholder_image', 0 );
		if ( $placeholder_image ) {
			
			if ( $width == $height ) {
				return wp_get_attachment_image_url( $placeholder_image, array(1,1) );
			}
			
			if ( apply_filters('et_lazy_load_intermediate_size', true) && !image_get_intermediate_size( $placeholder_image, array( absint( $width ), absint( $height ) ) ) ) {
				if ( function_exists( 'wpb_resize' ) ) {
					$image = wpb_resize( $placeholder_image, null, $width, $height, true);
					if ( isset($image['url'])) {
						return $image['url'];
					}
				}
				elseif ( defined('ELEMENTOR_PATH') ) {
					if ( ! class_exists( 'Group_Control_Image_Size' ) ) {
						require_once ELEMENTOR_PATH . '/includes/controls/groups/image-size.php';
					}
					return \Elementor\Group_Control_Image_Size::get_attachment_image_src(
						$placeholder_image,
						'image',
						array(
							'image' => array(
								'id' => $placeholder_image,
							),
							'image_custom_dimension' => array('width' => $width, 'height' => $height),
							'image_size' => 'custom',
							'hover_animation' => ' '
						)
					);
				}
			}
			return wp_get_attachment_image_url( $placeholder_image, $size );
		}
		
		return ETHEME_BASE_URI . 'images/lazy' . ( get_theme_mod( 'dark_styles', 0 ) ? '-dark' : '' ) . '.png';
	}
}


// **********************************************************************//
// Generate placeholder preview small size.
// **********************************************************************//
if( ! function_exists( 'etheme_get_placeholder_size' ) ) {
	function etheme_get_placeholder_size( $x0, $y0 ) {
		
		$x = $y = 100; // could be small but this one good for most images
		
		if( $x0 < $y0) {
			$y = ($x * $y0) / $x0;
		}
		
		if( $x0 > $y0) {
			$x = ($y * $x0) / $y0;
		}
		
		return array((int) ceil( $x ), (int) ceil( $y ) );
	}
}

// Ajaxify functions

/**
 * Set lazyload buffer
 * @param string $data
 * @return string
 */
function etheme_ajaxify_set_lazyload_buffer($data){
	if (empty($data)){
		return false;
	}
	
	$buffer     = (array)get_option('etheme_ajaxify_buffer');
	$hash       = hash('crc32', json_encode($data));
	
	$buffer[$hash] = $data;
	update_option('etheme_ajaxify_buffer', $buffer, false);
	
	return $hash;
}

/**
 * Get lazyload buffer
 * @param string $key
 * @return string
 */
function etheme_ajaxify_get_lazyload_buffer($key){
	$buffer = (array)get_option('etheme_ajaxify_buffer');
	return $buffer[$key];
}

/**
 * Delete lazyload buffer
 */
function etheme_ajaxify_delete_lazyload_buffer(){
	delete_option('etheme_ajaxify_buffer');
}

add_action('wp_ajax_etheme_ajaxify', 'etheme_ajaxify');
add_action('wp_ajax_nopriv_etheme_ajaxify', 'etheme_ajaxify');

/**
 * Ajaxify Widgets, Shortcode and Blocks
 */
function etheme_ajaxify(){
	define('DOING_ETHEME_AJAXIFY', true);
	
	$data = json_decode(etheme_decoding($_POST['data']), true);
	
	if (!empty($data[1])){
		global $post;
		$post = get_post($data[1]);
	}
	
	switch ($data[0]){
		case 'nav-menu':
			$args = etheme_ajaxify_get_lazyload_buffer($data[2]);
			$args->echo = true;
			add_filter('theme_mod_menu_dropdown_ajax', '__return_false');
			add_filter('theme_mod_menu_dropdown_ajax_cache', '__return_false');
			add_filter('theme_mod_menu_cache', '__return_false');
			add_filter('menu_dropdown_ajax', '__return_false');
			wp_nav_menu($args);
			break;
		case 'widget':
			$widget_data = etheme_ajaxify_get_lazyload_buffer($data[2]);
			add_filter('et_ajax_widgets', '__return_false');
			add_filter('woocommerce_widget_cart_is_hidden', '__return_false');
			the_widget($widget_data[0], $widget_data[1], $widget_data[2]);
			break;
		case 'elementor':
			$widget_data = etheme_ajaxify_get_lazyload_buffer($data[2]);
			add_filter('etheme_output_shortcodes_inline_css', '__return_true');
			$document = Elementor\Plugin::$instance->documents->get($data[1]);
			Elementor\Plugin::$instance->documents->switch_to_document( $document );
			echo $document->render_element($widget_data);
			break;
	}
	die;
}

// check if core is enabled because it uses functions from core plugin
if ( defined('ET_CORE_VERSION') ) {
	// Lazyload widgets
	add_filter( 'widget_display_callback', 'etheme_ajaxify_widgets', 10, 3 );
}
/**
 * Filter for widget_display_callback to modify html output for lazyloading
 * @param WP_Widget instance
 * @param object WP_Widget
 * @param array args
 * @return string
 */
function etheme_ajaxify_widgets($instance, $that, $args){
	$etheme_ajaxify_widgets = array_filter((array)get_theme_mod('widgets_ajaxify', array()));
	if ( !in_array(get_class($that), $etheme_ajaxify_widgets) || defined('DOING_ETHEME_AJAXIFY') || wp_doing_ajax() || isset($_GET['et_ajax']) || !apply_filters('etheme_ajaxify_lazyload_widget', true, $instance, $args) ) {
		return $instance;
	}
	add_filter('etheme_ajaxify_script', '__return_true');
	add_filter('et_ajax_widgets', '__return_false');
	$request = etheme_encoding(json_encode(array('widget', get_the_ID(), etheme_ajaxify_set_lazyload_buffer(array(get_class($that), $instance, $args)))));
	unset($instance['title']);
	unset($instance['text']);
	// create simplest widget instead of the origin one
	$new_that = new WP_Widget_Text;
	$args['before_widget'] = '<span class="etheme-ajaxify-lazy-wrapper etheme-ajaxify-replace etheme-ajaxify-skeleton skeleton-body" data-type="widget" data-request="' . $request . '">';
	$args['after_widget'] = '</span>';
	$new_that->widget( $args, $instance );
	return false;
}

// check if core is enabled because it uses functions from core plugin
if ( defined('ET_CORE_VERSION') ) {
	// Lazyload nav menu
	add_filter( 'wp_nav_menu', 'lazyload_nav_menu', 10, 2 );
}
/**
 * Lazyload nav menu
 */
function lazyload_nav_menu($nav_menu, $args){
	$lazyload_nav_menus = array_filter((array)get_theme_mod('menus_ajaxify', array()));
	if ( !in_array($args->menu, $lazyload_nav_menus) || defined('DOING_ETHEME_AJAXIFY') || wp_doing_ajax() || etheme_is_rest() || isset($_GET['et_ajax']) || !apply_filters('etheme_ajaxify_lazyload_nav_menu', true, $nav_menu, $args)) {
		return $nav_menu;
	}
	
	add_filter('etheme_ajaxify_script', '__return_true');
	
	$extra_class = '';
	switch (apply_filters('etheme_ajaxify_nav_menu_type', '') ) {
		case 'all-departments':
			$extra_class .= ' menu';
			break;
	}
	// Add request data
	$request = etheme_encoding(json_encode(array('nav-menu', get_the_ID(), etheme_ajaxify_set_lazyload_buffer($args))));
	return '<span class="etheme-ajaxify-lazy-wrapper etheme-ajaxify-replace'.$extra_class.'" data-type="nav-menu" data-request="'.$request.'"></span>';
}

add_action('wp_footer', function () {
	if ( !apply_filters('etheme_ajaxify_script', false) ) return;
	$preload_point = 50;
	
	echo '<script data-dont-merge>(function () {
        function iv(a) {
            if (typeof a.getBoundingClientRect !== "function") {
                return false;
            }
            var b = a.getBoundingClientRect();
            return (
                b.bottom + ' . $preload_point . '  >= 0 &&
                b.right + ' . $preload_point . ' >= 0 &&
                b.top - ' . $preload_point . ' <= (window.innerHeight || document.documentElement.clientHeight) &&
                b.left - ' . $preload_point . ' <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
        function ll() {
            var elements = document.querySelectorAll(".etheme-ajaxify-lazy-wrapper:not(.etheme-ajaxify-loading)");
            elements.forEach(function (element) {
                if (iv(element)) {
                    element.classList.add("etheme-ajaxify-loading");
                    var parent = jQuery(element).parent();
                    if ( parent.hasClass("et_b_header-menu")  ) {
                        parent.addClass("full-width");
                    }
                    var data = element.dataset["request"];
                    var type = element.dataset["type"];
                    var xhttp = new XMLHttpRequest();
                   
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            var elementor_widget = type == "elementor" ? jQuery(element).parents(".elementor-element") : "";
                            if (element.className.match(/etheme-ajaxify-replace/)) {
                                
                                // fix for double wrapper of .elementor-widget-container
                                if ( jQuery(element).parent().hasClass("elementor-widget-container"))
                                    jQuery(element).parent().parent().html(this.responseText);
                                else
                                    element.outerHTML = this.responseText;
                            } else {
                                element.innerHTML = this.responseText;
                            }
                            parent.removeClass("full-width");
                            element.classList.remove("etheme-ajaxify-lazy-wrapper");
                            element.classList.remove("etheme-ajaxify-skeleton");
                            element.classList.remove("skeleton-body");
                            element.classList.remove("etheme-ajaxify-lazy-marker");
                            element.classList.remove("etheme-ajaxify-loading");
                            if ( window.elementorFrontend && elementor_widget.length ) {
                                elementorFrontend.elementsHandler.runReadyTrigger( elementor_widget );
                                setTimeout(function(){
                                    jQuery(parent).removeClass("animated fadeIn");
                                }, 500);
                                
                                if ( elementor_widget.parents(".elementor-container").find(".etheme-elementor-sticky-column").length ) {
                                    elementor_widget.parents(".elementor-container").find(".etheme-elementor-sticky-column").each(function(index, element) {
                                        elementorFrontend.elementsHandler.runReadyTrigger( element );
                                    });
                                }
                                
                                if ( etTheme.swiperFunc !== undefined )
		                            etTheme.swiperFunc();
                            }
                                
                            element.dispatchEvent(new Event("etheme-ajaxify-finished"));
                            
	                        if ( ["widget", "shortcode", "nav-menu"].includes(type) ) {
		                        if ( etTheme.resizeVideo !== undefined )
		                            etTheme.resizeVideo();
		                        if ( etTheme.swiperFunc !== undefined )
		                            etTheme.swiperFunc();
		                        if ( etTheme.sidebarCanvas !== undefined )
		                            etTheme.sidebarCanvas();
		                        if ( etTheme.stickySidebar !== undefined )
		                            etTheme.stickySidebar();
		                        if ( etTheme.global_image_lazy !== undefined )
		                            etTheme.global_image_lazy();
	                            
	                            if ( this.responseText.match(/widget_shopping_cart/) )
	                                jQuery(document.body).trigger("wc_fragment_refresh");
	                                
                                // for static blocks but also others who has elementor content
                                if ( this.responseText.match(/elementor-element/) ) {
                                    if ( window.elementorFrontend ) {
                                        parent.find(".elementor-element").each(function(index, element) {
                                            elementorFrontend.elementsHandler.runReadyTrigger( element );
                                        });
                                    }
                                }
                            }
                        };
                    }
                    xhttp.open("POST", "' . admin_url( "admin-ajax.php" ) . '", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send("action=etheme_ajaxify&data=" + encodeURIComponent(data));
                }
            });
            if (elements.length > 0) {
                requestAnimationFrame(ll);
            }
        }
        requestAnimationFrame(ll);
    })();
	</script>';
	
}, 99999);