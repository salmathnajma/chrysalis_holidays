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
define( 'DB_NAME', 'wordpresschrysalis_db' );

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
define( 'AUTH_KEY',         'H{GV;rPf-/5kYg;rLcR?JE2eLE81WjKPS{Tn,C R|wP6-)qpa?;)e]&Aun5YH/o)' );
define( 'SECURE_AUTH_KEY',  'z#dPTq]Krv]6$yQU0 wHR!nJs,eNau=6gH$81|uNljiTQxFT]MLsBEU2^fIe>F9a' );
define( 'LOGGED_IN_KEY',    '$uL0&E{&{_hByfEkXbrsLsoo[AR96cAbMmqEn?Q_<N9~$ rCkH4L_g>)Q%O0W*B)' );
define( 'NONCE_KEY',        's,0UZ9w2<btoK:CItAaYKKylF)3mJ|Kmx{(1C9ly7A],Fg!3.r.ymEiI,BWh*/>p' );
define( 'AUTH_SALT',        '*`Hy/t,- ^JBIS5aZAU(S?2<`h/o1m<Yr1$|`RFT9*{;H+kvY$H$9eI!Pk%?ShSl' );
define( 'SECURE_AUTH_SALT', ':FD@#cVaSsF7{@?gybH-8FO9cZS$8O;Z3VW4[j?/+u|)q&#7#0*j6rGpmwN+k$%%' );
define( 'LOGGED_IN_SALT',   'J)KdUU.gWm{1}q<X^jT9{]A/ B/+;c%]LI$2/.Ym9<vekSk$n~KmUfHogj7d.kLx' );
define( 'NONCE_SALT',       '5r{A91S2JNc!*[ZYg`8$1Fj7}VDhJIP/Q6)(1ru3+F&zXL>>m;2kmoHbR %[<a(F' );

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
