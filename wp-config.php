<?php
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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress-test' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'EzY1?djzH$HR4tHm*gj|c&zq |7Mf_k4fbqi~A-G,b6BoxASL9~p9~~y?(:bG<wZ' );
define( 'SECURE_AUTH_KEY',  'ulz<0^%08D~qE:jzDF8oSEp9mt-g_ocuG3gf&a?%J< ?$I;v*Kvu5(T6jtHgz/+0' );
define( 'LOGGED_IN_KEY',    'SP#mS<uO$<(C>vZP[C@fI_AxT2f[+$<O.?N2rP;4 +yvEORzQ+Xfy#SPPU9NTX,H' );
define( 'NONCE_KEY',        '{*XyI^W?&_g4cIT=>4BGFczkSb7U$4j=V@6E,d2IZ9/swC+^yFI2J1p_0:RQAj/[' );
define( 'AUTH_SALT',        'cx(xA(OulBh[<jh;<wGJXD87M3O?z^f;S~w/f}4dHM1oP$_M_CQ1NoB@JA*cuqIh' );
define( 'SECURE_AUTH_SALT', 'hRPe/aK9C|+K~iOpV]X]Hs0-<w c1F)K=aOIHGUBdJTU98cgc,[65x._2CYzqL/y' );
define( 'LOGGED_IN_SALT',   ';0/8v$Trl$zS@^w:G4S~Z8;ecU(Yp6VpV44<ZCo^T[),AM[]q-5blM},qIFPM}]e' );
define( 'NONCE_SALT',       'X*2-UQ`33De8Q1&+#E3,Bu5dDh^as8e3*%po2|3]=VO_O1CBbgN8<N:As`D*u?U-' );

/**#@-*/

/**
 * WordPress database table prefix.
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
define('FS_METHOD', 'direct'); 

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
