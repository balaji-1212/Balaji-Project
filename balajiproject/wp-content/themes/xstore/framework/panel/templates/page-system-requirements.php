<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Template "Welcome" for 8theme dashboard.
 *
 * @since   6.3.10
 * @version 1.0.0
 */

if (isset($_GET['et_clear_wc_system_status_theme_info'])){
	delete_transient( 'wc_system_status_theme_info' );
	wp_redirect('?page=et-panel-system-requirements');
	exit;
}
?>

<h2 class="etheme-page-title etheme-page-title-type-2"><?php echo esc_html__('System Requirements', 'xstore'); ?></h2>
<p class="et-message et-info">
	<?php esc_html_e('Before using theme, first of all, make certain that your server and WordPress meet theme\'s requirements. You can change them by yourself, or contact your hosting provider with a request to increase the following minimums.', 'xstore'); ?>
</p>
<br/>
<?php
$system = new Etheme_System_Requirements();
    $system->html();
$result = $system->result();
?>

<div class="text-center">
	<a href="" class="et-button last-button">
            <span class="et-loader">
            <svg class="loader-circular" viewBox="25 25 50 50"><circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle></svg>
            </span><span class="dashicons dashicons-image-rotate"></span> <?php esc_html_e( 'Check again', 'xstore' ); ?>
	</a>
</div>

<br/>
<br/>
<h2 class="etheme-page-title etheme-page-title-type-2"><?php echo esc_html__('WooCommerce system info cache', 'xstore'); ?></h2>
<p class="et-message et-info">
	<?php esc_html_e('WooCommerce have system cache. If after theme update you still have out of date WooCommerce files, please clear this cache.', 'xstore'); ?>
</p>
<br/>

<div class="text-center">
    <a href="?page=et-panel-system-requirements&et_clear_wc_system_status_theme_info" class="et-button last-button">
            <span class="et-loader">
            <svg class="loader-circular" viewBox="25 25 50 50"><circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle></svg>
            </span><span class="dashicons dashicons-image-rotate"></span> <?php esc_html_e( 'Clear cache', 'xstore' ); ?>
    </a>
</div>
