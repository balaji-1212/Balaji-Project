<div class="wrap about-wrap full-width-layout qlwrap">
	<form id="qlwapp_woocommerce_form" method="post" action="options.php">
		<table class="form-table">
			<tbody>
			<tr class="qlwapp-phone-alert <?php echo esc_attr( $woocommerce['box'] == 'yes' ? '' : 'hidden' ); ?>">
				<th scope="row"></th>
				<td>
				<span style="display:block!important;" class="notice notice-error">
					<p>
					<?php printf( __( 'Contact box is enabled. Please set the contact phone number in the <a href="%s">contacts tab</a>', 'wp-whatsapp-chat' ), admin_url( 'admin.php?page=' . QLWAPP_DOMAIN . '_contacts' ) ); ?>.
					</p>
				</span>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Rounded', 'wp-whatsapp-chat' ); ?></th>
				<td>
				<select name="rounded" class="qlwapp-select2">
					<option value="yes" <?php selected( $woocommerce['rounded'], 'yes' ); ?>><?php esc_html_e( 'Add rounded border', 'wp-whatsapp-chat' ); ?></option>
					<option value="no" <?php selected( $woocommerce['rounded'], 'no' ); ?>><?php esc_html_e( 'Remove rounded border', 'wp-whatsapp-chat' ); ?></option>
				</select>
				<p class="description hidden"><?php esc_html_e( 'Add rounded border to the button.', 'wp-whatsapp-chat' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Icon', 'wp-whatsapp-chat' ); ?></th>
				<td>
				<div class="submit qlwapp-premium-field">
					<?php submit_button( esc_html__( 'Add Icon', 'wp-whatsapp-chat' ), 'secondary', null, false, array( 'id' => 'qlwapp_icon_add' ) ); ?>
					<p class="description hidden"><small><?php esc_html_e( 'This is a premium feature', 'wp-whatsapp-chat' ); ?></small></p>
				</div>
				<input type="text" name="icon" placeholder="<?php echo esc_html( $woocommerce['icon'] ); ?>" value="<?php echo esc_attr( $woocommerce['icon'] ); ?>" class="qlwapp-input" />
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Text', 'wp-whatsapp-chat' ); ?></th>
				<td>
				<input type="text" name="text" placeholder="<?php echo esc_html( $woocommerce['text'] ); ?>" value="<?php echo esc_attr( $woocommerce['text'] ); ?>" class="qlwapp-input" />
				<p class="description"><?php esc_html_e( 'Customize your button text.', 'wp-whatsapp-chat' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Phone', 'wp-whatsapp-chat' ); ?></th>
				<td>
				<input type="text" name="phone" placeholder="" value="<?php echo esc_attr( $woocommerce['phone'] ); ?>" class="qlwapp-input <?php echo esc_attr( $woocommerce['box'] == 'yes' ? 'disabled' : '' ); ?>" required="required" />
				<p class="description"><?php esc_html_e( 'Full phone number in international format. Only nnumbers.', 'wp-whatsapp-chat' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Message', 'wp-whatsapp-chat' ); ?></th>
				<td>
				<textarea maxlength="500" style="width:75%;height:50px;padding:8px;" name="message" placeholder="<?php echo esc_html( $woocommerce['message'] ); ?>"><?php echo esc_html( trim( $woocommerce['message'] ) ); ?></textarea>
				<p class="description"><?php esc_html_e( 'Message that will automatically appear in the text field of a chat.', 'wp-whatsapp-chat' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Schedule', 'wp-whatsapp-chat' ); ?></th>
				<td class="qlwapp-premium-field">
				<b><?php esc_html_e( 'From', 'wp-whatsapp-chat' ); ?></b>
				<input type="time" name="timefrom" placeholder="<?php echo esc_html( $woocommerce['timefrom'] ); ?>" value="<?php echo esc_html( $woocommerce['timefrom'] ); ?>" />
				<b><?php esc_html_e( 'To', 'wp-whatsapp-chat' ); ?></b>
				<input type="time" name="timeto" placeholder="<?php echo esc_html( $woocommerce['timeto'] ); ?>" value="<?php echo esc_html( $woocommerce['timeto'] ); ?>" />
				<p class="description hidden"><small><?php esc_html_e( 'This is a premium feature', 'wp-whatsapp-chat' ); ?></small></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Timezone', 'wp-whatsapp-chat' ); ?></th>
				<td class="qlwapp-premium-field">
				<select name="timezone" aria-describedby="timezone-description" required="" class="qlwapp-select2">
					<?php echo wp_timezone_choice( $woocommerce['timezone'], get_user_locale() ); ?>
				</select>
				<p class="description"><small><?php esc_html_e( 'Hide button if the user is out of the available hours.', 'wp-whatsapp-chat' ); ?></small></p>
				<p class="description hidden"><small><?php esc_html_e( 'This is a premium feature', 'wp-whatsapp-chat' ); ?></small></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Available days', 'wp-whatsapp-chat' ); ?></th>
				<td class="qlwapp-premium-field">
				<select name="timedays[]" multiple style="height:100px;">
					<option value="0" <?php echo in_array( '0', $woocommerce['timedays'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Sunday', 'wp-whatsapp-chat' ); ?></option>
					<option value="1" <?php echo in_array( '1', $woocommerce['timedays'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Monday', 'wp-whatsapp-chat' ); ?></option>
					<option value="2" <?php echo in_array( '2', $woocommerce['timedays'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Tuesday', 'wp-whatsapp-chat' ); ?></option>
					<option value="3" <?php echo in_array( '3', $woocommerce['timedays'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Wednesday', 'wp-whatsapp-chat' ); ?></option>
					<option value="4" <?php echo in_array( '4', $woocommerce['timedays'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Thursday', 'wp-whatsapp-chat' ); ?></option>
					<option value="5" <?php echo in_array( '5', $woocommerce['timedays'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Friday', 'wp-whatsapp-chat' ); ?></option>
					<option value="6" <?php echo in_array( '6', $woocommerce['timedays'] ) ? 'selected="selected"' : ''; ?>><?php esc_html_e( 'Saturday', 'wp-whatsapp-chat' ); ?></option>
				</select>
				<p class="description hidden"><small><?php esc_html_e( 'This is a premium feature', 'wp-whatsapp-chat' ); ?></small></p>
				</td>
				</th>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Position', 'wp-whatsapp-chat' ); ?></th>
				<td>
				<select name="position">
					<?php foreach ( $positions as $position => $name ) : ?>
						<option value="<?php echo esc_attr( $position ); ?>" <?php selected( $woocommerce['position'], $position ); ?>>
							<?php echo esc_html( $name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
				</td>
				</th>
			</tr>
			<tr>
				<th scope="row"><?php esc_html_e( 'Position Priority', 'wp-whatsapp-chat' ); ?></th>
				<td>
					<input type="number" step="5" min="-100" max="100" name="position_priority" placeholder="<?php echo esc_html( $woocommerce['position_priority'] ); ?>" value="<?php echo esc_attr( $woocommerce['position_priority'] ); ?>" />
					<p class="description"><?php esc_html_e( 'Position priority', 'wp-whatsapp-chat' ); ?></p>
				</td>
			</tr>
			</tbody>
		</table>
		<?php wp_nonce_field( 'qlwapp_save_woocommerce', 'qlwapp_woocommerce_form_nonce' ); ?>
		<p class="submit">
			<?php submit_button( esc_html__( 'Save', 'wp-whatsapp-chat' ), 'primary', 'submit', false ); ?>
			<span class="settings-save-status">
			<span class="saved"><?php esc_html_e( 'Saved successfully!' ); ?></span>
			<span class="spinner" style="float: none"></span>
			</span>
		</p>
	</form>
</div>
