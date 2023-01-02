<div id="tab_panel_contact" class="panel qlwapp_options_panel ">
	<div class="options_group">
		<p class="form-field" style="
				width: calc(50% - 30px);
				float: left;
				">
			<label><?php esc_html_e( 'Firstname', 'wp-whatsapp-chat' ); ?></label>
			<input type="text" name="firstname" placeholder="<?php echo esc_html( $contact_args['firstname'] ); ?>" value="{{data.firstname}}" />
		</p>
		<p class="form-field" style="
				width: calc(50% - 30px);
				float: right;
				">
			<label><?php esc_html_e( 'Lastname', 'wp-whatsapp-chat' ); ?></label>
			<input type="text" name="lastname" placeholder="<?php echo esc_html( $contact_args['lastname'] ); ?>" value="{{data.lastname}}" />
		</p>
	</div>

	<div class="options_group qlwapp-premium-field">
		<p class="form-field">
			<label><?php esc_html_e( 'Group', 'wp-whatsapp-chat' ); ?></label>
			<input type="radio" class="media-modal-change media-modal-subview2" name="type" value="phone" <# if(data.type=='phone') {#> checked <#}#> />
			<label><?php esc_html_e( 'Phone', 'wp-whatsapp-chat' ); ?></label>
			<input type="radio" class="media-modal-change media-modal-subview2" name="type" value="group" <# if(data.type=='group') {#> checked <#}#> />
			<label><?php esc_html_e( 'Group', 'wp-whatsapp-chat' ); ?></label>
			<span style="float: right;" class="description hidden"><small><?php esc_html_e( 'This is a premium feature', 'wp-whatsapp-chat' ); ?></small></span>
		</p>
	</div>

	<div class="options_group">
		<# if(data.type == 'phone' ) { #>
			<p class="form-field" style="width: calc(50% - 30px);float: left;">
				<label><?php esc_html_e( 'Phone', 'wp-whatsapp-chat' ); ?></label>
				<input type="text" name="phone" placeholder="<?php echo qlwapp_format_phone( $contact_args['phone'] ); ?>" value="{{data.phone}}" pattern="\d[0-9]{6,15}$" />
			</p>
		<# } else { #>
			<p class="form-field" style="width: calc(50% - 30px);float: left;">
				<label><?php esc_html_e( 'Group', 'wp-whatsapp-chat' ); ?></label>
				<input type="text" name="group" placeholder="<?php echo esc_url( $contact_args['group'] ); ?>" value="{{data.group}}" />
			</p>
		<# } #>
		<p class="form-field" style="width: calc(50% - 30px); float: right;">
			<?php esc_html_e( 'Label', 'wp-whatsapp-chat' ); ?>
			<input type="text" name="label" placeholder="<?php echo esc_html( $contact_args['label'] ); ?>" value="{{data.label}}" />
		</p>
	</div>
	<div class="options_group">
		<p class="form-field" style="
				width: calc(50% - 30px);
				float: left;
				">
			<label style="display: block"><?php esc_html_e( 'Available hours', 'wp-whatsapp-chat' ); ?></label>
			<input type="time" name="timefrom" placeholder="<?php echo esc_html( $contact_args['timefrom'] ); ?>" value="{{data.timefrom}}" />
			<?php esc_html_e( 'To', 'wp-whatsapp-chat' ); ?>
			<input type="time" name="timeto" placeholder="<?php echo esc_html( $contact_args['timeto'] ); ?>" value="{{data.timeto}}" />
		</p>
		<p class="form-field" style="
				width: calc(50% - 30px);
				float: right;
				">
			<label><?php esc_html_e( 'Timezone', 'wp-whatsapp-chat' ); ?></label>
			<select name="timezone" aria-describedby="timezone-description">
				<?php echo preg_replace( '/(.*)value="([^"]*)"(.*)/', '$1value="$2"<# if ( data.timezone  ==  "$2" ) { #> selected="selected"<# } #> $3', wp_timezone_choice( '__return_null' ) ); ?>
			</select>
		</p>
	</div>
	<div class="options_group qlwapp-premium-field">
		<p class="form-field">
			<label><?php esc_html_e( 'Visibility', 'wp-whatsapp-chat' ); ?></label>
			<select name="visibility">
				<option value="readonly" <# if( data.visibility=='readonly' ) { #> selected="selected"<# } #>><?php esc_html_e( 'Show the contact as readonly', 'wp-whatsapp-chat' ); ?></option>
				<option value="hidden" <# if( data.visibility=='hidden' ) { #> selected="selected"<# } #>><?php esc_html_e( 'Do not show the contact', 'wp-whatsapp-chat' ); ?></option>
			</select>
			<span class="description hidden"><?php esc_html_e( 'This is a premium feature', 'wp-whatsapp-chat' ); ?></small>
		</p>
	</div>
	<div class="options_group">
		<p class="form-field">
			<label><?php esc_html_e( 'Available days', 'wp-whatsapp-chat' ); ?></label>
			<select name="timedays[]" id="" multiple>
				<option value="0" <# if( data.timedays.includes('0')==true ) { #> selected="selected"<# } #>><?php esc_html_e( 'Sunday', 'wp-whatsapp-chat' ); ?></option>
				<option value="1" <# if( data.timedays.includes('1')==true ) { #> selected="selected"<# } #>><?php esc_html_e( 'Monday', 'wp-whatsapp-chat' ); ?></option>
				<option value="2" <# if( data.timedays.includes('2')==true ) { #> selected="selected"<# } #>><?php esc_html_e( 'Tuesday', 'wp-whatsapp-chat' ); ?></option>
				<option value="3" <# if( data.timedays.includes('3')==true ) { #> selected="selected"<# } #>><?php esc_html_e( 'Wednesday', 'wp-whatsapp-chat' ); ?></option>
				<option value="4" <# if( data.timedays.includes('4')==true ) { #> selected="selected"<# } #>><?php esc_html_e( 'Thursday', 'wp-whatsapp-chat' ); ?></option>
				<option value="5" <# if( data.timedays.includes('5')==true ) { #> selected="selected"<# } #>><?php esc_html_e( 'Friday', 'wp-whatsapp-chat' ); ?></option>
				<option value="6" <# if( data.timedays.includes('6')==true ) { #> selected="selected"<# } #>><?php esc_html_e( 'Saturday', 'wp-whatsapp-chat' ); ?></option>
			</select>
		</p>
	</div>
	<div class="options_group qlwapp-premium-field">
		<p class="form-field">
			<label><?php esc_html_e( 'Message', 'wp-whatsapp-chat' ); ?></label>
			<textarea style="width:100%" name="message">{{ _.escapeHtml(data.message)}}</textarea>
			<# if(data.chat == false) { #>
				<span class="description"><small><?php esc_html_e( 'Default message sent to the contact number.', 'wp-whatsapp-chat' ); ?></small></span>
			<# } else { #>
				<span class="description"><small><?php esc_html_e( 'Welcome message sent to the user in the chat box.', 'wp-whatsapp-chat' ); ?></small></span>
			<# } #>
			<br/>
			<span class="description"><small><?php esc_html_e( 'You can use this vars:', 'wp-whatsapp-chat' ); ?><code><?php echo esc_html( qlwapp_get_replacements_text() ); ?></code></small></span>
		</p>
	</div>
	<div class="options_group qlwapp-premium-field">
		<p class="form-field">
			<label><?php esc_html_e( 'Chat', 'wp-whatsapp-chat' ); ?></label>
			<input type="radio" class="media-modal-change media-modal-subview2" name="chat" value="1" <# if(data.chat) {#> checked <#}#> />
			<label><?php esc_html_e( 'Enabled', 'wp-whatsapp-chat' ); ?></label>
			<input type="radio" class="media-modal-change media-modal-subview2" name="chat" value="0" <# if(data.chat==false) {#> checked <#}#> />
			<label><?php esc_html_e( 'Disabled', 'wp-whatsapp-chat' ); ?></label>
			<span style="float: right;" class="description hidden"><small><?php esc_html_e( 'This is a premium feature', 'wp-whatsapp-chat' ); ?></small></span>
		</p>
	</div>
</div>
