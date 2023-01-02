<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Template "Navigation" for 8theme dashboard.
 *
 * @since   6.0.2
 * @version 1.0.2
 */

$mtips_notify = esc_html__('Register your theme and activate XStore Core plugin, please.', 'xstore');
$theme_active = etheme_is_activated();
$core_active = class_exists('ETC\App\Controllers\Admin\Import');
$amp_active = class_exists('XStore_AMP');

$system_requirements = $plugins = $theme_options = $generator = '';

$xstore_branding_settings = get_option( 'xstore_white_label_branding_settings', array() );

$show_pages = array(
	'welcome',
	'system_requirements',
	'demos',
	'plugins',
	'customize',
	'generator',
	'email_builder',
	'sales_booster',
	'custom_fonts',
	'maintenance_mode',
	'social',
	'support',
	'changelog',
);

if ( count($xstore_branding_settings) && isset($xstore_branding_settings['control_panel'])) {
	$show_pages_parsed = array();
	foreach ( $show_pages as $show_page ) {
		if ( isset($xstore_branding_settings['control_panel']['page_'.$show_page]))
			$show_pages_parsed[] = $show_page;
	};
	$show_pages = $show_pages_parsed;
}

$system = new Etheme_System_Requirements();
$system->system_test();
$result = $system->result();

$new_label = '<span style="margin-left: 5px; background: var(--et_admin_green-color, #489c33); letter-spacing: 1px; font-weight: 400; display: inline-block; text-transform: lowercase; border-radius: 3px; color: #fff; padding: 3px 2px 2px 3px; text-transform: uppercase; font-size: 8px; line-height: 1;">'.esc_html__('new', 'xstore').'</span>';
$hot_label = '<span style="margin-left: 5px; background: var(--et_admin_main-color, #A4004F); letter-spacing: 1px; font-weight: 400; display: inline-block; text-transform: lowercase; border-radius: 3px; color: #fff; padding: 3px 2px 2px 3px; text-transform: uppercase; font-size: 8px; line-height: 1;">'.esc_html__('hot', 'xstore').'</span>';

$info_label = '<span class="awaiting-mod" style="position: relative;min-width: 16px;height: 16px;margin: 2px 0 0 6px; background: #fff;"><span class="dashicons dashicons-warning" style="width: auto;height: auto;vertical-align: middle;position: absolute;left: -3px;top: -3px; color: var(--et_admin_orange-color); font-size: 22px;"></span></span>';

$changelog_icon = '';
$welcome_icon = '';
$check_update = new ETheme_Version_Check();
if( $check_update->is_update_available() )
	$changelog_icon = '
	<span style="		
	    	display: inline-block;
			position: relative;
		    min-width: 12px;
		    height: 12px;
		    margin: 0px 0px -2px 8px;
		    background: #fff;">
	    <span
	        style="
			    width: auto;
			    height: auto;
			    vertical-align: middle;
			    position: absolute;
			    left: -8px;
			    top: -5px;
			    font-size: 22px;"
	        class="dashicons dashicons-warning dashicons-warning et_admin_bullet-green-color"></span>
    </span>';
$is_update_support = 'active'; //$check_update->get_support_status();
if( $is_update_support !='active' ) {
	if ( $is_update_support == 'expire-soon' ) {
		$welcome_icon = '
			<span style="		
			        display: inline-block;
					position: relative;
				    min-width: 12px;
				    height: 12px;
				    margin: 0px 0px -2px 8px;
				    color: var(--et_admin_orange-color);
				    background: #fff;">
			    <span
			        style="
					    width: auto;
					    height: auto;
					    vertical-align: middle;
					    position: absolute;
					    left: -8px;
					    top: -5px;
					    font-size: 22px;"
			        class="dashicons dashicons-warning dashicons-warning et_admin_bullet-orange-color"></span>
		    </span>';
	} else {
		$welcome_icon = '
			<span style="		
			        display: inline-block;
					position: relative;
				    min-width: 12px;
				    height: 12px;
				    margin: 0px 0px -2px 8px;
				    color: var(--et_admin_red-color);
				    background: #fff;">
			    <span
			        style="
					    width: auto;
					    height: auto;
					    vertical-align: middle;
					    position: absolute;
					    left: -8px;
					    top: -5px;
					    font-size: 22px;"
			        class="dashicons dashicons-warning dashicons-warning et_admin_bullet-red-color"></span>
		    </span>';
	}
}


