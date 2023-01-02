<?php
/**
 * The template created for elements callbacks theme options and templates
 *
 * @version 1.0.2
 * @since   1.5.4
 * last changes in 1.5.5
 */

/**
 * Return header content html.
 *
 * @param   {string} top, main, bottom header part
 * @param   {boolean} mobile on desktop header part
 *
 * @return  {html} html of header part content
 * @version 1.0.0
 * @since   1.5.5
 */
function header_content_callback( $part, $mobile_part = false ) {
	
	global $et_builder_globals, $wp_customize;
	
	$et_builder_globals['is_customize_preview'] = get_query_var( 'et_is_customize_preview', false );
	$et_builder_globals['in_mobile_menu']       = false;
	
	switch ( $part ) {
		case 'top':
			$part_data = ( $mobile_part ) ? json_decode( get_theme_mod( 'header_mobile_top_elements', '' ), true ) : json_decode( get_theme_mod( 'header_top_elements', '' ), true );
			break;
		case 'main':
			$part_data = ( $mobile_part ) ? json_decode( get_theme_mod( 'header_mobile_main_elements', '' ), true ) : json_decode( get_theme_mod( 'header_main_elements', '' ), true );
			break;
		case 'bottom':
			$part_data = ( $mobile_part ) ? json_decode( get_theme_mod( 'header_mobile_bottom_elements', '' ), true ) : json_decode( get_theme_mod( 'header_bottom_elements', '' ), true );
			break;
	}
	
	if ( ! is_array( $part_data ) ) {
		$part_data = array();
	}
	
	uasort( $part_data, function ( $item1, $item2 ) {
		return $item1['index'] <=> $item2['index'];
	} );
	
	$et_promo_text_hidden = false;
	
	if ( get_theme_mod( 'promo_text_close_button_action_et-desktop', 0 ) && isset( $_COOKIE['et_promo_text_shows'] ) && $_COOKIE['et_promo_text_shows'] == 'false' ) {
		$et_promo_text_hidden = true;
	}
	
	$mobile_filters = array(
		'etheme_mini_cart_content_type'         => 'etheme_mini_cart_content_mobile',
		'etheme_mini_cart_content_position'     => 'etheme_mini_cart_content_position_mobile',
		'etheme_mini_account_content_type'      => 'etheme_mini_account_content_mobile',
		'etheme_mini_account_content_position'  => 'etheme_mini_account_content_position_mobile',
		'etheme_mini_wishlist_content_type'     => 'etheme_mini_wishlist_content_mobile',
		'etheme_mini_wishlist_content_position' => 'etheme_mini_wishlist_content_position_mobile',
        'etheme_mini_compare_content_type'     => 'etheme_mini_compare_content_mobile',
        'etheme_mini_compare_content_position' => 'etheme_mini_compare_content_position_mobile',
//            'search_mode' => 'etheme_search_mode_mobile',
		'search_icon'                           => 'etheme_search_icon_mobile',
		'search_icon_custom'                    => 'etheme_search_icon_custom_mobile',
		'search_large_loader'                   => 'etheme_return_false',
		'search_type'                           => 'etheme_mobile_search_type',
		'search_category'                       => 'etheme_return_false',
		'account_icon'                          => 'etheme_mobile_account_icon',
		'account_icon_custom'                   => 'etheme_mobile_account_icon_custom'
	);

//	if ( etheme_mobile_search_type() != 'icon' ) {
//		$mobile_filters['search_by_icon'] = 'etheme_return_false';
//	} else {
//		$mobile_filters['search_by_icon'] = 'etheme_return_true';
//	}
	
	ob_start();
	
	if ( $et_builder_globals['is_customize_preview'] ) {
		add_filter( 'is_customize_preview', 'etheme_return_true' );
	}
	
	if ( $mobile_part ) {
		foreach ( $mobile_filters as $key => $value ) {
			add_filter( $key, $value, 15 );
		}
	}
	
	foreach ( $part_data as $key => $value ) :
		
		if ( $value['element'] == 'promo_text' && $et_promo_text_hidden && count( $part_data ) == 1 ) {
			continue;
		} ?>
		
		<?php
		
		if ( $value['element'] == 'connect_block' ) {
			$blockID = $key;
			if ( $mobile_part ) {
				add_filter( 'connect_block_package', function () {
					return 'connect_block_mobile_package';
				} );
			}
			add_filter( 'et_connect_block_id', function ( $id ) use ( $blockID ) {
				return $blockID;
			} );
		}
		
		?>
		
		<?php
		$col_class   = array();
		$col_class[] = 'et_col-xs-' . $value['size'];
		$col_class[] = ( isset( $value['offset'] ) ) ? 'et_col-xs-offset-' . $value['offset'] : 'et_col-xs-offset-0';
		if ( $mobile_part && ( in_array( $value['element'], array(
				'main_menu',
				'secondary_menu',
				'mobile_menu',
				'search',
				'connect_block'
			) ) ) ) {
			$col_class[] = 'pos-static';
		} elseif ( in_array( $value['element'], array(
			'main_menu',
			'secondary_menu',
			'mobile_menu',
			'connect_block'
		) ) ) {
			$col_class[] = 'pos-static';
		}
		
		?>

        <div class="et_column <?php echo esc_attr( implode( ' ', $col_class ) ); ?>">
			<?php require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/' . $value['element'] . '.php' ); ?>
        </div>
	<?php endforeach;
	
	if ( $et_builder_globals['is_customize_preview'] ) {
		wp_enqueue_script( 'promo_text_carousel' );
		// for toggle action on menu items ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
				
				<?php if ( count( $part_data ) < 1 ) : ?>
                $('.<?php echo ( $mobile_part ) ? 'mobile-' : ''; ?>header-wrapper .header-<?php echo esc_js( $part ); ?>-wrapper').addClass('none');
				<?php else : ?>
                $('.<?php echo ( $mobile_part ) ? 'mobile-' : ''; ?>header-wrapper .header-<?php echo esc_js( $part ); ?>-wrapper').removeClass('none');
				<?php endif; ?>

                etTheme.swiperFunc();
                if (etTheme.fixedHeader !== undefined)
                    etTheme.fixedHeader();
                if (etTheme.promo_text_carousel !== undefined)
                    etTheme.promo_text_carousel();

            });
        </script>
	<?php }
	
	if ( $mobile_part ) {
		foreach ( $mobile_filters as $key => $value ) {
			remove_filter( $key, $value, 15 );
		}
	}
	
	if ( $et_builder_globals['is_customize_preview'] ) {
		remove_filter( 'is_customize_preview', 'etheme_return_true' );
	}
	
	$html = ob_get_clean();
	
	return $html;
}

/**
 * Return header top content html.
 *
 * @return  {html} html of header-top part content
 * @version 1.0.0
 * @since   1.5.5
 */
function header_top_callback() {
	return header_content_callback( 'top' );
}

/**
 * Return header main content html.
 *
 * @return  {html} html of header-main part content
 * @version 1.0.0
 * @since   1.5.5
 */
function header_main_callback() {
	return header_content_callback( 'main' );
}

/**
 * Return header bottom content html.
 *
 * @return  {html} html of header-bottom part content
 * @version 1.0.0
 * @since   1.5.5
 */
function header_bottom_callback() {
	return header_content_callback( 'bottom' );
}

/**
 * Return mobile header top content html.
 *
 * @return  {html} html of mobile-header-top part content
 * @version 1.0.0
 * @since   1.5.5
 */
function mobile_header_top_callback() {
	return header_content_callback( 'top', true );
}

/**
 * Return mobile header main content html.
 *
 * @return  {html} html of mobile-header-main part content
 * @version 1.0.0
 * @since   1.5.5
 */
function mobile_header_main_callback() {
	return header_content_callback( 'main', true );
}

/**
 * Return mobile header bottom content html.
 *
 * @return  {html} html of mobile-header-bottom part content
 * @version 1.0.0
 * @since   1.5.5
 */
function mobile_header_bottom_callback() {
	return header_content_callback( 'bottom', true );
}

/**
 * Return header content html.
 *
 * @return  {html} html of header part content
 * @version 1.0.0
 * @since   1.5.5
 */
function header_callback() {
	etheme_header_top();
	etheme_header_main();
	etheme_header_bottom();
}

/**
 * Return mobile header content html.
 *
 * @return  {html} html of mobile header part content
 * @version 1.0.0
 * @since   1.5.5
 */
function mobile_header_callback() {
	etheme_mobile_header_top();
	etheme_mobile_header_main();
	etheme_mobile_header_bottom();
}

/**
 *
 * @return  {html} header socials content
 * @version 1.0.0
 * @since   1.5.4
 */

function header_socials_callback() {
	global $et_social_icons;
	
	$element_options                                        = array();
	$element_options['header_socials_type_et-desktop']      = get_theme_mod( 'header_socials_type_et-desktop', 'type1' );
	$element_options['header_socials_direction_et-desktop'] = get_theme_mod( 'header_socials_direction_et-desktop', 'hor' );
	$element_options['header_socials_direction_et-desktop'] = apply_filters( 'header_socials_direction', $element_options['header_socials_direction_et-desktop'] );
	
	$header_socials_package_et_desktop = array(
		array(
			'social_name' => esc_html__( 'Facebook', 'xstore-core' ),
			'social_url'  => '#',
			'social_icon' => 'et_icon-facebook'
		),
		array(
			'social_name' => esc_html__( 'Twitter', 'xstore-core' ),
			'social_url'  => '#',
			'social_icon' => 'et_icon-twitter'
		),
		array(
			'social_name' => esc_html__( 'Instagram', 'xstore-core' ),
			'social_url'  => '#',
			'social_icon' => 'et_icon-instagram'
		),
		array(
			'social_name' => esc_html__( 'Youtube', 'xstore-core' ),
			'social_url'  => '#',
			'social_icon' => 'et_icon-youtube'
		),
		array(
			'social_name' => esc_html__( 'Linkedin', 'xstore-core' ),
			'social_url'  => '#',
			'social_icon' => 'et_icon-linkedin'
		),
	);
	
	$element_options['header_socials_package_et-desktop']   = get_theme_mod( 'header_socials_package_et-desktop', $header_socials_package_et_desktop );
	$element_options['header_socials_package_et-desktop']   = apply_filters( 'et_render_socials_theme_mod', $element_options['header_socials_package_et-desktop'] );
	$element_options['header_socials_target_et-desktop']    = get_theme_mod( 'header_socials_target_et-desktop', 0 ) ? 'target="_blank"' : '';
	$element_options['header_socials_no_follow_et-desktop'] = get_theme_mod( 'header_socials_no_follow_et-desktop', 0 ) ? 'rel="nofollow"' : '';
	
	ob_start();
	foreach ( $element_options['header_socials_package_et-desktop'] as $key ) {
		
		if ( ! isset( $key['social_icon'] ) ) {
			continue;
		}
		
		
		?>
        <a href="<?php echo $key['social_url'] ?>" <?php echo $element_options['header_socials_target_et-desktop']; ?> <?php echo $element_options['header_socials_no_follow_et-desktop']; ?>
           data-tooltip="<?php echo $key['social_name']; ?>" title="<?php echo $key['social_name']; ?>">
            <span class="screen-reader-text hidden"><?php echo esc_html( $key['social_name'] ); ?></span>
			<?php
			if ( isset( $key['social_icon'] ) && $key['social_icon'] != '' && isset( $et_social_icons[ $element_options['header_socials_type_et-desktop'] ][ $key['social_icon'] ] ) ) {
				echo $et_social_icons[ $element_options['header_socials_type_et-desktop'] ][ $key['social_icon'] ];
			} else {
				echo $et_social_icons[ $element_options['header_socials_type_et-desktop'] ]['et_icon-facebook'];
			}
			?>
        </a>
	<?php }
	$html = ob_get_clean();
	
	return $html;
}

/**
 *
 * @return  {html} header vertical content
 * @version 1.0.0
 * @since   1.5.4
 */
function header_vertical_callback() {
	global $et_builder_globals;
	$et_builder_globals['in_mobile_menu']       = false;
	$et_builder_globals['is_vertical']          = true;
	$et_builder_globals['is_customize_preview'] = get_query_var( 'et_is_customize_preview', false );
	
	$header_vertical_options = array();
	
	$header_vertical_options['header_vertical_menu_type_et-desktop'] = get_theme_mod( 'header_vertical_menu_type_et-desktop', 'icon' );
	$header_vertical_options['header_vertical_section1_content']     = get_theme_mod( 'header_vertical_section1_content', array( 'logo' ) );
	$header_vertical_options['header_vertical_section1_content']     = ! is_array( $header_vertical_options['header_vertical_section1_content'] ) ? array() : $header_vertical_options['header_vertical_section1_content'];
	
	$header_vertical_options['header_vertical_section2_content'] = get_theme_mod( 'header_vertical_section2_content', array( 'menu' ) );
	$header_vertical_options['header_vertical_section2_content'] = ! is_array( $header_vertical_options['header_vertical_section2_content'] ) ? array() : $header_vertical_options['header_vertical_section2_content'];
	
	$header_vertical_options['header_vertical_section3_content'] = get_theme_mod( 'header_vertical_section3_content', array( 'cart' ) );
	$header_vertical_options['header_vertical_section3_content'] = ! is_array( $header_vertical_options['header_vertical_section3_content'] ) ? array() : $header_vertical_options['header_vertical_section3_content'];
	
	if ( ! get_theme_mod( 'bold_icons', 0 ) ) {
		$header_vertical_options['menu_icon'] = array(
			'to_open'  => '<span class="et_b-icon"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path d="M0.792 5.904h22.416c0.408 0 0.744-0.336 0.744-0.744s-0.336-0.744-0.744-0.744h-22.416c-0.408 0-0.744 0.336-0.744 0.744s0.336 0.744 0.744 0.744zM23.208 11.256h-22.416c-0.408 0-0.744 0.336-0.744 0.744s0.336 0.744 0.744 0.744h22.416c0.408 0 0.744-0.336 0.744-0.744s-0.336-0.744-0.744-0.744zM23.208 18.096h-22.416c-0.408 0-0.744 0.336-0.744 0.744s0.336 0.744 0.744 0.744h22.416c0.408 0 0.744-0.336 0.744-0.744s-0.336-0.744-0.744-0.744z"></path></svg></span>',
			'to_close' => '<span class="et_b-icon"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" style="padding: .25em;"><path d="M13.536 12l10.128-10.104c0.216-0.216 0.312-0.48 0.312-0.792 0-0.288-0.12-0.552-0.312-0.768l-0.024-0.024c-0.12-0.12-0.408-0.288-0.744-0.288-0.312 0-0.6 0.12-0.768 0.312l-10.128 10.128-10.104-10.128c-0.408-0.408-1.104-0.432-1.512 0-0.216 0.192-0.336 0.48-0.336 0.768 0 0.312 0.12 0.576 0.312 0.792l10.104 10.104-10.128 10.104c-0.216 0.216-0.312 0.48-0.312 0.792 0 0.288 0.096 0.552 0.312 0.768 0.192 0.192 0.48 0.312 0.768 0.312s0.552-0.12 0.768-0.312l10.128-10.128 10.104 10.104c0.192 0.192 0.48 0.312 0.768 0.312s0.552-0.12 0.768-0.312c0.192-0.192 0.312-0.48 0.312-0.768s-0.12-0.552-0.312-0.768l-10.104-10.104z"></path></svg></span>'
		);
	} else {
		$header_vertical_options['menu_icon'] = array(
			'to_open'  => '<span class="et_b-icon"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path d="M0.792 5.904h22.416c0.408 0 0.744-0.336 0.744-0.744s-0.336-0.744-0.744-0.744h-22.416c-0.408 0-0.744 0.336-0.744 0.744s0.336 0.744 0.744 0.744zM23.208 11.256h-22.416c-0.408 0-0.744 0.336-0.744 0.744s0.336 0.744 0.744 0.744h22.416c0.408 0 0.744-0.336 0.744-0.744s-0.336-0.744-0.744-0.744zM23.208 18.096h-22.416c-0.408 0-0.744 0.336-0.744 0.744s0.336 0.744 0.744 0.744h22.416c0.408 0 0.744-0.336 0.744-0.744s-0.336-0.744-0.744-0.744z"></path></svg></span>',
			'to_close' => '<span class="et_b-icon"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" style="padding: .25em;"><path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path></svg></span>'
		);
	}
	
	$header_vertical_options['etheme_filters'] = array(
		'etheme_logo_sticky'               => 'etheme_return_false',
		'logo_img'                         => 'etheme_vertical_header_logo',
		'logo_align'                       => 'etheme_return_align_inherit',
		'menu_item_design'                 => 'etheme_menu_item_design_dropdown',
		'etheme_mini_content'              => 'etheme_return_false',
		'etheme_search_results'            => 'etheme_return_false',
		'search_category'                  => 'etheme_return_false',
		'cart_off_canvas'                  => 'etheme_return_false',
		'etheme_mini_cart_content'         => 'etheme_return_false',
		'etheme_mini_account_content'      => 'etheme_return_false',
		'account_off_canvas'               => 'etheme_return_false',
		'etheme_mini_wishlist_content'     => 'etheme_return_false',
		'wishlist_off_canvas'              => 'etheme_return_false',
        'etheme_mini_compare_content'     => 'etheme_return_false',
        'compare_off_canvas'              => 'etheme_return_false',
		'cart_content_alignment'           => 'etheme_return_align_inherit',
		'wishlist_content_alignment'       => 'etheme_return_align_inherit',
		'account_content_alignment'        => 'etheme_return_align_inherit',
		'header_socials_content_alignment' => 'etheme_return_align_center',
		'header_button_content_align'      => 'etheme_return_align_inherit',
		// 'et_mobile_menu' => 'etheme_return_true',
		// 'etheme_use_desktop_style' => 'etheme_return_true',
		// 'search_type' => 'etheme_mobile_type_input',
		// 'search_by_icon' => 'etheme_return_false',
		// 'cart_style' => 'etheme_mobile_content_element_type1',
		// 'account_style' => 'etheme_mobile_content_element_type1',
		// 'contacts_icon_position' => 'etheme_mobile_icon_left'
	);
	
	foreach ( $header_vertical_options['etheme_filters'] as $key => $value ) {
		add_filter( $key, $value, 15 );
	}
	
	$header_vertical_options['header_vertical_menu_term']      = get_theme_mod( 'header_vertical_menu_term' );
	$header_vertical_options['header_vertical_menu_term_name'] = ( $header_vertical_options['header_vertical_menu_term'] == '' ) ? 'main-menu' : $header_vertical_options['header_vertical_menu_term'];
	
	$header_vertical_options['sections'] = array(
		'start'  => '1',
		'center' => '2',
		'end'    => '3'
	);
	
	ob_start();
	foreach ( $header_vertical_options['sections'] as $key => $value ) { ?>
        <div class="header-vertical-section flex flex-wrap full-width-children align-content-<?php echo $key; ?> align-items-<?php echo $key; ?>">
			<?php foreach ( $header_vertical_options[ 'header_vertical_section' . $value . '_content' ] as $key => $value ) {
				switch ( $value ) {
					case 'menu':
						$args = array(
							'menu'            => $header_vertical_options['header_vertical_menu_term_name'],
							'before'          => '',
							'container_class' => 'menu-main-container flex-col-child',
							'after'           => '',
							'link_before'     => '',
							'link_after'      => '',
							'depth'           => 5,
							'echo'            => false,
							'fallback_cb'     => false,
							'walker'          => new ETheme_Navigation
						);
						
						$header_vertical_options['menu_getter'] = wp_nav_menu( $args );
						
						if ( $header_vertical_options['menu_getter'] != '' ) {
							
							if ( $header_vertical_options['header_vertical_menu_type_et-desktop'] == 'icon' ) { ?>
                                <div class="et_element et-content_toggle header-vertical-menu-icon-wrapper et_element-top-level static justify-content-inherit"
                                     data-title="<?php esc_html_e( 'Header vertical menu', 'xstore-core' ); ?>">
									<span class="et-toggle pos-relative inline-block">
										<?php echo $header_vertical_options['menu_icon']['to_open'] . $header_vertical_options['menu_icon']['to_close']; ?>
									</span>
                                    <div class="et-mini-content justify-content-inherit">
                                        <div class="et_element et_b_header-menu header-vertical-menu flex align-items-center"
                                             data-title="<?php esc_html_e( 'Header vertical menu', 'xstore-core' ); ?>">
											<?php echo $header_vertical_options['menu_getter']; ?>
                                        </div>
                                    </div>
                                </div>
							<?php } else { ?>
                                <div class="et_element et_b_header-menu et_element-top-level header-vertical-menu flex align-items-center"
                                     data-title="<?php esc_html_e( 'Header vertical menu', 'xstore-core' ); ?>">
									<?php echo $header_vertical_options['menu_getter']; ?>
                                </div>
							<?php }
						} else {
							esc_html_e( 'Vertical header menu', 'xstore-core' ); ?> <span class="mtips"
                                                                                          style="width: 1em; height: 1em; font-size: 1em; text-transform: none;">
                            <span style="width: .9em; height: .9em; font-size: .9em; vertical-align: -15%;"
                                  class="dashicons dashicons-warning"></span><span
                                    class="mt-mes"><?php esc_html_e( 'To use Header vertical menu please select your menu in dropdown', 'xstore-core' ); ?></span>
                            </span><?php
						}
						break;
					default:
						require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/' . $value . '.php' );
						break;
				}
			} ?>
        </div>
	<?php } ?>
	
	<?php foreach ( $header_vertical_options['etheme_filters'] as $key => $value ) {
		remove_filter( $key, $value, 15 );
	}
	
	$html                              = ob_get_clean();
	$et_builder_globals['is_vertical'] = false;
	
	return $html;
}

/**
 *
 * @return  {html} main menu content
 * @version 1.0.0
 * @since   1.5.4
 */
