<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Fancy Button shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Fancy_Button extends Shortcodes {

    function hooks() {}

	function fancy_button_shortcode( $atts, $content ) {
		if ( xstore_notice() )
			return;

	    $atts = shortcode_atts(array(
	    	'title' => '',
	    	'on_click' => 'link',
	    	'link' => '',
	    	'staticblocks' => '',
	    	'staticblock' => '',

	    	// extra settings 
	    	'add_icon' => '',
			'icon' => '',
			'position' => 'left',
			'type' => 'fontawesome',
			'icon_fontawesome' => '',
			'icon_openiconic' => '',
			'icon_typicons' => '',
			'icon_entypo' => '',
			'icon_linecons' => '',
			'icon_monosocial' => '',
			'icon_xstore-icons' => '',

    		'img' => '',
    		'img_size' => '270x170',

			'svg' => '',

			'icon_spacing' => '5px',
			'icon_size' => '',
			'icon_color' => '',
			'icon_color_hover' => '',
			'icon_bg_color' => '',
			'icon_bg_color_hover' => '',

			'advanced_icon_settings' => '',

	  		'icon_padding_top' => '',
			'icon_padding_right' => '',
			'icon_padding_bottom' => '',
			'icon_padding_left' => '',

	  		'icon_border_radius_top_left' => '',
			'icon_border_radius_top_right' => '',
			'icon_border_radius_bottom_right' => '',
			'icon_border_radius_bottom_left' => '',

			'icon_border_top' => '',
			'icon_border_right' => '',
			'icon_border_bottom' => '',
			'icon_border_left' => '',

			'icon_border_style' => '',
			'icon_border_color' => '',

			'color' => '',
			'bg_color' => '',
			'color_hover' => '',
			'bg_color_hover' => '',

	    	'btn_responsive_font_size' => '',
			'btn_responsive_line_height' => '',

	    	'btn_style' => '',
			'btn_size' => 'medium',

	  		'padding_top' => '',
			'padding_right' => '',
			'padding_bottom' => '',
			'padding_left' => '',

	  		'border_radius_top_left' => '',
			'border_radius_top_right' => '',
			'border_radius_bottom_right' => '',
			'border_radius_bottom_left' => '',

			'border_top' => '',
			'border_right' => '',
			'border_bottom' => '',
			'border_left' => '',

			'border_style' => '',
			'border_color' => '',
			'border_color_hover' => '',

			'class' => '',
			'css' => '',
			
			'full_width' => '',
			'button_link_html' => '',

			// extra settings
            'is_preview' => false,
            'is_elementor' => false
	    ), $atts);

	    $atts['is_preview'] = apply_filters('etheme_output_shortcodes_inline_css', $atts['is_preview']);
		
		$options = array(
			'box_id' => rand(1000,10000),
			'button_class' => 'btn flex flex-nowrap justify-content-center align-items-center',
		);
		
		$atts['class'] .= ' et-fancy-button-' . $options['box_id'];
     
	    if ( !$atts['is_elementor']) :
		
		    if ( $atts['btn_style'] != '' ) {
			    $options['button_class'] .= ' ' . ( ( $atts['btn_style'] == 'custom' ) ? ' style-' : '' ) . $atts['btn_style'];
		    }
		
		    if ( $atts['btn_size'] != '' ) {
			    $options['button_class'] .= ' ' . ( ( $atts['btn_size'] == 'custom' ) ? ' size-' : '' ) . $atts['btn_size'];
		    }
		
		    if( ! empty($atts['css']) && function_exists( 'vc_shortcode_custom_css_class' ) )
			    $atts['class'] .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
		    
            $options['rs_sizes'] = array(
                'desktop' => '',
                'tablet' => '',
                'mobile' => ''
            );
    
            $options['rs_btn_size'] = (array)json_decode(base64_decode($atts['btn_responsive_font_size']));
            $options['rs_btn_size'] = (isset($options['rs_btn_size']['data'])) ? (array)$options['rs_btn_size']['data'] : $options['rs_sizes'];
    
            $options['rs_btn_size'] = array_merge($options['rs_sizes'], $options['rs_btn_size']);
    
            $options['rs_button_line_height'] = (array)json_decode(base64_decode($atts['btn_responsive_line_height']));
            $options['rs_button_line_height'] = (isset($options['rs_button_line_height']['data'])) ? (array)$options['rs_button_line_height']['data'] : $options['rs_sizes'];
    
            $options['rs_button_line_height'] = array_merge($options['rs_sizes'], $options['rs_button_line_height']);
    
            // border radius
            $options['border_radius'] = array();
    
            if ( $atts['btn_style'] == 'custom' ) {
    
                $options['border_radius'][] = ( ( is_numeric( $atts['border_radius_top_left'] ) ) ? $atts['border_radius_top_left'] . 'px' : $atts['border_radius_top_left'] );
                $options['border_radius'][] = ( ( is_numeric( $atts['border_radius_top_right'] ) ) ? $atts['border_radius_top_right'] . 'px' : $atts['border_radius_top_right'] );
                $options['border_radius'][] = ( ( is_numeric( $atts['border_radius_bottom_right'] ) ) ? $atts['border_radius_bottom_right'] . 'px' : $atts['border_radius_bottom_right'] );
                $options['border_radius'][] = ( ( is_numeric( $atts['border_radius_bottom_left'] ) ) ? $atts['border_radius_bottom_left'] . 'px' : $atts['border_radius_bottom_left'] );
    
            }
    
            $options['border_radius'] = implode(' ', $options['border_radius']);
    
            // border width
            $options['border'] = array();
    
            if ( $atts['btn_style'] == 'custom' ) {
    
                $options['border'][] = ( ( is_numeric( $atts['border_top'] ) ) ? $atts['border_top'] . 'px' : $atts['border_top'] );
                $options['border'][] = ( ( is_numeric( $atts['border_right'] ) ) ? $atts['border_right'] . 'px' : $atts['border_right'] );
                $options['border'][] = ( ( is_numeric( $atts['border_bottom'] ) ) ? $atts['border_bottom'] . 'px' : $atts['border_bottom'] );
                $options['border'][] = ( ( is_numeric( $atts['border_left'] ) ) ? $atts['border_left'] . 'px' : $atts['border_left'] );
    
            }
    
            $options['border'] = implode(' ', $options['border']);
    
            // padding
            $options['padding'] = array();
    
            if ( $atts['btn_size'] == 'custom' ) {
    
                $options['padding'][] = ( ( is_numeric( $atts['padding_top'] ) ) ? $atts['padding_top'] . 'px' : $atts['padding_top'] );
                $options['padding'][] = ( ( is_numeric( $atts['padding_right'] ) ) ? $atts['padding_right'] . 'px' : $atts['padding_right'] );
                $options['padding'][] = ( ( is_numeric( $atts['padding_bottom'] ) ) ? $atts['padding_bottom'] . 'px' : $atts['padding_bottom'] );
                $options['padding'][] = ( ( is_numeric( $atts['padding_left'] ) ) ? $atts['padding_left'] . 'px' : $atts['padding_left'] );
    
            }
    
            $options['padding'] = implode(' ', $options['padding']);
    
            // icon styles
            $options['icon_border_radius'] = array();
    
            if ( $atts['advanced_icon_settings'] != '' ) {
    
                $options['icon_border_radius'][] = ( ( is_numeric( $atts['icon_border_radius_top_left'] ) ) ? $atts['icon_border_radius_top_left'] . 'px' : $atts['icon_border_radius_top_left'] );
                $options['icon_border_radius'][] = ( ( is_numeric( $atts['icon_border_radius_top_right'] ) ) ? $atts['icon_border_radius_top_right'] . 'px' : $atts['icon_border_radius_top_right'] );
                $options['icon_border_radius'][] = ( ( is_numeric( $atts['icon_border_radius_bottom_right'] ) ) ? $atts['icon_border_radius_bottom_right'] . 'px' : $atts['icon_border_radius_bottom_right'] );
                $options['icon_border_radius'][] = ( ( is_numeric( $atts['icon_border_radius_bottom_left'] ) ) ? $atts['icon_border_radius_bottom_left'] . 'px' : $atts['icon_border_radius_bottom_left'] );
    
            }
    
            $options['icon_border_radius'] = implode(' ', $options['icon_border_radius']);
    
            // icon border
            $options['icon_border'] = array();
    
            if ( $atts['advanced_icon_settings'] != '' ) {
    
                $options['icon_border'][] = ( ( is_numeric( $atts['icon_border_top'] ) ) ? $atts['icon_border_top'] . 'px' : $atts['icon_border_top'] );
                $options['icon_border'][] = ( ( is_numeric( $atts['icon_border_right'] ) ) ? $atts['icon_border_right'] . 'px' : $atts['icon_border_right'] );
                $options['icon_border'][] = ( ( is_numeric( $atts['icon_border_bottom'] ) ) ? $atts['icon_border_bottom'] . 'px' : $atts['icon_border_bottom'] );
                $options['icon_border'][] = ( ( is_numeric( $atts['icon_border_left'] ) ) ? $atts['icon_border_left'] . 'px' : $atts['icon_border_left'] );
    
            }
    
            $options['icon_border'] = implode(' ', $options['icon_border']);
    
            // icon padding
            $options['icon_padding'] = array();
    
           if ( $atts['advanced_icon_settings'] != '' ) {
    
                $options['icon_padding'][] = ( ( is_numeric( $atts['icon_padding_top'] ) ) ? $atts['icon_padding_top'] . 'px' : $atts['icon_padding_top'] );
                $options['icon_padding'][] = ( ( is_numeric( $atts['icon_padding_right'] ) ) ? $atts['icon_padding_right'] . 'px' : $atts['icon_padding_right'] );
                $options['icon_padding'][] = ( ( is_numeric( $atts['icon_padding_bottom'] ) ) ? $atts['icon_padding_bottom'] . 'px' : $atts['icon_padding_bottom'] );
                $options['icon_padding'][] = ( ( is_numeric( $atts['icon_padding_left'] ) ) ? $atts['icon_padding_left'] . 'px' : $atts['icon_padding_left'] );
    
            }
    
            $options['icon_padding'] = implode(' ', $options['icon_padding']);

            // selectors
    
            $options['selectors'] = array();
    
            $options['selectors']['box'] = '.et-fancy-button-' . $options['box_id'];
            $options['selectors']['button'] = $options['selectors']['box'] . ' .button-wrap a';
            $options['selectors']['button_hover'] = $options['selectors']['button'] . ':hover';
            $options['selectors']['icon'] = $options['selectors']['button'] . ' i';
            $options['selectors']['icon_hover'] = $options['selectors']['button'] . ':hover i';
            $options['selectors']['svg'] = $options['selectors']['button'] . ' svg';
    
            // create css data for selectors
            $options['css'] = array(
                $options['selectors']['button'] => array(),
                $options['selectors']['button_hover'] => array(),
                $options['selectors']['icon'] => array(),
                $options['selectors']['icon_hover'] => array(),
                $options['selectors']['svg'] => array(),
    
                // responsive css
                'button_desktop' => array(),
                'button_tablet' => array(),
                'button_mobile' => array(),
    
                'tablet' => array(),
                'mobile' => array()
            );
    
            if ( $atts['icon_size'] )
                $options['css'][$options['selectors']['icon']][] = 'font-size:' . $atts['icon_size'];
    
            if ( $atts['icon_color'] )
                $options['css'][$options['selectors']['icon']][] = 'color:' . $atts['icon_color'];
    
            if ( $atts['icon_color_hover'] )
                $options['css'][$options['selectors']['icon_hover']][] = 'color:' . $atts['icon_color_hover'];
    
            if ( $atts['icon_bg_color'] )
                $options['css'][$options['selectors']['icon']][] = 'background-color:' . $atts['icon_bg_color'];
    
            if ( $atts['icon_bg_color_hover'] )
                $options['css'][$options['selectors']['icon_hover']][] = 'background-color:' . $atts['icon_bg_color_hover'];
    
            if( trim($options['icon_border_radius']) != '' )
                $options['css'][$options['selectors']['icon']][] = 'border-radius:' . $options['icon_border_radius'];
    
            if( trim($options['icon_border']) != '' ) {
                $options['css'][$options['selectors']['icon']][] = 'border-width:' . $options['icon_border'];
            }
    
            if( trim($options['icon_padding']) != '' )
                $options['css'][$options['selectors']['icon']][] = 'padding:' . $options['icon_padding'];
    
            if ( $atts['advanced_icon_settings'] != '' ) {
                if ( $atts['icon_border_style'] )
                    $options['css'][$options['selectors']['icon']][] = 'border-style:' . ( $atts['icon_border_style'] ? $atts['icon_border_style'] : 'none' );;
                if ( $atts['icon_border_color'] )
                    $options['css'][$options['selectors']['icon']][] = 'border-color:' . $atts['icon_border_color'];
            }
    
            // btn size
            if ( $options['rs_btn_size']['desktop'] != '' )
                $options['css'][$options['selectors']['button']][] = 'font-size: ' . $options['rs_btn_size']['desktop'];
    
            if ( $options['rs_btn_size']['tablet'] != '' )
                $options['css']['button_tablet'][] = 'font-size: ' . $options['rs_btn_size']['tablet'];
    
            if ( $options['rs_btn_size']['mobile'] != '' )
                $options['css']['button_mobile'][] = 'font-size: ' . $options['rs_btn_size']['mobile'];
    
            // btn line height
            if ( $options['rs_button_line_height']['desktop'] != '' )
                $options['css'][$options['selectors']['button']][] = 'line-height: ' . $options['rs_button_line_height']['desktop'];
    
            if ( $options['rs_button_line_height']['tablet'] != '' )
                $options['css']['button_tablet'][] = 'line-height: ' . $options['rs_button_line_height']['tablet'];
    
            if ( $options['rs_button_line_height']['mobile'] != '' )
                $options['css']['button_mobile'][] = 'line-height: ' . $options['rs_button_line_height']['mobile'];
    
            if( trim($options['border_radius']) != '' )
                $options['css'][$options['selectors']['button']][] = 'border-radius:' . $options['border_radius'];
    
            if( trim($options['border']) != '' ) {
                $options['css'][$options['selectors']['button']][] = 'border-width:' . $options['border'];
            }
    
            if( trim($options['padding']) != '' )
                $options['css'][$options['selectors']['button']][] = 'padding:' . $options['padding'];
    
            if ( $atts['btn_size'] == 'custom' )
                $options['css'][$options['selectors']['button']][] = 'height: auto';
    
            if ( $atts['btn_style'] == 'custom' ) {
    
                $options['css'][$options['selectors']['button']][] = 'border-style:' . ( $atts['border_style'] ? $atts['border_style'] : 'none' );
    
                $options['css'][$options['selectors']['button']][] = 'border-color:' . ( $atts['border_color'] ? $atts['border_color'] : 'unset' );
    
                $options['css'][$options['selectors']['button_hover']][] = 'border-color:' . ( $atts['border_color_hover'] ? $atts['border_color_hover'] : 'unset' );
    
                $options['css'][$options['selectors']['button']][] = 'color:' . ( $atts['color'] ? $atts['color'] : 'unset');
    
                $options['css'][$options['selectors']['button']][] = 'background-color:' . ( $atts['bg_color'] ? $atts['bg_color'] : 'unset' );
    
                $options['css'][$options['selectors']['button_hover']][] = 'color:' . ( $atts['color_hover'] ? $atts['color_hover'] : 'unset');
    
                $options['css'][$options['selectors']['button_hover']][] = 'background-color:' . ( $atts['bg_color_hover'] ? $atts['bg_color_hover'] : 'unset' );
            }
    
            // spacing
            switch ( $atts['position'] ) {
                case 'top':
                    $options['button_class'] .= ' flex-col';
                    $options['css'][$options['selectors']['icon']][] = 'margin-bottom:' . $atts['icon_spacing'];
                    break;
                case 'right':
                    $options['css'][$options['selectors']['icon']][] = 'margin-left:' . $atts['icon_spacing'];
                    break;
                default:
                    $options['css'][$options['selectors']['icon']][] = 'margin-right:' . $atts['icon_spacing'];
                    break;
            }
    
            if ( $atts['type'] == 'svg' ) {
    
                $options['css'][$options['selectors']['svg']][] = 'fill: currentColor';
    
                if ( $atts['icon_size'] == '' )
                    $options['css'][$options['selectors']['icon']][] = 'font-size: unset';
                else {
                    $options['css'][$options['selectors']['svg']][] = 'width:' . $atts['icon_size'];
                    $options['css'][$options['selectors']['svg']][] = 'height:' . $atts['icon_size'];
                }
            }
    
            // tablet css output
            if ( count($options['css']['button_tablet']) )
                $options['css']['tablet'][] = $options['selectors']['button'] . '{' . implode(';', $options['css']['button_tablet']) . '}';
    
            // mobile css output
            if ( count($options['css']['button_mobile']) )
                $options['css']['mobile'][] = $options['selectors']['button'] . '{' . implode(';', $options['css']['button_mobile']) . '}';
    
            // create output css
            $options['output_css'] = array();
    
            if ( count( $options['css'][$options['selectors']['icon']] ) )
                $options['output_css'][] = $options['selectors']['icon'] . '{'.implode(';', $options['css'][$options['selectors']['icon']]).'}';
    
            if ( count( $options['css'][$options['selectors']['icon_hover']] ) )
                $options['output_css'][] = $options['selectors']['icon_hover'] . '{'.implode(';', $options['css'][$options['selectors']['icon_hover']]).'}';
    
            if ( count( $options['css'][$options['selectors']['svg']] ) )
                $options['output_css'][] = $options['selectors']['svg'] . '{'.implode(';', $options['css'][$options['selectors']['svg']]).'}';
    
            if ( count( $options['css'][$options['selectors']['button']] ) )
                $options['output_css'][] = $options['selectors']['button'] . '{'.implode(';', $options['css'][$options['selectors']['button']]).'}';
    
            if ( count( $options['css'][$options['selectors']['button_hover']] ) )
                $options['output_css'][] = $options['selectors']['button_hover'] . '{'.implode(';', $options['css'][$options['selectors']['button_hover']]).'}';
        
        if ( $atts['add_icon'] == '' )
        	$atts['icon'] = '';
        else {
	        switch ( $atts['type'] ) {
	        	case 'image':
	        	    if ( function_exists('etheme_get_image') ) {
			            $atts['icon'] = '<i class="icon-image">' . etheme_get_image( $atts['img'], $atts['img_size'] ) . '</i>';
		            }
	        	break;
	        	case 'svg':
	        		$atts['icon'] = '<i class="icon-svg flex-inline">' . rawurldecode( base64_decode( $atts['svg'] ) ) . '</i>';
	        		break;
	        	default:
	        	if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ) {
	        		vc_icon_element_fonts_enqueue( $atts['type'] );
	        		$atts['icon'] = '<i class="' . ( isset( $atts['icon_' . $atts['type']] ) && $atts['icon_' . $atts['type']] != '' ? esc_attr( $atts['icon_' . $atts['type']] ) : 'fa fa-adjust' ) . '"></i>'; 
	        	}
	        	break;
	        }
	    }
		
		    $options['link'] = ( '||' === $atts['link'] ) ? '' : $atts['link'];
		    $options['link'] = parent::build_link( $options['link'] );
		    $options['a_title'] = '';
		    $options['a_href'] = '#';
		    $options['a_target'] = '_self';
		
		    if ( strlen( $options['link']['url'] ) > 0 ) {
			    $options['a_href'] = $options['link']['url'];
			    $options['a_title'] = $options['link']['title'];
			    $options['a_target'] = strlen( $options['link']['target'] ) > 0 ? $options['link']['target'] : '_self';
		    }
		
		    $options['button_attr'] = 'href="'.$options['a_href'].'" class="'.$options['button_class'].'" title="'.$options['a_title'].'" target="'.$options['a_target'].'"';
	
	    endif;

    	$options['with_popup'] = $atts['on_click'] == 'popup';
    	$options['autoplay'] = false;

		ob_start();
		
		if ( function_exists('etheme_enqueue_style')) {
			if ( $options['with_popup'] ) {
				etheme_enqueue_style( 'etheme-popup' );
			}
		}

		?>

		<div class="et-fancy-button <?php echo $atts['class']; ?>">

			<?php 

				echo '<div class="button-wrap ' . ($atts['full_width'] ? 'block' : 'inline-block') . '">' .
                     (
                         ( $atts['button_link_html'] != '') ? $atts['button_link_html'] : sprintf(
                        '<a %s>%s <span>%s</span> %s</a>',
                        $options['button_attr'],
                        ( ( $atts['position'] != 'right' ) ? $atts['icon'] : '' ),
                        ( ( $atts['on_click'] != 'popup' ) ? $options['a_title'] : $atts['title'] ),
                        ( ( $atts['position'] == 'right' ) ? $atts['icon'] : '' )
                        )
                     ) .
                 '</div>';
				
			?>

		</div>
	
		<?php if ( $options['with_popup'] ) : ?>
		    <div id="etheme-popup-wrapper" class="white-popup-block mfp-hide mfp-with-anim zoom-anim-dialog etheme-popup-wrapper etheme-popup-wrapper-<?php echo $options['box_id']; ?>">
			    <div id="etheme-popup-holder">
					<button title="Close (Esc)" type="button" class="mfp-close"></button>
			        <div id="etheme-popup">
			        	<?php 
		      			    if ( $atts['staticblocks'] != '' && $atts['staticblock'] != '' ) 
						    	etheme_static_block( $atts['staticblock'], true);
						    else {
						    	$options['autoplay'] = stripos($content, 'autoplay');
						    	if ( $options['autoplay'] ) 
						    		$content = str_replace('autoplay', 'backup_autoplay', $content);
							    echo do_shortcode($content);
						    }
			          	?>
			        </div>
			    </div>
			</div>
            
            <?php if ( !$atts['is_preview'] ) : ?>
            
                <script>
                    <?php // autoplay works only for first type ?>
                    jQuery(document).ready( function($) {
                        $('.et-fancy-button-<?php echo $options['box_id']; ?>').magnificPopup({
                            items: {
                                src: "<?php echo '.etheme-popup-wrapper-' . $options['box_id']; ?>",
                                type: 'inline'
                            },
                            removalDelay: 300, //delay removal by X to allow out-animation
                            callbacks: {
                                beforeOpen: function() {
                                    this.st.mainClass = 'mfp-zoom-out';
                                    $('html').addClass('et-mfp-opened');
                                    <?php if ( $options['autoplay'] ) : ?>
                                        var src = $('.etheme-popup-wrapper-<?php echo $options['box_id']; ?>').find('iframe').attr('src');
                                        src = src.replace('backup_autoplay', 'autoplay');
                                        $('.etheme-popup-wrapper-<?php echo $options['box_id']; ?>').find('iframe').attr('src', src);
                                    <?php endif; ?>
                                },
                                afterOpen: function() {
                                    etTheme.resizeVideo();
                                    etTheme.swiperFunc();
                                },
                                beforeClose: function() {
                                    <?php if ( $options['autoplay'] ) : ?>
                                        var src = $(document).find('.etheme-popup-wrapper-<?php echo $options['box_id']; ?>').find('iframe').attr('src');
                                        src = src.replace('autoplay', 'backup_autoplay');
                                        $(document).find('.etheme-popup-wrapper-<?php echo $options['box_id']; ?>').find('iframe').attr('src', src);
                                    <?php endif; ?>
                                },
                                afterClose: function() {
                                    $('html').removeClass('et-mfp-opened');
                                }
                            }
                        });
                    });
                </script>
            
            <?php endif; ?>

		<?php endif; 

		if ( $atts['is_preview'] ) {
		    if ( !$atts['is_elementor'])
                echo parent::initPreviewCss($options['output_css'], $options['css']['tablet'], $options['css']['mobile']);
            echo parent::initPreviewJs();
        }
        elseif ( !$atts['is_elementor'])
            parent::initCss($options['output_css'], $options['css']['tablet'], $options['css']['mobile']);

	   	unset($options);
	   	unset($atts);

	    return ob_get_clean();
	}
}
