<div class="wrap about-wrap full-width-layout qlwrap">
	<form id="qlwapp_contacts_form" method="post">
		<div class="submit qlwapp-premium-field"> 
			<?php submit_button( esc_html__( '+ Contact', 'wp-whatsapp-chat' ), 'secondary', 'submit', false, array( 'id' => 'qlwapp_contact_add' ) ); ?>
			<?php
			submit_button(
				esc_html__( 'Save reorder', 'wp-whatsapp-chat' ),
				'primary',
				'submit',
				false,
				array(
					'id'       => 'qlwapp_contact_order',
					'disabled' => 'disabled',
				)
			);
			?>
			<span class="settings-save-status">
			<span class="spinner"></span>
			<span class="saved"><?php esc_html_e( 'Saved successfully!' ); ?></span>
			</span>	
		</div>

		<table id="qlwapp_contacts_table" class="form-table widefat striped">
			<thead>
			<tr>
				<th style="text-align: center;"><?php esc_html_e( 'Order', 'wp-whatsapp-chat' ); ?></th>
				<th><?php esc_html_e( 'Avatar', 'wp-whatsapp-chat' ); ?></th>
				<th><?php esc_html_e( 'Phone', 'wp-whatsapp-chat' ); ?></th>
				<th><?php esc_html_e( 'Name', 'wp-whatsapp-chat' ); ?></th>
				<th><?php esc_html_e( 'Label', 'wp-whatsapp-chat' ); ?></th>
				<th><?php esc_html_e( 'Chat', 'wp-whatsapp-chat' ); ?></th> 
				<th><?php esc_html_e( 'Message', 'wp-whatsapp-chat' ); ?></th> 
				<th><?php esc_html_e( 'Availability', 'wp-whatsapp-chat' ); ?></th>
				<th><?php esc_html_e( 'Timezone', 'wp-whatsapp-chat' ); ?></th>
				<th ><?php esc_html_e( 'Actions', 'wp-whatsapp-chat' ); ?></th>
			</tr>
			</thead>
			<tbody class="ui-sortable">
			<?php if ( count( $contacts ) ) : ?>
				<?php
				$position = 1;
				foreach ( $contacts as $id => $contact ) {
					?>
				<tr class="
					<?php
					if ( $position > 1 ) {
						echo 'qlwapp-premium-field';}
					?>
				" data-contact_id="<?php echo esc_attr( $id ); ?>" data-contact_position="<?php echo esc_attr( $position ); ?>"> 
					<td class="sort ui-sortable-handle">
					<div class="wc-item-reorder-nav"> 
						<button type="button" class="wc-move-up " tabindex="-1" aria-hidden="true" aria-label="<?php // echo ///esc_attr(sprintf(__('Move the "%s" payment method up', 'wp-whatsapp-chat'), $contact['label'])); ?>"><?php esc_html_e( 'Move up', 'wp-whatsapp-chat' ); ?></button>
						<button type="button" class="wc-move-down" tabindex="0" aria-hidden="false" aria-label="<?php // echo esc_attr(sprintf(__('Move the "%s" payment method down', 'wp-whatsapp-chat'), $field['label'])); ?>"><?php esc_html_e( 'Move down', 'wp-whatsapp-chat' ); ?></button>
						<input type="hidden" name="contact_id[]" value="<?php echo esc_attr( $id ); ?>"> 
					</div>
					</td> 
					<td>
					<img class="qlwapp-avatar" src="<?php echo esc_url( $contact['avatar'] ); ?>" alt="<?php echo esc_html( $contact['firstname'] . ', ' . $contact['lastname'] ); ?>" width="50" height="50" />			 </td>	
					<td><?php echo qlwapp_format_phone( $contact['phone'] ); ?></td> 
					<td><?php echo esc_html( $contact['firstname'] . ', ' . $contact['lastname'] ); ?> </td>	
					<td><?php echo esc_html( $contact['label'] ); ?></td>	
					<td>
					<i class="dashicons dashicons-<?php echo ( $contact['chat'] ? 'yes' : 'no' ); ?>"></i>
					</td>	
					<td><?php echo wp_trim_words( substr( $contact['message'], 0, 36 ), 3 ); ?></td>	
					<td><?php echo sprintf( '%s to %s', $contact['timefrom'], $contact['timeto'] ); ?></td>	 
					<td><?php echo esc_html( $contact['timezone'] ); ?></td> 
					<td>
					<a class="qlwapp_settings_edit button" aria-label="<?php esc_html_e( 'Edit checkout field', 'wp-whatsapp-chat' ); ?>" href="javascript:;"><?php esc_html_e( 'Edit' ); ?></a>
					<a class="qlwapp_settings_delete" aria-label="<?php esc_html_e( 'Edit checkout field', 'wp-whatsapp-chat' ); ?>" href="javascript:;"><?php esc_html_e( 'Delete' ); ?></a> 
					</td>	
				</tr>	
					<?php
					$position++;
				}
				?>					
			</tbody>
			<?php endif; ?>
		</table>	 
		<?php wp_nonce_field( 'qlwapp_delete_contact', 'qlwapp_delete_contact_nonce' ); ?>	 
	</form>
</div>

<?php require_once 'modals/template-scripts.php'; ?>