function main_menu_callback() {
	
	if ( ! class_exists( 'ETheme_Navigation' ) ) {
		return;
	}
	
	global $et_builder_globals;
	
	$element_options                             = array();
	$element_options['main_menu_term']           = get_theme_mod( 'main_menu_term' );
	$element_options['main_menu_term_name']      = $element_options['main_menu_term'] == '' ? 'main-menu' : $element_options['main_menu_term'];
	$element_options['main_menu_style']          = get_theme_mod( 'menu_item_style_et-desktop', 'underline' );
	$element_options['one_page_menu']            = ( get_theme_mod( 'menu_one_page' ) ) ? ' one-page-menu' : '';
	$element_options['menu_dropdown_full_width'] = get_theme_mod( 'menu_dropdown_full_width', false );
	$element_options['separator']                = get_theme_mod( 'menu_item_dots_separator_et-desktop', '2022' );
	$element_options['menu_link_after']          = ( $element_options['main_menu_style'] == 'dots' || $et_builder_globals['is_customize_preview'] ) ? '<span class="et_b_header-menu-sep align-self-center"></span>' : '';
	
	$args = array(
		'menu'            => $element_options['main_menu_term_name'],
		'before'          => '',
		'container_class' => 'menu-main-container' . $element_options['one_page_menu'],
		'after'           => $element_options['menu_link_after'],
		'link_before'     => '',
		'link_after'      => '',
		'depth'           => 100,
		'echo'            => false,
		'fallback_cb'     => false,
		'walker'          => new ETheme_Navigation
	);
	
	$element_options['menu_arrows'] = get_theme_mod( 'menu_arrows', 1 );
	
	if ( $element_options['menu_arrows'] || $et_builder_globals['is_customize_preview'] ) {
		add_filter( 'menu_item_with_svg_arrow', 'etheme_return_true' );
	}
	
	if ( ! $element_options['menu_arrows'] && $et_builder_globals['is_customize_preview'] ) {
		add_filter( 'menu_item_with_svg_arrow_class', 'etheme_return_none' );
	}
	
	if ( $element_options['menu_dropdown_full_width'] ) {
		add_filter( 'menu_item_dropdown_full_width', 'etheme_return_true' );
	}
	
	if ( $element_options['one_page_menu'] ) {
		wp_enqueue_script( 'one_page_menu' );
	}
	
	$element_options['menu_getter'] = wp_nav_menu( $args );
	
	ob_start();
	
	if ( $element_options['menu_getter'] != '' ) {
		echo $element_options['menu_getter'];
	} else { ?>
        <span class="flex-inline justify-content-center align-items-center flex-nowrap">
	            <?php esc_html_e( 'Main menu ', 'xstore-core' ); ?>
	            <span class="mtips" style="text-transform: none;">
	                <i class="et-icon et-exclamation"
                       style="margin-left: 3px; vertical-align: middle; font-size: 75%;"></i>
	                <span class="mt-mes"><?php esc_html_e( 'To use Main menu, please, set up the main menu in the dashboard -> appearence -> menus', 'xstore-core' ); ?></span>
	            </span>
	        </span>
	<?php }
	
	if ( $element_options['menu_dropdown_full_width'] ) {
		remove_filter( 'menu_item_dropdown_full_width', 'etheme_return_true' );
	}
	
	if ( $element_options['menu_arrows'] || $et_builder_globals['is_customize_preview'] ) {
		remove_filter( 'menu_item_with_svg_arrow', 'etheme_return_true' );
	}
	
	if ( ! $element_options['menu_arrows'] && $et_builder_globals['is_customize_preview'] ) {
		add_filter( 'menu_item_with_svg_arrow_class', 'etheme_return_none' );
	}
	
	$html = ob_get_clean();
	
	if ( $element_options['main_menu_style'] == 'dots' ) {
		if ( get_query_var( 'et_is_customize_preview', false ) ) {
			ob_start(); ?>
            <style>
                .header-main-menu.et_element-top-level .menu > li .et_b_header-menu-sep:before {
                    content: <?php echo '"\\' . str_replace('\\', '', $element_options['separator']) . '"'; ?>;
                }
            </style>
			<?php $html .= ob_get_clean();
		} else {
			wp_add_inline_style( 'xstore-inline-css',
				'.header-main-menu.et_element-top-level .menu > li .et_b_header-menu-sep:before {
                            content: "\\' . str_replace( '\\', '', $element_options['separator'] ) . '";' .
				'}'
			);
		}
	}
	
	unset( $element_options );
	
	return $html;
}

/**
 *
 * @return  {html} main menu2 content
 * @version 1.0.0
 * @since   1.5.4
 */
function main_menu2_callback() {
	
	if ( ! class_exists( 'ETheme_Navigation' ) ) {
		return;
	}
	
	global $et_builder_globals;
	
	$element_options                             = array();
	$element_options['main_menu_2_term']         = get_theme_mod( 'main_menu_2_term' );
	$element_options['main_menu_2_term_name']    = $element_options['main_menu_2_term'] == '' ? 'main-menu' : $element_options['main_menu_2_term'];
	$element_options['menu_2_item_style']        = get_theme_mod( 'menu_2_item_style_et-desktop', 'underline' );
	$element_options['separator']                = get_theme_mod( 'menu_2_item_dots_separator_et-desktop', '2022' );
	$element_options['one_page_menu']            = ( get_theme_mod( 'menu_2_one_page', '0' ) ) ? ' one-page-menu' : '';
	$element_options['menu_dropdown_full_width'] = get_theme_mod( 'menu_2_dropdown_full_width', false );
	$element_options['menu_link_after']          = ( $element_options['menu_2_item_style'] == 'dots' || $et_builder_globals['is_customize_preview'] ) ? '<span class="et_b_header-menu-sep align-self-center"></span>' : '';
	
	$args = array(
		'menu'            => $element_options['main_menu_2_term_name'],
		'before'          => '',
		'container_class' => 'menu-main-container' . $element_options['one_page_menu'],
		'after'           => $element_options['menu_link_after'],
		'link_before'     => '',
		'link_after'      => '',
		'depth'           => 100,
		'echo'            => false,
		'fallback_cb'     => false,
		'walker'          => new ETheme_Navigation
	);
	
	$element_options['menu_arrows'] = get_theme_mod( 'menu_2_arrows', 1 );
	
	if ( $element_options['menu_arrows'] || $et_builder_globals['is_customize_preview'] ) {
		add_filter( 'menu_item_with_svg_arrow', 'etheme_return_true' );
	}
	
	if ( ! $element_options['menu_arrows'] && $et_builder_globals['is_customize_preview'] ) {
		add_filter( 'menu_item_with_svg_arrow_class', 'etheme_return_none' );
	}
	
	if ( $element_options['menu_dropdown_full_width'] ) {
		add_filter( 'menu_item_dropdown_full_width', 'etheme_return_true' );
	}
	
	if ( $element_options['one_page_menu'] ) {
		wp_enqueue_script( 'one_page_menu' );
	}
	
	$element_options['menu_getter'] = wp_nav_menu( $args );
	
	ob_start();
	
	if ( $element_options['menu_getter'] != '' ) {
		echo $element_options['menu_getter'];
	} else { ?>
        <span class="flex-inline justify-content-center align-items-center flex-nowrap">
	            <?php esc_html_e( 'Secondary menu ', 'xstore-core' ); ?>
	            <span class="mtips" style="text-transform: none;">
	                <i class="et-icon et-exclamation"
                       style="margin-left: 3px; vertical-align: middle; font-size: 75%;"></i>
	                <span class="mt-mes"><?php esc_html_e( 'To use Secondary menu, please, set up the main menu in the dashboard -> appearence -> menus', 'xstore-core' ); ?></span>
	            </span>
	        </span>
	<?php }
	
	if ( $element_options['menu_dropdown_full_width'] ) {
		remove_filter( 'menu_item_dropdown_full_width', 'etheme_return_true' );
	}
	
	if ( $element_options['menu_arrows'] || $et_builder_globals['is_customize_preview'] ) {
		remove_filter( 'menu_item_with_svg_arrow', 'etheme_return_true' );
	}
	
	if ( ! $element_options['menu_arrows'] && $et_builder_globals['is_customize_preview'] ) {
		add_filter( 'menu_item_with_svg_arrow_class', 'etheme_return_none' );
	}
	
	$html = ob_get_clean();
	
	if ( $element_options['menu_2_item_style'] == 'dots' ) {
		if ( get_query_var( 'et_is_customize_preview', false ) ) {
			ob_start(); ?>
            <style>
                .header-main-menu2.et_element-top-level .menu > li .et_b_header-menu-sep:before {
                    content: <?php echo '"\\' . str_replace('\\', '', $element_options['separator']) . '"'; ?>;
                }
            </style>
			<?php $html .= ob_get_clean();
		} else {
			wp_add_inline_style( 'xstore-inline-css',
				'.header-main-menu2.et_element-top-level .menu > li .et_b_header-menu-sep:before {
                            content: "\\' . str_replace( '\\', '', $element_options['separator'] ) . '";' .
				'}'
			);
		}
	}
	
	unset( $element_options );
	
	return $html;
}

/**
 *
 * @return false|string|void
 * @version 1.0.1
 *
 * @since   3.2.4
 */
function account_menu_callback() {
	
	global $et_builder_globals;
	
	$element_options                        = array();
	$is_woocommerce                         = class_exists( 'WooCommerce' );
	$element_options['main_menu_term']      = get_theme_mod( 'account_menu_term', 'default' );
	$element_options['main_menu_term_name'] = ( $element_options['main_menu_term'] == '' || $element_options['main_menu_term'] == 0 ) ? 'default' : $element_options['main_menu_term'];
	
	ob_start();
	
	if ( $element_options['main_menu_term_name'] != 'default' ) {
		
		if ( ! class_exists( 'ETheme_Navigation' ) ) {
			return;
		}
		
		$args = array(
			'menu'            => $element_options['main_menu_term_name'],
			'before'          => '',
			'container_class' => 'menu-main-container',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'depth'           => 100,
			'echo'            => false,
			'fallback_cb'     => false,
			'walker'          => new ETheme_Navigation
		);
		
		$element_options['menu_getter'] = wp_nav_menu( $args );
  
		if ( $element_options['menu_getter'] != '' ) {
			echo $element_options['menu_getter'];
		} else { ?>
            <span class="flex-inline justify-content-center align-items-center flex-nowrap">
                    <?php esc_html_e( 'Main menu ', 'xstore-core' ); ?>
                    <span class="mtips" style="text-transform: none;">
                        <i class="et-icon et-exclamation"
                           style="margin-left: 3px; vertical-align: middle; font-size: 75%;"></i>
                        <span class="mt-mes"><?php esc_html_e( 'To use Main menu, please, set up the main menu in the dashboard -> appearence -> menus', 'xstore-core' ); ?></span>
                    </span>
                </span>
		<?php }
		
	} else {
		$login_link = ( $is_woocommerce ) ? get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) : wp_login_url(); ?>
        <ul class="menu">
			<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) {
				$url = ( $endpoint != 'dashboard' ) ? wc_get_endpoint_url( $endpoint, '', $login_link ) : $login_link;
				?>
                <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                    <a href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( $label ); ?></a>
                </li>
			<?php } ?>
        </ul>
	<?php }
	
	$html = ob_get_clean();
	
	unset( $element_options );
	
	return $html;
}

/**
 *
 * @return  {html} all departments content
 * @version 1.0.0
 * @since   1.5.4
 */
function all_departments_menu_callback() {
	
	if ( ! class_exists( 'ETheme_Navigation' ) ) {
		return;
	}
	
	$element_options                             = array();
	$element_options['secondary_menu_term']      = get_theme_mod( 'secondary_menu_term' );
	$element_options['secondary_menu_term_name'] = $element_options['secondary_menu_term'] == '' ? 'main-menu' : $element_options['secondary_menu_term'];
	
	add_filter( 'menu_item_with_svg_arrow', 'etheme_return_false' );
	
	$args = array(
		'menu'            => $element_options['secondary_menu_term_name'],
		'before'          => '',
		'container_class' => 'menu-main-container',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'hide_after'      => 3,
		'depth'           => 100,
		'echo'            => false,
		'fallback_cb'     => false,
		'walker'          => new ETheme_Navigation
	);
	
	add_filter( 'wp_nav_menu_objects', 'etheme_all_departments_limit_objects', 10, 2 );
	add_filter( 'wp_nav_menu_items', 'etheme_all_departments_limit_items', 10, 2 );
	
	$element_options['menu_getter'] = wp_nav_menu( $args );
	
	ob_start();
	
	if ( $element_options['menu_getter'] != '' ) { ?>
        <div class="secondary-menu-wrapper">
            <div class="secondary-title">
                <div class="secondary-menu-toggle">
                    <span class="et-icon et-burger"></span>
                </div>
                <span><?php echo etheme_get_option( 'all_departments_text', esc_html__( 'All departments', 'xstore-core' ) ); ?></span>
            </div>
			<?php
			add_filter( 'etheme_ajaxify_nav_menu_type', function () {
				return 'all-departments';
			} );
			echo $element_options['menu_getter'];
			add_filter( 'etheme_ajaxify_nav_menu_type', '__return_empty_string' );
			?>
        </div>
	<?php } else {
		?> <span style="white-space: nowrap;"><?php esc_html_e( 'All departments menu', 'xstore-core' ); ?></span><span
                class="mtips" style="width: 1em; height: 1em; font-size: 1em; text-transform: none;"><span
                    style="width: .9em; height: .9em; font-size: .9em; vertical-align: -15%;"
                    class="dashicons dashicons-warning"></span><span
                    class="mt-mes"><?php esc_html_e( 'To use All departments menu, please, set up the main menu in the dashboard -> appearence -> menus', 'xstore-core' ); ?></span></span>
	<?php }
	
	remove_filter( 'wp_nav_menu_objects', 'etheme_all_departments_limit_objects', 10, 2 );
	remove_filter( 'wp_nav_menu_items', 'etheme_all_departments_limit_items', 10, 2 );
	
	remove_filter( 'menu_item_with_svg_arrow', 'etheme_return_false' );
	
	unset( $element_options );
	$html = ob_get_clean();
	
	return $html;
}

/**
 *
 * @return  {html} mobile menu content
 * @version 1.0.3
 *          last changes in 4.0.0
 * @see     /templates/header/parts/mobile-menu
 * @since   1.5.4
 */
function mobile_menu_callback() {
	
	global $et_builder_globals;
	
	$mob_menu_element_options = array();
	
	$mob_menu_element_options['mobile_menu_type_et-desktop'] = get_theme_mod( 'mobile_menu_type_et-desktop', 'off_canvas_left' );
	// $mob_menu_element_options['mobile_menu_content'] = get_theme_mod( 'mobile_menu_content' );
	$mob_menu_element_options['mobile_menu_content_position']  = ( $mob_menu_element_options['mobile_menu_type_et-desktop'] == 'off_canvas_left' ) ? 'left' : 'right';
	$mob_menu_element_options['mobile_menu_content_alignment'] = ' justify-content-' . get_theme_mod( 'mobile_menu_content_alignment_et-desktop', 'start' );
	$mob_menu_element_options['mobile_menu_content_alignment'] .= ' mob-justify-content-' . get_theme_mod( 'mobile_menu_content_alignment_et-mobile', 'start' );
	
	$mob_menu_element_options['icon_type_et-desktop'] = get_theme_mod( 'mobile_menu_icon_et-desktop', 'icon1' );
	
	if ( ! get_theme_mod( 'bold_icons', 0 ) ) {
		$mob_menu_element_options['mobile_menu_icons_et-desktop'] = array(
			'icon1' => '<span class="et_b-icon"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path d="M0.792 5.904h22.416c0.408 0 0.744-0.336 0.744-0.744s-0.336-0.744-0.744-0.744h-22.416c-0.408 0-0.744 0.336-0.744 0.744s0.336 0.744 0.744 0.744zM23.208 11.256h-22.416c-0.408 0-0.744 0.336-0.744 0.744s0.336 0.744 0.744 0.744h22.416c0.408 0 0.744-0.336 0.744-0.744s-0.336-0.744-0.744-0.744zM23.208 18.096h-22.416c-0.408 0-0.744 0.336-0.744 0.744s0.336 0.744 0.744 0.744h22.416c0.408 0 0.744-0.336 0.744-0.744s-0.336-0.744-0.744-0.744z"></path></svg></span>',
			'icon2' => '<span class="et_b-icon"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="1em" height="1em" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><g><path d="M26,37h47.7c1.7,0,3.3-1.3,3.3-3.3s-1.3-3.3-3.3-3.3H26c-1.7,0-3.3,1.3-3.3,3.3S24.3,37,26,37z"/><path d="M74,46.7H26c-1.7,0-3.3,1.3-3.3,3.3s1.3,3.3,3.3,3.3h47.7c1.7,0,3.3-1.3,3.3-3.3S75.7,46.7,74,46.7z"/><path d="M74,63H26c-1.7,0-3.3,1.3-3.3,3.3s1.3,3.3,3.3,3.3h47.7c1.7,0,3.3-1.3,3.3-3.3S75.7,63,74,63z"/></g><path d="M50,0C22.3,0,0,22.3,0,50s22.3,50,50,50s50-22.3,50-50S77.7,0,50,0z M50,93.7C26,93.7,6.3,74,6.3,50S26,6.3,50,6.3S93.7,26,93.7,50S74,93.7,50,93.7z"/></svg></span>',
			'none'  => ( $et_builder_globals['is_customize_preview'] ) ? '<span class="et_b-icon none"></span>' : ''
		);
	} else {
		$mob_menu_element_options['mobile_menu_icons_et-desktop'] = array(
			'icon1' => '<span class="et_b-icon"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path d="M0.792 5.904h22.416c0.408 0 0.744-0.336 0.744-0.744s-0.336-0.744-0.744-0.744h-22.416c-0.408 0-0.744 0.336-0.744 0.744s0.336 0.744 0.744 0.744zM23.208 11.256h-22.416c-0.408 0-0.744 0.336-0.744 0.744s0.336 0.744 0.744 0.744h22.416c0.408 0 0.744-0.336 0.744-0.744s-0.336-0.744-0.744-0.744zM23.208 18.096h-22.416c-0.408 0-0.744 0.336-0.744 0.744s0.336 0.744 0.744 0.744h22.416c0.408 0 0.744-0.336 0.744-0.744s-0.336-0.744-0.744-0.744z"></path></svg></span>',
			'icon2' => '<span class="et_b-icon"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="1em" height="1em"viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><g><path d="M73.2,29.7H26.5c-2,0-4.2,1.6-4.2,4.2c0,2.6,2.2,4.2,4.2,4.2h46.7c2,0,4.2-1.6,4.2-4.2C77.5,31.5,75.7,29.7,73.2,29.7z"/><path d="M73.5,45.8H26.5c-2,0-4.2,1.6-4.2,4.2c0,2.5,1.7,4.2,4.2,4.2h46.7c2,0,4.2-1.6,4.2-4.2C77.5,47.6,75.8,45.8,73.5,45.8z"/><path d="M73.5,61.8H26.5c-2,0-4.2,1.6-4.2,4.2c0,2.5,1.7,4.2,4.2,4.2h46.7c2,0,4.2-1.6,4.2-4.2C77.5,63.6,75.8,61.8,73.5,61.8z"/><path d="M50,0C22.4,0,0,22.4,0,50c0,27.6,22.4,50,50,50c27.6,0,50-22.4,50-50C100,22.4,77.6,0,50,0z M50,91.8C26.9,91.8,8.2,73.1,8.2,50S26.9,8.2,50,8.2S91.8,26.9,91.8,50S73.1,91.8,50,91.8z"/></g></svg></span>',
			'none'  => ( $et_builder_globals['is_customize_preview'] ) ? '<span class="et_b-icon none"></span>' : ''
		);
	}
	
	$mob_menu_element_options['icon_custom'] = get_theme_mod( 'mobile_menu_icon_custom_svg_et-desktop', '' );
//	$mob_menu_element_options['icon_custom'] = apply_filters('mobile_menu_icon_custom', $mob_menu_element_options['icon_custom']);
	$mob_menu_element_options['icon_custom'] = isset( $mob_menu_element_options['icon_custom']['id'] ) ? $mob_menu_element_options['icon_custom']['id'] : '';
	
	if ( $mob_menu_element_options['icon_type_et-desktop'] == 'custom' ) {
		if ( $mob_menu_element_options['icon_custom'] != '' ) {
			$mob_menu_element_options['mobile_menu_icons_et-desktop']['custom'] = etheme_get_svg_icon( $mob_menu_element_options['icon_custom'] );
		} else {
			$mob_menu_element_options['mobile_menu_icons_et-desktop']['custom'] = $mob_menu_element_options['mobile_menu_icons_et-desktop']['icon1'];
		}
	}

//	$mob_menu_element_options['mobile_menu_icons_et-desktop']['custom'] = get_theme_mod( 'mobile_menu_icon_custom_et-desktop', '' );
	
	$mob_menu_element_options['mobile_menu_icon_et-desktop'] = $mob_menu_element_options['mobile_menu_icons_et-desktop'][ $mob_menu_element_options['icon_type_et-desktop'] ];
	
	$mob_menu_element_options['mobile_menu_label_et-desktop']      = get_theme_mod( 'mobile_menu_label_et-desktop', '0' );
	$mob_menu_element_options['mobile_menu_label_text_et-desktop'] = get_theme_mod( 'mobile_menu_text', 'Menu' );
	
	// $mob_menu_element_options['mobile_menu_logo_type'] = get_theme_mod( 'mobile_menu_logo_type_et-desktop' );
	// $mob_menu_element_options['mobile_menu_logo_filter'] = ( $mob_menu_element_options['mobile_menu_logo_type'] == 'sticky' ) ? 'simple' : 'sticky';
	
	// $mob_menu_element_options['mobile_menu_2'] = ( get_theme_mod( 'mobile_menu_2' ) != 'none' ) ? true : false;
	
	$mob_menu_element_options['mobile_menu_classes'] = ' static';
	$mob_menu_element_options['mobile_menu_classes'] .= ( $mob_menu_element_options['mobile_menu_type_et-desktop'] != 'popup' ) ? ' et-content_toggle et-off-canvas et-content-' . $mob_menu_element_options['mobile_menu_content_position'] : ' ';
	$mob_menu_element_options['mobile_menu_classes'] .= ' toggles-by-arrow';
	
	ob_start(); ?>

    <span class="et-element-label-wrapper flex <?php echo $mob_menu_element_options['mobile_menu_content_alignment']; ?>">
			<span class="flex-inline align-items-center et-element-label pointer et-<?php echo ( $mob_menu_element_options['mobile_menu_type_et-desktop'] != 'popup' ) ? '' : 'popup_'; ?>toggle valign-center" <?php echo ( $mob_menu_element_options['mobile_menu_type_et-desktop'] == 'popup' || $et_builder_globals['is_customize_preview'] ) ? 'data-type="mobile_menu"' : ''; ?>>
				<?php echo $mob_menu_element_options['mobile_menu_icon_et-desktop']; ?>
				<?php if ( $mob_menu_element_options['mobile_menu_label_et-desktop'] || $et_builder_globals['is_customize_preview'] ) { ?>
                    <span class="<?php echo ( $et_builder_globals['is_customize_preview'] && ! $mob_menu_element_options['mobile_menu_label_et-desktop'] ) ? 'none' : ''; ?>">
						<?php echo $mob_menu_element_options['mobile_menu_label_text_et-desktop']; ?>
					</span>
				<?php } ?>
			</span>
		</span>
	<?php if ( $mob_menu_element_options['mobile_menu_type_et-desktop'] != 'popup' ) : ?>
        <div class="et-mini-content">
			<span class="et-toggle pos-absolute et-close full-<?php echo $mob_menu_element_options['mobile_menu_content_position']; ?> top">
				<svg xmlns="http://www.w3.org/2000/svg" width="0.8em" height="0.8em" viewBox="0 0 24 24">
					<path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
				</svg>
			</span>

            <div class="et-content mobile-menu-content children-align-inherit">
				<?php echo mobile_menu_content_callback(); ?>
            </div>
        </div>
	<?php endif;
	
	$html = ob_get_clean();
	
	return $html;
}

