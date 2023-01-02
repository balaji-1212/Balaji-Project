<?php
	/**
	 * The template for displaying header promo text block
	 *
	 * @since   1.4.0
	 * @version 1.0.1
   	 * last changes in 1.5.4
 	*/
 ?>

<?php 

	global $et_icons;
	
	$et_promo_text_hidden = false;

	if ( get_theme_mod( 'promo_text_close_button_action_et-desktop', 0 ) && isset($_COOKIE['et_promo_text_shows']) && $_COOKIE['et_promo_text_shows'] == 'false') {
		$et_promo_text_hidden = true;
	}

	if ( $et_promo_text_hidden ) return;

    wp_enqueue_script('promo_text_carousel');

	$element_options = array();
	$element_options['promo_text_package'] = get_theme_mod( 'promo_text_package',
            array(
                array(
                    'text' => esc_html__( 'Take 30% off when you spend $120', 'xstore-core' ),
                    'icon'  => 'et_icon-delivery',
                    'icon_position'  => 'before',
                    'link_title' => esc_html__('Go shop', 'xstore-core'),
                    'link' => '#'
                ),
                array(
                    'text' => esc_html__( 'Free 2-days standard shipping on orders $255+', 'xstore-core' ),
                    'icon'  => 'et_icon-coupon',
                    'icon_position'  => 'before',
                    'link_title' => esc_html__( 'Custom link', 'xstore-core' ),
                    'link' => '#'
                ),
            )
	);

	if ( get_theme_mod( 'bold_icons', 0 ) ) {
		$element_options['icons'] = $et_icons['bold'];
	}
	else {
		$element_options['icons'] = $et_icons['light'];
	}

	$element_options['is_customize_preview'] = apply_filters('is_customize_preview', false);

	$element_options['class'] = array();
	$element_options['class'][] = 'header-promo-text';
	$element_options['class'][] = 'et-promo-text-carousel';
	$element_options['class'][] = 'swiper-wrapper';

	$element_options['attributes'] = array();

	if ( $element_options['is_customize_preview'] ) {
		$element_options['attributes'][] = 'data-title="' . esc_html__( 'Promo text', 'xstore-core' ) . '"';
		$element_options['attributes'][] = ' data-element="promo_text"'; 
	}
	

	$element_options['autoplay'] = get_theme_mod( 'promo_text_autoplay_et-desktop', 0 );
	$element_options['speed'] = get_theme_mod( 'promo_text_speed_et-desktop', 3 );
	$element_options['delay'] = get_theme_mod( 'promo_text_delay_et-desktop', 4 );
    $element_options['delay'] = ((int)$element_options['delay'] == 0 ? 0.01 : intval( $element_options['delay'] ));
	$element_options['navigation'] = get_theme_mod( 'promo_text_navigation_et-desktop', 0 );

	$element_options['promo_text_close_button'] = get_theme_mod( 'promo_text_close_button_et-desktop', 1 );
	$element_options['promo_text_close_button_action'] = get_theme_mod( 'promo_text_close_button_action_et-desktop', 0 ) ? true : false;

	$element_options['attributes'][] = 'data-loop="true"';
	$element_options['attributes'][] = 'data-speed="'.esc_js(intval( $element_options['speed'] * 100 )).'"';

	$element_options['attributes'][] = 'data-breakpoints="1"';
	$element_options['attributes'][] = 'data-xs-slides="1"';
	$element_options['attributes'][] = 'data-sm-slides="1"';
	$element_options['attributes'][] = 'data-md-slides="1"';
	$element_options['attributes'][] = 'data-lt-slides="1"';

	$element_options['attributes'][] = 'data-slides-per-view="1"';
	$element_options['attributes'][] = 'data-slides-per-group="1"';
	
    $element_options['attributes'][] = " data-autoplay='" . ( $element_options['autoplay'] ? esc_attr($element_options['delay'] * 1000) : '' ) . "'";

?>

<div class="et_promo_text_carousel swiper-entry pos-relative arrows-hovered <?php echo get_theme_mod('promo_text_navigation_static_et-desktop', false) ? ' arrows-hovered-static' : ''; ?>">
	<div class="swiper-container <?php echo ( $element_options['autoplay'] ) ? 'stop-on-hover' : ''; ?> et_element" <?php echo implode( ' ', $element_options['attributes'] ); ?>>
		<div class="<?php echo esc_attr( implode( ' ', $element_options['class'] ) ); ?>">
			<?php foreach ($element_options['promo_text_package'] as $key ) {
				?>
				<div class="swiper-slide flex justify-content-center align-items-center">
					<?php echo ( $key['icon'] != 'none' && $key['icon_position'] == 'before' ) ? '<span class="et_b-icon">'.$element_options['icons'][$key['icon']].'</span>' : ''; ?>
					<span class="text-nowrap"><?php echo do_shortcode($key['text']); ?></span>
					<?php echo ( $key['icon'] != 'none' && $key['icon_position'] == 'after' ) ? '<span class="et_b-icon">'.$element_options['icons'][$key['icon']].'</span>' : ''; ?>
					<?php if ( !empty($key['link_title']) ) : ?>
						<a class="text-nowrap" href="<?php echo $key['link'] ?>"><?php echo $key['link_title']; ?></a>
					<?php endif; ?>
				</div>
			<?php } ?>
		</div>
		<?php if ( $element_options['navigation'] || $element_options['is_customize_preview'] ) : ?>
			<div class="swiper-custom-left swiper-button-prev mob-hide <?php echo ( $element_options['is_customize_preview'] && !$element_options['navigation']) ? 'dt-hide' : ''; ?>">
			</div>
			<div class="swiper-custom-right swiper-button-next mob-hide <?php echo ( $element_options['is_customize_preview'] && !$element_options['navigation']) ? 'dt-hide' : ''; ?>">
			</div>
		<?php endif; // navigation arrows ?>
		<?php if ( $element_options['promo_text_close_button'] || $element_options['is_customize_preview'] ) : ?>
	    <span class="et-close pos-absolute right top <?php echo ( $element_options['promo_text_close_button_action'] ) ? 'close-forever' : ''; ?> <?php echo ( $element_options['is_customize_preview'] && !$element_options['promo_text_close_button']) ? 'dt-hide' : ''; ?>">
	      <?php echo $element_options['icons']['et_icon-close']; ?>
	    </span>
		<?php endif; ?>
	</div>
</div>

<?php 
	unset($element_options);
?>