if ( in_array('customize', $show_pages) ) {
	if ( ! class_exists( 'Kirki' ) ) {
		$theme_options = sprintf(
			'<li class="mtips inactive"><a href="%s" class="et-nav%s et-nav-general">%s</a><span class="mt-mes">' . $mtips_notify . '</span></li>',
			admin_url( 'themes.php?page=install-required-plugins&plugin_status=all' ),
			( $_GET['page'] == 'et-panel-options' ) ? ' active' : '',
			esc_html__( 'Theme Options', 'xstore' )
		);
	} else {
		$theme_options = sprintf(
			'<li><a href="%s" class="et-nav%s et-nav-general">%s</a></li>',
			wp_customize_url(),
			( $_GET['page'] == 'et-panel-options' ) ? ' active' : '',
			esc_html__( 'Theme Options', 'xstore' )
		);
	}
}

if ( in_array('plugins', $show_pages) ) {
	$plugins = sprintf(
		( ! $theme_active ) ? '<li class="mtips inactive"><a href="%s" class="et-nav%s et-nav-social">%s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="et-nav%s et-nav-general">%s</a></li>',
		( $theme_active ) ? admin_url( 'admin.php?page=et-panel-plugins' ) : admin_url( 'admin.php?page=et-panel-welcome' ),
		( $_GET['page'] == 'et-panel-plugins' ) ? ' active' : '',
		esc_html__( 'Plugins Installer', 'xstore' )
	);
}

//if ( in_array('generator', $show_pages) ) {
//	$generator = sprintf(
//		( ! $theme_active ) ? '<li class="mtips inactive"><a href="%s" class="et-nav%s et-nav-social">%s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="et-nav%s et-nav-general">%s</a></li>',
//		( $theme_active ) ? admin_url( 'admin.php?page=et-panel-generator' ) : admin_url( 'admin.php?page=et-panel-welcome' ),
//		( $_GET['page'] == 'et-panel-generator' ) ? ' active' : '',
//		esc_html__( 'Files Generator', 'xstore' ) //. $new_label
//	);
//}

$out = '';
if ( in_array('welcome', $show_pages) ) {
	$out .= sprintf(
		'<li><a href="%s" class="et-nav%s et-nav-menu">%s '.$welcome_icon.'</a></li>',
		admin_url( 'admin.php?page=et-panel-welcome' ),
		( ! isset( $_GET['page'] ) || $_GET['page'] == 'et-panel-welcome' ) ? ' active' : '',
		esc_html__( 'Welcome', 'xstore' )
	
	);
}

if ( in_array('system_requirements', $show_pages) ) {
	$system_requirements = sprintf(
		'<li><a href="%s" class="et-nav%s et-nav-general">%s</a></li>',
		admin_url( 'admin.php?page=et-panel-system-requirements' ),
		( $_GET['page'] == 'et-panel-system-requirements' ) ? ' active' : '',
		esc_html__( 'Server Requirements', 'xstore' ) . ( ( ! $result && $theme_active ) ? $info_label : '' )
	);
}