/**
 *
 * @return  {html} mobile menu content element
 * @version 1.0.0
 * @see     /templates/header/parts/mobile-menu
 * @since   1.5.5
 */
function mobile_menu_content_callback() {
	
	global $et_builder_globals;
	
	$et_builder_globals['in_mobile_menu'] = true;
	
	$mob_menu_element_options = array();
	
	$mob_menu_element_options['mobile_menu_logo_type']   = get_theme_mod( 'mobile_menu_logo_type_et-desktop', 'simple' );
	$mob_menu_element_options['mobile_menu_logo_filter'] = ( $mob_menu_element_options['mobile_menu_logo_type'] == 'sticky' ) ? 'simple' : 'sticky';
	
	$mob_menu_element_options['mobile_menu_type_et-desktop'] = get_theme_mod( 'mobile_menu_type_et-desktop', 'off_canvas_left' );
	$mob_menu_element_options['mobile_menu_content']         = get_theme_mod( 'mobile_menu_content', array(
		'logo',
		'search',
		'menu',
		'header_socials'
	) );
	$mob_menu_element_options['etheme_filters']              = array(
		"etheme_logo_{$mob_menu_element_options['mobile_menu_logo_filter']}" => 'etheme_return_false',
		'logo_align'                                                         => 'etheme_return_align_center',
		'etheme_mini_content'                                                => 'etheme_return_false',
//		'menu_item_design' => 'etheme_menu_item_design_dropdown', // some clients need to show mega-menu still
		'etheme_search_results'                                              => 'etheme_return_true',
		'search_category'                                                    => 'etheme_return_false',
		'cart_off_canvas'                                                    => 'etheme_return_false',
		'menu_dropdown_ajax'                                                 => 'etheme_return_false',
		'etheme_mini_cart_content'                                           => 'etheme_return_false',
		'etheme_mini_account_content'                                        => 'etheme_return_false',
		'account_off_canvas'                                                 => 'etheme_return_false',
		'etheme_mini_wishlist_content'                                       => 'etheme_return_false',
		'wishlist_off_canvas'                                                => 'etheme_return_false',
        'etheme_mini_compare_content'                                        => 'etheme_return_false',
        'compare_off_canvas'                                                 => 'etheme_return_false',
		'et_mobile_menu'                                                     => 'etheme_return_true',
		'etheme_use_desktop_style'                                           => 'etheme_return_true',
		'search_type'                                                        => 'etheme_mobile_type_input',
		'search_by_icon'                                                     => 'etheme_return_false',
		'cart_style'                                                         => 'etheme_mobile_content_element_type1',
		'account_style'                                                      => 'etheme_mobile_content_element_type1',
		'header_socials_direction'                                           => 'etheme_return_false',
		'contacts_icon_position'                                             => 'etheme_mobile_icon_left',
		'etheme_output_shortcodes_inline_css'                                => 'etheme_return_true',
		'search_ajax_with_tabs'                                              => 'etheme_return_false',
		'search_mode_is_popup'                                               => 'etheme_return_false'
	);
	foreach ( $mob_menu_element_options['etheme_filters'] as $key => $value ) {
		add_filter( $key, $value, 15 );
	}
	
	$mob_menu_element_options['mobile_menu_2']       = get_theme_mod( 'mobile_menu_2', 'none' );
	$mob_menu_element_options['mobile_menu_2_state'] = ( $mob_menu_element_options['mobile_menu_2'] != 'none' ) ? true : false;
	
	$mob_menu_element_options['mobile_menu_2_term']      = ( $mob_menu_element_options['mobile_menu_2'] == 'menu' ) ? get_theme_mod( 'mobile_menu_2_term' ) : '';
	$mob_menu_element_options['mobile_menu_2_term_name'] = $mob_menu_element_options['mobile_menu_2_term'] == '' ? 'main-menu' : $mob_menu_element_options['mobile_menu_2_term'];
	
	$mob_menu_element_options['mobile_menu_tab_2_text']           = get_theme_mod( 'mobile_menu_tab_2_text', 'Categories' );
	$mob_menu_element_options['mobile_menu_2_categories_primary'] = get_theme_mod( 'mobile_menu_2_categories_primary', false );
	
	$mob_menu_element_options['mobile_menu_term']      = get_theme_mod( 'mobile_menu_term' );
	$mob_menu_element_options['mobile_menu_term_name'] = $mob_menu_element_options['mobile_menu_term'] == '' ? 'main-menu' : $mob_menu_element_options['mobile_menu_term'];
	$mob_menu_element_options['mobile_menu_one_page']  = get_theme_mod( 'mobile_menu_one_page', '0' ) ? ' one-page-menu' : '';
	$args                                              = array(
		'menu'            => $mob_menu_element_options['mobile_menu_term_name'],
		'before'          => '',
		'container_class' => 'menu-main-container' . $mob_menu_element_options['mobile_menu_one_page'],
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'depth'           => 4,
		'echo'            => false,
		'fallback_cb'     => false,
		'walker'          => new ETheme_Navigation
	);
	
	$mob_menu_element_options['mobile_menu_2_tabs'] = $mob_menu_element_options['mobile_menu_2_wrapper_start'] = $mob_menu_element_options['mobile_menu_2_wrapper_end'] = '';
	if ( $mob_menu_element_options['mobile_menu_2_state'] ) {
		$mob_menu_element_options['mobile_menu_2_wrapper_start'] = '<div class="et_b-tabs-wrapper">';
		$mob_menu_element_options['mobile_menu_2_wrapper_end']   = '</div>';
		ob_start(); ?>
        <div class="et_b-tabs">
			<?php if ( ! $mob_menu_element_options['mobile_menu_2_categories_primary'] ) : ?>
                <span class="et-tab <?php echo ( ! $mob_menu_element_options['mobile_menu_2_categories_primary'] ) ? 'active' : ''; ?>"
                      data-tab="menu">
                        <?php esc_html_e( 'Menu', 'xstore-core' ); ?>
                    </span>
			<?php endif; ?>
            <span class="et-tab <?php echo ( $mob_menu_element_options['mobile_menu_2_categories_primary'] ) ? 'active' : ''; ?>"
                  data-tab="menu_2">
                    <?php echo esc_html( $mob_menu_element_options['mobile_menu_tab_2_text'] ); ?>
                </span>
			<?php if ( $mob_menu_element_options['mobile_menu_2_categories_primary'] ) : ?>
                <span class="et-tab <?php echo ( ! $mob_menu_element_options['mobile_menu_2_categories_primary'] ) ? 'active' : ''; ?>"
                      data-tab="menu">
                        <?php esc_html_e( 'Menu', 'xstore-core' ); ?>
                    </span>
			<?php endif; ?>
        </div>
		<?php
		$mob_menu_element_options['mobile_menu_2_tabs'] = ob_get_clean();
		
		ob_start();
		
		if ( $mob_menu_element_options['mobile_menu_2'] == 'categories' ) {
			$mob_menu_element_options['mobile_menu_2_categories_params'] = array( 'title' => '', 'orderby' => 'order' );
			if ( get_theme_mod( 'mobile_menu_2_categories_hide_empty', false ) ) {
				$mob_menu_element_options['mobile_menu_2_categories_params']['hide_empty'] = 1;
			}
			the_widget( 'WC_Widget_Product_Categories', $mob_menu_element_options['mobile_menu_2_categories_params'] );
		} else {
			$args_2 = array(
				'menu'            => $mob_menu_element_options['mobile_menu_2_term_name'],
				'before'          => '',
				'container_class' => 'menu-main-container',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'depth'           => 4,
				'echo'            => false,
				'fallback_cb'     => false,
				'walker'          => new ETheme_Navigation
			);
			
			$element_options['mob_menu_getter'] = wp_nav_menu( $args_2 );
			
			if ( $element_options['mob_menu_getter'] != '' ) { ?>
                <div class="et_element et_b_header-menu header-mobile-menu flex align-items-center"
                     data-title="<?php esc_html_e( 'Menu', 'xstore-core' ); ?>">
					<?php echo $element_options['mob_menu_getter']; ?>
                </div>
			<?php } else { ?>
                <span class="flex-inline justify-content-center align-items-center flex-nowrap">
                        <?php esc_html_e( 'Mobile menu 2', 'xstore-core' ); ?>
                        <span class="mtips" style="text-transform: none;">
                            <i class="et-icon et-exclamation"
                               style="margin-left: 3px; vertical-align: middle; font-size: 75%;"></i>
                            <span class="mt-mes"><?php esc_html_e( 'To use Mobile menu 2 please select your menu in dropdown', 'xstore-core' ); ?></span>
                        </span>
                    </span>
			<?php }
		}
		
		$mob_menu_element_options['mobile_menu_2_content'] = ob_get_clean();
		
	}
	
	ob_start();
	foreach ( $mob_menu_element_options['mobile_menu_content'] as $key => $value ) {
		if ( $value == 'menu' && $mob_menu_element_options['mobile_menu_2_state'] ) {
			echo $mob_menu_element_options['mobile_menu_2_wrapper_start'];
			echo $mob_menu_element_options['mobile_menu_2_tabs'];
			?>
            <div class="et_b-tab-content <?php echo ( ! $mob_menu_element_options['mobile_menu_2_categories_primary'] ) ? 'active' : ''; ?>"
                 data-tab-name="menu">
				<?php
				$element_options['mob_menu_2_getter'] = wp_nav_menu( $args );
				if ( $element_options['mob_menu_2_getter'] != '' ) {
					?>
                    <div class="et_element et_b_header-menu header-mobile-menu flex align-items-center"
                         data-title="<?php esc_html_e( 'Menu', 'xstore-core' ); ?>">
						<?php echo $element_options['mob_menu_2_getter']; ?>
                    </div>
				<?php } else { ?>
                    <span class="flex-inline justify-content-center align-items-center flex-nowrap">
                                <?php esc_html_e( 'Mobile menu ', 'xstore-core' ); ?>
                                <span class="mtips" style="text-transform: none;">
                                    <i class="et-icon et-exclamation"
                                       style="margin-left: 3px; vertical-align: middle; font-size: 75%;"></i>
                                    <span class="mt-mes"><?php esc_html_e( 'To use Mobile menu please select your menu in dropdown', 'xstore-core' ); ?></span>
                                </span>
                            </span>
				<?php } ?>
            </div>
            <div class="et_b-tab-content <?php echo ( $mob_menu_element_options['mobile_menu_2_categories_primary'] ) ? 'active' : ''; ?>"
                 data-tab-name="menu_2">
				<?php
				echo $mob_menu_element_options['mobile_menu_2_content'];
				?>
            </div>
			<?php
			echo $mob_menu_element_options['mobile_menu_2_wrapper_end'];
		} else {
			if ( $value == 'menu' ) {
				$element_options['mob_menu_2_getter'] = wp_nav_menu( $args );
				if ( $element_options['mob_menu_2_getter'] != '' ) {
					?>
                    <div class="et_element et_b_header-menu header-mobile-menu flex align-items-center"
                         data-title="<?php esc_html_e( 'Menu', 'xstore-core' ); ?>">
						<?php echo $element_options['mob_menu_2_getter']; ?>
                    </div>
				<?php } else { ?>
                    <span class="flex-inline justify-content-center align-items-center flex-nowrap">
                            <?php esc_html_e( 'Mobile menu ', 'xstore-core' ); ?>
                            <span class="mtips" style="text-transform: none;">
                                <i class="et-icon et-exclamation"
                                   style="margin-left: 3px; vertical-align: middle; font-size: 75%;"></i>
                                <span class="mt-mes"><?php esc_html_e( 'To use Mobile menu please select your menu in dropdown', 'xstore-core' ); ?></span>
                            </span>
                        </span>
				<?php }
			} else {
				require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/' . $value . '.php' );
			}
		}
	}
	
	if ( $et_builder_globals['is_customize_preview'] ) {
		// for toggle action on menu items ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                etTheme.mobileMenu();
            });
        </script>
	<?php }
	
	$html = ob_get_clean();
	
	foreach ( $mob_menu_element_options['etheme_filters'] as $key => $value ) {
		remove_filter( $key, $value, 15 );
	}
	
	$et_builder_globals['in_mobile_menu'] = false;
	
	return $html;
}

/**
 *
 * @return  {html} header cart content
 * @version 1.0.1
 * @since   1.5.4
 */
