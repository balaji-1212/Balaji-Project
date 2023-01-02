<?php
/**
 * Description
 *
 * @package    bbpress.php
 * @since      1.0.0
 * @author     stas
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

// rewrite default bbp separator with theme icon and removed wrapped p

function et_bbp_breadcrumb_sep() {
	$args['sep'] = '<i class="et-icon et-'.(is_rtl() ? 'right' : 'left').'-arrow"></i>';
	$args['before'] = '<div class="bbp-breadcrumb">';
	$args['after'] = '</div>';
	return $args;
}

add_filter('bbp_before_get_breadcrumb_parse_args', 'et_bbp_breadcrumb_sep' );