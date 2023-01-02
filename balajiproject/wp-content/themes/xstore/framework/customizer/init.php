<?php
/**
 * Global actions/filter for customizer
 *
 * @package    init.php
 * @since      8.3.6
 * @author     Stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Etheme Customize Init.
 *
 * Add custom search form for WordPress customizer.
 *
 * @since   8.3.6
 * @version 1.0.0
 */
class Etheme_Customize_Init {
	// ! Main construct/ just leave it empty
	function __construct() {
		$this->includes();
		$this->actions();
	}
	
	public function includes() {
		require_once( apply_filters( 'etheme_file_url', ETHEME_CODE . 'customizer/search/class-customize-search.php' ) );
	}
	
	public function actions() {
		
		// customizer Kirki loader style
		add_action( 'wp_head', array( $this, 'head_config_customizer' ), 99 );
		
		add_action( 'init', function () {
			if ( get_query_var( 'et_is_customize_preview', false ) ) {
				// dequeue WooZone style
				add_action( "admin_print_styles", function () {
					wp_dequeue_style( 'WooZone-main-style' );
				} );
			}
			
		}, 10 );
		
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'etheme_load_admin_styles_customizer' ) );
		
		add_action( 'customize_controls_print_styles', array( $this, 'etheme_customizer_css' ), 99 );
		
		add_action( 'customize_controls_print_scripts', array( $this, 'customizer_js' ), 99 );
		
		add_action( 'customize_preview_init', array( $this, 'previewer_scripts' ) );
		
		add_action( 'customize_register', array( $this, 'partial_refresh' ) );
		
		add_action( 'customize_register', array( $this, 'woocommerce_options' ) );
	}
	
	public function head_config_customizer() {
		if ( ! class_exists( 'Kirki' ) ) {
			return;
		}
		?>
        <style>
            .kirki-customizer-loading-wrapper {
                background-image: none !important;
            }

            .kirki-customizer-loading-wrapper .kirki-customizer-loading {
                background: #555 !important;
                width: 30px !important;
                height: 30px !important;
                margin: -15px !important;
            }

            body[data-elementor-device-mode]:not([data-elementor-device-mode="desktop"]) #beacon-container {
                display: none;
            }
        </style>
		<?php
	}
	
	public function etheme_load_admin_styles_customizer() {
		
		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
		
		if ( class_exists( 'Kirki' ) ) {
			wp_dequeue_style( 'woocommerce_admin_styles' );
		}
		wp_enqueue_style( 'etheme_customizer_css', ETHEME_BASE_URI . ETHEME_CODE . 'customizer/css/admin_customizer.css' );
		if ( is_rtl() ) {
			wp_enqueue_style( 'etheme_customizer_rtl_css', ETHEME_BASE_URI . ETHEME_CODE . 'customizer/css/admin_customizer-rtl.css' );
		}
		
		$icons_type = ( etheme_get_option( 'bold_icons', 0 ) ) ? 'bold' : 'light';
		wp_register_style( 'xstore-icons-font', false );
		wp_enqueue_style( 'xstore-icons-font' );
		wp_add_inline_style( 'xstore-icons-font',
			"@font-face {
		  font-family: 'xstore-icons';
		  src:
		    url('" . get_template_directory_uri() . "/fonts/xstore-icons-" . $icons_type . ".ttf') format('truetype'),
		    url('" . get_template_directory_uri() . "/fonts/xstore-icons-" . $icons_type . ".woff2') format('woff2'),
		    url('" . get_template_directory_uri() . "/fonts/xstore-icons-" . $icons_type . ".woff') format('woff'),
		    url('" . get_template_directory_uri() . "/fonts/xstore-icons-" . $icons_type . ".svg#xstore-icons') format('svg');
		  font-weight: normal;
		  font-style: normal;
		}"
		);
		
		if ( count( $xstore_branding_settings ) && isset( $xstore_branding_settings['customizer'] ) ) {
			$output = '';
			if ( isset( $xstore_branding_settings['customizer']['main_color'] ) && $xstore_branding_settings['customizer']['main_color'] ) {
				$output .= ':root {--et_admin_main-color: ' . $xstore_branding_settings['customizer']['main_color'] . ';}';
			}
			if ( isset( $xstore_branding_settings['customizer']['logo'] ) && trim( $xstore_branding_settings['customizer']['logo'] ) != '' ) {
				$output .= '#customize-header-actions {
                background-image: url("' . $xstore_branding_settings['customizer']['logo'] . '");
                background-size: contain;
            }';
			}
			wp_add_inline_style( 'etheme_customizer_css', $output );
		}
	}
	
	public function previewer_scripts() {
		
		wp_enqueue_style( 'etheme-customizer-preview-css', ETHEME_BASE_URI . ETHEME_CODE . 'customizer/css/preview.css', null, '0.1', 'all' );
		wp_enqueue_script( 'etheme-customizer-frontend-js', ETHEME_BASE_URI . ETHEME_CODE . 'customizer/js/preview.js', array( 'jquery' ), '0.1', 'all' );
		
		if ( etheme_is_activated() && get_option( 'et_documentation_beacon', false ) !== 'off' ) {
			wp_enqueue_script( 'etheme_panel_documentation', ETHEME_BASE_URI . 'framework/panel/js/documentation.js', array( 'jquery' ), false, true );
		}
	}
	
	public function etheme_customizer_css() { ?>
        <style>
            .wp-customizer:not(.ready) #customize-controls:before,
            .wp-customizer.et-preload #customize-controls:before {
                position: absolute;
                left: 0;
                top: 0;
                right: 0;
                bottom: 0;
                background: #fff;
                content: '';
                z-index: 500002;
            }

            .wp-customizer.et-preload #customize-controls:before {
                opacity: .5;
            }

            .wp-customizer:not(.ready) #customize-controls:after,
            .wp-customizer.et-preload #customize-controls:after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 30px;
                height: 30px;
                background: #555;
                margin: -15px;
                border-radius: 50%;
                -webkit-animation: sk-scaleout 1.0s infinite ease-in-out;
                animation: sk-scaleout 1.0s infinite ease-in-out;
                z-index: 500002;
            }

            @-webkit-keyframes sk-scaleout {
                0% {
                    -webkit-transform: scale(0)
                }
                100% {
                    -webkit-transform: scale(1.0);
                    opacity: 0;
                }
            }

            @keyframes sk-scaleout {
                0% {
                    -webkit-transform: scale(0);
                    transform: scale(0);
                }
                100% {
                    -webkit-transform: scale(1.0);
                    transform: scale(1.0);
                    opacity: 0;
                }
            }

        </style>
		<?php
	}
	
	public function customizer_js() { ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
				<?php
				
				$blog_id = get_option( 'page_for_posts' );
				
				if ( ! $blog_id || is_wp_error( $blog_id ) ) {
					$blog_id = get_option( 'page_on_front' );
				}
				
				if ( $blog_id ) : ?>
                wp.customize.section('blog-blog_page', function (section) {
                    section.expanded.bind(function (isExpanded) {
                        if (isExpanded) {
                            wp.customize.previewer.previewUrl.set('<?php echo esc_js( get_permalink( $blog_id ) ); ?>');
                        }
                    });
                });
				<?php endif;
				
				$single_post_link = '';
				$args = array(
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'orderby'        => 'date',
					'order'          => 'ASC',
					'posts_per_page' => 1
				);
				$loop = new WP_Query( $args );
				if ( $loop->have_posts() ) {
					while ( $loop->have_posts() ) : $loop->the_post();
						$single_post_link = get_permalink( get_the_ID() );
					endwhile;
				}
				wp_reset_postdata();
				
				if ( $single_post_link ) : ?>
                wp.customize.section('blog-single-post', function (section) {
                    section.expanded.bind(function (isExpanded) {
                        if (isExpanded) {
                            wp.customize.previewer.previewUrl.set('<?php echo esc_js( $single_post_link ); ?>');
                        }
                    });
                });
				<?php endif;
				
				$portfolio_id = get_theme_mod( 'portfolio_page', '' );
				if ( $portfolio_id ) { ?>
                wp.customize.section('portfolio', function (section) {
                    section.expanded.bind(function (isExpanded) {
                        if (isExpanded) {
                            wp.customize.previewer.previewUrl.set('<?php echo esc_js( get_permalink( $portfolio_id ) ); ?>');
                        }
                    });
                });
				<?php }
				
				if ( class_exists( 'WooCommerce' )) :
				
				$product_link = '';
				$args = array(
					'post_type'      => 'product',
					'post_status'    => 'publish',
					'orderby'        => 'date',
					'order'          => 'ASC',
					'posts_per_page' => 1
				);
				$loop = new WP_Query( $args );
				if ( $loop->have_posts() ) {
					while ( $loop->have_posts() ) : $loop->the_post();
						$product_link = get_permalink( get_the_ID() );
					endwhile;
				}
				
				if ( isset( $_REQUEST['et_multiple'] ) ) {
					$product_link = add_query_arg( 'et_multiple', $_REQUEST['et_multiple'], $product_link );
				}
				
				wp_reset_postdata(); ?>

                wp.customize.panel('shop', function (section) {
                    section.expanded.bind(function (isExpanded) {
                        if (isExpanded) {
                            wp.customize.previewer.previewUrl.set('<?php echo esc_js( wc_get_page_permalink( 'shop' ) ); ?>');
                        }
                    });
                });
                wp.customize.panel('shop-elements', function (section) {
                    section.expanded.bind(function (isExpanded) {
                        if (isExpanded) {
                            wp.customize.previewer.previewUrl.set('<?php echo esc_js( wc_get_page_permalink( 'shop' ) ); ?>');
                        }
                    });
                });
                wp.customize.panel('cart-page', function (section) {
                    section.expanded.bind(function (isExpanded) {
                        if (isExpanded) {
                            wp.customize.previewer.previewUrl.set('<?php echo esc_js( wc_get_page_permalink( 'cart' ) ); ?>');
                        }
                    });
                });

                wp.customize.section('cart-checkout-layout', function (section) {
                    section.expanded.bind(function (isExpanded) {
                        if (isExpanded) {
                            wp.customize.previewer.previewUrl.set('<?php echo esc_js( wc_get_page_permalink( 'cart' ) ); ?>');
                        }
                    });
                });
				
				<?php if ( $product_link ) { ?>
                wp.customize.panel('single_product_builder', function (section) {
                    section.expanded.bind(function (isExpanded) {
                        if (isExpanded) {
                            wp.customize.previewer.previewUrl.set('<?php echo esc_js( $product_link ); ?>');
                        }
                    });
                });
                wp.customize.panel('single-product-page', function (section) {
                    section.expanded.bind(function (isExpanded) {
                        if (isExpanded) {
                            wp.customize.previewer.previewUrl.set('<?php echo esc_js( $product_link ); ?>');
                        }
                    });
                });
				<?php } ?>

                wp.customize('woocommerce_shop_page_display', function (setting) {
                    setting.bind(function (value) {

                        if (value == 'both' || value == 'subcategories') {
                            jQuery(wp.customize.control('woocommerce_shop_page_categories_appearance').selector).show();
                        } else {
                            if (wp.customize.control('woocommerce_category_archive_display').setting.get() != 'both')
                                jQuery(wp.customize.control('woocommerce_shop_page_categories_appearance').selector).hide();
                        }
                    });
                });

                wp.customize('woocommerce_category_archive_display', function (setting) {
                    setting.bind(function (value) {

                        if (value == 'both' || value == 'subcategories') {
                            jQuery(wp.customize.control('woocommerce_shop_page_categories_appearance').selector).show();
                        } else {
                            if (wp.customize.control('woocommerce_shop_page_display').setting.get() != 'both')
                                jQuery(wp.customize.control('woocommerce_shop_page_categories_appearance').selector).hide();
                        }
                    });
                });
				
				<?php if ( get_option( 'woocommerce_shop_page_display' ) != 'both' && get_option( 'woocommerce_category_archive_display' ) != 'both') {?>
                jQuery(wp.customize.control('woocommerce_shop_page_categories_appearance').selector).hide();
				<?php }

                $xstore_wishlist_id = get_theme_mod( 'xstore_wishlist_page', '' );
                $xstore_wishlist_url = get_permalink( $xstore_wishlist_id );
				if ( !$xstore_wishlist_id ) {
                    $xstore_wishlist_ghost_id = absint(get_option( 'woocommerce_myaccount_page_id' ));
                    if ( $xstore_wishlist_ghost_id )
                        $xstore_wishlist_url = add_query_arg('et-wishlist-page', '', get_permalink($xstore_wishlist_ghost_id));
                }
                if ( $xstore_wishlist_url ) { ?>
                    wp.customize.section('xstore-wishlist', function (section) {
                        section.expanded.bind(function (isExpanded) {
                            if (isExpanded) {
                                wp.customize.previewer.previewUrl.set('<?php echo esc_js( $xstore_wishlist_url ); ?>');
                            }
                        });
                    });
                <?php }

                $xstore_compare_id = get_theme_mod( 'xstore_compare_page', '' );
                $xstore_compare_url = get_permalink( $xstore_compare_id );
                if ( !$xstore_compare_id ) {
                    $xstore_compare_ghost_id = absint(get_option( 'woocommerce_myaccount_page_id' ));
                    if ( $xstore_compare_ghost_id )
                        $xstore_compare_url = add_query_arg('et-compare-page', '', get_permalink($xstore_compare_ghost_id));
                }
                if ( $xstore_compare_url ) { ?>
                wp.customize.section('xstore-compare', function (section) {
                    section.expanded.bind(function (isExpanded) {
                        if (isExpanded) {
                            wp.customize.previewer.previewUrl.set('<?php echo esc_js( $xstore_compare_url ); ?>');
                        }
                    });
                });
                <?php }
				
				endif; ?>

                wp.customize.section('age_verify_popup', function (section) {
                    section.expanded.bind(function (isExpanded) {
                        if (isExpanded) {
                            wp.customize.previewer.refresh();
                        }
                    });
                });
            });
        </script>
		<?php
	}
	
	public function woocommerce_options( WP_Customize_Manager $wp_customize ) {
		if ( ! class_exists( 'WC_Shop_Customizer' ) ) {
			return;
		}
		
		$wp_customize->add_setting(
			'woocommerce_shop_page_categories_appearance',
			array(
				'default'           => '',
				'type'              => 'option',
				'capability'        => 'manage_woocommerce',
				'sanitize_callback' => function ( $value ) {
					$options = array( '', 'separated' );
					
					return in_array( $value, $options, true ) ? $value : '';
				},
			)
		);
		
		$wp_customize->add_control(
			'woocommerce_shop_page_categories_appearance',
			array(
				'label'       => __( 'Categories Appearance', 'xstore' ),
				'description' => __( 'Choose how to display product categories on the product archives.', 'xstore' ),
				'section'     => 'woocommerce_product_catalog',
				'settings'    => 'woocommerce_shop_page_categories_appearance',
				'type'        => 'select',
				'choices'     => array(
					''          => __( 'Merged with Products', 'xstore' ),
					'separated' => __( 'Apart from Products', 'xstore' ),
				),
			)
		);
	}
	
	public function partial_refresh( WP_Customize_Manager $wp_customize ) {
		// Abort if selective refresh is not available.
		if ( ! isset( $wp_customize->selective_refresh ) ) {
			return;
		}
		$et_partials = array();
		
		$et_partials['header_partials'] = array(
			// header parts
			array(
				'selector' => '.header-top',
				'partial'  => 'top_header_style_separator',
			),
			array(
				'selector' => 'body:not(.cart-checkout-light-header) .header-main',
				'partial'  => 'main_header_style_separator',
			),
			// cart checkout advanced layout use-case
			array(
				'selector' => 'body.cart-checkout-advanced-layout .header-main',
				'partial'  => 'cart_checkout_layout_header_separator',
			),
			array(
				'selector' => '.header-bottom',
				'partial'  => 'bottom_header_style_separator',
			),
			// vertical header
			array(
				'selector' => '#header-vertical',
				'partial'  => 'header_vertical_et-desktop',
			),
			array(
				'selector' => '#header-vertical .et_b_header-logo.et_element-top-level',
				'partial'  => 'header_vertical_logo_img_et-desktop',
			),
			array(
				'selector' => '#header-vertical .header-vertical-menu-icon-wrapper, .header-vertical-menu',
				'partial'  => 'header_vertical_menu_type_et-desktop',
			),
			// header elements
			array(
				'selector' => '.header-button-wrapper.et_element-top-level',
				'partial'  => 'button_content_separator',
			),
			array(
				'selector' => 'body:not(.cart-checkout-light-header) .site-header .et_b_header-logo.et_element-top-level',
				'partial'  => 'logo_content_separator',
			),
			array(
				'selector' => 'body.cart-checkout-advanced-layout .site-header .et_b_header-logo.et_element-top-level',
				'partial'  => 'cart_checkout_logo_img_et-desktop',
			),
			// all departments menu
			array(
				'selector' => '.et_b_header-menu.et_element-top-level .secondary-menu-wrapper',
				'partial'  => 'secondary_menu_content_separator',
			),
			array(
				'selector' => '.et_b_header-mobile-menu',
				'partial'  => 'mobile_menu_content_separator',
			),
			array(
				'selector' => '.et_b_header-wishlist.et_element-top-level',
				'partial'  => 'wishlist_content_separator',
			),
			array(
				'selector' => '.et_b_header-socials.et_element-top-level',
				'partial'  => 'header_socials_content_separator',
			),
			array(
				'selector' => '.et_b_header-languages.et_element-top-level',
				'partial'  => 'languages_content_separator',
			),
			array(
				'selector' => '.header-html_block1',
				'partial'  => 'html_block1',
			),
			array(
				'selector' => '.header-html_block2',
				'partial'  => 'html_block2',
			),
			array(
				'selector' => '.header-html_block3',
				'partial'  => 'html_block3',
			),
			array(
				'selector' => '.et_b_header-widget',
				'partial'  => 'header_widget1_content_separator',
			),
			array(
				'selector' => '.et_b_header-account.et_element-top-level',
				'partial'  => 'account_content_separator',
			),
			array(
				'selector' => '.et_b_header-search.et_element-top-level',
				'partial'  => 'search_content_separator',
			),
			array(
				'selector' => '.header-promo-text',
				'partial'  => 'promo_text_content_separator',
			),
			array(
				'selector' => '.et_b_header-newsletter.et_element-top-level',
				'partial'  => 'newsletter_content_separator',
			),
			array(
				'selector' => '.et_b_header-cart.et_element-top-level',
				'partial'  => 'cart_content_separator',
			),
			array(
				'selector' => '.header-main-menu.et_element-top-level',
				'partial'  => 'menu_content_separator',
			),
			array(
				'selector' => '.header-main-menu2.et_element-top-level',
				'partial'  => 'menu_2_content_separator',
			),
			array(
				'selector' => '.site-header .et_b_header-menu.et_element-top-level:not(.header-secondary-menu) .nav-sublist-dropdown, .site-header .header-secondary-menu.et_element-top-level .menu .nav-sublist-dropdown',
				'partial'  => 'menu_dropdown_content_separator',
			),
			array(
				'selector' => '.et_b_header-contacts.et_element-top-level',
				'partial'  => 'contacts_content_separator',
			),
		);
		
		if ( get_option( 'etheme_single_product_builder', false ) ) {
			// single product elements
			$et_partials['single_product_partials'] = array(
				array(
					'partial'  => 'product_gallery_content_separator',
					'selector' => '.woocommerce-product-gallery'
				),
				array(
					'partial'  => 'product_title_style_separator',
					'selector' => 'h1.product_title',
				),
				array(
					'partial'  => 'product_price_style_separator',
					'selector' => '.et_product-block > .price, .element > .price',
				),
				array(
					'partial'  => 'product_rating_content_separator',
					'selector' => '.woocommerce-product-rating .star-rating',
				),
				array(
					'partial'  => 'product_meta_content_separator',
					'selector' => '.product_meta',
				),
				array(
					'partial'  => 'product_cart_style_separator',
					'selector' => 'form.cart',
				),
                array(
                    'partial'  => 'single_countdown_type',
                    'selector' => '.et_product-block .product-sale-counter'
                ),
				array(
					'partial'  => 'product_tabs_content_separator',
					'selector' => '.woocommerce-tabs',
				),
				array(
					'partial'  => 'product_short_description_style_separator',
					'selector' => '.woocommerce-product-details__short-description',
				),
				array(
					'partial'  => 'product_sharing_content_separator',
					'selector' => '.single-product-socials',
				),
				array(
					'partial'  => 'product_size_guide_content_separator',
					'selector' => '.single-product-size-guide',
				),
				array(
					'partial'  => 'product_wishlist_content_separator',
					'selector' => '.single-wishlist',
				),
				array(
					'partial'  => 'product_compare_style_separator',
					'selector' => '.single-compare',
				),
				array(
					'partial'  => 'product_breadcrumbs_content_separator',
					'selector' => 'body.single-product .page-heading',
				),
				array(
					'partial'  => 'products_upsell_content_separator',
					'selector' => 'body.single-product .upsell-products',
				),
				array(
					'partial'  => 'products_cross_sell_content_separator',
					'selector' => 'body.single-product .cross-sell-products',
				),
				array(
					'partial'  => 'products_related_content_separator',
					'selector' => '.related-products',
				),
				array(
					'partial'  => 'single_product_html_block1_content_separator',
					'selector' => '.single_product-html_block',
				),
				// bought together
				array(
					'partial'  => 'single_product_bought_together_content_separator',
					'selector' => '.bought-together-products',
				),
				// sidebar settings
				array(
					'partial'  => 'single_product_layout_content_separator',
					'selector' => '.single-product .widget-area'
				)
			);
		} else {
			$et_partials['single_product_partials'] = array(
				// product brands
				array(
					'selector' => '.product_brand, .products-page-brands',
					'partial'  => 'enable_brands'
				),
				
				array(
					'selector' => 'body.single-product .page-heading',
					'partial'  => 'breadcrumb_type'
				),
				// countdown
                array(
                    'partial'  => 'single_countdown_type',
                    'selector' => '.product-information .product-sale-counter'
                ),
				// sale, outofstock
				array(
					'selector' => '.sale-wrapper',
					'partial'  => 'sale_icon'
				),
				
				// single related products
				array(
					'partial'  => 'show_related',
					'selector' => '.related-products',
				),
				
				// tabs
				array(
					'selector' => '.single .single-product .woocommerce-tabs',
					'partial'  => 'tabs_type'
				),
			);
		}
		
		$et_partials['global_partials'] = array(
			
			// breadcrumbs
			array(
				'selector' => 'body:not(.single-product) .page-heading, .cart-checkout-nav',
				'partial'  => 'breadcrumb_type',
			),
			
			// footer
			array(
				'selector' => '.footer',
				'partial'  => 'footer_columns',
			),
			array(
				'selector' => '.footer-bottom',
				'partial'  => 'copyrights_color',
			),
			
			// woocommerce
			array(
				'selector' => '.product-category',
				'partial'  => 'cat_style'
			),
			array(
				'selector' => '.content-product',
				'partial'  => 'product_view'
			),
			array(
				'selector' => '.woocommerce-cart .cross-sell-products',
				'partial'  => 'cart_products_cross_sell_type_et-desktop'
			),
			
			// toolbar
			array(
				'selector' => '.filter-wrap',
				'partial'  => 'view_mode'
			),
			
			// swatches
			array(
				'selector' => '.st-swatch-in-loop, .st-swatch-preview-single-product',
				'partial'  => 'enable_swatch'
			),
			
			// cart empty
			array(
				'selector' => '.cart-empty',
				'partial'  => 'empty_cart_content'
			),
			
			// sale, outofstock
			array(
				'selector' => '.content-product .stock, .content-product .available-on-backorder',
				'partial'  => 'sale_icon'
			),
			
			// default woocommerce checkout
			array(
				'selector' => '.woocommerce-checkout',
				'partial'  => 'wp_page_for_privacy_policy'
			),
			
			// social sharing
			array(
				'selector' => '.product-share, .share-post',
				'partial'  => 'socials',
			),
			
			// page 404 content
			array(
				'selector' => '.page-404',
				'partial'  => '404_text',
			),
			
			// mobile panel
			array(
				'selector' => '.et-mobile-panel-wrapper',
				'partial'  => 'mobile_panel_content_separator',
			),
		
		);
		
		foreach ( $et_partials['header_partials'] as $key ) {
			$wp_customize->selective_refresh->add_partial( $key['partial'],
				array(
					'selector' => $key['selector'],
				)
			);
		}
		
		foreach ( $et_partials['single_product_partials'] as $key ) {
			$wp_customize->selective_refresh->add_partial( $key['partial'],
				array(
					'selector' => $key['selector'],
				)
			);
		}
		
		foreach ( $et_partials['global_partials'] as $key ) {
			$wp_customize->selective_refresh->add_partial( $key['partial'],
				array(
					'selector' => $key['selector'],
				)
			);
		}
		
		unset( $et_partials );
	}
	
}

new Etheme_Customize_Init();