<span class="customize-control-title">
	<?php _e( 'Export', 'xstore-core' ); ?>
</span>
<span class="description customize-control-description">
	<?php _e( 'Click the button below to export the customization settings for this theme.', 'xstore-core' ); ?>
</span>
<input type="button" class="button" name="cei-export-button" value="<?php esc_attr_e( 'Export', 'xstore-core' ); ?>" />

<hr class="cei-hr" />

<span class="customize-control-title">
	<?php _e( 'Import', 'xstore-core' ); ?>
</span>
<span class="description customize-control-description">
	<?php _e( 'Upload a file to import customization settings for this theme.', 'xstore-core' ); ?>
</span>
<div class="cei-import-controls">
	<input type="file" name="cei-import-file" class="cei-import-file" />
	<label class="cei-import-images">
		<input type="checkbox" name="cei-import-images" value="1" /> <?php _e( 'Download and import image files?', 'xstore-core' ); ?>
	</label>
	<?php wp_nonce_field( 'cei-importing', 'cei-import' ); ?>
</div>
<div class="cei-uploading"><?php _e( 'Uploading...', 'xstore-core' ); ?></div>
<input type="button" class="button" name="cei-import-button" value="<?php esc_attr_e( 'Import', 'xstore-core' ); ?>" />