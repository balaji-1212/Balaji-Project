<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );
/**
 * Template "Demos" for 8theme dashboard.
 *
 * @since   6.0.2
 * @version 1.0.4
 */

$class = '';

$versions = etheme_get_demo_versions();

if ( ! $versions ){
	echo '<p class="et-message et-error" style="width: 100%;">' .
	     esc_html__('Can not connect to 8theme API to get versions list', 'xstore') .
	     '</p>';
	return;
}

$pages = array_filter($versions, function( $el ) {
	return $el['type'] == 'page';
});

$demos = array_filter($versions, function( $el ) {
	return $el['type'] == 'demo';
});

$installed_versions = array();
$installed_version = get_option('etheme_current_version');

if ( $installed_version ) {
	$installed_versions[] = json_decode($installed_version)->name;
}

?>
<div class="etheme-import-section <?php echo esc_attr( $class ); ?>">
    <div class="import-demos-wrapper admin-demos">
        <h1 class="etheme-page-title etheme-page-title-type-2">Import Demos
            <span class="etheme-page-title-label green"><span class="et-counter">120+</span><span><?php echo esc_html__('Demos', 'xstore');?></span></span>
        </h1>
        <p class="et-message et-info">Importing pre-built website is the easiest way to setup theme. It will allow you to quickly edit everything instead of creating content from scratch. </p>
        <div class="import-demos-header">
            <ul class="et-filters import-demos-filter et-filters-type-1">
                <li>
                    <span class="et-filter-toggle">
                        <svg width="1em" height="1em" viewBox="0 0 15 15" fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="vertical-align: -2px;">
                            <path d="M13.4742 0H0.736341C0.142439 0 -0.206675 0.637571 0.133612 1.1015C0.140163 1.11035 -0.0680259 0.8403 4.99408 7.4036C5.10763 7.5601 5.16764 7.74323 5.16764 7.93353V14.2591C5.16764 14.8742 5.90791 15.2134 6.40988 14.8516L8.56755 13.3023C8.86527 13.0918 9.04292 12.7554 9.04292 12.4021V7.93353C9.04292 7.74323 9.10289 7.5601 9.21648 7.4036C14.2747 0.84534 14.0704 1.11029 14.0769 1.1015C14.4171 0.637776 14.0684 0 13.4742 0V0ZM8.46932 6.88784C8.26014 7.15903 8.12023 7.53716 8.12023 7.9335V12.402C8.12023 12.4786 8.08163 12.5515 8.01705 12.597C7.96024 12.6368 8.39049 12.3288 6.09032 13.9804V7.93353C6.09032 7.56071 5.97182 7.20207 5.74764 6.89639C5.74207 6.8888 5.89828 7.09148 2.57538 2.78316H11.6351L8.46932 6.88784ZM12.313 1.90425H1.89754L1.10671 0.878883H13.1038L12.313 1.90425Z"/>
                        </svg>
                        <span><?php echo esc_html__('Filter', 'xstore'); ?></span>
                    </span>
                    <ul>
                        <li class="et-filter versions-filter active" data-filter="all"><?php esc_html_e('All', 'xstore'); ?></li>
                        <li class="et-filter versions-filter" data-filter="popular"><?php esc_html_e('Popular', 'xstore'); ?></li>
                        <li class="et-filter versions-filter" data-filter="catalog"><?php esc_html_e('Catalog', 'xstore'); ?></li>
                        <li class="et-filter versions-filter" data-filter="one-page"><?php esc_html_e('One page', 'xstore'); ?></li>
                        <li class="et-filter versions-filter" data-filter="corporate"><?php esc_html_e('Corporate', 'xstore'); ?></li>
                    </ul>
                </li>
            </ul>

            <ul class="et-filters import-demos-filter et-filters-builders">
                <li class="et-filter engine-filter active" data-filter="all">All</li>
                <li class="et-filter engine-filter" data-filter="wpb">
                    <svg width="20px" height="20px" viewBox="0 0 66 50" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <defs></defs>
                        <g id="Page-1" stroke="none" stroke-width="1" fill="#ffffff" fill-rule="evenodd">
                            <path d="M51.3446356,9.04135214 C46.8606356,8.68235214 44.9736356,9.78835214 42.8356356,10.0803521 C45.0046356,11.2153521 47.9606356,12.1793521 51.5436356,11.9703521 C48.2436356,13.2663521 42.8866356,12.8233521 39.1886356,10.5643521 C38.2256356,9.97535214 37.2136356,9.04535214 36.4556356,8.30235214 C33.4586356,5.58335214 31.2466356,0.401352144 21.6826356,0.0183521443 C9.68663559,-0.456647856 0.464635589,8.34735214 0.0156355886,19.6453521 C-0.435364411,30.9433521 8.92563559,40.4883521 20.9226356,40.9633521 C21.0806356,40.9713521 21.2386356,40.9693521 21.3946356,40.9693521 C24.5316356,40.7853521 28.6646356,39.5333521 31.7776356,37.6143521 C30.1426356,39.9343521 24.0316356,42.3893521 20.8506356,43.1673521 C21.1696356,45.6943521 22.5216356,46.8693521 23.6306356,47.6643521 C26.0896356,49.4243521 29.0086356,46.9343521 35.7406356,47.0583521 C39.4866356,47.1273521 43.3506356,48.0593521 46.4746356,49.8083521 L49.7806356,38.2683521 C58.1826356,38.3983521 65.1806356,32.2053521 65.4966356,24.2503521 C65.8176356,16.1623521 59.9106356,9.72335214 51.3446356,9.04135214 L51.3446356,9.04135214 Z" id="Fill-41" fill="currentColor"></path>
                        </g>
                    </svg>
                    <span><?php esc_html_e('WPBakery', 'xstore'); ?></span>

                </li>
                <li class="et-filter engine-filter" data-filter="elementor">

                    <svg height="20px" version="1.1" viewBox="0 0 512 512" width="20px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="_x31_09-elementor"><g><path d="M462.999,26.001H49c-12.731,0-22.998,10.268-22.998,23v413.998c0,12.732,10.267,23,22.998,23    h413.999c12.732,0,22.999-10.268,22.999-23V49.001C485.998,36.269,475.731,26.001,462.999,26.001" style="fill:#D63362;"/><rect height="204.329" style="fill:#FFFFFF;" width="40.865" x="153.836" y="153.836"/><rect height="40.866" style="fill:#FFFFFF;" width="122.7" x="235.566" y="317.299"/><rect height="40.865" style="fill:#FFFFFF;" width="122.7" x="235.566" y="235.566"/><rect height="40.865" style="fill:#FFFFFF;" width="122.7" x="235.566" y="153.733"/></g></g><g id="Layer_1"/></svg>
                    <span><?php esc_html_e('Elementor', 'xstore'); ?></span>
                </li>
            </ul>

            <div class="etheme-search">
                <input type="text" class="etheme-versions-search form-control" placeholder="Search for versions">
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

        <div class="import-demos">
			<?php
			foreach ( $demos as $key => $version ) : ?>
				<?php
				$imported = 'not-imported';
				if ( in_array($key, $installed_versions)) {
					$imported = 'imported';
				}

				if ( ! isset( $version['filter'] ) ) {
					$version['filter'] = 'all';
				}

				if ( isset( $version['engine'] ) ) {
					$version['filter'] = $version['filter'] . ' ' . implode( " ", $version['engine'] );
				} else {
					$version['filter'] = $version['filter'] . ' wpb';
				}
				$engine = (isset( $version['engine'] )) ? $version['engine'] : array();

				$required = false;

				if ( isset($version['required']) ){
					if ( isset($version['required']['theme']) && version_compare( ETHEME_THEME_VERSION, $version['required']['theme'], '<' )){
						$required['theme'] = $version['required']['theme'];
					}

					if (isset($version['required']['plugin']) && defined('ET_CORE_VERSION') && version_compare( ET_CORE_VERSION, $version['required']['plugin'], '<' )){
						$required['plugin'] = $version['required']['plugin'];
					}

					if ($required){
						$required = json_encode($required);
					}
				}

				if (count( $engine ) > 1){
					$engine = count( $engine );
				}elseif (count( $engine ) == 1 && isset($engine[0])){
					$engine = $engine[0];
				} else {
					$engine = 0;
				}


				?>
                <div
                        class="version-preview <?php echo esc_attr( $imported ); ?> version-preview-<?php echo esc_attr( $key ); ?> <?php echo strpos($version['filter'], 'elementor' ) > 0 ? '' : 'et-hide'; ?>" data-filter="<?php echo esc_js($version['filter']); ?>"
                        data-active-filter="all"
                >
                    <div class="version-screenshot">
                        <img
                                class="lazyload lazyload-simple et-lazyload-fadeIn"
                                src="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/placeholder-350x268.png"
                                data-src="<?php echo apply_filters('etheme_protocol_url', ETHEME_BASE_URL . 'import/xstore-demos/' . esc_attr( $key ) . '/screenshot.jpg'); ?>"

                                alt="<?php echo esc_attr( $key ); ?>">
						<?php if ( in_array($key, $installed_versions)) { ?>
                            <span class="version-badge green-badge"><?php esc_html_e('Imported', 'xstore'); ?></span>
						<?php } ?>
	                    <?php if ( isset($version['badge']) ) { ?>
                            <span class="version-badge red-badge"><?php echo esc_html($version['badge']); ?></span>
	                    <?php } ?>
                        <div class="version-footer">
                            <a href="<?php echo esc_url( $version['preview_url'] ); ?>" <?php echo (isset($version['preview_elementor_url'])) ? 'data-href="'.esc_url( $version['preview_elementor_url'] ).'"' : ''; ?> target="_blank" class="button-preview et-button et-button-dark-grey no-loader">
								<?php echo esc_html__('Preview', 'xstore'); ?>
                            </a>
                            <span
                                    class="button-import-version et-button et-button-green no-loader"
                                    data-version="<?php echo esc_attr( $key ); ?>"
                                    data-engine="<?php echo esc_attr($engine); ?>"
                                    data-required="<?php echo esc_attr($required); ?>"
                            >
                                <?php echo esc_html__('Import', 'xstore'); ?>

                            </span>
                        </div>
                    </div>
                    <span class="version-title"><?php echo esc_html( $version['title'] ); ?></span>
                </div>
                <style type="text/css">
                    <?php
