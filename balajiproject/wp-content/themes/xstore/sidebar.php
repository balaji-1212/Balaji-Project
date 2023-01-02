<?php

defined( 'ABSPATH' ) || exit( 'Direct script access denied.' );

$sidebar = get_query_var('et_sidebar', 'left');
if ( !$sidebar || in_array($sidebar, array('without', 'no_sidebar'))) return;

$sidebar_area = 'main-sidebar';
$widgetarea = get_query_var('et_widgetarea', false);
if(!empty($widgetarea) && $widgetarea != 'default') {
	$sidebar_area = $widgetarea;
}

$sidebar_sticky = etheme_get_option( 'sticky_sidebar', 0 ) && !get_query_var('is_yp');
if ( $sidebar_sticky ) {
	if ( !(get_query_var('et_sidebar', 'left') == 'off_canvas' || ( get_query_var('is_mobile', false) && get_query_var('et_sidebar-mobile', 'bottom') == 'off_canvas'))) {
		wp_enqueue_script( 'sticky-kit' );
		wp_enqueue_script( 'sticky_sidebar' );
	}
}

?>

<div class="<?php echo esc_attr( get_query_var('et_sidebar-class', 'col-md-3') ); ?> sidebar sidebar-<?php echo esc_attr( get_query_var('et_sidebar', 'left') ); ?>
<?php if ( $sidebar_sticky ) echo ' sticky-sidebar'; ?>">
	<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar($sidebar_area)): ?>
		<div class="sidebar-widget widget_search">
			<h4 class="widget-title"><?php esc_html_e('Search', 'xstore') ?></h4>
			<?php get_search_form(); ?>
		</div>
	<?php endif; ?>
</div>