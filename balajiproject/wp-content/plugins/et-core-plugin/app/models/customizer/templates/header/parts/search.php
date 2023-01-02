<?php
/**
 * The template for displaying header search block
 *
 * @since   1.4.0
 * @version 1.0.3
 * last changes in 2.0.0
 */
?>

<?php

global $et_builder_globals;

$element_options = array();

$element_options['is_mobile'] = get_query_var('is_mobile', false);
$element_options['is_woocommerce'] = class_exists('WooCommerce');

$element_options['search_type'] = get_theme_mod( 'search_type_et-desktop', 'input' ); // icon, full form
$element_options['search_type'] = apply_filters('search_type', $element_options['search_type']);

$element_options['is_popup'] = $element_options['search_type'] == 'popup';
$element_options['is_popup'] = apply_filters('search_mode_is_popup', $element_options['is_popup']);

//    $element_options['search_mode'] = get_theme_mod( 'search_mode_et-desktop' );
//	$element_options['search_mode'] = apply_filters('search_mode', $element_options['search_mode']);

$element_options['icon_type_et-desktop'] = get_theme_mod( 'search_icon_et-desktop', 'type1' );
$element_options['icon_type_et-desktop'] = apply_filters('search_icon', $element_options['icon_type_et-desktop']);
$element_options['icon_custom'] = get_theme_mod('search_icon_custom_et-desktop', '');
$element_options['icon_custom'] = apply_filters('search_icon_custom', $element_options['icon_custom']);
$element_options['icon_custom'] = isset($element_options['icon_custom']['id']) ? $element_options['icon_custom']['id'] : '';

$element_options['search_type'] = $element_options['is_popup'] ? 'icon' : $element_options['search_type'];

$element_options['search_content'] = get_theme_mod( 'search_results_content_et-desktop',
	array(
		'products',
		'posts',
	)
);

$element_options['search_ajax'] = get_theme_mod( 'search_ajax_et-desktop', '1' );
$element_options['search_ajax_history'] = get_theme_mod( 'search_ajax_history_et-desktop', '0' );
$element_options['search_ajax_history_title_et-desktop'] = get_theme_mod( 'search_ajax_history_title_et-desktop', esc_html('Search History:') );
//$element_options['search_ajax_clear_history_title_et-desktop'] = get_theme_mod( 'search_ajax_clear_history_title_et-desktop', esc_html('clear history') );
$element_options['search_ajax_history_length-desktop'] = get_theme_mod( 'search_ajax_history_length-desktop', 7 );
$element_options['search_ajax_with_tabs'] = apply_filters('search_ajax_with_tabs', get_theme_mod('search_ajax_with_tabs_et-desktop', '0'));
if ( $element_options['is_popup'] ) {
	$element_options['search_ajax_with_tabs'] = false;
}

// to use desktop styles when use this element in mobile menu for example etc.
$element_options['etheme_use_desktop_style'] = false;
$element_options['etheme_use_desktop_style'] = apply_filters( 'etheme_use_desktop_style', $element_options['etheme_use_desktop_style'] );

