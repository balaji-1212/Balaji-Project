<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Follow shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Follow extends Shortcodes {

    function hooks() {}

    function follow_shortcode( $atts ) {
	    if ( xstore_notice() )
		    return;

        $options = array(
            'id' => rand( 100, 999 ),
            'class' => array()
        );

        $options['socials'] = array(
            'facebook' => '',
            'twitter' => '',
            'instagram' => '',
            'skype' => '',
            'pinterest' => '',
            'linkedin' => '',
            'whatsapp' => '',
            'snapchat' => '',
            'etsy' => '',
            'tik-tok' => '',
            'strava' => '',
            'cafecito' => '',
            'dribbble' => '',
            'kofi' => '',
            'line' => '',
            'patreon' => '',
            'reddit' => '',
            'discord' => '',
            'email' => '',
            'untapped' => '',
            'tumblr' => '',
            'youtube' => '',
            'telegram' => '',
            'vimeo' => '',
            'rss' => '',
            'vk' => '',
            'tripadvisor' => '',
            'houzz' => '',
        );

        $atts = shortcode_atts(array_merge(array(
            'size' => 'normal',
            'align' => 'start',
            'target' => '_blank',
            'icons_bg' => '',
            'icons_color' => '',
            'icons_bg_hover' => '',
            'icons_color_hover' => '',
            'filled' => '',
            'icons_border_radius' => '',
            'tooltip' => '',
            'css' => '',

            // extra settings
            'is_preview' => false
        ), $options['socials']), $atts);

        $atts['align'] = str_replace(array('left', 'right'), array('start', 'end'), $atts['align']);

        $options['class'][] = 'buttons-size-'.$atts['size'];
        $options['class'][] = 'justify-content-'.$atts['align'];

        if( $atts['filled'] ) 
            $options['class'][] = 'icons-filled';

        if ( $atts['target'] )
            $atts['target'] = 'target="' . $atts['target'] . '"';

        if( !empty( $atts['css'] ) && function_exists( 'vc_shortcode_custom_css_class' ) )
            $options['class'][] = vc_shortcode_custom_css_class( $atts['css'] );

        $options['class'][] = 'follow-'. $options['id'];

        $options['selectors'] = array();
        $options['selectors']['link'] = '.follow-' . $options['id'] . ' a';
        $options['selectors']['icon'] = $options['selectors']['link'] . ' i';
        $options['selectors']['link_hover'] = $options['selectors']['link'] . ':hover';
        $options['selectors']['link_hover_icon'] = $options['selectors']['link_hover'] . ' i';

        // create css data for selectors
        $options['css'] = array(
            $options['selectors']['link'] => array(),
            $options['selectors']['icon'] => array(),
            $options['selectors']['link_hover'] => array(),
            $options['selectors']['link_hover_icon'] => array(),
        );

        if( ! empty( $atts['icons_bg'] ) ) 
            $options['css'][$options['selectors']['link']][] = 'background-color:' . $atts['icons_bg'] . '!important';
        
        if( ! empty( $atts['icons_color'] ) ) 
            $options['css'][$options['selectors']['icon']][] = 'color:' . $atts['icons_color'] . '!important';

        if( ! empty( $atts['icons_border_radius'] ) ) 
            $options['css'][$options['selectors']['link']][] = 'border-radius:' . $atts['icons_border_radius'] . '!important';
        
        if( ! empty( $atts['icons_bg_hover'] ) ) 
            $options['css'][$options['selectors']['link_hover']][] = 'background-color:' . $atts['icons_bg_hover'] . '!important';
        
        if( ! empty( $atts['icons_color_hover'] ) ) 
            $options['css'][$options['selectors']['link_hover_icon']][] = 'color:' . $atts['icons_color_hover'] . '!important';

        // create output css 
        $options['output_css'] = array();

        if ( count( $options['css'][$options['selectors']['link']] ) )
            $options['output_css'][] = $options['selectors']['link'] . '{'.implode(';', $options['css'][$options['selectors']['link']]).'}';

        if ( count( $options['css'][$options['selectors']['icon']] ) )
            $options['output_css'][] = $options['selectors']['icon'] . '{'.implode(';', $options['css'][$options['selectors']['icon']]).'}';

        if ( count( $options['css'][$options['selectors']['link_hover']] ) )
            $options['output_css'][] = $options['selectors']['link_hover'] . '{'.implode(';', $options['css'][$options['selectors']['link_hover']]).'}';

        if ( count( $options['css'][$options['selectors']['link_hover_icon']] ) )
            $options['output_css'][] = $options['selectors']['link_hover_icon'] . '{'.implode(';', $options['css'][$options['selectors']['link_hover_icon']]).'}';

        ob_start();

        ?>

        <div class="et-follow-buttons <?php echo implode(' ', $options['class']); ?>">

            <?php 
                foreach ( array_keys($options['socials']) as $social ) {
                    if ( $atts[$social] != '' ) {
                        $icon = $social == 'email' ? 'message' : $social; ?>
                        <a href="<?php echo 'skype' == $social ? 'skype:'.esc_attr($atts[$social]).'?chat' : esc_url( $atts[$social] ); ?>" class="follow-<?php echo esc_attr($social); ?><?php echo ( '' != $atts['tooltip']) ? ' mtips' : ''; ?>" <?php echo $atts['target']; ?> rel="nofollow">
                            <i class="et-icon et-<?php echo esc_attr($icon); ?>"></i>
                            <?php if ( '' != $atts['tooltip']) { ?>
                                <span class="mt-mes"><?php echo ucfirst(esc_html($social)); ?></span>
                            <?php }
                            else { ?>
                                <span class="screen-reader-text"><?php echo ucfirst(esc_html($social)); ?></span>
                            <?php }?>
                        </a>
                    <?php }
                }
            ?>

        </div>

        <?php 

        if ( $atts['is_preview'] ) {
            echo parent::initPreviewCss($options['output_css']);
        }
        else {
            parent::initCss($options['output_css']);
        }

        return ob_get_clean();

    }
}
