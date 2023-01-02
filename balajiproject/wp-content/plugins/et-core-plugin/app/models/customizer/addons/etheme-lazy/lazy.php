<?php
/**
 * Create customizer lazy section.
 *
 * @since      3.2.4
 * @package    ETC
 * @subpackage ETC/Models
 */
class Etheme_Lazy_Section extends WP_Customize_Section {
	const TYPE = 'kirki-lazy';

	/**
	 * Type of control, used by JS.
	 *
	 * @access public
	 * @var string
	 */
	public $type = self::TYPE;

	public $ajax_call;

	public $dependency;

	/**
	 * Render the panel's JS templates.
	 *
	 * This function is only run for panel types that have been registered with
	 * WP_Customize_Manager::register_panel_type().
	 *
	 * @see WP_Customize_Manager::register_panel_type()
	 */
	public function print_template() {
		?>
		<script type="text/html" id="tmpl-et-lazy-section-loader">
			<style type="text/css">
				.lazy-section-loading {position: absolute; top: 0; bottom: 0; right: 0; left: 0; background: #fff; z-index: 99; }
				.loader, .loader:after {border-radius: 50%; width: 30px; height: 30px; }
				.loader {left: 45%;top: 11%;font-size: 5px; position: relative; text-indent: -9999em; border-top: 5px solid rgba(0, 0, 0, 0.2); border-right: 5px solid rgba(2, 2, 2, 0.2); border-bottom: 5px solid rgba(0, 0, 0, 0.2); border-left: 5px solid #9b9b9b; -webkit-transform: translateZ(0); -ms-transform: translateZ(0); transform: translateZ(0); -webkit-animation: load8 1.1s infinite linear; animation: load8 1.1s infinite linear; }
				@-webkit-keyframes load8 {0% {-webkit-transform: rotate(0deg); transform: rotate(0deg); } 100% {-webkit-transform: rotate(360deg); transform: rotate(360deg); } }
				@keyframes load8 {0% {-webkit-transform: rotate(0deg); transform: rotate(0deg); } 100% {-webkit-transform: rotate(360deg); transform: rotate(360deg); } }
			</style>
			<li class="lazy-section-loading">
				<div class="loader">{{ data.loading }}</div>
			</li>

		</script>
		<?php
		parent::print_template();
	}

	/**
	 * Export data to JS.
	 *
	 * @return array
	 */
	public function json() {
		$data               = parent::json();
		$data['ajax_call']  = $this->ajax_call;
		$data['dependency'] = $this->dependency;

		return $data;
	}
}

add_filter( 'kirki_section_types', 'etheme_customizer_lazy_section' ); 
function etheme_customizer_lazy_section( $section_types ) {
	$section_types['kirki-lazy'] = 'Etheme_Lazy_Section';
	return $section_types;
}