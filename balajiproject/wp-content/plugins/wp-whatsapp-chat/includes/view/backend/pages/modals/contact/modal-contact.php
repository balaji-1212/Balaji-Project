<!--qlwapp_modal-->
<div class="media-modal-backdrop">&nbsp;</div> 
<div tabindex="0" id="<?php echo esc_attr(QLWAPP_DOMAIN . '_modal'); ?>" class="media-modal wp-core-ui upload-php qlwapp-modal-contact processing" role="dialog" aria-modal="true" aria-labelledby="media-frame-title">
  <div class="media-modal-content" role="document">
    <form class="media-modal-form" method="POST" data-contact_id="{{data.id}}">
      <# if ( data.id != undefined ) { #>
      <input type="hidden" name="id" value="{{data.id}}" />
      <input type="hidden" name="order" value="{{data.order}}" />
      <# } #>
      <div class="edit-attachment-frame mode-select hide-menu hide-router"> 
        <div id="panel-header" > </div>
        <div class="media-frame-content" style="bottom:61px;">
          <div class="attachment-details">
            <div class="attachment-media-view landscape" style="height:100%;">
              <div class="panel-wrap" style="height:100%;">
                <div id="panel-tabs"></div>
                <div id="panel-container" style="height: 100%;overflow-x: hidden;">
                  <div id="panel-contact"></div>
                  <div id="panel-visibility"></div>
                </div>
              </div>
            </div>
            <div class="attachment-info">
              <div id="panel-info" ></div>
            </div>
          </div> 
        </div>
        <div class="media-frame-toolbar" style="left:0;">
          <div class="media-toolbar">
            <div id="panel-footer" ></div> 
          </div>
        </div>
      </div>
    </form>
  </div>
</div>