if ( get_theme_mod('bold_icons', 0) ) {
	$element_options['search_icon_et-desktop'] = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path d="M23.64 22.176l-5.736-5.712c1.44-1.8 2.232-4.032 2.232-6.336 0-5.544-4.512-10.032-10.032-10.032s-10.008 4.488-10.008 10.008c-0.024 5.568 4.488 10.056 10.032 10.056 2.328 0 4.512-0.792 6.336-2.256l5.712 5.712c0.192 0.192 0.456 0.312 0.72 0.312 0.24 0 0.504-0.096 0.672-0.288 0.192-0.168 0.312-0.384 0.336-0.672v-0.048c0.024-0.288-0.096-0.552-0.264-0.744zM18.12 10.152c0 4.392-3.6 7.992-8.016 7.992-4.392 0-7.992-3.6-7.992-8.016 0-4.392 3.6-7.992 8.016-7.992 4.392 0 7.992 3.6 7.992 8.016z"></path></svg>';
}
else {
	$element_options['search_icon_et-desktop'] ='<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" viewBox="0 0 24 24"><path d="M23.784 22.8l-6.168-6.144c1.584-1.848 2.448-4.176 2.448-6.576 0-5.52-4.488-10.032-10.032-10.032-5.52 0-10.008 4.488-10.008 10.008s4.488 10.032 10.032 10.032c2.424 0 4.728-0.864 6.576-2.472l6.168 6.144c0.144 0.144 0.312 0.216 0.48 0.216s0.336-0.072 0.456-0.192c0.144-0.12 0.216-0.288 0.24-0.48 0-0.192-0.072-0.384-0.192-0.504zM18.696 10.080c0 4.752-3.888 8.64-8.664 8.64-4.752 0-8.64-3.888-8.64-8.664 0-4.752 3.888-8.64 8.664-8.64s8.64 3.888 8.64 8.664z"></path></svg>';
}

if ( $element_options['icon_type_et-desktop'] == 'custom' && $element_options['icon_custom'] != '' ) {
	$element_options['icon_custom_type']      = get_post_mime_type( $element_options['icon_custom'] );
	$element_options['icon_custom_mime_type'] = explode( '/', $element_options['icon_custom_type'] );
	if ( $element_options['icon_custom_mime_type']['1'] == 'svg+xml' ) {
		$element_options['rendered_svg'] = get_post_meta( $element_options['icon_custom'], '_xstore_inline_svg', true );
		
		if ( ! empty( $element_options['rendered_svg'] ) ) {
			$element_options['search_icon_et-desktop'] = $element_options['rendered_svg'];
		} else {
			
			$element_options['attachment_file'] = get_attached_file( $element_options['icon_custom'] );
			
			if ( $element_options['attachment_file'] ) {
				
				$element_options['rendered_svg'] = file_get_contents( $element_options['attachment_file'] );
				
				if ( ! empty( $element_options['rendered_svg'] ) ) {
					update_post_meta( $element_options['icon_custom'], '_xstore_inline_svg', $element_options['rendered_svg'] );
				}
				
				$element_options['search_icon_et-desktop'] = $element_options['rendered_svg'];
				
			}
			
		}
	}
    elseif ( function_exists('etheme_get_image') ) {
		$element_options['search_icon_et-desktop'] = etheme_get_image($element_options['icon_custom'], 'thumbnail' );
	}
}

$element_options['search_icon_et-desktop'] = apply_filters('et_b_search_icon', $element_options['search_icon_et-desktop']);

$element_options['search_category_et-desktop'] = get_theme_mod( 'search_category_et-desktop', '1' );
$element_options['search_category_et-desktop'] = apply_filters('search_category', $element_options['search_category_et-desktop']);
$element_options['search_posts_et-desktop'] = get_theme_mod( 'search_posts_et-desktop' );

$element_options['search_content_alignment'] = ' justify-content-' . get_theme_mod( 'search_content_alignment_et-desktop', 'center' );
$element_options['search_content_alignment'] .= ( !$element_options['etheme_use_desktop_style'] ) ? ' mob-justify-content-' . get_theme_mod( 'search_content_alignment_et-mobile' ) : '';

$element_options['search_content_position_et-desktop'] = get_theme_mod( 'search_content_position_et-desktop', 'right' );
$element_options['search_content_position_et-mobile'] = get_theme_mod( 'search_content_position_et-mobile' );

$element_options['search_content_position'] = ( $element_options['search_content_position_et-desktop'] != 'custom' ) ? ' et-content-' . $element_options['search_content_position_et-desktop'] : '';

$element_options['search_placeholder_et-desktop'] = get_theme_mod( 'search_placeholder_et-desktop', 'Search for...' );

