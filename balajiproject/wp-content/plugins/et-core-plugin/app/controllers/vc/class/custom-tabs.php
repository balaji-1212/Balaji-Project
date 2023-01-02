<?php
/**
 * The tabs shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/vc/class
 */
if( ! function_exists('vc_path_dir') ) return;

require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-column.php' );
\VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_VC_Tta_Accordion' );

class WPBakeryShortCode_ET_Tabs extends \WPBakeryShortCode_VC_Tta_Accordion {
	public function getFileName() {
		return 'et_tabs';
	}

}

\VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_VC_Tta_Accordion' );
class WPBakeryShortCode_ET_Tab extends \WPBakeryShortCode_VC_Tta_Accordion {
	protected $controls_css_settings = 'tc vc_control-container';
	protected $controls_list = array( 'add', 'edit', 'clone', 'delete' );
	protected $backened_editor_prepend_controls = false;

	public function getFileName() {
		return 'et_tab';
	}


	public function getParamHeading( $atts, $content ) {
		$output = '';
		$output .= $this->getTemplateVariable( 'title' );

		return $output;
	}

	public function getParamTitle( $atts, $content ) {
		if ( isset( $atts['title'] ) && strlen( $atts['title'] ) > 0 ) {
			return '<span>' . $atts['title'] . '</span>';
		}

		return null;
	}
}
