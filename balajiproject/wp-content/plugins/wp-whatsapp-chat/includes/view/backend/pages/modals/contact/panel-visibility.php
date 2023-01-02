<div id="tab_panel_visibility" class="panel qlwapp_options_panel hidden" style="display: none;">
  <div class="options_group">
    <p class="form-field">
      <label><?php esc_html_e('Devices', 'wp-whatsapp-chat'); ?></label>
      <select name="display[devices]" data-placeholder="<?php echo esc_attr('Choose target&hellip;', 'wp-whatsapp-chat'); ?>" aria-label="<?php echo esc_attr('Posts', 'wp-whatsapp-chat'); ?>" class="qlwapp-select2">
        <option <# if ( data.display.devices == '<?php echo esc_attr('all'); ?>') { #>selected="selected"<# } #> value="<?php echo esc_attr('all'); ?>"><?php echo esc_html('all'); ?></option>
        <option <# if ( data.display.devices == '<?php echo esc_attr('mobile'); ?>') { #>selected="selected"<# } #> value="<?php echo esc_attr('mobile'); ?>"><?php echo esc_html('mobile'); ?></option>
        <option <# if ( data.display.devices == '<?php echo esc_attr('desktop'); ?>') { #>selected="selected"<# } #> value="<?php echo esc_attr('desktop'); ?>"><?php echo esc_html('desktop'); ?></option>
        <option <# if ( data.display.devices == '<?php echo esc_attr('hide'); ?>') { #>selected="selected"<# } #> value="<?php echo esc_attr('hide'); ?>"><?php echo esc_html('hide'); ?></option>
      </select>
    </p>
  </div>
  <div class="options_group">
    <p class="form-field">
      <label><?php esc_html_e('Target', 'wp-whatsapp-chat'); ?></label>
      <select name="display[target][include]" class="qlwapp-select2"> 
        <option <# if ( data.display.target['include'] == 1 ) { #>selected="selected"<# } #> value="1"><?php esc_html_e('Include'); ?></option>
        <option <# if ( data.display.target['include'] == 0 ) { #>selected="selected"<# } #> value="0"><?php esc_html_e('Exclude'); ?></option> 
      </select> 

      <select multiple="multiple" name="display[target][ids][]" data-placeholder="<?php echo esc_attr('Choose target&hellip;', 'wp-whatsapp-chat'); ?>" aria-label="<?php echo esc_attr('Posts', 'wp-whatsapp-chat'); ?>" class="qlwapp-select2">
        <option <# if ( _.contains(data.display.target['ids'],'all')  ) { #>selected="selected"<# } #> value="<?php echo esc_attr('all'); ?>"><?php echo esc_html('all'); ?></option>
        <option <# if ( _.contains(data.display.target['ids'],'home')  ) { #>selected="selected"<# } #> value="<?php echo esc_attr('home'); ?>"><?php echo esc_html('home'); ?></option>
        <option <# if ( _.contains(data.display.target['ids'], 'blog')  ) { #>selected="selected"<# } #> value="<?php echo esc_attr('blog'); ?>"><?php echo esc_html('blog'); ?></option>
        <option <# if ( _.contains(data.display.target['ids'],'search')) { #>selected="selected"<# } #> value="<?php echo esc_attr('search'); ?>"><?php echo esc_html('search'); ?></option>
        <option <# if ( _.contains(data.display.target['ids'],'error') ) { #>selected="selected"<# } #> value="<?php echo esc_attr('error'); ?>"><?php echo esc_html('error'); ?></option>
      </select>
      <span class="description hidden"><?php esc_html_e('If you select an option all the other will be excluded', 'wp-whatsapp-chat'); ?></span>
    </p>
  </div> 
  <?php foreach ($contact_entries as $key => $entry) : ?>
    <div class="options_group qlwapp-premium-field">
      <p class="form-field">
        <label><?php esc_html_e(ucwords($entry->label)); ?></label>
        <select name="display[entries][<?php echo esc_attr($key); ?>][include]" class="qlwapp-select2"> 
          <option <# if ( data.display.entries['<?php echo esc_attr($key); ?>']['include'] == 1 ) { #>selected="selected"<# } #> value="1"><?php esc_html_e('Include'); ?></option>
          <option <# if ( data.display.entries['<?php echo esc_attr($key); ?>']['include'] == 0 ) { #>selected="selected"<# } #> value="0"><?php esc_html_e('Exclude'); ?></option> 
        </select> 
        <select multiple="multiple" id="qlwapp_select2_<?php echo esc_attr($key); ?>" data-placeholder="<?php echo esc_attr('Search by title&hellip;', 'wp-whatsapp-chat'); ?>" name="display[entries][<?php echo esc_attr($key); ?>][ids][]" data-name="<?php echo esc_attr($key); ?>" class="qlwapp-select2-search"> 
          <# _.each(data.display.entries['<?php echo esc_attr($key); ?>']['ids'], function(title, id){ #>
          <option selected="selected" value="{{id}}">{{title}} </option>
          <# }); #>
        </select>
      </p>
    </div>
  <?php endforeach; ?>
  <?php foreach ($contact_taxonomies as $key => $taxonomy) : ?>

    <div class="options_group ">
      <p class="form-field">
        <label><?php esc_html_e(ucwords($taxonomy->label)); ?></label>
        <select name="display[taxonomies][<?php echo esc_attr($key); ?>][include]" class="qlwapp-select2"> 
          <option <# if ( data.display.taxonomies['<?php echo esc_attr($key); ?>']['include'] == 1 ) { #>selected="selected"<# } #> value="1"><?php esc_html_e('Include', 'wp-whatsapp-chat'); ?></option>
          <option <# if ( data.display.taxonomies['<?php echo esc_attr($key); ?>']['include'] == 0 ) { #>selected="selected"<# } #> value="0"><?php esc_html_e('Exclude', 'wp-whatsapp-chat'); ?></option> 
        </select> 
        <select multiple="multiple" id="qlwapp_select2_<?php echo esc_attr($key); ?>" data-placeholder="<?php echo esc_attr('Choose target&hellip;', 'wp-whatsapp-chat'); ?>" name="display[taxonomies][<?php echo esc_attr($key); ?>][ids][]" data-name="<?php echo esc_attr($key); ?>" class="qlwapp-select2"> 
          <?php
          $terms = get_terms(array(
              'taxonomy' => $key,
              'hide_empty' => false,
          ));
          foreach ($terms as $term) :
            ?>
            <option value="<?php echo esc_attr($term->term_id); ?>" <# if ( data.display.taxonomies['<?php echo esc_attr($key); ?>']['ids'][<?php echo esc_attr($term->term_id); ?>]!= undefined ) { #>selected="selected"<# } #>><?php echo esc_html($term->name); ?></option>
          <?php endforeach; ?>
        </select>
      </p>
    </div>
  <?php endforeach; ?>
</div>