function header_cart_callback() {
	
	global $et_cart_icons, $et_builder_globals;
	
	$element_options                              = array();
	$element_options['is_woocommerce_et-desktop'] = class_exists( 'WooCommerce' );
	$element_options['cart_style']                = get_theme_mod( 'cart_style_et-desktop', 'type1' );
	$element_options['cart_style']                = apply_filters( 'cart_style', $element_options['cart_style'] );
	
	$element_options['cart_type_et-desktop'] = get_theme_mod( 'cart_icon_et-desktop', 'type1' );
	$element_options['cart_type_et-desktop'] = apply_filters( 'cart_icon', $element_options['cart_type_et-desktop'] );
	
	$element_options['cart_icon']   = false;
	$element_options['icon_custom'] = get_theme_mod( 'cart_icon_custom_svg_et-desktop', '' );
	$element_options['icon_custom'] = apply_filters( 'cart_icon_custom', $element_options['icon_custom'] );
	$element_options['icon_custom'] = isset( $element_options['icon_custom']['id'] ) ? $element_options['icon_custom']['id'] : '';
	
	if ( ! get_theme_mod( 'bold_icons', 0 ) ) {
		$element_options['cart_icons_et-desktop'] = $et_cart_icons['light'];
	} else {
		$element_options['cart_icons_et-desktop'] = $et_cart_icons['bold'];
	}
	
	if ( $element_options['cart_type_et-desktop'] == 'custom' ) {
		if ( $element_options['icon_custom'] != '' ) {
			$element_options['cart_icons_et-desktop']['custom'] = etheme_get_svg_icon( $element_options['icon_custom'] );
		} else {
			$element_options['cart_icons_et-desktop']['custom'] = $element_options['cart_icons_et-desktop']['type1'];
		}
	}
	
	if ( $element_options['is_woocommerce_et-desktop'] ) {
		$element_options['cart_icon'] = $element_options['cart_icons_et-desktop'][ $element_options['cart_type_et-desktop'] ];
	}
	$element_options['cart_label_et-desktop'] = get_theme_mod( 'cart_label_et-desktop', '1' );
	$element_options['cart_label_et-mobile']  = get_theme_mod( 'cart_label_et-mobile', '0' );
	$element_options['cart_label']            = ( $element_options['cart_label_et-desktop'] || $element_options['cart_label_et-mobile'] || ( isset( $et_builder_globals['in_mobile_menu'] ) && ( $et_builder_globals['in_mobile_menu'] || $et_builder_globals['is_customize_preview'] ) ) ) ? true : false;
	$element_options['cart_label_text']       = '';
	
	if ( $element_options['cart_label'] ) {
		$element_options['cart_label_text'] = esc_html__( 'Cart', 'xstore-core' );
		if ( get_theme_mod( 'cart_label_custom', 'Cart' ) != '' ) {
			$element_options['cart_label_text'] = get_theme_mod( 'cart_label_custom', 'Cart' );
		}
	}
	
	$element_options['cart_total_et-desktop'] = get_theme_mod( 'cart_total_et-desktop', 1 );
	$element_options['cart_total_et-mobile']  = get_theme_mod( 'cart_total_et-mobile', 0 );
	$element_options['cart_total']            = ( $element_options['cart_total_et-desktop'] || $element_options['cart_total_et-mobile'] || ( isset( $et_builder_globals['in_mobile_menu'] ) && $et_builder_globals['is_customize_preview'] ) ) ? true : false;
	
	$element_options['cart_quantity_et-desktop']          = get_theme_mod( 'cart_quantity_et-desktop', '1' );
	$element_options['cart_quantity_position_et-desktop'] = ( $element_options['cart_quantity_et-desktop'] ) ? ' et-quantity-' . get_theme_mod( 'cart_quantity_position_et-desktop', 'cart' ) : '';
	
	$element_options['cart_content_position_et-desktop'] = get_theme_mod( 'cart_content_position_et-desktop', 'right' );
	
	$element_options['cart_content_alignment'] = ' justify-content-' . get_theme_mod( 'cart_content_alignment_et-desktop', 'start' );
	$element_options['cart_content_alignment'] .= ' mob-justify-content-' . get_theme_mod( 'cart_content_alignment_et-mobile', 'start' );
	
	$element_options['cart_content_type_et-desktop']      = get_theme_mod( 'cart_content_type_et-desktop', 'dropdown' );
	$element_options['cart_dropdown_position_et-desktop'] = get_theme_mod( 'cart_dropdown_position_et-desktop', 'right' );
	
	$cart_footer_content_et_desktop = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M23.448 7.248h-3.24v-1.032c0-0.528-0.432-0.96-0.96-0.96h-11.784c-0.528 0-0.96 0.432-0.96 0.96v2.304h-3.048c0 0 0 0 0 0-0.192 0-0.384 0.096-0.48 0.264l-1.56 2.736h-0.864c-0.312 0-0.552 0.24-0.552 0.552v4.416c0 0.288 0.24 0.552 0.552 0.552h1.032c0.264 1.032 1.176 1.728 2.208 1.728 0.144 0 0.288-0.024 0.432-0.048 0.888-0.168 1.584-0.816 1.8-1.68h1.032c0.048 0 0.12-0.024 0.168-0.024 0.072 0.024 0.168 0.024 0.24 0.024h5.040c0.288 1.176 1.44 1.92 2.64 1.68 0.888-0.168 1.584-0.816 1.8-1.68h2.328c0.528 0 0.96-0.432 0.96-0.96v-3.48h2.4c0.312 0 0.552-0.24 0.552-0.552s-0.24-0.552-0.552-0.552h-2.4v-1.032h0.288c0.312 0 0.552-0.24 0.552-0.552s-0.24-0.552-0.552-0.552h-0.288v-1.032h3.24c0.312 0 0.552-0.24 0.552-0.552-0.024-0.288-0.264-0.528-0.576-0.528zM16.848 7.8c0 0.312 0.24 0.552 0.552 0.552h1.728v1.032h-4.68c-0.312 0-0.552 0.24-0.552 0.552s0.24 0.552 0.552 0.552h4.656v1.032h-2.568c-0.144 0-0.288 0.048-0.384 0.168-0.096 0.096-0.168 0.24-0.168 0.384 0 0.312 0.24 0.552 0.552 0.552h2.544v3.312h-2.16c-0.144-0.552-0.456-1.008-0.936-1.344-0.504-0.336-1.104-0.48-1.704-0.36-0.888 0.168-1.584 0.816-1.8 1.68l-4.92-0.024 0.024-9.552 11.496 0.024v0.888h-1.728c-0.264 0-0.504 0.24-0.504 0.552zM14.712 15.288c0.648 0 1.2 0.528 1.2 1.2 0 0.648-0.528 1.2-1.2 1.2-0.648 0-1.2-0.528-1.2-1.2 0.024-0.672 0.552-1.2 1.2-1.2zM3.792 15.288c0.648 0 1.2 0.528 1.2 1.2 0 0.648-0.528 1.2-1.2 1.2s-1.2-0.528-1.2-1.2c0.024-0.672 0.552-1.2 1.2-1.2zM6.48 12.6v3.312h-0.48c-0.144-0.552-0.456-1.008-0.936-1.344-0.504-0.336-1.104-0.48-1.704-0.36-0.888 0.168-1.584 0.816-1.8 1.68h-0.48v-3.288h5.4zM6.48 9.624v1.896h-3.792l1.080-1.872h2.712z"></path></svg>' . esc_html__( 'Free shipping over 49$', 'xstore-core' );
	
	$element_options['cart_footer_content_et-desktop'] = get_theme_mod( 'cart_footer_content_et-desktop', $cart_footer_content_et_desktop );
	
	$element_options['cart_link_to'] = get_theme_mod( 'cart_link_to', 'cart_url' );
	switch ( $element_options['cart_link_to'] ) {
		case 'custom_url':
			$element_options['cart_link'] = get_theme_mod( 'cart_custom_url', '#' );
			break;
		case 'checkout_url':
			$element_options['cart_link'] = ( $element_options['is_woocommerce_et-desktop'] ) ? wc_get_checkout_url() : home_url();
			break;
		default:
			$element_options['cart_link'] = ( $element_options['is_woocommerce_et-desktop'] ) ? wc_get_cart_url() : home_url();
			break;
	}
	
	$element_options['not_cart_checkout'] = ( $element_options['is_woocommerce_et-desktop'] && ! ( is_cart() || is_checkout() ) ) ? true : false;
	$element_options['not_cart_checkout'] = apply_filters( 'etheme_cart_content_shown_cart_checkout_pages', $element_options['not_cart_checkout'] );
	
	if ( isset( $et_builder_globals['in_mobile_menu'] ) && $et_builder_globals['in_mobile_menu'] ) {
		$element_options['cart_style']                        = 'type1';
		$element_options['cart_quantity_et-desktop']          = false;
		$element_options['cart_quantity_position_et-desktop'] = '';
		$element_options['cart_content_alignment']            = ' justify-content-inherit';
		$element_options['cart_content_type_et-desktop']      = 'none';
	}
	
	$element_options['cart_content_alignment'] = apply_filters( 'cart_content_alignment', $element_options['cart_content_alignment'] );
	
	$element_options['booster_progress_bar'] = get_option( 'xstore_sales_booster_settings_progress_bar', get_theme_mod( 'booster_progress_bar_et-desktop', false ) );
	
	// filters
	$element_options['etheme_mini_cart_content_type'] = apply_filters( 'etheme_mini_cart_content_type', $element_options['cart_content_type_et-desktop'] );
	
	$element_options['etheme_mini_cart_content'] = ( $element_options['etheme_mini_cart_content_type'] != 'none' ) ? true : false;
	$element_options['etheme_mini_cart_content'] = apply_filters( 'etheme_mini_cart_content', $element_options['etheme_mini_cart_content'] );
	
	$element_options['etheme_mini_cart_content_position'] = apply_filters( 'etheme_mini_cart_content_position', $element_options['cart_content_position_et-desktop'] );
	
	$element_options['cart_off_canvas'] = ( $element_options['etheme_mini_cart_content_type'] == 'off_canvas' ) ? true : false;
	$element_options['cart_off_canvas'] = apply_filters( 'cart_off_canvas', $element_options['cart_off_canvas'] );
	
	// link classes
	
	$element_options['class'] = ' flex flex-wrap full-width align-items-center';
	$element_options['class'] .= ' ' . $element_options['cart_content_alignment'];
	$element_options['class'] .= ( $element_options['cart_off_canvas'] && $element_options['etheme_mini_cart_content'] && $element_options['not_cart_checkout'] ) ? ' et-toggle' : '';
	
	// on testing mode
	if ( ! $et_builder_globals['in_mobile_menu'] && get_theme_mod( 'cart_background_et-desktop', 'current' ) == 'current' ) {
		$element_options['class'] .= ' currentColor';
	}
	
	$element_options['label_class'] = ( ! $element_options['cart_label_et-mobile'] ) ? 'mob-hide' : '';
	$element_options['label_class'] .= ( ! $element_options['cart_label_et-desktop'] ) ? ' dt-hide' : '';
	
	$element_options['total_class'] = ( ! $element_options['cart_total_et-mobile'] ) ? 'mob-hide' : '';
	$element_options['total_class'] .= ( ! $element_options['cart_total_et-desktop'] ) ? ' dt-hide' : '';
	
	ob_start();
	
	if ( $element_options['is_woocommerce_et-desktop'] ) : ?>
        <a href="<?php echo $element_options['cart_link']; ?>" class="<?php echo $element_options['class']; ?>">
			<span class="flex<?php echo ( ! $et_builder_globals['in_mobile_menu'] ) ? '-inline' : ''; ?> justify-content-center align-items-center
			<?php if ( $element_options['cart_style'] == 'type2' ) { ?>flex-wrap<?php } ?>">

				<?php if ( $element_options['is_woocommerce_et-desktop'] ) : ?>
					
					<?php if ( in_array( $element_options['cart_style'], array( 'type1', 'type2' ) ) ) : ?>
                        <span class="et_b-icon">
							<?php if ( $element_options['cart_icon'] != '' ) {
								echo '<span class="et-svg">' . $element_options['cart_icon'] . '</span>';
							} ?>
							<?php if ( $element_options['cart_quantity_et-desktop'] ) {
								etheme_cart_quantity();
							} ?>
						</span>
					<?php endif; // cart_position-before ?>

					<?php if ( $element_options['cart_label'] ) : ?>
                        <span class="et-element-label inline-block <?php echo ( ! $et_builder_globals['in_mobile_menu'] ) ? $element_options['label_class'] : ''; ?>">
							<?php echo $element_options['cart_label_text']; ?>
						</span>
					<?php endif; // end cart_label ?>
				
					<?php if ( $element_options['cart_total'] ) { ?>
                        <span class="et-cart-total et-total <?php echo $element_options['total_class']; ?>">
							<?php etheme_cart_total(); ?>
						</span>
					<?php } ?>

					<?php if ( $element_options['cart_style'] === 'type3' ) : ?>
                        <span class="et_b-icon">
							<?php if ( $element_options['cart_icon'] != '' ) {
								echo '<span class="et-svg">' . $element_options['cart_icon'] . '</span>';
							} ?>
							<?php if ( $element_options['cart_quantity_et-desktop'] ) {
								etheme_cart_quantity();
							} ?>
						</span>
					<?php endif; // cart_position-after ?>
				<?php endif; ?>
			</span>
        </a>
		<?php etheme_cart_quantity(); ?>
		<?php if ( $element_options['etheme_mini_cart_content'] && $element_options['not_cart_checkout'] ) :
			
			$element_options['xstore_sales_booster_settings']         = (array) get_option( 'xstore_sales_booster_settings', array() );
			$element_options['xstore_sales_booster_settings_default'] = array(
				'progress_bar'          => get_option( 'xstore_sales_booster_settings_progress_bar', get_theme_mod( 'booster_progress_bar_et-desktop', false ) ),
				'message_text'          => get_theme_mod( 'booster_progress_content_et-desktop', esc_html__( 'Spend {{et_price}} to get free shipping', 'xstore-core' ) ),
				'process_icon'          => get_theme_mod( 'booster_progress_icon_et-desktop', 'et_icon-delivery' ),
				'process_icon_position' => get_theme_mod( 'booster_progress_icon_position_et-desktop', 'before' ) != 'after' ? 'before' : 'after',
				'price'                 => get_theme_mod( 'booster_progress_price_et-desktop', 350 ),
				'message_success_text'  => get_theme_mod( 'booster_progress_content_success_et-desktop', esc_html__( 'Congratulations! You\'ve got free shipping.', 'xstore-core' ) ),
				'success_icon'          => get_theme_mod( 'booster_progress_success_icon_et-desktop', 'et_icon-star' ),
				'success_icon_position' => get_theme_mod( 'booster_progress_success_icon_position_et-desktop', 'before' ),
			);
			
			$element_options['xstore_sales_booster_settings_progress_bar'] = $element_options['xstore_sales_booster_settings_default'];
			
			if ( count( $element_options['xstore_sales_booster_settings'] ) && isset( $element_options['xstore_sales_booster_settings']['progress_bar'] ) ) {
				$element_options['xstore_sales_booster_settings'] = wp_parse_args( $element_options['xstore_sales_booster_settings']['progress_bar'],
					$element_options['xstore_sales_booster_settings_default'] );
				
				$element_options['xstore_sales_booster_settings_progress_bar'] = $element_options['xstore_sales_booster_settings'];
			}
			
			$element_options['cart_options'] = array(
				'etheme_mini_cart_content_position'      => $element_options['etheme_mini_cart_content_position'],
				'cart_off_canvas'                        => $element_options['cart_off_canvas'],
				'cart_link'                              => $element_options['cart_link'],
				'cart_quantity'                          => $element_options['cart_quantity_et-desktop'],
				'cart_quantity_position'                 => get_theme_mod( 'cart_quantity_position_et-desktop', 1 ),
				'cart_icon'                              => $element_options['cart_icon'],
				'cart_icon_backup'                       => $element_options['cart_icons_et-desktop']['type1'],
				'cart_footer_content'                    => $element_options['cart_footer_content_et-desktop'],
				'booster_progress_bar'                   => $element_options['xstore_sales_booster_settings_progress_bar']['progress_bar'],
				'booster_progress_content'               => $element_options['xstore_sales_booster_settings_progress_bar']['message_text'],
				'booster_progress_icon'                  => $element_options['xstore_sales_booster_settings_progress_bar']['process_icon'],
				'booster_progress_icon_position'         => $element_options['xstore_sales_booster_settings_progress_bar']['process_icon_position'],
				'booster_progress_content_success'       => $element_options['xstore_sales_booster_settings_progress_bar']['message_success_text'],
				'booster_progress_success_icon'          => $element_options['xstore_sales_booster_settings_progress_bar']['success_icon'],
				'booster_progress_success_icon_position' => $element_options['xstore_sales_booster_settings_progress_bar']['success_icon_position'],
				'booster_progress_price'                 => $element_options['xstore_sales_booster_settings_progress_bar']['price']
			);
			
			echo et_cart_mini_content_callback( $element_options['cart_options'], $et_builder_globals['is_customize_preview'] );
		
		endif; ?>
	<?php else : ?>
        <span class="flex flex-wrap full-width align-items-center currentColor">
				<span class="flex-inline justify-content-center align-items-center flex-nowrap">
		            <?php esc_html_e( 'Cart ', 'xstore-core' ); ?>
		            <span class="mtips" style="text-transform: none;">
		                <i class="et-icon et-exclamation"
                           style="margin-left: 3px; vertical-align: middle; font-size: 75%;"></i>
		                <span class="mt-mes"><?php esc_html_e( 'To use Cart please install WooCommerce plugin', 'xstore-core' ); ?></span>
		            </span>
		        </span>
			</span>
	<?php endif;
	
	$html = ob_get_clean();
	
	return $html;
	
}

/**
 *
 * @param   {array} settings to check
 * @param   {boolean} is_customize_preview()
 *
 * @return  {html} cart dropdown/canvas content
 * @version 1.0.0
 * @since   2.3.1
 */
function et_cart_mini_content_callback(
	$cart_options = array(
		'etheme_mini_cart_content_position'      => 'left',
		'cart_off_canvas'                        => false,
		'cart_link'                              => '',
		'cart_quantity'                          => false,
		'cart_quantity_position'                 => 'top',
		'cart_icon'                              => '',
		'cart_icon_backup'                       => '',
		'cart_footer_content'                    => '',
		'booster_progress_bar'                   => false,
		'booster_progress_content'               => '',
		'booster_progress_icon'                  => '',
		'booster_progress_icon_position'         => 'before',
		'booster_progress_content_success'       => '',
		'booster_progress_success_icon'          => '',
		'booster_progress_success_icon_position' => 'before',
		'booster_progress_price'                 => '',
	), $is_customize_preview = false
) {
	$cart_options['booster_progress_bar_output'] = '';
	
	ob_start();
	if ( $cart_options['booster_progress_bar'] ) :
		global $et_icons;
		$cart_options['cart_progress_bar_content'] = '<span class="et-cart-progress-amount" data-amount="' . $cart_options['booster_progress_price'] . '" data-currency="' . get_woocommerce_currency_symbol() . '"></span>';
		?>
        <div class="et-cart-progress woocommerce-mini-cart__footer flex justify-content-center align-items-center <?php echo ( $is_customize_preview && $cart_options['cart_footer_content'] == '' ) ? 'dt-hide mob-hide' : ''; ?>"
             data-percent-sold="0">
                        <span class="et-cart-in-progress">
                            <?php
                            $cart_options['booster_progress_content'] = '<span>' . str_replace( array( '{{et_price}}' ), array( $cart_options['cart_progress_bar_content'] ), $cart_options['booster_progress_content'] ) . '</span>';
                            if ( $cart_options['booster_progress_icon'] != 'none' ) {
	                            if ( $cart_options['booster_progress_icon_position'] == 'before' ) {
		                            $cart_options['booster_progress_content'] = '<span class="et_b-icon">' . $et_icons['light'][ $cart_options['booster_progress_icon'] ] . '</span>' . $cart_options['booster_progress_content'];
	                            } else {
		                            $cart_options['booster_progress_content'] .= '<span class="et_b-icon">' . $et_icons['light'][ $cart_options['booster_progress_icon'] ] . '</span>';
	                            }
                            }
                            echo $cart_options['booster_progress_content'];
                            ?>
                        </span>
            <span class="et-cart-progress-success">
                            <?php
                            $cart_options['booster_progress_content_success'] = '<span>' . $cart_options['booster_progress_content_success'] . '</span>';
                            if ( $cart_options['booster_progress_success_icon'] != 'none' ) {
	                            if ( $cart_options['booster_progress_success_icon_position'] == 'before' ) {
		                            $cart_options['booster_progress_content_success'] = '<span class="et_b-icon">' . $et_icons['light'][ $cart_options['booster_progress_success_icon'] ] . '</span>' . $cart_options['booster_progress_content_success'];
	                            } else {
		                            $cart_options['booster_progress_content_success'] .= '<span class="et_b-icon">' . $et_icons['light'][ $cart_options['booster_progress_success_icon'] ] . '</span>';
	                            }
                            }
                            echo $cart_options['booster_progress_content_success'];
                            ?>
                        </span>
            <progress class="et_cart-progress-bar" max="100" value="0"></progress>
        </div>
	<?php endif;
	$cart_options['booster_progress_bar_output'] = ob_get_clean();
	ob_start(); ?>
    <div class="et-mini-content">
		<?php if ( $cart_options['cart_off_canvas'] ) : ?>
            <span class="et-toggle pos-absolute et-close full-<?php echo $cart_options['etheme_mini_cart_content_position']; ?> top">
					<svg xmlns="http://www.w3.org/2000/svg" width="0.8em" height="0.8em" viewBox="0 0 24 24">
						<path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
					</svg>
				</span>
		<?php endif; ?>
        <div class="et-content">
			<?php if ( $cart_options['cart_off_canvas'] ) : ?>
                <div class="et-mini-content-head">
                    <a href="<?php echo $cart_options['cart_link']; ?>"
                       class="cart-type2 flex justify-content-center flex-wrap <?php echo 'et-quantity-' . ( $cart_options['cart_quantity'] ) ? $cart_options['cart_quantity_position'] : 'top' ?>">
						<?php if ( $cart_options['cart_icon'] == '' ) {
							$cart_options['cart_icon'] = $cart_options['cart_icon_backup'];
						}
						?>
                        <span class="et_b-icon">
                                    <?php echo '<span class="et-svg">' . $cart_options['cart_icon'] . '</span>';
                                    etheme_cart_quantity(); ?>
                                </span>
                        <span class="et-element-label pos-relative inline-block">
                                    <?php echo esc_html__( 'Shopping Cart', 'xstore-core' ); ?>
                                </span>
                    </a>
                </div>
			<?php endif; ?>

			<?php if ( class_exists( 'WC_Widget_Cart' ) && WC()->cart ) : ?>
				<?php if ( ! etheme_get_option( 'load_wc_cart_fragments', 0 ) && ! WC()->cart->cart_contents_count && function_exists( 'etheme_get_mini_cart_empty' ) ) : ?>
                    <div class="widget woocommerce widget_shopping_cart">
                        <div class="widget_shopping_cart_content">
                            <div class="woocommerce-mini-cart cart_list product_list_widget ">
								<?php etheme_get_mini_cart_empty(); ?>
                            </div>
                        </div>
                    </div>
				<?php else : ?>
					<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
				<?php endif; ?>
			<?php endif; ?>

            <div class="woocommerce-mini-cart__footer-wrapper">
				<?php etheme_woocomerce_mini_cart_footer();
				
				echo $cart_options['booster_progress_bar_output'];
				
				if ( $cart_options['cart_footer_content'] != '' || $is_customize_preview ) : ?>
                    <div class="woocommerce-mini-cart__footer flex justify-content-center align-items-center <?php echo ( $is_customize_preview && $cart_options['cart_footer_content'] == '' ) ? 'dt-hide mob-hide' : ''; ?>"><?php echo do_shortcode( $cart_options['cart_footer_content'] ); ?></div>
				<?php
				endif; ?>
            </div>
        </div>
    </div>
	
	<?php return ob_get_clean();
}

/**
 *
 * @return  {html} header wishlist content
 * @version 1.0.1
 * @since   1.5.4
 */
function header_wishlist_callback() {
	
	global $et_wishlist_icons, $et_builder_globals;
	
	$element_options                   = array();
    $element_options['built_in_wishlist'] = get_theme_mod('xstore_wishlist', false);
    $element_options['built_in_wishlist_page_id'] = $element_options['built_in_wishlist'] ? get_theme_mod('xstore_wishlist_page', '') : '';
    $element_options['built_in_wishlist_instance'] = $element_options['built_in_wishlist'] ? XStoreCore\Modules\WooCommerce\XStore_Wishlist::get_instance() : '';
	$element_options['is_YITH_WCWL']   = class_exists( 'YITH_WCWL' );
	$element_options['wishlist_style'] = get_theme_mod( 'wishlist_style_et-desktop', 'type1' );
	$element_options['wishlist_style'] = apply_filters( 'wishlist_style', $element_options['wishlist_style'] );
	
	$element_options['wishlist_type_et-desktop'] = get_theme_mod( 'wishlist_icon_et-desktop', 'type1' );
	$element_options['wishlist_type_et-desktop'] = apply_filters( 'wishlist_icon', $element_options['wishlist_type_et-desktop'] );
	
	if ( ! get_theme_mod( 'bold_icons', 0 ) ) {
		$element_options['wishlist_icons'] = $et_wishlist_icons['light'];
	} else {
		$element_options['wishlist_icons'] = $et_wishlist_icons['bold'];
	}
	
	$element_options['icon_custom'] = get_theme_mod( 'wishlist_icon_custom_svg_et-desktop', '' );
	$element_options['icon_custom'] = apply_filters( 'wishlist_icon_custom', $element_options['icon_custom'] );
	$element_options['icon_custom'] = isset( $element_options['icon_custom']['id'] ) ? $element_options['icon_custom']['id'] : '';
	
	if ( $element_options['wishlist_type_et-desktop'] == 'custom' ) {
		if ( $element_options['icon_custom'] != '' ) {
			$element_options['wishlist_icons']['custom'] = etheme_get_svg_icon( $element_options['icon_custom'] );
		} else {
			$element_options['wishlist_icons']['custom'] = $element_options['wishlist_icons']['type1'];
		}
	}
	
	$element_options['wishlist_icon']             = $element_options['wishlist_icons'][ $element_options['wishlist_type_et-desktop'] ];
	$element_options['wishlist_label_et-desktop'] = get_theme_mod( 'wishlist_label_et-desktop', '1' );
	$element_options['wishlist_label_et-mobile']  = get_theme_mod( 'wishlist_label_et-mobile', '0' );
	$element_options['wishlist_label']            = $element_options['wishlist_label_et-desktop'] || $element_options['wishlist_label_et-mobile'] || $et_builder_globals['in_mobile_menu'] || $et_builder_globals['is_customize_preview'];
	$element_options['wishlist_label_text']       = '';
	
	if ( $element_options['wishlist_label'] ) {
		$element_options['wishlist_label_text'] = esc_html__( 'Wishlist', 'xstore-core' );
		if ( get_theme_mod( 'wishlist_label_custom_et-desktop', 'Wishlist' ) != '' ) {
			$element_options['wishlist_label_text'] = get_theme_mod( 'wishlist_label_custom_et-desktop', 'Wishlist' );
		}
	}
	
	$element_options['wishlist_quantity_et-desktop']          = get_theme_mod( 'wishlist_quantity_et-desktop', '1' );
	$element_options['wishlist_quantity_position_et-desktop'] = ( $element_options['wishlist_quantity_et-desktop'] ) ? ' et-quantity-' . get_theme_mod( 'wishlist_quantity_position_et-desktop', 'right' ) : '';
	
	$element_options['wishlist_content_position_et-desktop'] = get_theme_mod( 'wishlist_content_position_et-desktop', 'right' );
	
	$element_options['wishlist_content_alignment'] = ' justify-content-' . get_theme_mod( 'wishlist_content_alignment_et-desktop', 'start' );
	$element_options['wishlist_content_alignment'] .= ' mob-justify-content-' . get_theme_mod( 'wishlist_content_alignment_et-mobile', 'start' );
	
	$element_options['wishlist_content_type_et-desktop']      = get_theme_mod( 'wishlist_content_type_et-desktop', 'dropdown' );
	$element_options['wishlist_dropdown_position_et-desktop'] = get_theme_mod( 'wishlist_dropdown_position_et-desktop', 'right' );
	
	if ( $et_builder_globals['in_mobile_menu'] ) {
		$element_options['wishlist_style']                        = 'type1';
		$element_options['wishlist_quantity_et-desktop']          = false;
		$element_options['wishlist_quantity_position_et-desktop'] = '';
		$element_options['wishlist_content_alignment']            = ' justify-content-inherit';
		$element_options['wishlist_content_type_et-desktop']      = 'none';
	}
	
	$element_options['wishlist_link_to'] = get_theme_mod( 'wishlist_link_to', 'wishlist_url' );
	switch ( $element_options['wishlist_link_to'] ) {
		case 'custom_url':
			$element_options['wishlist_link'] = get_theme_mod( 'wishlist_custom_url', '#' );
			break;
		default:
		    if ( $element_options['built_in_wishlist'] ) {
		        if ( $element_options['built_in_wishlist_page_id'] ) {
		            $element_options['wishlist_link'] = get_permalink($element_options['built_in_wishlist_page_id']);
                }
		        else {
                    $element_options['built_in_wishlist_ghost_page_id'] = absint(get_option( 'woocommerce_myaccount_page_id' ));
                    if ( $element_options['built_in_wishlist_ghost_page_id'] )
                        $element_options['wishlist_link'] = add_query_arg('et-wishlist-page', '', get_permalink($element_options['built_in_wishlist_ghost_page_id']));
                    else
                        $element_options['wishlist_link'] = home_url();
                }
            }
		    elseif ($element_options['is_YITH_WCWL']) {
                $element_options['wishlist_link'] = YITH_WCWL()->get_wishlist_url();
            }
		    else {
                $element_options['wishlist_link'] = home_url();
            }
			break;
	}
	
	// filters
	$element_options['etheme_mini_wishlist_content_type'] = apply_filters( 'etheme_mini_wishlist_content_type', $element_options['wishlist_content_type_et-desktop'] );
	
	$element_options['etheme_mini_wishlist_content'] = $element_options['etheme_mini_wishlist_content_type'] != 'none';
	$element_options['etheme_mini_wishlist_content'] = apply_filters( 'etheme_mini_wishlist_content', $element_options['etheme_mini_wishlist_content'] );
	
	$element_options['etheme_mini_wishlist_content_position'] = apply_filters( 'etheme_mini_wishlist_content_position', $element_options['wishlist_content_position_et-desktop'] );
	
	$element_options['wishlist_off_canvas'] = $element_options['etheme_mini_wishlist_content_type'] == 'off_canvas';
	$element_options['wishlist_off_canvas'] = apply_filters( 'wishlist_off_canvas', $element_options['wishlist_off_canvas'] );
	
	$element_options['not_wishlist_page'] = true;
	if ( $element_options['built_in_wishlist'] ) {
        $wishlist_page_id = $element_options['built_in_wishlist_page_id'];
        if ( ! empty( $wishlist_page_id ) && is_page( $wishlist_page_id ) || (isset($_GET['et-wishlist-page']) && is_account_page()) ) {
            $element_options['not_wishlist_page'] = false;
        }
    }
    elseif ( function_exists('yith_wcwl_object_id') ) {
        $wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );
        if ( ! empty( $wishlist_page_id ) && is_page( $wishlist_page_id ) ) {
            $element_options['not_wishlist_page'] = false;
        }
    }
	
	$element_options['wishlist_content_alignment'] = apply_filters( 'wishlist_content_alignment', $element_options['wishlist_content_alignment'] );
	
	// link classes
	$element_options['class'] = ' flex flex-wrap full-width align-items-center';
	$element_options['class'] .= ' ' . $element_options['wishlist_content_alignment'];
	$element_options['class'] .= ( $element_options['etheme_mini_wishlist_content'] ) ? ' et-toggle' : '';
	
	// on testing mode
	if ( ! $et_builder_globals['in_mobile_menu'] && get_theme_mod( 'wishlist_background_et-desktop', 'current' ) == 'current' ) {
		$element_options['class'] .= ' currentColor';
	}
	
	$element_options['label_class'] = ( ! $element_options['wishlist_label_et-mobile'] ) ? 'mob-hide' : '';
	$element_options['label_class'] .= ( ! $element_options['wishlist_label_et-desktop'] ) ? ' dt-hide' : '';
	
	ob_start(); ?>
    <a href="<?php echo $element_options['wishlist_link']; ?>" class="<?php echo $element_options['class']; ?>">
            <span class="flex<?php echo ( ! $et_builder_globals['in_mobile_menu'] ) ? '-inline' : ''; ?> justify-content-center align-items-center flex-wrap">
                <?php if ( in_array( $element_options['wishlist_style'], array( 'type1', 'type2' ) ) ) : ?>
                    <span class="et_b-icon">
                        <?php if ( $element_options['wishlist_icon'] != '' ) {
	                        echo '<span class="et-svg">' . $element_options['wishlist_icon'] . '</span>';
                        } ?>
                        <?php if ( $element_options['wishlist_quantity_et-desktop'] ) {
                            if ( $element_options['built_in_wishlist'] )
                                $element_options['built_in_wishlist_instance']->header_wishlist_quantity();
                            else
	                            etheme_wishlist_quantity();
                        } ?>
                    </span>
                <?php endif; // wishlist_position-before ?>
	
	            <?php if ( $element_options['wishlist_label'] ) : ?>
                    <span class="et-element-label inline-block <?php echo ( ! $et_builder_globals['in_mobile_menu'] ) ? $element_options['label_class'] : ''; ?>">
                        <?php echo $element_options['wishlist_label_text']; ?>
                    </span>
	            <?php endif; // end wishlist_label ?>
	
	            <?php if ( $element_options['wishlist_style'] === 'type3' ) : ?>
                    <span class="et_b-icon">
                        <?php if ( $element_options['wishlist_icon'] != '' ) {
	                        echo '<span class="et-svg">' . $element_options['wishlist_icon'] . '</span>';
                        } ?>
                        <?php if ( $element_options['wishlist_quantity_et-desktop'] ) {
                            if ( $element_options['built_in_wishlist'] )
                                $element_options['built_in_wishlist_instance']->header_wishlist_quantity();
                            else
                                etheme_wishlist_quantity();
                        } ?>
                    </span>
	            <?php endif; // wishlist_position-after ?>
            </span>
    </a>
	<?php $element_options['built_in_wishlist'] ? $element_options['built_in_wishlist_instance']->header_wishlist_quantity() : etheme_wishlist_quantity(); ?>
	<?php if ( $element_options['etheme_mini_wishlist_content'] && $element_options['not_wishlist_page'] ) :

		$element_options['wishlist_options'] = array(
			'etheme_mini_wishlist_content_position' => $element_options['wishlist_content_position_et-desktop'],
			'wishlist_off_canvas'                   => $element_options['wishlist_off_canvas'],
			'wishlist_link'                         => $element_options['wishlist_link'],
			'wishlist_quantity'                     => $element_options['wishlist_quantity_et-desktop'],
			'wishlist_quantity_position'            => get_theme_mod( 'wishlist_quantity_position_et-desktop', 'right' ),
			'wishlist_icon'                         => $element_options['wishlist_icon'],
			'wishlist_icon_backup'                  => $element_options['wishlist_icons']['type1'],
            'built_in_wishlist' => $element_options['built_in_wishlist'],
            'built_in_wishlist_instance' => $element_options['built_in_wishlist_instance'],
		);
		
		echo et_wishlist_mini_content_callback( $element_options['wishlist_options'], $et_builder_globals['is_customize_preview'] );

	endif;

	return ob_get_clean();

}