$element_options['search_categories_et-desktop'] = '';

$element_options['etheme_search_results'] = true;
$element_options['etheme_search_results'] = apply_filters('etheme_search_results', $element_options['etheme_search_results']);

if ( $element_options['search_category_et-desktop'] && $element_options['etheme_search_results'] ) {
	
	$element_options['search_taxonomy_et-desktop'] = ( $element_options['is_woocommerce'] && in_array('products', $element_options['search_content']) ) ? 'product_cat' : 'category';
	
	$element_options['search_all_categories_text_et-desktop'] = get_theme_mod('search_all_categories_text_et-desktop', 'All categories');
	
	$element_options['search_categories_et-desktop'] = wp_dropdown_categories(
		array(
			'show_option_all' => ( $element_options['search_all_categories_text_et-desktop'] != '' ) ? $element_options['search_all_categories_text_et-desktop'] : false,
			'taxonomy'        => $element_options['search_taxonomy_et-desktop'],
			'hierarchical'    => true,
			'echo'            => 0,
			'id'              => $element_options['search_taxonomy_et-desktop'] . '-' . rand( 100, 999 ),
			'name'            => $element_options['search_taxonomy_et-desktop'],
			'orderby'         => 'name',
			'value_field'     => 'slug',
			'hide_if_empty'   => true
		)
	);
	
	$element_options['search_categories_et-desktop'] = str_replace( '<select', '<select style="width: 100%; max-width: calc(122px + 1.4em)"', $element_options['search_categories_et-desktop'] );
}

$element_options['class'] = ( $element_options['etheme_search_results'] && $element_options['search_ajax'] ) ? ' ajax-with-suggestions' : '';

$element_options['search_by_icon-click'] = ( ( $element_options['search_type'] == 'icon' || $element_options['is_popup'] ) && $element_options['etheme_search_results'] ) ? true : false;
$element_options['search_by_icon-click'] = apply_filters('search_by_icon', $element_options['search_by_icon-click']);

$element_options['min_symbols'] = get_theme_mod( 'search_ajax_min_symbols_et-desktop', 3 );
$element_options['posts_per_page'] = get_theme_mod('search_ajax_posts_per_page', 100);

$element_options['icon_class'] = '';

$element_options['wrapper_class'] = ' ' . $element_options['search_content_position'];
$element_options['wrapper_class'] .= $element_options['search_content_alignment'];
$element_options['wrapper_class'] .= ( ( $element_options['search_type'] != 'icon' ) ? ' flex-basis-full' : '' );
$element_options['wrapper_class'] .= ( ($et_builder_globals['in_mobile_menu'] || (isset($et_builder_globals['in_mobile_panel']) && $et_builder_globals['in_mobile_panel'])) ? '' : ' et_element-top-level' );
$element_options['wrapper_class'] .= ( $element_options['is_popup'] ) ? ' search-full-width' : '';

if ( $element_options['search_by_icon-click'] && !$et_builder_globals['in_mobile_menu'] && (get_query_var('is_mobile', false) || $element_options['is_popup'])) {
	$element_options['wrapper_class'] .= ' et-content_toggle';
	$element_options['icon_class'] .= ' et-toggle';
	if ( $element_options['is_popup'])
		$element_options['icon_class'] .= ' pointer';
}

$element_options['class'] .= ' input-' . $element_options['search_type'].' ';

if ( $element_options['search_by_icon-click'] && ! $element_options['is_popup'] ) {
	$element_options['class'] .= ' et-mini-content';
}

if ( $element_options['is_popup'] ) {
	$element_options['class'] .= ' container search-full-width-form';
}
else {
	$element_options['wrapper_class'] .= ' et-content-dropdown';
}

