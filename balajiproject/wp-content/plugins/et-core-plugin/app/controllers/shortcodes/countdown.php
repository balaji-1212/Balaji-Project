<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Countdown shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Countdown extends Shortcodes {

	function hooks() {}

	public static function countdown_shortcode( $atts ) {

		if ( xstore_notice() )
			return;

		$atts = shortcode_atts(array(
			'year' => (date('Y') + 1),
			'month' => 'January',
			'day' => 1,
			'hour' => 00,
			'minute' => 00,
			'start_year' => date('Y'),
			'start_month' => date('M'),
			'start_day' => date('d'),
			'start_hour' => date('g'),
			'start_minute' => date('i'),
			'type' => 'type1',
			'scheme' => 'white',
			'remove_finish' => true,
			'hide_title' => true,
			'title' => false,
			'class' => '',
			'is_preview' => isset($_GET['vc_editable'])
		),$atts);

        $atts['is_preview'] = apply_filters('etheme_output_shortcodes_inline_css', $atts['is_preview']);

		$options = array(
			'data-final' => array(),
			'data-start' => array(),
		);

		$options['data-final'][] = $atts['day'];
		$options['data-final'][] = $atts['month'];
		$options['data-final'][] = $atts['year'];
		$options['data-final'][] = $atts['hour'] . ':' . $atts['minute'];

		$options['data-start'][] = $atts['start_day'];
		$options['data-start'][] = $atts['start_month'];
		$options['data-start'][] = $atts['start_year'];
		$options['data-start'][] = $atts['start_hour'] . ':' . $atts['start_minute'];

		$atts['class'] .= ' ' . $atts['scheme'] . ' ' . $atts['type'];
		
		wp_enqueue_script( 'et_countdown');
		
		ob_start();
		
		if ( function_exists('etheme_enqueue_style')) {
			if ( class_exists( 'WPBMap' ) ) {
				etheme_enqueue_style( 'wpb-et-timer', true );
			} else {
				etheme_enqueue_style( 'et-timer', true );
			}
		}

		?>

		<div class="et-timer <?php echo $atts['class']; ?>" data-final="<?php echo implode(' ', $options['data-final']); ?>" data-start="<?php echo implode(' ', $options['data-start']); ?>"
            <?php if ($atts['remove_finish']) : ?> data-remove-on-finish="true" <?php endif; ?>
            <?php if ($atts['hide_title']) : ?> data-hide-info="true" <?php endif; ?>>
			<div class="timer-info"><?php echo ( $atts['title'] ) ? $atts['title'] : '' ?></div>
			<div class="time-block">
				<div class="circle-box">
					<svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 100 100" version="1.1" height="100%" width="100%">
						<circle r="47" cx="50" cy="50" fill="transparent" stroke-dasharray="295.3097094374406" stroke-dashoffset="0" data-max-val="365" style="stroke-dashoffset: 0px;"></circle>
					</svg>
				</div>
				<span class="days timer-count">0</span>
				<span><?php esc_html_e( 'days', 'xstore-core' ); ?></span>
			</div>
			<div class="time-block">
				<div class="circle-box">
					<svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 100 100" version="1.1" height="100%" width="100%">
						<circle r="47" cx="50" cy="50" fill="transparent" stroke-dasharray="295.3097094374406" stroke-dashoffset="0" data-max-val="24" style="stroke-dashoffset: 0px;"></circle>
					</svg>
				</div>
				<span class="hours timer-count">0</span>
				<span><?php esc_html_e( 'hours', 'xstore-core' ); ?></span>
			</div>
			<div class="time-block">
				<div class="circle-box">
					<svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 100 100" version="1.1" height="100%" width="100%">
						<circle r="47" cx="50" cy="50" fill="transparent" stroke-dasharray="295.3097094374406" stroke-dashoffset="0" data-max-val="60" style="stroke-dashoffset: 0px;"></circle>
					</svg>
				</div>
				<span class="minutes timer-count">0</span>
				<span><?php esc_html_e( 'mins', 'xstore-core' ); ?></span>
			</div>
			<div class="time-block">
				<div class="circle-box">
					<svg xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 100 100" version="1.1" height="100%" width="100%">
						<circle r="47" cx="50" cy="50" fill="transparent" stroke-dasharray="295.3097094374406" stroke-dashoffset="0" data-max-val="60" style="stroke-dashoffset: 0px;"></circle>
					</svg>
				</div>
				<span class="seconds timer-count">0</span>
				<span><?php esc_html_e( 'secs', 'xstore-core' ); ?></span>
			</div>
		</div>
	
		<?php 

		if ( $atts['is_preview'] ) 
			echo parent::initPreviewJs();

		unset($atts);
		unset($options);
		
		return ob_get_clean(); 
	}
}