/**
 *
 * @param   {array} settings to check
 * @param   {boolean} is_customize_preview()
 *
 * @return  {html} wishlist dropdown/canvas content
 * @version 1.0.0
 * @since   2.3.1
 */
function et_wishlist_mini_content_callback(
	$wishlist_options = array(
		'etheme_mini_wishlist_content_position' => 'left',
		'wishlist_off_canvas'                   => false,
		'wishlist_link'                         => '',
		'wishlist_quantity'                     => '',
		'wishlist_quantity_position'            => 'top',
		'wishlist_icon'                         => '',
		'wishlist_icon_backup'                  => '',
        'built_in_wishlist' => false,
        'built_in_wishlist_instance' => '',
	), $is_customize_preview = false
) {
	
	ob_start(); ?>

    <div class="et-mini-content">
		<?php if ( $wishlist_options['wishlist_off_canvas'] ) : ?>
            <span class="et-toggle pos-absolute et-close full-<?php echo $wishlist_options['etheme_mini_wishlist_content_position']; ?> top">
                <svg xmlns="http://www.w3.org/2000/svg" width="0.8em" height="0.8em" viewBox="0 0 24 24">
                    <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                </svg>
            </span>
		<?php endif; ?>
        <div class="et-content">
			<?php if ( $wishlist_options['wishlist_off_canvas'] ) : ?>
                <div class="et-mini-content-head">
                    <a href="<?php echo $wishlist_options['wishlist_link']; ?>"
                       class="wishlist-type2 flex justify-content-center flex-wrap <?php echo 'et-quantity-' . ( $wishlist_options['wishlist_quantity'] ) ? $wishlist_options['wishlist_quantity_position'] : 'top' ?>">
						<?php if ( $wishlist_options['wishlist_icon'] == '' ) {
							$wishlist_options['wishlist_icon'] = $wishlist_options['wishlist_icons']['type1'];
						}
						?>
                        <span class="et_b-icon">
                                <?php
                                    echo '<span class="et-svg">' . $wishlist_options['wishlist_icon'] . '</span>';
                                    if ( $wishlist_options['built_in_wishlist'] )
                                        $wishlist_options['built_in_wishlist_instance']->header_wishlist_quantity();
                                    else
                                        etheme_wishlist_quantity();
                                ?>
                            </span>
                        <span class="et-element-label pos-relative inline-block">
                                <?php echo esc_html__( 'My Wishlist', 'xstore-core' ); ?>
                            </span>
                    </a>
                </div>
			<?php endif;
            if ( $wishlist_options['built_in_wishlist'] )
                $wishlist_options['built_in_wishlist_instance']->header_mini_wishlist();
            else
                etheme_mini_wishlist(); ?>
        </div>
    </div>
	
	<?php
	return ob_get_clean();
}

/**
 *
 * @return  {html} header compare content
 * @version 1.0.1
 * @since   2.3.7
 */
function header_compare_callback() {
	
	global $et_compare_icons, $et_builder_globals;
	
	$element_options = array();

    $element_options['built_in_compare'] = get_theme_mod('xstore_compare', false);
    $element_options['built_in_compare_page_id'] = $element_options['built_in_compare'] ? get_theme_mod('xstore_compare_page', '') : '';
    $element_options['built_in_compare_instance'] = $element_options['built_in_compare'] ? XStoreCore\Modules\WooCommerce\XStore_Compare::get_instance() : '';
	$element_options['is_YITH_Woocompare'] = defined( 'YITH_WOOCOMPARE' ) && class_exists( 'YITH_Woocompare_Frontend' );

    if ( !$element_options['built_in_compare'] && !$element_options['is_YITH_Woocompare'] ) :
		ob_start(); ?>
        <span class="flex flex-wrap full-width align-items-center currentColor">
                <span class="flex-inline justify-content-center align-items-center flex-nowrap">
                    <?php esc_html_e( 'Compare ', 'xstore-core' ); ?>
                    <span class="mtips" style="text-transform: none;">
                        <i class="et-icon et-exclamation"
                           style="margin-left: 3px; vertical-align: middle; font-size: 75%;"></i>
                        <span class="mt-mes"><?php echo current_user_can( 'edit_theme_options' ) ? sprintf(
                            /* translators: %s: URL to header image configuration in Customizer. */
                                __( 'Please, enable <a style="text-decoration: underline" href="%s" target="_blank">Built-in Compare</a>.', 'xstore-core'),
                                admin_url( 'customize.php?autofocus[section]=xstore-compare' )) :
                                __( 'Please, enable Built-in Compare.', 'xstore-core'); ?></span>
                    </span>
                    </span>
                </span>
            </span>
		<?php
		return ob_get_clean();
	endif;

	$element_options['compare_style'] = get_theme_mod( 'compare_style_et-desktop', 'type1' );
	$element_options['compare_style'] = apply_filters( 'compare_style', $element_options['compare_style'] );
	
	$element_options['compare_type'] = get_theme_mod( 'compare_icon_et-desktop', 'type1' );
	$element_options['compare_type'] = apply_filters( 'compare_icon', $element_options['compare_type'] );
	
	$element_options['icon_custom'] = get_theme_mod( 'compare_icon_custom_svg_et-desktop', '' );
//	$element_options['icon_custom'] = apply_filters('compare_icon_custom', $element_options['icon_custom']);
	$element_options['icon_custom'] = isset( $element_options['icon_custom']['id'] ) ? $element_options['icon_custom']['id'] : '';
	
	if ( ! get_theme_mod( 'bold_icons', 0 ) ) {
		$element_options['compare_icons'] = $et_compare_icons['light'];
	} else {
		$element_options['compare_icons'] = $et_compare_icons['bold'];
	}
	
	if ( $element_options['compare_type'] == 'custom' ) {
        if ( $element_options['icon_custom'] != '' ) {
            $element_options['compare_icons']['custom'] = etheme_get_svg_icon( $element_options['icon_custom'] );
        } else {
            $element_options['compare_icons']['custom'] = $element_options['compare_icons']['type1'];
        }
	}
    $element_options['compare_icon']             = $element_options['compare_icons'][ $element_options['compare_type'] ];
	
	$element_options['compare_label_et-desktop'] = get_theme_mod( 'compare_label_et-desktop', '1' );
	$element_options['compare_label_et-mobile']  = get_theme_mod( 'compare_label_et-mobile', '0' );
	$element_options['compare_label']            = ( $element_options['compare_label_et-desktop'] || $element_options['compare_label_et-mobile'] || $et_builder_globals['in_mobile_menu'] || $et_builder_globals['is_customize_preview'] ) ? true : false;
	$element_options['compare_label_text']       = '';
	
	if ( $element_options['compare_label'] ) {
		$element_options['compare_label_text'] = esc_html__( 'Compare', 'xstore-core' );
		if ( get_theme_mod( 'compare_label_custom_et-desktop', 'Compare' ) != '' ) {
			$element_options['compare_label_text'] = get_theme_mod( 'compare_label_custom_et-desktop', 'Compare' );
		}
	}

	if ( $element_options['built_in_compare'] ) {
        $element_options['compare_quantity_et-desktop'] = get_theme_mod( 'compare_quantity_et-desktop', '1' );
        $element_options['compare_quantity_position_et-desktop'] = ( $element_options['compare_quantity_et-desktop'] ) ? ' et-quantity-' . get_theme_mod( 'compare_quantity_position_et-desktop', 'right' ) : '';
        $element_options['compare_content_position_et-desktop'] = get_theme_mod( 'compare_content_position_et-desktop', 'right' );
        $element_options['compare_content_type_et-desktop']      = get_theme_mod( 'compare_content_type_et-desktop', 'dropdown' );
        $element_options['compare_dropdown_position_et-desktop'] = get_theme_mod( 'compare_dropdown_position_et-desktop', 'right' );
    }
	else {
        $element_options['compare_quantity_et-desktop'] = false;
        $element_options['compare_quantity_position_et-desktop'] = '';
        $element_options['compare_content_position_et-desktop'] = '';
        $element_options['compare_content_type_et-desktop'] = '';
        $element_options['compare_dropdown_position_et-desktop'] = '';
    }
	
	$element_options['compare_content_alignment'] = ' justify-content-' . get_theme_mod( 'compare_content_alignment_et-desktop', 'start' );
	$element_options['compare_content_alignment'] .= ' mob-justify-content-' . get_theme_mod( 'compare_content_alignment_et-mobile', 'start' );
	
	
	if ( $et_builder_globals['in_mobile_menu'] ) {
		$element_options['compare_style']             = 'type1';
        $element_options['compare_quantity_et-desktop']          = false;
        $element_options['compare_quantity_position_et-desktop'] = '';
		$element_options['compare_content_alignment'] = ' justify-content-inherit';
	}

    $element_options['not_compare_page'] = true;
    if ( $element_options['built_in_compare'] ) {
        $compare_page_id = get_theme_mod('xstore_compare_page', '');
        if ( ! empty( $compare_page_id ) && is_page( $compare_page_id ) || (isset($_GET['et-compare-page']) && is_account_page()) ) {
            $element_options['not_compare_page'] = false;
        }
    }

    // filters
    $element_options['etheme_mini_compare_content_type'] = apply_filters( 'etheme_mini_compare_content_type', $element_options['compare_content_type_et-desktop'] );

    $element_options['etheme_mini_compare_content'] = $element_options['etheme_mini_compare_content_type'] != 'none';
    $element_options['etheme_mini_compare_content'] = apply_filters( 'etheme_mini_compare_content', $element_options['etheme_mini_compare_content'] );

    $element_options['etheme_mini_compare_content_position'] = apply_filters( 'etheme_mini_compare_content_position', $element_options['compare_content_position_et-desktop'] );

    $element_options['compare_off_canvas'] = $element_options['etheme_mini_compare_content_type'] == 'off_canvas';
    $element_options['compare_off_canvas'] = apply_filters( 'compare_off_canvas', $element_options['compare_off_canvas'] );

	if ( $element_options['built_in_compare'] ) {
        if ( $element_options['built_in_compare_page_id'] ) {
            $element_options['compare_link'] = get_permalink($element_options['built_in_compare_page_id']);
        }
        else {
            $element_options['built_in_compare_ghost_page_id'] = absint(get_option( 'woocommerce_myaccount_page_id' ));
            if ( $element_options['built_in_compare_ghost_page_id'] )
                $element_options['compare_link'] = add_query_arg('et-compare-page', '', get_permalink($element_options['built_in_compare_ghost_page_id']));
            else
                $element_options['compare_link'] = home_url();
        }
    }
	
	$element_options['compare_content_alignment'] = apply_filters( 'compare_content_alignment', $element_options['compare_content_alignment'] );
	
	$element_options['class'] = ' flex flex-wrap full-width align-items-center';
	if ( !$element_options['built_in_compare'] )
	    $element_options['class'] .= ' yith-woocompare-open';
	$element_options['class'] .= ' ' . $element_options['compare_content_alignment'];
    $element_options['class'] .= ( $element_options['etheme_mini_compare_content'] ) ? ' et-toggle' : '';
	
	$element_options['label_class'] = ( ! $element_options['compare_label_et-mobile'] ) ? 'mob-hide' : '';
	$element_options['label_class'] .= ( ! $element_options['compare_label_et-desktop'] ) ? ' dt-hide' : '';

	ob_start();
	?>

    <a class="<?php echo $element_options['class']; ?>" <?php if ( $element_options['built_in_compare'] ) : ?>href="<?php echo $element_options['compare_link']; ?>"<?php endif; ?>>
            <span class="flex<?php echo ( ! $et_builder_globals['in_mobile_menu'] ) ? '-inline' : ''; ?> justify-content-center align-items-center flex-wrap">
                <?php if ( in_array( $element_options['compare_style'], array( 'type1', 'type2' ) ) ) : ?>
                    <span class="et_b-icon">
                        <?php if ( isset( $element_options['compare_icon'] ) && $element_options['compare_icon'] != '' ) {
	                        echo '<span class="et-svg">' . $element_options['compare_icon'] . '</span>';
                        } ?>
                        <?php if ( $element_options['compare_quantity_et-desktop'] ) {
                            if ( $element_options['built_in_compare'] )
                                $element_options['built_in_compare_instance']->header_compare_quantity();
                        } ?>
                    </span>
                <?php endif; // compare_position-before ?>
	
	            <?php if ( $element_options['compare_label'] ) : ?>
                    <span class="et-element-label inline-block <?php echo ( ! $et_builder_globals['in_mobile_menu'] ) ? $element_options['label_class'] : ''; ?>">
                        <?php echo $element_options['compare_label_text']; ?>
                    </span>
	            <?php endif; // end compare_label ?>
	
	            <?php if ( $element_options['compare_style'] === 'type3' ) : ?>
                    <span class="et_b-icon">
                        <?php if ( isset( $element_options['compare_icon'] ) && $element_options['compare_icon'] != '' ) {
	                        echo '<span class="et-svg">' . $element_options['compare_icon'] . '</span>';
                        } ?>
                        <?php if ( $element_options['compare_quantity_et-desktop'] ) {
                            if ( $element_options['built_in_compare'] )
                                $element_options['built_in_compare_instance']->header_compare_quantity();
                        } ?>
                    </span>
	            <?php endif; // compare_position-after ?>
            </span>
    </a>

    <?php if ( $element_options['built_in_compare'] ) :
        $element_options['built_in_compare_instance']->header_compare_quantity();
        if ( $element_options['etheme_mini_compare_content'] && $element_options['not_compare_page'] ) :

            $element_options['compare_options'] = array(
                'etheme_mini_compare_content_position' => $element_options['compare_content_position_et-desktop'],
                'compare_off_canvas'                   => $element_options['compare_off_canvas'],
                'compare_link'                         => $element_options['compare_link'],
                'compare_quantity'                     => $element_options['compare_quantity_et-desktop'],
                'compare_quantity_position'            => get_theme_mod( 'compare_quantity_position_et-desktop', 'right' ),
                'compare_icon'                         => $element_options['compare_icon'],
                'compare_icon_backup'                  => $element_options['compare_icons']['type1'],
                'built_in_compare' => $element_options['built_in_compare'],
                'built_in_compare_instance' => $element_options['built_in_compare_instance'],
            );

            echo et_compare_mini_content_callback( $element_options['compare_options'], $et_builder_globals['is_customize_preview'] );

        endif;
    endif;

	return ob_get_clean();
}

function et_compare_mini_content_callback(
    $compare_options = array(
        'etheme_mini_compare_content_position' => 'left',
        'compare_off_canvas'                   => false,
        'compare_link'                         => '',
        'compare_quantity'                     => '',
        'compare_quantity_position'            => 'top',
        'compare_icon'                         => '',
        'compare_icon_backup'                  => '',
        'built_in_compare' => false,
        'built_in_compare_instance' => '',
    ), $is_customize_preview = false
) {

    ob_start(); ?>

    <div class="et-mini-content">
        <?php if ( $compare_options['compare_off_canvas'] ) : ?>
            <span class="et-toggle pos-absolute et-close full-<?php echo $compare_options['etheme_mini_compare_content_position']; ?> top">
                <svg xmlns="http://www.w3.org/2000/svg" width="0.8em" height="0.8em" viewBox="0 0 24 24">
                    <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                </svg>
            </span>
        <?php endif; ?>
        <div class="et-content">
            <?php if ( $compare_options['compare_off_canvas'] ) : ?>
                <div class="et-mini-content-head">
                    <a href="<?php echo $compare_options['compare_link']; ?>"
                       class="compare-type2 flex justify-content-center flex-wrap <?php echo 'et-quantity-' . ( $compare_options['compare_quantity'] ) ? $compare_options['compare_quantity_position'] : 'top' ?>">
                        <?php if ( $compare_options['compare_icon'] == '' ) {
                            $compare_options['compare_icon'] = $compare_options['compare_icons']['type1'];
                        }
                        ?>
                        <span class="et_b-icon">
                                <?php
                                echo '<span class="et-svg">' . $compare_options['compare_icon'] . '</span>';
                                if ( $compare_options['built_in_compare'] )
                                    $compare_options['built_in_compare_instance']->header_compare_quantity();
                                ?>
                            </span>
                        <span class="et-element-label pos-relative inline-block">
                            <?php echo esc_html__( 'My compare', 'xstore-core' ); ?>
                        </span>
                    </a>
                </div>
            <?php endif;
            if ( $compare_options['built_in_compare'] )
                $compare_options['built_in_compare_instance']->header_mini_compare();
            ?>
        </div>
    </div>

    <?php
    return ob_get_clean();
}

/**
 *
 * @return  {html} header account content
 * @version 1.0.1
 * @since   1.5.4
 */