if ( ! $theme_active ) {
	if ( in_array('demos', $show_pages) ) {
		$out .= sprintf(
			'<li class="mtips inactive"><a href="%s" class="et-nav%s et-nav-portfolio">%s</a><span class="mt-mes">' . $mtips_notify . '</span></li>',
			admin_url( 'admin.php?page=et-panel-demos' ),
			( $_GET['page'] == 'et-panel-demos' ) ? ' active' : '',
			esc_html__( 'Import Demos', 'xstore' )
		);
	}
	// $out .= sprintf(
	// 	( $theme_active ) ? '<li><a href="%s" class="et-nav%s et-nav-speed">%s</a></li>' : '<li class="mtips inactive"><a href="%s" class="et-nav%s et-nav-speed">%s</a><span class="mt-mes">'.$mtips_notify.'</span></li>',
	// 	admin_url( 'themes.php?page=install-required-plugins&plugin_status=all' ),
	// 	( $_GET['page'] == 'et-panel-plugins' ) ? ' active' : '',
	// 	esc_html__( 'Plugins', 'xstore' )
	// );
	$out .= $system_requirements . $plugins . $theme_options . $generator;
} else {
	if ( in_array('demos', $show_pages) ) {
		$out .= sprintf(
			'<li><a href="%s" class="et-nav%s et-nav-portfolio">%s</a></li>',
			admin_url( 'admin.php?page=et-panel-demos' ),
			( $_GET['page'] == 'et-panel-demos' ) ? ' active' : '',
			esc_html__( 'Import Demos', 'xstore' )
		);
	}
	// $out .= sprintf(
	// 	'<li><a href="%s" class="et-nav%s et-nav-speed">%s</a></li>',
	// 	admin_url( 'themes.php?page=install-required-plugins&plugin_status=all' ),
	// 	( $_GET['page'] == 'et-panel-plugins' ) ? ' active' : '',
	// 	esc_html__( 'Plugins', 'xstore' )
	// );
	$out .= $system_requirements . $plugins . $theme_options . $generator;
	
	if ( class_exists('WooCommerce') ) {
		
		if ( in_array( 'email_builder', $show_pages ) ) {
			$out .= sprintf(
				( ! $core_active || ! $theme_active ) ? '<li class="mtips inactive"><a href="%s" class="et-nav%s et-nav-email-builder">%s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="et-nav%s et-nav-email-builder">%s</a></li>',
				( $theme_active && $core_active ) ? admin_url( 'admin.php?page=et-panel-email-builder' ) : admin_url( 'admin.php?page=et-panel-welcome' ),
				( $_GET['page'] == 'et-panel-email-builder' ) ? ' active' : '',
				esc_html__( 'Built-in Email Builder', 'xstore' )
			);
		}
		
		if ( in_array( 'sales_booster', $show_pages ) ) {
			$out .= sprintf(
				( $theme_active && $core_active ) ? '<li><a href="%s" class="et-nav%s et-nav-sales-booster">%s</a></li>' : '<li class="mtips inactive"><a href="%s" class="et-nav%s et-nav-sales-booster">%s</a><span class="mt-mes">' . $mtips_notify . '</span></li>',
				( $theme_active && $core_active ) ? admin_url( 'admin.php?page=et-panel-sales-booster' ) : admin_url( 'admin.php?page=et-panel-welcome' ),
				( $_GET['page'] == 'et-panel-sales-booster' ) ? ' active' : '',
//				'🚀&nbsp;&nbsp;' . esc_html__( 'Sales Booster', 'xstore' ) . $new_label
				 esc_html__( 'Sales Booster', 'xstore' ) . $hot_label
			);
		}

		if ( !$amp_active ) {
			$amp_url = admin_url( 'admin.php?page=et-panel-plugins&plugin=xstore-amp' );
			
			$out .= sprintf(
			//'<li class="mtips"><a href="%s" class="et-nav%s et-nav-branding">⚡ %s <span style="margin-left: 5px; background: var(--et_admin_green-color, #489c33); letter-spacing: 1px; font-weight: 400; display: inline-block; text-transform: lowercase; border-radius: 3px; color: #fff; padding: 3px 2px 2px 3px; text-transform: uppercase; font-size: 8px; line-height: 1;">new</span>'.
				'<li class="mtips"><a href="%s" class="et-nav%s et-nav-branding"> %s <span style="margin-left: 5px; background: var(--et_admin_green-color, #489c33); letter-spacing: 1px; font-weight: 400; display: inline-block; text-transform: lowercase; border-radius: 3px; color: #fff; padding: 3px 2px 2px 3px; text-transform: uppercase; font-size: 8px; line-height: 1;">new</span>' .
				'<span class="mt-mes">' . esc_html__( 'Install and Activate XStore AMP plugin to use amp settings', 'xstore' ) . '</span></a></li>',
				( $theme_active && $core_active ) ? $amp_url : admin_url( 'admin.php?page=et-panel-welcome' ),
				( $_GET['page'] == 'et-panel-xstore-amp' ) ? ' active' : '',
				esc_html__( 'AMP XStore', 'xstore' )
			);
		}

	}

	if ( $theme_active && in_array('custom_fonts', $show_pages) ) {
		$out .= sprintf(
			'<li><a href="%s" class="et-nav%s et-nav-typography">%s</a></li>',
			admin_url( 'admin.php?page=et-panel-custom-fonts' ),
			( $_GET['page'] == 'et-panel-custom-fonts' ) ? ' active' : '',
			esc_html__( 'Custom Fonts', 'xstore' )
		);
	}
	
}

