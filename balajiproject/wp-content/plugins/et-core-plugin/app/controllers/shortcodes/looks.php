<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Looks shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Looks extends Shortcodes {

	function hooks() {}

	function looks_shortcode( $atts, $content ) {
		if ( parent::woocommerce_notice() || xstore_notice() )
			return;

		global $woocommerce_loop, $et_look_loop;

		$atts = shortcode_atts(array(
			'class'  => '',
		), $atts);

		$options = array();

		preg_match_all( '/et_the_look([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );

		$options['look_titles'] = isset( $matches[1] ) ? $matches[1] : array();
		
		wp_enqueue_script('et_isotope');
		wp_enqueue_script('the_look');
		
		if ( function_exists('etheme_enqueue_style')) {
			etheme_enqueue_style( 'woocommerce' );
			etheme_enqueue_style( 'banners-global' );
			etheme_enqueue_style( 'banner' );
			etheme_enqueue_style( 'wpb-thelook' );
		}
		
		ob_start();
		
		?>

		<div class="et-looks <?php esc_attr_e( $atts['class'] ); ?>">
			<div class="et-looks-content">
				<?php echo do_shortcode( $content ); ?>
			</div>
			<?php
			
			if( count( $options['look_titles'] ) > 1 ) : ?>

                <ul class="et-looks-nav">
					<?php $local_options = array(
						'i' => 0
					);
					foreach ( $options['look_titles'] as $look ) {
						?>
                        <li<?php echo ($local_options['i'] < 1) ? ' class="active"' : ''; ?>>
                            <a href="#">
								<?php echo $local_options['i']; ?>
                            </a>
                        </li>
					<?php $local_options['i']++; }
					unset($local_options);
					?>
                </ul>
			
			<?php endif;
			
			?>
		</div>
	
		<?php 

		unset($atts);
		unset($options);
		// reset look loop item in case few looks are on page
		unset($et_look_loop['item']);
		
		return ob_get_clean();
	}
}
