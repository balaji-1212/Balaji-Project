<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Slider Item shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Slider_Item extends Shortcodes {

	function hooks() {}

	function slider_item_shortcode( $atts, $content ) {

		if ( xstore_notice() )
			return;

		if ( !is_array($atts) ) $atts = array();

		if( !isset($atts['subtitle_google_fonts']) || empty( $atts['subtitle_google_fonts'] ) ) {
			$atts['subtitle_google_fonts'] = 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal';
		}

		if( !isset($atts['title_google_fonts']) || empty( $atts['title_google_fonts'] ) ) {
			$atts['title_google_fonts'] = 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal';
		}

		$atts = shortcode_atts(array(
			'class'  => '',
			'link'  => '',
			'hover'  => '',
			'title'  => '',
			'subtitle'  => '',
			'subtitle_above' => '',

			// classes 
			'title_class' => '',
			'use_custom_fonts_title' => false,
			'subtitle_class' => '',
			'use_custom_fonts_subtitle' => false,
			'el_class' => '',

			// animations 
			'title_animation_duration' => '500',
			'subtitle_animation_duration' => '500',
			'description_animation_duration' => '500',
			'button_animation_duration' => '500',

			'description_animation' => '',
			'button_animation' => '',

			'title_animation_delay' => '0',
			'subtitle_animation_delay' => '0',
			'description_animation_delay' => '0',
			'button_animation_delay' => '0',
			'content_width' => '',

			// Aligns 
			'align' => 'start',
			'v_align' => 'start',
			'text_align' => '',

			// Title options 
			'size' => '',
			'spacing' => '',
			'line_height' => '',
			'color' => '',

			// title extra options 
			'title_link' => '',
			'title_font_container' => '',
			'title_use_theme_fonts' => false,
			'title_google_fonts' => false,
			'title_css_animation' => '',

			'title_responsive_font_size' => '',
			'title_responsive_line_height' => '',
			'title_responsive_letter_spacing' => '',

			// Subtitle options 
			'subtitle_size' => '',
			'subtitle_spacing' => '',
			'subtitle_line_height' => '',
			'subtitle_color' => '',

			// subtitle extra options 
			'subtitle_link' => '',
			'subtitle_font_container' => '',
			'subtitle_use_theme_fonts' => false,
			'subtitle_google_fonts' => false,
			'subtitle_css_animation' => '',

			'subtitle_responsive_font_size' => '',
			'subtitle_responsive_line_height' => '',
			'subtitle_responsive_letter_spacing' => '',

			'description_class' => '',

			// Button 

			'button_link' => '',
			// 'button_font_size' => '12px',
			'button_responsive_font_size' => '12px',
			'button_border_width' => '1px',
			'button_border_style' => 'none',
			'button_border_color' => '',
			'button_border_color_hover' => '',
			'button_border_radius' => '',
			'button_color' => '',
			'button_hover_color' => '',
			'button_bg' => '',
			'button_hover_bg' => '',
			'button_paddings' => '7px 15px',
			'button_margins' => '',
			'button_class' => '',

			// Background 
			'bg_img' => '',
			'bg_img_backup' => '',
			'background_position' => '',
			'content_bg_position' => '',
			'bg_pos_x' => '50',
			'bg_pos_y' => '50',
			'background_repeat' => '',
			'bg_size' => 'cover',
			'bg_color' => '',
			'bg_overlay' => '',

			'css' => '',
			'is_preview' => false,
			'is_elementor' => false
		), $atts);

		$options = array(
			'description_class' => $atts['description_class'],
			'button_class' => $atts['button_class']
		);

		$options['custom_class'] = $options['img'] = $options['item_style'] = '';

		$options['id'] = rand(1000,9999);

		$options['rs_sizes'] = array(
			'desktop' => '',
			'tablet' => '',
			'mobile' => ''
		);

		// font-sizes
		$options['rs_title_size'] = (array)json_decode(base64_decode($atts['title_responsive_font_size']));
		$options['rs_title_size'] = (isset($options['rs_title_size']['data'])) ? (array)$options['rs_title_size']['data'] : $options['rs_sizes'];

        $options['rs_title_size'] = array_merge($options['rs_sizes'], $options['rs_title_size']);

        // letter-spacing 
		$options['rs_title_letter_spacing'] = (array)json_decode(base64_decode($atts['title_responsive_letter_spacing']));
		$options['rs_title_letter_spacing'] = (isset($options['rs_title_letter_spacing']['data'])) ? (array)$options['rs_title_letter_spacing']['data'] : $options['rs_sizes'];

        $options['rs_title_letter_spacing'] = array_merge($options['rs_sizes'], $options['rs_title_letter_spacing']);

        // line-height
		$options['rs_title_line_height'] = (array)json_decode(base64_decode($atts['title_responsive_line_height']));
		$options['rs_title_line_height'] = (isset($options['rs_title_line_height']['data'])) ? (array)$options['rs_title_line_height']['data'] : $options['rs_sizes'];

        $options['rs_title_line_height'] = array_merge($options['rs_sizes'], $options['rs_title_line_height']);

		// font-sizes
		$options['rs_subtitle_size'] = (array)json_decode(base64_decode($atts['subtitle_responsive_font_size']));
		$options['rs_subtitle_size'] = (isset($options['rs_subtitle_size']['data'])) ? (array)$options['rs_subtitle_size']['data'] : $options['rs_sizes'];

        $options['rs_subtitle_size'] = array_merge($options['rs_sizes'], $options['rs_subtitle_size']);

        // letter-spacing 
		$options['rs_subtitle_letter_spacing'] = (array)json_decode(base64_decode($atts['subtitle_responsive_letter_spacing']));
		$options['rs_subtitle_letter_spacing'] = (isset($options['rs_subtitle_letter_spacing']['data'])) ? (array)$options['rs_subtitle_letter_spacing']['data'] : $options['rs_sizes'];

        $options['rs_subtitle_letter_spacing'] = array_merge($options['rs_sizes'], $options['rs_subtitle_letter_spacing']);
        
        // line-height
		$options['rs_subtitle_line_height'] = (array)json_decode(base64_decode($atts['subtitle_responsive_line_height']));
		$options['rs_subtitle_line_height'] = (isset($options['rs_subtitle_line_height']['data'])) ? (array)$options['rs_subtitle_line_height']['data'] : $options['rs_sizes'];

        $options['rs_subtitle_line_height'] = array_merge($options['rs_sizes'], $options['rs_subtitle_line_height']);

        // button font-sizes
		$options['rs_button_size'] = (array)json_decode(base64_decode($atts['button_responsive_font_size']));
		$options['rs_button_size'] = (isset($options['rs_button_size']['data'])) ? (array)$options['rs_button_size']['data'] : $options['rs_sizes'];

        $options['rs_button_size'] = array_merge($options['rs_sizes'], $options['rs_button_size']);

		// selectors 
		$options['selectors'] = array();

		$options['box_id'] = 'slider-item-' . $options['id'];
		$options['selectors']['item'] = '.'.$options['box_id'];
		$options['selectors']['content'] = $options['selectors']['item'] . ' .slide-content';
		$options['selectors']['overlay'] = $options['selectors']['item'].' .bg-overlay';
		$options['selectors']['description'] = $options['selectors']['item'].' .description';

		$options['selectors']['title'] = $options['selectors']['item'] . ' .slide-title';
		$options['selectors']['subtitle'] = $options['selectors']['item'] . ' .slide-subtitle';
		$options['selectors']['description'] = $options['selectors']['item'] . ' .description';

		$options['selectors']['button'] = $options['selectors']['item'] . ' .slide-button';
		$options['selectors']['button_hover'] = $options['selectors']['button'].':hover';

		$options['align_class'] = (!empty($atts['align'])) ? ' justify-content-'.$atts['align'] : '';
		$options['align_class'] .= (!empty($atts['v_align'])) ? ' align-items-'.$atts['v_align'] : '';

		if ( !empty($atts['background_repeat']) )
			$options['item_style'] .= 'background-repeat:'.$atts['background_repeat'].';';

	    // create css data for selectors
	    $options['data_css'] = array(
	    	$options['selectors']['item'] => array(),
	    	$options['selectors']['content'] => array(),
	    	$options['selectors']['overlay'] => array(),
	    	$options['selectors']['button'] => array(),
	    	$options['selectors']['button_hover'] => array(),

        	'title_desktop' => array(),
        	'title_tablet' => array(),
        	'title_mobile' => array(),

        	'subtitle_desktop' => array(),
        	'subtitle_tablet' => array(),
        	'subtitle_mobile' => array(),

        	'button_tablet' => array(),
        	'button_mobile' => array(),

            'desktop' => array(),
			'tablet' => array(),
			'mobile' => array()
	    );

	    // Elements attr
	    $options['data_attr'] = array(
	    	'item' => array(),
	    	'description' => array(),
	    	'button' => array()
	    );

		if( $atts['bg_img'] > 0 ) 
			$options['img'] = wp_get_attachment_image_src($atts['bg_img'], 'full');

	    // fix compatibility with Elementor 
	    if ( empty($options['img']) && !empty($atts['bg_img_backup']) ) 
	    	$options['img'] = $atts['bg_img_backup'];

	    if ( !empty($options['img']) ) {

			if ( !empty($atts['background_position']) && $atts['background_position'] != 'custom' ) 
				$atts['el_class'] .= ' et-parallax et-parallax-' . $atts['background_position'];

			if ( $atts['background_position'] == 'custom' ) 
				$options['data_css'][$options['selectors']['item']][] = 'background-position:'.$atts['bg_pos_x'].'% '.$atts['bg_pos_y'].'%';
		}

		if ( !empty( $atts['content_width'] ) ) 
			$options['data_css'][$options['selectors']['content']][] = 'flex-basis: '.$atts['content_width'].'%;';

		if ( is_array( $options['img'] ) && count( $options['img'] ) > 0 ) {
			$options['img'] = $options['img'][0];
		}

		if ( !empty($options['img']) ) {
			$options['item_style'] .= ' background-image: url('.$options['img'].');';

			if ( !empty( $atts['bg_size'] ) )
				$options['data_css'][$options['selectors']['item']][] = 'background-size:'.$atts['bg_size'];

		}

		if ( !empty( $atts['bg_color'] ) ) 
			$options['data_css'][$options['selectors']['item']][] = 'background-color:'.$atts['bg_color'];

		if ( !empty( $atts['bg_overlay'] ) )
			$options['data_css'][$options['selectors']['overlay']][] = 'background-color:'.$atts['bg_overlay'];

	   	// title, subtitle styles 

   		// title size
	        if ( $options['rs_title_size']['desktop'] != '' )
	        	$options['data_css']['title_desktop'][] = 'font-size: ' . $options['rs_title_size']['desktop'];

	        if ( $options['rs_title_size']['tablet'] != '' )
	        	$options['data_css']['title_tablet'][] = 'font-size: ' . $options['rs_title_size']['tablet'];

	        if ( $options['rs_title_size']['mobile'] != '' )
	        	$options['data_css']['title_mobile'][] = 'font-size: ' . $options['rs_title_size']['mobile'];

	    // backup of title size 
	        if ( !$atts['is_elementor'] && empty($options['rs_title_size']['desktop']) && empty($options['rs_title_size']['tablet']) && empty($options['rs_title_size']['mobile']) ) {
	        	$options['data_css']['title_tablet'][] = 'font-size: 32px !important';
	        	$options['data_css']['title_mobile'][] = 'font-size: 24px !important';
	        }

        // title line height
	        if ( $options['rs_title_line_height']['desktop'] != '' )
	        	$options['data_css']['title_desktop'][] = 'line-height: ' . $options['rs_title_line_height']['desktop'];

	        if ( $options['rs_title_line_height']['tablet'] != '' )
	        	$options['data_css']['title_tablet'][] = 'line-height: ' . $options['rs_title_line_height']['tablet'];

	        if ( $options['rs_title_line_height']['mobile'] != '' )
	        	$options['data_css']['title_mobile'][] = 'line-height: ' . $options['rs_title_line_height']['mobile'];

        // backup of title line height 
	        if ( !$atts['is_elementor'] && empty($options['rs_title_line_height']['desktop']) && empty($options['rs_title_line_height']['tablet']) && empty($options['rs_title_line_height']['mobile']) ) {
	        	$options['data_css']['title_tablet'][] = 'line-height: 36px !important';
	        	$options['data_css']['title_mobile'][] = 'line-height: 28px !important';
	        }

        // title letter spacing
	        if ( $options['rs_title_letter_spacing']['desktop'] != '' )
	        	$options['data_css']['title_desktop'][] = 'letter-spacing: ' . $options['rs_title_letter_spacing']['desktop'];

	        if ( $options['rs_title_letter_spacing']['tablet'] != '' )
	        	$options['data_css']['title_tablet'][] = 'letter-spacing: ' . $options['rs_title_letter_spacing']['tablet'];

	        if ( $options['rs_title_letter_spacing']['mobile'] != '' )
	        	$options['data_css']['title_mobile'][] = 'letter-spacing: ' . $options['rs_title_letter_spacing']['mobile'];

   		// subtitle size
	        if ( $options['rs_subtitle_size']['desktop'] != '' )
	        	$options['data_css']['subtitle_desktop'][] = 'font-size: ' . $options['rs_subtitle_size']['desktop'];

	        if ( $options['rs_subtitle_size']['tablet'] != '' )
	        	$options['data_css']['subtitle_tablet'][] = 'font-size: ' . $options['rs_subtitle_size']['tablet'];

	        if ( $options['rs_subtitle_size']['mobile'] != '' )
	        	$options['data_css']['subtitle_mobile'][] = 'font-size: ' . $options['rs_subtitle_size']['mobile'];

        // subtitle line height
	        if ( $options['rs_subtitle_line_height']['desktop'] != '' )
	        	$options['data_css']['subtitle_desktop'][] = 'line-height: ' . $options['rs_subtitle_line_height']['desktop'];

	        if ( $options['rs_subtitle_line_height']['tablet'] != '' )
	        	$options['data_css']['subtitle_tablet'][] = 'line-height: ' . $options['rs_subtitle_line_height']['tablet'];

	        if ( $options['rs_subtitle_line_height']['mobile'] != '' )
	        	$options['data_css']['subtitle_mobile'][] = 'line-height: ' . $options['rs_subtitle_line_height']['mobile'];

        // subtitle letter spacing
	        if ( $options['rs_subtitle_letter_spacing']['desktop'] != '' )
	        	$options['data_css']['subtitle_desktop'][] = 'letter-spacing: ' . $options['rs_subtitle_letter_spacing']['desktop'];

	        if ( $options['rs_subtitle_letter_spacing']['tablet'] != '' )
	        	$options['data_css']['subtitle_tablet'][] = 'letter-spacing: ' . $options['rs_subtitle_letter_spacing']['tablet'];

	        if ( $options['rs_subtitle_letter_spacing']['mobile'] != '' )
	        	$options['data_css']['subtitle_mobile'][] = 'letter-spacing: ' . $options['rs_subtitle_letter_spacing']['mobile'];

   		// button size
	        if ( $options['rs_button_size']['desktop'] != '' )
	        	$options['data_css'][$options['selectors']['button']][] = 'font-size: ' . $options['rs_button_size']['desktop'];

	        if ( $options['rs_button_size']['tablet'] != '' )
	        	$options['data_css']['button_tablet'][] = 'font-size: ' . $options['rs_button_size']['tablet'];

	        if ( $options['rs_button_size']['mobile'] != '' )
	        	$options['data_css']['button_mobile'][] = 'font-size: ' . $options['rs_button_size']['mobile'];

		if ( !empty($atts['color']) )
			$atts['title_font_container'] .= '|color:'.$atts['color'];
		// old
		if ( !empty($atts['spacing']) )
			$atts['title_font_container'] .= '|letter_spacing:'.$atts['spacing'];
		if ( !empty($atts['size']) )
			$atts['title_font_container'] .= '|font_size:'.$atts['size'];
		if ( !empty($atts['line_height']) )
			$atts['title_font_container'] .= '|line_height:'.$atts['line_height'];

		if ( !$atts['is_elementor'] ) 
			$atts['title_font_container'] .= '|animation_duration:'.$atts['title_animation_duration'].'ms|animation_delay:'.$atts['title_animation_delay'].'ms';

		if ( !empty( $atts['subtitle_color'] ) )
			$atts['subtitle_font_container'] .= '|color:'.$atts['subtitle_color'];

		// old
		if ( !empty( $atts['subtitle_spacing'] ) )
			$atts['subtitle_font_container'] .= '|letter_spacing:'.$atts['subtitle_spacing'];
		if ( !empty( $atts['subtitle_size'] ) )
			$atts['subtitle_font_container'] .= '|font_size:'.$atts['subtitle_size'];
		if ( !empty( $atts['subtitle_line_height']) )
			$atts['subtitle_font_container'] .= '|line_height:'.$atts['subtitle_line_height'];

		if ( !$atts['is_elementor'] ) 
			$atts['subtitle_font_container'] .= '|animation_duration:'.$atts['subtitle_animation_duration'].'ms|animation_delay:'.$atts['subtitle_animation_delay'].'ms';

		if ( !empty( $atts['button_color'] ) )
			$options['data_css'][$options['selectors']['button']][] = 'color:'.$atts['button_color'];

		if ( !empty( $atts['button_bg'] ) )
			$options['data_css'][$options['selectors']['button']][] = 'background-color:'.$atts['button_bg'];

		if ( !empty( $atts['button_paddings'] ) )
			$options['data_css'][$options['selectors']['button']][] = 'padding:'.$atts['button_paddings'];

		if ( !empty( $atts['button_margins'] ) )
			$options['data_css'][$options['selectors']['button']][] = 'margin:'.$atts['button_margins'];

		// old
		if ( !empty($atts['button_font_size']) )
			$options['data_css'][$options['selectors']['button']][] = 'font-size:'.$atts['button_font_size'];

		if ( !empty($atts['button_border_radius']) )
			$options['data_css'][$options['selectors']['button']][] = 'border-radius: ' . $atts['button_border_radius'];

		if ( !empty($atts['button_border_width']) )
			$options['data_css'][$options['selectors']['button']][] = 'border-width: ' . $atts['button_border_width'];

		if ( !empty($atts['button_border_style']) )
			$options['data_css'][$options['selectors']['button']][] = 'border-style: ' . $atts['button_border_style'];

		if ( !empty($atts['button_border_color']) )
			$options['data_css'][$options['selectors']['button']][] = 'border-color: ' . $atts['button_border_color'];

		if ( !empty($atts['button_hover_bg'] ) )
			$options['data_css'][$options['selectors']['button_hover']][] = 'background-color:'.$atts['button_hover_bg'];

		if ( !empty($atts['button_hover_color'] ) )
			$options['data_css'][$options['selectors']['button_hover']][] = 'color:'.$atts['button_hover_color'];

		if ( !empty($atts['button_border_color_hover']) )
			$options['data_css'][$options['selectors']['button_hover']][] = 'border-color: ' . $atts['button_border_color_hover'];

		// create output css 
	    $options['output_data_css'] = array();

		if ( count($options['data_css'][$options['selectors']['item']]) )
			$options['output_data_css'][] = $options['selectors']['item'] . '{'.implode(';', $options['data_css'][$options['selectors']['item']]).'}';
		if ( count($options['data_css'][$options['selectors']['content']]) )
			$options['output_data_css'][] = $options['selectors']['content'] . '{'.implode(';', $options['data_css'][$options['selectors']['content']]).'}';

		if ( count($options['data_css'][$options['selectors']['overlay']]) )
			$options['output_data_css'][] = $options['selectors']['overlay'] . '{'.implode(';', $options['data_css'][$options['selectors']['overlay']]).'}';

		if ( count($options['data_css'][$options['selectors']['button']]) )
			$options['output_data_css'][] = $options['selectors']['button'] . '{'.implode(';', $options['data_css'][$options['selectors']['button']]).'}';

		if ( count($options['data_css'][$options['selectors']['button_hover']]) )
			$options['output_data_css'][] = $options['selectors']['button_hover'] . '{'.implode(';', $options['data_css'][$options['selectors']['button_hover']]).'}';

        // desktop css output
        if ( count($options['data_css']['title_desktop']) ) 
        	$options['data_css']['desktop'][] = $options['selectors']['title'] . '{' . implode(';', $options['data_css']['title_desktop']) . '}';

        if ( count($options['data_css']['subtitle_desktop']) ) 
        	$options['data_css']['desktop'][] = $options['selectors']['subtitle'] . '{' . implode(';', $options['data_css']['subtitle_desktop']) . '}';

        // tablet css output
    	if ( count($options['data_css']['title_tablet']) )
        	$options['data_css']['tablet'][] = $options['selectors']['title'] . '{' . implode(';', $options['data_css']['title_tablet']) . '}';

        if ( count($options['data_css']['subtitle_tablet']) )
        	$options['data_css']['tablet'][] = $options['selectors']['subtitle'] . '{' . implode(';', $options['data_css']['subtitle_tablet']) . '}';

 	   if ( count($options['data_css']['button_tablet']) )
        	$options['data_css']['tablet'][] = $options['selectors']['button'] . '{' . implode(';', $options['data_css']['button_tablet']) . '}';

        // mobile css output
        if ( count($options['data_css']['title_mobile']) )
        	$options['data_css']['mobile'][] = $options['selectors']['title'] . '{' . implode(';', $options['data_css']['title_mobile']) . '}';

        if ( count($options['data_css']['subtitle_mobile']) )
        	$options['data_css']['mobile'][] = $options['selectors']['subtitle'] . '{' . implode(';', $options['data_css']['subtitle_mobile']) . '}';

   		if ( count($options['data_css']['button_mobile']) )
        	$options['data_css']['mobile'][] = $options['selectors']['button'] . '{' . implode(';', $options['data_css']['button_mobile']) . '}';

        $options['output_data_css'] = array_merge($options['output_data_css'], $options['data_css']['desktop']);

		if ( !empty($atts['text_align']) )
			$options['custom_class'] .= 'text-'.$atts['text_align'];

		if ( !empty($atts['content_bg_position']) )
			$options['custom_class'] .= ' et-parallax et-parallax-' . $atts['content_bg_position'];

		if( ! empty($atts['css']) && function_exists( 'vc_shortcode_custom_css_class' ))
			$options['custom_class'] .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );

		// button 

		if ( !empty( $atts['button_animation'] ) && !$atts['is_elementor'] ) {
			$options['button_class'] .= ' animated ' . $options['button_class'] . ' ' . $atts['button_animation'];
			$options['data_attr']['button'][] = 'style="animation-duration:'.$atts['button_animation_duration'].'ms;animation-delay:'.$atts['button_animation_delay'].'ms;"';
		}

	    // Button link 
		$atts['button_link'] = ( '||' === $atts['button_link'] ) ? '' : $atts['button_link'];

		$options['use_link'] = false;

		if ( $atts['is_elementor'] ) {

			$atts['button_link'] = explode('|', $atts['button_link']);

			$atts['button_link'] = array_combine(
				explode(',', $atts['button_link'][0]),
				explode(',', $atts['button_link'][1])
			);

			if ( strlen( $atts['button_link']['url'] ) > 0 ) {
				$options['use_link'] = true;
				$options['a_href'] = $atts['button_link']['url'];
				$options['a_target'] = $atts['button_link']['is_external'] ? '_blank' : '_self';

				$options['button_class'] .= ' cursor-pointer';
				if( strpos( $options['a_target'], 'blank' ) )
					$options['button_onclick'] = 'onclick="window.open(\''. esc_url( $options['a_href'] ).'\',\'_blank\')"';
				else 
					$options['button_onclick'] = 'onclick="window.location=\''. esc_url( $options['a_href'] ).'\'"';

				$options['data_attr']['button'][] = $options['button_onclick'];

			}
		}
		else {
			// Vc build link
			if ( function_exists( 'vc_build_link' ) ) 
				$atts['button_link'] = vc_build_link( $atts['button_link'] );

			if ( is_array($atts['button_link']) && strlen( $atts['button_link']['url'] ) > 0 ) {
				$options['use_link'] = true;
				$options['a_href'] = $atts['button_link']['url'];
				$options['a_target'] = strlen( $atts['button_link']['target'] ) > 0 ? $atts['button_link']['target'] : '_self';
				$options['button_class'] .= ' cursor-pointer';
				if( strpos( $options['a_target'], 'blank' ) )
					$options['button_onclick'] = 'onclick="window.open(\''. esc_url( $options['a_href'] ).'\',\'_blank\')"';
				else 
					$options['button_onclick'] = 'onclick="window.location=\''. esc_url( $options['a_href'] ).'\'"';

				$options['data_attr']['button'][] = $options['button_onclick'];
			}
		}

		$options['data_attr']['button'][] = 'class="slide-button '.$options['button_class'].'"';

		// end button options 

		// item options 

		if( $atts['link'] && $options['use_link'] ) {
			$atts['el_class'] .= ' cursor-pointer';
			if( strpos( $options['a_target'], 'blank' ) ) 
				$options['data_attr']['item'][] = 'onclick="window.open(\''. esc_url( $options['a_href'] ).'\',\'_blank\')"';
			else 
				$options['data_attr']['item'][] = 'onclick="window.location=\''. esc_url( $options['a_href'] ).'\'"';
		}

		$options['data_attr']['item'][] = 'class="slider-item '. (!$atts['is_preview'] ? ' fadeIn-slide ' : '') . $options['box_id'].' '. esc_attr( $atts['el_class'] ). '"';
		$options['data_attr']['item'][] = 'data-slide-id="'.$options['id'].'"';

		if ( $options['item_style'] != '' ) 
			$options['data_attr']['item'][] = 'style="'.$options['item_style'].'"';

		// end item options 

		// description options 

		if ( !empty( $atts['description_animation'] ) && !$atts['is_elementor'] ) {
			$options['description_class'] .= ' animated wpb_animate_when_almost_visible ' . $atts['description_animation'];
			$options['data_attr']['description'][] = 'style="animation-duration:'.$atts['description_animation_duration'].'ms;animation-delay:'.$atts['description_animation_delay'].'ms;"';
		}

		$options['data_attr']['description'][] = 'class="description ' . $options['description_class'] . '"';

		ob_start();

		?>
		<div <?php echo implode(' ', $options['data_attr']['item']); ?>>

			<?php if ( !empty($atts['bg_overlay'] ) ) { ?>
				<div class="bg-overlay"></div>
			<?php } ?>
			
			<div class="container <?php echo esc_attr($options['align_class']); ?>">

				<div class="slide-content <?php echo esc_attr($options['custom_class']); ?>">

					<?php if ( ! empty( $atts['title']) && $atts['subtitle_above'] ) {
						if ( class_exists( 'Vc_Manager' ) && !$atts['is_elementor'] ) 
							echo parent::getHeading('subtitle', $atts, 'slide-subtitle no-uppercase '. $atts['subtitle_class']);
						else 
							echo '<h2 class="slide-subtitle ' . $atts['subtitle_class'] . '">' . esc_html( $atts['subtitle'] ) . '</h2>';
					}

					if( ! empty( $atts['title'] ) ) {
						if ( class_exists( 'Vc_Manager' ) && !$atts['is_elementor'] ) 
							echo parent::getHeading('title', $atts, 'slide-title no-uppercase '. $atts['title_class']);
						else 
							echo '<h2 class="slide-title ' . $atts['title_class'] . '">' . esc_html( $atts['title'] ) . '</h2>';
					}

					if ( ! empty( $atts['title']) && !$atts['subtitle_above'] ) {
						if ( class_exists( 'Vc_Manager' ) && !$atts['is_elementor'] ) 
							echo parent::getHeading('subtitle', $atts, 'slide-subtitle no-uppercase '. $atts['subtitle_class']);
						else 
							echo '<h2 class="slide-subtitle ' . $atts['subtitle_class'] . '">' . esc_html( $atts['subtitle'] ) . '</h2>';
					}
					?>

					<div <?php echo implode(' ', $options['data_attr']['description']); ?>>
						<?php echo do_shortcode($content); ?>		
					</div>
		
					<?php 
					if ( strlen( $atts['button_link']['title'] ) > 0 ) { ?>
						<div <?php echo implode(' ', $options['data_attr']['button']); ?>>
							<?php echo esc_html($atts['button_link']['title']); ?>
						</div>
					<?php } ?>

				</div> <?php // .slide-content ?>

			</div> <?php // .container ?>

	   	</div> <?php // .slide ?>
	    
	    <?php 

		if ( $atts['is_preview'] ) {
			echo parent::initPreviewCss($options['output_data_css'], $options['data_css']['tablet'], $options['data_css']['mobile']);
			echo parent::initPreviewJs();
		}
		else {
			parent::initCss($options['output_data_css'], $options['data_css']['tablet'], $options['data_css']['mobile']);
		}

	    unset($options);
	    unset($atts);

	    return ob_get_clean();
	}	
}
