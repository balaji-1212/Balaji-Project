<?php

/**
 * Plugin Name:       Social Chat
 * Description:       Social Chat allows your visitors to contact you or your team through Social Chat with a single click.
 * Plugin URI:        https://quadlayers.com/portfolio/whatsapp-chat/
 * Version:           6.2.7
 * Author:            QuadLayers
 * Author URI:        https://quadlayers.com
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       wp-whatsapp-chat
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

define( 'QLWAPP_PLUGIN_NAME', 'Social Chat' );
define( 'QLWAPP_PLUGIN_VERSION', '6.2.7' );
define( 'QLWAPP_PLUGIN_FILE', __FILE__ );
define( 'QLWAPP_PLUGIN_DIR', __DIR__ . DIRECTORY_SEPARATOR );
define( 'QLWAPP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'QLWAPP_PREFIX', 'qlwapp' );
define( 'QLWAPP_DOMAIN', QLWAPP_PREFIX );
define( 'QLWAPP_WORDPRESS_URL', 'https://wordpress.org/plugins/wp-whatsapp-chat/' );
define( 'QLWAPP_REVIEW_URL', 'https://wordpress.org/support/plugin/wp-whatsapp-chat/reviews/?filter=5#new-post' );
define( 'QLWAPP_DEMO_URL', 'https://quadlayers.com/portfolio/whatsapp-chat/?utm_source=qlwapp_admin' );
define( 'QLWAPP_PURCHASE_URL', QLWAPP_DEMO_URL );
define( 'QLWAPP_LANDING_URL', 'https://quadlayers.com/whatsapp-chat-landing/?utm_source=qlwapp_admin' );
define( 'QLWAPP_SUPPORT_URL', 'https://quadlayers.com/account/support/?utm_source=qlwapp_admin' );
define( 'QLWAPP_DOCUMENTATION_URL', 'https://quadlayers.com/documentation/whatsapp-chat/?utm_source=qlwapp_admin' );
define( 'QLWAPP_GROUP_URL', 'https://www.facebook.com/groups/quadlayers' );
define( 'QLWAPP_PHONE_NUMBER', '12019713894' );

define( 'QLWAPP_PREMIUM_SELL_SLUG', 'wp-whatsapp-chat-pro' );
define( 'QLWAPP_PREMIUM_SELL_NAME', 'Social Chat PRO' );
define( 'QLWAPP_PREMIUM_SELL_URL', 'https://quadlayers.com/portfolio/whatsapp-chat/?utm_source=qlwapp_admin' );

define( 'QLWAPP_CROSS_INSTALL_SLUG', 'insta-gallery' );
define( 'QLWAPP_CROSS_INSTALL_NAME', 'Instagram Gallery' );
define( 'QLWAPP_CROSS_INSTALL_DESCRIPTION', esc_html__( 'Instagram Gallery is the most user-friendly Instagram plugin for WordPress . It was built to simplify the integration, to reduce time to have sites updated and to be on track with social media that shows best growing indicators.', 'wp-whatsapp-chat' ) );
define( 'QLWAPP_CROSS_INSTALL_URL', 'https://quadlayers.com/portfolio/instagram-feed/?utm_source=qlwapp_admin' );

if ( ! class_exists( 'QLWAPP' ) ) {
	include_once QLWAPP_PLUGIN_DIR . 'includes/qlwapp.php';
}

require_once QLWAPP_PLUGIN_DIR . 'compatibility/compatibility.php';
require_once QLWAPP_PLUGIN_DIR . 'includes/quadlayers/widget.php';
require_once QLWAPP_PLUGIN_DIR . 'includes/quadlayers/notices.php';
require_once QLWAPP_PLUGIN_DIR . 'includes/quadlayers/links.php';
