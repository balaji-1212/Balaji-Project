<?php
	/**
	 * The template for displaying header mobile menu block
	 *
	 * @since   1.4.0
	 * @version 1.0.3
	 * last changes in 1.5.5
	 */
 ?>

<?php 	
	
	if ( !class_exists('ETheme_Navigation') ) return;

	global $et_builder_globals;

	$et_builder_globals['in_mobile_menu'] = true;

	$mob_menu_element_options = array();

	$mob_menu_element_options['mobile_menu_type_et-desktop'] = get_theme_mod( 'mobile_menu_type_et-desktop', 'off_canvas_left' );
	$mob_menu_element_options['mobile_menu_item_click_et-desktop'] = get_theme_mod( 'mobile_menu_item_click_et-desktop', '0' );
    $mob_menu_element_options['mobile_menu_content'] = get_theme_mod( 'mobile_menu_content', array('logo','search','menu','header_socials') );
	$mob_menu_element_options['mobile_menu_content_position'] = ( $mob_menu_element_options['mobile_menu_type_et-desktop'] == 'off_canvas_left' ) ? 'left' : 'right';

	$mob_menu_element_options['mobile_menu_classes'] = ' static';
	$mob_menu_element_options['mobile_menu_classes'] .= ( $mob_menu_element_options['mobile_menu_type_et-desktop'] != 'popup' ) ? ' et-content_toggle et-off-canvas et-content-' . $mob_menu_element_options['mobile_menu_content_position'] : ' ';
	$mob_menu_element_options['mobile_menu_classes'] .= ' toggles-by-arrow';

	$mob_menu_element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
	$mob_menu_element_options['attributes'] = array();
	if ( $mob_menu_element_options['is_customize_preview'] ) 
		$mob_menu_element_options['attributes'] = array(
			'data-title="' . esc_html__( 'Mobile menu', 'xstore-core' ) . '"',
			'data-element="mobile_menu"'
		); 
	$mob_menu_element_options['attributes'][] = 'data-item-click="' . ( ( $mob_menu_element_options['mobile_menu_item_click_et-desktop'] ) ? 'arrow' : 'item' ) . '"';

	if ( !$mob_menu_element_options['is_customize_preview'] ) {
		foreach ( $mob_menu_element_options['mobile_menu_content'] as $element ) {
			switch ( $element ) {
				case 'search':
					wp_enqueue_script( 'ajax_search' );
					if ( get_theme_mod( 'search_category_fancy_select_et-desktop', false ) ) {
						wp_enqueue_script( 'fancy-select' );
					}
					etheme_enqueue_style( 'header-search' );
					if ( get_theme_mod( 'search_ajax_et-desktop', '1' ) ) {
						etheme_enqueue_style( 'ajax-search' );
                    }
					break;
				case 'menu':
					etheme_enqueue_style( 'header-menu' );
					break;
				default;
			}
		}
	}
    wp_enqueue_script( 'mobile_menu' );
    if ( get_theme_mod('mobile_menu_one_page', '0') ) {
        wp_enqueue_script( 'one_page_menu' );
    }
    
//    if ( $mob_menu_element_options['mobile_menu_type_et-desktop'] != 'popup' || $mob_menu_element_options['is_customize_preview'] ) {
        // could be via default wp
	    if ( function_exists('etheme_enqueue_style')) {
	        if ( $mob_menu_element_options['mobile_menu_type_et-desktop'] != 'popup' || $mob_menu_element_options['is_customize_preview'] ) {
		        etheme_enqueue_style( 'off-canvas' );
	        }
		    if ( $mob_menu_element_options['mobile_menu_type_et-desktop'] == 'popup' || $mob_menu_element_options['is_customize_preview'] ) {
			    etheme_enqueue_style( 'skeleton' );
		    }
		    etheme_enqueue_style( 'header-mobile-menu' );
		    etheme_enqueue_style( 'toggles-by-arrow' );
	    }
//    }

?>

<div class="et_element et_b_header-mobile-menu <?php echo $mob_menu_element_options['mobile_menu_classes']; ?>" <?php echo implode( ' ', $mob_menu_element_options['attributes'] ); ?>>
	<?php echo mobile_menu_callback(); ?>
</div>
<?php 

$et_builder_globals['in_mobile_menu'] = false;

?>
<?php unset($mob_menu_element_options); ?>