function header_account_callback() {
	
	global $et_account_icons, $et_builder_globals;
	
	$is_woocommerce                              = class_exists( 'WooCommerce' );
	$element_options                             = array();
	$element_options['account_style_et-desktop'] = get_theme_mod( 'account_style_et-desktop', 'type1' );
	$element_options['account_style_et-desktop'] = apply_filters( 'account_style', $element_options['account_style_et-desktop'] );
	$element_options['account_type_et-desktop']  = get_theme_mod( 'account_icon_et-desktop', 'type1' );
	$element_options['account_type_et-desktop']  = apply_filters( 'account_icon', $element_options['account_type_et-desktop'] );
	
	$element_options['icon_custom'] = get_theme_mod( 'account_icon_custom_svg_et-desktop', '' );
	$element_options['icon_custom'] = apply_filters( 'account_icon_custom', $element_options['icon_custom'] );
	$element_options['icon_custom'] = isset( $element_options['icon_custom']['id'] ) ? $element_options['icon_custom']['id'] : '';
	
	if ( ! get_theme_mod( 'bold_icons', 0 ) ) {
		$element_options['account_icons_et-desktop'] = $et_account_icons['light'];
	} else {
		$element_options['account_icons_et-desktop'] = $et_account_icons['bold'];
	}
	
	if ( $element_options['account_type_et-desktop'] == 'custom' ) {
		if ( $element_options['icon_custom'] != '' ) {
			$element_options['account_icons_et-desktop']['custom'] = etheme_get_svg_icon( $element_options['icon_custom'] );
		} else {
			$element_options['account_icons_et-desktop']['custom'] = $element_options['account_icons_et-desktop']['type1'];
		}
	}
	
	$element_options['account_icon_et-desktop'] = $element_options['account_icons_et-desktop'][ $element_options['account_type_et-desktop'] ];
	$element_options['account_icon_et-desktop'] = apply_filters( 'etheme_header_account_icon', $element_options['account_icon_et-desktop'] );
	
	$element_options['account_label_et-desktop'] = get_theme_mod( 'account_label_et-desktop', 1 );
	$element_options['account_label_et-mobile']  = get_theme_mod( 'account_label_et-mobile', 0 );
	$element_options['account_label']            = $element_options['account_label_et-desktop'] || $element_options['account_label_et-mobile'] || $et_builder_globals['in_mobile_menu'] || $et_builder_globals['is_customize_preview'];
	$element_options['account_label_text']       = '';
	if ( $element_options['account_label'] ) {
		if ( is_user_logged_in() ) {
			if ( get_theme_mod( 'account_label_username', '0' ) ) {
				$element_options['current_user_et-desktop'] = wp_get_current_user();
				$element_options['account_label_text']      = $element_options['current_user_et-desktop']->display_name;
			} elseif ( get_theme_mod( 'account_label_custom_et-desktop' ) != '' ) {
				$element_options['account_label_text'] = get_theme_mod( 'account_label_custom_et-desktop' );
			} else {
				$element_options['account_logged_in_text'] = get_theme_mod( 'account_logged_in_text', 'My account' );
				$element_options['account_label_text']     = ( $element_options['account_logged_in_text'] != '' ) ? $element_options['account_logged_in_text'] : esc_html__( 'My account', 'xstore-core' );
			}
		} else {
			$element_options['account_text']       = get_theme_mod( 'account_text', 'Sign in' );
			$element_options['account_label_text'] = ( $element_options['account_text'] != '' ) ? $element_options['account_text'] : esc_html__( 'Login / Sign in', 'xstore-core' );
		}
	}
	
	$element_options['account_content_type_et-desktop']     = get_theme_mod( 'account_content_type_et-desktop', 'dropdown' );
	$element_options['account_content_position_et-desktop'] = get_theme_mod( 'account_content_position_et-desktop', 'right' );
	
	$element_options['account_content_alignment'] = ' justify-content-' . get_theme_mod( 'account_content_alignment_et-desktop', 'start' );
	$element_options['account_content_alignment'] .= ' mob-justify-content-' . get_theme_mod( 'account_content_alignment_et-mobile', 'start' );
	
	$element_options['not_account'] = function_exists( 'is_account_page' ) && is_account_page() ? false : true;
    $element_options['not_account'] = apply_filters( 'etheme_account_content_shown_account_pages', $element_options['not_account'] );
	
	if ( $et_builder_globals['in_mobile_menu'] ) {
		$element_options['account_style_et-desktop']        = 'type1';
		$element_options['account_content_alignment']       = ' justify-content-inherit';
		$element_options['account_content_type_et-desktop'] = 'none';
	}
	
	$element_options['account_content_alignment'] = apply_filters( 'account_content_alignment', $element_options['account_content_alignment'] );
	
	// filters
	$element_options['etheme_mini_account_content_type'] = apply_filters( 'etheme_mini_account_content_type', $element_options['account_content_type_et-desktop'] );
	
	$element_options['etheme_mini_account_content'] = ( $element_options['etheme_mini_account_content_type'] != 'none' ) ? true : false;
	$element_options['etheme_mini_account_content'] = apply_filters( 'etheme_mini_account_content', $element_options['etheme_mini_account_content'] );
	
	$element_options['etheme_mini_account_content_position'] = apply_filters( 'etheme_mini_account_content_position', $element_options['account_content_position_et-desktop'] );
	
	$element_options['account_off_canvas'] = ( $element_options['etheme_mini_account_content_type'] == 'off_canvas' ) ? true : false;
	$element_options['account_off_canvas'] = apply_filters( 'account_off_canvas', $element_options['account_off_canvas'] );
	
	$element_options['class'] = ' flex full-width align-items-center';
	$element_options['class'] .= ' ' . $element_options['account_content_alignment'];
	$element_options['class'] .= ( $element_options['account_off_canvas'] && $element_options['etheme_mini_account_content'] && $element_options['not_account'] ) ? ' et-toggle' : '';
	if ( ! $et_builder_globals['in_mobile_menu'] && get_theme_mod( 'account_background_et-desktop', 'current' ) == 'current' ) {
		$element_options['class'] .= ' currentColor';
	}
	
	$element_options['label_class'] = ( ! $element_options['account_label_et-mobile'] ) ? 'mob-hide' : '';
	$element_options['label_class'] .= ( ! $element_options['account_label_et-desktop'] ) ? ' dt-hide' : '';
	
	$element_options['header_account_link'] = ( $is_woocommerce ) ? get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) : get_dashboard_url();
	$element_options['header_account_link'] = apply_filters( 'header_account_link', $element_options['header_account_link'] );
	
	ob_start(); ?>

    <a href="<?php echo esc_url( $element_options['header_account_link'] ); ?>"
       class="<?php echo $element_options['class']; ?>">
			<span class="flex<?php echo ( ! $et_builder_globals['in_mobile_menu'] ) ? '-inline' : ''; ?> justify-content-center align-items-center flex-wrap">

				<?php if ( in_array( $element_options['account_style_et-desktop'], array(
						'type1',
						'type2'
					) ) && $element_options['account_icon_et-desktop'] != '' ) : ?>
                    <span class="et_b-icon">
						<?php echo $element_options['account_icon_et-desktop']; ?>
					</span>
				<?php endif; // account_position-before ?>
				
				<?php if ( $element_options['account_label'] ) : ?>
                    <span class="et-element-label inline-block <?php echo ( ! $et_builder_globals['in_mobile_menu'] ) ? $element_options['label_class'] : ''; ?>">
						<?php echo $element_options['account_label_text']; ?>
					</span>
				<?php endif; ?>
				
				<?php if ( $element_options['account_style_et-desktop'] === 'type3' && $element_options['account_icon_et-desktop'] != '' ) : ?>
                    <span class="et_b-icon">
						<?php echo $element_options['account_icon_et-desktop']; ?>
					</span>
				<?php endif; // account_position-after ?>

			</span>
    </a>
	<?php if ( $element_options['etheme_mini_account_content'] && $element_options['not_account'] ) :
		et_b_account_link( true, $element_options['account_off_canvas'], $element_options );
	endif; ?>
	
	<?php $html = ob_get_clean();
	
	return $html;
}

/**
 *
 * @return  {html} header contacts content
 * @version 1.0.0
 * @since   1.5.4
 */
function header_contacts_callback() {
	
	global $et_icons, $et_social_icons, $et_builder_globals;
	
	$element_options                                = array();
	$element_options['contacts_package_et-desktop'] = get_theme_mod( 'contacts_package_et-desktop',
		array(
			array(
				'contact_title'       => esc_html__( 'Phone', 'xstore-core' ),
				'contact_subtitle'    => esc_html__( 'Call us any time', 'xstore-core' ),
				'contact_icon'        => 'et_icon-phone',
				'contact_link'        => '#',
				'contact_link_target' => '0'
			),
		)
	);
	
	$element_options['contacts_separator_et-desktop'] = get_theme_mod( 'contacts_separator_et-desktop', '0' );
	$element_options['separator']                     = get_theme_mod( 'contacts_separator_type_et-desktop', '2059' );
	
	if ( get_theme_mod( 'bold_icons', 0 ) ) {
		$element_options['icons'] = $et_icons['bold'];
	} else {
		$element_options['icons'] = $et_icons['light'];
	}
	$element_options['icons'] = array_merge( $et_social_icons['type1'], $element_options['icons'] );
	
	$element_options['contacts_direction_et-desktop'] = get_theme_mod( 'contacts_direction_et-desktop', 'hor' );
	
	$element_options['contacts_alignment_et-desktop']         = get_theme_mod( 'contacts_alignment_et-desktop', 'start' );
	$element_options['contacts_content_alignment_et-desktop'] = $element_options['contacts_alignment_et-desktop'];
	
	$element_options['contacts_icon_et-desktop'] = get_theme_mod( 'contacts_icon_et-desktop', 'left' );
	
	$element_options['contacts_inner_align'] = 'center';
	
	$element_options['wrapper_class'] = '';
	
	if ( $et_builder_globals['in_mobile_menu'] ) {
		$element_options['contacts_direction_et-desktop'] = 'ver';
		$element_options['contacts_alignment_et-desktop'] = $element_options['contacts_content_alignment_et-desktop'] = $element_options['contacts_inner_align'] = 'start';
		$element_options['contacts_icon_et-desktop']      = 'left';
	}
	
	$element_options['wrapper_class'] .= ( $element_options['contacts_direction_et-desktop'] == 'hor' ) ? '' : ' flex-col';
	
	$element_options['contacts_direction_et-desktop']         = ( $element_options['contacts_direction_et-desktop'] == 'hor' ) ? ' flex-inline' : ' flex';
	$element_options['contacts_content_alignment_et-desktop'] = ( $element_options['contacts_direction_et-desktop'] == 'hor' ) ? ' align-items-' . $element_options['contacts_content_alignment_et-desktop'] :
		' justify-content-' . $element_options['contacts_content_alignment_et-desktop'];
	
	$element_options['contacts_icon_position_et-desktop'] = ( $element_options['contacts_icon_et-desktop'] != 'none' ) ? $element_options['contacts_icon_et-desktop'] : '';
	$element_options['contacts_icon_position_et-desktop'] = apply_filters( 'contacts_icon_position', $element_options['contacts_icon_position_et-desktop'] );
	
	$element_options['contact_count'] = 0;
	
	$element_options['sep_align_type'] = ( $element_options['contacts_direction_et-desktop'] == 'hor' ) ? 'justify' : 'align';
	$element_options['contact_class']  = 'justify-content-' . $element_options['contacts_inner_align'];
	$element_options['contact_class']  .= ' flex-' . ( $element_options['contacts_icon_position_et-desktop'] != 'top' ? 'nowrap' : 'wrap' );
	
	$element_options['contact_info_class'] = '';
	
	ob_start();
	
	foreach ( $element_options['contacts_package_et-desktop'] as $key ) {
		if ( ! $key ) {
			return;
		}
		$element_options['contact_count'] ++; ?>
        <div class="contact contact-<?php echo str_replace( ' ', '_', $key['contact_title'] ); ?> icon-<?php echo $element_options['contacts_icon_position_et-desktop']; ?> <?php echo $element_options['contacts_direction_et-desktop']; ?> <?php echo $element_options['contacts_content_alignment_et-desktop']; ?>"
             data-tooltip="<?php echo $key['contact_title']; ?>" <?php
		if ( isset( $key['contact_link'] ) && $key['contact_link'] != '' ) {
			$element_options['contact_class']      .= ' pointer';
			$element_options['contact_info_class'] .= ' pointer';
			if ( ! $key['contact_link_target'] ) {
				echo 'onclick="window.location.href = \'' . $key['contact_link'] . '\'"';
			} else {
				echo 'onclick="window.open(\'' . $key['contact_link'] . '\')"';
			}
		} ?>
        >
			
			<?php if ( $element_options['contacts_icon_et-desktop'] != 'none' && $key['contact_icon'] != 'none' ) : ?>
            <span class="flex-inline <?php echo $element_options['contact_class']; ?>">
						<span class="contact-icon flex-inline justify-content-center align-items-center">
							<?php
							if ( $key['contact_icon'] != 'none' ) {
								if ( isset( $key['contact_icon'] ) && isset( $element_options['icons'][ $key['contact_icon'] ] ) ) {
									echo $element_options['icons'][ $key['contact_icon'] ];
								} else {
									echo $element_options['icons']['et_icon-chat'];
								}
							}
							?>
						</span>
						<?php endif; ?>
						<span class="contact-info <?php echo $element_options['contact_info_class']; ?>">
							<?php
							echo $key['contact_subtitle'];
							?>
						</span>
					</span>
        </div>
		<?php
		if ( $element_options['contacts_separator_et-desktop'] && $element_options['contact_count'] > 0 && $element_options['contact_count'] < count( $element_options['contacts_package_et-desktop'] ) ) {
			echo '<span class="et_b_header-contact-sep ' . $element_options['sep_align_type'] . '-self-center"></span>';
		}
		?>
		<?php
	}
	
	$html = ob_get_clean();
	
	if ( $element_options['contacts_separator_et-desktop'] ) {
		if ( get_query_var( 'et_is_customize_preview', false ) ) {
			ob_start(); ?>
            <style>
                .et_b_header-contacts.et_element-top-level .contact:not(:last-child) + .et_b_header-contact-sep:before {
                    content: <?php echo '"\\' . str_replace('\\', '', $element_options['separator']) . '"'; ?>;
                }
            </style>
			<?php $html .= ob_get_clean();
		} else {
			wp_add_inline_style( 'xstore-inline-css',
				'.et_b_header-contacts.et_element-top-level .contact:not(:last-child) + .et_b_header-contact-sep:before {
                            content: "\\' . str_replace( '\\', '', $element_options['separator'] ) . '";' .
				'}'
			);
		}
	}
	
	unset( $element_options );
	
	return $html;
}

/**
 *
 * @return  {html} header button content
 * @version 1.0.1
 *          last changes in 1.5.5
 * @since   1.5.4
 */
function header_button_callback() {
	$element_options                       = array();
	$element_options['button_text']        = get_theme_mod( 'button_text_et-desktop', 'Button' );
	$element_options['button_link']        = get_theme_mod( 'button_link_et-desktop' );
	$element_options['button_custom_link'] = get_theme_mod( 'button_custom_link_et-desktop', '#' );
	$element_options['button_link']        = ( $element_options['button_link'] == 'custom' ) ? $element_options['button_custom_link'] : get_permalink( $element_options['button_link'] );
	
	$element_options['is_customize_preview'] = apply_filters( 'is_customize_preview', false );
	$element_options['attributes']           = array();
	
	if ( $element_options['is_customize_preview'] ) {
		$element_options['attributes'] = array(
			'data-title="' . esc_html__( 'Button', 'xstore-core' ) . '"',
			'data-element="button"'
		);
	}
	
	if ( get_theme_mod( 'button_target_et-desktop', 0 ) ) {
		$element_options['attributes'][] = 'target="_blank"';
	}
	if ( get_theme_mod( 'button_no_follow_et-desktop', 0 ) ) {
		$element_options['attributes'][] = 'rel="nofollow"';
	}
	
	ob_start(); ?>
    <a
            class="et_element et_b_header-button inline-block pos-relative"
            href="<?php echo $element_options['button_link']; ?>"
		<?php echo implode( ' ', $element_options['attributes'] ); ?>>
		<?php esc_html_e( $element_options['button_text'], 'xstore-core' ); ?>
    </a>
	<?php
	return ob_get_clean();
}

/**
 *
 * @return  {html} header newsletter popup content
 * @version 1.0.0
 * @since   1.5.4
 */
function header_newsletter_content_callback() {
	
	$element_options                                              = array();
	$element_options['newsletter_title_et-desktop']               = get_theme_mod( 'newsletter_title_et-desktop', 'Title' );
	$element_options['newsletter_content_et-desktop']             = get_theme_mod( 'newsletter_content_et-desktop', '<p>You can add any HTML here (admin -&gt; Theme Options -&gt; Header builder -&gt; Newsletter).<br /> We suggest you create a static block and use it by turning on the settings below</p>' );
	$element_options['newsletter_section_et-desktop']             = ( get_theme_mod( 'newsletter_sections_et-desktop', 0 ) ) ? get_theme_mod( 'newsletter_section_et-desktop', '' ) : '';
	$element_options['newsletter_content_et-desktop']             = ( $element_options['newsletter_section_et-desktop'] != '' && $element_options['newsletter_section_et-desktop'] > 0 ) ? $element_options['newsletter_section_et-desktop'] : $element_options['newsletter_content_et-desktop'];
	$element_options['newsletter_close_button_action_et-desktop'] = get_theme_mod( 'newsletter_close_button_action_et-desktop', 1 );
	
	ob_start(); ?>

    <span class="et-close-popup et-toggle pos-fixed full-left top <?php echo ( $element_options['newsletter_close_button_action_et-desktop'] ) ? 'close-forever' : ''; ?>">
              <svg xmlns="http://www.w3.org/2000/svg" width=".8em" height=".8em" viewBox="0 0 24 24">
                <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
              </svg>
            </span>
	<?php
	if ( $element_options['newsletter_section_et-desktop'] != '' ) :
		
		$element_options['section_css'] = get_post_meta( $element_options['newsletter_section_et-desktop'], '_wpb_shortcodes_custom_css', true );
		if ( ! empty( $element_options['section_css'] ) ) {
			echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
			echo strip_tags( $element_options['section_css'] );
			echo '</style>';
		}
		
		etheme_static_block( $element_options['newsletter_section_et-desktop'], true );
	
	else :
		
		if ( $element_options['newsletter_title_et-desktop'] || get_query_var( 'et_is_customize_preview', false ) ) { ?>
            <h2><?php echo $element_options['newsletter_title_et-desktop']; ?></h2>
		<?php } ?>
		
		<?php if ( $element_options['newsletter_content_et-desktop'] || get_query_var( 'et_is_customize_preview', false ) ) { ?>
        <div class="et-content"><?php echo do_shortcode( $element_options['newsletter_content_et-desktop'] ); ?></div>
	<?php }
	
	endif; ?>
	
	<?php return ob_get_clean();
}

/**
 * @return  {html} age verify popup content
 * @version 1.0.0
 * @since   4.4.0
 */
function et_age_verify_popup_content_callback() {
	$element_options                                              = array();
	$element_options['dev_mode'] = get_theme_mod('age_verify_popup_switcher_dev_mode', 0);
	$element_options['age_verify_popup_title']               = get_theme_mod( 'age_verify_popup_title', esc_html__('Before you enter', 'xstore-core') );
	$element_options['age_verify_popup_content']             = get_theme_mod( 'age_verify_popup_content', '<p>'.esc_html__('Are you over 18 years old?', 'xstore-core').'<br/>'.
	                                                                                                                            esc_html__('This website requires you to be 18 years or older to enter our website and see the content.', 'xstore-core').'</p>' );
	$element_options['age_verify_popup_close_button_action_et-desktop'] = get_theme_mod( 'age_verify_popup_close_button_action_et-desktop', 1 );
	$element_options['agree_button_text'] = get_theme_mod('age_verify_popup_agree_button_text', esc_html__( 'Yes, I am', 'xstore-core' ));
	$element_options['deny_button_redirect'] = trim(get_theme_mod('age_verify_popup_deny_button_redirect', ''));
	$element_options['deny_button_text'] = get_theme_mod('age_verify_popup_deny_button_text', esc_html__( 'No, I am not', 'xstore-core' ));
	
	ob_start(); ?>
    
	<?php
  
		if ( $element_options['age_verify_popup_title'] || get_query_var( 'et_is_customize_preview', false ) ) { ?>
            <h2><?php echo $element_options['age_verify_popup_title']; ?></h2>
		<?php } ?>
		
		<?php if ( $element_options['age_verify_popup_content'] || get_query_var( 'et_is_customize_preview', false ) ) { ?>
        <div class="et-content">
            <?php echo do_shortcode( $element_options['age_verify_popup_content'] ); ?>
            <div class="button-wrapper">
	            <?php if ( $element_options['agree_button_text'] != '' ) : ?>
                    <a class="btn medium active et-close-popup <?php if ( !$element_options['dev_mode']): ?>close-forever et-age-verified<?php endif; ?>"><?php echo $element_options['agree_button_text']; ?></a>
	            <?php endif; ?>
	            <?php if ( $element_options['deny_button_text'] != '' ) : ?>
                    <a class="btn medium black<?php if ( !$element_options['deny_button_redirect']) : ?> et-switch-popup-content<?php endif; ?>" <?php if ( $element_options['deny_button_redirect'] != '') : ?>href="<?php echo $element_options['deny_button_redirect']; ?>"<?php endif; ?>>
                        <?php echo $element_options['deny_button_text']; ?>
                    </a>
	            <?php endif; ?>
            </div>
        </div>
	    <?php }
	    else { ?>
            <div class="et-content">
                <div class="button-wrapper">
                    <?php if ( $element_options['agree_button_text'] != '' ) : ?>
                        <a class="btn medium active et-close-popup <?php if ( !$element_options['dev_mode']): ?>close-forever et-age-verified<?php endif; ?>"><?php echo $element_options['agree_button_text']; ?></a>
                    <?php endif; ?>
	                <?php if ( $element_options['deny_button_text'] != '' ) : ?>
                        <a class="btn medium black<?php if ( !!$element_options['deny_button_redirect']) : ?> et-switch-popup-content<?php endif; ?>" <?php if ( $element_options['deny_button_redirect'] != '') : ?>href="<?php echo $element_options['deny_button_redirect']; ?>"<?php endif; ?>>
			                <?php echo $element_options['deny_button_text']; ?>
                        </a>
	                <?php endif; ?>
                </div>
            </div>
        <?php }
	
	return ob_get_clean();
}

function et_age_verify_popup_error_content_callback() {
	$element_options = array();
	$element_options['age_verify_popup_error_title']               = get_theme_mod( 'age_verify_popup_error_title', esc_html__('Access forbidden', 'xstore-core') );
	$element_options['age_verify_popup_error_content']             = get_theme_mod( 'age_verify_popup_error_content', '<p>'.esc_html__('Your access is restricted because of your age.', 'xstore-core').'</p>' );
	if ( $element_options['age_verify_popup_error_title'] || get_query_var( 'et_is_customize_preview', false ) ) { ?>
        <h2><?php echo $element_options['age_verify_popup_error_title']; ?></h2>
	<?php }
	if ( $element_options['age_verify_popup_error_content'] || get_query_var( 'et_is_customize_preview', false ) ) { ?>
        <div class="et-content">
			<?php echo do_shortcode( $element_options['age_verify_popup_error_content'] ); ?>
        </div>
	<?php }
}


