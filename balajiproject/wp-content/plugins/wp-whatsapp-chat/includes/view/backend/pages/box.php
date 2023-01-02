<div class="wrap about-wrap full-width-layout qlwrap">
  <form id="qlwapp_box_form" method="post" action="options.php">
	<table class="form-table">
		<tbody>
		<tr>
			<th scope="row"><?php esc_html_e( 'Auto open', 'wp-whatsapp-chat' ); ?></th>
			<td class="qlwapp-premium-field">
			<select name="auto_open" class="qlwapp-select2">
				<option value="yes" <?php selected( $box['auto_open'], 'yes' ); ?>><?php esc_html_e( 'Enable auto open', 'wp-whatsapp-chat' ); ?></option>
				<option value="no" <?php selected( $box['auto_open'], 'no' ); ?>><?php esc_html_e( 'Disable auto open', 'wp-whatsapp-chat' ); ?></option>
			</select>
			<p class="description hidden"><small><?php esc_html_e( 'This is a premium feature', 'wp-whatsapp-chat' ); ?></small></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Delay', 'wp-whatsapp-chat' ); ?></th>
			<td class="qlwapp-premium-field">
			<input type="number" step="100" name="auto_delay_open" placeholder="<?php echo esc_html( $box['auto_delay_open'] ); ?>" value="<?php echo esc_attr( $box['auto_delay_open'] ); ?>" />
			<p class="description"><?php esc_html_e( 'In miliseconds', 'wp-whatsapp-chat' ); ?></p>
			<p class="description hidden"><small><?php esc_html_e( 'This is a premium feature', 'wp-whatsapp-chat' ); ?></small></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Header', 'wp-whatsapp-chat' ); ?></th>
			<td>
			<?php
			wp_editor(
				$box['header'],
				'qlwapp_box_header',
				array(
					'editor_height' => 100,
					'textarea_name' => 'header',
					'tinymce'       => array( 'init_instance_callback' => 'function(editor) {   editor.on("change", function(e){jQuery(document).trigger("tinymce_change");}); }' ),
				)
			);
			?>
			<p class="description"><?php esc_html_e( 'You can use this vars:', 'wp-whatsapp-chat' ); ?><small><code><?php echo esc_html( qlwapp_get_replacements_text() ); ?></code></small></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Footer', 'wp-whatsapp-chat' ); ?></th>
			<td>
			<?php
			// wp_editor($box['footer'], 'qlwapp_box_footer', array('editor_height' => 50, 'textarea_name' => 'footer'));
			?>
			<?php
			wp_editor(
				$box['footer'],
				'qlwapp_box_footer',
				array(
					'editor_height' => 50,
					'textarea_name' => 'footer',
					'tinymce'       => array( 'init_instance_callback' => 'function(editor) {   editor.on("change", function(){jQuery(document).trigger("tinymce_change");}); }' ),
				)
			);
			?>
			<p class="description"><?php esc_html_e( 'You can use this vars:', 'wp-whatsapp-chat' ); ?><small><code><?php echo esc_html( qlwapp_get_replacements_text() ); ?></code></small></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Response', 'wp-whatsapp-chat' ); ?></th>
			<td>
			<input type="text" name="response" placeholder="<?php echo esc_html( $box['response'] ); ?>" value="<?php echo esc_attr( $box['response'] ); ?>" class="qlwapp-input" />
			<p class="description hidden"><?php esc_html_e( 'Write a response text.', 'wp-whatsapp-chat' ); ?></p>
			</td>
		</tr>

		</tbody>
	</table>
	<?php wp_nonce_field( 'qlwapp_save_box', 'qlwapp_box_form_nonce' ); ?>
	<p class="submit">
		<?php submit_button( esc_html__( 'Save', 'wp-whatsapp-chat' ), 'primary', 'submit', false ); ?>
		<span class="settings-save-status">
		<span class="saved"><?php esc_html_e( 'Saved successfully!' ); ?></span>
		<span class="spinner" style="float: none"></span>
		</span>
	</p>
  </form>
</div>
