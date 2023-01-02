<?php
namespace ETC\App\Controllers\Admin;

use ETC\Core\Controller;

/**
 * Blueprint for Admin related Controllers.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/controllers/admin
 */
abstract class Base_Controller extends Controller {

	/**
	 * Register callbacks for actions and filters.
	 *
	 * @since      1.4.4
	 * @package    ETC
	 */
	abstract protected function hooks();
}
