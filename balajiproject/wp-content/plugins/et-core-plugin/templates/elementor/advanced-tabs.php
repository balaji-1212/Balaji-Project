<?php
use ETC\App\Traits\Elementor;

$advancedtabnonce = wp_create_nonce( 'etheme_advancedtabnonce' );
$settings['hide_buttons'] = ( 'yes' == $settings['hide_buttons'] ) ? '' : 'yes';?>
<div <?php echo $et_tab_wrapper; ?>>
	<div class="et-tabs-nav">
		<ul <?php echo $et_tab_ul; ?> data-wid="<?php echo esc_attr( $_wid ); ?>"  data-nonce="<?php echo $advancedtabnonce; ?>">
			<?php
			$count = 0;
			if ( in_array( 'active-default', array_column( $settings['et_tabs_tab'], 'et_tabs_tab_show_as_default' ) ) ) {
				$default_tab = true;
			}
			
			$migration_allowed = \Elementor\Icons_Manager::is_migration_allowed();
			
			foreach ( $settings['et_tabs_tab'] as $tab ):
				$tab['navigation_position_style'] =	$settings['navigation_position_style'];
				$tab['navigation_position'] 	  =	$settings['navigation_position'];
				$tab['navigation_type'] 	  	  =	$settings['navigation_type'];
				$tab['navigation_style'] 	  	  =	$settings['navigation_style'];
				$tab['hide_buttons_for']          = $settings['hide_buttons_for'];
				$tab['hide_buttons']              = $settings['hide_buttons'];
				// Pagination
				$tab['pagination_type']         = $settings['pagination_type'];
				$tab['hide_fo']         		= $settings['hide_fo'];
				$tab['default_color']         	= $settings['default_color'];
				$tab['active_color']         	= $settings['active_color'];
				
				// Slider settings
				$tab['large'] = $tab['notebook'] = $tab['slides'];
				$tab['tablet_land'] = $tab['tablet_portrait'] = isset($tab['slides_tablet']) ? $tab['slides_tablet'] : 2;
				$tab['mobile'] = isset($tab['slides_mobile']) ? $tab['slides_mobile'] : 1;
								
				if( 'nav-bar' === $settings['navigation_position'] ):
					
					$visibility = 'none';
					
					if( (isset( $default_tab ) && 'active-default' == $tab['et_tabs_tab_show_as_default']) ||
                        (!isset( $default_tab ) && '' != $tab['et_tabs_tab_show_as_default'] && 0 === $count) ){
						$visibility = 'flex';
					}
					
					$count++;
					
					echo Elementor::slider_navigation( $settings, $tab, $visibility );
                
                endif; ?>
				
				<?php if( '' != $tab['et_tabs_content_title'] ): ?>
                    <li data-id="<?php echo esc_attr( $tab['_id'] ); ?>" class="skip et-content-title hidden">
                        <?php echo '<span>' . $tab['et_tabs_content_title'] . '</span>'; ?>
                    </li>
                <?php endif; ?>
            
				<li data-json="<?php echo esc_attr( wp_json_encode( $tab ) ); ?>" data-id="<?php echo esc_attr( $tab['_id'] ); ?>" class="<?php echo esc_attr($tab['et_tabs_tab_show_as_default']); ?> et-tab-nav">
					<?php if ( 'yes' === $settings['et_tabs_icon'] ):
                        if ( $tab['et_tabs_icon_type'] === 'icon' ) {
	                        if ( ( empty( $tab['et_tabs_tab_title_icon_fa'] ) && $migration_allowed ) || isset( $tab['__fa4_migrated']['et_tabs_tab_title_icon'] ) ) :
		                        \Elementor\Icons_Manager::render_icon( $tab['et_tabs_tab_title_icon'] );
	                        else : ?>
                                <i <?php echo $this->get_render_attribute_string( 'et_tabs_tab_title_icon' ); ?>></i>
	                        <?php endif;
                        } elseif ( $tab['et_tabs_icon_type'] === 'image' ) {
	                        echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $tab, 'et_tabs_tab_title_image' ); ?>
						<?php }
					endif;
					
                    if( 'yes' == $settings['et_tabs_title'] ): ?>
						<span class="et-tab-title">
                            <?php echo $tab['et_tabs_tab_title']; ?>
                        </span>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php if ( $settings['et_tabs_mobile_select'] ) : ?>
            <select class="et-tabs-mob-nav">
				<?php foreach ($settings['et_tabs_tab'] as $tab): ?>
                    <option value="<?php echo $tab['_id']; ?>" <?php echo 'inactive' != $tab['et_tabs_tab_show_as_default'] ? 'selected' : ''; ?>>
						<?php echo $tab['et_tabs_tab_title']; ?>
                    </option>
				<?php endforeach; ?>
            </select>
		<?php endif; ?>
    </div>
	<div class="et-tabs-content">
		<?php $count = 0;
		if ( in_array( 'active-default', array_column($settings['et_tabs_tab'], 'et_tabs_tab_show_as_default' ) ) ) {
			$skip = true;
		}
		
		foreach ( $settings['et_tabs_tab'] as $tabs ):
			
			if ( isset( $skip ) && 'active-default' !== $tabs['et_tabs_tab_show_as_default'] ) {
				continue;
			}
			
			if ( ! isset( $skip ) && 0 < $count ) {
				continue;
			}
			
			$count++;
			
			$atts = array();
			?>
			<div data-id="<?php echo esc_attr( $tabs['_id'] ); ?>" class="clearfix <?php echo esc_attr($tabs['et_tabs_tab_show_as_default']);?> <?php echo esc_attr( $settings['navigation_position_style'] );?> <?php echo ( 'middle-inside' === $settings['navigation_position'] ) ? esc_attr( 'middle-inside' ): ''; ?>">
				<?php
				foreach ( $tabs as $key => $tab ):
					
					if ( $tab ) {
						switch ( $key ) {
							case 'ids':
							case 'taxonomies':
								$atts[$key] = !empty( $tab ) ? implode( ',',$tab ) : array();
								break;
							case 'slides':
								$atts['large'] = $atts['notebook'] = $tab;
								break;
							case 'slides_tablet':
								$atts['tablet_land'] = $atts['tablet_portrait'] = $tab;
								break;
							case 'slides_mobile':
								$atts['mobile'] = $tab;
								break;
							default:
								$atts[$key] = $tab;
								break;
						}
						// General style
						$atts['navigation_position_style'] 	=	$settings['navigation_position_style'];
						$atts['navigation_position'] 	  	=	$settings['navigation_position'];
						$atts['navigation_type'] 	  	  	=	$settings['navigation_type'];
						$atts['navigation_style'] 	  	  	=	$settings['navigation_style'];
						$atts['hide_buttons_for']           =   $settings['hide_buttons_for'];
						$atts['hide_buttons']               =   $settings['hide_buttons'];
						
						// Pagination
						$atts['pagination_type']        = $settings['pagination_type'];
						$atts['hide_fo']         		= $settings['hide_fo'];
						$atts['default_color']         	= $settings['default_color'];
						$atts['active_color']         	= $settings['active_color'];
					}
				
				endforeach;
				
				$atts['is_preview'] = $is_preview;
				$atts['elementor']  = true;
				
				add_filter('woocommerce_sale_flash', 'etheme_woocommerce_sale_flash', 20, 3);
