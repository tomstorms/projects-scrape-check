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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

if (getenv('LANDO') != 'ON') {

	// LIVE DATABASE
	define('DB_NAME', '');
	define('DB_USER', '');
	define('DB_PASSWORD', '');
	define('DB_HOST', '');

} else {
    
    // LANDO DATABASE
    define('DB_NAME', 'wordpress');
    define('DB_USER', 'wordpress');
    define('DB_PASSWORD', 'wordpress');
    define('DB_HOST', 'database');

}

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Y&+wN>In,wD]Q8O.|NdI+67|u15O4CW;YGLw2s.+CAqXN`@J1-EN|,sUO ,ROSO}');
define('SECURE_AUTH_KEY',  '$<17^+-hILSHUF+Q0)]:v~D9s@^f{$?BQXW(U`[1dwhH$q}HA9I^!%~jY;g29%}-');
define('LOGGED_IN_KEY',    'K,lVcA3|JMJq+DBTQ kOF:iKN`FzkKxaNE_JU`g$Q6F{pHmMjR{r@et6p]aa{lbb');
define('NONCE_KEY',        'h]Z)6j3rpHzYKPSjyVr?Mr]mjNIhT-(WQdiR/X]^CiH-GzCyN)Cbi,L3kF({%H4p');
define('AUTH_SALT',        'iaF{*(U9%*|i=2-:$/XV+$OE@N%EKc@K@YNz_eYdyY$rL)g|.+M/!+}[l<+fy58E');
define('SECURE_AUTH_SALT', '5VghY$;_1_WN== #dbW;>Cl-+BQ7UGD_U,@>^*n76yc%KSoCVq4lSYtT?1CvT%zh');
define('LOGGED_IN_SALT',   '`U7[ugapAi)]0AL!s*L!0+-h[noT`DMY38-_Xfu^86x<dXpfuYf:o|CPz/=TyY8$');
define('NONCE_SALT',       '&{tAcR3W-zAY~QrPW.eSCv+%_{(f&K-q~0x}d*k%s!0+-f14z*q(b(2xyyph)A6W');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
