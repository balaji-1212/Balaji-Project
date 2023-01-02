<?php
namespace ETC\Views;

use ETC\Core\View;

/**
 * View class to load elementor template
 *
 * @since      1.0.0
 * @package    ETC
 * @subpackage ETC/views
 */
class Elementor extends View {

	/**
	 * Prints version requirment.
	 *
	 * @param  array $args
	 * @return void
	 * @since 1.0.0
	 */
	public function elementor_version_requirement( $args = [] ) {
		echo $this->render_template(
			'elementor/error.php',
			$args
		);
	}

	/**
	 * Prints advanced  tab.
	 *
	 * @param  array $args
	 * @return void
	 * @since 1.0.0
	 */
	public function advanced_tabs( $args = [] ) {
		echo $this->render_template(
			'elementor/advanced-tabs.php',
			$args
		);
	}

	/**
	 * Prints advanced tabs.
	 *
	 * @param  array $args
	 * @return void
	 * @since 1.0.0
	 */
	public function advanced_tabs_ajax( $args = [] ) {
		return $this->render_template(
			'elementor/advanced-tabs-ajax.php',
			$args
		);
	}

}
