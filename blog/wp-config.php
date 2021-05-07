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
define( 'DB_NAME', 'tema4798_wp187' );

/** MySQL database username */
define( 'DB_USER', 'tema4798_wp187' );

/** MySQL database password */
define( 'DB_PASSWORD', '@9pESe2@36' );

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
define( 'AUTH_KEY',         '726lmhbt7zozdrmqramot7ah0qncphwdlchjlxikh0vkwbf1kqlnhqn9ojecyrxa' );
define( 'SECURE_AUTH_KEY',  'esy3s3l7c9trsjkpapaev4w9qym1xqish389ysmy4hsqxnnbacfaxbslyrl5d8o7' );
define( 'LOGGED_IN_KEY',    'mr8pcgliq6yndgebd7mgw7fvwsb7hysoszk41y7dfzoutintvqkf3gdncvwytwsx' );
define( 'NONCE_KEY',        'yabcl0o5eu22rdyk00mtufcwqtbqlgj2wuebfmuabqrnp2jlzjskbljeafgp75pj' );
define( 'AUTH_SALT',        'f4j96dexyyb2dk6f7xtbodpwjszlcrdp7gsr1g1vvzfdwpcqqvqiuicppr92kt88' );
define( 'SECURE_AUTH_SALT', 'wbzaycumm6lrascgwxmhcp1hotlsrr65uonfpc6mt2ksyxrfejelvc80z7b9wjur' );
define( 'LOGGED_IN_SALT',   'spp7i3xdbglmdgd7t1evgbyje2mdrc1m0r6omm9ozktlmffrwwsivtibbgidqfwn' );
define( 'NONCE_SALT',       'jbv1wx2kunfvx2b8xfz3fchew0vaqnby3yhfrkcztp7yaf69zlymqbtgt6xijr6f' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp4n_';

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
