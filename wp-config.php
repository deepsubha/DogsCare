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
define( 'DB_NAME', 'lovealldogs' );

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
define( 'AUTH_KEY',         ' Y0Mc6HO{[l9^_NxOlv]Rn*&z}77-lB8;zx1#/ >Oo17W+w%9V1Q[,.@<-s0ZjqJ' );
define( 'SECURE_AUTH_KEY',  '$Z:op8XAfymJ^xTBDsMzE@4!wKHe(EE?M.Ut;rBW{~_GosB<Sbp=,*XvwVx,-|QQ' );
define( 'LOGGED_IN_KEY',    'S-R2c33~5r^DzPi3~~bT /ks1jCr{W8@r1-O{6xYN8,F.D]1sXgJoKl,;1-!aR3X' );
define( 'NONCE_KEY',        'l|)WHp0p>aK>Om /]gjm)6yewk$fxVLs_! pQM;9WW&M4P%p,0e$j5$3(3gq8%}J' );
define( 'AUTH_SALT',        '7[God*gUF+@SeaJH=B8^Jra[+)&qrnQ,mo jT8kQ&I03%M6{/lhA,[e3VW:v9[MQ' );
define( 'SECURE_AUTH_SALT', 'UBZsi9@MCq}%9G{.e_GtgkyT=I]Kf@<!A^Y2R/DRc/Q78hp&E%1MeM/7hntQxzT$' );
define( 'LOGGED_IN_SALT',   'MN9)+%wWbR7}^+|2K;X*d,xIU&@f FT>s5O;m9,rc!tQ`dVeZ/v&wn3v 9y2!pp}' );
define( 'NONCE_SALT',       '!U]9`nVL/yzi&}J6rE>.m+_*_5O/q.pQTkM)$,J^qphs0oa~7TZXL?/-rmiu$`]-' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
