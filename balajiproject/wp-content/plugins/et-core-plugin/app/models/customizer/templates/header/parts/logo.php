<?php
/**
 * The template for displaying header logo block
 *
 * @since   1.4.0
 * @version 1.0.4
 * last changes in 4.0.1
 * @log
 * 1.0.3
 * FIXED: polylang home page url
 * 4.0.1
 * FIXED: get logos via wp functions
 * 1.0.4
 * FIXED: retina logo overwrite vertical header logo
 */
?>

<?php

global $et_builder_globals;

$element_options = array();

$is_vertical = isset($et_builder_globals['is_vertical']) && $et_builder_globals['is_vertical'];

// logo img default
$element_options['logo_img_ready'] = '<img class="et_b_header-logo-img" src="'.ETHEME_BASE_URI.'theme/assets/images/logo.png'.'" alt="logo">';
$element_options['logo_sticky_img_ready'] = '<img class="et_b_header-logo-img" src="'.ETHEME_BASE_URI.'theme/assets/images/logo.png'.'" alt="logo">';

$element_options['logo_img_et-desktop'] = get_theme_mod( 'logo_img_et-desktop', 'logo' );
$element_options['logo_img_et-desktop'] = apply_filters('logo_img', $element_options['logo_img_et-desktop']);

// retina logo
$element_options['retina_logo_img_et-desktop'] = get_theme_mod( 'retina_logo_img_et-desktop', '' );
$element_options['retina_logo_img'] = '';

// to use desktop styles when use this element in mobile menu for example etc.
$element_options['etheme_use_desktop_style'] = false;
$element_options['etheme_use_desktop_style'] = apply_filters( 'etheme_use_desktop_style', $element_options['etheme_use_desktop_style'] );

$element_options['logo_align'] = 'align-' . get_theme_mod( 'logo_align_et-desktop', 'center' );
$element_options['logo_align'] .= ( !$element_options['etheme_use_desktop_style'] ) ? ' mob-align-' . get_theme_mod( 'logo_align_et-mobile', 'center' ) : '';

$element_options['logo_align'] = ' ' . apply_filters('logo_align', $element_options['logo_align']);

// retina logo sets up
if ( is_array($element_options['retina_logo_img_et-desktop']) && ! $is_vertical ) {
	if ( isset($element_options['retina_logo_img_et-desktop']['url']) && $element_options['retina_logo_img_et-desktop']['url'] != '' ) {
		$element_options['retina_logo_img'] = ' srcset=' . $element_options['retina_logo_img_et-desktop']['url'] . ' 2x';
	}
}

// sticky retina none by default
$element_options['headers_sticky_logo_img_et-desktop'] = get_theme_mod( 'headers_sticky_logo_img_et-desktop', '' );

$element_options['logo_simple_et-desktop'] = true;
$element_options['logo_simple_et-desktop'] = apply_filters('etheme_logo_simple', $element_options['logo_simple_et-desktop']);

$element_options['logo_sticky_et-desktop'] = true;
$element_options['logo_sticky_et-desktop'] = apply_filters('etheme_logo_sticky', $element_options['logo_sticky_et-desktop']);

$element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);
$element_options['attributes'] = array();
if ( $element_options['is_customize_preview'] )
	$element_options['attributes'] = array(
		'data-title="' . esc_html__( 'Logo', 'xstore-core' ) . '"',
		'data-element="logo"'
	);

$element_options['has_lazy_load_filter'] = has_filter('wp_get_attachment_image_attributes', 'etheme_lazy_attachment_attrs');
if ( $element_options['has_lazy_load_filter'] ) {
	remove_filter( 'wp_get_attachment_image_attributes', 'etheme_lazy_attachment_attrs', 10, 3 );
}
$element_options['logo_simple_attr'] = array(
    'class' => 'et_b_header-logo-img'
);

if ( is_array($element_options['retina_logo_img_et-desktop']) && ! $is_vertical ) {
	if ( isset($element_options['retina_logo_img_et-desktop']['url']) && $element_options['retina_logo_img_et-desktop']['url'] != '' ) {
		$element_options['logo_simple_attr']['srcset'] = $element_options['retina_logo_img_et-desktop']['url'] . ' 2x';
    }
}

// placed above because if it shows in mobile menu then it loads only one version and simple & sticky logos should be ready
if ( is_array($element_options['logo_img_et-desktop']) ) {
	if ( isset($element_options['logo_img_et-desktop']['id']) && $element_options['logo_img_et-desktop']['id'] != '' ) {
		$element_options['logo_img_ready'] = $element_options['logo_sticky_img_ready'] = wp_get_attachment_image( $element_options['logo_img_et-desktop']['id'], 'full', false, $element_options['logo_simple_attr'] );

		// do it for import because image imported with wrong id
		if (empty($element_options['logo_img_ready']) && isset($element_options['logo_img_et-desktop']['url'])){
			$element_options['logo_img_ready'] = '<img width="'.$element_options['logo_img_et-desktop']['width'].'" height="39" src="'.$element_options['logo_img_et-desktop']['url'].'" class="et_b_header-logo-img" alt="site logo" srcset="'.$element_options['logo_img_et-desktop']['url'].'">';
        }
	}
}

?>

    <div class="et_element et_b_header-logo<?php echo $element_options['logo_align'] . ( $et_builder_globals['in_mobile_menu'] ? '' : ' et_element-top-level' ); ?>" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
        <a href="<?php echo (function_exists('pll_home_url')&&function_exists('pll_current_language')) ? pll_home_url(pll_current_language()) : home_url(); ?>">
            <?php if ( $element_options['logo_simple_et-desktop'] ) :
	            echo '<span>'.str_replace('2x"', '" 2x',$element_options['logo_img_ready']).'</span>';
            endif;
            ?>



            
            <?php if ( $element_options['logo_sticky_et-desktop'] ) :
	            if ( is_array($element_options['headers_sticky_logo_img_et-desktop']) ) {
		            if ( isset($element_options['headers_sticky_logo_img_et-desktop']['id']) && $element_options['headers_sticky_logo_img_et-desktop']['id'] != '' ) {
			            $element_options['logo_sticky_img_ready'] = wp_get_attachment_image( $element_options['headers_sticky_logo_img_et-desktop']['id'], 'full', false, array(
				            'class' => 'et_b_header-logo-img'
                        ) );
		            }
	            }
//	            echo '<span class="fixed">'.$element_options['logo_sticky_img_ready'].'</span>';
	            echo '<span class="fixed">'.str_replace('2x"', '" 2x',$element_options['logo_sticky_img_ready']).'</span>';
            endif;
            ?>
            
        </a>
    </div>

<?php

if ( $element_options['has_lazy_load_filter'] ) {
	add_filter( 'wp_get_attachment_image_attributes', 'etheme_lazy_attachment_attrs', 10, 3 );
}

unset($element_options); ?>