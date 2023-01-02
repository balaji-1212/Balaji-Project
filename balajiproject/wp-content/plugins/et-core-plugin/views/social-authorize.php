<?php
namespace ETC\Views;

use ETC\Core\View;

/**
 * View class to load authorize template
 *
 * @since      3.0.3
 * @version    1.0.0
 * @package    ETC
 * @subpackage ETC/views
 */
class Social_Authorize extends View {

	/**
	 * Prints product metaboxes fields.
	 *
	 * @param  array $args
	 * @return void
	 * @since      2.3.4
	 * @version    1.0.0
	 */
	public function authorization_buttons($args = []) {
		echo $this->render_template( 'authorize/buttons.php', $args );
	}
}
