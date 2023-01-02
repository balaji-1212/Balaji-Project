<?php
namespace ETC\App\Controllers\Shortcodes;

use ETC\App\Controllers\Shortcodes;

/**
 * Menu shortcode.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Controllers/Shortcodes
 */
class Menu extends Shortcodes {
	
	function hooks() {}
	
	function menu_shortcode( $atts ) {
		if ( xstore_notice() )
			return;
		
		$atts = shortcode_atts(array(
			'title' => '',
			'menu'  => '',
			'style' => '',
			'align' => '',
			'class' => '',
			'hide_submenus' => false,
			'submenu_side' => '',
			'submenu_side_vertical' => ''
		), $atts);
		
		$atts['class'] .= ' ' . $atts['style'];
		$atts['class'] .= ' menu-align-' . $atts['align'];
		
		if ( $atts['style'] == 'horizontal')
			$atts['class'] .= (!empty($atts['submenu_side']) ) ? ' submenu-'.$atts['submenu_side'] : '';
		else
			$atts['class'] .= (!empty($atts['submenu_side_vertical']) ) ? ' submenu-'.$atts['submenu_side_vertical'] : '';
		
		if ( $atts['hide_submenus'] ) {
			add_filter( 'menu_item_with_sublists', '__return_false' );
		}
		
		add_filter('menu_subitem_with_arrow', '__return_false');
		
		$nav_args = array(
			'menu'				=>	$atts['menu'],
			'before' 			=>	'',
			'container_class'	=>	'',
			'after'				=>	'',
			'link_before'		=>	'',
			'link_after' 		=>	'',
			'depth' 			=>	( ( $atts['hide_submenus'] ) ? 1 : 100 ),
			'fallback_cb' 		=>	false
		);
		if ( class_exists('ETheme_Navigation')) {
			$nav_args['walker'] = new \ETheme_Navigation;
        }

		ob_start();
		
		?>

        <div class="menu-element <?php echo esc_attr($atts['class']); ?>">
			
			<?php if ( ! empty( $atts['title'] ) ) { ?>
                <h5><?php echo esc_html($atts['title']); ?></h5>
			<?php } ?>
			
			<?php
			
			wp_nav_menu(
				$nav_args
			);
			
			?>

        </div>
		
		<?php
		
		$output = ob_get_clean();
		
		remove_filter('menu_subitem_with_arrow', '__return_false');
		
		if ( $atts['hide_submenus'] ) {
			remove_filter( 'menu_item_with_sublists', '__return_false' );
		}
		
		return $output;
		
	}
}
