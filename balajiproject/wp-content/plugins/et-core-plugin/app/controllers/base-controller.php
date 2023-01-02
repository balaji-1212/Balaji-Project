<?php
namespace ETC\App\Controllers;

use ETC\Core\Controller;

/**
 * Blueprint for Frontend related Controllers.
 *
 * @since      1.0.0
 * @package    ETC
 * @subpackage ETC/Controllers
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
