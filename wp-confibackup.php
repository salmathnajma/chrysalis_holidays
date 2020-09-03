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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'test' );

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
define( 'AUTH_KEY',         'frv<u,DJ48CvgzvM^&rjL}*C^%{84QdN8>P>%{M5v87%rx.YB~NLqeVk:2qZe:_o' );
define( 'SECURE_AUTH_KEY',  'TSdKn2ZFC60m)EU[hzQ}8(j~<,%_3+l9KfFR}?4[,z2`qiDhNYz;l_-OEizEkPPa' );
define( 'LOGGED_IN_KEY',    '>hT4vkjk[n#qe|xu&vdr=![}I5bvcZkvLf`B!/)dya3TJjV3o)eXbW$`PmCN~fS|' );
define( 'NONCE_KEY',        'FBsgTEJAk brX3ltu):a.tgt8g&7[>([,X`,!rj#xD<xa,]T8VS9EHkcC8S(yw|e' );
define( 'AUTH_SALT',        'M8m@<KV$Ip}i:9uV-??vBcnW(kE{M.=O?Ex(8[:aB`*p*I=1!h%|0RR_`2]77l>Q' );
define( 'SECURE_AUTH_SALT', 'K}>.,HqJ6#l9Ra;L{2{aNNx;yD!&>jsVrXsdz?61Q:8Ae]lGA|AM26gG2ZM)3I4]' );
define( 'LOGGED_IN_SALT',   'C<yH_>CQ.POxzx659mvtV%4Od6RTQx|eeu3o1&Ui8CY02BONgs>`fV`Xb7I_1I.D' );
define( 'NONCE_SALT',       'tw6i<P*negUk$}V26!)TOFENAGb]8fwBkYCGMqISS=-W`QBsmo<%#?$46dIWE*A}' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
