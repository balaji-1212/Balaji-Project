<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Template "Welcome" for 8theme dashboard.
 *
 * @since   8.1.2
 * @version 1.0.0
 */

$maintenance_mode_page_options = array();

$maintenance_mode_page_options['is_enabled'] = get_option('etheme_maintenance_mode', false);
?>

    <h2 class="etheme-page-title etheme-page-title-type-2"><?php echo esc_html__('Maintenance Mode', 'xstore'); ?></h2>
    <p class="et-message et-info">
		<?php
            esc_html_e('Maintenance mode allows you to display a user-friendly notice to your visitors instead of a broken site during website maintenance. Build a cool maintenance page that will be shown to your site visitors. Only registered users with enough rights can see the front end. Switch it off if you want to launch the site back.', 'xstore');
        ?>
        <br/>
        <?php
            esc_html_e('You may create the maintenance page from scratch at Dashboard - Pages - Add new. Choose "Maintenance" in the "Template" list of "Page attributes". Or you can import the page from our demo in XStore Control Panel -> Import demos -> Coming Soon demos.', 'xstore');
		?>
    </p>
    <p>
        <label class="et-panel-option-switcher<?php if ( $maintenance_mode_page_options['is_enabled']) { ?> switched<?php } ?>" for="et_maintenance_mode">
            <input type="checkbox" id="et_maintenance_mode" name="et_maintenance_mode" <?php if ( $maintenance_mode_page_options['is_enabled']) { ?>checked<?php } ?>>
            <span></span>
        </label>
    </p>

<?php if ( $maintenance_mode_page_options['is_enabled'] ) : ?>
    <p class="et-message">
		<?php echo esc_html__('Your maintenance mode is activated. Add maintenance page by clicking the button below.', 'xstore'); ?>
    </p>
    <a href="<?php echo admin_url( 'edit.php?post_type=page' ); ?>" class="et-button et-button-green no-loader" target="_blank">
		<?php esc_html_e('Go to Pages', 'xstore'); ?>
    </a>
<?php endif; ?>

<?php unset($maintenance_mode_page_options); ?>