/**
 *
 * @param   {array} - options
 *
 * @return  {html} html blocks content
 * @since   1.5.5
 * @uses    WPBMap class
 * @uses    addAllMappedShortcodes method
 * @version 1.0.0
 */
function html_blocks_callback( $options = array() ) {
	
	ob_start();
	
	if ( class_exists( 'WPBMap' ) && method_exists( 'WPBMap', 'addAllMappedShortcodes' ) ) {
		WPBMap::addAllMappedShortcodes();
	}

	if ( isset( $options['section_content'] ) ) {
		$content = get_theme_mod( $options['section'] );
		
		if ( ( isset( $options['force_sections'] ) || get_theme_mod( $options['sections'] ) ) && $content != '' && $content > 0 ) {
			
			$section_css = get_post_meta( $content, '_wpb_shortcodes_custom_css', true );
			if ( ! empty( $section_css ) ) {
				echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
				echo strip_tags( $section_css );
				echo '</style>';
			}
			
			etheme_static_block( $content, true );

		} else {
			echo do_shortcode( get_theme_mod( $options['html_backup'], (isset($options['html_backup_default']) ? $options['html_backup_default'] : '')  ) );
		}
	} else {
		if ( isset( $options['html_backup'] ) ) {
			$content = get_theme_mod( $options['html_backup'], (isset($options['html_backup_default']) ? $options['html_backup_default'] : '')  );
		} else {
			$content = esc_html__( 'Please, choose your staticblock', 'xstore-core' );
		}
		echo do_shortcode( $content );
	}
	
	$html = ob_get_clean();
	
	return $html;
}

// single product builder callbacks

/**
 *
 * @return  {html} single product button content
 * @version 1.0.1
 * @since   1.5.5
 */
function single_product_button_callback() {
	$element_options                       = array();
	$element_options['button_text']        = get_theme_mod( 'single_product_button_text_et-desktop', 'Button' );
	$element_options['button_link']        = get_theme_mod( 'single_product_button_link_et-desktop' );
	$element_options['button_custom_link'] = get_theme_mod( 'single_product_button_custom_link_et-desktop', '#' );
	$element_options['button_link']        = ( $element_options['button_link'] == 'custom' ) ? $element_options['button_custom_link'] : get_permalink( $element_options['button_link'] );
	
	$element_options['is_customize_preview'] = apply_filters( 'is_customize_preview', false );
	$element_options['attributes']           = array();
	
	if ( $element_options['is_customize_preview'] ) {
		$element_options['attributes'] = array(
			'data-title="' . esc_html__( 'Button', 'xstore-core' ) . '"',
			'data-element="single-button"'
		);
	}
	
	if ( get_theme_mod( 'single_product_button_target_et-desktop', 0 ) ) {
		$element_options['attributes'][] = 'target="_blank"';
	}
	if ( get_theme_mod( 'single_product_button_no_follow_et-desktop', 0 ) ) {
		$element_options['attributes'][] = 'rel="nofollow';
	}
	
	ob_start(); ?>
    <a
            class="et_element et_b_single-button inline-block pos-relative"
            href="<?php echo $element_options['button_link']; ?>"
		<?php echo implode( ' ', $element_options['attributes'] ); ?>>
		<?php esc_html_e( $element_options['button_text'], 'xstore-core' ); ?>
    </a>
	<?php
	$html = ob_get_clean();
	
	return $html;
}


/**
 * Description of the function.
 *
 * @return false|string
 * @since 3.2.5
 *
 */
function single_product_request_quote_callback() {
	global $et_request_quote_icons;
	$element_options                         = array();
	$element_options['button_text']          = get_theme_mod( 'request_quote_button_text_et-desktop', 'Ask an expert' );
	$element_options['icon_type_et-desktop'] = get_theme_mod( 'request_quote_icon_et-desktop', 'type1' );
	$element_options['icon_custom']          = get_theme_mod( 'request_quote_icon_custom_et-desktop', '' );
	
	if ( get_theme_mod( 'bold_icons', 0 ) ) {
		$element_options['icon'] = $et_request_quote_icons['bold']['type1'];
	} else {
		$element_options['icon'] = $et_request_quote_icons['light']['type1'];
	}
	
	if ( $element_options['icon_type_et-desktop'] == 'custom' && $element_options['icon_custom'] != '' ) {
		$element_options['icon_custom_type']      = get_post_mime_type( $element_options['icon_custom'] );
		$element_options['icon_custom_mime_type'] = explode( '/', $element_options['icon_custom_type'] );
		if ( $element_options['icon_custom_mime_type']['1'] == 'svg+xml' ) {
			$element_options['rendered_svg'] = get_post_meta( $element_options['icon_custom'], '_xstore_inline_svg', true );
			
			if ( ! empty( $element_options['rendered_svg'] ) ) {
				$element_options['icon'] = $element_options['rendered_svg'];
			} else {
				
				$element_options['attachment_file'] = get_attached_file( $element_options['icon_custom'] );
				
				if ( $element_options['attachment_file'] ) {
					
					$element_options['rendered_svg'] = file_get_contents( $element_options['attachment_file'] );
					
					if ( ! empty( $element_options['rendered_svg'] ) ) {
						update_post_meta( $element_options['icon_custom'], '_xstore_inline_svg', $element_options['rendered_svg'] );
					}
					
					$element_options['icon'] = $element_options['rendered_svg'];
					
				}
				
			}
		} elseif ( function_exists( 'etheme_get_image' ) ) {
			$element_options['icon'] = etheme_get_image( $element_options['icon_custom'], 'thumbnail' );
		}
	} elseif ( $element_options['icon_type_et-desktop'] != 'custom' ) {
		if ( get_theme_mod( 'bold_icons', 0 ) ) {
			$element_options['icon'] = $et_request_quote_icons['bold'][ $element_options['icon_type_et-desktop'] ];
		} else {
			$element_options['icon'] = $et_request_quote_icons['light'][ $element_options['icon_type_et-desktop'] ];
		}
	}
	
	
	$element_options['is_customize_preview'] = apply_filters( 'is_customize_preview', false );
	
	// popup content
	$element_options['content'] = get_theme_mod( 'request_quote_popup_content_et-desktop', 'You may add any content here from Customizer->WooCommerce->Single Product Builder->Request a quote' );
//	    $element_options['newsletter_content_alignment_et-desktop']   = ' align-' . get_theme_mod( 'request_quote_popup_content_alignment_et-desktop', 'start' );
//	    $element_options['newsletter_section_et-desktop']             = ( get_theme_mod( 'newsletter_sections_et-desktop', 0 ) ) ? get_theme_mod( 'newsletter_section_et-desktop', '' ) : '';
//	    $element_options['newsletter_content_et-desktop']             = ( $element_options['newsletter_section_et-desktop'] != '' && $element_options['newsletter_section_et-desktop'] > 0 ) ? $element_options['newsletter_section_et-desktop'] : $element_options['newsletter_content_et-desktop'];
	
	ob_start();
	$element_options['class'] = 'with-static-block';
//	    $element_options['class'] .= $element_options['newsletter_content_alignment_et-desktop'];
	$element_options['class'] .= get_theme_mod( 'request_quote_popup_content_width_height_et-desktop', 'auto' ) == 'custom' ? ' et-popup-content-custom-dimenstions' : '';
	
	$element_options['attributes'] = array();
	
	if ( $element_options['is_customize_preview'] ) {
		$element_options['attributes'] = array(
			'data-title="' . esc_html__( 'Request quote', 'xstore-core' ) . '"',
			'data-element="single-request-quote"'
		);
	}
	
	ob_start(); ?>
    <div class="et_b_single-request-quote-popup et-called-popup" data-type="single-product-builder-quote">
        <div class="et-popup">
            <div class="et-popup-content <?php esc_attr_e( $element_options['class'] ); ?>">
                    <span class="et-close-popup et-toggle pos-fixed full-left top"
                          style="margin-<?php echo is_rtl() ? 'right' : 'left'; ?>: 5px;">
                      <svg xmlns="http://www.w3.org/2000/svg" width=".8em" height=".8em" viewBox="0 0 24 24">
                        <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
                      </svg>
                    </span>
				<?php if ( $element_options['content'] != '' ) { ?>
                    <div class="et-content"><?php echo do_shortcode( $element_options['content'] ); ?></div>
				<?php } else { ?>
                    <h2><?php esc_html_e( 'You may add any content here from Customizer->WooCommerce->Single Product Builder->Request a quote', 'xstore-core' ); ?></h2>
                    <p>At sem a enim eu vulputate nullam convallis Iaculis vitae odio faucibus adipiscing urna.</p>
				<?php } ?>
            </div>
        </div>
    </div>
    <span class="et_element et_b_single-request-quote-button inline-block pos-relative et-call-popup pointer"
          data-type="single-product-builder-quote"
            <?php echo implode( ' ', $element_options['attributes'] ); ?>>
            <?php if ( $element_options['icon'] != '' ) {
	            echo '<span class="et_b-icon">' . $element_options['icon'] . '</span>';
            } ?>
            <?php if ( $element_options['button_text'] != '' ) {
	            echo '<span>' . esc_html( $element_options['button_text'] ) . '</span>';
            } ?>
        </span>
	<?php
	$html = ob_get_clean();
	
	return $html;
}

/**
 *
 * @return  {html} product size guide popup content
 * @version 1.0.1
 * @since   1.5.5
 */
function product_size_guide_content_callback( $id, $multiple = false ) {
	
	if ( $multiple && isset( $multiple['singleProduct'] ) ) {
		$multiples         = new Etheme_Customize_header_Builder();
		$multiple_products = $multiples->get_json_option( 'et_multiple_single_product' );
		$multiples->set_single_product_element_theme_mod( $multiple_products[ $multiple['singleProduct'] ]['options'] );
	}
	
	$element_options                                          = array();
	$element_options['product_size_guide_img_et-desktop']     = get_theme_mod( 'product_size_guide_img_et-desktop', 'https://xstore.8theme.com/wp-content/uploads/2018/08/Size-guide.jpg' );
	$element_options['product_size_guide_title_et-desktop']   = get_theme_mod( 'product_size_guide_title_et-desktop', 'Title' );
	$element_options['product_size_guide_section_et-desktop'] = ( get_theme_mod( 'product_size_guide_sections_et-desktop', 0 ) ) ? get_theme_mod( 'product_size_guide_section_et-desktop', '' ) : '';
	$element_options['product_size_guide_content_et-desktop'] = ( $element_options['product_size_guide_section_et-desktop'] != '' && $element_options['product_size_guide_section_et-desktop'] > 0 ) ? $element_options['product_size_guide_section_et-desktop'] : '<img src="' . $element_options['product_size_guide_img_et-desktop'] . '" alt="' . esc_html__( 'sizing guide', 'xstore-core' ) . '">';
	
	$element_options['product_size_guide_local_img'] = etheme_get_custom_field( 'size_guide_img', $id );
	if ( ! $element_options['product_size_guide_local_img'] ) {
		if ( function_exists( 'etheme_category_size_guide' ) ) {
			$element_options['category_size_guide'] = etheme_category_size_guide( $id );
			if ( $element_options['category_size_guide'] ) {
				$element_options['product_size_guide_local_img'] = $element_options['category_size_guide'];
			}
		}
	}
	
	ob_start(); ?>

    <span class="et-close-popup pos-fixed full-left top">
          <svg xmlns="http://www.w3.org/2000/svg" width=".8em" height=".8em" viewBox="0 0 24 24">
            <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
          </svg>
        </span>
	<?php
	if ( $element_options['product_size_guide_section_et-desktop'] != '' && ! $element_options['product_size_guide_local_img'] ) :
		
		$element_options['section_css'] = get_post_meta( $element_options['product_size_guide_section_et-desktop'], '_wpb_shortcodes_custom_css', true );
		if ( ! empty( $element_options['section_css'] ) ) {
			echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
			echo strip_tags( $element_options['section_css'] );
			echo '</style>';
		}
		
		etheme_static_block( $element_options['product_size_guide_section_et-desktop'], true );
	
	else :
		
		if ( $element_options['product_size_guide_local_img'] != '' ) {
			echo '<img src="' . $element_options['product_size_guide_local_img'] . '" alt="' . esc_html__( 'sizing guide', 'xstore-core' ) . '">';
		} else {
			
			if ( $element_options['product_size_guide_title_et-desktop'] || get_query_var( 'et_is_customize_preview', false ) ) { ?>
                <h2><?php echo $element_options['product_size_guide_title_et-desktop']; ?></h2>
			<?php } ?>
			
			<?php if ( $element_options['product_size_guide_content_et-desktop'] || get_query_var( 'et_is_customize_preview', false ) ) { ?>
                <div class="et-content"><?php echo do_shortcode( $element_options['product_size_guide_content_et-desktop'] ); ?></div>
			<?php }
		}
	
	endif; ?>
	
	<?php $html = ob_get_clean();
	
	return $html;
}

/**
 *
 * @return  {html} product sharing content
 * @version 1.0.1
 * @since   1.5.5
 */
function product_sharing_callback( $post_id ) {
	global $et_social_icons;
	
	$element_options = array();
	
	$element_options['product_sharing_type_et-desktop'] = get_theme_mod( 'product_sharing_type_et-desktop', 'type1' );
	
	$element_options['permalink'] = get_permalink( $post_id );
	
	$element_options['image'] = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'small' );
	$element_options['image'] = ( isset( $element_options['image'] ) && isset( $element_options['image'][0] ) ) ? $element_options['image'][0] : '';
	$element_options['title'] = rawurlencode( get_the_title( $post_id ) );
	
	$element_options['product_socials_label']      = get_theme_mod( 'product_socials_label_et-desktop', 1 );
	$element_options['product_socials_label_text'] = get_theme_mod( 'product_socials_label_text_et-desktop', esc_html__( 'Share:', 'xstore-core' ) );
	
	$element_options['product_sharing_package_et-desktop'] = get_theme_mod( 'product_sharing_package_et-desktop',
		array(
			'facebook',
			'twitter',
			'tumblr',
			'linkedin',
		)
	);
	
	$element_options['product_sharing_links'] = array(
		'facebook'  => array(
			'href'  => 'https://www.facebook.com/sharer.php?u=' . $element_options['permalink'] . '&title=' . $element_options['title'],
			'title' => esc_attr__( 'Facebook', 'xstore-core' ),
		),
		'twitter'   => array(
			'href'  => 'https://twitter.com/share?url=' . $element_options['permalink'] . '&text=' . $element_options['title'],
			'title' => esc_attr__( 'Twitter', 'xstore-core' ),
		),
		'linkedin'  => array(
			'href'  => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $element_options['permalink'],
			'title' => esc_attr__( 'Linkedin', 'xstore-core' ),
		),
		'houzz'     => array(
			'href'  => 'http://www.houzz.com/imageClipperUpload?imageUrl=' . $element_options['image'] . '&title=' . $element_options['title'] . '&link=' . $element_options['permalink'],
			'title' => esc_attr__( 'Houzz', 'xstore-core' ),
		),
		'pinterest' => array(
			'href'  => 'http://pinterest.com/pin/create/button/?url=' . $element_options['permalink'] . '&media=' . $element_options['image'] . '&description=' . $element_options['title'],
			'title' => esc_attr__( 'Pinterest', 'xstore-core' ),
		),
		'tumblr'    => array(
			'href'  => 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=' . $element_options['permalink'],
			'title' => esc_attr__( 'Tumblr', 'xstore-core' ),
		),
		'vk'        => array(
			'href'  => 'http://vk.com/share.php?url=' . $element_options['permalink'],
			'title' => esc_attr__( 'Vk', 'xstore-core' ),
		),
		'whatsapp'  => array(
			'href'  => 'https://api.whatsapp.com/send?text=' . $element_options['title'] . ' - ' . $element_options['permalink'],
			'title' => esc_attr__( 'Whatsapp', 'xstore-core' ),
		),
	);
	
	$element_options['attributes'] = array();
	
	if ( get_theme_mod( 'product_sharing_target_et-desktop', 0 ) ) {
		$element_options['attributes'][] = 'target="_blank"';
	}
	if ( get_theme_mod( 'product_sharing_no_follow_et-desktop', 0 ) ) {
		$element_options['attributes'][] = 'rel="nofollow"';
	}
	
	ob_start();
	
	if ( $element_options['product_socials_label'] ) {
		echo '<span class="socials-title">' . $element_options['product_socials_label_text'] . '</span>';
	} ?>
	<?php foreach ( (array) $element_options['product_sharing_package_et-desktop'] as $key => $value ) {
		
		$element_options['attributes'][] = 'data-tooltip="' . $element_options['product_sharing_links'][ $value ]['title'] . '"';
		
		$element_options['attributes_backup'] = $element_options['attributes'];
		
		?>

        <a href="<?php echo $element_options['product_sharing_links'][ $value ]['href']; ?>" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
            <span class="screen-reader-text hidden"><?php echo esc_html( $element_options['product_sharing_links'][ $value ]['title'] ); ?></span>
			<?php
			echo $et_social_icons[ $element_options['product_sharing_type_et-desktop'] ][ 'et_icon-' . $value ];
			?>
        </a>
		
		<?php $element_options['attributes'] = $element_options['attributes_backup'];
		array_pop( $element_options['attributes'] ); // for tooltip attr ?>
	<?php }
	
	$html = ob_get_clean();
	
	return $html;
}

/**
 * Return single product builder content html.
 *
 * @return  {html} html of single product builder content
 * @version 1.0.0
 * @since   1.5.5
 */
function single_product_bulder_content_callback() {
	
	$is_customize_preview = get_query_var( 'et_is_customize_preview', false );
	
	if ( $is_customize_preview ) {
		add_filter( 'is_customize_preview', 'etheme_return_true' );
	}
	
	$element_options = array();
	$sidebar_element = ( get_theme_mod( 'single_product_sidebar_mode_et-desktop', 'element' ) != 'default' );
	
	$product_single_elements = '{"element-oCMF7":{"title":"Section1","width":"100","index":1,"align":"start","sticky":"false","data":{"element-lpYyv":{"element":"etheme_woocommerce_template_woocommerce_breadcrumb","index":0}}},"element-raHwF":{"title":"Section2","width":"30","index":2,"align":"start","sticky":"false","data":{"sA6vX":{"element":"etheme_woocommerce_show_product_images","index":0}}},"element-TFML4":{"title":"Section3","width":"35","index":3,"align":"start","sticky":"false","data":{"su2ri":{"element":"etheme_woocommerce_template_single_title","index":0},"pcrn2":{"element":"etheme_woocommerce_template_single_price","index":1},"ZhZAb":{"element":"etheme_woocommerce_template_single_rating","index":2},"DBsjn":{"element":"etheme_woocommerce_template_single_excerpt","index":3},"oXjuP":{"element":"etheme_woocommerce_template_single_add_to_cart","index":4},"element-Zwwrj":{"element":"etheme_product_single_wishlist","index":5},"4XneW":{"element":"etheme_woocommerce_template_single_meta","index":6},"WP7Ne":{"element":"etheme_woocommerce_template_single_sharing","index":7}}},"element-fgcNP":{"title":"Section4","width":"25","index":4,"align":"start","sticky":"element-TFML4","data":{"HK48p":{"element":"etheme_product_single_widget_area_01","index":0}}},"element-nnrkj":{"title":"Section5","width":"100","index":5,"align":"start","sticky":"false","data":{"BJZsk":{"element":"etheme_woocommerce_output_product_data_tabs","index":0}}},"element-aKxrL":{"title":"Section6","width":"100","index":6,"align":"start","sticky":"false","data":{"qyJz2":{"element":"etheme_woocommerce_output_related_products","index":0}}},"element-a8Rd9":{"title":"Section7","width":"100","index":7,"align":"start","sticky":"false","data":{"sbu5J":{"element":"etheme_woocommerce_output_upsell_products","index":0}}}}';
	
	$data = json_decode( get_theme_mod( 'product_single_elements', $product_single_elements ), true );
	
	if ( ! is_array( $data ) ) {
		$data = array();
	}
	
	uasort( $data, function ( $item1, $item2 ) {
		return $item1['index'] <=> $item2['index'];
	} );
	
	add_filter( 'connect_block_package', function () {
		return 'connect_block_product_single_package';
	} );
	
	ob_start();
	
	foreach ( $data as $key => $value ) {
		if ( ! isset( $data[ $value['sticky'] ] ) ) {
			$value['sticky'] = "false";
		}
		
		$css = 'width:' . $value['width'] . '%;';
		if ( isset( $value['style'] ) && ! is_array( $value['style'] ) ) {
			$style = json_decode( $value['style'], true );
			
			if ( is_array( $style ) && count( $style ) ) {
				foreach ( $style as $k => $v ) {
					if ( $v ) {
					    switch ($k) {
                            case 'background-image':
	                            $v = 'url(' . wp_get_attachment_image_url( $v, 'full' ) . ')';
                                break;
                            case 'border-radius':
	                            $v .= 'px';
                                break;
                            case 'border-color':
	                            $v .= '!important';
                                break;
                        }
						$css .= $k . ':' . $v . ';';
					}
				}
				
				if ( isset( $style['color'] ) && ! empty( $style['color'] ) ) {
					$style_css = '
				        	.et_column.' . $key . ' {color:' . $style['color'] . ';}
				        	.et_column.' . $key . ' .quantity .quantity-wrapper.type-circle input{
				        		color:' . $style['color'] . ';
				        	}
				        	.et_column.' . $key . ' .quantity-wrapper span {color:' . $style['color'] . ';}
				        	.et_column.' . $key . ' .quantity-wrapper.type-circle span {
				        		border-color:' . $style['color'] . ';
				        	}
				        	.et_column.' . $key . ' a {color:' . $style['color'] . ';}
				        	.et_column.' . $key . ' .single-tags, .product_meta, .product-share, .wcpv-sold-by-single {color:' . $style['color'] . ';}
				        	.et_column.' . $key . ' .single-product-size-guide {color:' . $style['color'] . ';}
				        	.et_column.' . $key . ' .product_title {color:' . $style['color'] . ';}
				        	.et_column.' . $key . '.et_product-block > .price ins .amount{color:' . $style['color'] . ';}
				        ';
					
					if ( ! $is_customize_preview ) {
						wp_add_inline_style( 'xstore-inline-css', $style_css );
					} else {
						echo '<style>' . $style_css . '</style>';
					}
				}
			}
		}
		
		$is_gallery = false;
		if ( $value['data'] ) {
			foreach ( $value['data'] as $id => $element ) {
				if ( $element['element'] == 'etheme_woocommerce_show_product_images' ) {
					$is_gallery = true;
				}
			}
		}
		?>
        <div
                class="<?php echo $key; ?> et_column et_product-block mob-full-width mob-full-width-children<?php echo ( $is_gallery ) ? ' etheme-woocommerce-product-gallery' : ''; ?><?php echo ( $is_gallery && get_theme_mod( 'product_gallery_type_et-desktop', 'thumbnails_bottom' ) == 'full_width' ) ? ' stretch-swiper-slider' : ''; ?> justify-content-<?php echo $value['align'] ?>"
                style="<?php echo $css; ?>"
			<?php if ( $value['sticky'] != 'false' ) {
				echo "data-sticky='" . $value['sticky'] . "'";
			} ?>
                data-key="<?php echo $key; ?>"
                data-width="<?php echo $value['width']; ?>"
			<?php echo ( $value['sticky'] ) ? ' data-start="0"' : ''; ?>
        >
			<?php if ( $value['data'] ) {
				uasort( $value['data'], function ( $item1, $item2 ) {
					return $item1['index'] <=> $item2['index'];
				} );
				
				foreach ( $value['data'] as $id => $element ) {
					
					if ( $element['element'] == 'connect_block' ) {
						$blockID = $id;
						add_filter( 'et_connect_block_id', function ( $id ) use ( $blockID ) {
							return $blockID;
						} );
					}
					
					if ( $element['element'] == 'etheme_woocommerce_template_woocommerce_breadcrumb' && get_theme_mod( 'product_breadcrumbs_mode_et-desktop', 'element' ) != 'element' || $element['element'] == 'etheme_product_single_widget_area_1' && ! $sidebar_element ) {
						continue;
					}
					
					$to_close = false;
					if ( in_array( $element['element'], array(
						'etheme_woocommerce_output_upsell_products',
						'etheme_woocommerce_output_cross_sells_products',
						'etheme_woocommerce_output_related_products'
					) ) ) {
						$to_close = true;
						switch ( $element['element'] ) {
							case 'etheme_woocommerce_output_upsell_products':
								echo '<div class="upsell-products-wrapper products-hover-only-icons">';
								break;
							case 'etheme_woocommerce_output_cross_sells_products':
								echo '<div class="cross-sell-products-wrapper products-hover-only-icons">';
								break;
							default:
								echo '<div class="related-products-wrapper products-hover-only-icons">';
								break;
						}
					}
					do_action( $element['element'] );
					if ( $to_close ) {
						echo '</div>';
					}
				}
			} ?>
        </div>
	<?php }
	
	$html = ob_get_clean();
	
	return $html;
}

