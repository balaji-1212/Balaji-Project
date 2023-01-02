<div class="wrap about-wrap full-width-layout qlwrap">
	<form id="qlwapp_settings_form" method="post" action="options.php">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><?php esc_html_e( 'Google Analytics', 'wp-whatsapp-chat' ); ?></th>
					<td class="qlwapp-premium-field">
						<select name="googleAnalytics" class="qlwapp-select2">
							<option value="disable" <?php selected( $settings['googleAnalytics'], 'disable' ); ?>><?php esc_html_e( 'Disable', 'wp-whatsapp-chat' ); ?></option>
							<option value="v3" <?php selected( $settings['googleAnalytics'], 'v3' ); ?>><?php esc_html_e( 'GAv3', 'wp-whatsapp-chat' ); ?></option>
							<option value="v4" <?php selected( $settings['googleAnalytics'], 'v4' ); ?>><?php esc_html_e( 'GAv4', 'wp-whatsapp-chat' ); ?></option>
						</select>
						<p class="description"><?php esc_html_e( 'Switch to change the button layout.', 'wp-whatsapp-chat' ); ?></p>
						<p class="description hidden"><small><?php esc_html_e( '(This is a premium feature)', 'wp-whatsapp-chat' ); ?></small></p>
					</td>
				</tr>
				<tr class="<?php echo $settings['googleAnalytics'] == 'disable' ? 'hidden' : ''; ?> googleEventContainer">
					<th scope="row"><?php esc_html_e( 'Script', 'wp-whatsapp-chat' ); ?></th>
					<td class="qlwapp-premium-field">
						<select name="googleAnalyticsScript" class="qlwapp-select2">
							<option value="yes" <?php selected( $settings['googleAnalyticsScript'], 'yes' ); ?>><?php esc_html_e( 'Yes', 'wp-whatsapp-chat' ); ?></option>
							<option value="no" <?php selected( $settings['googleAnalyticsScript'], 'no' ); ?>><?php esc_html_e( 'No', 'wp-whatsapp-chat' ); ?></option>
						</select>
						<p class="description"><?php esc_html_e( 'Select "No" if you have Google Analytics activated via plugin or theme to prevent double load.', 'wp-whatsapp-chat' ); ?></p>
						<p class="description hidden"><small><?php esc_html_e( '(This is a premium feature)', 'wp-whatsapp-chat' ); ?></small></p>
					</td>
				</tr>
				<tr class="qlwapp-phone-alert <?php echo esc_attr( $settings['googleAnalytics'] == 'disabled' ? 'hidden' : '' ); ?>">
					<th scope="row"></th>
					<td>
					<span style="display:block!important;" class="notice notice-success">
						<p>
						<?php printf( __( 'Check our documentation to understand how to configure Google Analytics correctly <a href="%s" target="_blank">here</a>', 'wp-whatsapp-chat' ), esc_url( QLWAPP_DOCUMENTATION_URL ) ); ?>.
						</p>
					</span>
					</td>
				</tr>
				<tr class="<?php echo $settings['googleAnalytics'] == 'disable' ? 'hidden' : ''; ?> googleEventContainer">
					<th scope="row"><?php esc_html_e( 'Label', 'wp-whatsapp-chat' ); ?></th>
					<td class="qlwapp-premium-field">
						<input type="text" name="googleAnalyticsLabel" placeholder="" value="<?php echo esc_attr( $settings['googleAnalyticsLabel'] ); ?>" class="qlwapp-input" />
						<p class="description"><?php esc_html_e( '', 'wp-whatsapp-chat' ); ?></p>
					</td>
				</tr>
				<tr class="<?php echo $settings['googleAnalytics'] == 'disable' ? 'hidden' : ''; ?> googleEventContainer">
					<th scope="row"><?php esc_html_e( 'Category', 'wp-whatsapp-chat' ); ?></th>
					<td class="qlwapp-premium-field">
						<input type="text" name="googleAnalyticsCategory" placeholder="" value="<?php echo esc_attr( $settings['googleAnalyticsCategory'] ); ?>" class="qlwapp-input" />
						<p class="description"><?php esc_html_e( '', 'wp-whatsapp-chat' ); ?></p>
					</td>
				</tr>
				<tr class="<?php echo $settings['googleAnalytics'] != 'v3' ? 'hidden' : ''; ?> googlev3container">
					<th scope="row"><?php esc_html_e( 'Property Tracking ID ', 'wp-whatsapp-chat' ); ?></th>
					<td class="qlwapp-premium-field">
						<input type="text" name="googleAnalyticsV3Id" placeholder="UA-XXXXXXX-XX" value="<?php echo esc_attr( $settings['googleAnalyticsV3Id'] ); ?>" class="qlwapp-input" />
						<p class="description"><?php esc_html_e( 'Google Analytics 3 sample property tracking ID: UA-XXXXXXX-XX', 'wp-whatsapp-chat' ); ?></p>
					</td>
				</tr>
				<tr class="<?php echo $settings['googleAnalytics'] != 'v4' ? 'hidden' : ''; ?> googlev4container">
					<th scope="row"><?php esc_html_e( 'Data Stream Measurement ID ', 'wp-whatsapp-chat' ); ?></th>
					<td class="qlwapp-premium-field">
						<input type="text" name="googleAnalyticsV4Id" placeholder="G-XXXXXXXXXX" value="<?php echo esc_attr( $settings['googleAnalyticsV4Id'] ); ?>" class="qlwapp-input" />
						<p class="description"><?php esc_html_e( 'Google Analytics 4 sample data stream measurement ID: G-XXXXXXXXXX', 'wp-whatsapp-chat' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<?php wp_nonce_field( 'qlwapp_save_settings', 'qlwapp_settings_form_nonce' ); ?>
		<p class="submit">
			<?php submit_button( esc_html__( 'Save', 'wp-whatsapp-chat' ), 'primary', 'submit', false ); ?>
			<span class="settings-save-status">
				<span class="saved"><?php esc_html_e( 'Saved successfully!', 'wp-whatsapp-chat' ); ?></span>
				<span class="spinner" style="float: none"></span>
			</span>
		</p>
	</form>
</div>
