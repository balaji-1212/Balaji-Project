<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Icons List shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Icons_List extends Shortcodes {

    function hooks() {}

    function icons_list_shortcode( $atts, $content ) {
	    if ( xstore_notice() )
		    return;

        if ( !is_array( $atts ) ) 
            $atts = array();

    	$atts = shortcode_atts(array(
    		'align'  => 'left',
    		'class'  => '',
            'rows_gap' => '',
            'cols_gap' => '',
    		'css' => '',
    		
            // extra settings
            'is_preview' => isset($_GET['vc_editable']),
    	), $atts);

        $atts['is_preview'] = apply_filters('etheme_output_shortcodes_inline_css', $atts['is_preview']);

        $options = array();
	
	    $options['item_id'] = rand(100, 999);
        
        $options['selectors'] = array(
                'wrapper' => '.etheme-icons-list-'.$options['item_id']
        );
	
        $options['css'] = array(
	        $options['selectors']['wrapper'] => array()
        );
	
	    $options['output_css'] = array();
	
	    if ( $atts['cols_gap'] ) {
		    $options['css'][$options['selectors']['wrapper']][] = '--icons-cols-gap: '.$atts['cols_gap'] . 'px';
	    }
	    
	    if ( $atts['rows_gap'] ) {
		    $options['css'][$options['selectors']['wrapper']][] = '--icons-rows-gap: '.$atts['rows_gap'] . 'px';
	    }
	    
	    if ( count( $options['css'][$options['selectors']['wrapper']] ) ) {
		    $options['output_css'][] = $options['selectors']['wrapper'] . '{'.implode(';', $options['css'][$options['selectors']['wrapper']]).'}';
	    }
        
	    $atts['class'] .= ' justify-content-'.str_replace(array('left', 'right'), array('start', 'end'), $atts['align']);
	
	    if( ! empty($atts['css']) && function_exists( 'vc_shortcode_custom_css_class' ) )
		    $atts['class'] .= ' ' . vc_shortcode_custom_css_class( $atts['css'] );
	    
        if ( !empty($content) ) { ?>
            <div class="<?php echo esc_attr(trim($atts['class'])); ?>">
                <div class="etheme-icons-list etheme-icons-list-<?php echo esc_attr($options['item_id']); ?>"><?php echo do_shortcode($content); ?></div>
            </div>
        <?php }
	
	    if ( $atts['is_preview'] ) {
		    echo parent::initPreviewCss($options['output_css']);
	    }
	    else {
		    parent::initCss($options['output_css']);
	    }

        unset($options);
        unset($atts);

    	return ob_get_clean();
    }
}
