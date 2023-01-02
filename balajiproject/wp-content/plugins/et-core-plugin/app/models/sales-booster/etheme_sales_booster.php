<?php

/**
 *
 * @package     XStore theme
 * @author      8theme
 * @version     1.0.2
 * @since       3.2.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'EthemeAdmin' ) ) {
	return;
}

if ( ! method_exists( 'EthemeAdmin', 'get_instance' ) ) {
	return;
}

// Don't duplicate me!
if ( ! class_exists( 'Etheme_Sales_Booster_Backend' ) ) {
	
	
	/**
	 * Main Etheme_Sales_Booster_Backend class
	 *
	 * @since       3.2.2
	 */
	class Etheme_Sales_Booster_Backend {
		
		/**
		 * Projects.
		 *
		 * @var array
		 * @since 3.2.2
		 */
		private $dir_url,
			$icons;
		
		public $global_admin_class;
		
		/**
		 * Class Constructor. Defines the args for the actions class
		 *
		 * @return      void
		 * @version     1.0.1
		 * @since       3.2.2
		 * @access      public
		 */
		public function __construct() {
			$this->global_admin_class = EthemeAdmin::get_instance();
			
			$this->global_admin_class->init_vars();
			
			add_action( 'admin_init', array( $this->global_admin_class, 'add_page_admin_settings_scripts' ), 1140 );
			
			add_action( 'wp_ajax_xstore_panel_settings_save', array(
				$this->global_admin_class,
				'xstore_panel_settings_save'
			) );
		}
		
		public function sales_booster_page_init_scripts() {
			
			$this->global_admin_class->settings_name = 'xstore_sales_booster_settings';
			
			$this->global_admin_class->xstore_panel_section_settings = get_option( $this->global_admin_class->settings_name, array() );
			
			$this->dir_url = ET_CORE_URL . 'app/models/sales-booster';
			
			$this->icons = array(
				'simple' => array(
					'et_icon-delivery'        => esc_html__( 'Delivery', 'xstore-core' ),
					'et_icon-coupon'          => esc_html__( 'Coupon', 'xstore-core' ),
					'et_icon-calendar'        => esc_html__( 'Calendar', 'xstore-core' ),
					'et_icon-compare'         => esc_html__( 'Compare', 'xstore-core' ),
					'et_icon-checked'         => esc_html__( 'Checked', 'xstore-core' ),
					'et_icon-chat'            => esc_html__( 'Chat', 'xstore-core' ),
					'et_icon-phone'           => esc_html__( 'Phone', 'xstore-core' ),
					'et_icon-whatsapp'        => esc_html__( 'Whatsapp', 'xstore-core' ),
					'et_icon-exclamation'     => esc_html__( 'Exclamation', 'xstore-core' ),
					'et_icon-gift'            => esc_html__( 'Gift', 'xstore-core' ),
					'et_icon-heart'           => esc_html__( 'Heart', 'xstore-core' ),
					'et_icon-message'         => esc_html__( 'Message', 'xstore-core' ),
					'et_icon-internet'        => esc_html__( 'Internet', 'xstore-core' ),
					'et_icon-account'         => esc_html__( 'Account', 'xstore-core' ),
					'et_icon-sent'            => esc_html__( 'Sent', 'xstore-core' ),
					'et_icon-home'            => esc_html__( 'Home', 'xstore-core' ),
					'et_icon-shop'            => esc_html__( 'Shop', 'xstore-core' ),
					'et_icon-shopping-bag'    => esc_html__( 'Bag', 'xstore-core' ),
					'et_icon-shopping-cart'   => esc_html__( 'Cart', 'xstore-core' ),
					'et_icon-shopping-cart-2' => esc_html__( 'Cart 2', 'xstore-core' ),
					'et_icon-burger'          => esc_html__( 'Burger', 'xstore-core' ),
					'et_icon-star'            => esc_html__( 'Star', 'xstore-core' ),
					'et_icon-time'            => esc_html__( 'Time', 'xstore-core' ),
					'et_icon-size'            => esc_html__( 'Size', 'xstore-core' ),
					'et_icon-more'            => esc_html__( 'More', 'xstore-core' ),
					'none'                    => esc_html__( 'None', 'xstore-core' ),
				),
			);
		}
		
		/**
		 * Section content html.
		 *
		 * @return void
		 * @version 1.0.0
		 * @since   3.2.2
		 *
		 */
		public function sales_booster_page() {
			
			$this->sales_booster_page_init_scripts();
			
			ob_start();
			
			$active_tab = get_transient( 'xstore_sales_booster_settings_active_tab' );
			if ( ! $active_tab ) {
				$active_tab = 'fake_sale_popup';
			}
			
			if ( isset( $_GET['etheme-sales-booster-tab'] ) ) {
				$active_tab = $_GET['etheme-sales-booster-tab'];
			}
			
			?>

            <h2 class="etheme-page-title etheme-page-title-type-2"><?php echo '🚀&nbsp;&nbsp;' . esc_html__( 'Sales Booster', 'xstore-core' ); ?></h2>
            <p class="et-message et-info">
				<?php echo '<strong>' . esc_html__( 'Welcome to the Sales Booster panel!', 'xstore-core' ) . '</strong> &#127881'; ?>
                <br/>
				<?php echo esc_html__( 'Do you want to boost conversions on your eCommerce store? XStore Sales Booster features help you to achieve goals and get closer to success.', 'xstore-core' ); ?>
            </p>
            <div class="et-tabs-filters-wrapper">
                <a class="et-tabs-filters-arrow" data-side="left">
                    <svg width="1em" height="1em" viewBox="0 0 10 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M0.156376 8.15451L9.02755 0.142539C9.24585 -0.0445767 9.58323 -0.0445767 9.82139 0.125529C9.94046 0.210582 10 0.346666 10 0.48275C10 0.601824 9.94046 0.720898 9.84123 0.822962L1.34714 8.47771L9.84123 16.1325C9.94046 16.2175 10 16.3536 10 16.4897C10 16.6258 9.94046 16.7448 9.84123 16.8469C9.742 16.949 9.58323 17 9.44431 17C9.32524 17 9.18631 16.966 9.06724 16.8639L9.04739 16.8469L0.156376 8.81792C-0.0619297 8.63081 -0.0420837 8.34163 0.156376 8.15451Z"
                              fill="currentColor"></path>
                    </svg>
                </a>
                <a class="et-tabs-filters-arrow" data-side="right">
                    <svg width="1em" height="1em" viewBox="0 0 10 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.84362 8.15451L0.972455 0.142539C0.754149 -0.0445767 0.416766 -0.0445767 0.178614 0.125529C0.059538 0.210582 0 0.346666 0 0.48275C0 0.601824 0.0595383 0.720898 0.158768 0.822962L8.65286 8.47771L0.158768 16.1325C0.0595383 16.2175 0 16.3536 0 16.4897C0 16.6258 0.0595383 16.7448 0.158768 16.8469C0.257998 16.949 0.416767 17 0.555689 17C0.674765 17 0.813687 16.966 0.932763 16.8639L0.952609 16.8469L9.84362 8.81792C10.0619 8.63081 10.0421 8.34163 9.84362 8.15451Z"
                              fill="currentColor"></path>
                    </svg>
                </a>
                <ul class="et-filters et-tabs-filters">
                    <li class="<?php echo 'fake_sale_popup' == $active_tab ? 'active' : ''; ?>"
                        data-tab="fake_sale_popup"><?php echo esc_html__( 'Fake Sale Popup', 'xstore-core' ); ?></li>
                    <li class="<?php echo 'progress_bar' == $active_tab ? 'active' : ''; ?>"
                        data-tab="progress_bar"><?php echo esc_html__( 'Progress Bar', 'xstore-core' ); ?></li>
                    <li class="<?php echo 'request_quote' == $active_tab ? 'active' : ''; ?>"
                        data-tab="request_quote"><?php echo esc_html__( 'Request Quote', 'xstore-core' ); ?></li>
                    <li class="<?php echo 'cart_checkout' == $active_tab ? 'active' : ''; ?>"
                        data-tab="cart_checkout"><?php echo esc_html__( 'Cart / Checkout Pages', 'xstore-core' ); ?></li>
                    <li class="<?php echo 'fake_live_viewing' == $active_tab ? 'active' : ''; ?>"
                        data-tab="fake_live_viewing"><?php echo esc_html__( 'Fake Live Viewing', 'xstore-core' ); ?></li>
                    <li class="<?php echo 'fake_product_sales' == $active_tab ? 'active' : ''; ?>"
                        data-tab="fake_product_sales"><?php echo esc_html__( 'Product Sold Counter', 'xstore-core' ); ?></li>
                    <li class="<?php echo 'estimated_delivery' == $active_tab ? 'active' : ''; ?>"
                        data-tab="estimated_delivery"><?php echo esc_html__( 'Estimated Delivery', 'xstore-core' ); ?></li>
                    <li class="<?php echo 'customer_reviews' == $active_tab ? 'active' : ''; ?>"
                        data-tab="customer_reviews"><?php echo esc_html__( 'Customer Reviews', 'xstore-core' ); ?></li>
                    <li class="<?php echo 'safe_checkout' == $active_tab ? 'active' : ''; ?>"
                        data-tab="safe_checkout"><?php echo esc_html__( 'Safe & Secure Checkout', 'xstore-core' ); ?></li>
                    <li class="<?php echo 'floating_menu' == $active_tab ? 'active' : ''; ?>"
                        data-tab="floating_menu"><?php echo esc_html__( 'Floating Menu', 'xstore-core' ); ?></li>
                </ul>
            </div>
			<?php
			$tab_content      = 'fake_sale_popup';
			$settings_enabled = get_option( $this->global_admin_class->settings_name . '_' . $tab_content, false );
			?>
            <div class="et-tabs-content<?php if ( $tab_content == $active_tab ) {
				echo ' active';
			} ?>" data-tab-content="<?php echo esc_attr( $tab_content ); ?>">
                <div class="tab-preview">
                    <img src="<?php echo $this->dir_url . '/images/' . $tab_content . '.jpg'; ?>" alt="">
                    <div>
                        <h4><?php echo esc_html__( 'Enable Fake Sale Popup', 'xstore-core' ) . ':'; ?></h4>
                        <label class="et-panel-option-switcher<?php if ( $settings_enabled ) { ?> switched<?php } ?>"
                               for="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>">
                            <input type="checkbox"
                                   id="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
                                   name="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
							       <?php if ( $settings_enabled ) { ?>checked<?php } ?>>
                            <span></span>
                        </label>
                    </div>
                </div>
                <br/>
                <br/>
				<?php if ( $settings_enabled ): ?>
                    <form class="xstore-panel-settings" method="post"
                          data-settings-name="<?php echo esc_attr( $this->global_admin_class->settings_name ); ?>"
                          data-save-tab="<?php echo esc_attr( $tab_content ); ?>">

                        <div class="xstore-panel-settings-inner">
							
							<?php $this->multicheckbox_field_type( $tab_content,
								'elements',
								esc_html__( 'Elements', 'xstore-core' ),
								esc_html__( 'Use this option to enable/disable popup elements.', 'xstore-core' ),
								array(
									'image'    => esc_html__( 'Image', 'xstore-core' ),
									'title'    => esc_html__( 'Title', 'xstore-core' ),
									'price'    => esc_html__( 'Price', 'xstore-core' ),
									'time'     => esc_html__( 'Time ago (hours, mins)', 'xstore-core' ),
									'location' => esc_html__( 'Location', 'xstore-core' ),
									'button'   => esc_html__( 'Button', 'xstore-core' ),
									'close'    => esc_html__( 'Close', 'xstore-core' ),
								),
								array(
									'image',
									'title',
									'time',
									'location',
									'button',
									'close',
								)
							); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
								'bag_icon',
								esc_html__( 'Bag emoji icon', 'xstore-core' ),
								esc_html__( 'Write emoji icon, 1 (to leave default one) or leave empty to remove it', 'xstore-core' ),
								false,
								1 ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
								'products_type',
								esc_html__( 'Show product source', 'xstore-core' ),
								false,
								array(
//                                        'recently_viewed' => esc_html__('Recently viewed', 'xstore-core'),
									'featured'     => esc_html__( 'Featured', 'xstore-core' ),
									'sale'         => esc_html__( 'On sale', 'xstore-core' ),
									'bestsellings' => esc_html__( 'Bestsellings', 'xstore-core' ),
									'orders'       => esc_html__( 'From real orders', 'xstore-core' ),
									'random'       => esc_html__( 'Random', 'xstore-core' ),
								),
								'random' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
								'hide_outofstock_products',
								esc_html__( 'Hide out of stock products', 'xstore-core' ),
								false,
								false ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
								'play_sound',
								esc_html__( 'Sound notification', 'xstore-core' ),
								esc_html__( 'Modern browsers recently changed their policy to let users able to disable auto play audio so this option is not working correctly now. ', 'xstore-core' ) .
								'<a href="https://developers.google.com/web/updates/2017/09/autoplay-policy-changes" target="_blank">' . esc_html__( 'More details', 'xstore-core' ) . '</a>' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_upload_field( $tab_content,
								'sound_file',
								esc_html__( 'Custom audio file', 'xstore-core' ),
								false,
								'audio' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
								'show_on_mobile',
								esc_html__( 'Show on mobile', 'xstore-core' ),
								false,
								true ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
								'locations',
								esc_html__( 'Locations description', 'xstore-core' ),
								'{{{Washington D.C., USA 🇺🇸}}}; {{{London, UK 🇬🇧}}}; {{{New Delhi, India 🇮🇳}}} <span class="mtips"><span class="dashicons dashicons-warning"></span><span class="mt-mes">' . esc_html__( 'Locations don\'t work if product source equals From real orders', 'xstore-core' ) . '</span></span>',
								'{{{Washington D.C., USA 🇺🇸}}}; {{{London, UK 🇬🇧}}}; {{{Madrid, Spain 🇪🇸}}}; {{{Berlin, Germany 🇩🇪}}}; {{{New Delhi, India 🇮🇳}}}; {{{Ottawa, Canada 🇨🇦}}}; {{{Paris, France 🇫🇷}}}; {{{Rome, Italy 🇮🇹}}}; {{{Dhaka, Bangladesh 🇧🇩}}}; {{{Kiev, Ukraine 🇺🇦}}}; {{{Islamabad, Pakistan 🇵🇰}}}; {{{Athens, Greece 🇬🇷}}}; {{{Brasilia, Brazil 🇧🇷}}}; {{{Lima, Peru 🇵🇪}}}; {{{Ankara, Turkey 🇹🇷}}}; {{{Colombo, Sri Lanka 🇱🇰}}}; {{{Warsaw, Poland 🇵🇱}}}; {{{Amsterdam, Netherlands 🇳🇱}}}; {{{Mexico City, Mexico 🇲🇽}}}; {{{Canberra, Australia 🇦🇺}}}' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_slider_field( $tab_content,
								'repeat_every',
								esc_html__( 'Repeat every x seconds', 'xstore-core' ),
								false,
								3,
								500,
								15,
								1,
								's' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
								'animation_type',
								esc_html__( 'Popup animation', 'xstore-core' ),
								false,
								array(
									'slide_right' => esc_html__( 'Slide right', 'xstore-core' ),
									'slide_up'    => esc_html__( 'Slide up', 'xstore-core' ),
								) ); ?>

                        </div>

                        <button class="et-button et-button-green no-loader"
                                type="submit"><?php echo esc_html__( 'Save changes', 'xstore-core' ); ?></button>
                    </form>
				<?php endif; ?>
            </div>
			<?php
			$tab_content      = 'progress_bar';
			$settings_enabled = get_option( $this->global_admin_class->settings_name . '_' . $tab_content, false ); ?>
            <div class="et-tabs-content<?php if ( $tab_content == $active_tab ) {
				echo ' active';
			} ?>" data-tab-content="<?php echo esc_attr( $tab_content ); ?>">
                <div class="tab-preview">
                    <img src="<?php echo $this->dir_url . '/images/' . $tab_content . '.jpg'; ?>" alt="">
                    <h4><?php echo esc_html__( 'Enable Progress Bar', 'xstore-core' ) . ':'; ?></h4>
                    <label class="et-panel-option-switcher<?php if ( $settings_enabled ) { ?> switched<?php } ?>"
                           for="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>">
                        <input type="checkbox"
                               id="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
                               name="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
						       <?php if ( $settings_enabled ) { ?>checked<?php } ?>>
                        <span></span>
                    </label>
                </div>
                <br/>
                <br/>
				<?php if ( $settings_enabled ): ?>
                    <form class="xstore-panel-settings" method="post"
                          data-settings-name="<?php echo esc_attr( $this->global_admin_class->settings_name ); ?>"
                          data-save-tab="<?php echo esc_attr( $tab_content ); ?>">
                        <div class="xstore-panel-settings-inner">
							
							<?php $this->global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
								'message_text',
								esc_html__( 'Progress message text', 'xstore-core' ),
								esc_html__( 'Write your text for progress bar using {{et_price}} to replace with scripts', 'xstore-core' ),
								get_theme_mod( 'booster_progress_content_et-desktop', esc_html__( 'Spend {{et_price}} to get free shipping', 'xstore-core' ) ) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_icons_select( $tab_content,
								'process_icon',
								esc_html__( 'Process icon', 'xstore-core' ),
								false,
								$this->icons['simple'],
								get_theme_mod( 'booster_progress_icon_et-desktop', 'et_icon-delivery' ) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
								'process_icon_position',
								esc_html__( 'Process icon position', 'xstore-core' ),
								false,
								array(
									'before' => esc_html__( 'Before', 'xstore-core' ),
									'after'  => esc_html__( 'After', 'xstore-core' ),
								),
								get_theme_mod( 'booster_progress_icon_position_et-desktop', 'before' ) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
								'price',
								esc_html__( 'Price {{Et_price}} For Count', 'xstore-core' ),
								esc_html__( 'Enter only numbers. Please, don\'t use any currency symbol.', 'xstore-core' ),
								false,
								get_theme_mod( 'booster_progress_price_et-desktop', '350' ) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
								'message_success_text',
								esc_html__( 'Success message text', 'xstore-core' ),
								false,
								get_theme_mod( 'booster_progress_content_success_et-desktop', esc_html__( 'Congratulations! You\'ve got free shipping.', 'xstore-core' ) ) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_icons_select( $tab_content,
								'success_icon',
								esc_html__( 'Success icon', 'xstore-core' ),
								false,
								$this->icons['simple'],
								get_theme_mod( 'booster_progress_success_icon_et-desktop', 'et_icon-star' ) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
								'success_icon_position',
								esc_html__( 'Success icon position', 'xstore-core' ),
								false,
								array(
									'before' => esc_html__( 'Before', 'xstore-core' ),
									'after'  => esc_html__( 'After', 'xstore-core' ),
								),
								get_theme_mod( 'booster_progress_success_icon_position_et-desktop', 'before' ) ); ?>
                        </div>
                        <button class="et-button et-button-green no-loader"
                                type="submit"><?php echo esc_html__( 'Save changes', 'xstore-core' ); ?></button>
                    </form>
				<?php endif; ?>
            </div>
			<?php
			$tab_content      = 'request_quote';
			$settings_enabled = get_option( $this->global_admin_class->settings_name . '_' . $tab_content, false ); ?>
            <div class="et-tabs-content<?php if ( $tab_content == $active_tab ) {
				echo ' active';
			} ?>" data-tab-content="<?php echo esc_attr( $tab_content ); ?>">
                <div class="tab-preview">
                    <img src="<?php echo $this->dir_url . '/images/' . $tab_content . '.jpg'; ?>" alt="">
                    <h4><?php echo esc_html__( 'Enable Request a quote on single product page', 'xstore-core' ) . ':'; ?></h4>
                    <label class="et-panel-option-switcher<?php if ( $settings_enabled ) { ?> switched<?php } ?>"
                           for="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>">
                        <input type="checkbox"
                               id="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
                               name="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
						       <?php if ( $settings_enabled ) { ?>checked<?php } ?>>
                        <span></span>
                    </label>
                </div>
                <br/>
                <br/>
				<?php if ( $settings_enabled ): ?>
                    <form class="xstore-panel-settings" method="post"
                          data-settings-name="<?php echo esc_attr( $this->global_admin_class->settings_name ); ?>"
                          data-save-tab="<?php echo esc_attr( $tab_content ); ?>">
                        <div class="xstore-panel-settings-inner">
							
							<?php $this->global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
								'show_all_pages',
								esc_html__( 'Show on all pages', 'xstore-core' ),
								false,
								false ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
								'show_as_button',
								esc_html__( 'Show as button on Single Product', 'xstore-core' ),
								false,
								false ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_upload_field( $tab_content,
								'icon',
								esc_html__( 'Custom Image/SVG', 'xstore-core' ),
								false ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
								'label',
								esc_html__( 'Custom label', 'xstore-core' ),
								false,
								false,
								esc_html__( 'Ask an expert', 'xstore-core' ) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
								'popup_content',
								esc_html__( 'Popup content', 'xstore-core' ),
								esc_html__( 'Enter static block shortcode or custom html', 'xstore-core' ),
								false ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
								'popup_dimensions_custom_width',
								esc_html__( 'Custom popup width', 'xstore-core' ),
								false,
								false,
								'' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
								'popup_dimensions_custom_height',
								esc_html__( 'Custom popup height', 'xstore-core' ),
								false,
								false,
								'' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
								'popup_background_color',
								esc_html__( 'Popup background color', 'xstore-core' ),
								esc_html__( 'Choose the background color of the request a quote popup.', 'xstore-core' ),
								'#fff' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_upload_field( $tab_content,
								'popup_background_image',
								esc_html__( 'Background image', 'xstore-core' ),
								esc_html__( 'Choose the background image of the request a quote popup.', 'xstore-core' ) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
								'popup_background_repeat',
								esc_html__( 'Background repeat', 'xstore-core' ),
								false,
								array(
									'no-repeat' => esc_html__( 'No repeat', 'xstore-core' ),
									'repeat'    => esc_html__( 'Repeat All', 'xstore-core' ),
									'repeat-x'  => esc_html__( 'Repeat-X', 'xstore-core' ),
									'repeat-y'  => esc_html__( 'Repeat-Y', 'xstore-core' ),
								),
								'no-repeat' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
								'popup_background_position',
								esc_html__( 'Background position', 'xstore-core' ),
								false,
								array(
									'left top'      => esc_html__( 'Left top', 'xstore-core' ),
									'left center'   => esc_html__( 'Left center', 'xstore-core' ),
									'left bottom'   => esc_html__( 'Left bottom', 'xstore-core' ),
									'right top'     => esc_html__( 'Right top', 'xstore-core' ),
									'right center'  => esc_html__( 'Right center', 'xstore-core' ),
									'right bottom'  => esc_html__( 'Right bottom', 'xstore-core' ),
									'center top'    => esc_html__( 'Center top', 'xstore-core' ),
									'center center' => esc_html__( 'Center center', 'xstore-core' ),
									'center bottom' => esc_html__( 'Center bottom', 'xstore-core' ),
								),
								'center center' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
								'popup_background_size',
								esc_html__( 'Background size', 'xstore-core' ),
								false,
								array(
									'cover'   => esc_html__( 'Cover', 'xstore-core' ),
									'contain' => esc_html__( 'Contain', 'xstore-core' ),
									'auto'    => esc_html__( 'Auto', 'xstore-core' ),
								),
								'cover' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
								'popup_color',
								esc_html__( 'Popup text color', 'xstore-core' ),
								'Choose the color of the request a quote popup.',
								'#000' ); ?>


                        </div>
                        <button class="et-button et-button-green no-loader"
                                type="submit"><?php echo esc_html__( 'Save changes', 'xstore-core' ); ?></button>
                    </form>
				<?php endif; ?>
            </div>
			<?php
			$tab_content      = 'cart_checkout';
			$postfix          = '_countdown';
			$settings_enabled = get_option( $this->global_admin_class->settings_name . '_' . $tab_content . $postfix, false );
			
			// uses to prevent showing double save button
			$next_postfix          = '_progress_bar';
			$next_settings_enabled = get_option( $this->global_admin_class->settings_name . '_' . $tab_content . $next_postfix, false );
			?>
            <div class="et-tabs-content<?php if ( $tab_content == $active_tab ) {
				echo ' active';
			} ?>" data-tab-content="<?php echo esc_attr( $tab_content ); ?>">
                <div class="tab-preview">
                    <img src="<?php echo $this->dir_url . '/images/' . $tab_content . $postfix . '.jpg'; ?>" alt="">
                    <div>
                        <h4><?php echo esc_html__( 'Enable Countdown on Cart page', 'xstore-core' ) . ':'; ?></h4>
                        <p><?php echo esc_html__( 'Show countdown timer as soon as any product has been added to the cart. This can help your store make those products sales quicker.', 'xstore-core' ); ?></p>
                        <label class="et-panel-option-switcher<?php if ( $settings_enabled ) { ?> switched<?php } ?>"
                               for="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content . $postfix; ?>">
                            <input type="checkbox"
                                   id="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content . $postfix; ?>"
                                   name="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content . $postfix; ?>"
							       <?php if ( $settings_enabled ) { ?>checked<?php } ?>>
                            <span></span>
                        </label>
                    </div>
                </div>
                <br/>
                <br/>
				<?php // loop, minutes duration, countdown message, countdown expired message
				if ( $settings_enabled ): ?>
                    <form class="xstore-panel-settings" method="post"
                          data-settings-name="<?php echo esc_attr( $this->global_admin_class->settings_name ); ?>"
                          data-save-tab="<?php echo esc_attr( $tab_content ); ?>">

                        <div class="xstore-panel-settings-inner">
							
							<?php $this->global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
								'countdown_loop',
								esc_html__( 'Countdown loop', 'xstore-core' ),
								false,
								false ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
								'countdown_message',
								esc_html__( 'Countdown Message', 'xstore-core' ),
								esc_html__( 'Text that will be shown while timer is live. {fire} will be replaced by emoji, {timer} will be replaced by countdown timer', 'xstore-core' ),
								'{fire} Hurry up, these products are limited, checkout within {timer}' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
								'countdown_expired_message',
								esc_html__( 'Countdown Expired Message', 'xstore-core' ),
								esc_html__( 'Text that will be shown when timer ends', 'xstore-core' ),
								'You are out of time! Checkout now to avoid losing your order!' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_slider_field( $tab_content,
								'countdown_minutes',
								esc_html__( 'Minutes', 'xstore-core' ),
								false,
								1,
								59,
								5,
								1,
								'min' ); ?>

                        </div>
						
						<?php if ( ! $next_settings_enabled ) : ?>
                            <button class="et-button et-button-green no-loader"
                                    type="submit"><?php echo esc_html__( 'Save changes', 'xstore-core' ); ?></button>
						<?php endif; ?>
                    </form>
				<?php endif; ?>
				<?php
				
				$postfix          = '_progress_bar';
				$settings_enabled = get_option( $this->global_admin_class->settings_name . '_' . $tab_content . $postfix, false );
				?>
                <div class="tab-preview">
                    <br/>
                    <br/>
                    <img src="<?php echo $this->dir_url . '/images/' . $tab_content . $postfix . '.jpg'; ?>" alt="">
                    <div>
                        <h4><?php echo esc_html__( 'Enable Progress Bar on Cart page', 'xstore-core' ) . ':'; ?></h4>
                        <p><?php echo esc_html__( 'Show progress bar as soon as any product has been added to the cart. This can help your store make those products sales quicker.', 'xstore-core' ); ?></p>
                        <label class="et-panel-option-switcher<?php if ( $settings_enabled ) { ?> switched<?php } ?>"
                               for="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content . $postfix; ?>">
                            <input type="checkbox"
                                   id="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content . $postfix; ?>"
                                   name="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content . $postfix; ?>"
							       <?php if ( $settings_enabled ) { ?>checked<?php } ?>>
                            <span></span>
                        </label>
                    </div>
                </div>
                <br/>
                <br/>
				<?php if ( $settings_enabled ): ?>
                    <form class="xstore-panel-settings" method="post"
                          data-settings-name="<?php echo esc_attr( $this->global_admin_class->settings_name ); ?>"
                          data-save-tab="<?php echo esc_attr( $tab_content ); ?>">
                        <div class="xstore-panel-settings-inner">
							
							<?php $this->global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
								'progress_bar_message_text',
								esc_html__( 'Progress message text', 'xstore-core' ),
								esc_html__( 'Write your text for progress bar using {{et_price}} to replace with scripts', 'xstore-core' ),
								get_theme_mod( 'booster_progress_content_et-desktop', esc_html__( 'Spend {{et_price}} to get free shipping', 'xstore-core' ) ) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_icons_select( $tab_content,
								'progress_bar_process_icon',
								esc_html__( 'Process icon', 'xstore-core' ),
								false,
								$this->icons['simple'],
								get_theme_mod( 'booster_progress_icon_et-desktop', 'et_icon-delivery' ) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
								'progress_bar_process_icon_position',
								esc_html__( 'Process icon position', 'xstore-core' ),
								false,
								array(
									'before' => esc_html__( 'Before', 'xstore-core' ),
									'after'  => esc_html__( 'After', 'xstore-core' ),
								),
								get_theme_mod( 'booster_progress_icon_position_et-desktop', 'before' ) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
								'progress_bar_price',
								esc_html__( 'Price {{Et_price}} For Count', 'xstore-core' ),
								esc_html__( 'Enter only numbers. Please, don\'t use any currency symbol.', 'xstore-core' ),
								false,
								get_theme_mod( 'booster_progress_price_et-desktop', '350' ) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
								'progress_bar_message_success_text',
								esc_html__( 'Success message text', 'xstore-core' ),
								false,
								get_theme_mod( 'booster_progress_content_success_et-desktop', esc_html__( 'Congratulations! You\'ve got free shipping.', 'xstore-core' ) ) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_icons_select( $tab_content,
								'progress_bar_success_icon',
								esc_html__( 'Success icon', 'xstore-core' ),
								false,
								$this->icons['simple'],
								get_theme_mod( 'booster_progress_success_icon_et-desktop', 'et_icon-star' ) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
								'progress_bar_success_icon_position',
								esc_html__( 'Success icon position', 'xstore-core' ),
								false,
								array(
									'before' => esc_html__( 'Before', 'xstore-core' ),
									'after'  => esc_html__( 'After', 'xstore-core' ),
								),
								get_theme_mod( 'booster_progress_success_icon_position_et-desktop', 'before' ) ); ?>
                        </div>
                        <button class="et-button et-button-green no-loader"
                                type="submit"><?php echo esc_html__( 'Save changes', 'xstore-core' ); ?></button>
                    </form>
				<?php endif; ?>
            </div>
			<?php
			$tab_content      = 'fake_live_viewing';
			$settings_enabled = get_option( $this->global_admin_class->settings_name . '_' . $tab_content, false );
			?>
            <div class="et-tabs-content<?php if ( $tab_content == $active_tab ) {
				echo ' active';
			} ?>" data-tab-content="<?php echo esc_attr( $tab_content ); ?>">
                <div class="tab-preview">
                    <img src="<?php echo $this->dir_url . '/images/' . $tab_content . '.jpg'; ?>" alt="">
                    <div>
                        <h4><?php echo esc_html__( 'Enable Fake Live Viewing', 'xstore-core' ) . ':'; ?></h4>
                        <p><?php echo esc_html__( 'Show live viewing message on single products and quick view. This can help your store make that product to sell quicker.', 'xstore-core' ); ?></p>
                        <label class="et-panel-option-switcher<?php if ( $settings_enabled ) { ?> switched<?php } ?>"
                               for="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>">
                            <input type="checkbox"
                                   id="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
                                   name="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
							       <?php if ( $settings_enabled ) { ?>checked<?php } ?>>
                            <span></span>
                        </label>
                    </div>
                </div>
                <br/>
                <br/>
				<?php
				if ( $settings_enabled ): ?>
                    <form class="xstore-panel-settings" method="post"
                          data-settings-name="<?php echo esc_attr( $this->global_admin_class->settings_name ); ?>"
                          data-save-tab="<?php echo esc_attr( $tab_content ); ?>">

                        <div class="xstore-panel-settings-inner">
							
							<?php $this->global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
								'message',
								esc_html__( 'Message', 'xstore-core' ),
								sprintf( esc_html__( 'Text that will be shown: %s - {eye} will be replaced by icon; %s - {count} will be replaced by calculated count between Min and Max values set below; %s Default text: {eye} {count} people are viewing this product right now', 'xstore-core' ), '<br/>', '<br/>', '<br/>' ),
								'{eye} {count} people are viewing this product right now' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_slider_field( $tab_content,
								'min_count',
								esc_html__( 'Min Count', 'xstore-core' ),
								esc_html__( 'Set minimum count of fake users are viewing right now. In other words: From X user to y users.', 'xstore-core' ),
								1,
								30,
								8,
								1,
								'users' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_slider_field( $tab_content,
								'max_count',
								esc_html__( 'Max Count', 'xstore-core' ),
								esc_html__( 'Set maximum count of fake users are viewing right now. In other words: From x user to Y users.', 'xstore-core' ),
								1,
								100,
								49,
								1,
								'users' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_slider_field( $tab_content,
								'minutes',
								esc_html__( 'Minutes', 'xstore-core' ),
								esc_html__( 'Set minutes of recalc count of viewing people for products.', 'xstore-core' ),
								1,
								59,
								2,
								1,
								'min' ); ?>

                        </div>

                        <button class="et-button et-button-green no-loader"
                                type="submit"><?php echo esc_html__( 'Save changes', 'xstore-core' ); ?></button>
                    </form>
				<?php endif; ?>
            </div>
			<?php
			$tab_content      = 'fake_product_sales';
			$settings_enabled = get_option( $this->global_admin_class->settings_name . '_' . $tab_content, false );
			?>
            <div class="et-tabs-content<?php if ( $tab_content == $active_tab ) {
				echo ' active';
			} ?>" data-tab-content="<?php echo esc_attr( $tab_content ); ?>">
                <div class="tab-preview">
                    <img src="<?php echo $this->dir_url . '/images/' . $tab_content . '.jpg'; ?>" alt="">
                    <div>
                        <h4><?php echo esc_html__( 'Enable Product Sold Counter', 'xstore-core' ) . ':'; ?></h4>
                        <p><?php echo esc_html__( 'Show total sales message on single products, quick view and products archives. This can help your store make that product to sell quicker.', 'xstore-core' ); ?></p>
                        <label class="et-panel-option-switcher<?php if ( $settings_enabled ) { ?> switched<?php } ?>"
                               for="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>">
                            <input type="checkbox"
                                   id="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
                                   name="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
							       <?php if ( $settings_enabled ) { ?>checked<?php } ?>>
                            <span></span>
                        </label>
                    </div>
                </div>
                <br/>
                <br/>
				<?php
				if ( $settings_enabled ): ?>
                    <form class="xstore-panel-settings" method="post"
                          data-settings-name="<?php echo esc_attr( $this->global_admin_class->settings_name ); ?>"
                          data-save-tab="<?php echo esc_attr( $tab_content ); ?>">

                        <div class="xstore-panel-settings-inner">
							
							<?php $this->global_admin_class->xstore_panel_settings_textarea_field( $tab_content,
								'message',
								esc_html__( 'Message', 'xstore-core' ),
								sprintf( esc_html__( 'Text that will be shown: %s - {fire} will be replaced by emoji; %s - {bag} will be replaced by shopping bag emoji;  %s - {count} will be replaced by calculated count between Min and Max values set below; %s - {timeframe} will be replaced by the timeframe value you set. %s Default text: {fire} {count} items sold in last {timeframe}', 'xstore-core' ), '<br/>', '<br/>', '<br/>', '<br/>', '<br/>' ),
								'{fire} {count} items sold in last {timeframe}' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
								'sales_type',
								esc_html__( 'Show Sales Type', 'xstore-core' ),
								false,
								array(
									'fake'   => esc_html__( 'Fake count', 'xstore-core' ),
									'orders' => esc_html__( 'Based on real orders', 'xstore-core' ),
								),
								'fake' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
								'show_on_shop',
								esc_html__( 'Show on Shop/Categories', 'xstore-core' ),
								false,
								false ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
								'hide_on_outofstock',
								esc_html__( 'Hide for Outofstock products', 'xstore-core' ),
								false,
								false ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_slider_field( $tab_content,
								'timeframe',
								esc_html__( 'Time Frame', 'xstore-core' ),
								esc_html__( 'Specify custom timeframe value.', 'xstore-core' ),
								1,
								59,
								3,
								1,
								'' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
								'timeframe_period',
								esc_html__( 'Time Period', 'xstore-core' ),
								esc_html__( 'Select custom time period', 'xstore-core' ),
								array(
									'minutes' => esc_html__( 'Minutes', 'xstore-core' ),
									'hours'   => esc_html__( 'Hours', 'xstore-core' ),
									'days'    => esc_html__( 'Days', 'xstore-core' ),
									'weeks'   => esc_html__( 'Weeks', 'xstore-core' ),
									'months'  => esc_html__( 'Months', 'xstore-core' ),
								),
								'hours' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_slider_field( $tab_content,
								'min_count',
								esc_html__( 'Min Count', 'xstore-core' ),
								esc_html__( 'Set minimum count of fake sales. In other words: From X sales to y sales.', 'xstore-core' ),
								1,
								30,
								3,
								1,
								'sales',
								array(
									array(
										'name'    => 'sales_type',
										'value'   => 'fake',
										'section' => 'fake_product_sales',
										'default' => 'fake'
									),
								) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_slider_field( $tab_content,
								'max_count',
								esc_html__( 'Max Count', 'xstore-core' ),
								esc_html__( 'Set maximum count of fake sales. In other words: From x sales to Y sales.', 'xstore-core' ),
								1,
								100,
								12,
								1,
								'sales',
								array(
									array(
										'name'    => 'sales_type',
										'value'   => 'fake',
										'section' => 'fake_product_sales',
										'default' => 'fake'
									),
								) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_slider_field( $tab_content,
								'shown_after',
								esc_html__( 'Min Sales Count', 'xstore-core' ),
								esc_html__( 'Set minimum count of sales. If sales count of product is less then product sales text will not be shown', 'xstore-core' ),
								0,
								30,
								0,
								1,
								'sales',
								array(
									array(
										'name'    => 'sales_type',
										'value'   => 'orders',
										'section' => 'fake_product_sales',
										'default' => 'fake'
									),
								) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_slider_field( $tab_content,
								'transient_hours',
								esc_html__( 'Cache Lifespan', 'xstore-core' ),
								esc_html__( 'Specify time after which the product sales cache is cleared.', 'xstore-core' ),
								1,
								72,
								24,
								1,
								'hours' ); ?>

                        </div>

                        <button class="et-button et-button-green no-loader"
                                type="submit"><?php echo esc_html__( 'Save changes', 'xstore-core' ); ?></button>
                    </form>
				<?php endif; ?>
            </div>
			<?php
			$tab_content      = 'estimated_delivery';
			$settings_enabled = get_option( $this->global_admin_class->settings_name . '_' . $tab_content, false );
			?>
            <div class="et-tabs-content<?php if ( $tab_content == $active_tab ) {
				echo ' active';
			} ?>" data-tab-content="<?php echo esc_attr( $tab_content ); ?>">
                <div class="tab-preview">
                    <img src="<?php echo $this->dir_url . '/images/' . $tab_content . '.jpg'; ?>" alt="">
                    <div>
                        <h4><?php echo esc_html__( 'Enable Estimated Delivery', 'xstore-core' ) . ':'; ?></h4>
                        <p><?php echo esc_html__( 'Show estimated delivery on your single products.', 'xstore-core' ); ?></p>
                        <label class="et-panel-option-switcher<?php if ( $settings_enabled ) { ?> switched<?php } ?>"
                               for="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>">
                            <input type="checkbox"
                                   id="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
                                   name="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
							       <?php if ( $settings_enabled ) { ?>checked<?php } ?>>
                            <span></span>
                        </label>
                    </div>
                </div>
                <br/>
                <br/>
				<?php
				if ( $settings_enabled ): ?>
                    <form class="xstore-panel-settings" method="post"
                          data-settings-name="<?php echo esc_attr( $this->global_admin_class->settings_name ); ?>"
                          data-save-tab="<?php echo esc_attr( $tab_content ); ?>">

                        <div class="xstore-panel-settings-inner">
							
							<?php $this->global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
								'text_before',
								esc_html__( 'Text before', 'xstore-core' ),
								esc_html__( 'Write title for estimated delivery output', 'xstore-core' ),
								false,
								esc_html__( 'Estimated delivery:', 'xstore-core' ) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
								'date_type',
								esc_html__( 'Date type', 'xstore-core' ),
								false,
								array(
									'range' => esc_html__( 'Days Range', 'xstore-core' ),
									'days'  => esc_html__( 'Days', 'xstore-core' ),
								),
								'days' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_slider_field( $tab_content,
								'min_days',
								esc_html__( 'Min days', 'xstore-core' ),
								esc_html__( 'Set minimum count of days. In other words: From X days to y days.', 'xstore-core' ),
								1,
								100,
								3,
								1,
								'days',
								array(
									array(
										'name'    => 'date_type',
										'value'   => 'range',
										'section' => $tab_content,
										'default' => 'days'
									),
								) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_slider_field( $tab_content,
								'max_days',
								esc_html__( 'Max days', 'xstore-core' ),
								esc_html__( 'Set max count of days. In other words: From x days to Y days.', 'xstore-core' ),
								1,
								100,
								5,
								1,
								'days',
								array(
									array(
										'name'    => 'date_type',
										'value'   => 'range',
										'section' => $tab_content,
										'default' => 'days'
									),
								) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_slider_field( $tab_content,
								'days',
								esc_html__( 'Days', 'xstore-core' ),
								esc_html__( 'Set count of days.', 'xstore-core' ),
								1,
								100,
								3,
								1,
								'days',
								array(
									array(
										'name'    => 'date_type',
										'value'   => 'days',
										'section' => $tab_content,
										'default' => 'days'
									),
								) ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
								'days_type',
								esc_html__( 'Days type', 'xstore-core' ),
								false,
								array(
									'number' => esc_html__( 'Number of days', 'xstore-core' ),
									'date'   => esc_html__( 'Exact date', 'xstore-core' ),
								),
								'number' ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
								'date_format',
								esc_html__( 'Date format', 'xstore-core' ),
								__( 'More examples of formats types <a href="https://www.php.net/manual/en/datetime.format.php" target="_blank">on this page</a>. Default format is inherited from your  <a href="' . admin_url( 'options-general.php' ) . '" target="_blank">WordPress settings</a>', 'xstore-core' ),
								sprintf(
								/* translators: %s: Allowed data letters (see: http://php.net/manual/en/function.date.php). */
									__( 'Use the letters: %s', 'xstore-core' ),
									'l D d j S F m M n Y y'
								),
								get_option( 'date_format' ),
								array(
									array(
										'name'    => 'days_type',
										'value'   => 'date',
										'section' => $tab_content,
										'default' => 'date'
									),
								) ); ?>
							
							<?php $this->multicheckbox_field_type( $tab_content,
								'non_working_days',
								esc_html__( 'Not working days', 'xstore-core' ),
								esc_html__( 'Exclude certain non-working days from date estimation count.', 'xstore-core' ),
								array(
									'day_off_monday'    => esc_html__( 'Monday', 'xstore-core' ),
									'day_off_tuesday'   => esc_html__( 'Tuesday', 'xstore-core' ),
									'day_off_wednesday' => esc_html__( 'Wednesday', 'xstore-core' ),
									'day_off_thursday'  => esc_html__( 'Thursday', 'xstore-core' ),
									'day_off_friday'    => esc_html__( 'Friday', 'xstore-core' ),
									'day_off_saturday'  => esc_html__( 'Saturday', 'xstore-core' ),
									'day_off_sunday'    => esc_html__( 'Sunday', 'xstore-core' ),
								),
								array(
									'day_off_saturday',
									'day_off_sunday',
								)
							); ?>
							
							<?php
							$estimated_delivery_only_for          = function_exists( 'wc_get_product_stock_status_options' ) ? wc_get_product_stock_status_options() : array(
								'instock'     => esc_html__( 'In Stock', 'xstore-core' ),
								'outofstock'  => esc_html__( 'Out of stock', 'xstore-core' ),
								'onbackorder' => esc_html__( 'Available on backorder', 'xstore-core' ),
							);
							$estimated_delivery_only_for_rendered = array();
							foreach ( $estimated_delivery_only_for as $key => $value ) {
								$estimated_delivery_only_for_rendered[ 'only_for_' . $key ] = $value;
							}
							$this->multicheckbox_field_type( $tab_content,
								'only_for',
								esc_html__( 'Show only for', 'xstore-core' ),
								esc_html__( 'Select product statuses if you need to show only for specific ones or deselect all to show on all products.', 'xstore-core' ),
								$estimated_delivery_only_for_rendered,
								array()
							); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
								'locale',
								esc_html__( 'Use Locale', 'xstore-core' ),
								false ); ?>
							
							<?php $this->global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
								'locale_format',
								esc_html__( 'Locale format', 'xstore-core' ),
								__( 'More examples of formats types <a href="https://www.php.net/manual/en/function.strftime.php" target="_blank">on this page</a>.', 'xstore-core' ),
								esc_html__( 'eg: %A, %b %d', 'xstore-core' ),
								'%A, %b %d',
								array(
									array(
										'name'    => 'locale',
										'value'   => 'on',
										'section' => $tab_content,
										'default' => false
									),
								) ); ?>
							
							<?php
							$single_product_builder = ! ! get_option( 'etheme_single_product_builder', false );
							$this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
								'position',
								esc_html__( 'Position', 'xstore-core' ),
								__( 'Select position of estimated delivery or choose Use shortcode choice and put <code>[etheme_sales_booster_estimated_delivery]</code> shortcode anywhere you want.', 'xstore-core' ),
								$single_product_builder ?
									array(
										'before_atc'               => esc_html__( 'Before Add to cart button', 'xstore-core' ),
										'after_atc'                => esc_html__( 'After Add to cart button', 'xstore-core' ),
										'before_cart_form'         => esc_html__( 'Before Cart form', 'xstore-core' ),
										'after_cart_form'          => esc_html__( 'After Cart form', 'xstore-core' ),
										'before_excerpt'           => esc_html__( 'Before product excerpt', 'xstore-core' ),
										'after_excerpt'            => esc_html__( 'After product excerpt', 'xstore-core' ),
										'before_product_meta'      => esc_html__( 'Before product meta', 'xstore-core' ),
										'after_product_meta'       => esc_html__( 'After product meta', 'xstore-core' ),
										'before_woocommerce_share' => esc_html__( 'Before share', 'xstore-core' ),
										'after_woocommerce_share'  => esc_html__( 'After share', 'xstore-core' ),
										'shortcode'                => esc_html__( 'Use shortcode', 'xstore-core' )
									) : array(
									'before_cart_form'         => esc_html__( 'Before Cart form', 'xstore-core' ),
									'after_cart_form'          => esc_html__( 'After Cart form', 'xstore-core' ),
									'before_product_meta'      => esc_html__( 'Before product meta', 'xstore-core' ),
									'after_product_meta'       => esc_html__( 'After product meta', 'xstore-core' ),
									'before_woocommerce_share' => esc_html__( 'Before share', 'xstore-core' ),
									'after_woocommerce_share'  => esc_html__( 'After share', 'xstore-core' ),
									'shortcode'                => esc_html__( 'Use shortcode', 'xstore-core' )
								),
								$single_product_builder ? 'after_excerpt' : 'after_product_meta' ); ?>

                        </div>

                        <button class="et-button et-button-green no-loader"
                                type="submit"><?php echo esc_html__( 'Save changes', 'xstore-core' ); ?></button>
                    </form>
				<?php endif; ?>
            </div>
			<?php
			$tab_content      = 'customer_reviews';
			$postfix          = '_images';
			$settings_enabled = get_option( $this->global_admin_class->settings_name . '_' . $tab_content . $postfix, false );
			?>
            <div class="et-tabs-content<?php if ( $tab_content == $active_tab ) {
				echo ' active';
			} ?>" data-tab-content="<?php echo esc_attr( $tab_content ); ?>">
                <div class="tab-preview">
                    <img src="<?php echo $this->dir_url . '/images/' . $tab_content . $postfix . '.jpg'; ?>" alt="">
                    <div>
                        <h4><?php echo esc_html__( 'Enable Review Images', 'xstore-core' ) . ':'; ?></h4>
                        <p><?php echo esc_html__( 'Turn on to allow customers to upload images in their review.', 'xstore-core' ); ?></p>
                        <label class="et-panel-option-switcher<?php if ( $settings_enabled ) { ?> switched<?php } ?>"
                               for="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content . $postfix; ?>">
                            <input type="checkbox"
                                   id="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content . $postfix; ?>"
                                   name="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content . $postfix; ?>"
							       <?php if ( $settings_enabled ) { ?>checked<?php } ?>>
                            <span></span>
                        </label>
                    </div>
                </div>
                <br/>
                <br/>
				<?php
				if ( $settings_enabled ): ?>
                    <form class="xstore-panel-settings" method="post"
                          data-settings-name="<?php echo esc_attr( $this->global_admin_class->settings_name ); ?>"
                          data-save-tab="<?php echo esc_attr( $tab_content ); ?>">

                        <div class="xstore-panel-settings-inner">
							
							<?php
							
							$this->global_admin_class->xstore_panel_settings_slider_field(
								$tab_content,
								'image_size',
								esc_html__( 'Max image size (MB)', 'xstore-core' ),
								false,
								1,
								5,
								1,
								1,
								'MB'
							);
							
							$this->global_admin_class->xstore_panel_settings_slider_field(
								$tab_content,
								'images_count',
								esc_html__( 'Max images count', 'xstore-core' ),
								false,
								1,
								10,
								3,
								1
							);
							
							$this->global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
								'images_required',
								esc_html__( 'Required Images', 'xstore-core' ),
								esc_html__( 'Turn on if it is required to upload images on new review', 'xstore-core' ),
								false );
							
							//							$this->global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
							//								'images_lightbox',
							//								esc_html__( 'Images Lightbox', 'xstore-core' ),
							//								esc_html__( 'Turn on to open lightbox on image click in comments', 'xstore-core' ),
							//								true );
							
							$this->global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
								'images_preview',
								esc_html__( 'Images Preview', 'xstore-core' ),
								esc_html__( 'Turn on to show customer preview images before submitting form', 'xstore-core' ),
								true );
							?>

                        </div>

                        <button class="et-button et-button-green no-loader"
                                type="submit"><?php echo esc_html__( 'Save changes', 'xstore-core' ); ?></button>
                    </form>
				<?php endif; ?>
            </div>
            <?php
            $tab_content      = 'safe_checkout';
            $settings_enabled = get_option( $this->global_admin_class->settings_name . '_' . $tab_content, false );
            ?>
            <div class="et-tabs-content<?php if ( $tab_content == $active_tab ) {
                echo ' active';
            } ?>" data-tab-content="<?php echo esc_attr( $tab_content ); ?>">
                <div class="tab-preview">
                    <img src="<?php echo $this->dir_url . '/images/' . $tab_content . '.jpg'; ?>" alt="">
                    <div>
                        <h4><?php echo esc_html__( 'Enable Safe & Secure checkout', 'xstore-core' ) . ':'; ?></h4>
                        <p><?php echo esc_html__( 'Show secure checkout information on Single product, Cart, Checkout pages.', 'xstore-core' ); ?></p>
                        <label class="et-panel-option-switcher<?php if ( $settings_enabled ) { ?> switched<?php } ?>"
                               for="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>">
                            <input type="checkbox"
                                   id="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
                                   name="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
                                   <?php if ( $settings_enabled ) { ?>checked<?php } ?>>
                            <span></span>
                        </label>
                    </div>
                </div>
                <br/>
                <br/>
                <?php
                if ( $settings_enabled ): ?>
                    <form class="xstore-panel-settings" method="post"
                          data-settings-name="<?php echo esc_attr( $this->global_admin_class->settings_name ); ?>"
                          data-save-tab="<?php echo esc_attr( $tab_content ); ?>">

                        <div class="xstore-panel-settings-inner">

                            <?php $this->global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
                                'text_before',
                                esc_html__( 'Text before', 'xstore-core' ),
                                esc_html__( 'Write your title. The word inside curly brackets {{word}} will be highlighted', 'xstore-core' ),
                                false,
                                esc_html__( 'Guaranteed {{safe}} checkout', 'xstore-core' ) );

                            $this->global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
                                'text_before_highlight_color',
                                esc_html__( 'Highlight text color', 'xstore-core' ),
                                esc_html__( 'Choose the color of highlighted text.', 'xstore-core' ),
                                '#2e7d32' );

                            $safe_payments_methods = array(
                                'visa'  => esc_html__( 'Visa', 'xstore-core' ),
                                'master-card'  => esc_html__( 'Master Card', 'xstore-core' ),
                                'paypal' => esc_html__( 'PayPal', 'xstore-core' ),
                                'american-express' => esc_html__( 'American Express', 'xstore-core' ),
                                'maestro' => esc_html__( 'Maestro', 'xstore-core' ),
                                'bitcoin' => esc_html__( 'Bitcoin', 'xstore-core' ),
                            );

                            $default_payment_items = array();
                            $default_payment_methods = $safe_payments_methods;

                            foreach ($default_payment_methods as $safe_payments_method_key => $safe_payments_method_name) {
                                $default_payment_items['items_'.array_search($safe_payments_method_key,array_keys($default_payment_methods))] =
                                    array(
                                    'callbacks' => array(
                                        array(
                                            'callback' => array(
                                                $this->global_admin_class,
                                                'xstore_panel_settings_select_field'
                                            ),
                                            'args'     => array(
                                                $tab_content,
                                                'payment_method',
                                                esc_html__( 'Payment method', 'xstore-core' ),
                                                false,
                                                array_merge($safe_payments_methods, array(
                                                    'custom' => esc_html__( 'Custom', 'xstore-core' ),
                                                )),
                                                $safe_payments_method_key
                                            )
                                        ),
                                        array(
                                            'callback' => array(
                                                $this->global_admin_class,
                                                'xstore_panel_settings_upload_field'
                                            ),
                                            'args'     => array(
                                                $tab_content,
                                                'custom_image',
                                                esc_html__( 'Custom Image', 'xstore-core' ),
                                                esc_html__( 'Recommended sizes are 90x60', 'xstore-core' ),
//                                                    'image/svg+xml',
                                            )
                                        ),
                                        array(
                                            'callback' => array(
                                                $this->global_admin_class,
                                                'xstore_panel_settings_input_text_field'
                                            ),
                                            'args'     => array(
                                                $tab_content,
                                                'tooltip',
                                                esc_html__( 'Tooltip', 'xstore-core' ),
                                                false,
                                                false,
                                                sprintf(esc_html__('Pay safely with %s', 'xstore-core'), $safe_payments_method_name)
                                            )
                                        ),
                                    )
                                );
                            }

                            $this->global_admin_class->xstore_panel_settings_repeater_field(
                                $tab_content,
                                'items',
                                esc_html__( 'Items', 'xstore-core' ),
                                false,
                                $default_payment_items,
                                array(
                                    array(
                                        'callback' => array(
                                            $this->global_admin_class,
                                            'xstore_panel_settings_select_field'
                                        ),
                                        'args'     => array(
                                            $tab_content,
                                            'payment_method',
                                            esc_html__( 'Payment method', 'xstore-core' ),
                                            false,
                                            array_merge($safe_payments_methods, array(
                                                'custom' => esc_html__( 'Custom', 'xstore-core' ),
                                            )),
                                        )
                                    ),
                                    array(
                                        'callback' => array(
                                            $this->global_admin_class,
                                            'xstore_panel_settings_upload_field'
                                        ),
                                        'args'     => array(
                                            $tab_content,
                                            'custom_image',
                                            esc_html__( 'Custom Image', 'xstore-core' ),
                                            false,
//                                            'image/svg+xml',
                                        )
                                    ),
                                    array(
                                        'callback' => array(
                                            $this->global_admin_class,
                                            'xstore_panel_settings_input_text_field'
                                        ),
                                        'args'     => array(
                                            $tab_content,
                                            'tooltip',
                                            esc_html__( 'Tooltip', 'xstore-core' ),
                                            false,
                                            false,
                                            esc_html__( 'Tooltip text', 'xstore-core' )
                                        )
                                    ),
                                )
                            );

                            $this->global_admin_class->xstore_panel_settings_input_text_field( $tab_content,
                                'text_after',
                                esc_html__( 'Text after', 'xstore-core' ),
                                esc_html__( 'Write your title. The word inside curly brackets {{word}} will be highlighted', 'xstore-core' ),
                                false,
                                esc_html__( 'Your Payment is {{100% Secure}}', 'xstore-core' ) );

                            $this->global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
                                'text_after_highlight_color',
                                esc_html__( 'Highlight text color', 'xstore-core' ),
                                esc_html__( 'Choose the color of highlighted text.', 'xstore-core' ),
                                get_theme_mod( 'dark_styles', false ) ? '#fff' : '#222' );

                            $this->global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
                                'tooltips',
                                esc_html__('Add tooltips for payments', 'xstore-core'),
                                false,
                                false );

                            foreach (
                                    array('single_product' => esc_html__('Show on Single product', 'xstore-core'),
                                        'quick_view' => esc_html__('Show in Quick View', 'xstore-core'),
                                        'cart' => esc_html__('Show on Cart', 'xstore-core'),
                                        'checkout' => esc_html__('Show on Checkout', 'xstore-core')) as $safe_checkout_pages_key => $safe_checkout_pages_title) {
                                $this->global_admin_class->xstore_panel_settings_switcher_field( $tab_content,
                                    'shown_on_'.$safe_checkout_pages_key,
                                    $safe_checkout_pages_title,
                                    false,
                                    !in_array($safe_checkout_pages_key, array('quick_view')) );
                            }

                            ?>

                            <p class="et-message et-info">
                                <?php echo __( 'Also you may use next shortcode and put <code>[etheme_sales_booster_safe_checkout]</code> shortcode anywhere you want. It will output all same content which you set up in the settings above.', 'xstore-core' ); ?>
                            </p>

                        </div>

                        <button class="et-button et-button-green no-loader"
                                type="submit"><?php echo esc_html__( 'Save changes', 'xstore-core' ); ?></button>
                    </form>
                <?php endif; ?>
            </div>
            <?php
			$tab_content      = 'floating_menu';
			$settings_enabled = get_option( $this->global_admin_class->settings_name . '_' . $tab_content, false );
			?>
            <div class="et-tabs-content<?php if ( $tab_content == $active_tab ) {
				echo ' active';
			} ?>" data-tab-content="<?php echo esc_attr( $tab_content ); ?>">
                <div class="tab-preview">
                    <img src="<?php echo $this->dir_url . '/images/' . $tab_content . '.jpg'; ?>" alt="">
                    <div>
                        <h4><?php echo esc_html__( 'Enable Floating Menu', 'xstore-core' ) . ':'; ?></h4>
                        <p><?php echo esc_html__( 'Show floating menu that could add extra links you want to make shown.', 'xstore-core' ); ?></p>
                        <label class="et-panel-option-switcher<?php if ( $settings_enabled ) { ?> switched<?php } ?>"
                               for="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>">
                            <input type="checkbox"
                                   id="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
                                   name="<?php echo esc_attr( $this->global_admin_class->settings_name ) . '_' . $tab_content; ?>"
							       <?php if ( $settings_enabled ) { ?>checked<?php } ?>>
                            <span></span>
                        </label>
                    </div>
                </div>
                <br/>
                <br/>
				<?php
				if ( $settings_enabled ): ?>
                    <form class="xstore-panel-settings" method="post"
                          data-settings-name="<?php echo esc_attr( $this->global_admin_class->settings_name ); ?>"
                          data-save-tab="<?php echo esc_attr( $tab_content ); ?>">

                        <div class="xstore-panel-settings-inner">
							
							<?php
							$this->global_admin_class->xstore_panel_settings_repeater_field(
								$tab_content,
								'items',
								esc_html__( 'Items', 'xstore-core' ),
								false,
								array(
									'items_1' => array(
										'callbacks' => array(
											array(
												'callback' => array(
													$this->global_admin_class,
													'xstore_panel_settings_upload_field'
												),
												'args'     => array(
													$tab_content,
													'svg_icon',
													esc_html__( 'SVG Icon', 'xstore-core' ),
													false,
													'image/svg+xml',
												)
											),
											array(
												'callback' => array(
													$this->global_admin_class,
													'xstore_panel_settings_input_text_field'
												),
												'args'     => array(
													$tab_content,
													'tooltip',
													esc_html__( 'Tooltip', 'xstore-core' ),
													false,
													false,
													esc_html__( 'Tooltip text', 'xstore-core' )
												)
											),
											array(
												'callback' => array(
													$this->global_admin_class,
													'xstore_panel_settings_input_text_field'
												),
												'args'     => array(
													$tab_content,
													'link',
													esc_html__( 'Link', 'xstore-core' ),
													false,
													false,
													'#'
												)
											),
											array(
												'callback' => array(
													$this->global_admin_class,
													'xstore_panel_settings_switcher_field'
												),
												'args'     => array(
													$tab_content,
													'target_blank',
													esc_html__( 'Open In New Window', 'xstore-core' ),
												)
											),
											array(
												'callback' => array(
													$this->global_admin_class,
													'xstore_panel_settings_switcher_field'
												),
												'args'     => array(
													$tab_content,
													'active_dot',
													esc_html__( 'Enable Dot', 'xstore-core' ),
													esc_html__( 'Enable pulsing dot for this item', 'xstore-core' )
												)
											),
											array(
												'callback' => array(
													$this->global_admin_class,
													'xstore_panel_settings_switcher_field'
												),
												'args'     => array(
													$tab_content,
													'is_active',
													esc_html__( 'Make Item Active', 'xstore-core' ),
												)
											),
											array(
												'callback' => array(
													$this->global_admin_class,
													'xstore_panel_settings_switcher_field'
												),
												'args'     => array(
													$tab_content,
													'mobile_hidden',
													esc_html__( 'Hide on Mobile', 'xstore-core' ),
												)
											),
										)
									),
								),
								array(
									array(
										'callback' => array(
											$this->global_admin_class,
											'xstore_panel_settings_upload_field'
										),
										'args'     => array(
											$tab_content,
											'svg_icon',
											esc_html__( 'SVG Icon', 'xstore-core' ),
											false,
											'image/svg+xml',
										)
									),
									array(
										'callback' => array(
											$this->global_admin_class,
											'xstore_panel_settings_input_text_field'
										),
										'args'     => array(
											$tab_content,
											'tooltip',
											esc_html__( 'Tooltip', 'xstore-core' ),
											false,
											false,
											esc_html__( 'Tooltip text', 'xstore-core' )
										)
									),
									array(
										'callback' => array(
											$this->global_admin_class,
											'xstore_panel_settings_input_text_field'
										),
										'args'     => array(
											$tab_content,
											'link',
											esc_html__( 'Link', 'xstore-core' )
										)
									),
									array(
										'callback' => array(
											$this->global_admin_class,
											'xstore_panel_settings_switcher_field'
										),
										'args'     => array(
											$tab_content,
											'target_blank',
											esc_html__( 'Open In New Window', 'xstore-core' ),
										)
									),
									array(
										'callback' => array(
											$this->global_admin_class,
											'xstore_panel_settings_switcher_field'
										),
										'args'     => array(
											$tab_content,
											'active_dot',
											esc_html__( 'Enable Dot', 'xstore-core' ),
											esc_html__( 'Enable pulsing dot for this item', 'xstore-core' )
										)
									),
									array(
										'callback' => array(
											$this->global_admin_class,
											'xstore_panel_settings_switcher_field'
										),
										'args'     => array(
											$tab_content,
											'is_active',
											esc_html__( 'Make Item Active', 'xstore-core' ),
										)
									),
									array(
										'callback' => array(
											$this->global_admin_class,
											'xstore_panel_settings_switcher_field'
										),
										'args'     => array(
											$tab_content,
											'mobile_hidden',
											esc_html__( 'Hide on Mobile', 'xstore-core' ),
										)
									),
								)
							);
							
							$this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
								'position',
								esc_html__( 'Position', 'xstore-core' ),
								esc_html__( 'Set default position of floating menu. Auto will make right position for ltr and left for rtl', 'xstore-core' ),
								array(
									'auto'  => esc_html__( 'Auto', 'xstore-core' ),
									'left'  => esc_html__( 'Left', 'xstore-core' ),
									'right' => esc_html__( 'Right', 'xstore-core' ),
								),
								'auto' );
							
							//                                $this->global_admin_class->xstore_panel_settings_select_field( $tab_content,
							//                                    'tooltip_color_scheme',
							//                                    esc_html__( 'Tooltip Color Scheme', 'xstore-core' ),
							//	                                esc_html__('Set colorscheme for items tooltips. Set auto to inherit from theme styles.', 'xstore-core'),
							//                                    array(
							//	                                    'auto' => esc_html__( 'Auto', 'xstore-core' ),
							//                                        'dark'   => esc_html__( 'Dark', 'xstore-core' ),
							//                                        'light' => esc_html__( 'Light', 'xstore-core' ),
							//                                    ),
							//                                    'dark' );
							
							
							$this->global_admin_class->xstore_panel_settings_tab_field_start(
								esc_html__( 'Global Style Settings', 'xstore-core' )
							);
							
							$this->global_admin_class->xstore_panel_settings_slider_field(
								$tab_content,
								'content_zoom',
								esc_html__( 'Content zoom (%)', 'xstore-core' ),
								false,
								50,
								300,
								100,
								1,
								'%'
							);
							
							$this->global_admin_class->xstore_panel_settings_slider_field(
								$tab_content,
								'items_gap',
								esc_html__( 'Items Gap (px)', 'xstore-core' ),
								false,
								0,
								50,
								7,
								1,
								'px'
							);
							
							$this->global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
								'background_color',
								esc_html__( 'Background Color', 'xstore-core' ),
								esc_html__( 'Choose the background color of the floating menu.', 'xstore-core' ),
								'#444' );
							
							$this->global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
								'box_shadow_color',
								esc_html__( 'Box Shadow Color', 'xstore-core' ),
								esc_html__( 'Choose the box-shadow color of the floating menu. It will be auto calculated with opacity.', 'xstore-core' ),
								'#fff' );
							
							$this->global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
								'item_color',
								esc_html__( 'Item Color', 'xstore-core' ),
								esc_html__( 'Choose the color of icons.', 'xstore-core' ),
								'#fff' );
							
							$this->global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
								'item_color_hover',
								esc_html__( 'Item Color (hover)', 'xstore-core' ),
								esc_html__( 'Choose the color of icons on hover.', 'xstore-core' ),
								get_theme_mod( 'activecol', '#a4004f' ) );
							
							$this->global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
								'dot_color',
								esc_html__( 'Dot Color', 'xstore-core' ),
								esc_html__( 'Choose the color of pulsing dots.', 'xstore-core' ),
								'#10a45d' );
							
							$this->global_admin_class->xstore_panel_settings_tab_field_end();
							
							$this->global_admin_class->xstore_panel_settings_tab_field_start(
								esc_html__( 'Active Colors', 'xstore-core' )
							);
							
							$this->global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
								'active_item_color',
								esc_html__( 'Active Item Color', 'xstore-core' ),
								esc_html__( 'Choose the color of active item.', 'xstore-core' ),
								'#fff' );
							
							$this->global_admin_class->xstore_panel_settings_colorpicker_field( $tab_content,
								'active_item_background_color',
								esc_html__( 'Active Item Background Color', 'xstore-core' ),
								esc_html__( 'Choose the background color of the active item.', 'xstore-core' ),
								'#10a45d' );
							
							$this->global_admin_class->xstore_panel_settings_tab_field_end();
							?>

                        </div>

                        <button class="et-button et-button-green no-loader"
                                type="submit"><?php echo esc_html__( 'Save changes', 'xstore-core' ); ?></button>
                    </form>
				<?php endif; ?>
            </div>
			<?php
			echo ob_get_clean();
		}
		
		/**
		 * Multicheckbox field type.
		 *
		 * @param string $section
		 * @param string $setting
		 * @param string $setting_title
		 * @param string $setting_descr
		 * @param array  $elements
		 * @param array  $default_elements
		 *
		 * @return void
		 *
		 * @version 1.0.0
		 * @since   3.2.2
		 *
		 */
		public function multicheckbox_field_type( $section = '', $setting = '', $setting_title = '', $setting_descr = '', $elements = array(), $default_elements = array(), $active_callbacks = array() ) {
			
			$settings = $this->global_admin_class->xstore_panel_section_settings;
			
			$class   = '';
			$to_hide = false;
			$attr    = array();
			if ( count( $active_callbacks ) ) {
				
				$this->enqueue_settings_scripts( 'callbacks' );
				
				$attr['data-callbacks'] = array();
				foreach ( $active_callbacks as $key ) {
					if ( isset( $settings[ $key['section'] ] ) ) {
						if ( isset( $settings[ $key['section'] ][ $key['name'] ] ) && $settings[ $key['section'] ][ $key['name'] ] == $key['value'] ) {
						} else {
							$to_hide = true;
						}
					} elseif ( $key['value'] != $key['default'] ) {
						$to_hide = true;
					}
					$attr['data-callbacks'][] = $key['name'] . ':' . $key['value'];
				}
				$attr[] = 'data-callbacks="' . implode( ',', $attr['data-callbacks'] ) . '"';
				unset( $attr['data-callbacks'] );
			}
			
			if ( $to_hide ) {
				$class .= ' hidden';
			}
			
			ob_start(); ?>

            <div class="xstore-panel-option xstore-panel-option-multicheckbox<?php echo esc_attr( $class ); ?>" <?php echo implode( ' ', $attr ); ?>>
                <div class="xstore-panel-option-title">

                    <h4><?php echo esc_html( $setting_title ); ?>:</h4>
					
					<?php if ( $setting_descr ) : ?>
                        <p class="description"><?php echo esc_html( $setting_descr ); ?></p>
					<?php endif; ?>

                </div>

                <div class="xstore-panel-option-input">
					<?php foreach ( $elements as $key => $val ) {
						$key_origin = $key;
						$key        = $section . '_' . $key; ?>
                        <label for="<?php echo esc_attr( $key ); ?>">
                            <input id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>"
                                   type="checkbox"
								<?php echo ( ( ! isset( $settings[ $section ] ) && in_array( $key_origin, $default_elements ) )
								             || ( isset( $settings[ $section ][ $key ] ) && $settings[ $section ][ $key ] ) ) ? 'checked' : ''; ?>>
							<?php echo esc_attr( $val ); ?>
                        </label>
					<?php } ?>
                </div>
            </div>
			
			<?php echo ob_get_clean();
		}
		
		protected function enqueue_settings_scripts( $script ) {
			wp_enqueue_script( 'etheme_panel_' . $script, ETHEME_BASE_URI . 'framework/panel/js/settings/' . $script . '.js', array(
				'jquery',
				'etheme_admin_js'
			), false, true );
			wp_localize_script( 'xstore_panel_settings_' . $script, 'XStorePanelSettings' . ucfirst( $script ) . 'Config', $this->global_admin_class->init_vars() );
		}
	}
	
	$Etheme_Sales_Booster_Backend = new Etheme_Sales_Booster_Backend();
}
