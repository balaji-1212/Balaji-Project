<?php
namespace ETC\App\Models;

use ETC\App\Traits\Widget;

/**
 * Widget model.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Admin
 */
class WC_Widget extends \WC_Widget {
	use Widget;
	/**
	 * Constructor
	 */
	public function __construct( $widget_id = null, $widget_name = '', $options = array() ) {
		parent::__construct( $widget_id, $widget_name, $options );
	}
}