//                         if ( isset($version['orders']) ) {
                            foreach ($version['orders'] as $key2 => $value2) {
                                echo '.version-preview-' . esc_attr( $key ) . '[data-active-filter="'.$key2.' wpb"]{ order: ' . $value2 . '}';
                                echo '.version-preview-' . esc_attr( $key ) . '[data-active-filter="'.$key2.' elementor"]{ order: ' . $value2 . '}';
                            }
//                         }
                    ?>
                </style>
			<?php endforeach; ?>
            <div class="et-hide et-not-found text-center" style="width:100%">
                <p><img src="<?php echo esc_url( ETHEME_BASE_URI.'framework/panel/images/empty-search.png' ); ?>" alt="search"></p>
                <h4>Whoops...</h4>
                <h4>We couldn't find "<span class="et-search-request"></span>"</h4>
            </div>
        </div>

        <div class="install-base-first">
            <h1><?php esc_html_e('No access!', 'xstore'); ?></h1>
            <p><?php esc_html_e('Please, install Base demo content before, to access the collection of our Home Pages.', 'xstore'); ?></p>
        </div>
    </div>
</div>
<?php if ( isset( $_GET['after_activate'] ) ): ?>
	<?php $out = ''; ?>

	<?php if ( ! class_exists( 'ETC\App\Controllers\Admin\Import' ) ) : ?>

		<?php
		// $out .= '<p class="et_installing-base-plugin et-message et-info">' . esc_html__( 'Please wait installing base plugin', 'xstore' ) . '</p>';
		// $out .= '<p class="et_installed-base-plugin hidden et-message et-success">' . esc_html__( 'plugin intalled', 'xstore' ) . '</p>';
		?>
        <span class="hidden et_plugin-nonce" data-plugin-nonce="<?php echo wp_create_nonce( 'envato_setup_nonce' ); ?>"></span>
	<?php endif; ?>

	<?php $out .= '<div class="et_all-success">
            <br>
            <img src="' . ETHEME_BASE_URI . ETHEME_CODE .'assets/images/' . 'success-icon.png" alt="installed icon" style="margin-bottom: -7px;"><br/><br/>
            <h3 class="et_step-title text-center">' . esc_html__('Theme successfully activated!', 'xstore') . '</h3>
            <p>'.esc_html__('Now you have lifetime updates, top-notch 24/7 live support and much more.', 'xstore').'</p>
        </div>
        <span class="et-button et-button-green no-loader et_close-popup" onclick="window.location=\'' . admin_url( 'admin.php?page=et-panel-demos' ) . '\'">ok</span><br><br>' ?>
	<?php echo '<div class="et_popup-activeted-content hidden">' . $out . '</div>'; ?>
<?php endif ?>