//				add_filter( 'woocommerce_available_variation', 'etheme_available_variation_gallery', 90, 3 );
//				add_filter( 'sten_wc_archive_loop_available_variation', 'etheme_available_variation_gallery', 90, 3 );
				add_filter( 'etheme_output_shortcodes_inline_css', function() { return true; } );
				
				// this add variation gallery filters at loop start and remove it after loop end
				//        if ( !$_POST['archiveVariationGallery'] ) {
				add_filter( 'woocommerce_product_loop_start', 'remove_et_variation_gallery_filter' );
				add_filter( 'woocommerce_product_loop_end', 'add_et_variation_gallery_filter' );
				//        }
				
				add_filter('woocommerce_get_availability_class', 'etheme_wc_get_availability_class', 20, 2);
				
				echo $Products_Shortcode->products_shortcode( $atts, '' );
				
				// removed remove filter woocommerce_sale_flash and added etheme_output_shortcodes_inline_css filter to prevent
                // errors in next widgets which are shown below this one
//				remove_filter('woocommerce_sale_flash', 'etheme_woocommerce_sale_flash', 20, 3);
//				remove_filter( 'woocommerce_available_variation', 'etheme_available_variation_gallery', 90, 3 );
//				remove_filter( 'sten_wc_archive_loop_available_variation', 'etheme_available_variation_gallery', 90, 3 );
				add_filter( 'etheme_output_shortcodes_inline_css', function() { return false; } );
				
				// this add variation gallery filters at loop start and remove it after loop end
				//        if ( !$_POST['archiveVariationGallery'] ) {
				remove_filter( 'woocommerce_product_loop_start', 'remove_et_variation_gallery_filter' );
				remove_filter( 'woocommerce_product_loop_end', 'add_et_variation_gallery_filter' );
				//        }
				
				remove_filter('woocommerce_get_availability_class', 'etheme_wc_get_availability_class', 20, 2);
				?>
			</div>
		<?php endforeach; ?>
		<?php if ( function_exists('etheme_loader') ) etheme_loader( true, 'product-ajax' ); ?>
	</div>
</div>