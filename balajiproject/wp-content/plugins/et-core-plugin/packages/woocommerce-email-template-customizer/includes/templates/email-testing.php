<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div>
    <div>
        <label><?php esc_html_e( 'Select order', 'xstore-core' ); ?></label>
        <select class="viwec-order-id-test">
			<?php
			if ( ! empty( $orders ) ) {
				foreach ( $orders as $order ) {
					$name   = method_exists( $order, 'get_formatted_billing_full_name' ) ? $order->get_formatted_billing_full_name() : '';
					$name   = trim( $name ) ? ' - ' . $name : '';
					$status = $order->get_status();
					$status = trim( $status ) ? ' - ' . $status : '';
					?>
                    <option value='<?php echo esc_attr( $order->get_id() ); ?>'>
                        #<?php echo esc_html( $order->get_id() . $name . $status ) ?>
                    </option>
					<?php
				}
			}
			?>
        </select>
    </div>
    <div>
        <label><?php esc_html_e( 'Send to', 'xstore-core' ); ?></label>
        <input type="text" class="viwec-to-email" value="<?php echo esc_html( get_bloginfo( 'admin_email' ) ) ?>">
    </div>
    <div class="viwec-btn-group vi-ui buttons">
        <button type="button" class="viwec-preview-email-btn desktop vi-ui button mini attached"
                title="<?php esc_html_e( 'Preview width device screen width > 380px', 'xstore-core' ); ?>">
            <i class="dashicons dashicons-laptop"></i></button>
        <button type="button" class="viwec-preview-email-btn mobile vi-ui button mini attached"
                title="<?php esc_html_e( 'Preview width device screen width < 380px', 'xstore-core' ); ?>">
            <i class="dashicons dashicons-smartphone"></i></button>
        <button type="button" class="viwec-send-test-email-btn vi-ui button mini attached"
                title="<?php esc_html_e( 'Send test email', 'xstore-core' ); ?>">
            <i class="dashicons dashicons-email"></i></button>
    </div>
    <div class="viwec-send-test-email-result et-message"></div>
</div>

<div class="vi-ui longer modal ">
    <i class="icon close dashicons dashicons-no-alt"></i>

    <div class="header">
		<?php esc_html_e( 'Preview', 'xstore-core' ); ?>
        <div class="viwec-view-btn-group vi-ui buttons">
            <button class="vi-ui button mini viwec-pc-view attached">
                <i class="dashicons dashicons-laptop "
                   title="<?php esc_html_e( 'Desktop & mobile (width >380px)', 'xstore-core' ); ?>"></i>
            </button>
            <button class="vi-ui button mini viwec-mobile-view attached">
                <i class="dashicons dashicons-smartphone"
                   title="<?php esc_html_e( 'View mobile version (width < 380px)', 'xstore-core' ); ?>"></i>
            </button>
            <button class="vi-ui button mini viwec-send-test-email-btn attached">
                <i class="dashicons dashicons-email "
                   title="<?php esc_html_e( 'Send test email', 'xstore-core' ); ?>"></i>
            </button>
        </div>
    </div>

    <div class="content scrolling">
        <div class="viwec-email-preview-content">

        </div>
    </div>

    <div class="actions">

    </div>
</div>
