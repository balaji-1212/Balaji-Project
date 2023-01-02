<?php
/**
 * Description
 *
 * @package    menu-functions.php
 * @since      1.0.0
 * @author     andrey
 * @link       http://xstore.8theme.com
 * @license    Themeforest Split Licence
 */

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );


// **********************************************************************//
// ! http://codex.wordpress.org/Function_Reference/wp_nav_menu#How_to_add_a_parent_class_for_menu_item
// **********************************************************************//
add_filter( 'wp_nav_menu_objects', 'etheme_add_menu_parent_class');
if ( !function_exists('etheme_add_menu_parent_class')) {
	function etheme_add_menu_parent_class( $items ) {
		$parents = array();
		foreach ( $items as $item ) {
			if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
				$parents[] = $item->menu_item_parent;
			}
		}
		foreach ( $items as $item ) {
			if ( in_array( $item->ID, $parents ) ) {
				$item->classes[] = 'menu-parent-item';
			}
		}
		
		return $items;
	}
}