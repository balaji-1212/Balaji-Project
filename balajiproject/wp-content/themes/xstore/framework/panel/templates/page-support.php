<?php if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}
/**
 * Template "Services" for 8theme dashboard.
 *
 * @since   6.0.2
 * @version 1.0.1
 */
new YouTube();
if ( isset( $_POST['et_YouTube'] ) && count( $_POST['et_YouTube'] ) ) {
	$videos = $_POST['et_YouTube'];
} else {
	$videos = false;
}

$documentation = get_option('et_documentation_beacon', false);

?>
    <div class="et-col-7 etheme-support">
        <h2 class="etheme-page-title etheme-page-title-type-2"><?php esc_html_e('Tutorials & Support', 'xstore'); ?></h2>
        <h3 class="et-title"><?php esc_html_e( 'XStore Documentation Beacon', 'xstore' ); ?></h3>
        
        <div>
            <img src="<?php echo ETHEME_BASE_URI.'images/documentation.jpg'; ?>" width="150px" alt="documentation">
            <p><?php esc_html_e('With this option, you can disable or enable the XStore assistant in the admin panel and customizer of the XStore theme.', 'xstore'); ?></p>
            <p>
                <label class="et-panel-option-switcher <?php if ( $documentation !== 'off' ) { ?> switched<?php } ?>"" for="et_documentation">
                    <input type="checkbox" id="et_documentation" name="et_documentation" <?php if ( $documentation !== 'off' ) { ?>checked<?php } ?>>
                    <span></span>
                </label>
            </p>
        </div>


        <h3 class="et-title"><?php esc_html_e( 'Video tutorials', 'xstore' ); ?></h3>
        <div class="etheme-videos-wrapper-new">
            <div class="etheme-videos loading">
				<?php
				if ( ! $videos ) {
					echo '<p class="et-message et-error" style="width: 100%; margin: 0 20px;">' .
					     esc_html__( 'Can not connect to youtube API to show video tutorials', 'xstore' ) .
					     '</p>';
				} else {
					$i = 0;
					foreach ( $videos as $key => $value ) {
						$i ++;
						$hidden = ( $i <= 2 ) ? '' : ' hidden';
						echo '<div class="etheme-video text-center' . $hidden . '"><div id="player-' . $key . '"></div><p class="video-title">' . $value['title'] . '</p></div>';
						
					}
				}
				?>
            </div>
        </div>
		<?php if ( $videos ): ?>
            <div class="text-center">
                <a href="https://www.youtube.com/channel/UCiZY0AJRFoKhLrkCXomrfmA"
                   class="et-button no-loader more-videos last-button"
                   target="_blank"><?php esc_html_e( 'View more videos', 'xstore' ); ?></a>
            </div>
		<?php endif; ?>
        <br/>
        <br/>
        <h3><?php esc_html_e( 'Help and support', 'xstore' ); ?></h3>
        <p><?php esc_html_e( 'If you encounter any difficulties with our product we are ready to assist you via:', 'xstore' ); ?></p>
        <ul class="support-blocks">
            <li>
                <a href="https://t.me/etheme" target="_blank">
                    <img src="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/telegram.png">
                    <span><?php esc_html_e( 'Telegram channel', 'xstore' ); ?></span>
                </a>
            </li>
            <li>
                <a href="https://www.8theme.com/forums/xstore-wordpress-support-forum/" target="_blank">
                    <img src="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/support-icon.png">
                    <span><?php esc_html_e( 'Support Forum', 'xstore' ); ?></span>
                </a>
            </li>
            <li>
                <a href="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/themeforest.jpg" target="_blank">
                    <img src="<?php echo esc_html( ETHEME_BASE_URI . ETHEME_CODE ); ?>assets/images/envato-icon.png">
                    <span><?php esc_html_e( 'ThemeForest profile', 'xstore' ); ?></span>
                </a>
            </li>
        </ul>
        <div class="support-includes">
            <div class="includes">
                <p><?php esc_html_e( 'Item Support includes:', 'xstore' ); ?></p>
                <ul>
                    <li><?php esc_html_e( 'Answering technical questions', 'xstore' ); ?></li>
                    <li><?php esc_html_e( 'Assistance with reported bugs and issues', 'xstore' ); ?></li>
                    <li><?php esc_html_e( 'Help with bundled 3rd party plugins', 'xstore' ); ?></li>
                </ul>
            </div>
            <div class="excludes">
                <p><?php _e( 'Item Support <span class="red-color">DOES NOT</span> Include:', 'xstore' ); ?></p>
                <ul>
                    <li><?php esc_html_e( 'Customization services', 'xstore' ); ?></li>
                    <li><?php esc_html_e( 'Installation services', 'xstore' ); ?></li>
                    <li><?php esc_html_e( 'Support for non-bundled 3rd party plugins.', 'xstore' ); ?></li>
                </ul>
            </div>
        </div>
    </div>
    <br/>
    <div class="et-col-5 etheme-documentation et-sidebar">
        <h3><?php esc_html_e( 'Documentation', 'xstore' ); ?></h3>
        <div class="et-row et-row-full">
            <div class="et-col-4 et-col-sm-2">
                <h4><?php esc_html_e( 'Theme Installation', 'xstore' ); ?></h4>
                <ul>
                    <li>
                        <a href="<?php etheme_documentation_url('4-theme-package'); ?>" target="_blank">XStore Theme
                            Package</a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('12-theme-installation'); ?>"
                           target="_blank"><?php esc_html_e( 'Theme Installation', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('32-child-theme'); ?>"
                           target="_blank"><?php esc_html_e( 'Child Theme', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('34-demo-content'); ?>"
                           target="_blank"><?php esc_html_e( 'Demo Content', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('45-8theme-page-post-layout-settings-8theme-post-options'); ?>"
                           target="_blank"><?php esc_html_e( '8theme Page/Post/Product Layout settings', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('42-portfolio-page'); ?>"
                           target="_blank"><?php esc_html_e( 'Portfolio Page', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('43-blank-page'); ?>"
                           target="_blank"><?php esc_html_e( 'Blank Page', 'xstore' ); ?></a>
                    </li>
                </ul>
            </div>
            <div class="et-col-4 et-col-sm-2">
                <h4><?php esc_html_e( 'Troubleshooting', 'xstore' ); ?></h4>
                <ul>
                    <li>
                        <a href="<?php etheme_documentation_url('64-how-to-add-custom-favicon'); ?>"
                           target="_blank"><?php esc_html_e( 'How to add custom favicon', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('69-how-to-add-slider-banner-in-product-category-page'); ?>"
                           target="_blank"><?php esc_html_e( 'How to add slider/banner on Category page', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('87-facebook-login'); ?>"
                           target="_blank"><?php esc_html_e( 'FaceBook login', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('41-contact-page'); ?>"
                           target="_blank"><?php esc_html_e( 'How to create a contact page', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('44-blog-page'); ?>"
                           target="_blank"><?php esc_html_e( 'How to create a blog page', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('90-how-to-find-your-themeforest-item-purchase-code'); ?>"
                           target="_blank"><?php esc_html_e( 'How to find your ThemeForest Item Purchase Code', 'xstore' ); ?></a>
                    </li>
                </ul>
            </div>
            <div class="et-col-4 et-col-sm-2">
                <h4><?php esc_html_e( 'Plugins', 'xstore' ); ?></h4>
                <ul>
                    <li>
                        <a href="<?php etheme_documentation_url('35-general-info'); ?>"
                           target="_blank"><?php esc_html_e( 'General Info', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('36-included-plugins'); ?>"
                           target="_blank"><?php esc_html_e( 'Included plugins', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('37-plugins-update'); ?>"
                           target="_blank"><?php esc_html_e( 'Plugins Update', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('38-activation-and-purchase-codes'); ?>"
                           target="_blank"><?php esc_html_e( 'Activation and Purchase Codes', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('65-woocommerce-infinite-scroll-and-ajax-pagination-settings'); ?>"
                           target="_blank"><?php esc_html_e( 'WooCommerce Infinite Scroll and Ajax Pagination', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('91-mail-chimp-form-custom-styles'); ?>"
                           target="_blank"><?php esc_html_e( 'MailChimp form custom styles', 'xstore' ); ?></a>
                    </li>
                </ul>
            </div>
            <div class="et-col-4 et-col-sm-2">
                <h4><?php esc_html_e( 'Widgets/Static Blocks', 'xstore' ); ?></h4>
                <ul>
                    <li>
                        <a href="<?php etheme_documentation_url('48-widgets-custom-widget-areas'); ?>"
                           target="_blank"><?php esc_html_e( 'Widgets & Custom Widget Areas', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('47-static-blocks'); ?>"
                           target="_blank"><?php esc_html_e( 'Static Blocks', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('46-xstore-shortcodes'); ?>"
                           target="_blank"><?php esc_html_e( 'XStore Shortcodes', 'xstore' ); ?></a>
                    </li>
                </ul>
            </div>
            <div class="et-col-4 et-col-sm-2">
                <h4><?php esc_html_e( 'API Client Terms of Use and Privacy Policies', 'xstore' ); ?></h4>
                <ul>
                    <li><a href="https://www.youtube.com/t/terms"
                           target="_blank"><?php esc_html_e( 'YouTube ​Terms of Service', 'xstore' ); ?></a></li>
                    <li><a href="https://policies.google.com/privacy"
                           target="_blank"><?php esc_html_e( '​Google Privacy Policy', 'xstore' ); ?></a></li>

                </ul>
            </div>
            <div class="et-col-4 et-col-sm-2">
                <h4><?php esc_html_e( 'Menu Set Up', 'xstore' ); ?></h4>
                <ul>
                    <li>
                        <a href="<?php etheme_documentation_url('86-general-information'); ?>"
                           target="_blank"><?php esc_html_e( 'General Information', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('27-mega-menu'); ?>"
                           target="_blank"><?php esc_html_e( 'Mega Menu', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('88-one-page-menu'); ?>"
                           target="_blank"><?php esc_html_e( 'One Page menu', 'xstore' ); ?></a>
                    </li>
                </ul>
            </div>
            <div class="et-col-4 et-col-sm-2">
                <h4><?php esc_html_e( 'Theme Translation', 'xstore' ); ?></h4>
                <ul>
                    <li>
                        <a href="<?php etheme_documentation_url('30-base-theme-translation'); ?>"
                           target="_blank"><?php esc_html_e( 'Base theme translation', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('31-translation-with-wpml'); ?>"
                           target="_blank"><?php esc_html_e( 'Translation with WPML', 'xstore' ); ?></a>
                    </li>
                </ul>
            </div>
            <div class="et-col-4 et-col-sm-2">
                <h4><?php esc_html_e( 'WooCommerce', 'xstore' ); ?></h4>
                <ul>
                    <li>
                        <a href="<?php etheme_documentation_url('29-general-information'); ?>"
                           target="_blank"><?php esc_html_e( 'General Information', 'xstore' ); ?></a>
                    </li>
                    <li>
                        <a href="<?php etheme_documentation_url('67-shop-page'); ?>"
                           target="_blank"><?php esc_html_e( 'Shop page', 'xstore' ); ?></a></li>
                    <li>
                        <a href="<?php etheme_documentation_url('68-single-product-page'); ?>"
                           target="_blank"><?php esc_html_e( 'Single Product page', 'xstore' ); ?></a>
                    </li>
                    <li><a href="<?php etheme_documentation_url('89-product-images'); ?>"
                           target="_blank"><?php esc_html_e( 'Product Images', 'xstore' ); ?></a>
                    </li>
                </ul>
            </div>
            <div class="et-col-4 et-col-sm-2">
                <h4><?php esc_html_e( 'Theme Update', 'xstore' ); ?></h4>
                <ul>
                    <li>
                        <a href="<?php etheme_documentation_url('63-theme-update'); ?>"
                           target="_blank"><?php esc_html_e( 'Theme Update', 'xstore' ); ?></a>
                    </li>
                </ul>
            </div>
            <div class="et-col-4 et-col-sm-2">
                <h4><?php esc_html_e( 'Support', 'xstore' ); ?></h4>
                <ul>
                    <li><a href="<?php etheme_documentation_url('25-support'); ?>"
                           target="_blank"><?php esc_html_e( 'Support Policy', 'xstore' ); ?></a></li>
                </ul>
            </div>
        </div>
    </div>
<?php if ( $videos ) : ?>
    <script>
        // This code loads the IFrame Player API code asynchronously.
        let tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        let firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        // This function creates an <iframe> (and YouTube player) after the API code downloads.
        const players = [];

        function onYouTubeIframeAPIReady() {
			<?php foreach ($videos as $key => $value) : ?>
            var player;

            player = new YT.Player('player-<?php echo esc_attr( $key ); ?>', {
                height: '270',
                width: '480',
                videoId: '<?php echo esc_attr( $value['id'] ); ?>',
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });

            players.push(player);
			<?php endforeach; ?>
        }

        // The API will call this function when the video player is ready.
        function onPlayerReady(event) {
            let i = <?php echo esc_js( $i ); ?>;
            players.forEach(function (e, t) {
                if (t + 1 == i) {
                    setTimeout(removeLoading, 500);
                }
            });
        }

        // Remove loading class from etheme-videos DOM element
        function removeLoading(event) {
            jQuery('.etheme-videos').removeClass('loading');
        }

        // The API calls this function when the player's state changes. Stop played video
        function onPlayerStateChange(event) {
            players.forEach(function (e, t) {
                if (event.target != e && event.data == 1) {
                    e.pauseVideo();
                }
            });
        }
    </script>
<?php endif; ?>