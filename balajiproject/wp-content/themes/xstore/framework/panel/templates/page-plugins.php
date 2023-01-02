<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}
/**
 * Template "Plugins" for 8theme dashboard.
 *
 * @since   6.3.4
 * @version 1.0.1
 */

$_plugin = ( isset($_GET['plugin']) ) ? $_GET['plugin'] : false;

?>
<div class="etheme-plugins-section">
    <h1 class="etheme-page-title etheme-page-title-type-2">Plugins Installer</h1>
    <?php if(!$_plugin): ?>
        <div class="import-demos-header etheme-plugins-header">
        <ul class="et-filters import-demos-filter et-filters-type-1">
            <li>
                <span class="et-filter-toggle">
                    <svg width="1em" height="1em" viewBox="0 0 15 15" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="vertical-align: -2px;">
                        <path d="M13.4742 0H0.736341C0.142439 0 -0.206675 0.637571 0.133612 1.1015C0.140163 1.11035 -0.0680259 0.8403 4.99408 7.4036C5.10763 7.5601 5.16764 7.74323 5.16764 7.93353V14.2591C5.16764 14.8742 5.90791 15.2134 6.40988 14.8516L8.56755 13.3023C8.86527 13.0918 9.04292 12.7554 9.04292 12.4021V7.93353C9.04292 7.74323 9.10289 7.5601 9.21648 7.4036C14.2747 0.84534 14.0704 1.11029 14.0769 1.1015C14.4171 0.637776 14.0684 0 13.4742 0V0ZM8.46932 6.88784C8.26014 7.15903 8.12023 7.53716 8.12023 7.9335V12.402C8.12023 12.4786 8.08163 12.5515 8.01705 12.597C7.96024 12.6368 8.39049 12.3288 6.09032 13.9804V7.93353C6.09032 7.56071 5.97182 7.20207 5.74764 6.89639C5.74207 6.8888 5.89828 7.09148 2.57538 2.78316H11.6351L8.46932 6.88784ZM12.313 1.90425H1.89754L1.10671 0.878883H13.1038L12.313 1.90425Z"/>
                    </svg>
                    <?php echo esc_html__('Filter', 'xstore'); ?>
                </span>
                <ul>
                    <li class="et-filter plugin-filter active" data-filter="all"><?php esc_html_e( 'All', 'xstore' ); ?></li>
                    <li class="et-filter plugin-filter" data-filter="active"><?php esc_html_e( 'Active', 'xstore' ); ?></li>
                    <li class="et-filter plugin-filter" data-filter="disabled"><?php esc_html_e( 'Inactive', 'xstore' ); ?></li>
                    <li class="et-filter plugin-filter" data-filter="premium"><?php esc_html_e( 'Premium', 'xstore' ); ?></li>
                    <li class="et-filter plugin-filter" data-filter="free"><?php esc_html_e( 'Free', 'xstore' ); ?></li>
                </ul>
            </li>
        </ul>

        <div class="etheme-search">
            <input type="text" class="etheme-plugin-search form-control" placeholder="<?php esc_html_e( 'Search for plugins', 'xstore' ); ?>">
            <i class="et-admin-icon et-zoom"></i>
            <span class="spinner">
                <div class="et-loader ">
                    <svg class="loader-circular" viewBox="25 25 50 50">
                        <circle class="loader-path" cx="50" cy="50" r="12" fill="none" stroke-width="2" stroke-miterlimit="10"></circle>
                    </svg>
                </div>
            </span>
        </div>
    </div>
    <?php endif; ?>
    <div class="manage-plugins">
		<?php
		$system = new Etheme_System_Requirements();
		$system_requirements = $system->requirements;
		$system_status = $system->get_system();
		$instance  = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		$installed = get_plugins();
		if ( !count($instance->plugins) ) {
			echo  '<p class="et-message et-error" style="width: 100%; margin: 0 20px;">' .
                  esc_html__('Can not connect to get plugin list', 'xstore') .
              '</p>';
			echo '</div></div>';
		    return;
        }
		if ( $system_status['filesystem'] != $system_requirements['filesystem']){
		    $filesystem = false;
        } else {
			$filesystem = true;
        }
		foreach ( $instance->plugins as $slug => $plugin ) : ?>
			<?php

            if ($_plugin && $_plugin != $slug){
                continue;
            }

			$new_is_plugin_active = (
				( ! empty( $instance->plugins[ $slug ]['is_callable'] ) && is_callable( $instance->plugins[ $slug ]['is_callable'] ) )
				|| in_array( $instance->plugins[ $slug ]['file_path'], (array) get_option( 'active_plugins', array() ) ) || is_plugin_active_for_network( $instance->plugins[ $slug ]['file_path'] )
			);
			$plugin_classes       = array();
			$plugin_classes[]     = 'et_plugin';
			$plugin_classes[]     = ( $new_is_plugin_active ) ? 'et_plugin-active' : '';
			if ( isset($plugin['latest_version']) ){
				$plugin['version']  = $plugin['latest_version'];
			}

			if ( isset( $installed[ $plugin['file_path'] ] ) ){
				$plugin['version'] = $installed[ $plugin['file_path'] ]['Version'];
            }

			$filters = array();
			$filters[] = 'all';

			if ($new_is_plugin_active){
				$filters[] = 'active';
            } else {
				$filters[] = 'disabled';
            }

			if (isset( $plugin['premium'] ) && $plugin['premium']){
				$filters[] = 'premium';
			} else {
				$filters[] = 'free';
			}
			?>

            <div class="<?php echo trim( esc_attr( implode( ' ', $plugin_classes ) ) ); ?>"
                 data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>"
                 data-filter="<?php echo trim( esc_attr( implode( ' ', $filters ) ) ); ?>>"
                    <?php if( $_plugin == 'xstore-amp' ):?>
                         data-redirect="<?php echo admin_url( 'admin.php?page=et-panel-xstore-amp' ); ?>"
                    <?php endif;?>
                >
                <div class="et_plugin-content">
                    <span
                            class="plugin-action-text"
                            data-install="<?php echo esc_html__('Installing', 'xstore') . ' ...'; ?>"
                            data-activate="<?php echo esc_html__('Activating', 'xstore') . ' ...'; ?>"
                            data-deactivate="<?php echo esc_html__('Deactivating', 'xstore') . ' ...'; ?>"
                            data-update="<?php echo esc_html__('Updating', 'xstore') . ' ...'; ?>"
                    ></span>
                    <div class="et_plugin-image">
                        <div class="et_plugin-labels">
							<?php if ( $plugin['required'] ) : ?>
                                <span class="required"><?php esc_html_e( 'required', 'xstore' ); ?></span>
							<?php endif; ?>
							<?php if ( isset( $plugin['premium'] ) && $plugin['premium'] ) : ?>
                                <span class="premium"><?php esc_html_e( 'premium', 'xstore' ); ?></span>
							<?php endif; ?>
                        </div>
                        <span class="dashicons dashicons-yes et_plugin-checkbox">
                            <span class="mt-mes">activated</span>
                        </span>
                        <style>
                            .mt-mes{
                                display: none;
                            }
                            .et_plugin-checkbox:hover .mt-mes, .et_plugin-info:hover .mt-mes{
                                display: inline-block;
                                position: absolute;
                                left: -75px;
                                top: 0px;
                                text-transform: capitalize;
                                border-radius: 3px;
                                background: #222;
                                color: #fff;
                                padding: 3px 5px;
                                white-space: nowrap;
                                font-size: 14px;
                                height: 16px;
                                line-height: 15px;
                                box-sizing: content-box;
                                box-shadow: 1px 1px 5px 0px rgba(0, 0, 0, 0.1);
                                font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
                            }
                            .et_plugin-info:hover .mt-mes{
                                left: -130px;
                                top: 2px;
                            }
                        </style>
						<?php if ( isset( $plugin['image_url'] ) ) : ?>
                            <img
                                class="lazyload lazyload-simple et-lazyload-fadeIn"
                                src="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/placeholder-350x268.png"
                                data-src="<?php echo esc_attr( apply_filters('etheme_protocol_url',$plugin['image_url'] ) ); ?>"
                                alt="<?php echo esc_attr( $plugin['slug'] ); ?>"
                            >
						<?php else: ?>
                            <span><?php esc_html_e( 'No image set', 'xstore' ); ?></span>
						<?php endif; ?>
                        <?php if ( isset($plugin['details_url']) ) : ?>
                            <a class="et_plugin-info" target="_blank" href="<?php echo esc_url($plugin['details_url'] ); ?>">
                                <span class="mt-mes">More informations</span>
                                <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info" class="svg-inline--fa fa-info fa-w-6" role="img" viewBox="0 0 192 512" style="width: 1;width: 1em;height: 1em;">
                                    <path fill="currentColor" d="M20 424.229h20V279.771H20c-11.046 0-20-8.954-20-20V212c0-11.046 8.954-20 20-20h112c11.046 0 20 8.954 20 20v212.229h20c11.046 0 20 8.954 20 20V492c0 11.046-8.954 20-20 20H20c-11.046 0-20-8.954-20-20v-47.771c0-11.046 8.954-20 20-20zM96 0C56.235 0 24 32.235 24 72s32.235 72 72 72 72-32.235 72-72S135.764 0 96 0z"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="et_plugin-info">
                    <span class="et_plugin-name">
                         <?php echo esc_html( $plugin['name'] ); ?>
                    </span>
                        <span class="et_plugin-version">
                        <?php
                        $new_is_plugin_active = (
	                        ( ! empty( $instance->plugins[ $plugin['slug'] ]['is_callable'] ) && is_callable( $instance->plugins[ $plugin['slug'] ]['is_callable'] ) )
	                        || in_array( $instance->plugins[ $plugin['slug'] ]['file_path'], (array) get_option( 'active_plugins', array() ) )
	                        || is_plugin_active_for_network( $instance->plugins[ $plugin['slug'] ]['file_path'] )
                        );
                        $is_update_available = false;
                        if (
                                $new_is_plugin_active &&
                                $instance->is_plugin_installed( $plugin['slug'] ) &&
                                (false !== $instance->does_plugin_have_update( $plugin['slug'] )
                                 || ( isset($plugin['premium']) && $plugin['premium']  && ( $plugin['latest_version'] > $plugin['version']) ) ) )
                        {
	                        $is_update_available = true;
                        }

                        $update_text = '<span class="success" style="visibility: hidden; opacity: 0;">' . esc_html__( 'Latest version', 'xstore' ) . '</span>';

                        if ($is_update_available){
	                        $update_text = '<span class="success new-version-text">' . esc_html__( 'New version available', 'xstore' ) . '</span>';
                        }

                        if ( ! $plugin['version'] ) {
	                        echo '<span class="warning">' . esc_html__( 'Can not get plugin version', 'xstore' ) . '</span>';
                        } else {
	                        printf(
		                        '<span class="current-version">v.%s</span> %s <span class="hidden new-version">%s</span>',
		                        $plugin['version'],
		                        $update_text,
		                        $instance->does_plugin_have_update( $plugin['slug'] )
	                        );
                        }
                        ?>
                    </span>
                        <div class="et_plugin-control-wrapper">

							<?php $btn_class = $install = ( ! $instance->is_plugin_installed( $plugin['slug'] ) ) ? '' : 'hidden'; ?>
                            <span
                                    class="et_plugin-control et-button et-button-green no-loader <?php echo esc_attr( $btn_class ); ?>"
                                    data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>"
                                    data-action="install"><?php esc_html_e( 'install', 'xstore' ); ?></span>
	                        <?php $btn_class = $acivate = ( $instance->is_plugin_installed( $plugin['slug'] ) && ! $new_is_plugin_active ) ? '' : 'hidden'; ?>

                            <?php
                                $btn_text = __( 'activate', 'xstore' );
                                if ( $instance->does_plugin_require_update( $plugin['slug'] ) ) {
                                    $btn_text = __( 'update & activate', 'xstore' );
                                }
	                        ?>
                            <span
                                    class="et_plugin-control et-button et-button-blue no-loader <?php echo esc_attr( $btn_class ); ?>"
                                    data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>"
                                    data-action="activate"><?php echo esc_html($btn_text); ?></span>

							<?php $btn_class = ( $acivate != '' && $install != '' && $is_update_available ) ? '' : 'hidden'; ?>
                            <span
                                    class="et_plugin-control et-button et-button-green no-loader <?php echo esc_attr( $btn_class ); ?>"
                                    data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>"
                                    data-action="update"><?php esc_html_e( 'update', 'xstore' ); ?></span>

							<?php $btn_class = ( $acivate != '' && $install != '' ) ? '' : 'hidden'; ?>
                            <span
                                    class="et_plugin-control et-button et-button-blue no-loader <?php echo esc_attr( $btn_class ); ?>"
                                    data-slug="<?php echo esc_attr( $plugin['slug'] ); ?>"
                                    data-action="deactivate"><?php esc_html_e( 'deactivate', 'xstore' ); ?></span>

                        </div>
                    </div>
                </div>
            </div>
		<?php endforeach; ?>
        <span class="hidden et_plugin-nonce"
              data-plugin-nonce="<?php echo wp_create_nonce( 'envato_setup_nonce' ); ?>"></span>
        <span class="hidden error-text"><?php esc_html_e( 'Oops it looks something went wrong. Please check your system requirements first. In case it will happened again, please, contact us 8theme.com', 'xstore' ); ?></span>
        <span class="hidden et_filesystem" data-filesystem="<?php echo esc_js($filesystem); ?>"></span>
        <div class="et-hide et-not-found text-center" style="width:100%">
            <p><img src="<?php echo esc_url( ETHEME_BASE_URI.'framework/panel/images/empty-search.png' ); ?>" alt="search"></p>
            <h4>Whoops...</h4>
            <h4>We couldn't find "<span class="et-search-request"></span>"</h4>
        </div>
    </div>
</div>