$element_options['search_label_et-desktop'] = get_theme_mod( 'search_label_et-desktop', false );
$element_options['search_label_et-mobile'] = get_theme_mod( 'search_label_et-mobile', false );
$element_options['search_label'] = $element_options['search_label_et-desktop'] || $element_options['search_label_et-mobile'] || apply_filters('is_customize_preview', false);
$element_options['search_label_text'] = '';

if ( $element_options['search_label'] ) {
	$element_options['search_label_text'] = esc_html__('Search', 'xstore-core');
	if ( get_theme_mod( 'search_label_custom_et-desktop', 'Search' ) != '' ) $element_options['search_label_text'] = get_theme_mod( 'search_label_custom_et-desktop', 'Search' );
}

$element_options['label_class'] = ( !$element_options['search_label_et-mobile'] ) ? 'mob-hide' : '';
$element_options['label_class'] .= ( !$element_options['search_label_et-desktop'] ) ? ' dt-hide' : '';

$element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
$element_options['attributes'] = array();
if ( $element_options['is_customize_preview'] )
	$element_options['attributes'] = array(
		'data-title="' . esc_html__( 'Search', 'xstore-core' ) . '"',
		'data-element="search"'
	);

$element_options['search_query'] = get_search_query();
if ( $element_options['search_query'])
	$element_options['class'] .= ' ajax-results-shown';

$element_options['input_row_class'] = array();

if ($element_options['search_categories_et-desktop'] == '')
	$element_options['input_row_class'][] = 'et-overflow-hidden';

$element_options['input_id'] = 'et_b-header-search-input-'.rand(0,99);

if ( get_theme_mod('search_category_fancy_select_et-desktop', false) ) {
	wp_enqueue_script( 'fancy-select' );
}

if ( $element_options['search_ajax'] ) {
	wp_enqueue_script( 'ajax_search');
    if ( !$et_builder_globals['in_mobile_menu'] && function_exists('etheme_enqueue_style')) {
        etheme_enqueue_style( 'ajax-search' );
    }
}
if ( !$et_builder_globals['in_mobile_menu'] && function_exists('etheme_enqueue_style')) {
	// in case it loads for mobile-menu
	etheme_enqueue_style( 'header-search' );
	
	if ( $element_options['is_popup'] ) {
		etheme_enqueue_style( 'full-width-search' );
//		if ( $element_options['search_ajax'] ) {
//			if ( $element_options['is_woocommerce'] && in_array( 'products', $element_options['search_content'] ) ) {
//				etheme_enqueue_style( 'woocommerce' );
//				etheme_enqueue_style( 'woocommerce-archive' );
//				etheme_enqueue_style( 'product-view-default' );
//			}
//			if ( in_array( 'posts', $element_options['search_content'] ) ) {
//				etheme_enqueue_style( 'blog-global' );
//			}
//			if ( in_array( 'portfolio', $element_options['search_content'] ) ) {
//				etheme_enqueue_style( 'portfolio' );
//			}
//		}
	}
}

?>

