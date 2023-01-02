<?php

use VIWEC\INC\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$email_types      = Utils::get_email_ids();
$email_types1     = Utils::get_email_ids_grouped();
$admin_email_type = Utils::admin_email_type()

?>
<div>
    <div class="viwec-setting-row">
        <select class="viwec-input viwec-set-email-type" name="viwec_settings_type" required>
			<?php
			$admin = $customer = [];

			printf( "<option value='default' %1s>%3s</option>", esc_attr( $type_selected == 'default' ? 'selected' : '' ), esc_html__( $email_types['default'] ) );

			foreach ( $email_types as $id => $title ) {
				if ( $id == 'default' ) {
					continue;
				}

				$selected = $type_selected == $id ? 'selected' : '';

				if ( in_array( $id, $admin_email_type ) ) {
					$admin[] = sprintf( "<option value='%1s' %2s>%3s</option>", esc_attr( $id ), esc_attr( $selected ), esc_html( $title ) );
				} else {
					$customer[] = sprintf( "<option value='%1s' %2s>%3s</option>", esc_attr( $id ), esc_attr( $selected ), esc_html( $title ) );
				}
			}

			?>
            <optgroup label="<?php esc_html_e( 'Admin', 'xstore-core' ); ?>">
				<?php echo implode( '', $admin ); ?>
            </optgroup>
            <optgroup label="<?php esc_html_e( 'Customer', 'xstore-core' ); ?>">
				<?php echo implode( '', $customer ); ?>
            </optgroup>
        </select>
    </div>
	<?php do_action( 'viwec_setting_options', $type_selected ); ?>
</div>
