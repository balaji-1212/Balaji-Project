<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Menu List shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Menu_List extends Shortcodes {

    function hooks() {}

    function menu_list_shortcode( $atts, $content ) {
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
    		'class'  => '',
    		'css' => '',
    		'link'  => '',
    		'title'  => '',
    		'label' => '',
    		'hover_color' => '',
    		'transform' => '',
            // 'hover_bg' => '',
    		'padding_top' => '',
    		'padding_right' => '',
    		'padding_bottom' => '',
    		'padding_left' => '',
    		'spacing' => '',
    		'use_custom_fonts_title' => false,
            'title_custom_tag' => 'h3',
            'title_font_container' => false,
            'title_use_theme_fonts' => false,
            'title_google_fonts' => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
    		'img' => '',
            'img_backup' => '',
    		'img_size' => '270x170',
            // icons 
    		'icon' => '',
    		'add_icon' => false,
    		'type' => 'fontawesome',
    		'icon_fontawesome' => '',
    		'icon_openiconic' => '',
    		'icon_typicons' => '',
    		'icon_entypo' => '',
    		'icon_linecons' => '',
    		'icon_monosocial' => '',
            'icon_xstore-icons' => '',
    		'icon_color' => '',
    		'icon_color_hover' => '',
    		'icon_bg_color' => '',
    		'icon_bg_color_hover' => '',
    		'icon_size' => '',
            'icon_border_radius' => '',
    		'position' => '',

            // extra settings
            'is_preview' => isset($_GET['vc_editable']),
            'is_elementor' => false,

            // extra icons 
            'icon_library_type' => '',
            'icon_rendered' => '',
            'svg_id' => '',
            'icon_class' => '',
    	), $atts);

        $atts['is_preview'] = apply_filters('etheme_output_shortcodes_inline_css', $atts['is_preview']);

        $options = array();

        $options['item_id'] = rand(100, 999);

    	$options['page_link'] = get_permalink();
        $options['home_link'] = home_url();

        if ( isset( $atts['title_font_container'] ) && !$atts['is_elementor'] ) {
     
            $atts['get_title_tag'] = explode('|', $atts['title_font_container']);

            foreach ($atts['get_title_tag'] as $key) {
                $atts['get_title_tag'] = explode(':', $key);
                if ( $atts['get_title_tag'][0] == 'tag' )
                    $atts['title_custom_tag'] = $atts['get_title_tag'][1];
            }

        }

        // create selectors to make css for them 
        $options['selectors'] = array();
        $options['selectors']['item'] = '.menu-item-'.$options['item_id'];
        $options['selectors']['title_holder'] = $options['selectors']['item'] .'.menu-parent-item > .item-title-holder';
        $options['selectors']['title'] = $options['selectors']['item'] .' .item-title-holder  .menu-title';
        $options['selectors']['title_hover'] =  $options['selectors']['item'] .' .item-title-holder:hover '.$atts['title_custom_tag'];
        $options['selectors']['title_hover'] .= ',' . $options['selectors']['item'] .' .item-title-holder:active '.$atts['title_custom_tag'];
        $options['selectors']['title_hover'] .= ',' .  $options['selectors']['item'] .'.current-menu-item .item-title-holder '.$atts['title_custom_tag'];
        $options['selectors']['title_tag'] = $options['selectors']['item'] .' > .item-title-holder '.$atts['title_custom_tag'];
        $options['selectors']['icon'] = $options['selectors']['title_holder'] . ' i';
        $options['selectors']['icon_hover'] = $options['selectors']['title_holder'] . ':hover i';

        // create css data for selectors 
        $options['css'] = array(
            $options['selectors']['title_holder'] => array(),
            $options['selectors']['title'] => array(),
            $options['selectors']['title_hover'] => array(),
            $options['selectors']['title_tag'] => array(),
            $options['selectors']['icon'] => array(),
            $options['selectors']['icon_hover'] => array(),
        );

        // tweak for elementor 
        if ( $atts['is_elementor'] ) {
            $options['css'][$options['selectors']['title_tag']][] = 'font: inherit';
            $options['css'][$options['selectors']['title_tag']][] = 'text-decoration: inherit';
        }

        // title styles 
        if($atts['padding_top'] != '') 
           $options['css'][$options['selectors']['title']][] = 'padding-top:' . $atts['padding_top'];

        if($atts['padding_right'] != '') 
            $options['css'][$options['selectors']['title']][] = 'padding-right:' . $atts['padding_right'];

        if($atts['padding_bottom'] != '') 
            $options['css'][$options['selectors']['title']][] = 'padding-bottom:' . $atts['padding_bottom'];

        if($atts['padding_left'] != '') 
            $options['css'][$options['selectors']['title']][] = 'padding-left:' . $atts['padding_left'];

        // title hover styles 
        if ( $atts['hover_color'] != '' )
            $options['css'][$options['selectors']['title_hover']][] = 'color:'.$atts['hover_color'].' !important';

        // title tag styles 
        if ( $atts['spacing'] != '' ) 
            $options['css'][$options['selectors']['title_tag']][] = 'letter-spacing:' . $atts['spacing'];

        // icon styles 
    	if($atts['icon_color'] != '') 
    	   $options['css'][$options['selectors']['icon']][] = 'color:' . $atts['icon_color'];

    	if($atts['icon_bg_color'] != '') 
    	   $options['css'][$options['selectors']['icon']][] = 'background-color:' . $atts['icon_bg_color'];

    	if($atts['icon_size'] != '') 
    	   $options['css'][$options['selectors']['icon']][] = 'font-size:' . $atts['icon_size'] . ( is_numeric( $atts['icon_size'] ) ? 'px' : '');

       if ( $atts['icon_border_radius'] != '' ) 
           $options['css'][$options['selectors']['icon']][] = 'border-radius:' . $atts['icon_border_radius'] . ( is_numeric($atts['icon_border_radius']) ? 'px' : '');

    	if($atts['icon_color_hover'] != '') 
    	   $options['css'][$options['selectors']['icon_hover']][] = 'color:' . $atts['icon_color_hover'];

    	if($atts['icon_bg_color_hover'] != '') 
    	   $options['css'][$options['selectors']['icon_hover']][] = 'background-color:' . $atts['icon_bg_color_hover'];

        // create output css 
        $options['output_css'] = array();

        if ( count( $options['css'][$options['selectors']['icon_hover']] ) ) {
            $options['css'][$options['selectors']['icon']][] = 'transition: inherit';
            $options['output_css'][] = $options['selectors']['icon_hover'] . '{'.implode(';', $options['css'][$options['selectors']['icon_hover']]).'}';
        }

        if ( count( $options['css'][$options['selectors']['icon']] ) ) 
            $options['output_css'][] = $options['selectors']['icon'] . '{'.implode(';', $options['css'][$options['selectors']['icon']]).'}';

        if ( count( $options['css'][$options['selectors']['title']] ) )
            $options['output_css'][] = $options['selectors']['title'] . '{'.implode(';', $options['css'][$options['selectors']['title']]).'}';

        if ( count( $options['css'][$options['selectors']['title_hover']] ) )
            $options['output_css'][] = $options['selectors']['title_hover'] . '{'.implode(';', $options['css'][$options['selectors']['title_hover']]).'}';

        if ( count( $options['css'][$options['selectors']['title_tag']] ) )
            $options['output_css'][] = $options['selectors']['title_tag'] . '{'.implode(';', $options['css'][$options['selectors']['title_tag']]).'}';

        if ( !$atts['is_elementor'] ) {
        	$atts['link'] = ( '||' === $atts['link'] ) ? '' : $atts['link'];
        	$atts['link'] = parent::build_link( $atts['link'] );
        }
        else {
            $atts['link'] = explode('|', $atts['link']);
            $atts['link'] = array_combine(
                explode(',', $atts['link'][0]),
                explode(',', $atts['link'][1])
            );
        }
        
        $options['a_attr'] = array(
            'href' => '#',
            'target' => '_self',
            'title' => '',
        );

    	if ( strlen( $atts['link']['url'] ) > 0 ) {
            if ( strpos($atts['link']['url'], 'http') === false ) { 
                $atts['link']['url'] = $options['home_link'] . $atts['link']['url'];
            }
    		$options['a_attr']['href'] = $atts['link']['url'];
    		$options['a_attr']['title'] = isset($atts['link']['title']) ? $atts['link']['title'] : '';

            if ( $atts['is_elementor'] ) {
                $options['a_attr']['title'] = $atts['title'];
                $options['a_attr']['target'] = $atts['link']['is_external'] ? '_blank' : '_self';
                if ( $atts['link']['nofollow'] ) 
                    $options['a_attr']['rel'] = 'nofollow';

                if ( $atts['link']['custom_attributes'] ) {
                    $atts['link']['custom_attributes'] = explode(',', $atts['link']['custom_attributes']);
                    foreach ($atts['link']['custom_attributes'] as $key => $value) {
                        $local_options = explode('|', $value);
                        if ( isset($local_options[0]) && isset($local_options[1]) )
                            $options['a_attr'][$local_options[0]] = $local_options[1];
                    }
                }
            }
            else {
	            foreach ($atts['link'] as $attribute => $attr_val) {
		            if ( $attribute == 'url' )
			            $attribute = 'href';
		            if ( $attr_val ) {
			            $options['a_attr'][ $attribute ] = $attr_val;
		            }
	            }
            }

    		$atts['class'] .= ($options['page_link'] == $options['a_attr']['href']) ? 'current-menu-item' : '';
    	}

    	if ( $atts['add_icon'] ) {

            switch ($atts['type']) {
                case 'image':
                    if ( $atts['img'] && function_exists('etheme_get_image'))
                        $atts['icon'] .= etheme_get_image($atts['img'], $atts['img_size']);
                    else 
                        $atts['icon'] .= $atts['img_backup'];
                    break;
                case 'svg':
                    if ( $atts['icon_rendered'] != '' ) {
                        $atts['icon'] .= $atts['icon_rendered'];
                    }
                    // else {
                    //     if ( $atts['icon_rendered'] != '' ) {
                    //         if ( $atts['icon_library_type'] == 'svg' ) {
                    //             $options['img_src'] = wp_get_attachment_image_src( $atts['svg_id'], 'full' );
                    //                 if ( $options['img_src'] ) 
                    //                     $options['img_src'] = $options['img_src'][0];
                    //                 $atts['icon'] = '<img src="' . $options['img_src'] . '"/>';
                    //         }
                    //         else 
                    //             $atts['icon'] = '<i class="' . esc_attr( $atts['icon_class'] ) . '"></i>';
                    //     }
                    // }
                    break;
                default:
                    vc_icon_element_fonts_enqueue( $atts['type'] );
                    $atts['icon'] = '<i class="' . ( isset( $atts['icon_' . $atts['type']] ) && $atts['icon_' . $atts['type']] != '' ? esc_attr( $atts['icon_' . $atts['type']] ) : 'fa fa-adjust' ) . '"></i>';
                    break;
            }
    	}

        $options['img_holder'] = ( !$atts['is_elementor'] && $atts['type'] == 'image' && trim($atts['icon']) != '');
        $options['img_elementor'] = ( $atts['is_elementor'] && $atts['add_icon'] );

    	$atts['class'] .= ' menu-list-'.rand(1000,9999);
        $atts['class'] .= ' text-' . $atts['align'];

    	if( ! empty($atts['css']) && !$atts['is_elementor'] && function_exists( 'vc_shortcode_custom_css_class' ) )
    		$atts['class'] .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );

        ob_start();

        ?>
        
        <ul class="et-menu-list <?php echo esc_attr( $atts['class'] ); ?>">
    
            <li class="menu-item menu-item-object-page menu-item-has-children menu-parent-item menu-item-<?php echo esc_attr($options['item_id']); ?>">

    	    <?php if( ! empty( $atts['title'] ) ) : ?>

                <div class="item-title-holder <?php echo ( !empty($atts['label'] ) ) ? 'item-has-label menu-label-'.$atts['label'] : ''; ?>">

                    <a class="menu-title et-column-title <?php echo esc_attr($atts['transform']); ?> <?php if ( $options['img_holder'] || $options['img_elementor'] ) echo 'type-img position-' . esc_attr($atts['position']); ?>" <?php 
                        foreach ($options['a_attr'] as $key => $value) {
                            if ( $value != '' ) {
	                            echo $key . '="' . $value . '" ';
                            }
                        }
                    ?>> 

                    <?php if ( ( ( $atts['type'] != 'image' && !$atts['is_elementor'] ) || ( $options['img_elementor'] && $atts['position'] == 'left-center' ) ) && trim($atts['icon']) != '') 
                        echo $atts['icon'];

                        if ( class_exists( 'Vc_Manager' ) && !$atts['is_elementor'] ) {
                            echo parent::getHeading('title', $atts);
                        } else{
                            if ( $options['img_elementor'] && $atts['position'] == 'center-center' ) 
                                echo '<'.$atts['title_custom_tag'] . '>' . $atts['icon'] . esc_html( $atts['title'] ). '</'.$atts['title_custom_tag'].'>';
                            else 
                                echo '<'.$atts['title_custom_tag'] . '>' . esc_html( $atts['title'] ). '</'.$atts['title_custom_tag'].'>';
                        }


                		if ( !empty( $atts['label'] ) ) {

                			switch ( $atts['label'] ) {
                				case 'hot':
                				    $options['label_text'] = esc_html__('Hot', 'xstore-core');
                				break;
                				case 'sale':
                				    $options['label_text'] = esc_html__('Sale', 'xstore-core');
                				break;
                				default:
                				    $options['label_text'] = esc_html__('New', 'xstore-core');
                				break;
                			}
            			    
                            echo '<span class="label-text">'.$options['label_text'].'</span>';

                		}

                        if ( $options['img_holder'] || ( $options['img_elementor'] && $atts['position'] == 'right-center' ) )
                            echo $atts['icon']

                    ?>

                    </a>

                </div> <?php // .item-title-holder ?>

            <?php endif; // ! empty( $atts['title'] ?>

        	<?php if ( !empty($content) ) { ?>
        		<div class="menu-sublist"><ul><?php echo do_shortcode($content); ?></ul></div>
        	<?php } ?>

            </li>

        </ul>

        <?php 

        if ( $atts['is_preview'] ) {
            echo parent::initPreviewCss($options['output_css']);
            echo parent::initPreviewJs();
        }
        else {
            parent::initCss($options['output_css']);
        }

        unset($options);
        unset($atts);

    	return ob_get_clean();
    }
}
