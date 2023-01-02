<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Scroll Text Item shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Scroll_Text_Item extends Shortcodes {

    function hooks() {}

	function scroll_text_item_shortcode( $atts, $content ) {

		if ( xstore_notice() )
			return;

		$atts = shortcode_atts(array(
	        'button_link'  => '#',
	        'tooltip'  => false,
	        'tooltip_title' => '',
	        'tooltip_content' => '',
	        'tooltip_content_pos' => 'bottom',
			'el_class' => '',
			'is_preview' => false
		), $atts);

		// Button link 
		 
		$atts['button_link'] = ( '||' === $atts['button_link'] ) ? '' : $atts['button_link'];

		$atts['button_link'] = parent::build_link( $atts['button_link'] );

		ob_start(); 

		?>

		<div class="autoscrolling-item <?php echo esc_attr( $atts['el_class'] ); ?>">

			<?php echo wp_kses_post( $content ) . ' ';

			if ( !$atts['tooltip'] ) {

				if ( is_array($atts['button_link']) && strlen( $atts['button_link']['title'] ) > 0 ) { 

					$options['link_attr'] = array(
						'href="' . ( !empty( $atts['button_link']['url'] ) ? esc_attr( $atts['button_link']['url'] ) : '#' ) . '"',
						'class="scr-text-button"',
						'target="' . ( strlen( $atts['button_link']['target'] ) > 0 ? esc_attr( $atts['button_link']['target'] ) : '_self' ) . '"',
						'rel="' . esc_attr( $atts['button_link']['rel'] ) . '"'
					);

					?>

					<a <?php echo implode(' ', $options['link_attr']) ?>>
						<?php echo esc_html($atts['button_link']['title']); ?>
					</a>

				<?php } 

			} 
			else { ?>

				<div class="et-text-tooltip" rel="tooltip" data-placement="<?php echo esc_attr($atts['tooltip_content_pos']); ?>">

					<?php esc_html_e( $atts['tooltip_title'] ); ?>

					<div class="tooltip-content">
						<?php echo wp_kses_post( $atts['tooltip_content'] ); ?>
					</div>

				</div>

			<?php } ?>

		</div>

		<?php 

        if ( $atts['is_preview'] ) 
            echo parent::initPreviewJs();
		
        unset($atts);
        unset($options); 

		return ob_get_clean();
	}
}