<div class="et_element et_b_header-search flex align-items-center <?php echo $element_options['wrapper_class']; ?>" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
	<?php if ( $element_options['search_by_icon-click'] ) { ?>
        <span class="flex et_b_search-icon <?php echo esc_attr($element_options['icon_class']); ?>">
            <?php
            if ( $element_options['search_icon_et-desktop'] )
                echo '<span class="et_b-icon">'.$element_options['search_icon_et-desktop'].'</span>';
            if ( $element_options['search_label'] ) : ?>
            <span class="et-element-label inline-block <?php echo ( !$et_builder_globals['in_mobile_menu'] ) ? $element_options['label_class'] : ''; ?>">
                <?php echo $element_options['search_label_text']; ?>
            </span>
            <?php endif; ?>
        </span>
	<?php } ?>
	<?php if ( $element_options['is_popup'] ) : ?>
    <div class="et-mini-content" style="display: none">
	    <?php if ( !$element_options['is_mobile'] ) { ?>
            <span class="et-toggle pos-absolute et-close right top">
				<svg xmlns="http://www.w3.org/2000/svg" width="1.4em" height="1.4em" viewBox="0 0 24 24"><path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path>
				</svg>
			</span>
        <?php } ?>
		<?php endif; ?>

	    <?php
            $url = home_url( '/' );
            if ( apply_filters( 'etheme_forced_search', false ) && defined('ICL_LANGUAGE_CODE') ) {
                $url = home_url( '/' . ICL_LANGUAGE_CODE . '/' );
            }

	        $url = apply_filters( 'etheme_search_url', $url );
	    ?>

        <form action="<?php echo esc_url( $url ); ?>" role="search" data-min="<?php echo esc_attr($element_options['min_symbols']); ?>" data-per-page="<?php echo esc_attr($element_options['posts_per_page']); ?>"
              <?php if ($element_options['search_ajax_with_tabs']) : ?>data-tabs="<?php echo $element_options['search_ajax_with_tabs']; ?>"<?php endif; ?>
              class="ajax-search-form <?php  esc_attr_e( $element_options['class'] ); ?>" method="get">
			<?php if ( $element_options['is_popup'] ) : ?>
            <div class="search-content-head">
                <?php if ( !$element_options['is_mobile'] ) { ?>
                    <div class="full-width align-center products-title"><?php echo esc_html__('What are you looking for?', 'xstore-core'); ?></div>
                <?php } ?>
            <?php endif; ?>

                <div class="input-row flex align-items-center <?php echo implode(' ', $element_options['input_row_class']); ?>" data-search-mode="<?php echo (get_theme_mod('search_category_select_color_scheme_et-desktop', 'dark')) ?>">
                    <?php
                        if ( $element_options['is_popup'] && $element_options['is_mobile']) { ?>
                            <span class="et-toggle et-close">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1.3em" height="1.3em" fill="currentColor" style="vertical-align: middle" viewBox="0 0 24 24">
                                    <path d="M17.976 22.8l-10.44-10.8 10.464-10.848c0.24-0.288 0.24-0.72-0.024-0.96-0.24-0.24-0.72-0.264-0.984 0l-10.92 11.328c-0.264 0.264-0.264 0.672 0 0.984l10.92 11.28c0.144 0.144 0.312 0.216 0.504 0.216 0.168 0 0.336-0.072 0.456-0.192 0.144-0.12 0.216-0.288 0.24-0.48 0-0.216-0.072-0.384-0.216-0.528z"></path>
                                </svg>
                            </span>
                        <?php }
                    ?>
					<?php echo $element_options['search_categories_et-desktop']; ?>
                    <label class="screen-reader-text" for="<?php echo esc_attr($element_options['input_id']); ?>"><?php echo esc_html__('Search input', 'xstore-core'); ?></label>
                    <input type="text" value="<?php if($element_options['search_query']) echo esc_attr($element_options['search_query']); ?>"
                           placeholder="<?php echo $element_options['search_placeholder_et-desktop']; ?>" autocomplete="off" class="form-control" id="<?php echo esc_attr($element_options['input_id']); ?>" name="s">
					
					<?php if ( $element_options['is_woocommerce'] ): ?>
                        <input type="hidden" name="post_type" value="<?php echo ( $element_options['is_woocommerce'] ) ? 'product': 'post'; ?>">
					<?php endif ?>

                    <input type="hidden" name="et_search" value="true">
					
					<?php if ( defined( 'ICL_LANGUAGE_CODE' ) && ! defined( 'LOCO_LANG_DIR' ) ) : ?>
                        <input type="hidden" name="lang" value="<?php echo ICL_LANGUAGE_CODE; ?>"/>
					<?php endif ?>
                    <span class="buttons-wrapper flex flex-nowrap pos-relative">
                    <span class="clear flex-inline justify-content-center align-items-center pointer">
                        <span class="et_b-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width=".7em" height=".7em" viewBox="0 0 24 24"><path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path></svg>
                        </span>
                    </span>
                    <button type="submit" class="search-button flex justify-content-center align-items-center pointer">
                        <span class="et_b-loader"></span>
                    <?php echo $element_options['search_icon_et-desktop']; ?>
                    <span class="screen-reader-text"><?php echo esc_html__('Search', 'xstore-core'); ?></span></button>
                </span>
                </div>
				
				<?php if ( $element_options['is_popup'] ) :
				$element_options['search_tags'] = get_theme_mod('search_tags_et-desktop', 'Shirt, Shoes, Cap, Coat, Skirt');
				if ($element_options['search_tags'] != '') {
					$element_options['search_tags'] = explode(',', $element_options['search_tags']);
				}
				else {
				    $element_options['search_tags'] = array();
                }
				if ( count($element_options['search_tags']) ) :
                    $title = get_theme_mod('search_trending_et-desktop', esc_html__('Trending Searches:', 'xstore-core') ); ?>
                    <div class="ajax-search-tags full-width align-<?php echo !$element_options['is_mobile'] ? 'center' : 'start'; ?>">
                        <?php if ( !empty($title))
						    echo '<span>' . $title . '</span>'; ?>
          
                        <?php if ( $element_options['is_popup'] && $element_options['is_mobile'] ) echo '<div class="ajax-search-tags-inner">';
                            foreach ( $element_options['search_tags'] as $search_tag ) { ?>
                                <a><?php echo esc_html($search_tag); ?></a>
                            <?php } ?>
                        <?php if ( $element_options['is_popup'] && $element_options['is_mobile'] ) echo '</div>'; ?>
                    </div>
				<?php endif; ?>

                <?php if($element_options['search_ajax_history']): ?>
                    <?php
                    $history = isset($_COOKIE['et_search_history']) ? $_COOKIE['et_search_history'] : array();

                    if ($history){
	                    $history = json_decode(wp_unslash($history, true));
                    }


	                $history = array_reverse($history);
                    ?>
                    <div class="ajax-search-history full-width align-<?php echo !$element_options['is_mobile'] ? 'center' : 'start'; ?> <?php if (count($history)) echo 'active'; ?>">

                        <?php echo '<span class="et_history-title">'.$element_options['search_ajax_history_title_et-desktop'].'</span>'; ?>

                        <?php if ( $element_options['is_popup'] && $element_options['is_mobile'] ) echo '<div class="ajax-search-tags-inner">'; ?>

                           <?php $_i = 0; foreach ( $history as $item ) : $_i++; ?>
                                <?php if($_i > $element_options['search_ajax_history_length-desktop']) continue; ?>
                               <a data-s="<?php echo esc_js($item); ?>"><?php echo esc_html($item); ?></a>
                           <?php endforeach; ?>

