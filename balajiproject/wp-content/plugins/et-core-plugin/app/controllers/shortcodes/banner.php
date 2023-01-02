<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Banner shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
class Banner extends Shortcodes {

	public function hooks() {}

	public static function banner_shortcode( $atts, $content ) {

		if ( xstore_notice() )
			return;

		if ( !is_array( $atts ) ) 
			$atts = array();

		if( !isset($atts['subtitle_google_fonts']) || empty( $atts['subtitle_google_fonts'] ) ) {
			$atts['subtitle_google_fonts'] = 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal';
		}

		if( !isset($atts['title_google_fonts']) || empty( $atts['title_google_fonts'] ) ) {
			$atts['title_google_fonts'] = 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal';
		}

	    $atts = shortcode_atts(array(
	        'align'  => 'left',
	        'valign'  => 'top',
	        'class'  => '',
	        'link'  => '',
	        // 'hover'  => '',
            
            'content_on_hover' => false,
		    'subtitle_html_tag' => 'h2', // elementor only
            'title_html_tag' => 'h2', // elementor only
			'title'  => '',
			'title_link' => '',
			'title_font_container' => '',
			'title_google_fonts' => false,
			'use_custom_fonts_title' => false,
			'title_use_theme_fonts' => false,
			'use_theme_fonts_title' => '',
			'title_css_animation' => '',
			'title_class' => '',

			'title_responsive_font_size' => '',
			'title_responsive_line_height' => '',
			
			'subtitle'  => '',
			'subtitle_link' => '',
			'subtitle_font_container' => '',
			'subtitle_google_fonts' => false,
			'use_custom_fonts_subtitle' => false,
			'subtitle_use_theme_fonts' => '',
			'subtitle_css_animation' => '',
			'subtitle_class' => '',

			'subtitle_responsive_font_size' => '',
			'subtitle_responsive_line_height' => '',

			'font_style'  => 'dark',
	        'type'  => 2,
	        'type_with_diagonal' => '',
	        'type_with_border' => '',
	        'text_effect' => '',
	        'is_active' => '',
	        'hide_title_responsive' => '',
	        'hide_subtitle_responsive' => '',
	        'hide_description_responsive' => '',
	        'hide_button_responsive' => '',
	        'img' => '',
	        'img_backup' => '',
			'responsive_fonts' => 1, 
	        'banner_color_bg' => 'transparent',
	        'img_size' => '270x170',
	        'img_min_size' => '',
			'image_opacity' => '1',
			'image_opacity_on_hover' => '1',
			'button_link' => '',
			'button_state' => false,
			'button_color' => '',
			'button_hover_color' => '',
			'button_bg' => '',
			'button_hover_bg' => '',
			'button_font_size' => '',
			'button_border_radius' => '',
			'button_paddings' => '',
			'button_only_on_hover' => '',
			'css' => '',
			'ajax' => false,
	        'ajax_loaded' => false,

			// extra settings
			'is_preview' => isset($_GET['vc_editable']),
			'is_elementor' => false,
            'prevent_load_inline_style' => false
	    ), $atts);

	    $atts['is_preview'] = apply_filters('etheme_output_shortcodes_inline_css', $atts['is_preview']);

	    // fix backward compatibility from v.2.1.1 
	    if ( $atts['type'] == 1 ) {
	    	$atts['type'] = 2;
	    	$atts['type_with_diagonal'] = 'true';
	    }

	    $options = array();

		$options['rs_sizes'] = array(
			'desktop' => '',
			'tablet' => '',
			'mobile' => ''
		);

		$options['rs_title_size'] = (array)json_decode(base64_decode($atts['title_responsive_font_size']));
		$options['rs_title_size'] = (isset($options['rs_title_size']['data'])) ? (array)$options['rs_title_size']['data'] : $options['rs_sizes'];

        $options['rs_title_size'] = array_merge($options['rs_sizes'], $options['rs_title_size']);

		$options['rs_title_line_height'] = (array)json_decode(base64_decode($atts['title_responsive_line_height']));
		$options['rs_title_line_height'] = (isset($options['rs_title_line_height']['data'])) ? (array)$options['rs_title_line_height']['data'] : $options['rs_sizes'];

        $options['rs_title_line_height'] = array_merge($options['rs_sizes'], $options['rs_title_line_height']);

		$options['rs_subtitle_size'] = (array)json_decode(base64_decode($atts['subtitle_responsive_font_size']));
		$options['rs_subtitle_size'] = (isset($options['rs_subtitle_size']['data'])) ? (array)$options['rs_subtitle_size']['data'] : $options['rs_sizes'];

        $options['rs_subtitle_size'] = array_merge($options['rs_sizes'], $options['rs_subtitle_size']);
        
		$options['rs_subtitle_line_height'] = (array)json_decode(base64_decode($atts['subtitle_responsive_line_height']));
		$options['rs_subtitle_line_height'] = (isset($options['rs_subtitle_line_height']['data'])) ? (array)$options['rs_subtitle_line_height']['data'] : $options['rs_sizes'];

        $options['rs_subtitle_line_height'] = array_merge($options['rs_sizes'], $options['rs_subtitle_line_height']);

        // fix backward compatibility from v.2.2.2
        if ( $options['rs_title_size']['tablet'] != '' || $options['rs_title_size']['mobile'] != '' || $options['rs_subtitle_size']['tablet'] != '' || $options['rs_subtitle_size']['mobile'] != '' )
        	$atts['responsive_fonts'] = false;
        
        $options['lazy_load_element'] = ! $atts['is_preview'] && $atts['ajax'];

		if ( $options['lazy_load_element'] ) {
            if ( !$atts['prevent_load_inline_style'] && function_exists( 'etheme_enqueue_style' ) ) {
                etheme_enqueue_style( 'banner' );
                if ( $atts['type'] != '')
                    etheme_enqueue_style( 'banners-global' );
            }
			return et_ajax_element_holder( 'banner', $atts, '', $content );
		}

   		$options['image'] = $options['custom_class'] = $options['onclick'] = '';

	    $options['id'] = rand(1000,9999);

		if ( ! in_array( $atts['img_size'], array(  'thumbnail', 'medium', 'large', 'full' ) ) ) 
			$options['size'] = explode( 'x', $atts['img_size'] );
		else 
			$options['size'] = $atts['img_size'];

		$options['image'] = function_exists('etheme_get_image') ? etheme_get_image($atts['img'], $options['size']) : '';

	    // fix compatibility with Elementor 
	    if ( empty($options['image']) && !empty($atts['img_backup']) ) 
	    	$options['image'] = $atts['img_backup'];

		//parse link
		$options['use_link'] = false;
		if ( $atts['is_elementor'] ) {
			if ( strlen( $atts['link']['url'] ) > 0 ) {
				$options['use_link'] = true;
				$options['a_href'] = $atts['link']['url'];
				$options['a_target'] = $atts['link']['is_external'] ? '_blank' : '_self';
			}
		}
		else {
			if ( function_exists('vc_build_link') ) {
				$atts['link'] = ( '||' === $atts['link'] ) ? '' : $atts['link'];
				$atts['link'] = vc_build_link( $atts['link'] );
				if ( strlen( $atts['link']['url'] ) > 0 ) {
					$options['use_link'] = true;
					$options['a_href'] = $atts['link']['url'];
					$options['a_target'] = strlen( $atts['link']['target'] ) > 0 ? $atts['link']['target'] : '_self';
				}
			}
		}

	    if( $options['use_link'] ) {
	        $atts['class'] .= ' cursor-pointer';
	        if ( !$atts['is_preview'] ) {
		        if ( strpos( $options['a_target'], 'blank' ) ) {
			        $options['onclick'] = 'onclick="window.open(\'' . esc_url( $options['a_href'] ) . '\',\'_blank\')"';
		        } else {
			        $options['onclick'] = 'onclick="window.location=\'' . esc_url( $options['a_href'] ) . '\'"';
		        }
	        }
	    }

	    $options['button_attr'] = array();
	    $options['button_class'] = '';

	    // Button link 
		$atts['button_link'] = ( '||' === $atts['button_link'] ) ? '' : $atts['button_link'];

		if ( $atts['is_elementor'] ) {
			if ( is_array($atts['button_link']) && strlen( $atts['button_link']['url'] ) > 0 ) {
				$options['a_href'] = $atts['button_link']['url'];
				$options['a_target'] = $atts['button_link']['is_external'] ? '_blank' : '_self';
				$options['button_class'] .= ' cursor-pointer';
				if ( !$atts['is_preview'] ) {
					if ( strpos( $options['a_target'], 'blank' ) ) {
						$options['button_onclick'] = 'onclick="window.open(\'' . esc_url( $options['a_href'] ) . '\',\'_blank\')"';
					} else {
						$options['button_onclick'] = 'onclick="window.location=\'' . esc_url( $options['a_href'] ) . '\'"';
					}
					
					$options['button_attr'][] = $options['button_onclick'];
				}
			}
			
			if ( $atts['content_on_hover'] )
			    $atts['class'] .= ' content-effect-on-hover';
		}
		else {
			// Vc build link
			if ( function_exists( 'vc_build_link' ) ) 
				$atts['button_link'] = vc_build_link( $atts['button_link'] );

			if ( is_array($atts['button_link']) && strlen( $atts['button_link']['url'] ) > 0 ) {
				$options['a_href'] = $atts['button_link']['url'];
				$options['a_target'] = strlen( $atts['button_link']['target'] ) > 0 ? $atts['button_link']['target'] : '_self';
				$options['button_class'] .= ' cursor-pointer';
				if( strpos( $options['a_target'], 'blank' ) )
					$options['button_onclick'] = 'onclick="window.open(\''. esc_url( $options['a_href'] ).'\',\'_blank\')"';
				else 
					$options['button_onclick'] = 'onclick="window.location=\''. esc_url( $options['a_href'] ).'\'"';

				$options['button_attr'][] = $options['button_onclick'];
			}
		}

		$options['button_attr'][] = 'class="banner-button btn medium inline-block '.$options['button_class'].'"';


		// selectors 
        $options['selectors']['banner'] = '#banner-' . $options['id'];
        $options['selectors']['img'] = $options['selectors']['banner'] . ' img';
        $options['selectors']['img_hover'] = $options['selectors']['banner'] . ':hover img';
        $options['selectors']['title'] = $options['selectors']['banner'] . ' .banner-title';
        $options['selectors']['subtitle'] = $options['selectors']['banner'] . ' .banner-subtitle';
        $options['selectors']['content'] = $options['selectors']['banner'] . ' .banner-content';
        $options['selectors']['content_inner'] = $options['selectors']['banner'] . ' .content-inner';
		$options['selectors']['button'] =  $options['selectors']['banner'] . ' .banner-button';
		$options['selectors']['button_hover'] = $options['selectors']['button'].':hover';

        // create css data for selectors
        $options['css'] = array(
            $options['selectors']['banner'] => array(),
            $options['selectors']['img'] => array(),
            $options['selectors']['img_hover'] => array(),
            $options['selectors']['button'] => array(),
	    	$options['selectors']['button_hover'] => array(),
        	
        	// responsive
   			'title_desktop' => array(),
			'subtitle_desktop' => array(),

   			'title_tablet' => array(),
			'subtitle_tablet' => array(),
			'content_inner_tablet' => array(),
			'button_tablet' => array(),
			'content_tablet' => array(),

   			'title_mobile' => array(),
			'subtitle_mobile' => array(),

			'desktop' => array(),
			'tablet' => array(),
			'mobile' => array()
        );

   		$options['css'][$options['selectors']['banner']][] = 'background-color:'.$atts['banner_color_bg'];
   		$options['css'][$options['selectors']['img']][] = 'opacity:'.$atts['image_opacity'];
   		
   		if ( $atts['img_min_size'] ) {
   			$options['css'][$options['selectors']['img']][] = 'min-height: ' . $atts['img_min_size'];
   			$options['css'][$options['selectors']['img']][] = 'object-fit: cover';
   		}

   		$options['css'][$options['selectors']['img_hover']][] = 'opacity: ' . $atts['image_opacity_on_hover'];

   		// hide elements on responsive if needed 

   		if ( $atts['hide_title_responsive'] != '' && $atts['hide_subtitle_responsive'] != '' && $atts['hide_description_responsive'] != '' && ( $atts['hide_button_responsive'] != '' || strlen( $atts['button_link']['title'] ) < 1 ) )
   			$options['css']['content_tablet'][] = 'display: none !important';
   		else {

			if ( $atts['hide_title_responsive'] != '' ) 
	   			$options['css']['title_tablet'][] = 'display: none !important';

	   		if ( $atts['hide_subtitle_responsive'] != '' ) 
	   			$options['css']['subtitle_tablet'][] = 'display: none !important';

	   		if ( $atts['hide_description_responsive'] != '' ) 
	   			$options['css']['content_inner_tablet'][] = 'display: none !important';

	   		if ( $atts['hide_button_responsive'] != '' ) 
	   			$options['css']['button_tablet'][] = 'display: none !important';

	   	}

   		// title size
        if ( $options['rs_title_size']['desktop'] != '' )
        	$options['css']['title_desktop'][] = 'font-size: ' . $options['rs_title_size']['desktop'];

        if ( $options['rs_title_size']['tablet'] != '' )
        	$options['css']['title_tablet'][] = 'font-size: ' . $options['rs_title_size']['tablet'];

        if ( $options['rs_title_size']['mobile'] != '' )
        	$options['css']['title_mobile'][] = 'font-size: ' . $options['rs_title_size']['mobile'];

        // title line height
        if ( $options['rs_title_line_height']['desktop'] != '' )
        	$options['css']['title_desktop'][] = 'line-height: ' . $options['rs_title_line_height']['desktop'];

        if ( $options['rs_title_line_height']['tablet'] != '' )
        	$options['css']['title_tablet'][] = 'line-height: ' . $options['rs_title_line_height']['tablet'];

        if ( $options['rs_title_line_height']['mobile'] != '' )
        	$options['css']['title_mobile'][] = 'line-height: ' . $options['rs_title_line_height']['mobile'];

        // subtitle size
        if ( $options['rs_subtitle_size']['desktop'] != '' )
        	$options['css']['subtitle_desktop'][] = 'font-size: ' . $options['rs_subtitle_size']['desktop'];

        if ( $options['rs_subtitle_size']['tablet'] != '' )
        	$options['css']['subtitle_tablet'][] = 'font-size: ' . $options['rs_subtitle_size']['tablet'];

        if ( $options['rs_subtitle_size']['mobile'] != '' )
        	$options['css']['subtitle_mobile'][] = 'font-size: ' . $options['rs_subtitle_size']['mobile'];

        // title line height
        if ( $options['rs_subtitle_line_height']['desktop'] != '' )
        	$options['css']['subtitle_desktop'][] = 'line-height: ' . $options['rs_subtitle_line_height']['desktop'];

        if ( $options['rs_subtitle_line_height']['tablet'] != '' )
        	$options['css']['subtitle_tablet'][] = 'line-height: ' . $options['rs_subtitle_line_height']['tablet'];

        if ( $options['rs_subtitle_line_height']['mobile'] != '' )
        	$options['css']['subtitle_mobile'][] = 'line-height: ' . $options['rs_subtitle_line_height']['mobile'];

	   	// button styles 
//		$options['css'][$options['selectors']['button']][] = 'height: auto';

	   	if ( $atts['button_color'] != '' )
			$options['css'][$options['selectors']['button']][] = 'color:'.$atts['button_color'];

		if ( $atts['button_bg'] != '' )
			$options['css'][$options['selectors']['button']][] = 'background-color:'.$atts['button_bg'];

		if ( $atts['button_paddings'] != '' )
			$options['css'][$options['selectors']['button']][] = 'padding:'.$atts['button_paddings'];

		if ( $atts['button_font_size'] != '' )
			$options['css'][$options['selectors']['button']][] = 'font-size:'.$atts['button_font_size'] . 'px';

		if ( $atts['button_border_radius'] != '' )
			$options['css'][$options['selectors']['button']][] = 'border-radius: ' . $atts['button_border_radius'] . ' !important';

		if ( $atts['button_hover_bg'] != '' )
			$options['css'][$options['selectors']['button_hover']][] = 'background-color:'.$atts['button_hover_bg'];

		if ( $atts['button_hover_color'] != '' )
			$options['css'][$options['selectors']['button_hover']][] = 'color:'.$atts['button_hover_color'];

		// create output css 
        $options['output_css'] = $options['output_desktop_css'] = $options['output_tablet_css'] = $options['output_mobile_css'] = array();

        if ( count( $options['css'][$options['selectors']['banner']] ) )
            $options['output_css'][] = $options['selectors']['banner'] . '{'.implode(';', $options['css'][$options['selectors']['banner']]).'}';

        if ( count( $options['css'][$options['selectors']['img']] ) )
    		$options['output_css'][] = $options['selectors']['img'] . '{'.implode(';', $options['css'][$options['selectors']['img']]).'}';

        if ( count( $options['css'][$options['selectors']['img_hover']] ) )
    		$options['output_css'][] = $options['selectors']['img_hover'] . '{'.implode(';', $options['css'][$options['selectors']['img_hover']]).'}';

        if ( count( $options['css'][$options['selectors']['button']] ) )
			$options['output_css'][] = $options['selectors']['button'] . '{'.implode(';', $options['css'][$options['selectors']['button']]).'}';

        if ( count( $options['css'][$options['selectors']['button_hover']] ) )
    		$options['output_css'][] = $options['selectors']['button_hover'] . '{'.implode(';', $options['css'][$options['selectors']['button_hover']]).'}';

        // desktop css output
        if ( count($options['css']['title_desktop']) ) 
        	$options['css']['desktop'][] = $options['selectors']['title'] . '{' . implode(';', $options['css']['title_desktop']) . '}';

        if ( count($options['css']['subtitle_desktop']) ) 
        	$options['css']['desktop'][] = $options['selectors']['subtitle'] . '{' . implode(';', $options['css']['subtitle_desktop']) . '}';

    	// tablet css output 
	    if ( count( $options['css']['title_tablet'] ) )
			$options['css']['tablet'][] = $options['selectors']['title'] . '{'.implode(';', $options['css']['title_tablet']).'}';

        if ( count( $options['css']['subtitle_tablet'] ) )
    		$options['css']['tablet'][] = $options['selectors']['subtitle'] . '{'.implode(';', $options['css']['subtitle_tablet']).'}';

        if ( count( $options['css']['content_inner_tablet'] ) )
    		$options['css']['tablet'][] = $options['selectors']['content_inner'] . '{'.implode(';', $options['css']['content_inner_tablet']).'}';

	    if ( count( $options['css']['button_tablet'] ) )
			$options['css']['tablet'][] = $options['selectors']['button'] . '{'.implode(';', $options['css']['button_tablet']).'}';

  	  	if ( count( $options['css']['content_tablet'] ) )
    		$options['css']['tablet'][] = $options['selectors']['content'] . '{'.implode(';', $options['css']['content_tablet']).'}';

        // mobile css output
        if ( count($options['css']['title_mobile']) )
        	$options['css']['mobile'][] = $options['selectors']['title'] . '{' . implode(';', $options['css']['title_mobile']) . '}';

        if ( count($options['css']['subtitle_mobile']) )
        	$options['css']['mobile'][] = $options['selectors']['subtitle'] . '{' . implode(';', $options['css']['subtitle_mobile']) . '}';

        $options['output_css'] = array_merge( $options['output_css'], $options['css']['desktop'] );

	    if ($atts['type'] != '') 
	      	$atts['class'] .= ' banner-type-'.$atts['type'] . ' et_image-with-hover et_image-hover-'.$atts['type'];

	    if ($atts['type_with_diagonal'] != '')
	    	$atts['class'] .= ' with-diagonal';

	    if ($atts['type_with_border'] != '')
	    	$atts['class'] .= ' with-border';

	    if ($atts['is_active'] != '' )
	    	$atts['class'] .= ' active';

	    if ($atts['text_effect'] != '')
	    	$atts['class'] .= ' text-effect-'.$atts['text_effect'];

		if ($atts['align'] != '') 
			$atts['class'] .= ' text-'.$atts['align'];

		if ($atts['responsive_fonts'] == 1) 
			$atts['class'] .= ' responsive-fonts';

	    if ($atts['valign'] != '') 
	      	$atts['class'] .= ' valign-'.$atts['valign'];

	    if ($atts['font_style'] != '') 
	      	$atts['class'] .= ' font-style-'.$atts['font_style'];

		if( empty( $atts['subtitle'] ) && empty( $atts['title'] ) ) 
			$atts['class'] .= ' no-titles';

		if ($atts['button_state'] != '' )
			$atts['class'] .= ' button-on-hover';

		if( ! empty($atts['css']) && function_exists( 'vc_shortcode_custom_css_class' )) 
			$options['custom_class'] = ' ' . vc_shortcode_custom_css_class( $atts['css'] );
		
		ob_start();
		
		if ( !$options['lazy_load_element'] && !$atts['ajax_loaded'] && !$atts['prevent_load_inline_style']) {
			if ( function_exists( 'etheme_enqueue_style' ) ) {
				etheme_enqueue_style( 'banner', true );
				if ( $atts['type'] != '' ) {
					etheme_enqueue_style( 'banners-global', true );
				}
			}
		}
		
		?>
	    <div id="<?php echo 'banner-' . $options['id']; ?>" class="banner <?php esc_attr_e( $atts['class'] ); ?>" <?php echo $options['onclick']; ?>>
    		<?php echo $options['image']; ?>
	    	<div class="banner-content <?php esc_attr_e( $options['custom_class'] ); ?>">

				<?php 
					if( ! empty( $atts['subtitle'] ) ) {
						if ( class_exists( 'Vc_Manager' ) && !$atts['is_elementor'] ) 
							echo parent::getHeading('subtitle', $atts, 'banner-subtitle '. $atts['subtitle_class']);
						else 
							echo '<'.$atts['subtitle_html_tag'].' class="banner-subtitle ' . $atts['subtitle_class'] . '"><span>' . esc_html( $atts['subtitle'] ) . '</span></'.$atts['subtitle_html_tag'].'>';
					}

					if( ! empty( $atts['title'] ) ) {
						if ( class_exists( 'Vc_Manager' ) && !$atts['is_elementor'] ) 
							echo parent::getHeading('title', $atts, 'banner-title '. $atts['title_class']);
						else 
							echo '<'.$atts['title_html_tag'].' class="banner-title ' . $atts['title_class'] . '"><span>' . esc_html( $atts['title'] ) . '</span></'.$atts['title_html_tag'].'>';
					}
				?>
		    	<div class="content-inner">

		    		<?php echo do_shortcode($content); ?>

		    	</div>
		    	<?php 
					if ( isset($atts['button_link']['title']) && strlen( $atts['button_link']['title'] ) > 0 ) { ?>
						<div class="button-wrap">
							<div <?php echo implode(' ', $options['button_attr']); ?>>
								<?php echo esc_html($atts['button_link']['title']); ?>
							</div>
						</div>
				<?php } ?>
	    	</div>
	  	</div>
	
		<?php 

		if ( $atts['is_preview'] ) {
			echo parent::initPreviewCss($options['output_css'], $options['css']['tablet'], $options['css']['mobile']);
			echo parent::initPreviewJs();
		}
		else {
			parent::initCss($options['output_css'], $options['css']['tablet'], $options['css']['mobile']);
		}

	   	unset($options);
	   	unset($atts);

	    return ob_get_clean();
	}

}
