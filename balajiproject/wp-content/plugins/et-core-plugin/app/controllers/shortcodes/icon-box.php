<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Icon Box shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Icon_Box extends Shortcodes {

    function hooks() {}

	function icon_box_shortcode( $atts, $content ) {
		if ( xstore_notice() )
			return;

		if( ! function_exists( 'vc_icon_element_fonts_enqueue' ) ) return;

	    $atts = shortcode_atts(array(
	    	'title' => '',
	    	'title_text_transform' => 'uppercase',
			'icon' => '',
			'type' => 'fontawesome',
			'icon_fontawesome' => '',
			'icon_openiconic' => '',
			'icon_typicons' => '',
			'icon_entypo' => '',
			'icon_linecons' => '',
			'icon_monosocial' => '',
			'icon_xstore-icons' => '',
			'view' => 'default',
			'color' => '#555',
			'bg_colour' => '#e1e1e1',
			'color_hover' => '#888',
			'bg_color_hover' => '#fafafa',
			'transparent_bg' => '',
	    	'size' => '',

	    	'content_spacing' => '10px',

	    	'icon_spacing' => '',

	  		'padding_top' => '',
			'padding_right' => '',
			'padding_bottom' => '',
			'padding_left' => '',

	  		'border_radius_top_left' => '',
			'border_radius_top_right' => '',
			'border_radius_bottom_right' => '',
			'border_radius_bottom_left' => '',

			'border_top' => '2px',
			'border_right' => '2px',
			'border_bottom' => '2px',
			'border_left' => '2px',

			'align' => '',
			'valign' => 'top',

	    	'position' => 'left',
	    	'link' => '',
			'btn_text' => '',
			'btn_style' => 'default',
			'btn_size' => 'default',
	    	'design' => '',
	    	'svg' => '',
	        'img' => '',
	        'img_size' => 'full',
	    	'animation' => '',
	        'class'  => '',
	        'on_click' => '',
	        'custom_link' => '',
			'target' => '_self',
			'css' => '',
			// 'css_md' => '',
			// 'css_sm' => '',
			// 'css_xs' => '',

			// extra settings
            'is_preview' => false
	    ), $atts);

	    $options = array(
	    	'box_id' => rand(1000,10000)
	    );

	    if ( $atts['align'] == '' ) 
	    	$atts['align'] = ( $atts['position'] == 'top' ) ? 'center' : $atts['position'];

	    if ( $atts['view'] == 'default' ) 
	    	$atts['transparent_bg'] = 'yes';

	    $options['is_table'] = 'top' != $atts['position'];

	    // border radius 
	    $options['border_radius'] = array();

	    $options['border_radius'][] = ( ( is_numeric( $atts['border_radius_top_left'] ) ) ? $atts['border_radius_top_left'] . 'px' : $atts['border_radius_top_left'] );
	    $options['border_radius'][] = ( ( is_numeric( $atts['border_radius_top_right'] ) ) ? $atts['border_radius_top_right'] . 'px' : $atts['border_radius_top_right'] );
	    $options['border_radius'][] = ( ( is_numeric( $atts['border_radius_bottom_right'] ) ) ? $atts['border_radius_bottom_right'] . 'px' : $atts['border_radius_bottom_right'] );
	    $options['border_radius'][] = ( ( is_numeric( $atts['border_radius_bottom_left'] ) ) ? $atts['border_radius_bottom_left'] . 'px' : $atts['border_radius_bottom_left'] );

	    $options['border_radius'] = implode(' ', $options['border_radius']);

	    // border width
	    $options['border'] = array();

	    if ( $atts['view'] == 'framed' ) {

		    $options['border'][] = ( ( is_numeric( $atts['border_top'] ) ) ? $atts['border_top'] . 'px' : $atts['border_top'] );
		    $options['border'][] = ( ( is_numeric( $atts['border_right'] ) ) ? $atts['border_right'] . 'px' : $atts['border_right'] );
		    $options['border'][] = ( ( is_numeric( $atts['border_bottom'] ) ) ? $atts['border_bottom'] . 'px' : $atts['border_bottom'] );
		    $options['border'][] = ( ( is_numeric( $atts['border_left'] ) ) ? $atts['border_left'] . 'px' : $atts['border_left'] );
		
		    if ( $atts['color'] )
			    $options['css'][$options['selectors']['icon']][] = 'border-color: ' . $atts['color'];
		
		    if( $atts['color_hover'] != '')
			    $options['css'][$options['selectors']['icon_hover']][] = 'border-color: ' . $atts['color_hover'];

		}

	    $options['border'] = implode(' ', $options['border']);

	    // padding
	    $options['padding'] = array();

	    $options['padding'][] = ( ( is_numeric( $atts['padding_top'] ) ) ? $atts['padding_top'] . 'px' : $atts['padding_top'] );
	    $options['padding'][] = ( ( is_numeric( $atts['padding_right'] ) ) ? $atts['padding_right'] . 'px' : $atts['padding_right'] );
	    $options['padding'][] = ( ( is_numeric( $atts['padding_bottom'] ) ) ? $atts['padding_bottom'] . 'px' : $atts['padding_bottom'] );
	    $options['padding'][] = ( ( is_numeric( $atts['padding_left'] ) ) ? $atts['padding_left'] . 'px' : $atts['padding_left'] );

	    $options['padding'] = implode(' ', $options['padding']);

		$atts['class'] .= ' box-' . $options['box_id'];

	    $atts['class'] .= ' ' . 'icon-' . $atts['position'];

	    $atts['class'] .= ' align-' . $atts['align'];

	    if ( $atts['position'] == 'top' ) 
	    	$atts['class'] .= ' flex-wrap';
	     
	    if( $atts['design'] != '' )
		    $atts['class'] .= ' design-' . $atts['design'];
	    
	    if( $atts['animation'] != '' )
		    $atts['class'] .= ' animation-' . $atts['animation'];

		// $options['vc_css'] = array(
		// 	'md' => '',
		// 	'sm' => '',
		// 	'xs' => ''
		// );

		if( function_exists( 'vc_shortcode_custom_css_class' ) ) {
			if ( ! empty($atts['css']) )
				$atts['class'] .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
			/*
			if ( ! empty($atts['css_md']) ) {
				$options['vc_css']['md'] = str_replace(vc_shortcode_custom_css_class( $atts['css_md'] ), '', $atts['css_md']);
				$options['vc_css']['md'] = substr($options['vc_css']['md'], 2, -1); // get all properties  
			}
			if ( ! empty($atts['css_sm']) ) {
				$options['vc_css']['sm'] = str_replace(vc_shortcode_custom_css_class( $atts['css_sm'] ), '', $atts['css_sm']);
				$options['vc_css']['sm'] = substr($options['vc_css']['sm'], 2, -1); // get all properties 
			} 
			if ( ! empty($atts['css_xs']) ) {
				$options['vc_css']['xs'] = str_replace(vc_shortcode_custom_css_class( $atts['css_xs'] ), '', $atts['css_xs']);
				$options['vc_css']['xs'] = substr($options['vc_css']['xs'], 2, -1); // get all properties 
			}
			*/
		}

		// selectors 

		$options['selectors'] = array();

        $options['selectors']['box'] = '.ibox-block.box-' . $options['box_id'];
        if ( $options['is_table'] ) 
        	$options['selectors']['box'] .= '.table';
        $options['selectors']['icon_symbol'] = $options['selectors']['box'] . ' .ibox-symbol';
        $options['selectors']['icon'] = $options['selectors']['box'] . ' .ibox-symbol i';
        $options['selectors']['icon_hover'] = $options['selectors']['box'] . ':hover .ibox-symbol i';
        $options['selectors']['svg'] = $options['selectors']['box'] . ' .ibox-symbol svg';
        $options['selectors']['content'] = $options['selectors']['box'] . ' .ibox-content';
        $options['selectors']['content_elements'] = $options['selectors']['box'] . ' h3:not(:last-child)';
        $options['selectors']['content_elements'] .= ', ' . $options['selectors']['box'] . ' .ibox-text:not(:last-child)';
        $options['selectors']['content_elements'] .= ', ' . $options['selectors']['box'] . ' .ibox-text ul:not(:last-child)';
        $options['selectors']['content_elements'] .= ', ' . $options['selectors']['box'] . ' .button-wrap:not(:last-child)';

        // create css data for selectors
        $options['css'] = array(
        	$options['selectors']['icon_symbol'] => array(),
            $options['selectors']['icon'] => array(),
            $options['selectors']['icon_hover'] => array(),
            $options['selectors']['svg'] => array(),
            $options['selectors']['content'] => array(),
            $options['selectors']['content_elements'] => array()
        );

   		if ( $atts['color'] )
   			$options['css'][$options['selectors']['icon']][] = 'color:' . $atts['color'];

   		if( $atts['size'] != '') 
   			$options['css'][$options['selectors']['icon']][] = 'font-size:' . ( ( is_numeric( $atts['size'] ) ) ? $atts['size'] . 'px' : $atts['size'] );

			$options['css'][$options['selectors']['svg']][] = 'min-width:' . ( ( is_numeric( $atts['size'] ) ) ? $atts['size'] . 'px' : $atts['size'] );
			$options['css'][$options['selectors']['svg']][] = 'min-height:' . ( ( is_numeric( $atts['size'] ) ) ? $atts['size'] . 'px' : $atts['size'] );

   		if( trim($options['border_radius']) != '' ) 
   			$options['css'][$options['selectors']['icon']][] = 'border-radius:' . $options['border_radius'];

   		if( trim($options['border']) != '' ) {
   			$options['css'][$options['selectors']['icon']][] = 'border-style: solid';
   			$options['css'][$options['selectors']['icon']][] = 'border-width:' . $options['border'];
   		}

   		if( trim($options['padding']) != '' ) 
   			$options['css'][$options['selectors']['icon']][] = 'padding:' . $options['padding'];
		
		$img_size_explode = explode('x',$atts['img_size']);
		if ( is_array($img_size_explode) && $atts['type'] == 'image' ) {
		    $options['css'][$options['selectors']['icon_symbol']][] = 'width:' . $img_size_explode[0] . 'px';
        }

	    // spacing 
	    if ( $atts['icon_spacing'] ) {
		    switch ( $atts['position'] ) {
		    	case 'top':
		    		$options['css'][$options['selectors']['icon_symbol']][] = ( ( $options['is_table'] ) ? 'padding' : 'margin' ) . '-bottom:' . $atts['icon_spacing'];
		    		break;
		    	case 'right':
		    		$options['css'][$options['selectors']['icon_symbol']][] = ( ( $options['is_table'] ) ? 'padding' : 'margin' ) . '-left:' . $atts['icon_spacing'];
		    		break;
		    	default:
		    		$options['css'][$options['selectors']['icon_symbol']][] = ( ( $options['is_table'] ) ? 'padding' : 'margin' ) . '-right:' . $atts['icon_spacing'];
		    		break;
		    }
		}

	    $options['valign'] = $atts['valign'];

	    switch ( $atts['valign'] ) {
	    	case 'start':
		    	$options['valign'] = 'top';
	    		break;
	    	case 'center':
		    	$options['valign'] = 'middle';
	    		break;
	    	case 'end':
	    		$options['valign'] = 'bottom';
	    		break;
	    }

	    $options['css'][$options['selectors']['content']][] = 'vertical-align:' . $options['valign'];

   		if( $atts['color_hover'] != '')
   			$options['css'][$options['selectors']['icon_hover']][] = 'color:' . $atts['color_hover'];

   		if ( $atts['transparent_bg'] == '' ) {
   			
   			if ( $atts['bg_colour'] != '' )
   				$options['css'][$options['selectors']['icon']][] = 'background-color:' . $atts['bg_colour'];

   			if ( $atts['bg_color_hover'] != '' ) 
   				$options['css'][$options['selectors']['icon_hover']][] = 'background-color:' . $atts['bg_color_hover'];
   		
   		}
		else {
			$options['css'][$options['selectors']['icon']][] = 
			$options['css'][$options['selectors']['icon_hover']][] = 'background-color: transparent';
		}

		if ( $atts['type'] == 'svg' ) {
			$options['css'][$options['selectors']['svg']][] = 'fill: currentColor';
			// $options['css'][$options['selectors']['icon']][] = 'width: unset';
			// $options['css'][$options['selectors']['icon']][] = 'height: unset';
			// $options['css'][$options['selectors']['icon']][] = 'min-width: unset';
			// $options['css'][$options['selectors']['icon']][] = 'min-height: unset';
			// $options['css'][$options['selectors']['icon']][] = 'line-height: unset';
			if ( $atts['size'] == '' ) 
				$options['css'][$options['selectors']['icon']][] = 'font-size: unset';
			else {
				$options['css'][$options['selectors']['svg']][] = 'width:' . $atts['size'];
				$options['css'][$options['selectors']['svg']][] = 'height:' . $atts['size'];
			}
		}

		if ( $atts['content_spacing'] != '' ) 
			$options['css'][$options['selectors']['content_elements']][] = 'margin-bottom: '. ( ( is_numeric( $atts['content_spacing'] ) ) ? $atts['content_spacing'] . 'px' : $atts['content_spacing'] );

		// create output css 
        $options['output_css'] = array();

        if ( count( $options['css'][$options['selectors']['icon_symbol']] ) )
            $options['output_css'][] = $options['selectors']['icon_symbol'] . '{'.implode(';', $options['css'][$options['selectors']['icon_symbol']]).'}';

        if ( count( $options['css'][$options['selectors']['icon']] ) )
            $options['output_css'][] = $options['selectors']['icon'] . '{'.implode(';', $options['css'][$options['selectors']['icon']]).'}';

        if ( count( $options['css'][$options['selectors']['icon_hover']] ) )
            $options['output_css'][] = $options['selectors']['icon_hover'] . '{'.implode(';', $options['css'][$options['selectors']['icon_hover']]).'}';

        if ( count( $options['css'][$options['selectors']['svg']] ) )
            $options['output_css'][] = $options['selectors']['svg'] . '{'.implode(';', $options['css'][$options['selectors']['svg']]).'}';

        if ( count( $options['css'][$options['selectors']['content_elements']] ) )
            $options['output_css'][] = $options['selectors']['content_elements'] . '{'.implode(';', $options['css'][$options['selectors']['content_elements']]).'}';

        if ( count( $options['css'][$options['selectors']['content']] ) )
            $options['output_css'][] = $options['selectors']['content'] . '{'.implode(';', $options['css'][$options['selectors']['content']]).'}';

        switch ( $atts['type'] ) {
        	case 'image':
        	    if ( function_exists('etheme_get_image')) {
		            $atts['icon'] = '<i class="icon-image">' . etheme_get_image( $atts['img'], $atts['img_size'] ) . '</i>';
	            }
        	break;
        	case 'svg':
        		$atts['icon'] = '<i class="icon-image">' . rawurldecode( base64_decode( $atts['svg'] ) ) . '</i>';
        		break;
        	default:
        		vc_icon_element_fonts_enqueue( $atts['type'] );
				$atts['icon'] = '<i class="' . ( isset( $atts['icon_' . $atts['type']] ) && $atts['icon_' . $atts['type']] != '' ? esc_attr( $atts['icon_' . $atts['type']] ) : 'fa fa-adjust' ) . '"></i>'; 
        	break;
        }

	    $options['with_custom_link'] = ( $atts['on_click'] == 'link' && $atts['custom_link'] != '' );

	    if ( $options['with_custom_link'] ) 
	    	$atts['class'] .= ' pointer';

	    if ( $options['is_table'] )
	    	$atts['class'] .= ' table';

		ob_start();
		
		if ( function_exists('etheme_enqueue_style') )
		    etheme_enqueue_style('icon-boxes', true);

		?>

		<div
                class="ibox-block <?php echo $atts['class']; ?>"
                <?php
                if ( $options['with_custom_link'] ) {
	                if ( $atts['target'] == '_blank' ) {
		                echo 'onclick="window.open(\'' . esc_url( $atts['custom_link'] ) . '\',\'_blank\')"';
	                } else {
		                echo 'onclick="window.location=\'' . esc_url( $atts['custom_link'] ) . '\'"';
	                }
                }
                ?>
        >
			<?php if ( $atts['position'] != 'right' ) : ?>
				<div class="ibox-symbol<?php echo ( 'top' == $atts['position'] ) ? ' full-width' : ''; ?>">
					<?php echo $atts['icon']; ?>
				</div>
			<?php endif; ?>

			<div class="ibox-content">
				<?php echo ( ( '' != $atts['title'] ) ? '<h3 class="' . ( ( $atts['title_text_transform'] != 'none' ) ? 'text-' . esc_attr($atts['title_text_transform']) : '' ) . '">' . $atts['title'] . '</h3>' : '' ); ?>

				<?php if ( $content != '' ) : ?>
					<div class="ibox-text">
						<?php echo do_shortcode( $content ); ?>
					</div>
				<?php endif; ?>

				<?php 

			    if( $atts['link'] != '' && $atts['btn_text'] != '' )
					echo sprintf(
						'<div class="button-wrap"><a href="%s" class="%s">%s</a></div>', 
						$atts['link'],
						'btn style-' . $atts['btn_style'] . ' size-' . $atts['btn_size'],
						$atts['btn_text']
					);
				?>
			</div>

			<?php if ( $atts['position'] == 'right' ) : ?>
				<div class="ibox-symbol">
					<?php echo $atts['icon']; ?>
				</div>
			<?php endif; ?>

		</div>

		<?php 

        if ( $atts['is_preview'] )
            echo parent::initPreviewCss($options['output_css']);
        else 
            parent::initCss($options['output_css']);

	   	unset($options);
	   	unset($atts);

	    return ob_get_clean();
	}
}