<!--                            <span class="et_s-icon et_clear-history pointer flex-inline justify-content-center ">-->
                                <?php //echo esc_html($element_options['search_ajax_clear_history_title_et-desktop']); ?>
<!--                                <svg xmlns="http://www.w3.org/2000/svg" width=".7em" height=".7em" viewBox="0 0 24 24"><path d="M13.056 12l10.728-10.704c0.144-0.144 0.216-0.336 0.216-0.552 0-0.192-0.072-0.384-0.216-0.528-0.144-0.12-0.336-0.216-0.528-0.216 0 0 0 0 0 0-0.192 0-0.408 0.072-0.528 0.216l-10.728 10.728-10.704-10.728c-0.288-0.288-0.768-0.288-1.056 0-0.168 0.144-0.24 0.336-0.24 0.528 0 0.216 0.072 0.408 0.216 0.552l10.728 10.704-10.728 10.704c-0.144 0.144-0.216 0.336-0.216 0.552s0.072 0.384 0.216 0.528c0.288 0.288 0.768 0.288 1.056 0l10.728-10.728 10.704 10.704c0.144 0.144 0.336 0.216 0.528 0.216s0.384-0.072 0.528-0.216c0.144-0.144 0.216-0.336 0.216-0.528s-0.072-0.384-0.216-0.528l-10.704-10.704z"></path></svg>-->
<!--                            </span>-->

                        <?php if ( $element_options['is_popup'] && $element_options['is_mobile'] ) echo '</div>'; ?>
                    </div>
                <?php endif; ?>

            </div>
		<?php endif;?>
			<?php if ( $element_options['etheme_search_results'] && $element_options['search_ajax'] ) : ?>
				<?php if ( $element_options['is_popup'] ) :
                    $element_options['extra_content'] = get_theme_mod('search_extra_content_et-desktop', 'product_categories'); ?>

                    <?php if ( $element_options['extra_content'] != 'none' ) : ?>
                        <div class="ajax-extra-content">
                    <?php endif; ?>
                    
                    <?php switch ($element_options['extra_content']) {
                        case 'product_categories':
                                if ( $element_options['is_woocommerce'] ) { ?>
                                    <div class="ajax-search-categories">
                                        <div class="full-width align-center products-title"><?php esc_html_e('Popular categories', 'xstore-core'); ?></div>
		                                <?php
		                                $element_options['categories'] = get_theme_mod('search_categories_et-desktop', array());
		                                // fix for multiple
		                                if ( count($element_options['categories']) == 1 && empty($element_options['categories'][0])) {
			                                $element_options['categories'] = array();
		                                }
		                                $element_options['categories_columns'] = 6;
		                                $element_options['ids'] = '';
		                                $element_options['hide_empty'] = true;
		                                if ( count($element_options['categories']) >= 1 ) {
			                                $element_options['ids'] = implode(',', $element_options['categories']);
			                                $element_options['categories_columns'] = count($element_options['categories']);
			                                $element_options['hide_empty'] = false;
		                                }
		
		                                $element_options['categories_options'] = array(
			                                'columns' => $element_options['categories_columns'],
			                                'number' => $element_options['categories_columns'],
			                                'style' => 'default',
			                                'content_position' => 'under',
			                                'text_color' => 'dark',
			                                'ids' => $element_options['ids'],
			                                'hide_empty' => $element_options['hide_empty']
		                                );
		
		                                if ( $element_options['is_mobile']) {
			                                $element_options['categories_options'] = array_merge(
				                                $element_options['categories_options'],
				                                array(
					                                'display_type' => 'slider',
					                                'large'                => 3,
					                                'notebook'             => 3,
					                                'tablet_land'          => 3,
					                                'tablet_portrait'      => 3,
					                                'mobile'               => 3,
					                                'slider_spacing' => 15
				                                )
			                                );
		                                }
		
		                                echo ETC\App\Controllers\Shortcodes\Categories::categories_shortcode(
			                                $element_options['categories_options']
		                                );
		                                ?>
                                        <div class="full-width text-center"><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn black"><?php esc_html_e('View all categories', 'xstore-core'); ?></a></div>
                                    </div>
                                <?php }
                            break;
                        case 'custom_html':
	                        echo html_blocks_callback( array(
		                        'html_backup'         => 'search_html_block_et-desktop',
	                        ) );
                            break;
                    case 'staticblock':
	                    echo html_blocks_callback( array(
		                    'section'         => 'search_staticblock',
		                    'html_backup'     => 'search_html_block_et-desktop',
		                    'force_sections'  => true,
		                    'section_content' => true
	                    ) );
                        break;
                    }?>
	
                    <?php if ( $element_options['extra_content'] != 'none' ) : ?>
                      </div>
                    <?php endif; ?>
                
				<?php endif; ?>
                <div class="ajax-results-wrapper"></div>
			<?php endif; ?>
        </form>
		<?php if ( $element_options['is_popup'] ) : ?>
    </div>
<?php endif; ?>
</div>