function etheme_mobile_panel_callback() {
	global $et_icons, $et_social_icons, $et_builder_globals;
	
	$et_builder_globals['in_mobile_panel'] = true;
	$mob_panel_element_options             = array();
	
	$mob_panel_element_options['current_link'] = get_permalink( get_the_ID() );
	add_filter( 'menu_dropdown_ajax', '__return_false' );
	$mobile_panel_package_et_mobile =
		array(
			array(
				'element'     => 'home',
				'icon'        => 'et_icon-home',
				'icon_custom' => '',
				'link'        => 0,
				'custom_link' => '',
				'text'        => '',
				'is_active'   => false
			),
			array(
				'element'     => 'shop',
				'icon'        => 'et_icon-shop',
				'icon_custom' => '',
				'link'        => 0,
				'custom_link' => '',
				'text'        => '',
				'is_active'   => false
			),
			array(
				'element'     => 'cart',
				'icon'        => 'et_icon-shopping-bag',
				'icon_custom' => '',
				'link'        => 0,
				'custom_link' => '',
				'text'        => '',
				'is_active'   => false
			),
		);
	
	$mob_panel_element_options['mobile_panel_package']         = get_theme_mod( 'mobile_panel_package_et-mobile', $mobile_panel_package_et_mobile );
	$mob_panel_element_options['is_woocommerce']               = class_exists( 'WooCommerce' );
	$mob_panel_element_options['is_YITH_WCWL']                 = class_exists( 'YITH_WCWL' );
	$mob_panel_element_options['mobile_panel_elements_labels'] = get_theme_mod( 'mobile_panel_elements_labels_et-mobile', 1 );
	$mob_panel_element_options['mobile_panel_elements_texts']  = get_theme_mod( 'mobile_panel_elements_texts_et-mobile', 1 );
	$mob_panel_element_options['count_of_package']             = count( $mob_panel_element_options['mobile_panel_package'] );
	
	if ( get_theme_mod( 'bold_icons', 0 ) ) {
		$mob_panel_element_options['icons'] = array_merge( $et_icons['bold'], $et_social_icons['type1'] );
	} else {
		$mob_panel_element_options['icons'] = array_merge( $et_icons['light'], $et_social_icons['type1'] );
	}

	$home_url = home_url();
	
	$mob_panel_element_options['elements_settings'] = array(
		'links'  => array(
			'shop'     => $mob_panel_element_options['is_woocommerce'] ? get_permalink( wc_get_page_id( 'shop' ) ) : $home_url,
			'home'     => $home_url,
			'cart'     => $mob_panel_element_options['is_woocommerce'] ? wc_get_cart_url() : $home_url,
			'account'  => $mob_panel_element_options['is_woocommerce'] ? get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) : get_dashboard_url(),
			'wishlist' => $mob_panel_element_options['is_YITH_WCWL'] ? YITH_WCWL()->get_wishlist_url() : $home_url,
            'compare'  => $home_url
		),
		'labels' => array(
			'shop'           => esc_html__( 'Shop', 'xstore-core' ),
			'cart'           => esc_html__( 'Cart', 'xstore-core' ),
			'home'           => esc_html__( 'Home', 'xstore-core' ),
			'account'        => get_query_var( 'et_is-loggedin', false) ? esc_html__( 'My account', 'xstore-core' ) : esc_html__( 'Sign in', 'xstore-core' ),
			'wishlist'       => esc_html__( 'Wishlist', 'xstore-core' ),
            'compare'        => esc_html__( 'Compare', 'xstore-core' ),
			'search'         => esc_html__( 'Search', 'xstore-core' ),
			'mobile_menu'    => esc_html__( 'Mobile menu', 'xstore-core' ),
			'more_toggle'    => esc_html__( 'More', 'xstore-core' ),
			'more_toggle_02' => esc_html__( 'More', 'xstore-core' ),
			'custom'         => esc_html__( 'Custom', 'xstore-core' ),
		),
	);

    $mob_panel_element_options['built_in_wishlist'] = get_theme_mod('xstore_wishlist', false);
    $mob_panel_element_options['built_in_wishlist_page_id'] = $mob_panel_element_options['built_in_wishlist'] ? get_theme_mod('xstore_wishlist_page', '') : '';
    $mob_panel_element_options['built_in_wishlist_instance'] = $mob_panel_element_options['built_in_wishlist'] ? XStoreCore\Modules\WooCommerce\XStore_Wishlist::get_instance() : '';

    if ( $mob_panel_element_options['built_in_wishlist'] ) {
        if ( $mob_panel_element_options['built_in_wishlist_page_id'] ) {
            $mob_panel_element_options['elements_settings']['links']['wishlist'] = get_permalink($mob_panel_element_options['built_in_wishlist_page_id']);
        }
        else {
            $mob_panel_element_options['built_in_wishlist_ghost_page_id'] = absint(get_option( 'woocommerce_myaccount_page_id' ));
            if ( $mob_panel_element_options['built_in_wishlist_ghost_page_id'] )
                $mob_panel_element_options['elements_settings']['links']['wishlist'] = add_query_arg('et-wishlist-page', '', get_permalink($mob_panel_element_options['built_in_wishlist_ghost_page_id']));
            else
                $mob_panel_element_options['elements_settings']['links']['wishlist'] = home_url();
        }
    }

    $mob_panel_element_options['built_in_compare'] = get_theme_mod('xstore_compare', false);
    $mob_panel_element_options['built_in_compare_page_id'] = $mob_panel_element_options['built_in_compare'] ? get_theme_mod('xstore_compare_page', '') : '';
    $mob_panel_element_options['built_in_compare_instance'] = $mob_panel_element_options['built_in_compare'] ? XStoreCore\Modules\WooCommerce\XStore_compare::get_instance() : '';

    if ( $mob_panel_element_options['built_in_compare'] ) {
        if ( $mob_panel_element_options['built_in_compare_page_id'] ) {
            $mob_panel_element_options['elements_settings']['links']['compare'] = get_permalink($mob_panel_element_options['built_in_compare_page_id']);
        }
        else {
            $mob_panel_element_options['built_in_compare_ghost_page_id'] = absint(get_option( 'woocommerce_myaccount_page_id' ));
            if ( $mob_panel_element_options['built_in_compare_ghost_page_id'] )
                $mob_panel_element_options['elements_settings']['links']['compare'] = add_query_arg('et-compare-page', '', get_permalink($mob_panel_element_options['built_in_compare_ghost_page_id']));
            else
                $mob_panel_element_options['elements_settings']['links']['compare'] = home_url();
        }
    }
	
	add_filter( 'menu_item_design', 'etheme_menu_item_design_dropdown', 15 );
	
	$mob_panel_element_options['_i'] = 0;
	
	foreach ( $mob_panel_element_options['mobile_panel_package'] as $key ) {
		
		$mob_panel_element_options['label'] = $mob_panel_element_options['mini_content'] = $mob_panel_element_options['class'] = $mob_panel_element_options['link_class'] = '';
		
		$link = '';
		if ( ! empty( $key['custom_link'] ) ) {
			$link = $key['custom_link'];
		} elseif ( $key['link'] > 0 ) {
			$link = get_permalink( $key['link'] );
		} elseif ( isset( $mob_panel_element_options['elements_settings']['links'][ $key['element'] ] ) ) {
			$link = $mob_panel_element_options['elements_settings']['links'][ $key['element'] ];
		}
		
		$mob_panel_element_options['class']              = '';
		
		switch ( $key['element'] ) {
			case 'cart':
				ob_start();
				etheme_cart_quantity();
				$mob_panel_element_options['label'] = ob_get_clean();
				if ( $mob_panel_element_options['is_woocommerce'] ) {
//                    if ( is_cart() ) {
					if ( get_query_var( 'et_is-cart', false ) ) {
						$mob_panel_element_options['class'] .= 'active';
					}
				}
				break;
			case 'wishlist':
				ob_start();
				if ( $mob_panel_element_options['built_in_wishlist'] )
                    $mob_panel_element_options['built_in_wishlist_instance']->header_wishlist_quantity();
                else
				    etheme_wishlist_quantity();
				$mob_panel_element_options['label'] = ob_get_clean();

				if ( $mob_panel_element_options['built_in_wishlist'] ) {
                    $wishlist_page_id = $mob_panel_element_options['built_in_wishlist_page_id'];
                    if ( ! empty( $wishlist_page_id ) && is_page( $wishlist_page_id ) || (isset($_GET['et-wishlist-page']) && is_account_page()) )
                        $mob_panel_element_options['class'] .= 'active';
                }
				elseif ( $mob_panel_element_options['is_YITH_WCWL'] && function_exists( 'yith_wcwl_is_wishlist_page' ) && yith_wcwl_is_wishlist_page() ) {
					$mob_panel_element_options['class'] .= 'active';
				}
				break;

            case 'compare':
                ob_start();

                if ( $mob_panel_element_options['built_in_compare'] )
                    $mob_panel_element_options['built_in_compare_instance']->header_compare_quantity();

                $mob_panel_element_options['label'] = ob_get_clean();

                if ( $mob_panel_element_options['built_in_compare'] ) {
                    $compare_page_id = $mob_panel_element_options['built_in_compare_page_id'];
                    if ( ! empty( $compare_page_id ) && is_page( $compare_page_id ) || (isset($_GET['et-compare-page']) && is_account_page()) )
                        $mob_panel_element_options['class'] .= 'active';
                }
                elseif ( defined('YITH_WOOCOMPARE') && class_exists('YITH_Woocompare_Frontend') ) {
                    $mob_panel_element_options['class'] .= 'yith-woocompare-open';
                }
                break;
			
			case 'shop':
				if ( $mob_panel_element_options['is_woocommerce'] ) {
					if ( is_shop() ) {
						$mob_panel_element_options['class'] .= ' active';
					}
				}
				break;
			
			case 'home':
				if ( is_home() || is_front_page() ) {
					$mob_panel_element_options['class'] .= 'active';
				}
				break;
			case 'search':
				$mob_panel_element_options['content_pos'] = $mob_panel_element_options['_i'] == 0 ? 'left' : 'right';
				$mob_panel_element_options['class']      = 'et-content-' . $mob_panel_element_options['content_pos'] . ' et-content_toggle static pos-static';
				$mob_panel_element_options['link_class'] = 'et-toggle';
				$etheme_search_filters                   = array(
					'etheme_mini_content'      => 'etheme_return_false',
					'etheme_search_results'    => 'etheme_return_true',
					'search_category'          => 'etheme_return_false',
					'etheme_use_desktop_style' => 'etheme_return_true',
					'search_type'              => 'etheme_mobile_type_input',
					'search_by_icon'           => 'etheme_return_false',
					'search_ajax_with_tabs'    => 'etheme_return_false',
					'search_mode_is_popup'     => 'etheme_return_false'
				);
				foreach ( $etheme_search_filters as $filter_key => $value ) {
					add_filter( $filter_key, $value, 15 );
				}
				ob_start();
				?>
                <div class="et-mini-content et-mini-content-from-bottom full-bottom">
                    <div class="et-content">
						<?php
						require( ET_CORE_DIR . 'app/models/customizer/templates/header/parts/search.php' );
						?>
                    </div>
                </div>
				<?php
				$mob_panel_element_options['mini_content'] = ob_get_clean();
				foreach ( $etheme_search_filters as $filter_key => $value ) {
					remove_filter( $filter_key, $value, 15 );
				}
				break;
			case 'more_toggle':
			case 'more_toggle_02':
				$mob_panel_element_options['content_pos'] = $mob_panel_element_options['_i'] == 0 ? 'left' : 'right';
				$mob_panel_element_options['class']      = 'et-content-' . $mob_panel_element_options['content_pos'] . ' et-content_toggle static pos-static';
				$mob_panel_element_options['link_class'] = 'et-toggle';
				
				$mob_panel_element_options['content_type'] = get_theme_mod( 'mobile_panel_' . $key['element'] . '_content', 'menu' );
				
				if ( $mob_panel_element_options['content_type'] == 'menu' ) {
					
					$mob_panel_element_options['main_menu_term']      = get_theme_mod( 'mobile_panel_' . $key['element'] . '_menu_term' );
					$mob_panel_element_options['main_menu_term_name'] = $mob_panel_element_options['main_menu_term'] == '' ? 'main-menu' : $mob_panel_element_options['main_menu_term'];
					
					$args = array(
						'menu'            => $mob_panel_element_options['main_menu_term_name'],
						'before'          => '',
						'container_class' => 'menu-main-container',
						'after'           => '',
						'link_before'     => '',
						'link_after'      => '',
						'depth'           => 100,
						'echo'            => false,
						'fallback_cb'     => false,
						'walker'          => new ETheme_Navigation
					);
					
				}
				
				ob_start(); ?>

                <div class="et-mini-content et-mini-content-from-bottom full-bottom">
                    <div class="et-content">

			            	<span class="et-mini-content-head flex justify-content-center flex-wrap">

								<span class="et_b-icon">
                                    <?php

                                    if ( isset( $key['icon_custom'] ) && $key['icon_custom'] != '' ) {
	                                    $type      = get_post_mime_type( $key['icon_custom'] );
	                                    $mime_type = explode( '/', $type );
	                                    if ( $mime_type['1'] == 'svg+xml' ) {
		                                    $svg = get_post_meta( $key['icon_custom'], '_xstore_inline_svg', true );
		
		                                    if ( ! empty( $svg ) ) {
			                                    echo '<span class="et-svg">' . $svg . '</span>';
		                                    } else {
			
			                                    $attachment_file = get_attached_file( $key['icon_custom'] );
			
			                                    if ( $attachment_file ) {
				
				                                    $svg = file_get_contents( $attachment_file );
				
				                                    if ( ! empty( $svg ) ) {
					                                    update_post_meta( $key['icon_custom'], '_xstore_inline_svg', $svg );
				                                    }
				
				                                    echo '<span class="et-svg">' . $svg . '</span>';
				
			                                    }
			
		                                    }
	                                    } else {
		                                    echo '<span class="et-svg">' . etheme_get_image( $key['icon_custom'], 'thumbnail' ) . '</span>';
	                                    }
                                    } else {
	                                    echo '<span class="et-svg">' . ( ( $mob_panel_element_options['icons'][ $key['icon'] ] == '' ) ? $mob_panel_element_options['icons']['et_icon-more'] : $mob_panel_element_options['icons'][ $key['icon'] ] ) . '</span>';
                                    }

                                    ?>
			                    </span>
			                    <span class="et-element-label pos-relative inline-block">
			                        <?php echo ! empty( $key['text'] ) ? do_shortcode( $key['text'] ) : $mob_panel_element_options['elements_settings']['labels'][ $key['element'] ]; ?>
			                    </span>
							</span>
						
						<?php
						
						if ( $mob_panel_element_options['content_type'] == 'menu' ) {
							$mob_panel_element_options['mob_panel_menu_getter'] = wp_nav_menu( $args );
							if ( $mob_panel_element_options['mob_panel_menu_getter'] != '' ) {
								echo $mob_panel_element_options['mob_panel_menu_getter'];
							}
						} else {
							echo html_blocks_callback( array(
								'section'         => 'mobile_panel_' . $key['element'] . '_section',
								'force_sections'  => true,
								'section_content' => true
							) );
						} ?>

                    </div>
                </div>
				
				<?php
				
				$mob_panel_element_options['mini_content'] = ob_get_clean();
				
				break;
			
			default:
				if ( $link != '' ) {
					$local_link = $mob_panel_element_options['current_link'];
					
					if ( is_tax() ) {
						global $wp_query;
						$obj        = $wp_query->get_queried_object();
						$local_link = get_term_link( $obj );
					}
					
					if ( strpos( $local_link, substr( $link, 0, - 2 ) ) !== false ) {
						$mob_panel_element_options['class'] .= 'active';
					}
					
				}
				break;
		}
		
		$mob_panel_element_options['class']              .= ' et_b_mobile-panel-' . $key['element'];
		$mob_panel_element_options['class']              .= ( isset( $key['is_active'] ) && $key['is_active'] ) ? ' with-dot' : '';
		
		if ( get_theme_mod( 'mobile_panel_active_colors_et-mobile', 'current' ) == 'current' && get_query_var( 'et_is_customize_preview', false ) ) {
			$mob_panel_element_options['class'] = str_replace( 'active', '', $mob_panel_element_options['class'] );
		}
		
		?>
        <div class="et_column flex align-items-center justify-content-center <?php echo esc_attr( $mob_panel_element_options['class'] ); ?>">
            <a <?php echo $link != '' ? 'href="' . $link . '"' : ''; ?>
                    class="currentColor flex flex-col align-items-center <?php echo esc_attr( $mob_panel_element_options['link_class'] ); ?>">
				
				<?php if ( $key['icon'] != 'none' ) : ?>
                    <span class="et_b-icon">
							<?php
							if ( isset( $key['icon_custom'] ) && $key['icon_custom'] != '' ) {
								$type      = get_post_mime_type( $key['icon_custom'] );
								$mime_type = explode( '/', $type );
								if ( $mime_type['1'] == 'svg+xml' ) {
									$svg = get_post_meta( $key['icon_custom'], '_xstore_inline_svg', true );
									
									if ( ! empty( $svg ) ) {
										echo '<span class="et-svg">' . $svg . '</span>';
									} else {
										
										$attachment_file = get_attached_file( $key['icon_custom'] );
										
										if ( $attachment_file ) {
											
											$svg = file_get_contents( $attachment_file );
											
											if ( ! empty( $svg ) ) {
												update_post_meta( $key['icon_custom'], '_xstore_inline_svg', $svg );
											}
											
											echo '<span class="et-svg">' . $svg . '</span>';
											
										}
										
									}
								} else {
									echo '<span class="et-svg">' . etheme_get_image( $key['icon_custom'], 'thumbnail' ) . '</span>';
								}
							} elseif ( $mob_panel_element_options['icons'][ $key['icon'] ] != '' ) {
								echo '<span class="et-svg">' . $mob_panel_element_options['icons'][ $key['icon'] ] . '</span>';
							}
							if ( in_array( $key['element'], array( 'more_toggle', 'more_toggle_02', 'search' ) ) ) : ?>
                                <span class="et-svg et-close">
									<svg xmlns="http://www.w3.org/2000/svg" width=".8em" height=".8em"
                                         viewBox="0 0 24 24">
					                    <path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
					                </svg>
					            </span>
							
							<?php endif;
							
							if ( $mob_panel_element_options['mobile_panel_elements_labels'] ) {
								echo $mob_panel_element_options['label'];
							}
							?>
						</span>
				<?php endif; ?>
				
				<?php if ( $mob_panel_element_options['mobile_panel_elements_texts'] ) : ?>
                    <span class="text-nowrap">
							<?php echo ! empty( $key['text'] ) ? do_shortcode( $key['text'] ) : $mob_panel_element_options['elements_settings']['labels'][ $key['element'] ]; ?>
						</span>
				<?php endif; ?>

            </a>
			<?php echo $mob_panel_element_options['mini_content']; ?>
        </div>
		<?php
		$mob_panel_element_options['_i'] ++;
	}
	
	remove_filter( 'menu_item_design', 'etheme_menu_item_design_dropdown', 15 );
	remove_filter( 'menu_dropdown_ajax', '__return_false' );
	
	$et_builder_globals['in_mobile_panel'] = false;
	unset( $mob_panel_element_options );
}