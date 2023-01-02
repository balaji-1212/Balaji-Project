<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * The template for single page import popup
 *
 * @since   2.2.0
 * @version 1.0.0
 */

$Etheme_Single_pages_Import = new Etheme_Single_pages_Import();
$plugins = $Etheme_Single_pages_Import->is_plugins_enabled();
?>

<div class="et_response-holder">
	<div class="et_page-import et-button et-button-lg et-button-green no-loader no-transform"><span class="dashicons dashicons-download"></span> <?php esc_html_e( 'Import', 'xstore-core' ); ?></div>
	<div class="et_panel-popup et_popup-import-single-page size-lg">
		<span class="et_close-popup et-button-cancel et-button"><span class="dashicons dashicons-no" style="font-size: 15px;"></span></span>
		<div class="popup-import-head with-bg">
			<p><?php esc_html_e( 'Page Importer', 'xstore-core' ); ?></p>
		</div>
		<div class="et_popup-import-content">
			<div class="et_popup-step et_step-import">
				<img src="<?php echo ETHEME_BASE_URI.'theme/assets/images/box-opening.gif'; ?>" alt="box-opening" style="max-width: 70px; margin-bottom:  -35px; margin-top:  -15px;">
				<h3 style="font-weight: normal;font-size: 21px;line-height: 2; margin: 20px 0 40px;">
					<?php 
						printf(
							'%s <a href="https://xstore.8theme.com/preview-new/" target="_blank">%s</a> %s',
							esc_html__( 'Import any page layout from any', 'xstore-core' ),
							esc_html__( 'demo', 'xstore-core' ),
							esc_html__( 'just by adding page URL to the field below.', 'xstore-core' )
						);
					?>
				</h3>
				<p class="flex justify-content-between">
					<input id="et_single-page-import" name="et_single-page-import" placeholder="<?php esc_html_e( 'Example: https://xstore.8theme.com/team-members/', 'xstore-core' ); ?>" type="text" style="width: 100%;margin: 0 -1px 0 0;padding: 11px 10px 10px;outline: none;border-radius: 3px 0 0 3px; line-height: 1px;">
					<span class="et_try-to-load et-button et-button-green no-loader no-transform" style="line-height: 1.6; white-space: nowrap; margin-left: 10px;"><span class="dashicons dashicons-download" style="vertical-align: -15%; font-size: 1.2em"></span> <?php esc_html_e( 'Import', 'xstore-core' ); ?></span>
				</p>
				<p style="font-size: 16px;">
					<label for="et_custom-css">
						<input type="checkbox" id="et_custom-css" name="et_custom-css">
						<span><?php esc_html_e( 'Import page custom CSS', 'xstore-core' ); ?></span>
					</label>
				</p>
				<p style="font-size: 16px;">
					<label for="et_rewrite-page">
						<input type="checkbox" id="et_rewrite-page" name="et_rewrite-page">
						<span><?php esc_html_e( 'Rewrite page content', 'xstore-core' ); ?></span>
					</label>
				</p>
				<p class="et-message et-error hidden url-error">
					<?php 
						printf(
							'%s <a href="https://xstore.8theme.com/preview-new/" target="_blank">%s</a> %s Example: https://xstore.8theme.com/informations/',
							esc_html__( 'Oops, it seems you used the incorrect request URL. Go to the', 'xstore-core' ),
							esc_html__( 'demo', 'xstore-core' ),
							esc_html__( 'and make sure that you copied the correct path to the page.', 'xstore-core' )
						);
					?>
				</p>
				<p class="et-message et-error hidden post-type-error"><?php esc_html_e( 'It seems that you are trying to import incorrect post type. Only pages template import allowed!', 'xstore-core' ); ?></p>
				<p class="et-message et-error hidden api-error">
					<span><?php esc_html_e( 'Failed API connection. Why this might happened:', 'xstore-core' ); ?></span>
					<span><?php esc_html_e( '- Your server does not allow outgoing connections', 'xstore-core' ); ?></span>
					<span><?php 
							printf( 
								'%s <a href="https://www.8theme.com/forums/xstore-wordpress-support-forum" target="_blank">%s</a> %s',
								esc_html__( 'Contact', 'xstore-core' ),
								esc_html__( 'theme support', 'xstore-core' ),
								esc_html__( 'to get help with this.', 'xstore-core' )
							); 
						?>
					</span>
				</p>
				<p class="et-message et-error hidden page-error"><?php 
						printf( 
							'%s <a href="https://www.8theme.com/forums/xstore-wordpress-support-forum" target="_blank">%s</a> %s',
							esc_html__( 'This page is not available for import. Contact', 'xstore-core' ),
							esc_html__( 'theme support', 'xstore-core' ),
							esc_html__( 'to get help with this.', 'xstore-core' )
						); 
					?>
				</p>
				<p class="et-message et-error hidden rewrite-notice"><?php esc_html_e( 'Existing page content will be replaced by the imported content.', 'xstore-core' ); ?></p>
			</div>
			<?php if ( count($plugins) ): ?>
				<div class="et_popup-step et_step-plugins hidden">
					<h3 style="font-weight: normal;font-size: 21px;line-height: 2; margin: 20px 0 20px;"><?php esc_html_e( 'This page needs some additional plugins to be activated', 'xstore-core' ); ?></h3>
					<ul class="et_popup-import-plugins et_page-plugins" style="font-size: 16px; margin-bottom: 40px;">
						<?php foreach ($plugins as $key => $value): ?>
							<li 
								class="et_popup-import-plugin flex justify-content-between align-items-center <?php echo $key; ?>" style="display: none;">
								<span class="flex align-items-center" style="margin-right: 10px;">
									<span class="dashicons dashicons-warning dashicons-warning  orange-color"></span>
									<?php echo $value['title']; ?>
								</span>
								<span 
									class="et_popup-import-plugin-btn" 
									data-slug="<?php echo $key; ?>" 
									style="cursor: pointer; border-bottom: 1px solid; line-height: 1.2; color: #0073aa; margin-left: 10px">
									<?php echo $value['text']; ?>
								</span>
							</li>
						<?php endforeach; ?>
					</ul>
					<span class="hidden et_plugin-nonce" data-plugin-nonce="<?php echo wp_create_nonce( 'envato_setup_nonce' ); ?>"></span>
					<span class="et-button et-button-lg et-button-grey2 et_install-page-content" data-text="<?php esc_html_e( 'Continue', 'xstore-core' ); ?>" style="padding-left: 30px;padding-right: 30px;"><?php esc_html_e( 'Skip', 'xstore-core' ); ?></span>
				</div>
			<?php endif; ?>
			<div class="et_popup-step et_step-final hidden" style="animation: none;">
				<div class="et_all-success">
					<img src="<?php echo ETHEME_BASE_URI . ETHEME_CODE .'assets/images/'; ?>success-icon.png" alt="installed icon" style="margin-bottom: -7px;">
					<h3 class="et_step-title text-center" style="font-size: 22px;font-weight: normal; margin: 24px 0 42px;"><?php esc_html_e( 'Successfully Imported!', 'xstore-core' ); ?></h3>
					<span class="et-button et-button-lg et_close-popup et-button-green" style="padding-left: 30px;padding-right: 30px;"><?php esc_html_e('Continue editing', 'xstore-core'); ?></span>
				</div>
			</div>
            <style>
                .et_popup-import-content.ajax-processing:before ,.et_step-plugins.ajax-processing:before{
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(255,255,255,.5);
                    z-index: 1;
                }

                .et_popup-import-content.ajax-processing:after ,.et_step-plugins.ajax-processing:after{
                    position: absolute;
                    top: calc(50% - 9px);
                    left: calc(50% - 9px);
                    content: '';
                    border: 1px solid var(--et_admin_border-color);
                    border-left-color: var(--et_admin_grey-color, #888);
                    animation: rotate .7s infinite linear;
                    width: 18px;
                    height: 18px;
                    border-radius: 50%;
                    z-index: 2;
                }
            </style>
		</div>
	</div>
</div>