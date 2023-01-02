
<span class="settings-save-status">
  <span class="spinner"></span>
  <span class="saved"><?php esc_html_e('Saved.'); ?></span>
</span>

<div class="details">
  <div class="filename"><strong><?php esc_html_e('Contact id', 'wp-whatsapp-chat'); ?>:</strong> {{data.id}}</div>
</div>

<div class="settings">
  <div class="upload">
    <img id="cavatar-img" class="qlwapp-avatar" data-src="{{data.avatar}}" src="{{data.avatar}}"  width="150" height="150"/>
    <div>
      <input type="hidden" name="avatar" id="cavatar" value="{{data.avatar}}" />
      <button type="button" class="upload_image_button button"><?php esc_html_e('Upload', 'wp-whatsapp-chat'); ?></button>
      <button type="button" class="remove_image_button button">&times;</button>
    </div>
  </div> 
</div>

<div class="actions">
  <a target="_blank" href="<?php echo QLWAPP_PURCHASE_URL; ?>"><?php esc_html_e('Premium', 'wp-whatsapp-chat'); ?></a> |
  <a target="_blank" href="<?php echo QLWAPP_DOCUMENTATION_URL; ?>"><?php esc_html_e('Documentation', 'wp-whatsapp-chat'); ?></a>
</div>