if ( in_array( 'maintenance_mode', $show_pages ) ) {
	$out .= sprintf(
		( ! $core_active || ! $theme_active ) ? '<li class="mtips inactive"><a href="%s" class="et-nav%s et-nav-general">%s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="et-nav%s et-nav-general">%s</a></li>',
		( $theme_active && $core_active ) ? admin_url( 'admin.php?page=et-panel-maintenance-mode' ) : admin_url( 'admin.php?page=et-panel-welcome' ),
		( $_GET['page'] == 'et-panel-maintenance-mode' ) ? ' active' : '',
		esc_html__( 'Maintenance Mode', 'xstore' )
	);
}

if ( in_array('social', $show_pages) ) {
	$out .= sprintf(
		( ! $core_active || ! $theme_active ) ? '<li class="mtips inactive"><a href="%s" class="et-nav%s et-nav-social">%s</a><span class="mt-mes">' . $mtips_notify . '</span></li>' : '<li><a href="%s" class="et-nav%s et-nav-social">%s</a></li>',
		( $theme_active && $core_active ) ? admin_url( 'admin.php?page=et-panel-social' ) : admin_url( 'admin.php?page=et-panel-welcome' ),
		( $_GET['page'] == 'et-panel-social' ) ? ' active' : '',
		esc_html__( 'Authorization APIs', 'xstore' )
	);
}

if ( in_array('support', $show_pages) ) {
	$out .= sprintf(
		( $theme_active && $core_active ) ? '<li><a href="%s" class="et-nav%s et-nav-support">%s</a></li>' : '<li class="mtips inactive"><a href="%s" class="et-nav%s et-nav-support">%s</a><span class="mt-mes">' . $mtips_notify . '</span></li>',
		( $theme_active && $core_active ) ? admin_url( 'admin.php?page=et-panel-support' ) : admin_url( 'admin.php?page=et-panel-welcome' ),
		( $_GET['page'] == 'et-panel-support' ) ? ' active' : '',
		esc_html__( 'Tutorials & Support', 'xstore' )
	);
}

if ( in_array('changelog', $show_pages) ) {
	$out .= sprintf(
		( $theme_active && $core_active ) ? '<li><a href="%s" class="et-nav%s et-nav-documentation">%s</a></li>' : '<li class="mtips inactive"><a href="%s" class="et-nav%s et-nav-documentation">%s</a><span class="mt-mes">' . $mtips_notify . '</span></li>',
		admin_url( 'admin.php?page=et-panel-changelog' ),
		( $_GET['page'] == 'et-panel-changelog' ) ? ' active' : '',
		esc_html__( 'Changelog', 'xstore' ) . $changelog_icon
	
	);
}

ob_start();
    do_action('etheme_last_dashboard_nav_item');
$out .= ob_get_clean();

echo'<div class="etheme-page-nav"><ul>' . $out . '</ul></div>';