<div class="edit-media-header ">
  <# if (data.id != undefined ) {  #>  
  <button type="button" class="media-modal-prev left dashicons qlwapp-premium-field" <# if ( data.order == 1 ) { #>disabled="disabled"<# } #>><span class="screen-reader-text"><?php esc_html_e('Edit previous media item'); ?></span></button>
  <button type="button" class="media-modal-next right dashicons qlwapp-premium-field" <# if ( data.order == <?php echo esc_attr(count($contacts)); ?> ) { #>disabled="disabled"<# } #>><span class="screen-reader-text"><?php esc_html_e('Edit next media item'); ?></span></button>
  <#  } #>
  <button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text"><?php esc_html_e('Close dialog'); ?></span></span></button>       
</div> 
<div class="media-frame-title">
  <h1><?php esc_html_e('Edit Contact:', 'wp-whatsapp-chat'); ?>  <# if ( data.id != undefined ) { #>{{data.firstname}} {{data.lastname}}<# } else { #><?php echo esc_html_e('new', 'wp-whatsapp-chat'); ?><# } #></h1>
</div>