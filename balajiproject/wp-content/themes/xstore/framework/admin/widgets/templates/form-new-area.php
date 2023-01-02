<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' ); ?>
<form action="<?php echo admin_url( 'widgets.php' ); ?>" method="post" id="etheme_add_sidebar_form">
	<h2>Custom Sidebar</h2>
	<?php wp_nonce_field( 'etheme-add-sidebar-widgets', '_wpnonce_etheme_widgets', false ); ?>
	<p><?php esc_html_e('You can use only upper and lower case letters, numbers, and symbols like - or _', 'xstore'); ?></p>
	<input type="text" name="etheme_sidebar_name" id="etheme_sidebar_name" />
	<button type="submit" class="button-primary" value="add-sidebar">Add Sidebar</button>
</form>

