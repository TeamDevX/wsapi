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
define('DB_NAME', 'wordpress453');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', '192.168.0.135');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'Pu!Wl? Rt?ypea^hTcKB:c]=@p555m_wCxTW9^ZJO}MRk)6.MxEO1>&2U:=g@4&n');
define('SECURE_AUTH_KEY',  'KH-n5e#GA.wt;VBXT$S}=EB2{<D/VI9kE(-alo`&d@bw_xo7%ZgoYmwbStL$^@Z&');
define('LOGGED_IN_KEY',    '^kiXgg>B28BTTNrl03SHNct<p|6I>7<MSgQf%I:}CM[v.0Ee[ah>|af<lN5C:1P7');
define('NONCE_KEY',        '6U)+2STU,WFvbgG`3@dN:7*<O%q&)FxW+Ac-.8Yf|v{D /4.J]5<R-JSUT4g*2bU');
define('AUTH_SALT',        '[8gF`lte7-M+~ E$_B~1-Q]m)ba`Npv(|)ul[~hz5@nfH|]@cI8+I`8]u&d<g=Ke');
define('SECURE_AUTH_SALT', '2jZkO{AZ[+]0<.c y9A-S..ump6jZvZ-5z9xa6C#bidZ>$R1/nFOrHW,%#h5D-nV');
define('LOGGED_IN_SALT',   '%DWL712/[M$W@CP*ne5?<@?^TjM~/ --z3@IKOa_s0nZ M[(^>kcqnA[-^:(Aw:l');
define('NONCE_SALT',       'Ol>wr*Zm;4#(q:Sg_xW1j)@5|QrG%PZdY@HV;En/`p5{nMta4]KQ8wZ&^Li`v.Jb');

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
