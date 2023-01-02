<?php
namespace ETC\Views;

use ETC\Core\View;

/**
 * View class to load admin template
 *
 * @since      2.3.4
 * @version    1.0.0
 * @package    ETC
 * @subpackage ETC/views
 */
class Product_Videos extends View {

	/**
	 * Prints product metaboxes fields.
	 *
	 * @param  array $args
	 * @return void
	 * @since      2.3.4
	 * @version    1.0.0
	 */
	public function product_video_box($args = []) {
		echo $this->render_template( 'admin/product-video-box.php', $args );
	}
}
