<?php  if ( ! defined('ETHEME_FW')) {exit('No direct script access allowed');}
// **********************************************************************//
// ! Add select2 styles and scripts admin widgets page
// **********************************************************************//
add_action( 'widgets_admin_page', 'etheme_load_selec2' );
function etheme_load_selec2(){
	wp_register_style( 'select2css', ETHEME_CODE_CSS . 'select2.min.css', false, '1.0', 'all' );
    wp_register_script( 'select2', ETHEME_CODE_JS . 'select2.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style( 'select2css' );
    wp_enqueue_script( 'select2' );
}

// **********************************************************************//
// ! Add admin styles and scripts
// **********************************************************************//
if(!function_exists('etheme_load_admin_styles')) {
	add_action( 'admin_enqueue_scripts', 'etheme_load_admin_styles', 150 );
	function etheme_load_admin_styles() {
		global $pagenow;

		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';
		$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

		if ( strpos($screen_id, 'et-panel') ) {
		    wp_enqueue_style('etheme_admin_panel_css', ETHEME_CODE_CSS.'et_admin-panel.css');
		    if ( strpos($screen_id, 'et-panel-sales-booster') ||
		    strpos($screen_id, 'et-panel-white-label-branding') ||
		    strpos($screen_id, 'et-panel-xstore-amp') ) {
		        wp_enqueue_style('etheme_admin_panel_options_css', ETHEME_CODE_CSS.'et_admin-panel-options.css');
		    }
		    if ( count($xstore_branding_settings) ) {
		        if ( isset($xstore_branding_settings['control_panel']) ) {
			        $colors = array();
			        $colors_output = array();
			        foreach ($xstore_branding_settings['control_panel'] as $color => $color_val) {
			            if ( strpos($color, '_color') !== false && $color_val ) {
			                $colors['--et_admin_' . str_replace('_', '-', $color)] = $color_val;
			            }
			        }
			        foreach ($colors as $color_key => $color_val) {
			            $colors_output[] = $color_key . ':' . $color_val . ' !important';
			        }
			        if ( count($colors_output)) {
	                    wp_add_inline_style('etheme_admin_panel_css', '
	                        :root {
	                            '.implode(';', $colors_output) . '
	                        }
	                    ' );
			        }
		        }
		    }
		}
		
	    wp_enqueue_style('farbtastic');
	    wp_enqueue_style('etheme_admin_css', ETHEME_CODE_CSS.'etheme_admin_backend.css');
	    if ( is_rtl() ) {
	    	wp_enqueue_style('etheme_admin_rtl_css', ETHEME_CODE_CSS.'etheme_admin_backend-rtl.css');
	    }
	    wp_enqueue_style('xstore-icons', ETHEME_CODE_CSS.'xstore-admin-icons.css');
	    wp_enqueue_style("font-awesome", get_template_directory_uri().'/css/fontawesome/4.7.0/font-awesome.min.css');
	    
        if ( count($xstore_branding_settings) ) {
		    
            if ( isset($xstore_branding_settings['advanced']) ) {
	            wp_add_inline_style('etheme_admin_css', $xstore_branding_settings['advanced']['admin_css']);
	        }
		}

	    // Variations Gallery Images script 
		if ( in_array( $screen_id, array( 'product', 'edit-product' ) ) && etheme_get_option('enable_variation_gallery', 0) ) {
			wp_enqueue_script('etheme_admin_product_variations_js', ETHEME_CODE_JS.'product-variations.js', array('etheme_admin_js', 'wc-admin-product-meta-boxes', 'wc-admin-variation-meta-boxes'), false,true);
		}
	}
}

if(!function_exists('etheme_add_admin_script')) {
	add_action('admin_init','etheme_add_admin_script', 1130);
	function etheme_add_admin_script(){
		global $pagenow;

	    add_thickbox();

	    wp_enqueue_script('theme-preview');
	    wp_enqueue_script('common');
	    wp_enqueue_script('wp-lists');
	    // wp_enqueue_script('postbox');
	    wp_enqueue_script('farbtastic');
//	    wp_enqueue_script('et_masonry', get_template_directory_uri().'/js/jquery.masonry.min.js',array(),false,true);
     
	    wp_enqueue_script('etheme_admin_js', ETHEME_CODE_JS.'admin-scripts-new.js', array(), false,true);

    	wp_localize_script( 'etheme_admin_js', 'et_variation_gallery_admin', array(
			'choose_image' => esc_html__( 'Choose Image', 'xstore' ),
			'add_image'    => esc_html__( 'Add Images', 'xstore' ),
			'menu_enabled' => etheme_get_option('et_menu_options', 1),
		) );
    	
    	$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
    	
    	if ( count($xstore_branding_settings) && isset($xstore_branding_settings['advanced']) &&
    	        $xstore_branding_settings['advanced']['screenshot'] ) {

    		$theme = wp_get_theme();

    		$xstore_branding_settings['theme_template'] = $theme->template;
	        
	         add_filter( 'wp_prepare_themes_for_js', function($themes) use ($xstore_branding_settings){
                $themes[$xstore_branding_settings['theme_template']]['screenshot'][0] = $xstore_branding_settings['advanced']['screenshot'];
                return $themes;
            } );
     
    	}

	}
}

add_action('wp_ajax_etheme_deactivate_theme', 'etheme_deactivate_theme');
if( ! function_exists( 'etheme_deactivate_theme' ) ) {
	function etheme_deactivate_theme() {
	    $url  = apply_filters('etheme_protocol_url', ETHEME_BASE_URL . 'import/xstore-demos/1/versions/');

	    $domain = get_option('siteurl'); //or home
		$domain = str_replace('http://', '', $domain);
		$domain = str_replace('https://', '', $domain);
		$domain = str_replace('www', '', $domain); //add the . after the www if you don't want it

		$activated_data = get_option( 'etheme_activated_data' );
		$activated_data = ( isset( $activated_data['purchase'] ) && ! empty( $activated_data['purchase'] ) ) ? $activated_data['purchase'] : '';

	    $url = add_query_arg(
			array(
				'deactivate' => true,
				'data' => json_encode(
					array(
						'theme'=> ETHEME_THEME_VERSION,
						'plugin' => ET_CORE_VERSION,
						'code' => $activated_data,
						'domain' => $domain
					)
				),
			),
		$url );
	    $data = wp_remote_get($url);

        $status = 'deleted';
        $data = array(
            'api_key' => 0,
            'theme' => 0,
            'purchase' => 0,
        );
        update_option( 'etheme_activated_data', maybe_unserialize( $data ) );
        update_option( 'envato_purchase_code_15780546', '' );

        echo json_encode( $status );
        die();
	}
}

add_action( 'wp_ajax_et_update_menu_ajax', 'et_update_menu_ajax' ); 
if ( ! function_exists('et_update_menu_ajax')) {

	function et_update_menu_ajax () {

		$post = $_POST['item_menu'];

		// update_post_meta( $post['db_id'], '_menu-item-disable_titles', $post['dis_titles']);
		update_post_meta( $post['db_id'], '_menu-item-anchor', sanitize_post($post['anchor']));
		update_post_meta( $post['db_id'], '_menu-item-design', sanitize_post($post['design']));
		update_post_meta( $post['db_id'], '_menu-item-design2', sanitize_post($post['design2']));
		update_post_meta( $post['db_id'], '_menu-item-column_width', $post['column_width']);
		update_post_meta( $post['db_id'], '_menu-item-column_height', $post['column_height']);

		update_post_meta( $post['db_id'], '_menu-item-sublist_width', $post['sublist_width']);

		update_post_meta( $post['db_id'], '_menu-item-columns', $post['columns']);
		update_post_meta( $post['db_id'], '_menu-item-icon_type', sanitize_post($post['icon_type']));
		update_post_meta( $post['db_id'], '_menu-item-icon', $post['icon']);
		update_post_meta( $post['db_id'], '_menu-item-label', sanitize_post($post['item_label']));
		update_post_meta( $post['db_id'], '_menu-item-background_repeat', sanitize_post($post['background_repeat']));
		update_post_meta( $post['db_id'], '_menu-item-background_position', $post['background_position']);
		update_post_meta( $post['db_id'], '_menu-item-use_img', sanitize_post($post['use_img']));
		update_post_meta( $post['db_id'], '_menu-item-widget_area', sanitize_post($post['widget_area']));
		update_post_meta( $post['db_id'], '_menu-item-static_block', sanitize_post($post['static_block']));

		echo json_encode($post);
		die();
	}
}

add_action( 'admin_footer', 'admin_template_js' );
function admin_template_js() {
	if ( !etheme_get_option('enable_variation_gallery', 0) ) {return;}
	ob_start();
	?>
		<script type="text/html" id="tmpl-et-variation-gallery-image">
		    <li class="image">
		        <input type="hidden" name="et_variation_gallery[{{data.product_variation_id}}][]" value="{{data.id}}">
		        <img src="{{data.url}}">
		        <a href="#" class="delete remove-et-variation-gallery-image"></a>
		    </li>
		</script>
	<?php 
	$data = ob_get_clean();
	echo apply_filters( 'et_variation_gallery_admin_template_js', $data );
}

add_action( 'woocommerce_save_product_variation', 'et_save_variation_gallery', 10, 2 );

add_action( 'woocommerce_product_after_variable_attributes', 'et_gallery_admin_html', 10, 3 );

if ( ! function_exists( 'et_gallery_admin_html' ) ):
		function et_gallery_admin_html( $loop, $variation_data, $variation ) {
			if ( !etheme_get_option('enable_variation_gallery', 0) ) {return;}
			$variation_id   = absint( $variation->ID );
			$gallery_images = get_post_meta( $variation_id, 'et_variation_gallery_images', true );
			if ( !(bool)$gallery_images) {
			    // Compatibility with WooCommerce Additional Variation Images plugin
			    $gallery_images = get_post_meta($variation_id, '_wc_additional_variation_images', true);
                if ( (bool)$gallery_images )
                    $gallery_images = array_filter( explode( ',', $gallery_images ) );
            }
			?>
            <div class="form-row form-row-full et-variation-gallery-wrapper">
                <h4><?php esc_html_e( 'Variation Image Gallery', 'xstore' ) ?></h4>
                <div class="et-variation-gallery-image-container">
                    <ul class="et-variation-gallery-images">
						<?php
							if ( is_array( $gallery_images ) && ! empty( $gallery_images ) ) {

								foreach ( $gallery_images as $image_id ):
									
									$image = wp_get_attachment_image_src( $image_id );
									
									?>
							        <li class="image">
							            <input type="hidden" name="et_variation_gallery[<?php echo esc_attr( $variation_id ); ?>][]" value="<?php echo esc_attr( $image_id ); ?>">
							            <img src="<?php echo esc_url( $image[ 0 ] ) ?>">
							            <a href="#" class="delete remove-et-variation-gallery-image"></a>
							        </li>
								
								<?php endforeach;
							}
						?>
                    </ul>
                </div>
                <p class="add-et-variation-gallery-image-wrapper hide-if-no-js">
                    <a href="#" data-product_variation_loop="<?php echo esc_attr($loop); ?>" data-product_variation_id="<?php echo absint( $variation->ID ) ?>" class="button add-et-variation-gallery-image"><?php esc_html_e( 'Add Gallery Images', 'xstore' ) ?></a>
                </p>
            </div>
			<?php
		}
	endif;
	
//-------------------------------------------------------------------------------
// Save Gallery
//-------------------------------------------------------------------------------
if ( ! function_exists( 'et_save_variation_gallery' ) ):
    function et_save_variation_gallery( $variation_id, $loop ) {
        if ( !etheme_get_option('enable_variation_gallery', 0) ) {return;}

        if ( isset( $_POST[ 'et_variation_gallery' ] ) ) {
            if ( isset( $_POST[ 'et_variation_gallery' ][ $variation_id ] ) ) {

                $gallery_image_ids = (array) array_map( 'absint', $_POST[ 'et_variation_gallery' ][ $variation_id ] );
                update_post_meta( $variation_id, 'et_variation_gallery_images', $gallery_image_ids );
            } else {
                delete_post_meta( $variation_id, 'et_variation_gallery_images' );
            }
        } else {
            delete_post_meta( $variation_id, 'et_variation_gallery_images' );
        }
    }
endif;
	
add_action( 'woocommerce_product_after_variable_attributes', 'et_extra_variation_options', 10, 3 );
if ( !function_exists('et_extra_variation_options')) {
    function et_extra_variation_options($loop, $variation_data, $variation) {
        if ( !etheme_get_option('variable_products_detach', false) ) {return;}
        ?>
        <div>
            <?php
                woocommerce_wp_text_input( array(
                    'id'    => "_et_product_variation_title[$loop]",
                    'label' => __( 'Custom variation title', 'xstore' ),
                    'type'  => 'text',
                    'value' => get_post_meta( $variation->ID, '_et_product_variation_title', true )
                    )
                );
            ?>
        </div>
        <?php
    }
}

add_action( 'woocommerce_save_product_variation', 'et_save_extra_variation_options', 100, 2 );
//add_action( 'woocommerce_new_product_variation', 'et_save_extra_variation_options', 10 );
//add_action( 'woocommerce_update_product_variation', 'et_save_extra_variation_options', 10 );
function et_save_extra_variation_options($variation_id, $i) {
//    if ( etheme_get_option( 'variable_products_detach', false ) ) {

        $custom_title = (isset($_POST['_et_product_variation_title']) && isset($_POST['_et_product_variation_title'][$i])) ? $_POST['_et_product_variation_title'][$i] : false;

        //$custom_title = $_POST['_et_product_variation_title'][$i];
        if ( ! empty( $custom_title ) ) {
            update_post_meta( $variation_id, '_et_product_variation_title', esc_attr( $custom_title ) );
        } else {
            delete_post_meta( $variation_id, '_et_product_variation_title' );
        }
//    }
    
    // sale price time start/end
    $_sale_price_time_start = $_POST['_sale_price_time_start'][$i];
    if ( ! empty( $_sale_price_time_start ) ) {
        update_post_meta( $variation_id, '_sale_price_time_start', esc_attr( $_sale_price_time_start ) );
    } else {
    	delete_post_meta( $variation_id, '_sale_price_time_start' );
    }

    $_sale_price_time_end = $_POST['_sale_price_time_end'][$i];
    if ( ! empty( $_sale_price_time_end ) ) {
        update_post_meta( $variation_id, '_sale_price_time_end', esc_attr( $_sale_price_time_end ) );
    } else {
    	delete_post_meta( $variation_id, '_sale_price_time_end' );
    }
    
    $_et_gtin = $_POST['_et_gtin'][$i];
    if ( ! empty( $_et_gtin ) ) {
        update_post_meta( $variation_id, '_et_gtin', esc_attr( $_et_gtin ) );
    } else {
    	delete_post_meta( $variation_id, '_et_gtin' );
    }
}

add_action( 'woocommerce_product_options_pricing', 'et_general_product_data_time_fields' );
function et_general_product_data_time_fields() {
	?>
	</div>
	<div class="options_group pricing show_if_simple show_if_external hidden">
	<?php
	
	woocommerce_wp_text_input(
	        array(
	                'id' => '_sale_price_time_start',
	                'label' => esc_html('Sale price time start', 'xstore'),
	                'placeholder' => esc_html( 'From&hellip; 12:00', 'xstore'),
                    'desc_tip' => 'true',
                    'description' => __( 'Only when sale price schedule is enabled', 'xstore' ),
                )
            );
	woocommerce_wp_text_input(
	        array(
	                'id' => '_sale_price_time_end',
	                'label' => esc_html('Sale price time end', 'xstore'),
	                'placeholder' => esc_html( 'To&hellip; 12:00', 'xstore' ),
                    'desc_tip' => 'true',
                    'description' => __( 'Only when sale price schedule is enabled', 'xstore' ),
                )
            );

}

add_action('woocommerce_product_options_sku', function() {
//    global $product_object;
   woocommerce_wp_text_input(
            array(
                'id'          => '_et_gtin',
//                'value'       => get_post_meta( $product_object->ID, '_et_gtin', true ),
                'placeholder'   => esc_html__('GTIN code', 'xstore'),
                'label'         => '<abbr title="' . esc_attr__( 'Global Trade Item Number', 'xstore' ) . '">' . esc_html__( 'GTIN', 'xstore' ) . '</abbr>',
                'desc_tip'      => true,
                'description'   => __( 'Such identifiers are used to look up product information in a database (often by entering the number through a barcode scanner pointed at an actual product) which may belong to a retailer, manufacturer, collector, researcher, or other entity.', 'xstore' ),
            )
        );
});
add_action('woocommerce_variation_options', function($loop, $variation_data, $variation) {
    woocommerce_wp_text_input(
        array(
            'id'            => "_et_gtin{$loop}",
            'name'          => "_et_gtin[{$loop}]",
            'value'         => get_post_meta( $variation->ID, '_et_gtin', true ),
            'placeholder'   => esc_html__('GTIN code', 'xstore'),
            'label'         => '<abbr title="' . esc_attr__( 'Global Trade Item Number', 'xstore' ) . '">' . esc_html__( 'GTIN', 'xstore' ) . '</abbr>',
            'desc_tip'      => true,
            'description'   => __( 'Such identifiers are used to look up product information in a database (often by entering the number through a barcode scanner pointed at an actual product) which may belong to a retailer, manufacturer, collector, researcher, or other entity.', 'xstore' ),
            'wrapper_class' => 'form-row',
        )
    );
}, 10, 3);

// -----------------------------------------
// 1. Add custom field input @ Product Data > Variations > Single Variation
  
add_action( 'woocommerce_variation_options_pricing', 'et_add_custom_field_to_variations', 10, 3 ); 
 
function et_add_custom_field_to_variations( $loop, $variation_data, $variation ) {
	?>

	<div class="form-field sale_price_time_fields">
	
		<?php
		$time_start = get_post_meta( $variation->ID, '_sale_price_time_start', true );
		$time_end = get_post_meta( $variation->ID, '_sale_price_time_end', true );
		
			woocommerce_wp_text_input( 
				array( 
					'id' => '_sale_price_time_start[' . $loop . ']', 
					'wrapper_class' => 'form-row form-row-first', 
					'label' => __( 'Sale price time start', 'xstore' ),
					'placeholder' => esc_html__( 'From&hellip; 12:00', 'xstore'),
					'value' => $time_start == 'Array' ? '' : $time_start
				)
			);
			woocommerce_wp_text_input( 
				array( 
					'id' => '_sale_price_time_end[' . $loop . ']', 
					'wrapper_class' => 'form-row form-row-last', 
					'label' => __( 'Sale price time end', 'xstore' ),
					'placeholder' => esc_html__( 'To&hellip; 12:00', 'xstore' ),
					'value' => $time_end == 'Array' ? '' : $time_end
				) 
			); 
		?> 

	</div>

<?php }

// Hook to save the data value from the custom fields 
add_action( 'woocommerce_process_product_meta', 'et_save_general_product_data_time_fields' );
function et_save_general_product_data_time_fields( $post_id ) { 
	$_sale_price_time_start = $_POST['_sale_price_time_start']; 
	update_post_meta( $post_id, '_sale_price_time_start', esc_attr( $_sale_price_time_start ) ); 
	$_sale_price_time_end = $_POST['_sale_price_time_end']; 
	update_post_meta( $post_id, '_sale_price_time_end', esc_attr( $_sale_price_time_end ) );
	
	$_et_gtin = $_POST['_et_gtin'];
	if ( !is_array($_et_gtin) )
	    update_post_meta( $post_id, '_et_gtin', esc_attr( $_et_gtin ) );
}

// Add Bought Together
add_action( 'woocommerce_product_write_panel_tabs', 'et_add_product_bought_together_panel_tab' );
add_action( 'woocommerce_product_data_panels', 'et_add_product_bought_together_panel_data' );

//if ( function_exists('wc_get_product_types') ) {
//    foreach ( wc_get_product_types() as $value => $label ) {
//        add_action( 'woocommerce_process_product_meta_' . $value, 'et_save_product_bought_together_panel_data' );
//    }
//}

add_action( 'woocommerce_process_product_meta', 'et_save_product_bought_together_panel_data' );

function et_add_product_bought_together_panel_tab() {
        $xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );
		$label = 'XStore';
		if ( count($xstore_branding_settings) && isset($xstore_branding_settings['control_panel'])) {
			if ( $xstore_branding_settings['control_panel']['icon'] )
				$icon = $xstore_branding_settings['control_panel']['icon'];
			if ( $xstore_branding_settings['control_panel']['label'] )
				$label = $xstore_branding_settings['control_panel']['label'];
        }
        ?>
        <li class="et_bought_together_options et_bought_together_tab show_if_simple show_if_external">
            <a href="#et_bought_together_product_data"><span>
            <?php echo esc_html__( 'Bought together', 'xstore' ); ?>
            <?php echo '<span class="et-brand-label" style="margin: 0; margin-inline-start: 5px; background: var(--et_admin_main-color, #A4004F); letter-spacing: 1px; font-weight: 400; display: inline-block; border-radius: 3px; color: #fff; padding: 3px 2px 2px 3px; text-transform: uppercase; font-size: 8px; line-height: 1;">'.$label.'</span>'; ?>
            </span></a>
        </li>
        <?php
    }

function et_add_product_bought_together_panel_data() {
        global $post;
        $exclude_types = wc_get_product_types();
        unset($exclude_types['simple']);
        $exclude_types = array_keys($exclude_types);
        ?>
        <div id="et_bought_together_product_data" class="panel woocommerce_options_panel">
            <div class="options_group">
                <p class="form-field">
                    <label for="et_bought_together_ids"><?php _e( 'Products', 'xstore' ); ?></label>

                        <select class="wc-product-search" multiple="multiple" style="width: 50%;" id="et_bought_together_ids" name="et_bought_together_ids[]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'xstore' ); ?>" data-action="woocommerce_json_search_products_and_variations" data-exclude="<?php echo intval( $post->ID ); ?>" data-exclude_type="<?php echo implode(',', $exclude_types); ?>">

                            <?php
                                $product_ids = array_filter( array_map( 'absint', (array) get_post_meta( $post->ID, '_et_bought_together_ids', true ) ) );

                                foreach ( $product_ids as $product_id ) {
                                    $product = wc_get_product( $product_id );
                                    if ( is_object( $product ) ) {
                                        echo '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
                                    }
                                }
                            ?>
                        </select>

                    <?php echo wc_help_tip( __( 'Bought Together are products which you recommend to be bought along with this product. Only simple products and product variations can be added as accessories.', 'xstore' ) ); ?>
                </p>
            </div>
        </div>
        <?php
    }

function et_save_product_bought_together_panel_data( $post_id ) {
    $et_bought_together = isset( $_POST['et_bought_together_ids'] ) ? array_map( 'intval', (array) $_POST['et_bought_together_ids'] ) : array();
    update_post_meta( $post_id, '_et_bought_together_ids', $et_bought_together );
}

// WooCommerce settings
add_filter('woocommerce_account_settings', function($settings) {
    $updated_settings = array();

      foreach ( $settings as $section ) {
          
          $updated_settings[] = $section;
    
        // at the bottom of the General Options section
        if ( isset( $section['id'] ) && 'account_registration_options' == $section['id'] &&
           isset( $section['type'] ) && 'sectionend' == $section['type'] ) {
            
            $updated_settings[] = array(
                'type'  => 'et_custom_section_start',
                'id' => 'et_custom_section_start'
            );
            
            $updated_settings[] = array(
                'title' => __( 'XStore "My account" page settings', 'xstore' ),
                'type'  => 'title',
                'id'    => 'et_wc_account_options',
            );
            
              $updated_settings[] = array(
                'title'    => __( 'Account page type', 'xstore' ),
                'id'       => 'et_wc_account_page_type',
                'default'  => 'new',
                'type'     => 'select',
                'options'  => array(
                        'default'     => esc_html__( 'Default', 'xstore' ),
                        'new'         => esc_html__( 'New', 'xstore' ),
                    ),
                'autoload'      => false
                );
    
              $updated_settings[] = array(
                'name'     => __( 'Account banner', 'xstore' ),
                'id'       => 'et_wc_account_banner',
                'type'     => 'textarea',
                'css'      => 'min-width:300px;',
                'desc_tip'     => __( 'You can add simple html or staticblock shortcode', 'xstore' ),
                'autoload'      => false
              );
              
              $updated_settings[] = array(
                'title'    => __( 'Products type', 'xstore' ),
                'id'       => 'et_wc_account_products_type',
                'default'  => 'random',
                'type'     => 'select',
                'options'  => array(
                        'featured'     => esc_html__( 'Featured', 'xstore' ),
                        'sale'         => esc_html__( 'On sale', 'xstore' ),
                        'bestsellings' => esc_html__( 'Bestsellings', 'xstore' ),
                        'none' => esc_html__( 'None', 'xstore' ),
                        'random'       => esc_html__( 'Random', 'xstore' ),
                    ),
                'autoload'      => false
                );
                $updated_settings[] = array(
                    'title'    => __( 'Navigation icons', 'xstore' ),
                    'desc'          => __( 'Show icons on the "My account" page for the account navigation', 'xstore' ),
                    'id'            => 'et_wc_account_nav_icons',
                    'default'       => 'yes',
                    'type'          => 'checkbox',
                    'autoload'      => false
                );
              
              $updated_settings[] = array(
                    'type' => 'sectionend',
                    'id'   => 'et_wc_account_options',
                );
              
              $updated_settings[] = array(
                'type'  => 'et_custom_section_end',
                'id'  => 'et_custom_section_end',
            );
        }
        
      }
    
      return $updated_settings;
});

add_action('woocommerce_admin_field_et_custom_section_start', function() {
    echo '<div class="et-wc-section-wrapper">';
});
add_action('woocommerce_admin_field_et_custom_section_end', function() {
    echo '</div>';
});

// WooCommerce status
add_filter('woocommerce_debug_tools', function($settings) {
   $settings['clear_et_brands_transients'] = array(
        'name'   => __( 'Brands transients', 'xstore' ),
        'button' => __( 'Clear transients', 'xstore' ),
        'desc'   => __( 'This tool will clear the brands transients cache.', 'xstore' ),
        'callback' => 'etheme_clear_brands_transients'
    );
    return $settings;
});

function etheme_clear_brands_transients() {
    delete_transient('wc_layered_nav_counts_brand');
}

add_filter('et_ajax_widgets', '__return_false');
add_filter('etheme_ajaxify_lazyload_widget', '__return_false');
add_filter('etheme_ajaxify_elementor_widget', '__return_false');

if (etheme_get_option('old_widgets_panel_type', 0)){
    add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
	add_filter( 'use_widgets_block_editor', '__return_false' );
}

add_action( 'admin_enqueue_scripts', function ($hook){
    if ( 'nav-menus.php' == $hook ) {
        wp_enqueue_script('etheme_admin_menus_js', ETHEME_CODE_JS.'admin-menus.js', array(), false,true);
    }
    if ( 'themes.php' == $hook ) {
        wp_enqueue_script('etheme_admin_major_update_js', ETHEME_CODE_JS.'admin-major-version.js', array(), false,true);
    }
} );

add_action('vc_backend_editor_enqueue_js_css', function () {
    wp_enqueue_script('etheme_admin_vc_js', ETHEME_CODE_JS.'admin-vc.js', array('vc-backend-actions-js'), false,true);
});

// remove fake sales transients for products in order on it's status change action
if ( get_option('xstore_sales_booster_settings_fake_product_sales', false) ) {
    add_action( 'woocommerce_order_status_changed', function ($order_id) {
        
         $orders = get_posts( array(
                    'numberposts' => -1,
                    'post_type'   => array( 'shop_order' ),
                    'post_status' => array_keys(wc_get_order_statuses()),
                    'post__in' => array($order_id)
                ));
         
                foreach ( $orders as $order_id ) {
                    $order = wc_get_order( $order_id );
                    foreach ( $order->get_items() as $item_id => $item_values ) {
                        delete_transient( 'etheme_fake_product_sales_' . $item_values->get_product_id() );
                    }
                }
            
     }, 30, 1 );
}

