<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'nithya' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'lkqgjukcjtq4qkwqp0oywkwf00gnarlfwqsvjmaumhqljxqqwgksx45gyxjnjkmk' );
define( 'SECURE_AUTH_KEY',  'rt5d59lj1v0qxapjlcav4cxkdgkulpbshbcmivbhzhs9erl2bzifxx10lupywcbx' );
define( 'LOGGED_IN_KEY',    'pgvlq7y1xzhu7j0tgjhmzoykfqlguqhf5vcd09rr7sbtikjbjvou1f4orrupqefx' );
define( 'NONCE_KEY',        'mhjca88k7nxsoomdpwcahrdo5zgmmspajxbbzer8itp83rqbxrklkkgivkay4ki9' );
define( 'AUTH_SALT',        'f5cdrvhrlyw0w4fxzya1cgy9v8bpd38fkf3t0g1e9eu8jrnhtu0snxrhe7cwhe13' );
define( 'SECURE_AUTH_SALT', 'fbyjpm7qjzzy3vajfhbi2tnd8legyy0lrwtl3vdr608rw6vimwypdwofcqsvuetp' );
define( 'LOGGED_IN_SALT',   'nj3pulqjlcywhlogtcgtshukzvajwucu0aedhajlsbcltsdxqpiuinowogbsmige' );
define( 'NONCE_SALT',       'km2jyq5pekdfvnzrb5imisiyvx6jzxmwsgosj4mmlhiarybqevrkf6kjidge8flr' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpay_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG_DISPLAY', false);
define('ALLOW_UNFILTERED_UPLOADS', true);

define( 'AUTOSAVE_INTERVAL', 300 );
define( 'WP_POST_REVISIONS', 5 );
define( 'EMPTY_TRASH_DAYS', 7 );
define( 'WP_CRON_LOCK_TIMEOUT', 120 );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
