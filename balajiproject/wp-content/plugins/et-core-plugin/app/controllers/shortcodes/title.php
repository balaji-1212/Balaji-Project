<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Title shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Title extends Shortcodes {

	function hooks() {}

	function title_shortcode( $atts, $content ) {

		if ( xstore_notice() )
			return;

		$atts = shortcode_atts(array(
			'subtitle' => '',
            'subtitle_custom_tag' => 'h3',
            'subtitle_font_container' => '',
            'subtitle_use_theme_fonts' => '',
			'subtitle_google_fonts' => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
			'title' => 'Title',
    		'use_custom_fonts_title' => '',
            'title_custom_tag' => 'h3',
            'title_font_container' => '',
            'title_use_theme_fonts' => '',
			'title_google_fonts' => 'font_family:Abril%20Fatface%3Aregular|font_style:400%20regular%3A400%3Anormal',
			'divider' => '',
			'divider_color' => '#e1e1e1',
			'divider_width' => '1px',
			'use_custom_fonts_subtitle' => false,
			'title_responsive_font_size' => '',
			'subtitle_responsive_font_size' => '',
			'title_responsive_line_height' => '',
			'subtitle_responsive_line_height' => '',
			'class'  => '',
			'is_preview' => false
		), $atts);

		$options = array();
		$options['id'] = rand(100, 1000);

		$options['rs_sizes'] = array(
			'desktop' => '',
			'tablet' => '',
			'mobile' => ''
		);

		$options['rs_title_size'] = (array)json_decode(base64_decode($atts['title_responsive_font_size']));
		$options['rs_title_size'] = (isset($options['rs_title_size']['data'])) ? (array)$options['rs_title_size']['data'] : $options['rs_sizes'];

        $options['rs_title_size'] = array_merge($options['rs_sizes'], $options['rs_title_size']);

		$options['rs_title_line_height'] = (array)json_decode(base64_decode($atts['title_responsive_line_height']));
		$options['rs_title_line_height'] = (isset($options['rs_title_line_height']['data'])) ? (array)$options['rs_title_line_height']['data'] : $options['rs_sizes'];

        $options['rs_title_line_height'] = array_merge($options['rs_sizes'], $options['rs_title_line_height']);

		$options['rs_subtitle_size'] = (array)json_decode(base64_decode($atts['subtitle_responsive_font_size']));
		$options['rs_subtitle_size'] = (isset($options['rs_subtitle_size']['data'])) ? (array)$options['rs_subtitle_size']['data'] : $options['rs_sizes'];

        $options['rs_subtitle_size'] = array_merge($options['rs_sizes'], $options['rs_subtitle_size']);
        
		$options['rs_subtitle_line_height'] = (array)json_decode(base64_decode($atts['subtitle_responsive_line_height']));
		$options['rs_subtitle_line_height'] = (isset($options['rs_subtitle_line_height']['data'])) ? (array)$options['rs_subtitle_line_height']['data'] : $options['rs_sizes'];

        $options['rs_subtitle_line_height'] = array_merge($options['rs_sizes'], $options['rs_subtitle_line_height']);

		// selectors 
        $options['selectors']['wrapper'] = '.title-' . $options['id'];
        $options['selectors']['title'] = $options['selectors']['wrapper'] . ' .banner-title';
        $options['selectors']['title_preudo'] = $options['selectors']['title'] . ':before, ' . $options['selectors']['title'] . ':after';
        $options['selectors']['subtitle'] = $options['selectors']['wrapper'] . ' .banner-subtitle';

        // create css data for selectors
        $options['css'] = array(
        	'title_desktop' => array(),
        	'title_tablet' => array(),
        	'title_mobile' => array(),
        	'subtitle_desktop' => array(),
        	'subtitle_tablet' => array(),
        	'subtitle_mobile' => array(),
            'title_preudo' => array(),
            'desktop' => array(),
			'tablet' => array(),
			'mobile' => array()
        );

   		$options['css']['title_preudo'][] = 'border-color: '.$atts['divider_color'].' !important';
   		$options['css']['title_preudo'][] = 'border-bottom-width: ' . $atts['divider_width'] . ' !important';

   		// title size
        if ( $options['rs_title_size']['desktop'] != '' )
        	$options['css']['title_desktop'][] = 'font-size: ' . $options['rs_title_size']['desktop'];

        if ( $options['rs_title_size']['tablet'] != '' )
        	$options['css']['title_tablet'][] = 'font-size: ' . $options['rs_title_size']['tablet'];

        if ( $options['rs_title_size']['mobile'] != '' )
        	$options['css']['title_mobile'][] = 'font-size: ' . $options['rs_title_size']['mobile'];

        // title line height
        if ( $options['rs_title_line_height']['desktop'] != '' )
        	$options['css']['title_desktop'][] = 'line-height: ' . $options['rs_title_line_height']['desktop'];

        if ( $options['rs_title_line_height']['tablet'] != '' )
        	$options['css']['title_tablet'][] = 'line-height: ' . $options['rs_title_line_height']['tablet'];

        if ( $options['rs_title_line_height']['mobile'] != '' )
        	$options['css']['title_mobile'][] = 'line-height: ' . $options['rs_title_line_height']['mobile'];

        // subtitle size
        if ( $options['rs_subtitle_size']['desktop'] != '' )
        	$options['css']['subtitle_desktop'][] = 'font-size: ' . $options['rs_subtitle_size']['desktop'];

        if ( $options['rs_subtitle_size']['tablet'] != '' )
        	$options['css']['subtitle_tablet'][] = 'font-size: ' . $options['rs_subtitle_size']['tablet'];

        if ( $options['rs_subtitle_size']['mobile'] != '' )
        	$options['css']['subtitle_mobile'][] = 'font-size: ' . $options['rs_subtitle_size']['mobile'];

        // title line height
        if ( $options['rs_subtitle_line_height']['desktop'] != '' )
        	$options['css']['subtitle_desktop'][] = 'line-height: ' . $options['rs_subtitle_line_height']['desktop'];

        if ( $options['rs_subtitle_line_height']['tablet'] != '' )
        	$options['css']['subtitle_tablet'][] = 'line-height: ' . $options['rs_subtitle_line_height']['tablet'];

        if ( $options['rs_subtitle_line_height']['mobile'] != '' )
        	$options['css']['subtitle_mobile'][] = 'line-height: ' . $options['rs_subtitle_line_height']['mobile'];

        // desktop css output
        if ( count($options['css']['title_desktop']) ) 
        	$options['css']['desktop'][] = $options['selectors']['title'] . '{' . implode(';', $options['css']['title_desktop']) . '}';

        if ( count($options['css']['subtitle_desktop']) ) 
        	$options['css']['desktop'][] = $options['selectors']['subtitle'] . '{' . implode(';', $options['css']['subtitle_desktop']) . '}';

        // tablet css output 
        if ( count($options['css']['title_tablet']) )
        	$options['css']['tablet'][] = $options['selectors']['title'] . '{' . implode(';', $options['css']['title_tablet']) . '}';

        if ( count($options['css']['subtitle_tablet']) )
        	$options['css']['tablet'][] = $options['selectors']['subtitle'] . '{' . implode(';', $options['css']['subtitle_tablet']) . '}';

        // mobile css output
        if ( count($options['css']['title_mobile']) )
        	$options['css']['mobile'][] = $options['selectors']['title'] . '{' . implode(';', $options['css']['title_mobile']) . '}';

        if ( count($options['css']['subtitle_mobile']) )
        	$options['css']['mobile'][] = $options['selectors']['subtitle'] . '{' . implode(';', $options['css']['subtitle_mobile']) . '}';

		// create output css 
        $options['output_css'] = $options['output_tablet_css'] = $options['output_mobile_css'] = array();

        $options['output_css'][] = $options['selectors']['title_preudo'] . '{'.implode(';', $options['css']['title_preudo']).'}';

        // generate output css 
        $options['output_css'] = array_merge( $options['output_css'], $options['css']['desktop'] );

        $atts['class'] .= ' title-' . $options['id'];
        $atts['class'] .= ' title';
        $atts['class'] .= ' ' . $atts['divider'];
        $atts['class'] .= (( !$atts['use_custom_fonts_subtitle'] ) ? ' text-center' : '' );

        ob_start();

        ?>

		<div class="<?php esc_attr_e($atts['class']); ?>">
			<?php 

            if( ! empty( $atts['title'] ) ) {
                if ( class_exists( 'Vc_Manager' ) ) 
                    echo parent::getHeading('title', $atts, 'banner-title');
                else 
                    echo '<h2 class="banner-title">' . esc_html( $atts['title'] ) . '</h2>';
            }
			
            if( ! empty( $atts['subtitle'] ) ) {
                if ( class_exists( 'Vc_Manager' ) ) 
                    echo parent::getHeading('subtitle', $atts, 'banner-subtitle');
                else 
                    echo '<h2 class="banner-subtitle">' . esc_html( $atts['subtitle'] ) . '</h2>';
            }

			?>

		</div>

		<?php 

        if ( $atts['is_preview'] ) 
            echo parent::initPreviewCss($options['output_css'], $options['css']['tablet'], $options['css']['mobile']);
        else 
            parent::initCss($options['output_css'], $options['css']['tablet'], $options['css']['mobile']);
		
		unset($atts);
		unset($options);

		return ob_get_clean();
	}
}
