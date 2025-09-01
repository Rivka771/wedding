<?php
//Begin Really Simple Security key
define('RSSSL_KEY', 'C87IBnCt7yJyoDsUCjnbUZj46Y8OCZ0kVQqaaq9rIiiFOYCYEq6ByCJGqPJuwn5p');
//END Really Simple Security key
define( 'WP_CACHE', true ); // Added by AccelerateWP

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'vngs_sq5qd' );

/** Database username */
define( 'DB_USER', 'vngs_ug0t7' );

/** Database password */
define( 'DB_PASSWORD', '*O57!sZWky^nva6v' );

/** Database hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '3O~/[)pCA91u/]4+M329e#yuQ9Da7H76|ZSXj2wsU[y44ur!+&1(92BQ%3)M&(f-');
define('SECURE_AUTH_KEY', 'M:ogd@4h2t22k4|5(c17sGy697k7Y5%C0#13[)n196Aq&-/iEze22M6+X&_u8r01');
define('LOGGED_IN_KEY', 'q!*Zx-[*J+Y(G_6oCg22Z[3_o-_!wyyW/6Or)0l&7iOvM63~X:|4skBeu@wR1r9X');
define('NONCE_KEY', '+4|Yah-zwfB1b|CP21~wU]37q4m9c:094|sJBia|3fk@c:0*9F)Z7+7!e+0A2f1A');
define('AUTH_SALT', ':s5(YB3f#euM#760|R#2ioWIu3@29H033ND/D(ph35)2_@g)1(%!QwJpS|gc@W61');
define('SECURE_AUTH_SALT', '29yV3DiE62&42I(31*8*-q~L5K32O]CMi7--r1hLBC]83:jP;EJ9F#96+]gR0gHK');
define('LOGGED_IN_SALT', '1)wF_HNM42z9Iv2Uxrl|*7*0N&62u_(J5IaXF90i5[A&E9X:4h#H!YB&pY~k9X#4');
define('NONCE_SALT', '4LNj*sYP7[;6Z6#dZLIsRG1Uw2)8z-j|6]g]1_hn;~X85F%8qNq7zoq1X_Y#g15N');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'JTujPt_';


/* Add any custom values between this line and the "stop editing" line. */

define('WP_ALLOW_MULTISITE', false);
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
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', true );
ini_set('log_errors', 1);
error_log('Manual error log test: If you see this, logging is fixed!');
trigger_error("Test notice", E_USER_NOTICE);


define( 'WP_AUTO_UPDATE_CORE', false );
define( 'WP_MEMORY_LIMIT', '768M' );
define( 'DISALLOW_FILE_EDIT', true );
define( 'WP_POST_REVISIONS', 5 );
define( 'AUTOSAVE_INTERVAL', 120 );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
