<?php
namespace ETC\Views;

use ETC\Core\View;

/**
 * View class to load admin template
 *
 * @since      1.0.0
 * @package    ETC
 * @subpackage ETC/views
 */
class Post_Types extends View {

	/**
	 * Prints brand fields.
	 *
	 * @param  array $args
	 * @return void
	 * @since 1.0.0
	 */
	public function add_brand_fileds( $args = [] ) {
		echo $this->render_template(
			'admin/add-brand-fields.php',
			$args
		);
	}

	/**
	 * Prints brand fields.
	 *
	 * @param  array $args
	 * @return void
	 * @since 1.0.0
	 */
	public function edit_brand_fields( $args = [] ) {
		echo $this->render_template(
			'admin/edit-brand-fields.php',
			$args
		);
	}

}
