<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Menu List Item shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Menu_List_Item extends Shortcodes {

    function hooks() {}

    function menu_list_item_shortcode( $atts ) {
	    if ( xstore_notice() )
		    return;

        if( !isset($atts['subtitle_google_fonts']) || empty( $atts['subtitle_google_fonts'] ) ) {
            $atts['subtitle_google_fonts'] = 'font_family:Abril%20FLatface%3Aregular|font_style:400%20regular%3A400%3Anormal';
        }

        if( !isset($atts['title_google_fonts']) || empty( $atts['title_google_fonts'] ) ) {
            $atts['title_google_fonts'] = 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal';
        }

    	$atts = shortcode_atts(array(
    		'class'  => '',
    		'css' => '',
    		'title_link'  => '',
    		'title'  => '',
    		'label' => '',
    		'hover_color' => '',
    		'hover_bg' => '',
    		'padding_top' => '',
    		'padding_right' => '',
    		'padding_bottom' => '',
    		'padding_left' => '',
    		'transform' => '',
    		'spacing' => '',
    		'use_custom_fonts_title' => false,
            'title_custom_tag' => 'h3',
            'title_font_container' => '',
            'title_use_theme_fonts' => false,
            'title_google_fonts' => false,
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
            'el_class' => '',

            // extra icons 
            'icon_library_type' => '',
            'icon_rendered' => '',
            'svg_id' => '',
            'icon_class' => '',
    	), $atts);

        $atts['is_preview'] = apply_filters('etheme_output_shortcodes_inline_css', $atts['is_preview']);

        $options = array();

    	$options['page_link'] = get_permalink();
        $options['home_link'] = home_url();

        $options['item_id'] = rand(1000,9999);

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
        $options['selectors']['item_hover'] = $options['selectors']['item'] . ':hover';
        $options['selectors']['item_hover'] .= ', ' . $options['selectors']['item'] . ':active';
        $options['selectors']['item_hover'] .= ', ' . $options['selectors']['item'] . '.current-menu-item';

        $options['selectors']['subtitle_holder'] = $options['selectors']['item'] .' > .subitem-title-holder';
        $options['selectors']['subtitle'] = $options['selectors']['item'] .' .subitem-title-holder  .menu-title';
        $options['selectors']['subtitle_hover'] = $options['selectors']['subtitle_holder'] . ':hover ' . $atts['title_custom_tag']; 
        $options['selectors']['subtitle_hover'] .= ', ' . $options['selectors']['subtitle_holder'] . ':active ' . $atts['title_custom_tag']; 
        $options['selectors']['subtitle_hover'] .= ', ' . $options['selectors']['item'] .'.current-menu-item > .subitem-title-holder ' . $atts['title_custom_tag'];

        $options['selectors']['subtitle_tag'] = $options['selectors']['subtitle_holder'] .' '.$atts['title_custom_tag'];

        $options['selectors']['icon'] = $options['selectors']['subtitle_holder'] . ' > .menu-title i';
        $options['selectors']['icon_hover'] = $options['selectors']['subtitle_holder'] . ':hover > .menu-title i';

        // create css data for selectors
        $options['css'] = array(
            $options['selectors']['item_hover'] => array(),
            $options['selectors']['subtitle'] => array(),
            $options['selectors']['subtitle_hover'] => array(),
            $options['selectors']['subtitle_tag'] => array(),
            $options['selectors']['icon'] => array(),
            $options['selectors']['icon_hover'] => array(),
        );

        // tweak for elementor 
        if ( $atts['is_elementor'] ) {
            $options['css'][$options['selectors']['subtitle_tag']][] = 'font: inherit';
            $options['css'][$options['selectors']['subtitle_tag']][] = 'text-decoration: inherit';
        }

        // subtitle styles 
        if ( $atts['padding_top'] != '' ) 
           $options['css'][$options['selectors']['subtitle']][] = 'padding-top:' . $atts['padding_top'];

        if ( $atts['padding_right'] != '' ) 
            $options['css'][$options['selectors']['subtitle']][] = 'padding-right:' . $atts['padding_right'];

        if ( $atts['padding_bottom'] != '' ) 
            $options['css'][$options['selectors']['subtitle']][] = 'padding-bottom:' . $atts['padding_bottom'];

        if ( $atts['padding_left'] != '' ) 
            $options['css'][$options['selectors']['subtitle']][] = 'padding-left:' . $atts['padding_left'];

        // subtitle hover styles 
        if ( $atts['hover_color'] != '' )
            $options['css'][$options['selectors']['subtitle_hover']][] = 'color:'.$atts['hover_color'].' !important';

        // subtitle tag styles 
        if ( $atts['spacing'] != '' ) 
            $options['css'][$options['selectors']['subtitle_tag']][] = 'letter-spacing:' . $atts['spacing'];

        if ( $atts['hover_bg'] != '' )
            $options['css'][$options['selectors']['item_hover']][] = 'background-color:'.$atts['hover_bg'].' !important';

        // icon styles 
        if ( $atts['icon_color'] != '' ) 
           $options['css'][$options['selectors']['icon']][] = 'color:' . $atts['icon_color'];

        if ( $atts['icon_bg_color'] != '' ) 
           $options['css'][$options['selectors']['icon']][] = 'background-color:' . $atts['icon_bg_color'];

        if ( $atts['icon_size'] != '' ) 
           $options['css'][$options['selectors']['icon']][] = 'font-size:' . $atts['icon_size'] . ( is_numeric($atts['icon_size']) ? 'px' : '');

       if ( $atts['icon_border_radius'] != '' ) 
           $options['css'][$options['selectors']['icon']][] = 'border-radius:' . $atts['icon_border_radius'] . ( is_numeric($atts['icon_border_radius']) ? 'px' : '');

        if ( $atts['icon_color_hover'] != '' ) 
           $options['css'][$options['selectors']['icon_hover']][] = 'color:' . $atts['icon_color_hover'];

        if ( $atts['icon_bg_color_hover'] != '' ) 
           $options['css'][$options['selectors']['icon_hover']][] = 'background-color:' . $atts['icon_bg_color_hover'];

       // create output css 
        $options['output_css'] = array();

        if ( count( $options['css'][$options['selectors']['icon_hover']] ) ) {
            $options['css'][$options['selectors']['icon']][] = 'transition: inherit';
            $options['output_css'][] = $options['selectors']['icon_hover'] . '{'.implode(';', $options['css'][$options['selectors']['icon_hover']]).'}';
        }

        if ( count( $options['css'][$options['selectors']['icon']] ) )
            $options['output_css'][] = $options['selectors']['icon'] . '{'.implode(';', $options['css'][$options['selectors']['icon']]).'}';

        if ( count( $options['css'][$options['selectors']['subtitle_tag']] ) )
            $options['output_css'][] = $options['selectors']['subtitle_tag'] . '{'.implode(';', $options['css'][$options['selectors']['subtitle_tag']]).'}';

        if ( count( $options['css'][$options['selectors']['item_hover']] ) )
            $options['output_css'][] = $options['selectors']['item_hover'] . '{'.implode(';', $options['css'][$options['selectors']['item_hover']]).'}';

        if ( count( $options['css'][$options['selectors']['subtitle']] ) )
            $options['output_css'][] = $options['selectors']['subtitle'] . '{'.implode(';', $options['css'][$options['selectors']['subtitle']]).'}';

        if ( count( $options['css'][$options['selectors']['subtitle_hover']] ) )
            $options['output_css'][] = $options['selectors']['subtitle_hover'] . '{'.implode(';', $options['css'][$options['selectors']['subtitle_hover']]).'}';

        if ( !$atts['is_elementor'] ) {
            $atts['link'] = ( '||' === $atts['title_link'] ) ? '' : $atts['title_link'];
            unset($atts['title_link']);
            $atts['link'] = parent::build_link( $atts['link'] );
        }
        else {
            $atts['link'] = explode('|', $atts['title_link']);
            // quick fix for notice
            if ( is_array($atts['link'][0]) && is_array($atts['link'][1])) {
	            if ( count( $atts['link'][0] ) < count( $atts['link'][1] ) ) {
		            $atts['link'][0][] = '';
	            } elseif ( count( $atts['link'][0] ) > count( $atts['link'][1] ) ) {
		            $atts['link'][1][] = '';
	            }
            }
            $atts['link'] = array_combine(
                explode(',', $atts['link'][0]),
                explode(',', $atts['link'][1])
            );
        }

        $options['a_attr'] = array(
            'href' => '#',
            'target' => '_self',
            'title' => ''
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
                    if ( $atts['img'] && function_exists('etheme_get_image') )
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

    	if( ! empty($atts['css']) && !$atts['is_elementor'] && function_exists( 'vc_shortcode_custom_css_class' ) )
    		$atts['class'] .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );

        $options['img_holder'] = ( !$atts['is_elementor'] && $atts['type'] == 'image' && trim($atts['icon']) != '');
        $options['img_elementor'] = ( $atts['is_elementor'] && $atts['add_icon'] );

        $atts['class'] .= ' menu-item-'.$options['item_id'];

        ob_start();

        ?>

            <li class="menu-item <?php echo esc_attr($atts['class']); ?>">

            <?php if( ! empty( $atts['title'] ) ) : ?>

                <div class="subitem-title-holder <?php echo ( !empty($atts['label'] ) ) ? 'item-has-label menu-label-' . $atts['label'] : ''; ?> <?php echo ' ' . esc_attr($atts['el_class']); ?>">

                    <a class="menu-title et-column-title <?php echo esc_attr($atts['transform']); ?> <?php if ( $options['img_holder'] || $options['img_elementor'] ) echo 'type-img position-' . esc_attr($atts['position']); ?>" <?php 
                        foreach ($options['a_attr'] as $key => $value) {
                            if ( $value != '') {
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

                        if ( !empty($atts['label']) ) {
                            switch ($atts['label']) {
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

                </div> <?php // .subitem-title-holder ?>

            <?php endif; // ! empty( $atts['title'] ?>

            </li>

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
