<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * ToolTip shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class ToolTip extends Shortcodes {

	function hooks() {}

    function tooltip_shortcode( $atts, $content = null ) {

	    if ( xstore_notice() )
		    return;

        $atts = shortcode_atts( array(
           'position' => 'top',
           'text' => '',
           'class' => ''
       	), $atts );

       	$options = array(
       		'wrapper_attr' => array(
       			'class="et-tooltip '.$atts['class'].'"',
       			'rel="tooltip"',
       			'data-placement="'.$atts['position'].'"',
       			'data-original-title="'.$atts['text'].'"',
       		)
       	);

       	ob_start();
       	
       	?>

       	<div <?php echo implode(' ', $options['wrapper_attr']); ?>>
       		<div>
       			<div>
       				<?php echo $content; ?>
       			</div>
       		</div>
   		</div>

   		<?php 

   		unset($atts);
   		unset($options);
    	
    	return ob_get_clean();
    }
}